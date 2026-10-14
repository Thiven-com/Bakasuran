{{-- resources/views/admin/orders/orders.blade.php --}}
@extends('layout.mainlayout')

@section('content')
    <style>
        .orders:hover {
            background: rgba(254, 159, 67, 0.08) !important;
            cursor: pointer;
        }

        .table tbody tr td {
            background: transparent;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <div>
                    <h4 class="mb-1">Pre Orders</h4>
                    <h6 class="text-muted">Manage Your Pre Orders</h6>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <form action="" method="GET" class="row g-2 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search invoice / customer / id" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                <option value="">Select Status</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed
                                </option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                    Refunded
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Select Status</option>
                                <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped
                                </option>
                                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Shipping
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-secondary btn-sm w-100">
                                Search
                            </button>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <a href="{{ route('admin.preorders', ['payment_status' => 'paid']) }}"
                                class="btn btn-light btn-sm w-100">
                                Clear
                            </a>

                            <button id="createParcelBtn" onclick="disableBtn()" type="button"
                                class="btn btn-primary btn-sm w-100">
                                Create Parcel
                            </button>
                        </div>
                        <div class="col-md-3 mt-3" style="float: inline-end;">
                            <button id="refreshAWB" onclick="disableBtn1()" type="button"
                                class="btn btn-warning btn-sm w-100">
                                Refresh AWB Status
                            </button>
                        </div>

                    </form>
                </div>

                <div class="row g-4">
                    <!-- Orders -->
                    <span class="badge text-muted" style="font-size: 14px;color: black !important;font-weight:700">Order
                        Grand Total
                        (₹{{ number_format($data['order_amount'] ?? 0) }})</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                        <span id="selected-count" class="ms-2"></span>
                                    </th>
                                    <th class="no-sort">
                                        {{-- S.No --}}
                                        Id
                                    </th>
                                    <th>Customer</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Payment Status</th>
                                    <th>Shipment</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $customer = $order->user ?? null;
                                        $avatar = $customer?->avatar ?? $customer?->profile_pic ?? null;
                                    @endphp
                                    <tr class="orders">
                                        <td>
                                            <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        </td>
                                        <td>
                                            {{-- {{$loop->iteration}} --}}
                                            {{ $order->id }}
                                        </td>
                                        <td style="max-width: 400px !important;text-wrap:auto">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="javascript:void(0)"
                                                        class="fw-medium">
                                                        {{ $customer?->name ?? ($order->shipping_address['name'] ?? 'Guest / Unknown') }}
                                                    </a>
                                                    <div class="text-muted small">
                                                        {{ $customer?->email ?? ($order->shipping_address['email'] ?? '-') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>{{ $order->invoice_id ?? 'SL' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ optional($order->created_at)->format('d M Y') }}<br>
                                            {{ optional($order->created_at)->format('h:i A') }}</td>
                                        <td>
                                            @if(strtolower($order->status ?? '') === 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif(strtolower($order->status ?? '') === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif(strtolower($order->status ?? '') === 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($order->status ?? 'N/A') }}</span>
                                            @endif
                                        </td>

                                        <td>{{ $order->currency_symbol ?? '₹' }}{{ number_format((float) ($order->grand_total ?? 0), 2) }}
                                        </td>
                                        <td>
                                            @php $ps = strtolower($order->payment_status ?? ''); @endphp
                                            @if($ps === 'paid')
                                                <span class="badge badge-soft-success shadow-none badge-xs"><i
                                                        class="ti ti-point-filled me-1"></i>Paid</span>
                                            @elseif($ps === 'partial')
                                                <span class="badge badge-soft-warning shadow-none badge-xs">Partial</span>
                                            @elseif($ps === 'unpaid')
                                                <span class="badge badge-soft-danger shadow-none badge-xs">Unpaid</span>
                                            @else
                                                <span
                                                    class="badge badge-soft-secondary shadow-none badge-xs">{{ ucfirst($order->payment_status ?? 'N/A') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(strtolower($order->status ?? '') === 'completed')
                                                <span class="badge bg-success me-1">Delivered</span>
                                            @elseif($order->shipment_status == 'Fail')
                                                <span class="badge bg-primary me-1">Shipment Failed</span>
                                            @else
                                                @php
                                                    $awb = $order->awb ?? null;
                                                    $carrier = $order->carrier ?? null;
                                                @endphp

                                                @if($carrier || $awb)
                                                    @if($carrier)
                                                        <span class="badge bg-primary me-1">Carrier: {{ $carrier }}</span> <br>
                                                    @endif
                                                    @if($awb)
                                                        <span class="badge bg-secondary">AWB: {{ $awb }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="dropdown-item"><i
                                                    data-feather="eye" class="me-1"></i> View</a>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- <tr>
                                    </tr> --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Showing {{ $orders->firstItem() ?? 0 }} -
                                {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</small>
                        </div>
                        <div>
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0">{{ now()->year }} &copy; {{ config('app.name', 'ThiVen') }}. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="#" class="text-primary">ThiVen</a></p>
        </div>
    </div>

    <script>
        document.getElementById('createParcelBtn').addEventListener('click', function () {
            let selectedOrders = [];

            document.querySelectorAll('.order-checkbox:checked').forEach(function (checkbox) {
                selectedOrders.push(checkbox.value);
            });

            if (selectedOrders.length === 0) {
                alert('Please select at least one order.');
                return;
            }

            // Send to backend
            fetch("{{ route('admin.orders.createBulkParcel') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_ids: selectedOrders
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || "Shipment created successfully!");
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert("Something went wrong!");
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectAllCheckbox = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.order-checkbox');
            const selectedCountEl = document.getElementById('selected-count');

            function updateSelectedCount() {
                const selectedCount = document.querySelectorAll('.order-checkbox:checked').length;
                selectedCountEl.textContent = `${selectedCount} selected`;
            }

            // Individual checkboxes
            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    updateSelectedCount();

                    // Update "select all" checkbox state
                    selectAllCheckbox.checked = document.querySelectorAll('.order-checkbox:checked').length === checkboxes.length;
                });
            });

            // "Select All" checkbox
            selectAllCheckbox.addEventListener('change', () => {
                const isChecked = selectAllCheckbox.checked;
                checkboxes.forEach(cb => cb.checked = isChecked);
                updateSelectedCount();
            });
        });
    </script>
    <script>
        function disableBtn() {
            // Get the button element
            const btn = document.getElementById("createParcelBtn");
            // Disable it
            btn.disabled = true;
            // Optional: change the text
            btn.innerText = "Processing...";
        }
        function disableBtn1() {
            // Get the button element
            const btn = document.getElementById("refreshAWB");
            // Disable it
            btn.disabled = true;
            // Optional: change the text
            btn.innerText = "Processing...";
        }
    </script>
    <script>
        document.getElementById('refreshAWB').addEventListener('click', function () {
            let selectedOrders = [];

            document.querySelectorAll('.order-checkbox:checked').forEach(function (checkbox) {
                selectedOrders.push(checkbox.value);
            });

            if (selectedOrders.length === 0) {
                alert('Please select at least one order.');
                const btn = document.getElementById("refreshAWB");
                // Disable it
                btn.disabled = false;
                btn.innerText = "Refresh AWB Status";

                return;
            }

            if (selectedOrders.length > 50) {
                alert('You can select a maximum of 50 orders at a time.');
                const btn = document.getElementById("refreshAWB");
                // Disable it
                btn.disabled = false;
                btn.innerText = "Refresh AWB Status";

                return;
            }

            // Send to backend
            fetch("{{ route('admin.orders.refresh-awb-status') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_ids: selectedOrders
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || "successfully!");
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert("Something went wrong!");
                    const btn = document.getElementById("refreshAWB");
                    // Disable it
                    btn.disabled = false;
                    btn.innerText = "Refresh AWB Status";

                });
        });
    </script>
@endsection