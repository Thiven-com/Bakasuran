
@extends('layouts.website')

@section('content')

<style>
.dine-menu-page {
    background: #faf7f2;
    min-height: 100vh;
    padding: 35px 0 60px;
}

.dine-menu-container {
    max-width: 1250px;
    margin: auto;
    padding: 0 20px;
}

.dine-menu-header {
    background: #420916;
    color: #fff;
    padding: 28px;
    border-radius: 16px;
    margin-bottom: 30px;
}

.dine-menu-header h2 {
    font-size: 27px;
    font-weight: 700;
    margin-bottom: 10px;
}

.dine-menu-header p {
    color: #ead9d5;
    margin: 5px 0;
}

.dine-menu-section {
    margin-bottom: 35px;
}

.dine-menu-section h3 {
    color: #420916;
    font-size: 21px;
    font-weight: 700;
    margin-bottom: 20px;
}

.dine-menu-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
}

.dine-menu-card {
    background: #fff;
    border: 1px solid #eee5dc;
    border-radius: 14px;
    overflow: hidden;
    transition: .25s ease;
}

.dine-menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(66, 9, 22, .10);
}

.dine-menu-card img {
    width: 100%;
    height: 210px;
    object-fit: cover;
}

.dine-menu-card-body {
    padding: 18px;
}

.dine-menu-card h4 {
    color: #420916;
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 10px;
}

.dine-menu-price {
    color: #a47732;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
}

.dine-menu-variant {
    width: 100%;
    padding: 10px;
    border: 1px solid #e5d9ce;
    border-radius: 8px;
    margin-bottom: 12px;
    background: #fff;
}

.add-dine-item {
    width: 100%;
    padding: 11px;
    background: #420916;
    color: #fff;
    border: 0;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.add-dine-item:hover {
    background: #a47732;
}

.dine-menu-empty {
    background: #fff;
    padding: 40px 20px;
    text-align: center;
    border-radius: 12px;
    color: #766b62;
}

/* CART */

.dine-cart {
    background: #fff;
    border: 1px solid #eee2d7;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 35px;
    box-shadow: 0 5px 20px rgba(66, 9, 22, .05);
}

.dine-cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid #eee5dc;
    padding-bottom: 15px;
    margin-bottom: 10px;
}

.dine-cart-header h3 {
    color: #420916;
    font-size: 21px;
    font-weight: 700;
    margin: 0;
}

#cart-count {
    color: #a47732;
    font-weight: 600;
}

.empty-cart {
    color: #887d73;
    padding: 15px 0;
    margin: 0;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f0e9e1;
}

.cart-item strong {
    color: #420916;
    display: block;
}

.cart-item small {
    color: #888;
}

.cart-item p {
    color: #a47732;
    margin: 5px 0 0;
}

.cart-controls {
    display: flex;
    align-items: center;
    gap: 9px;
}

.cart-controls button {
    border: 0;
    background: #420916;
    color: #fff;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    cursor: pointer;
}

.cart-controls .remove-cart-item {
    background: #eee5dc;
    color: #420916;
}

.cart-total {
    display: flex;
    justify-content: space-between;
    padding: 18px 0;
    color: #420916;
    font-size: 19px;
    font-weight: 700;
}

#special-instructions {
    width: 100%;
    min-height: 75px;
    padding: 12px;
    margin: 10px 0 15px;
    border: 1px solid #e5d9ce;
    border-radius: 8px;
    resize: vertical;
}

.place-order-btn {
    width: 100%;
    padding: 14px;
    border: 0;
    border-radius: 9px;
    background: #420916;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
}

.place-order-btn:hover:not(:disabled) {
    background: #a47732;
}

