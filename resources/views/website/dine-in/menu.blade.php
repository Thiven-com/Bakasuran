
@extends('layouts.website')

@section('content')

<style>
.dine-menu-page {
    --dine-maroon: #420916;
    --dine-maroon-light: #641b27;
    --dine-gold: #bd9147;
    --dine-bg: #f8f6f2;
    --dine-text: #302822;
    --dine-muted: #898078;
    --dine-border: #eee8df;

    background: var(--dine-bg);
    min-height: 100vh;
    padding: 30px 0 65px;
    color: var(--dine-text);
}

.dine-menu-page * {
    box-sizing: border-box;
}

.dine-menu-page .dine-menu-container {
    max-width: 1350px;
    padding: 0 22px;
    margin: auto;
}

/* RESTAURANT HERO */
.dine-menu-page .dine-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(115deg, #310611, #641b27);
    border-radius: 20px;
    padding: 34px 38px;
    margin-bottom: 30px;
    color: #fff;
    box-shadow: 0 12px 30px rgba(66, 9, 22, .12);
}

.dine-menu-page .dine-hero::after {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 50%;
    right: -65px;
    top: -115px;
    box-shadow: 0 0 0 25px rgba(255,255,255,.025),
                0 0 0 55px rgba(255,255,255,.02);
}

.dine-menu-page .dine-eyebrow {
    color: #d9b779;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 3px;
    margin-bottom: 10px;
}

.dine-menu-page .dine-hero h2 {
    color: #fff;
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 8px;
}

.dine-menu-page .dine-hero-description {
    color: #e8d9d6;
    font-size: 14px;
    margin-bottom: 22px;
}

.dine-menu-page .dine-booking-info {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.dine-menu-page .dine-info-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.12);
    color: #fff;
    padding: 9px 14px;
    border-radius: 8px;
    font-size: 12px;
}

/* ALERTS */
.dine-menu-page .dine-alert {
    padding: 14px 17px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 13px;
}

.dine-menu-page .dine-alert.success {
    background: #e8f5e9;
    color: #256029;
}

.dine-menu-page .dine-alert.error {
    background: #fdeaea;
    color: #9b2525;
}

/* MAIN LAYOUT */
.dine-menu-page .dine-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 355px;
    gap: 28px;
    align-items: start;
}

.dine-menu-page .dine-menu-heading {
    margin-bottom: 24px;
}

.dine-menu-page .dine-menu-heading span {
    color: var(--dine-gold);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
}

.dine-menu-page .dine-menu-heading h3 {
    color: var(--dine-maroon);
    font-size: 27px;
    font-weight: 800;
    margin: 6px 0;
}

.dine-menu-page .dine-menu-heading p {
    color: var(--dine-muted);
    font-size: 13px;
    margin: 0;
}

/* CATEGORY */
.dine-menu-page .dine-menu-section {
    margin-bottom: 34px;
}

.dine-menu-page .dine-category-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 17px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--dine-border);
}

.dine-menu-page .dine-category-title h4 {
    color: var(--dine-maroon);
    font-size: 19px;
    font-weight: 800;
    margin: 0;
}

.dine-menu-page .dine-category-count {
    color: var(--dine-muted);
    font-size: 12px;
}

/* FOOD GRID */
.dine-menu-page .dine-menu-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.dine-menu-page .dine-menu-card {
    background: #fff;
    border: 1px solid var(--dine-border);
    border-radius: 15px;
    overflow: hidden;
    transition: .25s ease;
}

.dine-menu-page .dine-menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(66,9,22,.09);
}

.dine-menu-page .dine-food-image {
    position: relative;
    height: 190px;
    overflow: hidden;
    background: #eee8df;
}

.dine-menu-page .dine-food-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}

.dine-menu-page .dine-menu-card:hover .dine-food-image img {
    transform: scale(1.05);
}

.dine-menu-page .dine-image-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #978b7e;
    font-size: 13px;
}

.dine-menu-page .dine-food-body {
    padding: 17px;
}

.dine-menu-page .dine-food-body h5 {
    color: var(--dine-maroon);
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 8px;
    min-height: 24px;
}

.dine-menu-page .dine-food-price {
    color: var(--dine-gold);
    font-size: 19px;
    font-weight: 800;
    margin-bottom: 13px;
}

.dine-menu-page .dine-menu-variant {
    width: 100%;
    padding: 9px 11px;
    border: 1px solid var(--dine-border);
    border-radius: 7px;
    margin-bottom: 12px;
    background: #fff;
    color: var(--dine-text);
    font-size: 12px;
}

.dine-menu-page .add-dine-item {
    width: 100%;
    background: var(--dine-maroon);
    color: #fff;
    border: none;
    padding: 11px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s;
}

.dine-menu-page .add-dine-item:hover {
    background: var(--dine-gold);
}

