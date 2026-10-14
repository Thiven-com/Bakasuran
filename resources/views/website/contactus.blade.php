@extends('layouts.website')
@section('content')

    <!-- <style>
            /* =========================================================
                   SUDHEERA SAREES - PREMIUM CONTACT PAGE
                ========================================================= */

            .sudheera-contact-wrapper {
                display: grid;
                grid-template-columns: 1fr 1.1fr;
                gap: 32px;
                align-items: start;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: 20px;
            }

            /* ================= LEFT PANEL ================= */

            .sudheera-contact-left {
                background: linear-gradient(135deg,
                        #30000e 0%,
                        #5b0018 35%,
                        #7a2d19 70%,
                        #9b4a28 100%);
                color: #fff;
                border-radius: 32px;
                padding: 42px 36px;
                position: sticky;
                top: 20px;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(48, 0, 14, .18);
            }

            .sudheera-contact-left::before {
                content: '';
                position: absolute;
                width: 280px;
                height: 280px;
                border-radius: 50%;
                background: rgba(216, 173, 86, .10);
                top: -100px;
                right: -90px;
            }

            .sudheera-contact-left::after {
                content: '';
                position: absolute;
                width: 180px;
                height: 180px;
                border-radius: 50%;
                background: rgba(255, 255, 255, .05);
                bottom: -70px;
                left: -60px;
            }

            .sudheera-contact-content {
                position: relative;
                z-index: 2;
            }

            .sudheera-contact-tag {
                font-size: 13px;
                letter-spacing: 3px;
                font-weight: 700;
                text-transform: uppercase;
                color: #e5c27b;
                margin-bottom: 14px;
            }

            .sudheera-contact-title {
                font-size: 43px;
                line-height: 1.2;
                font-weight: 700;
                margin-bottom: 20px;
                color: #fff;
            }

            .sudheera-contact-text {
                font-size: 16px;
                line-height: 1.85;
                color: rgba(255, 255, 255, .88);
                margin-bottom: 34px;
            }

            /* ================= BRAND BOX ================= */

            .sudheera-brand-box {
                display: flex;
                align-items: center;
                gap: 16px;
                background: rgba(255, 255, 255, .09);
                border: 1px solid rgba(229, 194, 123, .28);
                border-radius: 24px;
                padding: 20px;
                backdrop-filter: blur(12px);
            }

            .sudheera-brand-icon {
                width: 66px;
                height: 66px;
                min-width: 66px;
                border-radius: 18px;
                background: linear-gradient(135deg,
                        #c88618,
                        #e4bd68);
                color: #30000e;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                box-shadow: 0 10px 25px rgba(200, 134, 24, .25);
            }

            .sudheera-brand-box h5 {
                margin: 0 0 5px;
                color: #fff;
                font-size: 19px;
                font-weight: 700;
            }

            .sudheera-brand-box p {
                margin: 0 0 5px;
                font-size: 14px;
                color: rgba(255, 255, 255, .82);
            }

            .sudheera-brand-box span {
                font-size: 13px;
                font-weight: 600;
                color: #e5c27b;
            }

            /* ================= RIGHT SIDE ================= */

            .sudheera-contact-right {
                display: grid;
                gap: 18px;
            }

            .sudheera-contact-card {
                background: #fff;
                border: 1px solid #eee1d8;
                border-radius: 26px;
                padding: 25px;
                display: flex;
                gap: 18px;
                align-items: flex-start;
                box-shadow: 0 12px 32px rgba(48, 0, 14, .05);
                transition: all .35s ease;
            }

            .sudheera-contact-card:hover {
                transform: translateY(-6px);
                border-color: #d9b36a;
                box-shadow: 0 20px 42px rgba(48, 0, 14, .10);
            }

            /* ================= ICON ================= */

            .sudheera-card-icon {
                width: 58px;
                height: 58px;
                min-width: 58px;
                border-radius: 18px;
                background: linear-gradient(135deg,
                        #30000e,
                        #7a2d19);
                color: #e5c27b;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 25px;
                box-shadow: 0 10px 24px rgba(48, 0, 14, .16);
            }

            .sudheera-contact-card h5 {
                margin: 0 0 10px;
                font-size: 19px;
                font-weight: 700;
                color: #351019;
            }

            .sudheera-contact-card p {
                margin: 0;
                font-size: 15px;
                line-height: 1.8;
                color: #67585a;
            }

            .sudheera-contact-card strong {
                color: #3b111c;
            }

            .sudheera-contact-card a {
                color: #76001f;
                text-decoration: none;
                font-weight: 600;
                transition: .3s ease;
            }

            .sudheera-contact-card a:hover {
                color: #c88618;
            }

            /* ================= SOCIAL BUTTONS ================= */

            .sudheera-social-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 15px;
            }

            .sudheera-social-buttons a {
                padding: 10px 17px;
                border-radius: 999px;
                background: #fbf5ef;
                border: 1px solid #ead8c9;
                color: #5c1725;
                font-size: 14px;
                font-weight: 600;
                transition: all .3s ease;
                text-decoration: none;
            }

            .sudheera-social-buttons a:hover {
                background: #30000e;
                border-color: #30000e;
                color: #e5c27b;
                transform: translateY(-2px);
            }

            /* ================= RESPONSIVE ================= */

            @media (max-width: 991px) {

                .sudheera-contact-wrapper {
                    grid-template-columns: 1fr;
                }

                .sudheera-contact-left {
                    position: relative;
                    top: auto;
                }
            }

            @media (max-width: 767px) {

                .sudheera-contact-left {
                    padding: 32px 24px;
                    border-radius: 26px;
                }

                .sudheera-contact-title {
                    font-size: 32px;
                }

                .sudheera-contact-text {
                    font-size: 15px;
                    line-height: 1.75;
                }

                .sudheera-brand-box {
                    padding: 16px;
                    border-radius: 20px;
                }

                .sudheera-brand-icon {
                    width: 56px;
                    height: 56px;
                    min-width: 56px;
                    font-size: 24px;
                }

                .sudheera-brand-box h5 {
                    font-size: 16px;
                }

                .sudheera-brand-box p {
                    font-size: 13px;
                }

                .sudheera-contact-card {
                    padding: 20px;
                    border-radius: 22px;
                    gap: 14px;
                }

                .sudheera-card-icon {
                    width: 52px;
                    height: 52px;
                    min-width: 52px;
                    border-radius: 16px;
                    font-size: 22px;
                }

                .sudheera-contact-card h5 {
                    font-size: 17px;
                }

                .sudheera-contact-card p {
                    font-size: 14px;
                }

                .sudheera-social-buttons a {
                    padding: 9px 14px;
                    font-size: 13px;
                }
            }

            .breadcrumb-list {
                display: flex;
                align-items: center;
                gap: 8px;
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .breadcrumb-list li {
                display: flex;
                align-items: center;
                font-size: 14px;
                font-weight: 500;
                color: #6f5a5f;
            }

            .breadcrumb-list li a {
                display: inline-flex;
                align-items: center;
                text-decoration: none;
                color: #30000e;
                transition: all 0.3s ease;
            }

            .breadcrumb-list li a:hover {
                color: #c88618;
            }

            .breadcrumb-list li span {
                color: #c88618;
                font-weight: 600;
            }

            .breadcrumb-list li:last-child {
                color: #8a6870;
                font-weight: 600;
            }

            /* Mobile */
            @media (max-width: 767px) {
                .breadcrumb-list {
                    padding-left: 0;
                }

                .breadcrumb-list li {
                    font-size: 13px;
                }

                .breadcrumb-list li span {
                    margin: 0 6px !important;
                }
            }
        </style> -->


    <style>
        /* =========================================================
       BAKASURAN — PREMIUM CONTACT PAGE THEME
    ========================================================= */

        .sudheera-contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            gap: 32px;
            align-items: start;
            margin: 0 20px 20px;
        }

        /* LEFT PANEL */

        .sudheera-contact-left {
            background: linear-gradient(145deg,
                    #171717 0%,
                    #0A0A0A 65%,
                    #250000 100%);
            color: #FFFFFF;
            border: 1px solid #303030;
            border-radius: 28px;
            padding: 42px 36px;
            position: sticky;
            top: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .sudheera-contact-left::before {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 0, 0, 0.10);
            top: -100px;
            right: -90px;
            pointer-events: none;
        }

        .sudheera-contact-left::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 201, 40, 0.06);
            bottom: -70px;
            left: -60px;
            pointer-events: none;
        }

        .sudheera-contact-content {
            position: relative;
            z-index: 2;
        }

        .sudheera-contact-tag {
            font-size: 13px;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
            color: #FFC928;
            margin-bottom: 14px;
        }

        .sudheera-contact-title {
            font-size: 43px;
            line-height: 1.2;
            font-weight: 700;
            margin-bottom: 20px;
            color: #FFFFFF;
        }

        .sudheera-contact-text {
            font-size: 16px;
            line-height: 1.85;
            color: #B8B8B8;
            margin-bottom: 34px;
        }

        /* BRAND BOX */

        .sudheera-brand-box {
            display: flex;
            align-items: center;
            gap: 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid #383838;
            border-radius: 22px;
            padding: 20px;
            backdrop-filter: blur(12px);
        }

        .sudheera-brand-icon {
            width: 66px;
            height: 66px;
            min-width: 66px;
            border-radius: 18px;
            background: linear-gradient(135deg, #FF0000, #CC0000);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 10px 25px rgba(255, 0, 0, 0.20);
        }

        .sudheera-brand-box h5 {
            margin: 0 0 5px;
            color: #FFFFFF;
            font-size: 19px;
            font-weight: 700;
        }

        .sudheera-brand-box p {
            margin: 0 0 5px;
            font-size: 14px;
            color: #B8B8B8;
        }

        .sudheera-brand-box span {
            font-size: 13px;
            font-weight: 600;
            color: #FFC928;
            line-height: 1.7;
        }

        /* RIGHT SIDE */

        .sudheera-contact-right {
            display: grid;
            gap: 18px;
        }

        /* CONTACT CARDS */

        .sudheera-contact-card {
            background: #171717;
            border: 1px solid #303030;
            border-radius: 22px;
            padding: 25px;
            display: flex;
            gap: 18px;
            align-items: flex-start;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
            transition: transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
        }

        .sudheera-contact-card:hover {
            transform: translateY(-4px);
            border-color: #FF0000;
            box-shadow: 0 18px 38px rgba(255, 0, 0, 0.08);
        }

        /* CONTACT ICONS */

        .sudheera-card-icon {
            width: 58px;
            height: 58px;
            min-width: 58px;
            border-radius: 17px;
            background: rgba(255, 0, 0, 0.10);
            border: 1px solid rgba(255, 0, 0, 0.25);
            color: #FFC928;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            transition: background 0.3s ease;
        }

        .sudheera-contact-card:hover .sudheera-card-icon {
            background: #FF0000;
            color: #FFFFFF;
        }

        /* CARD TEXT */

        .sudheera-contact-card h5 {
            margin: 0 0 10px;
            font-size: 19px;
            font-weight: 700;
            color: #FFFFFF;
        }

        .sudheera-contact-card p {
            margin: 0;
            font-size: 15px;
            line-height: 1.8;
            color: #B8B8B8;
        }

        .sudheera-contact-card strong {
            color: #FFFFFF;
        }

        /* CARD LINKS */

        .sudheera-contact-card a {
            color: #FF4545;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.25s ease;
        }

        .sudheera-contact-card a:hover {
            color: #FFC928;
        }

        /* SOCIAL BUTTONS */

        .sudheera-social-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .sudheera-social-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 17px;
            border-radius: 999px;
            background: #202020;
            border: 1px solid #383838;
            color: #FFFFFF;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .sudheera-social-buttons a:hover {
            background: #FF0000;
            border-color: #FF0000;
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        /* BREADCRUMB */

        .breadcrumb-list {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .breadcrumb-list li {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            color: #B8B8B8;
        }

        .breadcrumb-list li a {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #000000;
            transition: color 0.25s ease;
        }

        .breadcrumb-list li a:hover {
            color: #FF4545;
        }

        .breadcrumb-list li span {
            color: #FFC928;
            font-weight: 600;
        }

        .breadcrumb-list li:last-child {
            color: #FFC928;
            font-weight: 600;
        }

        /* ACCESSIBILITY */

        .sudheera-contact-card a:focus-visible,
        .sudheera-social-buttons a:focus-visible,
        .breadcrumb-list a:focus-visible {
            outline: 2px solid #FFC928;
            outline-offset: 4px;
        }

        /* TABLET */

        @media (max-width: 991px) {
            .sudheera-contact-wrapper {
                grid-template-columns: 1fr;
            }

            .sudheera-contact-left {
                position: relative;
                top: auto;
            }
        }

        /* MOBILE */

        @media (max-width: 767px) {
            .sudheera-contact-wrapper {
                margin-left: 12px;
                margin-right: 12px;
                gap: 22px;
            }

            .sudheera-contact-left {
                padding: 32px 24px;
                border-radius: 24px;
            }

            .sudheera-contact-title {
                font-size: 32px;
            }

            .sudheera-contact-text {
                font-size: 15px;
                line-height: 1.75;
            }

            .sudheera-brand-box {
                padding: 16px;
                border-radius: 18px;
                align-items: flex-start;
            }

            .sudheera-brand-icon {
                width: 56px;
                height: 56px;
                min-width: 56px;
                font-size: 24px;
            }

            .sudheera-brand-box h5 {
                font-size: 16px;
            }

            .sudheera-brand-box p {
                font-size: 13px;
            }

            .sudheera-brand-box span {
                font-size: 12px;
            }

            .sudheera-contact-card {
                padding: 20px;
                border-radius: 20px;
                gap: 14px;
            }

            .sudheera-card-icon {
                width: 48px;
                height: 48px;
                min-width: 48px;
                border-radius: 14px;
                font-size: 21px;
            }

            .sudheera-contact-card h5 {
                font-size: 17px;
            }

            .sudheera-contact-card p {
                font-size: 14px;
            }

            .sudheera-social-buttons a {
                padding: 9px 14px;
                font-size: 13px;
            }

            .breadcrumb-list {
                padding-left: 0;
            }

            .breadcrumb-list li {
                font-size: 13px;
            }

            .breadcrumb-list li span {
                margin: 0 6px !important;
            }
        }
    </style>

    {{-- BREADCRUMB --}}
    <section class="page-breadcrumb" style="padding-bottom: 10px;">
        <div class="container">
            <div class="breadcrumb-content" style="padding-top: 50px; margin-left: 20px;">
                <ul class="breadcrumb-list">
                    <li>
                        <a href="/">
                            Home
                            <span style="margin:0 8px;"> / </span>
                            Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <div class="section-contact-infor flat-spacing">
        <div class="container">
            <div class="sudheera-contact-wrapper">

                {{-- LEFT SIDE --}}
                <div class="sudheera-contact-left">
                    <div class="sudheera-contact-content">

                        <p class="sudheera-contact-tag">
                            BAKASURAN FOOD
                        </p>

                        <h2 class="sudheera-contact-title">
                            Let's Talk About Food!
                        </h2>

                        <p class="sudheera-contact-text">
                            Have a question about our menu, your order,
                            delivery, offers, or restaurant partnerships?
                            The BAKASURAN team is here to help make your
                            food ordering experience simple and enjoyable.
                            Get in touch with us for assistance, feedback,
                            or business enquiries.
                        </p>

                        {{-- BRAND BOX --}}
                        <div class="sudheera-brand-box">
                            <div class="sudheera-brand-icon">
                                🍔
                            </div>

                            <div>
                                <h5>BAKASURAN</h5>
                                <p>Your Food, Your Cravings</p>
                                <span>
                                    Fresh Flavours • Great Taste • Happy Moments
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="sudheera-contact-right">

                    {{-- FOOD & RESTAURANT --}}
                    <div class="sudheera-contact-card">
                        <div class="sudheera-card-icon">
                            🍽️
                        </div>

                        <div>
                            <h5>Explore Our Food</h5>
                            <p>
                                Looking for your favourite biryani, burgers,
                                pizza, snacks, or desserts? Explore our menu
                                and discover delicious options for your next meal.
                            </p>
                            <p style="margin-top:8px;">
                                <a href="{{ url('/explore') }}">
                                    Explore Menu
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- CUSTOMER SUPPORT --}}
                    <div class="sudheera-contact-card">
                        <div class="sudheera-card-icon">
                            📞
                        </div>

                        <div>
                            <h5>Customer Support</h5>
                            <p>
                                Need help with an order, delivery status,
                                payment, cancellation, or an issue with your
                                food? Contact our support team using the
                                customer service details provided on our platform.
                            </p>
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div class="sudheera-contact-card">
                        <div class="sudheera-card-icon">
                            ✉️
                        </div>

                        <div>
                            <h5>Email & Website</h5>
                            <p>
                                Have feedback, questions, or suggestions
                                for BAKASURAN? Reach us through the official
                                contact email configured for your platform.
                                <br><br>
                                <a href="{{ url('/') }}">
                                    Visit BAKASURAN
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- SUPPORT HOURS --}}
                    <div class="sudheera-contact-card">
                        <div class="sudheera-card-icon">
                            🕒
                        </div>

                        <div>
                            <h5>Customer Support Hours</h5>
                            <p>
                                <strong>Support Availability</strong>
                                <br>
                                Please check the support timings published
                                on our platform before contacting our team.
                                <br><br>
                                <strong>Order Assistance</strong>
                                <br>
                                Keep your order details ready so we can
                                help you more efficiently.
                            </p>
                        </div>
                    </div>

                    {{-- BUSINESS ENQUIRIES --}}
                    <div class="sudheera-contact-card">
                        <div class="sudheera-card-icon">
                            🤝
                        </div>

                        <div>
                            <h5>Restaurant & Business Partnerships</h5>
                            <p>
                                Are you a restaurant owner, food business,
                                or delivery partner interested in working
                                with BAKASURAN? We welcome partnership
                                enquiries and opportunities to grow together.
                            </p>
                        </div>
                    </div>

                    {{-- SOCIAL MEDIA --}}
                    <div class="sudheera-contact-card">
                        <div class="sudheera-card-icon">
                            🌐
                        </div>

                        <div>
                            <h5>Follow BAKASURAN</h5>
                            <p>
                                Stay connected for food inspiration,
                                new menu items, special offers, restaurant
                                updates, and exciting deals.
                            </p>

                            <div class="sudheera-social-buttons">
                                {{-- Replace # with your actual social URLs --}}
                                <a href="#" target="_blank" rel="noopener noreferrer">
                                    Facebook
                                </a>

                                <a href="#" target="_blank" rel="noopener noreferrer">
                                    Instagram
                                </a>

                                <a href="#" target="_blank" rel="noopener noreferrer">
                                    YouTube
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection