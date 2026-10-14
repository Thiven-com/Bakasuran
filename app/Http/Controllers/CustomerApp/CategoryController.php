<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryCollection;
class CategoryController extends Controller
{

    public function categories(Request $request)
    {

        $categories = Category::query();
        if(!empty($request->category_id)){
            $categories->where('parent_id',$request->category_id);
        }else{
            $categories->where('parent_id',0);
        }

        if(!empty($request->featured)){
            $categories->where('is_featured','yes');
        }

        $categories = new CategoryCollection($categories->get());
        if (count($categories) > 0) {
                return response()->json([
                    'success' => 1,
                    'data' => $categories,
                    'message' => "Fetched Successfully."
                ]);
        } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'No Data Found'
                ]);
        }

    }
}
