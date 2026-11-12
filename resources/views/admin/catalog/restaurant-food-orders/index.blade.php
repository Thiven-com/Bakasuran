@extends('layout.mainlayout')

@section('content')

    <div class="restaurant-food-orders-page" style="margin-top:70px;">
        <div class="container-fluid">

            <!-- PAGE HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>Restaurant Dine-In Orders</h4>
                    <p class="text-muted mb-0">
                        Manage restaurant food orders and kitchen status.
                    </p>
                </div>
            </div>

            <!-- SUCCESS MESSAGE -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ORDERS TABLE -->
            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Restaurant</th>
                                <th>Table Name</th>
                                <th>Table Number</th>
                                <th>Seating Type</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $order->order_number }}
                                        </strong>

                                        @php
    $firstOrderId = $order->booking
        ? $order->booking->foodOrders
            ->sortBy('created_at')
            ->first()?->id
        : $order->id;
@endphp

@if($order->id === $firstOrderId)
    <span class="badge bg-success">FIRST ORDER</span>
@else
    <span class="badge bg-info">REPEAT ORDER</span>
@endif

@if($order->created_at && $order->created_at->greaterThanOrEqualTo(now()->subMinutes(15)))
    <span class="badge bg-danger">NEW ORDER</span>
@endif
                                    </td>

                                    <td>
                                        {{ $order->restaurant->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $order->table->table_name ?? 'N/A' }}
                                    </td>

                                    <td>{{ $order->table->table_number ?? 'N/A' }}</td>
                                    <td>{{ $order->table?->seating_type ?? 'N/A' }}</td>

                                    <td>
                                        {{ $order->items->sum('quantity') }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">

                                            <a href="{{ route('admin.restaurant-food-orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary">
                                                View
                                            </a>

                                            <form action="{{ route('admin.restaurant-food-orders.destroy', $order->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        No restaurant food orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
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