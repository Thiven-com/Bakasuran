<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->invoice_id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6f8;
            font-size: 13px;
            font-family: "Segoe UI", Arial, sans-serif;
            line-height: 1.4;
        }

        .invoice-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.08);
        }

        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }

        .table {
            font-size: 13px;
        }

        .table th {
            background: #f7f7f7;
            font-weight: 600;
        }

        .table td,
        .table th {
            padding: 6px;
        }

        .total-table td {
            padding: 4px 0;
        }

        h6 {
            font-size: 14px;
        }

        .footer {
            border-top: 1px dashed #ccc;
            margin-top: 25px;
            padding-top: 10px;
            font-size: 12px;
        }

        .print-button {
            text-align: right;
            margin: 20px auto;
            max-width: 800px;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        @media print {
            body {
                background: #fff;
                font-size: 12px;
            }

            .print-button {
                display: none;
            }

            .invoice-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body onload="window.print()">
    {{--
    <div class="print-button">
        <button onclick="window.print()" class="btn btn-dark">Print Invoice</button>
    </div> --}}

    <div class="invoice-container">

        <!-- HEADER -->
        <div class="invoice-header row align-items-center">
            <div class="col-5">
                @if($site->logo)
                    <img src="{{ $site->logo }}" style="height:45px; margin-bottom:5px;">
                @endif
                {{-- <h5 class="invoice-title">{{ $site->site_name ?? ' '  }}</h5> --}}
                {{-- <p class="text-muted mb-0"><strong>INVOICE</strong></p> --}}
            </div>
            <div class="col-7 text-end">
                <h5>Invoice #{{ $order->invoice_id }}</h5>
                <p class="mb-1">Date: {{ $order->created_at->format('d M Y') }}</p>
                <p class="mb-0">Status: {{ ucfirst($order->status) }}</p>
            </div>
        </div>

        <!-- CUSTOMER + ORDER INFO -->
        <div class="row mb-3">
            @php
                $shipping = $order->shipping_address;
            @endphp

            <div class="col-6">
                <h6 class="fw-bold mb-2">Shipping Address</h6>

                <p class="mb-1"><strong>Name :</strong> {{ $shipping['name'] ?? '-' }}</p>
                <p class="mb-1"><strong>Phone :</strong> {{ $shipping['mobile'] ?? '-' }}</p>
                <p class="mb-1"><strong>Email :</strong> {{ $shipping['email'] ?? '-' }}</p>

                <p class="mb-1">
                    <strong>Address :</strong>
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
                    <p class="mb-1"><strong>Landmark :</strong> {{ $shipping['landmark'] }}</p>
                @endif

                @if(!empty($shipping['gst']))
                    <p class="mb-1"><strong>GST :</strong> {{ $shipping['gst'] }}</p>
                @endif
            </div>
            <div class="col-6">
                {{-- <h6 class="fw-bold mb-2">Order Details</h6>
                <p class="mb-1"><strong>Order ID :</strong> {{ $order->id }}</p>
                <p class="mb-1"><strong>Payment Status :</strong> {{ ucfirst($order->payment_status) }}</p>
                @if($order->awb)
                <p class="mb-1"><strong>AWB Number :</strong> {{ $order->awb }}</p>
                @endif --}}
                <strong>Seller Details :</strong><br>

                <strong>{{ $site->site_name ?? ' '  ?? '-' }}</strong><br>

                @if(!empty($site->address))
                    <strong>Address :</strong> {{ $site->address }}<br>
                @endif
                @if(!empty($site->phone))
                    <strong>Phone :</strong> {{ $site->phone }}<br>
                @endif

                @if(!empty($site->email))
                    <strong>Email :</strong>{{ $site->email }}<br>
                @endif
                @if(!empty($site->gst))
                    <strong>GSTIN :</strong> {{ $site->gst }}<br>
                @endif
            </div>
        </div>

        <!-- ORDER ITEMS -->
        <h6 class="fw-bold mb-2">Order Items</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="max-width:250px;">{{ $item->product_title ?? '-' }}</td>
                        <td>{{ $item->sku ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>₹{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL -->
        {{-- <div class="row mt-3">
            <div class="col-6"></div>
            <div class="col-6">
                <table class="table total-table">
                    <tr>
                        <td><strong>Subtotal</strong></td>
                        <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr style="font-size:15px;">
                        <td><strong>Grand Total</strong></td>
                        <td class="text-end text-success fw-bold">₹{{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div> --}}

        <!-- TOTALS -->
        @php
            $taxRate = 2.5; // percent for CGST and SGST
            $totalTaxRate = $taxRate * 2; // 5%

            // CGST and SGST included in grand total
            $cgst = $order->grand_total * ($taxRate / (100 + $totalTaxRate));
            $sgst = $order->grand_total * ($taxRate / (100 + $totalTaxRate));

            // Adjusted subtotal (excluding taxes)
            $subtotalExclTax = $order->grand_total - $cgst - $sgst - $order->delivery_total + $order->discount_total;
        @endphp
        <div class="row mt-3">
            <div class="col-6"></div>
            <div class="col-6">
                <table class="table total-table">
                    <tr>
                        <td><strong>Subtotal</strong></td>
                        <td class="text-end">₹{{ number_format($subtotalExclTax, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Delivery Charge</strong></td>
                        <td class="text-end">₹{{ number_format($order->delivery_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Coupon Discount</strong></td>
                        <td class="text-end text-danger">- ₹{{ number_format($order->discount_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>CGST (2.5%) Included</strong></td>
                        <td class="text-end text-danger">₹{{ number_format($cgst, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>SGST (2.5%) Included</strong></td>
                        <td class="text-end text-danger">₹{{ number_format($sgst, 2) }}</td>
                    </tr>
                    <tr style="font-size:15px;">
                        <td><strong>Grand Total</strong></td>
                        <td class="text-end text-success"><strong>₹{{ number_format($order->grand_total, 2) }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- FOOTER -->
        <div class="footer text-center text-muted">
            <p class="mb-1">Thank you for your order.</p>
            <small>{{ $site->site_name ?? ' '  }}.</small>
        </div>

    </div>

</body>

</html>