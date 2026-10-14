
@extends('layouts.website')

@section('content')

<style>

    /* =========================================================
       SUDHEERA SAREES - TRACK ORDER
    ========================================================= */

    .sudheera-track-page {
        background: #faf7f2;
        min-height: 100vh;
        padding: 45px 15px 70px;
    }

    .sudheera-track-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }


    /* =========================================================
       TOP BAR
    ========================================================= */

    .sudheera-track-topbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 18px;
    }

    .sudheera-back-home {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 10px 17px;

        background: #30000e;
        color: #fff !important;

        border: 1px solid #30000e;
        border-radius: 12px;

        text-decoration: none;
        font-size: 13px;
        font-weight: 600;

        transition: all .3s ease;
    }

    .sudheera-back-home i {
        color: #d4a84f;
        font-size: 14px;
    }

    .sudheera-back-home:hover {
        background: #c88618;
        border-color: #c88618;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .sudheera-back-home:hover i {
        color: #fff;
    }


    /* =========================================================
       LOGO
    ========================================================= */

    .sudheera-track-logo {
        background: #fff;
        border: 1px solid #eee1d5;

        border-radius: 20px;

        padding: 25px 20px;

        text-align: center;

        margin-bottom: 22px;

        box-shadow: 0 8px 25px rgba(48, 0, 14, .06);
    }

    .sudheera-track-logo img {
        max-width: 220px;
        max-height: 75px;
        object-fit: contain;
    }


    /* =========================================================
       MAIN CARD
    ========================================================= */

    .sudheera-track-card {
        background: #fff;

        border: 1px solid #eee1d5;

        border-radius: 28px;

        padding: 38px 40px 42px;

        box-shadow: 0 15px 45px rgba(48, 0, 14, .08);

        position: relative;

        overflow: hidden;
    }

    .sudheera-track-card::before {
        content: '';

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 4px;

        background: linear-gradient(
            90deg,
            #30000e,
            #76001f,
            #c88618
        );
    }


    /* =========================================================
       TITLE
    ========================================================= */

    .sudheera-track-title {
        text-align: center;

        color: #30000e;

        font-size: 38px;

        font-weight: 600;

        line-height: 1.2;

        margin: 5px 0 10px;

        font-family: 'Cormorant Garamond', serif;
    }

    .sudheera-track-subtitle {
        text-align: center;

        color: #796c6d;

        font-size: 15px;

        margin: 0 0 30px;
    }


    /* =========================================================
       DECORATIVE TITLE LINE
    ========================================================= */

    .sudheera-track-divider {
        display: flex;

        align-items: center;

        justify-content: center;

        gap: 10px;

        margin-bottom: 30px;
    }

    .sudheera-track-divider span {
        display: block;

        width: 55px;
        height: 1px;

        background: #d2a34b;
    }

    .sudheera-track-divider i {
        color: #c88618;

        font-size: 14px;

        font-style: normal;
    }


    /* =========================================================
       TABS
    ========================================================= */

    .sudheera-track-tabs {
        max-width: 540px;

        margin: 0 auto 35px;

        display: flex;

        gap: 5px;

        padding: 5px;

        background: #f7f1eb;

        border: 1px solid #eadfd6;

        border-radius: 15px;
    }

    .sudheera-track-tab {
        flex: 1;

        height: 46px;

        border: 0;

        border-radius: 11px;

        background: transparent;

        color: #553c42;

        font-size: 14px;

        font-weight: 600;

        cursor: pointer;

        transition: all .3s ease;
    }

    .sudheera-track-tab:hover {
        color: #30000e;
    }

    .sudheera-track-tab.active {
        background: #30000e;

        color: #fff;

        box-shadow: 0 7px 18px rgba(48, 0, 14, .18);
    }


    /* =========================================================
       FORM
    ========================================================= */

    .sudheera-track-form {
        max-width: 540px;

        margin: 0 auto;
    }


    /* =========================================================
       FIELD
    ========================================================= */

    .sudheera-track-field {
        display: flex;

        align-items: center;

        gap: 13px;

        margin-bottom: 20px;
    }

    .sudheera-track-icon {
        width: 50px;
        min-width: 50px;
        height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #fff8ee;

        border: 1px solid #ead7bb;

        color: #a36c0c;

        font-size: 20px;
    }

    .sudheera-track-field input {
        width: 100%;

        height: 52px;

        padding: 0 17px;

        border: 1px solid #dcd0c8;

        border-radius: 13px;

        background: #fff;

        color: #30000e;

        font-size: 14px;

        outline: none;

        transition: all .3s ease;
    }

    .sudheera-track-field input::placeholder {
        color: #a19596;
    }

    .sudheera-track-field input:focus {
        border-color: #c88618;

        box-shadow:
            0 0 0 3px rgba(200, 134, 24, .10);
    }


    /* =========================================================
       BUTTON
    ========================================================= */

    .sudheera-track-btn-wrap {
        text-align: center;

        margin-top: 25px;
    }

    .sudheera-track-btn {
        min-width: 220px;

        height: 52px;

        padding: 0 30px;

        border: 0;

        border-radius: 13px;

        background: linear-gradient(
            135deg,
            #30000e 0%,
            #65001b 55%,
            #8f3b16 100%
        );

        color: #fff;

        font-size: 16px;

        font-weight: 600;

        letter-spacing: .3px;

        cursor: pointer;

        box-shadow:
            0 10px 24px rgba(48, 0, 14, .18);

        transition: all .3s ease;
    }

    .sudheera-track-btn:hover {
        background: linear-gradient(
            135deg,
            #8f3b16 0%,
            #c88618 100%
        );

        transform: translateY(-2px);

        box-shadow:
            0 14px 28px rgba(200, 134, 24, .22);
    }


    /* =========================================================
       PANES
    ========================================================= */

    .sudheera-track-pane {
        display: none;
    }

    .sudheera-track-pane.active {
        display: block;
        animation: sudheeraFade .3s ease;
    }

    @keyframes sudheeraFade {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    /* =========================================================
       ERROR
    ========================================================= */

    .sudheera-track-error {
        max-width: 540px;

        margin: 0 auto 22px;

        padding: 12px 16px;

        border-radius: 12px;

        background: #fff0f1;

        border: 1px solid #e6bfc4;

        color: #8b1e2d;

        text-align: center;

        font-size: 14px;
    }


    /* =========================================================
       BOTTOM BRAND NOTE
    ========================================================= */

    .sudheera-track-note {
        text-align: center;

        margin-top: 25px;

        color: #897878;

        font-size: 13px;
    }

    .sudheera-track-note strong {
        color: #30000e;
    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 767px) {

        .sudheera-track-page {
            padding: 30px 12px 45px;
        }

        .sudheera-track-card {
            padding: 32px 22px 35px;

            border-radius: 23px;
        }

        .sudheera-track-title {
            font-size: 32px;
        }

        .sudheera-track-subtitle {
            font-size: 14px;
        }

        .sudheera-track-tabs {
            margin-bottom: 28px;
        }

        .sudheera-track-tab {
            height: 44px;

            font-size: 13px;

            padding: 0 8px;
        }

        .sudheera-track-field {
            gap: 9px;
        }

        .sudheera-track-icon {
            width: 44px;
            min-width: 44px;
            height: 44px;

            font-size: 18px;
        }

        .sudheera-track-field input {
            height: 50px;

            font-size: 14px;
        }

        .sudheera-track-btn {
            width: 100%;
            min-width: 100%;
        }
    }


    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {

        .sudheera-track-page {
            padding: 22px 9px 35px;
        }

        .sudheera-track-topbar {
            margin-bottom: 12px;
        }

        .sudheera-back-home {
            padding: 8px 12px;

            font-size: 12px;

            border-radius: 10px;
        }

        .sudheera-track-logo {
            padding: 20px 15px;

            border-radius: 17px;
        }

        .sudheera-track-logo img {
            max-width: 180px;
        }

        .sudheera-track-card {
            padding: 28px 15px 30px;

            border-radius: 20px;
        }

        .sudheera-track-title {
            font-size: 28px;
        }

        .sudheera-track-subtitle {
            font-size: 13px;
            line-height: 1.6;
        }

        .sudheera-track-divider span {
            width: 40px;
        }

        .sudheera-track-tab {
            font-size: 12px;
            padding: 0 5px;
        }

        .sudheera-track-field {
            gap: 7px;
        }

        .sudheera-track-icon {
            width: 40px;
            min-width: 40px;
            height: 46px;

            font-size: 17px;
        }

        .sudheera-track-field input {
            height: 46px;

            padding: 0 12px;

            font-size: 13px;
        }

        .sudheera-track-btn {
            height: 48px;

            font-size: 15px;
        }
    }

</style>


<!-- =========================================================
     TRACK PAGE
========================================================= -->

<div class="sudheera-track-page">

    <div class="sudheera-track-wrapper">


        <!-- ================= TOP BAR ================= -->

        <div class="sudheera-track-topbar">

            <a href="{{ route('home') }}"
               class="sudheera-back-home">

                <i class="fa fa-home"></i>

                Back to Home

            </a>

        </div>


        <!-- ================= LOGO ================= -->

        <div class="sudheera-track-logo">

            <a href="{{ route('home') }}">

                <img
                    src="{{ asset('website') }}/images/sudheera.png"
                    alt="Sudheera Sarees">

            </a>

        </div>


        <!-- ================= ERROR ================= -->

        @if(session('error'))

            <div class="sudheera-track-error">

                {{ session('error') }}

            </div>

        @endif


        <!-- ================= MAIN CARD ================= -->

        <div class="sudheera-track-card">


            <h2 class="sudheera-track-title">
                Track Your Order
            </h2>


            <p class="sudheera-track-subtitle">
                Enter your order number or tracking number
                to check your delivery status.
            </p>


            <!-- Decorative Divider -->

            <div class="sudheera-track-divider">

                <span></span>

                <i>✦</i>

                <span></span>

            </div>


            <!-- ================= TABS ================= -->

            <div class="sudheera-track-tabs">

                <button
                    type="button"
                    class="sudheera-track-tab active"
                    data-target="sudheera-order-pane">

                    Order Number

                </button>


                <button
                    type="button"
                    class="sudheera-track-tab"
                    data-target="sudheera-tracking-pane">

                    Tracking Number

                </button>

            </div>


            <!-- =================================================
                 ORDER NUMBER
            ================================================== -->

            <div
                id="sudheera-order-pane"
                class="sudheera-track-pane active">

                <form
                    action="#"
                    method="POST"
                    class="sudheera-track-form">

                    @csrf


                    <!-- Order ID -->

                    <div class="sudheera-track-field">

                        <span class="sudheera-track-icon">
                            🛍️
                        </span>

                        <input
                            type="text"
                            name="order_id"
                            placeholder="Enter Order ID"
                            required>

                    </div>


                    <!-- Phone -->

                    <div class="sudheera-track-field">

                        <span class="sudheera-track-icon">
                            📞
                        </span>

                        <input
                            type="text"
                            name="phone"
                            placeholder="Enter Phone Number"
                            required>

                    </div>


                    <!-- Button -->

                    <div class="sudheera-track-btn-wrap">

                        <button
                            type="button"
                            class="sudheera-track-btn">

                            Track Order

                        </button>

                    </div>

                </form>

            </div>


            <!-- =================================================
                 TRACKING NUMBER
            ================================================== -->

            <div
                id="sudheera-tracking-pane"
                class="sudheera-track-pane">

                <form
                    action="#"
                    method="POST"
                    class="sudheera-track-form">

                    @csrf


                    <!-- Tracking Number -->

                    <div class="sudheera-track-field">

                        <span class="sudheera-track-icon">
                            🚚
                        </span>

                        <input
                            type="text"
                            name="awb"
                            placeholder="Enter Tracking Number"
                            required>

                    </div>


                    <!-- Button -->

                    <div class="sudheera-track-btn-wrap">

                        <button
                            type="submit"
                            class="sudheera-track-btn">

                            Track Shipment

                        </button>

                    </div>

                </form>

            </div>


        </div>


        <!-- ================= NOTE ================= -->

        <div class="sudheera-track-note">

            Need help with your order?
            <strong>Contact Sudheera Sarees Support</strong>

        </div>


    </div>

</div>


<!-- =========================================================
     TAB SCRIPT
========================================================= -->

<script>

    document
        .querySelectorAll('.sudheera-track-tab')
        .forEach(function(tab) {

            tab.addEventListener('click', function() {

                document
                    .querySelectorAll('.sudheera-track-tab')
                    .forEach(function(t) {

                        t.classList.remove('active');

                    });


                document
                    .querySelectorAll('.sudheera-track-pane')
                    .forEach(function(p) {

                        p.classList.remove('active');

                    });


                this.classList.add('active');


                const target =
                    document.getElementById(
                        this.dataset.target
                    );


                if (target) {

                    target.classList.add('active');

                }

            });

        });

</script>

@endsection