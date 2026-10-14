@extends('layout.mainlayout')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
            @slot('title') Edit Product @endslot
            @slot('li_1') Edit product @endslot
            @slot('li_2') product-list @endslot
            @slot('li_3') Back to Product @endslot
            @endcomponent

            <style>
                .subcategory-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 10px;
                }

                .subcategory-grid label {
                    font-size: 16px;
                    color: black;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }
            </style>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        <h5><i data-feather="info"></i><span>Product Information</span></h5>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" name="title"
                                                        value="{{ $product->title }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label">Brand</label>
                                                <select class="select" name="brand_id">
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Category</label>
                                                    </div>
                                                    <select class="select" name="category_id" id="categorySelect"
                                                        required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}" data-title="{{ $cat->title }}" {{
                                                            $product->category_id == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                <label class="form-label">Primary Category</label>
                                                <input type="text" id="primaryCategory" class="form-control"
                                                    value="{{ $product->category->title ?? '' }}" readonly>
                                            </div>

                                            <div id="subcategorySection" class="mt-3 mb-3" style="display:none;">
                                                <input type="text" id="subcategorySearch" placeholder="Search here..."
                                                    class="form-control mb-2">
                                                <div id="subcategoryList" class="subcategory-grid"
                                                    style="max-height:200px; overflow-y:auto; border:1px solid #ccc; padding:10px;">
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="select">
                                                    <option value="show" {{ $product->status == 'show' ? 'selected' : '' }}>
                                                        Show</option>
                                                    <option value="hide" {{ $product->status == 'hide' ? 'selected' : '' }}>
                                                        Hide</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                                                        <label class="form-label">Product Code</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $product->hsn_code ?? '' }}" required>
                                                    </div>


                                            <div class="col-lg-12">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control h-100" rows="5" id="summernote"
                                                    name="description">{!! $product->description !!}</textarea>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="input-blocks summer-description-box transfer mb-3">
                                                    <label>Short Description</label>
                                                    <textarea class="form-control h-100" rows="2" minlength="20" maxlength="200" name="short_description">{!! $product->short_description !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mt-3">
                                                <label class="form-label">Product Image (450x600 px)</label>
                                                @if ($product->image)
                                                    <div class="mb-2">
                                                        <img src="{{ asset($product->image) }}" alt="Product Image" width="100">
                                                    </div>
                                                @endif
                                                <input type="file" name="image" class="form-control">
                                            </div>


                                            <div class="col-lg-6 mt-3">
                                                <label class="form-label">Is Featured</label><br>
                                                <input type="checkbox" name="is_feature" {{ $product->is_feature ? 'checked' : '' }}> Yes
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VARIANTS -->
                
                <div class="card mt-4">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span>Product Variants</span>
                                            </h5>
                                            <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="addservice-info">
                                            <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    <h5 class="mt-4 mb-3">Product Variants</h5>
                                                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal" style="float: inline-end;">
    Bulk Update Variants
</button>
                                                </div>
                                                             {{-- <input type="text" class="form-control mt-2 attribute-search" placeholder="Search..."> --}}

                                                <div class="row">
                                                    @foreach($variantAttributes as $attribute)
                                                        <div class="col-lg-12 mb-6 mt-3">
                                                            <div class="d-flex justify-content-between">
                                                                <label class="form-label fw-bold">{{$attribute->name }}</label>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-primary open-modal mx-2"
                                                                    data-attribute-id="{{ $attribute->id }}"
                                                                    data-attribute-name="{{ $attribute->name }}">
                                                                    + Add
                                                                </button>
                                                            </div>
                                                            @if (str_contains(strtolower($attribute->name), 'color'))
    <input type="text"
        class="form-control mt-2 attribute-search"
        placeholder="Search {{ $attribute->name }}..." style="width:min-content"
        data-attribute-id="{{ $attribute->id }}">
@endif
                                                            <div class="row attribute-values mt-2" data-attribute-id="{{ $attribute->id }}">
                                                                @foreach($attribute->attributeValues as $value)
                                                                    <div class="col-lg-2 attribute-item">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                name="variant_attributes[{{ $attribute->id }}][]"
                                                                                value="{{ $value->name }}"
                                                                                class="form-check-input variant-checkbox">
                                                                            <b class="text-dark">{{ $value->name }}</b>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div id="variantPreview" class="mt-4 mb-3" style="display: none;">
                                                    <h5>Variant Combinations:</h5>
                                                    <div id="variantList"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div id="variantPreview">
                                    <h6 class="mb-3">Existing Variants</h6>
                                    @foreach($product->variants as $variant)
                                        @php
                                            $orderQty = App\Models\OrderItem::where('product_variant_id', $variant->id)->whereHas('order', function($query) {$query->where('status', '!=', 'cancelled');})->sum('quantity');
                                            $addedPiece = ((int) $variant->stock + (int) $orderQty) ?? 0;
                                        @endphp
                                        <div class="border rounded p-3 mb-3 variant-box">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6>{{ $variant->sku }}</h6>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-variant">Remove</button>
                                                <input type="hidden" name="existing_variants[{{ $variant->id }}][id]"
                                                    value="{{ $variant->id }}">
                                            </div>

                                            <div class="demo" style="display:flex; align-items:center; gap:5px;">
                                                @if ($variant->image)
                                                    <div class="mb-2">
                                                        <img src="{{ asset($variant->image) }}" width="100">
                                                    </div>
                                                @endif
                                                    <div style="w-25">
                                                        <label for="image">Product Image(450x600 px)</label>
                                                <input type="file" name="existing_variants[{{ $variant->id }}][image]"
                                                    class="form-control">
                                                    </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">Variant Video</label>

                                                    <input type="file" name="existing_variants[{{ $variant->id }}][video]"
                                                        class="form-control variant-video">
                                                    <button type="button"
                                                        class="btn btn-sm btn-warning mt-2 remove-selected-file"
                                                        style="display:none;">
                                                        Remove Selected File
                                                    </button>
                                                            @if($variant->video)
                                                                <div class="mt-2">
                                                                    <video controls style="width:200px;height:150px">
                                                                        <source src="{{ asset($variant->video) }}">
                                                                    </video>
                                                                </div>

                                                                <div class="form-check mt-1">
                                                                   <button type="button"
                                                                        class="btn btn-sm btn-danger delete-video-btn"
                                                                        data-id="{{ $variant->id }}"
                                                                        data-type="video">
                                                                        Delete Video
                                                                    </button>
                                                                </div>
                                                            @endif

                                                </div>
                                                
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">Promotional Video</label>

                                                    <input type="file"
                                                        name="existing_variants[{{ $variant->id }}][promotional_video]"
                                                        class="form-control variant-video">
                                                    <button type="button"
                                                        class="btn btn-sm btn-warning mt-2 remove-selected-file"
                                                        style="display:none;">
                                                        Remove Selected File
                                                    </button>
                                                         @if($variant->promotional_video)
                                                                <div class="row d-flex">
                                                                <div class="mt-2">
                                                                    <video controls style="width: 200px;height:150px">
                                                                        <source src="{{ asset($variant->promotional_video) }}">
                                                                    </video>
                                                                </div>

                                                                <div class="form-check mt-1">
                                                                   <button type="button"
                                                                        class="btn btn-sm btn-danger delete-video-btn"
                                                                        data-id="{{ $variant->id }}"
                                                                        data-type="promotional_video">
                                                                        Delete Video
                                                                    </button>
                                                                </div>
                                                                </div>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Variant Gallery</label>
                                                <div class="mb-2 d-flex flex-wrap gap-3">
                                                    @foreach($variant->media as $media)
                                                        <div class="border p-2 position-relative"
                                                            style="width: 120px; text-align: center;">
                                                            <img src="{{ asset($media->url) }}" alt="" class="img-fluid"
                                                                style="max-height:100px;">
                                                            <button type="button" class="btn btn-sm btn-danger remove-variant-media"
                                                                data-id="{{ $media->id }}">
                                                                Remove
                                                            </button>
                                                            <input type="hidden" name="keep_variant_gallery[{{ $variant->id }}][]"
                                                                value="{{ $media->id }}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <input type="file" name="variant_gallery[{{ $variant->id }}][]"
                                                    class="form-control" multiple accept="image/*">
                                            </div>

                                            <div class="row g-3 mt-1">
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">SKU</label>
                                                    <input type="text" class="form-control" readonly value="{{ $variant->sku }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-2">
                                                <div class="col-md-4 mt-3">
                                                    <label>Actual Price</label>
                                                    <input type="text"
                                                        name="existing_variants[{{ $variant->id }}][actual_price]"
                                                        value="{{ $variant->actual_price }}" class="form-control">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Sale Price</label>
                                                    <input type="text" name="existing_variants[{{ $variant->id }}][sale_price]"
                                                        value="{{ $variant->price }}" class="form-control">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="form-label">Weight</label>
                                                    <input type="number" step="0.01" min="0.1" class="form-control"
                                                        name="existing_variants[{{ $variant->id }}][weight]"
                                                        value="{{ $variant->weight }}" required>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="form-label">Height</label>
                                                    <input type="number" min="1" class="form-control"
                                                        name="existing_variants[{{ $variant->id }}][height]"
                                                        value="{{ $variant->height }}" required>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="form-label">Width</label>
                                                    <input type="number" min="1" class="form-control"
                                                        name="existing_variants[{{ $variant->id }}][width]"
                                                        value="{{ $variant->width }}" required>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="form-label">Length</label>
                                                    <input type="number" min="1" class="form-control"
                                                        name="existing_variants[{{ $variant->id }}][length]"
                                                        value="{{ $variant->length }}" required>
                                                </div>
                                               
                                                <div class="col-md-4 mt-3">
                                                    <label>Stock</label>
                                                    <input type="text" name="existing_variants[{{ $variant->id }}][stock]"
                                                        value="{{ $variant->stock }}" class="form-control">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Low Stock Alert</label>
                                                    <input type="text"
                                                        name="existing_variants[{{ $variant->id }}][low_stock_alert]"
                                                        value="{{ $variant->low_stock_alert }}" class="form-control">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Min Order</label>
                                                    <input type="text"
                                                        name="existing_variants[{{ $variant->id }}][product_min_order]"
                                                        value="{{ $variant->product_min_order }}" class="form-control">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Max Order</label>
                                                    <input type="text"
                                                        name="existing_variants[{{ $variant->id }}][product_max_order]"
                                                        value="{{ $variant->product_max_order }}" class="form-control">
                                                </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label>Preorder Stock</label>
                                                        <input type="text"
                                                            name="existing_variants[{{ $variant->id }}][preorder_stock]"
                                                            value="{{ $variant->preorder_stock }}" class="form-control">
                                                    </div>
                                                        <div class="col-md-4 mt-3">
                                                            <label>Preorder</label>
                                                            <select class="form-control"
                                                                name="existing_variants[{{ $variant->id }}][preorder]">
                                                                <option value="0" {{ !$variant->preorder ? 'selected' : '' }}>No</option>
                                                                <option value="1" {{ $variant->preorder ? 'selected' : '' }}>Yes</option>
                                                                
                                                            </select>
                                                            </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Status</label>
                                                    <select name="existing_variants[{{ $variant->id }}][status]"
                                                        class="form-control">
                                                        <option value="show" {{ $variant->status === 'show' ? 'selected' : '' }}>
                                                            Show</option>
                                                        <option value="hide" {{ $variant->status === 'hide' ? 'selected' : '' }}>
                                                            Hide</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Added Pieces</label>
                                                    <input type="text" readonly value="{{ $addedPiece }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="newVariantList"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-submit">Update Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

        <div class="modal fade" id="addValueModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Value</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="modalAttributeId">

                    <div class="mb-3">
                        <label id="modalLabel"></label>
                        <input type="text" id="newValueInput" class="form-control" onkeydown="if(event.key === ' '){ event.preventDefault(); }
        if(event.key === 'Enter'){
            event.preventDefault();
            this.value = this.value.replace(/\s+/g,'');
        }" placeholder="Enter value">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveValueBtn">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.bulkProductUpdate') }}" method="post">
            @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Update Variants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <input type="hidden" value="{{ $product->id }}" name="product_id" required>
                    <div class="col-md-4 mt-3">
                        <label>Actual Price</label>
                        <input type="text" id="bulk_actual_price" name="actual_price" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Sale Price</label>
                        <input type="text" id="bulk_sale_price" name="price" class="form-control">
                    </div>

                   
                    

                    <div class="col-md-4 mt-3">
                        <label>Height</label>
                        <input type="number" min="1" id="bulk_height" name="height" class="form-control">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Width</label>
                        <input type="number" min="1" id="bulk_width" name="width" class="form-control">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Length</label>
                        <input type="number" min="1" id="bulk_length" name="length" class="form-control">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Weight</label>
                        <input type="number"  step="0.01" min="0.1" id="bulk_weight" name="weight"  class="form-control">
                    </div>

                     <div class="col-md-4 mt-3">
                        <label>Stock</label>
                        <input type="number" min="0" id="bulk_stock" name="stock" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Preorder Stock</label>
                        <input type="number" min="0" name="preorder_stock" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Low Stock Alert</label>
                        <input type="number" min="0" name="low_stock_alert" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Min Order</label>
                        <input type="number" min="0" name="product_min_order" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Max Order</label>
                        <input type="number" min="0" name="product_max_order" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Preorder</label>
                        <select class="form-control" name="preorder">
                            <option value="">Select</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="">Select Status</option>
                            <option value="show">Show</option>
                            <option value="hide">Hide</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" id="applyBulkUpdate" class="btn btn-success">
                    Apply Changes
                </button>
            </div>
        </div>
        </form>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".remove-variant").forEach(button => {
                button.addEventListener("click", function () {
                    const box = this.closest(".variant-box");
                    if (box) box.remove();
                });
            });
        });

        function getCombinations(arrays) {
            if (arrays.length === 0) return [[]];
            const restCombinations = getCombinations(arrays.slice(1));
            return arrays[0].flatMap(item => restCombinations.map(rest => [item, ...rest]));
        }


        function updateVariantPreview() {
            let selectedAttributes = {};

            $('input.variant-checkbox:checked').each(function () {
                const name = $(this).attr('name');
                const match = name ? name.match(/variant_attributes\[(\d+)\]/) : null;
                if (match) {
                    const attrId = match[1];
                    const value = $(this).parent().text().trim();

                    if (!selectedAttributes[attrId]) {
                        selectedAttributes[attrId] = [];
                    }
                    selectedAttributes[attrId].push(value);
                }
            });

            const valuesArray = Object.values(selectedAttributes);
            const combinations = getCombinations(valuesArray);
            const $variantList = $('#variantList');

            $variantList.empty();

            if (combinations.length > 0) {
                $('#variantPreview').show();

                combinations.forEach(combo => {
                    const combinationName = combo.join(' + ');
                    const inputKey = combo.map(val => val.replace(/\s+/g, '_')).join('_');

                    const variantBlock = `
                        <div class="border rounded p-3 mb-3">
                            <h6 class="col-md-4 mt-3">${combinationName}</h6>
                            <div class="demo mb-4" style="display:flex; align-items:center; gap:5px;">
                                <input type="file" class="form-control w-25" name="variants[${inputKey}][image]" accept="image/*" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">SKU</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][sku]" required>
                                </div>
                            
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Actual Price</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][actual_price]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Sale Price</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][sale_price]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Weight</label>
                                    <input type="number" step="0.01" min="0.1" class="form-control" name="variants[${inputKey}][weight]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Height</label>
                                    <input type="number" min="1" class="form-control" name="variants[${inputKey}][height]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Width</label>
                                    <input type="number" min="1" class="form-control" name="variants[${inputKey}][width]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Length</label>
                                    <input type="number" min="1" class="form-control" name="variants[${inputKey}][length]" required>
                                </div>                                
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Stock</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][stock]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Low Stock Alert</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][low_stock_alert]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Product Min Order</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][product_min_order]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Product Max Order</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][product_max_order]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Preorder Stock</label>
                                    <input type="text" class="form-control" name="variants[${inputKey}][preorder_stock]" required>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Preorder</label>
                                    <select class="form-control" name="variants[${inputKey}][preorder]" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="variants[${inputKey}][status]" required>
                                        <option value="show">Show</option>
                                        <option value="hide">Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>
                    `;

                    $variantList.append(variantBlock);
                });
            } else {
                $('#variantPreview').hide();
            }
        }

        $(document).on('change', 'input.variant-checkbox', function () {
            updateVariantPreview();
        });


        $(document).ready(function () {
            let selectedSubs = @json($selectedSubcategories);
            let initialCategoryId = $('#categorySelect').val();
            let initialCategoryTitle = $('#categorySelect').find(':selected').data('title');

            if (initialCategoryId) {
                $('#primaryCategory').val(initialCategoryTitle);
                loadSubcategories(initialCategoryId, selectedSubs);
            }

            $('#categorySelect').on('change', function () {
                let categoryId = $(this).val();
                let categoryTitle = $(this).find(':selected').data('title');
                $('#primaryCategory').val(categoryTitle || '');
                loadSubcategories(categoryId, []);
            });

            function loadSubcategories(categoryId, checkedList) {
                if (!categoryId) {
                    $('#subcategorySection').hide();
                    return;
                }
                $.ajax({
                    url: '/admin/get-subcategories/' + categoryId,
                    type: 'GET',
                    success: function (data) {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(function (subcat) {
                                let checked = checkedList.includes(subcat.id) ? 'checked' : '';
                                html += `<div>
                                            <input type="checkbox" name="subcategories[]" value="${subcat.id}" ${checked}>
                                            ${subcat.title}
                                        </div>`;
                            });
                        } else {
                            html = '<p>No subcategories found.</p>';
                        }
                        $('#subcategoryList').html(html);
                        $('#subcategorySection').show();
                    }
                });
            }

            $('#subcategorySearch').on('keyup', function () {
                let value = $(this).val().toLowerCase();
                $("#subcategoryList div").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });


        $(document).on('click', '.remove-variant-media', function () {
            let mediaId = $(this).data('id');
            let card = $(this).closest('div');

            if (confirm('Remove this image?')) {
                $.ajax({
                    url: '/admin/product-media/' + mediaId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        if (res.success) {
                            card.remove();
                        } else {
                            alert('Error deleting image');
                        }
                    },
                    error: function () {
                        alert('Something went wrong!');
                    }
                });
            }
        });


    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const videoInputs = document.querySelectorAll('.variant-video');

            videoInputs.forEach(input => {
                input.addEventListener("change", function () {
                    const file = this.files[0];

                    if (file) {
                        const maxSize = 3 * 1024 * 1024;

                        if (file.size > maxSize) {
                            alert("Video must be less than 3MB");
                            this.value = "";
                        }
                    }
                });
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ✅ SAVE VALUE (AJAX)
            let btn = document.getElementById("saveValueBtn");

            if (btn) {
                btn.addEventListener("click", function () {

                    let attrId = document.getElementById("modalAttributeId").value;
                    let value = document.getElementById("newValueInput").value.trim();

                    if (!value) return;

                    let container = document.querySelector(`.attribute-values[data-attribute-id="${attrId}"]`);

                    // ✅ Prevent duplicate (frontend)
                    let already = [...container.querySelectorAll("input")]
                        .some(i => i.value.toLowerCase() === value.toLowerCase());

                    if (already) {
                        alert("Already exists");
                        return;
                    }

                    // ✅ AJAX CALL
                    fetch('/admin/add-value', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            attribute_id: attrId,
                            value: value
                        })
                    })
                        .then(res => res.json())
                        .then(data => {

                            if (data.error) {
                                alert("Already exists");
                                return;
                            }

                            // ✅ Add checkbox dynamically
                            container.insertAdjacentHTML('beforeend', `
                                                    <div class="col-lg-2">
                                                        <label>
                                                            <input type="checkbox"
                                                                name="variant_attributes[${attrId}][]"
                                                                value="${data.name}" checked class="form-check-input variant-checkbox">
                                                            ${data.name}
                                                        </label>
                                                    </div>
                                                `);

                            // clear input
                            document.getElementById("newValueInput").value = "";

                            // close modal
                            bootstrap.Modal.getInstance(document.getElementById('addValueModal')).hide();

                            updateVariantPreview();


                        })
                        .catch(() => {
                            alert("Something went wrong");
                        });

                });
            }

            // ✅ RESET BUTTON FIX (no error)
            let resetButton = document.getElementById("resetButton");

            if (resetButton) {
                resetButton.addEventListener('click', resetThemeAndSidebarThemeAndColorAndBg);
            }

        });

        $(document).on('click', '.open-modal', function () {
            let attrId = $(this).data('attribute-id');
            let attrName = $(this).data('attribute-name');

            $('#modalAttributeId').val(attrId);
            $('#modalLabel').text("Add " + attrName);
            $('#newValueInput').val('');

            let modal = new bootstrap.Modal(document.getElementById('addValueModal'));
            modal.show();
        });
    </script>

