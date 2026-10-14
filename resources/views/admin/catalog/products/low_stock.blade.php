<?php $page = 'low-stocks'; ?>
@extends('layouts.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="page-header">
                <div class="page-title me-auto">
                    <h4>Low Stocks</h4>
                    <h6>Manage your low stocks</h6>
                </div>
                <ul class="table-top-head low-stock-top-head">
                   
                </ul>
            </div>
            <div class="table-tab">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Low Stocks</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Out of Stocks</button>
                    </li>

                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                                            <span><img src="{{ asset('admin/build/img/icons/closes.svg') }}"
                                                    alt="img"></span>
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
                                                    <i data-feather="box" class="info-img"></i>
                                                    <select class="select">
                                                        <option>Choose Product</option>
                                                        <option>Lenovo 3rd Generation </option>
                                                        <option>Nike Jordan </option>
                                                        <option>Amazon Echo Dot </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks">
                                                    <i data-feather="zap" class="info-img"></i>
                                                    <select class="select">
                                                        <option>Choose Category</option>
                                                        <option>Laptop</option>
                                                        <option>Shoe</option>
                                                        <option>Speaker</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks">
                                                    <i data-feather="archive" class="info-img"></i>
                                                    <select class="select">
                                                        <option>Choose Warehouse</option>
                                                        <option>Lavish Warehouse </option>
                                                        <option>Lobar Handy </option>
                                                        <option>Traditional Warehouse </option>
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
                                                {{-- <th>Warehouse</th>
                                                <th>Store</th> --}}
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>SKU</th>
                                                <th>Qty</th>
                                                <th>Qty Alert</th>
                                                {{-- <th class="no-sort">Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                            <tr>
                                                <td>
                                                    <label class="checkboxs">
                                                        <input type="checkbox">
                                                        <span class="checkmarks"></span>
                                                    </label>
                                                </td>
                                                {{-- <td>Lavish Warehouse </td>
                                                <td>Crinol</td> --}}

                                                <td>
                                                    <div class="productimgname">
                                                        <a href="javascript:void(0);" class="product-img stock-img">
                                                            <img src="{{ asset($product->product->products_image) ?? '' }}"
                                                                alt="product">
                                                        </a>
                                                        <a href="javascript:void(0);">{{$product->product->product_name}}</a>
                                                    </div>
                                                </td>
                                                <td>{{ $product->product->category->name }}</td>
                                                <td>{{ $product->sku_number }}</td>
                                                <td>{{ $product->stock }}</td>
                                                <td>{{ $product->low_stock_alert }}</td>
                                                {{-- <td class="action-table-data">
                                                    <div class="edit-delete-action">
                                                        <a class="me-2 p-2" href="javascript:void(0);"
                                                            data-bs-toggle="modal" data-bs-target="#edit-stock">
                                                            <i data-feather="edit" class="feather-edit"></i>
                                                        </a>
                                                        <a class="confirm-text p-2" href="javascript:void(0);">
                                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                                        </a>
                                                    </div>

                                                </td> --}}
                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /product list -->
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
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
                                        <a class="btn btn-filter" id="filter_search1">
                                            <i data-feather="filter" class="filter-icon"></i>
                                            <span><img src="{{ asset('admin/build/img/icons/closes.svg') }}"
                                                    alt="img"></span>
                                        </a>
                                    </div>
                                    <div class="form-sort">
                                        <i data-feather="sliders" class="info-img"></i>
                                        <select class="select">
                                            <option>Sort by Date</option>
                                            <option>Newest</option>
                                            <option>Oldest</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /Filter -->
                                <div class="card" id="filter_inputs1">
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks">
                                                    <i data-feather="box" class="info-img"></i>
                                                    <select class="select">
                                                        <option>Choose Product</option>
                                                        <option>Lenovo 3rd Generation </option>
                                                        <option>Nike Jordan </option>
                                                        <option>Amazon Echo Dot </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks">
                                                    <i data-feather="zap" class="info-img"></i>
                                                    <select class="select">
                                                        <option>Choose Category</option>
                                                        <option>Laptop</option>
                                                        <option>Shoe</option>
                                                        <option>Speaker</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks">
                                                    <i data-feather="archive" class="info-img"></i>
                                                    <select class="select">
                                                        <option>Choose Warehouse</option>
                                                        <option>Lavish Warehouse </option>
                                                        <option>Lobar Handy </option>
                                                        <option>Traditional Warehouse </option>
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
                                </div>
                                <!-- /Filter -->
                                <div class="table-responsive">
                                    <table class="table  datanew">
                                        <thead>
                                            <tr>
                                                <th class="no-sort">
                                                    <label class="checkboxs">
                                                        <input type="checkbox" id="select-all2">
                                                        <span class="checkmarks"></span>
                                                    </label>
                                                </th>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>SKU</th>
                                                <th>Qty</th>
                                                <th>Qty Alert</th>
                                                {{-- <th class="no-sort">Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($out_products as $out_product)
                                            <tr>
                                                <td>
                                                    <label class="checkboxs">
                                                        <input type="checkbox">
                                                        <span class="checkmarks"></span>
                                                    </label>
                                                </td>
                                                {{-- <td>Lavish Warehouse </td>
                                                <td>Crinol</td> --}}

                                                <td>
                                                    <div class="productimgname">
                                                        <a href="javascript:void(0);" class="product-img stock-img">
                                                            <img src="{{ asset($out_product->product->products_image) }}"
                                                                alt="product">
                                                        </a>
                                                        <a href="javascript:void(0);">{{$out_product->product->product_name}}</a>
                                                    </div>
                                                </td>
                                                <td>{{ $out_product->product->category->name }}</td>
                                                <td>{{ $out_product->sku_number }}</td>
                                                <td>{{ $out_product->stock }}</td>
                                                <td>{{ $out_product->low_stock_alert }}</td>
                                                {{-- <td class="action-table-data">
                                                    <div class="edit-delete-action">
                                                        <a class="me-2 p-2" href="javascript:void(0);"
                                                            data-bs-toggle="modal" data-bs-target="#edit-stock">
                                                            <i data-feather="edit" class="feather-edit"></i>
                                                        </a>
                                                        <a class="confirm-text p-2" href="javascript:void(0);">
                                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                                        </a>
                                                    </div>

                                                </td> --}}
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
            </div>
        </div>
    </div>
@endsection
