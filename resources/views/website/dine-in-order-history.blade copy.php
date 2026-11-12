
@extends('layouts.website')

@section('content')

<style>
    :root {
        --dine-primary: #420916;
        --dine-red: #a91d32;
        --dine-gold: #c88618;
        --dine-bg: #faf7f2;
        --dine-muted: #857a70;
        --dine-border: #eee4d8;
    }

    .dine-history-page {
        min-height: 100vh;
        padding: 55px 0 80px;
        background: var(--dine-bg);
    }

    .dine-history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 35px;
    }

    .dine-history-eyebrow {
        display: inline-block;
        color: var(--dine-gold);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .dine-history-title {
        color: var(--dine-primary);
        font-size: 34px;
        font-weight: 800;
        margin: 0 0 8px;
    }

    .dine-history-subtitle {
        color: var(--dine-muted);
        font-size: 14px;
        margin: 0;
    }

    .dine-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 12px 19px;
        border: 1px solid var(--dine-border);
        border-radius: 10px;
        background: #fff;
        color: var(--dine-primary);
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: .3s;
    }

    .dine-back-btn:hover {
        background: var(--dine-primary);
        color: #fff;
    }

    .dine-order-card {
        background: #fff;
        border: 1px solid var(--dine-border);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 8px 30px rgba(66, 9, 22, .045);
    }

    .dine-order-top {
        padding: 23px 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border-bottom: 1px solid var(--dine-border);
    }

    .dine-order-label {
        color: var(--dine-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .dine-order-number {
        color: var(--dine-primary);
        font-size: 20px;
        font-weight: 800;
        margin: 0 0 6px;
        overflow-wrap: anywhere;
    }

    .dine-order-date {
        color: var(--dine-muted);
        font-size: 12px;
    }

    .dine-completed-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 15px;
        border-radius: 30px;
        background: #edf7ee;
        color: #347b43;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .dine-order-body {
        padding: 25px 26px;
    }

    .dine-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .dine-info-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dine-info-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fbf3e6;
        color: var(--dine-gold);
        font-size: 17px;
    }

    .dine-info-label {
        color: var(--dine-muted);
        font-size: 11px;
        margin-bottom: 5px;
    }

    .dine-info-value {
        color: var(--dine-primary);
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .dine-order-divider {
        border: 0;
        border-top: 1px solid var(--dine-border);
        margin: 25px 0;
    }

    .dine-section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--dine-primary);
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .dine-section-heading i {
        color: var(--dine-gold);
    }

    .dine-items-list {
        background: #fcfaf7;
        border: 1px solid var(--dine-border);
        border-radius: 12px;
        padding: 5px 17px;
    }

    .dine-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid var(--dine-border);
        color: #55483f;
        font-size: 13px;
    }

    .dine-item-row:last-child {
        border-bottom: 0;
    }

    .dine-item-name {
        font-weight: 600;
    }

    .dine-item-quantity {
        color: var(--dine-primary);
        font-weight: 700;
        white-space: nowrap;
    }

    .dine-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 17px 0 0;
    }

    .dine-total-label {
        color: var(--dine-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .dine-total-amount {
        color: var(--dine-primary);
        font-size: 21px;
        font-weight: 800;
    }

    /* REVIEW SECTION */

    .dine-review-section {
        margin-top: 25px;
        padding: 25px;
        border: 1px solid #ead9bc;
        border-radius: 15px;
        background: linear-gradient(135deg, #fffaf0, #fff);
    }

    .dine-review-header {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 20px;
    }

    .dine-review-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f8ebd3;
        color: var(--dine-gold);
        font-size: 19px;
    }

    .dine-review-heading {
        color: var(--dine-primary);
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .dine-review-description {
        color: var(--dine-muted);
        font-size: 12px;
        margin: 0;
    }

    .dine-rating-options {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 7px;
        margin: 10px 0 20px;
    }

    .dine-rating-options input {
        display: none;
    }

    .dine-rating-options label {
        color: #d8d0c5;
        font-size: 34px;
        cursor: pointer;
        transition: .2s;
    }

    .dine-rating-options label:hover,
    .dine-rating-options label:hover ~ label,
    .dine-rating-options input:checked ~ label {
        color: #e5a323;
    }

    .dine-review-form-label {
        display: block;
        color: var(--dine-primary);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .dine-review-textarea {
        width: 100%;
        min-height: 115px;
        padding: 14px 16px;
        border: 1px solid var(--dine-border);
        border-radius: 11px;
        background: #fff;
        color: var(--dine-primary);
        font-size: 13px;
        resize: vertical;
        outline: none;
        transition: .3s;
    }

    .dine-review-textarea:focus {
        border-color: var(--dine-gold);
        box-shadow: 0 0 0 3px rgba(200, 134, 24, .10);
    }

    .dine-submit-review {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 22px;
        border: 0;
        border-radius: 9px;
        background: var(--dine-primary);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: .3s;
    }

    .dine-submit-review:hover {
        background: var(--dine-red);
        transform: translateY(-2px);
    }

    .dine-review-success {
        padding: 20px;
        border: 1px solid #d7ead9;
        border-radius: 13px;
        background: #f2faf2;
    }

    .dine-review-success-title {
        color: #347b43;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 9px;
    }

    .dine-review-stars {
        color: #e5a323;
        font-size: 21px;
        letter-spacing: 3px;
        margin-bottom: 8px;
    }

    .dine-review-success-text {
        color: #526b55;
        font-size: 13px;
        margin: 0;
    }

    .dine-empty-state {
        background: #fff;
        border: 1px solid var(--dine-border);
        border-radius: 18px;
        padding: 65px 20px;
        text-align: center;
    }

    .dine-empty-icon {
        width: 75px;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: #fbf3e6;
        color: var(--dine-gold);
        font-size: 29px;
    }

    .dine-empty-state h4 {
        color: var(--dine-primary);
        font-weight: 800;
    }

    .dine-empty-state p {
        color: var(--dine-muted);
        font-size: 13px;
    }

    .dine-alert {
        padding: 13px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media(max-width: 767px) {
        .dine-history-page {
            padding: 30px 0 50px;
        }

        .dine-history-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .dine-history-title {
            font-size: 27px;
        }

        .dine-order-top {
            padding: 19px;
            align-items: flex-start;
        }

        .dine-order-body {
            padding: 20px;
        }

        .dine-info-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 19px 12px;
        }

        .dine-info-icon {
            width: 37px;
            height: 37px;
            min-width: 37px;
            font-size: 14px;
        }

        .dine-info-value {
            font-size: 12px;
        }

        .dine-review-section {
            padding: 18px;
        }
    }

    @media(max-width: 420px) {
        .dine-info-grid {
            grid-template-columns: 1fr;
        }

        .dine-order-number {
            font-size: 17px;
        }

        .dine-completed-badge {
            padding: 7px 10px;
            font-size: 10px;
        }
    }
</style>

<main class="dine-history-page">

    <div class="container">

        <!-- PAGE HEADER -->
        <div class="dine-history-header">

            <div>
                <span class="dine-history-eyebrow">
                    BAKASURAN FOOD EXPERIENCE
                </span>

                <h2 class="dine-history-title">
                    Dine-In Order History
                </h2>

                <p class="dine-history-subtitle">
                    Your dining memories, orders and restaurant experiences.
                </p>
            </div>

            <a href="{{ route('account') }}" class="dine-back-btn">
                <i class="fa fa-arrow-left"></i>
                Back to My Account
            </a>

        </div>

        <!-- ALERT MESSAGES -->
        @if(session('success'))
            <div class="alert alert-success dine-alert">
                <i class="fa fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger dine-alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger dine-alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @forelse($dineInOrders as $order)

            <div class="dine-order-card">

                <!-- ORDER HEADER -->
                <div class="dine-order-top">

                    <div>
                        <div class="dine-order-label">
                            Order Reference
                        </div>

                        <h5 class="dine-order-number">
                            #{{ $order->order_number }}
                        </h5>

                        <div class="dine-order-date">
                            <i class="fa fa-calendar-alt me-1"></i>
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <span class="dine-completed-badge">
                        <i class="fa fa-check-circle"></i>
                        Completed
                    </span>

                </div>

                <div class="dine-order-body">

                    <!-- ORDER INFORMATION -->
                    <div class="dine-info-grid">

                        <div class="dine-info-item">
                            <div class="dine-info-icon">
                                <i class="fa fa-utensils"></i>
                            </div>

                            <div>
                                <div class="dine-info-label">Restaurant</div>
                                <div class="dine-info-value">
                                    {{ $order->restaurant->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="dine-info-item">
                            <div class="dine-info-icon">
                                <i class="fa fa-chair"></i>
                            </div>

                            <div>
                                <div class="dine-info-label">Table Number</div>
                                <div class="dine-info-value">
                                    {{ $order->table->table_number ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="dine-info-item">
                            <div class="dine-info-icon">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>

                            <div>
                                <div class="dine-info-label">Seating Type</div>
                                <div class="dine-info-value">
                                    {{ ucfirst($order->table->seating_type ?? 'N/A') }}
                                </div>
                            </div>
                        </div>

                        <div class="dine-info-item">
                            <div class="dine-info-icon">
                                <i class="fa fa-shopping-basket"></i>
                            </div>

                            <div>
                                <div class="dine-info-label">Total Items</div>
                                <div class="dine-info-value">
                                    {{ $order->items->sum('quantity') }} Items
                                </div>
                            </div>
                        </div>

                        <div class="dine-info-item">
                            <div class="dine-info-icon">
                                <i class="fa fa-wallet"></i>
                            </div>

                            <div>
                                <div class="dine-info-label">Order Total</div>
                                <div class="dine-info-value">
                                    ₹{{ number_format($order->grand_total, 2) }}
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr class="dine-order-divider">

                    <!-- ORDERED ITEMS -->
                    <h6 class="dine-section-heading">
                        <i class="fa fa-receipt"></i>
                        Ordered Items
                    </h6>

                    <div class="dine-items-list">

                        @foreach($order->items as $item)

                            <div class="dine-item-row">

                                <span class="dine-item-name">
                                    {{ $item->food_name ?? $item->name ?? 'Food Item' }}
                                </span>

                                <span class="dine-item-quantity">
                                    × {{ $item->quantity }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                    <div class="dine-total-row">
                        <span class="dine-total-label">Grand Total</span>

                        <span class="dine-total-amount">
                            ₹{{ number_format($order->grand_total, 2) }}
                        </span>
                    </div>

                    <hr class="dine-order-divider">

                    <!-- CUSTOMER REVIEW -->
                    @if(!$order->review)

                        <div class="dine-review-section">

                            <div class="dine-review-header">

                                <div class="dine-review-icon">
                                    <i class="fa fa-star"></i>
                                </div>

                                <div>
                                    <h6 class="dine-review-heading">
                                        How Was Your Experience?
                                    </h6>

                                    <p class="dine-review-description">
                                        Your feedback helps us improve our food and service.
                                    </p>
                                </div>

                            </div>

                            <form action="{{ route('customer.dine-in-order-review') }}"
                                  method="POST">

                                @csrf

                                <input type="hidden"
                                       name="order_id"
                                       value="{{ $order->id }}">

                                <label class="dine-review-form-label">
                                    Select Your Rating
                                </label>

                                <div class="dine-rating-options">

                                    @for($i = 5; $i >= 1; $i--)
                                        <input
                                            type="radio"
                                            id="rating-{{ $order->id }}-{{ $i }}"
                                            name="rating"
                                            value="{{ $i }}"
                                            required
                                        >

                                        <label for="rating-{{ $order->id }}-{{ $i }}"
                                               title="{{ $i }} Stars">
                                            ★
                                        </label>
                                    @endfor

                                </div>

                                <div class="mb-3">

                                    <label class="dine-review-form-label">
                                        Write Your Review
                                    </label>

                                    <textarea
                                        name="review"
                                        class="dine-review-textarea"
                                        maxlength="1000"
                                        placeholder="Tell us about your food, taste and dining experience..."
                                    ></textarea>

                                </div>

                                <button type="submit" class="dine-submit-review">
                                    <i class="fa fa-paper-plane"></i>
                                    Submit Your Review
                                </button>

                            </form>

                        </div>

                    @else

                        <!-- REVIEW SUBMITTED -->
                        <div class="dine-review-success">

                            <div class="dine-review-success-title">
                                <i class="fa fa-check-circle me-2"></i>
                                Thank You for Your Feedback!
                            </div>

                            <div class="dine-review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $order->review->rating ? '★' : '☆' }}
                                @endfor
                            </div>

                            <p class="dine-review-success-text">
                                {{ $order->review->review ?? 'Thank you for sharing your experience.' }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        @empty

            <div class="dine-empty-state">

                <div class="dine-empty-icon">
                    <i class="fa fa-utensils"></i>
                </div>

                <h4>No Completed Orders Yet</h4>

                <p>
                    Your completed dine-in orders will appear here.
                    Start exploring delicious food!
                </p>

                <a href="{{ route('account') }}" class="dine-submit-review">
                    Back to My Account
                </a>

            </div>

        @endforelse

    </div>

</main>

@endsection
