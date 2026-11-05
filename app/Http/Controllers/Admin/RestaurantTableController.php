<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantTableController extends Controller
{
    /**
     * Display all restaurant tables.
     */

   public function index(Request $request)
{
    $query = Restaurant::query()
        ->withCount([
            'tables',
            'bookingHours',
        ])
        ->with([
            'tables:id,restaurant_id,status,seating_capacity'
        ])
        ->orderBy('name');

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where('name', 'like', "%{$search}%");
    }

    $restaurants = $query
        ->paginate(12)
        ->withQueryString();

    $totalRestaurants = Restaurant::count();

    $totalTables = RestaurantTable::count();

    $totalCapacity = RestaurantTable::where('status', 1)
        ->sum('seating_capacity');

    return view(
        'admin.catalog.restaurant-tables.index',
        compact(
            'restaurants',
            'totalRestaurants',
            'totalTables',
            'totalCapacity'
        )
    );
}




    /**
     * Show all tables belonging to the selected restaurant.
     */
    public function manage(Restaurant $restaurant)
    {
        $tables = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->latest()
            ->paginate(12);

        $totalTables = RestaurantTable::where('restaurant_id', $restaurant->id)->count();

        $activeTables = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->where('status', 1)
            ->count();

        $inactiveTables = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->where('status', 0)
            ->count();

        $totalCapacity = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->where('status', 1)
            ->sum('seating_capacity');

        return view(
            'admin.catalog.restaurant-tables.manage',
            compact(
                'restaurant',
                'tables',
                'totalTables',
                'activeTables',
                'inactiveTables',
                'totalCapacity'
            )
        );
    }

    /**
     * Show the add table form.
     */
    public function create(Request $request)
    {
        $restaurants = Restaurant::where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedRestaurantId = $request->query('restaurant_id');

        return view(
            'admin.catalog.restaurant-tables.create',
            compact('restaurants', 'selectedRestaurantId')
        );
    }

    /**
     * Store a new restaurant table.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => [
                'required',
                'exists:restaurants,id',
            ],

            'table_name' => [
                'required',
                'string',
                'max:100',
            ],

            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_number')
                    ->where('restaurant_id', $request->restaurant_id)->where('seating_type', $request->seating_type),
            ],

            'seating_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'seating_type' => [
                'required',
                Rule::in([
                    'indoor',
                    'outdoor',
                    'family',
                    'private',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:0,1',
            ],
        ]);

        RestaurantTable::create($validated);

        return redirect()
            ->route('admin.restaurant-tables.manage', $validated['restaurant_id'])
            ->with('success', 'Restaurant table added successfully.');
    }

    /**
     * Redirect table details to edit page.
     */
    public function show(RestaurantTable $restaurant_table)
    {
        return redirect()->route(
            'admin.restaurant-tables.edit',
            $restaurant_table->id
        );
    }

    /**
     * Show the edit table form.
     */
    public function edit(RestaurantTable $restaurant_table)
{
    $restaurant = $restaurant_table->restaurant;

    return view(
        'admin.catalog.restaurant-tables.edit',
        compact(
            'restaurant_table',
            'restaurant'
        )
    );
}

    /**
     * Update restaurant table details.
     */
    public function update(
        Request $request,
        RestaurantTable $restaurant_table
    ) {
        $validated = $request->validate([
           'restaurant_id' => [
    'required',
    Rule::in([$restaurant_table->restaurant_id]),
],

            'table_name' => [
                'required',
                'string',
                'max:100',
            ],

            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_number')
                    ->where('restaurant_id', $request->restaurant_id)
                    ->where('seating_type', $request->seating_type)
                    ->ignore($restaurant_table->id),
            ],

            'seating_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'seating_type' => [
                'required',
                Rule::in([
                    'indoor',
                    'outdoor',
                    'family',
                    'private',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:0,1',
            ],
        ]);

        $restaurant_table->update($validated);

        return redirect()
            ->route('admin.restaurant-tables.manage', $restaurant_table->restaurant_id)
            ->with('success', 'Restaurant table updated successfully.');
    }

    /**
     * Delete a restaurant table.
     */
    public function destroy(RestaurantTable $restaurant_table)
{
    $restaurantId = $restaurant_table->restaurant_id;

    // Prevent deletion if booking records exist
    if ($restaurant_table->bookings()->exists()) {
        return redirect()
            ->route('admin.restaurant-tables.manage', $restaurantId)
            ->with(
                'error',
                'This table has booking records. Deactivate it instead of deleting it.'
            );
    }

    $restaurant_table->delete();

    return redirect()
        ->route('admin.restaurant-tables.manage', $restaurantId)
        ->with('success', 'Restaurant table deleted successfully.');
}
}
