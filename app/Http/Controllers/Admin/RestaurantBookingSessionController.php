<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantBookingHour;
use App\Models\RestaurantBookingSession;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class RestaurantBookingSessionController extends Controller
{
    /**
     * Display all booking sessions.
     */
    public function index(Request $request)
{
    $query = Restaurant::withCount('bookingSessions')
        ->orderBy('name');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $restaurants = $query->paginate(10)->withQueryString();

    $totalRestaurants = Restaurant::count();

    $totalSessions = RestaurantBookingSession::count();

    $activeSessions = RestaurantBookingSession::where('is_active', true)->count();

    $restaurantsWithSessions = Restaurant::whereHas('bookingSessions')->count();

    return view(
        'admin.catalog.booking-sessions.index',
        compact(
            'restaurants',
            'totalRestaurants',
            'totalSessions',
            'activeSessions',
            'restaurantsWithSessions'
        )
    );
}



public function restaurantSessions(Request $request, Restaurant $restaurant)
{
    $query = RestaurantBookingSession::where(
        'restaurant_id',
        $restaurant->id
    );

    if ($request->filled('search')) {
        $query->where('session_name', 'like', '%' . $request->search . '%');
    }

    $sessions = $query
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->paginate(20)
        ->withQueryString();

    $totalSessions = RestaurantBookingSession::where(
        'restaurant_id',
        $restaurant->id
    )->count();

    $activeSessions = RestaurantBookingSession::where('restaurant_id', $restaurant->id)
        ->where('is_active', true)
        ->count();

    $inactiveSessions = $totalSessions - $activeSessions;

    return view(
        'admin.catalog.booking-sessions.restaurant-index',
        compact(
            'restaurant',
            'sessions',
            'totalSessions',
            'activeSessions',
            'inactiveSessions'
        )
    );
}

    /**
     * Show create form.
     */
    public function create(Request $request)
{
    $restaurants = Restaurant::orderBy('name')->get();

    $selectedRestaurantId = $request->query('restaurant_id');

    return view(
        'admin.catalog.booking-sessions.create',
        compact('restaurants', 'selectedRestaurantId')
    );
}

    /**
     * Store a new booking session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'session_name' => ['required', 'string', 'max:100'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Validate opening hours and overlapping sessions.
        if ($validated['is_active']) {
            $this->validateSession($validated);
        }

        RestaurantBookingSession::create($validated);

       return redirect()
    ->route('admin.booking-sessions.restaurant', $validated['restaurant_id'])
    ->with('success', 'Booking session created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(RestaurantBookingSession $bookingSession)
    {
        $restaurants = Restaurant::orderBy('name')->get();

        return view(
            'admin.catalog.booking-sessions.edit',
            compact('bookingSession', 'restaurants')
        );
    }

    /**
     * Update an existing booking session.
     */
    public function update(
        Request $request,
        RestaurantBookingSession $bookingSession
    ) {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'session_name' => ['required', 'string', 'max:100'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Ignore the current session when checking overlaps.
        if ($validated['is_active']) {
            $this->validateSession($validated, $bookingSession->id);
        }

        $bookingSession->update($validated);

        return redirect()
    ->route('admin.booking-sessions.restaurant', $bookingSession->restaurant_id)
    ->with('success', 'Booking session updated successfully.');
    }

    /**
     * Delete a booking session.
     */
   public function destroy(RestaurantBookingSession $bookingSession)
{
    $restaurantId = $bookingSession->restaurant_id;

    if ($bookingSession->bookings()->exists()) {
        return redirect()
            ->route('admin.booking-sessions.restaurant', $restaurantId)
            ->with(
                'error',
                'This session cannot be deleted because customer bookings are associated with it.'
            );
    }

    $bookingSession->delete();

    return redirect()
        ->route('admin.booking-sessions.restaurant', $restaurantId)
        ->with('success', 'Booking session deleted successfully.');
}

    /**
     * Validate session against restaurant opening hours
     * and other active sessions.
     */
    private function validateSession(
        array $validated,
        ?int $ignoreSessionId = null
    ): void {
        $restaurantId = $validated['restaurant_id'];
        $dayOfWeek = (int) $validated['day_of_week'];

        // Check restaurant opening hours.
        $bookingHour = RestaurantBookingHour::where(
            'restaurant_id',
            $restaurantId
        )
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (
            !$bookingHour ||
            $bookingHour->is_closed ||
            !$bookingHour->opening_time ||
            !$bookingHour->closing_time
        ) {
            throw ValidationException::withMessages([
                'day_of_week' => 'The restaurant is closed on the selected day.',
            ]);
        }

        // Ensure session is within opening hours.
        $sessionStart = Carbon::createFromFormat(
            'H:i',
            $validated['start_time']
        )->format('H:i');

        $sessionEnd = Carbon::createFromFormat(
            'H:i',
            $validated['end_time']
        )->format('H:i');

        $openingTime = Carbon::parse(
            $bookingHour->opening_time
        )->format('H:i');

        $closingTime = Carbon::parse(
            $bookingHour->closing_time
        )->format('H:i');

        if (
            $sessionStart < $openingTime ||
            $sessionEnd > $closingTime
        ) {
            throw ValidationException::withMessages([
                'start_time' => 'Session timings must be within restaurant opening hours.',
            ]);
        }

        // Check overlapping active sessions.
        $overlapQuery = RestaurantBookingSession::where(
            'restaurant_id',
            $restaurantId
        )
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time']);

        if ($ignoreSessionId !== null) {
            $overlapQuery->where('id', '!=', $ignoreSessionId);
        }

        if ($overlapQuery->exists()) {
            throw ValidationException::withMessages([
                'start_time' => 'This session overlaps with another active session.',
            ]);
        }
    }
}
