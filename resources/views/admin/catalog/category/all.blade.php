<?php $page = 'sub-categories'; ?>
@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold"> Category</h4>
                        <h6>Manage your categories</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                   
                </ul>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-category"><i
                            class="ti ti-circle-plus me-1"></i>Add Category</a>
                </div>
            </div>


            <!-- /product list -->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                        </div>
                    </div>
                   
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="no-sort">
                                        S.No
                                    </th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    {{-- <th>Category</th> --}}
                                    <th>Status</th>
                                    <th>Is Feature</th>
                                    <th class="no-sort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a class="avatar avatar-md me-2">
                                                <img src="{{asset($category->image)}}" alt="product">
                                            </a>
                                        </td>
                                        <td>{{$category->title}}</td>
                                        {{-- <td>
                                            @if($category->parent_id == 0)
                                                <!--begin::Badges-->
                                                <span class="badge bg-primary">Main Category</span>

                                            @else
                                                <span class="badge bg-secondary">{{$category->parent?->title}}</span>
                                            @endif
                                        </td> --}}
                                        <td><span class="badge bg-success fw-medium fs-10">{{$category->status}}</span></td>
                                       <td>{{ $category->is_feature }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-category"
                                                    href="{{ route('admin.categories.edit', $category->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#edit-category"
                                                    data-category-id="{{ $category->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2 delete-btn"
                                                    href="javascript:void(0);" data-id="{{ $category->id }}">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>

                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
        <!-- Add Category -->
        <div class="modal fade" id="add-category">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="page-title">
                            <h4>Add Category</h4>
                        </div>
                        <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            {{-- <div class="col-lg-6 col-sm-10 col-10">
                                <div class="input-blocks">
                                    <label>Parent Category</label>
                                    @php
                                        $categories = \App\Models\Category::where([
                                            'status' => 'show',
                                            'parent_id' =>
                                                0
                                        ])->get();
                                    @endphp
                                    <select class="select" name="category_id">
                                        <option value="" selected>This is Parent Category</option>
                                        @if(isset($categories))
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <label class="form-label">Category Image (237x255 px)</label>
                            <div class="profile-pic-upload mb-3">
                                <div class="profile-pic brand-pic" id="imagePreview">
                                    <span id="uploadText">
                                        <i data-feather="plus-circle"></i> Add Image
                                    </span>
                                </div>
                                <button type="button" class="remove-btn" id="removeImage"
                                    style="display:none;">&times;</button>
                                <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;">
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Description</label>
                                <textarea type="text" class="form-control" name="description" required></textarea>
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label">Category Slug<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control">
                            </div> --}}
                            <div class="mb-0">
                                <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                    <span class="status-label">Status<span class="text-danger ms-1">*</span></span>
                                    <input type="checkbox" id="user2" class="check" checked="" name="status">
                                    <label for="user2" class="checktoggle"></label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Add Category -->
        <!-- Edit Category -->
        <div class="modal fade" id="edit-category">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="page-title">
                            <h4>Edit Category</h4>
                        </div>
                        <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit-category-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            {{-- <div class="col-lg-6 col-sm-10 col-10">
                                <div class="input-blocks">
                                    <label>Parent Category</label>
                                    @php
                                        $categories = \App\Models\Category::where([
                                            'status' => 'show',
                                            'parent_id' =>
                                                0
                                        ])->get();
                                    @endphp
                                    <select class="select" name="category_id" id="edit_parent_id">
                                        <option value="" selected>This is Parent Category</option>
                                        @if(isset($categories))
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                            <label class="form-label">Category Image (237x255 px)</label>
                            <div class="profile-pic-upload mb-3">

                                <img src="" id="editCategoryImage" style="height:100px; display:none;" />

                                <div class="profile-pic brand-pic" id="editImagePreview">
                                    <span id="editUploadText">
                                        <i data-feather="plus-circle"></i> Add Image
                                    </span>
                                </div>

                                <button type="button" class="remove-btn" id="editRemoveImage"
                                    style="display:none;">&times;</button>

                                <input type="file" name="image" id="editImageInput" accept="image/*" style="display:none;">
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Description</label>
                                <textarea type="text" class="form-control" name="description" id="edit_description"
                                    required></textarea>
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label">Category Slug<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control">
                            </div> --}}
                            <div class="mb-0">
                                <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                    <span class="status-label">Status<span class="text-danger ms-1">*</span></span>
                                    <input type="checkbox" id="edit_status" class="check" name="status">
                                    <label for="edit_status" class="checktoggle"></label>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                    <span class="status-label">IS Feature<span class="text-danger ms-1">*</span></span>
                                    <input type="checkbox" id="edit_is_feature" class="check" name="is_feature">
                                    <label for="edit_is_feature" class="checktoggle"></label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Edit Category -->
        <!-- Delete Category Modal -->
        <div class="modal fade" id="delete-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Category</h5>
                        <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-3">Are you sure you want to delete this category?</p>
                        <input type="hidden" id="deleteCategoryId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2026 &copy; {{ $site->site_name ?? ' ' }}. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">ThiVen</a></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.edit-category', function (event) {
            event.preventDefault();

            var categoryId = $(this).data('category-id');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#category-id').val(response.category.parent_id).prop('selected', true).trigger('change');
                    $('#edit_title').val(response.category.title);
                    $('#edit_description').val(response.category.description);
                    $('#edit_status').prop('checked', response.category.status === 'show');
                    $('#edit_is_feature').prop('checked', response.category.is_feature === 'yes');

                    if (response.category.image) {

                        var imageUrl = "{{ asset('') }}" + response.category.image;

                        $('#editCategoryImage')
                            .attr('src', imageUrl)
                            .show();

                        $('#editUploadText').hide();
                        $('#editRemoveImage').show();

                    } else {

                        $('#editCategoryImage')
                            .attr('src', '')
                            .hide();

                        $('#editUploadText').show();
                        $('#editRemoveImage').hide();
                    }

                    $('#edit-category-form').attr('action', response.update_url);

                    console.log($('#edit-category-form').attr('action'));
                    $('#edit-category').modal('show');
                },
                error: function () {
                    alert('Failed to fetch category details.');
                }
            });
        });

        // Open file selector
        $('#editImagePreview').on('click', function () {
            $('#editImageInput').click();
        });

        // When file selected
        $('#editImageInput').on('change', function (event) {
            let file = event.target.files[0];

            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {

                    $('#editCategoryImage')
                        .attr('src', e.target.result)
                        .show();

                    $('#editUploadText').hide();
                    $('#editRemoveImage').show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image
        $('#editRemoveImage').on('click', function () {
            $('#editCategoryImage')
                .attr('src', '')
                .hide();

            $('#editUploadText').show();
            $('#editImageInput').val('');
            $(this).hide();
        });
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
            let deleteId = null;

            // Capture ID from button
            $(document).on('click', '.delete-btn', function () {
                deleteId = $(this).data('id');
                $('#deleteCategoryId').val(deleteId);
            });

            // Confirm Delete
            $('#confirmDeleteBtn').on('click', function () {
                let id = $('#deleteCategoryId').val();
                if (!id) return;

                $.ajax({
                    url: '/admin/categories/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        $('#delete-modal').modal('hide');
                        $('a.delete-btn[data-id="' + id + '"]').closest('tr').fadeOut();
                        location.reload();
                    },
                    error: function (xhr) {
                        alert('Failed to delete category.');
                    }
                });
            });
        });

    </script>

@endsection