/* CART PANEL */
.dine-menu-page .dine-cart-panel {
    position: sticky;
    top: 105px;
    background: #fff;
    border: 1px solid var(--dine-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(66,9,22,.06);
}

.dine-menu-page .dine-cart-top {
    background: var(--dine-maroon);
    color: #fff;
    padding: 20px;
}

.dine-menu-page .dine-cart-top h4 {
    font-size: 19px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 5px;
}

.dine-menu-page .dine-cart-top p {
    color: #e5cfd0;
    font-size: 12px;
    margin: 0;
}

.dine-menu-page .dine-cart-count {
    display: inline-block;
    margin-top: 12px;
    background: rgba(255,255,255,.12);
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 11px;
}

.dine-menu-page .dine-cart-body {
    padding: 18px;
}

.dine-menu-page .dine-empty-cart {
    text-align: center;
    padding: 28px 10px;
    color: var(--dine-muted);
}

.dine-menu-page .dine-empty-cart i {
    display: block;
    font-size: 34px;
    color: #d7c8b7;
    margin-bottom: 12px;
}

.dine-menu-page .dine-empty-cart p {
    font-size: 13px;
    margin: 0;
}

.dine-menu-page .dine-cart-item {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    padding: 14px 0;
    border-bottom: 1px solid var(--dine-border);
}

.dine-menu-page .dine-cart-item-info {
    min-width: 0;
}

.dine-menu-page .dine-cart-item-info strong {
    display: block;
    color: var(--dine-maroon);
    font-size: 13px;
    overflow-wrap: anywhere;
}

.dine-menu-page .dine-cart-item-info small {
    display: block;
    color: var(--dine-muted);
    font-size: 11px;
    margin-top: 3px;
}

.dine-menu-page .dine-cart-item-info p {
    color: var(--dine-gold);
    font-size: 12px;
    font-weight: 700;
    margin: 5px 0 0;
}

.dine-menu-page .dine-cart-controls {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-shrink: 0;
}

.dine-menu-page .dine-cart-controls button {
    width: 25px;
    height: 25px;
    border: 1px solid var(--dine-border);
    background: #faf7f2;
    color: var(--dine-maroon);
    border-radius: 6px;
    cursor: pointer;
}

.dine-menu-page .dine-cart-controls span {
    font-size: 12px;
    font-weight: 700;
}

.dine-menu-page .dine-cart-controls .dine-remove-cart-item {
    background: #fff0f0;
    border-color: #f4dada;
    color: #b52c2c;
}

.dine-menu-page .dine-cart-summary {
    border-top: 1px dashed #e6ddd2;
    margin-top: 12px;
    padding-top: 16px;
}

.dine-menu-page .dine-cart-total {
    display: flex;
    justify-content: space-between;
    color: var(--dine-maroon);
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 15px;
}

.dine-menu-page .dine-cart-note-label {
    display: block;
    color: var(--dine-text);
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 8px;
}

.dine-menu-page #dine-special-instructions {
    width: 100%;
    min-height: 75px;
    resize: vertical;
    padding: 11px;
    border: 1px solid var(--dine-border);
    border-radius: 8px;
    font-size: 12px;
    margin-bottom: 14px;
}

.dine-menu-page .dine-place-order-btn {
    width: 100%;
    padding: 14px;
    background: var(--dine-maroon);
    border: 0;
    border-radius: 8px;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s;
}

.dine-menu-page .dine-place-order-btn:hover:not(:disabled) {
    background: var(--dine-gold);
}

.dine-menu-page .dine-place-order-btn:disabled {
    opacity: .45;
    cursor: not-allowed;
}

.dine-menu-page .dine-menu-empty {
    background: #fff;
    border: 1px solid var(--dine-border);
    border-radius: 14px;
    padding: 45px 20px;
    text-align: center;
    color: var(--dine-muted);
}

/* RESPONSIVE */
@media(max-width: 1050px) {
    .dine-menu-page .dine-layout {
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 18px;
    }

    .dine-menu-page .dine-food-image {
        height: 165px;
    }
}

@media(max-width: 767px) {
    .dine-menu-page {
        padding-top: 20px;
    }

    .dine-menu-page .dine-layout {
        grid-template-columns: 1fr;
    }

    .dine-menu-page .dine-cart-panel {
        position: static;
        grid-row: 2;
    }

    .dine-menu-page .dine-menu-grid {
        gap: 12px;
    }

    .dine-menu-page .dine-food-image {
        height: 150px;
    }

    .dine-menu-page .dine-food-body {
        padding: 12px;
    }

    .dine-menu-page .dine-hero {
        padding: 25px 22px;
    }

    .dine-menu-page .dine-hero h2 {
        font-size: 25px;
    }
}

