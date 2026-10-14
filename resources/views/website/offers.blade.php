@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                                                           SUDHEERA SAREES - OFFERS PAGE
                                                        ========================================================= */

        .offers-page {
            background: #fbf7f1;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        /* ================= HERO ================= */

        .offers-hero {
            position: relative;
            min-height: 390px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background:
                radial-gradient(circle at 15% 20%, rgba(218, 170, 72, .22), transparent 28%),
                radial-gradient(circle at 85% 75%, rgba(255, 255, 255, .08), transparent 25%),
                linear-gradient(135deg, #35000f, #650d20 50%, #30000c);
            color: #fff;
            text-align: center;
        }

        .offers-hero::before,
        .offers-hero::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(218, 170, 72, .35);
            border-radius: 50%;
        }

        .offers-hero::before {
            width: 450px;
            height: 450px;
            left: -180px;
            top: -180px;
        }

        .offers-hero::after {
            width: 500px;
            height: 500px;
            right: -220px;
            bottom: -250px;
        }

        .offers-hero-content {
            position: relative;
            z-index: 2;
            max-width: 850px;
            padding: 50px 20px;
        }

        .offers-small-title {
            font-size: 14px;
            letter-spacing: 5px;
            color: #e5bd6a;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .offers-hero h1 {
            font-family: Georgia, serif;
            font-size: clamp(42px, 6vw, 72px);
            margin: 0 0 15px;
            font-weight: 500;
            line-height: 1.1;
        }

        .offers-hero h1 span {
            color: #e4b95f;
        }

        .offers-hero p {
            max-width: 650px;
            margin: auto;
            color: #f4e8d5;
            font-size: 17px;
            line-height: 1.8;
        }

        .hero-offer-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 28px;
            padding: 11px 26px;
            border: 1px solid #dcb45d;
            border-radius: 30px;
            color: #fff;
            font-size: 14px;
            letter-spacing: 2px;
            background: rgba(255, 255, 255, .05);
        }

        /* ================= OFFER INTRO ================= */

        .offers-container {
            max-width: 1250px;
            margin: auto;
            padding: 70px 20px 0;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-heading .mini-title {
            color: #a67a28;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .section-heading h2 {
            color: #39000e;
            font-family: Georgia, serif;
            font-size: 38px;
            margin: 0 0 12px;
            font-weight: 500;
        }

        .section-heading p {
            max-width: 650px;
            margin: auto;
            color: #777;
            line-height: 1.7;
        }

        /* ================= OFFER CARDS ================= */

        .offer-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 70px;
        }

        .offer-card {
            position: relative;
            overflow: hidden;
            min-height: 270px;
            padding: 38px 30px;
            border-radius: 18px;
            color: #fff;
            background: linear-gradient(135deg, #4b0012, #850d27);
            box-shadow: 0 15px 35px rgba(48, 0, 14, .12);
            transition: .35s ease;
        }

        .offer-card:nth-child(2) {
            background: linear-gradient(135deg, #a87924, #d4a94f);
        }

        .offer-card:nth-child(3) {
            background: linear-gradient(135deg, #26000a, #540014);
        }

        .offer-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 20px 45px rgba(48, 0, 14, .2);
        }

        .offer-card::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 50%;
            right: -70px;
            bottom: -80px;
        }

        .offer-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, .13);
            font-size: 22px;
            margin-bottom: 22px;
        }

        .offer-card h3 {
            font-family: Georgia, serif;
            font-size: 27px;
            margin-bottom: 12px;
        }

        .offer-card p {
            color: rgba(255, 255, 255, .86);
            line-height: 1.7;
            font-size: 14px;
            max-width: 270px;
        }

        .offer-code {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            border: 1px dashed rgba(255, 255, 255, .7);
            border-radius: 6px;
            font-size: 12px;
            letter-spacing: 1px;
        }

        /* ================= PRODUCTS ================= */

        .offer-products {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .offer-product {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #eee3d5;
            transition: .35s ease;
            position: relative;
        }

        .offer-product:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(50, 0, 15, .12);
        }

        .product-image {
            position: relative;
            height: 320px;
            background: #f5eee5;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
            transition: .5s ease;
        }

        .offer-product:hover .product-image img {
            transform: scale(1.05);
        }

        .discount-badge {
            position: absolute;
            left: 14px;
            top: 14px;
            z-index: 2;
            background: #8b1027;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
        }

        .product-wishlist {
            position: absolute;
            right: 14px;
            top: 14px;
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, .92);
            color: #650d20;
            cursor: pointer;
            font-size: 16px;
        }

        .product-info {
            padding: 20px;
        }

        .product-category {
            color: #a17a35;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        .product-info h3 {
            font-size: 17px;
            color: #35000f;
            margin: 8px 0 12px;
            font-weight: 600;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 17px;
        }

        .sale-price {
            color: #780d25;
            font-size: 19px;
            font-weight: 700;
        }

        .old-price {
            color: #999;
            font-size: 14px;
            text-decoration: line-through;
        }

        .shop-offer-btn {
            width: 100%;
            border: none;
            padding: 12px 15px;
            border-radius: 7px;
            background: #4b0012;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
            cursor: pointer;
            transition: .3s ease;
        }

        .shop-offer-btn:hover {
            background: #a17a35;
        }

        /* ================= COUPON BANNER ================= */

        .coupon-banner {
            margin-top: 70px;
            padding: 45px 50px;
            border-radius: 18px;
            background:
                linear-gradient(120deg, rgba(48, 0, 12, .96), rgba(99, 13, 31, .94)),
                #35000f;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            position: relative;
            overflow: hidden;
        }

        .coupon-banner::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border: 1px solid rgba(224, 183, 91, .3);
            border-radius: 50%;
            right: -150px;
            top: -180px;
        }

        .coupon-content {
            position: relative;
            z-index: 2;
        }

        .coupon-content small {
            color: #dfb962;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .coupon-content h2 {
            font-family: Georgia, serif;
            font-size: 32px;
            margin: 10px 0;
        }

        .coupon-content p {
            margin: 0;
            color: #eadfce;
        }

        .coupon-code-box {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .coupon-code {
            padding: 14px 25px;
            border: 1px dashed #dcb45d;
            color: #f3d37f;
            font-weight: 700;
            letter-spacing: 2px;
            border-radius: 6px;
            background: rgba(255, 255, 255, .05);
        }

        .coupon-btn {
            padding: 14px 25px;
            border: none;
            border-radius: 6px;
            background: #d5aa50;
            color: #35000f;
            font-weight: 700;
            cursor: pointer;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 991px) {

            .offer-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .offer-product {
                grid-column: auto;
            }

            .offer-products {
                grid-template-columns: repeat(2, 1fr);
            }

            .coupon-banner {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 600px) {

            .offers-hero {
                min-height: 350px;
            }

            .offers-hero h1 {
                font-size: 42px;
            }

            .offers-hero p {
                font-size: 14px;
            }

            .offers-container {
                padding-top: 50px;
            }

            .section-heading h2 {
                font-size: 30px;
            }

            .offer-cards {
                grid-template-columns: 1fr;
            }

            .offer-products {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-image {
                height: 240px;
            }

            .product-info {
                padding: 14px;
            }

            .product-info h3 {
                font-size: 14px;
            }

            .sale-price {
                font-size: 16px;
            }

            .old-price {
                font-size: 12px;
            }

            .shop-offer-btn {
                padding: 10px 8px;
                font-size: 11px;
            }

            .coupon-banner {
                padding: 35px 20px;
            }

            .coupon-content h2 {
                font-size: 26px;
            }

            .coupon-code-box {
                flex-direction: column;
                width: 100%;
            }

            .coupon-code,
            .coupon-btn {
                width: 100%;
                text-align: center;
            }
        }




        /* Product image container */
        .offer-product .product-image {
            position: relative;
        }

        /* Wishlist button */
        .offer-product .product-wishlist {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 5;

            display: flex;
            align-items: center;
            justify-content: center;

            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: #fff;
            color: #650019;
            font-size: 24px;
            cursor: pointer;

            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        /* Show wishlist when hovering over the card */
        .offer-product:hover .product-wishlist,
        .offer-product .product-wishlist.active {
            opacity: 1;
            visibility: visible;
        }

        /* Filled heart */
        .offer-product .product-wishlist.active {
            color: #a92d0f;
        }

        /* Keep button visible while hovering over it */
        .offer-product .product-wishlist:hover {
            transform: scale(1.08);
        }
    </style>
    <style>
        .add-cart-btn {
            width: 100%;
            height: 40px;
            border: 1px solid #650019;
            background: #650019;
            color: #fff;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            transition: .25s ease;
        }

        .add-cart-btn:hover {
            background: #fff;
            color: #650019;
        }

        @media (max-width: 767px) {
            .add-cart-btn {
                height: 38px;
            }
        }

        @media (max-width: 480px) {
            .add-cart-btn {
                height: 36px;
                font-size: 10px;
            }
        }
    </style>



<div class="offers-page">

    {{-- ================= HERO ================= --}}

    <section class="offers-hero">

        <div class="offers-hero-content">

            <div class="offers-small-title">
                BAKASURAN
            </div>

            <h1>
                Exclusive <span>Offers</span>
            </h1>

            <p>
                Discover delicious meals at irresistible prices.
                Explore our food collection, enjoy exciting savings
                and find something special for every craving.
            </p>

            <div class="hero-offer-badge">
                ✦ EXCITING DEALS ON YOUR FAVOURITE FOODS ✦
            </div>

        </div>

    </section>


    <div class="offers-container">

        {{-- ================= HEADING ================= --}}

        <div class="section-heading">

            <div class="mini-title">
                Special For You
            </div>

            <h2>
                Explore Our Best Offers
            </h2>

            <p>
                Enjoy delicious biryanis, burgers, pizzas, snacks
                and desserts with exciting offers from BAKASURAN.
            </p>

        </div>


        {{-- ================= OFFER CARDS ================= --}}

        <div class="offer-cards">

            <div class="offer-card">

                <div class="offer-icon">
                    %
                </div>

                <h3>
                    Flat 30% Off
                </h3>

                <p>
                    Enjoy up to 30% savings on selected food products
                    and discover delicious deals for your next meal.
                </p>

                <span class="offer-code">
                    USE: BAKA30
                </span>

            </div>


            <div class="offer-card">

                <div class="offer-icon">
                    ₹
                </div>

                <h3>
                    Save ₹100
                </h3>

                <p>
                    Get ₹100 off eligible orders when your
                    purchase meets the minimum order value.
                </p>

                <span class="offer-code">
                    USE: BAKA100
                </span>

            </div>


            <div class="offer-card">

                <div class="offer-icon">
                    ✦
                </div>

                <h3>
                    Foodie Specials
                </h3>

                <p>
                    Explore special prices on selected biryanis,
                    burgers, pizzas, snacks and sweet treats.
                </p>

                <span class="offer-code">
                    LIMITED OFFER
                </span>

            </div>

        </div>


        {{-- ================= PRODUCTS ================= --}}

        <div class="section-heading">

            <div class="mini-title">
                Limited Time Deals
            </div>

            <h2>
                Food Offers You’ll Love
            </h2>

        </div>


        <div class="offer-products">

            @forelse ($products as $product)

                @php

                    // PRODUCT VARIANT
                    $variant = $product->variants->first();

                    // PRODUCT IMAGE
                    $productImage = $variant?->image
                        ?: $product->primaryMedia?->url
                        ?: $product->image
                        ?: null;

                    // PRODUCT NAME
                    $productName = $product->name
                        ?? $product->title
                        ?? 'Food Product';

                    // CATEGORY
                    $categoryName = $product->category?->name
                        ?? $product->category?->title
                        ?? $variant?->category?->name
                        ?? 'Food & Beverages';

                    // PRICES
                    $salePrice = (float) ($variant?->price ?? 0);

                    $oldPrice = (float) (
                        $variant?->actual_price ?? $salePrice
                    );

                    // DISCOUNT
                    $discount = ($oldPrice > $salePrice && $oldPrice > 0)
                        ? round((($oldPrice - $salePrice) / $oldPrice) * 100)
                        : 0;

                    // IMAGE URL
                    $imageUrl = $productImage
                        ? (
                            preg_match('/^https?:\/\//i', $productImage)
                                ? $productImage
                                : asset($productImage)
                        )
                        : asset('website/images/product-placeholder.png');

                    // WISHLIST STATUS
                    $isWishlisted = $variant
                        && in_array(
                            $variant->id,
                            $wishlistVariantIds ?? []
                        );

                @endphp

                <div class="offer-product">

                    <div class="product-image">

                        {{-- DISCOUNT BADGE --}}

                        @if ($discount > 0)

                            <span class="discount-badge">
                                {{ $discount }}% OFF
                            </span>

                        @endif


                        {{-- WISHLIST --}}

                        <button
                            type="button"
                            class="wishlist product-wishlist {{ $isWishlisted ? 'active' : '' }}"
                            data-variant-id="{{ $variant?->id }}"
                            aria-label="Add to wishlist"
                        >

                            <span class="wishlist-icon">
                                {{ $isWishlisted ? '♥' : '♡' }}
                            </span>

                        </button>


                        {{-- PRODUCT IMAGE --}}

                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $productName }}"
                            loading="lazy"
                        >

                    </div>


                    {{-- PRODUCT DETAILS --}}

                    <div class="product-info">

                        <div class="product-category">
                            {{ $categoryName }}
                        </div>

                        <h3>
                            {{ $productName }}
                        </h3>


                        {{-- PRICE --}}

                        <div class="price-row">

                            <span class="sale-price">
                                ₹{{ number_format($salePrice, 0) }}
                            </span>

                            @if ($oldPrice > $salePrice)

                                <span class="old-price">
                                    ₹{{ number_format($oldPrice, 0) }}
                                </span>

                            @endif

                        </div>


                        {{-- ADD TO CART --}}

                        <button
                            type="button"
                            class="add-cart-btn"
                            data-product-id="{{ $product->id }}"
                            data-variant-id="{{ $variant?->id }}"
                        >
                            ADD TO CART
                        </button>

                    </div>

                </div>

            @empty

                <p>
                    No food offers are available right now.
                    Please check back soon.
                </p>

            @endforelse

        </div>


        {{-- ================= COUPON BANNER ================= --}}

        <div class="coupon-banner">

            <div class="coupon-content">

                <small>
                    Exclusive Online Offer
                </small>

                <h2>
                    Enjoy Extra Savings On Your First Order
                </h2>

                <p>
                    Discover delicious food and enter the coupon
                    code at checkout if it is eligible for your order.
                </p>

            </div>

            <div class="coupon-code-box">

                <div class="coupon-code">
                    WELCOME10
                </div>

                <button
                    type="button"
                    class="coupon-btn"
                    onclick="navigator.clipboard.writeText('WELCOME10')"
                >
                    COPY CODE
                </button>

            </div>

        </div>

    </div>

</div>





    <script>
        document.addEventListener('click', async function (event) {
            const button = event.target.closest('.wishlist');

            if (!button) return;

            event.preventDefault();
            event.stopPropagation();

            const variantId = button.dataset.variantId;

            if (!variantId) {
                alert('Product variant not found.');
                return;
            }

            if (button.disabled) return;

            button.disabled = true;

            try {
                const response = await fetch(@json(route('wishlist.add')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_variant_id: variantId
                    })
                });

                const data = await response.json();

                if (response.status === 401) {
                    window.location.href = @json(route('login'));
                    return;
                }

                if (!response.ok || !data.status) {
                    throw new Error(data.message || 'Unable to update wishlist.');
                }

                button.classList.add('active');

                const icon = button.querySelector('.wishlist-icon');

                if (icon) {
                    icon.textContent = '♥';
                }

                alert(data.message || 'Added to wishlist.');
            } catch (error) {
                console.error('Wishlist error:', error);
                alert(error.message || 'Something went wrong.');
            } finally {
                button.disabled = false;
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.add-cart-btn').forEach(function (button) {

                button.addEventListener('click', function () {

                    const variantId = this.dataset.variantId;
                    const currentButton = this;

                    if (!variantId) {
                        alert('Product variant not available.');
                        return;
                    }

                    const originalText = currentButton.innerHTML;

                    currentButton.disabled = true;
                    currentButton.innerHTML = 'ADDING...';

                    fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_variant_id: variantId,
                            quantity: 1
                        })
                    })
                        .then(async response => {

                            const data = await response.json();

                            if (response.status === 401) {
                                alert(data.message || 'Please login first.');

                                window.location.href = "{{ route('login') }}";
                                return;
                            }

                            if (!response.ok) {
                                throw new Error(data.message || 'Something went wrong.');
                            }

                            return data;
                        })
                        .then(data => {

                            if (!data) {
                                return;
                            }

                            if (data.status) {

                                currentButton.innerHTML = 'ADDED ✓';

                                // Update cart count if available
                                document.querySelectorAll('.cart-count').forEach(function (element) {
                                    element.textContent = data.cart_count;
                                });

                                setTimeout(function () {
                                    currentButton.innerHTML = originalText;
                                    currentButton.disabled = false;
                                }, 1500);

                            } else {

                                currentButton.innerHTML = originalText;
                                currentButton.disabled = false;

                                alert(data.message || 'Unable to add product to cart.');
                            }
                        })
                        .catch(function (error) {

                            console.error('Add to cart error:', error);

                            currentButton.innerHTML = originalText;
                            currentButton.disabled = false;

                            alert(error.message || 'Something went wrong. Please try again.');
                        });

                });

            });

        });
    </script>



@endsection