<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Alert;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogCategories = BlogCategory::get();
        return view('admin.blogcategory.all', compact('blogCategories'));
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
        $blogCat = new BlogCategory();
        $blogCat->parent_id = $request->parent_id ?? 0;
        $blogCat->name = $request->name;
        $blogCat->description = $request->description;
        if($request->hasFile('icon')) {
            $blogCat->icon = $request->icon->store('blogcategory');
        }
        if($request->hasFile('image')) {
            $blogCat->image = $request->image->store('blogcategory');
        }

        $blogCat->status = $request->status;
        $blogCat->save();

        Alert::toast("Blog Category saved successfully", "success");
        return redirect()->back();
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
         $blog_category = BlogCategory::find($id);

        if(!$blog_category) {
            return response()->json(['error', 'Blog Category not found'], 404);
        }

        return response()->json([
            'blog_category' => $blog_category,
            'update_url' => route('admin.blog.categories.update', [$blog_category->id])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $blog_category = BlogCategory::where('id', $id)->first();
        $blog_category->parent_id = $request->edit_parent_id;
        $blog_category->name = $request->name;
        $blog_category->description = $request->description;
        if($request->hasFile('icon')) {
           $blog_category->icon = $request->icon->store('blogcategory');
        }
        if($request->hasFile('image')) {
           $blog_category->image = $request->image->store('blogcategory');
        }
        $blog_category->status = $request->status;
        $blog_category->save();

        Alert::toast("Blog Category updated successfully", "success");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blogCategory = BlogCategory::findOrFail($id);

        if($blogCategory) {
            $blogCategory->delete();
            return response()->json([
                'status' => true,
                'message' => 'Blog Category deleted successfully'
            ]);

        }
    }
}
