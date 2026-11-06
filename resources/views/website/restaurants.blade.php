@extends('layouts.website')

@section('content')
    <style>
        .bk-restaurants {
            --bk-red: #e11d2e;
            --bk-ink: #17191f;
            --bk-muted: #6b7280;
            --bk-line: #e8eaf0;
            --bk-bg: #f6f7f9;
            background: var(--bk-bg);
            color: var(--bk-ink);
            font-family: Arial, Helvetica, sans-serif;
            padding-bottom: 44px
        }

        .bk-restaurants * {
            box-sizing: border-box
        }

        .bk-restaurants a {
            text-decoration: none
        }

        .bk-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            min-height: 350px;
            display: flex;
            align-items: center;
            padding: 48px max(5%, calc((100% - 1380px)/2));
            color: #fff;
            background: #161316 url('https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=2000&q=85') center/cover no-repeat
        }

        .bk-hero:before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(100deg, rgba(12, 12, 14, .96) 0%, rgba(18, 15, 17, .84) 48%, rgba(18, 15, 17, .22) 100%)
        }

        .bk-hero-content {
            width: 100%;
            max-width: 760px
        }

        .bk-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 999px;
            background: rgba(255, 255, 255, .08);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .6px
        }

        .bk-eyebrow i {
            width: 7px;
            height: 7px;
            background: #ff4250;
            border-radius: 50%;
            display: inline-block
        }

        .bk-hero h1 {
            margin: 18px 0 12px;
            font-size: clamp(36px, 5vw, 66px);
            line-height: 1.02;
            letter-spacing: -2px;
            font-weight: 900
        }

        .bk-hero h1 span {
            color: #ff4554
        }

        .bk-hero p {
            max-width: 570px;
            margin: 0 0 24px;
            color: #e5e7eb;
            line-height: 1.7;
            font-size: 15px
        }

        .bk-search {
            display: flex;
            gap: 8px;
            max-width: 740px;
            padding: 7px;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 16px 45px rgba(0, 0, 0, .22)
        }

        .bk-search input {
            width: 100%;
            min-width: 0;
            padding: 13px 14px;
            border: 0;
            outline: 0;
            background: #fff;
            color: #17191f;
            font-size: 13px
        }

        .bk-search .bk-location {
            max-width: 245px;
            border-right: 1px solid #eceef2
        }

        .bk-search button {
            flex-shrink: 0;
            padding: 0 20px;
            border: 0;
            border-radius: 10px;
            background: var(--bk-red);
            color: #fff;
            font-weight: 800;
            cursor: pointer
        }

        .bk-search button:hover {
            background: #bd1422
        }

        .bk-benefits {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 22px
        }

        .bk-benefit {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #f3f4f6;
            font-size: 12px
        }

        .bk-benefit-icon {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(255, 75, 90, .75);
            border-radius: 11px;
            background: rgba(225, 29, 46, .12);
            font-size: 17px
        }

        .bk-container {
            width: min(1440px, 100%);
            margin: 0 auto;
            padding: 36px clamp(14px, 3vw, 42px)
        }

        .bk-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 18px;
            margin-bottom: 22px
        }

        .bk-heading h2 {
            margin: 0;
            font-size: clamp(25px, 3vw, 34px);
            font-weight: 900;
            letter-spacing: -1px
        }

        .bk-heading p {
            margin: 8px 0 0;
            color: var(--bk-muted);
            font-size: 13px;
            line-height: 1.5
        }

        .bk-count {
            white-space: nowrap;
            color: var(--bk-muted);
            font-size: 13px
        }

        .bk-filterbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 22px;
            padding: 13px;
            background: #fff;
            border: 1px solid var(--bk-line);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(20, 25, 35, .035)
        }

        .bk-cuisines {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            scrollbar-width: none
        }

        .bk-cuisines::-webkit-scrollbar {
            display: none
        }

        .bk-cuisine {
            flex-shrink: 0;
            padding: 10px 14px;
            border: 1px solid #e8eaf0;
            border-radius: 10px;
            background: #f8f9fb;
            color: #343741;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s
        }

        .bk-cuisine:hover,
        .bk-cuisine.active {
            background: var(--bk-red);
            border-color: var(--bk-red);
            color: #fff
        }

        .bk-sort {
            min-width: 180px;
            min-height: 41px;
            padding: 0 12px;
            border: 1px solid #e1e4ea;
            border-radius: 10px;
            background: #fff;
            color: #30323a;
            font-size: 12px;
            outline: 0
        }

        .bk-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            align-items: stretch
        }

        .bk-card[hidden] {
            display: none !important
        }

        .bk-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            border: 1px solid #e9ebef;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 5px 18px rgba(24, 30, 40, .05);
            transition: transform .2s, box-shadow .2s
        }

        .bk-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 32px rgba(24, 30, 40, .12)
        }

        .bk-photo {
            height: 190px;
            position: relative;
            overflow: hidden;
            background: #e5e7eb
        }

        .bk-photo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s
        }

        .bk-card:hover .bk-photo img {
            transform: scale(1.045)
        }

        .bk-status {
            position: absolute;
            left: 12px;
            top: 12px;
            padding: 7px 9px;
            border-radius: 7px;
            background: #fff;
            color: #16803d;
            font-size: 10px;
            font-weight: 900;
            box-shadow: 0 3px 10px #0002
        }

        .bk-status.closed {
            color: #b42318
        }

        .bk-discount {
            position: absolute;
            left: 12px;
            bottom: 12px;
            padding: 7px 10px;
            border-radius: 7px;
            background: var(--bk-red);
            color: #fff;
            font-size: 11px;
            font-weight: 900
        }

        .bk-featured {
            position: absolute;
            right: 12px;
            top: 12px;
            padding: 7px 9px;
            border-radius: 7px;
            background: #17191f;
            color: #fff;
            font-size: 10px;
            font-weight: 800
        }

        .bk-favorite {
            position: absolute;
            right: 11px;
            bottom: 11px;
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .96);
            color: #333;
            font-size: 21px;
            cursor: pointer;
            box-shadow: 0 3px 12px #0002
        }

        .bk-favorite.active {
            color: var(--bk-red)
        }

        .bk-card-body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 14px 15px 15px
        }

        .bk-card-title {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0
        }

        .bk-logo {
            flex-shrink: 0;
            display: grid;
            place-items: center;
            width: 50px;
            height: 50px;
            overflow: hidden;
            border: 1px solid #eceef2;
            border-radius: 13px;
            background: #fff;
            color: var(--bk-red);
            font-size: 22px
        }

        .bk-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .bk-name-wrap {
            min-width: 0;
            flex: 1
        }

        .bk-name {
            margin: 0;
            color: #1b1d23;
            font-size: 15px;
            line-height: 1.35;
            font-weight: 850;
            overflow-wrap: anywhere
        }

        .bk-featured-text {
            display: block;
            margin-top: 4px;
            color: #a16207;
            font-size: 10px;
            font-weight: 800
        }

        .bk-cuisine-text {
            min-height: 18px;
            margin: 11px 0 12px;
            color: var(--bk-muted);
            font-size: 12px;
            line-height: 1.5
        }

        .bk-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 11px 0;
            border-top: 1px solid #f0f1f4;
            border-bottom: 1px solid #f0f1f4;
            color: #424650;
            font-size: 11px
        }

        .bk-rating {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #176b3a;
            font-weight: 900
        }

        .bk-rating span {
            color: #e6a000;
            font-size: 15px
        }

        .bk-detail-row {
            display: flex;
            flex-wrap: wrap;
            gap: 7px 12px;
            margin: 11px 0 13px;
            color: #5c606b;
            font-size: 11px;
            line-height: 1.45
        }

        .bk-detail-row span {
            display: inline-flex;
            align-items: center;
            gap: 5px
        }

        .bk-price {
            font-weight: 800;
            color: #282b33
        }

        .bk-card-actions {
            margin-top: auto
        }

        .bk-view {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 42px;
            padding: 10px;
            border-radius: 10px;
            background: var(--bk-red);
            color: #fff !important;
            font-size: 12px;
            font-weight: 850;
            transition: background .2s
        }

        .bk-view:hover {
            background: #bd1422
        }

        .bk-empty {
            display: none;
            grid-column: 1/-1;
            padding: 45px 20px;
            border: 1px dashed #d6dae2;
            border-radius: 14px;
            background: #fff;
            text-align: center;
            color: var(--bk-muted)
        }

        .bk-empty strong {
            display: block;
            margin-bottom: 7px;
            color: #20232b;
            font-size: 18px
        }

        .bk-pagination {
            display: flex;
            justify-content: center;
            margin-top: 28px
        }

        .bk-pagination nav {
            display: flex;
            justify-content: center
        }

        .bk-pagination nav a,
        .bk-pagination nav span {
            border-radius: 8px !important
        }

        .bk-promo {
            display: grid;
            grid-template-columns: 1.3fr repeat(3, 1fr);
            align-items: center;
            gap: 20px;
            margin-top: 34px;
            padding: 25px;
            border: 1px solid #2d2d34;
            border-radius: 18px;
            background: linear-gradient(120deg, #15151a, #282127);
            color: #fff
        }

        .bk-promo h3 {
            margin: 0;
            font-size: clamp(18px, 2vw, 24px);
            line-height: 1.3;
            font-weight: 900
        }

        .bk-promo h3 span {
            color: #ff4554
        }

        .bk-promo-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px
        }

        .bk-promo-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            border: 1px solid #e11d2e;
            border-radius: 12px;
            background: #e11d2e1a;
            font-size: 18px
        }

        .bk-promo-item strong,
        .bk-promo-item small {
            display: block
        }

        .bk-promo-item small {
            margin-top: 4px;
            color: #c7c9d0;
            font-size: 11px
        }

        @media(max-width:1200px) {
            .bk-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px
            }

            .bk-promo {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .bk-promo h3 {
                grid-column: 1/-1
            }
        }

        @media(max-width:900px) {
            .bk-hero {
                min-height: 320px;
                padding: 34px 5%
            }

            .bk-filterbar {
                align-items: stretch;
                flex-direction: column
            }

            .bk-sort {
                width: 100%
            }

            .bk-photo {
                height: 170px
            }

            .bk-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px
            }
        }

        @media(max-width:600px) {
            .bk-hero {
                min-height: 350px;
                padding: 27px 15px;
                background-position: 60% center
            }

            .bk-hero h1 {
                font-size: clamp(35px, 10vw, 48px);
                letter-spacing: -1.2px
            }

            .bk-hero p {
                font-size: 13px
            }

            .bk-search {
                display: grid;
                grid-template-columns: 1fr;
                gap: 4px;
                border-radius: 12px
            }

            .bk-search .bk-location {
                max-width: none;
                border-right: 0;
                border-bottom: 1px solid #eceef2
            }

            .bk-search input {
                padding: 12px 10px
            }

            .bk-search button {
                min-height: 42px
            }

            .bk-benefits {
                gap: 10px 14px
            }

            .bk-benefit {
                font-size: 10px
            }

            .bk-benefit-icon {
                width: 31px;
                height: 31px;
                font-size: 14px
            }

            .bk-container {
                padding: 25px 12px
            }

            .bk-filterbar {
                padding: 9px;
                border-radius: 12px
            }

            .bk-cuisine {
                padding: 9px 11px;
                font-size: 11px
            }

            .bk-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px
            }

            .bk-photo {
                height: 125px
            }

            .bk-status {
                left: 6px;
                top: 6px;
                padding: 5px 6px;
                font-size: 9px
            }

            .bk-discount {
                left: 6px;
                bottom: 6px;
                padding: 5px 6px;
                font-size: 9px
            }

            .bk-featured {
                right: 6px;
                top: 6px;
                padding: 5px 6px;
                font-size: 9px
            }

            .bk-favorite {
                right: 6px;
                bottom: 6px;
                width: 29px;
                height: 29px;
                font-size: 18px
            }

            .bk-card {
                border-radius: 12px
            }

            .bk-card-body {
                padding: 10px 9px
            }

            .bk-card-title {
                gap: 7px
            }

            .bk-logo {
                width: 37px;
                height: 37px;
                border-radius: 10px;
                font-size: 17px
            }

            .bk-name {
                font-size: 12px
            }

            .bk-featured-text {
                font-size: 9px
            }

            .bk-cuisine-text {
                font-size: 10px;
                margin: 8px 0;
                min-height: 30px
            }

            .bk-meta {
                padding: 8px 0;
                font-size: 9px;
                gap: 4px
            }

            .bk-rating span {
                font-size: 12px
            }

            .bk-detail-row {
                gap: 5px;
                margin: 8px 0 10px;
                font-size: 9px
            }

            .bk-view {
                min-height: 37px;
                padding: 8px 4px;
                font-size: 10px
            }

            .bk-promo {
                grid-template-columns: 1fr;
                padding: 18px 14px;
                gap: 15px
            }

            .bk-promo h3 {
                grid-column: auto
            }

            .bk-promo-item {
                font-size: 11px
            }
        }

        @media(max-width:360px) {
            .bk-grid {
                grid-template-columns: 1fr
            }

            .bk-photo {
                height: 180px
            }

            .bk-card-body {
                padding: 13px
            }

            .bk-name {
                font-size: 14px
            }

            .bk-cuisine-text {
                min-height: 0;
                font-size: 11px
            }

            .bk-meta {
                font-size: 11px
            }

            .bk-detail-row {
                font-size: 10px
            }

            .bk-view {
                font-size: 12px
            }
        }
    </style>
    <style>
        .bk-favorite {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 5;

            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 50%;

            background: rgba(255, 255, 255, 0.95);

            color: #333;
            font-size: 25px;

            cursor: pointer;

            transition: all 0.25s ease;
        }

        .bk-favorite:hover {
            transform: scale(1.12);
        }

        .bk-favorite.active {
            color: #e53935;
            background: #fff0f0;
        }

        .bk-favorite:disabled {
            opacity: 0.6;
            cursor: wait;
        }
    </style>

    <div class="bk-restaurants">
        <section class="bk-hero" aria-labelledby="bk-restaurant-heading">
            <div class="bk-hero-content">
                <div class="bk-eyebrow"><i></i> GOOD FOOD. GREAT MOOD.</div>
                <h1 id="bk-restaurant-heading">Find your next<br><span>favourite meal.</span></h1>
                <p>Explore local restaurants, compare ratings and prices, and discover delicious food for every craving.</p>
                <form class="bk-search" id="bkRestaurantSearchForm" role="search">
                    <input class="bk-location" id="bkDeliveryLocation" type="text"
                        placeholder="📍 Delivery location (optional)" aria-label="Delivery location">
                    <input id="bkRestaurantSearch" type="search" placeholder="Search restaurants or cuisines..."
                        aria-label="Search restaurants or cuisines">
                    <button type="submit">Search restaurants&nbsp; →</button>
                </form>
                <div class="bk-benefits">
                    <div class="bk-benefit"><span class="bk-benefit-icon">🍽</span><span>Local favourites</span></div>
                    <div class="bk-benefit"><span class="bk-benefit-icon">🛵</span><span>Delivery details</span></div>
                    <div class="bk-benefit"><span class="bk-benefit-icon">🏷</span><span>Restaurant offers</span></div>
                    <div class="bk-benefit"><span class="bk-benefit-icon">♡</span><span>Save favourites</span></div>
                </div>
            </div>
        </section>

        <main class="bk-container">
            <div class="bk-heading">
                <div>
                    <h2>Explore Restaurants</h2>
                    <p>Choose a restaurant that suits your taste and budget.</p>
                </div>
                <div class="bk-count"><span id="bkVisibleCount">{{ $restaurants->count() }}</span> restaurants on this page
                </div>
            </div>

            <div class="bk-filterbar">
                <div class="bk-cuisines" aria-label="Filter by cuisine">

                    <button type="button" class="bk-cuisine active" data-cuisine="all" aria-pressed="true">
                        All restaurants
                    </button>

                    @foreach ($cuisines as $cuisine)
                        <button type="button" class="bk-cuisine" data-cuisine="{{ strtolower(trim($cuisine)) }}"
                            aria-pressed="false">
                            {{ $cuisine }}
                        </button>
                    @endforeach

                </div>
                {{-- <select class="bk-sort" id="bkRestaurantSort" aria-label="Sort restaurants">
                    <option value="default">Sort: Relevance</option>
                    <option value="rating">Sort: Highest rated</option>
                    <option value="offer">Sort: Best offers</option>
                    <option value="price-low">Sort: Price low to high</option>
                    <option value="price-high">Sort: Price high to low</option>
                    <option value="name">Sort: Name A–Z</option>
                </select> --}}
            </div>

            <div class="bk-grid" id="bkRestaurantGrid">
                @forelse ($restaurants as $index => $restaurant)
                    <article class="bk-card" data-name="{{ strtolower($restaurant->name ?? '') }}"
                        data-categories="{{ strtolower($restaurant->cuisine_type ?? '') }}"
                        data-rating="{{ (float) ($restaurant->average_rating ?? 0) }}"
                        data-offer="{{ (float) ($restaurant->discount_percentage ?? 0) }}"
                        data-price="{{ (float) ($restaurant->price_for_two ?? 0) }}" data-index="{{ $index }}">
                        <div class="bk-photo">
                            <img src="{{ !empty($restaurant->cover_image) ? asset($restaurant->cover_image) : asset('images/restaurant-placeholder.jpg') }}"
                                alt="{{ $restaurant->name }} restaurant" loading="{{ $index < 4 ? 'eager' : 'lazy' }}"
                                onerror="this.onerror=null;this.src='https://placehold.co/750x420/17191f/ffffff?text=BAKASURAN';">
                            @if ($restaurant->is_open)
                                <span class="bk-status">● OPEN NOW</span>
                            @else
                                <span class="bk-status closed">● CLOSED</span>
                            @endif
                            @if ((float) ($restaurant->discount_percentage ?? 0) > 0)
                                <span class="bk-discount">{{ (float) $restaurant->discount_percentage }}% OFF</span>
                            @endif
                            @if ($restaurant->is_featured ?? false)
                                <span class="bk-featured">★ FEATURED</span>
                            @endif
                            @php
                                $isFavorite = auth()->check()
                                    ? auth()->user()->favoriteRestaurants()
                                        ->where('restaurants.id', $restaurant->id)
                                        ->exists()
                                    : false;
                            @endphp

                            <button class="bk-favorite {{ $isFavorite ? 'active' : '' }}" type="button"
                                data-restaurant-id="{{ $restaurant->id }}"
                                aria-label="{{ $isFavorite ? 'Remove from' : 'Add to' }} favourites"
                                aria-pressed="{{ $isFavorite ? 'true' : 'false' }}"
                                title="{{ $isFavorite ? 'Remove from favourites' : 'Add to favourites' }}">

                                {{ $isFavorite ? '♥' : '♡' }}

                            </button>
                        </div>

                        <div class="bk-card-body">
                            <div class="bk-card-title">
                                <div class="bk-logo">
                                    @if (!empty($restaurant->logo))
                                        <img src="{{ asset($restaurant->logo) }}" alt="{{ $restaurant->name }} logo" loading="lazy"
                                            onerror="this.onerror=null;this.parentElement.textContent='🍽';">
                                    @else
                                        🍽️
                                    @endif
                                </div>
                                <div class="bk-name-wrap">
                                    <h3 class="bk-name">{{ $restaurant->name }}</h3>
                                    @if ($restaurant->is_featured ?? false)
                                        <span class="bk-featured-text">Recommended restaurant</span>
                                    @endif
                                </div>
                            </div>

                            <p class="bk-cuisine-text">{{ $restaurant->cuisine_type ?: 'Various cuisines' }}</p>

                            <div class="bk-meta">
                                <span class="bk-rating"><span>★</span>
                                    {{ number_format((float) ($restaurant->average_rating ?? 0), 1) }}</span>
                                <span>🛵
                                    {{ $restaurant->delivery_time ? $restaurant->delivery_time . ' mins' : 'Time varies' }}</span>
                            </div>

                            <div class="bk-detail-row">
                                @if (!is_null($restaurant->price_for_two ?? null))
                                    <span class="bk-price">👥 ₹{{ number_format((float) $restaurant->price_for_two, 0) }} for
                                        two</span>
                                @endif
                                @if (!is_null($restaurant->delivery_fee ?? null))
                                    <span>🛵 ₹{{ number_format((float) $restaurant->delivery_fee, 0) }} delivery</span>
                                @endif
                                @if (!is_null($restaurant->minimum_order ?? null) && (float) $restaurant->minimum_order > 0)
                                    <span>Minimum order ₹{{ number_format((float) $restaurant->minimum_order, 0) }}</span>
                                @endif
                            </div>

                            <div class="bk-card-actions">
                                <a href="{{ route('restaurant.details', ['slug' => $restaurant->slug]) }}" class="bk-view">View
                                    restaurant <span aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bk-empty" style="display:block">
                        <strong>No restaurants available yet</strong>
                        Please check back soon for more options.
                    </div>
                @endforelse
                <div class="bk-empty" id="bkRestaurantEmpty">
                    <strong>No matching restaurants</strong>
                    Try a different search term or cuisine filter.
                </div>
            </div>

            @if ($restaurants->hasPages())
                <div class="bk-pagination">{{ $restaurants->links() }}</div>
            @endif

            <section class="bk-promo" aria-label="Why choose BAKASURAN">
                <h3>Why choose <span>BAKASURAN?</span></h3>
                <div class="bk-promo-item"><span class="bk-promo-icon">🍽</span>
                    <div><strong>Great variety</strong><small>Restaurants for every craving</small></div>
                </div>
                <div class="bk-promo-item"><span class="bk-promo-icon">🛵</span>
                    <div><strong>Delivery details</strong><small>See time and fees upfront</small></div>
                </div>
                <div class="bk-promo-item"><span class="bk-promo-icon">🏷</span>
                    <div><strong>Offers & savings</strong><small>Spot available restaurant discounts</small></div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const grid = document.getElementById('bkRestaurantGrid');
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll('.bk-card'));
            const searchForm = document.getElementById('bkRestaurantSearchForm');
            const searchInput = document.getElementById('bkRestaurantSearch');
            const sortSelect = document.getElementById('bkRestaurantSort');
            const cuisineButtons = Array.from(document.querySelectorAll('.bk-cuisine'));
            const emptyMessage = document.getElementById('bkRestaurantEmpty');
            const visibleCount = document.getElementById('bkVisibleCount');
            let selectedCuisine = 'all';
            let searchTerm = '';

            function updateRestaurants() {
                const visibleCards = [];
                cards.forEach(function (card) {
                    const name = (card.dataset.name || '').toLowerCase();
                    const categories = (card.dataset.categories || '').toLowerCase();
                    const matchesSearch = !searchTerm || name.includes(searchTerm) || categories.includes(searchTerm);
                    const matchesCuisine = selectedCuisine === 'all' || categories.includes(selectedCuisine.toLowerCase());
                    const show = matchesSearch && matchesCuisine;
                    card.hidden = !show;
                    if (show) visibleCards.push(card);
                });

                visibleCards.sort(function (a, b) {
                    switch (sortSelect.value) {
                        case 'rating': return Number(b.dataset.rating || 0) - Number(a.dataset.rating || 0);
                        case 'offer': return Number(b.dataset.offer || 0) - Number(a.dataset.offer || 0);
                        case 'price-low': return Number(a.dataset.price || 0) - Number(b.dataset.price || 0);
                        case 'price-high': return Number(b.dataset.price || 0) - Number(a.dataset.price || 0);
                        case 'name': return (a.dataset.name || '').localeCompare(b.dataset.name || '');
                        default: return Number(a.dataset.index || 0) - Number(b.dataset.index || 0);
                    }
                });
                visibleCards.forEach(function (card) { grid.insertBefore(card, emptyMessage); });
                emptyMessage.style.display = visibleCards.length ? 'none' : 'block';
                visibleCount.textContent = visibleCards.length;
            }

            searchInput.addEventListener('input', function () {
                searchTerm = this.value.trim().toLowerCase();
                updateRestaurants();
            });
            searchForm.addEventListener('submit', function (event) {
                event.preventDefault();
                searchTerm = searchInput.value.trim().toLowerCase();
                updateRestaurants();
                document.querySelector('.bk-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            cuisineButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    cuisineButtons.forEach(function (item) {
                        item.classList.remove('active');
                        item.setAttribute('aria-pressed', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-pressed', 'true');
                    selectedCuisine = this.dataset.cuisine;
                    updateRestaurants();
                });
            });
            sortSelect.addEventListener('change', updateRestaurants);
            grid.querySelectorAll('.bk-favorite').forEach(function (button) {
                button.addEventListener('click', function () {
                    const active = this.classList.toggle('active');
                    this.textContent = active ? '♥' : '♡';
                    this.setAttribute('aria-pressed', String(active));
                    this.setAttribute('aria-label', active ? 'Remove from favourites' : 'Add to favourites');
                });
            });
            updateRestaurants();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const favoriteButtons = document.querySelectorAll('.bk-favorite');

            favoriteButtons.forEach(function (button) {

                button.addEventListener('click', async function () {

                    const restaurantId = this.dataset.restaurantId;

                    const buttonElement = this;

                    buttonElement.disabled = true;

                    try {

                        const response = await fetch(
                            "{{ route('favorite-restaurants.toggle') }}",
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },

                                body: JSON.stringify({
                                    restaurant_id: restaurantId
                                })
                            }
                        );

                        if (response.status === 401 || response.redirected) {
                            window.location.href = "{{ route('login') }}";
                            return;
                        }

                        const data = await response.json();

                        if (!response.ok || !data.status) {
                            throw new Error(data.message || 'Unable to update favourite.');
                        }

                        if (data.is_favorite) {

                            buttonElement.classList.add('active');
                            buttonElement.innerHTML = '♥';
                            buttonElement.setAttribute('aria-pressed', 'true');
                            buttonElement.setAttribute('aria-label', 'Remove from favourites');

                        } else {

                            buttonElement.classList.remove('active');
                            buttonElement.innerHTML = '♡';
                            buttonElement.setAttribute('aria-pressed', 'false');
                            buttonElement.setAttribute('aria-label', 'Add to favourites');
                        }

                        // Optional toast notification
                        if (typeof Swal !== 'undefined') {

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            });

                        } else {

                            console.log(data.message);
                        }

                    } catch (error) {

                        console.error('Favorite error:', error);

                        alert(error.message || 'Something went wrong. Please try again.');

                    } finally {

                        buttonElement.disabled = false;
                    }
                });
            });

        });
    </script>
@endsection