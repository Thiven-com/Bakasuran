<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Models\ProductMedia;
use App\Models\VariantAttributeValue;
use Illuminate\Support\Facades\File;
use App\Models\Address;
use Carbon\Carbon;

use App\Models\CartItem;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Restaurant;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $user = Auth::guard('admin')->user();

        $data = Product::query();
        if (!empty($request->search)) {
            $data = $data->where(function ($query) use ($request) {

                return $query
                    ->where(function ($query) use ($request) {
                        return $query
                            ->where('title', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere(function ($query) use ($request) {
                        return $query
                            ->where('hsn_code', 'like', '%' . $request->search . '%');
                    });
            });
        }
        if (!empty($from)) {
            try {
                $data->whereDate('created_at', '>=', Carbon::parse($from)->toDateString());
            } catch (\Throwable $e) {
            }
        }
        if (!empty($to)) {
            try {
                $data->whereDate('created_at', '<=', Carbon::parse($to)->toDateString());
            } catch (\Throwable $e) {
            }
        }
        $products = $data->latest()->get();
        return view('admin.catalog.products.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::guard('admin')->user();
        $brands = Brand::where('status', 'show')->get();
        $categories = Category::where(['status' => 'show', 'parent_id' => 0])->get();
        $variantAttributes = Attribute::with('attributeValues')->where('status', 'show')->get();
        // Load active restaurants
        $restaurants = Restaurant::where('is_active', true)->orderBy('name')->get();
        // $taxes = Tax::where('status', 'Show')->where('company_id',$user->id)->get();
        return view('admin.catalog.products.create', compact('categories', 'brands', 'variantAttributes', 'restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_attributes' => 'required|array',
            'variant_attributes.*' => 'required',
            'hsn_code' => 'required|string|unique:products,hsn_code', // ensure HSN code is unique
            'variants.*.sku' => 'required|string|distinct|unique:product_variants,sku',
        ]);
        if ($validator->fails()) {
            Alert::toast($validator->errors()->first(), 'warning');
            return redirect()->back()->withInput();
        }
        $product = new Product();
        $product->title = $request->title;
        $product->slug = $this->slugGenerate($request->title, 0);
        $product->description = $request->description;
        $product->short_description = $request->short_description;

        $manager = new ImageManager(new Driver());

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('media/products/'), $fileName);

            $product->image = 'media/products/' . $fileName;
        }

        $product->category_id = $request->category_id ?? 0;
        $product->brand_id = $request->brand_id ?? 0;
        $product->status = $request->status;
        $product->restaurant_id = $request->restaurant_id ?: null;
        $product->hsn_code = $request->hsn_code;
        $product->is_feature = $request->has('is_feature') ? 'yes' : 'no';
        $product->save();
        if ($request->has('subcategories')) {
            $product->subcategories()->sync($request->subcategories);
        }

        $variantAttributes = $request->variant_attributes;
        $attributeValues = [];

        foreach ($variantAttributes as $attributeId => $valueNames) {
            foreach ($valueNames as $valueName) {
                $value = AttributeValue::where('name', $valueName)->where('attribute_id', $attributeId)->first();
                if ($value) {
                    $attributeValues[$attributeId][] = [
                        'attribute_id' => $attributeId,
                        'attribute_name' => Attribute::find($attributeId)->name,
                        'value_id' => $value->id,
                        'value_name' => $value->name,
                    ];
                }
            }
        }

        $combinations = $this->cartesian(array_values($attributeValues));
        $variantData = $request->variants;

        foreach ($combinations as $combo) {
            $comboNames = array_column($combo, 'value_name');

            $variantKey = implode('_', $comboNames);

            if (!isset($variantData[$variantKey]))
                continue;

            $variantInput = $variantData[$variantKey];

            $skuParts = [$product->slug];
            foreach ($combo as $entry) {
                $skuParts[] = strtoupper($entry['value_name']);
            }
            $sku = strtoupper(implode('_', $skuParts));

            $imagePath = null;
            if ($request->hasFile('variant_image')) {

                $file = $request->file('variant_image');

                $imageName = time() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('media/variants/'), $imageName);

                $imagePath = 'media/variants/' . $imageName;
            }

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'sku' => $variantInput['sku'],
                'slug' => $sku,
                'product_slug' => $product->slug,
                'actual_price' => $variantInput['actual_price'],
                'price' => $variantInput['sale_price'],
                'weight' => $variantInput['weight'],
                'preorder' => $variantInput['preorder'],
                'preorder_stock' => $variantInput['preorder_stock'],
                'stock' => $variantInput['stock'],
                'low_stock_alert' => $variantInput['low_stock_alert'],
                'status' => $variantInput['status'],
                'product_min_order' => $variantInput['product_min_order'],
                'product_max_order' => $variantInput['product_max_order'],
                'height' => $variantInput['height'] ?? 0,
                'width' => $variantInput['width'] ?? 0,
                'length' => $variantInput['length'] ?? 0,
                'image' => $imagePath,
            ]);

            foreach ($combo as $entry) {
                VariantAttributeValue::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $entry['attribute_id'],
                    'attribute_value_id' => $entry['value_id'],
                ]);
            }
        }

        Alert::toast('Product created successfully', 'success');
        return redirect(route('admin.products.index'));
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:products,id',
            'status' => 'required|in:show,hide',
        ]);

        Product::whereIn('id', $request->ids)->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Selected products status updated successfully.'
        ]);
    }


    private function cartesian($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['brand', 'category', 'subcategories', 'variants'])->findOrFail($id);
        return view('admin.catalog.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::where(['status' => 'show', 'parent_id' => 0])->get();
        $brands = Brand::all();
        $variantAttributes = Attribute::with('attributeValues')->get();
        $selectedSubcategories = $product->subcategories->pluck('id')->toArray();
        // Load active restaurants
        $restaurants = Restaurant::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.catalog.products.edit', compact('product', 'categories', 'brands', 'variantAttributes', 'selectedSubcategories', 'restaurants'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'restaurant_id' => 'nullable|integer|exists:restaurants,id',
        ]);
        $product->title = $request->title;
        if ($product->title != $request->title) {
            $product->slug = $this->slugGenerate($request->title, 0);
        }
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->category_id = $request->category_id ?? 0;
        $product->brand_id = $request->brand_id ?? 0;
        $product->restaurant_id = $request->restaurant_id ?: null;
        $product->status = $request->status;
        // $product->hsn_code = $request->hsn_code;
        $product->is_feature = $request->has('is_feature') ? 'yes' : 'no';

        $manager = new ImageManager(new Driver());

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('media/products/'), $fileName);

            $product->image = 'media/products/' . $fileName;
        }

        $product->save();

        $existingVariants = $request->existing_variants ?? [];
        $dbVariantIds = ProductVariant::where('product_id', $product->id)
            ->pluck('id')
            ->toArray();

        $requestVariantIds = array_column($existingVariants, 'id');
        $deletedVariantIds = array_diff($dbVariantIds, $requestVariantIds);

        if (!empty($deletedVariantIds)) {
            VariantAttributeValue::whereIn('product_variant_id', $deletedVariantIds)->delete();
            ProductVariant::whereIn('id', $deletedVariantIds)->delete();
        }

        // --- Variant Galleries ---
        $variantGalleries = $request->file('variant_gallery', []);   // uploaded files
        $keepVariantGallery = $request->input('keep_variant_gallery', []); // keep existing ids

        foreach ($product->variants as $variant) {
            $keepIds = $keepVariantGallery[$variant->id] ?? [];

            // Delete removed images for this variant
            \App\Models\ProductMedia::where('product_variant_id', $variant->id)
                ->whereNotIn('id', $keepIds)
                ->delete();

            // Add new uploads
            if (isset($variantGalleries[$variant->id])) {
                foreach ($variantGalleries[$variant->id] as $file) {
                    $path = $file->store('products/variants', 'public');

                    $media = new \App\Models\ProductMedia();
                    $media->product_id = $product->id;
                    $media->product_variant_id = $variant->id;
                    $media->url = $path;
                    $media->type = 'image';
                    $media->save();
                }
            }
        }

        if (!empty($existingVariants)) {
            foreach ($existingVariants as $exVariant) {
                $variantModel = ProductVariant::find($exVariant['id']);
                if ($variantModel) {
                    $var = VariantAttributeValue::where('product_variant_id', $variantModel->id)->first();
                    $sku = $product->slug . '_' . $var->value->name ?? '';
                    if (
                        isset($exVariant['image']) &&
                        $exVariant['image'] instanceof \Illuminate\Http\UploadedFile
                    ) {
                        $skuFileName = strtolower(
                            preg_replace('/[^A-Za-z0-9\-_]/', '_', $variantModel->sku)
                        ) . '.webp';

                        $directory = public_path('media/variants');

                        // Create the directory if it doesn't exist
                        if (!File::exists($directory)) {
                            File::makeDirectory($directory, 0755, true);
                        }

                        $img = $manager->decodeSplFileInfo($exVariant['image']);
                        $img->resize(450, 600);

                        $img->encodeUsingFormat(
                            \Intervention\Image\Format::WEBP,
                            quality: 100
                        )->save($directory . DIRECTORY_SEPARATOR . $skuFileName);

                        $variantModel->image = 'media/variants/' . $skuFileName;
                    }
                    if (isset($exVariant['video']) && $exVariant['video'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $exVariant['video'];
                        // Use original extension
                        $extension = $file->getClientOriginalExtension();

                        // Create filename like SKU.mp4
                        $variantVideoName = $product->slug . "_" . strtoupper($variantModel->id) . '.' . $extension;

                        // Delete old video if exists
                        if ($variantModel->video && file_exists(public_path($variantModel->video))) {
                            unlink(public_path($variantModel->video));
                        }

                        // Move file
                        $file->move(public_path('media/variants/'), $variantVideoName);
                        $variantModel->video = 'media/variants/' . $variantVideoName;
                    }
                    if (isset($exVariant['promotional_video']) && $exVariant['promotional_video'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $exVariant['promotional_video'];
                        // Use original extension
                        $extension = $file->getClientOriginalExtension();

                        // Create filename like SKU.mp4
                        $variantVidName = $product->slug . "_" . strtoupper($variantModel->id) . '.' . $extension;

                        // Delete old video if exists
                        if ($variant->promotional_video && file_exists(public_path($variant->promotional_video))) {
                            unlink(public_path($variant->promotional_video));
                        }

                        // Move file
                        $file->move(public_path('media/variants/promotional/'), $variantVidName);
                        $variantModel->promotional_video = 'media/variants/promotional/' . $variantVidName;
                    }

                    $variantModel->actual_price = $exVariant['actual_price'];
                    $variantModel->price = $exVariant['sale_price'];
                    $variantModel->weight = $exVariant['weight'];
                    $variantModel->preorder = $exVariant['preorder'];
                    $variantModel->preorder_stock = $exVariant['preorder_stock'];
                    $variantModel->height = $exVariant['height'];
                    $variantModel->width = $exVariant['width'];
                    $variantModel->length = $exVariant['length'];
                    $variantModel->stock = $exVariant['stock'];
                    $variantModel->status = $exVariant['status'];
                    $variantModel->category_id = $product->category_id;
                    $variantModel->product_slug = $product->slug;
                    // $variantModel->slug = $sku;
                    // $variantModel->sku = $exVariant['sku'];
                    $variantModel->low_stock_alert = $exVariant['low_stock_alert'];
                    $variantModel->product_min_order = $exVariant['product_min_order'];
                    $variantModel->product_max_order = $exVariant['product_max_order'];
                    $variantModel->save();
                }
            }
        }

        $variantAttributes = $request->variant_attributes ?? [];
        $attributeValues = [];

        foreach ($variantAttributes as $attributeId => $valueNames) {
            foreach ($valueNames as $valueName) {
                $value = AttributeValue::where('name', $valueName)
                    ->where('attribute_id', $attributeId)
                    ->first();
                if ($value) {
                    $attributeValues[$attributeId][] = [
                        'attribute_id' => $attributeId,
                        'attribute_name' => Attribute::find($attributeId)->name,
                        'value_id' => $value->id,
                        'value_name' => $value->name,
                    ];
                }
            }
        }

        $combinations = $this->cartesian(array_values($attributeValues));
        $variantData = $request->variants ?? [];

        foreach ($combinations as $combo) {
            $comboNames = array_column($combo, 'value_name');
            $variantKey = implode('_', $comboNames);

            if (!isset($variantData[$variantKey]))
                continue;

            $variantInput = $variantData[$variantKey];
            $newSku = $variantInput['sku'];

            // Check uniqueness
            if (ProductVariant::where('sku', $newSku)->exists()) {
                Alert::toast("SKU {$newSku} already exists", 'error');
                return redirect()->back()->withInput();
            }
            $skuParts = [$product->slug];
            foreach ($combo as $entry) {
                $skuParts[] = strtoupper($entry['value_name']);
            }
            $sku = strtoupper(implode('_', $skuParts));

            $imagePath = null;
            if ($request->hasFile('variant_image')) {

                $file = $request->file('variant_image');

                $imageName = time() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('media/variants/'), $imageName);

                $imagePath = 'media/variants/' . $imageName;
            }
            $videoPath = null;

            if (isset($variantInput['video']) && $variantInput['video'] instanceof \Illuminate\Http\UploadedFile) {

                $file = $variantInput['video'];
                // Use original extension
                $extension = $file->getClientOriginalExtension();

                // Create filename like SKU.mp4
                $variantVideoName = $sku . '.' . $extension;

                // Delete old video if exists
                if ($variant->video && file_exists(public_path($variant->video))) {
                    unlink(public_path($variant->video));
                }

                // Move file
                $file->move(public_path('media/variants'), $variantVideoName);
                $videoPath = 'media/variants/' . $variantVideoName;
            }

            $vidPath = null;

            if (isset($variantInput['promotional_video']) && $variantInput['promotional_video'] instanceof \Illuminate\Http\UploadedFile) {

                $file = $variantInput['promotional_video'];
                // Use original extension
                $extension = $file->getClientOriginalExtension();

                // Create filename like SKU.mp4
                $varVidName = $sku . '.' . $extension;

                // Delete old video if exists
                if ($variant->promotional_video && file_exists(public_path($variant->promotional_video))) {
                    unlink(public_path($variant->promotional_video));
                }

                // Move file
                $file->move(public_path('media/variants/promotional/'), $varVidName);
                $vidPath = 'media/variants/promotional/' . $varVidName;
            }


            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'slug' => $sku,
                'sku' => $variantInput['sku'],
                'product_slug' => $product->slug,
                'actual_price' => $variantInput['actual_price'],
                'price' => $variantInput['sale_price'],
                'weight' => $variantInput['weight'],
                'preorder' => $variantInput['preorder'],
                'preorder_stock' => $variantInput['preorder_stock'],
                'stock' => $variantInput['stock'],
                'status' => $variantInput['status'],
                'low_stock_alert' => $variantInput['low_stock_alert'],
                'product_min_order' => $variantInput['product_min_order'],
                'product_max_order' => $variantInput['product_max_order'],
                'image' => $imagePath,
                'video' => $videoPath,
                'promotional_video' => $vidPath,
                'height' => $variantInput['height'],
                'width' => $variantInput['width'],
                'length' => $variantInput['length'],
            ]);

            foreach ($combo as $entry) {
                VariantAttributeValue::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $entry['attribute_id'],
                    'attribute_value_id' => $entry['value_id'],
                ]);
            }
        }

        if ($request->has('subcategories')) {
            $product->subcategories()->sync($request->subcategories);
        } else {
            $product->subcategories()->sync([]);
        }


        Alert::toast('Product updated successfully', 'success');
        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function olddestroy(string $id)
    {
        $product = Product::with(['variants', 'media'])->find($id);

        DB::beginTransaction();
        try {
            // 1) Delete main product image file if exists (you store as 'media/products/xxx.webp')
            if (!empty($product->image)) {
                $publicPath = public_path($product->image);
                if (file_exists($publicPath)) {
                    @unlink($publicPath);
                }
            }

            // 2) Delete variants and their files + variant attribute values
            $variantIds = $product->variants->pluck('id')->toArray();

            foreach ($product->variants as $variant) {
                // variant main image (you store as 'media/variants/xxx.webp' in public)
                if (!empty($variant->image)) {
                    $publicPath = public_path($variant->image);
                    if (file_exists($publicPath)) {
                        @unlink($publicPath);
                    }
                }

                // Variant gallery media stored in ProductMedia (could be 'storage' path or 'media/...' path)
                $variantMedias = ProductMedia::where('product_variant_id', $variant->id)->get();
                foreach ($variantMedias as $vm) {
                    if (!empty($vm->url)) {
                        // If url starts with 'products/' it was stored with Storage::disk('public')->put(...)
                        if (Storage::disk('public')->exists($vm->url)) {
                            Storage::disk('public')->delete($vm->url);
                        } else {
                            // try public_path
                            $filePath = public_path($vm->url);
                            if (file_exists($filePath)) {
                                @unlink($filePath);
                            }
                        }
                    }
                    $vm->delete();
                }

                // Delete VariantAttributeValue rows for this variant
                VariantAttributeValue::where('product_variant_id', $variant->id)->delete();

                // Finally delete the variant
                $variant->delete();
            }

            // 3) Delete product-level media entries (if any)
            $productMedias = ProductMedia::where('product_id', $product->id)->get();
            foreach ($productMedias as $pm) {
                if (!empty($pm->url)) {
                    if (Storage::disk('public')->exists($pm->url)) {
                        Storage::disk('public')->delete($pm->url);
                    } else {
                        $filePath = public_path($pm->url);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
                $pm->delete();
            }

            // 4) Detach subcategories relation (if pivot)
            if (method_exists($product, 'subcategories')) {
                $product->subcategories()->sync([]);
            }

            // 5) Delete any other related models if necessary (example: attribute values on product level)
            // If you have other relations to cleanup, do them here.

            // 6) Delete product record
            $product->delete();

            DB::commit();

            Alert::toast('Product deleted successfully', 'success');
            return redirect()->route('admin.products.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Product delete error: ' . $e->getMessage(), ['product_id' => $id]);
            Alert::toast('Unable to delete product: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
    public function destroy(string $id)
    {
        $product = Product::with(['variants', 'media'])->find($id);

        if (!$product) {
            Alert::toast('Product not found', 'error');
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            // 1) Delete main product image
            if (!empty($product->image)) {
                $productImagePath = public_path($product->image);
                if (file_exists($productImagePath)) {
                    @unlink($productImagePath);
                }
            }

            // 2) Delete variants
            foreach ($product->variants as $variant) {
                // Delete variant main image
                if (!empty($variant->image)) {
                    $variantImagePath = public_path($variant->image);
                    if (file_exists($variantImagePath)) {
                        @unlink($variantImagePath);
                    }
                }

                CartItem::where('product_variant_id', $variant->id)->delete();
                WishlistItem::where('product_variant_id', $variant->id)->delete();

                // Delete variant media
                $variantMedias = ProductMedia::where('product_variant_id', $variant->id)->get();
                foreach ($variantMedias as $vm) {
                    if (!empty($vm->url)) {
                        $filePath = public_path($vm->url);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                    $vm->delete();
                }

                // Delete variant attribute values
                VariantAttributeValue::where('product_variant_id', $variant->id)->delete();

                // Delete variant itself
                $variant->delete();
            }

            // 3) Delete product media
            foreach ($product->media as $pm) {
                if (!empty($pm->url)) {
                    $filePath = public_path($pm->url);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
                $pm->delete();
            }

            // 4) Detach subcategories (pivot table)
            if (method_exists($product, 'subcategories')) {
                $product->subcategories()->detach();
            }

            // 5) Delete product record
            $product->delete();

            DB::commit();

            Alert::toast('Product deleted successfully', 'success');
            return redirect()->route('admin.products.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Product delete error: ' . $e->getMessage(), ['product_id' => $id]);
            Alert::toast('Unable to delete product: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
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
        $checkSlug = Product::where('slug', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }

    public function getAttributeValues($unitId)
    {
        $attributeValues = AttributeValue::where('attribute_id', $unitId)->get();

        return response()->json($attributeValues);
    }

    public function delete_media($id)
    {
        $media = ProductMedia::findOrFail($id);

        // delete file from public path if exists
        $filePath = public_path($media->url);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $media->delete();

        return response()->json(['success' => true, 'message' => 'Media deleted']);
    }

    public function todayDeals(Request $request)
    {
        $data = ProductVariant::where('today_deal', 'yes');
        // if (!empty($request->search)) {
        //     $data = $data->where(function ($query) use ($request) {

        //         return $query
        //             ->where(function ($query) use ($request) {
        //                 return $query
        //                     ->where('title', 'like', '%' . $request->search . '%');
        //             });
        //     });
        // }
        $data = $data->latest()->get();

        $variants = ProductVariant::where('status', 'show')->where('today_deal', 'no')->get();
        return view('admin.catalog.products.today_deals', compact('variants', 'data'));
    }

    public function updateTodayDeal(Request $request)
    {
        $variant = ProductVariant::where('id', $request->variant_id)->first();
        if (!isset($variant->id)) {
            Alert::toast("Variant Details Not Found", 'warning');
            return redirect(route('admin.todayDeals'));
        }
        $variant->today_deal = 'yes';
        $variant->save();
        Alert::toast("Deal Added Successfully", 'success');
        return redirect(route('admin.todayDeals'));
    }

    public function deleteTodaySale($id)
    {
        $variation = ProductVariant::where('id', $id)->first();

        if (!$variation) {
            return response()->json([
                'status' => false,
                'message' => 'Sale not found'
            ]);
        }
        $variation->today_deal = 'no';
        $variation->save();
        // Update today_sale_no
        // $variation->update([
        //     'today_deal' => 'no'   // or 0 based on your DB structure
        // ]);

        return response()->json([
            'status' => true,
            'message' => 'Today sale removed successfully'
        ]);
    }


    public function deleteVideo(Request $request)
    {
        $variant = ProductVariant::find($request->id);

        if (!$variant) {
            return response()->json(['success' => false]);
        }

        if ($request->type === 'video') {
            if ($variant->video && file_exists(public_path($variant->video))) {
                unlink(public_path($variant->video));
            }
            $variant->video = null;
        }

        if ($request->type === 'promotional_video') {
            if ($variant->promotional_video && file_exists(public_path($variant->promotional_video))) {
                unlink(public_path($variant->promotional_video));
            }
            $variant->promotional_video = null;
        }

        $variant->save();

        return response()->json(['success' => true]);
    }

    public function bulkProductUpdate(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        if (!isset($product->id)) {
            Alert::toast("Product Details Not Found", 'warning');
            return redirect(route('admin.products.index'));
        }
        $updateData = [];
        if ($request->filled('price')) {
            $updateData['price'] = $request->price;
        }
        if ($request->filled('actual_price')) {
            $updateData['actual_price'] = $request->actual_price;
        }
        if ($request->filled('height')) {
            $updateData['height'] = $request->height;
        }
        if ($request->filled('width')) {
            $updateData['width'] = $request->width;
        }
        if ($request->filled('length')) {
            $updateData['length'] = $request->length;
        }
        if ($request->filled('weight')) {
            $updateData['weight'] = $request->weight;
        }
        if ($request->filled('stock')) {
            $updateData['stock'] = $request->stock;
        }
        if ($request->filled('preorder_stock')) {
            $updateData['preorder_stock'] = $request->preorder_stock;
        }
        if ($request->filled('preorder')) {
            $updateData['preorder'] = $request->preorder;
        }
        if ($request->filled('low_stock_alert')) {
            $updateData['low_stock_alert'] = $request->low_stock_alert;
        }
        if ($request->filled('product_min_order')) {
            $updateData['product_min_order'] = $request->product_min_order;
        }
        if ($request->filled('product_max_order')) {
            $updateData['product_max_order'] = $request->product_max_order;
        }
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        if (!empty($updateData)) {
            ProductVariant::where('product_id', $product->id)->update($updateData);
        }
        Alert::toast("Product Details Updated Successfully", 'success');
        return redirect()->back();
    }
}
