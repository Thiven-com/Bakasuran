@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                                                   PRODUCT DETAILS PAGE
                                                ========================================================= */

        :root {
            --sudheera-maroon: #650019;
            --sudheera-dark: #420014;
            --sudheera-gold: #b58a52;
            --sudheera-cream: #faf8f2;
            --sudheera-border: #e8e1d7;
            --sudheera-text: #222;
            --sudheera-muted: #777;
        }

        .product-details-page {
            background: #fff;
            padding-bottom: 70px;
        }


        /* =========================================================
                                                   BREADCRUMB
                                                ========================================================= */

        .product-breadcrumb {
            padding: 35px 0 20px;
        }

        .product-breadcrumb-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .product-breadcrumb-list li {
            font-size: 13px;
            color: #888;
        }

        .product-breadcrumb-list a {
            color: #555;
            text-decoration: none;
            transition: .25s ease;
        }

        .product-breadcrumb-list a:hover {
            color: var(--sudheera-maroon);
        }

        .breadcrumb-divider {
            color: #bbb;
        }

        .breadcrumb-current {
            color: var(--sudheera-maroon) !important;
        }


        /* =========================================================
                                                   MAIN PRODUCT AREA
                                                ========================================================= */

        .product-details-wrapper {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr);
            gap: 55px;
            align-items: start;
        }


        /* =========================================================
                                                   PRODUCT GALLERY
                                                ========================================================= */

        .product-gallery {
            position: sticky;
            top: 90px;
        }

        .product-gallery-layout {
            display: grid;
            grid-template-columns: 88px minmax(0, 1fr);
            gap: 14px;
        }

        .product-thumbnails {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .product-thumb {
            width: 88px;
            height: 105px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--sudheera-border);
            background: var(--sudheera-cream);
            cursor: pointer;
            transition: .25s ease;
        }

        .product-thumb:hover,
        .product-thumb.active {
            border-color: var(--sudheera-maroon);
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }

        .product-main-image {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1.15;
            overflow: hidden;
            border-radius: 18px;
            background: #f7f4ed;
        }

        .product-main-image img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
            transition: transform .6s ease;
        }

        .product-main-image:hover img {
            transform: scale(1.035);
        }


        /* =========================================================
                                                   IMAGE BADGES
                                                ========================================================= */

        .product-image-badge {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 3;

            padding: 7px 13px;

            background: var(--sudheera-maroon);
            color: #fff;

            border-radius: 50px;

            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .product-image-wishlist {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 3;

            width: 44px;
            height: 44px;

            border: 0;
            border-radius: 50%;

            background: rgba(255, 255, 255, .94);

            display: flex;
            align-items: center;
            justify-content: center;

            color: var(--sudheera-dark);

            cursor: pointer;
            transition: .25s ease;
        }

        .product-image-wishlist:hover {
            color: var(--sudheera-maroon);
            transform: scale(1.06);
        }

        .product-image-wishlist i {
            font-size: 19px;
        }


        /* =========================================================
                                                   PRODUCT INFO
                                                ========================================================= */

        .product-information {
            padding-top: 5px;
        }

        .product-brand {
            font-size: 12px;
            color: var(--sudheera-maroon);
            text-transform: uppercase;
            letter-spacing: 1.8px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .product-title {
            margin: 0 0 13px;

            font-size: 34px;
            line-height: 1.18;

            color: var(--sudheera-dark);
            font-weight: 500;

            letter-spacing: -.3px;
        }

        .product-rating-row {
            display: flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 18px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 3px;

            color: #c18a37;
            font-size: 13px;
        }

        .product-review-text {
            font-size: 13px;
            color: #777;
        }

        .product-review-divider {
            width: 1px;
            height: 16px;
            background: #ddd;
        }

        .product-price-row {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 11px;

            margin-bottom: 19px;
        }

        .product-current-price {
            font-size: 28px;
            font-weight: 700;
            color: #181818;
        }

        .product-old-price {
            font-size: 15px;
            color: #999;
            text-decoration: line-through;
        }

        .product-discount {
            padding: 5px 9px;

            border-radius: 5px;

            background: #f6e8e8;
            color: var(--sudheera-maroon);

            font-size: 11px;
            font-weight: 700;
        }

        .product-tax-note {
            font-size: 12px;
            color: #888;
            margin-bottom: 22px;
        }


        /* =========================================================
                                                   SHORT DESCRIPTION
                                                ========================================================= */

        .product-short-description {
            font-size: 14px;
            line-height: 1.8;
            color: #666;

            margin-bottom: 25px;

            max-width: 620px;
        }


        /* =========================================================
                                                   PRODUCT OPTIONS
                                                ========================================================= */

        .product-option {
            padding: 20px 0;
            border-top: 1px solid var(--sudheera-border);
        }

        .product-option:last-of-type {
            border-bottom: 1px solid var(--sudheera-border);
        }

        .option-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 13px;
        }

        .option-title {
            margin: 0;

            font-size: 13px;
            font-weight: 600;

            color: #333;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .option-selected {
            font-size: 13px;
            color: #777;
        }

        .variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
        }

        .variant-option {
            min-width: 70px;
            height: 40px;

            padding: 0 17px;

            border: 1px solid #d9d2c9;
            border-radius: 6px;

            background: #fff;

            color: #444;
            font-size: 13px;

            cursor: pointer;

            transition: .25s ease;
        }

        .variant-option:hover,
        .variant-option.active {
            border-color: var(--sudheera-maroon);
            background: var(--sudheera-maroon);
            color: #fff;
        }


        /* =========================================================
                                                   QUANTITY + CART
                                                ========================================================= */

        .purchase-area {
            padding-top: 24px;
        }

        .quantity-cart-row {
            display: grid;
            grid-template-columns: 125px 1fr;
            gap: 12px;
        }

        .quantity-selector {
            height: 52px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border: 1px solid #ddd4ca;
            border-radius: 8px;

            overflow: hidden;
        }

        .quantity-btn {
            width: 38px;
            height: 100%;

            border: 0;
            background: transparent;

            color: #555;
            font-size: 18px;

            cursor: pointer;
        }

        .quantity-value {
            font-size: 14px;
            font-weight: 600;
        }

        .add-cart-button {
            height: 52px;

            border: 1px solid var(--sudheera-maroon);
            border-radius: 8px;

            background: var(--sudheera-maroon);
            color: #fff;

            font-size: 14px;
            font-weight: 600;

            letter-spacing: .4px;

            cursor: pointer;

            transition: .3s ease;
        }

        .add-cart-button:hover {
            background: var(--sudheera-dark);
            border-color: var(--sudheera-dark);
        }

        .buy-now-button {
            width: 100%;
            height: 52px;

            margin-top: 11px;

            border: 1px solid var(--sudheera-gold);
            border-radius: 8px;

            background: #fff;
            color: var(--sudheera-dark);

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;
            transition: .3s ease;
        }

        .buy-now-button:hover {
            background: var(--sudheera-gold);
            color: #fff;
        }


        /* =========================================================
                                                   DELIVERY
                                                ========================================================= */

        .delivery-box {
            margin-top: 25px;
            padding: 18px;

            background: #faf8f3;
            border-radius: 10px;
        }

        .delivery-title {
            display: flex;
            align-items: center;
            gap: 9px;

            margin-bottom: 13px;

            color: var(--sudheera-dark);
            font-size: 14px;
            font-weight: 600;
        }

        .delivery-title i {
            font-size: 17px;
            color: var(--sudheera-maroon);
        }

        .pincode-row {
            display: flex;
            gap: 8px;
        }

        .pincode-input {
            flex: 1;

            height: 43px;

            border: 1px solid #ddd4ca;
            border-radius: 6px;

            padding: 0 13px;

            outline: none;

            font-size: 13px;
            background: #fff;
        }

        .pincode-input:focus {
            border-color: var(--sudheera-maroon);
        }

        .check-pincode {
            height: 43px;

            padding: 0 19px;

            border: 0;
            border-radius: 6px;

            background: var(--sudheera-dark);
            color: #fff;

            font-size: 12px;
            font-weight: 600;

            cursor: pointer;
        }


        /* =========================================================
                                                   SERVICE FEATURES
                                                ========================================================= */

        .product-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);

            gap: 10px;

            margin-top: 22px;
        }

        .product-feature {
            padding: 15px 10px;

            text-align: center;

            border: 1px solid #eee7dd;
            border-radius: 9px;
        }

        .product-feature i {
            display: block;

            margin-bottom: 7px;

            font-size: 18px;
            color: var(--sudheera-maroon);
        }

        .product-feature span {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }


        /* =========================================================
                                                   PRODUCT INFORMATION TABS
                                                ========================================================= */

        .product-extra-section {
            margin-top: 75px;

            border-top: 1px solid var(--sudheera-border);
        }

        .product-tabs {
            display: flex;
            justify-content: center;
            gap: 45px;

            border-bottom: 1px solid var(--sudheera-border);
        }

        .product-tab {
            position: relative;

            padding: 20px 0;

            border: 0;
            background: transparent;

            color: #777;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;
        }

        .product-tab.active {
            color: var(--sudheera-maroon);
        }

        .product-tab.active::after {
            content: "";

            position: absolute;
            bottom: -1px;
            left: 0;

            width: 100%;
            height: 2px;

            background: var(--sudheera-maroon);
        }

        .tab-content {
            padding: 35px 10px;

            max-width: 900px;
            margin: auto;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .tab-panel h4 {
            margin-bottom: 14px;

            color: var(--sudheera-dark);

            font-size: 20px;
            font-weight: 500;
        }

        .tab-panel p {
            color: #666;

            font-size: 14px;
            line-height: 1.9;
        }

        .details-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);

            gap: 0;

            border: 1px solid #eee7dd;
            border-radius: 10px;
            overflow: hidden;
        }

        .details-item {
            display: flex;
            justify-content: space-between;

            padding: 14px 17px;

            border-bottom: 1px solid #eee7dd;

            font-size: 13px;
        }

        .details-item:nth-child(odd) {
            border-right: 1px solid #eee7dd;
        }

        .details-label {
            color: #888;
        }

        .details-value {
            color: #333;
            font-weight: 500;
        }


        /* =========================================================
                                                   RESPONSIVE
                                                ========================================================= */

        @media (max-width: 991px) {

            .product-details-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .product-gallery {
                position: static;
            }

            .product-title {
                font-size: 29px;
            }
        }


        @media (max-width: 767px) {

            .product-breadcrumb {
                padding-top: 20px;
            }

            .product-gallery-layout {
                display: flex;
                flex-direction: column-reverse;
            }

            .product-thumbnails {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 3px;
            }

            .product-thumb {
                flex: 0 0 72px;
                width: 72px;
                height: 85px;
            }

            .product-main-image {
                aspect-ratio: 1 / 1.15;
                border-radius: 14px;
            }

            .product-title {
                font-size: 25px;
            }

            .product-current-price {
                font-size: 24px;
            }

            .quantity-cart-row {
                grid-template-columns: 105px 1fr;
            }

            .product-features {
                grid-template-columns: repeat(3, 1fr);
            }

            .product-tabs {
                gap: 22px;
                justify-content: flex-start;
                overflow-x: auto;
            }

            .product-tab {
                white-space: nowrap;
            }

            .details-list {
                grid-template-columns: 1fr;
            }

            .details-item:nth-child(odd) {
                border-right: 0;
            }
        }


        @media (max-width: 480px) {

            .product-details-page {
                padding-bottom: 40px;
            }

            .product-main-image {
                border-radius: 12px;
            }

            .product-image-badge {
                top: 12px;
                left: 12px;
            }

            .product-image-wishlist {
                top: 10px;
                right: 10px;
                width: 40px;
                height: 40px;
            }

            .product-title {
                font-size: 22px;
            }

            .product-short-description {
                font-size: 13px;
            }

            .product-features {
                gap: 6px;
            }

            .product-feature {
                padding: 12px 5px;
            }

            .product-feature span {
                font-size: 10px;
            }
        }
    </style>

    <style>
        /* Restaurant Name */
        .product-restaurant-details {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 16px;
            color: #E85D04;
            font-size: 14px;
            font-weight: 600;
        }

        .product-restaurant-details i {
            font-size: 15px;
        }
    </style>


    <!-- =========================================================
                                                 PRODUCT DETAILS PAGE
                                            ========================================================= -->

    <!-- =========================================================
                                                 PRODUCT DETAILS PAGE
                                            ========================================================= -->

    <section class="product-details-page" style="margin-left: 20px; margin-right: 20px;">



        <div class="container">



            <!-- =====================================================

                                                         BREADCRUMB

                                                    ====================================================== -->



            <div class="product-breadcrumb">



                <ul class="product-breadcrumb-list">



                    <li>

                        <a href="{{ route('home') }}">

                            Home

                        </a>

                    </li>



                    <li class="breadcrumb-divider">

                        /

                    </li>



                    @if($product->category)



                        <li>



                            <a href="{{ route('shop', ['category' => $product->category->slug]) }}">



                                {{ $product->category->title }}



                            </a>



                        </li>



                        <li class="breadcrumb-divider">

                            /

                        </li>



                    @endif



                    <li class="breadcrumb-current">



                        {{ $product->title }}



                    </li>



                </ul>



            </div>





            <!-- =====================================================

                                                         MAIN PRODUCT

                                                    ====================================================== -->



            <div class="product-details-wrapper">





                <!-- =================================================

                                                             LEFT - PRODUCT GALLERY

                                                        ================================================= -->



                <div class="product-gallery">



                    <div class="product-gallery-layout">





                        <!-- =================================================

                                                                     THUMBNAILS

                                                                ================================================= -->



                        <div class="product-thumbnails">





                            @if($productImage)



                                <div class="product-thumb active" onclick="changeProductImage(

                                                                                                                            this,

                                                                                                                            '{{ asset($productImage) }}'

                                                                                                                        )">



                                    <img src="{{ asset($productImage) }}" alt="{{ $product->title }}">



                                </div>



                            @endif





                            {{-- Product main image if different from variant --}}



                            @if(

                                    !empty($product->image) &&

                                    $product->image !== $productImage

                                )



                                <div class="product-thumb" onclick="changeProductImage(

                                                                                                                            this,

                                                                                                                            '{{ asset($product->image) }}'

                                                                                                                        )">



                                    <img src="{{ asset($product->image) }}" alt="{{ $product->title }}">



                                </div>



                            @endif





                            {{-- Other variant images --}}



                            @foreach($product->variants as $productVariant)



                                @if(

                                        !empty($productVariant->image) &&

                                        $productVariant->image !== $productImage &&

                                        $productVariant->image !== $product->image

                                    )



                                    <div class="product-thumb"
                                        onclick="changeProductImage(

                                                                                                                                                                            this,

                                                                                                                                                                            '{{ asset($productVariant->image) }}'

                                                                                                                                                                        )">



                                        <img src="{{ asset($productVariant->image) }}" alt="{{ $product->title }}">



                                    </div>



                                @endif



                            @endforeach





                            @if(!$productImage)



                                <div class="product-thumb active">



                                    <img src="{{ asset('website/images/product-placeholder.png') }}"
                                        alt="{{ $product->title }}">



                                </div>



                            @endif



                        </div>





                        <!-- =================================================

                                                                     MAIN IMAGE

                                                                ================================================= -->



                        <div class="product-main-image">





                            @if($badge)



                                <span class="product-image-badge">



                                    {{ $badge }}



                                </span>



                            @endif





                            <button
                                class="product-image-wishlist {{ in_array($variant?->id, $wishlistVariantIds ?? []) ? 'active' : '' }}"
                                title="Add to Wishlist" type="button" data-variant-id="{{ $variant?->id }}">



                                <i
                                    class="{{ in_array($variant?->id, $wishlistVariantIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>



                            </button>





                            @if($productImage)



                                <img id="mainProductImage" src="{{ asset($productImage) }}" alt="{{ $product->title }}">



                            @else



                                <img id="mainProductImage" src="{{ asset('website/images/product-placeholder.png') }}"
                                    alt="{{ $product->title }}">



                            @endif



                        </div>



                    </div>



                </div>





                <!-- =================================================

                                                             RIGHT - PRODUCT INFORMATION

                                                        ================================================= -->



                <div class="product-information">





                    <!-- Brand -->



                    @if($product->brand)



                        <div class="product-brand">



                            {{ $product->brand->name }}



                        </div>



                    @else



                        <div class="product-brand">

                            BAKASURAN FOOD

                        </div>



                    @endif





                    <!-- Product Title -->



                    <h1 class="product-title">



                        {{ $product->title }}



                    </h1>

                    {{-- Restaurant Name --}}
                    @if($product->restaurant)
                        <div class="product-restaurant-details">
                            <i class="fa-solid fa-utensils"></i>
                            <span>By {{ $product->restaurant->name }}</span>
                        </div>
                    @endif





                    <!-- =================================================

                                                                 RATING / ORDERS

                                                            ================================================= -->



                    <div class="product-rating-row">



                        <div class="product-rating">



                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>



                        </div>





                        <span class="product-review-text">



                            @if($product->orders > 0)



                                Bestselling



                            @else



                                No reviews yet



                            @endif



                        </span>





                        <span class="product-review-divider"></span>





                        <span class="product-review-text">



                            {{ $product->orders ?? 0 }} Sold



                        </span>



                    </div>





                    <!-- =================================================

                                                                 PRICE

                                                            ================================================= -->



                    <div class="product-price-row">





                        @if(is_numeric($sellingPrice))



                            <span class="product-current-price">



                                ₹{{ number_format((float) $sellingPrice, 0) }}



                            </span>



                        @endif





                        @if(

                                is_numeric($actualPrice) &&

                                is_numeric($sellingPrice) &&

                                (float) $actualPrice > (float) $sellingPrice

                            )



                            <span class="product-old-price">



                                ₹{{ number_format((float) $actualPrice, 0) }}



                            </span>



                        @endif





                        @if($discount > 0)



                            <span class="product-discount">



                                {{ $discount }}% OFF



                            </span>



                        @endif



                    </div>





                    <div class="product-tax-note">



                        Prices include applicable taxes



                    </div>





                    <!-- =================================================

                                                                 SHORT DESCRIPTION

                                                            ================================================= -->



                    @if(!empty($product->short_description))



                        <div class="product-short-description">



                            {!! nl2br(e($product->short_description)) !!}



                        </div>



                    @elseif(!empty($product->description))



                        <div class="product-short-description">



                            {!! nl2br(e(Str::limit(strip_tags($product->description), 350))) !!}



                        </div>



                    @endif





                    <!-- =================================================

                                                                 VARIANTS

                                                            ================================================= -->



                    @if($product->variants->count() >= 1)



                        <div class="product-option">



                            <div class="option-heading">



                                <h5 class="option-title">

                                    VARIANT

                                </h5>



                                @php

                                    $selectedVariantName = $variant?->sku ?? 'Select';



                                    if ($variant && $variant->attributeMappings->count()) {

                                        $firstMapping = $variant->attributeMappings->first();



                                        if ($firstMapping->value) {

                                            $selectedVariantName = $firstMapping->value->name;

                                        }

                                    }

                                @endphp



                                <span class="option-selected" id="selectedVariantName">

                                    {{ $selectedVariantName }}

                                </span>



                            </div>





                            <div class="variant-options">



                                @foreach($product->variants as $productVariant)



                                    @php

                                        $variantName = $productVariant->sku ?: 'Variant ' . $loop->iteration;



                                        if ($productVariant->attributeMappings->count()) {

                                            $firstMapping = $productVariant->attributeMappings->first();



                                            if ($firstMapping->value) {

                                                $variantName = $firstMapping->value->name;

                                            }

                                        }

                                    @endphp



                                    <button type="button"
                                        class="variant-option {{ $productVariant->id === $variant?->id ? 'active' : '' }}"
                                        data-variant-id="{{ $productVariant->id }}" data-variant-name="{{ $variantName }}"
                                        data-variant-image="{{ !empty($productVariant->image) ? asset($productVariant->image) : '' }}"
                                        data-variant-price="{{ $productVariant->price }}"
                                        data-variant-actual-price="{{ $productVariant->actual_price }}"
                                        data-variant-sku="{{ $productVariant->sku }}">



                                        {{ $variantName }}



                                    </button>



                                @endforeach



                            </div>



                        </div>



                    @endif





                    <!-- =================================================

                                                                 PRODUCT INFORMATION FROM DATABASE

                                                            ================================================= -->



                    <div class="product-option">



                        <div class="option-heading">



                            <h5 class="option-title">

                                PRODUCT

                            </h5>



                            <span class="option-selected">

                                {{ $product->category?->title ?? 'Food Item' }}

                            </span>



                        </div>





                        <div class="variant-options">



                            @if($product->category)



                                <button type="button" class="variant-option active">



                                    {{ $product->category->title }}



                                </button>



                            @endif





                            @if($product->brand)



                                <button type="button" class="variant-option">



                                    {{ $product->brand->name }}



                                </button>



                            @endif



                        </div>



                    </div>





                    <!-- =================================================

                                                                 PURCHASE

                                                            ================================================= -->



                    <div class="purchase-area">



                        <div class="quantity-cart-row">





                            <!-- Quantity -->



                            <div class="quantity-selector">



                                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">

                                    −

                                </button>





                                <span class="quantity-value" id="quantityValue">

                                    1

                                </span>





                                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">

                                    +

                                </button>



                            </div>





                            <!-- Cart -->



                            <button type="button" class="add-cart-button" data-product-id="{{ $product->id }}"
                                data-variant-id="{{ $variant?->id }}">



                                <i class="fa-solid fa-bag-shopping me-2"></i>



                                ADD TO CART



                            </button>



                        </div>





                        <!-- Buy Now -->



                        <!-- <button type="button" class="buy-now-button" data-product-id="{{ $product->id }}"
                                                        data-variant-id="{{ $variant?->id }}">



                                                        ORDER NOW



                                                    </button> -->

                        <!-- <button type="button" class="buy-now-button" data-product-id="{{ $product->id }}"
                                                data-variant-id="{{ $variant?->id }}" data-url="{{ route('customer.order-now') }}">
                                                ORDER NOW
                                            </button> -->

                        <form id="buyNowForm" action="{{ route('customer.order-now') }}" method="POST">
                            @csrf

                            <input type="hidden" name="product_variant_id" value="{{ $variant?->id }}">

                            <input type="hidden" name="quantity" id="buyNowQuantity" value="1">

                            <button type="submit" class="buy-now-button" {{ !$variant ? 'disabled' : '' }}>
                                ORDER NOW
                            </button>
                        </form>





                    </div>





                    <!-- =================================================

                                                                 DELIVERY

                                                            ================================================= -->



                    <div class="delivery-box">



                        <div class="delivery-title">



                            <i class="fa-solid fa-truck"></i>



                            Check Delivery Availability



                        </div>





                        <div class="pincode-row">



                            <input type="text" class="pincode-input" id="pincodeInput" placeholder="Enter Pincode"
                                maxlength="6">





                            <button type="button" class="check-pincode" onclick="checkPincode()">



                                CHECK



                            </button>



                        </div>





                        <div id="pincodeMessage" style="

                                                                        margin-top:8px;

                                                                        font-size:12px;

                                                                        color:#777;

                                                                    "></div>



                    </div>





                    <!-- =================================================

                                                                 FEATURES

                                                            ================================================= -->



                    <div class="product-features">



                        <div class="product-feature">



                            <i class="fa-solid fa-truck-fast"></i>



                            <span>

                                Fast Food Delivery

                            </span>



                        </div>





                        <div class="product-feature">



                            <i class="fa-solid fa-rotate-left"></i>



                            <span>

                                Order Support

                            </span>



                        </div>





                        <div class="product-feature">



                            <i class="fa-solid fa-shield-halved"></i>



                            <span>

                                Secure Payment

                            </span>



                        </div>



                    </div>



                </div>



            </div>





            <!-- =====================================================

                                                         PRODUCT INFORMATION

                                                    ===================================================== -->



            <div class="product-extra-section">







                <!-- Tabs -->



                <div class="product-tabs">



                    <button type="button" class="product-tab active" onclick="openTab(event, 'descriptionTab')">

                        DESCRIPTION

                    </button>





                    <button type="button" class="product-tab" onclick="openTab(event, 'detailsTab')">

                        FOOD DETAILS

                    </button>





                    <button type="button" class="product-tab" onclick="openTab(event, 'shippingTab')">

                        DELIVERY INFORMATION

                    </button>



                </div>





                <div class="tab-content">





                    <!-- =================================================

                                                                 DESCRIPTION

                                                            ================================================= -->



                    <div id="descriptionTab" class="tab-panel active">



                        <h4>

                            About {{ $product->title }}

                        </h4>





                        @if(!empty($product->description))



                            {!! $product->description !!}



                        @elseif(!empty($product->short_description))



                            <p>

                                {!! nl2br(e($product->short_description)) !!}

                            </p>



                        @else



                            <p>

                                Food description is currently unavailable.

                            </p>



                        @endif



                    </div>





                    <!-- =================================================

                                                                 FOOD DETAILS

                                                            ================================================= -->



                    <div id="detailsTab" class="tab-panel">



                        <h4>

                            Food Details

                        </h4>





                        <div class="details-list">





                            @if($product->category)



                                <div class="details-item">



                                    <span class="details-label">

                                        Category

                                    </span>



                                    <span class="details-value">

                                        {{ $product->category->title }}

                                    </span>



                                </div>



                            @endif





                            @if($product->brand)



                                <div class="details-item">



                                    <span class="details-label">

                                        Brand

                                    </span>



                                    <span class="details-value">

                                        {{ $product->brand->name }}

                                    </span>



                                </div>



                            @endif





                            @if($variant?->sku)



                                <div class="details-item">



                                    <span class="details-label">

                                        SKU

                                    </span>



                                    <span class="details-value">

                                        {{ $variant->sku }}

                                    </span>



                                </div>



                            @endif





                            @if($variant?->weight)



                                <div class="details-item">



                                    <span class="details-label">

                                        Weight

                                    </span>



                                    <span class="details-value">

                                        {{ $variant->weight }}

                                    </span>



                                </div>



                            @endif





                            @if($variant)



                                <div class="details-item">



                                    <span class="details-label">

                                        Availability

                                    </span>



                                    <span class="details-value">



                                        @if($variant->preorder)



                                            Pre-order



                                        @else



                                            Available



                                        @endif



                                    </span>



                                </div>



                            @endif





                            @if($product->status)



                                <div class="details-item">



                                    <span class="details-label">

                                        Status

                                    </span>



                                    <span class="details-value">

                                        {{ ucfirst($product->status) }}

                                    </span>



                                </div>



                            @endif





                        </div>



                    </div>





                    <!-- =================================================

                                                                 SHIPPING

                                                            ================================================= -->



                    <div id="shippingTab" class="tab-panel">



                        <h4>

                            Delivery Information

                        </h4>



                        <p>

                            Food orders are prepared and dispatched within

                            the estimated delivery period. Delivery timelines

                            may vary depending on your location.

                        </p>



                        <p>

                            For order issues, please contact customer support according to the applicable

                            food order policy. Please keep your order details ready

                            when requesting assistance.

                        </p>



                    </div>



                </div>



            </div>



        </div>



    </section>




