@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                                           SUDHEERA SHOP PAGE
                                        ========================================================= */

        .sudheera-shop {
            background: #fffdf9;
            color: #241719;
        }

        /* Breadcrumb */
        .shop-breadcrumb {
            padding: 45px 0 18px;
        }

        .shop-breadcrumb-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #777;
            margin-left: 30px;
        }

        .shop-breadcrumb-list a {
            color: #6b001b;
            text-decoration: none;
        }

        /* Header */
        .shop-heading {
            padding: 15px 0 25px;
        }

        .shop-heading-inner {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
        }

        .shop-eyebrow {
            color: #8a2035;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .shop-title {
            font-family: Georgia, serif;
            font-size: 42px;
            line-height: 1.1;
            font-weight: 400;
            margin: 0;
            color: #420014;
        }

        .shop-subtitle {
            color: #777;
            margin: 9px 0 0;
            font-size: 14px;
        }

        .shop-count {
            color: #555;
            font-size: 13px;
            white-space: nowrap;
        }

        /* Main */
        .shop-main {
            padding: 10px 0 70px;
        }

        .shop-layout {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            gap: 30px;
            margin: 20px;
        }

        /* =========================================================
                                           SIDEBAR
                                        ========================================================= */

        .shop-sidebar {
            background: #fff;
            border: 1px solid #eadfd9;
            border-radius: 16px;
            padding: 22px;
            height: fit-content;
            position: sticky;
            top: 90px;
        }

        .filter-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee5df;
        }

        .filter-title h5 {
            margin: 0;
            font-family: Georgia, serif;
            font-size: 20px;
            font-weight: 400;
            color: #470018;
        }

        .filter-clear {
            font-size: 11px;
            color: #8a2035;
            text-decoration: none;
            text-transform: uppercase;
        }

        .filter-group {
            padding: 22px 0;
            border-bottom: 1px solid #eee5df;
        }

        .filter-group:last-child {
            border-bottom: 0;
        }

        .filter-group-title {
            font-size: 13px;
            font-weight: 700;
            color: #3b2025;
            margin-bottom: 10px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .filter-option-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .filter-option input {
            width: 16px;
            height: 16px;
            accent-color: #72001e;
            cursor: pointer;
        }

        .filter-option span {
            font-size: 13px;
            color: #555;
        }

        .filter-number {
            font-size: 11px !important;
            color: #aaa !important;
        }

        .filter-price {
            display: flex;
            gap: 8px;
        }

        .filter-price input {
            width: 50%;
            height: 30px;
            border: 1px solid #ddd1cb;
            border-radius: 8px;
            padding: 0 10px;
            outline: none;
            font-size: 12px;
        }

        .filter-button {
            width: 100%;
            height: 35px;
            border: 0;
            border-radius: 8px;
            background: #650019;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .5px;
            margin-top: 5px;
        }

        /* =========================================================
                                           TOOLBAR
                                        ========================================================= */

        .shop-toolbar {
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e9dfda;
            margin-bottom: 20px;
        }

        .shop-toolbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mobile-filter-btn {
            display: none;
            border: 1px solid #ddd1cb;
            background: #fff;
            border-radius: 8px;
            height: 40px;
            padding: 0 14px;
            font-size: 12px;
        }

        .category-pills {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .category-pill {
            border: 1px solid #ded3ce;
            background: #fff;
            color: #555;
            border-radius: 20px;
            padding: 7px 13px;
            font-size: 11px;
            cursor: pointer;
        }

        .category-pill.active {
            background: #650019;
            color: #fff;
            border-color: #650019;
        }

        .sort-box {
            position: relative;
        }

        .sort-select {
            border: 1px solid #ddd1cb;
            background: #fff;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 12px;
            color: #444;
            outline: none;
            min-width: 155px;
        }

        /* =========================================================
                                           PRODUCT GRID
                                        ========================================================= */

        .sudheera-product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .sudheera-product-card {
            min-width: 0;
            background: #fff;
            border: 1px solid #eee4df;
            border-radius: 15px;
            overflow: hidden;
            transition: all .3s ease;
        }

        .sudheera-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 35px rgba(75, 0, 20, .10);
        }

        .product-image-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1.18;
            overflow: hidden;
            background: #f4eee9;
        }

        .product-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
            transition: transform .5s ease;
        }

        .sudheera-product-card:hover .product-image-wrap img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            left: 10px;
            top: 10px;
            background: #650019;
            color: #fff;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .product-badge.new {
            background: #497d45;
        }

        .product-wishlist {
            position: absolute;
            right: 10px;
            top: 10px;
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .95);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #650019;
            cursor: pointer;
            transition: .25s ease;
        }

        .product-wishlist:hover {
            background: #650019;
            color: #fff;
        }

        .product-info {
            padding: 13px;
        }

        .product-category {
            font-size: 10px;
            color: #9b7b6d;
            margin-bottom: 5px;
        }

        .product-name {
            color: #2e2021;
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 7px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 8px;
        }

        .stars {
            color: #d59b24;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .rating-count {
            font-size: 10px;
            color: #999;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .price-current {
            font-size: 17px;
            font-weight: 700;
            color: #380013;
        }

        .price-old {
            font-size: 11px;
            color: #aaa;
            text-decoration: line-through;
        }

        .price-off {
            font-size: 9px;
            background: #f7e5e4;
            color: #9a3340;
            padding: 3px 5px;
            border-radius: 4px;
            font-weight: 600;
        }

        .add-cart-btn {
            width: 100%;
            height: 40px;
            border: 1px solid #650019;
            background: #650019;
            color: #fff;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            transition: .25s ease;
        }

        .add-cart-btn:hover {
            background: #fff;
            color: #650019;
        }

        /* =========================================================
                                           MOBILE FILTER
                                        ========================================================= */

        .mobile-filter-panel {
            display: none;
            background: #fff;
            border: 1px solid #eadfd9;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 20px;
        }

        /* =========================================================
                                           RESPONSIVE
                                        ========================================================= */

        @media (max-width: 1199px) {

            .shop-layout {
                grid-template-columns: 220px minmax(0, 1fr);
                gap: 20px;
            }

            .sudheera-product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {

            .shop-layout {
                display: block;
            }

            .shop-sidebar {
                display: none;
            }

            .mobile-filter-btn {
                display: block;
            }

            .mobile-filter-panel.show {
                display: block;
            }

            .sudheera-product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {

            .shop-breadcrumb {
                padding-top: 25px;
            }

            .shop-heading-inner {
                display: block;
            }

            .shop-title {
                font-size: 32px;
            }

            .shop-count {
                margin-top: 10px;
            }

            .shop-toolbar {
                align-items: flex-start;
                padding: 12px 0;
                gap: 10px;
            }

            .shop-toolbar-left {
                flex-wrap: wrap;
            }

            .category-pills {
                display: none;
            }

            .sort-select {
                min-width: 135px;
            }

            .sudheera-product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .product-image-wrap {
                aspect-ratio: 1 / 1.25;
            }

            .product-info {
                padding: 10px;
            }

            .product-name {
                font-size: 12px;
            }

            .price-current {
                font-size: 15px;
            }

            .add-cart-btn {
                height: 38px;
            }
        }

        @media (max-width: 480px) {

            .shop-title {
                font-size: 28px;
            }

            .sudheera-product-grid {
                gap: 8px;
            }

            .product-image-wrap {
                aspect-ratio: 1 / 1.3;
            }

            .product-wishlist {
                width: 28px;
                height: 28px;
                right: 7px;
                top: 7px;
            }

            .product-badge {
                left: 7px;
                top: 7px;
            }

            .product-info {
                padding: 8px;
            }

            .product-rating {
                margin-bottom: 5px;
            }

            .product-price {
                gap: 4px;
                margin-bottom: 8px;
            }

            .price-old {
                font-size: 9px;
            }

            .price-off {
                font-size: 8px;
            }

            .add-cart-btn {
                height: 36px;
                font-size: 10px;
            }
        }

        .sudheera-product-card-link,
        .sudheera-product-card-link:hover,
        .sudheera-product-card-link:focus,
        .sudheera-product-card-link:active {
            text-decoration: none !important;
            color: inherit !important;
        }

        .sudheera-product-card-link * {
            text-decoration: none !important;
        }

        .sudheera-product-card-link h3,
        .sudheera-product-card-link .product-name,
        .sudheera-product-card-link .product-category,
        .sudheera-product-card-link .product-rating,
        .sudheera-product-card-link .rating-count,
        .sudheera-product-card-link .product-price,
        .sudheera-product-card-link .price-current,
        .sudheera-product-card-link .price-old,
        .sudheera-product-card-link .price-off {
            text-decoration: none !important;
        }
    </style>


    <div class="sudheera-shop">

        <!-- =====================================================
                                             BREADCRUMB
                                        ====================================================== -->

        <section class="shop-breadcrumb">
            <div class="container">

                <ul class="shop-breadcrumb-list">
                    <li>
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    <li>/</li>

                    <li>Shop</li>
                </ul>

            </div>
        </section>




        <!-- =====================================================
                                             SHOP MAIN
                                        ====================================================== -->

        <section class="shop-main">

            <div class="container">

                <div class="shop-layout">


                    <!-- =================================================
                                                         SIDEBAR
                                                    ================================================== -->

                    <aside class="shop-sidebar">

                        <div class="filter-title">

                            <h5>
                                Filter
                            </h5>

                            <a href="#" class="filter-clear">
                                Clear All
                            </a>

                        </div>


                        <!-- Category -->

                        <!-- Category -->

                        <div class="filter-group">

                            <div class="filter-group-title">
                                Shop By Category
                            </div>

                            @forelse($categories as $category)

                                @php
                                    $categoryCount = $categoryCounts[$category->id] ?? 0;
                                @endphp

                                <label class="filter-option">

                                    <div class="filter-option-left">

                                        <input type="checkbox" class="category-filter" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }}>

                                        <span>
                                            {{ $category->title }}
                                        </span>

                                    </div>

                                    <span class="filter-number">
                                        {{ str_pad($categoryCount, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                </label>

                            @empty

                                <span style="font-size:13px;color:#999;">
                                    No categories available
                                </span>

                            @endforelse

                        </div>




                        <!-- Price -->

                        <!-- Price -->

                        <div class="filter-group">

                            <div class="filter-group-title">
                                Price Range
                            </div>

                            <div class="filter-price">

                                <input type="number" name="min_price" id="minPrice" placeholder="Min ₹"
                                    value="{{ request('min_price') }}">

                                <input type="number" name="max_price" id="maxPrice" placeholder="Max ₹"
                                    value="{{ request('max_price') }}">

                            </div>

                        </div>

                        <button type="button" class="filter-button" onclick="applyFilters()">
                            APPLY FILTER
                        </button>

                    </aside>


                    <!-- =================================================
                                                         PRODUCTS
                                                    ================================================== -->

                    <div class="shop-products-area">


                        <!-- Toolbar -->

                        <div class="shop-toolbar">

                            <div class="shop-toolbar-left">

                                <button type="button" class="mobile-filter-btn desktop-filter-btn" id="openFilterModal">
                                    ☰ FILTER
                                </button>
                                <style>
                                    /* =========================================
       FILTER MODAL
    ========================================= */

                                    .filter-modal-overlay {
                                        position: fixed;
                                        inset: 0;
                                        z-index: 99999;
                                        display: none;
                                        align-items: center;
                                        justify-content: center;
                                        padding: 20px;
                                        background: rgba(25, 10, 12, 0.60);
                                    }

                                    .filter-modal-overlay.active {
                                        display: flex;
                                        margin-top: 80px;
                                    }

                                    .filter-modal-box {
                                        width: 100%;
                                        max-width: 480px;
                                        max-height: 85vh;
                                        display: flex;
                                        flex-direction: column;
                                        overflow: hidden;
                                        background: #fffdf9;
                                        border-radius: 16px;
                                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.22);
                                        animation: filterModalOpen 0.25s ease;
                                    }

                                    @keyframes filterModalOpen {
                                        from {
                                            opacity: 0;
                                            transform: translateY(15px);
                                        }

                                        to {
                                            opacity: 1;
                                            transform: translateY(0);
                                        }
                                    }

                                    .filter-modal-header {
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                        padding: 20px 22px;
                                        border-bottom: 1px solid #eee5df;
                                    }

                                    .filter-modal-header h5 {
                                        margin: 0;
                                        font-family: Georgia, serif;
                                        font-size: 22px;
                                        font-weight: 400;
                                        color: #470018;
                                    }

                                    .filter-modal-close {
                                        width: 36px;
                                        height: 36px;
                                        border: 0;
                                        border-radius: 50%;
                                        background: #f4e9e8;
                                        color: #650019;
                                        font-size: 28px;
                                        line-height: 1;
                                        cursor: pointer;
                                    }

                                    .filter-modal-body {
                                        padding: 0 22px;
                                        overflow-y: auto;
                                        overscroll-behavior: contain;
                                    }

                                    .filter-modal-body .filter-group {
                                        padding: 5px 0;
                                    }

                                    .filter-modal-body .filter-option {
                                        justify-content: flex-start;
                                        gap: 12px;
                                    }

                                    .filter-modal-body .filter-option-left {
                                        flex: 1;
                                    }

                                    .filter-modal-body .filter-number {
                                        margin-left: auto;
                                    }

                                    .filter-modal-footer {
                                        display: flex;
                                        gap: 12px;
                                        padding: 16px 22px 20px;
                                        border-top: 1px solid #eee5df;
                                        background: #fffdf9;
                                    }

                                    .filter-modal-footer .filter-button {
                                        flex: 1;
                                        margin: 0;
                                    }

                                    .filter-reset-btn {
                                        flex: 1;
                                        min-height: 35px;
                                        border: 1px solid #650019;
                                        border-radius: 8px;
                                        background: #fff;
                                        color: #650019;
                                        font-size: 12px;
                                        font-weight: 600;
                                        cursor: pointer;
                                    }

                                    .filter-reset-btn:hover {
                                        background: #f7e9eb;
                                    }

                                    body.filter-modal-open {
                                        overflow: hidden;
                                    }

                                    /* Keep the existing sidebar on desktop */
                                    @media (min-width: 992px) {
                                        .filter-modal-box {
                                            max-width: 450px;
                                        }
                                    }

                                    @media (max-width: 480px) {
                                        .filter-modal-overlay {
                                            padding: 12px;
                                        }

                                        .filter-modal-box {
                                            max-height: 88vh;
                                            border-radius: 13px;
                                        }

                                        .filter-modal-header {
                                            padding: 16px;
                                        }

                                        .filter-modal-body {
                                            padding: 0 16px;
                                        }

                                        .filter-modal-footer {
                                            padding: 14px 16px 16px;
                                        }
                                    }
                                </style>
                                <!-- Filter Modal -->
                                <div class="filter-modal-overlay" id="filterModal">

                                    <div class="filter-modal-box">

                                        <!-- Modal Header -->
                                        <div class="filter-modal-header">
                                            <h5>Filter Products</h5>

                                            <button type="button" class="filter-modal-close" id="closeFilterModal"
                                                aria-label="Close filters">
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="filter-modal-body">

                                            <!-- Category Filter -->
                                            <div class="filter-group">

                                                <div class="filter-group-title">
                                                    Shop By Category
                                                </div>

                                                @forelse($categories as $category)

                                                    @php
                                                        $categoryCount =
                                                            $categoryCounts[$category->id] ?? 0;
                                                    @endphp

                                                    <label class="filter-option">

                                                        <div class="filter-option-left">

                                                            <input type="checkbox" class="category-filter"
                                                                value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }}>

                                                            <span>{{ $category->title }}</span>

                                                        </div>

                                                        <span class="filter-number">
                                                            {{ str_pad($categoryCount, 2, '0', STR_PAD_LEFT) }}
                                                        </span>

                                                    </label>

                                                @empty

                                                    <span style="font-size:13px;color:#999;">
                                                        No categories available
                                                    </span>

                                                @endforelse

                                            </div>

                                            <!-- Price Filter -->
                                            <div class="filter-group">

                                                <div class="filter-group-title">
                                                    Price Range
                                                </div>

                                                <div class="filter-price">

                                                    <input type="number" id="modalMinPrice" placeholder="Min ₹" min="0"
                                                        value="{{ request('min_price') }}">

                                                    <input type="number" id="modalMaxPrice" placeholder="Max ₹" min="0"
                                                        value="{{ request('max_price') }}">

                                                </div>

                                            </div>

                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="filter-modal-footer">

                                            <button type="button" class="filter-reset-btn" id="clearModalFilters">
                                                CLEAR ALL
                                            </button>

                                            <button type="button" class="filter-button" id="applyModalFilters">
                                                APPLY FILTER
                                            </button>

                                        </div>

                                    </div>

                                </div>


                                <select class="sort-select" id="sortProducts" onchange="applySort(this.value)">

                                    <option value="" {{ !request('sort') ? 'selected' : '' }}>
                                        Sort By: Featured
                                    </option>

                                    <option value="best-selling" {{ request('sort') == 'best-selling' ? 'selected' : '' }}>
                                        Best Selling
                                    </option>

                                    <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>
                                        Price: Low to High
                                    </option>

                                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>
                                        Price: High to Low
                                    </option>

                                    <option value="new-arrivals" {{ request('sort') == 'new-arrivals' ? 'selected' : '' }}>
                                        New Arrivals
                                    </option>

                                </select>

                            </div>




                        </div>


                        <!-- Mobile Filter -->

                        <div class="mobile-filter-panel">

                            <div class="filter-group-title">
                                Shop By Category
                            </div>

                            @forelse($categories as $category)

                                @php
                                    $categoryCount = $categoryCounts[$category->id] ?? 0;
                                @endphp

                                <label class="filter-option">

                                    <div class="filter-option-left">

                                        <input type="checkbox" class="category-filter" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }}>

                                        <span>
                                            {{ $category->title }}
                                        </span>

                                    </div>

                                    <span class="filter-number">
                                        {{ str_pad($categoryCount, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                </label>

                            @empty

                                <span style="font-size:13px;color:#999;">
                                    No categories available
                                </span>

                            @endforelse

                        </div>

                        <!-- =================================================
                                                             PRODUCT GRID
                                                        ================================================== -->


                        <div class="sudheera-product-grid">

                            @forelse($products as $product)

                                @php

                                    $variant = $product->variant;

                                    $sellingPrice = $variant?->price;
                                    $actualPrice = $variant?->actual_price;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Discount
                                    |--------------------------------------------------------------------------
                                    */

                                    $discount = 0;

                                    if (
                                        is_numeric($actualPrice) &&
                                        is_numeric($sellingPrice) &&
                                        (float) $actualPrice > 0 &&
                                        (float) $actualPrice > (float) $sellingPrice
                                    ) {
                                        $discount = round(
                                            (
                                                ((float) $actualPrice - (float) $sellingPrice)
                                                / (float) $actualPrice
                                            ) * 100
                                        );
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Product Image
                                    |--------------------------------------------------------------------------
                                    */

                                    $productImage = null;

                                    if ($variant && !empty($variant->image)) {

                                        $productImage = $variant->image;

                                    } elseif (!empty($product->image)) {

                                        $productImage = $product->image;

                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Product Badge
                                    |--------------------------------------------------------------------------
                                    */

                                    $badge = null;
                                    $badgeClass = '';

                                    if (
                                        $product->orders > 0 &&
                                        $loop->iteration <= 2
                                    ) {

                                        $badge = 'Bestseller';

                                    } elseif (
                                        $product->created_at &&
                                        $product->created_at->gt(now()->subDays(30))
                                    ) {

                                        $badge = 'New';
                                        $badgeClass = 'new';

                                    } elseif ($product->is_feature === 'yes') {

                                        $badge = 'Featured';

                                    }

                                @endphp


                                <div class="sudheera-product-card">

                                    <!-- Product Image -->

                                    <div class="product-image-wrap">

                                        <a href="{{ route('productdetails', ['slug' => $product->slug]) }}"
                                            class="sudheera-product-card-link">

                                            @if($productImage)

                                                <img src="{{ asset($productImage) }}" alt="{{ $product->title }}" loading="lazy">

                                            @else

                                                <img src="{{ asset('website/images/product-placeholder.png') }}"
                                                    alt="{{ $product->title }}" loading="lazy">

                                            @endif

                                        </a>


                                        @if($badge)

                                            <span class="product-badge {{ $badgeClass }}">
                                                {{ $badge }}
                                            </span>

                                        @endif


                                        @php
                                            $isWishlisted = in_array(
                                                $variant?->id,
                                                $wishlistVariantIds ?? []
                                            );
                                        @endphp

                                        <button type="button" class="product-wishlist {{ $isWishlisted ? 'active' : '' }}"
                                            data-variant-id="{{ $variant?->id }}">
                                            {{ $isWishlisted ? '♥' : '♡' }}
                                        </button>

                                    </div>


                                    <!-- Product Information -->

                                    <div class="product-info">


                                        @if($product->category)

                                            <div class="product-category">

                                                {{ $product->category->title }}

                                            </div>

                                        @endif


                                        <a href="{{ route('productdetails', ['slug' => $product->slug]) }}"
                                            class="sudheera-product-card-link">

                                            <h3 class="product-name">

                                                {{ $product->title }}

                                            </h3>

                                        </a>


                                        <!-- Rating -->

                                        <div class="product-rating">

                                            <span class="stars">
                                                ★★★★★
                                            </span>

                                            <span class="rating-count">

                                                @if($product->orders > 0)

                                                    Bestselling

                                                @else

                                                    Available

                                                @endif

                                            </span>

                                        </div>


                                        <!-- Price -->

                                        <div class="product-price">

                                            @if(is_numeric($sellingPrice))

                                                <span class="price-current">

                                                    ₹{{ number_format((float) $sellingPrice, 0) }}

                                                </span>

                                            @endif


                                            @if(
                                                    is_numeric($actualPrice) &&
                                                    is_numeric($sellingPrice) &&
                                                    (float) $actualPrice > (float) $sellingPrice
                                                )

                                                <span class="price-old">

                                                    ₹{{ number_format((float) $actualPrice, 0) }}

                                                </span>

                                            @endif


                                            @if($discount > 0)

                                                <span class="price-off">

                                                    {{ $discount }}% OFF

                                                </span>

                                            @endif

                                        </div>


                                        <!-- Add To Cart -->

                                        <button type="button" class="add-cart-btn" data-product-id="{{ $product->id }}"
                                            data-variant-id="{{ $variant?->id }}">
                                            ADD TO CART
                                        </button>


                                    </div>

                                </div>


                            @empty

                                            <div style="
                                    grid-column: 1 / -1;
                                    text-align:center;
                                    padding:60px 20px;
                                    color:#777;
                                ">

                                                <h3>
                                                    No products found
                                                </h3>

                                                <p>
                                                    Try changing your filters or browse another category.
                                                </p>

                                            </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
    @if($products->hasPages())

        <div class="shop-pagination">

            {{ $products->links('pagination::bootstrap-5') }}

        </div>

    @endif


    <script>

        /*
        |--------------------------------------------------------------------------
        | Apply Category + Price Filters
        |--------------------------------------------------------------------------
        */

        function applyFilters() {

            const url = new URL(
                "{{ route('shop') }}",
                window.location.origin
            );

            const selectedCategory =
                document.querySelector('.category-filter:checked');

            const minPrice =
                document.getElementById('minPrice')?.value;

            const maxPrice =
                document.getElementById('maxPrice')?.value;


            if (selectedCategory && selectedCategory.value) {

                url.searchParams.set(
                    'category',
                    selectedCategory.value
                );

            }


            if (minPrice) {

                url.searchParams.set(
                    'min_price',
                    minPrice
                );

            }


            if (maxPrice) {

                url.searchParams.set(
                    'max_price',
                    maxPrice
                );

            }


            window.location.href = url.toString();

        }


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.category-filter')
            .forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    document
                        .querySelectorAll('.category-filter')
                        .forEach(function (item) {

                            if (item !== checkbox) {

                                item.checked = false;

                            }

                        });

                    applyFilters();

                });

            });


        /*
        |--------------------------------------------------------------------------
        | Sort Products
        |--------------------------------------------------------------------------
        */

        function applySort(sortValue) {

            const url = new URL(
                "{{ route('shop') }}",
                window.location.origin
            );


            const selectedCategory =
                document.querySelector('.category-filter:checked');

            const minPrice =
                document.getElementById('minPrice')?.value;

            const maxPrice =
                document.getElementById('maxPrice')?.value;


            if (selectedCategory && selectedCategory.value) {

                url.searchParams.set(
                    'category',
                    selectedCategory.value
                );

            }


            if (minPrice) {

                url.searchParams.set(
                    'min_price',
                    minPrice
                );

            }


            if (maxPrice) {

                url.searchParams.set(
                    'max_price',
                    maxPrice
                );

            }


            if (sortValue) {

                url.searchParams.set(
                    'sort',
                    sortValue
                );

            }


            window.location.href = url.toString();

        }


        /*
        |--------------------------------------------------------------------------
        | Clear All Filters
        |--------------------------------------------------------------------------
        */

        document
            .querySelector('.filter-clear')
            ?.addEventListener('click', function (event) {

                event.preventDefault();

                window.location.href =
                    "{{ route('shop') }}";

            });


        /*
        |--------------------------------------------------------------------------
        | Wishlist
        |--------------------------------------------------------------------------
        */

        const wishlistVariantIds =
            @json($wishlistVariantIds ?? []);


        document
            .querySelectorAll('.product-wishlist')
            .forEach(function (button) {

                button.addEventListener('click', function (event) {

                    event.preventDefault();
                    event.stopPropagation();


                    const variantId =
                        this.getAttribute('data-variant-id');


                    /*
                    |--------------------------------------------------------------------------
                    | Variant Check
                    |--------------------------------------------------------------------------
                    */

                    if (!variantId) {

                        alert('Product variant not found.');

                        return;

                    }


                    const currentButton = this;


                    /*
                    |--------------------------------------------------------------------------
                    | Send Wishlist Request
                    |--------------------------------------------------------------------------
                    */

                    fetch('{{ route('wishlist.add') }}', {

                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}',

                            'Accept':
                                'application/json'

                        },

                        body: JSON.stringify({

                            product_variant_id:
                                variantId

                        })

                    })


                        .then(async function (response) {

                            const data =
                                await response.json();


                            /*
                            |--------------------------------------------------------------------------
                            | Login Required
                            |--------------------------------------------------------------------------
                            */

                            if (response.status === 401) {

                                window.location.href =
                                    '{{ route('login') }}';

                                return;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Server Error
                            |--------------------------------------------------------------------------
                            */

                            if (!response.ok) {

                                throw new Error(

                                    data.message ||
                                    'Something went wrong.'

                                );

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Wishlist Success
                            |--------------------------------------------------------------------------
                            */

                            if (data.status) {

                                const numericVariantId =
                                    Number(variantId);


                                /*
                                |--------------------------------------------------------------------------
                                | Update Local Wishlist State
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    !wishlistVariantIds.includes(
                                        numericVariantId
                                    )
                                ) {

                                    wishlistVariantIds.push(
                                        numericVariantId
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Change Heart
                                |--------------------------------------------------------------------------
                                */

                                currentButton.innerHTML =
                                    '♥';


                                currentButton.classList.add(
                                    'active'
                                );


                                alert(
                                    data.message
                                );


                            } else {

                                alert(

                                    data.message ||
                                    'Unable to add to wishlist.'

                                );

                            }

                        })


                        .catch(function (error) {

                            console.error(
                                'Wishlist Error:',
                                error
                            );


                            alert(

                                error.message ||
                                'Something went wrong.'

                            );

                        });

                });

            });


        /*
        |--------------------------------------------------------------------------
        | Add To Cart Visual State
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.add-cart-btn')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    const originalText =
                        this.innerHTML;


                    this.innerHTML =
                        'ADDED ✓';

                    this.disabled = true;


                    const currentButton = this;


                    setTimeout(function () {

                        currentButton.innerHTML =
                            originalText;

                        currentButton.disabled =
                            false;

                    }, 1500);

                });

            });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.add-cart-btn').forEach(function (button) {

                button.addEventListener('click', function () {

                    const variantId = this.dataset.variantId;
                    const currentButton = this;

                    if (!variantId) {
                        alert('Product variant not available.');
                        return;
                    }

                    const originalText = currentButton.innerHTML;

                    currentButton.disabled = true;
                    currentButton.innerHTML = 'ADDING...';

                    fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_variant_id: variantId,
                            quantity: 1
                        })
                    })
                        .then(async response => {

                            const data = await response.json();

                            if (response.status === 401) {
                                alert(data.message || 'Please login first.');

                                window.location.href = "{{ route('login') }}";
                                return;
                            }

                            if (!response.ok) {
                                throw new Error(data.message || 'Something went wrong.');
                            }

                            return data;
                        })
                        .then(data => {

                            if (!data) {
                                return;
                            }

                            if (data.status) {

                                currentButton.innerHTML = 'ADDED ✓';

                                // Update cart count if available
                                document.querySelectorAll('.cart-count').forEach(function (element) {
                                    element.textContent = data.cart_count;
                                });

                                setTimeout(function () {
                                    currentButton.innerHTML = originalText;
                                    currentButton.disabled = false;
                                }, 1500);

                            } else {

                                currentButton.innerHTML = originalText;
                                currentButton.disabled = false;

                                alert(data.message || 'Unable to add product to cart.');
                            }
                        })
                        .catch(function (error) {

                            console.error('Add to cart error:', error);

                            currentButton.innerHTML = originalText;
                            currentButton.disabled = false;

                            alert(error.message || 'Something went wrong. Please try again.');
                        });

                });

            });

        });
    </script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const modal = document.getElementById('filterModal');
            const openBtn = document.getElementById('openFilterModal');
            const closeBtn = document.getElementById('closeFilterModal');
            const applyBtn = document.getElementById('applyModalFilters');
            const clearBtn = document.getElementById('clearModalFilters');

            if (!modal) return;

            function openFilterModal() {
                modal.classList.add('active');
                document.body.classList.add('filter-modal-open');
            }

            function closeFilterModal() {
                modal.classList.remove('active');
                document.body.classList.remove('filter-modal-open');
            }

            if (openBtn) {
                openBtn.addEventListener('click', openFilterModal);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeFilterModal);
            }

            // Close when clicking the dark overlay
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeFilterModal();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal.classList.contains('active')) {
                    closeFilterModal();
                }
            });

            // Apply category and price filters
            if (applyBtn) {
                applyBtn.addEventListener('click', function () {

                    const url = new URL(
                        "{{ route('shop') }}",
                        window.location.origin
                    );

                    const selectedCategory =
                        modal.querySelector('.category-filter:checked');

                    const minPrice =
                        document.getElementById('modalMinPrice').value;

                    const maxPrice =
                        document.getElementById('modalMaxPrice').value;

                    if (selectedCategory) {
                        url.searchParams.set(
                            'category',
                            selectedCategory.value
                        );
                    }

                    if (minPrice !== '') {
                        url.searchParams.set('min_price', minPrice);
                    }

                    if (maxPrice !== '') {
                        url.searchParams.set('max_price', maxPrice);
                    }

                    // Preserve the selected sort option
                    const sortSelect = document.getElementById('sortProducts');

                    if (sortSelect && sortSelect.value) {
                        url.searchParams.set('sort', sortSelect.value);
                    }

                    window.location.href = url.toString();
                });
            }

            // Clear all filters
            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    window.location.href = "{{ route('shop') }}";
                });
            }

            // Allow only one category to be selected
            modal.querySelectorAll('.category-filter').forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    if (this.checked) {
                        modal.querySelectorAll('.category-filter').forEach(
                            function (otherCheckbox) {
                                if (otherCheckbox !== checkbox) {
                                    otherCheckbox.checked = false;
                                }
                            }
                        );
                    }

                });

            });

        });

    </script>

@endsection