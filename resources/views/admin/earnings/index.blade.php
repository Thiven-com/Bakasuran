@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="container-fluid mt-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Order Wise Delivery Earnings</h4>
            </div>

            {{-- Total Earnings --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-muted">Total Delivery Partner Earnings</h6>
                    <h3 class="text-success">
                        ₹{{ number_format($totalDeliveryEarnings, 2) }}
                    </h3>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filter Orders</h5>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.earnings.index') }}">
                        <div class="row align-items-end">

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Order ID</label>
                                <input type="text" name="order_id" class="form-control" placeholder="Enter Order ID"
                                    value="{{ request('order_id') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile Number"
                                    value="{{ request('mobile') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Order Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                        Delivered
                                    </option>
                                    <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>
                                        Picked Up
                                    </option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                                        Accepted
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i> Search
                                </button>

                                <a href="{{ route('admin.earnings.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Orders Table --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Wise Earnings List</h5>
                </div>

                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Order ID</th>
                                <th>Delivery Partner</th>
                                <th>Mobile</th>
                                <th>Order Status</th>
                                <th>Delivery Date</th>
                                <th>Partner Earning</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $key => $order)
                                                <tr>
                                                    <td>{{ $orders->firstItem() + $key }}</td>

                                                    <td>
                                                        {{ $order->invoice_id ?? $order->order_id ?? $order->id }}
                                                    </td>

                                                    <td>
                                                        {{ $order->deliveryPartner->name ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $order->deliveryPartner->mobile ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-success">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{ $order->delivered_at
                                ? \Carbon\Carbon::parse($order->delivered_at)->format('d-m-Y h:i A')
                                : 'N/A' }}
                                                    </td>

                                                    <td class="text-success fw-bold">
                                                        ₹{{ number_format($order->delivery_earning ?? 0, 2) }}
                                                    </td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection