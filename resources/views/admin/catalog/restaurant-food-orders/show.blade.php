@extends('layout.mainlayout')

@section('content')

    <div class="restaurant-food-orders-page" style="margin-top:70px;">
        <div class="container-fluid">

            <!-- PAGE HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>Restaurant Food Order Details</h4>
                    <p class="text-muted mb-0">
                        Order Number: {{ $order->order_number }}
                    </p>
                </div>

                <a href="{{ route('admin.restaurant-food-orders.index') }}" class="btn btn-outline-secondary">
                    Back to Orders
                </a>
            </div>

            <!-- SUCCESS MESSAGE -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="row">

                <!-- ORDER INFORMATION -->
                <div class="col-lg-8">

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Information</h5>
                        </div>

                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Order Number</strong>
                                    <p>{{ $order->order_number }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Order Date</strong>
                                    <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Restaurant</strong>
                                    <p>{{ $order->restaurant->name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Table Number</strong>
                                    <p>
                                        {{ $order->table->table_number ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Seating Type</strong>
                                    <p>
                                        {{ $order->table->seating_type
        ? ucfirst(str_replace('_', ' ', $order->table->seating_type))
        : 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Table Name</strong>
                                    <p>{{ $order->table->table_name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Customer ID</strong>
                                    <p>{{ $order->customer_id }}</p>
                                </div>

                                <div class="col-md-6">
                                    <strong>Order Status</strong>
                                    <p>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            @if($order->special_instructions)
                                <hr>
                                <strong>Special Instructions</strong>
                                <p class="mb-0">
                                    {{ $order->special_instructions }}
                                </p>
                            @endif

                        </div>
                    </div>

                    <!-- FOOD ITEMS -->
                    <!-- <div class="card mb-4">
                                            <div class="card-header">
                                                <h5 class="mb-0">Ordered Food Items</h5>
                                            </div>

                                            <div class="card-body table-responsive">

                                                <table class="table table-bordered">
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
                                                        @forelse($order->items as $item)
                                                            <tr>
                                                                <td>
                                                                    {{ $item->product_name }}
                                                                </td>

                                                                <td>
                                                                    {{ $item->variant_name ?? 'N/A' }}
                                                                </td>

                                                                <td>
                                                                    ₹{{ number_format($item->unit_price, 2) }}
                                                                </td>

                                                                <td>
                                                                    {{ $item->quantity }}
                                                                </td>

                                                                <td>
                                                                    ₹{{ number_format($item->total_price, 2) }}
                                                                </td>
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
                                        </div> -->



                    <!-- FOOD ITEMS -->
                    @php
                        $bookingOrders = $order->booking
                            ? $order->booking->foodOrders->sortBy('created_at')
                            : collect([$order]);
                    @endphp

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Dine-In Orders & Food Items</h5>
                            <small class="text-muted">
                                All orders from this table booking
                            </small>
                        </div>

                        <div class="card-body">

                            @forelse($bookingOrders as $bookingOrder)

                                <div class="border rounded p-3 mb-4">

                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $bookingOrder->order_number }}

                                                @if($bookingOrder->id === $order->id)
                                                    <span class="badge bg-primary">Current Order</span>
                                                @endif

                                                @if($bookingOrder->created_at && $bookingOrder->created_at->greaterThanOrEqualTo(now()->subMinutes(15)))
                                                    <span class="badge bg-success">NEW ORDER</span>
                                                @endif
                                            </h6>

                                            <small class="text-muted">
                                                Placed:
                                                {{ $bookingOrder->created_at->format('d M Y, h:i A') }}
                                            </small>
                                        </div>

                                        <div class="mt-2">
                                            <span class="badge bg-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $bookingOrder->status)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-3">
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

                            @empty
                                <p class="text-center text-muted mb-0">
                                    No orders found for this booking.
                                </p>
                            @endforelse

                        </div>
                    </div>


                </div>


                @php
                    $bookingOrders = $order->booking
                        ? $order->booking->foodOrders
                        : collect([$order]);

                    $combinedSubtotal = $bookingOrders->sum('subtotal');
                    $combinedTax = $bookingOrders->sum('tax');
                    $combinedGrandTotal = $bookingOrders->sum('grand_total');
                @endphp


                <!-- ORDER SUMMARY AND STATUS -->
                <div class="col-lg-4">

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>

                        <div class="card-body">

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



                    <!-- DINE-IN BILL GENERATION -->
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="mb-0">Dine-In Bill</h5>
                        </div>

                        <div class="card-body">

                            <p class="text-muted">
                                Generate the bill after the food has been served.
                            </p>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Bill Amount</span>

                                <strong class="text-success">
                                    ₹{{ number_format($order->grand_total, 2) }}
                                </strong>
                            </div>

                            @if($order->bill_generated_at)

                                <span class="badge bg-success mb-3">
                                    Bill Generated
                                </span>

                                <p class="text-muted">
                                    Generated at:
                                    {{ $order->bill_generated_at->format('d M Y, h:i A') }}
                                </p>

                            @elseif($order->status === 'served')

                                <form action="{{ route('admin.restaurant-food-orders.generate-bill', $order->id) }}"
                                    method="POST">

                                    @csrf

                                    <button type="submit" class="btn btn-success w-100">
                                        Generate Bill
                                    </button>

                                </form>

                            @else

                                <button class="btn btn-secondary w-100" disabled>
                                    Available After Serving
                                </button>

                            @endif


                        </div>
                    </div>

                    <!-- PAYMENT CONFIRMATION -->

                    <!-- PAYMENT CONFIRMATION -->
                    @php
                        $allOrdersPaid = $bookingOrders->every(
                            fn($item) => $item->payment_status === 'paid'
                        );

                        $allBillsGenerated = $bookingOrders->every(
                            fn($item) => $item->bill_generated_at !== null
                        );

                        $hasRazorpay = $bookingOrders->contains(
                            fn($item) => $item->payment_method === 'razorpay'
                        );
                    @endphp

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Combined Payment Confirmation</h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">
                                <span>Total Bill Amount</span>
                                <strong class="text-success">
                                    ₹{{ number_format($combinedGrandTotal, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Number of Orders</span>
                                <strong>{{ $bookingOrders->count() }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Payment Status</span>

                                @if($allOrdersPaid)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </div>

                            @if($allOrdersPaid)

                                <div class="alert alert-success mb-0">
                                    <i class="fa fa-check-circle"></i>
                                    Combined payment confirmed successfully.
                                </div>

                            @elseif(!$allBillsGenerated)

                                <div class="alert alert-warning mb-0">
                                    Generate the combined bill before confirming payment.
                                </div>

                            @elseif($hasRazorpay)

                                <div class="alert alert-info mb-0">
                                    Online payment is selected. Payment must be verified
                                    through Razorpay.
                                </div>

                            @else

                                <form action="{{ route('admin.restaurant-food-orders.confirm-payment', $order->id) }}"
                                    method="POST" onsubmit="return confirm('Have you received the full combined payment?');">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Select Payment Method
                                        </label>

                                        <select name="payment_method" class="form-control" required>
                                            <option value="">Select Cash or UPI</option>
                                            <option value="cash">Cash</option>
                                            <option value="upi">UPI</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fa fa-check-circle"></i>
                                        Confirm Combined Payment
                                    </button>
                                </form>

                                <small class="text-muted d-block mt-2">
                                    Select the method only after receiving the full payment.
                                    This confirmation will update all orders in this booking.
                                </small>

                            @endif

                        </div>
                    </div>

                    <!-- COMPLETE ALL ORDERS -->
                    @php
                        $allOrdersServed = $bookingOrders->every(
                            fn($item) => in_array($item->status, ['served', 'completed'])
                        );

                        $allOrdersPaid = $bookingOrders->every(
                            fn($item) => $item->payment_status === 'paid'
                        );

                        $allOrdersCompleted = $bookingOrders->every(
                            fn($item) => $item->status === 'completed'
                        );
                    @endphp

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Complete Table Booking Orders</h5>
                        </div>

                        <div class="card-body">

                            <p class="text-muted">
                                Complete all orders under this table booking together.
                            </p>

                            @if($allOrdersCompleted)

                                <div class="alert alert-success mb-0">
                                    All orders are already completed.
                                </div>

                            @elseif(!$allOrdersServed)

                                <button class="btn btn-secondary w-100" disabled>
                                    Waiting for All Orders to Be Served
                                </button>

                            @elseif(!$allOrdersPaid)

                                <button class="btn btn-secondary w-100" disabled>
                                    Complete Payment Before Completing Orders
                                </button>

                            @else

                                <form action="{{ route('admin.restaurant-food-orders.complete-all', $order->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to complete all orders under this table booking?');">
                                    @csrf

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fa fa-check-circle"></i>
                                        Complete All Orders
                                    </button>
                                </form>

                            @endif

                        </div>
                    </div>


                    <!-- UPDATE KITCHEN STATUS -->

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Kitchen Order Status</h5>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('admin.restaurant-food-orders.update-status', $order->id) }}"
                                method="POST">

                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">
                                        Update Status
                                    </label>

                                    <select name="status" class="form-control" required>

                                        @foreach([
                                                'pending' => 'Pending',
                                                'accepted' => 'Accepted',
                                                'preparing' => 'Preparing',
                                                'ready_to_serve' => 'Ready to Serve',
                                                'served' => 'Served',
                                                'completed' => 'Completed',
                                                'cancelled' => 'Cancelled',
                                            ] as $value => $label)

                                            <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Update Order Status
                                </button>

                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- RESPONSIVE SIDEBAR LAYOUT -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function syncRestaurantFoodOrdersLayout() {
                const sidebar = document.getElementById('sidebar');
                const page = document.querySelector('.restaurant-food-orders-page');

                if (!sidebar || !page) return;

                if (window.innerWidth <= 991) {
                    page.style.marginLeft = '0';
                    page.style.width = '100%';
                } else {
                    const sidebarWidth = sidebar.getBoundingClientRect().width;

                    page.style.marginLeft = sidebarWidth + 'px';
                    page.style.width = 'calc(100% - ' + sidebarWidth + 'px)';
                }
            }

            syncRestaurantFoodOrdersLayout();

            window.addEventListener('load', syncRestaurantFoodOrdersLayout);
            window.addEventListener('resize', syncRestaurantFoodOrdersLayout);

            document.addEventListener('click', function () {
                setTimeout(syncRestaurantFoodOrdersLayout, 100);
                setTimeout(syncRestaurantFoodOrdersLayout, 300);
            });

            const sidebar = document.getElementById('sidebar');

            if (sidebar && typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(syncRestaurantFoodOrdersLayout);
                observer.observe(sidebar);
            }

        });
    </script>

@endsection