.place-order-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.dine-alert {
    padding: 13px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.dine-alert.success {
    background: #e8f5e9;
    color: #256029;
}

.dine-alert.error {
    background: #fdeaea;
    color: #9b2525;
}

@media(max-width: 991px) {
    .dine-menu-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 576px) {
    .dine-menu-grid {
        grid-template-columns: 1fr;
    }

    .dine-menu-header {
        padding: 22px;
    }

    .dine-menu-header h2 {
        font-size: 22px;
    }

    .dine-menu-card img {
        height: 200px;
    }

    .dine-cart {
        padding: 16px;
    }

    .cart-item {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>

<div class="dine-menu-page">
    <div class="dine-menu-container">

        {{-- SUCCESS / ERROR MESSAGES --}}

        @if(session('success'))
            <div class="dine-alert success">
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

        {{-- RESTAURANT AND BOOKING DETAILS --}}

        <div class="dine-menu-header">

            <h2>{{ $restaurant->name }}</h2>

            <p>Welcome to your Dine-In Experience</p>

            <p>
                Booking Number: {{ $booking->booking_number }}
            </p>

            <p>
                Table:
                {{ $booking->restaurantTable->table_name ?? 'N/A' }}
            </p>

            <p>
                Guests: {{ $booking->guest_count }}
            </p>

        </div>

        {{-- DINE-IN CART --}}

        <div class="dine-cart">

            <div class="dine-cart-header">
                <h3>Your Dine-In Cart</h3>
                <span id="cart-count">0 Items</span>
            </div>

            <div id="cart-items">
                <p class="empty-cart">Your cart is empty.</p>
            </div>

            <div class="cart-total">
                <span>Total</span>
                <span>₹<span id="cart-total">0.00</span></span>
            </div>

            <form
                id="dine-order-form"
                method="POST"
                action="{{ route('customer.dine-in.place-order', $booking->booking_number) }}"
            >
                @csrf

                <div id="cart-hidden-inputs"></div>

                <label for="special-instructions">
                    Special Instructions
                </label>

                <textarea
                    name="special_instructions"
                    id="special-instructions"
                    placeholder="Any special requests?"
                ></textarea>

                <button
                    type="submit"
                    class="place-order-btn"
                    id="place-order-btn"
                    disabled
                >
                    Place Order
                </button>

            </form>

        </div>

        {{-- FOOD MENU --}}

        @forelse($categories as $category)

            <div class="dine-menu-section">

                <h3>
                    {{ $category['name'] }}

                    <small style="color:#999;font-size:13px;">
                        ({{ $category['count'] }} items)
                    </small>
                </h3>

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

                            @if($image)

                                <img
                                    src="{{ filter_var($image, FILTER_VALIDATE_URL) ? $image : asset(ltrim($image, '/')) }}"
                                    alt="{{ $product->title }}"
                                >

                            @else

                                <div style="height:210px;background:#f0e9e1;display:flex;align-items:center;justify-content:center;color:#927d6c;">
                                    No Image Available
                                </div>

                            @endif

                            <div class="dine-menu-card-body">

                                <h4>{{ $product->title }}</h4>

                                @if($product->variant)

                                    <div class="dine-menu-price">
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
                                        Add to Cart
                                    </button>

                                @else

                                    <p style="color:#999;">
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
                <h4>No Food Items Available</h4>
                <p>Please check with the restaurant staff.</p>
            </div>

        @endforelse

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let cart = [];

    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');
    const hiddenInputs = document.getElementById('cart-hidden-inputs');
    const placeOrderBtn = document.getElementById('place-order-btn');

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
            cartItems.innerHTML =
                '<p class="empty-cart">Your cart is empty.</p>';
        }

        cart.forEach(function (item, index) {

            const itemTotal = item.price * item.quantity;

            total += itemTotal;
            count += item.quantity;

            cartItems.innerHTML += `
                <div class="cart-item">

                    <div>
                        <strong>${escapeHtml(item.name)}</strong>
                        <small>${escapeHtml(item.variant)}</small>
                        <p>₹${item.price.toFixed(2)} × ${item.quantity}</p>
                    </div>

                    <div class="cart-controls">

                        <button
                            type="button"
                            data-action="minus"
                            data-index="${index}"
                        >−</button>

                        <span>${item.quantity}</span>

                        <button
                            type="button"
                            data-action="plus"
                            data-index="${index}"
                        >+</button>

                        <button
                            type="button"
                            class="remove-cart-item"
                            data-action="remove"
                            data-index="${index}"
                        >×</button>

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

    // ADD FOOD TO CART

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
                    variant: this.dataset.variant || '',
                    price: Number(this.dataset.price),
                    quantity: 1
                });

            }

            renderCart();
        });

    });

    // INCREASE / DECREASE / REMOVE

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

    // FORM VALIDATION

    document.getElementById('dine-order-form')
        .addEventListener('submit', function (event) {

            if (cart.length === 0) {
                event.preventDefault();
                alert('Please add food items to your cart.');
            } else {
                placeOrderBtn.disabled = true;
                placeOrderBtn.textContent = 'Placing Order...';
            }

        });

    renderCart();

});
</script>

@endsection
