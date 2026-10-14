<?php $page = 'activity-details'; ?>
@extends('layout.mainlayout')

@section('content')

    <style>
        .notes-scroll {
            min-height: 150px;
            max-height: 350px;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* Each note block */
        .note-item {
            margin-bottom: 15px;
        }

        /* Bubble style */
        .note-bubble {
            background: #f1f1f1;
            padding: 12px 14px;
            border-radius: 6px;
            position: relative;
            font-size: 14px;
        }

        /* Bubble arrow */
        .note-bubble::after {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 20px;
            border-width: 6px;
            border-style: solid;
            border-color: #f1f1f1 transparent transparent transparent;
        }

        /* Meta (date + delete) */
        .note-meta {
            font-size: 12px;
            margin-top: 6px;
            padding-left: 5px;
        }

        /* Scrollbar */
        .notes-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .notes-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- ================= HEADER ================= --}}
            <div class="page-header d-flex align-items-center justify-content-between mb-3">
                <a href="{{ route('admin.orders.index', ['payment_status' => 'paid']) }}"
                    class="d-inline-flex align-items-center text-decoration-none">
                    <i class="ti ti-chevron-left me-2"></i> Back to Orders
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-dark">
                        Print Invoice
                    </a>
                    <a href="{{ route('admin.orders.download', $order->id) }}" class="btn btn-success">
                        <i class="ti ti-download me-1"></i>Download PDF
                    </a>

                    @if (!in_array($order->status, ['delivered', 'cancelled', 'shipped']))
                        @if (empty($order->awb))
                            <a href="{{route('admin.sales.createParcel.delhivery', ['order_id' => $order->id])}}"
                                class="btn btn-primary">
                                <i class="ti ti-circle-plus me-1"></i>Create Delhivery Parcel
                            </a>
                        @endif
                    @endif
                    @if (!in_array($order->status, ['delivered', 'cancelled', 'shipped']))
                        @if (empty($order->awb))
                            <a href="{{ route('admin.sales.createParcel.dtdc', ['order_id' => $order->id]) }}"
                                class="btn btn-info">
                                <i class="ti ti-circle-plus me-1"></i>Create DTDC Parcel
                            </a>
                        @endif
                    @endif
                    @if (!empty($order->awb) && !in_array($order->status, ['delivered', 'cancelled']))
                        <a href="{{ route('admin.sales.cancelDTDCShipment', $order->id) }}" class="btn btn-danger btn-sm">
                            Cancel DTDC Shipment
                        </a>
                    @endif

                    @if (!empty($order->awb))
                        <a href="{{ route('orders.dtdc.track', $order->id) }}" class="btn btn-info btn-sm">
                            Track Shipment
                        </a>

                        <a href="{{ route('orders.dtdc.label', $order->id) }}" target="_blank" class="btn btn-primary btn-sm">
                            Shipping DTDC Label
                        </a>
                    @endif
                </div>


            </div>

            {{-- ================= ACTIVITY HEADER CARD ================= --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex align-items-center gap-4">

                    {{-- Icon --}}
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;">
                        <i class="ti ti-activity text-warning fs-36"></i>
                    </div>

                    {{-- Activity Info --}}
                    <div class="flex-grow-1">
                        <h5 class="fw-semibold mb-1">
                            {{ $order->invoice_id ?? 'User Activity' }}
                        </h5>

                        <div class="text-muted fs-13">
                            Order Created by <strong>{{ $order->user->name ?? '_'}}</strong>
                            • {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="text-end">
                        <span class="badge bg-success px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>


                </div>
            </div>
            <div class="row g-3 mb-3">
                @php
                    $shipping = $order->shipping_address;
                @endphp
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">

                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                    style="width:42px;height:42px;">
                                    <i class="ti ti-map-pin text-light"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Shipping Address</h6>
                                <a class="ms-auto p-2" href="#edit-category" data-bs-toggle="modal"
                                    data-id="{{ $order->id }}" id="editCategoryBtn">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                            </div>


                            <p class="fw-semibold mb-1">
                                {{ $shipping['name'] ?? '-' }}
                            </p>

                            <p class="mb-1 text-muted">
                                <i class="ti ti-phone me-1"></i>
                                {{ $shipping['mobile'] ?? '-' }}
                            </p>

                            <p class="mb-2 text-muted">
                                <i class="ti ti-mail me-1"></i>
                                {{ $shipping['email'] ?? '-' }}
                            </p>

                            <hr class="my-2">

                            <p class="mb-1">
                                {{ $shipping['address'] ?? '' }},
                                {{ $shipping['address_2'] ?? '' }}
                            </p>

                            <p class="mb-1">
                                {{ $shipping['city'] ?? '' }} - {{ $shipping['pincode'] ?? '' }}
                            </p>

                            <p class="mb-1">
                                {{ $shipping['state'] ?? '' }}
                            </p>

                            @if(!empty($shipping['landmark']))
                                <p class="mb-1 text-muted">
                                    <i class="ti ti-map-2 me-1"></i>
                                    <strong>Landmark:</strong> {{ $shipping['landmark'] }}
                                </p>
                            @endif

                            @if(!empty($shipping['gst']))
                                <p class="mb-0 text-muted">
                                    <i class="ti ti-file-text me-1"></i>
                                    <strong>GST:</strong> {{ $shipping['gst'] }}
                                </p>
                            @endif

                        </div>
                    </div>
                </div>

                <!------edit------->
                <div class="modal fade" id="edit-category">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{ route('admin.orders.updateAddress') }}">
                                @csrf

                                <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">

                                <div class="modal-header">
                                    <h4>Edit Address</h4>
                                </div>

                                <div class="modal-body">

                                    <input type="text" name="name" class="form-control mb-2" placeholder="Name"
                                        value="{{ $shipping['name'] ?? '-' }}" required>

                                    <input type="text" name="mobile" class="form-control mb-2" placeholder="Mobile"
                                        value="{{ $shipping['mobile'] ?? '-' }}" required>

                                    <input type="email" name="email" class="form-control mb-2" placeholder="Email"
                                        value="{{ $shipping['email'] ?? '-' }}">
                                    <input type="text" name="gst" class="form-control mb-2" placeholder="GST"
                                        value="{{ $shipping['gst'] ?? '-' }}">

                                    <textarea name="address" class="form-control mb-2" placeholder="Address"
                                        required>{{ $shipping['address'] ?? '' }}</textarea>

                                    <input type="text" name="address_2" class="form-control mb-2" placeholder="Address 2"
                                        value="{{ $shipping['address_2'] ?? '-' }}">

                                    <input type="text" name="city" class="form-control mb-2" placeholder="City"
                                        value="{{ $shipping['city'] ?? '-' }}" required>

                                    <!-- ✅ STATE DROPDOWN -->
                                    <select name="state_id" class="form-control mb-2" id="stateSelect" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ (isset($shipping['state_id']) && $shipping['state_id'] == $state->id) ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- hidden state name -->
                                    <input type="hidden" name="state" id="state_name">

                                    <input type="text" name="pincode" class="form-control mb-2" placeholder="Pincode"
                                        value="{{ $shipping['pincode'] ?? '-' }}" required>

                                    <input type="text" name="landmark" class="form-control mb-2" placeholder="Landmark"
                                        value="{{ $shipping['landmark'] ?? '-' }}">

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn me-2 btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-success">Update Address</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ================= ORDER DETAILS ================= --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">

                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                    style="width:42px;height:42px;">
                                    <i class="ti ti-package text-success"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Order Details</h6>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Order ID</span><br>
                                <span class="fw-semibold">#{{ $order->id }}</span>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Payment Status</span><br>
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>

                            @if($order->awb)
                                <div class="mb-2">
                                    <span class="text-muted">AWB Number</span><br>
                                    <span class="fw-semibold">{{ $order->awb }}</span>
                                </div>
                            @endif

                            <div class="mb-2">
                                <span class="text-muted">Order Date</span><br>
                                <span class="fw-semibold">
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>

                            <div>
                                <span class="text-muted">Last Update</span><br>
                                <span class="fw-semibold">
                                    {{ $order->created_at->diffForHumans() }}
                                </span>
                            </div>
                            @if (!empty($order->shipment_status))
                                <div>
                                    <span class="text-bold">Shipment Status:</span><br>
                                    <span class="fw-semibold">
                                        {{ $order->shipment_status }}
                                    </span>
                                </div>
                            @endif
                            @if (!empty($order->shipment_message) && $order->shipment_status == 'Fail')
                                <div>
                                    <span class="text-bold">Shipment Message:</span><br>
                                    <span class="fw-semibold text-danger">
                                        {{ $order->shipment_message }}
                                    </span>
                                </div>
                            @else
                                <div>
                                    <span class="text-bold">Shipment Message:</span><br>
                                    <span class="fw-semibold">
                                        {{ ucwords(str_replace('_', ' ', $order->shipment_message)) }}
                                    </span>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>

            </div>

            {{-- ================= ACTIVITY SUMMARY ================= --}}
            <div class="row g-3 mb-3">

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="ti ti-user fs-24 text-primary mb-2"></i>
                            <div class="fw-semibold">{{ $order->user->name ?? ''}} ({{ $order->user->mobile ?? ''}})</div>
                            <div class="fs-12 text-muted">User Name</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="ti ti-currency-rupee fs-24 text-success mb-2"></i>
                            <div class="fw-semibold">{{ $order->grand_total ?? '0' }}</div>
                            <div class="fs-12 text-muted">Amount</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="ti ti-clock fs-24 text-danger mb-2"></i>
                            <div class="fw-semibold">{{ $order->created_at->diffForHumans() }}</div>
                            <div class="fs-12 text-muted">Time</div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- ================= ORDER ITEMS ================= --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Order Items</h5>
                    <span class="badge bg-primary">{{ $order->items->count() ?? 0 }} Items</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>SKU</th>
                                    <th>Product Code</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_title ?? '-' }}</td>
                                        <td>{{ $item->sku ?? '-' }}</td>
                                        <td>{{ $item->variant->product->hsn_code ?? '' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td>₹{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= PAYMENT DETAILS ================= --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Payment Details</h5>
                    <span class="badge bg-success">{{ ucfirst($order->payment_status) ?? 'Pending' }}</span>
                </div>
                <div class="card-body">
                    @if($order->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->payments as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucfirst($payment->method ?? '-') }}</td>
                                            <td>₹{{ number_format($payment->amount ?? 0, 2) }}</td>
                                            <td>{{ $payment->provider_payment_id ?? '-' }}</td>
                                            <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                                            <td>{{ $payment->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">No payments recorded yet.</div>
                    @endif


                    @if (!in_array($order->status, ['completed', 'cancelled', 'returned']))
                        <hr class="my-4">

                        <h6 class="mb-3">Update Order Status</h6>

                        <form action="{{ route('admin.orders.updateStatus') }}" method="POST">
                            @csrf

                            <div class="row g-2 align-items-end">
                                <input type="hidden" name="id" value="{{ $order->id }}" required>
                                {{-- Order Status --}}

                                <div class="col-md-6">
                                    <label class="form-label">Order Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Select Status</option>
                                        @if ($order->status == 'placed')
                                            <option value="placed" {{ $order->status == 'placed' ? 'selected' : '' }}>
                                                Placed</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                            </option>
                                        @endif
                                        @if ($order->status == 'shipped')
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                            </option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        @endif
                                        @if ($order->status == 'delivered')
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        @endif
                                        @if ($order->status == 'pending')
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="placed" {{ $order->status == 'placed' ? 'selected' : '' }}>
                                                Placed</option>
                                        @endif
                                        <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>
                                            Returned</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                </div>

                                {{-- Payment Status --}}
                                @if ($order->payment_status == 'pending')
                                    <div class="col-md-6">
                                        <label>Payment Status</label>
                                        <select name="payment_status" class="form-select">
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                    </div>

                                @elseif ($order->status == 'cancelled')
                                    <div class="col-md-6">
                                        <label>Payment Status</label>
                                        <select name="payment_status" class="form-select">
                                            <option value="refunded" selected>Refunded</option>
                                        </select>
                                    </div>
                                @endif




                                {{-- Button --}}
                                <div class="col-md-4">
                                    <button class="btn btn-primary w-100">
                                        Update
                                    </button>
                                </div>

                            </div>
                        </form>
                    @endif

                    <hr class="my-4">

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-12">
                            <h6 class="mb-3">Update Order</h6>
                            <form action="{{ route('admin.orders.updateOrder') }}" method="POST">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <input type="hidden" name="id" value="{{ $order->id }}" required>

                                    <div class="col-md-6">
                                        <label>Tracking</label>
                                        <input type="text" name="tracking_link" class="form-control"
                                            style="border: 1px solid #ccc;" onfocus="this.style.borderColor='skyblue'"
                                            onblur="this.style.borderColor='#ccc'" placeholder="Tracking Link">
                                    </div>




                                    {{-- Button --}}
                                    <div class="col-md-4">
                                        <button class="btn btn-primary w-100">
                                            Update
                                        </button>
                                    </div>

                                </div>
                            </form>
                            <hr class="my-4">

                            <h6 class="mb-3">Add Order Note</h6>

                            <form action="{{ route('admin.orders.addNote') }}" method="POST">
                                @csrf

                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div class="row g-2 align-items-end">

                                    <div class="col-md-6">
                                        <label class="form-label">Note Type</label>
                                        <select name="type" class="form-select">
                                            <option value="admin">Admin</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Note</label>
                                        <textarea name="note" class="form-control" rows="2" required></textarea>
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary w-100">
                                            Add Note
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12">
                            <h6 class="mb-3">Order Notes</h6>

                            @if($order->notes->count())

                                <div class="notes-scroll">

                                    @foreach($order->notes as $note)
                                        <div class="note-item">

                                            <!-- Bubble -->
                                            <span class="text-{{ $note->type == 'admin' ? 'primary' : 'success' }} mb-2"
                                                style="font-weight: 700;">
                                                {{ ucfirst($note->type) }}
                                            </span>
                                            <div class="note-bubble">
                                                {{ $note->note }}
                                            </div>

                                            <!-- Meta -->
                                            <div class="note-meta d-flex justify-content-between align-items-center mb-2">

                                                <small class="text-muted">
                                                    {{ $note->created_at->format('F j, Y \a\t h:i A') }}
                                                </small>

                                                <div class="d-flex align-items-center gap-2">

                                                    {{-- <span
                                                        class="badge bg-{{ $note->type == 'admin' ? 'primary' : 'success' }}">
                                                        {{ ucfirst($note->type) }}
                                                    </span> --}}

                                                    <form action="{{ route('admin.orders.deleteNote', $note->id) }}" method="POST"
                                                        onsubmit="return confirm('Delete this note?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm text-danger p-0 border-0 bg-transparent">
                                                            Delete
                                                        </button>
                                                    </form>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            @else
                                <p class="text-muted">No notes added yet.</p>
                            @endif
                        </div>

                    </div>

                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h3 class="mb-0">Order Status Timeline</h3>
                        </div>

                        <div class="card-body">

                            {{-- @if($order->statusHistories->count()) --}}
                            @if($order->status && in_array($order->status, ['cancelled', 'returned', 'completed']))

                                <div style="position:relative; padding-left:30px;">

                                    @foreach($order->statusHistories as $index => $history)

                                        <div style="position:relative; margin-bottom:25px;">

                                            <!-- Vertical Line -->
                                            @if(!$loop->last)
                                                <div style="
                                                                                                        position:absolute;
                                                                                                        left:-18px;
                                                                                                        top:20px;
                                                                                                        width:2px;
                                                                                                        height:100%;
                                                                                                        background:#e5e5e5;">
                                                </div>
                                            @endif

                                            <!-- Dot -->
                                            <div style="
                                                                                position:absolute;
                                                                                left:-22px;
                                                                                top:5px;
                                                                                width:12px;
                                                                                height:12px;
                                                                                border-radius:50%;

                                                                                @if($history->status == 'placed') background:#0d6efd;
                                                                                @elseif($history->status == 'shipped') background:#ffc107;
                                                                                @elseif($history->status == 'delivered') background:#198754;
                                                                                @elseif($history->status == 'cancelled') background:#dc3545;
                                                                                @else background:#6c757d;
                                                                                @endif
                                                                            "></div>

                                            <!-- Content Box -->
                                            <div style="
                                                                                padding:12px 15px;
                                                                                border:1px solid #eee;
                                                                                border-radius:8px;
                                                                                background:#fafafa;
                                                                            ">

                                                <div style="font-weight:600; margin-bottom:3px;">
                                                    {{ ucfirst($history->status) }}
                                                </div>

                                                {{-- <div style="color:#6c757d; font-size:13px; margin-bottom:4px;">
                                                    {{ $history->remark }}
                                                </div> --}}

                                                <small style="color:#999;">
                                                    {{ $history->created_at->format('d M Y, h:i A') }}
                                                </small>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else
                                <p style="color:#999;">No status history found.</p>
                            @endif

                        </div>
                    </div>


                    @if($tracking && !in_array($order->status, ['cancelled', 'returned', 'completed']))
                        <hr class="my-4">

                        <h3>Tracking Information</h3>
                        <p><strong>Status:</strong>
                            <strong class="
                                                                        @if($tracking['status'] === 'delivered') text-success
                                                                        @elseif($tracking['status'] === 'dispatched') text-secondary
                                                                        @elseif($tracking['status'] === 'pending') text-danger
                                                                        @elseif($tracking['status'] === 'in_transit') text-warning
                                                                        @elseif($tracking['status'] === 'failure') text-danger
                                                                        @elseif($tracking['status'] === 'manifested') text-primary
                                                                        @endif
                                                                    ">
                                {{ ucfirst(str_replace('_', ' ', $tracking['status'])) }}
                            </strong>

                        </p>
                        <p><strong>Last Update:</strong>
                            {{ !empty($tracking['last_update']) ? \Carbon\Carbon::parse($tracking['last_update'])->format('d M Y, h:i A') : 'NA' }}
                        </p>
                        <p><strong>Destination:</strong> {{ $tracking['destination'] ?? 'N/A' }}</p>
                        <p><strong>Expected Delivery:</strong>
                            {{ !empty($tracking['expected_delivery']) ? \Carbon\Carbon::parse($tracking['expected_delivery'])->format('d M Y, h:i A') : 'N/A' }}
                        </p>
                        <p><strong>Delivery Date:</strong>
                            {{ !empty($tracking['delivery_date']) ? \Carbon\Carbon::parse($tracking['delivery_date'])->format('d M Y, h:i A') : 'N/A' }}
                        </p>

                        <h4>Shipment Status History</h4>
                        <div style="padding-left: 16px">
                            <ul style="list-style-type: circle;">
                                @foreach($tracking['events'] as $event)
                                    <li class="mt-3 text-dark">
                                        <strong class="
                                                                                        @if(strtolower($event['status']) === 'delivered') text-success
                                                                                        @elseif(strtolower($event['status']) === 'dispatched') text-secondary
                                                                                        @elseif(strtolower($event['status']) === 'pending') text-danger
                                                                                        @elseif(strtolower($event['status']) === 'in transit') text-warning
                                                                                        @elseif(strtolower($event['status']) === 'failure') text-danger
                                                                                        @elseif(strtolower($event['status']) === 'manifested') text-primary
                                                                                        @endif
                                                                                    ">
                                            {{ $event['status'] }}
                                        </strong> -
                                        {{ !empty($event['time']) ? \Carbon\Carbon::parse($event['time'])->format('d M Y, h:i A') : 'N/A' }}
                                        <br>
                                        {{ $event['description'] ?: 'No instructions' }}<br>
                                        @if($event['location'])
                                            ({{ $event['location'] }})
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        {{-- @else
                        <p>No tracking information available.</p> --}}
                    @endif
                </div>
            </div>
        </div>




        {{-- ================= FOOTER ================= --}}
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0">2026 © {{ $site->site_name ?? ' '  }}. All Rights Reserved</p>
            <p>Designed & Developed by <span class="text-primary">{{ $site->site_name ?? ' '  }}</span></p>
        </div>

    </div>
@endsection