@media(max-width: 480px) {
    .dine-menu-page .dine-menu-container {
        padding: 0 13px;
    }

    .dine-menu-page .dine-menu-grid {
        grid-template-columns: 1fr;
    }

    .dine-menu-page .dine-food-image {
        height: 205px;
    }

    .dine-menu-page .dine-booking-info {
        gap: 8px;
    }

    .dine-menu-page .dine-info-chip {
        font-size: 11px;
    }
}
</style>

<div class="dine-menu-page">
    <div class="dine-menu-container">

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            <div class="dine-alert success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="dine-alert error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="dine-alert error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- RESTAURANT HERO --}}
        <div class="dine-hero">
            <div class="dine-eyebrow">
                BAKASURAN DINE-IN EXPERIENCE
            </div>

            <h2>{{ $restaurant->name }}</h2>

            <p class="dine-hero-description">
                Discover delicious food and enjoy your dining experience.
            </p>

            <div class="dine-booking-info">
                <span class="dine-info-chip">
                    <i class="fas fa-ticket-alt"></i>
                    {{ $booking->booking_number }}
                </span>

                <span class="dine-info-chip">
                    <i class="fas fa-chair"></i>
                    Table: {{ $booking->restaurantTable->table_name ?? 'N/A' }}
                </span>

                <span class="dine-info-chip">
                    <i class="fas fa-users"></i>
                    {{ $booking->guest_count }} Guests
                </span>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="dine-layout">

            {{-- FOOD MENU --}}
            <div class="dine-menu-content">

                <div class="dine-menu-heading">
                    <span>OUR MENU</span>
                    <h3>Explore Our Dishes</h3>
                    <p>Choose your favourites and add them to your cart.</p>
                </div>

                @forelse($categories as $category)

                    <div class="dine-menu-section">

                        <div class="dine-category-title">
                            <h4>{{ $category['name'] }}</h4>

                            <span class="dine-category-count">
                                {{ $category['count'] }} Items
                            </span>
                        </div>

                        <div class="dine-menu-grid">

                            @foreach($products->filter(function ($product) use ($category) {
                                return $product->category &&
                                    $product->category->title === $category['name'];
                            }) as $product)

                                <div class="dine-menu-card">

                                    @php
                                        $image = $product->primaryMedia?->url
                                            ?? $product->image
                                            ?? $product->media->first()?->url;
                                    @endphp

                                    <div class="dine-food-image">

                                        @if($image)
                                            <img
                                                src="{{ filter_var($image, FILTER_VALIDATE_URL) ? $image : asset(ltrim($image, '/')) }}"
                                                alt="{{ $product->title }}"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="dine-image-placeholder">
                                                <i class="fas fa-utensils"></i>
                                                Image unavailable
                                            </div>
                                        @endif

                                    </div>

                                    <div class="dine-food-body">

                                        <h5>{{ $product->title }}</h5>

                                        @if($product->variant)

                                            <div class="dine-food-price">
                                                ₹{{ number_format($product->variant->price, 2) }}
                                            </div>

                                            <select class="dine-menu-variant">
                                                <option value="{{ $product->variant->id }}">
                                                    {{ $product->variant->name ?? 'Regular' }}
                                                    - ₹{{ number_format($product->variant->price, 2) }}
                                                </option>
                                            </select>

                                            <button
                                                type="button"
                                                class="add-dine-item"
                                                data-id="{{ $product->variant->id }}"
                                                data-name="{{ $product->title }}"
                                                data-variant="{{ $product->variant->name ?? 'Regular' }}"
                                                data-price="{{ $product->variant->price }}"
                                            >
                                                <i class="fas fa-plus"></i>
                                                ADD TO CART
                                            </button>

                                        @else

                                            <p class="text-muted small">
                                                Currently unavailable
                                            </p>

                                        @endif

                                    </div>
                                </div>

                            @endforeach

                        </div>
                    </div>

                @empty

                    <div class="dine-menu-empty">
                        <i class="fas fa-utensils fa-2x mb-3"></i>
                        <h4>No Food Items Available</h4>
                        <p>Please check with the restaurant staff.</p>
                    </div>

                @endforelse

            </div>

            {{-- CART SIDEBAR --}}
            <aside class="dine-cart-panel">

                <div class="dine-cart-top">
                    <h4>
                        <i class="fas fa-shopping-basket"></i>
                        Your Order
                    </h4>

                    <p>Review your selected dishes.</p>

                    <span class="dine-cart-count" id="dine-cart-count">
                        0 Items
                    </span>
                </div>

                <div class="dine-cart-body">

                    <div id="dine-cart-items">
                        <div class="dine-empty-cart">
                            <i class="fas fa-shopping-basket"></i>
                            <p>Your cart is empty.</p>
                            <small>Add dishes from the menu.</small>
                        </div>
                    </div>

                    <div class="dine-cart-summary">

                        <div class="dine-cart-total">
                            <span>Total Amount</span>
                            <span>
                                ₹<span id="dine-cart-total">0.00</span>
                            </span>
                        </div>

                        <form
                            id="dine-order-form"
                            method="POST"
                            action="{{ route('customer.dine-in.place-order', $booking->booking_number) }}"
                        >
                            @csrf

                            <div id="dine-cart-hidden-inputs"></div>

                            <label
                                for="dine-special-instructions"
                                class="dine-cart-note-label"
                            >
                                Special Instructions
                            </label>

                            <textarea
                                name="special_instructions"
                                id="dine-special-instructions"
                                placeholder="Any special requests?"
                            ></textarea>

                            <button
                                type="submit"
                                class="dine-place-order-btn"
                                id="dine-place-order-btn"
                                disabled
                            >
                                PLACE YOUR ORDER
                                <i class="fas fa-arrow-right ms-1"></i>
                            </button>

                        </form>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let cart = [];

    const cartItems = document.getElementById('dine-cart-items');
    const cartCount = document.getElementById('dine-cart-count');
    const cartTotal = document.getElementById('dine-cart-total');
    const hiddenInputs = document.getElementById('dine-cart-hidden-inputs');
    const placeOrderBtn = document.getElementById('dine-place-order-btn');
    const orderForm = document.getElementById('dine-order-form');

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function renderCart() {

        cartItems.innerHTML = '';
        hiddenInputs.innerHTML = '';

        let total = 0;
        let count = 0;

        if (cart.length === 0) {
            cartItems.innerHTML = `
                <div class="dine-empty-cart">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Your cart is empty.</p>
                    <small>Add dishes from the menu.</small>
                </div>
            `;
        }

        cart.forEach(function (item, index) {

            const itemTotal = item.price * item.quantity;

            total += itemTotal;
            count += item.quantity;

            cartItems.innerHTML += `
                <div class="dine-cart-item">

                    <div class="dine-cart-item-info">
                        <strong>${escapeHtml(item.name)}</strong>
                        <small>${escapeHtml(item.variant)}</small>
                        <p>₹${item.price.toFixed(2)} × ${item.quantity}</p>
                    </div>

                    <div class="dine-cart-controls">

                        <button
                            type="button"
                            data-action="minus"
                            data-index="${index}"
                            aria-label="Decrease quantity"
                        >−</button>

                        <span>${item.quantity}</span>

                        <button
                            type="button"
                            data-action="plus"
                            data-index="${index}"
                            aria-label="Increase quantity"
                        >+</button>

                        <button
                            type="button"
                            class="dine-remove-cart-item"
                            data-action="remove"
                            data-index="${index}"
                            aria-label="Remove item"
                        >
                            <i class="fas fa-trash"></i>
                        </button>

                    </div>
                </div>
            `;

            hiddenInputs.innerHTML += `
                <input
                    type="hidden"
                    name="products[${index}][product_variant_id]"
                    value="${item.id}"
                >

                <input
                    type="hidden"
                    name="products[${index}][quantity]"
                    value="${item.quantity}"
                >
            `;
        });

        cartCount.textContent = count + ' Items';
        cartTotal.textContent = total.toFixed(2);

        placeOrderBtn.disabled = cart.length === 0;
    }

    // ADD FOOD
    document.querySelectorAll('.add-dine-item').forEach(function (button) {

        button.addEventListener('click', function () {

            const id = Number(this.dataset.id);

            const existing = cart.find(function (item) {
                return item.id === id;
            });

            if (existing) {
                existing.quantity++;
            } else {
                cart.push({
                    id: id,
                    name: this.dataset.name,
                    variant: this.dataset.variant || 'Regular',
                    price: Number(this.dataset.price),
                    quantity: 1
                });
            }

            renderCart();
        });

    });

    // UPDATE QUANTITY / REMOVE ITEM
    cartItems.addEventListener('click', function (event) {

        const button = event.target.closest('button[data-action]');

        if (!button) return;

        const index = Number(button.dataset.index);
        const action = button.dataset.action;

        if (!cart[index]) return;

        if (action === 'plus') {
            cart[index].quantity++;
        }

        if (action === 'minus') {
            cart[index].quantity--;

            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
        }

        if (action === 'remove') {
            cart.splice(index, 1);
        }

        renderCart();
    });

    // PLACE ORDER VALIDATION
    orderForm.addEventListener('submit', function (event) {

        if (cart.length === 0) {
            event.preventDefault();
            alert('Please add food items to your cart.');
            return;
        }

        placeOrderBtn.disabled = true;
        placeOrderBtn.textContent = 'PLACING ORDER...';
    });

    renderCart();

});
</script>

@endsection
