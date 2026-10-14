<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::get();
        return view('admin.blogs.all', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blog_categories = BlogCategory::get();
        return view('admin.blogs.create', compact('blog_categories'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $blog = new Blog();
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        if (!empty($request->title)) {
            $request->merge(['slug' => strtolower(str_replace(' ', '_', $request->title))]);

            $slugcheck = Blog::where('slug', $request->slug)->get();

            if (count($slugcheck) > 0) {
                $request->slug = $request->slug . rand(1, 9999);
            }
        }
        $blog->slug = $request->slug;
        $blog->short_description = $request->short_description;
        $blog->description = $request->description;
        $blog->tags = $request->tags;
        $blog->status = $request->status;
        $blog->facebook = $request->facebook;
        $blog->instagram = $request->instagram;
        $blog->youtube = $request->youtube;
        $manager = new ImageManager(new Driver());

        // if ($request->hasFile('image')) {
        //     $productImage = $request->file('image');
        //     $productName = $request->slug . ".webp";
        //     $image = $manager->read($productImage);
        //     $image->resize(450, 600);
        //     $image->toWebp(70)->save(public_path('blogs/image/') . $productName);
        //     $blog->image = 'blogs/image/' . $productName;
        //     // $blog->image = $request->image->store('blogs');
        // }
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('blogs/image/'), $fileName);

            $blog->image = 'blogs/image/' . $fileName;
        }
        // if ($request->hasFile('banner')) {
        //     $productImage = $request->file('banner');
        //     $bannerName = $request->slug . ".webp";
        //     $image = $manager->read($productImage);
        //     $image->resize(850, 510);
        //     $image->toWebp(70)->save(public_path('blogs/banner/') . $bannerName);
        //     $blog->banner = 'blogs/banner/' . $bannerName;
        //     // $blog->banner = $request->banner->store('blogs');
        // }
        if ($request->hasFile('banner')) {

            $file = $request->file('banner');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('blogs/banner/'), $fileName);

            $blog->banner = 'blogs/banner/' . $fileName;
        }
        $blog->save();

        Alert::toast('Blog saved successfuuly', 'success');
        return redirect()->route('admin.blogs.index')->with('success', 'Blog Saved Successfully!');
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
        $blog = Blog::findOrFail($id);
        $blog_categories = BlogCategory::get();
        return view('admin.blogs.edit', compact('blog', 'blog_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::where('id', $id)->first();
        $blog->category_id = $request->category_id;
        if (!empty($request->title) && $request->title != $blog->title) {
            $request->merge(['slug' => strtolower(str_replace(' ', '_', $request->title))]);

            $slugcheck = Blog::where('slug', $request->slug)->get();

            if (count($slugcheck) > 0) {
                $request->slug = $request->slug . rand(1, 9999);
            }
        }
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->short_description = $request->short_description;
        $blog->description = $request->description;
        $blog->tags = $request->tags;
        $blog->status = $request->status;
        $blog->facebook = $request->facebook;
        $blog->instagram = $request->instagram;
        $blog->youtube = $request->youtube;
        $manager = new ImageManager(new Driver());

        // if ($request->hasFile('image')) {
        //     $blog->image = $request->image->store('blogs');
        // }
        // if ($request->hasFile('banner')) {
        //     $blog->banner = $request->banner->store('blogs');
        // }
        // UPDATE BLOG IMAGE
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $directory = public_path('blogs/image');

            if (!\Illuminate\Support\Facades\File::exists($directory)) {
                \Illuminate\Support\Facades\File::makeDirectory(
                    $directory,
                    0755,
                    true
                );
            }

            $fileName = time() . '_' .
                \Illuminate\Support\Str::random(8) . '.' .
                $file->getClientOriginalExtension();

            $file->move($directory, $fileName);

            $blog->image = 'blogs/image/' . $fileName;
        }


        // UPDATE BLOG BANNER
        if ($request->hasFile('banner')) {

            $file = $request->file('banner');

            $directory = public_path('blogs/banner');

            if (!\Illuminate\Support\Facades\File::exists($directory)) {
                \Illuminate\Support\Facades\File::makeDirectory(
                    $directory,
                    0755,
                    true
                );
            }

            $fileName = time() . '_' .
                \Illuminate\Support\Str::random(8) . '.' .
                $file->getClientOriginalExtension();

            $file->move($directory, $fileName);

            $blog->banner = 'blogs/banner/' . $fileName;
        }
        $blog->save();
        Alert::toast('Blog updated successfuuly', 'success');
        return redirect()->route('admin.blogs.index')->with('success', 'Blog Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog) {
            $blog->delete();
            return response()->json([
                'status' => true,
                'message' => 'Blog deleted successfully'
            ]);
        }
    }
}
