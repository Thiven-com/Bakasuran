@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                           SUDHEERA SAREES - STATIC ACCOUNT DASHBOARD
                        ========================================================= */

        .sudheera-account-page {
            background: #fbf8f3;
            min-height: 100vh;
            padding: 10px 0 70px;
            color: #420916;
        }

        /* =========================================================
                           BREADCRUMB
                        ========================================================= */

        .sudheera-breadcrumb-section {
            padding-bottom: 10px;
        }

        .sudheera-breadcrumb-content {
            padding-top: 50px;
        }

        .sudheera-breadcrumb-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sudheera-breadcrumb-list a {
            color: #76001f;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .sudheera-breadcrumb-list a:hover {
            color: #c88618;
        }

        /* =========================================================
                           ACCOUNT LAYOUT
                        ========================================================= */

        .sudheera-account-layout {
            padding-top: 35px;
        }

        /* =========================================================
                           SIDEBAR
                        ========================================================= */

        .sudheera-sidebar {
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 18px;
            padding: 12px;
            box-shadow: 0 8px 25px rgba(66, 9, 22, .05);
            position: sticky;
            top: 20px;
        }

        .sudheera-sidebar-title {
            padding: 18px 17px 12px;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 24px;
            font-weight: 700;
            color: #420916;
        }

        .sudheera-nav-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px 15px;
            margin-bottom: 4px;
            border-radius: 10px;
            color: #62584f;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .sudheera-nav-item i {
            width: 23px;
            text-align: center;
            font-size: 17px;
            color: #a47724;
        }

        .sudheera-nav-arrow {
            margin-left: auto;
            font-size: 12px !important;
            color: #b9ada1 !important;
        }

        .sudheera-nav-item:hover {
            background: #fbf3e5;
            color: #420916;
        }

        .sudheera-nav-item.active {
            background: #420916;
            color: #fff;
        }

        .sudheera-nav-item.active i {
            color: #c88618;
        }

        .sudheera-nav-item.active .sudheera-nav-arrow {
            color: #fff !important;
        }

        .sudheera-nav-logout {
            margin-top: 8px;
            border-top: 1px solid #eee6dc;
            padding-top: 13px;
            color: #a52d2d;
        }

        .sudheera-nav-logout i {
            color: #a52d2d;
        }

        /* =========================================================
                           MAIN CONTENT
                        ========================================================= */

        .sudheera-account-content {
            padding-left: 10px;
        }

        /* =========================================================
                           WELCOME
                        ========================================================= */

        .sudheera-welcome {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg,
                    #420916 0%,
                    #76001f 55%,
                    #a85c17 100%);
            border-radius: 20px;
            padding: 32px;
            color: #fff;
            margin-bottom: 28px;
            box-shadow: 0 12px 30px rgba(66, 9, 22, .12);
        }

        .sudheera-welcome::before {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            right: -65px;
            top: -80px;
            border-radius: 50%;
            background: rgba(200, 134, 24, .18);
        }

        .sudheera-welcome::after {
            content: "";
            position: absolute;
            width: 120px;
            height: 120px;
            right: 90px;
            bottom: -80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .sudheera-welcome-content {
            position: relative;
            z-index: 2;
        }

        .sudheera-welcome-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: #e5c477;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sudheera-welcome-title {
            margin: 0 0 9px;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 35px;
            font-weight: 700;
            color: #fff;
        }

        .sudheera-welcome-text {
            max-width: 650px;
            margin: 0;
            color: rgba(255, 255, 255, .85);
            font-size: 14px;
            line-height: 1.7;
        }

        /* =========================================================
                           DASHBOARD CARDS
                        ========================================================= */

        .sudheera-dashboard-card {
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 17px;
            padding: 25px;
            height: 100%;
            transition: all .3s ease;
            box-shadow: 0 6px 20px rgba(66, 9, 22, .035);
        }

        .sudheera-dashboard-card:hover {
            transform: translateY(-5px);
            border-color: #d9c49b;
            box-shadow: 0 14px 32px rgba(66, 9, 22, .09);
        }

        .sudheera-card-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #fbf3e5;
            color: #8a5c13;
            font-size: 24px;
            margin-bottom: 17px;
        }

        .sudheera-dashboard-card h6 {
            color: #420916;
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .sudheera-dashboard-card p {
            color: #7b7168;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .sudheera-dashboard-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 40px;
            padding: 8px 18px;
            border: 1px solid #c88618;
            border-radius: 30px;
            background: #fff;
            color: #8a5c13;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            transition: all .3s ease;
        }

        .sudheera-dashboard-btn:hover {
            background: #420916;
            border-color: #420916;
            color: #fff;
        }

        /* =========================================================
                           QUICK INFORMATION
                        ========================================================= */

        .sudheera-info-card {
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 17px;
            padding: 25px;
            margin-top: 28px;
            box-shadow: 0 6px 20px rgba(66, 9, 22, .035);
        }

        .sudheera-info-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #eee6dc;
        }

        .sudheera-info-title h5 {
            margin: 0;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 24px;
            font-weight: 700;
            color: #420916;
        }

        .sudheera-info-badge {
            padding: 5px 10px;
            border-radius: 50px;
            background: #edf5ed;
            color: #52743d;
            font-size: 10px;
            font-weight: 700;
        }

        .sudheera-info-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 8px 0;
        }

        .sudheera-info-row-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fbf3e5;
            color: #a47724;
        }

        .sudheera-info-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9a9087;
            margin-bottom: 3px;
            font-weight: 700;
        }

        .sudheera-info-value {
            color: #420916;
            font-size: 13px;
            font-weight: 600;
        }

        /* =========================================================
                           RESPONSIVE
                        ========================================================= */

        @media (max-width: 1199px) {

            .sudheera-account-content {
                padding-left: 0;
            }

            .sudheera-welcome {
                padding: 28px;
            }

            .sudheera-welcome-title {
                font-size: 31px;
            }
        }

        @media (max-width: 991px) {

            .sudheera-sidebar {
                position: static;
                margin-bottom: 25px;
            }

            .sudheera-account-content {
                padding-left: 0;
            }

            .sudheera-welcome {
                text-align: center;
            }

            .sudheera-welcome-text {
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (max-width: 767px) {

            .sudheera-account-page {
                padding-bottom: 50px;
            }

            .sudheera-breadcrumb-content {
                padding-top: 30px;
            }

            .sudheera-account-layout {
                padding-top: 20px;
            }

            .sudheera-sidebar-title {
                font-size: 22px;
            }

            .sudheera-nav-item {
                padding: 13px;
            }

            .sudheera-welcome {
                padding: 25px 20px;
                border-radius: 16px;
            }

            .sudheera-welcome-title {
                font-size: 27px;
            }

            .sudheera-welcome-text {
                font-size: 12px;
            }

            .sudheera-dashboard-card {
                text-align: center;
                padding: 22px 18px;
            }

            .sudheera-card-icon {
                margin-left: auto;
                margin-right: auto;
            }

            .sudheera-dashboard-card h6 {
                font-size: 18px;
            }

            .sudheera-dashboard-card p {
                font-size: 12px;
            }

            .sudheera-info-card {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {

            .sudheera-welcome-title {
                font-size: 24px;
            }

            .sudheera-welcome-label {
                font-size: 9px;
            }

            .sudheera-dashboard-card {
                padding: 20px 15px;
            }

            .sudheera-card-icon {
                width: 52px;
                height: 52px;
                font-size: 21px;
            }

            .sudheera-info-title h5 {
                font-size: 21px;
            }
        }
    </style>


    <!-- =========================================================
                         ACCOUNT PAGE
                    ========================================================= -->

<main class="sudheera-account-page">

    <!-- =====================================================
         BREADCRUMB
    ====================================================== -->

    <section class="sudheera-breadcrumb-section">

        <div class="container">

            <div class="sudheera-breadcrumb-content">

                <ul class="sudheera-breadcrumb-list">

                    <li>
                        <a href="{{ route('home') }}">
                            Home
                        </a>

                        <span style="margin:0 8px; color:#b6a99c;">
                            /
                        </span>

                        <span style="color:#83786e; font-size:13px;">
                            My Account
                        </span>
                    </li>

                </ul>

            </div>

        </div>

    </section>


    <!-- =====================================================
         ACCOUNT CONTENT
    ====================================================== -->

    <section class="sudheera-account-layout">

        <div class="container">

            <div class="row g-4">


                <!-- =================================================
                     SIDEBAR
                ================================================== -->

                <div class="col-lg-4 col-xl-3">

                    <div class="sudheera-sidebar">

                        <div class="sudheera-sidebar-title">
                            My Account
                        </div>


                        <a href="{{ route('account') }}" class="sudheera-nav-item active">

                            <i class="icon icon-Dashboard"></i>

                            <span>
                                Dashboard
                            </span>

                            <i class="icon icon-ArrowCaretRight sudheera-nav-arrow"></i>

                        </a>


                        <a href="{{ route('orders') }}" class="sudheera-nav-item">

                            <i class="icon icon-Box"></i>

                            <span>
                                My Orders
                            </span>

                            <i class="icon icon-ArrowCaretRight sudheera-nav-arrow"></i>

                        </a>


                        <a href="{{ route('wishlist') }}" class="sudheera-nav-item">

                            <i class="icon icon-Hearth"></i>

                            <span>
                                My Wishlist
                            </span>

                            <i class="icon icon-ArrowCaretRight sudheera-nav-arrow"></i>

                        </a>


                        <a href="{{ route('addresses') }}" class="sudheera-nav-item">

                            <i class="icon icon-DotLocation"></i>

                            <span>
                                Delivery Addresses
                            </span>

                            <i class="icon icon-ArrowCaretRight sudheera-nav-arrow"></i>

                        </a>


                        <a href="{{ route('account-settings') }}" class="sudheera-nav-item">

                            <i class="icon icon-Setting"></i>

                            <span>
                                Account Settings
                            </span>

                            <i class="icon icon-ArrowCaretRight sudheera-nav-arrow"></i>

                        </a>


                        <a href="{{ route('logout') }}" class="sudheera-nav-item sudheera-nav-logout">

                            <i class="icon icon-Logout"></i>

                            <span>
                                Log Out
                            </span>

                        </a>

                    </div>

                </div>


                <!-- =================================================
                     MAIN CONTENT
                ================================================== -->

                <div class="col-lg-8 col-xl-9">

                    <div class="sudheera-account-content">


                        <!-- =================================================
                             WELCOME
                        ================================================== -->

                        <div class="sudheera-welcome">

                            <div class="sudheera-welcome-content">

                                <div class="sudheera-welcome-label">
                                    BAKASURAN FOOD
                                </div>

                                <h2 class="sudheera-welcome-title">
                                    Welcome back, {{ $address->name ?? 'Food Lover' }} 👋
                                </h2>

                                <p class="sudheera-welcome-text">
                                    Welcome to your BAKASURAN account dashboard.
                                    Manage your food orders, track deliveries,
                                    revisit your favourite dishes and update
                                    your account details — all in one place.
                                    Your next delicious meal is just a few
                                    clicks away.
                                </p>

                            </div>

                        </div>


                        <!-- =================================================
                             DASHBOARD CARDS
                        ================================================== -->

                        <div class="row g-4">


                            <!-- ORDERS -->

                            <div class="col-md-6">

                                <div class="sudheera-dashboard-card">

                                    <div class="sudheera-card-icon">
                                        <i class="fa fa-shopping-bag"></i>
                                    </div>

                                    <h6>
                                        My Food Orders
                                    </h6>

                                    <p>
                                        View your previous orders, check
                                        order details and follow the status
                                        of your food deliveries.
                                    </p>

                                    <a href="{{ route('orders') }}" class="sudheera-dashboard-btn">

                                        View My Orders

                                        <i class="fa fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>


                            <!-- WISHLIST -->

                            <div class="col-md-6">

                                <div class="sudheera-dashboard-card">

                                    <div class="sudheera-card-icon">
                                        <i class="fa fa-heart"></i>
                                    </div>

                                    <h6>
                                        Favourite Foods
                                    </h6>

                                    <p>
                                        Find the food items you have saved
                                        and revisit dishes you would love
                                        to order again.
                                    </p>

                                    <a href="{{ route('wishlist') }}" class="sudheera-dashboard-btn">

                                        View Wishlist

                                        <i class="fa fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>


                            <!-- ACCOUNT -->

                            <div class="col-md-6">

                                <div class="sudheera-dashboard-card">

                                    <div class="sudheera-card-icon">
                                        <i class="fa fa-user"></i>
                                    </div>

                                    <h6>
                                        My Account Details
                                    </h6>

                                    <p>
                                        Keep your personal information,
                                        contact details and account
                                        preferences up to date.
                                    </p>

                                    <a href="{{ route('account-settings') }}" class="sudheera-dashboard-btn">

                                        Manage Account

                                        <i class="fa fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>


                            <!-- LOGOUT -->

                            <div class="col-md-6">

                                <div class="sudheera-dashboard-card">

                                    <div class="sudheera-card-icon">
                                        <i class="fa fa-sign-out"></i>
                                    </div>

                                    <h6>
                                        Sign Out
                                    </h6>

                                    <p>
                                        Finished ordering for now?
                                        Securely sign out of your
                                        BAKASURAN account.
                                    </p>

                                    <a href="{{ route('logout') }}" class="sudheera-dashboard-btn">

                                        Log Out

                                        <i class="fa fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             QUICK ACCOUNT INFORMATION
                        ================================================== -->

                        <div class="sudheera-info-card">

                            <div class="sudheera-info-title">

                                <h5>
                                    My Account Information
                                </h5>

                                <span class="sudheera-info-badge">
                                    Account Overview
                                </span>

                            </div>


                            <div class="row g-3">


                                <!-- NAME -->

                                <div class="col-md-4">

                                    <div class="sudheera-info-row">

                                        <div class="sudheera-info-row-icon">
                                            <i class="fa fa-user"></i>
                                        </div>

                                        <div>

                                            <div class="sudheera-info-label">
                                                Full Name
                                            </div>

                                            <div class="sudheera-info-value">
                                                {{ $address->name ?? 'Not provided' }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- EMAIL -->

                                <div class="col-md-4">

                                    <div class="sudheera-info-row">

                                        <div class="sudheera-info-row-icon">
                                            <i class="fa fa-envelope"></i>
                                        </div>

                                        <div>

                                            <div class="sudheera-info-label">
                                                Email Address
                                            </div>

                                            <div class="sudheera-info-value">
                                                {{ $address->email ?? 'Not provided' }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- PHONE -->

                                <div class="col-md-4">

                                    <div class="sudheera-info-row">

                                        <div class="sudheera-info-row-icon">
                                            <i class="fa fa-phone"></i>
                                        </div>

                                        <div>

                                            <div class="sudheera-info-label">
                                                Mobile Number
                                            </div>

                                            <div class="sudheera-info-value">
                                                {{ $address->mobile ?? 'Not provided' }}
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </section>

</main>


@endsection