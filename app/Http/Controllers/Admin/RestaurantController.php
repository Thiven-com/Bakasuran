<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

            'price_for_two' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|integer|between:0,100',
            'is_featured' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',

            'average_rating' => 'nullable|numeric|between:0,5',
            'is_active' => 'required|boolean',
            'is_open' => 'required|boolean',
        ]);

        // Use safe defaults for optional fields.
        $validated['discount_percentage'] = $validated['discount_percentage'] ?? 0;
        $validated['is_featured'] = $validated['is_featured'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['average_rating'] = $validated['average_rating'] ?? 0;

        // Generate a unique slug, including a fallback for names with no slug characters.
        $baseSlug = Str::slug($validated['name']);
        if ($baseSlug === '') {
            $baseSlug = 'restaurant';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (Restaurant::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;

        $logoDir = public_path('uploads/restaurants/logos');
        $coverDir = public_path('uploads/restaurants/covers');

        File::ensureDirectoryExists($logoDir);
        File::ensureDirectoryExists($coverDir);

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

        Restaurant::create($validated);

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant created successfully!');
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->loadCount('products');

        return view('admin.catalog.restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        return view('admin.catalog.restaurants.edit', compact('restaurant'));
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

            'price_for_two' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|integer|between:0,100',
            'is_featured' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',

            'average_rating' => 'nullable|numeric|between:0,5',
            'is_active' => 'required|boolean',
            'is_open' => 'required|boolean',
        ]);

        $validated['discount_percentage'] = $validated['discount_percentage'] ?? 0;
        $validated['is_featured'] = $validated['is_featured'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['average_rating'] = $validated['average_rating'] ?? 0;

        // Regenerate a unique slug, excluding this restaurant.
        $baseSlug = Str::slug($validated['name']);
        if ($baseSlug === '') {
            $baseSlug = 'restaurant';
        }

        $slug = $baseSlug;
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

        // Keep existing images unless a replacement is uploaded.
        unset($validated['logo'], $validated['cover_image']);

        $logoDir = public_path('uploads/restaurants/logos');
        $coverDir = public_path('uploads/restaurants/covers');

        File::ensureDirectoryExists($logoDir);
        File::ensureDirectoryExists($coverDir);

        $oldLogo = $restaurant->logo;
        $oldCoverImage = $restaurant->cover_image;

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

        // Remove old files only after the database update succeeds.
        if (isset($validated['logo']) && $oldLogo) {
            $oldLogoPath = public_path($oldLogo);
            if (is_file($oldLogoPath)) {
                File::delete($oldLogoPath);
            }
        }

        if (isset($validated['cover_image']) && $oldCoverImage) {
            $oldCoverPath = public_path($oldCoverImage);
            if (is_file($oldCoverPath)) {
                File::delete($oldCoverPath);
            }
        }

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant updated successfully!');
    }

    public function destroy(Restaurant $restaurant)
    {
        // Prevent deletion when products are associated with this restaurant.
        if ($restaurant->products()->exists()) {
            return redirect()
                ->route('admin.restaurants.index')
                ->with(
                    'error',
                    'This restaurant cannot be deleted because it has associated products.'
                );
        }

        // Delete uploaded images if they exist.
        foreach ([$restaurant->logo, $restaurant->cover_image] as $imagePath) {
            if ($imagePath) {
                $fullPath = public_path($imagePath);

                if (is_file($fullPath)) {
                    File::delete($fullPath);
                }
            }
        }

        $restaurant->delete();

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant deleted successfully!');
    }
}
