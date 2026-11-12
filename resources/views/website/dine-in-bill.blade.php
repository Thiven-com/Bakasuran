
@extends('layouts.website')

@section('content')

<div class="container py-5" style="margin-top:50px;">

    <div class="card shadow-sm mx-auto" style="max-width:850px;">

        <!-- BILL HEADER -->
        <div class="card-header text-center py-4">
            <h3 class="mb-2">BAKASURAN</h3>
            <h5>Dine-In Bill</h5>
            <p class="text-muted mb-0">
                Thank you for dining with us!
            </p>
        </div>

        <div class="card-body p-4">

            <!-- ORDER DETAILS -->
            <div class="row mb-4">

                <div class="col-md-6 mb-3">
                    <strong>Order Number:</strong>
                    <div>{{ $order->order_number }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Order Date:</strong>
                    <div>{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Restaurant:</strong>
                    <div>{{ $order->restaurant->name ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Table Number:</strong>
                    <div>{{ $order->table->table_number ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Customer ID:</strong>
                    <div>{{ $order->customer_id }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Bill Generated:</strong>
                    <div>{{ $order->bill_generated_at->format('d M Y, h:i A') }}</div>
                </div>

            </div>

            <hr>

           
<!-- ALL BOOKING FOOD ITEMS -->
<h5 class="mb-3">Combined Order Items</h5>

@foreach($bookingOrders as $bookingOrder)

    <div class="border rounded p-3 mb-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <strong>{{ $bookingOrder->order_number }}</strong>
                <br>
                <small class="text-muted">
                    {{ $bookingOrder->created_at->format('d M Y, h:i A') }}
                </small>
            </div>

            <span class="badge bg-secondary">
                {{ ucfirst(str_replace('_', ' ', $bookingOrder->status)) }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered mb-2">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookingOrder->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->variant_name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No food items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <strong>
                Order Total:
                ₹{{ number_format($bookingOrder->grand_total, 2) }}
            </strong>
        </div>

    </div>

@endforeach

            <!-- BILL SUMMARY -->
            <div class="row justify-content-end">

                <div class="col-md-6">

                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <strong>
                            ₹{{ number_format($combinedSubtotal, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Tax</span>
                        <strong>
                            ₹{{ number_format($combinedTax, 2) }}
                        </strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <h5>Grand Total</h5>

                        <h5 class="text-success">
                            ₹{{ number_format($combinedGrandTotal, 2) }}
                        </h5>
                    </div>

                </div>

            </div>

            <hr>

            <!-- PAYMENT STATUS -->
            <div class="d-flex justify-content-between align-items-center">

                <strong>Payment Status</strong>

                @if($isPaid)    
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-warning text-dark">
                        Payment Pending
                    </span>
                @endif

            </div>



<!-- PAYMENT METHOD SELECTION -->

<!-- ONLINE PAYMENT -->
@if(!$isPaid)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Online Payment</h5>
        </div>

        <div class="card-body">
            <p class="text-muted">
                Pay your combined dine-in bill securely online.
            </p>

            <form action="{{ route('customer.dine-in-payment-method', $order->id) }}"
                  method="POST">
                @csrf

                <input type="hidden"
                       name="payment_method"
                       value="razorpay">

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa fa-credit-card"></i>
                    Pay ₹{{ number_format($combinedGrandTotal, 2) }} Online
                </button>
            </form>

            <p class="text-muted small mt-3 mb-0">
                For Cash or UPI at the restaurant, please inform the admin.
                The admin will confirm your payment.
            </p>
        </div>
    </div>
@endif



            <!-- ACTIONS -->
            <div class="text-center mt-4">

                <button onclick="window.print()"
                    class="btn btn-dark">
                    <i class="fa fa-print"></i> Print Bill
                </button>

                <a href="{{ route('customer.dine-in-order-history') }}"
                    class="btn btn-outline-secondary">
                    Back to Order History
                </a>

            </div>

        </div>

        <div class="card-footer text-center text-muted">
            Thank you for choosing BAKASURAN!
        </div>

    </div>

</div>

<style>
    @media print {
        header,
        footer,
        .btn {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

@endsection
