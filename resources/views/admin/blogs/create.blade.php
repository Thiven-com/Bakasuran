<?php $page = 'add-blog'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Blogs</h4>
                        <h6>Manage your blogs</h6>
                    </div>
                </div>
            </div>
            <!-- /add -->
            <form action="{{route('admin.blogs.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span> Blog Information</span>
                                            </h5>
                                            {{-- <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a> --}}
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-blog">
                                                    <label class="form-label">Title<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="title" name="title"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="input-blocks add-blog">
                                                    <label>Slug<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control list" id="slug" name="slug"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-blog">
                                                    <label class="form-label">Blog Category<span
                                                            style="color:red;">*</span></label>
                                                    <select class="select" name="category_id">
                                                        <option value="">Choose Blog Category</option>
                                                        @foreach ($blog_categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-12 col-sm-12 col-12">
                                            <div class="mb-3 add-blog">
                                                <label class="form-label">Short Description<span
                                                        style="color:red;">*</span></label>
                                                <textarea class="form-control" name="short_description"
                                                    id="short_Description" required></textarea>

                                            </div>
                                        </div>


                                        <!-- Editor -->
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description<span style="color:red;">*</span></label>
                                                <textarea class="form-control h-100" id="summernote" name="description"
                                                    required></textarea>
                                                <p class="mt-1">Maximum 60 Characters</p>
                                            </div>
                                        </div>
                                        <!-- /Editor -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-card-one accordion mt-3" id="accordionExample2">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingTwo">
                                    {{-- <div class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-controls="collapseTwo">
                                    </div> --}}
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
                                                                            Images</span></h5>
                                                                    {{-- <a href="javascript:void(0);"><i
                                                                            data-feather="chevron-down"
                                                                            class="chevron-down-add"></i></a> --}}
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
                                                                                            style="background-image: url('{{ isset($blog->image) ? asset($blog->image) : '' }}');
                                                                                                                        background-size: cover;
                                                                                                                        background-position: center;">

                                                                                            @if(empty($blog->image))
                                                                                                <span id="uploadText">
                                                                                                    <i
                                                                                                        data-feather="plus-circle"></i>
                                                                                                    Add Image
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>

                                                                                        <button type="button"
                                                                                            class="remove-btn"
                                                                                            id="removeImage">&times;</button>

                                                                                        <input type="file" name="image"
                                                                                            style="display:none;"
                                                                                            id="imageInput"
                                                                                            accept="image/*">
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Banner-->
                                                                                <div class="col-md-6">
                                                                                    <div class="profile-pic-upload">
                                                                                        <div class="profile-pic brand-pic"
                                                                                            id="bannerPreview"
                                                                                            style="background-image: url('{{ isset($blog->banner) ? asset($blog->banner) : '' }}');
                                                                                                                        background-size: cover;
                                                                                                                        background-position: center;">

                                                                                            @if(empty($blog->banner))
                                                                                                <span id="uploadText2">
                                                                                                    <i
                                                                                                        data-feather="plus-circle"></i>
                                                                                                    Add Banner
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>

                                                                                        <button type="button"
                                                                                            class="remove-banner"
                                                                                            id="removeBanner">&times;</button>

                                                                                        <input type="file" name="banner"
                                                                                            style="display:none;"
                                                                                            id="bannerInput"
                                                                                            accept="image/*">
                                                                                        <input type="hidden"
                                                                                            name="remove_banner"
                                                                                            id="remove_banner" value="0">
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
                                            <div class="col-lg-6 col-sm-6 col-12 ">
                                                <div class="mb-3">
                                                    <label class="form-label">Tags</label>
                                                    <input type="text" class="form-control" name="tags" required>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="select" name="status">
                                                        <option>Show</option>
                                                        <option>Hide</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12 ">
                                                <div class="mb-3">
                                                    <label class="form-label">Instagram</label>
                                                    <input type="text" class="form-control" name="instagram">

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12 ">
                                                <div class="mb-3">
                                                    <label class="form-label">Facebook</label>
                                                    <input type="text" class="form-control" name="facebook">

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12 ">
                                                <div class="mb-3">
                                                    <label class="form-label">Youtube</label>
                                                    <input type="text" class="form-control" name="youtube">

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
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-submit">Save Blog</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let titleInput = document.getElementById("title");
            let slugInput = document.getElementById("slug");

            titleInput.addEventListener("input", function () {
                let title = titleInput.value;
                let slug = title
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-'); // Remove multiple hyphens

                slugInput.value = slug;
                console.log("Generated Slug:", slug); // Debugging
            });
        });


        //banner
        document.addEventListener("DOMContentLoaded", function () {

            const preview = document.getElementById("bannerPreview");
            const removeBtn = document.getElementById("removeBanner");
            const inputFile = document.getElementById("bannerInput");
            const removeVal = document.getElementById("remove_banner");

            // Open file chooser
            preview.addEventListener("click", function () {
                inputFile.click();
            });

            // Select Banner
            inputFile.addEventListener("change", function () {

                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.style.backgroundImage = "url('" + e.target.result + "')";
                    preview.innerHTML = "";
                };

                reader.readAsDataURL(file);

                removeVal.value = 0;
            });

            // Remove Banner
            removeBtn.addEventListener("click", function () {

                preview.style.backgroundImage = "none";
                preview.innerHTML = "<span>Add Banner</span>";
                inputFile.value = "";

                removeVal.value = 1;
            });

        });

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
                // inputFile.click();
            });

        });
    </script>
@endsection