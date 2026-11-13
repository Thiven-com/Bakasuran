@extends('layouts.website')

@section('content')

    @php
        $restaurantName = $restaurant->name ?? 'Restaurant';
        $restaurantRating = (float) ($restaurant->average_rating ?? 0);
        $restaurantImage = $restaurant->cover_image
            ? (\Illuminate\Support\Str::startsWith($restaurant->cover_image, ['http://', 'https://'])
                ? $restaurant->cover_image
                : asset($restaurant->cover_image))
            : 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=2000&q=90';
        $restaurantLogo = $restaurant->logo
            ? (\Illuminate\Support\Str::startsWith($restaurant->logo, ['http://', 'https://'])
                ? $restaurant->logo
                : asset($restaurant->logo))
            : null;
    @endphp

    <style>
        .rd-page {
            --rd-accent: #e11d2e;
            --rd-ink: #18181b;
            --rd-muted: #71717a;
            --rd-line: #e8e8ed;
            background: #f7f7f9;
            color: var(--rd-ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            padding-bottom: 36px
        }

        .rd-page * {
            box-sizing: border-box
        }

        .rd-page button,
        .rd-page input {
            font: inherit
        }

        .rd-page button {
            cursor: pointer
        }

        .rd-page a {
            text-decoration: none
        }

        .rd-breadcrumb {
            padding: 14px clamp(16px, 4vw, 64px);
            background: #fff;
            color: var(--rd-muted);
            font-size: 12px
        }

        .rd-breadcrumb a {
            color: #3f3f46
        }

        .rd-breadcrumb span {
            margin: 0 8px;
            color: var(--rd-accent)
        }

        .rd-hero {
            position: relative;
            display: flex;
            align-items: center;
            gap: 28px;
            min-height: 290px;
            overflow: hidden;
            padding: 38px clamp(18px, 4vw, 64px) 26px;
            color: #fff;
            /* background: linear-gradient(90deg, rgba(12, 12, 15, .97), rgba(12, 12, 15, .82) 55%, rgba(12, 12, 15, .32)), url('{{ $restaurantImage }}') center/cover no-repeat */
            background: linear-gradient(90deg, rgba(12, 12, 15, .97), rgba(12, 12, 15, .82) 55%, rgba(12, 12, 15, .32)), url('{{ $restaurantImage }}') center/cover no-repeat
        }

        .rd-hero-main {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 24px;
            width: 74%;
            min-width: 0
        }

        .rd-logo {
            display: flex;
            flex-shrink: 0;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            border: 3px solid #fff;
            border-radius: 22px;
            background: #fff;
            color: var(--rd-accent);
            text-align: center;
            box-shadow: 0 12px 35px #0004
        }

        .rd-logo .rd-logo-icon {
            font-size: 34px
        }

        .rd-logo strong {
            color: var(--rd-ink);
            font-size: 15px;
            font-style: normal;
            letter-spacing: -.4px
        }

        .rd-logo small {
            color: var(--rd-muted);
            font-size: 6px;
            letter-spacing: 1px
        }

        .rd-info {
            min-width: 0
        }

        .rd-title-line {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap
        }

        .rd-title-line h1 {
            margin: 0;
            font-size: clamp(25px, 2.6vw, 38px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.12
        }

        .rd-open {
            padding: 7px 11px;
            border-radius: 999px;
            background:
                {{ ($restaurant->is_open ?? false) ? '#dcfce7' : '#fee2e2' }}
            ;
            color:
                {{ ($restaurant->is_open ?? false) ? '#166534' : '#991b1b' }}
            ;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap
        }

        .rd-meta {
            display: flex;
            align-items: center;
            gap: 8px 12px;
            flex-wrap: wrap;
            margin: 13px 0;
            color: #e4e4e7;
            font-size: 12px
        }

        .rd-rating-star {
            color: #fbbf24;
            font-size: 17px
        }

        .rd-meta-divider {
            color: #a1a1aa
        }

        .rd-tags,
        .rd-deals {
            display: flex;
            gap: 7px;
            flex-wrap: wrap
        }

        .rd-tags {
            margin-top: 10px
        }

        .rd-tag {
            padding: 7px 11px;
            border: 1px solid #ffffff30;
            border-radius: 999px;
            background: #ffffff14;
            color: #fff;
            font-size: 11px
        }

        .rd-featured-label {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            font-weight: 800
        }

        .rd-deals {
            margin-top: 14px
        }

        .rd-deal {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 11px 6px 6px;
            border: 1px solid #ffffff35;
            border-radius: 12px;
            background: #ffffff12;
            font-size: 11px
        }

        .rd-deal b {
            padding: 8px 10px;
            border-radius: 8px;
            background: var(--rd-accent);
            white-space: nowrap
        }

        .rd-deal small {
            display: block;
            margin-top: 3px;
            color: #d4d4d8
        }

        .rd-booking-action {
            margin-top: 15px
        }

        .rd-booking-action a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            border: 1px solid var(--rd-accent);
            border-radius: 11px;
            background: var(--rd-accent);
            color: #fff !important;
            transition: background .2s
        }

        .rd-booking-action a:hover {
            background: #be123c
        }

        .rd-booking-icon {
            font-size: 22px
        }

        .rd-booking-action strong,
        .rd-booking-action small {
            display: block
        }

        .rd-booking-action strong {
            font-size: 13px
        }

        .rd-booking-action small {
            margin-top: 3px;
            color: #ffe4e6;
            font-size: 10px
        }

        .rd-gallery {
            position: relative;
            z-index: 2;
            width: 25%;
            min-width: 180px;
            max-width: 270px;
            margin-left: auto
        }

        .rd-gallery-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px
        }

        .rd-gallery img,
        .rd-gallery-button {
            width: 100%;
            height: 76px;
            border: 1px solid #ffffff80;
            border-radius: 12px;
            object-fit: cover
        }

        .rd-gallery-button {
            background: #18181b;
            color: #fff;
            font-size: 11px;
            font-weight: 800
        }

        .rd-hero-actions {
            position: absolute;
            top: 14px;
            right: 20px;
            z-index: 3;
            display: flex;
            gap: 8px
        }

        .rd-hero-actions button {
            padding: 8px 11px;
            border: 1px solid #ffffff40;
            border-radius: 999px;
            background: #1119;
            color: #fff;
            backdrop-filter: blur(8px)
        }

        .rd-benefits {
            display: flex;
            gap: 0;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #ffffff25
        }

        .rd-benefit {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0 12px;
            border-right: 1px solid #ffffff25;
            font-size: 10px;
            line-height: 1.4
        }

        .rd-benefit:first-child {
            padding-left: 0
        }

        .rd-benefit:last-child {
            border: 0
        }

        .rd-benefit-icon {
            display: grid;
            flex-shrink: 0;
            place-items: center;
            width: 34px;
            height: 34px;
            border: 1px solid #fb7185;
            border-radius: 50%;
            color: #fb7185;
            font-size: 18px
        }

        .rd-tabs {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 12px;
            overflow-x: auto;
            padding: 0 clamp(12px, 3vw, 48px);
            border-bottom: 1px solid var(--rd-line);
            background: #fff;
            box-shadow: 0 4px 18px #18181b08
        }

        .rd-tab {
            flex-shrink: 0;
            padding: 17px 11px 14px;
            border: 0;
            border-bottom: 3px solid transparent;
            background: transparent;
            color: #52525b;
            font-size: 12px;
            font-weight: 750;
            white-space: nowrap
        }

        .rd-tab.active {
            border-bottom-color: var(--rd-accent);
            color: var(--rd-accent)
        }

        .rd-tab-link {
            display: inline-flex;
            align-items: center;
            color: var(--rd-accent)
        }

        .rd-layout {
            display: grid;
            grid-template-columns: 210px minmax(0, 1fr) 310px;
            gap: 18px;
            align-items: start;
            padding: 22px clamp(12px, 2.5vw, 36px)
        }

        .rd-category-panel,
        .rd-cart-panel {
            position: sticky;
            top: 66px;
            padding: 15px;
            border: 1px solid var(--rd-line);
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 8px 28px #18181b08
        }

        .rd-panel-title,
        .rd-cart-heading h2 {
            margin: 0 0 12px;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.3px
        }

        .rd-category-list {
            display: flex;
            flex-direction: column;
            gap: 4px
        }

        .rd-category {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: 11px 10px;
            border: 0;
            border-radius: 10px;
            background: #fff;
            color: #3f3f46;
            text-align: left;
            font-size: 12px
        }

        .rd-category.active {
            background: #fff1f2;
            color: var(--rd-accent);
            font-weight: 800
        }

        .rd-category .rd-count {
            margin-left: auto;
            color: #a1a1aa;
            font-size: 10px
        }

        .rd-category.active .rd-count {
            color: var(--rd-accent)
        }

        .rd-category-promo {
            display: flex;
            align-items: center;
            min-height: 110px;
            margin-top: 14px;
            padding: 18px 12px;
            border-radius: 12px;
            background: linear-gradient(90deg, #000b, #0005), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=500&q=80') center/cover;
            color: #fff;
            font-size: 17px;
            font-weight: 900;
            font-style: italic
        }

        .rd-category-promo span {
            color: #fb7185
        }

        .rd-menu-area {
            min-width: 0
        }

        .rd-menu-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 100px;
            padding: 14px;
            border-radius: 15px;
            background: linear-gradient(90deg, #111827f2, #1118279c), url('https://images.unsplash.com/photo-1563379091339-03246963d96c?auto=format&fit=crop&w=1000&q=85') center/cover;
            color: #fff
        }

        .rd-menu-banner img {
            width: 215px;
            height: 72px;
            border-radius: 10px;
            object-fit: cover
        }

        .rd-menu-banner h2 {
            margin: 0 0 5px;
            font-size: 22px;
            letter-spacing: -.5px
        }

        .rd-menu-banner p {
            margin: 0;
            color: #e4e4e7;
            font-size: 11px;
            line-height: 1.5
        }

        .rd-menu-search {
            width: 200px;
            max-width: 38%;
            margin-left: auto;
            padding: 11px 12px;
            border: 0;
            border-radius: 9px;
            outline-color: var(--rd-accent);
            font-size: 11px !important
        }

        .rd-menu-list {
            display: flex;
            flex-direction: column;
            gap: 11px;
            margin-top: 12px
        }

        .rd-dish {
            display: grid;
            grid-template-columns: 145px minmax(0, 1fr) 92px;
            align-items: center;
            gap: 14px;
            min-height: 128px;
            padding: 11px;
            border: 1px solid var(--rd-line);
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 5px 18px #18181b05;
            transition: box-shadow .2s, transform .2s
        }

        .rd-dish:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px #18181b0c
        }

        .rd-dish-photo {
            position: relative;
            height: 104px
        }

        .rd-dish-photo img {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 11px;
            object-fit: cover
        }

        .rd-dish-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            padding: 5px 7px;
            border-radius: 5px;
            background: var(--rd-accent);
            color: #fff;
            font-size: 9px;
            font-weight: 800
        }

        .rd-dish-info {
            min-width: 0
        }

        .rd-dish-name-row {
            display: flex;
            align-items: center;
            gap: 7px
        }

        .rd-dish-name {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -.2px
        }

        .rd-veg-mark {
            display: inline-grid;
            place-items: center;
            flex-shrink: 0;
            width: 13px;
            height: 13px;
            border: 1px solid #159447;
            color: #159447;
            font-size: 7px
        }

        .rd-veg-mark.nonveg {
            border-color: #ef3333;
            color: #ef3333
        }

        .rd-dish-description {
            margin: 6px 0;
            color: var(--rd-muted);
            font-size: 11px;
            line-height: 1.5
        }

        .rd-dish-price {
            font-size: 14px;
            font-weight: 850
        }

        .rd-dish-rating {
            margin-top: 4px;
            color: var(--rd-muted);
            font-size: 11px
        }

        .rd-dish-rating span {
            color: #f59e0b;
            font-size: 15px
        }

        /* Admin-managed product details */
        .rd-product-flags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin: 8px 0
        }

        .rd-product-flag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 8px;
            border-radius: 6px;
            background: #f4f4f5;
            color: #52525b;
            font-size: 9px;
            font-weight: 800
        }

        .rd-product-flag.recommended {
            background: #fff1f2;
            color: #c11224
        }

        .rd-product-flag.available {
            background: #dcfce7;
            color: #166534
        }

        .rd-product-flag.unavailable {
            background: #fee2e2;
            color: #991b1b
        }

        .rd-product-flag.food {
            background: #fff7ed;
            color: #9a3412
        }

        .rd-product-flag.prep {
            background: #eff6ff;
            color: #1d4ed8
        }

        .rd-variant-select-wrap {
            margin-top: 9px;
            max-width: 250px
        }

        .rd-variant-select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #e4e4e7;
            border-radius: 8px;
            background: #fff;
            color: #27272a;
            font-size: 11px;
            outline-color: #e11d2e
        }

        .rd-variant-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 6px;
            color: #71717a;
            font-size: 10px
        }

        .rd-dish-actions .rd-add:disabled {
            background: #a1a1aa;
            cursor: not-allowed
        }

        .rd-variant-price-old {
            margin-left: 6px;
            color: #a1a1aa;
            text-decoration: line-through;
            font-size: 11px;
            font-weight: 500
        }

        @media(max-width:760px) {
            .rd-variant-select-wrap {
                max-width: 100%
            }
        }

        .rd-dish-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px
        }

        .rd-fav {
            border: 0;
            background: transparent;
            color: var(--rd-accent);
            font-size: 22px;
            line-height: 1
        }

        .rd-fav.active {
            color: #be123c
        }

        .rd-add {
            width: 100%;
            padding: 10px 9px;
            border: 0;
            border-radius: 9px;
            background: var(--rd-accent);
            color: #fff;
            font-size: 11px !important;
            font-weight: 800
        }

        .rd-add:hover,
        .rd-checkout:hover {
            background: #be123c
        }

        .rd-qty {
            display: none;
            align-items: center;
            overflow: hidden;
            border: 1px solid #fecdd3;
            border-radius: 9px
        }

        .rd-qty button {
            width: 29px;
            height: 32px;
            border: 0;
            background: var(--rd-accent);
            color: #fff;
            font-size: 17px
        }

        .rd-qty span {
            min-width: 30px;
            text-align: center;
            font-size: 12px;
            font-weight: 800
        }

        .rd-cart-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 13px;
            border-bottom: 1px solid var(--rd-line)
        }

        .rd-cart-heading h2 {
            margin: 0
        }

        .rd-cart-heading small {
            font-size: 10px;
            font-weight: 400
        }

        .rd-clear {
            border: 0;
            background: transparent;
            color: var(--rd-accent);
            font-size: 10px !important;
            font-weight: 800
        }

        .rd-cart-items {
            max-height: 280px;
            overflow-y: auto
        }

        .rd-cart-empty {
            margin-top: 12px;
            padding: 25px 10px;
            border-radius: 12px;
            background: #fafafa;
            color: var(--rd-muted);
            text-align: center;
            font-size: 11px;
            line-height: 1.7
        }

        .rd-cart-empty span {
            display: block;
            margin-bottom: 8px;
            font-size: 30px
        }

        .rd-cart-item {
            display: grid;
            grid-template-columns: 54px minmax(0, 1fr) auto;
            align-items: center;
            gap: 8px;
            padding: 11px 0;
            border-bottom: 1px solid #f0f0f2
        }

        .rd-cart-item img {
            width: 54px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover
        }

        .rd-cart-item-name {
            font-size: 11px;
            font-weight: 750;
            line-height: 1.4
        }

        .rd-cart-item-price {
            margin-top: 4px;
            font-size: 11px;
            font-weight: 800
        }

        .rd-cart-qty {
            display: flex;
            align-items: center;
            overflow: hidden;
            border: 1px solid #fecdd3;
            border-radius: 6px
        }

        .rd-cart-qty button {
            width: 22px;
            height: 26px;
            border: 0;
            background: #fff;
            color: var(--rd-accent);
            font-weight: 800
        }

        .rd-cart-qty span {
            min-width: 19px;
            text-align: center;
            font-size: 10px
        }

        .rd-cart-delete {
            grid-column: 3;
            border: 0;
            background: transparent;
            color: #a1a1aa;
            font-size: 13px;
            padding: 2px
        }

        .rd-coupon {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 13px 0;
            border: 0;
            border-bottom: 1px solid var(--rd-line);
            background: #fff;
            color: #3f3f46;
            text-align: left;
            font-size: 11px !important
        }

        .rd-coupon-icon {
            padding: 4px 6px;
            border-radius: 4px;
            background: #fff1f2;
            color: var(--rd-accent)
        }

        .rd-coupon span:last-child {
            margin-left: auto;
            color: var(--rd-accent)
        }

        .rd-coupon-input {
            display: none;
            gap: 6px;
            padding: 9px 0
        }

        .rd-coupon-input input {
            width: 100%;
            min-width: 0;
            padding: 8px;
            border: 1px solid #d4d4d8;
            border-radius: 7px;
            font-size: 11px !important
        }

        .rd-coupon-input button {
            padding: 8px 10px;
            border: 0;
            border-radius: 7px;
            background: var(--rd-accent);
            color: #fff;
            font-size: 11px !important
        }

        .rd-cart-totals {
            padding-top: 8px
        }

        .rd-total-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 10px 0;
            color: #52525b;
            font-size: 11px
        }

        .rd-total-row strong {
            color: var(--rd-ink)
        }

        .rd-total-row.final {
            padding-top: 12px;
            border-top: 1px solid var(--rd-line);
            color: var(--rd-ink);
            font-size: 15px;
            font-weight: 850
        }

        .rd-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 10px;
            padding: 13px 10px;
            border: 0;
            border-radius: 10px;
            background: var(--rd-accent);
            color: #fff;
            font-size: 12px !important;
            font-weight: 850
        }

        .rd-checkout:disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .rd-saved {
            margin: 11px 0 0;
            color: #15803d;
            text-align: center;
            font-size: 10px
        }

        .rd-no-results {
            display: none;
            padding: 24px;
            border: 1px dashed #d4d4d8;
            border-radius: 12px;
            color: var(--rd-muted);
            text-align: center;
            font-size: 12px
        }

        .rd-extra-panel {
            display: none;
            grid-column: 1/-1;
            min-height: 220px;
            padding: 26px;
            border: 1px solid var(--rd-line);
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 8px 28px #18181b08;
            line-height: 1.8
        }

        .rd-extra-panel h2 {
            margin-top: 0;
            letter-spacing: -.5px
        }

        .rd-field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
            margin: 16px 0
        }

        .rd-field {
            min-width: 0;
            padding: 13px;
            border: 1px solid var(--rd-line);
            border-radius: 11px;
            background: #fafafa
        }

        .rd-field small {
            display: block;
            margin-bottom: 4px;
            color: var(--rd-muted);
            font-size: 10px
        }

        .rd-field strong {
            display: block;
            overflow-wrap: anywhere;
            color: var(--rd-ink);
            font-size: 12px
        }

        .rd-photo-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 10px
        }

        .rd-photo-strip img {
            width: calc(33.333% - 7px);
            height: 140px;
            border-radius: 11px;
            object-fit: cover
        }

        /* Restaurant information tabs */
        .rd-layout.rd-layout--tab-view {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
        }

        .rd-layout.rd-layout--tab-view .rd-menu-area {
            grid-column: 1 / -1;
            min-width: 0;
        }

        .rd-extra-panel {
            padding: clamp(18px, 3vw, 32px);
            border: 1px solid #e9e9ef;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 35px rgba(24, 24, 27, .045);
            line-height: 1.65;
        }

        .rd-extra-panel[style*="display: block"] {
            animation: rdPanelIn .22s ease both;
        }

        @keyframes rdPanelIn {
            from {
                opacity: 0;
                transform: translateY(7px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rd-section-heading {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 22px;
        }

        .rd-section-heading h2 {
            margin: 0 0 4px;
            color: #18181b;
            font-size: clamp(21px, 2.5vw, 28px);
            font-weight: 850;
            letter-spacing: -.8px;
        }

        .rd-section-heading p {
            margin: 0;
            color: #777782;
            font-size: 13px;
        }

        .rd-section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 8px;
            color: #e11d2e;
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .rd-about-intro {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(220px, .85fr);
            gap: 22px;
            align-items: stretch;
        }

        .rd-about-copy {
            padding: 22px;
            border-radius: 15px;
            background: linear-gradient(135deg, #fff5f5, #fff);
            border: 1px solid #ffe1e3;
        }

        .rd-about-copy h3 {
            margin: 0 0 9px;
            font-size: 18px;
            font-weight: 800;
        }

        .rd-about-copy p {
            margin: 0;
            color: #62626c;
            font-size: 13px;
            line-height: 1.85;
        }

        .rd-about-highlight {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .rd-field {
            padding: 15px;
            border: 1px solid #ededf1;
            border-radius: 13px;
            background: #fff;
        }

        .rd-field small {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .45px;
        }

        .rd-field strong {
            font-size: 13px;
            line-height: 1.5;
        }

        .rd-about-details {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 11px;
            margin-top: 13px;
        }

        .rd-info-line {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 15px;
            border: 1px solid #ededf1;
            border-radius: 13px;
            background: #fff;
        }

        .rd-info-icon {
            display: grid;
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 11px;
            background: #fff0f1;
            color: #e11d2e;
            font-size: 17px;
        }

        .rd-info-line small {
            display: block;
            margin-bottom: 3px;
            color: #85858e;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .rd-info-line strong {
            display: block;
            color: #292930;
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .rd-review-summary {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 20px;
            margin-bottom: 22px;
        }

        .rd-rating-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 22px;
            border-radius: 15px;
            background: #fff8e9;
            text-align: center;
        }

        .rd-rating-number {
            color: #202027;
            font-size: 42px;
            font-weight: 900;
            line-height: 1.1;
        }

        .rd-rating-stars {
            margin: 8px 0 4px;
            color: #f59e0b;
            font-size: 19px;
            letter-spacing: 2px;
        }

        .rd-rating-card small {
            color: #777782;
            font-size: 11px;
        }

        .rd-review-note {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 22px;
            border: 1px solid #ededf1;
            border-radius: 15px;
        }

        .rd-review-note h3 {
            margin: 0 0 7px;
            font-size: 16px;
        }

        .rd-review-note p {
            margin: 0;
            color: #777782;
            font-size: 13px;
        }

        .rd-empty-state {
            padding: 28px 18px;
            border: 1px dashed #dedee5;
            border-radius: 14px;
            background: #fcfcfd;
            text-align: center;
        }

        .rd-empty-icon {
            display: grid;
            width: 48px;
            height: 48px;
            place-items: center;
            margin: 0 auto 10px;
            border-radius: 50%;
            background: #fff0f1;
            color: #e11d2e;
            font-size: 22px;
        }

        .rd-empty-state h3 {
            margin: 0 0 5px;
            font-size: 15px;
        }

        .rd-empty-state p {
            margin: 0;
            color: #777782;
            font-size: 12px;
        }

        .rd-photo-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 13px;
        }

        .rd-photo-card {
            position: relative;
            overflow: hidden;
            min-height: 180px;
            border-radius: 14px;
            background: #f1f1f4;
        }

        .rd-photo-card img {
            display: block;
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .rd-photo-card:hover img {
            transform: scale(1.04);
        }

        .rd-photo-caption {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 24px 12px 11px;
            background: linear-gradient(transparent, rgba(0, 0, 0, .72));
            color: #fff;
            font-size: 12px;
            font-weight: 750;
        }

        .rd-offer-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .rd-offer-card {
            position: relative;
            overflow: hidden;
            padding: 21px;
            border: 1px solid #ffe0e3;
            border-radius: 15px;
            background: linear-gradient(120deg, #fff5f5, #fff 72%);
        }

        .rd-offer-card:after {
            content: '%';
            position: absolute;
            right: 15px;
            bottom: -25px;
            color: rgba(225, 29, 46, .07);
            font-size: 110px;
            font-weight: 900;
            line-height: 1;
        }

        .rd-offer-label {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 6px;
            background: #e11d2e;
            color: #fff;
            font-size: 10px;
            font-weight: 850;
        }

        .rd-offer-card h3 {
            position: relative;
            z-index: 1;
            margin: 12px 0 5px;
            font-size: 19px;
            font-weight: 900;
        }

        .rd-offer-card p {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #686872;
            font-size: 12px;
        }

        .rd-offer-meta {
            position: relative;
            z-index: 1;
            margin-top: 13px;
            color: #8a5960;
            font-size: 10px;
            font-weight: 700;
        }

        .rd-offer-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 14px;
        }

        .rd-offer-detail {
            padding: 14px;
            border: 1px solid #ededf1;
            border-radius: 12px;
            background: #fff;
        }

        .rd-offer-detail small {
            display: block;
            color: #85858e;
            font-size: 10px;
        }

        .rd-offer-detail strong {
            display: block;
            margin-top: 3px;
            font-size: 13px;
        }

        @media(max-width:700px) {

            .rd-about-intro,
            .rd-review-summary {
                grid-template-columns: 1fr;
            }

            .rd-about-details {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .rd-photo-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .rd-photo-card,
            .rd-photo-card img {
                min-height: 135px;
            }

            .rd-offer-grid,
            .rd-offer-details {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width:1200px) {
            .rd-layout {
                grid-template-columns: 185px minmax(0, 1fr)
            }

            .rd-cart-panel {
                position: static;
                grid-column: 1/-1
            }

            .rd-hero-main {
                width: 100%
            }

            .rd-gallery {
                display: none
            }
        }

        @media(max-width:760px) {
            .rd-hero {
                display: block;
                min-height: 0;
                padding: 40px 15px 18px
            }

            .rd-hero-main {
                align-items: flex-start;
                gap: 12px
            }

            .rd-logo {
                width: 72px;
                height: 72px;
                border-radius: 16px
            }

            .rd-logo .rd-logo-icon {
                font-size: 22px
            }

            .rd-logo strong {
                font-size: 9px
            }

            .rd-logo small {
                font-size: 5px
            }

            .rd-title-line h1 {
                font-size: 22px
            }

            .rd-meta {
                gap: 6px 9px;
                margin: 9px 0;
                font-size: 10px
            }

            .rd-tag {
                padding: 6px 8px;
                font-size: 10px
            }

            .rd-deal {
                font-size: 10px
            }

            .rd-benefits {
                overflow-x: auto;
                margin-top: 16px;
                padding-top: 14px
            }

            .rd-benefit {
                flex-shrink: 0;
                padding: 0 8px;
                font-size: 9px
            }

            .rd-benefit-icon {
                width: 29px;
                height: 29px;
                font-size: 15px
            }

            .rd-hero-actions {
                top: 6px;
                right: 8px
            }

            .rd-tabs {
                gap: 4px;
                padding: 0 8px
            }

            .rd-tab {
                padding: 14px 9px 11px;
                font-size: 11px
            }

            .rd-layout {
                display: flex;
                flex-direction: column;
                gap: 12px;
                padding: 12px
            }

            .rd-category-panel,
            .rd-menu-area,
            .rd-cart-panel {
                position: static;
                width: 100%
            }

            .rd-category-list {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 3px
            }

            .rd-category {
                flex-shrink: 0;
                width: auto;
                padding: 9px 10px;
                font-size: 11px
            }

            .rd-category .rd-count,
            .rd-category-promo {
                display: none
            }

            .rd-menu-banner {
                gap: 9px;
                min-height: 76px;
                padding: 9px
            }

            .rd-menu-banner img {
                width: 65px;
                height: 56px
            }

            .rd-menu-banner h2 {
                font-size: 17px
            }

            .rd-menu-banner p {
                font-size: 9px
            }

            .rd-menu-search {
                width: 42%;
                max-width: 42%;
                padding: 9px;
                font-size: 10px !important
            }

            .rd-dish {
                grid-template-columns: 94px minmax(0, 1fr);
                gap: 9px;
                padding: 9px
            }

            .rd-dish-photo {
                height: 94px
            }

            .rd-dish-name {
                font-size: 12px
            }

            .rd-dish-description {
                font-size: 10px;
                margin: 5px 0
            }

            .rd-dish-price {
                font-size: 12px
            }

            .rd-dish-actions {
                grid-column: 2;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin-top: -3px
            }

            .rd-add {
                width: 100px;
                padding: 9px
            }

            .rd-qty button {
                width: 27px;
                height: 29px
            }

            .rd-cart-items {
                max-height: none
            }

            .rd-extra-panel {
                padding: 17px
            }

            .rd-field-grid {
                grid-template-columns: 1fr
            }

            .rd-photo-strip img {
                width: calc(50% - 5px);
                height: 115px
            }
        }

        @media(max-width:380px) {
            .rd-logo {
                width: 60px;
                height: 60px
            }

            .rd-logo .rd-logo-icon {
                font-size: 18px
            }

            .rd-title-line h1 {
                font-size: 19px
            }

            .rd-meta {
                font-size: 9px
            }

            .rd-menu-banner {
                flex-wrap: wrap;
            }

            .rd-menu-search {
                display: block;
                width: 100%;
                max-width: 100%;
                margin: 8px 0 0;
                padding: 11px;
            }
        }

        /* =========================================================
               PREMIUM UI REFINEMENT
               Visual-only overrides. Existing Blade, routes and JS remain
               unchanged.
            ========================================================= */
        .rd-page {
            --rd-accent: #d7192d;
            --rd-accent-dark: #b91427;
            --rd-ink: #171923;
            --rd-muted: #707784;
            --rd-line: #e7e9ef;
            --rd-surface: #ffffff;
            --rd-soft: #f7f8fb;
            --rd-shadow: 0 10px 30px rgba(20, 25, 38, .055);
            --rd-shadow-hover: 0 16px 36px rgba(20, 25, 38, .095);
            background: #f5f6f9;
            color: var(--rd-ink);
            font-size: 14px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        .rd-page button,
        .rd-page input,
        .rd-page select,
        .rd-page textarea {
            font-family: inherit;
        }

        .rd-page button:focus-visible,
        .rd-page a:focus-visible,
        .rd-page input:focus-visible,
        .rd-page select:focus-visible {
            outline: 3px solid rgba(215, 25, 45, .22);
            outline-offset: 2px;
        }

        .rd-breadcrumb {
            padding: 13px clamp(18px, 5vw, 76px);
            color: #858b96;
            font-size: 12px;
            letter-spacing: .01em;
        }

        .rd-breadcrumb a {
            color: #4b5360;
            transition: color .2s ease;
        }

        .rd-breadcrumb a:hover {
            color: var(--rd-accent);
        }

        .rd-hero {
            min-height: 310px;
            padding: 44px clamp(20px, 5vw, 76px) 34px;
            background-position: center;
            background-size: cover;
        }

        .rd-hero-main {
            width: min(100%, 1120px);
            gap: clamp(18px, 2.5vw, 32px);
        }

        .rd-logo {
            width: 126px;
            height: 126px;
            border: 4px solid rgba(255, 255, 255, .96);
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .24);
        }

        .rd-title-line {
            gap: 14px;
        }

        .rd-title-line h1 {
            font-size: clamp(28px, 3vw, 42px);
            letter-spacing: -1.35px;
            line-height: 1.08;
        }

        .rd-open {
            padding: 7px 12px;
            font-size: 11px;
            letter-spacing: .01em;
        }

        .rd-meta {
            gap: 9px 14px;
            margin: 15px 0;
            font-size: 13px;
            color: rgba(255, 255, 255, .84);
        }

        .rd-tags,
        .rd-deals {
            gap: 9px;
        }

        .rd-tag {
            padding: 7px 12px;
            border-color: rgba(255, 255, 255, .22);
            border-radius: 999px;
            background: rgba(255, 255, 255, .10);
            font-size: 11px;
            backdrop-filter: blur(8px);
        }

        .rd-deal {
            gap: 11px;
            padding: 7px 13px 7px 7px;
            border-color: rgba(255, 255, 255, .20);
            border-radius: 13px;
            background: rgba(255, 255, 255, .10);
            font-size: 12px;
            backdrop-filter: blur(8px);
        }

        .rd-deal b {
            padding: 9px 11px;
            border-radius: 9px;
            font-size: 12px;
        }

        .rd-booking-action a {
            min-height: 48px;
            padding: 11px 17px;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(215, 25, 45, .24);
            transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .rd-booking-action a:hover {
            transform: translateY(-2px);
            background: var(--rd-accent-dark);
            box-shadow: 0 12px 22px rgba(215, 25, 45, .28);
        }

        .rd-hero-actions button {
            min-width: 40px;
            min-height: 40px;
            border-radius: 12px;
            background: rgba(12, 15, 22, .48);
            transition: background .2s ease, transform .2s ease;
        }

        .rd-hero-actions button:hover {
            transform: translateY(-1px);
            background: rgba(12, 15, 22, .75);
        }

        .rd-tabs {
            gap: clamp(8px, 1.5vw, 20px);
            padding: 0 clamp(18px, 5vw, 76px);
            border-bottom-color: #e8eaf0;
            box-shadow: 0 5px 18px rgba(20, 25, 38, .035);
        }

        .rd-tab {
            padding: 18px 13px 15px;
            border-bottom-width: 3px;
            color: #626a77;
            font-size: 13px;
            font-weight: 700;
            transition: color .2s ease, border-color .2s ease;
        }

        .rd-tab:hover {
            color: var(--rd-accent);
        }

        .rd-tab.active {
            color: var(--rd-accent);
        }

        .rd-layout {
            grid-template-columns: minmax(190px, 220px) minmax(0, 1fr) minmax(280px, 320px);
            gap: 22px;
            padding: 28px clamp(18px, 3vw, 48px) 42px;
        }

        .rd-category-panel,
        .rd-cart-panel {
            top: 84px;
            padding: 19px;
            border: 1px solid #e8eaf0;
            border-radius: 18px;
            box-shadow: var(--rd-shadow);
        }

        .rd-panel-title,
        .rd-cart-heading h2 {
            margin-bottom: 16px;
            color: #202431;
            font-size: 16px;
            letter-spacing: -.35px;
        }

        .rd-category-list {
            gap: 6px;
        }

        .rd-category {
            min-height: 45px;
            padding: 11px 12px;
            border-radius: 11px;
            color: #4e5663;
            font-size: 13px;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .rd-category:hover {
            transform: translateX(2px);
            background: #f8f1f2;
            color: var(--rd-accent);
        }

        .rd-category.active {
            background: #fff0f2;
            color: var(--rd-accent);
        }

        .rd-menu-banner {
            min-height: 112px;
            gap: 18px;
            padding: 18px 20px;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
        }

        .rd-menu-banner img {
            width: 190px;
            height: 76px;
            border-radius: 12px;
        }

        .rd-menu-banner h2 {
            margin-bottom: 6px;
            font-size: clamp(20px, 2vw, 25px);
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .rd-menu-banner p {
            max-width: 440px;
            font-size: 12px;
            line-height: 1.6;
            color: rgba(255, 255, 255, .82);
        }

        .rd-menu-search {
            width: min(240px, 30%);
            max-width: 100%;
            min-height: 44px;
            padding: 11px 14px;
            border: 1px solid rgba(255, 255, 255, .55);
            border-radius: 11px;
            background: #fff;
            color: #242936;
            font-size: 12px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        .rd-menu-search::placeholder {
            color: #9aa1ad;
        }

        .rd-menu-list {
            gap: 14px;
            margin-top: 16px;
        }

        .rd-dish {
            grid-template-columns: 152px minmax(0, 1fr) 100px;
            gap: 18px;
            min-height: 150px;
            padding: 15px;
            border: 1px solid #e8eaf0;
            border-radius: 17px;
            box-shadow: 0 5px 18px rgba(20, 25, 38, .035);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .rd-dish:hover {
            transform: translateY(-2px);
            border-color: #f0c8ce;
            box-shadow: var(--rd-shadow-hover);
        }

        .rd-dish-photo {
            height: 118px;
            overflow: hidden;
            border-radius: 12px;
            background: #f0f1f4;
        }

        .rd-dish-photo img {
            border-radius: 12px;
            transition: transform .35s ease;
        }

        .rd-dish:hover .rd-dish-photo img {
            transform: scale(1.045);
        }

        .rd-dish-badge {
            top: 8px;
            left: 8px;
            padding: 5px 8px;
            border-radius: 6px;
            font-size: 9px;
            letter-spacing: .04em;
        }

        .rd-dish-name {
            color: #202431;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.35px;
            line-height: 1.35;
        }

        .rd-dish-description {
            margin: 7px 0;
            color: #747c89;
            font-size: 12px;
            line-height: 1.55;
        }

        .rd-dish-price {
            color: #202431;
            font-size: 15px;
            font-weight: 850;
        }

        .rd-product-flags {
            gap: 7px;
            margin: 9px 0;
        }

        .rd-product-flag {
            padding: 5px 8px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 750;
        }

        .rd-variant-select-wrap {
            max-width: 280px;
            margin-top: 10px;
        }

        .rd-variant-select {
            min-height: 40px;
            padding: 9px 12px;
            border: 1px solid #e2e5eb;
            border-radius: 10px;
            font-size: 12px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .rd-variant-select:focus {
            border-color: var(--rd-accent);
            box-shadow: 0 0 0 3px rgba(215, 25, 45, .08);
            outline: 0;
        }

        .rd-variant-meta {
            margin-top: 7px;
            color: #858c98;
            font-size: 11px;
        }

        .rd-dish-actions {
            gap: 14px;
        }

        .rd-fav {
            display: inline-grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border: 1px solid #f0e2e5;
            border-radius: 50%;
            background: #fff;
            color: var(--rd-accent);
            font-size: 21px;
            transition: background .2s ease, transform .2s ease, border-color .2s ease;
        }

        .rd-fav:hover {
            transform: scale(1.06);
            border-color: #f2bdc5;
            background: #fff3f4;
        }

        .rd-fav.active {
            border-color: #f7d1d7;
            background: #fff0f2;
            color: #be123c;
        }

        .rd-fav:disabled {
            cursor: wait;
            opacity: .6;
        }

        .rd-add,
        .rd-checkout {
            min-height: 43px;
            border-radius: 11px;
            background: var(--rd-accent);
            box-shadow: 0 5px 12px rgba(215, 25, 45, .14);
            font-size: 12px !important;
            font-weight: 800;
            transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .rd-add:hover,
        .rd-checkout:hover {
            transform: translateY(-1px);
            background: var(--rd-accent-dark);
            box-shadow: 0 8px 16px rgba(215, 25, 45, .20);
        }

        .rd-qty {
            border-color: #f1c6cd;
            border-radius: 10px;
        }

        .rd-qty button {
            width: 32px;
            height: 34px;
        }

        .rd-cart-heading {
            padding-bottom: 15px;
        }

        .rd-cart-heading h2 {
            margin: 0;
        }

        .rd-clear {
            padding: 7px 9px;
            border-radius: 8px;
            font-size: 11px !important;
            transition: background .2s ease;
        }

        .rd-clear:hover {
            background: #fff0f2;
        }

        .rd-cart-items {
            max-height: 340px;
            padding-right: 2px;
        }

        .rd-cart-empty {
            margin-top: 14px;
            padding: 30px 14px;
            border: 1px dashed #e3e6ec;
            border-radius: 14px;
            background: #fafbfc;
            font-size: 12px;
        }

        .rd-cart-item {
            grid-template-columns: 58px minmax(0, 1fr) auto;
            gap: 11px;
            padding: 13px 0;
            border-bottom-color: #eef0f4;
        }

        .rd-cart-item img {
            width: 58px;
            height: 54px;
            border-radius: 10px;
        }

        .rd-cart-item-name {
            color: #333a47;
            font-size: 12px;
        }

        .rd-cart-item-price {
            color: #202431;
            font-size: 12px;
        }

        .rd-cart-qty {
            border-color: #f0c6cd;
            border-radius: 7px;
        }

        .rd-coupon {
            min-height: 48px;
            padding: 14px 0;
            font-size: 12px !important;
        }

        .rd-coupon-input {
            gap: 8px;
            padding: 12px 0;
        }

        .rd-coupon-input input {
            min-height: 40px;
            padding: 9px 11px;
            border-color: #e1e4ea;
            border-radius: 9px;
            font-size: 12px !important;
        }

        .rd-coupon-input button {
            min-height: 40px;
            padding: 9px 14px;
            border-radius: 9px;
            font-weight: 750;
        }

        .rd-total-row {
            margin: 12px 0;
            color: #626b78;
            font-size: 12px;
        }

        .rd-total-row.final {
            margin-top: 15px;
            padding-top: 15px;
            font-size: 16px;
        }

        .rd-checkout {
            min-height: 48px;
            margin-top: 13px;
            font-size: 13px !important;
        }

        .rd-extra-panel {
            padding: clamp(20px, 3vw, 34px);
            border: 1px solid #e8eaf0;
            border-radius: 18px;
            background: #fff;
            box-shadow: var(--rd-shadow);
            line-height: 1.7;
        }

        .rd-field,
        .rd-info-line,
        .rd-review-note,
        .rd-offer-detail {
            border-color: #e9ebf0;
            border-radius: 14px;
        }

        .rd-about-copy {
            border-color: #f4dfe2;
            border-radius: 16px;
        }

        .rd-rating-card {
            border: 1px solid #f3e7ce;
            border-radius: 16px;
        }

        .rd-photo-card {
            overflow: hidden;
            border-radius: 15px;
        }

        .rd-offer-card {
            border-color: #f2dfe2;
            border-radius: 16px;
        }

        /* Large tablets and compact laptops */
        @media (max-width: 1180px) {
            .rd-layout {
                grid-template-columns: minmax(180px, 205px) minmax(0, 1fr);
                gap: 18px;
            }

            .rd-cart-panel {
                position: static;
                grid-column: 1 / -1;
            }

            .rd-cart-items {
                max-height: 300px;
            }

            .rd-hero-main {
                width: 100%;
            }

            .rd-gallery {
                display: none;
            }
        }

        @media (max-width: 900px) {
            .rd-hero {
                min-height: 270px;
            }

            .rd-layout {
                padding: 22px 18px 34px;
            }

            .rd-menu-banner img {
                width: 145px;
            }

            .rd-dish {
                grid-template-columns: 128px minmax(0, 1fr) 88px;
                gap: 13px;
            }

            .rd-dish-photo {
                height: 108px;
            }
        }

        /* Phones and small tablets */
        @media (max-width: 760px) {
            .rd-breadcrumb {
                padding: 11px 16px;
                font-size: 11px;
            }

            .rd-hero {
                min-height: 0;
                padding: 48px 16px 22px;
            }

            .rd-hero-main {
                gap: 14px;
            }

            .rd-logo {
                width: 76px;
                height: 76px;
                border-radius: 17px;
            }

            .rd-logo .rd-logo-icon {
                font-size: 23px;
            }

            .rd-logo strong {
                font-size: 9px;
            }

            .rd-logo small {
                font-size: 5px;
            }

            .rd-title-line h1 {
                font-size: clamp(21px, 5vw, 27px);
                letter-spacing: -.7px;
            }

            .rd-meta {
                gap: 6px 9px;
                margin: 10px 0;
                font-size: 11px;
            }

            .rd-tags,
            .rd-deals {
                gap: 7px;
            }

            .rd-tag {
                padding: 6px 9px;
                font-size: 10px;
            }

            .rd-deal {
                padding: 6px 9px 6px 6px;
                font-size: 10px;
            }

            .rd-deal b {
                padding: 7px 8px;
                font-size: 10px;
            }

            .rd-booking-action a {
                min-height: 44px;
                padding: 9px 12px;
            }

            .rd-hero-actions {
                top: 7px;
                right: 10px;
            }

            .rd-hero-actions button {
                min-width: 36px;
                min-height: 36px;
            }

            .rd-tabs {
                gap: 3px;
                padding: 0 10px;
            }

            .rd-tab {
                padding: 15px 10px 12px;
                font-size: 12px;
            }

            .rd-layout {
                display: flex;
                flex-direction: column;
                gap: 14px;
                padding: 15px 12px 28px;
            }

            .rd-category-panel,
            .rd-menu-area,
            .rd-cart-panel {
                position: static;
                width: 100%;
            }

            .rd-category-panel,
            .rd-cart-panel {
                padding: 15px;
                border-radius: 15px;
            }

            .rd-panel-title {
                margin-bottom: 11px;
                font-size: 14px;
            }

            .rd-category-list {
                flex-direction: row;
                gap: 7px;
                overflow-x: auto;
                padding: 1px 1px 5px;
                scrollbar-width: thin;
            }

            .rd-category {
                min-height: 39px;
                padding: 9px 11px;
                font-size: 11px;
                white-space: nowrap;
            }

            .rd-category .rd-count,
            .rd-category-promo {
                display: none;
            }

            .rd-menu-banner {
                display: grid;
                grid-template-columns: 58px minmax(0, 1fr);
                gap: 10px;
                min-height: 0;
                padding: 12px;
                border-radius: 14px;
            }

            .rd-menu-banner img {
                grid-row: span 2;
                width: 58px;
                height: 58px;
                border-radius: 10px;
            }

            .rd-menu-banner h2 {
                font-size: 17px;
            }

            .rd-menu-banner p {
                font-size: 10px;
            }

            .rd-menu-search {
                grid-column: 1 / -1;
                width: 100%;
                max-width: 100%;
                min-height: 42px;
                margin: 2px 0 0;
                padding: 10px 12px;
                font-size: 12px !important;
            }

            .rd-menu-list {
                gap: 11px;
                margin-top: 12px;
            }

            .rd-dish {
                grid-template-columns: 92px minmax(0, 1fr);
                gap: 9px 12px;
                min-height: 0;
                padding: 11px;
                border-radius: 14px;
            }

            .rd-dish-photo {
                grid-row: span 2;
                height: 96px;
                border-radius: 10px;
            }

            .rd-dish-photo img {
                border-radius: 10px;
            }

            .rd-dish-name {
                font-size: 13px;
            }

            .rd-dish-description {
                margin: 5px 0;
                font-size: 10px;
            }

            .rd-dish-price {
                font-size: 13px;
            }

            .rd-product-flags {
                gap: 5px;
                margin: 6px 0;
            }

            .rd-product-flag {
                padding: 4px 6px;
                font-size: 9px;
            }

            .rd-variant-select-wrap {
                max-width: 100%;
                margin-top: 7px;
            }

            .rd-variant-select {
                min-height: 36px;
                padding: 7px 9px;
                font-size: 10px;
            }

            .rd-dish-actions {
                grid-column: 2;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 9px;
                margin-top: 0;
            }

            .rd-fav {
                width: 34px;
                height: 34px;
                font-size: 19px;
            }

            .rd-add {
                width: min(118px, 65%);
                min-height: 38px;
                padding: 8px 10px;
                font-size: 11px !important;
            }

            .rd-qty button {
                width: 29px;
                height: 31px;
            }

            .rd-cart-items {
                max-height: none;
            }

            .rd-cart-item {
                grid-template-columns: 52px minmax(0, 1fr) auto;
                gap: 9px;
            }

            .rd-cart-item img {
                width: 52px;
                height: 48px;
            }

            .rd-extra-panel {
                padding: 18px;
                border-radius: 15px;
            }

            .rd-field-grid {
                grid-template-columns: 1fr;
            }

            .rd-about-intro,
            .rd-review-summary {
                grid-template-columns: 1fr;
            }

            .rd-about-details {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .rd-photo-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 9px;
            }

            .rd-photo-card,
            .rd-photo-card img {
                min-height: 125px;
                height: 125px;
            }

            .rd-offer-grid,
            .rd-offer-details {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 420px) {
            .rd-hero-main {
                align-items: flex-start;
                gap: 10px;
            }

            .rd-logo {
                width: 62px;
                height: 62px;
                border-radius: 14px;
            }

            .rd-logo .rd-logo-icon {
                font-size: 18px;
            }

            .rd-logo strong {
                font-size: 8px;
            }

            .rd-title-line h1 {
                font-size: 20px;
            }

            .rd-meta {
                font-size: 10px;
            }

            .rd-menu-banner {
                grid-template-columns: 48px minmax(0, 1fr);
                padding: 10px;
            }

            .rd-menu-banner img {
                width: 48px;
                height: 48px;
            }

            .rd-dish {
                grid-template-columns: 82px minmax(0, 1fr);
                gap: 8px 10px;
                padding: 9px;
            }

            .rd-dish-photo {
                height: 88px;
            }

            .rd-dish-name {
                font-size: 12px;
            }

            .rd-dish-description {
                font-size: 9px;
            }

            .rd-product-flag {
                font-size: 8px;
            }

            .rd-about-details {
                grid-template-columns: 1fr;
            }

            .rd-photo-strip {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .rd-page *,
            .rd-page *::before,
            .rd-page *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    <div class="rd-page">

        {{-- Breadcrumb --}}

        <div class="rd-breadcrumb">

            <a href="{{ route('home') }}">Home</a>

            <span>›</span>

            <a href="{{ route('restaurants') }}">Restaurants</a>

            <span>›</span>

            {{ $restaurant->name }}

        </div>

        {{-- Restaurant hero --}}

        <section class="rd-hero">

            <div class="rd-hero-actions">

                <button type="button" id="restaurantFavorite" aria-label="Favourite restaurant">♡</button>

                <button type="button"
                    onclick="document.getElementById('rd-gallery').scrollIntoView({behavior:'smooth'})">♧</button>

            </div>

            <div class="rd-hero-main">

                <div class="rd-logo">

                    <span class="rd-logo-icon">🍲</span>

                    <strong>BAKASURAN</strong>

                    <small>FOOD & BEVERAGES</small>

                </div>

                <div class="rd-info">

                    <div class="rd-title-line">

                        <h1>{{ $restaurant->name }}</h1>

                        <span class="rd-open">{{ $restaurant->is_open ? "✓ Open Now" : "Closed" }}</span>

                    </div>

                    <div class="rd-meta">

                        <span><span class="rd-rating-star">★</span>

                            {{ number_format((float) ($restaurant->average_rating ?? 0), 1) }}</span>

                        <span class="rd-meta-divider">|</span>

                        <span>◷
                            {{ ($restaurant->delivery_time ? $restaurant->delivery_time . ' mins' : 'Time unavailable') }}</span>

                        <span class="rd-meta-divider">|</span>

                        <span>♧ ₹{{ number_format((float) ($restaurant->price_for_two ?? 0), 0) }} for two</span>
                        @if (!is_null($restaurant->delivery_fee))
                            <span class="rd-meta-divider">|</span><span>Delivery
                                ₹{{ number_format((float) $restaurant->delivery_fee, 0) }}</span>
                        @endif
                        @if (!empty($restaurant->minimum_order))
                            <span class="rd-meta-divider">|</span><span>Min. order
                                ₹{{ number_format((float) $restaurant->minimum_order, 0) }}</span>
                        @endif

                    </div>

                    <div class="rd-tags">
                        @if ((bool) ($restaurant->is_featured ?? false))
                            <span class="rd-featured-label">★ Featured Restaurant</span>
                        @endif
                        @if ($restaurant->cuisine_type)
                            @foreach (preg_split("/[,|]/", $restaurant->cuisine_type) as $cuisine)
                                @if (trim($cuisine) !== "")
                                    <span class="rd-tag">{{ trim($cuisine) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    @if ((int) ($restaurant->discount_percentage ?? 0) > 0 || !is_null($restaurant->delivery_fee))
                        <div class="rd-deals">
                            @if ((int) ($restaurant->discount_percentage ?? 0) > 0)
                                <div class="rd-deal"><b>{{ (int) $restaurant->discount_percentage }}% OFF</b><span>Restaurant
                                        offer<small>Terms may apply at checkout</small></span></div>
                            @endif
                            @if (!is_null($restaurant->delivery_fee))
                                <div class="rd-deal"><b>₹{{ number_format((float) $restaurant->delivery_fee, 0) }}</b><span>Delivery
                                        fee<small>Shown before checkout</small></span></div>
                            @endif
                        </div>
                    @endif

                    <div class="rd-booking-action">

                        <a href="{{ route('restaurant.table-booking', ['slug' => $restaurant->slug]) }}">

                            <span class="rd-booking-icon">📅</span>

                            <span>

                                <strong>Book a Table</strong>

                                <small>Reserve your dining experience →</small>

                            </span>

                        </a>

                    </div>

                </div>

            </div>

            {{-- <div class="rd-gallery" id="rd-gallery">

                <div class="rd-gallery-grid">

                    <img src="https://images.unsplash.com/photo-1563379091339-03246963d96c?auto=format&fit=crop&w=300&q=80"
                        alt="Biryani">

                    <img src="https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=300&q=80"
                        alt="Chicken dish">

                    <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=300&q=80"
                        alt="Indian meal">

                    <button class="rd-gallery-button" type="button"
                        onclick="document.querySelector('[data-tab=photos]').click()">

                        View Photos

                    </button>

                </div>

            </div> --}}

            {{-- <div class="rd-benefits">

                <div class="rd-benefit"><span class="rd-benefit-icon">♢</span>Hygienic &<br>Safe</div>

                <div class="rd-benefit"><span class="rd-benefit-icon">♧</span>Premium<br>Ingredients</div>

                <div class="rd-benefit"><span class="rd-benefit-icon">♙</span>Expert<br>Chefs</div>

                <div class="rd-benefit"><span class="rd-benefit-icon">♧</span>Fast & Reliable<br>Delivery</div>

                <div class="rd-benefit"><span class="rd-benefit-icon">♡</span>Great<br>Taste</div>

            </div> --}}

        </section>

        {{-- Page tabs --}}

        <nav class="rd-tabs">

            <button type="button" class="rd-tab active" data-tab="menu">Menu</button>

            <button type="button" class="rd-tab" data-tab="about">About</button>

            <button type="button" class="rd-tab" data-tab="reviews">Reviews</button>

            <button type="button" class="rd-tab" data-tab="photos">Photos</button>

            <button type="button" class="rd-tab" data-tab="offers">Offers</button>

            {{-- ADD HERE, BEFORE THE CLOSING NAV TAG --}}

            <a href="{{ route('restaurant.table-booking', ['slug' => $restaurant->slug]) }}" class="rd-tab">

                Book a Table

            </a>

        </nav>

        <div class="rd-layout" id="rd-layout">

            {{-- Categories sidebar --}}

            <aside class="rd-category-panel" id="rd-category-panel">

                <h2 class="rd-panel-title">Menu Categories</h2>

                <div class="rd-category-list">

                    {{-- Show all menu items --}}
                    <button type="button" class="rd-category active" data-category="all">
                        <span>🍽️</span>
                        <span>All</span>
                        <span class="rd-count">({{ $products->count() }})</span>
                    </button>

                    @foreach ($categories as $category)
                        @if (strtolower(trim($category['name'])) !== 'all')
                            <button type="button" class="rd-category" data-category="{{ $category['name'] }}">
                                <span>{{ $category['icon'] }}</span>
                                <span>{{ $category['name'] }}</span>
                                <span class="rd-count">({{ $category['count'] }})</span>
                            </button>
                        @endif
                    @endforeach

                </div>

                <div class="rd-category-promo">

                    GOOD FOOD<br><span>GOOD MOOD</span>

                </div>

            </aside>

            {{-- Menu and extra content --}}

            <main class="rd-menu-area" id="rd-menu-area">

                <div class="rd-menu-content">

                    <div class="rd-menu-banner">

                        <img src="{{ $restaurantImage }}" alt="{{ $restaurant->name }}">

                        <div>

                            <h2 id="rd-category-heading">{{ $categories->first()["name"] ?? "Menu" }}</h2>

                            <p id="rd-category-description">Aromatic rice cooked with rich spices and premium ingredients.

                            </p>

                        </div>

                        <input type="search" class="rd-menu-search" id="rd-menu-search"
                            placeholder="⌕ Search within menu...">

                    </div>

                    <div class="rd-menu-list" id="rd-menu-list">

                        @forelse ($products as $item)
                            @php
                                // These fields match the BAKASURAN admin Product create/edit form.
                                $itemName = data_get($item, 'title', data_get($item, 'name', 'Menu item'));
                                $itemCategory = data_get($item, 'category.name')
                                    ?: data_get($item, 'category_name')
                                    ?: data_get($item, 'category.title')
                                    ?: data_get($item, 'menuCategory.name')
                                    ?: 'Other';
                                $itemDescription = data_get($item, 'short_description') ?: data_get($item, 'description', '');
                                $itemFoodType = strtolower((string) data_get($item, 'food_type', ''));
                                $itemIsVeg = in_array($itemFoodType, ['veg', 'vegetarian'], true)
                                    || (bool) data_get($item, 'is_veg', data_get($item, 'veg', false));
                                // Product status in this project uses show / hide.
                                $itemAvailable = (bool) data_get($item, 'is_available', true)
                                    && strtolower((string) data_get($item, 'status', 'show')) === 'show';
                                $itemRecommended = (bool) data_get($item, 'is_recommended', false);
                                $itemFeatured = (bool) data_get($item, 'is_feature', false);
                                $itemPrepTime = data_get($item, 'preparation_time');
                                $itemAllergens = data_get($item, 'allergen_info');
                                $itemRating = data_get($item, 'average_rating', data_get($item, 'rating'));
                                $itemBadge = data_get($item, 'badge');
                                $variants = collect(data_get($item, 'variants', []));
                                // ProductVariant uses status show / hide. Its model does not define is_available.
                                $activeVariants = $variants->filter(function ($variant) {
                                    return strtolower((string) data_get($variant, 'status', 'show')) === 'show';
                                })->values();
                                $firstVariant = $activeVariants->first() ?: $variants->first();
                                $itemPrice = (float) data_get($firstVariant, 'price', data_get($item, 'price', 0));
                                $itemActualPrice = (float) data_get($firstVariant, 'actual_price', data_get($item, 'actual_price', 0));
                                $rawItemImage = data_get($firstVariant, 'image')
                                    ?: data_get($firstVariant, 'media.0.file_path')
                                    ?: data_get($item, 'primaryMedia.file_path')
                                    ?: data_get($item, 'media.0.file_path')
                                    ?: data_get($item, 'image', data_get($item, 'thumbnail'));
                                $itemImage = $rawItemImage
                                    ? (\Illuminate\Support\Str::startsWith($rawItemImage, ['http://', 'https://'])
                                        ? $rawItemImage
                                        : asset(ltrim($rawItemImage, '/')))
                                    : 'https://placehold.co/400x300/171717/ffffff?text=Food';
                                $variantAttributes = data_get($firstVariant, 'attributeMappings', collect());

                                // Prepare add-on data from Product -> addonGroups -> options.
                                $addonGroupsData = collect(data_get($item, 'addonGroups', []))
                                    ->map(function ($group) {
                                        $options = collect(data_get($group, 'options', []))
                                            ->filter(function ($option) {
                                                $available = (bool) data_get($option, 'is_available', true);
                                                $tracksStock = (bool) data_get($option, 'track_stock', false);
                                                $stock = (int) data_get($option, 'stock', 0);

                                                return $available && (!$tracksStock || $stock > 0);
                                            })
                                            ->map(function ($option) {
                                                return [
                                                    'id' => (int) data_get($option, 'id'),
                                                    'name' => (string) data_get($option, 'name', ''),
                                                    'price' => (float) data_get($option, 'price', 0),
                                                ];
                                            })
                                            ->values();

                                        return [
                                            'id' => (int) data_get($group, 'id'),
                                            'name' => (string) data_get($group, 'name', 'Add-ons'),
                                            'selection_type' => strtolower((string) data_get($group, 'selection_type', 'single')),
                                            'is_required' => (bool) data_get($group, 'is_required', false),
                                            'min_select' => (int) data_get($group, 'min_select', 0),
                                            'max_select' => (int) data_get($group, 'max_select', 1),
                                            'options' => $options,
                                        ];
                                    })
                                    ->values()
                                    ->all();
                            @endphp

                            <article class="rd-dish" data-id="{{ $item->id }}" data-name="{{ $itemName }}"
                                data-category="{{ $itemCategory }}" data-price="{{ $itemPrice }}" data-image="{{ $itemImage }}"
                                data-rating="{{ $itemRating }}" data-veg="{{ $itemIsVeg ? '1' : '0' }}"
                                data-default-variant-id="{{ data_get($item, 'variant.id', '') }}"
                                data-available="{{ $itemAvailable && ($variants->isEmpty() || $activeVariants->isNotEmpty()) ? '1' : '0' }}"
                                data-addons="{{ json_encode($addonGroupsData) }}">

                                <div class="rd-dish-photo">
                                    <img src="{{ $itemImage }}" alt="{{ $itemName }}" loading="lazy"
                                        onerror="this.onerror=null;this.src='https://placehold.co/400x300/171717/ffffff?text=Food';">
                                    @if ($itemRecommended)
                                        <span class="rd-dish-badge">RECOMMENDED</span>
                                    @elseif ($itemFeatured)
                                        <span class="rd-dish-badge">FEATURED</span>
                                    @elseif ($itemBadge)
                                        <span class="rd-dish-badge">{{ $itemBadge }}</span>
                                    @endif
                                </div>

                                <div class="rd-dish-info">
                                    <div class="rd-dish-name-row">
                                        <h3 class="rd-dish-name">{{ $itemName }}</h3>
                                        <span class="rd-veg-mark {{ $itemIsVeg ? '' : 'nonveg' }}">●</span>
                                    </div>
                                    <p class="rd-dish-description">{{ $itemDescription }}</p>

                                    <div class="rd-product-flags">
                                        @if ($itemFoodType)
                                            <span class="rd-product-flag food">{{ ucfirst($itemFoodType) }}</span>
                                        @endif
                                        @if ($itemPrepTime)
                                            <span class="rd-product-flag prep">◷ {{ $itemPrepTime }} min prep</span>
                                        @endif
                                        @if ($itemRecommended)
                                            <span class="rd-product-flag recommended">★ Recommended</span>
                                        @endif
                                        <span
                                            class="rd-product-flag {{ $itemAvailable && ($variants->isEmpty() || $activeVariants->isNotEmpty()) ? 'available' : 'unavailable' }}">
                                            {{ $itemAvailable && ($variants->isEmpty() || $activeVariants->isNotEmpty()) ? 'Available' : 'Currently unavailable' }}
                                        </span>
                                    </div>

                                    <div class="rd-dish-price">
                                        ₹<span class="rd-current-price">{{ number_format($itemPrice, 2) }}</span>
                                        @if ($itemActualPrice > $itemPrice && $itemActualPrice > 0)
                                            <span class="rd-variant-price-old">₹{{ number_format($itemActualPrice, 2) }}</span>
                                        @endif
                                    </div>

                                    @if ($variants->isNotEmpty())
                                        <div class="rd-variant-select-wrap">
                                            <select class="rd-variant-select" aria-label="Choose {{ $itemName }} variant">
                                                @foreach ($variants as $variant)
                                                    @php
                                                        $variantAvailable = strtolower((string) data_get($variant, 'status', 'show')) === 'show';
                                                        $variantPrice = (float) data_get($variant, 'price', 0);
                                                        $variantActualPrice = (float) data_get($variant, 'actual_price', 0);
                                                        $variantPrepTime = data_get($variant, 'preparation_time', $itemPrepTime);
                                                        $variantStock = (int) data_get($variant, 'stock', 0);
                                                        $variantMinOrder = (int) data_get($variant, 'product_min_order', 1);
                                                        $variantMaxOrder = (int) data_get($variant, 'product_max_order', 0);
                                                        $variantImageRaw = data_get($variant, 'image') ?: $rawItemImage;
                                                        $variantImage = $variantImageRaw
                                                            ? (\Illuminate\Support\Str::startsWith($variantImageRaw, ['http://', 'https://'])
                                                                ? $variantImageRaw
                                                                : asset(ltrim($variantImageRaw, '/')))
                                                            : $itemImage;
                                                        $variantLabel = data_get($variant, 'name') ?: data_get($variant, 'sku') ?: ('Option ' . $loop->iteration);
                                                        $attributeNames = collect(data_get($variant, 'attributeMappings', []))
                                                            ->map(function ($mapping) {
                                                                $attributeName = data_get($mapping, 'attribute.name');
                                                                $valueName = data_get($mapping, 'value.name')
                                                                    ?: data_get($mapping, 'value.value');

                                                                return $attributeName && $valueName
                                                                    ? $attributeName . ': ' . $valueName
                                                                    : ($valueName ?: $attributeName);
                                                            })->filter()->implode(' / ');
                                                        if ($attributeNames)
                                                            $variantLabel = $attributeNames;
                                                    @endphp
                                                    <option value="{{ data_get($variant, 'id') }}" data-price="{{ $variantPrice }}"
                                                        data-actual-price="{{ $variantActualPrice }}" data-image="{{ $variantImage }}"
                                                        data-sku="{{ data_get($variant, 'sku', '') }}"
                                                        data-prep="{{ $variantPrepTime }}" data-stock="{{ $variantStock }}"
                                                        data-min-order="{{ max(1, $variantMinOrder) }}"
                                                        data-max-order="{{ $variantMaxOrder }}"
                                                        data-available="{{ $variantAvailable ? '1' : '0' }}" {{ !$variantAvailable ? 'disabled' : '' }} {{ $firstVariant && (string) data_get($firstVariant, 'id') === (string) data_get($variant, 'id') ? 'selected' : '' }}>
                                                        {{ $variantLabel }} —
                                                        ₹{{ number_format($variantPrice, 2) }}{{ !$variantAvailable ? ' (Unavailable)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="rd-variant-meta">
                                                <span
                                                    class="rd-variant-sku">{{ data_get($firstVariant, 'sku') ? 'SKU: ' . data_get($firstVariant, 'sku') : '' }}</span>
                                                <span
                                                    class="rd-variant-prep">{{ data_get($firstVariant, 'preparation_time', $itemPrepTime) ? 'Prep: ' . data_get($firstVariant, 'preparation_time', $itemPrepTime) . ' min' : '' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($itemAllergens)
                                        <div class="rd-variant-meta">Allergen info: {{ $itemAllergens }}</div>
                                    @endif
                                    @if ($itemRating !== null)
                                        <div class="rd-dish-rating"><span>★</span> {{ $itemRating }}</div>
                                    @endif
                                </div>

                                <div class="rd-dish-actions">
                                    <button type="button" class="rd-fav" aria-label="Favourite dish">♡</button>
                                    <button type="button" class="rd-add" {{ !$itemAvailable || ($variants->isNotEmpty() && $activeVariants->isEmpty()) ? 'disabled' : '' }}>
                                        {{ !$itemAvailable || ($variants->isNotEmpty() && $activeVariants->isEmpty()) ? 'Unavailable' : 'Add' }}
                                    </button>
                                    <div class="rd-qty">
                                        <button type="button" class="rd-minus">−</button>
                                        <span class="rd-qty-value">1</span>
                                        <button type="button" class="rd-plus">+</button>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rd-no-results" style="display:block;">
                                No menu items are available for this restaurant yet.
                            </div>
                        @endforelse

                    </div>

                    <div class="rd-no-results" id="rd-no-results">

                        No dishes found in this category.

                    </div>

                </div>

                <section class="rd-extra-panel" data-panel="about">
                    <div class="rd-section-heading">
                        <div><span class="rd-section-kicker">✦ Restaurant story</span>
                            <h2>About {{ $restaurant->name }}</h2>
                            <p>Get to know the restaurant, its cuisine and dining information.</p>
                        </div>
                    </div>
                    <div class="rd-about-intro">
                        <div class="rd-about-copy">
                            <h3>Welcome to {{ $restaurant->name }}</h3>
                            <p>{{ $restaurant->description ?: 'Discover freshly prepared meals, carefully selected ingredients and delicious dishes made for you. Explore the menu to find your next favourite.' }}
                            </p>
                        </div>
                        <div class="rd-about-highlight">
                            <div class="rd-field">
                                <small>Cuisine</small><strong>{{ $restaurant->cuisine_type ?: 'Not specified' }}</strong>
                            </div>
                            <div class="rd-field"><small>Delivery
                                    time</small><strong>{{ $restaurant->delivery_time ? $restaurant->delivery_time . ' mins' : 'Not available' }}</strong>
                            </div>
                            <div class="rd-field"><small>Price for
                                    two</small><strong>{{ $restaurant->price_for_two ? '₹' . number_format((float) $restaurant->price_for_two, 0) : 'Not specified' }}</strong>
                            </div>
                            <div class="rd-field"><small>Customer
                                    rating</small><strong>{{ $restaurantRating > 0 ? number_format($restaurantRating, 1) . ' / 5' : 'Not rated yet' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="rd-about-details">
                        <div class="rd-info-line"><span class="rd-info-icon">◷</span>
                            <div><small>Opening
                                    hours</small><strong>{{ $restaurant->opening_time ? \Illuminate\Support\Carbon::parse($restaurant->opening_time)->format('g:i A') : '—' }}
                                    –
                                    {{ $restaurant->closing_time ? \Illuminate\Support\Carbon::parse($restaurant->closing_time)->format('g:i A') : '—' }}</strong>
                            </div>
                        </div>
                        <div class="rd-info-line"><span class="rd-info-icon">⌖</span>
                            <div><small>Restaurant
                                    address</small><strong>{{ collect([$restaurant->address, $restaurant->city, $restaurant->state, $restaurant->pincode])->filter()->implode(', ') ?: 'Address not available' }}</strong>
                            </div>
                        </div>
                        <div class="rd-info-line"><span class="rd-info-icon">☎</span>
                            <div><small>Contact</small><strong>@if($restaurant->phone)<a
                            href="tel:{{ $restaurant->phone }}">{{ $restaurant->phone }}</a>@else Not available
                                        @endif</strong></div>
                        </div>
                        <div class="rd-info-line"><span class="rd-info-icon">⌁</span>
                            <div><small>Delivery
                                    fee</small><strong>{{ is_null($restaurant->delivery_fee) ? 'Not specified' : '₹' . number_format((float) $restaurant->delivery_fee, 0) }}</strong>
                            </div>
                        </div>
                        <div class="rd-info-line"><span class="rd-info-icon">₹</span>
                            <div><small>Minimum
                                    order</small><strong>{{ is_null($restaurant->minimum_order) ? 'Not specified' : '₹' . number_format((float) $restaurant->minimum_order, 0) }}</strong>
                            </div>
                        </div>
                        <div class="rd-info-line"><span class="rd-info-icon">✉</span>
                            <div><small>Email</small><strong>@if($restaurant->email)<a
                            href="mailto:{{ $restaurant->email }}">{{ $restaurant->email }}</a>@else Not
                                        available @endif</strong></div>
                        </div>
                    </div>
                </section>

                <section class="rd-extra-panel" data-panel="reviews">
                    <div class="rd-section-heading">
                        <div><span class="rd-section-kicker">✦ Guest feedback</span>
                            <h2>Customer Reviews</h2>
                            <p>Ratings and feedback help customers choose their next meal.</p>
                        </div>
                    </div>
                    <div class="rd-review-summary">
                        <div class="rd-rating-card">
                            <div class="rd-rating-number">
                                {{ $restaurantRating > 0 ? number_format($restaurantRating, 1) : '—' }}
                            </div>
                            <div class="rd-rating-stars">{{ $restaurantRating > 0 ? '★★★★★' : '☆☆☆☆☆' }}</div>
                            <small>Out of 5.0</small>
                        </div>
                        <div class="rd-review-note">
                            <h3>Real customer experiences</h3>
                            <p>Customer reviews will be displayed here when restaurant review records are connected. We
                                don't show sample reviews as genuine customer feedback.</p>
                        </div>
                    </div>
                    <div class="rd-empty-state">
                        <div class="rd-empty-icon">★</div>
                        <h3>Reviews are coming here</h3>
                        <p>There are no customer review entries available to display right now.</p>
                    </div>
                </section>

                <section class="rd-extra-panel" data-panel="photos">

                    <div class="rd-section-heading">
                        <div>
                            <span class="rd-section-kicker">✦ Photo gallery</span>
                            <h2>{{ $restaurant->name }} Photos</h2>
                            <p>Explore delicious food from this restaurant.</p>
                        </div>
                    </div>

                    <div class="rd-photo-strip">

                        @forelse ($products as $product)

                            @php
                                $photo = $product->primaryMedia->file_path
                                    ?? $product->image
                                    ?? null;

                                $photoUrl = $photo
                                    ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://'])
                                        ? $photo
                                        : asset(ltrim($photo, '/')))
                                    : null;
                            @endphp

                            @if ($photoUrl)
                                <div class="rd-photo-card">
                                    <img src="{{ $photoUrl }}" alt="{{ $product->title }}" loading="lazy"
                                        onerror="this.closest('.rd-photo-card').style.display='none';">

                                    <span class="rd-photo-caption">
                                        {{ $product->title }}
                                    </span>
                                </div>
                            @endif

                        @empty

                            <p>No restaurant photos available yet.</p>

                        @endforelse

                    </div>

                </section>

                <section class="rd-extra-panel" data-panel="offers">
                    <div class="rd-section-heading">
                        <div><span class="rd-section-kicker">✦ Special deals</span>
                            <h2>Offers & Benefits</h2>
                            <p>Check the restaurant offer and ordering conditions before checkout.</p>
                        </div>
                    </div>
                    <div class="rd-offer-grid">
                        @if ((int) ($restaurant->discount_percentage ?? 0) > 0)
                            <article class="rd-offer-card"><span class="rd-offer-label">RESTAURANT OFFER</span>
                                <h3>{{ (int) $restaurant->discount_percentage }}% OFF</h3>
                                <p>Discount offer listed by this restaurant.</p>
                                <div class="rd-offer-meta">Final eligibility is confirmed at checkout.</div>
                            </article>
                        @else
                            <article class="rd-offer-card"><span class="rd-offer-label">BAKASURAN</span>
                                <h3>Freshly prepared meals</h3>
                                <p>Explore the menu for available dishes and current pricing.</p>
                                <div class="rd-offer-meta">No percentage discount is currently listed.</div>
                            </article>
                        @endif
                    </div>
                    <div class="rd-offer-details">
                        <div class="rd-offer-detail"><small>Delivery
                                fee</small><strong>{{ is_null($restaurant->delivery_fee) ? 'Not specified' : '₹' . number_format((float) $restaurant->delivery_fee, 0) }}</strong>
                        </div>
                        <div class="rd-offer-detail"><small>Minimum
                                order</small><strong>{{ is_null($restaurant->minimum_order) ? 'Not specified' : '₹' . number_format((float) $restaurant->minimum_order, 0) }}</strong>
                        </div>
                    </div>
                </section>

            </main>

            {{-- Cart sidebar --}}

            <aside class="rd-cart-panel" id="rd-cart-panel">

                <div class="rd-cart-heading">

                    <h2>Your Cart <small id="rd-cart-count">(0 items)</small></h2>

                    <button type="button" class="rd-clear" id="rd-clear">Clear All</button>

                </div>

                <div class="rd-cart-items" id="rd-cart-items">

                    <div class="rd-cart-empty">

                        <span>🛒</span>

                        Your cart is waiting.<br>Add delicious dishes to get started!

                    </div>

                </div>

                <button type="button" class="rd-coupon" id="rd-coupon-toggle">

                    <span class="rd-coupon-icon">%</span>

                    <span>Apply Coupon</span>

                    <span>›</span>

                </button>

                <div class="rd-coupon-input" id="rd-coupon-input">

                    <input type="text" id="rd-coupon-code" placeholder="Enter coupon code">

                    <button type="button" id="rd-apply-coupon">Apply</button>

                </div>

                <div class="rd-cart-totals">

                    <div class="rd-total-row">

                        <span>Item Total</span>

                        <strong id="rd-subtotal">₹0</strong>

                    </div>

                    <div class="rd-total-row">

                        <span>Delivery Fee</span>

                        <span id="rd-delivery">₹0</span>

                    </div>

                    <div class="rd-total-row">

                        <span>Taxes & Charges</span>

                        <span id="rd-tax">₹0</span>

                    </div>

                    <div class="rd-total-row" id="rd-discount-row" style="display:none">

                        <span>Coupon Discount</span>

                        <strong id="rd-discount" style="color:#078d45">−₹0</strong>

                    </div>

                    <div class="rd-total-row final">

                        <span>Total Amount</span>

                        <strong id="rd-total">₹0</strong>

                    </div>

                </div>

                <button type="button" class="rd-checkout" id="rd-checkout" disabled>

                    Proceed to Checkout <span>→</span>

                </button>

                <p class="rd-saved" id="rd-saved">✓ Add items to see your order total.</p>

            </aside>

        </div>

    </div>

    {{-- Add-on selection modal: populated from the selected product's admin-managed add-on groups --}}
    <div class="rd-addon-modal" id="rd-addon-modal" aria-hidden="true">
        <div class="rd-addon-backdrop" data-addon-close></div>
        <div class="rd-addon-dialog" role="dialog" aria-modal="true" aria-labelledby="rd-addon-title">
            <div class="rd-addon-heading">
                <div>
                    <h2 id="rd-addon-title">Customize your item</h2>
                    <p id="rd-addon-product-name"></p>
                </div>
                <button type="button" class="rd-addon-close" data-addon-close aria-label="Close">×</button>
            </div>
            <div id="rd-addon-groups" class="rd-addon-groups"></div>
            <div class="rd-addon-footer">
                <strong id="rd-addon-total">₹0</strong>
                <button type="button" id="rd-addon-confirm" class="rd-addon-confirm">Add to Cart</button>
            </div>
        </div>
    </div>

    <style>
        .rd-addon-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 16px
        }

        .rd-addon-modal.open {
            display: flex
        }

        .rd-addon-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 15, 20, .62);
            backdrop-filter: blur(3px)
        }

        .rd-addon-dialog {
            position: relative;
            width: min(520px, 100%);
            max-height: min(82vh, 720px);
            overflow: auto;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 24px 80px #0004
        }

        .rd-addon-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 20px;
            border-bottom: 1px solid #e8e8ed
        }

        .rd-addon-heading h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 850;
            color: #18181b
        }

        .rd-addon-heading p {
            margin: 5px 0 0;
            color: #71717a;
            font-size: 12px
        }

        .rd-addon-close {
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 50%;
            background: #f4f4f5;
            color: #27272a;
            font-size: 22px
        }

        .rd-addon-groups {
            padding: 8px 20px 16px
        }

        .rd-addon-group {
            padding: 14px 0;
            border-bottom: 1px solid #f0f0f2
        }

        .rd-addon-group:last-child {
            border-bottom: 0
        }

        .rd-addon-group-title {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 4px;
            font-size: 13px;
            font-weight: 800;
            color: #27272a
        }

        .rd-addon-group-hint {
            margin: 0 0 10px;
            color: #71717a;
            font-size: 10px
        }

        .rd-addon-option {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 0;
            color: #3f3f46;
            font-size: 12px
        }

        .rd-addon-option input {
            accent-color: #e11d2e
        }

        .rd-addon-option-price {
            margin-left: auto;
            font-weight: 750;
            color: #27272a
        }

        .rd-addon-empty {
            padding: 18px 0;
            color: #71717a;
            font-size: 12px
        }

        .rd-addon-footer {
            position: sticky;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 15px 20px;
            border-top: 1px solid #e8e8ed;
            background: #fff
        }

        .rd-addon-footer strong {
            font-size: 17px;
            color: #18181b
        }

        .rd-addon-confirm {
            padding: 11px 18px;
            border: 0;
            border-radius: 9px;
            background: #e11d2e;
            color: #fff;
            font-size: 12px;
            font-weight: 800
        }

        .rd-addon-confirm:disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .rd-addon-error {
            margin: 0 20px 12px;
            color: #b91c1c;
            font-size: 11px
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* -------------------------------------------------------
               ELEMENTS
            ------------------------------------------------------- */

            const menuList = document.getElementById('rd-menu-list');

            if (!menuList) {
                console.error('Menu list #rd-menu-list was not found.');
                return;
            }

            const dishCards = Array.from(menuList.querySelectorAll('.rd-dish'));
            const categoryButtons = Array.from(document.querySelectorAll('.rd-category'));
            const searchInput = document.getElementById('rd-menu-search');
            const cartItemsElement = document.getElementById('rd-cart-items');

            const noResults = document.getElementById('rd-no-results');
            const categoryHeading = document.getElementById('rd-category-heading');

            const addonModal = document.getElementById('rd-addon-modal');
            const addonGroupsContainer = document.getElementById('rd-addon-groups');
            const addonConfirmButton = document.getElementById('rd-addon-confirm');

            const cartStorageKey = 'bakasuran_cart_restaurant_{{ (int) $restaurant->id }}';
            
            const cartAddUrl = @json(route('cart.add'));
            const cartUpdateUrlTemplate = @json(route('cart.update', ['id' => '__ID__']));
            const cartRemoveUrlTemplate = @json(route('cart.remove', ['id' => '__ID__']));
            const checkoutUrl = @json(route('checkout'));
            const wishlistAddUrl = @json(route('wishlist.add'));
            const wishlistRemoveUrlTemplate = @json(route('wishlist.remove', ['id' => '__ID__']));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || @json(csrf_token());

            async function cartApiRequest(url, method, payload = {}) {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: method === 'GET' ? undefined : JSON.stringify(payload)
                });

                let result = {};
                try { result = await response.json(); } catch (error) { }

                if (response.status === 401) {
                    throw new Error('Please login to manage your cart.');
                }
                if (!response.ok || result.status === false) {
                    const error = new Error(result.message || 'Unable to update your cart. Please try again.');
                    error.status = response.status;
                    error.data = result;
                    throw error;
                }
                return result;
            }

            function isMissingCartItemError(error) {
                const message = String(error?.message || '').toLowerCase();
                return error?.status === 404 ||
                    message.includes('cart item not found') ||
                    message.includes('cart item does not exist');
            }

            async function wishlistApiRequest(url, method, payload = {}) {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: method === 'GET' ? undefined : JSON.stringify(payload)
                });

                let result = {};

                try {
                    result = await response.json();
                } catch (error) { }

                if (response.status === 401) {
                    throw new Error('Please login first.');
                }

                if (!response.ok || result.status === false) {
                    throw new Error(result.message || 'Unable to update wishlist.');
                }

                return result;
            }

            /* -------------------------------------------------------
               CART DATA
            ------------------------------------------------------- */

            let cart = {};

            try {
                const savedCart = JSON.parse(localStorage.getItem(cartStorageKey) || '{}');

                if (savedCart && typeof savedCart === 'object' && !Array.isArray(savedCart)) {
                    cart = savedCart;
                }
            } catch (error) {
                console.warn('Saved cart could not be restored:', error);
            }

            let selectedCategory = 'all';
            let couponApplied = false;
            let pendingDish = null;

            const restaurantDeliveryFee = Number(
                @json((float) ($restaurant->delivery_fee ?? 0))
            );

            const restaurantMinimumOrder = Number(
                @json((float) ($restaurant->minimum_order ?? 0))
            );

            const money = value => {
                return '₹' + Math.round(Number(value || 0)).toLocaleString('en-IN');
            };

            /* -------------------------------------------------------
               TEXT NORMALIZATION
               Makes category matching case-insensitive and ignores
               extra spaces, hyphens and underscores.
            ------------------------------------------------------- */

            function normalizeText(value) {
                return String(value || '')
                    .trim()
                    .toLowerCase()
                    .replace(/[_-]+/g, ' ')
                    .replace(/\s+/g, ' ');
            }

            function normalizeCategory(value) {
                return normalizeText(value);
            }

            /* -------------------------------------------------------
               CATEGORY FILTER + MENU SEARCH
            ------------------------------------------------------- */

            function updateMenu() {

                const term = normalizeText(searchInput ? searchInput.value : '');

                let visibleCount = 0;

                dishCards.forEach(function (card) {

                    const productCategory = normalizeCategory(card.dataset.category);
                    const productName = normalizeText(card.dataset.name);

                    const description = normalizeText(
                        card.querySelector('.rd-dish-description')?.textContent
                    );

                    const variantDetails = normalizeText(
                        Array.from(card.querySelectorAll('.rd-variant-select option'))
                            .map(option => option.textContent)
                            .join(' ')
                    );

                    /*
                     * "all" displays every product.
                     * Other categories match the product's data-category.
                     */
                    const categoryMatches =
                        selectedCategory === 'all' ||
                        productCategory === selectedCategory;

                    /*
                     * Search checks product name, description,
                     * category and variant names.
                     */
                    const searchMatches =
                        !term ||
                        productName.includes(term) ||
                        description.includes(term) ||
                        productCategory.includes(term) ||
                        variantDetails.includes(term);

                    const visible = categoryMatches && searchMatches;

                    card.style.display = visible ? '' : 'none';

                    if (visible) {
                        visibleCount++;
                    }
                });

                /* No-results message */
                if (noResults) {

                    noResults.style.display = visibleCount === 0 ? 'block' : 'none';

                    noResults.textContent = term
                        ? `No menu items found for "${searchInput?.value || ''}".`
                        : 'No dishes found in this category.';
                }

                /* Heading */
                if (categoryHeading) {

                    if (selectedCategory === 'all') {

                        categoryHeading.textContent = 'All Dishes';

                    } else {

                        const activeButton = document.querySelector('.rd-category.active');

                        const categoryName = activeButton
                            ?.querySelectorAll('span')[1]
                            ?.textContent.trim();

                        categoryHeading.textContent = categoryName || 'Menu';
                    }
                }
            }

            /* -------------------------------------------------------
               CATEGORY BUTTONS
            ------------------------------------------------------- */

            categoryButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    categoryButtons.forEach(function (item) {
                        item.classList.remove('active');
                    });

                    this.classList.add('active');

                    selectedCategory = normalizeCategory(this.dataset.category);

                    updateMenu();
                });
            });

            /* -------------------------------------------------------
               SEARCH EVENTS
            ------------------------------------------------------- */

            if (searchInput) {

                searchInput.addEventListener('input', updateMenu);
                searchInput.addEventListener('search', updateMenu);
                searchInput.addEventListener('keyup', updateMenu);
            }

            /* -------------------------------------------------------
               INITIAL CATEGORY
            ------------------------------------------------------- */

            const allButton = categoryButtons.find(function (button) {
                return normalizeCategory(button.dataset.category) === 'all';
            });

            if (allButton) {

                categoryButtons.forEach(button => button.classList.remove('active'));

                allButton.classList.add('active');

                selectedCategory = 'all';
            }

            updateMenu();

            /* -------------------------------------------------------
               GET SELECTED VARIANT
            ------------------------------------------------------- */

            function getSelectedVariant(card) {

                const select = card.querySelector('.rd-variant-select');

                if (!select) {

                    return {
                        id: card.dataset.defaultVariantId || null,
                        price: Number(card.dataset.price || 0),
                        actualPrice: 0,
                        image: card.dataset.image || '',
                        label: '',
                        stock: 0,
                        minOrder: 1,
                        maxOrder: 0,
                        available: card.dataset.available === '1'
                    };
                }

                const option = select.options[select.selectedIndex];

                if (!option) {
                    return null;
                }

                return {
                    id: option.value,
                    price: Number(option.dataset.price || 0),
                    actualPrice: Number(option.dataset.actualPrice || 0),
                    image: option.dataset.image || card.dataset.image || '',
                    label: option.textContent.split(' — ')[0].trim(),
                    stock: Number(option.dataset.stock || 0),
                    minOrder: Number(option.dataset.minOrder || 1),
                    maxOrder: Number(option.dataset.maxOrder || 0),
                    available: option.dataset.available === '1'
                };
            }

            /* -------------------------------------------------------
               UPDATE VARIANT DETAILS ON PRODUCT CARD
            ------------------------------------------------------- */

            function updateCardVariant(card) {

                const variant = getSelectedVariant(card);

                if (!variant) {
                    return;
                }

                const priceElement = card.querySelector('.rd-current-price');

                if (priceElement) {
                    priceElement.textContent = variant.price.toFixed(2);
                }

                const oldPrice = card.querySelector('.rd-variant-price-old');

                if (oldPrice) {

                    if (variant.actualPrice > variant.price) {

                        oldPrice.textContent = money(variant.actualPrice);
                        oldPrice.style.display = '';

                    } else {

                        oldPrice.style.display = 'none';
                    }

                } else if (variant.actualPrice > variant.price) {

                    const old = document.createElement('span');

                    old.className = 'rd-variant-price-old';
                    old.textContent = money(variant.actualPrice);

                    card.querySelector('.rd-dish-price')?.appendChild(old);
                }

                const image = card.querySelector('.rd-dish-photo img');

                if (image && variant.image) {

                    image.onerror = function () {
                        this.onerror = null;
                        this.src = 'https://placehold.co/400x300/171717/ffffff?text=Food';
                    };

                    image.src = variant.image;
                }

                card.dataset.price = String(variant.price);
                card.dataset.image = variant.image;

                const sku = card.querySelector('.rd-variant-sku');
                const prep = card.querySelector('.rd-variant-prep');

                const selectedOption = card.querySelector('.rd-variant-select')
                    ?.selectedOptions?.[0];

                if (sku && selectedOption) {
                    sku.textContent = selectedOption.dataset.sku
                        ? 'SKU: ' + selectedOption.dataset.sku
                        : '';
                }

                if (prep && selectedOption) {
                    prep.textContent = selectedOption.dataset.prep
                        ? 'Prep: ' + selectedOption.dataset.prep + ' min'
                        : '';
                }

                const addButton = card.querySelector('.rd-add');

                if (addButton) {

                    addButton.disabled =
                        !variant.available || card.dataset.available === '0';

                    addButton.textContent = addButton.disabled
                        ? 'Unavailable'
                        : 'Add';
                }
            }

            /* -------------------------------------------------------
               PRODUCT ADD-ON GROUPS
            ------------------------------------------------------- */

            function getCardAddonGroups(card) {

                try {
                    return JSON.parse(card.dataset.addons || '[]');
                } catch (error) {
                    console.error('Unable to read product add-ons:', error);
                    return [];
                }
            }

            /* -------------------------------------------------------
               OPEN ADD-ON MODAL
            ------------------------------------------------------- */

            function openAddonModal(card) {

                const variant = getSelectedVariant(card);

                if (!variant || !variant.available || card.dataset.available === '0') {
                    return;
                }

                const groups = getCardAddonGroups(card);

                pendingDish = {
                    card,
                    variant,
                    groups
                };

                const productNameElement = document.getElementById('rd-addon-product-name');

                if (productNameElement) {
                    productNameElement.textContent = card.dataset.name || '';
                }

                if (!addonGroupsContainer || !addonModal) {
                    addConfiguredDish(card, variant, []);
                    pendingDish = null;
                    return;
                }

                addonGroupsContainer.innerHTML = '';

                if (!groups.length) {

                    addConfiguredDish(card, variant, []);

                    pendingDish = null;

                    return;
                }

                groups.forEach(function (group) {

                    const section = document.createElement('section');

                    section.className = 'rd-addon-group';
                    section.dataset.groupId = group.id;

                    const title = document.createElement('div');

                    title.className = 'rd-addon-group-title';

                    const groupName = document.createElement('span');

                    groupName.textContent = group.name;

                    const requirement = document.createElement('span');

                    requirement.textContent = group.is_required ? 'Required' : 'Optional';

                    title.append(groupName, requirement);

                    const hint = document.createElement('p');

                    hint.className = 'rd-addon-group-hint';

                    const min = Number(group.min_select || 0);
                    const max = Number(group.max_select || 1);

                    hint.textContent = max > 1
                        ? `Choose ${min} to ${max}`
                        : (group.is_required
                            ? 'Choose one option'
                            : 'Choose an option if you like');

                    section.append(title, hint);

                    const isMultiple = String(group.selection_type || '')
                        .toLowerCase()
                        .includes('multiple');

                    (group.options || []).forEach(function (option) {

                        const label = document.createElement('label');

                        label.className = 'rd-addon-option';

                        const input = document.createElement('input');

                        input.type = isMultiple ? 'checkbox' : 'radio';

                        input.name = isMultiple
                            ? `addon-group-${group.id}[]`
                            : `addon-group-${group.id}`;

                        input.value = option.id;
                        input.dataset.name = option.name;
                        input.dataset.price = option.price;

                        input.addEventListener('change', function () {

                            if (isMultiple && this.checked && max > 0) {

                                const checked = section.querySelectorAll('input:checked');

                                if (checked.length > max) {

                                    this.checked = false;

                                    showAddonError(
                                        `You can select a maximum of ${max} option(s) for ${group.name}.`
                                    );
                                }
                            }

                            updateAddonTotal();
                        });

                        const name = document.createElement('span');

                        name.textContent = option.name;

                        const price = document.createElement('span');

                        price.className = 'rd-addon-option-price';

                        price.textContent = option.price > 0
                            ? '+ ' + money(option.price)
                            : 'Free';

                        label.append(input, name, price);

                        section.appendChild(label);
                    });

                    addonGroupsContainer.appendChild(section);
                });

                const error = document.createElement('p');

                error.className = 'rd-addon-error';
                error.id = 'rd-addon-error';
                error.style.display = 'none';

                addonGroupsContainer.appendChild(error);

                if (addonConfirmButton) {
                    addonConfirmButton.disabled = false;
                }

                updateAddonTotal();

                addonModal.classList.add('open');

                addonModal.setAttribute('aria-hidden', 'false');
            }

            /* -------------------------------------------------------
               ADD-ON ERROR
            ------------------------------------------------------- */

            function showAddonError(message) {

                const error = document.getElementById('rd-addon-error');

                if (!error) {
                    return;
                }

                error.textContent = message;

                error.style.display = 'block';
            }

            /* -------------------------------------------------------
               UPDATE ADD-ON TOTAL
            ------------------------------------------------------- */

            function updateAddonTotal() {

                if (!pendingDish || !addonGroupsContainer) {
                    return;
                }

                const selected = Array.from(
                    addonGroupsContainer.querySelectorAll('input:checked')
                );

                const addonTotal = selected.reduce(function (sum, input) {
                    return sum + Number(input.dataset.price || 0);
                }, 0);

                const totalElement = document.getElementById('rd-addon-total');

                if (totalElement) {
                    totalElement.textContent = money(
                        pendingDish.variant.price + addonTotal
                    );
                }
            }

            /* -------------------------------------------------------
               COLLECT SELECTED ADD-ONS
            ------------------------------------------------------- */

            function collectSelectedAddons() {

                const selected = [];

                if (!pendingDish || !addonGroupsContainer) {
                    return selected;
                }

                for (const group of pendingDish.groups) {

                    const section = addonGroupsContainer.querySelector(
                        `[data-group-id="${group.id}"]`
                    );

                    const inputs = Array.from(
                        section?.querySelectorAll('input:checked') || []
                    );

                    const min = Number(group.min_select || 0);
                    const max = Number(group.max_select || 1);

                    const requiredMin = group.is_required
                        ? Math.max(1, min)
                        : min;

                    if (inputs.length < requiredMin) {

                        showAddonError(
                            `Please select at least ${requiredMin} option(s) for ${group.name}.`
                        );

                        return null;
                    }

                    if (max > 0 && inputs.length > max) {

                        showAddonError(
                            `Please select no more than ${max} option(s) for ${group.name}.`
                        );

                        return null;
                    }

                    inputs.forEach(function (input) {

                        selected.push({
                            id: Number(input.value),
                            name: input.dataset.name,
                            price: Number(input.dataset.price || 0),
                            groupId: Number(group.id),
                            groupName: group.name
                        });
                    });
                }

                return selected;
            }

            /* -------------------------------------------------------
               CLOSE ADD-ON MODAL
            ------------------------------------------------------- */

            function closeAddonModal() {

                if (!addonModal) {
                    return;
                }

                addonModal.classList.remove('open');

                addonModal.setAttribute('aria-hidden', 'true');

                pendingDish = null;
            }

            if (addonModal) {

                addonModal.querySelectorAll('[data-addon-close]').forEach(function (button) {
                    button.addEventListener('click', closeAddonModal);
                });
            }

            if (addonConfirmButton) {
                addonConfirmButton.addEventListener('click', async function () {
                    if (!pendingDish) return;

                    const selectedAddons = collectSelectedAddons();
                    if (selectedAddons === null) return;

                    const { card, variant } = pendingDish;
                    addonConfirmButton.disabled = true;
                    const originalText = addonConfirmButton.textContent;
                    addonConfirmButton.textContent = 'Adding...';

                    try {
                        const added = await addConfiguredDish(card, variant, selectedAddons);
                        if (added) closeAddonModal();
                    } catch (error) {
                        alert(error.message || 'Unable to add this item.');
                    } finally {
                        addonConfirmButton.disabled = false;
                        addonConfirmButton.textContent = originalText || 'Add to Cart';
                    }
                });
            }

            /* -------------------------------------------------------
               SAVE CART
            ------------------------------------------------------- */

            function persistCart() {

                try {

                    localStorage.setItem(
                        cartStorageKey,
                        JSON.stringify(cart)
                    );

                } catch (error) {

                    console.warn('Cart could not be saved in this browser:', error);
                }
            }

            /* -------------------------------------------------------
               ADD PRODUCT TO CART
            ------------------------------------------------------- */

            async function addConfiguredDish(card, variant, addons) {
                if (!variant || !variant.id || variant.id === 'default') {
                    alert('Please select a valid product variant.');
                    return false;
                }

                const productId = String(card.dataset.id);
                const addonKey = addons.map(addon => addon.id).sort((a, b) => a - b).join(',');
                const variantId = String(variant.id);
                const key = `${productId}:${variantId}:${addonKey}`;
                const currentQty = cart[key]?.qty || 0;

                if (variant.stock > 0 && currentQty >= variant.stock) {
                    alert('Only ' + variant.stock + ' units are available for this variant.');
                    return false;
                }

                const result = await cartApiRequest(cartAddUrl, 'POST', {
                    product_variant_id: Number(variantId),
                    quantity: 1
                });

                if (!cart[key]) {
                    cart[key] = {
                        id: key,
                        productId,
                        variantId,
                        databaseCartItemId: result.cart_item_id || null,
                        name: card.dataset.name,
                        variantLabel: variant.label || '',
                        price: Number(variant.price || 0) + addons.reduce(
                            (sum, addon) => sum + Number(addon.price || 0), 0
                        ),
                        basePrice: Number(variant.price || 0),
                        image: variant.image || card.dataset.image,
                        stock: Number(variant.stock || 0),
                        addons,
                        qty: 1
                    };
                } else {
                    cart[key].qty += 1;
                    cart[key].databaseCartItemId = result.cart_item_id || cart[key].databaseCartItemId;
                }

                persistCart();
                renderCart();
                return true;
            }

            /* -------------------------------------------------------
               RENDER CART
            ------------------------------------------------------- */

            function renderCart() {

                if (!cartItemsElement) {
                    return;
                }

                cartItemsElement.innerHTML = '';

                const items = Object.values(cart).filter(item => item.qty > 0);

                const itemCount = items.reduce(
                    (sum, item) => sum + item.qty,
                    0
                );

                const subtotal = items.reduce(
                    (sum, item) => sum + item.price * item.qty,
                    0
                );

                const cartCountElement = document.getElementById('rd-cart-count');

                if (cartCountElement) {

                    cartCountElement.textContent =
                        '(' + itemCount + (itemCount === 1 ? ' item' : ' items') + ')';
                }

                if (!items.length) {

                    cartItemsElement.innerHTML = `
                                        <div class="rd-cart-empty">
                                            <span>🛒</span>
                                            Your cart is waiting.<br>
                                            Add delicious dishes to get started!
                                        </div>
                                    `;

                } else {

                    items.forEach(function (item) {

                        const row = document.createElement('div');

                        row.className = 'rd-cart-item';

                        const img = document.createElement('img');

                        img.src = item.image ||
                            'https://placehold.co/150x120/171717/ffffff?text=Food';

                        img.alt = item.name;

                        const details = document.createElement('div');

                        const name = document.createElement('div');

                        name.className = 'rd-cart-item-name';

                        name.textContent =
                            item.name +
                            (item.variantLabel ? ` (${item.variantLabel})` : '');

                        const price = document.createElement('div');

                        price.className = 'rd-cart-item-price';

                        price.textContent = money(item.price * item.qty);

                        details.append(name, price);

                        if (item.addons && item.addons.length) {

                            const addonNames = document.createElement('small');

                            addonNames.style.cssText =
                                'display:block;margin-top:3px;color:#71717a;font-size:9px;line-height:1.4';

                            addonNames.textContent = item.addons
                                .map(addon => addon.name)
                                .join(', ');

                            details.appendChild(addonNames);
                        }

                        const controls = document.createElement('div');

                        controls.className = 'rd-cart-qty';

                        const minus = document.createElement('button');

                        minus.textContent = '−';

                        minus.setAttribute('aria-label', 'Decrease quantity');

                        const qty = document.createElement('span');

                        qty.textContent = item.qty;

                        const plus = document.createElement('button');

                        plus.textContent = '+';

                        plus.setAttribute('aria-label', 'Increase quantity');

                        minus.addEventListener('click', function () {
                            changeQty(item.id, -1);
                        });

                        plus.addEventListener('click', function () {
                            changeQty(item.id, 1);
                        });

                        controls.append(minus, qty, plus);

                        const remove = document.createElement('button');

                        remove.className = 'rd-cart-delete';

                        remove.textContent = '×';

                        remove.setAttribute('aria-label', 'Remove item');

                        remove.addEventListener('click', async function () {
                            const databaseId = item.databaseCartItemId;
                            const relatedItems = Object.values(cart).filter(
                                entry => databaseId
                                    ? String(entry.databaseCartItemId) === String(databaseId)
                                    : entry.id === item.id
                            );

                            relatedItems.forEach(entry => delete cart[entry.id]);

                            try {
                                if (databaseId) {
                                    await cartApiRequest(
                                        cartRemoveUrlTemplate.replace('__ID__', encodeURIComponent(databaseId)),
                                        'DELETE'
                                    );
                                }
                            } catch (error) {
                                if (!isMissingCartItemError(error)) {
                                    alert(error.message || 'Unable to remove item.');
                                    return;
                                }
                                // The cart page may already have deleted this database item.
                                // Remove its stale browser copy instead of showing an error.
                            }

                            persistCart();
                            renderCart();
                        });

                        row.append(img, details, controls, remove);

                        cartItemsElement.appendChild(row);
                    });
                }

                /* Cart totals */

                const delivery = subtotal === 0
                    ? 0
                    : restaurantDeliveryFee;

                const tax = 0;
                const discount = 0;

                const total = Math.max(0, subtotal + delivery);

                const subtotalElement = document.getElementById('rd-subtotal');
                const deliveryElement = document.getElementById('rd-delivery');
                const taxElement = document.getElementById('rd-tax');
                const discountElement = document.getElementById('rd-discount');
                const discountRow = document.getElementById('rd-discount-row');
                const totalElement = document.getElementById('rd-total');
                const checkoutButton = document.getElementById('rd-checkout');
                const savedElement = document.getElementById('rd-saved');

                if (subtotalElement) {
                    subtotalElement.textContent = money(subtotal);
                }

                if (deliveryElement) {
                    deliveryElement.textContent =
                        delivery === 0 && subtotal > 0
                            ? 'FREE'
                            : money(delivery);
                }

                if (taxElement) {
                    taxElement.textContent = money(tax);
                }

                if (discountElement) {
                    discountElement.textContent = '−' + money(discount);
                }

                if (discountRow) {
                    discountRow.style.display = discount ? 'flex' : 'none';
                }

                if (totalElement) {
                    totalElement.textContent = money(total);
                }

                const belowMinimum =
                    restaurantMinimumOrder > 0 &&
                    subtotal > 0 &&
                    subtotal < restaurantMinimumOrder;

                if (checkoutButton) {
                    checkoutButton.disabled = itemCount === 0 || belowMinimum;
                }

                if (savedElement) {

                    savedElement.textContent = belowMinimum
                        ? 'Minimum order is ' + money(restaurantMinimumOrder) +
                        '. Add ' + money(restaurantMinimumOrder - subtotal) + ' more.'
                        : subtotal > 0
                            ? 'Delivery fee is based on this restaurant.'
                            : 'Add items to see your order total.';
                }

                syncDishControls();
            }

            /* -------------------------------------------------------
               CHANGE CART QUANTITY
            ------------------------------------------------------- */

            async function changeQty(id, change) {
                if (!cart[id]) return;

                const item = cart[id];
                if (change > 0 && Number(item.stock || 0) > 0 && item.qty >= Number(item.stock)) {
                    alert('Maximum available stock reached.');
                    return;
                }

                const previousQty = item.qty;
                item.qty += change;
                if (item.qty <= 0) delete cart[id];

                const databaseId = item.databaseCartItemId;
                if (databaseId) {
                    const combinedQuantity = Object.values(cart)
                        .filter(entry => String(entry.databaseCartItemId) === String(databaseId))
                        .reduce((sum, entry) => sum + Number(entry.qty || 0), 0);

                    try {
                        if (combinedQuantity <= 0) {
                            await cartApiRequest(
                                cartRemoveUrlTemplate.replace('__ID__', encodeURIComponent(databaseId)),
                                'DELETE'
                            );
                        } else {
                            await cartApiRequest(
                                cartUpdateUrlTemplate.replace('__ID__', encodeURIComponent(databaseId)),
                                'PUT',
                                { quantity: combinedQuantity }
                            );
                        }
                    } catch (error) {
                        if (isMissingCartItemError(error)) {
                            Object.keys(cart).forEach(key => {
                                if (String(cart[key].databaseCartItemId) === String(databaseId)) {
                                    delete cart[key];
                                }
                            });
                            persistCart();
                            renderCart();
                            return;
                        }
                        if (cart[id]) cart[id].qty = previousQty;
                        else cart[id] = item;
                        alert(error.message || 'Unable to update cart quantity.');
                        return;
                    }
                }

                persistCart();
                renderCart();
            }

            /* -------------------------------------------------------
               SYNC PRODUCT QUANTITY BUTTONS
            ------------------------------------------------------- */

            function syncDishControls() {

                dishCards.forEach(function (card) {

                    const productItems = Object.values(cart).filter(function (item) {
                        return item.productId === String(card.dataset.id);
                    });

                    const quantity = productItems.reduce(
                        (sum, item) => sum + item.qty,
                        0
                    );

                    const addButton = card.querySelector('.rd-add');
                    const qtyBox = card.querySelector('.rd-qty');
                    const qtyValue = card.querySelector('.rd-qty-value');

                    if (!addButton || !qtyBox || !qtyValue) {
                        return;
                    }

                    if (quantity > 0) {

                        addButton.style.display = 'none';

                        qtyBox.style.display = 'flex';

                        qtyValue.textContent = quantity;

                    } else {

                        addButton.style.display = 'block';

                        qtyBox.style.display = 'none';

                        const variant = getSelectedVariant(card);

                        addButton.disabled =
                            card.dataset.available === '0' ||
                            !variant?.available;

                        addButton.textContent = addButton.disabled
                            ? 'Unavailable'
                            : 'Add';
                    }
                });
            }

            /* -------------------------------------------------------
               PRODUCT CARD EVENTS
            ------------------------------------------------------- */

            dishCards.forEach(function (card) {

                const id = String(card.dataset.id);

                const variantSelect = card.querySelector('.rd-variant-select');

                if (variantSelect) {

                    variantSelect.addEventListener('change', function () {

                        updateCardVariant(card);

                        syncDishControls();
                    });
                }

                const addButton = card.querySelector('.rd-add');

                if (addButton) {

                    addButton.addEventListener('click', function () {
                        openAddonModal(card);
                    });
                }

                function selectedVariantCartItem() {

                    const selected = getSelectedVariant(card);

                    if (!selected) {
                        return null;
                    }

                    return Object.values(cart)
                        .filter(function (entry) {

                            return entry.productId === id &&
                                String(entry.variantId) === String(selected.id || 'default');
                        })
                        .sort((a, b) => a.id.localeCompare(b.id))[0] || null;
                }

                const minusButton = card.querySelector('.rd-minus');

                if (minusButton) {

                    minusButton.addEventListener('click', function () {

                        const item = selectedVariantCartItem();

                        if (item) {
                            changeQty(item.id, -1);
                        }
                    });
                }

                const plusButton = card.querySelector('.rd-plus');

                if (plusButton) {

                    plusButton.addEventListener('click', function () {

                        const item = selectedVariantCartItem();

                        if (!item) {
                            return;
                        }

                        const selected = getSelectedVariant(card);

                        if (selected?.stock > 0 && item.qty >= selected.stock) {

                            alert('Only ' + selected.stock + ' units are available for this variant.');

                            return;
                        }

                        changeQty(item.id, 1);
                    });
                }

                const favButton = card.querySelector('.rd-fav');

                if (favButton) {
                    favButton.addEventListener('click', async function () {

                        if (this.disabled) return;

                        const button = this;
                        const wishlistItemId = button.dataset.wishlistItemId;

                        button.disabled = true;

                        try {

                            // REMOVE FROM WISHLIST
                            if (wishlistItemId) {

                                const removeUrl = wishlistRemoveUrlTemplate.replace(
                                    '__ID__',
                                    encodeURIComponent(wishlistItemId)
                                );

                                await wishlistApiRequest(removeUrl, 'DELETE');

                                delete button.dataset.wishlistItemId;

                                button.classList.remove('active');
                                button.textContent = '♡';
                                button.setAttribute('aria-pressed', 'false');

                            } else {

                                // ADD TO WISHLIST
                                const selectedVariant = getSelectedVariant(card);

                                if (!selectedVariant || !selectedVariant.id) {
                                    throw new Error('Please select a product variant.');
                                }

                                const result = await wishlistApiRequest(
                                    wishlistAddUrl,
                                    'POST',
                                    {
                                        product_variant_id: Number(selectedVariant.id)
                                    }
                                );

                                if (!result.wishlist_item_id) {
                                    throw new Error('Wishlist item ID was not returned by the server.');
                                }

                                button.dataset.wishlistItemId = result.wishlist_item_id;

                                button.classList.add('active');
                                button.textContent = '♥';
                                button.setAttribute('aria-pressed', 'true');
                            }

                        } catch (error) {

                            alert(error.message || 'Something went wrong.');

                        } finally {

                            button.disabled = false;
                        }
                    });
                }
            });

            /* -------------------------------------------------------
               CLEAR CART
            ------------------------------------------------------- */

            const clearButton = document.getElementById('rd-clear');

            if (clearButton) {

                clearButton.addEventListener('click', async function () {
                    const databaseIds = [...new Set(
                        Object.values(cart).map(item => item.databaseCartItemId).filter(Boolean).map(String)
                    )];

                    try {
                        for (const databaseId of databaseIds) {
                            try {
                                await cartApiRequest(
                                    cartRemoveUrlTemplate.replace('__ID__', encodeURIComponent(databaseId)),
                                    'DELETE'
                                );
                            } catch (error) {
                                if (!isMissingCartItemError(error)) throw error;
                            }
                        }

                        cart = {};
                        persistCart();
                        couponApplied = false;
                        renderCart();
                    } catch (error) {
                        alert(error.message || 'Unable to clear the cart.');
                    }
                });
            }

            /* -------------------------------------------------------
               COUPON PANEL
            ------------------------------------------------------- */

            const couponToggle = document.getElementById('rd-coupon-toggle');

            if (couponToggle) {

                couponToggle.addEventListener('click', function () {

                    const panel = document.getElementById('rd-coupon-input');

                    if (!panel) {
                        return;
                    }

                    panel.style.display =
                        panel.style.display === 'flex'
                            ? 'none'
                            : 'flex';
                });
            }

            const applyCouponButton = document.getElementById('rd-apply-coupon');

            if (applyCouponButton) {

                applyCouponButton.addEventListener('click', function () {

                    alert(
                        'Coupon validation is handled on the secure checkout page using active coupon records.'
                    );
                });
            }

            /* -------------------------------------------------------
               PAGE TABS
            ------------------------------------------------------- */

            document.querySelectorAll('.rd-tab[data-tab]').forEach(function (tab) {

                tab.addEventListener('click', function () {

                    document.querySelectorAll('.rd-tab[data-tab]').forEach(function (item) {
                        item.classList.remove('active');
                    });

                    this.classList.add('active');

                    const panelName = this.dataset.tab;

                    const isMenu = panelName === 'menu';

                    const layout = document.getElementById('rd-layout');

                    if (layout) {
                        layout.classList.toggle('rd-layout--tab-view', !isMenu);
                    }

                    const categoryPanel = document.getElementById('rd-category-panel');
                    const cartPanel = document.getElementById('rd-cart-panel');
                    const menuContent = document.querySelector('.rd-menu-content');

                    if (categoryPanel) {
                        categoryPanel.style.display = isMenu ? '' : 'none';
                    }

                    if (cartPanel) {
                        cartPanel.style.display = isMenu ? '' : 'none';
                    }

                    if (menuContent) {
                        menuContent.style.display = isMenu ? '' : 'none';
                    }

                    document.querySelectorAll('.rd-extra-panel').forEach(function (panel) {

                        panel.style.display =
                            panel.dataset.panel === panelName
                                ? 'block'
                                : 'none';
                    });
                });
            });

            /* -------------------------------------------------------
               RESTAURANT FAVOURITE
            ------------------------------------------------------- */

            const restaurantFavorite = document.getElementById('restaurantFavorite');

            if (restaurantFavorite) {

                restaurantFavorite.addEventListener('click', function () {

                    const active = this.dataset.active === '1';

                    this.dataset.active = active ? '0' : '1';

                    this.textContent = active ? '♡' : '♥';

                    this.style.color = active ? '#fff' : '#ff0718';
                });
            }

            /* -------------------------------------------------------
               CHECKOUT
            ------------------------------------------------------- */

            const checkoutButton = document.getElementById('rd-checkout');

            if (checkoutButton) {
                checkoutButton.addEventListener('click', function () {
                    const items = Object.values(cart).filter(item => Number(item.qty || 0) > 0);

                    if (!items.length) {
                        alert('Your cart is empty. Please add a dish before checkout.');
                        renderCart();
                        return;
                    }

                    // Cart items are saved through cart.add/cart.update API calls.
                    // The Laravel checkout reads the authenticated customer's DB cart.
                    const unsavedItems = items.filter(item => !item.databaseCartItemId);
                    if (unsavedItems.length) {
                        alert('Some cart items are not saved to your account. Please remove them and add them again.');
                        return;
                    }

                    checkoutButton.disabled = true;
                    checkoutButton.textContent = 'Redirecting to Checkout...';
                    window.location.assign(checkoutUrl);
                });
            }

            /* -------------------------------------------------------
               INITIALIZE PRODUCT VARIANTS AND CART
            ------------------------------------------------------- */

            dishCards.forEach(function (card) {
                updateCardVariant(card);
            });

            renderCart();

        });
    </script>

@endsection