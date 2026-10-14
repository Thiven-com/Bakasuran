<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Models\Address;
use Carbon\Carbon;
use Validator;
use Alert;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = AttributeValue::query();
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
        $attributes = $data->latest()->paginate(1000);
        return view('admin.catalog.attributes.all', compact('attributes'));
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
        $attribute = new AttributeValue();
        $attribute->name = $request->name;
        // generate slug from name
        $slug = Str::slug($request->name);

        // ensure uniqueness by checking DB
        $original = $slug;
        $count = 1;
        while (AttributeValue::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        $attribute->slug = $slug;
        $attribute->attribute_id = $request->unit_id ?? 0;
        $attribute->save();

        Alert::toast('Attribute created Succesfully', 'success');
        return redirect(route('admin.attributes.index'));
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addValue(Request $request)
    {
        $value = trim($request->value);

        // check duplicate
        $exists = AttributeValue::where('attribute_id', $request->attribute_id)
            ->where('name', $value)
            ->first();

        if ($exists) {
            return response()->json(['error' => 'exists']);
        }

        $new = AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'name' => $value,
            'slug' => $this->generateUniqueSlug($value)
        ]);

        return response()->json($new);
    }

    function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (AttributeValue::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
