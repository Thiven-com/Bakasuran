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
                {{-- ORDER FILTERS --}}
<div class="card-body border-bottom">
    <form action="{{ route('admin.restaurant-food-orders.index') }}" method="GET">

        <div class="row">

            {{-- Order Number --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Order Number</label>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search order number"
                    value="{{ request('search') }}"
                >
            </div>

            {{-- Restaurant --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Restaurant</label>
                <select name="restaurant_id" class="form-select">
                    <option value="">All Restaurants</option>

                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}"
                            {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                            {{ $restaurant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Table Number --}}
            <div class="col-md-2 mb-3">
                <label class="form-label">Table Number</label>
                <input
                    type="text"
                    name="table_number"
                    class="form-control"
                    placeholder="Table number"
                    value="{{ request('table_number') }}"
                >
            </div>

            {{-- Seating Type --}}
            <div class="col-md-2 mb-3">
                <label class="form-label">Seating Type</label>
                <select name="seating_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="indoor" {{ request('seating_type') == 'indoor' ? 'selected' : '' }}>
                        Indoor
                    </option>
                    <option value="outdoor" {{ request('seating_type') == 'outdoor' ? 'selected' : '' }}>
                        Outdoor
                    </option>
                </select>
            </div>

            {{-- Status --}}
            <div class="col-md-2 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Order Date --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Order Date</label>
                <input
                    type="date"
                    name="order_date"
                    class="form-control"
                    value="{{ request('order_date') }}"
                >
            </div>

            {{-- Buttons --}}
            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="ti ti-search me-1"></i> Filter
                </button>

                <a href="{{ route('admin.restaurant-food-orders.index') }}"
                   class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </div>
    </form>
</div>
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