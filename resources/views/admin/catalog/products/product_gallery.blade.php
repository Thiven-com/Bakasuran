<?php $page = 'product-list'; ?>
@extends('layouts.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('admin.components.breadcrumb')
                @slot('title')
                    Product Gallery List
                @endslot
                @slot('li_1')
                    Manage your products Gallery
                @endslot
                @slot('li_2')
                    {{ route('admin.products.create') }}
                @endslot
                @slot('li_3')
                    Add New Product Gallery
                @endslot
                {{-- @slot('li_4')
                    Import Product
                @endslot --}}
            @endcomponent

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        {{-- <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ asset('admin/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div> --}}
                        {{-- <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>14 09 23</option>
                                <option>11 09 23</option>
                            </select>
                        </div> --}}
                    </div>
                    <!-- /Filter -->
                    {{-- <div class="card mb-0" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="box" class="info-img"></i>
                                                <select class="select">
                                                    <option>Choose Product</option>
                                                    <option>
                                                        Lenovo 3rd Generation</option>
                                                    <option>Nike Jordan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="stop-circle" class="info-img"></i>
                                                <select class="select">
                                                    <option>Choose Categroy</option>
                                                    <option>Laptop</option>
                                                    <option>Shoe</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="git-merge" class="info-img"></i>
                                                <select class="select">
                                                    <option>Choose Sub Category</option>
                                                    <option>Computers</option>
                                                    <option>Fruits</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="stop-circle" class="info-img"></i>
                                                <select class="select">
                                                    <option>All Brand</option>
                                                    <option>Lenovo</option>
                                                    <option>Nike</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i class="fas fa-money-bill info-img"></i>
                                                <select class="select">
                                                    <option>Price</option>
                                                    <option>$12500.00</option>
                                                    <option>$12500.00</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                        class="feather-search"></i> Search </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    {{-- <th>Category</th>
                                    <th>Brand</th>
                                    <th>Variation</th>
                                    <th>Price</th>
                                    <th>Sale Price</th>
                                    <th>Stock</th> --}}
                                    {{-- <th>Unit</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Created by</th> --}}
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product_galleries  as $product_gallery)


                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>

                                    {{-- <td>
                                        <img src="{{ asset($product_gallery->image) }}" alt="gallery" style="height: 20px;">
                                    </td> --}}
                                    <td>
                                        <div class="productimgname">
                                            <a href="javascript:void(0);" class="product-img stock-img">
                                                <img src="{{ asset($product_gallery->image) }}"
                                                    alt="product">
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $product_gallery->product->product_name ?? '' }}</td>

                                    {{-- <td>
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="{{ asset('admin/build/img/users/user-30.jpg') }}" alt="product">
                                            </a>
                                            <a href="javascript:void(0);">Arroon</a>
                                        </div>
                                    </td> --}}
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            {{-- <a class="me-2 edit-icon  p-2" href="{{ url('product-details') }}">
                                                <i data-feather="eye" class="feather-eye"></i>
                                            </a> --}}
                                            {{-- <a class="me-2 p-2" href="{{ route('admin.productGallery.edit',$product_gallery->id) }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a> --}}
                                            {{-- <a class="confirm-text p-2" href="javascript:void(0);">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a> --}}
                                            <a class="me-2 p-2 edit-gallery"
                                                href="{{ route('admin.productGallery.edit', $product_gallery->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-gallery"
                                                data-gallery-id="{{ $product_gallery->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                            </a>

                                            {{-- <a class="confirm-texts p-2" href="javascript:void(0);" >
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a> --}}
                                            <a href="javascript:void(0);" class="confirm-texts p-2 delete-gallery" data-id="{{ $product_gallery->id }}" data-url="{{ route('admin.productGallery.destroy', $product_gallery->id) }}">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).on('click', '.edit-gallery', function (event) {
            event.preventDefault();

            var customer = $(this).data('gallery-id');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    // $('#s-name').val(response.supplier.first_name);

                    $('#product_name').val(response.ProductGallery.product_id).prop('selected', true).trigger('change');

                    if (response.ProductGallery.image) {
                        var imageUrl = "{{ url('/') }}/" + response.ProductGallery.image;
                        $('#editSupplierImage').attr('src', imageUrl).show();
                        $('#editUploadText').hide();
                    } else {
                        $('#editSupplierImage').hide();
                        $('#editUploadText').show();
                    }

                    $('#edit-gallery-form').attr('action', response.update_url);

                    // console.log($('#edit-supplier-form').attr('action'));
                    // console.log('Modal should open now');
                    $('#edit-gallery').modal('show');
                },
                error: function () {
                    console.log('Error:', xhr.responseText);
                    alert('Failed to fetch Product Gallery details.');
                }
            });
        });

        $(document).on('click', '.confirm-texts', function () {
            var requestId = $(this).data('id');
            var deleteUrl = $(this).data('url');

            var confirmation = confirm('Are you sure you want to delete this Product Gallery?');

            if (confirmation) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert(xhr.responseJSON.message);
                    }
                });
            } else {
                console.log('Deletion canceled');
            }
        });

        $(document).on('change', '#file-input', function(event) {
            var fileName = event.target.files[0] ? event.target.files[0].name : '';
            $('#file-name').text(fileName ? 'Selected File: ' + fileName : '');
        });

        $(document).ready(function () {
            $('#editImageInput').on('change', function (event) {
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#editSupplierImage').attr('src', e.target.result).show();
                        $('#editUploadText').hide();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#removeEditImage').on('click', function () {
                $('#editSupplierImage').hide();
                $('#editUploadText').show();
                $('#editImageInput').val('');
            });

            $('#editUploadText').on('click', function () {
                $('#editImageInput').click();
            });
        });
        // $(".confirm-text").on("click", function () {
        //   Swal.fire({
        //     title: "Are you sure?",
        //     text: "You won't be able to revert this!",
        //     type: "warning",
        //     showCancelButton: !0,
        //     confirmButtonColor: "#3085d6",
        //     cancelButtonColor: "#d33",
        //     confirmButtonText: "Yes, delete it!",
        //     confirmButtonClass: "btn btn-primary",
        //     cancelButtonClass: "btn btn-danger ml-1",
        //     buttonsStyling: !1,
        //   }).then(function (t) {
        //     t.value &&
        //       Swal.fire({
        //         type: "success",
        //         title: "Deleted!",
        //         text: "Your file has been deleted.",
        //         confirmButtonClass: "btn btn-success",
        //       });
        //   });
        // }),
    </script>
    <script>
    $(document).ready(function (){
        $('#add-product-gallery').on('hidden.bs.modal', function (){

            $(this).find('form')[0].reset();
            $(this).find('select.select').val('').trigger('change');

            $('#imageInput').val('');
            $('#imagePreview').html('<span id="uploadText"><i data-feather="plus-circle"></i> Add Image</span>');

            feather.replace();

            $('#removeImage').hide();

        })
    })


    </script>
@endsection
