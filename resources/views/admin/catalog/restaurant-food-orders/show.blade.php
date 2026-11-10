
@extends('layout.mainlayout')

@section('content')

<div class="restaurant-food-orders-page">
    <div class="container-fluid">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4>Restaurant Food Order Details</h4>
                <p class="text-muted mb-0">
                    Order Number: {{ $order->order_number }}
                </p>
            </div>

            <a href="{{ route('admin.restaurant-food-orders.index') }}"
               class="btn btn-outline-secondary">
                Back to Orders
            </a>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
                                <strong>Table</strong>
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
                <div class="card mb-4">
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
                </div>

            </div>

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
                                ₹{{ number_format($order->subtotal, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <strong>
                                ₹{{ number_format($order->tax, 2) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <h5>Grand Total</h5>
                            <h5 class="text-success">
                                ₹{{ number_format($order->grand_total, 2) }}
                            </h5>
                        </div>

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

                                <select name="status"
                                        class="form-control"
                                        required>

                                    @foreach([
                                        'pending' => 'Pending',
                                        'accepted' => 'Accepted',
                                        'preparing' => 'Preparing',
                                        'ready_to_serve' => 'Ready to Serve',
                                        'served' => 'Served',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ $order->status === $value ? 'selected' : '' }}>
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
