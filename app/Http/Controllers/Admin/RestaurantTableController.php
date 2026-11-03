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
        $query = RestaurantTable::with('restaurant')->latest();

        // Search tables by name, number or restaurant
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('table_name', 'like', "%{$search}%")
                    ->orWhere('table_number', 'like', "%{$search}%")
                    ->orWhereHas('restaurant', function ($restaurant) use ($search) {
                        $restaurant->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by restaurant
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter by seating type
        if ($request->filled('seating_type')) {
            $query->where('seating_type', $request->seating_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $tables = $query->paginate(12)->withQueryString();

        $restaurants = Restaurant::orderBy('name')->get();

        // Dashboard statistics
        $totalTables = RestaurantTable::count();

        $activeTables = RestaurantTable::where('status', 1)->count();

        $inactiveTables = RestaurantTable::where('status', 0)->count();

        $totalCapacity = RestaurantTable::where('status', 1)
            ->sum('seating_capacity');

        return view(
            'admin.catalog.restaurant-tables.index',
            compact(
                'tables',
                'restaurants',
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
    public function create()
    {
        $restaurants = Restaurant::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.catalog.restaurant-tables.create',
            compact('restaurants')
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
                    ->where('restaurant_id', $request->restaurant_id),
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
            ->route('admin.restaurant-tables.index')
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
        $restaurants = Restaurant::orderBy('name')->get();

        return view(
            'admin.catalog.restaurant-tables.edit',
            compact(
                'restaurant_table',
                'restaurants'
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
                    ->where('restaurant_id', $request->restaurant_id)
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
            ->route('admin.restaurant-tables.index')
            ->with('success', 'Restaurant table updated successfully.');
    }

    /**
     * Delete a restaurant table.
     */
    public function destroy(RestaurantTable $restaurant_table)
    {
        // Prevent deletion if booking records exist
        if ($restaurant_table->bookings()->exists()) {
            return redirect()
                ->route('admin.restaurant-tables.index')
                ->with(
                    'error',
                    'This table has booking records. Deactivate it instead of deleting it.'
                );
        }

        $restaurant_table->delete();

        return redirect()
            ->route('admin.restaurant-tables.index')
            ->with('success', 'Restaurant table deleted successfully.');
    }
}
