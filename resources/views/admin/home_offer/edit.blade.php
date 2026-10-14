<?php $page = 'homeoffer'; ?>
@extends('layout.mainlayout')

@section('content')
    @php
        $selectedProducts = json_decode($homeOffer->product_ids, true) ?? [];
    @endphp
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
        }

        .product-card.selected {
            border: 2px solid #28a745;
            background: #f0fff4;
        }

        .product-image img {
            width: 100%;
            height: 120px;
            object-fit: fill;
        }
    </style>
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
                        <!-- SEARCH -->
                        <div class="mb-3">
                            <input type="text" id="searchProduct" class="form-control"
                                placeholder="Search by name or HSN...">
                        </div>

                        <!-- PRODUCT GRID -->
                        <div class="row products-container">

                            @foreach($products as $product)

                                @php
                                    $isSelected = in_array($product->id, $selectedProducts);
                                @endphp

                                <div class="col-md-3 mb-3 product-wrapper" data-title="{{ strtolower($product->title) }}"
                                    data-hsn="{{ strtolower($product->hsn_code) }}">

                                    <div class="product-card {{ $isSelected ? 'selected' : '' }}" data-id="{{ $product->id }}">

                                        <div class="product-image">
                                            <img src="{{ asset($product->image) }}">
                                        </div>

                                        <div class="product-info mt-2">
                                            <h6>{{ $product->title }}</h6>
                                            <small>{{ $product->hsn_code }}</small>
                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </div>

                        <!-- hidden container -->
                        <div id="selectedProducts"></div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let selected = @json($selectedProducts); // 👈 load old data

        // render initial hidden inputs
        updateHiddenInputs();

        // click select
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function () {

                let id = parseInt(this.dataset.id);

                if (selected.includes(id)) {
                    selected = selected.filter(i => i !== id);
                    this.classList.remove('selected');
                } else {
                    selected.push(id);
                    this.classList.add('selected');
                }

                updateHiddenInputs();
            });
        });

        function updateHiddenInputs() {

            const container = document.getElementById('selectedProducts');
            container.innerHTML = '';

            selected.forEach(id => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = id;

                container.appendChild(input);
            });
        }


        // SEARCH
        document.getElementById("searchProduct").addEventListener("keyup", function () {

            let search = this.value.toLowerCase();

            document.querySelectorAll(".product-wrapper").forEach(item => {

                let title = item.dataset.title;
                let hsn = item.dataset.hsn;

                item.style.display =
                    (title.includes(search) || hsn.includes(search))
                        ? "block"
                        : "none";

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