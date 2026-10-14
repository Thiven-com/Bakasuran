<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Section;
use App\Models\SectionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Section::get();
        return view('admin.sections.all', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data = Section::find($id);
        if (!isset($data->id)) {
            Alert::toast('Section Details Not Found', 'warning');
            return redirect(route('admin.sections.index'));
        }
        $products = ProductVariant::where('stock', '>', 0)->whereHas('product', function ($q) {
            $q->where('status', 'show');
        })->get();

        $rules = $this->getSectionRules($data->section_slug);

        $sectionImageRules = $this->sectionImageRules();
        return view('admin.sections.edit', compact('data', 'products', 'rules', 'sectionImageRules'));
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        // Get rules based on section_slug
        $rules = $this->getSectionRules($section->section_slug);

        // ---------------- VALIDATION ----------------
        $validation = [
            'title' => ($rules['title_required'] ? 'required' : 'nullable') . '|max:' . $rules['title_max'],
            'short_description' => ($rules['short_description_required'] ? 'required' : 'nullable') . '|max:' . $rules['short_description_max'],
            'description' => 'nullable',
            // 'image' => ($rules['image_required'] ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png|max:' . $rules['image_size'],
            'image' => (!empty($section->image) ? 'nullable' : ($rules['image_required'] ? 'required' : 'nullable'))
                . '|image|mimes:jpg,jpeg,png|max:' . $rules['image_size'],
            'product_ids' => $rules['product_required'] ? 'required|array' : 'nullable|array',
        ];

        $validator = Validator::make($request->all(), $validation);

        // ---------------- IMAGE WIDTH/HEIGHT CHECK ----------------
        $validator->after(function ($validator) use ($request, $rules) {

            if ($request->hasFile('image') && $rules['image_width']) {

                $img = getimagesize($request->file('image'));

                $width = $img[0];
                $height = $img[1];

                if ($width != $rules['image_width'] || $height != $rules['image_height']) {
                    $validator->errors()->add(
                        'image',
                        "Image must be {$rules['image_width']} x {$rules['image_height']} px"
                    );
                }
            }
        });

        if ($validator->fails()) {
            Alert::toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        // $validator->validate();

        // ---------------- SAVE IMAGE ----------------
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sections');
            $section->image = $path;
        }

        // ---------------- UPDATE DATA ----------------
        $section->title = $request->title;
        $section->short_description = $request->short_description;
        $section->description = $request->description;

        // Save multiple products as comma string
        $section->product_ids = $request->product_ids ?? [];

        $section->save();

        if ($request->has('images')) {

            foreach ($request->images as  $img) {
                // UPDATE
                if (!empty($img['id'])) {
                    $image = SectionImage::find($img['id']);
                    $image->title = $img['title'] ?? null;

                    $dimension = $this->getImageDimensionsBySlug($image->slug);
                    if (isset($img['image']) && $img['image']) {
                        // ✅ CHECK DIMENSIONS
                        if ($dimension['width'] && $dimension['height']) {

                            $size = getimagesize($img['image']);

                            $width  = $size[0];
                            $height = $size[1];

                            if ($width != $dimension['width'] || $height != $dimension['height']) {

                                Alert::toast(
                                    "Image " . ($image->title) . " must be {$dimension['width']} x {$dimension['height']} px",
                                    'error'
                                );

                                return back()->withInput();
                            }
                        }

                        if (isset($img['image'])) {
                            $path = $img['image']->store('section');
                            $image->image = $path;
                        }
                    }
                    $image->save();
                }
            }
        }
        Alert::toast('Section updated successfully!', 'success');
        return redirect(route('admin.sections.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function getSectionRules($slug)
    {
        switch ($slug) {

            case 'today_deals':
                return [
                    'title_required' => true,
                    'title_max' => 25,
                    'short_description_required' => true,
                    'short_description_max' => 45,
                    'image_required' => false,
                    'image_width' => 800,
                    'image_height' => 400,
                    'image_size' => 2048,
                    'product_required' => true,
                ];
            case 'nearby_products':
                return [
                    'title_required' => true,
                    'title_max' => 75,
                    'short_description_required' => true,
                    'short_description_max' => 50,
                    'image_required' => false,
                    'image_width' => 800,
                    'image_height' => 400,
                    'image_size' => 2048,
                    'product_required' => true,
                ];

            case 'clearance_sale':
                return [
                    'title_required' => true,
                    'title_max' => 100,
                    'short_description_required' => false,
                    'short_description_max' => 45,
                    'image_required' => true,
                    'image_width' => 450,
                    'image_height' => 494,
                    'image_size' => 2048,
                    'product_required' => true,
                ];
            case 'recent_products':
                return [
                    'title_required' => true,
                    'title_max' => 50,
                    'short_description_required' => false,
                    'short_description_max' => 45,
                    'image_required' => true,
                    'image_width' => 830,
                    'image_height' => 775,
                    'image_size' => 2048,
                    'product_required' => true,
                ];

            case 'great_saving_sales':
                return [
                    'title_required' => true,
                    'title_max' => 40,
                    'short_description_required' => true,
                    'short_description_max' => 45,
                    'image_required' => true,
                    'image_width' => 1000,
                    'image_height' => 1000,
                    'image_size' => 2048,
                    'product_required' => true,
                ];
            case 'category_collection':
                return [
                    'title_required' => true,
                    'title_max' => 50,
                    'short_description_required' => true,
                    'short_description_max' => 200,
                    'image_required' => false,
                    'image_width' => 1000,
                    'image_height' => 1000,
                    'image_size' => 2048,
                    'product_required' => false,
                ];
            case 'all_collections':
                return [
                    'title_required' => true,
                    'title_max' => 80,
                    'short_description_required' => false,
                    'short_description_max' => 200,
                    'image_required' => false,
                    'image_width' => 1000,
                    'image_height' => 1000,
                    'image_size' => 2048,
                    'product_required' => false,
                ];

            default:
                return [
                    'title_required' => false,
                    'title_max' => 100,
                    'short_description_required' => false,
                    'short_description_max' => 45,
                    'image_required' => false,
                    'image_width' => 0,
                    'image_height' => 0,
                    'image_size' => 1024,
                    'product_required' => true,
                ];
        }
    }

    // public function getImageDimensionsBySlug($slug)
    // {
    //     return match ($slug) {
    //         'category_first' => ['width' => 1024, 'height' => 1024],
    //         'category_second' => ['width' => 450, 'height' => 400],
    //         'category_third'  => ['width' => 450, 'height' => 400],
    //         'all_collection_1'  => ['width' => 190, 'height' => 220],
    //         'all_collection_2'  => ['width' => 200, 'height' => 100],
    //         'all_collection_3'  => ['width' => 190, 'height' => 220],
    //         'all_collection_4'  => ['width' => 190, 'height' => 220],
    //         'all_collection_5'  => ['width' => 190, 'height' => 220],
    //         default       => ['width' => null, 'height' => null],
    //     };
    // }

    public function getImageDimensionsBySlug($slug)
    {
        // Exact matches
        $map = [
            'category_first'  => [1024, 1024],
            'category_second' => [450, 400],
            'category_third'  => [450, 400],
            'all_collection_2' => [200, 100],
        ];

        // ✅ Direct match
        if (isset($map[$slug])) {
            return [
                'width'  => $map[$slug][0],
                'height' => $map[$slug][1],
            ];
        }

        // ✅ Pattern match (all_collection_1,3,4,5...)
        if (str_starts_with($slug, 'all_collection_')) {
            return [
                'width'  => 190,
                'height' => 220,
            ];
        }

        // Default
        return [
            'width' => null,
            'height' => null,
        ];
    }

    public function sectionImageRules()
    {
        return [
            'category_first' => ['width' => 1024, 'height' => 1024],
            'category_second' => ['width' => 450, 'height' => 400],
            'category_third'  => ['width' => 450, 'height' => 400],
            'all_collection_1' => ['width' => 190, 'height' => 220],
            'all_collection_2' => ['width' => 200, 'height' => 100],
            'all_collection_3' => ['width' => 190, 'height' => 220],
            'all_collection_4' => ['width' => 190, 'height' => 220],
            'all_collection_5' => ['width' => 190, 'height' => 220],
            'default' => ['width' => null, 'height' => null],
        ];
    }
}
