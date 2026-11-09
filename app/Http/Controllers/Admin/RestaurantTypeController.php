<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantTypeController extends Controller
{
    public function index()
    {
        $types = RestaurantType::get();

        return view('admin.restaurant-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:restaurant_types,name',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        RestaurantType::create($validated);

        return redirect()
            ->route('admin.restaurant-types.index')
            ->with('success', 'Restaurant type created successfully.');
    }

    public function update(Request $request, RestaurantType $restaurantType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('restaurant_types', 'name')->ignore($restaurantType->id),
            ],
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $restaurantType->update($validated);

        return redirect()
            ->route('admin.restaurant-types.index')
            ->with('success', 'Restaurant type updated successfully.');
    }

    public function destroy(RestaurantType $restaurantType)
    {
        if ($restaurantType->restaurants()->exists()) {
            return redirect()
                ->route('admin.restaurant-types.index')
                ->with('error', 'This type is assigned to restaurants. Please reassign those restaurants before deleting it.');
        }

        $restaurantType->delete();

        return redirect()
            ->route('admin.restaurant-types.index')
            ->with('success', 'Restaurant type deleted successfully.');
    }
}
