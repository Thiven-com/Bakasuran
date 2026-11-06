@extends('layouts.website')

@section('content')

    <style>
        /* ================================
           BAKASURAN RESTAURANTS
        ================================ */

        .restaurant-page {
            --red: #f50918;
            --dark: #090909;
            --text: #171717;
            --muted: #747780;
            --border: #e9e9e9;

            color: var(--text);
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
            padding-bottom: 45px;
        }

        .restaurant-page * {
            box-sizing: border-box;
        }

        .restaurant-page a {
            text-decoration: none;
        }

        /* Top announcement bar */

        .restaurant-topbar {
            min-height: 37px;
            padding: 8px 4.5%;
            background: #050505;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            font-size: 13px;
        }

        .topbar-message,
        .topbar-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .topbar-divider {
            color: var(--red);
            font-weight: 800;
        }

        /* Main navigation */

        .restaurant-navbar {
            min-height: 77px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            padding: 10px 4.5%;
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .restaurant-brand {
            flex-shrink: 0;
            color: var(--red);
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -1.5px;
            line-height: 1;
        }

        .restaurant-brand small {
            display: block;
            margin-top: 4px;
            font-size: 8px;
            color: #222;
            letter-spacing: 2px;
            text-align: center;
        }

        .restaurant-navlinks {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .restaurant-navlinks a {
            color: #161616;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 0;
            white-space: nowrap;
        }

        .restaurant-navlinks a.active {
            color: var(--red);
            border-bottom: 3px solid var(--red);
        }

        .restaurant-navtools {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .restaurant-search {
            display: flex;
            align-items: center;
            height: 44px;
            width: 285px;
            border: 1px solid #e6e6e6;
            border-radius: 28px;
            overflow: hidden;
            background: #fafafa;
        }

        .restaurant-search input {
            width: 100%;
            min-width: 0;
            padding: 0 15px;
            border: 0;
            outline: 0;
            background: transparent;
            font-size: 12px;
        }

        .restaurant-search button {
            flex-shrink: 0;
            width: 39px;
            height: 39px;
            margin-right: 3px;
            border: 0;
            border-radius: 50%;
            background: var(--red);
            color: #fff;
            cursor: pointer;
            font-size: 20px;
        }

        .nav-icon {
            color: #111;
            font-size: 27px;
            position: relative;
            cursor: pointer;
        }

        .nav-count {
            position: absolute;
            top: -4px;
            right: -7px;
            background: var(--red);
            color: #fff;
            font-size: 10px;
            border-radius: 50%;
            min-width: 16px;
            height: 16px;
            display: grid;
            place-items: center;
        }

        /* Hero banner */

        .restaurant-hero {
            min-height: 320px;
            position: relative;
            display: flex;
            align-items: center;
            padding: 35px 7%;
            overflow: hidden;
            color: white;
            background:
                linear-gradient(90deg,
                    rgba(0, 0, 0, .97) 0%,
                    rgba(0, 0, 0, .85) 34%,
                    rgba(0, 0, 0, .18) 100%),
                url('https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=2000&q=90') center 50% / cover no-repeat;
        }

        .restaurant-hero-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .restaurant-hero h1 {
            margin: 0;
            font-size: clamp(35px, 4vw, 66px);
            font-weight: 1000;
            font-style: italic;
            line-height: .98;
            letter-spacing: -2px;
            text-transform: uppercase;
        }

        .restaurant-hero h1 span {
            display: block;
            color: var(--red);
            margin-left: 35px;
        }

        .restaurant-hero p {
            margin: 14px 0 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #eee;
        }

        .hero-highlight {
            color: #ff343d;
            font-weight: 700;
        }

        .location-search {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 30px;
            padding: 4px;
            max-width: 690px;
            min-height: 48px;
            color: #222;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .15);
        }

        .location-search .location-icon {
            color: var(--red);
            font-size: 22px;
            padding: 0 12px;
        }

        .location-search input {
            width: 35%;
            min-width: 0;
            padding: 10px 8px;
            border: 0;
            outline: 0;
            font-size: 12px;
        }

        .location-search .food-search-input {
            flex: 1;
            border-left: 1px solid #eee;
            padding-left: 15px;
        }

        .location-search button {
            flex-shrink: 0;
            padding: 14px 20px;
            border: 0;
            border-radius: 25px;
            background: var(--red);
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
        }

        .hero-benefits {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 22px;
        }

        .hero-benefit {
            display: flex;
            align-items: center;
            gap: 9px;
            padding-right: 18px;
            border-right: 1px solid #a52a2a;
            font-size: 12px;
            line-height: 1.4;
        }

        .hero-benefit:last-child {
            border: 0;
        }

        .benefit-icon {
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            border: 1px solid var(--red);
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 21px;
            box-shadow: 0 0 10px rgba(255, 0, 0, .4);
        }

        /* Main restaurant section */

        .restaurant-container {
            padding: 25px 30px;
            max-width: 1800px;
            margin: auto;
        }

        .restaurant-heading {
            text-align: center;
            margin-bottom: 20px;
        }

        .restaurant-heading h2 {
            display: inline-block;
            position: relative;
            margin: 0;
            font-family: Georgia, serif;
            font-size: 31px;
            font-weight: 800;
        }

        .restaurant-heading h2:before,
        .restaurant-heading h2:after {
            content: "";
            display: inline-block;
            width: 38px;
            height: 1px;
            vertical-align: middle;
            background: #bd8a53;
            margin: 0 12px;
        }

        .restaurant-heading p {
            margin: 7px 0 0;
            font-size: 12px;
            color: var(--muted);
        }

        /* Filters */

        .restaurant-filterbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 18px;
        }

        .cuisine-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .cuisine-btn {
            padding: 10px 15px;
            border: 1px solid #e5e6e8;
            border-radius: 25px;
            background: #f8f9fa;
            color: #303030;
            font-size: 12px;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s ease;
        }

        .cuisine-btn:hover,
        .cuisine-btn.active {
            background: var(--red);
            border-color: var(--red);
            color: white;
        }

        .restaurant-sort {
            min-width: 175px;
            padding: 11px 13px;
            border: 1px solid #ead9c7;
            border-radius: 7px;
            background: white;
            font-size: 12px;
            outline: 0;
        }

        /* Restaurant cards grid */

        .restaurant-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .restaurant-card {
            min-width: 0;
            overflow: hidden;
            position: relative;
            border: 1px solid #ededed;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .07);
            transition: transform .22s ease, box-shadow .22s ease;
        }

        .restaurant-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 9px 24px rgba(0, 0, 0, .12);
        }

        .restaurant-photo {
            height: 145px;
            position: relative;
            overflow: hidden;
            background: #21140e;
        }

        .restaurant-photo img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .restaurant-card:hover .restaurant-photo img {
            transform: scale(1.05);
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            padding: 7px 13px;
            border-radius: 20px;
            color: #fff;
            background: var(--red);
            font-size: 12px;
            font-weight: 800;
            box-shadow: 0 3px 8px rgba(0, 0, 0, .15);
        }

        .favorite-btn {
            position: absolute;
            top: 9px;
            right: 9px;
            z-index: 3;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, .55);
            color: #fff;
            font-size: 23px;
            cursor: pointer;
        }

        .favorite-btn.active {
            color: #ff1424;
            background: white;
        }

        .restaurant-card-info {
            padding: 0 13px 12px;
        }

        .restaurant-card-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: -23px;
            position: relative;
            z-index: 2;
        }

        .restaurant-logo-small {
            width: 54px;
            height: 54px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border: 3px solid white;
            border-radius: 50%;
            background: #101010;
            color: white;
            font-size: 23px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        }

        .restaurant-name {
            margin-top: 25px;
            color: #171717;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.4;
        }

        .restaurant-cuisines {
            margin: 3px 0 8px 63px;
            color: #747474;
            font-size: 12px;
            line-height: 1.45;
        }

        .restaurant-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
            margin: 8px 0;
            font-size: 12px;
        }

        .restaurant-rating {
            color: #353535;
            white-space: nowrap;
        }

        .restaurant-rating span {
            color: #ff8a00;
            font-size: 17px;
            margin-right: 4px;
        }

        .delivery-time {
            white-space: nowrap;
            color: #303030;
        }

        .delivery-offer {
            display: flex;
            align-items: center;
            gap: 7px;
            padding-top: 8px;
            border-top: 1px solid #f0f0f0;
            color: #4e4e4e;
            font-size: 12px;
        }

        .delivery-offer span {
            color: var(--red);
            font-size: 15px;
        }

        .restaurant-empty {
            display: none;
            padding: 35px;
            text-align: center;
            color: var(--muted);
            border: 1px dashed #ddd;
            border-radius: 10px;
        }

        /* Bottom promotional strip */

        .restaurant-promo {
            margin-top: 35px;
            padding: 25px 30px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap;
            background: #090909;
            color: white;
            border-radius: 12px;
        }

        .restaurant-promo h3 {
            margin: 0;
            color: #fff;
            font-size: 22px;
            font-style: italic;
        }

        .restaurant-promo h3 span {
            color: var(--red);
        }

        .promo-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }

        .promo-feature strong {
            display: block;
            margin-bottom: 4px;
        }

        .promo-feature small {
            color: #c8c8c8;
        }

        .promo-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border: 1px solid var(--red);
            color: var(--red);
            border-radius: 50%;
            font-size: 20px;
        }

        /* Responsive desktop and laptop */

        @media (min-width: 1500px) {
            .restaurant-grid {
                gap: 18px;
            }

            .restaurant-photo {
                height: 175px;
            }

            .restaurant-container {
                padding-left: 40px;
                padding-right: 40px;
            }
        }

        @media (max-width: 1250px) {
            .restaurant-navbar {
                gap: 15px;
            }

            .restaurant-navlinks {
                gap: 15px;
            }

            .restaurant-search {
                width: 220px;
            }

            .restaurant-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .restaurant-filterbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .restaurant-sort {
                align-self: flex-end;
            }
        }

        /* Tablet */

        @media (max-width: 900px) {
            .restaurant-topbar {
                font-size: 11px;
                padding: 8px 15px;
            }

            .restaurant-navbar {
                padding: 12px 18px;
                flex-wrap: wrap;
            }

            .restaurant-navlinks {
                order: 3;
                width: 100%;
                justify-content: center;
                gap: 22px;
                overflow-x: auto;
            }

            .restaurant-navtools {
                margin-left: auto;
            }

            .restaurant-hero {
                min-height: 290px;
                padding: 30px 5%;
            }

            .restaurant-container {
                padding: 22px 18px;
            }

            .restaurant-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Mobile */

        @media (max-width: 600px) {
            .restaurant-topbar {
                min-height: 29px;
                justify-content: center;
                padding: 7px 8px;
                font-size: 10px;
            }

            .topbar-message {
                justify-content: center;
                gap: 6px;
            }

            .topbar-links {
                display: none;
            }

            .restaurant-navbar {
                padding: 10px 12px;
                gap: 10px;
            }

            .restaurant-brand {
                font-size: 20px;
            }

            .restaurant-brand small {
                font-size: 6px;
            }

            .restaurant-navlinks {
                display: none;
            }

            .restaurant-search {
                width: min(44vw, 200px);
                height: 37px;
            }

            .restaurant-search input {
                padding-left: 9px;
                font-size: 10px;
            }

            .restaurant-search button {
                width: 31px;
                height: 31px;
                font-size: 15px;
            }

            .restaurant-navtools {
                gap: 9px;
            }

            .nav-icon {
                font-size: 22px;
            }

            .nav-icon.account-icon {
                display: none;
            }

            .restaurant-hero {
                min-height: 310px;
                padding: 25px 16px;
                background-position: center;
            }

            .restaurant-hero h1 {
                font-size: clamp(34px, 10vw, 46px);
                letter-spacing: -1.5px;
            }

            .restaurant-hero h1 span {
                margin-left: 15px;
            }

            .restaurant-hero p {
                font-size: 13px;
                line-height: 1.5;
                max-width: 330px;
            }

            .location-search {
                display: grid;
                grid-template-columns: 35px minmax(0, 1fr);
                border-radius: 13px;
                padding: 7px;
                gap: 3px;
            }

            .location-search .location-icon {
                padding: 0;
                text-align: center;
                font-size: 18px;
            }

            .location-search input,
            .location-search .food-search-input {
                width: 100%;
                padding: 10px 5px;
                font-size: 11px;
                border-left: 0;
                border-bottom: 1px solid #eee;
            }

            .location-search button {
                grid-column: 1 / -1;
                width: 100%;
                padding: 12px;
                border-radius: 8px;
            }

            .hero-benefits {
                gap: 10px;
                margin-top: 16px;
            }

            .hero-benefit {
                gap: 5px;
                padding-right: 8px;
                font-size: 10px;
            }

            .benefit-icon {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }

            .restaurant-container {
                padding: 20px 12px;
            }

            .restaurant-heading {
                margin-bottom: 16px;
            }

            .restaurant-heading h2 {
                font-size: 23px;
            }

            .restaurant-heading h2:before,
            .restaurant-heading h2:after {
                width: 16px;
                margin: 0 5px;
            }

            .restaurant-heading p {
                font-size: 11px;
            }

            .restaurant-filterbar {
                gap: 12px;
                margin-bottom: 15px;
            }

            .cuisine-filters {
                flex-wrap: nowrap;
                width: 100%;
                overflow-x: auto;
                padding-bottom: 5px;
                scrollbar-width: none;
            }

            .cuisine-filters::-webkit-scrollbar {
                display: none;
            }

            .cuisine-btn {
                padding: 9px 12px;
                font-size: 11px;
            }

            .restaurant-sort {
                width: 100%;
                padding: 10px;
            }

            .restaurant-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .restaurant-photo {
                height: 115px;
            }

            .discount-badge {
                top: 7px;
                left: 6px;
                padding: 6px 8px;
                font-size: 10px;
            }

            .favorite-btn {
                top: 5px;
                right: 5px;
                width: 28px;
                height: 28px;
                font-size: 19px;
            }

            .restaurant-card-info {
                padding: 0 8px 10px;
            }

            .restaurant-card-title {
                gap: 6px;
                margin-top: -19px;
            }

            .restaurant-logo-small {
                width: 39px;
                height: 39px;
                font-size: 17px;
                border-width: 2px;
            }

            .restaurant-name {
                font-size: 12px;
            }

            .restaurant-cuisines {
                margin: 5px 0 7px;
                font-size: 10px;
                min-height: 28px;
            }

            .restaurant-stats {
                flex-wrap: wrap;
                font-size: 10px;
                gap: 5px;
            }

            .restaurant-rating span {
                font-size: 14px;
            }

            .delivery-time {
                font-size: 10px;
            }

            .delivery-offer {
                align-items: flex-start;
                font-size: 10px;
                line-height: 1.4;
            }

            .restaurant-promo {
                margin-top: 20px;
                padding: 20px 15px;
                justify-content: flex-start;
                border-radius: 8px;
            }

            .restaurant-promo h3 {
                width: 100%;
                font-size: 20px;
            }

            .promo-feature {
                width: calc(50% - 10px);
                gap: 8px;
                font-size: 11px;
            }

            .promo-icon {
                width: 35px;
                height: 35px;
                flex-shrink: 0;
                font-size: 16px;
            }
        }

        @media (max-width: 360px) {
            .restaurant-grid {
                grid-template-columns: 1fr;
            }

            .restaurant-photo {
                height: 180px;
            }

            .restaurant-cuisines {
                min-height: 0;
            }
        }


        .view-restaurant-btn {
            display: block;
            width: 100%;
            margin-top: 12px;
            padding: 11px;
            background: #ff0718;
            color: #fff !important;
            text-align: center;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            transition: background .2s ease;
        }

        .view-restaurant-btn:hover {
            background: #d90010;
        }
    </style>

    <style>
        .restaurant-pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.restaurant-pagination nav {
    display: flex;
    justify-content: center;
}
    </style>

    <div class="restaurant-page">

        {{-- Announcement bar --}}
        <!-- <div class="restaurant-topbar">
                <div class="topbar-message">
                    <span>🍽 Delicious Food, Just a Click Away</span>
                    <span class="topbar-divider">|</span>
                    <span>Freshly Prepared Meals</span>
                    <span class="topbar-divider">|</span>
                    <span>Discover Your Next Favourite</span>
                </div>

                <div class="topbar-links">
                    <a href="#" style="color:white">Track Order</a>
                    <span class="topbar-divider">|</span>
                    <a href="#" style="color:white">Contact</a>
                    <span>◎</span>
                    <span>f</span>
                    <span>▶</span>
                </div>
            </div> -->

        {{-- Navigation --}}
        <!-- <header class="restaurant-navbar">
                <a href="{{ url('/') }}" class="restaurant-brand">
                    🍲 BAKASURAN
                    <small>FOOD & BEVERAGES</small>
                </a>

                <nav class="restaurant-navlinks">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="#">Explore Food</a>
                    <a href="{{ url('/restaurants') }}" class="active">Restaurants</a>
                    <a href="#">About Us</a>
                    <a href="#">Offers</a>
                    <a href="#">Food Blogs</a>
                </nav>

                <div class="restaurant-navtools">
                    <form class="restaurant-search" id="navSearchForm">
                        <input id="navRestaurantSearch"
                               type="search"
                               placeholder="Search restaurants, cuisines..."
                               aria-label="Search restaurants">
                        <button type="submit" aria-label="Search">⌕</button>
                    </form>

                    <a href="#" class="nav-icon account-icon" aria-label="Account">♙</a>
                    <a href="#" class="nav-icon" aria-label="Wishlist">♡
                        <span class="nav-count" id="favoriteCount">0</span>
                    </a>
                    <a href="{{ url('/cart') }}" class="nav-icon" aria-label="Cart">🛒
                        <span class="nav-count">0</span>
                    </a>
                </div>
            </header> -->

        {{-- Hero banner --}}
        <section class="restaurant-hero">
            <div class="restaurant-hero-content">
                <h1>
                    Restaurants
                    <span>Near You!</span>
                </h1>

                <p>
                    Discover the best restaurants, delicious food, and
                    <br class="desktop-break">
                    get it delivered <span class="hero-highlight">fresh, fast and hot!</span>
                </p>

                <form class="location-search" id="heroSearchForm">
                    <span class="location-icon">📍</span>
                    <input type="text" id="deliveryLocation" placeholder="Enter your delivery location..."
                        aria-label="Delivery location">

                    <input type="search" id="heroRestaurantSearch" class="food-search-input"
                        placeholder="Search restaurants or cuisines..." aria-label="Search food">

                    <button type="submit">⌕ Search Restaurants</button>
                </form>

                <div class="hero-benefits">
                    <div class="hero-benefit">
                        <span class="benefit-icon">🍴</span>
                        <span>Wide Variety<br>of Restaurants</span>
                    </div>

                    <div class="hero-benefit">
                        <span class="benefit-icon">🛵</span>
                        <span>Fast<br>Delivery</span>
                    </div>

                    <div class="hero-benefit">
                        <span class="benefit-icon">🏷</span>
                        <span>Great<br>Offers</span>
                    </div>

                    <div class="hero-benefit">
                        <span class="benefit-icon">🛡</span>
                        <span>Secure<br>Payments</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Restaurant listing --}}
        <section class="restaurant-container">

            <div class="restaurant-heading">
                <h2>Explore Restaurants</h2>
                <p>Discover top restaurants near you with great food, offers and fast delivery</p>
            </div>

            {{-- Cuisine filters and sort --}}
            <div class="restaurant-filterbar">
                <div class="cuisine-filters">
                    <button type="button" class="cuisine-btn active" data-cuisine="all">▦ All</button>
                    <button type="button" class="cuisine-btn" data-cuisine="North Indian">🍛 North Indian</button>
                    <button type="button" class="cuisine-btn" data-cuisine="South Indian">🥘 South Indian</button>
                    <button type="button" class="cuisine-btn" data-cuisine="Chinese">🥢 Chinese</button>
                    <button type="button" class="cuisine-btn" data-cuisine="Italian">🍝 Italian</button>
                    <button type="button" class="cuisine-btn" data-cuisine="Burgers">🍔 Burgers</button>
                    <button type="button" class="cuisine-btn" data-cuisine="Pizza">🍕 Pizza</button>
                    <button type="button" class="cuisine-btn" data-cuisine="Biryani">🍛 Biryani</button>
                    <button type="button" class="cuisine-btn" data-cuisine="Desserts">🍰 Desserts</button>
                    <button type="button" class="cuisine-btn" data-cuisine="more">More⌄</button>
                </div>

                <select class="restaurant-sort" id="restaurantSort" aria-label="Sort restaurants">
                    <option value="default">Sort by: Relevance</option>
                    <option value="rating">Sort by: Rating</option>
                    <option value="offer">Sort by: Best Offers</option>
                    <option value="name">Sort by: Name</option>
                </select>
            </div>

            {{-- Restaurant cards --}}
            <div class="restaurant-grid" id="restaurantGrid">


                @forelse ($restaurants as $index => $restaurant)
                        <article class="restaurant-card" data-name="{{ strtolower($restaurant->name) }}"
                            data-categories="{{ strtolower($restaurant->cuisine_type ?? '') }}"
                            data-rating="{{ $restaurant->average_rating ?? 0 }}" data-offer="0" data-index="{{ $index }}">
                            <div class="restaurant-photo">
                                <img src="{{ $restaurant->cover_image
                    ? asset($restaurant->cover_image)
                    : asset('images/restaurant-placeholder.jpg') }}" alt="{{ $restaurant->name }}"
                                    loading="{{ $index < 4 ? 'eager' : 'lazy' }}"
                                    onerror="this.onerror=null;this.src='https://placehold.co/750x400/171717/ffffff?text=BAKASURAN+FOOD';">

                                @if ($restaurant->is_open)
                                    <span class="discount-badge">OPEN NOW</span>
                                @else
                                    <span class="discount-badge">CLOSED</span>
                                @endif

                                <button class="favorite-btn" type="button" aria-label="Add {{ $restaurant->name }} to wishlist"
                                    aria-pressed="false">♡</button>
                            </div>

                            <div class="restaurant-card-info">
                                <div class="restaurant-card-title">
                                    <div class="restaurant-logo-small">
                                        @if ($restaurant->logo)
                                            <img src="{{ asset( $restaurant->logo) }}" alt="{{ $restaurant->name }} logo"
                                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                        @else
                                            🍽️
                                        @endif
                                    </div>

                                    <h3 class="restaurant-name">
                                        {{ $restaurant->name }}
                                    </h3>
                                </div>

                                <p class="restaurant-cuisines">
                                    {{ $restaurant->cuisine_type ?? 'Various Cuisines' }}
                                </p>

                                <div class="restaurant-stats">
                                    <span class="restaurant-rating">
                                        <span>★</span>
                                        {{ number_format((float) $restaurant->average_rating, 1) }}
                                    </span>

                                    <span class="delivery-time">
                                        🛵
                                        {{ $restaurant->delivery_time
                    ? $restaurant->delivery_time . ' mins'
                    : 'Delivery time unavailable' }}
                                    </span>
                                </div>

                                <div class="delivery-offer">
                                    <span>◆</span>
                                    Minimum order: ₹{{ number_format((float) $restaurant->minimum_order, 0) }}
                                </div>
                            </div>

                            <a href="{{ route('restaurant.details', ['slug' => $restaurant->slug]) }}" class="view-restaurant-btn">
                                View Restaurant →
                            </a>
                        </article>
                @empty
                    <p class="restaurant-empty" style="display:block;grid-column:1/-1;">
                        No restaurants available right now. Please check back soon.
                    </p>
                @endforelse


            </div>

            @if ($restaurants->hasPages())
                <div class="restaurant-pagination">
                    {{ $restaurants->links() }}
                </div>
            @endif

            <div class="restaurant-empty" id="restaurantEmpty">
                No restaurants found. Try another search or cuisine.
            </div>

            {{-- Promotional footer --}}
            <div class="restaurant-promo">
                <h3>WHY CHOOSE <span>BAKASURAN?</span></h3>

                <div class="promo-feature">
                    <span class="promo-icon">🏪</span>
                    <div>
                        <strong>100+ Restaurants</strong>
                        <small>Across Your City</small>
                    </div>
                </div>

                <div class="promo-feature">
                    <span class="promo-icon">🛵</span>
                    <div>
                        <strong>Fast Delivery</strong>
                        <small>Fresh Food, Fast</small>
                    </div>
                </div>

                <div class="promo-feature">
                    <span class="promo-icon">🛡</span>
                    <div>
                        <strong>Best Offers</strong>
                        <small>Save More</small>
                    </div>
                </div>

                <div class="promo-feature">
                    <span class="promo-icon">💳</span>
                    <div>
                        <strong>Secure Payments</strong>
                        <small>Multiple Options</small>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const grid = document.getElementById('restaurantGrid');
            const cards = Array.from(grid.querySelectorAll('.restaurant-card'));
            const cuisineButtons = document.querySelectorAll('.cuisine-btn');
            const sortSelect = document.getElementById('restaurantSort');
            const emptyMessage = document.getElementById('restaurantEmpty');
            const navSearch = document.getElementById('navRestaurantSearch');
            const heroSearch = document.getElementById('heroRestaurantSearch');
            const favoriteCount = document.getElementById('favoriteCount');

            let selectedCuisine = 'all';
            let searchTerm = '';
            let favorites = 0;

            function updateRestaurants() {
                cards.forEach(function (card) {
                    const name = card.dataset.name;
                    const categories = card.dataset.categories;

                    const matchesSearch =
                        !searchTerm ||
                        name.includes(searchTerm) ||
                        categories.includes(searchTerm);

                    const matchesCuisine =
                        selectedCuisine === 'all' ||
                        selectedCuisine === 'more' ||
                        categories.includes(selectedCuisine.toLowerCase());

                    card.style.display =
                        matchesSearch && matchesCuisine ? '' : 'none';
                });

                const visibleCards = cards.filter(function (card) {
                    return card.style.display !== 'none';
                });

                visibleCards.sort(function (a, b) {
                    switch (sortSelect.value) {
                        case 'rating':
                            return Number(b.dataset.rating) - Number(a.dataset.rating);
                        case 'offer':
                            return Number(b.dataset.offer) - Number(a.dataset.offer);
                        case 'name':
                            return a.dataset.name.localeCompare(b.dataset.name);
                        default:
                            return Number(a.dataset.index) - Number(b.dataset.index);
                    }
                });

                visibleCards.forEach(function (card) {
                    grid.appendChild(card);
                });

                emptyMessage.style.display =
                    visibleCards.length ? 'none' : 'block';
            }

            function applySearch(value) {
                searchTerm = value.trim().toLowerCase();
                navSearch.value = value;
                heroSearch.value = value;
                updateRestaurants();
            }

            document.getElementById('navSearchForm')
                .addEventListener('submit', function (event) {
                    event.preventDefault();
                    applySearch(navSearch.value);
                    document.querySelector('.restaurant-container')
                        .scrollIntoView({ behavior: 'smooth' });
                });

            document.getElementById('heroSearchForm')
                .addEventListener('submit', function (event) {
                    event.preventDefault();
                    applySearch(heroSearch.value);
                    document.querySelector('.restaurant-container')
                        .scrollIntoView({ behavior: 'smooth' });
                });

            navSearch.addEventListener('input', function () {
                applySearch(this.value);
            });

            heroSearch.addEventListener('input', function () {
                applySearch(this.value);
            });

            cuisineButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    cuisineButtons.forEach(function (item) {
                        item.classList.remove('active');
                    });

                    this.classList.add('active');
                    selectedCuisine = this.dataset.cuisine;
                    updateRestaurants();
                });
            });

            sortSelect.addEventListener('change', updateRestaurants);

            document.querySelectorAll('.favorite-btn')
                .forEach(function (button) {
                    button.addEventListener('click', function () {
                        const isFavorite = this.classList.toggle('active');

                        this.textContent = isFavorite ? '♥' : '♡';
                        this.setAttribute('aria-pressed', String(isFavorite));

                        favorites += isFavorite ? 1 : -1;
                        favoriteCount.textContent = favorites;
                    });
                });
        });
    </script>

@endsection