<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // PRODUCT IMAGE
    // =========================================================
    window.changeProductImage = function (element, image) {
        if (!image) return;

        const mainImage = document.getElementById('mainProductImage');

        if (mainImage) mainImage.src = image;

        document.querySelectorAll('.product-thumb').forEach(function (thumb) {
            thumb.classList.remove('active');
        });

        if (element) element.classList.add('active');
    };

    // =========================================================
    // QUANTITY
    // =========================================================
    let quantity = 1;

    window.changeQuantity = function (value) {
        quantity = Math.max(1, quantity + Number(value));

        const display = document.getElementById('quantityValue');
        const buyQuantity = document.getElementById('buyNowQuantity');

        if (display) display.textContent = quantity;
        if (buyQuantity) buyQuantity.value = quantity;
    };

    // =========================================================
    // WISHLIST
    // =========================================================
    const wishlistVariantIds = (@json($wishlistVariantIds ?? [])).map(Number);

    function updateWishlistIcon(variantId) {
        const button = document.querySelector('.product-image-wishlist');
        if (!button) return;

        const icon = button.querySelector('i');
        const selected = wishlistVariantIds.includes(Number(variantId));

        button.classList.toggle('active', selected);

        if (icon) {
            icon.classList.toggle('fa-solid', selected);
            icon.classList.toggle('fa-regular', !selected);
        }
    }

    // =========================================================
    // VARIANT SELECTION
    // =========================================================
    document.querySelectorAll('.variant-option[data-variant-id]').forEach(function (button) {
        button.addEventListener('click', function () {
            const variantId = this.dataset.variantId;
            const parent = this.closest('.variant-options');

            if (parent) {
                parent.querySelectorAll('.variant-option').forEach(function (item) {
                    item.classList.remove('active');
                });
            }

            this.classList.add('active');

            // Keep both purchase buttons synchronized.
            const buyVariant = document.querySelector(
                '#buyNowForm input[name="product_variant_id"]'
            );
            const cartButton = document.querySelector('.add-cart-button');
            const wishlistButton = document.querySelector('.product-image-wishlist');

            if (buyVariant) buyVariant.value = variantId;
            if (cartButton) cartButton.dataset.variantId = variantId;
            if (wishlistButton) wishlistButton.dataset.variantId = variantId;

            updateWishlistIcon(variantId);

            const selectedName = document.getElementById('selectedVariantName');
            if (selectedName) {
                selectedName.textContent =
                    this.dataset.variantName || this.dataset.variantSku || 'Selected';
            }

            if (this.dataset.variantImage) {
                const mainImage = document.getElementById('mainProductImage');
                if (mainImage) mainImage.src = this.dataset.variantImage;
            }

            const currentPrice = document.querySelector('.product-current-price');
            const oldPrice = document.querySelector('.product-old-price');

            if (currentPrice && this.dataset.variantPrice !== '') {
                currentPrice.textContent =
                    '₹' + Number(this.dataset.variantPrice).toLocaleString('en-IN');
            }

            if (oldPrice && this.dataset.variantActualPrice !== '') {
                oldPrice.textContent =
                    '₹' + Number(this.dataset.variantActualPrice).toLocaleString('en-IN');
            }
        });
    });

    const initialWishlistButton = document.querySelector('.product-image-wishlist');
    if (initialWishlistButton?.dataset.variantId) {
        updateWishlistIcon(initialWishlistButton.dataset.variantId);
    }

    if (initialWishlistButton) {
        initialWishlistButton.addEventListener('click', async function (event) {
            event.preventDefault();

            const variantId = this.dataset.variantId;

            if (!variantId) {
                alert('Please select a product variant.');
                return;
            }

            try {
                const response = await fetch(@json(route('wishlist.add')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({
                        product_variant_id: variantId
                    })
                });

                const data = await response.json();

                if (response.status === 401) {
                    window.location.href = @json(route('login'));
                    return;
                }

                if (!response.ok || !data.status) {
                    throw new Error(data.message || 'Unable to update wishlist.');
                }

                const id = Number(variantId);
                if (!wishlistVariantIds.includes(id)) {
                    wishlistVariantIds.push(id);
                }

                updateWishlistIcon(id);
                alert(data.message || 'Wishlist updated.');

            } catch (error) {
                console.error('Wishlist error:', error);
                alert(error.message || 'Something went wrong.');
            }
        });
    }

    // =========================================================
    // ADD TO CART
    // =========================================================
    const addCartButton = document.querySelector('.add-cart-button');

    if (addCartButton) {
        addCartButton.addEventListener('click', async function () {
            const button = this;
            const variantId = button.dataset.variantId;
            const selectedQuantity = Math.max(1, quantity);
            const originalText = button.innerHTML;

            if (!variantId) {
                alert('Please select a product variant.');
                return;
            }

            button.disabled = true;
            button.textContent = 'ADDING...';

            try {
                const response = await fetch(@json(route('cart.add')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({
                        product_variant_id: variantId,
                        quantity: selectedQuantity
                    })
                });

                const data = await response.json();

                if (response.status === 401) {
                    window.location.href = @json(route('login'));
                    return;
                }

                if (!response.ok || !data.status) {
                    throw new Error(data.message || 'Unable to add product to cart.');
                }

                document.querySelectorAll('.cart-count').forEach(function (element) {
                    element.textContent = data.cart_count ?? '';
                });

                button.textContent = 'ADDED TO CART';

                setTimeout(function () {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);

            } catch (error) {
                console.error('Add to cart error:', error);
                alert(error.message || 'Unable to add product to cart.');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    }

    // =========================================================
    // ORDER NOW — SUBMIT FORM TO LARAVEL
    // =========================================================
    const buyNowForm = document.getElementById('buyNowForm');

    if (buyNowForm) {
        buyNowForm.addEventListener('submit', function (event) {
            const variantInput = buyNowForm.querySelector(
                'input[name="product_variant_id"]'
            );
            const quantityInput = buyNowForm.querySelector(
                'input[name="quantity"]'
            );
            const button = buyNowForm.querySelector('button[type="submit"]');

            if (!variantInput || !variantInput.value) {
                event.preventDefault();
                alert('Please select a product variant.');
                return;
            }

            quantityInput.value = Math.max(1, quantity);

            // Prevent double-clicks without cancelling form submission.
            if (button) {
                button.disabled = true;
                button.textContent = 'PROCESSING...';
            }
        });
    }

    // =========================================================
    // PINCODE
    // =========================================================
    window.checkPincode = function () {
        const input = document.getElementById('pincodeInput');
        const message = document.getElementById('pincodeMessage');

        if (!input || !message) return;

        const pincode = input.value.trim();

        if (!/^[0-9]{6}$/.test(pincode)) {
            message.textContent = 'Please enter a valid 6-digit pincode.';
            message.style.color = '#b00020';
            return;
        }

        message.textContent =
            'Delivery availability will be checked for this pincode.';
        message.style.color = '#497d45';
    };

    // =========================================================
    // PRODUCT TABS
    // =========================================================
    window.openTab = function (event, tabId) {
        document.querySelectorAll('.tab-panel').forEach(function (panel) {
            panel.classList.remove('active');
        });

        document.querySelectorAll('.product-tab').forEach(function (tab) {
            tab.classList.remove('active');
        });

        const panel = document.getElementById(tabId);
        if (panel) panel.classList.add('active');

        if (event?.currentTarget) {
            event.currentTarget.classList.add('active');
        }
    };

});
</script>


@endsection