<script>
    $(document).on('keyup', '.attribute-search', function () {
        let searchText = $(this).val().toLowerCase();
    let attributeId = $(this).data('attribute-id');

    let container = $('.attribute-values[data-attribute-id="' + attributeId + '"]');

    container.find('.attribute-item').each(function () {
        let labelText = $(this).text().toLowerCase();

    if (labelText.includes(searchText)) {
        $(this).show();
        } else {
        $(this).hide();
        }
    });
    updateVariantPreview();

});

</script>

<script>
    $(document).on('click', '.delete-video-btn', function () {
    let button = $(this);
    let variantId = button.data('id');
    let type = button.data('type');

    if (!confirm('Are you sure you want to delete this video?')) return;

    $.ajax({
        url: '/admin/delete-variant-video',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: variantId,
            type: type
        },
        success: function (response) {
            if (response.success) {
                button.closest('.mt-2').remove(); // remove video preview
                button.remove();
            }
        }
    });
});
</script>
<script>
    $(document).on('change', '.variant-video', function () {
    let input = $(this);
    let removeBtn = input.siblings('.remove-selected-file');

    if (this.files.length > 0) {
        removeBtn.show();
    } else {
        removeBtn.hide();
    }
});

$(document).on('click', '.remove-selected-file', function () {
    let btn = $(this);
    let input = btn.siblings('.variant-video');

    input.val(''); // clear selected file
    btn.hide();
});
</script>
@endsection