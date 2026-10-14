@extends('layouts.website')

@section('content')

    <main class="main-bg">

        <section class="pt-120 pb-120">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8">

                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                            <div class="card-header text-center py-4 bg-dark text-white">
                                <h3 class="mb-1 text-white">Complete Payment</h3>
                                <p class="mb-0 opacity-75">Secure checkout powered by Razorpay</p>
                            </div>

                            <div class="card-body p-5">

                                <!-- Order Info -->
                                <div class="mb-4">

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Invoice ID</span>
                                        <strong>{{ $order->invoice_id }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Customer</span>
                                        <strong>{{ $user->name }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Email</span>
                                        <strong>{{ $user->email }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Amount</span>
                                        <strong class="text-success">
                                            ₹{{ number_format($grandTotal, 2) }}
                                        </strong>
                                    </div>

                                </div>

                                <hr>

                                <!-- Payment Security -->
                                <div class="text-center my-4">
                                    <img src="https://cdn.razorpay.com/logo.svg" style="height:40px;">
                                    <p class="mt-3 text-muted small">
                                        Pay securely using UPI, Cards, Net Banking or Wallets
                                    </p>
                                </div>

                                <!-- Pay Button -->
                                <button id="rzp-button" class="theme-btn style-one w-100 py-3">
                                    Pay ₹{{ number_format($grandTotal, 2) }}
                                </button>

                                <a href="{{ route('checkout') }}" class="btn btn-light border w-100 mt-3">
                                    Back To Checkout
                                </a>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </section>

    </main>




    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        var options = {

            key: "{{$razorpayKey }}",

            amount: "{{ $grandTotal * 100 }}",

            currency: "INR",

            name: "{{ config('app.name') }}",

            description: "Order Payment",

            image: "{{ asset($site->logo ?? '') }}",

            order_id: "{{ $razorpayOrder['id'] }}",

            handler: function (response) {

                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('customer.payment.success') }}";

                form.innerHTML = `@csrf<input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}"><input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}"><input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">`;

                document.body.appendChild(form);
                form.submit();
            },

            prefill: {
                name: "{{ $user->name }}",
                email: "{{ $user->email }}",
                contact: "{{ $user->phone ?? '' }}"
            },

            theme: {
                color: "#111111"
            }

        };

        var rzp1 = new Razorpay(options);

        document.getElementById('rzp-button').onclick = function (e) {
            rzp1.open();
            e.preventDefault();
        }
    </script>
@endsection