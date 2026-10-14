<?php $page = 'units'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Units
                @endslot
                @slot('li_1')
                    Manage your Units
                @endslot
                @slot('li_2')
                    Add New Units
                @endslot
            @endcomponent

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        {{-- <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ asset('admin/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                        </div> --}}
                        {{-- <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div> --}}
                    </div>
                    <!-- /Filter -->
                    {{-- <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Customer Name</option>
                                            <option>Benjamin</option>
                                            <option>Ellen</option>
                                            <option>Freda</option>
                                            <option>Kaitlin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="globe" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Country</option>
                                            <option>India</option>
                                            <option>USA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    {{-- <th>Email</th>
                                    <th>Mobile</th> --}}
                                    {{-- <th>Address</th> --}}
                                    {{-- <th>Join Date</th> --}}
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $unit->name }}</td>
                                        <td>{{ $unit->status }}</td>
                                       <td>{{ \Carbon\Carbon::parse($unit->created_at)->format('d-m-Y') }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-unit"
                                                    href="{{ route('admin.units.edit', $unit->id) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#edit-unit"
                                                    data-unit-id="{{ $unit->id }}">
                                                        <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a class="confirm-texts p-2" href="javascript:void(0);" data-id="{{ $unit->id }}" data-url="{{ route('admin.units.destroy', $unit->id) }}">
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
    </div>

    @if (Route::is(['units.index']))
        <!-- Add Category -->
        <div class="modal fade" id="add-seller">
            <div class="modal-dialog modal-dialog-centered custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Create Unit</h4>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body custom-modal-body">
                                <form action="{{ route('admin.units.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="select" name="status">
                                            <option value="show">Show</option>
                                            <option value="hide">Hide</option>
                                        </select>
                                    </div>
                                    {{-- <div class="mb-3">
                                    <label class="form-label">Profile Pic</label>
                                        <div class="profile-pic-upload mb-3 mt-3">
                                            <div class="profile-pic-upload">
                                                <div class="profile-pic brand-pic" id="imagePreview">
                                                    <span id="uploadText"> <i data-feather="plus-circle"></i> Add Image</span>
                                                </div>
                                                <button class="remove-btn" id="removeImage">&times;</button>
                                                <input style="display:none;" type="file" name="profile_pic" class="image-upload-file" id="imageInput" accept="image/*">
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="modal-footer-btn">
                                        <button type="reset" class="btn btn-cancel me-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-submit">Create Unit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Category -->

        <!-- Edit Customer -->
        <div class="modal fade" id="edit-customer" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryLabel">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-customer-form" method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="customer-name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="customer-email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" class="form-control" name="mobile" id="customer-mobile" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="customer-address">
                            </div>

                            <div class="profile-pic-upload mb-3">
                                <div class="profile-pic-upload">
                                    <div class="profile-pic brand-pic" id="editImagePreview">
                                        <img id="editCategoryImage" src="" alt="Category Image"
                                            style="width: 100px; height: 100px; object-fit: cover; display: none;">
                                        <span id="editUploadText"><i data-feather="plus-circle"></i> Add Image</span>
                                    </div>
                                    <button type="button" class="remove-btn" id="removeEditImage">&times;</button>
                                    <input style="display: none;" type="file" name="profile_pic" class="image-upload-file"
                                        id="editImageInput" accept="image/*">
                                </div>
                            </div>

                            <div class="modal-footer-btn">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-submit">Save Changes</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>

        </div>

        <!-- /Edit Category -->
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
    </script>

@endsection
