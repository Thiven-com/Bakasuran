@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
               SUDHEERA SAREES - MY ORDERS
            ========================================================= */

        .sudheera-orders-page {
            background: #fbf8f3;
            min-height: 100vh;
            padding: 10px 0 10px;
        }

        /* ================= PAGE HEADER ================= */

        .sudheera-page-header {
            text-align: center;
            margin-bottom: 48px;
            padding-top: 25px;
        }

        .sudheera-page-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #a47a25;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .sudheera-page-eyebrow::before,
        .sudheera-page-eyebrow::after {
            content: "";
            width: 42px;
            height: 1px;
            background: #c9a35b;
        }

        .sudheera-page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 54px;
            line-height: 1;
            font-weight: 600;
            color: #420916;
            margin: 0 0 15px;
        }

        .sudheera-page-subtitle {
            max-width: 520px;
            margin: 0 auto 16px;
            color: #756c64;
            font-size: 15px;
            line-height: 1.7;
        }

        .sudheera-breadcrumb {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            font-size: 13px;
        }

        .sudheera-breadcrumb a {
            color: #76001f;
            text-decoration: none;
            font-weight: 600;
        }

        .sudheera-breadcrumb span {
            color: #b6a99c;
        }

        .sudheera-breadcrumb .active {
            color: #83786e;
        }

        .sudheera-header-line {
            width: 70px;
            height: 3px;
            border-radius: 50px;
            background: linear-gradient(90deg,
                    #420916,
                    #c88618,
                    #420916);
            margin: 22px auto 0;
        }

        /* =========================================================
               2 COLUMN ORDER GRID
            ========================================================= */

        .sudheera-orders-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 30px;
            /* width: 100%; */
            margin-left: 20px;
            margin-right: 20px;
        }

        .sudheera-order-item {
            width: 100%;
            min-width: 0;
        }

        /* =========================================================
               ORDER CARD
            ========================================================= */

        .sudheera-order-card {
            width: 100%;
            height: 100%;
            position: relative;
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(66, 9, 22, .055);
            transition: all .35s ease;
        }

        .sudheera-order-card:hover {
            transform: translateY(-5px);
            border-color: #d7bd8d;
            box-shadow: 0 18px 42px rgba(66, 9, 22, .10);
        }

        .sudheera-card-line {
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg,
                    #420916,
                    #8f3b16,
                    #c88618);
        }

        .sudheera-order-content {
            padding: 27px;
        }

        /* =========================================================
               ORDER TOP
            ========================================================= */

        .sudheera-order-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 21px;
            border-bottom: 1px solid #eee6dc;
        }

        .sudheera-label {
            font-size: 10px;
            color: #9a9087;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .sudheera-order-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 9px;
        }

        .sudheera-order-number a {
            color: #420916;
            text-decoration: none;
        }

        .sudheera-order-number a:hover {
            color: #9a6910;
        }

        /* =========================================================
               STATUS
            ========================================================= */

        .sudheera-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .sudheera-status-success {
            background: #edf5ed;
            color: #52743d;
        }

        .sudheera-status-warning {
            background: #fff5df;
            color: #9a6910;
        }

        .sudheera-status-danger {
            background: #faeaea;
            color: #a52d2d;
        }

        .sudheera-status-shipped {
            background: #f5ecdf;
            color: #8b6524;
        }

        /* =========================================================
               TOTAL
            ========================================================= */

        .sudheera-total-box {
            text-align: right;
        }

        .sudheera-total {
            color: #76001f;
            font-size: 25px;
            font-weight: 800;
            line-height: 1.2;
            white-space: nowrap;
        }

        .sudheera-item-count {
            display: block;
            color: #91877d;
            font-size: 11px;
            margin-top: 5px;
        }

        /* =========================================================
               DATE
            ========================================================= */

        .sudheera-order-date {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 0;
            color: #71685f;
            font-size: 13px;
        }

        .sudheera-order-date i {
            width: 31px;
            height: 31px;
            border-radius: 50%;
            background: #faf3e8;
            color: #a47724;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        /* =========================================================
               SUMMARY
            ========================================================= */

        .sudheera-summary {
            background: #fcfaf7;
            border: 1px solid #eee6dc;
            border-radius: 15px;
            padding: 17px 18px;
        }

        .sudheera-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #70675e;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .sudheera-summary-row:last-child {
            margin-bottom: 0;
        }

        .sudheera-summary-total {
            border-top: 1px dashed #d8cbb9;
            margin-top: 13px;
            padding-top: 14px;
            color: #420916;
            font-size: 14px;
        }

        .sudheera-summary-total strong:last-child {
            color: #76001f;
            font-size: 20px;
        }

        /* =========================================================
               MESSAGE
            ========================================================= */

        .sudheera-order-message {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 18px;
            padding: 13px 14px;
            background: #fff8e9;
            border-left: 3px solid #c88618;
            border-radius: 9px;
            color: #755c2c;
            font-size: 12px;
            line-height: 1.65;
        }

        .sudheera-order-message i {
            color: #c88618;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* =========================================================
               PAYMENT
            ========================================================= */

        .sudheera-payment {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid #eee6dc;
        }

        .sudheera-payment-value {
            color: #420916;
            font-size: 13px;
            font-weight: 700;
        }

        .sudheera-payment-status {
            text-align: right;
        }

        /* =========================================================
               BUTTON
            ========================================================= */

        .sudheera-view-btn {
            width: 100%;
            min-height: 48px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border-radius: 11px;
            background: #420916;
            color: #fff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .3px;
            transition: all .3s ease;
        }

        .sudheera-view-btn:hover {
            background: linear-gradient(135deg,
                    #420916,
                    #76001f,
                    #a85c17);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(66, 9, 22, .20);
        }

        /* =========================================================
               EMPTY STATE
            ========================================================= */

        .sudheera-empty {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 24px;
            padding: 65px 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(66, 9, 22, .06);
        }

        .sudheera-empty-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #faf0e8;
            color: #76001f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
        }

        .sudheera-empty h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            color: #420916;
            margin-bottom: 8px;
        }

        .sudheera-empty p {
            color: #81776d;
            font-size: 14px;
            margin-bottom: 23px;
        }

        .sudheera-shop-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 27px;
            border-radius: 11px;
            background: #420916;
            color: #fff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: .3s ease;
        }

        .sudheera-shop-btn:hover {
            background: #76001f;
            transform: translateY(-2px);
        }

        /* =========================================================
               TABLET
            ========================================================= */

        @media (max-width: 991px) {

            .sudheera-orders-page {
                padding: 50px 0 70px;
            }

            .sudheera-page-title {
                font-size: 46px;
            }

            .sudheera-orders-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 22px;
            }

            .sudheera-order-content {
                padding: 22px;
            }

            .sudheera-order-top {
                gap: 12px;
            }

            .sudheera-total {
                font-size: 22px;
            }
        }

        /* =========================================================
               MOBILE
            ========================================================= */

        @media (max-width: 767px) {

            .sudheera-orders-page {
                padding: 35px 0 55px;
            }

            .sudheera-page-header {
                padding-top: 15px;
                margin-bottom: 32px;
            }

            .sudheera-page-title {
                font-size: 38px;
            }

            .sudheera-page-eyebrow {
                font-size: 9px;
                letter-spacing: 2px;
            }

            .sudheera-page-eyebrow::before,
            .sudheera-page-eyebrow::after {
                width: 25px;
            }

            .sudheera-page-subtitle {
                font-size: 13px;
                padding: 0 15px;
            }

            /* ONE COLUMN ON MOBILE */

            .sudheera-orders-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .sudheera-order-content {
                padding: 20px;
            }

            .sudheera-order-number {
                font-size: 21px;
            }

            .sudheera-total {
                font-size: 21px;
            }

            .sudheera-summary {
                padding: 15px;
            }

            .sudheera-payment {
                gap: 18px;
            }
        }

        /* =========================================================
               SMALL MOBILE
            ========================================================= */

        @media (max-width: 480px) {

            .sudheera-page-title {
                font-size: 34px;
            }

            .sudheera-order-top {
                flex-direction: column;
            }

            .sudheera-total-box {
                text-align: left;
            }

            .sudheera-order-number {
                font-size: 20px;
            }

            .sudheera-payment {
                align-items: flex-start;
                flex-direction: column;
            }

            .sudheera-payment-status {
                text-align: left;
            }
        }
    </style>



    <main class="sudheera-orders-page">
        <div class="container">

            {{-- PAGE HEADER --}}

            <div class="sudheera-page-header">
                <div class="sudheera-page-eyebrow">
                    BAKASURAN FOOD
                </div>

                <h1 class="sudheera-page-title">My Orders</h1>

                <p class="sudheera-page-subtitle">
                    View your recent food orders and track your
                    deliveries, all in one place.
                </p>

                <div class="sudheera-breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span>/</span>
                    <span class="active">My Orders</span>
                </div>

                <div class="sudheera-header-line"></div>
            </div>


            {{-- TWO COLUMN ORDER GRID --}}
            <div class="sudheera-orders-grid">

                @forelse ($orders as $order)

                            @php
                                $rawStatus = strtolower(str_replace(
                                    ['_', '-'],
                                    ' ',
                                    $order->status ?? 'pending'
                                ));

                                $statusText = ucwords($rawStatus);

                                // Order status badge
                                if (
                                    in_array($rawStatus, [
                                        'delivered',
                                        'completed',
                                        'confirmed',
                                        'success'
                                    ])
                                ) {
                                    $statusClass = 'sudheera-status-success';
                                    $statusIcon = 'fa-check-circle';
                                } elseif (
                                    in_array($rawStatus, [
                                        'shipped',
                                        'in transit',
                                        'out for delivery'
                                    ])
                                ) {
                                    $statusClass = 'sudheera-status-shipped';
                                    $statusIcon = 'fa-truck';
                                } elseif (
                                    in_array($rawStatus, [
                                        'cancelled',
                                        'canceled',
                                        'failed',
                                        'refunded'
                                    ])
                                ) {
                                    $statusClass = 'sudheera-status-danger';
                                    $statusIcon = 'fa-times-circle';
                                } else {
                                    $statusClass = 'sudheera-status-warning';
                                    $statusIcon = 'fa-clock';
                                }

                                // Order totals
                                $subtotal = (float) ($order->subtotal ?? 0);
                                $shipping = (float) ($order->delivery_total ?? 0);
                                $discount = (float) ($order->discount_total ?? 0);
                                $total = (float) ($order->grand_total ?? (
                                    $subtotal + $shipping - $discount
                                ));

                                // Total quantity across all order items
                                $itemCount = $order->items->sum(
                                    fn($item) => (int) ($item->quantity ?? 1)
                                );

                                // Order number
                                $orderNumber = $order->invoice_id
                                    ?: 'SDR-' . $order->created_at->format('Y')
                                    . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

                                // Payment details
                                $payment = $order->payments
                                    ->sortByDesc('created_at')
                                    ->first();

                                $paymentMethod = $order->payment_method
                                    ?: ($payment->payment_method ?? 'Not specified');

                                $paymentStatus = strtolower(str_replace(
                                    ['_', '-'],
                                    ' ',
                                    $order->payment_status
                                    ?? ($payment->status ?? 'pending')
                                ));

                                $paymentStatusText = ucwords($paymentStatus);

                                if (
                                    in_array($paymentStatus, [
                                        'paid',
                                        'success',
                                        'completed'
                                    ])
                                ) {
                                    $paymentStatusClass = 'sudheera-status-success';
                                } elseif (
                                    in_array($paymentStatus, [
                                        'refunded',
                                        'failed',
                                        'cancelled',
                                        'canceled'
                                    ])
                                ) {
                                    $paymentStatusClass = 'sudheera-status-danger';
                                } else {
                                    $paymentStatusClass = 'sudheera-status-warning';
                                }

                                // Status message and icon
                                if (in_array($rawStatus, ['cancelled', 'canceled'])) {
                                    $messageIcon = 'fa-times-circle';
                                    $message = 'This order has been cancelled. If payment was completed, any applicable refund will be processed according to our refund policy.';
                                } elseif (in_array($rawStatus, ['delivered', 'completed'])) {
                                    $messageIcon = 'fa-check-circle';
                                    $message = 'Your order has been delivered. Thank you for shopping with Sudheera Sarees.';
                                } elseif (in_array($rawStatus, ['shipped', 'in transit'])) {
                                    $messageIcon = 'fa-truck';
                                    $message = 'Your saree order has been shipped and is on its way to you.';
                                } elseif (in_array($rawStatus, ['confirmed', 'processing'])) {
                                    $messageIcon = 'fa-info-circle';
                                    $message = 'Your order has been confirmed and is being prepared for dispatch.';
                                } else {
                                    $messageIcon = 'fa-clock';
                                    $message = 'Your order has been received and is awaiting confirmation.';
                                }
                            @endphp

                            <div class="sudheera-order-item">
                                <div class="sudheera-order-card">

                                    <div class="sudheera-card-line"></div>

                                    <div class="sudheera-order-content">

                                        {{-- ORDER HEADER --}}
                                        <div class="sudheera-order-top">
                                            <div>
                                                <div class="sudheera-label">
                                                    Order Number
                                                </div>

                                                <h4 class="sudheera-order-number">
                                                    <a href="{{ route('order-details', ['order_id' => $order->id]) }}">
                                                        {{ $orderNumber }}
                                                    </a>
                                                </h4>

                                                <span class="sudheera-status {{ $statusClass }}">
                                                    <i class="fa {{ $statusIcon }}"></i>
                                                    {{ $statusText }}
                                                </span>
                                            </div>

                                            <div class="sudheera-total-box">
                                                <div class="sudheera-label">
                                                    Total
                                                </div>

                                                <div class="sudheera-total">
                                                    ₹{{ number_format($total, 2) }}
                                                </div>

                                                <span class="sudheera-item-count">
                                                    {{ $itemCount }}
                                                    {{ $itemCount == 1 ? 'Item' : 'Items' }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- ORDER DATE --}}
                                        <div class="sudheera-order-date">
                                            <i class="fa fa-calendar"></i>

                                            <span>
                                                {{ $order->created_at
                        ? $order->created_at->format('d M Y, h:i A')
                        : 'Date unavailable' }}
                                            </span>
                                        </div>

                                        {{-- ORDER SUMMARY --}}
                                        <div class="sudheera-summary">

                                            <div class="sudheera-summary-row">
                                                <span>Subtotal</span>
                                                <span>₹{{ number_format($subtotal, 2) }}</span>
                                            </div>

                                            <div class="sudheera-summary-row">
                                                <span>Shipping</span>
                                                <span>
                                                    {{ $shipping <= 0
                        ? 'Free'
                        : '₹' . number_format($shipping, 2) }}
                                                </span>
                                            </div>

                                            <div class="sudheera-summary-row">
                                                <span>Discount</span>
                                                <span>
                                                    − ₹{{ number_format($discount, 2) }}
                                                </span>
                                            </div>

                                            <div class="sudheera-summary-row sudheera-summary-total">
                                                <strong>Total Amount</strong>

                                                <strong>
                                                    ₹{{ number_format($total, 2) }}
                                                </strong>
                                            </div>

                                        </div>

                                        {{-- ORDER MESSAGE --}}
                                        <div class="sudheera-order-message">
                                            <i class="fa {{ $messageIcon }}"></i>

                                            <span>{{ $message }}</span>
                                        </div>

                                        {{-- PAYMENT DETAILS --}}
                                        <div class="sudheera-payment">
                                            <div>
                                                <div class="sudheera-label">
                                                    Payment Method
                                                </div>

                                                <div class="sudheera-payment-value">
                                                    {{ ucwords(str_replace(
                        ['_', '-'],
                        ' ',
                        $paymentMethod
                    )) }}
                                                </div>
                                            </div>

                                            <div class="sudheera-payment-status">
                                                <div class="sudheera-label">
                                                    Payment Status
                                                </div>

                                                <span class="sudheera-status {{ $paymentStatusClass }}">
                                                    {{ $paymentStatusText }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- VIEW ORDER DETAILS --}}
                                        <a href="{{ route('order-details', ['order_id' => $order->id]) }}" class="sudheera-view-btn">
                                            <i class="fa fa-eye"></i>
                                            View Order Details
                                        </a>

                                    </div>
                                </div>
                            </div>

                @empty

                  
                    <div class="sudheera-order-item">
                        <div class="sudheera-order-card">
                            <div class="sudheera-card-line"></div>

                            <div class="sudheera-order-content">
                                <h4 class="sudheera-order-number">
                                    No Orders Found
                                </h4>

                                <p class="sudheera-page-subtitle">
                                    You haven't ordered any food yet.
                                    Explore our delicious food collection
                                    and place your first order with BAKASURAN.
                                </p>

                                <a href="{{ route('shop') }}" class="sudheera-view-btn">
                                    <i class="fa fa-shopping-bag"></i>
                                    Explore Food
                                </a>
                            </div>
                        </div>
                    </div>
                   
                @endforelse

            </div>
        </div>
    </main>


@endsection