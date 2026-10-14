<?php $page = 'product-list'; ?>
@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Today Deals List</h4>
                        <h6>Manage your Today Deals</h6>
                    </div>
                </div>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-deal"><i
                            class="ti ti-circle-plus me-1"></i>Add Deal</a>
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
                                    <th>Product Details </th>
                                    <th>Category</th>
                                    <th>Product Code</th>
                                    <th class="no-sort text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $product)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        {{-- <td>PT001 </td> --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar avatar-md me-2">
                                                    <img src="{{asset($product->image)}}" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">{{$product->product->title ?? ''}} </a>
                                            </div>
                                        </td>
                                        <td>{{$product->category->title ?? '--'}}</td>
                                        <td>{{$product->sku ?? '--'}}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="p-2 delete-btn" href="javascript:void(0);"
                                                    data-id="{{ $product->id }}"
                                                    data-url="{{ route('admin.deleteTodaySale', $product->id) }}">
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

    <div class="modal fade" id="add-deal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>Add Deal</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.updateTodayDeal')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body new-employee-field">
                        <div class="mb-3">
                            <label class="form-label">Select Product<span class="text-danger ms-1">*</span></label>
                            <select class="form-control select2" name="variant_id" id="productSelect" required>
                                <option value="">Search Product</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->product->title ?? '' }} ({{ $variant->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Deal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#productSelect').select2({
                placeholder: "Search Product",
                allowClear: true,
                dropdownParent: $('#add-deal') // important if inside modal
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete-btn', function () {

            let url = $(this).data('url');

            if (confirm('Remove this today sale?')) {

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {

                        if (response.status) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }

                    }
                });

            }

        });
    </script>

@endsection