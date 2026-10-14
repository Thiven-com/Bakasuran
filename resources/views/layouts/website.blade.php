<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bakasuran</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('website') }}/images/logobaka.png">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('website') }}/images/logobaka.png">

    <!-- Apple / iPhone -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('website') }}/images/logobaka.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /*=========
    header and topbar
    ========*/
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* =========================================================
   FIXED TOP BAR + NAVBAR
========================================================= */

        .sudheera-fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;

            z-index: 999999;

            background: #ffffff;

            box-shadow:
                0 3px 18px rgba(0, 0, 0, 0.08);
        }


        /* =========================================================
   TOP BAR
========================================================= */

        .sudheera-fixed-header .top-bar {
            width: 100%;
            height: 40px;

            /* background: #76001f; */

            background: #000;
            color: #ffffff;

            display: flex;
            align-items: center;
        }


        /* =========================================================
   MAIN NAVBAR
========================================================= */

        .sudheera-fixed-header .main-navbar {
            width: 100%;
            height: 70px;

            background: #fffdf9;

            border-bottom: 1px solid #eeeeee;

            display: flex;
            align-items: center;

            position: relative;
            z-index: 1000;
        }


        /* =========================================================
   PAGE OFFSET
========================================================= */

        /*
   Top Bar = 40px
   Navbar   = 70px
   Total    = 110px
*/

        body {
            padding-top: 110px;
        }


        /* =========================================================
   DROPDOWN MUST APPEAR ABOVE CONTENT
========================================================= */

        .sudheera-fixed-header .nav-dropdown .dropdown-menu {
            z-index: 999999;
        }


        /* =========================================================
   MOBILE
========================================================= */

        @media (max-width: 991px) {

            .sudheera-fixed-header .top-bar {
                height: 32px;
            }

            .sudheera-fixed-header .main-navbar {
                height: 65px;
            }

            body {
                padding-top: 97px;
            }

        }


        /* =========================================================
   SMALL MOBILE
========================================================= */

        @media (max-width: 480px) {

            .sudheera-fixed-header .top-bar {
                height: 32px;
            }

            .sudheera-fixed-header .main-navbar {
                height: 60px;
            }

            body {
                padding-top: 92px;
            }

        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Top Bar */
        .top-bar {
            width: 100%;
            height: 40px;
            background: #76001f;
            color: #ffffff;

            display: flex;
            align-items: center;
        }

        .top-bar-container {
            width: 100%;
            padding: 0 30px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Left / Center Message */
        .top-bar-message {
            display: flex;
            align-items: center;
            justify-content: center;

            gap: 10px;

            font-size: 15px;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Right Links */
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 15px;
        }

        .top-bar-right a {
            color: #ffffff;
            text-decoration: none;
        }

        .top-bar-right a:hover {
            text-decoration: underline;
        }

        .separator {
            color: red;
            opacity: 1;
        }

        /* Mobile */
        @media (max-width: 768px) {

            .top-bar {
                height: 32px;
            }

            .top-bar-container {
                padding: 0 10px;
                justify-content: center;
            }

            .top-bar-message {
                font-size: 9px;
                gap: 5px;
            }

            .top-bar-right {
                display: none;
            }
        }

        @media (max-width: 480px) {

            .top-bar-message {
                font-size: 8px;
                gap: 4px;
            }
        }

        < !-- navbar-->
        /* =========================================
   MAIN NAVBAR
========================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .main-navbar {
            width: 100%;
            height: 70px;
            background: #fffdf9;
            border-bottom: 1px solid #eeeeee;

            display: flex;
            align-items: center;

            position: relative;
            z-index: 1000;
        }

        .navbar-container {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 40px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 30px;
        }


        /* =========================================
   LOGO
========================================= */

        .navbar-logo {
            flex-shrink: 0;
        }

        .navbar-logo img {
            width: 150px;
            height: auto;
            display: block;
        }

        @media (max-width: 767px) {
            .navbar-logo {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }

            .navbar-logo img {
                width: 100px !important;
            }
        }

        .navbar-logo a {
            display: flex;
            align-items: center;
            gap: 8px;

            color: #651024;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 38px;
            color: #9b7b3c;
        }

        .logo-text {
            line-height: 1;
        }

        .logo-name {
            font-family: Georgia, serif;
            font-size: 22px;
            letter-spacing: 1px;
            color: #5b1625;
        }

        .logo-tagline {
            margin-top: 4px;

            font-size: 7px;
            letter-spacing: 2px;
            color: #777;
            text-align: center;
        }


        /* =========================================
   NAVIGATION
========================================= */

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 25px;

            height: 100%;
        }

        .nav-link {
            position: relative;

            height: 70px;

            display: flex;
            align-items: center;

            color: #222;
            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            white-space: nowrap;

            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #7a1429;
        }


        /* Active */
        .nav-link.active {
            color: #7a1429;
        }

        .nav-link.active::after {
            content: "";

            position: absolute;
            bottom: 17px;
            left: 0;

            width: 100%;
            height: 1px;

            background: #7a1429;
        }

        /* =========================================
   NAV DROPDOWN
========================================= */

        .nav-dropdown {
            position: relative;
            display: inline-block;
        }

        .nav-dropdown .nav-link {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .dropdown-arrow {
            font-size: 10px;
            transition: transform 0.3s ease;
        }


        /* Dropdown */

        .nav-dropdown .dropdown-menu {
            display: block !important;

            position: absolute;
            top: calc(100% + 5px);
            left: 50%;

            width: 210px;

            padding: 8px 0;
            margin: 0;

            background: #fff;

            border: 1px solid #eee;
            border-radius: 10px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);

            opacity: 0;
            visibility: hidden;
            pointer-events: none;

            transform: translateX(-50%) translateY(10px);

            transition: all 0.25s ease;

            z-index: 99999;
        }


        /* Dropdown links */

        .nav-dropdown .dropdown-menu a {
            display: block;

            padding: 11px 18px;

            color: #333;
            text-decoration: none;

            font-size: 13px;
        }

        .nav-dropdown .dropdown-menu a:hover {
            background: #faf3f4;
            color: #73122e;
        }


        /* OPEN */

        .nav-dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;

            transform: translateX(-50%) translateY(0);
        }


        /* Arrow */

        .nav-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }


        /* =========================================
   RIGHT SECTION
========================================= */

        .navbar-right {
            display: flex;
            align-items: center;

            gap: 18px;

            flex-shrink: 0;
        }


        /* =========================================
   SEARCH
========================================= */

        /* .search-box {
            width: 200px;
            height: 37px;

            border: 1px solid #dddddd;
            border-radius: 20px;

            background: #fff;

            display: flex;
            align-items: center;

            padding-left: 14px;
        } */

        .search-box input {
            width: 100%;
            height: 100%;

            border: none;
            outline: none;

            background: transparent;

            font-size: 10px;
            color: #333;
        }

        .search-box input::placeholder {
            color: #777;
        }

        .search-btn {
            width: 38px;
            height: 100%;

            border: none;
            background: transparent;

            font-size: 25px;

            cursor: pointer;
        }


        /* =========================================
   ICONS
========================================= */

        .nav-icon {
            position: relative;

            color: #222;
            text-decoration: none;

            font-size: 30px;

            width: 24px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon:hover {
            color: #e90909;
        }

        .icon-count {
            position: absolute;

            top: -2px;
            right: -8px;

            min-width: 14px;
            height: 14px;

            padding: 0 3px;

            border-radius: 50%;

            background: #000000;
            color: #fff;

            font-size: 8px;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* =========================================
   MOBILE BUTTON
========================================= */

        .mobile-menu-btn {
            display: none;

            border: none;
            background: transparent;

            font-size: 25px;

            cursor: pointer;
        }


        /* =========================================
   TABLET
========================================= */

        @media (max-width: 1200px) {

            .navbar-container {
                padding: 0 20px;
                gap: 15px;
            }

            .navbar-menu {
                gap: 15px;
            }

            .nav-link {
                font-size: 11px;
            }

            .search-box {
                width: 150px;
            }

            .navbar-right {
                gap: 12px;
            }
        }


        /* =========================================
   MOBILE
========================================= */

        @media (max-width: 991px) {

            .main-navbar {
                height: 65px;
            }

            .navbar-container {
                padding: 0 15px;
            }

            .navbar-menu,
            .navbar-right {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
                /* margin-left: auto; */
            }

            .logo-name {
                font-size: 20px;
            }
        }


        /* =========================================
   SMALL MOBILE
========================================= */

        @media (max-width: 480px) {

            .main-navbar {
                height: 60px;
            }

            .navbar-container {
                padding: 0 12px;
            }

            .logo-icon {
                width: 32px;
                font-size: 30px;
            }

            .logo-name {
                font-size: 18px;
            }

            .logo-tagline {
                font-size: 6px;
                letter-spacing: 1.5px;
            }
        }
    </style>

</head>

<body>
    <div class="sudheera-fixed-header">
        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="top-bar-container">

                <!-- Announcement -->
                <div class="top-bar-message">

                    <span>🍽️ Delicious Food, Just a Click Away</span>

                    <span class="separator">|</span>

                    <span>Freshly Prepared Meals</span>

                    <span class="separator">|</span>

                    <span>Discover Your Next Favourite</span>

                </div>

                <!-- Right Links -->
                <div class="top-bar-right">

                    <a href="{{ route('track-order') }}">Track Order</a>

                    <span class="separator">|</span>

                    {{-- <a href="#">Help</a> --}}
                    {{-- <span class="separator">|</span> --}}

                    <a href="{{ route('contactus') }}">Contact</a>

                    <span class="separator">|</span>

                    <a href="#" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#" aria-label="YouTube">
                        <i class="fa-brands fa-youtube"></i>
                    </a>

                    <a href="#" aria-label="Pinterest">
                        <i class="fa-brands fa-pinterest-p"></i>
                    </a>

                </div>

            </div>
        </div>



        <!-- =========================================
     PAGE LOADER
========================================= -->

        <!-- =========================================
     SUDHEERA SAREES - HOME PAGE LOADER
========================================= -->

        <!-- =========================================
     SUDHEERA SAREES - PREMIUM INTRO
========================================= -->

        <!-- =========================================
     SUDHEERA SAREES - PREMIUM LOADER
========================================= -->


        <!-- <div id="pageLoader">

            <div class="loader-light"></div>

            <div class="loader-content">

                <div class="loader-flower">

                    <img src="{{ asset('website') }}/images/logobaka.png" alt="BAKASURAN Food">

                </div>

                <div class="loader-brand">
                    BAKASURAN
                </div>

                <div class="loader-sarees">
                    FOOD
                </div>

                <div class="loader-divider">
                    <span></span>
                </div>

                <div class="loader-tagline">
                    BIG FLAVOURS. UNFORGETTABLE CRAVINGS.
                </div>

            </div>

        </div> -->

<!-- 
        <style>
            /* =========================================
   SUDHEERA SAREES
   LUXURY LOADER
========================================= */

            #pageLoader {

                position: fixed;

                inset: 0;

                width: 100%;
                height: 100vh;

                background: #000;

                display: flex;

                align-items: center;
                justify-content: center;

                overflow: hidden;

                z-index: 999999;

                opacity: 1;

                visibility: visible;

                transition:
                    opacity 1.2s cubic-bezier(.65, 0, .35, 1),
                    visibility 1.2s;
            }


            /* =========================================
   MOVING LIGHT
========================================= */

            .loader-light {

                position: absolute;

                top: -20%;

                left: -40%;

                width: 35%;

                height: 140%;

                background:
                    linear-gradient(90deg,
                        transparent,
                        rgba(190, 150, 85, .10),
                        rgba(255, 255, 255, .7),
                        rgba(190, 150, 85, .10),
                        transparent);

                transform: skewX(-18deg);

                animation:
                    luxuryLight 2.8s ease-in-out .3s forwards;
            }


            @keyframes luxuryLight {

                0% {
                    left: -40%;
                }

                100% {
                    left: 110%;
                }

            }


            /* =========================================
   CONTENT
========================================= */

            .loader-content {

                position: relative;

                z-index: 5;

                display: flex;

                flex-direction: column;

                align-items: center;

                justify-content: center;

                text-align: center;

                transform: translateY(0);

                animation:
                    contentExit 1.1s cubic-bezier(.65, 0, .35, 1) 3.2s forwards;
            }


            /* =========================================
   FLOWER
========================================= */

            .loader-flower {

                position: relative;

                width: 110px;

                height: 110px;

                display: flex;

                align-items: center;

                justify-content: center;

                opacity: 0;

                transform:
                    scale(.65) translateY(20px);

                animation:
                    flowerReveal 1.4s cubic-bezier(.16, 1, .3, 1) .15s forwards;
            }


            .loader-flower img {

                width: 100%;

                height: 100%;

                object-fit: contain;

                display: block;

                filter:
                    drop-shadow(0 8px 18px rgba(91, 32, 41, .10));
            }


            /* =========================================
   FLOWER HALO
========================================= */

            .loader-flower::after {

                content: "";

                position: absolute;

                width: 75px;

                height: 75px;

                border-radius: 50%;

                background:
                    rgba(183, 143, 78, .12);

                filter: blur(25px);

                z-index: -1;

                animation:
                    haloPulse 2s ease-in-out .5s infinite;
            }


            @keyframes haloPulse {

                0% {
                    transform: scale(.7);

                    opacity: .25;
                }

                50% {
                    transform: scale(1.15);

                    opacity: .65;
                }

                100% {
                    transform: scale(.7);

                    opacity: .25;
                }

            }


            /* =========================================
   FLOWER REVEAL
========================================= */

            @keyframes flowerReveal {

                0% {

                    opacity: 0;

                    transform:
                        scale(.65) translateY(20px);
                }

                55% {

                    opacity: 1;

                    transform:
                        scale(1.06) translateY(-3px);
                }

                100% {

                    opacity: 1;

                    transform:
                        scale(1) translateY(0);
                }

            }


            /* =========================================
   BRAND
========================================= */

            .loader-brand {

                margin-top: 17px;

                font-family:
                    "Times New Roman",
                    Georgia,
                    serif;

                font-size: 38px;

                font-weight: 500;

                letter-spacing: 13px;

                color: #5d202b;

                opacity: 0;

                transform:
                    translateY(15px);

                animation:
                    brandReveal 1.3s cubic-bezier(.16, 1, .3, 1) .9s forwards;
            }


            @keyframes brandReveal {

                0% {

                    opacity: 0;

                    transform:
                        translateY(15px);

                    letter-spacing: 20px;
                }

                100% {

                    opacity: 1;

                    transform:
                        translateY(0);

                    letter-spacing: 9px;
                }

            }


            /* =========================================
   SAREES
========================================= */

            .loader-sarees {

                margin-top: 3px;

                font-family:
                    Arial,
                    sans-serif;

                font-size: 10px;

                font-weight: 400;

                letter-spacing: 8px;

                color: #aa8042;

                opacity: 0;

                transform:
                    translateY(8px);

                animation:
                    sareesReveal 1s ease 1.35s forwards;
            }


            @keyframes sareesReveal {

                0% {

                    opacity: 0;

                    transform:
                        translateY(8px);

                    letter-spacing: 13px;
                }

                100% {

                    opacity: 1;

                    transform:
                        translateY(0);

                    letter-spacing: 7px;
                }

            }


            /* =========================================
   DIVIDER
========================================= */

            .loader-divider {

                width: 150px;

                height: 1px;

                margin-top: 18px;

                background:
                    rgba(93, 32, 43, .12);

                overflow: hidden;

                opacity: 0;

                animation:
                    dividerReveal .5s ease 1.65s forwards;
            }


            .loader-divider span {

                display: block;

                width: 0;

                height: 100%;

                background:
                    linear-gradient(90deg,
                        transparent,
                        #b18a4b,
                        transparent);

                animation:
                    dividerSweep 1.2s ease 1.7s forwards;
            }


            @keyframes dividerReveal {

                to {
                    opacity: 1;
                }

            }


            @keyframes dividerSweep {

                0% {
                    width: 0;
                }

                100% {
                    width: 100%;
                }

            }


            /* =========================================
   TAGLINE
========================================= */

            .loader-tagline {

                margin-top: 10px;

                font-family:
                    Arial,
                    sans-serif;

                font-size: 8px;

                letter-spacing: 4px;

                color: #81736d;

                opacity: 0;

                transform:
                    translateY(7px);

                animation:
                    taglineReveal .9s ease 1.9s forwards;
            }


            @keyframes taglineReveal {

                0% {

                    opacity: 0;

                    transform:
                        translateY(7px);
                }

                100% {

                    opacity: 1;

                    transform:
                        translateY(0);
                }

            }


            /* =========================================
   FINAL EXIT
========================================= */

            @keyframes contentExit {

                0% {

                    opacity: 1;

                    transform:
                        scale(1) translateY(0);
                }

                70% {

                    opacity: 1;

                    transform:
                        scale(1.025) translateY(-3px);
                }

                100% {

                    opacity: 0;

                    transform:
                        scale(1.08) translateY(-8px);
                }

            }


            /* =========================================
   MOBILE
========================================= */

            @media (max-width: 600px) {

                .loader-flower {

                    width: 82px;

                    height: 82px;
                }


                .loader-brand {

                    margin-top: 14px;

                    font-size: 27px;

                    letter-spacing: 7px;
                }


                .loader-sarees {

                    font-size: 8px;

                    letter-spacing: 6px;
                }


                .loader-divider {

                    width: 110px;

                    margin-top: 14px;
                }


                .loader-tagline {

                    font-size: 6px;

                    letter-spacing: 2.5px;
                }

            }


            /* =========================================
   SMALL MOBILE
========================================= */

            @media (max-width: 400px) {

                .loader-flower {

                    width: 72px;

                    height: 72px;
                }


                .loader-brand {

                    font-size: 23px;

                    letter-spacing: 5px;
                }


                .loader-sarees {

                    font-size: 7px;

                    letter-spacing: 5px;
                }


                .loader-divider {

                    width: 95px;
                }


                .loader-tagline {

                    font-size: 5px;

                    letter-spacing: 2px;
                }

            }
        </style> -->


        {{-- BAKASURAN CINEMATIC PAGE LOADER --}}
<div id="pageLoader" aria-label="Loading BAKASURAN" role="status">

    <div class="bakasuran-loader-bg"></div>
    <div class="bakasuran-loader-grid"></div>

    <div class="bakasuran-loader-light"></div>

    <div class="bakasuran-loader-content">

        {{-- Animated Logo --}}
        <div class="bakasuran-loader-logo-wrap">

            <div class="bakasuran-loader-ring"></div>
            <div class="bakasuran-loader-ring bakasuran-loader-ring-two"></div>

            <div class="bakasuran-loader-logo">
                <img
                    src="{{ asset('website/images/logobaka.png') }}"
                    alt="BAKASURAN"
                >
            </div>

        </div>

        {{-- Brand --}}
        <h1 class="bakasuran-loader-brand">
            BAKASURAN
        </h1>

        <div class="bakasuran-loader-subbrand">
            RESTAURANT COMMERCE PLATFORM
        </div>

        <div class="bakasuran-loader-divider">
            <span></span>
        </div>

        <p class="bakasuran-loader-tagline">
            BIG FLAVOURS. UNFORGETTABLE CRAVINGS.
        </p>

        {{-- Loading indicator --}}
        <div class="bakasuran-loader-progress">
            <span></span>
        </div>

        <div class="bakasuran-loader-status">
            PREPARING YOUR EXPERIENCE
            <span class="bakasuran-loader-dots">...</span>
        </div>

    </div>

</div>


<style>
/* ==========================================
   BAKASURAN CINEMATIC LOADER
========================================== */

#pageLoader {
    position: fixed;
    inset: 0;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    isolation: isolate;
    background: #050303;
    opacity: 1;
    visibility: visible;
     transition:
        opacity 4s ease-in-out,
        visibility 4s ease-in-out;
}


