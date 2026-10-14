<?php $page = 'sellers'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Sellers List
                @endslot
                @slot('li_1')
                    Manage Your Sellers
                @endslot
                @slot('li_2')
                    Add New Sellers
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
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ asset('admin/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                        </div>
                    </div>
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
                                    <th>Seller Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    {{-- <th>Address</th> --}}
                                    <th>KYC Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sellers as $seller)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="productimgname">
                                            {{-- <a href="javascript:void(0);" class="product-img supplier-img">
                                                <img src="{{ asset($user->profile_pic) }}" style="height:25px; width:25px;"
                                                    alt="product">
                                            </a> --}}
                                            <div>
                                                <a href="javascript:void(0);" class="ms-2"> {{ $seller->name }}</a>
                                            </div>

                                        </div>
                                    </td>
                                    {{-- <td>201</td> --}}
                                    <td> {{ $seller->email }} </td>
                                    <td> {{ $seller->mobile }} </td>
                                    {{-- <td> {{ $seller->address ?? '-' }} </td> --}}
                                    <td> {{ $seller->kyc_status ?? 'N/A' }} </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            {{-- <a class="me-2 p-2 mb-0" href="javascript:void(0);">
                                                <i data-feather="eye" class="action-eye"></i>
                                            </a> --}}
                                            {{-- <a class="me-2 p-2 mb-0" data-bs-toggle="modal" data-bs-target="#edit-units">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a> --}}
                                            <a class="me-2 p-2 edit-staff"
                                                href="{{ route('sellers.edit', $seller->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-seller"
                                                data-staff-id="{{ $seller->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-texts p-2" href="javascript:void(0);" data-id="{{ $seller->id }}" data-url="{{ route('sellers.destroy', $seller->id) }}">
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


    @if (Route::is(['sellers.index']))
        <!-- Add Category -->
        <div class="modal fade" id="add-seller">
            <div class="modal-dialog modal-dialog-centered custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Create Seller</h4>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body custom-modal-body">
                                <form action="{{ route('sellers.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mobile</label>
                                        <input type="text" class="form-control" name="mobile">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">KYC Status</label>
                                        <select class="select" name="kyc_status">
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="cancelled">Canceled</option>
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
                                        <button type="submit" class="btn btn-submit">Create Seller</button>
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









    </script>
@endsection


