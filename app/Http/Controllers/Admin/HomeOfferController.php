<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeOffer;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class HomeOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = HomeOffer::get();

        return view('admin.home_offer.all', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::get();

        return view('admin.home_offer.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ---------------- VALIDATION ----------------
        $validation = [
            'title' => 'required|max:255',
            'product_ids' => 'required|array',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'offer' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            Alert::toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        $offer = new HomeOffer();
        // ---------------- SAVE IMAGE ----------------
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('homeoffer');
            $offer->image = $path;
        }

        // ---------------- UPDATE DATA ----------------
        $offer->title = $request->title;
        $offer->slug = $this->slugGenerate($request->title, 0);
        $offer->offer = $request->offer;
        $offer->product_ids = json_encode($request->product_ids) ?? [];
        $offer->save();
        Alert::toast('Home Offer Added successfully', 'success');
        return redirect(route('admin.homeoffers.index'));
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
        $homeOffer = HomeOffer::findOrFail($id);
        $products = Product::all();

        return view('admin.home_offer.edit', compact('homeOffer', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $homeOffer = HomeOffer::findOrFail($id);

        $homeOffer->title = $request->title;
        $homeOffer->offer = $request->offer;

        if ($request->hasFile('image')) {
            if ($homeOffer->image) {
                Storage::delete($homeOffer->image);
            }

            $path = $request->file('image')->store('homeoffer');
            $homeOffer->image = $path;
        }
        $homeOffer->product_ids = json_encode($request->product_ids) ?? [];
        $homeOffer->save();

        // Sync products

        return redirect()->route('admin.homeoffers.index')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function slugGenerate($name, $count)
    {

        if (preg_match("/[\/\\\^£$%&*()}{@#~?><>,|=_+¬-]/", $name)) {
            $name = str_replace(']', '_', $name);
            $name = str_replace('[', '_', $name);
            $name = str_replace("'", '_', $name);
            $pattern = "/[\/\\\^£$%&*()}{@#~?><>,|=_+¬-]/";
            $replacement = "_";

            // Replace special characters with underscores
            $name = preg_replace($pattern, $replacement, $name);
        }
        $slug = ($count == 0) ? strtolower(str_replace(' ', '_', $name)) : strtolower(str_replace(' ', '_', $name)) . $count;

        // Check for uniqueness
        $checkSlug = HomeOffer::where('slug', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }
}
