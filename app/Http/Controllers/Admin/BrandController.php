<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Carbon\Carbon;
use Validator;
use Alert;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = Brand::query()->withCount('products');
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
        return view('admin.catalog.brands.all', compact('brands'));
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
        $brand = new Brand();

        $brand->name = $request->name;
        $brand->slug = $this->slugGenerate($request->name, 0);

        $manager = new ImageManager(new Driver());

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('brands'), $fileName);

            $brand->image = 'brands/' . $fileName;
        }

        $brand->status = $request->has('status') ? 'show' : 'hide';

        $brand->save();

        Alert::toast('Brand created Successfully', 'success');

        return redirect()->route('admin.brands.index');
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
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['error', 'Brand not found'], 404);
        }

        return response()->json([
            'brand' => $brand,
            'update_url' => route('admin.brands.update', [$brand->id])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $brand = Brand::find($id);

        if (!$brand) {
            Alert::toast("Brand Details Not Found", 'warning');
            return redirect()->back();
        }
        $brand->name = $request->name;
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('brands'), $fileName);

            $brand->image = 'brands/' . $fileName;
        }
        $brand->status = $request->has('status') ? 'show' : 'hide';
        $brand->save();

        Alert::toast('Brand Updated Succesfully', 'success');
        return redirect(route('admin.brands.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && file_exists(public_path($brand->image))) {
            unlink(public_path($brand->image));
        }

        $brand->delete();
        Alert::toast("Deleted Successfully", 'success');

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully'
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
        $checkSlug = Brand::where('slug', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }
}
