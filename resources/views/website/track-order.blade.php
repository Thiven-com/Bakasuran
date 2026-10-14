
@extends('layouts.website')

@section('title', 'Track Your Order | BAKASURAN')

@section('content')

<style>
    /* =========================================
       BAKASURAN - TRACK ORDER
    ========================================= */

    .bakasuran-track-page {
        min-height: 100vh;
        padding: 45px 16px 70px;
        background:
            radial-gradient(circle at top left, rgba(255, 159, 28, .09), transparent 32%),
            #fffaf4;
        color: #202124;
    }

    .bakasuran-track-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }

    /* Top navigation */

    .bakasuran-track-topbar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 18px;
    }

    .bakasuran-back-home {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 11px 18px;
        border: 1px solid #202124;
        border-radius: 12px;
        background: #202124;
        color: #fff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all .25s ease;
    }

    .bakasuran-back-home i {
        color: #FF9F1C;
    }

    .bakasuran-back-home:hover {
        background: #E85D04;
        border-color: #E85D04;
        transform: translateY(-2px);
    }

    .bakasuran-back-home:hover i {
        color: #fff;
    }

    /* Brand section */

    .bakasuran-track-logo {
        margin-bottom: 22px;
        padding: 25px 20px;
        border: 1px solid #F0E5DA;
        border-radius: 22px;
        background: #fff;
        text-align: center;
        box-shadow: 0 8px 28px rgba(32, 33, 36, .045);
    }

    .bakasuran-track-logo a {
        display: inline-block;
    }

    .bakasuran-track-logo img {
        display: block;
        max-width: 220px;
        max-height: 80px;
        margin: 0 auto;
        object-fit: contain;
    }

    .bakasuran-track-logo p {
        margin: 12px 0 0;
        color: #77736E;
        font-size: 13px;
        letter-spacing: .3px;
    }

    .bakasuran-brand-highlight {
        color: #E85D04;
        font-weight: 800;
    }

    /* Main card */

    .bakasuran-track-card {
        position: relative;
        overflow: hidden;
        padding: 40px 40px 42px;
        border: 1px solid #F0E5DA;
        border-radius: 28px;
        background: #fff;
        box-shadow: 0 18px 55px rgba(32, 33, 36, .075);
    }

    .bakasuran-track-card::before {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #202124, #E85D04, #FF9F1C);
        content: '';
    }

    /* Heading */

    .bakasuran-track-eyebrow {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 5px 0 12px;
        color: #E85D04;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .bakasuran-track-title {
        margin: 0 0 12px;
        color: #202124;
        font-size: 38px;
        font-weight: 800;
        line-height: 1.2;
        text-align: center;
        letter-spacing: -.8px;
    }

    .bakasuran-track-subtitle {
        max-width: 480px;
        margin: 0 auto;
        color: #77736E;
        font-size: 14px;
        line-height: 1.8;
        text-align: center;
    }

    /* Divider */

    .bakasuran-track-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 25px 0 30px;
    }

    .bakasuran-track-divider span {
        width: 55px;
        height: 1px;
        background: #E8D8C7;
    }

    .bakasuran-track-divider i {
        color: #E85D04;
        font-size: 15px;
    }

    /* Tabs */

    .bakasuran-track-tabs {
        display: flex;
        gap: 6px;
        max-width: 540px;
        margin: 0 auto 30px;
        padding: 5px;
        border: 1px solid #F0E5DA;
        border-radius: 15px;
        background: #FFF8F1;
    }

    .bakasuran-track-tab {
        display: inline-flex;
        flex: 1;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 47px;
        padding: 10px 12px;
        border: 0;
        border-radius: 11px;
        background: transparent;
        color: #6B625B;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .25s ease;
    }

    .bakasuran-track-tab:hover {
        color: #E85D04;
    }

    .bakasuran-track-tab.active {
        background: #202124;
        color: #fff;
        box-shadow: 0 6px 16px rgba(32, 33, 36, .15);
    }

    .bakasuran-track-tab.active i {
        color: #FF9F1C;
    }

    /* Form */

    .bakasuran-track-form {
        max-width: 540px;
        margin: 0 auto;
    }

    .bakasuran-track-field {
        margin-bottom: 20px;
    }

    .bakasuran-track-field label {
        display: block;
        margin-bottom: 9px;
        color: #292929;
        font-size: 13px;
        font-weight: 700;
    }

    .bakasuran-track-input-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 55px;
        padding: 0 15px;
        border: 1px solid #E6DED6;
        border-radius: 14px;
        background: #fff;
        transition: border-color .25s ease, box-shadow .25s ease;
    }

    .bakasuran-track-input-wrap:focus-within {
        border-color: #E85D04;
        box-shadow: 0 0 0 4px rgba(232, 93, 4, .09);
    }

    .bakasuran-track-input-icon {
        display: flex;
        flex: 0 0 35px;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: #FFF1E4;
        color: #E85D04;
        font-size: 17px;
    }

    .bakasuran-track-input-wrap input {
        width: 100%;
        min-width: 0;
        height: 52px;
        padding: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: #202124;
        font-size: 14px;
        box-shadow: none !important;
    }

    .bakasuran-track-input-wrap input::placeholder {
        color: #AAA29B;
    }

    .bakasuran-track-hint {
        display: block;
        margin-top: 8px;
        color: #89817A;
        font-size: 11px;
        line-height: 1.6;
    }

    /* Submit button */

    .bakasuran-track-btn-wrap {
        margin-top: 27px;
        text-align: center;
    }

    .bakasuran-track-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 54px;
        padding: 14px 24px;
        border: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #E85D04, #FF7B00);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: .2px;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(232, 93, 4, .2);
        transition: all .25s ease;
    }

    .bakasuran-track-btn:hover {
        background: linear-gradient(135deg, #C94B00, #E85D04);
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(232, 93, 4, .25);
    }

    .bakasuran-track-btn:focus-visible,
    .bakasuran-track-tab:focus-visible,
    .bakasuran-back-home:focus-visible {
        outline: 3px solid rgba(232, 93, 4, .4);
        outline-offset: 3px;
    }

    /* Form panes */

    .bakasuran-track-pane {
        display: none;
    }

    .bakasuran-track-pane.active {
        display: block;
        animation: bakasuranTrackFade .3s ease;
    }

    @keyframes bakasuranTrackFade {
        from {
            opacity: 0;
            transform: translateY(7px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Error / status message */

    .bakasuran-track-message {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0 auto 22px;
        padding: 14px 16px;
        border: 1px solid #F2C7C3;
        border-radius: 13px;
        background: #FFF1F0;
        color: #A12C25;
        font-size: 13px;
        line-height: 1.6;
    }

    .bakasuran-track-message i {
        margin-top: 3px;
    }

    .bakasuran-track-success {
        border-color: #B7E4C7;
        background: #F0FFF4;
        color: #216E39;
    }

    .bakasuran-track-validation {
        margin-top: 7px;
        color: #C62828;
        font-size: 12px;
    }

    /* Bottom support section */

    .bakasuran-track-support {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 24px;
        padding: 18px;
        border: 1px solid #F0E5DA;
        border-radius: 17px;
        background: rgba(255, 255, 255, .8);
    }

    .bakasuran-track-support-icon {
        display: flex;
        flex: 0 0 40px;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #FFF1E4;
        color: #E85D04;
        font-size: 17px;
    }

    .bakasuran-track-support-text {
        color: #77736E;
        font-size: 12px;
        line-height: 1.7;
    }

    .bakasuran-track-support-text strong {
        display: block;
        color: #202124;
        font-size: 13px;
    }

    .bakasuran-track-note {
        margin-top: 22px;
        color: #89817A;
        font-size: 12px;
        line-height: 1.7;
        text-align: center;
    }

    .bakasuran-track-note strong {
        color: #E85D04;
    }

    /* Tablet */

    @media (max-width: 767px) {
        .bakasuran-track-page {
            padding: 30px 12px 45px;
        }

        .bakasuran-track-card {
            padding: 34px 24px 35px;
            border-radius: 23px;
        }

        .bakasuran-track-title {
            font-size: 32px;
        }

        .bakasuran-track-subtitle {
            font-size: 13px;
        }

        .bakasuran-track-tabs {
            margin-bottom: 25px;
        }

        .bakasuran-track-tab {
            min-height: 44px;
            padding: 8px;
            font-size: 12px;
        }

        .bakasuran-track-btn {
            min-height: 51px;
        }
    }

    /* Small mobile */

    @media (max-width: 480px) {
        .bakasuran-track-page {
            padding: 20px 9px 32px;
        }

        .bakasuran-track-topbar {
            margin-bottom: 12px;
        }

        .bakasuran-back-home {
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 12px;
        }

        .bakasuran-track-logo {
            padding: 20px 15px;
            border-radius: 17px;
        }

        .bakasuran-track-logo img {
            max-width: 180px;
        }

        .bakasuran-track-card {
            padding: 29px 16px 30px;
            border-radius: 20px;
        }

        .bakasuran-track-title {
            font-size: 28px;
        }

        .bakasuran-track-eyebrow {
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .bakasuran-track-subtitle {
            font-size: 12px;
        }

        .bakasuran-track-divider {
            margin: 22px 0 25px;
        }

        .bakasuran-track-divider span {
            width: 40px;
        }

        .bakasuran-track-tab {
            gap: 5px;
            font-size: 11px;
        }

        .bakasuran-track-input-wrap {
            min-height: 51px;
            gap: 9px;
            padding: 0 11px;
        }

        .bakasuran-track-input-icon {
            flex-basis: 32px;
            width: 32px;
            height: 32px;
            font-size: 15px;
        }

        .bakasuran-track-input-wrap input {
            height: 49px;
            font-size: 13px;
        }

        .bakasuran-track-support {
            padding: 14px 12px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bakasuran-track-page *,
        .bakasuran-track-page *::before,
        .bakasuran-track-page *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="bakasuran-track-page">
    <div class="bakasuran-track-wrapper">

        {{-- Top navigation --}}
        <div class="bakasuran-track-topbar">
            <a href="{{ route('home') }}" class="bakasuran-back-home">
                <i class="fa fa-home" aria-hidden="true"></i>
                Back to Home
            </a>
        </div>

        {{-- BAKASURAN branding --}}
        <div class="bakasuran-track-logo">
            <a href="{{ route('home') }}" aria-label="BAKASURAN Home">
                <img
                    src="{{ asset('website/images/bakalogo.png') }}"
                    alt="BAKASURAN Food Delivery">
            </a>

            <p>
                Delicious food.
                <span class="bakasuran-brand-highlight">Delivered with care.</span>
            </p>
        </div>

        {{-- Server messages --}}
        @if (session('error'))
            <div class="bakasuran-track-message" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (session('success'))
            <div class="bakasuran-track-message bakasuran-track-success" role="status">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Main tracking card --}}
        <div class="bakasuran-track-card">

            <div class="bakasuran-track-eyebrow">
                <i class="fa fa-cutlery" aria-hidden="true"></i>
                Your food journey
            </div>

            <h1 class="bakasuran-track-title">Track Your Order</h1>

            <p class="bakasuran-track-subtitle">
                Your favourite food is worth the wait.
                Enter your order details below to check your delivery status.
            </p>

            <div class="bakasuran-track-divider" aria-hidden="true">
                <span></span>
                <i class="fa fa-circle"></i>
                <span></span>
            </div>

            {{-- Tabs --}}
            <div class="bakasuran-track-tabs" role="tablist" aria-label="Tracking method">

                <button
                    type="button"
                    id="bakasuran-order-tab"
                    class="bakasuran-track-tab active"
                    role="tab"
                    aria-selected="true"
                    aria-controls="bakasuran-order-pane"
                    data-target="bakasuran-order-pane">

                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                    Order Number
                </button>

                <button
                    type="button"
                    id="bakasuran-shipment-tab"
                    class="bakasuran-track-tab"
                    role="tab"
                    aria-selected="false"
                    aria-controls="bakasuran-shipment-pane"
                    data-target="bakasuran-shipment-pane">

                    <i class="fa fa-truck" aria-hidden="true"></i>
                    Tracking Number
                </button>
            </div>

            {{-- Order number form --}}
            <div
                id="bakasuran-order-pane"
                class="bakasuran-track-pane active"
                role="tabpanel"
                aria-labelledby="bakasuran-order-tab">

                <form
                    action="#"
                    method="POST"
                    class="bakasuran-track-form"
                    id="bakasuran-order-form">

                    @csrf

                    <div class="bakasuran-track-field">
                        <label for="bakasuran-order-id">Order Number</label>

                        <div class="bakasuran-track-input-wrap">
                            <span class="bakasuran-track-input-icon">
                                <i class="fa fa-receipt" aria-hidden="true"></i>
                            </span>

                            <input
                                type="text"
                                id="bakasuran-order-id"
                                name="order_id"
                                value="{{ old('order_id') }}"
                                placeholder="e.g. BK-10025"
                                autocomplete="off"
                                required>
                        </div>

                        <small class="bakasuran-track-hint">
                            Find your order number in your order confirmation.
                        </small>

                        @error('order_id')
                            <div class="bakasuran-track-validation">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="bakasuran-track-field">
                        <label for="bakasuran-phone">Phone Number</label>

                        <div class="bakasuran-track-input-wrap">
                            <span class="bakasuran-track-input-icon">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </span>

                            <input
                                type="tel"
                                id="bakasuran-phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Enter your registered phone number"
                                autocomplete="tel"
                                inputmode="tel"
                                required>
                        </div>

                        <small class="bakasuran-track-hint">
                            Use the phone number associated with your order.
                        </small>

                        @error('phone')
                            <div class="bakasuran-track-validation">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="bakasuran-track-btn-wrap">
                        <button type="submit" class="bakasuran-track-btn">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            Track My Order
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tracking number form --}}
            <div
                id="bakasuran-shipment-pane"
                class="bakasuran-track-pane"
                role="tabpanel"
                aria-labelledby="bakasuran-shipment-tab"
                hidden>

                <form
                    action="#"
                    method="POST"
                    class="bakasuran-track-form"
                    id="bakasuran-shipment-form">

                    @csrf

                    <div class="bakasuran-track-field">
                        <label for="bakasuran-tracking-number">Tracking Number</label>

                        <div class="bakasuran-track-input-wrap">
                            <span class="bakasuran-track-input-icon">
                                <i class="fa fa-truck" aria-hidden="true"></i>
                            </span>

                            <input
                                type="text"
                                id="bakasuran-tracking-number"
                                name="awb"
                                value="{{ old('awb') }}"
                                placeholder="Enter your shipment tracking number"
                                autocomplete="off"
                                required>
                        </div>

                        <small class="bakasuran-track-hint">
                            Enter the shipment tracking number provided for your delivery.
                        </small>

                        @error('awb')
                            <div class="bakasuran-track-validation">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="bakasuran-track-btn-wrap">
                        <button type="submit" class="bakasuran-track-btn">
                            <i class="fa fa-location-arrow" aria-hidden="true"></i>
                            Track Shipment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Customer support --}}
        <div class="bakasuran-track-support">
            <div class="bakasuran-track-support-icon">
                <i class="fa fa-headphones" aria-hidden="true"></i>
            </div>

            <div class="bakasuran-track-support-text">
                Need help with your order?
                <strong>BAKASURAN Customer Support</strong>
            </div>
        </div>

        <div class="bakasuran-track-note">
            Fresh food, careful packing, happy moments.
            <strong>That's the BAKASURAN promise.</strong>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.bakasuran-track-tab');
        const panes = document.querySelectorAll('.bakasuran-track-pane');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const targetPane = document.getElementById(targetId);

                if (!targetPane) {
                    return;
                }

                tabs.forEach(function (item) {
                    item.classList.remove('active');
                    item.setAttribute('aria-selected', 'false');
                });

                panes.forEach(function (pane) {
                    pane.classList.remove('active');
                    pane.hidden = true;
                });

                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');

                targetPane.hidden = false;
                targetPane.classList.add('active');
            });
        });
    });
</script>

@endsection
