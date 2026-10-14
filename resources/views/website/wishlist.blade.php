@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                                                                   SUDHEERA SAREES - WISHLIST
                                                                   ========================================================= */

        .sudheera-wishlist-page {
            background: #fff;
        }


        /* =========================================================
                                                                   BREADCRUMB
                                                                   ========================================================= */

        .sudheera-wishlist-breadcrumb {
            background: #faf8f4;
            border-bottom: 1px solid #eee8df;
            padding: 10px 0;
        }

        .sudheera-wishlist-breadcrumb .breadcrumb-content {
            padding-top: 35px;
        }

        .sudheera-wishlist-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
            margin-left: 20px;
        }

        .sudheera-wishlist-breadcrumb li,
        .sudheera-wishlist-breadcrumb a {
            font-size: 13px;
            color: #777;
            text-decoration: none;
        }

        .sudheera-wishlist-breadcrumb .current {
            color: #a96b18;
            font-weight: 500;
        }


        /* =========================================================
                                                                   WISHLIST SECTION
                                                                   ========================================================= */

        .sudheera-wishlist-section {
            padding: 45px 0 70px;
        }

        .sudheera-wishlist-heading {
            text-align: center;
            margin-bottom: 40px;
        }

        .sudheera-wishlist-heading h2 {
            margin: 0 0 8px;
            font-family: "Instrument Serif", serif;
            font-size: 40px;
            font-weight: 500;
            color: #2c2118;
        }

        .sudheera-wishlist-heading p {
            margin: 0;
            color: #888;
            font-size: 14px;
        }

        .sudheera-wishlist-heading .line {
            width: 55px;
            height: 2px;
            background: #a96b18;
            margin: 15px auto 0;
        }


        /* =========================================================
                                                                   PRODUCT GRID
                                                                   ========================================================= */

        .sudheera-wishlist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px 22px;
            margin-left: 20px;
            margin-right: 20px;
        }


        /* =========================================================
                                                                   PRODUCT CARD
                                                                   ========================================================= */

        .sudheera-wishlist-card {
            position: relative;
            background: #fff;
            transition: .35s ease;
        }

        .sudheera-wishlist-card:hover {
            transform: translateY(-5px);
        }


        /* =========================================================
                                                                   PRODUCT IMAGE
                                                                   ========================================================= */

        .sudheera-wishlist-image {
            position: relative;
            width: 100%;
            height: 390px;
            overflow: hidden;
            background: #f7f3ed;
            border-radius: 6px;
        }

        .sudheera-wishlist-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: fill;
            transition: transform .5s ease;
        }

        .sudheera-wishlist-card:hover .sudheera-wishlist-image img {
            transform: scale(1.04);
        }


        /* =========================================================
                                                                   SALE BADGE
                                                                   ========================================================= */

        .sudheera-sale-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            z-index: 3;
            background: #a96b18;
            color: #fff;
            padding: 6px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .5px;
        }


        /* =========================================================
                                                                   REMOVE WISHLIST
                                                                   ========================================================= */

        .sudheera-wishlist-remove {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 4;

            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255, 255, 255, .95);
            border: 1px solid #eee4d9;
            border-radius: 50%;

            color: #a96b18;
            font-size: 17px;

            cursor: pointer;
            transition: .3s ease;
        }

        .sudheera-wishlist-remove:hover {
            background: #a96b18;
            color: #fff;
        }


        /* =========================================================
                                                                   QUICK VIEW
                                                                   ========================================================= */

        .sudheera-quick-view {
            position: absolute;
            left: 50%;
            bottom: 68px;
            transform: translate(-50%, 15px);

            width: calc(100% - 28px);
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255, 255, 255, .96);
            color: #33271e;

            border-radius: 4px;
            text-decoration: none;

            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;

            opacity: 0;
            visibility: hidden;

            transition: .35s ease;
            z-index: 3;
        }

        .sudheera-wishlist-card:hover .sudheera-quick-view {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, 0);
        }

        .sudheera-quick-view:hover {
            background: #a96b18;
            color: #fff;
        }


        /* =========================================================
                                                                   ADD TO CART
                                                                   ========================================================= */

        .sudheera-add-cart {
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 14px;

            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            background: #fff;
            color: #33271e;

            border: 1px solid #ddd2c5;
            border-radius: 4px;

            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;

            text-decoration: none;
            transition: .3s ease;

            z-index: 4;
        }

        .sudheera-add-cart:hover {
            background: #a96b18;
            border-color: #a96b18;
            color: #fff;
        }


        /* =========================================================
                                                                   PRODUCT INFO
                                                                   ========================================================= */

        .sudheera-wishlist-info {
            padding: 17px 3px 0;
        }

        .sudheera-product-name {
            display: block;
            color: #29221d;
            font-size: 15px;
            line-height: 1.5;
            text-decoration: none;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .sudheera-product-name:hover {
            color: #a96b18;
        }

        .sudheera-product-category {
            color: #999;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .sudheera-product-price {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .sudheera-price-new {
            color: #a96b18;
            font-size: 15px;
            font-weight: 600;
        }

        .sudheera-price-old {
            color: #aaa;
            font-size: 12px;
            text-decoration: line-through;
        }


        /* =========================================================
                                                                   EMPTY WISHLIST
                                                                   ========================================================= */

        .sudheera-empty-wishlist {
            text-align: center;
            padding: 75px 20px;
            border: 1px solid #eee6dc;
            border-radius: 8px;
            background: #faf8f4;
            justify-content: center;
        }

        .sudheera-empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            background: #f2e4d0;
            color: #a96b18;

            font-size: 32px;
        }

        .sudheera-empty-wishlist h3 {
            margin: 0 0 10px;
            font-family: "Instrument Serif", serif;
            font-size: 30px;
            font-weight: 500;
            color: #33271e;
        }

        .sudheera-empty-wishlist p {
            margin: 0 0 22px;
            color: #888;
            font-size: 13px;
            line-height: 1.7;
        }

        .sudheera-start-shopping {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 170px;
            height: 46px;

            background: #a96b18;
            color: #fff;

            border-radius: 4px;
            text-decoration: none;

            font-size: 12px;
            font-weight: 600;
            letter-spacing: .6px;

            transition: .3s ease;
        }

        .sudheera-start-shopping:hover {
            background: #875310;
            color: #fff;
        }


        /* =========================================================
                                                                   TABLET
                                                                   ========================================================= */

        @media (max-width: 1199px) {

            .sudheera-wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .sudheera-wishlist-image {
                height: 350px;
            }
        }


        @media (max-width: 991px) {

            .sudheera-wishlist-section {
                padding: 35px 0 55px;
            }

            .sudheera-wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 25px 18px;
            }

            .sudheera-wishlist-image {
                height: 380px;
            }
        }


        /* =========================================================
                                                                   MOBILE
                                                                   ========================================================= */

        @media (max-width: 767px) {

            .sudheera-wishlist-breadcrumb .breadcrumb-content {
                padding-top: 25px;
            }

            .sudheera-wishlist-section {
                padding: 28px 0 45px;
            }

            .sudheera-wishlist-heading {
                margin-bottom: 28px;
            }

            .sudheera-wishlist-heading h2 {
                font-size: 31px;
            }

            .sudheera-wishlist-heading p {
                font-size: 12px;
            }

            .sudheera-wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px 12px;
            }

            .sudheera-wishlist-image {
                height: 270px;
            }

            .sudheera-wishlist-remove {
                width: 32px;
                height: 32px;
                top: 9px;
                right: 9px;
                font-size: 14px;
            }

            .sudheera-sale-badge {
                top: 9px;
                left: 9px;
                padding: 5px 7px;
                font-size: 8px;
            }

            .sudheera-add-cart {
                left: 8px;
                right: 8px;
                bottom: 8px;
                height: 38px;
                font-size: 9px;
            }

            .sudheera-quick-view {
                display: none;
            }

            .sudheera-wishlist-info {
                padding-top: 12px;
            }

            .sudheera-product-name {
                font-size: 13px;
            }

            .sudheera-product-category {
                font-size: 10px;
            }

            .sudheera-price-new {
                font-size: 13px;
            }

            .sudheera-price-old {
                font-size: 10px;
            }
        }


        /* =========================================================
                                                                   SMALL MOBILE
                                                                   ========================================================= */

        @media (max-width: 480px) {

            .sudheera-wishlist-grid {
                gap: 20px 10px;
            }

            .sudheera-wishlist-image {
                height: 190px;
            }

            .sudheera-product-name {
                font-size: 12px;
            }

            .sudheera-add-cart {
                font-size: 8px;
                height: 35px;
            }

            .sudheera-empty-wishlist {
                padding: 55px 15px;
            }
        }
    </style>


    <!-- =========================================================
                                                                     BREADCRUMB
                                                                     ========================================================= -->

    <section class="sudheera-wishlist-breadcrumb">

        <div class="container">

            <div class="breadcrumb-content">

                <ul>

                    <li>
                        <a href="{{ route('home') }}">
                            Home
                        </a>
                    </li>

                    <li>/</li>

                    <li class="current">
                        Wishlist
                    </li>

                </ul>

            </div>

        </div>

    </section>


    <!-- =========================================================
                                                                     WISHLIST
                                                                     ========================================================= -->

    <section class="sudheera-wishlist-page">

        <div class="sudheera-wishlist-section">

            <div class="container">

                <!-- HEADING -->

                <div class="sudheera-wishlist-heading">

                    <h2>
                        My Wishlist
                    </h2>

                    <p>
                        Your favourite foods, saved in one place
                    </p>

                    <div class="line"></div>

                </div>


                <!-- =================================================
                                                                                 PRODUCT GRID
                                                                                 ================================================= -->

                <div id="wishlist-cart-message" role="status" aria-live="polite" style="display:none; margin:0 20px 20px; padding:14px 18px;
                                        border-radius:5px; font-size:14px;">
                </div>

                <div class="sudheera-wishlist-grid">

                    @forelse($wishlistItems as $wishlist)

                        @php
                            $variant = $wishlist->variant;
                            $product = $variant?->product;

                            $title = $product?->title ?? 'Product';

                            $category = $variant?->category?->title
                                ?? $product?->category?->title
                                ?? 'Collection';

                            $price = (float) ($variant?->price ?? 0);
                            $actualPrice = (float) ($variant?->actual_price ?? 0);

                            $discount = 0;

                            if ($actualPrice > 0 && $actualPrice > $price) {
                                $discount = round(
                                    (($actualPrice - $price) / $actualPrice) * 100
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Variant image
                            |--------------------------------------------------------------------------
                            */
                            $image = $variant?->image;

                            /*
                            |--------------------------------------------------------------------------
                            | Variant media fallback
                            |--------------------------------------------------------------------------
                            */
                            if (empty($image) && $variant?->media?->count()) {
                                $variantMedia = $variant->media->first();

                                $image = $variantMedia->file_path
                                    ?? $variantMedia->image
                                    ?? null;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Product image fallback
                            |--------------------------------------------------------------------------
                            */
                            if (empty($image) && $product) {
                                $image = $product->image ?? null;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Final image URL
                            |--------------------------------------------------------------------------
                            */
                            $imageUrl = !empty($image)
                                ? asset($image)
                                : asset('website/images/product1.webp');
                        @endphp


                        <!-- =================================================
                                                                                                                                                         PRODUCT CARD
                                                                                                                                                         ================================================= -->

                        <div class="sudheera-wishlist-card" data-wishlist-id="{{ $wishlist->id }}"
                            data-variant-id="{{ $variant?->id }}">


                            <!-- PRODUCT IMAGE -->

                            <div class="sudheera-wishlist-image">

                                <img src="{{ $imageUrl }}" alt="{{ $title }}" loading="lazy">


                                <!-- SALE BADGE -->

                                @if($discount > 0)

                                    <span class="sudheera-sale-badge">
                                        {{ $discount }}% OFF
                                    </span>

                                @endif


                                <!-- REMOVE -->

                                <button class="sudheera-wishlist-remove" type="button" data-wishlist-id="{{ $wishlist->id }}"
                                    title="Remove from wishlist">
                                    ♡
                                </button>


                                <!-- ADD TO CART -->

                                <button type="button" class="sudheera-add-cart wishlist-add-to-cart"
                                    data-variant-id="{{ $variant?->id }}" {{ !$variant ? 'disabled' : '' }}>
                                    ADD TO CART
                                </button>
                                <style>
                                    .sudheera-add-cart {
                                        width: 90%;
                                        font-family: inherit;
                                        cursor: pointer;
                                    }

                                    .sudheera-add-cart:disabled {
                                        opacity: 0.5;
                                        cursor: not-allowed;
                                    }
                                </style>

                            </div>


                            <!-- PRODUCT INFO -->

                            <div class="sudheera-wishlist-info">


                                <!-- PRODUCT NAME -->

                                <a href="{{ $product?->slug ? route('productdetails', $product->slug) : '#' }}"
                                    class="sudheera-product-name">
                                    {{ $title }}
                                </a>


                                <!-- CATEGORY -->

                                <div class="sudheera-product-category">

                                    {{ $category }}

                                </div>


                                <!-- PRICE -->

                                <div class="sudheera-product-price">


                                    <span class="sudheera-price-new">

                                        ₹{{ number_format($price, 0) }}

                                    </span>


                                    @if($actualPrice > $price)

                                        <span class="sudheera-price-old">

                                            ₹{{ number_format($actualPrice, 0) }}

                                        </span>

                                    @endif


                                </div>

                            </div>

                        </div>


                    @empty


                        <!-- EMPTY WISHLIST -->

                        <div class="sudheera-empty-wishlist">

                            <div class="sudheera-empty-icon">
                                ♡
                            </div>

                            <h3>
                                Your Wishlist is Empty
                            </h3>

                            <p>
                                Save your favourite foods here and
                                find them easily whenever you crave something delicious.
                            </p>

                            <a href="{{ route('shop') }}" class="sudheera-start-shopping">
                                EXPLORE FOOD
                            </a>

                        </div>


                    @endforelse

                </div>
                <style>
                    /* Center empty wishlist */
                    .sudheera-empty-wishlist {
                        grid-column: 1 / -1;
                        width: 100%;
                        min-height: 400px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        text-align: center;
                        padding: 40px 20px;
                        box-sizing: border-box;
                    }
                </style>

            </div>

        </div>

    </section>


    <!-- =========================================================
                                                                     REMOVE WISHLIST JAVASCRIPT
                                                                     ========================================================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const removeButtons = document.querySelectorAll(
                '.sudheera-wishlist-remove'
            );


            removeButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    const wishlistId = this.dataset.wishlistId;


                    if (!wishlistId) {

                        alert('Wishlist item not found.');

                        return;

                    }


                    fetch(
                        '{{ url('wishlist/remove') }}/' + wishlistId,
                        {
                            method: 'DELETE',

                            headers: {

                                'X-CSRF-TOKEN':
                                    '{{ csrf_token() }}',

                                'Accept':
                                    'application/json'

                            }
                        }
                    )

                        .then(async function (response) {

                            const data = await response.json();


                            /*
                            |--------------------------------------------------------------------------
                            | Not logged in
                            |--------------------------------------------------------------------------
                            */

                            if (response.status === 401) {

                                window.location.href =
                                    '{{ route('login') }}';

                                return;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Error
                            |--------------------------------------------------------------------------
                            */

                            if (!response.ok) {

                                throw new Error(
                                    data.message ||
                                    'Unable to remove wishlist item.'
                                );

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Successfully removed
                            |--------------------------------------------------------------------------
                            */

                            if (data.status) {

                                const card =
                                    button.closest(
                                        '.sudheera-wishlist-card'
                                    );


                                if (card) {

                                    card.remove();

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | If no cards remain, reload page
                                |--------------------------------------------------------------------------
                                */

                                const remainingCards =
                                    document.querySelectorAll(
                                        '.sudheera-wishlist-card'
                                    );


                                if (remainingCards.length === 0) {

                                    window.location.reload();

                                }

                            } else {

                                alert(
                                    data.message ||
                                    'Unable to remove wishlist item.'
                                );

                            }

                        })

                        .catch(function (error) {

                            console.error(error);

                            alert(
                                error.message ||
                                'Something went wrong.'
                            );

                        });

                });

            });

        });

    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messageBox = document.getElementById('wishlist-cart-message');

            function showCartMessage(message, success) {
                if (!messageBox) return;

                messageBox.textContent = message;
                messageBox.style.display = 'block';
                messageBox.style.background = success ? '#edf7ed' : '#fff0f0';
                messageBox.style.color = success ? '#236b35' : '#b42318';
                messageBox.style.border = success
                    ? '1px solid #c8e6cc'
                    : '1px solid #f2c5c5';

                messageBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }

            document.querySelectorAll('.wishlist-add-to-cart').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const variantId = this.dataset.variantId;
                    const originalText = this.innerHTML;

                    if (!variantId) {
                        showCartMessage('Product variant not found.', false);
                        return;
                    }

                    button.disabled = true;
                    button.innerHTML = 'ADDING...';

                    try {
                        const response = await fetch(@json(route('cart.add')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                product_variant_id: variantId,
                                quantity: 1
                            })
                        });

                        const data = await response.json();

                        if (response.status === 401) {
                            window.location.href = @json(route('login'));
                            return;
                        }

                        if (!response.ok || data.status === false) {
                            throw new Error(
                                data.message || 'Unable to add product to cart.'
                            );
                        }

                        button.innerHTML = '<i class="fa-solid fa-check"></i> ADDED TO CART';
                        button.style.background = '#edf7ed';
                        button.style.color = '#236b35';
                        button.style.borderColor = '#c8e6cc';
                        setTimeout(function () {
                            button.innerHTML = originalText;
                            button.style.background = '';
                            button.style.color = '';
                            button.style.borderColor = '';
                            button.disabled = false;
                        }, 2000);

                        // Update the cart badge when the API returns a count.
                        if (data.cart_count !== undefined) {
                            const cartCount = document.getElementById('cart-count');

                            if (cartCount) {
                                cartCount.textContent = data.cart_count;
                            }
                        }

                    } catch (error) {
                        console.error('Add to cart error:', error);
                        showCartMessage(
                            error.message || 'Something went wrong. Please try again.',
                            false
                        );
                    } finally {
                        setTimeout(function () {
                            button.innerHTML = originalText;
                            button.disabled = false;
                        }, 1500);
                    }
                });
            });
        });
    </script>




@endsection