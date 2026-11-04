<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\RestaurantTableBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RestaurantTableBookingController extends Controller
{

    public function tables(Request $request)
    {
        $query = RestaurantTable::query();

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        $tables = $query->with([
            'bookings' => function ($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'cancelled'])
                    ->latest();
            }
        ])
            ->orderBy('restaurant_id')
            ->orderBy('table_number')
            ->get();

        return response()->json([
            'success' => 1,
            'message' => 'Restaurant tables retrieved successfully',
            'data' => $tables->map(function ($table) {
                return [
                    'id' => $table->id,
                    'restaurant_id' => $table->restaurant_id,
                    'table_name' => $table->table_name,
                    'table_number' => $table->table_number,
                    'seating_capacity' => $table->seating_capacity,
                    'status' => $table->status,

                    'bookings' => $table->bookings->map(function ($booking) {
                        return [
                            'booking_id' => $booking->id,
                            'booking_number' => $booking->booking_number,
                            'customer_name' => $booking->customer_name,
                            'booking_date' => $booking->booking_date,
                            'booking_time' => $booking->booking_time,
                            'end_time' => $booking->end_time,
                            'guest_count' => $booking->guest_count,
                            'booking_status' => $booking->status,
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * GET: Get available tables for a restaurant and time slot.
     */
    public function availableTables(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'booking_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:booking_time',
            'guest_count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Default booking duration: 2 hours
        $startTime = $data['booking_time'];

        $endTime = $data['end_time'] ?? date(
            'H:i:s',
            strtotime($startTime) + 7200
        );

        // Prevent booking a past time today
        if (
            $data['booking_date'] === now()->toDateString()
            && $startTime <= now()->format('H:i')
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a future booking time',
                'data' => [],
            ], 422);
        }

        // Get booked tables during the selected time slot
        $bookedTableIds = RestaurantTableBooking::where(
            'restaurant_id',
            $data['restaurant_id']
        )
            ->where('booking_date', $data['booking_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->pluck('restaurant_table_id')
            ->unique()
            ->values()
            ->all();

        // Get all active restaurant tables
        $tables = RestaurantTable::where(
            'restaurant_id',
            $data['restaurant_id']
        )
            ->where('status', 1)
            ->orderBy('seating_capacity')
            ->orderBy('table_number')
            ->get([
                'id',
                'restaurant_id',
                'table_name',
                'table_number',
                'seating_capacity',
                'status',
            ]);

        // Add availability status to every table
        $tables = $tables->map(function ($table) use ($bookedTableIds, $data) {

            if ($table->seating_capacity < $data['guest_count']) {
                $table->availability = 'not_available';
                $table->availability_reason = 'Insufficient seating capacity';
            } elseif (in_array($table->id, $bookedTableIds)) {
                $table->availability = 'not_available';
                $table->availability_reason = 'Already booked for this time slot';
            } else {
                $table->availability = 'available';
                $table->availability_reason = null;
            }

            return $table;
        });
        $totalTables = RestaurantTable::where('restaurant_id', $data['restaurant_id'])->count();

        $totalBookedTables = RestaurantTableBooking::where('restaurant_id', $data['restaurant_id'])
            ->where('booking_date', $data['booking_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->distinct('restaurant_table_id')
            ->count('restaurant_table_id');

        $restaurant = Restaurant::select('id', 'name')
            ->find($data['restaurant_id']);

        $availableTables = $tables
            ->where('availability', 'available')
            ->values();

        $notAvailableTables = $tables
            ->where('availability', 'not_available')
            ->values();

        return response()->json([
            'success' => 1,
            'message' => 'Restaurant tables retrieved successfully',

            'booking_details' => [
                'restaurant_id' => (int) $data['restaurant_id'],
                'booking_date' => $data['booking_date'],
                'booking_time' => $startTime,
                'end_time' => $endTime,
                'guest_count' => (int) $data['guest_count'],
            ],

            'summary' => [
                'total_booked_tables' => $totalBookedTables,
                'total_tables' => $tables->count(),
                'available_tables' => $availableTables->count(),
                'not_available_tables' => $notAvailableTables->count(),
            ],

            'available_tables' => $availableTables,
            'not_available_tables' => $notAvailableTables,

            'data' => $tables->values(),
        ]);
    }


    // POST: Create restaurant table booking
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'restaurant_table_id' => 'required|integer|exists:restaurant_tables,id',
            'customer_name' => 'required|string|max:150',
            'customer_mobile' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:150',
            'booking_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:booking_time',
            'guest_count' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $data = $validator->validated();

        // Always get customer ID from authenticated user
        $data['customer_id'] = $user->id;

        if (empty($data['end_time'])) {
            $data['end_time'] = date(
                'H:i:s',
                strtotime($data['booking_time']) + 7200
            );
        }

        if (
            $data['booking_date'] === now()->toDateString()
            && $data['booking_time'] <= now()->format('H:i')
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a future booking time',
            ], 422);
        }

        $result = DB::transaction(function () use ($data) {

            $table = RestaurantTable::where('id', $data['restaurant_table_id'])
                ->lockForUpdate()
                ->first();

            if (
                !$table ||
                $table->restaurant_id != $data['restaurant_id'] ||
                $table->status != 1
            ) {
                return [
                    'error' => 'Invalid or inactive restaurant table',
                    'code' => 422,
                ];
            }

            if ($data['guest_count'] > $table->seating_capacity) {
                return [
                    'error' => 'Guest count exceeds table seating capacity',
                    'code' => 422,
                ];
            }

            $exists = RestaurantTableBooking::where(
                'restaurant_table_id',
                $table->id
            )
                ->where('booking_date', $data['booking_date'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('booking_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['booking_time'])
                ->exists();

            if ($exists) {
                return [
                    'error' => 'This table is already booked for the selected time',
                    'code' => 409,
                ];
            }

            $data['booking_number'] = 'RTB-'
                . now()->format('Ymd')
                . '-'
                . Str::upper(Str::random(8));

            $data['status'] = 'pending';

            $booking = RestaurantTableBooking::create($data);

            $restaurant = Restaurant::select('id', 'name')
                ->find($data['restaurant_id']);

            $booking->setAttribute('restaurant', [
                'id' => $restaurant?->id,
                'name' => $restaurant?->name,
            ]);

            return ['booking' => $booking];
        });

        if (isset($result['error'])) {
            return response()->json([
                'success' => 0,
                'message' => $result['error'],
            ], $result['code']);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Restaurant table booked successfully',
            'data' => $result['booking'],
        ], 201);
    }

    // GET: Booking list
    public function index(Request $request)
    {
        $query = RestaurantTableBooking::with([
            'restaurant:id,name,logo',
            'restaurantTable:id,restaurant_id,table_name,table_number,seating_capacity'
        ]);

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('booking_date')) {
            $query->where('booking_date', $request->booking_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $restaurantIds = (clone $query)
            ->distinct()
            ->pluck('restaurant_id');

        $totalBookedTables = RestaurantTableBooking::whereIn(
            'restaurant_id',
            $restaurantIds
        )
            ->whereIn('status', ['pending', 'confirmed'])
            ->distinct('restaurant_table_id')
            ->count('restaurant_table_id');

        $bookings = $query->latest()->paginate(15);

        return response()->json([
            'success' => 1,
            'message' => 'Restaurant table bookings retrieved successfully',

            'summary' => [
                'total_booked_tables' => $totalBookedTables,
                'total_bookings' => (clone $query)->count(),
            ],

            'data' => $bookings,
        ]);
    }

    // GET: Booking details by ID
    public function show($id)
    {
        $booking = RestaurantTableBooking::with([
            'restaurant:id,name',
            'restaurantTable'
        ])->find($id);

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Booking not found',
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Booking details retrieved successfully',
            'data' => $booking,
        ]);
    }

    // POST: Cancel restaurant table booking
    // POST: Cancel restaurant table booking
    public function cancel($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $booking = RestaurantTableBooking::with([
            'restaurant:id,name',
            'restaurantTable:id,restaurant_id,table_name,table_number,seating_capacity'
        ])
            ->where('customer_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Booking not found for this customer',
                'customer_id' => $user->id,
                'booking_id' => $id,
            ], 404);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => 0,
                'message' => 'This booking cannot be cancelled',
                'current_status' => $booking->status,
            ], 422);
        }

        $booking->status = 'cancelled';
        $booking->save();

        $booking->refresh();

        $booking->load([
            'restaurant:id,name',
            'restaurantTable:id,restaurant_id,table_name,table_number,seating_capacity'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Restaurant table booking cancelled successfully',
            'data' => $booking,
        ]);
    }
}
