<?php $page = 'add-product'; ?>
@extends('layout.mainlayout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-wrapper">
<div class="content">
    @component('components.breadcrumb')
        @slot('title') New Food Product @endslot
        @slot('li_1') Catalog @endslot
        @slot('li_2') product-list @endslot
        @slot('li_3') Back to Products @endslot
    @endcomponent

    <style>
        .food-page{--accent:#e85d2a;--soft:#fff3ed}
        .food-page .section-card{border:1px solid #edf0f2;border-radius:14px;margin-bottom:20px;overflow:hidden}
        .food-page .section-head{display:flex;align-items:center;gap:12px;padding:18px 22px;border-bottom:1px solid #edf0f2}
        .food-page .section-icon{width:38px;height:38px;border-radius:10px;background:var(--soft);color:var(--accent);display:flex;align-items:center;justify-content:center}
        .food-page .section-head h5{margin:0;font-size:16px;font-weight:700}.food-page .section-head p{margin:3px 0 0;color:#858d96;font-size:12px}
        .food-page .section-body{padding:22px}.food-page .form-label{font-weight:600;color:#39424c;margin-bottom:7px}
        .food-page .form-control,.food-page .form-select{min-height:43px;border-radius:8px}
        .food-page textarea.form-control{min-height:95px}.food-page .help{font-size:12px;color:#89919a;margin-top:5px;display:block}
        .food-page .required{color:#dc3545}.food-page .upload-box{height:155px;border:1.5px dashed #d7dce1;border-radius:12px;background:#fafbfc;display:flex;align-items:center;justify-content:center;flex-direction:column;cursor:pointer;background-position:center;background-size:cover}
        .food-page .upload-box i{font-size:25px;color:var(--accent);margin-bottom:8px}.food-page .upload-box span{font-size:13px;color:#69727c}
        .food-page .toggle-box{height:100%;display:flex;justify-content:space-between;align-items:center;padding:13px;border:1px solid #edf0f2;border-radius:10px}
        .food-page .toggle-box strong{display:block;font-size:13px}.food-page .toggle-box small{font-size:11px;color:#89919a}
        .food-page .attribute-box,.food-page .variant-card,.food-page .addon-box{border:1px solid #edf0f2;border-radius:11px;padding:15px;margin-bottom:13px}
        .food-page .variant-value{display:inline-flex;align-items:center;gap:7px;border:1px solid #e7eaed;border-radius:8px;padding:7px 10px;margin:5px 5px 0 0;font-size:13px}
        .food-page .variant-title{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
        .food-page .pill{background:var(--soft);color:#d94f20;border-radius:20px;padding:5px 9px;font-size:11px;font-weight:600}
        .food-page .sticky-actions{position:sticky;bottom:0;z-index:5;background:rgba(255,255,255,.97);border:1px solid #edf0f2;border-radius:12px;padding:14px 18px;box-shadow:0 -5px 20px #17212b0d}
        .food-page .btn-food{background:var(--accent);border-color:var(--accent);color:#fff}
        @media(max-width:767px){.food-page .section-body{padding:16px}.food-page .section-head{padding:15px 16px}.food-page .sticky-actions{position:static}}
    </style>

    <div class="food-page">
        @if ($errors->any())
            <div class="alert alert-danger"><strong>Please correct these errors:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <form id="productCreateForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- BASIC INFORMATION --}}
            <div class="card section-card">
                <div class="section-head"><div class="section-icon"><i data-feather="package"></i></div><div><h5>Basic Information</h5><p>Product identity, brand and restaurant details</p></div></div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label">Product Name <span class="required">*</span></label>
                            <input class="form-control" type="text" name="title" id="productName" value="{{ old('title') }}" placeholder="e.g. Chicken Dum Biryani" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Product Slug</label>
                            <input class="form-control" type="text" name="slug" id="productSlug" value="{{ old('slug') }}" placeholder="Auto-generated" readonly>
                            <small class="help">Automatically generated from product name.</small>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Brand <span class="required">*</span></label>
                            <select class="form-select select" name="brand_id" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Category <span class="required">*</span></label>
                            <select class="form-select select" name="category_id" id="categorySelect" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-title="{{ $cat->title }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Restaurant</label>
                            <select class="form-select select" name="restaurant_id">
                                <option value="">Select Restaurant (Optional)</option>
                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Product Code (HSN) <span class="required">*</span></label>
                            <input class="form-control" type="text" name="hsn_code" value="{{ old('hsn_code') }}" placeholder="Unique product code" required>
                            <small class="help">Required by your current ProductController.</small>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Food Type <span class="required">*</span></label>
                            <select class="form-select" name="food_type" required>
                                <option value="veg" {{ old('food_type','veg') == 'veg' ? 'selected' : '' }}>Vegetarian</option>
                                <option value="non_veg" {{ old('food_type') == 'non_veg' ? 'selected' : '' }}>Non-Vegetarian</option>
                                <option value="egg" {{ old('food_type') == 'egg' ? 'selected' : '' }}>Contains Egg</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="show" {{ old('status','show') == 'show' ? 'selected' : '' }}>Active</option>
                                <option value="hide" {{ old('status') == 'hide' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short Description</label>
                            <textarea class="form-control" name="short_description" rows="2" maxlength="200" placeholder="Short customer-facing description">{{ old('short_description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Describe the dish, taste and serving details">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="card section-card">
                <div class="section-head"><div class="section-icon"><i data-feather="image"></i></div><div><h5>Product Image</h5><p>Main image displayed to customers</p></div></div>
                <div class="section-body">
                    <div class="col-lg-4 col-md-6 px-0">
                        <label class="form-label">Main Product Image</label>
                        <label class="upload-box w-100" for="imageInput" id="imagePreview">
                            <i data-feather="upload-cloud" id="uploadIcon"></i><span id="uploadText">Click to upload product image</span><small class="text-muted mt-1">JPG, PNG or WEBP</small>
                        </label>
                        <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/webp" class="d-none">
                        <button type="button" class="btn btn-sm btn-light mt-2 d-none" id="removeImage">Remove image</button>
                    </div>
                </div>
            </div>

            {{-- FOOD SETTINGS --}}
            <div class="card section-card">
                <div class="section-head"><div class="section-icon"><i data-feather="coffee"></i></div><div><h5>Food Details & Availability</h5><p>Preparation time, allergens and customer-facing settings</p></div></div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6"><label class="form-label">Preparation Time (minutes)</label><input class="form-control" type="number" min="0" name="preparation_time" value="{{ old('preparation_time') }}" placeholder="e.g. 25"></div>
                        <div class="col-lg-4 col-md-6"><label class="form-label">Sort Order</label><input class="form-control" type="number" min="0" name="sort_order" value="{{ old('sort_order',0) }}"></div>
                        <div class="col-lg-4 col-md-6"><label class="form-label">Allergen Information</label><input class="form-control" type="text" name="allergen_info" value="{{ old('allergen_info') }}" placeholder="e.g. Dairy, nuts, gluten"></div>
                        <div class="col-lg-4 col-md-6"><div class="toggle-box"><div><strong>Available</strong><small class="d-block">Customers can order it</small></div><div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" name="is_available" value="1" {{ old('is_available','1') ? 'checked' : '' }}></div></div></div>
                        <div class="col-lg-4 col-md-6"><div class="toggle-box"><div><strong>Recommended</strong><small class="d-block">Mark as recommended</small></div><div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}></div></div></div>
                        <div class="col-lg-4 col-md-6"><div class="toggle-box"><div><strong>Featured</strong><small class="d-block">Show in featured areas</small></div><div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" name="is_feature" value="1" {{ old('is_feature') ? 'checked' : '' }}></div></div></div>
                    </div>
                </div>
            </div>

            {{-- VARIANTS --}}
            <div class="card section-card">
                <div class="section-head">
                    <div class="section-icon"><i data-feather="sliders"></i></div>
                    <div class="flex-grow-1"><h5>Variants & Pricing</h5><p>Select portion sizes or other options to generate variants</p></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal"><i data-feather="edit-3" class="me-1"></i>Bulk Update</button>
                </div>
                <div class="section-body">
                    @forelse($variantAttributes as $attribute)
                        <div class="attribute-box">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div><label class="form-label mb-0">{{ ucwords($attribute->name) }}</label><small class="help">Select one or more values.</small></div>
                                <button type="button" class="btn btn-sm btn-outline-primary open-modal" data-attribute-id="{{ $attribute->id }}" data-attribute-name="{{ $attribute->name }}">+ Add value</button>
                            </div>
                            @if(str_contains(strtolower($attribute->name),'color'))
                                <input type="text" class="form-control form-control-sm mt-3 attribute-search" placeholder="Search {{ $attribute->name }}" data-attribute-id="{{ $attribute->id }}">
                            @endif
                            <div class="mt-2 attribute-values" data-attribute-id="{{ $attribute->id }}">
                                @foreach($attribute->attributeValues as $value)
                                    <label class="variant-value attribute-item"><input type="checkbox" class="form-check-input variant-checkbox" name="variant_attributes[{{ $attribute->id }}][]" value="{{ $value->name }}" {{ in_array($value->name, old('variant_attributes.'.$attribute->id,[])) ? 'checked' : '' }}> {{ $value->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">No active variant attributes found. Add attributes before creating a product.</div>
                    @endforelse
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3"><div><h6 class="fw-bold mb-1">Generated Variants</h6><small class="text-muted">Set pricing, stock and optional preorder details for each combination.</small></div><span class="pill" id="variantCount">0 variants</span></div>
                    <div id="variantList"><div class="text-center text-muted py-4 border rounded">Select variant values above to generate combinations.</div></div>
                </div>
            </div>

            {{-- ADDONS --}}
            <div class="card section-card">
                <div class="section-head"><div class="section-icon"><i data-feather="plus-square"></i></div><div><h5>Add-on Groups</h5><p>Attach existing extras and customization groups</p></div></div>
                <div class="section-body">
                    @if(isset($addonGroups) && $addonGroups->count())
                        <div class="row">
                            @foreach($addonGroups as $group)
                                <div class="col-lg-6">
                                    <label class="addon-box d-flex gap-3">
                                        <input class="form-check-input mt-1" type="checkbox" name="addon_groups[]" value="{{ $group->id }}" {{ in_array($group->id,old('addon_groups',[])) ? 'checked' : '' }}>
                                        <span><strong class="d-block">{{ $group->name }}</strong>
                                            <small class="text-muted">{{ ucfirst($group->selection_type) }} · {{ $group->is_required ? 'Required' : 'Optional' }} · Min {{ $group->min_select }} / Max {{ $group->max_select }}</small>
                                            @if($group->options && $group->options->count())<small class="d-block mt-2 text-muted">@foreach($group->options as $option){{ $option->name }} (₹{{ number_format($option->price,2) }})@if(!$loop->last), @endif @endforeach</small>@endif
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border mb-0">Add-on groups will appear here after ProductController create() loads the active groups.</div>
                    @endif
                </div>
            </div>

            <div class="sticky-actions d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-food px-4"><i data-feather="save" class="me-1"></i>Save Product</button>
            </div>
        </form>
    </div>
</div>
</div>

{{-- ADD ATTRIBUTE VALUE --}}
<div class="modal fade" id="addValueModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Attribute Value</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><input type="hidden" id="modalAttributeId"><label class="form-label" id="modalLabel">Value</label><input type="text" id="newValueInput" class="form-control" placeholder="e.g. Half, Full, Family Pack"></div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-food" id="saveValueBtn">Save Value</button></div>
    </div></div>
</div>

{{-- BULK UPDATE --}}
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><div><h5 class="modal-title mb-1">Bulk Update Variants</h5><small class="text-muted">Only entered values will be applied.</small></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="row g-3">
            <div class="col-md-4"><label class="form-label">Actual Price (₹)</label><input type="number" min="0" step=".01" id="bulk_actual_price" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Selling Price (₹)</label><input type="number" min="0" step=".01" id="bulk_sale_price" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Weight (kg, optional)</label><input type="number" min="0" step=".001" id="bulk_weight" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Stock Quantity</label><input type="number" min="0" id="bulk_stock" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Low Stock Alert</label><input type="number" min="0" id="bulk_low_stock" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Minimum Order</label><input type="number" min="1" id="bulk_min_order" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Maximum Order</label><input type="number" min="1" id="bulk_max_order" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Preorder (optional)</label><select id="bulk_preorder" class="form-select"><option value="">Do not change</option><option value="0">No</option><option value="1">Yes</option></select></div>
            <div class="col-md-4"><label class="form-label">Preorder Stock</label><input type="number" min="0" id="bulk_preorder_stock" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Variant Status</label><select id="bulk_status" class="form-select"><option value="">Do not change</option><option value="show">Active</option><option value="hide">Inactive</option></select></div>
        </div></div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-food" id="applyBulkUpdate">Apply to Variants</button></div>
    </div></div>
</div>

<script>
const oldVariants = @json(old('variants', []));

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
}
function combinations(arrays) {
    return arrays.reduce((result, values) => result.flatMap(row => values.map(value => [...row,value])), [[]]);
}
function updateVariantPreview() {
    const selected = [];
    document.querySelectorAll('.attribute-values').forEach(box => {
        const values = [...box.querySelectorAll('.variant-checkbox:checked')].map(input => input.value);
        if (values.length) selected.push(values);
    });
    const list = document.getElementById('variantList');
    const count = document.getElementById('variantCount');
    list.innerHTML = '';
    if (!selected.length) {
        list.innerHTML = '<div class="text-center text-muted py-4 border rounded">Select variant values above to generate combinations.</div>';
        count.textContent = '0 variants'; return;
    }
    const rows = combinations(selected);
    count.textContent = rows.length + (rows.length === 1 ? ' variant' : ' variants');
    rows.forEach(combo => {
        // Matches ProductController: implode('_', $comboNames).
        const key = combo.join('_');
        const old = oldVariants[key] || {};
        const card = document.createElement('div');
        card.className = 'variant-card';
        card.innerHTML = `
            <div class="variant-title"><h6 class="mb-0 fw-bold">${escapeHtml(combo.join(' + '))}</h6><span class="pill">Food variant</span></div>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">SKU <span class="text-danger">*</span></label><input class="form-control" name="variants[${escapeHtml(key)}][sku]" value="${escapeHtml(old.sku || '')}" required></div>
                <div class="col-md-4"><label class="form-label">Actual Price (₹) <span class="text-danger">*</span></label><input type="number" min="0" step=".01" class="form-control variant-actual" name="variants[${escapeHtml(key)}][actual_price]" value="${escapeHtml(old.actual_price || '')}" required></div>
                <div class="col-md-4"><label class="form-label">Selling Price (₹) <span class="text-danger">*</span></label><input type="number" min="0" step=".01" class="form-control variant-sale" name="variants[${escapeHtml(key)}][sale_price]" value="${escapeHtml(old.sale_price || '')}" required></div>
                <div class="col-md-4"><label class="form-label">Weight (kg, optional)</label><input type="number" min="0" step=".001" class="form-control variant-weight" name="variants[${escapeHtml(key)}][weight]" value="${escapeHtml(old.weight || '')}" placeholder="e.g. 0.5"><small class="help">Leave blank when not applicable.</small></div>
                <div class="col-md-4"><label class="form-label">Stock Quantity</label><input type="number" min="0" class="form-control variant-stock" name="variants[${escapeHtml(key)}][stock]" value="${escapeHtml(old.stock ?? 0)}"></div>
                <div class="col-md-4"><label class="form-label">Low Stock Alert</label><input type="number" min="0" class="form-control variant-low" name="variants[${escapeHtml(key)}][low_stock_alert]" value="${escapeHtml(old.low_stock_alert ?? 0)}"></div>
                <div class="col-md-4"><label class="form-label">Minimum Order</label><input type="number" min="1" class="form-control variant-min" name="variants[${escapeHtml(key)}][product_min_order]" value="${escapeHtml(old.product_min_order ?? 1)}"></div>
                <div class="col-md-4"><label class="form-label">Maximum Order</label><input type="number" min="1" class="form-control variant-max" name="variants[${escapeHtml(key)}][product_max_order]" value="${escapeHtml(old.product_max_order ?? 10)}"></div>
                <div class="col-md-4"><label class="form-label">Preparation Time (minutes)</label><input type="number" min="0" class="form-control" name="variants[${escapeHtml(key)}][preparation_time]" value="${escapeHtml(old.preparation_time || '')}" placeholder="Optional"></div>
                <div class="col-md-4"><label class="form-label">Variant Available</label><select class="form-select" name="variants[${escapeHtml(key)}][is_available]"><option value="1" ${String(old.is_available ?? '1') === '1' ? 'selected' : ''}>Available</option><option value="0" ${String(old.is_available) === '0' ? 'selected' : ''}>Unavailable</option></select></div>
                <div class="col-md-4"><label class="form-label">Preorder (optional)</label><select class="form-select variant-preorder" name="variants[${escapeHtml(key)}][preorder]"><option value="0" ${String(old.preorder ?? '0') === '0' ? 'selected' : ''}>No</option><option value="1" ${String(old.preorder) === '1' ? 'selected' : ''}>Yes</option></select></div>
                <div class="col-md-4 preorder-wrap" style="display:${String(old.preorder) === '1' ? 'block' : 'none'}"><label class="form-label">Preorder Stock</label><input type="number" min="0" class="form-control variant-preorder-stock" name="variants[${escapeHtml(key)}][preorder_stock]" value="${escapeHtml(old.preorder_stock ?? 0)}"></div>
                <div class="col-md-4"><label class="form-label">Variant Status</label><select class="form-select" name="variants[${escapeHtml(key)}][status]"><option value="show" ${String(old.status || 'show') === 'show' ? 'selected' : ''}>Active</option><option value="hide" ${String(old.status) === 'hide' ? 'selected' : ''}>Inactive</option></select></div>
            </div>`;
        list.appendChild(card);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const productName = document.getElementById('productName');
    const productSlug = document.getElementById('productSlug');
    productName.addEventListener('input', () => {
        productSlug.value = productName.value.toLowerCase().trim().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-');
    });

    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const removeImage = document.getElementById('removeImage');
    imageInput.addEventListener('change', event => {
        const file = event.target.files[0]; if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            preview.style.backgroundImage = `url("${e.target.result}")`;
            document.getElementById('uploadText').textContent = file.name;
            document.getElementById('uploadIcon').style.display = 'none';
            removeImage.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });
    removeImage.addEventListener('click', () => {
        imageInput.value = ''; preview.style.backgroundImage = '';
        document.getElementById('uploadText').textContent = 'Click to upload product image';
        document.getElementById('uploadIcon').style.display = '';
        removeImage.classList.add('d-none');
    });

    document.querySelectorAll('.attribute-search').forEach(search => search.addEventListener('input', () => {
        const box = document.querySelector(`.attribute-values[data-attribute-id="${search.dataset.attributeId}"]`);
        box.querySelectorAll('.attribute-item').forEach(item => item.style.display = item.textContent.toLowerCase().includes(search.value.toLowerCase()) ? 'inline-flex' : 'none');
    }));
    updateVariantPreview();
});

document.addEventListener('change', event => {
    if (event.target.classList.contains('variant-checkbox')) updateVariantPreview();
    if (event.target.classList.contains('variant-preorder')) {
        event.target.closest('.variant-card').querySelector('.preorder-wrap').style.display = event.target.value === '1' ? 'block' : 'none';
    }
});

document.addEventListener('click', event => {
    const button = event.target.closest('.open-modal');
    if (!button) return;
    document.getElementById('modalAttributeId').value = button.dataset.attributeId;
    document.getElementById('modalLabel').textContent = 'New ' + button.dataset.attributeName + ' value';
    document.getElementById('newValueInput').value = '';
    bootstrap.Modal.getOrCreateInstance(document.getElementById('addValueModal')).show();
});

document.getElementById('saveValueBtn').addEventListener('click', async () => {
    const attributeId = document.getElementById('modalAttributeId').value;
    const value = document.getElementById('newValueInput').value.trim();
    if (!value) return;
    const container = document.querySelector(`.attribute-values[data-attribute-id="${attributeId}"]`);
    if ([...container.querySelectorAll('input')].some(input => input.value.toLowerCase() === value.toLowerCase())) {
        alert('This value already exists.'); return;
    }
    try {
        const response = await fetch('/admin/add-value', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
            body:JSON.stringify({attribute_id:attributeId,value})
        });
        const data = await response.json();
        if (!response.ok || data.error) throw new Error(data.error || 'Unable to save value.');
        const label = document.createElement('label');
        label.className = 'variant-value attribute-item';
        label.innerHTML = `<input type="checkbox" class="form-check-input variant-checkbox" name="variant_attributes[${attributeId}][]" value="${escapeHtml(data.name)}" checked> ${escapeHtml(data.name)}`;
        container.appendChild(label);
        bootstrap.Modal.getInstance(document.getElementById('addValueModal')).hide();
        updateVariantPreview();
    } catch(error) { alert(error.message || 'Something went wrong.'); }
});

document.getElementById('applyBulkUpdate').addEventListener('click', () => {
    const map = [
        ['bulk_actual_price','.variant-actual'],['bulk_sale_price','.variant-sale'],['bulk_weight','.variant-weight'],
        ['bulk_stock','.variant-stock'],['bulk_low_stock','.variant-low'],['bulk_min_order','.variant-min'],
        ['bulk_max_order','.variant-max'],['bulk_preorder_stock','.variant-preorder-stock']
    ];
    document.querySelectorAll('.variant-card').forEach(card => {
        map.forEach(([id,selector]) => {
            const value = document.getElementById(id).value, input = card.querySelector(selector);
            if (value !== '' && input) input.value = value;
        });
        const preorder = document.getElementById('bulk_preorder').value;
        if (preorder !== '') {
            card.querySelector('.variant-preorder').value = preorder;
            card.querySelector('.preorder-wrap').style.display = preorder === '1' ? 'block' : 'none';
        }
        const status = document.getElementById('bulk_status').value;
        if (status) card.querySelector('select[name$="[status]"]').value = status;
    });
    bootstrap.Modal.getInstance(document.getElementById('bulkUpdateModal')).hide();
});
</script>
@endsection
