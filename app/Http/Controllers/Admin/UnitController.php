<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Address;
use Carbon\Carbon;
use Validator;
use Alert;
use App\Models\AttributeValue;
use App\Models\VariantAttributeValue;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Str;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = Attribute::query();
        if (!empty($request->search)) {
            $data = $data->where(function ($query) use ($request) {

                return $query
                    ->where(function ($query) use ($request) {
                        return $query
                            ->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere(function ($query) use ($request) {
                        return $query
                            ->where('email', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere(function ($query) use ($request) {
                        return $query
                            ->where('mobile', 'like', '%' . $request->search . '%');
                    });
            });
        }
        $units = $data->latest()->paginate(1000);
        return view('admin.catalog.units.all', compact('units'));
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
        $attribute = new Attribute();
        $attribute->name = $request->name;
        // generate slug from name
        $slug = Str::slug($request->name);

        // ensure uniqueness by checking DB
        $original = $slug;
        $count = 1;
        while (Attribute::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        $attribute->slug = $slug;
        $attribute->status  = $request->has('status') ? 'show' : 'hide';
        $attribute->save();

        Alert::toast('Unit created Succesfully', 'success');
        return redirect(route('admin.units.index'));
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
        $unit = Attribute::find($id);

        if (!$unit) {
            return response()->json(['error', 'Unit not found'], 404);
        }

        return response()->json([
            'unit' => $unit,
            'update_url' => route('admin.units.update', [$unit->id])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $unit = Attribute::find($id);

        if (!$unit) {
            return response()->json(['error', 'Unit not found'], 404);
        }
        $unit->name = $request->name;
        $unit->save();
        Alert::toast("Unit updated successfully", "success");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $unit = Attribute::where('id', $id)->first();
        if (!isset($unit->id)) {
            Alert::toast("Details Not Found", 'warning');
            return response()->json([
                'success' => false,
                'message' => 'Brand deleted Failed'
            ]);
        }
        $variants = VariantAttributeValue::where('attribute_id', $unit->id)->count();
        if ($variants == 0) {
            $attributeValues = AttributeValue::where('attribute_id', $unit->id)->delete();
            $unit->delete();
            Alert::toast("Deleted Successfully", 'success');

            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully'
            ]);
        } else {
            Alert::toast("Cant Delete Right Now", 'warning');
            return response()->json([
                'success' => false,
                'message' => 'Brand deleted Failed'
            ]);
        }
    }
}
