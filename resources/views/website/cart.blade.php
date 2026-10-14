@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                                   SUDHEERA SAREES - PREMIUM CART
                                ========================================================= */

        .sudheera-cart-page {
            background: #fffdf9;
            color: #2d241e;
        }

        /* =========================================================
                                   BREADCRUMB
                                ========================================================= */

        .sudheera-cart-breadcrumb {
            background: #f8f3eb;
            border-bottom: 1px solid #eee5d9;
            padding: 0;
        }

        .sudheera-cart-breadcrumb .breadcrumb-content {
            padding: 28px 0;
        }

        .sudheera-cart-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-left: 20px;
        }

        .sudheera-cart-breadcrumb li,
        .sudheera-cart-breadcrumb a {
            font-size: 13px;
            color: #81766c;
            text-decoration: none;
        }

        .sudheera-cart-breadcrumb .current {
            color: #a96b18;
            font-weight: 600;
        }


        /* =========================================================
                                   MAIN SECTION
                                ========================================================= */

        .sudheera-cart-section {
            padding: 55px 0 80px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .sudheera-cart-heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .sudheera-cart-heading .small-title {
            display: block;
            margin-bottom: 8px;
            color: #a96b18;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .sudheera-cart-heading h1 {
            margin: 0 0 10px;
            color: #2d241e;
            font-family: "Instrument Serif", serif;
            font-size: 46px;
            font-weight: 500;
            line-height: 1.2;
        }

        .sudheera-cart-heading p {
            margin: 0;
            color: #81766c;
            font-size: 14px;
        }


        /* =========================================================
                                   CART LEFT
                                ========================================================= */

        .sudheera-cart-left {
            background: #fff;
            border: 1px solid #eee6dc;
            border-radius: 12px;
            padding: 25px 28px;
        }

        .sudheera-cart-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            border-bottom: 1px solid #eee6dc;
            margin-bottom: 0;
        }

        .sudheera-cart-top h4 {
            margin: 0;
            color: #2d241e;
            font-size: 17px;
            font-weight: 600;
        }

        .sudheera-cart-top span {
            color: #91867b;
            font-size: 12px;
        }


        /* =========================================================
                                   PRODUCT
                                ========================================================= */

        .sudheera-cart-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 145px 110px;
            gap: 25px;
            align-items: center;
            padding: 25px 0;
            border-bottom: 1px solid #eee6dc;
        }

        .sudheera-cart-item:last-child {
            border-bottom: 0;
        }

        .sudheera-product {
            display: flex;
            align-items: center;
            gap: 20px;
            min-width: 0;
        }

        .sudheera-product-image {
            width: 105px;
            height: 130px;
            flex-shrink: 0;
            display: block;
            overflow: hidden;
            border-radius: 6px;
            background: #f5eee5;
        }

        .sudheera-product-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: fill;
            transition: .5s ease;
        }

        .sudheera-product-image:hover img {
            transform: scale(1.06);
        }

        .sudheera-product-info {
            min-width: 0;
        }

        .sudheera-product-name {
            display: block;
            color: #2e261f;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.45;
            text-decoration: none;
            margin-bottom: 8px;
        }

        .sudheera-product-name:hover {
            color: #a96b18;
        }

        .sudheera-product-variant {
            display: inline-block;
            padding: 5px 9px;
            background: #f8f2ea;
            border-radius: 4px;
            color: #82766a;
            font-size: 11px;
            margin-bottom: 9px;
        }

        .sudheera-product-price {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sudheera-price-new {
            color: #a96b18;
            font-size: 15px;
            font-weight: 700;
        }

        .sudheera-price-old {
            color: #aaa;
            font-size: 12px;
            text-decoration: line-through;
        }

        .sudheera-remove {
            margin-top: 10px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #968b82;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .3s ease;
        }

        .sudheera-remove:hover {
            color: #a96b18;
        }


        /* =========================================================
                                   QUANTITY
                                ========================================================= */

        .sudheera-quantity {
            text-align: center;
        }

        .sudheera-quantity-label {
            display: block;
            margin-bottom: 8px;
            color: #91867b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sudheera-quantity-box {
            display: inline-flex;
            align-items: center;
            height: 40px;
            border: 1px solid #ded5ca;
            border-radius: 5px;
            overflow: hidden;
            background: #fff;
        }

        .sudheera-quantity-box button {
            width: 35px;
            height: 40px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #665b52;
            font-size: 16px;
            cursor: pointer;
            transition: .3s ease;
        }

        .sudheera-quantity-box button:hover {
            color: #a96b18;
            background: #faf5ee;
        }

        .sudheera-quantity-box input {
            width: 40px;
            height: 40px;
            padding: 0;
            border: 0;
            border-left: 1px solid #eee6dc;
            border-right: 1px solid #eee6dc;
            outline: none;
            text-align: center;
            color: #332a23;
            font-size: 13px;
            background: #fff;
        }

        .sudheera-update {
            display: block;
            margin: 7px auto 0;
            padding: 0;
            border: 0;
            background: transparent;
            color: #a96b18;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
        }


        /* =========================================================
                                   ITEM TOTAL
                                ========================================================= */

        .sudheera-item-total {
            text-align: right;
            color: #2e261f;
            font-size: 15px;
            font-weight: 600;
        }


        /* =========================================================
                                   SIDEBAR
                                ========================================================= */

        .sudheera-cart-sidebar {
            position: sticky;
            top: 20px;
            background: #f8f3eb;
            border: 1px solid #eee4d8;
            border-radius: 12px;
            padding: 28px;
        }


        /* =========================================================
                                   FREE SHIPPING
                                ========================================================= */

        .sudheera-shipping-message {
            padding-bottom: 22px;
            margin-bottom: 24px;
            border-bottom: 1px solid #e4d9cc;
        }

        .sudheera-shipping-message p {
            margin: 0 0 12px;
            color: #5f554d;
            font-size: 12px;
        }

        .sudheera-shipping-message strong {
            color: #a96b18;
        }

        .sudheera-progress {
            width: 100%;
            height: 5px;
            overflow: hidden;
            background: #ded4c8;
            border-radius: 20px;
        }

        .sudheera-progress span {
            display: block;
            width: 65%;
            height: 100%;
            background: #a96b18;
            border-radius: 20px;
        }


        /* =========================================================
                                   GIFT
                                ========================================================= */

        .sudheera-gift-box {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px;
            margin-bottom: 28px;
            background: #fff;
            border: 1px solid #e8ded2;
            border-radius: 8px;
        }

        .sudheera-gift-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5e6d2;
            border-radius: 50%;
            font-size: 19px;
        }

        .sudheera-gift-content h5 {
            margin: 0 0 5px;
            color: #342a22;
            font-size: 13px;
            font-weight: 600;
        }

        .sudheera-gift-content p {
            margin: 0 0 8px;
            color: #83776d;
            font-size: 11px;
            line-height: 1.6;
        }

        .sudheera-gift-content a {
            color: #a96b18;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: .5px;
        }


        /* =========================================================
                                   SUMMARY
                                ========================================================= */

        .sudheera-summary-title {
            margin: 0 0 20px;
            color: #2d241e;
            font-family: "Instrument Serif", serif;
            font-size: 28px;
            font-weight: 500;
        }

        .sudheera-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            color: #655b52;
            font-size: 13px;
        }

        .sudheera-summary-row span:last-child {
            color: #342a23;
            font-weight: 500;
        }

        .sudheera-summary-row.discount span:last-child {
            color: #a96b18;
        }

        .sudheera-summary-row.total {
            padding-top: 18px;
            margin-top: 18px;
            border-top: 1px solid #ddd2c5;
            color: #2e261f;
            font-size: 17px;
            font-weight: 600;
        }

        .sudheera-summary-row.total span:last-child {
            color: #a96b18;
            font-size: 20px;
            font-weight: 700;
        }

        .sudheera-saved {
            padding: 9px 12px;
            margin: 16px 0 20px;
            background: #f0e5d5;
            border-radius: 5px;
            color: #8c5e24;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
        }


        /* =========================================================
                                   CHECKOUT
                                ========================================================= */

        .sudheera-checkout-btn {
            display: flex;
            width: 100%;
            height: 52px;
            align-items: center;
            justify-content: center;
            background: #a96b18;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.3px;
            transition: .3s ease;
        }

        .sudheera-checkout-btn:hover {
            background: #875310;
            color: #fff;
            transform: translateY(-2px);
        }

        .sudheera-continue {
            display: block;
            margin-top: 16px;
            color: #665d55;
            text-align: center;
            font-size: 11px;
            text-decoration: underline;
        }


        /* =========================================================
                                   EMPTY CART
                                ========================================================= */

        .sudheera-empty-cart {
            padding: 80px 20px;
            background: #fff;
            border: 1px solid #eee5da;
            border-radius: 10px;
            text-align: center;
        }

        .sudheera-empty-cart img {
            width: 160px;
            max-width: 100%;
            margin-bottom: 18px;
        }

        .sudheera-empty-cart h4 {
            margin-bottom: 8px;
            color: #342a23;
            font-family: "Instrument Serif", serif;
            font-size: 28px;
        }

        .sudheera-empty-cart p {
            margin-bottom: 22px;
            color: #888078;
            font-size: 13px;
        }


        /* =========================================================
                                   TABLET
                                ========================================================= */

        @media (max-width: 991px) {

            .sudheera-cart-section {
                padding: 40px 0 60px;
            }

            .sudheera-cart-heading h1 {
                font-size: 40px;
            }

            .sudheera-cart-item {
                grid-template-columns: minmax(0, 1fr) 130px 80px;
                gap: 15px;
            }

            .sudheera-product-image {
                width: 90px;
                height: 115px;
            }

            .sudheera-cart-sidebar {
                position: static;
                margin-top: 25px;
            }
        }


        /* =========================================================
                                   MOBILE
                                ========================================================= */

        @media (max-width: 767px) {

            .sudheera-cart-breadcrumb .breadcrumb-content {
                padding: 22px 0;
            }

            .sudheera-cart-section {
                padding: 30px 0 45px;
            }

            .sudheera-cart-heading {
                margin-bottom: 28px;
            }

            .sudheera-cart-heading .small-title {
                font-size: 9px;
            }

            .sudheera-cart-heading h1 {
                font-size: 32px;
            }

            .sudheera-cart-heading p {
                font-size: 12px;
            }

            .sudheera-cart-left {
                padding: 18px 15px;
            }

            .sudheera-cart-top {
                padding-bottom: 15px;
            }

            .sudheera-cart-top h4 {
                font-size: 15px;
            }

            .sudheera-cart-item {
                grid-template-columns: 1fr;
                gap: 10px;
                position: relative;
                padding: 18px 0;
            }

            .sudheera-product {
                gap: 13px;
                padding-right: 60px;
            }

            .sudheera-product-image {
                width: 78px;
                height: 100px;
            }

            .sudheera-product-name {
                font-size: 13px;
            }

            .sudheera-product-variant {
                font-size: 9px;
                padding: 4px 7px;
            }

            .sudheera-price-new {
                font-size: 12px;
            }

            .sudheera-price-old {
                font-size: 10px;
            }

            .sudheera-item-total {
                position: absolute;
                top: 20px;
                right: 0;
                font-size: 12px;
            }

            .sudheera-quantity {
                text-align: left;
                padding-left: 91px;
            }

            .sudheera-quantity-label {
                text-align: left;
            }

            .sudheera-quantity-box {
                height: 36px;
            }

            .sudheera-quantity-box button {
                width: 31px;
                height: 36px;
            }

            .sudheera-quantity-box input {
                width: 35px;
                height: 36px;
            }

            .sudheera-cart-sidebar {
                padding: 20px 16px;
                border-radius: 9px;
            }

            .sudheera-summary-title {
                font-size: 24px;
            }
        }


        /* =========================================================
                                   SMALL MOBILE
                                ========================================================= */

        @media (max-width: 480px) {

            .sudheera-cart-heading h1 {
                font-size: 29px;
            }

            .sudheera-cart-left {
                padding: 15px 12px;
            }

            .sudheera-product-image {
                width: 70px;
                height: 90px;
            }

            .sudheera-product {
                gap: 10px;
                padding-right: 55px;
            }

            .sudheera-product-name {
                font-size: 12px;
            }

            .sudheera-quantity {
                padding-left: 80px;
            }

            .sudheera-summary-row {
                font-size: 12px;
            }

            .sudheera-summary-row.total {
                font-size: 15px;
            }

            .sudheera-summary-row.total span:last-child {
                font-size: 18px;
            }
        }
    </style>


    <!-- =========================================================
                                 BREADCRUMB
                            ========================================================= -->

    <section class="sudheera-cart-breadcrumb">

        <div class="container">

            <div class="breadcrumb-content">

                <ul>
                    <li>
                        <a href="#">Home</a>
                    </li>

                    <li>/</li>

                    <li class="current">
                        Cart
                    </li>
                </ul>

            </div>

        </div>

    </section>


    <!-- =========================================================
                                 CART
                            ========================================================= -->


    <!-- =========================================================
                                 CART
                            ========================================================= -->

    <section class="sudheera-cart-page">

        <div class="sudheera-cart-section">

            <div class="container">

                <!-- HEADING -->

                <div class="sudheera-cart-heading">

                    <span class="small-title">
                        BAKASURAN FOOD
                    </span>

                    <h1>
                        Your Food Cart
                    </h1>

                    <p>
                        Review your favourite dishes before placing your order.
                    </p>

                </div>


                @if($cartItems->count() > 0)

                    <div class="row gy-30">

                        <!-- =================================================
                                                                                 LEFT CART
                                                                            ================================================== -->

                        <div class="col-lg-7">

                            <div class="sudheera-cart-left">

                                <div class="sudheera-cart-top">

                                    <h4>
                                        Your Items
                                    </h4>

                                    <span>
                                        {{ $cartCount }}
                                        {{ $cartCount == 1 ? 'Item' : 'Items' }}
                                    </span>

                                </div>


                                @foreach($cartItems as $item)

                                    @php

                                        $variant = $item->variant;

                                        $product = $variant?->product;

                                        $sellingPrice = (float) (
                                            $item->unit_price
                                            ?? $variant?->price
                                            ?? 0
                                        );

                                        $actualPrice = (float) (
                                            $variant?->actual_price
                                            ?? $sellingPrice
                                        );

                                        $quantity = (int) $item->quantity;

                                        $itemTotal = $sellingPrice * $quantity;

                                        $image = $variant?->image
                                            ?? $product?->image;

                                        /*
                                         * If image is stored in storage,
                                         * change this according to your project.
                                         */
                                        $imageUrl = $image
                                            ? asset($image)
                                            : asset('website/images/product1.webp');

                                    @endphp


                                    <!-- PRODUCT -->

                                    <div class="sudheera-cart-item" data-cart-item-id="{{ $item->id }}">

                                        <div class="sudheera-product">

                                            <a href="{{ $product?->slug ? route('productdetails', ['slug' => $product->slug]) : '#' }}"
                                                class="sudheera-product-image">

                                                <img loading="lazy" src="{{ $imageUrl }}" alt="{{ $product?->title ?? 'Product' }}">

                                            </a>


                                            <div class="sudheera-product-info">

                                                <a href="{{ $product?->slug ? route('productdetails', ['slug' => $product->slug]) : '#' }}"
                                                    class="sudheera-product-name">

                                                    {{ $product?->title ?? 'Product' }}

                                                </a>


                                                @if($variant?->attributeValues && $variant->attributeValues->count())

                                                    @foreach($variant->attributeValues as $attributeValue)

                                                        <div class="sudheera-product-variant">

                                                            {{ $attributeValue->attribute?->name ?? 'Option' }}:
                                                            {{ $attributeValue->value ?? $attributeValue->name ?? '' }}

                                                        </div>

                                                    @endforeach

                                                @endif


                                                <div class="sudheera-product-price">

                                                    <span class="sudheera-price-new">

                                                        ₹{{ number_format($sellingPrice, 2) }}

                                                    </span>


                                                    @if($actualPrice > $sellingPrice)

                                                        <span class="sudheera-price-old">

                                                            ₹{{ number_format($actualPrice, 2) }}

                                                        </span>

                                                    @endif

                                                </div>


                                                <!-- REMOVE BUTTON -->
                                                <button type="button" class="sudheera-remove remove-cart-item"
                                                    data-id="{{ $item->id }}">
                                                    REMOVE
                                                </button>

                                            </div>

                                        </div>


                                        <!-- QUANTITY -->

                                        <div class="sudheera-quantity">

                                            <span class="sudheera-quantity-label">
                                                Quantity
                                            </span>


                                            <div class="sudheera-quantity-box">

                                                <button type="button" class="quantity-minus" data-id="{{ $item->id }}">
                                                    −
                                                </button>


                                                <input type="number" class="cart-quantity" data-id="{{ $item->id }}"
                                                    value="{{ $quantity }}" min="1">


                                                <button type="button" class="quantity-plus" data-id="{{ $item->id }}">
                                                    +
                                                </button>

                                            </div>


                                            <button type="button" class="sudheera-update update-cart-item"
                                                data-id="{{ $item->id }}">

                                                UPDATE

                                            </button>

                                        </div>


                                        <!-- ITEM TOTAL -->

                                        <div class="sudheera-item-total">

                                            ₹{{ number_format($itemTotal, 2) }}

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        <!-- =================================================
                                                                                 RIGHT SIDEBAR
                                                                            ================================================== -->

                       <div class="col-lg-5">

    <div class="sudheera-cart-sidebar">

        {{-- DELIVERY OFFER --}}
        <div class="sudheera-shipping-message">

            @php
                $freeShippingAmount = 199;

                $remainingForShipping = max(
                    0,
                    $freeShippingAmount - $subtotal
                );

                $shippingProgress = min(
                    100,
                    ($subtotal / $freeShippingAmount) * 100
                );
            @endphp

            @if($remainingForShipping > 0)

                <p>
                    Add
                    <strong>
                        ₹{{ number_format($remainingForShipping, 2) }} more
                    </strong>
                    to unlock FREE DELIVERY!
                </p>

            @else

                <p>
                    <strong>
                        You've unlocked FREE DELIVERY!
                    </strong>
                </p>

            @endif

            <div class="sudheera-progress">
                <span style="width: {{ $shippingProgress }}%;"></span>
            </div>

        </div>

        {{-- SPECIAL OFFER --}}
        <div class="sudheera-gift-box">

            <div class="sudheera-gift-icon">
                🍽️
            </div>

            <div class="sudheera-gift-content">

                <h5>
                    Make Your Meal Special
                </h5>

                <p>
                    Discover delicious dishes, exciting combos,
                    and special offers for your next meal.
                </p>

                <a href="{{ route('shop') }}">
                    EXPLORE FOOD
                </a>

            </div>

        </div>

        {{-- ORDER SUMMARY --}}
        <h3 class="sudheera-summary-title">
            Order Summary
        </h3>

        <div class="sudheera-summary-row">

            <span>Subtotal</span>

            <span>
                ₹{{ number_format($subtotal, 2) }}
            </span>

        </div>

        <div class="sudheera-summary-row">

            <span>Delivery Fee</span>

            <span>
                @if($shipping > 0)
                    ₹{{ number_format($shipping, 2) }}
                @else
                    FREE
                @endif
            </span>

        </div>

        @if($discount > 0)

            <div class="sudheera-summary-row discount">

                <span>Discount</span>

                <span>
                    - ₹{{ number_format($discount, 2) }}
                </span>

            </div>

        @endif

        <div class="sudheera-summary-row total">

            <span>Total Amount</span>

            <span id="cart-total">
                ₹{{ number_format($total, 2) }}
            </span>

        </div>

        @if($discount > 0)

            <div class="sudheera-saved">

                You saved ₹{{ number_format($discount, 2) }}
                on this order!

            </div>

        @endif

        <a href="{{ route('checkout') }}" class="sudheera-checkout-btn">
            PROCEED TO CHECKOUT
        </a>

        <a href="{{ route('shop') }}" class="sudheera-continue">
            Continue Exploring Food
        </a>

    </div>

