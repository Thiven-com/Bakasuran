<?php $page = 'add-homeoffer'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Home Offer</h4>
                        <h6>Manage your Home Offer</h6>
                    </div>
                </div>
            </div>
            <!-- /add -->
            <form action="{{route('admin.homeoffers.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span> Home Offer
                                                    Information</span>
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 add-blog">
                                                    <label class="form-label">Title<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="title"
                                                        value="{{ old('title') }}" placeholder="Title" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 add-blog">
                                                    <label class="form-label">Offer<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="offer"
                                                        value="{{ old('offer') }}" placeholder="UPTO 40% Off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-card-one accordion mt-3" id="accordionExample2">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingTwo">

                                </div>
                                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">

                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                                aria-labelledby="pills-home-tab">


                                                <div class="accordion-card-one accordion" id="accordionExample3">
                                                    <div class="accordion-item">
                                                        <div class="accordion-header" id="headingThree">
                                                            <div class="accordion-button" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseThree"
                                                                aria-controls="collapseThree">
                                                                <div class="addproduct-icon list">
                                                                    <h5><i data-feather="image" class="add-info"></i><span>
                                                                            Image (920x600 px)</span></h5>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="collapseThree" class="accordion-collapse collapse show"
                                                            aria-labelledby="headingThree"
                                                            data-bs-parent="#accordionExample3">
                                                            <div class="accordion-body">
                                                                <div class="text-editor add-list add">
                                                                    <div class="col-lg-12">
                                                                        <div class="add-choosen">
                                                                            <div class="row">
                                                                                <!-- FIRST IMAGE -->
                                                                                <div class="col-md-6">
                                                                                    <div class="profile-pic-upload">
                                                                                        <div class="profile-pic brand-pic"
                                                                                            id="imagePreview"
                                                                                            style="background-image: url('');background-size: cover;background-position: center;">Select Image
                                                                                        </div>

                                                                                        <button type="button"
                                                                                            class="remove-btn"
                                                                                            id="removeImage">&times;</button>
                                                                                        <input type="hidden"
                                                                                            name="remove_image"
                                                                                            id="remove_image" value="0">

                                                                                        <input type="file" name="image"
                                                                                            style="display:none;"
                                                                                            id="imageInput"
                                                                                            accept="image/*">
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="mb-3 field-products">
                                                <label>Products</label>

                                                <select name="product_ids[]" class="form-control select2" multiple>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', [])) ? 'selected' : '' }}>

                                                            {{ $product->title ?? 'No Title' }} ({{ $product->hsn_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
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
                        <a href="{{ route('admin.homeoffers.index') }}" class="btn btn-cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-submit">Save Home Offer</button>
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
        //image
        document.addEventListener("DOMContentLoaded", function () {

            const preview = document.getElementById("imagePreview");
            const removeBtn = document.getElementById("removeImage");
            let inputFile = document.getElementById("imageInput");
            const removeInput = document.getElementById("remove_image");

            let isSelectingFile = false; // 🔐 prevents dialog reopening

            // Check if image already exists
            function isImagePresent() {
                const bg = window.getComputedStyle(preview).backgroundImage;
                return bg && bg !== "none";
            }

            // Bind change event
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

                        // allow clicking again AFTER image fully loaded
                        setTimeout(() => {
                            isSelectingFile = false;
                        }, 200);
                    };

                    reader.readAsDataURL(file);

                    removeInput.value = 0;
                });
            }

            bindFileChange();

            // Remove image
            removeBtn.addEventListener("click", function (e) {
                e.preventDefault();

                preview.style.backgroundImage = "none";
                preview.innerHTML = '<span id="uploadText"><i data-feather="plus-circle"></i> Add Image</span>';
                preview.classList.remove("has-image");

                // reset input (important)
                const newInput = inputFile.cloneNode(true);
                inputFile.parentNode.replaceChild(newInput, inputFile);
                inputFile = newInput;

                bindFileChange();

                removeInput.value = 1;
            });

            // Open file picker ONLY when allowed
            preview.addEventListener("click", function () {

                if (isSelectingFile) return;
                if (isImagePresent()) return;

                isSelectingFile = true;
                inputFile.click();
            });

        });
    </script>
@endsection