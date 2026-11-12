
@extends('layouts.website')

@section('content')

@php
    $bookingOrders = $order->booking
        ? $order->booking->foodOrders
        : collect([$order]);

    $combinedSubtotal = $bookingOrders->sum('subtotal');
    $combinedTax = $bookingOrders->sum('tax');
    $combinedGrandTotal = $bookingOrders->sum('grand_total');
@endphp

<div class="container py-5" style="margin-top: 50px;">
    <div class="card shadow mx-auto" style="max-width: 650px;">

        <div class="card-header text-center bg-dark text-white py-4">
            <h3 class="text-white">BAKASURAN</h3>
            <h5 class="text-white">Dine-In Payment</h5>
        </div>

        <div class="card-body p-4">

            <div class="d-flex justify-content-between mb-3">
                <span>Booking Orders</span>
                <strong>{{ $bookingOrders->count() }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <span>Reference Order Number</span>
                <strong>{{ $order->order_number }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <span>Payment Method</span>
                <strong>Razorpay</strong>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-3">
                <span>Subtotal</span>
                <strong>₹{{ number_format($combinedSubtotal, 2) }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <span>Tax</span>
                <strong>₹{{ number_format($combinedTax, 2) }}</strong>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-4">
                <h5>Combined Total</h5>
                <h5 class="text-success">
                    ₹{{ number_format($combinedGrandTotal, 2) }}
                </h5>
            </div>

            <button
                type="button"
                id="rzp-button"
                class="btn btn-dark w-100 py-3"
            >
                Pay ₹{{ number_format($combinedGrandTotal, 2) }} Online
            </button>

            <a
                href="{{ route('customer.dine-in-bill', $order->id) }}"
                class="btn btn-outline-secondary w-100 mt-3"
            >
                Back to Bill
            </a>

        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const options = {
        key: @json($razorpayKey),
        amount: @json($razorpayOrder['amount']),
        currency: @json($razorpayOrder['currency']),
        name: @json(config('app.name')),
        description: @json('Combined Dine-In Bill'),
        order_id: @json($razorpayOrder['id']),

        handler: function (response) {
            const form = document.createElement('form');

            form.method = 'POST';
            form.action = @json(url('/dine-in-payment/' . $order->id . '/verify'));

            const fields = {
                _token: @json(csrf_token()),
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature
            };

            Object.entries(fields).forEach(([name, value]) => {
                const input = document.createElement('input');

                input.type = 'hidden';
                input.name = name;
                input.value = value;

                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        },

        theme: {
            color: '#111111'
        }
    };

    const razorpay = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function (event) {
        event.preventDefault();
        razorpay.open();
    };
</script>

@endsection
