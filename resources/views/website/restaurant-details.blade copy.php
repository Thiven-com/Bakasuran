@extends('layouts.website')

@section('content')

    @php
        $restaurant = [
            'name' => 'Bakasuran Signature',
            'rating' => '4.7',
            'reviews' => '2.1K+',
            'delivery_time' => '25–35 mins',
            'price_for_two' => 199,
            'offer' => 20,
            'image' => 'photo-1563379091339-03246963d96c',
        ];

        $categories = [
            ['name' => 'Biryani', 'count' => 12, 'icon' => '🍛'],
            ['name' => 'Starters', 'count' => 18, 'icon' => '🥙'],
            ['name' => 'Kebabs', 'count' => 12, 'icon' => '🍢'],
            ['name' => 'Main Course', 'count' => 20, 'icon' => '🍗'],
            ['name' => 'Chinese', 'count' => 14, 'icon' => '🥢'],
            ['name' => 'Rice & Noodles', 'count' => 10, 'icon' => '🍜'],
            ['name' => 'Combos', 'count' => 8, 'icon' => '🍱'],
            ['name' => 'Breads', 'count' => 8, 'icon' => '🫓'],
            ['name' => 'Desserts', 'count' => 6, 'icon' => '🍰'],
            ['name' => 'Beverages', 'count' => 10, 'icon' => '🥤'],
        ];

        $menu = [
            [
                'id' => 1,
                'name' => 'Chicken Dum Biryani',
                'category' => 'Biryani',
                'description' => 'Aromatic basmati rice cooked with tender chicken and signature Bakasuran spices.',
                'price' => 249,
                'rating' => '4.8',
                'reviews' => '1.2K+',
                'image' => 'photo-1563379091339-03246963d96c',
                'veg' => false,
                'badge' => 'Bestseller',
            ],
            [
                'id' => 2,
                'name' => 'Mutton Dum Biryani',
                'category' => 'Biryani',
                'description' => 'Juicy mutton pieces cooked with traditional spices and fragrant basmati rice.',
                'price' => 299,
                'rating' => '4.7',
                'reviews' => '980+',
                'image' => 'photo-1589302168068-964664d93dc0',
                'veg' => false,
                'badge' => '',
            ],
            [
                'id' => 3,
                'name' => 'Egg Biryani',
                'category' => 'Biryani',
                'description' => 'Flavourful basmati rice with boiled eggs and aromatic spices.',
                'price' => 199,
                'rating' => '4.5',
                'reviews' => '620+',
                'image' => 'photo-1512058564366-18510be2db19',
                'veg' => false,
                'badge' => '',
            ],
            [
                'id' => 4,
                'name' => 'Veg Biryani',
                'category' => 'Biryani',
                'description' => 'A delicious mix of fresh vegetables and long-grain basmati rice.',
                'price' => 179,
                'rating' => '4.4',
                'reviews' => '410+',
                'image' => 'photo-1596797038530-2c107229654b',
                'veg' => true,
                'badge' => '',
            ],
            [
                'id' => 5,
                'name' => 'Chicken 65',
                'category' => 'Starters',
                'description' => 'Crispy, spicy chicken bites tossed with curry leaves and Indian spices.',
                'price' => 199,
                'rating' => '4.6',
                'reviews' => '750+',
                'image' => 'photo-1626082927389-6cd097cdc6ec',
                'veg' => false,
                'badge' => 'Popular',
            ],
            [
                'id' => 6,
                'name' => 'Paneer Tikka',
                'category' => 'Starters',
                'description' => 'Smoky grilled paneer marinated in a creamy tandoori masala.',
                'price' => 189,
                'rating' => '4.5',
                'reviews' => '350+',
                'image' => 'photo-1567188040759-fb8a883dc6d8',
                'veg' => true,
                'badge' => '',
            ],
            [
                'id' => 7,
                'name' => 'Chicken Kebab',
                'category' => 'Kebabs',
                'description' => 'Tender, flame-grilled chicken with a rich spice marinade.',
                'price' => 229,
                'rating' => '4.6',
                'reviews' => '530+',
                'image' => 'photo-1599487488170-d11ec9c172f0',
                'veg' => false,
                'badge' => '',
            ],
            [
                'id' => 8,
                'name' => 'Cold Coffee',
                'category' => 'Beverages',
                'description' => 'Chilled, creamy coffee blended to perfection.',
                'price' => 99,
                'rating' => '4.4',
                'reviews' => '290+',
                'image' => 'photo-1461023058943-07fcbe16d735',
                'veg' => true,
                'badge' => '',
            ],
            [
                'id' => 9,
                'name' => 'Chocolate Brownie',
                'category' => 'Desserts',
                'description' => 'Rich chocolate brownie served with a delicious dessert finish.',
                'price' => 149,
                'rating' => '4.7',
                'reviews' => '320+',
                'image' => 'photo-1606313564200-e75d5e30476c',
                'veg' => true,
                'badge' => '',
            ],
        ];
    @endphp

    <style>
        /* BAKASURAN restaurant details */
        .rd-page {
            --rd-red: #ff0718;
            --rd-black: #080808;
            --rd-muted: #777b85;
            --rd-border: #ededed;
            color: #171717;
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
            padding-bottom: 35px;
        }

        .rd-page * {
            box-sizing: border-box;
        }

        .rd-page button,
        .rd-page input {
            font: inherit;
        }

        .rd-page button {
            cursor: pointer;
        }

        .rd-page a {
            text-decoration: none;
        }

        .rd-breadcrumb {
            padding: 12px 2.2%;
            font-size: 12px;
            color: #666;
            background: #fff;
        }

        .rd-breadcrumb a {
            color: #333;
        }

        .rd-breadcrumb span {
            margin: 0 8px;
            color: var(--rd-red);
        }

        /* Restaurant hero */
        .rd-hero {
            position: relative;
            min-height: 265px;
            padding: 28px 3%;
            color: white;
            display: flex;
            align-items: center;
            gap: 30px;
            overflow: hidden;
            background:
                linear-gradient(90deg, #080808 0%, rgba(8, 8, 8, .96) 29%,
                    rgba(8, 8, 8, .65) 55%, rgba(8, 8, 8, .2) 100%),
                url('https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=2000&q=90') center / cover no-repeat;
        }

        .rd-hero-main {
            display: flex;
            align-items: center;
            gap: 28px;
            position: relative;
            z-index: 2;
            width: 70%;
        }

        .rd-logo {
            width: 145px;
            height: 145px;
            border: 4px solid #fff;
            outline: 2px solid var(--rd-red);
            border-radius: 50%;
            flex-shrink: 0;
            background: #151515;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: 0 0 24px #ff07184a;
        }

        .rd-logo .rd-logo-icon {
            font-size: 42px;
        }

        .rd-logo strong {
            font-size: 18px;
            font-style: italic;
        }

        .rd-logo small {
            font-size: 7px;
            letter-spacing: 1px;
        }

        .rd-info {
            min-width: 0;
        }

        .rd-title-line {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .rd-title-line h1 {
            font-size: clamp(25px, 2.5vw, 36px);
            margin: 0;
            font-weight: 800;
        }

        .rd-open {
            background: #078d45;
            color: white;
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
        }

        .rd-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin: 12px 0 16px;
            font-size: 13px;
        }

        .rd-rating-star {
            color: #ffab00;
            font-size: 19px;
        }

        .rd-meta-divider {
            color: #777;
        }

        .rd-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .rd-tag {
            padding: 8px 12px;
            background: #292929;
            color: #fff;
            border-radius: 22px;
            font-size: 12px;
        }

        .rd-deals {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 15px;
        }

        .rd-deal {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid var(--rd-red);
            border-radius: 10px;
            padding: 5px 12px 5px 5px;
            font-size: 12px;
        }

        .rd-deal b {
            padding: 8px 11px;
            background: var(--rd-red);
            border-radius: 7px;
        }

        .rd-deal small {
            display: block;
            color: #ddd;
            margin-top: 2px;
        }

        .rd-gallery {
            margin-left: auto;
            width: 25%;
            min-width: 190px;
            max-width: 270px;
            position: relative;
            z-index: 2;
        }

        .rd-gallery-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .rd-gallery img {
            width: 100%;
            height: 70px;
            border: 1px solid #fff;
            border-radius: 8px;
            object-fit: cover;
        }

        .rd-gallery-button {
            height: 70px;
            background: #111;
            color: white;
            border: 1px solid #fff;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .rd-hero-actions {
            position: absolute;
            top: 14px;
            right: 20px;
            display: flex;
            gap: 8px;
            z-index: 3;
        }

        .rd-hero-actions button {
            background: #111b;
            color: #fff;
            border: 1px solid #555;
            border-radius: 8px;
            padding: 7px 10px;
        }

        /* Benefits */
        .rd-benefits {
            display: flex;
            gap: 0;
            margin-top: 15px;
        }

        .rd-benefit {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 0 15px;
            border-right: 1px solid #373737;
            font-size: 11px;
            line-height: 1.35;
        }

        .rd-benefit:first-child {
            padding-left: 0;
        }

        .rd-benefit:last-child {
            border: 0;
        }

        .rd-benefit-icon {
            color: var(--rd-red);
            font-size: 22px;
            border: 1px solid var(--rd-red);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        /* Tabs */
        .rd-tabs {
            display: flex;
            gap: 25px;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid #e6e6e6;
            overflow-x: auto;
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .rd-tab {
            border: 0;
            border-bottom: 3px solid transparent;
            background: transparent;
            padding: 17px 12px 14px;
            white-space: nowrap;
            color: #333;
            font-size: 13px;
            font-weight: 700;
        }

        .rd-tab.active {
            color: var(--rd-red);
            border-bottom-color: var(--rd-red);
        }

        /* Three-column menu layout */
        .rd-layout {
            display: grid;
            grid-template-columns: 216px minmax(0, 1fr) 335px;
            gap: 20px;
            padding: 14px 1.5%;
            align-items: start;
        }

        .rd-category-panel,
        .rd-cart-panel {
            border: 1px solid #ededed;
            border-radius: 9px;
            background: #fff;
            box-shadow: 0 3px 15px #00000008;
        }

        .rd-category-panel {
            padding: 12px 9px;
            position: sticky;
            top: 60px;
        }

        .rd-panel-title {
            margin: 0 5px 12px;
            font-size: 16px;
            font-weight: 800;
        }

        .rd-category-list {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .rd-category {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 11px 10px;
            border: 0;
            background: white;
            color: #353535;
            border-radius: 7px;
            text-align: left;
            font-size: 12px;
        }

        .rd-category span:first-child {
            font-size: 18px;
        }

        .rd-category .rd-count {
            margin-left: auto;
            color: #777;
            font-size: 11px;
        }

        .rd-category.active {
            background: var(--rd-red);
            color: white;
        }

        .rd-category.active .rd-count {
            color: white;
        }

        .rd-category-promo {
            margin-top: 14px;
            border-radius: 8px;
            padding: 20px 12px;
            color: #fff;
            min-height: 115px;
            display: flex;
            align-items: center;
            font-size: 19px;
            font-style: italic;
            font-weight: 900;
            background:
                linear-gradient(90deg, #000b, #0004),
                url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=500&q=80') center / cover;
        }

        .rd-category-promo span {
            color: var(--rd-red);
        }

        /* Menu heading */
        .rd-menu-area {
            min-width: 0;
        }

        .rd-menu-banner {
            min-height: 84px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 10px 16px;
            color: #fff;
            background:
                linear-gradient(90deg, #080808eF, #080808a8),
                url('https://images.unsplash.com/photo-1563379091339-03246963d96c?auto=format&fit=crop&w=1000&q=85') center / cover;
        }

        .rd-menu-banner img {
            width: 150px;
            height: 65px;
            object-fit: cover;
            border-radius: 7px;
        }

        .rd-menu-banner h2 {
            margin: 0 0 6px;
            font-size: 23px;
        }

        .rd-menu-banner p {
            margin: 0;
            font-size: 12px;
            color: #ddd;
        }

        .rd-menu-search {
            width: 200px;
            max-width: 40%;
            margin-left: auto;
            padding: 12px;
            background: #fff;
            color: #333;
            border: 0;
            border-radius: 7px;
            outline-color: var(--rd-red);
            font-size: 11px !important;
        }

        /* Dish rows */
        .rd-menu-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }

        .rd-dish {
            display: grid;
            grid-template-columns: 176px minmax(0, 1fr) 120px;
            align-items: center;
            gap: 16px;
            padding: 7px 12px;
            min-height: 120px;
            border: 1px solid #ededed;
            border-radius: 9px;
            background: #fff;
            box-shadow: 0 3px 12px #00000006;
        }

        .rd-dish-photo {
            height: 94px;
            position: relative;
        }

        .rd-dish-photo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 7px;
        }

        .rd-dish-badge {
            position: absolute;
            top: 5px;
            left: 5px;
            padding: 5px 8px;
            border-radius: 5px;
            color: white;
            background: var(--rd-red);
            font-size: 10px;
            font-weight: 800;
        }

        .rd-dish-info {
            min-width: 0;
        }

        .rd-dish-name-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rd-dish-name {
            font-size: 14px;
            font-weight: 800;
            margin: 0;
        }

        .rd-veg-mark {
            width: 14px;
            height: 14px;
            display: inline-grid;
            place-items: center;
            flex-shrink: 0;
            border: 1px solid #159447;
            color: #159447;
            font-size: 8px;
        }

        .rd-veg-mark.nonveg {
            border-color: #ef3333;
            color: #ef3333;
        }

        .rd-dish-description {
            margin: 7px 0;
            color: var(--rd-muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .rd-dish-price {
            font-size: 14px;
            font-weight: 800;
        }

        .rd-dish-rating {
            color: #777;
            font-size: 12px;
            margin-top: 5px;
        }

        .rd-dish-rating span {
            color: #ff9900;
            font-size: 17px;
        }

        .rd-dish-actions {
            display: flex;
            align-items: flex-end;
            flex-direction: column;
            gap: 10px;
        }

        .rd-fav {
            background: transparent;
            color: var(--rd-red);
            border: 0;
            font-size: 23px;
            line-height: 1;
        }

        .rd-fav.active {
            color: #d80013;
        }

        .rd-add {
            width: 100%;
            padding: 11px 12px;
            border: 0;
            border-radius: 7px;
            background: var(--rd-red);
            color: white;
            font-weight: 800;
            font-size: 12px !important;
        }

        .rd-qty {
            display: none;
            align-items: center;
            border: 1px solid var(--rd-red);
            border-radius: 7px;
            overflow: hidden;
        }

        .rd-qty button {
            width: 31px;
            height: 34px;
            border: 0;
            background: var(--rd-red);
            color: #fff;
            font-size: 18px;
        }

        .rd-qty span {
            min-width: 34px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
        }

        /* Cart sidebar */
        .rd-cart-panel {
            padding: 15px;
            position: sticky;
            top: 60px;
        }

        .rd-cart-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 13px;
        }

        .rd-cart-heading h2 {
            margin: 0;
            font-size: 17px;
        }

        .rd-cart-heading small {
            font-size: 11px;
            font-weight: 400;
        }

        .rd-clear {
            color: var(--rd-red);
            background: transparent;
            border: 0;
            font-size: 11px !important;
            font-weight: 700;
        }

        .rd-cart-items {
            max-height: 270px;
            overflow-y: auto;
        }

        .rd-cart-empty {
            text-align: center;
            padding: 24px 8px;
            color: #777;
            font-size: 12px;
        }

        .rd-cart-empty span {
            display: block;
            font-size: 35px;
            margin-bottom: 10px;
        }

        .rd-cart-item {
            display: grid;
            grid-template-columns: 58px minmax(0, 1fr) auto;
            align-items: center;
            gap: 9px;
            padding: 11px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .rd-cart-item img {
            width: 58px;
            height: 53px;
            object-fit: cover;
            border-radius: 6px;
        }

        .rd-cart-item-name {
            font-size: 11px;
            font-weight: 700;
            line-height: 1.4;
        }

        .rd-cart-item-price {
            margin-top: 5px;
            font-size: 12px;
            font-weight: 800;
        }

        .rd-cart-qty {
            display: flex;
            align-items: center;
            border: 1px solid #ffb5bb;
            border-radius: 6px;
            overflow: hidden;
        }

        .rd-cart-qty button {
            width: 22px;
            height: 27px;
            border: 0;
            background: white;
            color: var(--rd-red);
            font-weight: 800;
        }

        .rd-cart-qty span {
            min-width: 20px;
            text-align: center;
            font-size: 11px;
        }

        .rd-cart-delete {
            grid-column: 3;
            border: 0;
            background: transparent;
            color: #888;
            font-size: 14px;
            padding: 3px;
        }

        .rd-coupon {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: 13px 0;
            background: white;
            border: 0;
            border-bottom: 1px solid #eee;
            text-align: left;
            color: #333;
            font-size: 12px !important;
        }

        .rd-coupon-icon {
            background: var(--rd-red);
            color: #fff;
            padding: 3px 6px;
            border-radius: 3px;
        }

        .rd-coupon span:last-child {
            margin-left: auto;
        }

        .rd-coupon-input {
            display: none;
            gap: 6px;
            padding: 9px 0;
        }

        .rd-coupon-input input {
            width: 100%;
            min-width: 0;
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 5px;
            font-size: 11px !important;
        }

        .rd-coupon-input button {
            border: 0;
            background: var(--rd-red);
            color: white;
            padding: 8px;
            border-radius: 5px;
            font-size: 11px !important;
        }

        .rd-cart-totals {
            padding-top: 10px;
        }

        .rd-total-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 10px 0;
            color: #555;
            font-size: 12px;
        }

        .rd-total-row strong {
            color: #171717;
        }

        .rd-total-row.final {
            padding-top: 12px;
            border-top: 1px solid #eee;
            font-size: 15px;
            font-weight: 800;
            color: #111;
        }

        .rd-checkout {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 15px 10px;
            margin-top: 12px;
            background: var(--rd-red);
            color: white;
            border: 0;
            border-radius: 7px;
            font-weight: 800;
            font-size: 13px !important;
        }

        .rd-checkout:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .rd-saved {
            text-align: center;
            color: #078d45;
            margin: 13px 0 0;
            font-size: 11px;
        }

        .rd-no-results {
            display: none;
            padding: 25px;
            text-align: center;
            color: #777;
        }

        /* Other tab panels */
        .rd-extra-panel {
            display: none;
            grid-column: 1 / -1;
            min-height: 240px;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 10px;
            line-height: 1.8;
        }

        .rd-extra-panel h2 {
            margin-top: 0;
        }

        .rd-photo-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rd-photo-strip img {
            width: 180px;
            height: 130px;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Responsive */
        @media (max-width: 1250px) {
            .rd-layout {
                grid-template-columns: 185px minmax(0, 1fr) 290px;
                gap: 12px;
            }

            .rd-dish {
                grid-template-columns: 125px minmax(0, 1fr) 95px;
                gap: 11px;
            }

            .rd-gallery {
                min-width: 170px;
            }

            .rd-hero-main {
                width: 75%;
                gap: 20px;
            }

            .rd-logo {
                width: 110px;
                height: 110px;
            }

            .rd-logo .rd-logo-icon {
                font-size: 32px;
            }

            .rd-logo strong {
                font-size: 15px;
            }

            .rd-benefit {
                padding: 0 8px;
            }
        }

        @media (max-width: 950px) {
            .rd-hero {
                align-items: flex-start;
            }

            .rd-hero-main {
                width: 100%;
            }

            .rd-gallery {
                display: none;
            }

            .rd-layout {
                grid-template-columns: 165px minmax(0, 1fr);
            }

            .rd-cart-panel {
                grid-column: 1 / -1;
                position: static;
            }

            .rd-category-panel {
                position: static;
            }

            .rd-dish {
                grid-template-columns: 135px minmax(0, 1fr) 95px;
            }
        }

        @media (max-width: 600px) {
            .rd-breadcrumb {
                padding: 10px 13px;
                font-size: 11px;
            }

            .rd-hero {
                padding: 28px 15px 18px;
                min-height: auto;
            }

            .rd-hero-main {
                align-items: flex-start;
                gap: 13px;
            }

            .rd-logo {
                width: 76px;
                height: 76px;
                border-width: 2px;
                outline-width: 1px;
            }

            .rd-logo .rd-logo-icon {
                font-size: 23px;
            }

            .rd-logo strong {
                font-size: 10px;
            }

            .rd-logo small {
                font-size: 5px;
            }

            .rd-title-line {
                gap: 7px;
            }

            .rd-title-line h1 {
                font-size: 21px;
            }

            .rd-open {
                font-size: 10px;
                padding: 5px 7px;
            }

            .rd-meta {
                gap: 6px;
                margin: 9px 0 11px;
                font-size: 10px;
            }

            .rd-tags {
                gap: 5px;
            }

            .rd-tag {
                font-size: 10px;
                padding: 6px 8px;
            }

            .rd-deals {
                gap: 7px;
                margin-top: 11px;
            }

            .rd-deal {
                font-size: 10px;
                padding-right: 7px;
                gap: 6px;
            }

            .rd-deal b {
                padding: 7px;
            }

            .rd-benefits {
                overflow-x: auto;
                padding-top: 16px;
            }

            .rd-benefit {
                flex-shrink: 0;
                font-size: 9px;
                padding: 0 8px;
            }

            .rd-benefit-icon {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }

            .rd-hero-actions {
                top: 5px;
                right: 8px;
            }

            .rd-tabs {
                gap: 6px;
                padding: 0 7px;
            }

            .rd-tab {
                padding: 14px 10px 12px;
                font-size: 12px;
            }

            .rd-layout {
                display: flex;
                flex-direction: column;
                padding: 10px;
                gap: 12px;
            }

            .rd-category-panel {
                width: 100%;
                padding: 10px;
            }

            .rd-category-list {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 3px;
            }

            .rd-category {
                width: auto;
                flex-shrink: 0;
                padding: 9px 11px;
                gap: 6px;
                font-size: 11px;
            }

            .rd-category .rd-count {
                display: none;
            }

            .rd-category-promo {
                display: none;
            }

            .rd-menu-area {
                width: 100%;
            }

            .rd-menu-banner {
                min-height: 75px;
                gap: 9px;
                padding: 9px;
            }

            .rd-menu-banner img {
                width: 75px;
                height: 58px;
            }

            .rd-menu-banner h2 {
                font-size: 18px;
            }

            .rd-menu-banner p {
                font-size: 10px;
            }

            .rd-menu-search {
                display: none;
            }

            .rd-dish {
                grid-template-columns: 100px minmax(0, 1fr);
                gap: 10px;
                padding: 9px;
            }

            .rd-dish-photo {
                height: 100px;
            }

            .rd-dish-name {
                font-size: 12px;
            }

            .rd-dish-description {
                font-size: 10px;
                margin: 5px 0;
            }

            .rd-dish-price {
                font-size: 12px;
            }

            .rd-dish-rating {
                font-size: 10px;
            }

            .rd-dish-actions {
                grid-column: 2;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin-top: -2px;
            }

            .rd-fav {
                font-size: 22px;
            }

            .rd-add {
                width: 105px;
                padding: 9px;
            }

            .rd-qty button {
                width: 29px;
                height: 31px;
            }

            .rd-cart-panel {
                width: 100%;
                padding: 12px;
            }

            .rd-cart-items {
                max-height: none;
            }

            .rd-extra-panel {
                padding: 18px;
            }

            .rd-photo-strip img {
                width: calc(50% - 6px);
                height: 115px;
            }
        }
    </style>



<style>
    
.rd-booking-action {
    margin-top: 14px;
}

.rd-booking-action a {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px;
    background: #ff0718;
    color: #fff !important;
    border: 1px solid #ff0718;
    border-radius: 9px;
    text-decoration: none;
    transition: 0.25s ease;
}

.rd-booking-action a:hover {
    background: #d90012;
    box-shadow: 0 5px 18px #ff071844;
}

.rd-booking-icon {
    font-size: 24px;
}

.rd-booking-action strong,
.rd-booking-action small {
    display: block;
}

.rd-booking-action strong {
    font-size: 14px;
}

.rd-booking-action small {
    margin-top: 3px;
    font-size: 11px;
    color: #fff;
}

@media (max-width: 600px) {
    .rd-booking-action a {
        padding: 9px 12px;
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
            {{ $restaurant['name'] }}
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
                        <h1>{{ $restaurant['name'] }}</h1>
                        <span class="rd-open">✓ Open Now</span>
                    </div>

                    <div class="rd-meta">
                        <span><span class="rd-rating-star">★</span>
                            {{ $restaurant['rating'] }} ({{ $restaurant['reviews'] }} reviews)</span>
                        <span class="rd-meta-divider">|</span>
                        <span>◷ {{ $restaurant['delivery_time'] }}</span>
                        <span class="rd-meta-divider">|</span>
                        <span>♧ ₹{{ $restaurant['price_for_two'] }} for two</span>
                    </div>

                    <div class="rd-tags">
                        <span class="rd-tag">North Indian</span>
                        <span class="rd-tag">Biryani</span>
                        <span class="rd-tag">Chinese</span>
                        <span class="rd-tag">Mughlai</span>
                        <span class="rd-tag">Kebabs</span>
                        <span class="rd-tag">Seafood</span>
                    </div>

                    <div class="rd-deals">
                        <div class="rd-deal">
                            <b>{{ $restaurant['offer'] }}% OFF</b>
                            <span>Use code BAKA20</span>
                        </div>
                        <div class="rd-deal">
                            <b>♜</b>
                            <span>Free Delivery<small>above ₹299</small></span>
                        </div>
                    </div>


                    <div class="rd-booking-action">
                        <a href="{{ route('restaurant.table-booking', ['slug' => 'bakasuran-signature']) }}">
                            <span class="rd-booking-icon">📅</span>
                            <span>
                                <strong>Book a Table</strong>
                                <small>Reserve your dining experience →</small>
                            </span>
                        </a>
                    </div>

                </div>
            </div>

            <div class="rd-gallery" id="rd-gallery">
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
            </div>

            <div class="rd-benefits">
                <div class="rd-benefit"><span class="rd-benefit-icon">♢</span>Hygienic &<br>Safe</div>
                <div class="rd-benefit"><span class="rd-benefit-icon">♧</span>Premium<br>Ingredients</div>
                <div class="rd-benefit"><span class="rd-benefit-icon">♙</span>Expert<br>Chefs</div>
                <div class="rd-benefit"><span class="rd-benefit-icon">♧</span>Fast & Reliable<br>Delivery</div>
                <div class="rd-benefit"><span class="rd-benefit-icon">♡</span>Great<br>Taste</div>
            </div>
        </section>

        {{-- Page tabs --}}
        <nav class="rd-tabs">
            <button type="button" class="rd-tab active" data-tab="menu">Menu</button>
            <button type="button" class="rd-tab" data-tab="about">About</button>
            <button type="button" class="rd-tab" data-tab="reviews">Reviews (2.1K+)</button>
            <button type="button" class="rd-tab" data-tab="photos">Photos</button>
            <button type="button" class="rd-tab" data-tab="offers">Offers</button>

            {{-- ADD HERE, BEFORE THE CLOSING NAV TAG --}}
    <a href="{{ route('restaurant.table-booking', ['slug' => 'bakasuran-signature']) }}"
       class="rd-tab">
        Book a Table
    </a>
        </nav>

        <div class="rd-layout" id="rd-layout">

            {{-- Categories sidebar --}}
            <aside class="rd-category-panel" id="rd-category-panel">
                <h2 class="rd-panel-title">Menu Categories</h2>

                <div class="rd-category-list">
                    @foreach ($categories as $category)
                        <button type="button" class="rd-category {{ $loop->first ? 'active' : '' }}"
                            data-category="{{ $category['name'] }}">
                            <span>{{ $category['icon'] }}</span>
                            <span>{{ $category['name'] }}</span>
                            <span class="rd-count">({{ $category['count'] }})</span>
                        </button>
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
                        <img src="https://images.unsplash.com/photo-1563379091339-03246963d96c?auto=format&fit=crop&w=350&q=80"
                            alt="Biryani">
                        <div>
                            <h2 id="rd-category-heading">Biryani</h2>
                            <p id="rd-category-description">Aromatic rice cooked with rich spices and premium ingredients.
                            </p>
                        </div>
                        <input type="search" class="rd-menu-search" id="rd-menu-search"
                            placeholder="⌕ Search within menu...">
                    </div>

                    <div class="rd-menu-list" id="rd-menu-list">
                        @foreach ($menu as $item)
                            <article class="rd-dish" data-id="{{ $item['id'] }}" data-name="{{ $item['name'] }}"
                                data-category="{{ $item['category'] }}" data-price="{{ $item['price'] }}"
                                data-image="{{ $item['image'] }}" data-rating="{{ $item['rating'] }}"
                                data-veg="{{ $item['veg'] ? '1' : '0' }}">

                                <div class="rd-dish-photo">
                                    <img src="https://images.unsplash.com/{{ $item['image'] }}?auto=format&fit=crop&w=400&q=80"
                                        alt="{{ $item['name'] }}" loading="lazy"
                                        onerror="this.onerror=null;this.src='https://placehold.co/400x300/171717/ffffff?text=Food';">
                                    @if ($item['badge'])
                                        <span class="rd-dish-badge">{{ $item['badge'] }}</span>
                                    @endif
                                </div>

                                <div class="rd-dish-info">
                                    <div class="rd-dish-name-row">
                                        <h3 class="rd-dish-name">{{ $item['name'] }}</h3>
                                        <span class="rd-veg-mark {{ $item['veg'] ? '' : 'nonveg' }}">
                                            {{ $item['veg'] ? '●' : '●' }}
                                        </span>
                                    </div>
                                    <p class="rd-dish-description">{{ $item['description'] }}</p>
                                    <div class="rd-dish-price">₹{{ $item['price'] }}</div>
                                    <div class="rd-dish-rating">
                                        <span>★</span> {{ $item['rating'] }} ({{ $item['reviews'] }})
                                    </div>
                                </div>

                                <div class="rd-dish-actions">
                                    <button type="button" class="rd-fav" aria-label="Favourite dish">♡</button>
                                    <button type="button" class="rd-add">Add</button>
                                    <div class="rd-qty">
                                        <button type="button" class="rd-minus">−</button>
                                        <span class="rd-qty-value">1</span>
                                        <button type="button" class="rd-plus">+</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="rd-no-results" id="rd-no-results">
                        No dishes found in this category.
                    </div>
                </div>

                <section class="rd-extra-panel" data-panel="about">
                    <h2>About {{ $restaurant['name'] }}</h2>
                    <p>Discover delicious Indian favourites, freshly prepared with aromatic spices and carefully selected
                        ingredients.</p>
                    <p><strong>Cuisines:</strong> North Indian, Biryani, Chinese, Mughlai and Kebabs.</p>
                    <p><strong>Estimated delivery:</strong> {{ $restaurant['delivery_time'] }}</p>
                </section>

                <section class="rd-extra-panel" data-panel="reviews">
                    <h2>Customer Reviews</h2>
                    <h3>★ {{ $restaurant['rating'] }} / 5</h3>
                    <p>Customer reviews and feedback will appear here when connected to your review data.</p>
                </section>

                <section class="rd-extra-panel" data-panel="photos">
                    <h2>Restaurant Photos</h2>
                    <div class="rd-photo-strip">
                        <img src="https://images.unsplash.com/photo-1563379091339-03246963d96c?auto=format&fit=crop&w=400&q=80"
                            alt="Biryani">
                        <img src="https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=400&q=80"
                            alt="Chicken">
                        <img src="https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=400&q=80"
                            alt="Dessert">
                    </div>
                </section>

                <section class="rd-extra-panel" data-panel="offers">
                    <h2>Available Offers</h2>
                    <p><strong>{{ $restaurant['offer'] }}% OFF</strong> — use code BAKA20 at checkout, subject to offer
                        eligibility.</p>
                    <p>Free delivery may apply to qualifying orders. Final charges are shown at checkout.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuList = document.getElementById('rd-menu-list');
            const dishCards = Array.from(menuList.querySelectorAll('.rd-dish'));
            const categoryButtons = document.querySelectorAll('.rd-category');
            const searchInput = document.getElementById('rd-menu-search');
            const cartItemsElement = document.getElementById('rd-cart-items');

            const cart = {};
            let selectedCategory = 'Biryani';
            let couponApplied = false;

            const money = value => '₹' + Math.round(value).toLocaleString('en-IN');

            function updateMenu() {
                const term = searchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                dishCards.forEach(function (card) {
                    const categoryMatches =
                        selectedCategory === 'All' ||
                        card.dataset.category === selectedCategory;

                    const searchMatches =
                        !term ||
                        card.dataset.name.toLowerCase().includes(term) ||
                        card.querySelector('.rd-dish-description').textContent
                            .toLowerCase().includes(term);

                    const visible = categoryMatches && searchMatches;
                    card.style.display = visible ? '' : 'none';

                    if (visible) visibleCount++;
                });

                document.getElementById('rd-no-results').style.display =
                    visibleCount ? 'none' : 'block';

                document.getElementById('rd-category-heading').textContent =
                    selectedCategory === 'All' ? 'All Dishes' : selectedCategory;
            }

            categoryButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    categoryButtons.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                    selectedCategory = this.dataset.category;
                    updateMenu();
                });
            });

            searchInput.addEventListener('input', updateMenu);

            function renderCart() {
                cartItemsElement.innerHTML = '';

                const items = Object.values(cart).filter(item => item.qty > 0);
                const itemCount = items.reduce((sum, item) => sum + item.qty, 0);
                const subtotal = items.reduce((sum, item) => sum + item.price * item.qty, 0);

                document.getElementById('rd-cart-count').textContent =
                    '(' + itemCount + (itemCount === 1 ? ' item' : ' items') + ')';

                if (!items.length) {
                    cartItemsElement.innerHTML = `
                    <div class="rd-cart-empty">
                        <span>🛒</span>
                        Your cart is waiting.<br>Add delicious dishes to get started!
                    </div>`;
                } else {
                    items.forEach(function (item) {
                        const row = document.createElement('div');
                        row.className = 'rd-cart-item';

                        const img = document.createElement('img');
                        img.src = 'https://images.unsplash.com/' + item.image +
                            '?auto=format&fit=crop&w=150&q=75';
                        img.alt = item.name;

                        const details = document.createElement('div');
                        const name = document.createElement('div');
                        name.className = 'rd-cart-item-name';
                        name.textContent = item.name;

                        const price = document.createElement('div');
                        price.className = 'rd-cart-item-price';
                        price.textContent = money(item.price * item.qty);

                        details.append(name, price);

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

                        minus.addEventListener('click', () => changeQty(item.id, -1));
                        plus.addEventListener('click', () => changeQty(item.id, 1));

                        controls.append(minus, qty, plus);

                        const remove = document.createElement('button');
                        remove.className = 'rd-cart-delete';
                        remove.textContent = '▤';
                        remove.setAttribute('aria-label', 'Remove item');
                        remove.addEventListener('click', () => {
                            delete cart[item.id];
                            syncDishControls();
                            renderCart();
                        });

                        row.append(img, details, controls, remove);
                        cartItemsElement.appendChild(row);
                    });
                }

                const delivery = subtotal === 0 ? 0 : (subtotal >= 299 ? 0 : 40);
                const tax = Math.round(subtotal * 0.05);
                const discount = couponApplied && subtotal >= 299
                    ? Math.round(subtotal * 0.10)
                    : 0;
                const total = Math.max(0, subtotal + delivery + tax - discount);

                document.getElementById('rd-subtotal').textContent = money(subtotal);
                document.getElementById('rd-delivery').textContent =
                    delivery === 0 && subtotal > 0 ? 'FREE' : money(delivery);
                document.getElementById('rd-tax').textContent = money(tax);
                document.getElementById('rd-discount').textContent = '−' + money(discount);
                document.getElementById('rd-discount-row').style.display =
                    discount ? 'flex' : 'none';
                document.getElementById('rd-total').textContent = money(total);
                document.getElementById('rd-checkout').disabled = itemCount === 0;

                document.getElementById('rd-saved').textContent =
                    discount ? '✓ Coupon discount applied!' :
                        subtotal >= 299 ? '✓ You qualify for free delivery!' :
                            subtotal > 0 ? 'Add ₹' + (299 - subtotal) + ' more for free delivery.' :
                                '✓ Add items to see your order total.';

                syncDishControls();
            }

            function changeQty(id, change) {
                if (!cart[id]) return;

                cart[id].qty += change;
                if (cart[id].qty <= 0) delete cart[id];

                renderCart();
            }

            function syncDishControls() {
                dishCards.forEach(function (card) {
                    const item = cart[card.dataset.id];
                    const addButton = card.querySelector('.rd-add');
                    const qtyBox = card.querySelector('.rd-qty');
                    const qtyValue = card.querySelector('.rd-qty-value');

                    if (item) {
                        addButton.style.display = 'none';
                        qtyBox.style.display = 'flex';
                        qtyValue.textContent = item.qty;
                    } else {
                        addButton.style.display = 'block';
                        qtyBox.style.display = 'none';
                    }
                });
            }

            dishCards.forEach(function (card) {
                const id = card.dataset.id;

                card.querySelector('.rd-add').addEventListener('click', function () {
                    if (!cart[id]) {
                        cart[id] = {
                            id: id,
                            name: card.dataset.name,
                            price: Number(card.dataset.price),
                            image: card.dataset.image,
                            qty: 1
                        };
                    } else {
                        cart[id].qty++;
                    }

                    renderCart();
                });

                card.querySelector('.rd-minus').addEventListener('click', () => changeQty(id, -1));
                card.querySelector('.rd-plus').addEventListener('click', () => {
                    if (cart[id]) {
                        cart[id].qty++;
                        renderCart();
                    }
                });

                card.querySelector('.rd-fav').addEventListener('click', function () {
                    const active = this.classList.toggle('active');
                    this.textContent = active ? '♥' : '♡';
                });
            });

            document.getElementById('rd-clear').addEventListener('click', function () {
                Object.keys(cart).forEach(key => delete cart[key]);
                couponApplied = false;
                renderCart();
            });

            document.getElementById('rd-coupon-toggle').addEventListener('click', function () {
                const panel = document.getElementById('rd-coupon-input');
                panel.style.display = panel.style.display === 'flex' ? 'none' : 'flex';
            });

            document.getElementById('rd-apply-coupon').addEventListener('click', function () {
                const code = document.getElementById('rd-coupon-code').value.trim().toUpperCase();

                if (code === 'BAKA20' && Object.values(cart).some(item => item.qty > 0) &&
                    Object.values(cart).reduce((sum, item) => sum + item.price * item.qty, 0) >= 299) {
                    couponApplied = true;
                    alert('Coupon applied successfully! 10% demo discount.');
                } else {
                    couponApplied = false;
                    alert('Enter BAKA20 and add at least ₹299 to your cart.');
                }

                renderCart();
            });

            document.querySelectorAll('.rd-tab').forEach(function (tab) {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('.rd-tab').forEach(item => item.classList.remove('active'));
                    this.classList.add('active');

                    const panelName = this.dataset.tab;
                    const isMenu = panelName === 'menu';

                    document.getElementById('rd-category-panel').style.display = isMenu ? '' : 'none';
                    document.getElementById('rd-cart-panel').style.display = isMenu ? '' : 'none';
                    document.querySelector('.rd-menu-content').style.display = isMenu ? '' : 'none';

                    document.querySelectorAll('.rd-extra-panel').forEach(function (panel) {
                        panel.style.display = panel.dataset.panel === panelName ? 'block' : 'none';
                    });

                    if (isMenu) {
                        document.querySelector('.rd-extra-panel[data-panel="about"]').style.display = 'none';
                        document.querySelector('.rd-extra-panel[data-panel="reviews"]').style.display = 'none';
                        document.querySelector('.rd-extra-panel[data-panel="photos"]').style.display = 'none';
                        document.querySelector('.rd-extra-panel[data-panel="offers"]').style.display = 'none';
                    }
                });
            });

            document.getElementById('restaurantFavorite').addEventListener('click', function () {
                const active = this.dataset.active === '1';
                this.dataset.active = active ? '0' : '1';
                this.textContent = active ? '♡' : '♥';
                this.style.color = active ? '#fff' : '#ff0718';
            });

            document.getElementById('rd-checkout').addEventListener('click', function () {
                // Frontend preview only. Connect this to the real cart/checkout flow next.
                alert('Your cart is ready. Next, connect this button to your Laravel cart and checkout routes.');
            });

            renderCart();
        });
    </script>

@endsection