</div>

                    </div>

                @else

                    <!-- =================================================
                                                                             EMPTY CART
                                                                        ================================================== -->

                    <div class="sudheera-empty-cart">

    <img src="{{ asset('website/images/empty-cart.png') }}" alt="Empty Food Cart"
        onerror="this.style.display='none'">

    <h4>
        Your Cart is Empty
    </h4>

    <p>
        Your next delicious meal is waiting!
        Explore our menu and add your favourite dishes to your cart.
    </p>

    <a href="{{ route('shop') }}" class="sudheera-checkout-btn" style="max-width:220px;margin:0 auto;">

        EXPLORE FOOD

    </a>

</div>

                @endif

            </div>

        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* =========================================================
               INCREMENT
            ========================================================= */

            document.querySelectorAll('.quantity-plus').forEach(function (button) {

                button.addEventListener('click', function () {

                    const cartItem = this.closest('.sudheera-cart-item');

                    if (!cartItem) {
                        return;
                    }

                    const input = cartItem.querySelector('.cart-quantity');

                    if (!input) {
                        return;
                    }

                    let quantity = parseInt(input.value) || 1;

                    quantity++;

                    input.value = quantity;
                });

            });


            /* =========================================================
               DECREMENT
            ========================================================= */

            document.querySelectorAll('.quantity-minus').forEach(function (button) {

                button.addEventListener('click', function () {

                    const cartItem = this.closest('.sudheera-cart-item');

                    if (!cartItem) {
                        return;
                    }

                    const input = cartItem.querySelector('.cart-quantity');

                    if (!input) {
                        return;
                    }

                    let quantity = parseInt(input.value) || 1;

                    if (quantity > 1) {
                        quantity--;
                    }

                    input.value = quantity;
                });

            });


            /* =========================================================
               PREVENT QUANTITY BELOW 1
            ========================================================= */

            document.querySelectorAll('.cart-quantity').forEach(function (input) {

                input.addEventListener('input', function () {

                    let quantity = parseInt(this.value);

                    if (!quantity || quantity < 1) {
                        this.value = 1;
                    }

                });

            });


            /* =========================================================
               UPDATE CART
            ========================================================= */

            document.querySelectorAll('.update-cart-item').forEach(function (button) {

                button.addEventListener('click', function () {

                    const cartItemId = this.dataset.id;

                    const cartItem = this.closest('.sudheera-cart-item');

                    if (!cartItem) {
                        return;
                    }

                    const input = cartItem.querySelector('.cart-quantity');

                    if (!input) {
                        return;
                    }

                    let quantity = parseInt(input.value) || 1;

                    if (quantity < 1) {
                        quantity = 1;
                        input.value = 1;
                    }

                    const currentButton = this;

                    currentButton.disabled = true;
                    currentButton.innerHTML = 'UPDATING...';


                    fetch("{{ url('/cart/update') }}/" + cartItemId, {

                        method: 'PUT',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },

                        body: JSON.stringify({
                            quantity: quantity
                        })

                    })

                        .then(async function (response) {

                            const data = await response.json();

                            if (response.status === 401) {
                                window.location.href = "{{ route('login') }}";
                                return;
                            }

                            if (!response.ok) {
                                throw new Error(
                                    data.message || 'Unable to update cart.'
                                );
                            }

                            return data;

                        })

                        .then(function (data) {

                            if (!data) {
                                return;
                            }

                            if (data.status) {

                                /* =================================================
                                   UPDATE ITEM TOTAL
                                ================================================= */

                                const itemTotal = cartItem.querySelector(
                                    '.sudheera-item-total'
                                );

                                if (itemTotal) {
                                    itemTotal.innerHTML =
                                        '₹' + data.item_total;
                                }


                                /* =================================================
                                   UPDATE SUBTOTAL
                                ================================================= */

                                document.querySelectorAll('.sudheera-summary-row')
                                    .forEach(function (row) {

                                        const label = row.querySelector(
                                            'span:first-child'
                                        );

                                        if (
                                            label &&
                                            label.textContent.trim() === 'Subtotal'
                                        ) {

                                            const value = row.querySelector(
                                                'span:last-child'
                                            );

                                            if (value) {
                                                value.textContent =
                                                    '₹' + data.subtotal;
                                            }
                                        }

                                    });


                                /* =================================================
                                   UPDATE TOTAL
                                ================================================= */

                                const cartTotal = document.getElementById('cart-total');

                                if (cartTotal) {
                                    cartTotal.textContent =
                                        '₹' + data.total;
                                }


                                /* =================================================
                                   UPDATE SHIPPING IF ELEMENT EXISTS
                                ================================================= */

                                const shippingAmount =
                                    document.getElementById('cart-shipping');

                                if (shippingAmount && data.shipping !== undefined) {

                                    if (parseFloat(data.shipping) > 0) {
                                        shippingAmount.textContent =
                                            '₹' + data.shipping;
                                    } else {
                                        shippingAmount.textContent = 'FREE';
                                    }

                                }


                                /* =================================================
                                   UPDATE CART COUNT
                                ================================================= */

                                document.querySelectorAll('.cart-count')
                                    .forEach(function (element) {

                                        element.textContent =
                                            data.cart_count;

                                    });


                                /* =================================================
                                   BUTTON SUCCESS
                                ================================================= */

                                currentButton.innerHTML = 'UPDATED ✓';

                                setTimeout(function () {

                                    currentButton.innerHTML = 'UPDATE';
                                    currentButton.disabled = false;

                                }, 1200);

                            } else {

                                currentButton.innerHTML = 'UPDATE';
                                currentButton.disabled = false;

                                alert(
                                    data.message ||
                                    'Unable to update cart.'
                                );

                            }

                        })

                        .catch(function (error) {

                            console.error(
                                'Update cart error:',
                                error
                            );

                            currentButton.innerHTML = 'UPDATE';
                            currentButton.disabled = false;

                            alert(
                                error.message ||
                                'Something went wrong. Please try again.'
                            );

                        });

                });

            });


            /* =========================================================
               REMOVE PRODUCT
            ========================================================= */

            document.querySelectorAll('.remove-cart-item').forEach(function (button) {

                button.addEventListener('click', function () {

                    const cartItemId = this.dataset.id;

                    const cartItem = this.closest('.sudheera-cart-item');

                    const currentButton = this;


                    if (!confirm(
                        'Are you sure you want to remove this product from your cart?'
                    )) {
                        return;
                    }


                    currentButton.disabled = true;
                    currentButton.innerHTML = 'REMOVING...';


                    fetch("{{ url('/cart/remove') }}/" + cartItemId, {

                        method: 'DELETE',

                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }

                    })

                        .then(function (response) {
                            return response.json();
                        })

                        .then(function (data) {

                            if (data.status) {

                                if (cartItem) {
                                    cartItem.remove();
                                }


                                /* Update header cart count */

                                document.querySelectorAll('.cart-count')
                                    .forEach(function (element) {

                                        element.textContent =
                                            data.cart_count;

                                    });


                                /* Reload page */

                                window.location.reload();

                            } else {

                                currentButton.disabled = false;
                                currentButton.innerHTML = 'REMOVE';

                                alert(
                                    data.message ||
                                    'Unable to remove product.'
                                );

                            }

                        })

                        .catch(function (error) {

                            console.error(
                                'Remove cart error:',
                                error
                            );

                            currentButton.disabled = false;
                            currentButton.innerHTML = 'REMOVE';

                            alert(
                                'Something went wrong. Please try again.'
                            );

                        });

                });

            });

        });
    </script>

@endsection