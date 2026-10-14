<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Validator;
use Alert;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = Category::query();
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
        $categories = $data->latest()->paginate(10);
        return view('admin.catalog.category.all', compact('categories'));
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
        $category = new Category();
        $category->title = $request->title;
        $category->slug = $this->slugGenerate($request->title, 0);
        $category->description = $request->description;
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('category'), $fileName);

            $category->image = 'category/' . $fileName;
        }
        $category->parent_id = $request->category_id ?? 0;
        $category->status = $request->has('status') ? 'show' : 'hide';

        $category->is_feature = $request->has('is_feature') ? 'yes' : 'no';
        $category->save();

        Alert::toast('Category created Succesfully', 'success');
        return redirect(route('admin.categories.index'));
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
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error', 'Category not found'], 404);
        }

        return response()->json([
            'category' => $category,
            'update_url' => route('admin.categories.update', [$category->id])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = Category::find($id);

        if (!$category) {
            Alert::toast("Category Details Not Found", 'warning');
            return redirect()->back();
        }
        $title = $category->title;
        $category->title = $request->title;
        if ($title != $request->title) {
            $category->slug = $this->slugGenerate($request->title, 0);
        }
        $category->description = $request->description;
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('category'), $fileName);

            $category->image = 'category/' . $fileName;
        }
        $category->parent_id = $request->category_id ?? 0;
        $category->status = $request->has('status') ? 'show' : 'hide';
        $category->is_feature = $request->has('is_feature') ? 'yes' : 'no';
        $category->save();

        Alert::toast('Category Updated Succesfully', 'success');
        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        // If category has children, delete them or restrict
        if ($category->children()->count()) {
            return response()->json(['error' => 'Category has subcategories, cannot delete.'], 422);
        }

        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();
        Alert::toast("Deleted Successfully", 'success');
        return response()->json(['success' => true]);
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
        $checkSlug = Category::where('slug', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }
}