#pageLoader.loader-hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

/* Background glow */

.bakasuran-loader-bg {
    position: absolute;
    inset: -20%;
    z-index: -3;
    background:
        radial-gradient(
            ellipse at 50% 45%,
            rgba(135, 0, 0, 0.30),
            transparent 43%
        ),
        radial-gradient(
            circle at 15% 80%,
            rgba(255, 0, 0, 0.10),
            transparent 30%
        ),
        radial-gradient(
            circle at 85% 15%,
            rgba(255, 45, 0, 0.10),
            transparent 30%
        ),
        #050303;
    animation: bakasuranBgPulse 4s ease-in-out infinite;
}

@keyframes bakasuranBgPulse {
    0%, 100% {
        transform: scale(1);
        filter: brightness(0.85);
    }

    50% {
        transform: scale(1.06);
        filter: brightness(1.15);
    }
}

/* Subtle background grid */

.bakasuran-loader-grid {
    position: absolute;
    inset: 0;
    z-index: -2;
    opacity: 0.12;
    background-image:
        linear-gradient(
            rgba(255, 0, 0, 0.15) 1px,
            transparent 1px
        ),
        linear-gradient(
            90deg,
            rgba(255, 0, 0, 0.15) 1px,
            transparent 1px
        );
    background-size: 55px 55px;
    mask-image: radial-gradient(
        ellipse at center,
        black,
        transparent 75%
    );
}

