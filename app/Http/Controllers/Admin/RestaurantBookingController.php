<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\RestaurantTableBooking;
use App\Models\RestaurantBookingSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class RestaurantBookingController extends Controller
{
    /**
     * Display all restaurants with their booking counts.
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::query()
            ->select('restaurants.*')
            ->selectSub(function ($query) {
                $query->from('restaurant_table_bookings')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('restaurant_id', 'restaurants.id');
            }, 'bookings_count')
            ->selectSub(function ($query) {
                $query->from('restaurant_table_bookings')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('restaurant_id', 'restaurants.id')
                    ->where('status', 'pending');
            }, 'pending_bookings_count')
            ->selectSub(function ($query) {
                $query->from('restaurant_table_bookings')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('restaurant_id', 'restaurants.id')
                    ->where('status', 'confirmed');
            }, 'confirmed_bookings_count')
            ->selectSub(function ($query) {
                $query->from('restaurant_table_bookings')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('restaurant_id', 'restaurants.id')
                    ->where('status', 'cancelled');
            }, 'cancelled_bookings_count')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('restaurants.name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('restaurants.name')
            ->paginate(12)
            ->withQueryString();

        $totalRestaurants = Restaurant::count();
        $totalBookings = RestaurantTableBooking::count();

        $restaurantsWithBookings = RestaurantTableBooking::query()
            ->distinct()
            ->count('restaurant_id');

        return view(
            'admin.catalog.restaurant-bookings.index',
            compact(
                'restaurants',
                'totalRestaurants',
                'totalBookings',
                'restaurantsWithBookings'
            )
        );
    }

    /**
     * Display bookings for one selected restaurant.
     */
    public function restaurantBookings(Request $request, Restaurant $restaurant)
    {
        $query = RestaurantTableBooking::with([
            'restaurant',
            'restaurantTable',
        ])
            ->where('restaurant_id', $restaurant->id)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $bookings = $query->paginate(12)->withQueryString();

        $restaurantBookings = RestaurantTableBooking::where(
            'restaurant_id',
            $restaurant->id
        );

        $totalBookings = (clone $restaurantBookings)->count();
        $pendingBookings = (clone $restaurantBookings)
            ->where('status', 'pending')
            ->count();
        $confirmedBookings = (clone $restaurantBookings)
            ->where('status', 'confirmed')
            ->count();
        $cancelledBookings = (clone $restaurantBookings)
            ->where('status', 'cancelled')
            ->count();

        return view(
            'admin.catalog.restaurant-bookings.restaurant-index',
            compact(
                'restaurant',
                'bookings',
                'totalBookings',
                'pendingBookings',
                'confirmedBookings',
                'cancelledBookings'
            )
        );
    }

    /**
     * Show the create booking page.
     */
    public function create(Request $request)
    {
        $restaurants = Restaurant::orderBy('name')->get();

        $selectedRestaurantId = Restaurant::whereKey(
            $request->query('restaurant_id')
        )->value('id');

        return view(
            'admin.catalog.restaurant-bookings.create',
            compact('restaurants', 'selectedRestaurantId')
        );
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $this->normalizeTimeInputs($request);

        $validated = $request->validate(
            $this->bookingValidationRules($request)
        );

        $this->validateBookingAvailability($validated);

        do {
            $bookingNumber = 'BK' . now()->format('YmdHis') . random_int(100, 999);
        } while (
            RestaurantTableBooking::where('booking_number', $bookingNumber)->exists()
        );

        $validated['booking_number'] = $bookingNumber;

        $validated['confirmed_at'] = $validated['status'] === 'confirmed'
            ? now()
            : null;

        $validated['cancelled_at'] = $validated['status'] === 'cancelled'
            ? now()
            : null;

        RestaurantTableBooking::create($validated);

        return redirect()
            ->route('admin.restaurant-bookings.restaurant', $validated['restaurant_id'])
            ->with('success', 'Restaurant booking created successfully.');
    }

    /**
     * Display booking details.
     */
    public function show(RestaurantTableBooking $restaurant_booking)
    {
        $restaurant_booking->load([
            'restaurant',
            'restaurantTable',
        ]);

        return view(
            'admin.catalog.restaurant-bookings.show',
            compact('restaurant_booking')
        );
    }

    /**
     * Show the edit booking page.
     */
    public function edit(RestaurantTableBooking $restaurant_booking)
    {
        $restaurants = Restaurant::orderBy('name')->get();

        $tables = RestaurantTable::where(
            'restaurant_id',
            $restaurant_booking->restaurant_id
        )
            ->where('status', 1)
            ->orderBy('table_number')
            ->get();

        return view(
            'admin.catalog.restaurant-bookings.edit',
            compact(
                'restaurant_booking',
                'restaurants',
                'tables'
            )
        );
    }

    /**
     * Update booking information and status.
     */
    public function update(
        Request $request,
        RestaurantTableBooking $restaurant_booking
    ) {
        $this->normalizeTimeInputs($request);

        $validated = $request->validate(
            $this->bookingValidationRules($request)
        );

        $this->validateBookingAvailability(
            $validated,
            $restaurant_booking->id
        );

        if (
            $validated['status'] === 'confirmed' &&
            $restaurant_booking->status !== 'confirmed'
        ) {
            $validated['confirmed_at'] = now();
        }

        if ($validated['status'] !== 'confirmed') {
            $validated['confirmed_at'] = null;
        }

        if (
            $validated['status'] === 'cancelled' &&
            $restaurant_booking->status !== 'cancelled'
        ) {
            $validated['cancelled_at'] = now();
        }

        if ($validated['status'] !== 'cancelled') {
            $validated['cancelled_at'] = null;
        }

        $restaurant_booking->update($validated);

        return redirect()
            ->route(
                'admin.restaurant-bookings.restaurant',
                $restaurant_booking->restaurant_id
            )
            ->with('success', 'Restaurant booking updated successfully.');
    }

    /**
     * Delete a booking.
     */
    public function destroy(RestaurantTableBooking $restaurant_booking)
    {
        $restaurantId = $restaurant_booking->restaurant_id;

        $restaurant_booking->delete();

        return redirect()
            ->route('admin.restaurant-bookings.restaurant', $restaurantId)
            ->with('success', 'Restaurant booking deleted successfully.');
    }

    /**
     * Get active tables for the selected restaurant.
     */
    public function getRestaurantTables($restaurantId)
    {
        $tables = RestaurantTable::where('restaurant_id', $restaurantId)
            ->where('status', 1)
            ->orderBy('table_number')
            ->get([
                'id',
                'table_name',
                'table_number',
                'seating_capacity',
            ]);

        return response()->json($tables);
    }

    /**
     * Normalize time inputs.
     * HTML time inputs normally submit H:i. This also handles H:i:s.
     */
    private function normalizeTimeInputs(Request $request)
    {
        foreach (['booking_time', 'end_time'] as $field) {
            $value = $request->input($field);

            if (
                is_string($value) &&
                preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)
            ) {
                $request->merge([
                    $field => substr($value, 0, 5),
                ]);
            }
        }
    }

    /**
     * Shared validation rules for creating and updating bookings.
     */
    private function bookingValidationRules(Request $request)
    {
        return [
            'restaurant_id' => [
                'required',
                'integer',
                'exists:restaurants,id',
            ],

            'restaurant_table_id' => [
                'required',
                'integer',
                Rule::exists('restaurant_tables', 'id')
                    ->where('restaurant_id', $request->input('restaurant_id')),
            ],

            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_mobile' => [
                'required',
                'string',
                'max:30',
            ],

            'customer_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'booking_date' => [
                'required',
                'date',
            ],

            'booking_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:booking_time',
            ],

            'guest_count' => [
                'required',
                'integer',
                'min:1',
            ],

            'seating_type' => [
                'nullable',
                Rule::in(['indoor', 'outdoor', 'family', 'private']),
            ],

            'occasion' => [
                'nullable',
                'string',
                'max:100',
            ],

            'special_requests' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'admin_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'cancelled',
                    'completed',
                    'no_show',
                ]),
            ],
        ];
    }

    /**
     * Validate table capacity and booking time availability.
     */
    private function validateBookingAvailability(
        array $data,
        $ignoreBookingId = null
    ) {
        $settings = RestaurantBookingSetting::where(
            'restaurant_id',
            $data['restaurant_id']
        )->first();

        $minGuests = $settings->min_guests ?? 1;
        $maxGuests = $settings->max_guests ?? 20;
        $maxAdvanceDays = $settings->max_advance_days ?? 30;

        $activeBooking = in_array(
            $data['status'],
            ['pending', 'confirmed'],
            true
        );

        if (
            $activeBooking &&
            $settings &&
            !$settings->is_booking_enabled
        ) {
            throw ValidationException::withMessages([
                'status' => 'Table reservations are currently disabled for this restaurant.',
            ]);
        }

        if ($activeBooking) {
            if ((int) $data['guest_count'] < (int) $minGuests) {
                throw ValidationException::withMessages([
                    'guest_count' => "Minimum guests allowed is {$minGuests}.",
                ]);
            }

            if ((int) $data['guest_count'] > (int) $maxGuests) {
                throw ValidationException::withMessages([
                    'guest_count' => "Maximum guests allowed is {$maxGuests}.",
                ]);
            }

            $bookingDate = Carbon::parse($data['booking_date'])->startOfDay();
            $today = now()->startOfDay();
            $lastBookingDate = $today->copy()->addDays($maxAdvanceDays);

            if ($bookingDate->lessThan($today)) {
                throw ValidationException::withMessages([
                    'booking_date' => 'Past booking dates are not allowed.',
                ]);
            }

            if ($bookingDate->greaterThan($lastBookingDate)) {
                throw ValidationException::withMessages([
                    'booking_date' => "Bookings can only be made {$maxAdvanceDays} days in advance.",
                ]);
            }

            if (
                $bookingDate->isSameDay($today) &&
                $data['booking_time'] <= now()->format('H:i')
            ) {
                throw ValidationException::withMessages([
                    'booking_time' => 'The booking time must be later than the current time.',
                ]);
            }
        }

        $table = RestaurantTable::where(
            'id',
            $data['restaurant_table_id']
        )
            ->where('restaurant_id', $data['restaurant_id'])
            ->where('status', 1)
            ->first();

        if (!$table) {
            throw ValidationException::withMessages([
                'restaurant_table_id' => 'Please select a valid active table.',
            ]);
        }

        if ((int) $data['guest_count'] > (int) $table->seating_capacity) {
            throw ValidationException::withMessages([
                'guest_count' => 'This table can accommodate only '
                    . $table->seating_capacity . ' guests.',
            ]);
        }

        $requestedStart = Carbon::createFromFormat(
            'H:i',
            $data['booking_time']
        );

        $requestedEnd = Carbon::createFromFormat(
            'H:i',
            $data['end_time']
        );

        if ($requestedEnd->lessThanOrEqualTo($requestedStart)) {
            throw ValidationException::withMessages([
                'end_time' => 'End time must be later than booking start time.',
            ]);
        }

        if (!$activeBooking) {
            return;
        }

        $existingBookings = RestaurantTableBooking::where(
            'restaurant_table_id',
            $data['restaurant_table_id']
        )
            ->whereDate('booking_date', $data['booking_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($ignoreBookingId, function ($query) use ($ignoreBookingId) {
                $query->where('id', '!=', $ignoreBookingId);
            })
            ->get([
                'id',
                'booking_time',
                'end_time',
            ]);

        foreach ($existingBookings as $booking) {
            $existingStart = Carbon::parse($booking->booking_time);

            $existingEnd = $booking->end_time
                ? Carbon::parse($booking->end_time)
                : $existingStart->copy()->addMinutes(60);

            $hasConflict = $requestedStart->lessThan($existingEnd)
                && $requestedEnd->greaterThan($existingStart);

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'restaurant_table_id' =>
                        'This table is already booked during the selected time. '
                        . 'Please choose another table or time.',
                ]);
            }
        }
    }
}
