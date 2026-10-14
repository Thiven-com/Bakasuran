<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Validator;

use App\Models\Category;
use App\Models\HomeOffer;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = Banner::query();
        if (!empty($request->search)) {
            $data = $data->where(function ($query) use ($request) {

                return $query
                    ->where(function ($query) use ($request) {
                        return $query
                            ->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $brands = $data->latest()->paginate(10);

        $offers = HomeOffer::get();
        // $categories = Category::get();
        return view('admin.banners.all', compact('brands', 'offers'));
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
        $count = Banner::where('title', $request->title)->count();
        if ($count > 0) {
            Alert::toast('Name Already Exists', 'warning');
            return redirect(route('admin.banners.index'));
        }
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->category_id = $request->category_id;

        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('banners'), $fileName);

            $banner->image = 'banners/' . $fileName;
        }
        $banner->status = $request->has('status') ? 'show' : 'hide';
        $banner->offer_slug = $request->offer_slug;
        $banner->save();

        Alert::toast('Banner created Succesfully', 'success');
        return redirect(route('admin.banners.index'));
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
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['error', 'Banner not found'], 404);
        }

        return response()->json([
            'banner' => $banner,
            'update_url' => route('admin.banners.update', [$banner->id])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $banner = Banner::find($id);

        if (!$banner) {
            Alert::toast("Brand Details Not Found", 'warning');
            return redirect()->back();
        }
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('banners'), $fileName);

            $banner->image = 'banners/' . $fileName;
        }
        $banner->status = $request->has('status') ? 'show' : 'hide';
        $banner->category_id = $request->category_id;
        $banner->offer_slug = $request->offer_slug;
        $banner->save();

        Alert::toast('Banner Updated Succesfully', 'success');
        return redirect(route('admin.banners.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully'
        ]);
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
        $checkSlug = Banner::where('title', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }
}
