<?php $page = 'add-product'; ?>
@extends('layout.mainlayout')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
            @slot('title')
            New Product
            @endslot
            @slot('li_1')
            Create new product
            @endslot
            @slot('li_2')
            product-list
            @endslot
            @slot('li_3')
            Back to Product
            @endslot
            @endcomponent
            <!-- /add -->
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
            <form action="{{route('admin.products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span>Product Information</span>
                                            </h5>
                                            <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" name="title"
                                                        value="{{ old('title') ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Brand</label>
                                                    </div>
                                                    <select class="select" name="brand_id">
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}" @if (old('brand_id') == $brand->id)
                                                            selected @endif> {{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="addservice-info">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="mb-3 add-product">
                                                        <div class="add-newplus">
                                                            <label class="form-label">Category</label>
                                                        </div>
                                                        <select class="select" name="category_id" id="categorySelect"
                                                            required>
                                                            <option value=""> Select Category</option>

                                                            @foreach ($categories as $cat)
                                                                <option value="{{ $cat->id }}" data-title="{{ $cat->title }}"
                                                                    @if (old('category_id') == $cat->id) selected @endif>
                                                                    {{ $cat->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <label class="form-label">Primary Category</label>
                                                    <input type="text" id="primaryCategory" class="form-control" readonly>
                                                </div>

                                                <div id="subcategorySection" class="mt-3 mb-3" style="display:none;">
                                                    <input type="text" id="subcategorySearch" placeholder="Search here..."
                                                        class="form-control mb-2">
                                                    <div id="subcategoryList" class="subcategory-grid"
                                                        style="max-height:200px; overflow-y:auto; border:1px solid #ccc; padding:10px;">
                                                    </div>
                                                </div> --}}
                                            </div>

                                            <div class="add-product-new">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="select" name="status">
                                                            <option value="show">Show</option>
                                                            <option value="hide">Hide</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                                                        <label class="form-label">Product Code</label>
                                                        <input type="text" class="form-control" name="hsn_code"
                                                            value="{{ old('hsn_code') ?? '' }}" required>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="input-blocks summer-description-box transfer mb-3">
                                                            <label>Description</label>
                                                            <textarea class="form-control h-100" rows="5" id="  "
                                                                name="description">{!! old('description') !!}</textarea>
                                                            {{-- <p class="mt-1">Maximum 60 Characters</p> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="input-blocks summer-description-box transfer mb-3">
                                                            <label>Short Description</label>
                                                            <textarea class="form-control h-100" rows="2" minlength="20"
                                                                maxlength="200"
                                                                name="short_description">{!! old('short_description') !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <label class="form-label">Product Image (450x600 px)</label>
                                            <div class="profile-pic-upload mb-3">
                                                <div class="profile-pic brand-pic" id="imagePreview">
                                                    <span id="uploadText">
                                                        <i data-feather="plus-circle"></i> Add Image
                                                    </span>
                                                </div>
                                                <button type="button" class="remove-btn" id="removeImage"
                                                    style="display:none;">&times;</button>
                                                <input type="file" name="image" id="imageInput" accept="image/*"
                                                    style="display:none;">
                                            </div>

                                            <div class="mb-0">
                                                <div
                                                    class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                    <span class="status-label">Is Feature</span>
                                                    <input type="checkbox" id="user2" class="check" name="is_feature"
                                                        checked="">
                                                    <label for="user2" class="checktoggle"
                                                        style="margin-right:1050px"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
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

                                                <div class="row">
                                                    @foreach($variantAttributes as $attribute)
                                                        <div class="col-lg-12 mb-3">
                                                            <div class="d-flex justify-content-between">
                                                                <label class="form-label fw-bold">{{ucwords( $attribute->name) }}</label>
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
                                                            <div class="row attribute-values mt-2"
                                                                data-attribute-id="{{ $attribute->id }}">
                                                                @foreach($attribute->attributeValues as $value)
                                                                    <div class="col-lg-2 attribute-item">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                name="variant_attributes[{{ $attribute->id }}][]"
                                                                                value="{{ $value->name }}"
                                                                                class="form-check-input variant-checkbox" {{ in_array($value->name, old('variant_attributes.'.$attribute->id, [])) ? 'checked' : '' }}>
                                                                            <b class="text-dark">{{ $value->name }}</b>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div id="variantPreview" class="mt-4 mb-3">
                                                    <h5>Variant Combinations:</h5>
                                                    <div id="variantList"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <button type="button" class="btn btn-cancel me-2">Cancel</button>
                        <button type="submit" class="btn btn-submit">Save Product</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

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

        <form >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Update Variants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
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
                        <input type="number" min="0" name="preorder_stock" id="preorder_stock" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Low Stock Alert</label>
                        <input type="number" min="0" name="low_stock_alert" id="low_stock_alert" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Min Order</label>
                        <input type="number" min="0" name="product_min_order" id="product_min_order" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Max Order</label>
                        <input type="number" min="0" name="product_max_order" id="product_max_order" class="form-control">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Preorder</label>
                        <select class="form-control" name="preorder" id="preorder">
                            <option value="">Select</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Status</label>
                        <select class="form-control" name="status" id="status">
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

    <script type="text/javascript">
        $(document).ready(function () {

            // Click anywhere on upload area to trigger file input
            $('#imagePreview').on('click', function () {
                $('#imageInput').click();
            });

            // When a file is selected
            $('#imageInput').on('change', function (event) {
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imagePreview')
                            .css('background-image', 'url(' + e.target.result + ')')
                            .css('border', 'none'); // remove dashed border
                        $('#uploadText').hide();
                        $('#removeImage').show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove image
            $('#removeImage').on('click', function () {
                $('#imagePreview')
                    .css('background-image', 'none')
                    .css('border', '2px dashed #ccc');
                $('#uploadText').show();
                $('#imageInput').val('');
                $(this).hide();
            });

        });


        $(document).ready(function () {
            $('select.form-control[multiple]').select2();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#mainUnit').on('change', function () {
                var unitId = $(this).val();

                if (unitId) {
                    $.ajax({
                        url: '{{ route("products.getAttributeValues", ":id") }}'.replace(':id', unitId),
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#mainUnit_unit_attributes').empty();
                            $('#mainUnit_unit_attributes').append('<option value="">Select Attribute</option>');

                            $.each(data, function (key, value) {
                                $('#mainUnit_unit_attributes').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#mainUnit_unit_attributes').empty();
                    $('#mainUnit_unit_attributes').append('<option value="">Select Attribute</option>');
                }
            });
        });
    </script>

    <script>
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
                combinations.forEach(combo => {
                    const combinationName = combo.join(' + ');
                    const inputKey = combo.map(val => val.replace(/\s+/g, '_')).join('_');

                     const oldData = oldVariants[inputKey] || {};

                    const variantBlock = `
                                                                                            <div class="border rounded p-3 mb-3">
                                                                                                <h6 class="mb-3">${combinationName}</h6>
                                                                                                <div class="demo mb-4" style="align-items:center; gap:5px;">
                                                                                                    <label class="form-label">Image  (450x600 px)</label>
                                                                                                    <input type="file" class="form-control w-25" name="variants[${inputKey}][image]" accept="image/*" required>
                                                                                                </div>
                                                                                                <div class="row g-3">
                                                                                                     <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">SKU</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][sku]" value="${oldData.sku || ''}" required>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row g-3">
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Actual Price</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][actual_price]"  value="${oldData.actual_price || ''}" required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Sale Price</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][sale_price]" value="${oldData.sale_price || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Weight</label>
                                                                                                        <input type="number" step="0.01" min="0.1" class="form-control" name="variants[${inputKey}][weight]" value="${oldData.weight || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Height</label>
                                                                                                        <input type="number" min="1" class="form-control" name="variants[${inputKey}][height]" value="${oldData.height || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Width</label>
                                                                                                        <input type="number" min="1" class="form-control" name="variants[${inputKey}][width]" value="${oldData.width || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Length</label>
                                                                                                        <input type="number" min="1" class="form-control" name="variants[${inputKey}][length]" value="${oldData.length || ''}"  required>
                                                                                                    </div>
                                                                                                    
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Stock</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][stock]" value="${oldData.stock || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Low Stock Alert</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][low_stock_alert]" value="${oldData.low_stock_alert || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Product Min Order</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][product_min_order]" value="${oldData.product_min_order || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Product Max Order</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][product_max_order]" value="${oldData.product_max_order || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Preorder Stock</label>
                                                                                                        <input type="text" class="form-control" name="variants[${inputKey}][preorder_stock]" value="${oldData.preorder_stock || ''}"  required>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Preorder</label>
                                                                                                        <select class="form-control" name="variants[${inputKey}][preorder]" value="${oldData.preorder || ''}"  required>
                                                                                                            <option value="0">No</option>
                                                                                                            <option value="1">Yes</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <div class="col-md-4 mt-3">
                                                                                                        <label class="form-label">Status</label>
                                                                                                        <select class="form-control" name="variants[${inputKey}][status]" value="${oldData.status || ''}"  required>
                                                                                                            <option value="show">Show</option>
                                                                                                            <option value="hide">Hide</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        `;

                    $variantList.append(variantBlock);
                });
            } else {
                $variantList.append(`<p class="text-muted">No combinations selected.</p>`);
            }
        }

        $(document).on('change', 'input.variant-checkbox', function () {
            updateVariantPreview();
        });

        $(document).ready(function () {
            updateVariantPreview();
        });


        $(document).ready(function () {
            $('#categorySelect').on('change', function () {
                let categoryId = $(this).val();
                let categoryTitle = $(this).find(':selected').data('title');

                if (categoryId) {

                    $('#primaryCategory').val(categoryTitle);

                    $.ajax({
                        url: '/admin/get-subcategories/' + categoryId,
                        type: 'GET',
                        success: function (data) {
                            let html = '';
                            if (data.length > 0) {
                                data.forEach(function (subcat) {
                                    html += `<div>
                                                                                                                    <input type="checkbox" name="subcategories[]" value="${subcat.id}">
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
                } else {
                    $('#primaryCategory').val('');
                    $('#subcategorySection').hide();
                }
            });

            $('#subcategorySearch').on('keyup', function () {
                let value = $(this).val().toLowerCase();
                $("#subcategoryList div").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

    </script>
    <!-- Place the first <script> tag in your HTML's <head> -->
    {{--
    <script src="https://cdn.tiny.cloud/1/go8pzwa1dd7ty2i742u6zj35grs3eqd1bc2687khmkr5zpa6/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Dec 15, 2025:
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'ai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            uploadcare_public_key: 'ea9008fbc6453a2f3a30',
        });
    </script> --}}

    <script>
    let oldVariants = @json(old('variants', []));
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
    $(document).on('click', '#applyBulkUpdate', function (e) {
    e.preventDefault();

    // Get bulk values
    let actual_price = $('#bulk_actual_price').val();
    let sale_price   = $('#bulk_sale_price').val();
    let height       = $('#bulk_height').val();
    let width        = $('#bulk_width').val();
    let length       = $('#bulk_length').val();
    let weight       = $('#bulk_weight').val();
    let stock        = $('#bulk_stock').val();
    let preorder_stock        = $('#preorder_stock').val();
    let low_stock        = $('#low_stock_alert').val();
    let min_order        = $('#product_min_order').val();
    let max_order        = $('#product_max_order').val();
    let preorder        = $('#preorder').val();
    let status        = $('#status').val();

    // Loop all variants
    $('#variantList').find('.border').each(function () {

        if (actual_price) $(this).find('input[name*="[actual_price]"]').val(actual_price);
        if (sale_price)   $(this).find('input[name*="[sale_price]"]').val(sale_price);
        if (height)       $(this).find('input[name*="[height]"]').val(height);
        if (width)        $(this).find('input[name*="[width]"]').val(width);
        if (length)       $(this).find('input[name*="[length]"]').val(length);
        if (weight)       $(this).find('input[name*="[weight]"]').val(weight);
        if (stock)        $(this).find('input[name*="[stock]"]').val(stock);

        if (preorder_stock) $(this).find('input[name*="[preorder_stock]"]').val(preorder_stock);
        if (low_stock)      $(this).find('input[name*="[low_stock_alert]"]').val(low_stock);
        if (min_order)      $(this).find('input[name*="[product_min_order]"]').val(min_order);
        if (max_order)      $(this).find('input[name*="[product_max_order]"]').val(max_order);

        if (preorder !== "") $(this).find('select[name*="[preorder]"]').val(preorder);
        if (status !== "")   $(this).find('select[name*="[status]"]').val(status);
    });

    // Close modal
    $('#bulkUpdateModal').modal('hide');
});
</script>

@endsection