@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h4>SKU Report</h4>
                <h6 class="text-muted">SKU wise order analytics</h6>
            </div>
        </div>

        <div class="card mt-3">
            {{-- 🔍 FILTERS (same as orders) --}}
            <div class="card-header">
                <form method="GET" class="row g-2 align-items-end">

                    <div class="col-md-3">
                        <label>Search SKU</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search SKU"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label>From</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2">
                        <label>To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2">
                        <label>Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Select Status</option>
                            <option value="placed" {{ request('status')=='placed' ? 'selected' : '' }}>Placed</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped
                            </option>
                            <option value="shipping" {{ request('status')=='shipping' ? 'selected' : '' }}>Shipping
                            </option>
                            <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                            <option value="returned" {{ request('status')=='returned' ? 'selected' : '' }}>Returned
                            </option>
                            <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Payment</label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">Select Status</option>
                            <option value="paid" {{ request('payment_status')=='paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('payment_status')=='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="failed" {{ request('payment_status')=='failed' ? 'selected' : '' }}>Failed
                            </option>
                            <option value="refunded" {{ request('payment_status')=='refunded' ? 'selected' : '' }}>
                                Refunded
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-secondary btn-sm w-100">Search</button>


                    </div>

                </form>
            </div>

            {{-- 📋 TABLE --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SKU</th>
                                <th>Total Orders</th>
                                <th>Total Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($skus as $key => $row)
                            <tr>
                                <td>{{ $skus->firstItem() + $key }}</td>
                                <td>{{ $row->sku }} ({{ $row->product_title }})</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $row->order_count }}
                                    </span>
                                </td>
                                <td>{{ $row->total_qty }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="p-3 d-flex justify-content-between">
                    <small>
                        Showing {{ $skus->firstItem() ?? 0 }} -
                        {{ $skus->lastItem() ?? 0 }}
                        of {{ $skus->total() }}
                    </small>

                    {{ $skus->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection