<?php $page = 'brand-list'; ?>
@extends('layouts.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('admin.components.breadcrumb')
                @slot('title')
                   Coupons
                @endslot
                @slot('li_1')
                    Manage your Coupons
                @endslot
                {{-- @slot('li_2')
                    Add New Brand
                @endslot --}}
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
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ asset('admin/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
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
                                        <i data-feather="zap" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Brand</option>
                                            <option>Lenevo</option>
                                            <option>Boat</option>
                                            <option>Nike</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="calendar" class="info-img"></i>
                                        <div class="input-groupicon">
                                            <input type="text" class="datetimepicker" placeholder="Choose Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Status</option>
                                            <option>Active</option>
                                            <option>Inactive</option>
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
                        <table class="table  datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th class="no-sort">
                                        S.no
                                       </th>
                                       <th>Title</th>
                                       <th>Description</th>
                                       <th>Code</th>
                                       {{-- <th>Product</th> --}}
                                       {{-- <th>Category</th> --}}
                                       <th>Minimum Purchase</th>
                                       <th>Discount</th>
                                       <th>Discount Type</th>
                                       <th>Start Date</th>
                                       <th>End Date</th>
                                       <th>Status</th>
                                       <th class="no-sort">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i=1;
                            @endphp
                                @foreach ($data as $Coupon)

                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{$i}}</td>
                                    <td>{{$Coupon->title ?? ""}}</td>
                                    <td>{{$Coupon->description ?? ""}}</td>
                                    <td>{{$Coupon->code ?? ""}}</td>
                                    <td>{{$Coupon->min_purchace ?? ""}}</td>
                                    <td>{{$Coupon->discount ?? ""}}</td>
                                    <td>{{$Coupon->discount_type ?? ""}}</td>
                                    <td>{{$Coupon->start_date ?? ""}}</td>
                                    <td>{{$Coupon->end_date ?? ""}}</td>
                                    <td>{{$Coupon->status ?? ""}}</td>
                                      <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-coupon"
                                                href="{{ route('admin.coupons.edit', $Coupon->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-coupon"
                                                data-coupon-id="{{ $Coupon->id }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-texts p-2" href="javascript:void(0);" data-id="{{ $Coupon->id }}" data-url="{{ route('admin.coupons.destroy', $Coupon->id) }}">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                $i++;
                            @endphp
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

        $(document).on('click', '.edit-coupon', function (event) {
            console.log("click on edit coupon ");
            event.preventDefault();

            var coupon = $(this).data('coupon-id');
            console.log("coupon", coupon);
            var url = $(this).attr('href');
            console.log("URL", url);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    console.log("response", response);
                    $('#coupon-title').val(response.coupon.title);
                    $('#coupon-description').val(response.coupon.description);
                    $('#coupon-type').val(response.coupon.type);
                    $('#coupon-code').val(response.coupon.code);
                    $('#coupon-min_purchase').val(response.coupon.min_purchase);
                    $('#coupon-discount').val(response.coupon.discount);
                    $('#coupon-discount_type').val(response.coupon.discount_type);
                    $('#coupon-start-date').val(response.coupon.start_date);
                    $('#coupon-end-date').val(response.coupon.end_date);
                    $('#coupon-status').val(response.coupon.status);

                    $('#edit-coupon-form').attr('action', response.update_url);

                    console.log($('#edit-coupon-form').attr('action'));
                    console.log("update url", response.update_url);
                    $('#edit-coupon').modal('show');
                },
                error: function () {
                    alert('Failed to fetch coupon details.');
                }
            });
        });


        $(document).on('click', '.confirm-texts', function () {
            var requestId = $(this).data('id');
            var deleteUrl = $(this).data('url');

            var confirmation = confirm('Are you sure you want to delete this Coupon?');

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
    </script>
    <script>

        document.getElementById('coupon-status').addEveneListener('chnage', function() {
            if(this.checked){
                console.log("status:Active");
            }else{
                console.log("status:Inactive");
            }
        });
        </script>
        <script>
            $(document).ready(function () {
                $('#add-coupons').on('hidden.bs.modal', function(){
                    $(this).find('form')[0].reset();
                    $(this).find('select.select').val('').trigger(change);

                });

            });

        </script>
@endsection