/* Cinematic light sweep */

.bakasuran-loader-light {
    position: absolute;
    top: -30%;
    left: -60%;
    width: 35%;
    height: 160%;
    z-index: -1;
    pointer-events: none;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 0, 0, 0.04),
        rgba(255, 255, 255, 0.12),
        rgba(255, 0, 0, 0.08),
        transparent
    );
    transform: skewX(-20deg);
    animation: bakasuranLightSweep 3.5s ease-in-out infinite;
}

@keyframes bakasuranLightSweep {
    0% {
        left: -60%;
    }

    100% {
        left: 130%;
    }
}

/* Main content */

.bakasuran-loader-content {
    width: min(90%, 600px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 25px 15px;
    animation: bakasuranContentReveal 1s cubic-bezier(.16, 1, .3, 1) both;
}

@keyframes bakasuranContentReveal {
    from {
        opacity: 0;
        transform: translateY(22px) scale(0.96);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Logo and rings */

.bakasuran-loader-logo-wrap {
    position: relative;
    width: 205px;
    height: 205px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
}

.bakasuran-loader-ring,
.bakasuran-loader-ring-two {
    position: absolute;
    inset: 3px;
    border: 1px solid rgba(255, 0, 0, 0.8);
    border-radius: 50%;
    box-shadow:
        0 0 12px rgba(255, 0, 0, 0.18),
        inset 0 0 12px rgba(255, 0, 0, 0.08);
    animation: bakasuranRingPulse 2.5s ease-in-out infinite;
}

.bakasuran-loader-ring-two {
    inset: 13px;
    border-color: rgba(255, 45, 20, 0.4);
    animation-delay: 0.7s;
}

@keyframes bakasuranRingPulse {
    0%, 100% {
        transform: scale(0.97);
        opacity: 0.45;
    }

    50% {
        transform: scale(1.04);
        opacity: 1;
    }
}

.bakasuran-loader-logo {
    position: relative;
    z-index: 2;
    width: 170px;
    height: 170px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bakasuranLogoFloat 3s ease-in-out infinite;
}

.bakasuran-loader-logo img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter:
        drop-shadow(0 0 12px rgba(255, 0, 0, 0.28));
}

@keyframes bakasuranLogoFloat {
    0%, 100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-6px);
    }
}

/* Brand name */

.bakasuran-loader-brand {
    margin: 10px 0 0;
    color: #ffffff;
    font-family: Impact, "Arial Black", sans-serif;
    font-size: clamp(32px, 5vw, 49px);
    font-weight: 900;
    line-height: 1.15;
    letter-spacing: 5px;
    text-shadow:
        0 2px 0 #790000,
        0 0 20px rgba(255, 0, 0, 0.30);
    animation: bakasuranBrandGlow 2.5s ease-in-out infinite;
}

@keyframes bakasuranBrandGlow {
    0%, 100% {
        text-shadow:
            0 2px 0 #790000,
            0 0 12px rgba(255, 0, 0, 0.18);
    }

    50% {
        text-shadow:
            0 2px 0 #790000,
            0 0 25px rgba(255, 0, 0, 0.48);
    }
}

/* Subbrand */

.bakasuran-loader-subbrand {
    margin-top: 8px;
    color: #ff3030;
    font-family: Arial, sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 3px;
}

/* Divider */

.bakasuran-loader-divider {
    position: relative;
    width: 185px;
    height: 2px;
    margin-top: 23px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.12);
}

.bakasuran-loader-divider span {
    position: absolute;
    inset: 0;
    width: 40%;
    background: linear-gradient(
        90deg,
        transparent,
        #ff1010,
        #ffb347,
        #ff1010,
        transparent
    );
    animation: bakasuranDividerSweep 1.5s ease-in-out infinite;
}

@keyframes bakasuranDividerSweep {
    0% {
        transform: translateX(-120%);
    }

    100% {
        transform: translateX(350%);
    }
}

/* Tagline */

.bakasuran-loader-tagline {
    margin: 17px 0 0;
    color: #e6e0e0;
    font-family: Arial, sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 2.5px;
    line-height: 1.7;
}

/* Progress bar */

.bakasuran-loader-progress {
    position: relative;
    width: 180px;
    height: 3px;
    margin-top: 27px;
    overflow: hidden;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.13);
}

.bakasuran-loader-progress span {
    position: absolute;
    inset: 0 auto 0 0;
    width: 35%;
    border-radius: inherit;
    background: linear-gradient(
        90deg,
        #a50000,
        #ff1010,
        #ff9a47
    );
    box-shadow: 0 0 12px rgba(255, 0, 0, 0.65);
    animation: bakasuranProgress 1.5s ease-in-out infinite;
}

@keyframes bakasuranProgress {
    0% {
        transform: translateX(-110%);
    }

    100% {
        transform: translateX(400%);
    }
}

/* Loading status */

.bakasuran-loader-status {
    margin-top: 14px;
    color: #999;
    font-family: Arial, sans-serif;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 2px;
}

.bakasuran-loader-dots {
    display: inline-block;
    width: 15px;
    overflow: hidden;
    vertical-align: bottom;
    animation: bakasuranDots 1s steps(4, end) infinite;
}

@keyframes bakasuranDots {
    0% {
        width: 0;
    }

    100% {
        width: 15px;
    }
}

/* Mobile */

@media (max-width: 600px) {
    .bakasuran-loader-logo-wrap {
        width: 160px;
        height: 160px;
    }

    .bakasuran-loader-logo {
        width: 132px;
        height: 132px;
    }

    .bakasuran-loader-brand {
        font-size: 31px;
        letter-spacing: 3px;
    }

    .bakasuran-loader-subbrand {
        font-size: 8px;
        letter-spacing: 2px;
    }

    .bakasuran-loader-tagline {
        max-width: 290px;
        font-size: 8px;
        letter-spacing: 1.5px;
    }

    .bakasuran-loader-divider {
        width: 145px;
    }
}

@media (max-width: 360px) {
    .bakasuran-loader-logo-wrap {
        width: 135px;
        height: 135px;
    }

    .bakasuran-loader-logo {
        width: 110px;
        height: 110px;
    }

    .bakasuran-loader-brand {
        font-size: 26px;
        letter-spacing: 2px;
    }

    .bakasuran-loader-tagline {
        font-size: 7px;
        letter-spacing: 1px;
    }
}

/* Respect reduced-motion accessibility settings */

