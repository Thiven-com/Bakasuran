<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::withCount('products')
            ->latest()
            ->get();

        return view('admin.catalog.restaurants.all', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.catalog.restaurants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cuisine_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'delivery_time' => 'required|integer|min:1',
            'delivery_fee' => 'required|numeric|min:0',
            'minimum_order' => 'required|numeric|min:0',
            'average_rating' => 'nullable|numeric|between:0,5',
            'is_active' => 'required|boolean',
            'is_open' => 'required|boolean',
        ]);

        $slug = Str::slug($validated['name']);

        $baseSlug = $slug;
        $counter = 1;

        while (Restaurant::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;

        // Create image directories if they don't exist
        $logoDir = public_path('uploads/restaurants/logos');
        $coverDir = public_path('uploads/restaurants/covers');

        File::ensureDirectoryExists($logoDir);
        File::ensureDirectoryExists($coverDir);

        // Upload restaurant logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            $filename = uniqid('logo_', true) . '.' . $file->extension();

            $file->move($logoDir, $filename);

            $validated['logo'] = 'uploads/restaurants/logos/' . $filename;
        }

        // Upload restaurant cover image
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            $filename = uniqid('cover_', true) . '.' . $file->extension();

            $file->move($coverDir, $filename);

            $validated['cover_image'] = 'uploads/restaurants/covers/' . $filename;
        }
        // Save restaurant details to the database
        $restaurant = Restaurant::create($validated);

        // Redirect to the restaurant listing
        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant created successfully!');
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->loadCount('products');

        return view(
            'admin.catalog.restaurants.show',
            compact('restaurant')
        );
    }


    public function edit(Restaurant $restaurant)
    {
        return view(
            'admin.catalog.restaurants.edit',
            compact('restaurant')
        );
    }


    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cuisine_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'delivery_time' => 'required|integer|min:1',
            'delivery_fee' => 'required|numeric|min:0',
            'minimum_order' => 'required|numeric|min:0',
            'average_rating' => 'nullable|numeric|between:0,5',
            'is_active' => 'required|boolean',
            'is_open' => 'required|boolean',
        ]);

        // Generate a unique slug, excluding this restaurant.
        $slug = \Illuminate\Support\Str::slug($validated['name']);
        $baseSlug = $slug;
        $counter = 1;

        while (
            Restaurant::where('slug', $slug)
                ->where('id', '!=', $restaurant->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;

        // Keep the current images unless replacements are uploaded.
        unset($validated['logo'], $validated['cover_image']);

        $logoDir = public_path('uploads/restaurants/logos');
        $coverDir = public_path('uploads/restaurants/covers');

        \Illuminate\Support\Facades\File::ensureDirectoryExists($logoDir);
        \Illuminate\Support\Facades\File::ensureDirectoryExists($coverDir);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = uniqid('logo_', true) . '.' . $file->extension();

            $file->move($logoDir, $filename);

            $validated['logo'] = 'uploads/restaurants/logos/' . $filename;
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = uniqid('cover_', true) . '.' . $file->extension();

            $file->move($coverDir, $filename);

            $validated['cover_image'] = 'uploads/restaurants/covers/' . $filename;
        }

        $restaurant->update($validated);

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant updated successfully!');
    }

    public function destroy(Restaurant $restaurant)
    {
        // Prevent deletion if products are associated.
        if ($restaurant->products()->exists()) {
            return redirect()
                ->route('admin.restaurants.index')
                ->with(
                    'error',
                    'This restaurant cannot be deleted because it has associated products.'
                );
        }

        // Delete uploaded logo if it exists.
        if ($restaurant->logo) {
            $logoPath = public_path($restaurant->logo);

            if (is_file($logoPath)) {
                unlink($logoPath);
            }
        }

        // Delete uploaded cover image if it exists.
        if ($restaurant->cover_image) {
            $coverPath = public_path($restaurant->cover_image);

            if (is_file($coverPath)) {
                unlink($coverPath);
            }
        }

        $restaurant->delete();

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant deleted successfully!');
    }



}