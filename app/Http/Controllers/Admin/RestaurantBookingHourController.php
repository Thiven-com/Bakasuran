<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantBookingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RestaurantBookingHourController extends Controller
{
    private $days = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    // INDEX
    public function index()
    {
        $restaurants = Restaurant::whereHas('bookingHours')
            ->with('bookingHours')
            ->orderBy('name')
            ->paginate(10);

        return view(
            'admin.catalog.booking-hours.index',
            compact('restaurants')
        );
    }

    // CREATE
    public function create()
    {
        $restaurants = Restaurant::whereDoesntHave('bookingHours')
            ->orderBy('name')
            ->get();

        return view(
            'admin.catalog.booking-hours.create',
            [
                'restaurants' => $restaurants,
                'days' => $this->days,
            ]
        );
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $this->validateSchedule($request, true);

        DB::transaction(function () use ($validated) {

            foreach ($this->days as $day => $dayName) {

                $hour = $validated['hours'][$day];

                RestaurantBookingHour::create([
                    'restaurant_id' => $validated['restaurant_id'],
                    'day_of_week' => $day,
                    'opening_time' => $hour['is_closed']
                        ? null : $hour['opening_time'],
                    'closing_time' => $hour['is_closed']
                        ? null : $hour['closing_time'],
                    'is_closed' => $hour['is_closed'],
                ]);
            }
        });

        return redirect()
            ->route('admin.booking-hours.index')
            ->with('success', 'Booking hours created successfully.');
    }

    // EDIT
    public function edit(Restaurant $restaurant)
    {
        if (!$restaurant->bookingHours()->exists()) {
            return redirect()
                ->route('admin.booking-hours.index')
                ->with('error', 'Booking hours not found for this restaurant.');
        }


        $bookingHours = RestaurantBookingHour::where(
            'restaurant_id',
            $restaurant->id
        )->get()->keyBy('day_of_week');

        return view(
            'admin.catalog.booking-hours.edit',
            [
                'restaurant' => $restaurant,
                'bookingHours' => $bookingHours,
                'days' => $this->days,
            ]
        );
    }

    // UPDATE
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $this->validateSchedule($request, false);

        DB::transaction(function () use ($validated, $restaurant) {

            foreach ($this->days as $day => $dayName) {

                $hour = $validated['hours'][$day];

                RestaurantBookingHour::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'day_of_week' => $day,
                    ],
                    [
                        'opening_time' => $hour['is_closed']
                            ? null : $hour['opening_time'],
                        'closing_time' => $hour['is_closed']
                            ? null : $hour['closing_time'],
                        'is_closed' => $hour['is_closed'],
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.booking-hours.index')
            ->with('success', 'Booking hours updated successfully.');
    }

    // DELETE
    public function destroy(Restaurant $restaurant)
    {
        RestaurantBookingHour::where(
            'restaurant_id',
            $restaurant->id
        )->delete();

        return redirect()
            ->route('admin.booking-hours.index')
            ->with('success', 'Booking hours deleted successfully.');
    }

    // VALIDATION
    private function validateSchedule(Request $request, bool $requireRestaurant = true)
    {
        $rules = [
            'hours' => ['required', 'array', 'size:7'],
            'hours.*' => ['required', 'array'],
            'hours.*.is_closed' => ['nullable', 'boolean'],
            'hours.*.opening_time' => ['nullable', 'date_format:H:i'],
            'hours.*.closing_time' => ['nullable', 'date_format:H:i'],
        ];

        if ($requireRestaurant) {
            $rules['restaurant_id'] = ['required', 'exists:restaurants,id'];
        }

        $validated = $request->validate($rules);

        foreach ($this->days as $day => $dayName) {

            if (!isset($validated['hours'][$day])) {
                throw ValidationException::withMessages([
                    "hours.$day" => "Schedule missing for $dayName.",
                ]);
            }

            $hour = $validated['hours'][$day];

            $isClosed = filter_var(
                $hour['is_closed'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            );

            if (!$isClosed) {

                if (
                    empty($hour['opening_time']) ||
                    empty($hour['closing_time'])
                ) {
                    throw ValidationException::withMessages([
                        "hours.$day.opening_time" =>
                            "Opening and closing times are required for $dayName.",
                    ]);
                }

                if ($hour['closing_time'] <= $hour['opening_time']) {
                    throw ValidationException::withMessages([
                        "hours.$day.closing_time" =>
                            "Closing time must be later than opening time for $dayName.",
                    ]);
                }
            }

            $validated['hours'][$day]['is_closed'] = $isClosed;
        }

        return $validated;
    }
}