@media (prefers-reduced-motion: reduce) {
    #pageLoader *,
    #pageLoader *::before,
    #pageLoader *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
    }
}
</style>


        <!-- NAVBAR -->
        <header class="main-navbar">

            <div class="navbar-container">

                <!-- Logo -->
                <div class="navbar-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('website') }}/images/bakalogo.png" alt="" style="width: 200px;">
                    </a>
                </div>


                <!-- Navigation -->
                <nav class="navbar-menu">

                    {{-- HOME --}}
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Home
                    </a>


                    <!-- <div class="nav-dropdown">

                        <a href="javascript:void(0);" class="nav-link">
                            Sarees

                            <span class="dropdown-arrow">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </a>

                        <div class="dropdown-menu">

                            @foreach($featuredProducts as $product)

                                <a href="{{ route('productdetails', ['slug' => $product->slug]) }}">
                                    {{ $product->title }}
                                </a>

                            @endforeach

                        </div>

                    </div> -->

                    {{-- EXPLORE FOOD --}}
                    <a href="{{ route('shop') }}"
                        class="nav-link {{ request()->routeIs('shop', 'productdetails') ? 'active' : '' }}">
                        Explore Food
                    </a>

                    {{-- RESTAURANTS / ABOUT US --}}
                    <a href="{{ route('aboutus') }}"
                        class="nav-link {{ request()->routeIs('aboutus') ? 'active' : '' }}">
                        About Us
                    </a>

                    {{-- OFFERS --}}
                    <a href="{{ route('offers') }}" class="nav-link {{ request()->routeIs('offers') ? 'active' : '' }}">
                        Offers
                    </a>

                    {{-- FOOD BLOGS --}}
                    <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}">
                        Food Blogs
                    </a>

                    {{-- MOBILE WISHLIST --}}
                    <a href="{{ route('wishlist') }}"
                        class="nav-link mobile-wishlist-link {{ request()->routeIs('wishlist') ? 'active' : '' }}"
                        title="Wishlist" aria-label="Wishlist">
                        Wishlist
                    </a>


                </nav>


                <!-- Right Section -->
                <div class="navbar-right">

                    <!-- Search -->

                    <div class="search-box">
                        <form action="{{ route('shop') }}" method="GET" class="search-form">

                            <input type="text" name="search" placeholder="Search dishes, restaurants..."
                                value="{{ request('search') }}" aria-label="Search dishes and restaurants">

                            <button type="submit" class="search-btn" aria-label="Search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>

                        </form>
                    </div>

                    <style>
                        .search-box {
                            width: 280px;
                            min-width: 220px;
                            flex-shrink: 0;
                        }

                        .search-form {
                            display: flex;
                            align-items: center;
                            width: 100%;
                            height: 46px;
                            padding: 4px;
                            border: 1px solid #e4e4e4;
                            border-radius: 30px;
                            background: #fff;
                            box-sizing: border-box;
                        }

                        .search-form input {
                            flex: 1;
                            width: 0;
                            min-width: 0;
                            height: 100%;
                            padding: 0 12px;
                            border: none;
                            outline: none;
                            background: transparent;
                            font-size: 13px;
                        }

                        .search-form .search-btn {
                            flex: 0 0 36px;
                            width: 36px;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: none;
                            border-radius: 50%;
                            background: #d40000;
                            color: #fff;
                            font-size: 15px;
                            cursor: pointer;
                        }

                        /* Keep header items aligned */
                        .header-actions {
                            display: flex;
                            align-items: center;
                            gap: 18px;
                        }

                        @media (max-width: 992px) {
                            .search-box {
                                width: 240px;
                                min-width: 180px;
                            }
                        }

                        @media (max-width: 576px) {
                            .search-box {
                                width: 100%;
                                min-width: 0;
                            }
                        }
                    </style>



                    <!-- Account -->
                    @if(Auth::guard('customer')->check())
                        <a href="{{ route('account') }}" class="nav-icon" title="My Account">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-icon" title="Login">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @endif


                    <!-- Wishlist -->
                    @if(Auth::guard('customer')->check())

                        @php
                            $customer = Auth::guard('customer')->user();

                            $wishlistCount = \App\Models\WishlistItem::where('user_id', $customer->id)
                                ->count();
                        @endphp

                        <a href="{{ route('wishlist') }}" class="nav-icon wishlist-icon" title="Wishlist">

                            <i class="fa-regular fa-heart"></i>

                            <span class="icon-count">
                                {{ $wishlistCount }}
                            </span>

                        </a>

                    @else

                        <a href="{{ route('login') }}" class="nav-icon wishlist-icon" title="Login">

                            <i class="fa-regular fa-heart"></i>

                            <span class="icon-count">0</span>

                        </a>

                    @endif


                    <!-- Cart -->
                    @if(Auth::guard('customer')->check())

                        @php
                            $cartCount = \App\Models\CartItem::where(
                                'user_id',
                                Auth::guard('customer')->id()
                            )->sum('quantity');
                        @endphp

                        <a href="{{ route('cart') }}" class="nav-icon cart-icon" title="Cart">

                            <i class="fa-solid fa-cart-shopping"></i>

                            <span class="icon-count" id="cart-count">
                                {{ $cartCount }}
                            </span>

                        </a>

                    @else

                        <a href="{{ route('login') }}" class="nav-icon cart-icon" title="Login">

                            <i class="fa-solid fa-cart-shopping"></i>

                            <span class="icon-count" id="cart-count">0</span>

                        </a>

                    @endif

                </div>


                <!-- Mobile Menu -->
                <button class="mobile-menu-btn" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <!-- Mobile Search and Account Icons -->
                <div class="mobile-right-icons"
                    style="display:none; align-items:center; justify-content:center; gap:1px; margin-left:auto;">

                    <!-- Search Icon -->

                    <button type="button" aria-label="Search"
                        onclick="document.getElementById('mobileSearchModal').style.display='flex'; document.body.style.overflow='hidden';"
                        style="transform:scaleX(-1);display:flex;align-items:center;justify-content:center;width:30px;height:38px;padding:0;border:none;background:transparent;color:#650019;font-size:30px;cursor:pointer;margin-bottom:5px;">
                        ⌕
                    </button>

                    <!-- Mobile Search Modal -->
                    <div id="mobileSearchModal"
                        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:999999; align-items:flex-start; justify-content:center; padding:100px 15px 20px;">

                        <div
                            style="position:relative; width:100%; max-width:500px; background:#fff; padding:25px 18px; border-radius:10px; box-shadow:0 5px 25px rgba(0,0,0,0.2);">

                            <!-- Close Button -->
                            <button type="button" aria-label="Close search" onclick="closeMobileSearchModal()"
                                style="position:absolute; right:12px; top:8px; border:none; background:transparent; font-size:28px; color:#650019; cursor:pointer;">
                                &times;
                            </button>

                            <!-- Title -->
                            <h3 style="margin:0 0 18px; color:#650019; font-size:18px;">
                                Search Products
                            </h3>

                            <!-- Search Form -->
                            <form action="{{ route('shop') }}" method="GET" style="display:flex; gap:8px;">

                                <input type="search" name="search" placeholder="Search products..."
                                    aria-label="Search products" required autofocus
                                    style="flex:1; min-width:0; height:44px; padding:0 12px; border:1px solid #ddd; border-radius:5px; font-size:14px; outline:none;">

                                <button type="submit" aria-label="Search"
                                    style="height:44px; padding:0 16px; border:none; border-radius:5px; background:#650019; color:#fff; font-size:16px; cursor:pointer;">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>

                            </form>
                        </div>
                    </div>

                    <!-- Account Icon -->
                    @if(Auth::guard('customer')->check())
                        <a href="{{ route('account') }}" title="My Account" aria-label="My Account"
                            style="display:flex; align-items:center; justify-content:center; width:30px; height:38px; color:#650019; font-size:19px; text-decoration:none;">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" title="Login" aria-label="Login"
                            style="display:flex; align-items:center; justify-content:center; width:30px; height:38px; color:#650019; font-size:19px; text-decoration:none;">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @endif

                    <!-- Mobile Cart Icon -->
                    @if(Auth::guard('customer')->check())

                        @php
                            $cartCount = \App\Models\CartItem::where(
                                'user_id',
                                Auth::guard('customer')->id()
                            )->sum('quantity');
                        @endphp

                        <a href="{{ route('cart') }}" title="My Cart" aria-label="My Cart"
                            style="display:flex; align-items:center; justify-content:center; width:30px; height:38px; color:#650019; font-size:19px; text-decoration:none; position:relative;">

                            <i class="fa-solid fa-cart-shopping"></i>

                            <span id="mobile-cart-count"
                                style="position:absolute; top:0; right:-5px; min-width:15px; height:15px; padding:0 3px; display:flex; align-items:center; justify-content:center; background:#650019; color:#fff; border-radius:50%; font-size:10px; line-height:15px;">
                                {{ $cartCount }}
                            </span>

                        </a>

                    @else

                        <a href="{{ route('login') }}" title="Login" aria-label="Login"
                            style="display:flex; align-items:center; justify-content:center; width:30px; height:38px; color:#650019; font-size:19px; text-decoration:none; position:relative;">

                            <i class="fa-solid fa-cart-shopping"></i>

                            <span id="mobile-cart-count"
                                style="position:absolute; top:0; right:-5px; min-width:15px; height:15px; padding:0 3px; display:flex; align-items:center; justify-content:center; background:#650019; color:#fff; border-radius:50%; font-size:10px; line-height:15px;">
                                0
                            </span>

                        </a>

                    @endif

                </div>
                <style>
                    @media (max-width: 991px) {
                        .mobile-right-icons {
                            display: flex !important;
                        }
                    }
                </style>
                <style>
                    /* =========================================================
   NAVBAR MENU
========================================================= */

                    .navbar-menu {
                        display: flex;
                        align-items: center;
                        gap: 25px;
                        height: 100%;
                    }

                    .navbar-right {
                        display: flex;
                        align-items: center;
                        gap: 18px;
                    }

                    .mobile-menu-btn {
                        display: none;
                    }


                    /* =========================================================
   NORMAL NAV LINKS
========================================================= */

                    .navbar-menu>.nav-link,
                    .nav-dropdown>.nav-link {

                        position: relative;

                        height: 70px;

                        display: flex;
                        align-items: center;

                        color: #222;
                        text-decoration: none;

                        font-size: 15px;
                        font-weight: 600;

                        white-space: nowrap;

                        transition: all 0.25s ease;
                    }

                    .navbar-menu>.nav-link:hover,
                    .nav-dropdown>.nav-link:hover {
                        color: black;
                    }


                    /* =========================================================
   ACTIVE LINK
========================================================= */

                    .nav-link.active {
                        color: red;
                    }

                    .nav-link.active::after {

                        content: "";

                        position: absolute;

                        bottom: 17px;
                        left: 0;

                        width: 100%;
                        height: 2px;

                        background: red;
                    }


                    /* =========================================================
   SAREES DROPDOWN - DESKTOP
========================================================= */

                    .nav-dropdown {

                        position: relative;

                        display: inline-flex;

                        align-items: center;

                        height: 100%;
                    }


                    /* Sarees link */

                    .nav-dropdown>.nav-link {

                        display: flex;

                        align-items: center;

                        justify-content: center;

                        gap: 6px;

                        cursor: pointer;
                    }


                    /* Dropdown arrow */

                    .dropdown-arrow {

                        display: inline-flex;

                        align-items: center;
                        justify-content: center;

                        font-size: 9px;

                        transition: transform 0.3s ease;
                    }


                    /* =========================================================
   DESKTOP DROPDOWN BOX
========================================================= */

                    .nav-dropdown .dropdown-menu {

                        position: absolute;

                        top: calc(100% + 5px);

                        left: 50%;

                        width: 220px;

                        padding: 8px 0;

                        margin: 0;

                        display: block;

                        background: #ffffff;

                        border: 1px solid #eee;

                        border-radius: 12px;

                        box-shadow:
                            0 12px 35px rgba(0, 0, 0, 0.14);

                        opacity: 0;

                        visibility: hidden;

                        pointer-events: none;

                        transform:
                            translateX(-50%) translateY(10px);

                        transition:
                            opacity 0.25s ease,
                            visibility 0.25s ease,
                            transform 0.25s ease;

                        z-index: 99999;
                    }


                    /* =========================================================
   DESKTOP DROPDOWN LINKS
========================================================= */

                    .nav-dropdown .dropdown-menu a {

                        position: relative;

                        display: flex;

                        align-items: center;

                        width: 100%;

                        padding: 12px 18px;

                        color: #3b2525;

                        background: transparent;

                        text-decoration: none;

                        font-size: 13px;

                        font-weight: 500;

                        transition: all 0.25s ease;
                    }


                    /* Small gold icon */

                    .nav-dropdown .dropdown-menu a::before {

                        content: "✦";

                        margin-right: 10px;

                        color: #a98145;

                        font-size: 8px;

                        transition: transform 0.25s ease;
                    }


                    /* Hover */

                    .nav-dropdown .dropdown-menu a:hover {

                        background:
                            linear-gradient(135deg,
                                #fff7f3,
                                #f9eee9);

                        color: #651027;

                        padding-left: 23px;
                    }

                    .nav-dropdown .dropdown-menu a:hover::before {

                        transform: rotate(45deg);

                    }


                    /* =========================================================
   OPEN DROPDOWN
========================================================= */

                    .nav-dropdown.open .dropdown-menu {

                        opacity: 1;

                        visibility: visible;

                        pointer-events: auto;

                        transform:
                            translateX(-50%) translateY(0);
                    }


                    /* Arrow rotation */

                    .nav-dropdown.open .dropdown-arrow {

                        transform: rotate(180deg);

                        color: #651027;
                    }


                    /* =========================================================
   RIGHT SECTION
========================================================= */

                    .navbar-right {

                        display: flex;

                        align-items: center;

                        gap: 18px;

                        flex-shrink: 0;
                    }


                    /* =========================================================
   TABLET
========================================================= */

                    @media (max-width: 1200px) {

                        .navbar-container {

                            padding: 0 20px;

                            gap: 15px;
                        }


                        .navbar-menu {

                            gap: 15px;
                        }


                        .nav-link {

                            font-size: 11px;
                        }


                        .search-box {

                            width: 150px;
                        }


                        .navbar-right {

                            gap: 12px;
                        }


                        .nav-dropdown .dropdown-menu {

                            width: 200px;
                        }


                        .nav-dropdown .dropdown-menu a {

                            font-size: 12px;

                            padding: 11px 15px;
                        }
                    }


                    /* =========================================================
   MOBILE NAVBAR
========================================================= */

                    @media (max-width: 991px) {

                        .main-navbar {

                            height: 65px;

                            position: relative;

                            z-index: 10000;
                        }


                        .navbar-container {

                            width: 100%;

                            padding: 0 15px;

                            position: relative;
                        }


                        /* -----------------------------------------
       HIDE RIGHT DESKTOP SECTION
    ----------------------------------------- */

                        .navbar-right {

                            display: none !important;
                        }


                        /* -----------------------------------------
       MOBILE MENU BUTTON
    ----------------------------------------- */

                        .mobile-menu-btn {

                            width: 42px;

                            height: 42px;

                            display: flex !important;

                            align-items: center;

                            justify-content: center;

                            /* margin-left: auto; */

                            border: 1px solid rgba(101, 16, 39, 0.15);

                            border-radius: 12px;

                            background: #fff8f4;

                            color: #651027;

                            font-size: 19px;

                            cursor: pointer;

                            position: relative;

                            z-index: 10002;

                            transition: all 0.25s ease;
                        }


                        .mobile-menu-btn:hover,
                        .mobile-menu-btn.active {

                            background: #651027;

                            color: #ffffff;

                            border-color: #651027;

                            box-shadow:
                                0 5px 15px rgba(101, 16, 39, 0.20);
                        }


                        .mobile-menu-btn i {

                            transition:
                                transform 0.25s ease;
                        }


                        .mobile-menu-btn.active i {

                            transform: rotate(90deg);
                        }


                        /* =====================================================
       MOBILE MENU
    ===================================================== */

                        .navbar-menu {

                            position: absolute;

                            top: calc(100% + 8px);

                            left: 10px;

                            right: 10px;

                            width: auto;

                            height: auto;

                            max-height:
                                calc(100vh - 85px);

                            padding: 12px;

                            display: none !important;

                            flex-direction: column;

                            align-items: stretch;

                            gap: 3px;

                            background:
                                linear-gradient(145deg,
                                    #fffaf6 0%,
                                    #ffffff 50%,
                                    #fdf2ed 100%);

                            border:
                                1px solid rgba(139, 64, 75, 0.12);

                            border-radius: 18px;

                            box-shadow:
                                0 15px 40px rgba(55, 20, 25, 0.18);

                            overflow-y: auto;

                            z-index: 10001;
                        }


                        /* OPEN MOBILE MENU */

                        .navbar-menu.mobile-open {

                            display: flex !important;

                            animation:
                                mobileMenuOpen 0.25s ease;
                        }


                        @keyframes mobileMenuOpen {

                            from {

                                opacity: 0;

                                transform:
                                    translateY(-10px);
                            }

                            to {

                                opacity: 1;

                                transform:
                                    translateY(0);
                            }
                        }


                        /* =====================================================
       MOBILE NORMAL LINKS
    ===================================================== */

                        .navbar-menu>.nav-link,
                        .navbar-menu>.nav-dropdown>.nav-link {

                            width: 100%;

                            min-height: 50px;

                            height: auto;

                            display: flex;

                            align-items: center;

                            justify-content: space-between;

                            padding: 14px 16px;

                            margin: 0;

                            border-radius: 12px;

                            color: #3b2525;

                            font-size: 13px;

                            font-weight: 600;

                            text-decoration: none;

                            white-space: normal;

                            transition: all 0.25s ease;
                        }


                        .navbar-menu>.nav-link:hover,
                        .navbar-menu>.nav-dropdown>.nav-link:hover {

                            background:
                                linear-gradient(135deg,
                                    #f8e9e3,
                                    #fff4ef);

                            color: black;

                            padding-left: 20px;
                        }


                        /* Remove desktop active underline */

                        .navbar-menu .nav-link.active::after {

                            display: none;
                        }


                        /* =====================================================
       MOBILE SAREES DROPDOWN
    ===================================================== */

                        .nav-dropdown {

                            width: 100%;

                            display: block;

                            height: auto;

                            position: relative;
                        }


                        .nav-dropdown>.nav-link {

                            cursor: pointer;
                        }


                        /* =====================================================
       MOBILE DROPDOWN BOX
    ===================================================== */

                        .nav-dropdown .dropdown-menu {

                            position: static !important;

                            width: 100% !important;

                            display: none !important;

                            padding: 6px;

                            margin: 0 0 5px;

                            background: #fffaf7;

                            border:
                                1px solid #f0dfd8;

                            border-radius: 12px;

                            box-shadow: none;

                            opacity: 1 !important;

                            visibility: visible !important;

                            pointer-events: auto !important;

                            transform: none !important;
                        }


                        /* OPEN */

                        .nav-dropdown.open .dropdown-menu {

                            display: block !important;

                            animation:
                                dropdownOpen 0.2s ease;
                        }


                        @keyframes dropdownOpen {

                            from {

                                opacity: 0;

                                transform:
                                    translateY(-5px);
                            }

                            to {

                                opacity: 1;

                                transform:
                                    translateY(0);
                            }
                        }


                        /* =====================================================
       MOBILE DROPDOWN LINKS
    ===================================================== */

                        .nav-dropdown .dropdown-menu a {

                            display: flex;

                            align-items: center;

                            width: 100%;

                            padding: 11px 13px;

                            border-radius: 8px;

                            color: #694d48;

                            font-size: 12px;

                            text-decoration: none;

                            transition: all 0.2s ease;
                        }


                        .nav-dropdown .dropdown-menu a::before {

                            content: "✦";

                            margin-right: 9px;

                            font-size: 8px;

                            color: #a98145;
                        }


                        .nav-dropdown .dropdown-menu a:hover {

                            background: #f9eee9;

                            color: #651027;

                            padding-left: 18px;
                        }


                        /* =====================================================
       MOBILE DROPDOWN ARROW
    ===================================================== */

                        .dropdown-arrow {

                            width: 28px;

                            height: 28px;

                            display: flex;

                            align-items: center;

                            justify-content: center;

                            flex-shrink: 0;

                            border-radius: 50%;

                            background: #f8ebe5;

                            color: #651027;

                            font-size: 10px;

                            transition: all 0.25s ease;
                        }


                        .nav-dropdown.open .dropdown-arrow {

                            transform: rotate(180deg);

                            background: #651027;

                            color: #ffffff;
                        }
                    }


                    /* =========================================================
   SMALL MOBILE
========================================================= */

                    @media (max-width: 480px) {

                        .main-navbar {

                            height: 60px;
                        }


                        .navbar-container {

                            padding: 0 12px;
                        }


                        .logo-icon {

                            width: 32px;

                            height: 32px;

                            font-size: 30px;
                        }


                        .logo-name {

                            font-size: 18px;
                        }


                        .logo-tagline {

                            font-size: 6px;

                            letter-spacing: 1.5px;
                        }


                        .mobile-menu-btn {

                            width: 39px;

                            height: 39px;

                            border-radius: 10px;

                            font-size: 17px;
                        }


                        .navbar-menu {

                            left: 7px;

                            right: 7px;

                            top: calc(100% + 6px);

                            padding: 9px;

                            border-radius: 16px;

                            max-height:
                                calc(100vh - 75px);
                        }


                        .navbar-menu>.nav-link,
                        .navbar-menu>.nav-dropdown>.nav-link {

                            min-height: 47px;

                            padding: 12px 14px;

                            font-size: 12px;
                        }


                        .nav-dropdown .dropdown-menu a {

                            padding: 10px 11px;

                            font-size: 11px;
                        }
                    }
                </style>
                {{--
                Your existing HTML for the navigation can remain exactly as it is.

                The important addition is the desktop dropdown section:

                .nav-dropdown .dropdown-menu {
                position: absolute;
                top: calc(100% + 5px);
                left: 50%;
                width: 220px;
                ...
                }

                and:

                .nav-dropdown.open .dropdown-menu {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: translateX(-50%) translateY(0);
                } --}}

            </div>

        </header>
    </div>

    @yield('content')
    {{-- @include('sweetalert::alert') --}}

    <!-------footer----->
    <style>
        /* =========================================
   SUDHEERA FOOTER
========================================= */

        .sudheera-footer {
            width: 100%;

            margin-top: 30px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #430014;
        }


        /* =========================================
   NEWSLETTER AREA
========================================= */

        .footer-newsletter {
            position: relative;

            width: 100%;

            min-height: 105px;

            padding: 14px 48px 15px 50px;

            display: flex;

            align-items: center;

            gap: 25px;

            overflow: hidden;

            background:
                linear-gradient(90deg,
                    #470015 0%,
                    #65001e 48%,
                    #4c0016 100%);
        }


        /* =========================================
   LOTUS
========================================= */

        .footer-lotus {
            flex-shrink: 0;

            width: 42px;
            height: 55px;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #d6a94b;

            font-size: 40px;

            opacity: 0.95;
        }


        /* =========================================
   NEWSLETTER CONTENT
========================================= */

        .newsletter-content {
            position: relative;

            z-index: 3;

            width: 390px;

            flex-shrink: 0;
        }

        .newsletter-content h2 {
            margin: 0;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 22px;

            line-height: 1.15;

            font-weight: 600;

            color: #e6bd67;
        }

        .newsletter-content p {
            margin: 2px 0 7px;

            font-size: 10px;

            line-height: 1.3;

            color: rgba(255,
                    240,
                    220,
                    0.82);
        }


        /* =========================================
   SUBSCRIBE FORM
========================================= */

        .subscribe-form {
            width: 355px;

            height: 30px;

            display: flex;

            overflow: hidden;

            border-radius: 3px;

            background: #fff;
        }

        .subscribe-form input {
            flex: 1;

            min-width: 0;

            height: 100%;

            padding: 0 11px;

            border: none;

            outline: none;

            font-size: 8px;

            color: #332c29;

            background: #fff;
        }

        .subscribe-form input::placeholder {
            color: #817773;
        }

        .subscribe-form button {
            width: 98px;

            height: 100%;

            border: none;

            outline: none;

            cursor: pointer;

            font-size: 7px;

            font-weight: 700;

            letter-spacing: 0.2px;

            color: #fff;

            background:
                linear-gradient(90deg,
                    #a66b20,
                    #c58b36);

            transition:
                background 0.25s ease,
                transform 0.2s ease;
        }

        .subscribe-form button:hover {
            background:
                linear-gradient(90deg,
                    #bd7c27,
                    #d49b46);
        }

        .subscribe-form button:active {
            transform: scale(0.98);
        }

        .subscribe-form button span {
            margin-left: 7px;

            font-size: 10px;
        }


        /* =========================================
   FOOTER HIGHLIGHTS
========================================= */

        .footer-highlights {
            position: relative;

            z-index: 3;

            margin-left: auto;

            padding-right: 55px;

            display: flex;

            align-items: center;

            gap: 28px;
        }

        .footer-highlight {
            display: flex;

            align-items: center;

            gap: 6px;

            color: rgba(255,
                    236,
                    204,
                    0.9);

            white-space: nowrap;
        }

        .highlight-icon {
            width: 30px;
            height: 30px;

            display: flex;

            align-items: center;
            justify-content: center;

            border: 1px solid rgba(213,
                    171,
                    86,
                    0.5);

            border-radius: 50%;

            color: #d7aa51;

            font-size: 20px;
        }

        .footer-highlight span {
            font-size: 12px;

            font-weight: 500;
        }


        /* =========================================
   DECORATIVE SAREE / FLORAL SIDE
========================================= */

        .footer-saree-decoration {
            position: absolute;

            z-index: 1;

            right: -10px;

            bottom: -55px;

            width: 270px;

            height: 140px;

            border-radius: 50% 0 0 0;

            transform:
                rotate(-7deg);

            opacity: 0.95;

            background:
                radial-gradient(ellipse at 75% 15%,
                    rgba(255, 222, 165, 0.95) 0 5%,
                    transparent 6%),
                radial-gradient(ellipse at 60% 35%,
                    rgba(209, 155, 68, 0.9) 0 4%,
                    transparent 5%),
                linear-gradient(135deg,
                    #9c1534,
                    #650019 45%,
                    #3d0010 100%);

            box-shadow:
                -25px -12px 35px rgba(193, 126, 38, 0.22);
        }


        /* =========================================
   FOOTER BOTTOM
========================================= */

        .footer-bottom {
            width: 100%;

            padding: 12px 50px;

            /* background: #30000e; */

            background: #000;

            border-top: 1px solid rgba(216,
                    173,
                    86,
                    0.12);
        }

        .footer-bottom-inner {
            max-width: 1200px;

            margin: auto;

            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .footer-bottom p {
            margin: 0;

            font-size: 11px;

            color: rgba(255,
                    238,
                    216,
                    0.55);
        }

        .footer-links {
            display: flex;

            gap: 20px;
        }

        .footer-links a {
            text-decoration: none;

            font-size: 11px;

            color: rgba(255,
                    238,
                    216,
                    0.65);

            transition:
                color 0.2s ease;
        }

        .footer-links a:hover {
            color: #e1b85e;
        }


        /* =========================================
   TABLET
========================================= */

        @media (max-width: 1000px) {

            .footer-newsletter {
                padding-left: 30px;
                padding-right: 30px;

                gap: 15px;
            }

            .newsletter-content {
                width: 350px;
            }

            .subscribe-form {
                width: 330px;
            }

            .footer-highlights {
                gap: 15px;

                padding-right: 20px;
            }

            .footer-highlight span {
                font-size: 7px;
            }

        }


        /* =========================================
   MOBILE
========================================= */

        @media (max-width: 700px) {

            .footer-newsletter {
                min-height: auto;

                padding: 24px 18px;

                flex-direction: column;

                align-items: flex-start;

                gap: 16px;
            }

            .footer-lotus {
                position: absolute;

                top: 12px;

                right: 18px;

                width: 35px;
                height: 35px;

                font-size: 30px;
            }

            .newsletter-content {
                width: 100%;
            }

            .newsletter-content h2 {
                font-size: 19px;
            }

            .newsletter-content p {
                font-size: 9px;

                max-width: 300px;
            }

            .subscribe-form {
                width: 100%;

                max-width: 430px;

                height: 35px;
            }

            .subscribe-form input {
                font-size: 10px;
            }

            .subscribe-form button {
                width: 105px;

                font-size: 8px;
            }

            .footer-highlights {
                width: 100%;

                margin: 0;

                padding: 0;

                display: grid;

                grid-template-columns:
                    repeat(2, 1fr);

                gap: 12px;
            }

            .footer-highlight {
                gap: 7px;
            }

            .footer-highlight span {
                font-size: 9px;
            }

            .footer-saree-decoration {
                right: -80px;

                opacity: 0.3;
            }

            .footer-bottom {
                padding: 15px 18px;
            }

            .footer-bottom-inner {
                flex-direction: column;

                gap: 10px;

                text-align: center;
            }

            .footer-links {
                gap: 12px;

                flex-wrap: wrap;

                justify-content: center;
            }

        }


        /* =========================================
   SMALL MOBILE
========================================= */

        @media (max-width: 400px) {

            .subscribe-form button {
                width: 90px;
            }

            .subscribe-form button span {
                margin-left: 3px;
            }

            .footer-highlight span {
                font-size: 8px;
            }

        }
    </style>
    <style>
        @media (max-width: 767px) {
            .newsletter-content {
                margin-left: 0 !important;
            }
        }
    </style>

    <footer class="sudheera-footer">

        {{-- <div class="footer-newsletter">



            <!-- Newsletter Content -->
            <div class="newsletter-content" style="margin-left: 50px;">

                <h2>
                    Join the Sudheera Family
                </h2>

                <p>
                    Subscribe for latest collections, exclusive offers & style inspiration
                </p>

                <form class="subscribe-form">

                    <input type="email" placeholder="Enter your email address" required>

                    <button type="submit">
                        SUBSCRIBE
                        <span>→</span>
                    </button>

                </form>

            </div>


            <!-- Footer Highlights -->
            <div class="footer-highlights">

                <div class="footer-highlight">

                    <div class="highlight-icon">
                        ◉
                    </div>

                    <span>
                        Exclusive Offers
                    </span>

                </div>


                <div class="footer-highlight">

                    <div class="highlight-icon">
                        ◇
                    </div>

                    <span>
                        New Arrivals
                    </span>

                </div>


                <div class="footer-highlight">

                    <div class="highlight-icon">
                        ▣
                    </div>

                    <span>
                        Style Guides
                    </span>

                </div>


                <div class="footer-highlight">

                    <div class="highlight-icon">
                        ♡
                    </div>
                    <span>
                        Wishlist
                    </span>

                </div>

            </div>


            <!-- Decorative saree -->
            <div class="footer-saree-decoration"></div>

        </div> --}}


        <!-- =========================================================
     FOOTER
========================================================= -->

        <!------- FOOTER ------->
        <style>
            /* =========================================
       SUDHEERA FOOTER
    ========================================= */

            .sudheera-footer {
                width: 100%;
                margin-top: 0px;
                background: #2c2118;
                color: #fff;
                font-family: Arial, Helvetica, sans-serif;
            }

            /* =========================================
       NEWSLETTER TOP
    ========================================= */

            .sudheera-footer-newsletter {
                background: linear-gradient(90deg,
                        #470015 0%,
                        #65001e 50%,
                        #470015 100%);

                padding: 28px 0;
            }

            .sudheera-newsletter-inner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 30px;
            }

            .sudheera-newsletter-content h3 {
                margin: 0 0 6px;
                color: #e6bd67;
                font-family: Georgia, "Times New Roman", serif;
                font-size: 24px;
                font-weight: 600;
            }

            .sudheera-newsletter-content p {
                margin: 0;
                color: rgba(255, 240, 220, 0.82);
                font-size: 12px;
                line-height: 1.5;
            }

            .sudheera-newsletter-form {
                display: flex;
                width: 430px;
                max-width: 100%;
                height: 44px;
            }

            .sudheera-newsletter-form input {
                flex: 1;
                min-width: 0;
                border: 0;
                outline: none;
                padding: 0 15px;
                background: #fff;
                color: #333;
                font-size: 12px;
            }

            .sudheera-newsletter-form input::placeholder {
                color: #888;
            }

            .sudheera-newsletter-form button {
                width: 125px;
                border: 0;
                outline: none;
                background: #a96b18;
                color: #fff;
                cursor: pointer;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: .7px;
                transition: .3s ease;
            }

            .sudheera-newsletter-form button:hover {
                background: #875310;
            }


            /* =========================================
       MAIN FOOTER
    ========================================= */

            .sudheera-footer-main {
                padding: 10px 0 10px;

                display: grid;
                grid-template-columns:
                    1.7fr 1fr 1.2fr 1.2fr 1.5fr;

                gap: 45px;
            }


            /* =========================================
       LOGO
    ========================================= */

            .sudheera-footer-logo {
                display: inline-block;
                margin-bottom: 18px;

                color: #fff;
                text-decoration: none;

                font-family: Georgia, "Times New Roman", serif;
                font-size: 27px;
                letter-spacing: 1.5px;
            }

            .sudheera-footer-logo span {
                display: block;

                margin-top: -2px;

                color: #c99a59;

                font-family: inherit;
                font-size: 11px;
                letter-spacing: 4px;
            }


            /* =========================================
       ABOUT
    ========================================= */

            .sudheera-footer-about p {
                max-width: 320px;

                margin: 0;

                color: #c7beb5;

                font-size: 13px;
                line-height: 1.8;
            }


            /* =========================================
       FOOTER HEADINGS
    ========================================= */

            .sudheera-footer-col h4 {
                margin: 5px 0 22px;

                color: #fff;

                font-size: 15px;
                font-weight: 500;
            }


            /* =========================================
       LINKS
    ========================================= */

            .sudheera-footer-col ul {
                padding: 0;
                margin: 0;
                list-style: none;
            }

            .sudheera-footer-col ul li {
                margin-bottom: 12px;
            }

            .sudheera-footer-col ul li a {
                color: #c7beb5;

                text-decoration: none;

                font-size: 13px;

                transition: .3s ease;
            }

            .sudheera-footer-col ul li a:hover {
                color: #c99a59;
                padding-left: 4px;
            }


            /* =========================================
       SOCIAL ICONS
    ========================================= */

            .sudheera-social {
                display: flex;
                gap: 9px;

                margin-top: 22px;
            }

            .sudheera-social a {
                width: 36px;
                height: 36px;

                display: flex;
                align-items: center;
                justify-content: center;

                border: 1px solid #66584c;
                border-radius: 50%;

                color: #d4c5b6;
                text-decoration: none;

                transition: .3s ease;
            }

            .sudheera-social a:hover {
                background: #a96b18;
                border-color: #a96b18;
                color: #fff;
            }


            /* =========================================
       CONTACT
    ========================================= */

            .sudheera-contact-item {
                display: flex;
                align-items: flex-start;

                gap: 11px;

                margin-bottom: 17px;
            }

            .sudheera-contact-icon {
                width: 20px;
                flex-shrink: 0;

                color: #c99a59;

                font-size: 15px;
            }

            .sudheera-contact-item p,
            .sudheera-contact-item a {
                margin: 0;

                color: #c7beb5;

                font-size: 12px;
                line-height: 1.7;

                text-decoration: none;
            }

            .sudheera-contact-item a:hover {
                color: #c99a59;
            }


            /* =========================================
       FOOTER BOTTOM
    ========================================= */

            .sudheera-footer-bottom {
                min-height: 65px;

                border-top: 1px solid #51463d;

                display: flex;
                align-items: center;
                justify-content: space-between;

                gap: 20px;
            }

            .sudheera-footer-bottom p {
                margin: 0;

                color: #958b82;

                font-size: 11px;
            }

            .sudheera-footer-payment {
                display: flex;
                align-items: center;

                gap: 10px;

                color: #958b82;

                font-size: 11px;
            }


            /* =========================================
       TABLET
    ========================================= */

            @media (max-width: 1100px) {

                .sudheera-footer-main {
                    grid-template-columns:
                        1.5fr 1fr 1fr 1fr;

                    gap: 35px;
                }

                .sudheera-footer-contact {
                    grid-column: span 2;
                }

            }


            /* =========================================
       MOBILE
    ========================================= */

            @media (max-width: 767px) {

                .sudheera-footer-newsletter {
                    padding: 30px 0;
                }

                .sudheera-newsletter-inner {
                    flex-direction: column;
                    align-items: flex-start;

                    gap: 18px;
                }

                .sudheera-newsletter-content h3 {
                    font-size: 21px;
                }

                .sudheera-newsletter-content p {
                    font-size: 11px;
                }

                .sudheera-newsletter-form {
                    width: 100%;
                    height: 42px;
                }

                .sudheera-newsletter-form button {
                    width: 110px;
                }


                .sudheera-footer-main {
                    grid-template-columns: repeat(2, 1fr);

                    gap: 35px 25px;

                    padding: 45px 0 35px;
                }

                .sudheera-footer-about {
                    grid-column: span 2;
                }

                .sudheera-footer-contact {
                    grid-column: span 2;
                }

                .sudheera-footer-about p {
                    max-width: 100%;
                }


                .sudheera-footer-bottom {
                    min-height: auto;

                    padding: 20px 0;

                    flex-direction: column;

                    justify-content: center;

                    text-align: center;

                    gap: 10px;
                }

                .sudheera-footer-payment {
                    justify-content: center;

                    flex-wrap: wrap;
                }

            }


            /* =========================================
       SMALL MOBILE
    ========================================= */

            @media (max-width: 480px) {

                .sudheera-footer-main {
                    grid-template-columns: 1fr;

                    gap: 28px;
                }

                .sudheera-footer-about,
                .sudheera-footer-contact {
                    grid-column: span 1;
                }

                .sudheera-footer-col h4 {
                    margin-bottom: 15px;
                }

                .sudheera-newsletter-form {
                    height: 40px;
                }

                .sudheera-newsletter-form input {
                    font-size: 11px;
                }

                .sudheera-newsletter-form button {
                    width: 100px;
                    font-size: 10px;
                }

            }
        </style>


        <footer class="sudheera-footer">

            <!-- =========================================
         MAIN FOOTER
    ========================================= -->

            <div class="container">

                <div class="sudheera-footer-main">

                    <!-- ABOUT -->

                    <div class="sudheera-footer-col sudheera-footer-about">

                        <a href="{{ route('home') }}" class="sudheera-footer-logo">

                            <img src="{{ asset('website') }}/images/logobaka.png" alt="BAKASURAN Food"
                                style="width: 200px; justify-content: center;">

                        </a>

                        <p>
                            Discover delicious food and your next favourite meal with BAKASURAN. Enjoy every craving,
                            every day.
                        </p>

                        <div class="sudheera-social">

                            <a href="#" aria-label="Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>

                            <a href="#" aria-label="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>

                            <a href="#" aria-label="YouTube">
                                <i class="fa-brands fa-youtube"></i>
                            </a>

                            <a href="#" aria-label="Pinterest">
                                <i class="fa-brands fa-pinterest-p"></i>
                            </a>

                        </div>

                    </div>

                    <!-- QUICK LINKS -->

                    <div class="sudheera-footer-col">

                        <h4>
                            Quick Links
                        </h4>

                        <ul>

                            <li>
                                <a href="{{ route('home') }}">
                                    Home
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('shop') }}">
                                    Explore Food
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('aboutus') }}">
                                    About BAKASURAN
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('blog') }}">
                                    Food Blogs
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('contactus') }}">
                                    Contact Us
                                </a>
                            </li>

                        </ul>

                    </div>

                    <!-- CUSTOMER SERVICE -->

                    <div class="sudheera-footer-col">

                        <h4>
                            Customer Service
                        </h4>

                        <ul>

                            <li>
                                <a href="{{ route('account') }}">
                                    My Account
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('wishlist') }}">
                                    My Wishlist
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cart') }}">
                                    My Cart
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('shippingdelivery') }}">
                                    Delivery Information
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('returnexchange') }}">
                                    Cancellation & Refunds
                                </a>
                            </li>

                        </ul>

                    </div>

                    <!-- INFORMATION -->

                    <div class="sudheera-footer-col">

                        <h4>
                            Information
                        </h4>

                        <ul>

                            <li>
                                <a href="{{ route('privacypolicy') }}">
                                    Privacy Policy
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('terms') }}">
                                    Terms & Conditions
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('faq') }}">
                                    FAQs
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('contactus') }}">
                                    Contact Support
                                </a>
                            </li>

                        </ul>

                    </div>

                    <!-- CONTACT -->

                    <div class="sudheera-footer-col sudheera-footer-contact">

                        <h4>
                            Get In Touch
                        </h4>

                        <div class="sudheera-contact-item">

                            <span class="sudheera-contact-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>

                            <p>
                                BAKASURAN Food<br>
                                Add your registered business address<br>
                                India
                            </p>

                        </div>

                        <div class="sudheera-contact-item">

                            <span class="sudheera-contact-icon">
                                <i class="fa-solid fa-phone"></i>
                            </span>

                            <a href="{{ route('contactus') }}">
                                Customer Support
                            </a>

                        </div>

                        <div class="sudheera-contact-item">

                            <span class="sudheera-contact-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </span>

                            <a href="{{ route('contactus') }}">
                                Email & Contact Details
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </footer>



        <style>
            /* =========================================================
   SUDHEERA FOOTER
========================================================= */


            .sudheera-footer {
                background-color: #080808;

                background-image:
                    linear-gradient(rgba(5, 5, 5, 0.25),
                        rgba(5, 5, 5, 0.40)),
                    url("{{ asset('website/images/footerback.png') }}");

                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;

                color: #fff;
                padding-top: 0;
            }



            /* =========================================================
   MAIN FOOTER
========================================================= */

            .sudheera-footer-main {
                display: grid;
                grid-template-columns: 1.7fr 1fr 1.2fr 1.2fr 1.5fr;
                gap: 45px;
                padding-bottom: 10px;
                padding-top: 20px;
                margin-left: 20px;
                margin-right: 20px;
            }


            /* =========================================================
   LOGO
========================================================= */

            .sudheera-footer-logo {
                display: inline-block;
                margin-bottom: 20px;
                color: #fff;
                text-decoration: none;
                font-family: "Instrument Serif", serif;
                font-size: 27px;
                letter-spacing: 1.5px;
            }

            .sudheera-footer-logo span {
                display: block;
                font-family: inherit;
                font-size: 12px;
                letter-spacing: 4px;
                color: #c99a59;
                margin-top: -3px;
            }


            /* =========================================================
   ABOUT
========================================================= */

            .footer-about p {
                max-width: 320px;
                color: #c7beb5;
                font-size: 13px;
                line-height: 1.8;
                margin: 0;
            }


            /* =========================================================
   FOOTER HEADINGS
========================================================= */

            .sudheera-footer-col h4 {
                color: #fff;
                font-size: 15px;
                font-weight: 500;
                margin: 5px 0 22px;
            }


            /* =========================================================
   LINKS
========================================================= */

            .sudheera-footer-col ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .sudheera-footer-col ul li {
                margin-bottom: 12px;
            }

            .sudheera-footer-col ul li a {
                color: #c7beb5;
                text-decoration: none;
                font-size: 13px;
                transition: all .3s ease;
            }

            .sudheera-footer-col ul li a:hover {
                color: #c99a59;
                padding-left: 4px;
            }


            /* =========================================================
   SOCIAL
========================================================= */

            .sudheera-social {
                display: flex;
                gap: 9px;
                margin-top: 24px;
            }

            .sudheera-social a {
                width: 36px;
                height: 36px;
                border: 1px solid #66584c;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #d4c5b6;
                text-decoration: none;
                transition: .3s ease;
            }

            .sudheera-social a:hover {
                background: #a96b18;
                border-color: #a96b18;
                color: #fff;
            }


            /* =========================================================
   CONTACT
========================================================= */

            .footer-contact-item {
                display: flex;
                align-items: flex-start;
                gap: 11px;
                margin-bottom: 17px;
            }

            .footer-contact-icon {
                color: #c99a59;
                font-size: 15px;
                width: 20px;
                flex-shrink: 0;
            }

            .footer-contact-item p,
            .footer-contact-item a {
                margin: 0;
                color: #c7beb5;
                font-size: 12px;
                line-height: 1.7;
                text-decoration: none;
            }

            .footer-contact-item a:hover {
                color: #c99a59;
            }


            /* =========================================================
   NEWSLETTER
========================================================= */

            .sudheera-footer-newsletter {
                border-top: 1px solid #51463d;
                border-bottom: 1px solid #51463d;
                padding: 30px 0;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 30px;
            }

            .newsletter-content h3 {
                margin: 0 0 5px;
                color: #fff;
                font-family: "Instrument Serif", serif;
                font-size: 25px;
                font-weight: 400;
            }

            .newsletter-content p {
                margin: 0;
                color: #bdb2a8;
                font-size: 12px;
                margin-bottom: 10px;
            }


            /* =========================================================
   NEWSLETTER FORM
========================================================= */

            .newsletter-form {
                display: flex;
                width: 430px;
                max-width: 100%;
                height: 46px;
            }

            .newsletter-form input {
                flex: 1;
                min-width: 0;
                border: 1px solid #66584c;
                border-right: 0;
                background: transparent;
                color: #fff;
                padding: 0 15px;
                outline: none;
                font-size: 12px;
            }

            .newsletter-form input::placeholder {
                color: #958b82;
            }

            .newsletter-form button {
                width: 125px;
                border: 0;
                background: #a96b18;
                color: #fff;
                cursor: pointer;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: .7px;
                transition: .3s ease;
            }

            .newsletter-form button:hover {
                background: #875310;
            }


            /* =========================================================
   FOOTER BOTTOM
========================================================= */

            .sudheera-footer-bottom {
                min-height: 65px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
            }

            .sudheera-footer-bottom p {
                margin: 0;
                color: #958b82;
                font-size: 11px;
            }

            .footer-payment {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #958b82;
                font-size: 11px;
            }


            /* =========================================================
   TABLET
========================================================= */

            @media (max-width: 1100px) {

                .sudheera-footer-main {
                    grid-template-columns: 1.5fr 1fr 1fr 1fr;
                    gap: 35px;
                }

                .footer-contact {
                    grid-column: span 2;
                }

            }


            /* =========================================================
   MOBILE
========================================================= */

            @media (max-width: 767px) {

                .sudheera-footer {
                    padding-top: 45px;
                }

                .sudheera-footer-main {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 35px 25px;
                    padding-bottom: 35px;
                }

                .footer-about {
                    grid-column: span 2;
                }

                .footer-contact {
                    grid-column: span 2;
                }

                .footer-about p {
                    max-width: 100%;
                }

                .sudheera-footer-newsletter {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 18px;
                }

                .newsletter-form {
                    width: 100%;
                }

                .sudheera-footer-bottom {
                    padding: 20px 0;
                    flex-direction: column;
                    justify-content: center;
                    text-align: center;
                }

            }


            /* =========================================================
   SMALL MOBILE
========================================================= */

            @media (max-width: 480px) {

                .sudheera-footer-main {
                    grid-template-columns: 1fr;
                    gap: 28px;
                }

                .footer-about,
                .footer-contact {
                    grid-column: span 1;
                }

                .sudheera-footer-col h4 {
                    margin-bottom: 15px;
                }

                .newsletter-form {
                    height: 44px;
                }

                .newsletter-form button {
                    width: 105px;
                }

            }
        </style>


        <!-- Bottom Footer -->

        <div class="footer-bottom">

            <div class="footer-bottom-inner">

                <p>
                    © 2026 BAKASURAN. All rights reserved.
                </p>

                <div class="footer-links">

                    <a href="{{ route('privacypolicy') }}">
                        Privacy Policy
                    </a>

                    <a href="{{ route('terms') }}">
                        Terms & Conditions
                    </a>

                    <a href="{{ route('contactus') }}">
                        Contact Us
                    </a>

                </div>

            </div>

        </div>


    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            /* =====================================================
               MOBILE MENU TOGGLE
            ===================================================== */

            const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
            const navbarMenu = document.querySelector(".navbar-menu");

            if (mobileMenuBtn && navbarMenu) {

                mobileMenuBtn.addEventListener("click", function (e) {

                    e.preventDefault();
                    e.stopPropagation();

                    navbarMenu.classList.toggle("mobile-open");
                    mobileMenuBtn.classList.toggle("active");

                    /* Change hamburger to X */
                    const icon = mobileMenuBtn.querySelector("i");

                    if (navbarMenu.classList.contains("mobile-open")) {
                        icon.classList.remove("fa-bars");
                        icon.classList.add("fa-xmark");
                    } else {
                        icon.classList.remove("fa-xmark");
                        icon.classList.add("fa-bars");
                    }

                });
            }


            /* =====================================================
               SAREES DROPDOWN
            ===================================================== */

            const dropdown = document.querySelector(".nav-dropdown");

            if (dropdown) {

                const dropdownButton = dropdown.querySelector(".nav-link");

                if (dropdownButton) {

                    dropdownButton.addEventListener("click", function (e) {

                        e.preventDefault();
                        e.stopPropagation();

                        dropdown.classList.toggle("open");

                    });
                }


                /* Prevent dropdown menu links from triggering parent */
                const dropdownMenu = dropdown.querySelector(".dropdown-menu");

                if (dropdownMenu) {

                    dropdownMenu.addEventListener("click", function (e) {
                        e.stopPropagation();
                    });

                }
            }


            /* =====================================================
               CLOSE MOBILE MENU WHEN CLICKING OUTSIDE
            ===================================================== */

            document.addEventListener("click", function (e) {

                if (
                    navbarMenu &&
                    mobileMenuBtn &&
                    !navbarMenu.contains(e.target) &&
                    !mobileMenuBtn.contains(e.target)
                ) {

                    navbarMenu.classList.remove("mobile-open");
                    mobileMenuBtn.classList.remove("active");

                    const icon = mobileMenuBtn.querySelector("i");

                    if (icon) {
                        icon.classList.remove("fa-xmark");
                        icon.classList.add("fa-bars");
                    }

                }

            });


            /* =====================================================
               CLOSE MOBILE MENU AFTER CLICKING NORMAL LINK
            ===================================================== */

            if (navbarMenu) {

                const normalLinks = navbarMenu.querySelectorAll(
                    ".nav-link:not(.nav-dropdown > .nav-link)"
                );

                normalLinks.forEach(function (link) {

                    link.addEventListener("click", function () {

                        navbarMenu.classList.remove("mobile-open");
                        mobileMenuBtn.classList.remove("active");

                        const icon = mobileMenuBtn.querySelector("i");

                        if (icon) {
                            icon.classList.remove("fa-xmark");
                            icon.classList.add("fa-bars");
                        }

                    });

                });

            }

        });
    </script>
    <!-- <script>

        document.addEventListener("DOMContentLoaded", function () {

            const loader =
                document.getElementById("pageLoader");

            if (!loader) {
                return;
            }


            /* =========================================
               CHECK FIRST VISIT
            ========================================= */

            const loaderShown =
                sessionStorage.getItem("sudheeraLoaderShown");


            /* =========================================
               ALREADY SHOWN
            ========================================= */

            if (loaderShown === "true") {

                loader.remove();

                return;
            }


            /* =========================================
               FIRST VISIT
            ========================================= */

            sessionStorage.setItem(
                "sudheeraLoaderShown",
                "true"
            );


            /* =========================================
               FAST PREMIUM INTRO
            ========================================= */

            setTimeout(function () {

                loader.style.opacity = "0";

                loader.style.visibility = "hidden";

                loader.style.pointerEvents = "none";


                setTimeout(function () {

                    loader.remove();

                }, 700);

            }, 2200);

        });

    </script> -->
    <script>
        function openMobileSearchModal() {
            document.getElementById('mobileSearchModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';

            setTimeout(function () {
                document.querySelector('#mobileSearchModal input[name="search"]').focus();
            }, 100);
        }

        function closeMobileSearchModal() {
            document.getElementById('mobileSearchModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('mobileSearchModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeMobileSearchModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMobileSearchModal();
            }
        });
    </script>
    <script>
        function toggleMobileWishlist() {
            document.querySelectorAll('a[aria-label="Wishlist"]').forEach(function (link) {
                link.style.display = window.innerWidth <= 991 ? 'flex' : 'none';
            });
        }

        toggleMobileWishlist();
        window.addEventListener('resize', toggleMobileWishlist);
    </script>


