
@extends('layouts.website')

@section('content')

<style>
    /* =========================================================
       SUDHEERA SAREES - STATIC ORDER DETAILS
    ========================================================= */

    .sudheera-order-page {
        background: #fbf8f3;
        min-height: 100vh;
        padding: 10px 0 70px;
        color: #420916;
    }

    /* PAGE HEADER */

    .sudheera-page-header {
        text-align: center;
        margin-bottom: 42px;
        padding-top: 25px;
    }

    .sudheera-page-eyebrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 54px;
        line-height: 1;
        font-weight: 600;
        color: #420916;
        margin: 0 0 15px;
    }

    .sudheera-page-subtitle {
        max-width: 570px;
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

    .sudheera-breadcrumb a:hover {
        color: #c88618;
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
        background: linear-gradient(
            90deg,
            #420916,
            #c88618,
            #420916
        );
        margin: 22px auto 0;
    }

    /* TOP ACTIONS */

    .sudheera-top-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .sudheera-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 45px;
        padding: 10px 18px;
        background: #fff;
        border: 1px solid #eadfce;
        border-radius: 11px;
        color: #420916;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: all .3s ease;
    }

    .sudheera-action-btn i {
        color: #a47724;
    }

    .sudheera-action-btn:hover {
        color: #fff;
        background: #420916;
        border-color: #420916;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(66, 9, 22, .15);
    }

    .sudheera-action-btn:hover i {
        color: #fff;
    }

    .sudheera-download-btn {
        background: #420916;
        color: #fff;
        border-color: #420916;
    }

    .sudheera-download-btn i {
        color: #c88618;
    }

    .sudheera-download-btn:hover {
        background: linear-gradient(
            135deg,
            #420916,
            #76001f,
            #a85c17
        );
        border-color: #76001f;
    }

    /* DETAIL CARD */

    .sudheera-detail-card {
        position: relative;
        background: #fff;
        border: 1px solid #eadfce;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 8px 30px rgba(66, 9, 22, .055);
        overflow: hidden;
    }

    .sudheera-detail-card::before {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 3px;
        background: linear-gradient(
            90deg,
            #420916,
            #8f3b16,
            #c88618
        );
    }

    /* LABEL / VALUES */

    .sudheera-label {
        font-size: 10px;
        color: #9a9087;
        text-transform: uppercase;
        letter-spacing: 1.3px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .sudheera-value {
        font-size: 15px;
        color: #420916;
        font-weight: 700;
    }

    .sudheera-order-id {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 34px;
        line-height: 1.1;
        font-weight: 700;
        color: #420916;
        letter-spacing: .4px;
        margin-bottom: 10px;
    }

    .sudheera-total {
        color: #76001f;
        font-size: 29px;
        font-weight: 800;
        line-height: 1.2;
    }

    .sudheera-item-count {
        display: block;
        color: #91877d;
        font-size: 11px;
        margin-top: 5px;
    }

    /* STATUS */

    .sudheera-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .sudheera-status-pending {
        background: #fff5df;
        color: #9a6910;
    }

    .sudheera-status-success {
        background: #edf5ed;
        color: #52743d;
    }

    .sudheera-status-danger {
        background: #faeaea;
        color: #a52d2d;
    }

    /* SECTION TITLE */

    .sudheera-section-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 25px;
        font-weight: 700;
        color: #420916;
        margin-bottom: 18px;
    }

    /* ORDER ITEMS */

    .sudheera-product-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 0;
        border-bottom: 1px solid #eee6dc;
    }

    .sudheera-product-row:last-child {
        border-bottom: none;
    }

    .sudheera-product-left {
        display: flex;
        align-items: center;
        min-width: 0;
    }

    .sudheera-product-image {
        width: 78px;
        height: 78px;
        object-fit: cover;
        border-radius: 13px;
        border: 1px solid #eadfce;
        background: #faf7f2;
        flex-shrink: 0;
    }

    .sudheera-product-info {
        margin-left: 15px;
        min-width: 0;
    }

    .sudheera-product-name {
        color: #420916;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .sudheera-product-meta {
        color: #91877d;
        font-size: 12px;
    }

    .sudheera-product-price {
        color: #76001f;
        font-size: 16px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* REVIEW BUTTON */

    .sudheera-review-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 8px;
        padding: 5px 10px;
        border: 1px solid #c88618;
        background: #fff;
        color: #8a5c13;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        transition: all .3s ease;
    }

    .sudheera-review-btn:hover {
        background: #420916;
        border-color: #420916;
        color: #fff;
    }

    /* SUMMARY */

    .sudheera-summary {
        background: #fcfaf7;
        border: 1px solid #eee6dc;
        border-radius: 15px;
        padding: 18px;
    }

    .sudheera-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 7px 0;
        color: #70675e;
        font-size: 13px;
    }

    .sudheera-summary-row strong {
        color: #420916;
    }

    .sudheera-summary-total {
        border-top: 1px dashed #d8cbb9;
        margin-top: 10px;
        padding-top: 14px;
        font-size: 15px;
    }

    .sudheera-summary-total strong:last-child {
        color: #76001f;
        font-size: 22px;
    }

    .sudheera-discount {
        color: #52743d !important;
    }

    /* ORDER MESSAGE */

    .sudheera-order-message {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 20px;
        padding: 14px 15px;
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

    /* PAYMENT */

    .sudheera-payment-box {
        display: flex;
        justify-content: space-between;
        gap: 25px;
    }

    .sudheera-payment-column {
        flex: 1;
    }

    .sudheera-payment-value {
        color: #420916;
        font-size: 14px;
        font-weight: 700;
    }

    .sudheera-paid-amount {
        color: #52743d;
        font-size: 25px;
        font-weight: 800;
    }

    .sudheera-amount-due {
        color: #76001f;
        font-size: 25px;
        font-weight: 800;
    }

    .sudheera-warning-note {
        margin-top: 12px;
        padding: 12px 14px;
        background: #fff8e9;
        border: 1px solid #ead9ad;
        border-radius: 10px;
        color: #755c2c;
        font-size: 12px;
        line-height: 1.6;
    }

    .sudheera-warning-note i {
        color: #c88618;
    }

    /* TRACKING */

    .sudheera-tracking-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        padding-bottom: 26px;
    }

    .sudheera-tracking-item:last-child {
        padding-bottom: 0;
    }

    .sudheera-tracking-item:not(:last-child)::after {
        content: "";
        position: absolute;
        left: 19px;
        top: 43px;
        width: 2px;
        height: calc(100% - 18px);
        background: #eadfce;
    }

    .sudheera-tracking-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        z-index: 1;
    }

    .sudheera-tracking-completed .sudheera-tracking-icon {
        background: #420916;
        color: #fff;
        box-shadow: 0 4px 12px rgba(66, 9, 22, .16);
    }

    .sudheera-tracking-active .sudheera-tracking-icon {
        background: #c88618;
        color: #fff;
        box-shadow: 0 4px 12px rgba(200, 134, 24, .20);
    }

    .sudheera-tracking-inactive .sudheera-tracking-icon {
        background: #f0e9df;
        color: #9b9187;
    }

    .sudheera-tracking-content {
        flex: 1;
        min-width: 0;
    }

    .sudheera-tracking-title {
        color: #420916;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .sudheera-tracking-date {
        color: #91877d;
        font-size: 11px;
    }

    .sudheera-track-badge {
        padding: 5px 9px;
        border-radius: 50px;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .sudheera-track-completed {
        background: #edf5ed;
        color: #52743d;
    }

    .sudheera-track-pending {
        background: #fff5df;
        color: #9a6910;
    }

    .sudheera-track-inactive {
        background: #f3eee8;
        color: #91877d;
    }

    /* DELIVERY */

    .sudheera-delivery-box {
        margin-top: 22px;
        padding: 14px 15px;
        background: #fcfaf7;
        border: 1px solid #eadfce;
        border-radius: 12px;
        color: #70675e;
        font-size: 12px;
        line-height: 1.6;
    }

    .sudheera-delivery-box i {
        color: #a47724;
        margin-right: 5px;
    }

    .sudheera-delivery-box strong {
        color: #420916;
    }

    /* ADDRESS */

    .sudheera-address-box {
        padding: 16px;
        background: #fcfaf7;
        border: 1px solid #eee6dc;
        border-radius: 13px;
        color: #70675e;
        font-size: 13px;
        line-height: 1.75;
    }

    .sudheera-address-box strong {
        color: #420916;
    }

    /* PAYMENT METHOD */

    .sudheera-side-payment {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    /* DIVIDER */

    .sudheera-divider {
        border: 0;
        border-top: 1px solid #eee6dc;
        margin: 22px 0;
        opacity: 1;
    }

    /* RESPONSIVE */

    @media (max-width: 991px) {

        .sudheera-page-title {
            font-size: 46px;
        }

        .sudheera-detail-card {
            padding: 24px;
        }

        .sudheera-order-id {
            font-size: 29px;
        }

        .sudheera-total {
            font-size: 25px;
        }
    }

    @media (max-width: 767px) {

        .sudheera-order-page {
            padding: 25px 0 55px;
        }

        .sudheera-page-header {
            padding-top: 15px;
            margin-bottom: 30px;
        }

        .sudheera-page-eyebrow {
            font-size: 9px;
            letter-spacing: 2px;
        }

        .sudheera-page-eyebrow::before,
        .sudheera-page-eyebrow::after {
            width: 25px;
        }

        .sudheera-page-title {
            font-size: 38px;
        }

        .sudheera-page-subtitle {
            font-size: 13px;
            padding: 0 15px;
        }

        .sudheera-breadcrumb {
            font-size: 12px;
        }

        .sudheera-header-line {
            margin-top: 18px;
        }

        .sudheera-top-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 9px;
        }

        .sudheera-action-btn {
            width: 100%;
        }

        .sudheera-detail-card {
            padding: 20px;
            border-radius: 17px;
        }

        .sudheera-order-id {
            font-size: 27px;
        }

        .sudheera-total {
            font-size: 24px;
        }

        .sudheera-product-row {
            position: relative;
            align-items: flex-start;
            padding: 15px 0;
            gap: 8px;
        }

        .sudheera-product-image {
            width: 65px;
            height: 65px;
            border-radius: 9px;
        }

        .sudheera-product-info {
            margin-left: 10px;
            padding-right: 55px;
        }

        .sudheera-product-name {
            font-size: 13px;
        }

        .sudheera-product-meta {
            font-size: 11px;
        }

        .sudheera-product-price {
            position: absolute;
            top: 17px;
            right: 0;
            font-size: 13px;
        }

        .sudheera-review-btn {
            font-size: 10px;
            padding: 5px 8px;
        }

        .sudheera-summary {
            padding: 14px;
        }

        .sudheera-summary-row {
            font-size: 12px;
        }

        .sudheera-summary-total strong:last-child {
            font-size: 19px;
        }

        .sudheera-payment-box {
            flex-direction: column;
            gap: 20px;
        }

        .sudheera-payment-column {
            width: 100%;
        }

        .sudheera-payment-column.text-md-end {
            text-align: left !important;
        }

        .sudheera-tracking-item {
            gap: 10px;
        }

        .sudheera-tracking-icon {
            width: 36px;
            min-width: 36px;
            height: 36px;
        }

        .sudheera-tracking-item:not(:last-child)::after {
            left: 17px;
            top: 40px;
        }

        .sudheera-tracking-title {
            font-size: 13px;
        }

        .sudheera-tracking-date {
            font-size: 10px;
        }

        .sudheera-track-badge {
            font-size: 8px;
            padding: 5px 7px;
        }

        .sudheera-address-box {
            font-size: 12px;
        }

        .sudheera-order-message {
            font-size: 11px;
        }

        .sudheera-section-title {
            font-size: 22px;
        }
    }

    @media (max-width: 480px) {

        .sudheera-page-title {
            font-size: 34px;
        }

        .sudheera-detail-card {
            padding: 18px;
        }

        .sudheera-order-id {
            font-size: 24px;
        }

        .sudheera-total {
            font-size: 22px;
        }

        .sudheera-side-payment {
            align-items: flex-start;
            flex-direction: column;
        }

        .sudheera-product-name {
            max-width: 160px;
        }

        .sudheera-product-info {
            padding-right: 45px;
        }
    }

    @media print {

        .sudheera-top-actions,
        .sudheera-review-btn {
            display: none !important;
        }

        .sudheera-order-page {
            background: #fff;
            padding: 0;
        }

        .sudheera-detail-card {
            box-shadow: none;
            break-inside: avoid;
        }
    }
</style>



<main class="sudheera-order-page">
    <div class="container">

        @php
            $status = strtolower(str_replace(
                ['_', '-'],
                ' ',
                $order->status ?? 'pending'
            ));

            $statusLabel = ucwords($status);

            if (in_array($status, ['delivered', 'completed'])) {
                $statusClass = 'sudheera-status-success';
                $statusIcon = 'fa-check-circle';
            } elseif (in_array($status, ['shipped', 'in transit', 'out for delivery'])) {
                $statusClass = 'sudheera-status-shipped';
                $statusIcon = 'fa-truck';
            } elseif (in_array($status, ['cancelled', 'canceled', 'failed'])) {
                $statusClass = 'sudheera-status-danger';
                $statusIcon = 'fa-times-circle';
            } else {
                $statusClass = 'sudheera-status-pending';
                $statusIcon = 'fa-clock';
            }

            $subtotal = (float) ($order->subtotal ?? 0);
            $shipping = (float) ($order->delivery_total ?? 0);
            $discount = (float) ($order->discount_total ?? 0);
            $total = (float) ($order->grand_total ?? ($subtotal + $shipping - $discount));

            $itemCount = $order->items->sum(
                fn ($item) => (int) ($item->quantity ?? 1)
            );

            $orderNumber = $order->invoice_id
                ?: 'SDR-' . $order->created_at->format('Y')
                    . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

            $payment = $order->payments->sortByDesc('created_at')->first();

            $paymentMethod = $order->payment_method
                ?: ($payment->payment_method ?? 'Not specified');

            $paymentStatus = strtolower(str_replace(
                ['_', '-'],
                ' ',
                $order->payment_status ?? ($payment->status ?? 'pending')
            ));

            $paymentStatusClass = in_array($paymentStatus, ['paid', 'success', 'completed'])
                ? 'sudheera-status-success'
                : (in_array($paymentStatus, ['failed', 'refunded', 'cancelled'])
                    ? 'sudheera-status-danger'
                    : 'sudheera-status-pending');

            $shippingAddress = $order->shipping_address ?? [];
            $addressName = data_get($shippingAddress, 'name')
                ?? data_get($shippingAddress, 'full_name')
                ?? data_get($shippingAddress, 'customer_name')
                ?? 'Customer';

            $addressLine = data_get($shippingAddress, 'address')
                ?? data_get($shippingAddress, 'address_line')
                ?? data_get($shippingAddress, 'address_line_1');

            $city = data_get($shippingAddress, 'city');
            $state = data_get($shippingAddress, 'state');
            $postcode = data_get($shippingAddress, 'postcode')
                ?? data_get($shippingAddress, 'postal_code')
                ?? data_get($shippingAddress, 'pincode');

            $country = data_get($shippingAddress, 'country');
            $phone = data_get($shippingAddress, 'phone')
                ?? data_get($shippingAddress, 'mobile');
        @endphp

        {{-- PAGE HEADER --}}
        <div class="sudheera-page-header">
            <div class="sudheera-page-eyebrow">SUDHEERA SAREES</div>

            <h1 class="sudheera-page-title">Order Details</h1>

            <p class="sudheera-page-subtitle">
                Thank you for choosing Sudheera Sarees.
                View your order summary, payment information
                and delivery progress below.
            </p>

            <div class="sudheera-breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span>/</span>
                <a href="{{ route('orders') }}">My Orders</a>
                <span>/</span>
                <span class="active">Order Details</span>
            </div>

            <div class="sudheera-header-line"></div>
        </div>

        {{-- TOP ACTIONS --}}
        <div class="sudheera-top-actions">
            <a href="#" onclick="window.print(); return false;"
               class="sudheera-action-btn">
                <i class="fa fa-print"></i>
                Print Invoice
            </a>

            <a href="#"
               onclick="window.print(); return false;"
               class="sudheera-action-btn sudheera-download-btn">
                <i class="fa fa-download"></i>
                Download / Save Invoice
            </a>
        </div>

        {{-- MAIN GRID --}}
        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-8">

                {{-- ORDER SUMMARY --}}
                <div class="sudheera-detail-card">
                    <div class="row g-4 align-items-start">

                        <div class="col-md-4">
                            <div class="sudheera-label">Order ID</div>

                            <div class="sudheera-order-id">
                                #{{ $orderNumber }}
                            </div>

                            <span class="sudheera-status {{ $statusClass }}">
                                <i class="fa {{ $statusIcon }}"></i>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <div class="sudheera-label">Order Date</div>

                            <div class="sudheera-value">
                                <i class="fa fa-calendar"
                                   style="color:#a47724; margin-right:7px;"></i>
                                {{ optional($order->created_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <div class="col-md-4 text-md-end">
                            <div class="sudheera-label">Total Amount</div>

                            <div class="sudheera-total">
                                ₹{{ number_format($total, 2) }}
                            </div>

                            <span class="sudheera-item-count">
                                {{ $itemCount }}
                                {{ $itemCount == 1 ? 'Item' : 'Items' }}
                            </span>
                        </div>
                    </div>

                    <hr class="sudheera-divider">

                    {{-- ORDER ITEMS --}}
                    <h5 class="sudheera-section-title">Order Items</h5>

                    @forelse ($order->items as $item)
                        @php
                            $product = $item->productVariant->product ?? null;

                            $productName = $item->product_title
                                ?? $product->name
                                ?? $product->title
                                ?? 'Saree Product';

                            $quantity = (int) ($item->quantity ?? 1);
                            $unitPrice = (float) ($item->unit_price ?? 0);

                            $lineTotal = (float) (
                                $item->subtotal ?? ($unitPrice * $quantity)
                            );

                            // Adjust this image field if your product model uses another name.
                            $imagePath = $product->image
                                ?? $product->image_path
                                ?? null;
                        @endphp

                        <div class="sudheera-product-row">
                            <div class="sudheera-product-left">

                                @if ($imagePath)
                                    <img
                                        src="{{ asset($imagePath) }}"
                                        class="sudheera-product-image"
                                        alt="{{ $productName }}">
                                @else
                                    <img
                                        src="{{ asset('website/images/product/product-1.jpg') }}"
                                        class="sudheera-product-image"
                                        alt="{{ $productName }}">
                                @endif

                                <div class="sudheera-product-info">
                                    <div class="sudheera-product-name">
                                        {{ $productName }}
                                    </div>

                                    <div class="sudheera-product-meta">
                                        Quantity: {{ $quantity }}
                                    </div>

                                    <div class="sudheera-product-meta">
                                        Unit Price: ₹{{ number_format($unitPrice, 2) }}
                                    </div>
                                </div>
                            </div>

                            <strong class="sudheera-product-price">
                                ₹{{ number_format($lineTotal, 2) }}
                            </strong>
                        </div>
                    @empty
                        <p>No items were found for this order.</p>
                    @endforelse

                    <hr class="sudheera-divider">

                    {{-- PRICE SUMMARY --}}
                    <div class="sudheera-summary">
                        <div class="sudheera-summary-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="sudheera-summary-row">
                            <span>Shipping</span>
                            <span>
                                {{ $shipping <= 0 ? 'Free' : '₹' . number_format($shipping, 2) }}
                            </span>
                        </div>

                        <div class="sudheera-summary-row">
                            <span>Discount</span>
                            <span class="sudheera-discount">
                                − ₹{{ number_format($discount, 2) }}
                            </span>
                        </div>

                        <div class="sudheera-summary-row sudheera-summary-total">
                            <strong>Total Amount</strong>
                            <strong>₹{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    {{-- ORDER MESSAGE --}}
                    <div class="sudheera-order-message">
                        <i class="fa {{ $statusIcon }}"></i>
                        <span>
                            Your order is currently
                            <strong>{{ $statusLabel }}</strong>.
                            @if (in_array($status, ['cancelled', 'canceled']))
                                This order has been cancelled.
                            @elseif (in_array($status, ['delivered', 'completed']))
                                Your order has been delivered. Thank you for shopping with us.
                            @elseif (in_array($status, ['shipped', 'in transit', 'out for delivery']))
                                Your order is on its way to you.
                            @else
                                You will receive an update as your order progresses.
                            @endif
                        </span>
                    </div>
                </div>

                {{-- PAYMENT DETAILS --}}
                <div class="sudheera-detail-card mt-4">
                    <h5 class="sudheera-section-title">Payment Details</h5>

                    <div class="sudheera-payment-box">
                        <div class="sudheera-payment-column">
                            <div class="sudheera-label">Payment Method</div>

                            <div class="sudheera-payment-value">
                                {{ ucwords(str_replace(['_', '-'], ' ', $paymentMethod)) }}
                            </div>

                            <div class="sudheera-label mt-4">Amount Paid</div>

                            <div class="sudheera-paid-amount">
                                ₹{{ number_format(
                                    in_array($paymentStatus, ['paid', 'success', 'completed'])
                                        ? $total
                                        : 0,
                                    2
                                ) }}
                            </div>
                        </div>

                        <div class="sudheera-payment-column">
                            <div class="sudheera-label">Payment Status</div>

                            <span class="sudheera-status {{ $paymentStatusClass }}">
                                {{ ucwords($paymentStatus) }}
                            </span>
                        </div>

                        <div class="sudheera-payment-column text-md-end">
                            <div class="sudheera-label">Amount to be Paid</div>

                            <div class="sudheera-amount-due">
                                ₹{{ number_format(
                                    in_array($paymentStatus, ['paid', 'success', 'completed'])
                                        ? 0
                                        : $total,
                                    2
                                ) }}
                            </div>

                            @if (str_contains(strtolower($paymentMethod), 'cash') &&
                                !in_array($paymentStatus, ['paid', 'success', 'completed']))
                                <div class="sudheera-warning-note">
                                    <i class="fa fa-exclamation-circle"></i>
                                    Please keep the exact amount ready for Cash on Delivery.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                {{-- ORDER TRACKING --}}
                <div class="sudheera-detail-card">
                    <h5 class="sudheera-section-title">Order Tracking</h5>

                    @php
                        $trackingSteps = [
                            'placed' => [
                                'title' => 'Order Placed',
                                'done' => true,
                            ],
                            'confirmed' => [
                                'title' => 'Order Confirmed',
                                'done' => in_array($status, [
                                    'confirmed', 'processing', 'shipped',
                                    'in transit', 'out for delivery',
                                    'delivered', 'completed'
                                ]),
                            ],
                            'shipped' => [
                                'title' => 'Shipped',
                                'done' => in_array($status, [
                                    'shipped', 'in transit', 'out for delivery',
                                    'delivered', 'completed'
                                ]),
                            ],
                            'delivered' => [
                                'title' => 'Delivered',
                                'done' => in_array($status, ['delivered', 'completed']),
                            ],
                        ];
                    @endphp

                    @foreach ($trackingSteps as $key => $step)
                        @php
                            $isDone = $step['done'];

                            $isCurrent = !$isDone && (
                                ($key === 'confirmed' && $status === 'pending') ||
                                ($key === 'shipped' && in_array($status, ['confirmed', 'processing'])) ||
                                ($key === 'delivered' && in_array($status, ['shipped', 'in transit', 'out for delivery']))
                            );

                            $trackingClass = $isDone
                                ? 'sudheera-tracking-completed'
                                : ($isCurrent
                                    ? 'sudheera-tracking-active'
                                    : 'sudheera-tracking-inactive');
                        @endphp

                        <div class="sudheera-tracking-item {{ $trackingClass }}">
                            <div class="sudheera-tracking-icon">
                                {{ $isDone ? '✓' : $loop->iteration }}
                            </div>

                            <div class="sudheera-tracking-content">
                                <div class="sudheera-tracking-title">
                                    {{ $step['title'] }}
                                </div>

                                <div class="sudheera-tracking-date">
                                    @if ($key === 'placed')
                                        {{ optional($order->created_at)->format('d M Y, h:i A') }}
                                    @elseif ($isDone)
                                        Updated
                                    @elseif ($isCurrent)
                                        In progress
                                    @else
                                        Awaiting update
                                    @endif
                                </div>
                            </div>

                            <span class="sudheera-track-badge {{ $isDone ? 'sudheera-track-completed' : ($isCurrent ? 'sudheera-track-pending' : 'sudheera-track-inactive') }}">
                                {{ $isDone ? 'Completed' : ($isCurrent ? 'In Progress' : 'Pending') }}
                            </span>
                        </div>
                    @endforeach

                    <div class="sudheera-delivery-box">
                        <i class="fa fa-calendar"></i>
                        <strong>Delivery Status:</strong>
                        {{ $statusLabel }}
                    </div>
                </div>

                {{-- SHIPPING ADDRESS --}}
                <div class="sudheera-detail-card mt-4">
                    <h5 class="sudheera-section-title">Shipping Address</h5>

                    <div class="sudheera-address-box">
                        <strong>{{ $addressName }}</strong>
                        <br>

                        @if ($addressLine)
                            {{ $addressLine }}<br>
                        @endif

                        @if ($city)
                            {{ $city }}
                        @endif

                        @if ($state)
                            {{ $city ? ', ' : '' }}{{ $state }}
                        @endif

                        @if ($postcode)
                            - {{ $postcode }}
                        @endif

                        @if ($city || $state || $postcode)
                            <br>
                        @endif

                        @if ($country)
                            {{ $country }}<br>
                        @endif

                        @if ($phone)
                            <br>
                            <strong>Mobile:</strong> {{ $phone }}
                        @endif
                    </div>
                </div>

                {{-- PAYMENT METHOD --}}
                <div class="sudheera-detail-card mt-4">
                    <div class="sudheera-side-payment">
                        <div>
                            <h5 class="sudheera-section-title mb-1">
                                Payment Method
                            </h5>

                            <div class="sudheera-payment-value">
                                {{ ucwords(str_replace(['_', '-'], ' ', $paymentMethod)) }}
                            </div>
                        </div>

                        <span class="sudheera-status {{ $paymentStatusClass }}">
                            {{ ucwords($paymentStatus) }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>



<!-- =========================================================
     STATIC REVIEW MODALS
========================================================= -->


<!-- REVIEW MODAL 1 -->

<div class="modal fade"
     id="reviewModal1"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content"
             style="
                border:0;
                border-radius:20px;
                overflow:hidden;
                box-shadow:0 25px 70px rgba(66,9,22,.20);
             ">

            <div class="modal-header"
                 style="
                    background:#420916;
                    color:#fff;
                    padding:20px 24px;
                    border:0;
                 ">

                <h5 class="modal-title"
                    style="
                        font-family:'Cormorant Garamond',Georgia,serif;
                        font-size:25px;
                        font-weight:700;
                    ">

                    Review Kanchipuram Silk Saree

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    style="filter:brightness(0) invert(1);">
                </button>

            </div>


            <form>

                <div class="modal-body"
                     style="padding:25px;background:#fff;">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter your name">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Rating
                            </label>

                            <select class="form-select">

                                <option>★★★★★</option>
                                <option>★★★★☆</option>
                                <option>★★★☆☆</option>
                                <option>★★☆☆☆</option>
                                <option>★☆☆☆☆</option>

                            </select>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Review Title
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter review title">

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Review
                            </label>

                            <textarea
                                rows="4"
                                class="form-control"
                                placeholder="Write your review..."></textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Upload Image
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                accept="image/*">

                        </div>

                    </div>

                </div>


                <div class="modal-footer"
                     style="
                        padding:18px 25px;
                        border-top:1px solid #eee6dc;
                        background:#fcfaf7;
                     ">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="button"
                        class="btn"
                        style="
                            background:#420916;
                            border:1px solid #420916;
                            color:#fff;
                            border-radius:9px;
                            padding:9px 18px;
                            font-size:13px;
                            font-weight:700;
                        ">

                        <i class="fa fa-paper-plane me-1"></i>

                        Submit Review

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- REVIEW MODAL 2 -->

<div class="modal fade"
     id="reviewModal2"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content"
             style="
                border:0;
                border-radius:20px;
                overflow:hidden;
                box-shadow:0 25px 70px rgba(66,9,22,.20);
             ">

            <div class="modal-header"
                 style="
                    background:#420916;
                    color:#fff;
                    padding:20px 24px;
                    border:0;
                 ">

                <h5 class="modal-title"
                    style="
                        font-family:'Cormorant Garamond',Georgia,serif;
                        font-size:25px;
                        font-weight:700;
                    ">

                    Review Banarasi Silk Saree

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    style="filter:brightness(0) invert(1);">
                </button>

            </div>


            <form>

                <div class="modal-body"
                     style="padding:25px;background:#fff;">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter your name">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Rating
                            </label>

                            <select class="form-select">

                                <option>★★★★★</option>
                                <option>★★★★☆</option>
                                <option>★★★☆☆</option>
                                <option>★★☆☆☆</option>
                                <option>★☆☆☆☆</option>

                            </select>

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Review Title
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter review title">

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Review
                            </label>

                            <textarea
                                rows="4"
                                class="form-control"
                                placeholder="Write your review..."></textarea>

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Upload Image
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                accept="image/*">

                        </div>

                    </div>

                </div>


                <div class="modal-footer"
                     style="
                        padding:18px 25px;
                        border-top:1px solid #eee6dc;
                        background:#fcfaf7;
                     ">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="button"
                        class="btn"
                        style="
                            background:#420916;
                            border:1px solid #420916;
                            color:#fff;
                            border-radius:9px;
                            padding:9px 18px;
                            font-size:13px;
                            font-weight:700;
                        ">

                        <i class="fa fa-paper-plane me-1"></i>

                        Submit Review

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- REVIEW MODAL 3 -->

<div class="modal fade"
     id="reviewModal3"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content"
             style="
                border:0;
                border-radius:20px;
                overflow:hidden;
                box-shadow:0 25px 70px rgba(66,9,22,.20);
             ">

            <div class="modal-header"
                 style="
                    background:#420916;
                    color:#fff;
                    padding:20px 24px;
                    border:0;
                 ">

                <h5 class="modal-title"
                    style="
                        font-family:'Cormorant Garamond',Georgia,serif;
                        font-size:25px;
                        font-weight:700;
                    ">

                    Review Designer Cotton Saree

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    style="filter:brightness(0) invert(1);">
                </button>

            </div>


            <form>

                <div class="modal-body"
                     style="padding:25px;background:#fff;">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter your name">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Rating
                            </label>

                            <select class="form-select">

                                <option>★★★★★</option>
                                <option>★★★★☆</option>
                                <option>★★★☆☆</option>
                                <option>★★☆☆☆</option>
                                <option>★☆☆☆☆</option>

                            </select>

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Review Title
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter review title">

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Review
                            </label>

                            <textarea
                                rows="4"
                                class="form-control"
                                placeholder="Write your review..."></textarea>

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Upload Image
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                accept="image/*">

                        </div>

                    </div>

                </div>


                <div class="modal-footer"
                     style="
                        padding:18px 25px;
                        border-top:1px solid #eee6dc;
                        background:#fcfaf7;
                     ">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="button"
                        class="btn"
                        style="
                            background:#420916;
                            border:1px solid #420916;
                            color:#fff;
                            border-radius:9px;
                            padding:9px 18px;
                            font-size:13px;
                            font-weight:700;
                        ">

                        <i class="fa fa-paper-plane me-1"></i>

                        Submit Review

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection