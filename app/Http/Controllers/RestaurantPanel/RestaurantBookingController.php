<?php

namespace App\Http\Controllers\RestaurantPanel;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTableBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\RestaurantBookingSetting;
use App\Models\RestaurantTable;
use Illuminate\Validation\ValidationException;

class RestaurantBookingController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();

        $query = RestaurantTableBooking::with([
            'restaurantTable',
        ])
            ->where('restaurant_id', $restaurant->id)
            ->latest();

        // Search bookings
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_mobile', 'like', "%{$search}%");
            });
        }

        // Filter by booking status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by booking date
        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $bookings = $query->paginate(12)->withQueryString();

        // Booking statistics
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
            'restaurantpanel.table-bookings.index',
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

    public function show($id)
    {
        $restaurant = Auth::guard('restaurant')->user();

        $booking = RestaurantTableBooking::with([
            'restaurantTable',
            'restaurant',
            'customer',
            'bookingSession',
        ])
            ->where('restaurant_id', $restaurant->id)
            ->findOrFail($id);

        return view(
            'restaurantpanel.table-bookings.show',
            compact('restaurant', 'booking')
        );
    }

   
public function quickUpdateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => ['required', Rule::in(['confirmed', 'cancelled'])],
    ]);

    $restaurantId = Auth::guard('restaurant')->id();

    $booking = RestaurantTableBooking::where('restaurant_id', $restaurantId)
        ->findOrFail($id);

    if ($booking->status !== 'pending') {
        return redirect()
            ->route('restaurant.table-bookings.index')
            ->with('error', 'Only pending bookings can be updated.');
    }

    if ($validated['status'] === 'confirmed') {
        $bookingStart = Carbon::parse($booking->booking_time);

        $endTime = $booking->end_time
            ? Carbon::parse($booking->end_time)->format('H:i')
            : $bookingStart->copy()->addMinutes(60)->format('H:i');

        $this->validateBookingAvailability([
            'restaurant_id' => $booking->restaurant_id,
            'restaurant_table_id' => $booking->restaurant_table_id,
            'booking_date' => Carbon::parse($booking->booking_date)->format('Y-m-d'),
            'booking_time' => $bookingStart->format('H:i'),
            'end_time' => $endTime,
            'guest_count' => $booking->guest_count,
            'status' => 'confirmed',
        ], $booking->id);
    }

    $booking->status = $validated['status'];

    if ($validated['status'] === 'confirmed') {
        $booking->confirmed_at = now();
        $booking->cancelled_at = null;
    } else {
        $booking->cancelled_at = now();
    }

    $booking->save();

    return redirect()
        ->route('restaurant.table-bookings.index')
        ->with('success', 'Booking status updated successfully.');
}



private function validateBookingAvailability(array $data, $ignoreBookingId = null)
{
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

    $table = RestaurantTable::where('id', $data['restaurant_table_id'])
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
        ->where('restaurant_id', $data['restaurant_id'])
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
