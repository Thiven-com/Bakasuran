<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    // Display all testimonials
    public function index()
    {
        $testimonials = Testimonial::latest()->get();

        return view(
            'admin.testimonials.index',
            compact('testimonials')
        );
    }

    // Show create form
    public function create()
    {
        return view('admin.testimonials.create');
    }

    // Save new testimonial
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'city' => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('image')) {
            $directory = public_path('website/images/testimonials');

            File::ensureDirectoryExists($directory);

            $file = $request->file('image');
            $fileName = uniqid('testimonial_', true)
                . '.' . $file->extension();

            $file->move($directory, $fileName);

            $validated['image'] =
                'website/images/testimonials/' . $fileName;
        }

        Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    // Show one testimonial
    public function show(Testimonial $testimonial)
    {
        return view(
            'admin.testimonials.show',
            compact('testimonial')
        );
    }

    // Show edit form
    public function edit(Testimonial $testimonial)
    {
        return view(
            'admin.testimonials.edit',
            compact('testimonial')
        );
    }

    // Update testimonial
    public function update(
        Request $request,
        Testimonial $testimonial
    ) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'city' => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('image')) {
            $directory = public_path('website/images/testimonials');

            File::ensureDirectoryExists($directory);

            $file = $request->file('image');
            $fileName = uniqid('testimonial_', true)
                . '.' . $file->extension();

            $file->move($directory, $fileName);

            // Remove the previous image if it exists
            if ($testimonial->image) {
                $oldImage = public_path($testimonial->image);

                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }

            $validated['image'] =
                'website/images/testimonials/' . $fileName;
        } else {
            unset($validated['image']);
        }

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    // Delete testimonial
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image) {
            $imagePath = public_path($testimonial->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    public function publicTestimonials()
{
    $testimonials = \App\Models\Testimonial::where('status', 'approved')
        ->latest()
        ->get();

    return view('website.testimonials', compact('testimonials'));
}
}