<!-- <script>
(function () {
    const loader = document.getElementById('pageLoader');

    if (!loader) return;

    let dismissed = false;

    function hideLoader() {
        if (dismissed) return;

        dismissed = true;
        loader.classList.add('loader-hidden');

        window.setTimeout(function () {
            if (loader.parentNode) {
                loader.parentNode.removeChild(loader);
            }
        }, 750);
    }

    // Hide when the page and its assets have finished loading.
    window.addEventListener('load', hideLoader, { once: true });

    // Fallback in case a third-party asset never finishes loading.
    window.setTimeout(hideLoader, 5000);

    // Handle pages where the load event already fired.
    if (document.readyState === 'complete') {
        hideLoader();
    }
})();
</script> -->


<script>
(function () {
    const loader = document.getElementById('pageLoader');

    if (!loader) return;

    let dismissed = false;

    function hideLoader() {
        if (dismissed) return;

        dismissed = true;

        // Start the slow fade-out animation
        loader.classList.add('loader-hidden');

        // Remove only after the animation completes
        window.setTimeout(function () {
            if (loader.parentNode) {
                loader.parentNode.removeChild(loader);
            }
        }, 2000);
    }

    // Hide after the page and its assets finish loading
    window.addEventListener('load', hideLoader, { once: true });

    // Fallback if loading takes too long
    window.setTimeout(hideLoader, 5000);

    // Handle pages that have already loaded
    if (document.readyState === 'complete') {
        hideLoader();
    }
})();
</script>


</body>


</html>