<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantBookingSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RestaurantBookingSettingController extends Controller
{
    /**
     * Display all restaurant booking settings.
     */
    public function index(Request $request)
    {
        $query = RestaurantBookingSetting::with('restaurant')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('restaurant', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'is_booking_enabled',
                $request->status === 'enabled'
            );
        }

        $settings = $query->paginate(12)->withQueryString();

        $totalSettings = RestaurantBookingSetting::count();

        $enabledSettings = RestaurantBookingSetting::where(
            'is_booking_enabled',
            true
        )->count();

        $disabledSettings = RestaurantBookingSetting::where(
            'is_booking_enabled',
            false
        )->count();

        return view(
            'admin.catalog.booking-settings.index',
            compact(
                'settings',
                'totalSettings',
                'enabledSettings',
                'disabledSettings'
            )
        );
    }


    public function restaurantSettings(Restaurant $restaurant)
{
    $bookingSetting = RestaurantBookingSetting::where(
        'restaurant_id',
        $restaurant->id
    )->first();

    if ($bookingSetting) {
        return redirect()->route(
            'admin.booking-settings.edit',
            $bookingSetting->id
        );
    }

    return redirect()
        ->route('admin.booking-settings.create', [
            'restaurant_id' => $restaurant->id
        ])
        ->with('info', 'Please configure booking settings for this restaurant.');
}

    /**
     * Show create booking settings page.
     */
   public function create(Request $request)
{
    $restaurants = Restaurant::whereNotIn(
        'id',
        RestaurantBookingSetting::select('restaurant_id')
    )
        ->orderBy('name')
        ->get();

    $selectedRestaurantId = $request->query('restaurant_id');

    return view(
        'admin.catalog.booking-settings.create',
        compact('restaurants', 'selectedRestaurantId')
    );
}

    /**
     * Store booking settings.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => [
                'required',
                'exists:restaurants,id',
                Rule::unique('restaurant_booking_settings', 'restaurant_id'),
            ],

            'slot_duration' => [
                'required',
                'integer',
                'min:15',
                'max:240',
            ],

            'max_advance_days' => [
                'required',
                'integer',
                'min:1',
                'max:365',
            ],

            'min_guests' => [
                'required',
                'integer',
                'min:1',
            ],

            'max_guests' => [
                'required',
                'integer',
                'min:1',
                'gte:min_guests',
            ],

            'is_booking_enabled' => [
                'nullable',
                'boolean',
            ],

            'booking_duration' => [
                'required',
                'integer',
                'min:15',
                'max:480',
            ],

            'cleaning_duration' => [
                'required',
                'integer',
                'min:0',
                'max:120',
            ],

            'min_notice_minutes' => [
                'required',
                'integer',
                'min:0',
                'max:10080',
            ],
        ]);

     $bookingSetting = RestaurantBookingSetting::create([
    'restaurant_id' => $validated['restaurant_id'],
    'slot_duration' => $validated['slot_duration'],
    'max_advance_days' => $validated['max_advance_days'],
    'min_guests' => $validated['min_guests'],
    'max_guests' => $validated['max_guests'],
    'is_booking_enabled' => $request->boolean('is_booking_enabled'),
    'booking_duration' => $validated['booking_duration'],
    'cleaning_duration' => $validated['cleaning_duration'],
    'min_notice_minutes' => $validated['min_notice_minutes'],
]);

return redirect()
    ->route('admin.booking-settings.edit', $bookingSetting->id)
    ->with('success', 'Booking settings created successfully.');
    }

    /**
     * Show edit booking settings page.
     */
    public function edit(RestaurantBookingSetting $booking_setting)
    {
        $restaurants = Restaurant::orderBy('name')->get();

        return view(
            'admin.catalog.booking-settings.edit',
            compact('booking_setting', 'restaurants')
        );
    }

    /**
     * Update booking settings.
     */
    public function update(
        Request $request,
        RestaurantBookingSetting $booking_setting
    ) {
        $validated = $request->validate([
            'restaurant_id' => [
                'required',
                'exists:restaurants,id',
                Rule::unique('restaurant_booking_settings', 'restaurant_id')
                    ->ignore($booking_setting->id),
            ],

            'slot_duration' => [
                'required',
                'integer',
                'min:15',
                'max:240',
            ],

            'max_advance_days' => [
                'required',
                'integer',
                'min:1',
                'max:365',
            ],

            'min_guests' => [
                'required',
                'integer',
                'min:1',
            ],

            'max_guests' => [
                'required',
                'integer',
                'min:1',
                'gte:min_guests',
            ],

            'is_booking_enabled' => [
                'nullable',
                'boolean',
            ],

            'booking_duration' => [
                'required',
                'integer',
                'min:15',
                'max:480',
            ],

            'cleaning_duration' => [
                'required',
                'integer',
                'min:0',
                'max:120',
            ],

            'min_notice_minutes' => [
                'required',
                'integer',
                'min:0',
                'max:10080',
            ],
        ]);

        $booking_setting->update([
            'restaurant_id' => $validated['restaurant_id'],
            'slot_duration' => $validated['slot_duration'],
            'max_advance_days' => $validated['max_advance_days'],
            'min_guests' => $validated['min_guests'],
            'max_guests' => $validated['max_guests'],
            'is_booking_enabled' => $request->boolean('is_booking_enabled'),
            'booking_duration' => $validated['booking_duration'],
            'cleaning_duration' => $validated['cleaning_duration'],
            'min_notice_minutes' => $validated['min_notice_minutes'],
        ]);

       return redirect()
    ->route('admin.booking-settings.edit', $booking_setting->id)
    ->with('success', 'Booking settings updated successfully.');
    }

    /**
     * Delete booking settings.
     */
    public function destroy(RestaurantBookingSetting $booking_setting)
    {
        $booking_setting->delete();

        return redirect()
            ->route('admin.booking-settings.index')
            ->with('success', 'Booking settings deleted successfully.');
    }
}
