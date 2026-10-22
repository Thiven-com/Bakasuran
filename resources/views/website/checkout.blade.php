@extends('layouts.website')

@section('content')



    <style>
        /* =========================================================
                           SUDHEERA SAREES - STATIC CHECKOUT PAGE
                        ========================================================= */

        .sudheera-checkout {
            background: #fbf8f3;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        /* ================= BREADCRUMB ================= */

        .checkout-breadcrumb {
            background: #fff;
            border-bottom: 1px solid #eee4d8;
            padding: 22px 0;
        }

        .checkout-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 13px;
        }

        .checkout-breadcrumb a {
            text-decoration: none;
            color: #5b0719;
        }

        .checkout-breadcrumb span {
            color: #aaa;
        }

        /* ================= PAGE HEADER ================= */

        .checkout-header {
            text-align: center;
            padding: 55px 20px 35px;
        }

        .checkout-header small {
            color: #a77b32;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .checkout-header h1 {
            font-family: Georgia, "Times New Roman", serif;
            color: #3b0713;
            font-size: 45px;
            font-weight: 500;
            margin: 10px 0;
        }

        .checkout-header p {
            color: #777;
            font-size: 14px;
            margin: 0;
        }

        /* ================= MAIN CONTAINER ================= */

        .checkout-container {
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.45fr .8fr;
            gap: 28px;
            align-items: start;
        }

        /* ================= LEFT SIDE ================= */

        .checkout-left {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .checkout-card {
            background: #fff;
            border: 1px solid #eadfd2;
            border-radius: 14px;
            padding: 28px;
        }

        .checkout-card-title {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 25px;
        }

        .checkout-step {
            width: 35px;
            height: 35px;
            background: #4b0717;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .checkout-card-title h2 {
            margin: 0;
            color: #3b0713;
            font-family: Georgia, serif;
            font-size: 23px;
            font-weight: 500;
        }

        /* ================= INPUTS ================= */

        .checkout-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .checkout-field {
            margin-bottom: 17px;
        }

        .checkout-field label {
            display: block;
            color: #4d4d4d;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .checkout-field input,
        .checkout-field select,
        .checkout-field textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd2c4;
            border-radius: 7px;
            padding: 13px 14px;
            background: #fff;
            outline: none;
            color: #333;
            font-size: 13px;
            transition: .25s;
        }

        .checkout-field textarea {
            height: 95px;
            resize: none;
        }

        .checkout-field input:focus,
        .checkout-field select:focus,
        .checkout-field textarea:focus {
            border-color: #9d712f;
            box-shadow: 0 0 0 3px rgba(157, 113, 47, .08);
        }

        /* ================= ADDRESS ================= */

        .address-card {
            border: 1px solid #e4d8ca;
            border-radius: 10px;
            padding: 18px;
            display: flex;
            gap: 14px;
            cursor: pointer;
            transition: .25s;
        }

        .address-card:hover {
            border-color: #a77b32;
            background: #fffdfa;
        }

        .address-card input {
            margin-top: 4px;
            accent-color: #4b0717;
        }

        .address-card strong {
            display: block;
            color: #3b0713;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .address-card p {
            margin: 0;
            color: #777;
            font-size: 12px;
            line-height: 1.7;
        }

        /* ================= PAYMENT ================= */

        .payment-card {
            border: 1px solid #e3d7c9;
            border-radius: 10px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .payment-card:hover {
            border-color: #a77b32;
            background: #fffdfa;
        }

        .payment-card input {
            accent-color: #4b0717;
        }

        .payment-info {
            flex: 1;
        }

        .payment-info strong {
            display: block;
            color: #3b0713;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .payment-info span {
            color: #888;
            font-size: 11px;
        }

        .payment-icon {
            color: #9b722f;
            font-size: 19px;
        }

        /* ================= RIGHT ORDER SUMMARY ================= */

        .checkout-right {
            position: sticky;
            top: 25px;
        }

        .order-summary {
            background: #fff;
            border: 1px solid #eadfd2;
            border-radius: 15px;
            overflow: hidden;
        }

        .summary-top {
            padding: 25px;
            border-bottom: 1px solid #eee4d8;
        }

        .summary-top small {
            color: #a77b32;
            font-size: 10px;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .summary-top h2 {
            color: #3b0713;
            font-family: Georgia, serif;
            font-size: 25px;
            font-weight: 500;
            margin: 7px 0 0;
        }

        /* ================= PRODUCTS ================= */

        .summary-products {
            padding: 20px 25px 5px;
        }

        .summary-product {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
        }

        .summary-image {
            position: relative;
            width: 70px;
            height: 85px;
            border-radius: 7px;
            overflow: hidden;
            background: #f3ece3;
            flex-shrink: 0;
        }

        .summary-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quantity {
            position: absolute;
            right: 4px;
            top: 4px;
            background: #4b0717;
            color: #fff;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-product-info {
            flex: 1;
        }

        .summary-product-info h4 {
            margin: 0 0 6px;
            color: #3b0713;
            font-size: 13px;
            line-height: 1.4;
        }

        .summary-product-info span {
            color: #999;
            font-size: 11px;
        }

        .summary-price {
            color: #5e091a;
            font-size: 13px;
            font-weight: 700;
        }

        /* ================= COUPON ================= */

        .coupon {
            padding: 15px 25px 20px;
        }

        .coupon-box {
            display: flex;
            gap: 8px;
        }

        .coupon-box input {
            flex: 1;
            min-width: 0;
            border: 1px solid #ddd2c4;
            padding: 12px;
            border-radius: 6px;
            outline: none;
            font-size: 12px;
        }

        .coupon-box button {
            border: 0;
            background: #4b0717;
            color: #fff;
            border-radius: 6px;
            padding: 0 17px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        /* ================= TOTAL ================= */

        .summary-total {
            border-top: 1px solid #eee4d8;
            padding: 20px 25px 25px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 13px;
            margin-bottom: 13px;
        }

        .total-row strong {
            color: #3b0713;
        }

        .discount-row strong {
            color: #9a6c2b;
        }

        .grand-total {
            border-top: 1px solid #e7dbcd;
            padding-top: 18px;
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .grand-total span {
            color: #3b0713;
            font-size: 15px;
            font-weight: 600;
        }

        .grand-total strong {
            color: #5c091a;
            font-size: 23px;
        }

        /* ================= BUTTON ================= */

        .place-order {
            width: 100%;
            border: 0;
            border-radius: 7px;
            padding: 15px;
            margin-top: 20px;
            background: linear-gradient(135deg, #4b0717, #760e27);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .3s;
        }

        .place-order:hover {
            background: linear-gradient(135deg, #760e27, #a4772c);
            transform: translateY(-2px);
        }

        .secure-text {
            text-align: center;
            color: #999;
            font-size: 10px;
            margin-top: 12px;
        }

        /* ================= TRUST ================= */

        .trust-box {
            margin-top: 18px;
            background: #f7f0e8;
            border-radius: 10px;
            padding: 18px 10px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            text-align: center;
        }

        .trust-item {
            color: #685743;
            font-size: 10px;
        }

        .trust-item strong {
            display: block;
            color: #98702e;
            font-size: 17px;
            margin-bottom: 5px;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 991px) {

            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .checkout-right {
                position: static;
                order: -1;
            }

        }

        @media (max-width: 600px) {

            .checkout-header {
                padding: 40px 15px 30px;
            }

            .checkout-header h1 {
                font-size: 36px;
            }

            .checkout-container {
                padding: 0 13px;
            }

            .checkout-card {
                padding: 20px 16px;
            }

            .checkout-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .summary-top,
            .summary-products,
            .summary-total {
                padding-left: 17px;
                padding-right: 17px;
            }

            .coupon {
                padding-left: 17px;
                padding-right: 17px;
            }

            .trust-box {
                grid-template-columns: 1fr;
                gap: 12px;
            }

        }

        /* Checkout restaurant name */
.checkout-restaurant-name {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #de0000;
    margin-top: 5px;
    margin-bottom: 8px;
    line-height: 1.5;
}

/* Optional restaurant icon */
.checkout-restaurant-name::before {
    /* content: "📍"; */
     content: "🏪";
    margin-right: 5px;
    font-size: 11px;
}

.add-address-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border: 1px solid #98702e;
    border-radius: 8px;
    color: #98702e;
    background: transparent;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.add-address-btn:hover {
    background: #98702e;
    color: #fff;
}

.add-address-btn span {
    font-size: 20px;
    line-height: 1;
}
    </style>


    <div class="sudheera-checkout">


        <!-- ================= BREADCRUMB ================= -->

        <section class="checkout-breadcrumb">

            <div class="container">

                <ul>

                    <li>
                        <a href="{{ url('/') }}">
                            Home
                        </a>
                    </li>

                    <li>
                        <span>/</span>
                    </li>

                    <li>
                        Checkout
                    </li>

                </ul>

            </div>

        </section>


        <!-- ================= HEADER ================= -->

       <div class="checkout-header">

    <small>
        BAKASURAN FOOD
    </small>

    <h1>
        Checkout
    </h1>

    <p>
        You're just a few steps away from enjoying your favourite food.
    </p>

</div>


        <!-- ================= MAIN ================= -->

        <div class="checkout-container">

            <div class="checkout-grid">
                <form
        id="checkoutForm"
        action="{{ route('customer.order.store') }}"
        method="POST"
        class="checkout-left"
    >
        @csrf

        <input
    type="hidden"
    name="mode"
    value="{{ $mode ?? '' }}"
>   


                    <!-- =================================================
                                         LEFT
                                    ================================================== -->



                        <!-- ================= CONTACT ================= -->

                        <div class="checkout-card">

                            <div class="checkout-card-title">

                                <div class="checkout-step">
                                    01
                                </div>

                                <h2>
                                    Contact Information
                                </h2>

                            </div>



                            @php
                                $customer = auth('customer')->user();
                            @endphp

                            <div class="checkout-field">
                                <label>Email Address</label>
                                <input type="email" name="contact_email"
                                    value="{{ old('contact_email', $customer->email ?? '') }}"
                                    placeholder="Enter your email address">
                            </div>

                            <div class="checkout-field">
                                <label>Mobile Number</label>
                                <input type="text" name="contact_mobile"
                                    value="{{ old('contact_mobile', $customer->mobile ?? '') }}"
                                    placeholder="Enter your mobile number">
                            </div>


                        </div>


                        <!-- ================= DELIVERY ================= -->


<div class="checkout-card">
    <div class="checkout-card-title">
        <div class="checkout-step">02</div>
        <h2>Delivery Address</h2>
    </div>

    {{-- Add New Address Button --}}
    <div style="margin-bottom: 16px;">
        <a href="{{ route('addresses') }}"
           class="add-address-btn">
            <span>+</span> Add New Address
        </a>
    </div>

    @if($addresses->isNotEmpty())
        @foreach($addresses as $address)
            <label class="address-card" style="margin-bottom: 12px;">
                <input
                    type="radio"
                    name="address_id"
                    value="{{ $address->id }}"
                    required
                    {{ old(
                        'address_id',
                        $addresses->firstWhere('default', true)?->id
                            ?? $addresses->first()?->id
                    ) == $address->id ? 'checked' : '' }}
                >

                <div>
                    <strong>
                        {{ $address->name }}

                        @if($address->default)
                            <span style="color:#98702e;">(Default)</span>
                        @endif
                    </strong>

                    <p>
                        {{ $address->address }}

                        @if($address->address_2)
                            , {{ $address->address_2 }}
                        @endif

                        <br>
                        {{ $address->city }}, {{ $address->state }}
                        - {{ $address->pincode }}

                        <br>
                        Mobile: {{ $address->mobile }}

                        @if($address->email)
                            <br>Email: {{ $address->email }}
                        @endif
                    </p>
                </div>
            </label>
        @endforeach
    @else
        <p>
            No saved delivery address found. Please add a delivery
            address to your account before placing your food order.
        </p>
    @endif
</div>



                        
<!-- ================= PAYMENT ================= -->

<div class="checkout-card">

    <div class="checkout-card-title">
        <div class="checkout-step">03</div>
        <h2>Payment Method</h2>
    </div>

    <label class="payment-card">
        <input
            type="radio"
            name="payment_method"
            value="online_payment"
            required
            {{ old('payment_method', 'online_payment') === 'online_payment' ? 'checked' : '' }}
        >

        <div class="payment-info">
            <strong>Online Payment</strong>
            <span>UPI, Credit Card, Debit Card &amp; Net Banking</span>
        </div>

        <div class="payment-icon">₹</div>
    </label>

    <label class="payment-card">
        <input
            type="radio"
            name="payment_method"
            value="cod"
            required
            {{ old('payment_method') === 'cod' ? 'checked' : '' }}
        >

        <div class="payment-info">
            <strong>Cash on Delivery</strong>
            <span>Pay securely when your order arrives</span>
        </div>

        <div class="payment-icon">✓</div>
    </label>

</div>



                </form>

                <!-- =================================================
                                         RIGHT - ORDER SUMMARY
                                    ================================================== -->

                <div class="checkout-right">


                    <div class="order-summary">


                        <!-- HEADER -->

                        <div class="summary-top">

                            <small>
                                Your Selection
                            </small>

                            <h2>
                                Order Summary
                            </h2>

                        </div>


                        <!-- PRODUCTS -->

                        <div class="summary-products">
                            @foreach($cartItems as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $variant?->product;

                                    $quantity = (int) $item->quantity;
                                    $price = (float) ($variant?->price ?? 0);
                                    $itemTotal = $price * $quantity;

                                    $image = $variant?->image ?? $product?->image;
                                    $imageUrl = $image
                                        ? asset($image)
                                        : asset('website/images/product1.webp');

                                    $productTitle = $product?->title ?? 'Product';
                                @endphp

                                <div class="summary-product">
                                    <div class="summary-image">
                                        <img src="{{ $imageUrl }}" alt="{{ $productTitle }}">

                                        <span class="quantity">{{ $quantity }}</span>
                                    </div>

                                    <div class="summary-product-info">
                                        <h4>{{ $productTitle }}</h4>

                                        @if($product?->restaurant)
                                         <span class="checkout-restaurant-name" style="color: red;">
                                         {{ $product->restaurant->name }}
                                             </span>
                                        @endif

                                        @if($variant?->attributeValues)
                                            @foreach($variant->attributeValues as $attributeValue)
                                                <span>
                                                    {{ $attributeValue->attribute?->name }}:
                                                    {{ $attributeValue->value ?? $attributeValue->name ?? '' }}
                                                </span>
                                            @endforeach
                                        @endif

                                        <span>₹{{ number_format($price, 2) }} each</span>
                                    </div>

                                    <div class="summary-price">
                                        ₹{{ number_format($itemTotal, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <!-- COUPON -->
                        <div class="coupon">
                            <form action="{{ route('checkout') }}" method="GET" class="coupon-box">
                                <input type="hidden" name="order_type" value="{{ $orderType }}">

                                <input type="text" name="code" value="{{ $couponCode }}" placeholder="Discount code">

                                <button type="submit">APPLY</button>
                            </form>
                        </div>

                        <!-- TOTAL -->
                        <div class="summary-total">
                            <div class="total-row">
                                <span>Subtotal</span>
                                <strong>₹{{ number_format($subtotal, 2) }}</strong>
                            </div>

                            <div class="total-row">
                                <span>Shipping</span>
                                <strong>
                                    {{ $shipping > 0 ? '₹' . number_format($shipping, 2) : 'FREE' }}
                                </strong>
                            </div>

                            <div class="total-row discount-row">
                                <span>Discount</span>
                                <strong>- ₹{{ number_format($discount, 2) }}</strong>
                            </div>

                            <div class="grand-total">
                                <span>Total</span>
                                <strong>₹{{ number_format($total, 2) }}</strong>
                            </div>

                            <button
    type="submit"
    form="checkoutForm"
    class="place-order"
    {{ $addresses->isEmpty() || $cartItems->isEmpty() ? 'disabled' : '' }}
>
    PLACE ORDER
</button>

                            <div class="secure-text">
                                🔒 Secure &amp; encrypted checkout
                            </div>
                        </div>

                    </div>


                    <!-- TRUST -->

                   <div class="trust-box">

    <div class="trust-item">

        <strong>✓</strong>

        Secure Payment

    </div>

    <div class="trust-item">

        <strong>◆</strong>

        Freshly Prepared Food

    </div>

    <div class="trust-item">

        <strong>↻</strong>

        Easy Order Tracking

    </div>

</div>

                </div>


            </div>

        </div>

    </div>

@endsection