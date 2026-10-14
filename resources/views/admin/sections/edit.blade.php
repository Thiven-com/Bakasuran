<?php $page = 'add-blog'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Section</h4>
                        <h6>Edit your Section</h6>
                    </div>
                </div>
            </div>
            <!-- /add -->
            <form action="{{route('admin.sections.update', $data->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span> Section
                                                    Information ({{ $data->section_name }})</span>
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        @php
                                            $selectedProducts = is_array($data->product_ids)
                                                ? $data->product_ids
                                                : json_decode($data->product_ids, true) ?? [];
                                        @endphp

                                        <div class="mb-3 field-title">
                                            <label>
                                                Title @if($rules['title_required']) <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            <input type="text" name="title" class="form-control"
                                                maxlength="{{ $rules['title_max'] }}"
                                                value="{{ old('title', $data->title) }}" {{ $rules['title_required'] ? 'required' : '' }}>
                                        </div>

                                        <div class="mb-3 field-short-desc">
                                            <label>Short Description</label>
                                            <input type="text" name="short_description"
                                                maxlength="{{ $rules['short_description_max'] }}" class="form-control" {{ $rules['short_description_required'] ? 'required' : '' }}
                                                value="{{ $data->short_description }}">
                                        </div>

                                        <div class="mb-3 field-description">
                                            <label>Description</label>
                                            <textarea name="description"
                                                class="form-control">{{ $data->description }}</textarea>
                                        </div>

                                        <div class="mb-3 field-image">
                                            <label>
                                                Image @if($rules['image_required']) <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            @if(!empty($data->image))
                                                <div class="mb-2">
                                                    <img src="{{ asset($data->image) }}" alt="Image"
                                                        style="width:120px; height:auto; border-radius:6px;">
                                                </div>
                                            @endif


                                            <input type="file" name="image" id="imageInput" {{ ($rules['image_required'] && empty($data->image)) ? 'required' : '' }}>

                                            <small>
                                                {{ $rules['image_width'] }}x{{ $rules['image_height'] }} px |
                                                Max {{ $rules['image_size'] / 1024 }}MB
                                            </small>
                                        </div>

                                        <div class="mb-3 field-products">
                                            <label>
                                                Products @if($rules['product_required']) <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            <select name="product_ids[]" class="form-control select2" multiple>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                                                        {{ $product->product->title ?? 'No Title' }} ({{ $product->sku }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="imageRepeater">

                                            @foreach($data->images as $key => $img)

                                                @php
                                                    $sectionImageRules[$img->slug];
                                                @endphp
                                                <div class="row mb-3 image-item">
                                                    {{-- Existing Image --}}
                                                    <div class="col-md-2">
                                                        <img src="{{ asset($img->image) }}" width="80">
                                                    </div>
                                                    @if ($data->section_slug == 'category_collection')
                                                        {{-- Title --}}
                                                        <div class="col-md-3">
                                                            <input type="text" name="images[{{ $key }}][title]"
                                                                value="{{ $img->title }}" class="form-control" placeholder="Title">
                                                        </div>
                                                    @endif

                                                    {{-- Replace Image --}}
                                                    <div class="col-md-3">
                                                        <input type="file" name="images[{{ $key }}][image]">
                                                        <input type="hidden" name="images[{{ $key }}][id]"
                                                            value="{{ $img->id }}">
                                                    </div>
                                                    <div class="col-md-2">
    <b>
        {{ $sectionImageRules[$img->slug]['width'] ?? $sectionImageRules['default']['width'] }}
        x
        {{ $sectionImageRules[$img->slug]['height'] ?? $sectionImageRules['default']['height'] }}
        px
    </b>
</div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <a href="{{ route('admin.sections.index') }}" class="btn btn-cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-submit">Update Section</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {

            $('.select2').select2({
                placeholder: "Select Products",
                allowClear: true,
                width: '100%'
            });

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const slug = "{{ $data->section_slug }}";

            // First hide all
            const fields = [
                '.field-title',
                '.field-short-desc',
                '.field-description',
                '.field-image',
                '.field-products'
            ];

            fields.forEach(f => {
                document.querySelector(f).style.display = 'none';
            });

            // Apply conditions
            switch (slug) {

                case 'today_deals':
                    show(['.field-title', '.field-short-desc', '.field-products']); // only products
                    break;

                case 'nearby_products':
                    show(['.field-title', '.field-short-desc', '.field-products']);
                    break;

                case 'clearance_sale':
                    show(['.field-title', '.field-products', '.field-image']);
                    break;
                case 'recent_products':
                    show(['.field-title', '.field-products', '.field-image']);
                    break;

                case 'great_saving_sales':
                    show(['.field-title', '.field-short-desc', '.field-image', '.field-products']);
                    break;
                case 'category_collection':
                    show(['.field-title', '.field-short-desc']);
                    break;
                case 'all_collections':
                    show(['.field-title']);
                    break;
            }

            function show(selectors) {
                selectors.forEach(sel => {
                    let el = document.querySelector(sel);
                    if (el) el.style.display = 'block';
                });
            }

        });
    </script>
@endsection