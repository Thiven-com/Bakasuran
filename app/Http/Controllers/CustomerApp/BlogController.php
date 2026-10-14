<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogCategoryCollection;
use App\Http\Resources\BlogCollection;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //
    public function blogs(Request $request)
    {
        $blogs = Blog::query();
        if (!empty($request->id)) {
            $blogs = $blogs->where('id', $request->id);
        }
        if (!empty($request->category_id)) {
            $blogs = $blogs->where('category_id', $request->category_id);
        }
        $blogs = $blogs->latest()->paginate(24);
        $data = new BlogCollection($blogs);
        if (count($data) > 0) {
            return response()->json([
                'success' => 1,
                'data' => $data,
                'message' => "Data Fetched Successfully"
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => "No Data Found"
            ]);
        }
    }

    public function blogCategories(Request $request)
    {
        $categories = BlogCategory::get();
        $data = new BlogCategoryCollection($categories);
        if (count($data) > 0) {
            return response()->json([
                'success' => 1,
                'data' => $data,
                'message' => "Data Fetched Successfully"
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => "No Data Found"
            ]);
        }
    }
}
