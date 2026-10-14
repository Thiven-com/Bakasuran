<?php $page = 'homeoffer'; ?>
@extends('layout.mainlayout')

@section('content')
    @php
        $selectedProducts = json_decode($homeOffer->product_ids, true) ?? [];
    @endphp
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Edit Home Offer</h4>
                        <h6>Update your Home Offer</h6>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.homeoffers.update', $homeOffer->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body add-product pb-0">

                        <!-- Basic Info -->
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        <h5>Home Offer Information</h5>
                                    </div>
                                </div>

                                <div id="collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <label class="form-label">Title *</label>
                                                <input type="text" name="title" class="form-control"
                                                    value="{{ old('title', $homeOffer->title) }}" required>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label">Offer *</label>
                                                <input type="text" name="offer" class="form-control"
                                                    value="{{ old('offer', $homeOffer->offer) }}" required>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="accordion-card-one accordion mt-3">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseImage">
                                        <h5>Image (920x600 px)</h5>
                                    </div>
                                </div>

                                <div id="collapseImage" class="accordion-collapse collapse show">
                                    <div class="accordion-body">

                                        <div class="profile-pic-upload">
                                            <div class="profile-pic brand-pic" id="imagePreview" style="background-image: url('{{ $homeOffer->image ? asset($homeOffer->image) : '' }}');
                                                           background-size: cover;
                                                           background-position: center;">

                                                @if(!$homeOffer->image)
                                                    Select Image
                                                @endif
                                            </div>

                                            <button type="button" class="remove-btn" id="removeImage">&times;</button>

                                            <input type="hidden" name="remove_image" id="remove_image" value="0">

                                            <input type="file" name="image" id="imageInput" style="display:none;"
                                                accept="image/*">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="mt-3">
                            <label>Products</label>
                            @php
                                $productIds = json_decode($homeOffer->product_ids, true) ?? [];
                            @endphp
                            <select name="product_ids[]" class="form-control select2" multiple>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', $productIds)) ? 'selected' : '' }}>

                                        {{ $product->title ?? 'No Title' }} ({{ $product->hsn_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-3">
                    <a href="{{ route('admin.homeoffers.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Home Offer</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <!-- Image Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const preview = document.getElementById("imagePreview");
            const removeBtn = document.getElementById("removeImage");
            let inputFile = document.getElementById("imageInput");
            const removeInput = document.getElementById("remove_image");

            let isSelectingFile = false;

            function isImagePresent() {
                const bg = window.getComputedStyle(preview).backgroundImage;
                return bg && bg !== "none";
            }

            function bindFileChange() {
                inputFile.addEventListener("change", function (e) {

                    const file = e.target.files[0];
                    if (!file) {
                        isSelectingFile = false;
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (event) {

                        preview.style.backgroundImage = "url('" + event.target.result + "')";
                        preview.innerHTML = "";
                        preview.classList.add("has-image");

                        setTimeout(() => {
                            isSelectingFile = false;
                        }, 200);
                    };

                    reader.readAsDataURL(file);
                    removeInput.value = 0;
                });
            }

            bindFileChange();

            removeBtn.addEventListener("click", function (e) {
                e.preventDefault();

                preview.style.backgroundImage = "none";
                preview.innerHTML = 'Select Image';
                preview.classList.remove("has-image");

                const newInput = inputFile.cloneNode(true);
                inputFile.parentNode.replaceChild(newInput, inputFile);
                inputFile = newInput;

                bindFileChange();
                removeInput.value = 1;
            });

            preview.addEventListener("click", function () {
                if (isSelectingFile) return;
                if (isImagePresent()) return;

                isSelectingFile = true;
                inputFile.click();
            });

        });
    </script>

@endsection