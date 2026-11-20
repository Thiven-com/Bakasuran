<?php

namespace App\Http\Controllers\RestaurantPanel;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RestaurantTableController extends Controller
{
    public function index()
    {
        $restaurant = Auth::guard('restaurant')->user();

        $tables = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )->latest()->paginate(12);

        $totalTables = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )->count();

        $activeTables = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )->where('status', 1)->count();

        $inactiveTables = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )->where('status', 0)->count();

        $totalCapacity = RestaurantTable::where(
            'restaurant_id',
            $restaurant->id
        )->where('status', 1)->sum('seating_capacity');

        return view('restaurantpanel.tables.index', compact(
            'restaurant',
            'tables',
            'totalTables',
            'activeTables',
            'inactiveTables',
            'totalCapacity'
        ));
    }

    public function create()
    {
        return view('restaurantpanel.tables.create');
    }

    public function store(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();

        $validated = $request->validate([
            'table_name' => 'required|string|max:100',

            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_number')
                    ->where(function ($query) use ($restaurant, $request) {
                        return $query
                            ->where('restaurant_id', $restaurant->id)
                            ->where('seating_type', $request->seating_type);
                    }),
            ],

            'seating_capacity' => 'required|integer|min:1',

            'seating_type' => [
                'required',
                Rule::in(['indoor', 'outdoor', 'family', 'private']),
            ],

            'description' => 'nullable|string',

            'status' => 'required|in:0,1',
        ]);

        $validated['restaurant_id'] = $restaurant->id;

        RestaurantTable::create($validated);

        return redirect()
            ->route('restaurant.tables.index')
            ->with('success', 'Restaurant table added successfully.');
    }


    public function edit($id)
    {
        $restaurant = Auth::guard('restaurant')->user();

        $table = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->findOrFail($id);

        return view('restaurantpanel.tables.edit', compact('table'));
    }

    public function update(Request $request, $id)
    {
        $restaurant = Auth::guard('restaurant')->user();

        // Ensure the table belongs to the logged-in restaurant
        $table = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'table_name' => 'required|string|max:100',

            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_number')
                    ->where(function ($query) use ($restaurant, $request) {
                        return $query
                            ->where('restaurant_id', $restaurant->id)
                            ->where('seating_type', $request->seating_type);
                    })
                    ->ignore($table->id),
            ],

            'seating_capacity' => 'required|integer|min:1',

            'seating_type' => [
                'required',
                Rule::in(['indoor', 'outdoor', 'family', 'private']),
            ],

            'description' => 'nullable|string',

            'status' => 'required|in:0,1',
        ]);

        $table->update($validated);

        return redirect()
            ->route('restaurant.tables.index')
            ->with('success', 'Restaurant table updated successfully.');
    }


    public function destroy($id)
    {
        $restaurant = Auth::guard('restaurant')->user();

        // Ensure the table belongs to the logged-in restaurant
        $table = RestaurantTable::where('restaurant_id', $restaurant->id)
            ->findOrFail($id);

        // Prevent deletion if the table has booking records
        if ($table->bookings()->exists()) {
            return redirect()
                ->route('restaurant.tables.index')
                ->with('error', 'This table cannot be deleted because booking records exist.');
        }

        $table->delete();

        return redirect()
            ->route('restaurant.tables.index')
            ->with('success', 'Restaurant table deleted successfully.');
    }
}
