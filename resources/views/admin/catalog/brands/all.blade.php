<?php $page = 'brand-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Brand</h4>
                        <h6>Manage your brands</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    
                </ul>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-brand"><i
                            class="ti ti-circle-plus me-1"></i>Add Brand</a>
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
                                    <th>Brand</th>
                                    <th>No Of Products</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar avatar-md bg-light-900 p-1 me-2">
                                                    <img class="object-fit-contain" src="{{asset($brand->image)}}" alt="img">
                                                </a>
                                                <a href="javascript:void(0);">{{$brand->name}}</a>
                                            </div>
                                        </td>
                                        <td> {{ count($brand->products) }} </td>
                                        <td><span class="badge table-badge bg-success fw-medium fs-10">{{$brand->status}}</span>
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-brand"
                                                    href="{{ route('admin.brands.edit', $brand->id) }}" data-bs-toggle="modal"
                                                    data-bs-target="#edit-brand" data-brand-id="{{ $brand->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a data-bs-toggle="modal" data-id="{{ $brand->id }}"
                                                    data-bs-target="#delete-modal" class="p-2 open-delete-brand-modal"
                                                    href="javascript:void(0);">
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
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2026 &copy; {{ $site->site_name ?? ' '  }}. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">ThiVen</a></p>
        </div>
    </div>
    <!-- Add Brand -->
    <div class="modal fade" id="add-brand">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>Add Brand</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.brands.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body new-employee-field">
                        <label class="form-label">Brand Logo</label>
                        <div class="profile-pic-upload">
                            <div class="profile-pic brand-pic" id="imagePreview">
                                <span id="uploadText"> <i data-feather="plus-circle"></i> Add Image</span>
                            </div>
                            <button type="button" class="remove-btn" id="removeImage">&times;</button>
                            <input style="display:none;" type="file" name="image" class="image-upload-file" id="imageInput"
                                accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-0">
                            <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                <span class="status-label">Status</span>
                                <input type="checkbox" id="user2" class="check" checked="" name="status">
                                <label for="user2" class="checktoggle"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add Brand -->
    <div class="modal fade" id="edit-brand">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>Edit Brand</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit-brand-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <label class="form-label">Image</label>
                        <div class="profile-pic-upload mb-3">

                            <img src="" id="editBrandImage" style="height:100px;width:100px;display:none;" />

                            <div class="profile-pic brand-pic" id="editImagePreview">
                                <span id="editUploadText">
                                    <i data-feather="plus-circle"></i> Add Image
                                </span>
                            </div>

                            <button type="button" class="remove-btn" id="editRemoveImage"
                                style="display:none;">&times;</button>

                            <input type="file" name="image" id="editImageInput" accept="image/*" style="display:none;">
                        </div>
                        <div class="mb-0">
                            <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                <span class="status-label">Status<span class="text-danger ms-1">*</span></span>
                                <input type="checkbox" id="edit_status" class="check" name="status">
                                <label for="edit_status" class="checktoggle"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Brand Modal -->
    <div class="modal fade" id="deleteBrandModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Brand</h5>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">Are you sure you want to delete this brand?</p>
                    <input type="hidden" id="deleteBrandId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBrandBtn" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.edit-brand', function (event) {
            event.preventDefault();

            var categoryId = $(this).data('category-id');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_name').val(response.brand.name);
                    $('#edit_status').prop('checked', response.brand.status === 'show');
                    if (response.brand.image) {

                        var imageUrl = "{{ asset('') }}" + response.brand.image;

                        $('#editBrandImage')
                            .attr('src', imageUrl)
                            .show();

                        $('#editUploadText').hide();
                        $('#editRemoveImage').show();

                    } else {

                        $('#editBrandImage')
                            .attr('src', '')
                            .hide();

                        $('#editUploadText').show();
                        $('#editRemoveImage').hide();
                    }

                    $('#edit-brand-form').attr('action', response.update_url);

                    console.log($('#edit-brand-form').attr('action'));
                    $('#edit-brand').modal('show');
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

                    $('#editBrandImage')
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
            $('#editBrandImage')
                .attr('src', '')
                .hide();

            $('#editUploadText').show();
            $('#editImageInput').val('');
            $(this).hide();
        });
        $(document).on("click", ".open-delete-brand-modal", function () {
            let brandId = $(this).data("id");
            $("#deleteBrandId").val(brandId);
            $("#deleteBrandModal").modal("show");
        });

        $("#confirmDeleteBrandBtn").on("click", function () {
            let brandId = $("#deleteBrandId").val();
            let row = $("button[data-id='" + brandId + "']").closest("tr");

            $.ajax({
                url: "/admin/brands/" + brandId,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $("#deleteBrandModal").modal("hide");
                    row.fadeOut(500, function () {
                        $(this).remove();
                    });
                    location.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message ?? "Something went wrong!");
                }
            });
        });
    </script>
@endsection