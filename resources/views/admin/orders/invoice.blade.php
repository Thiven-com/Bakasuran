<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->invoice_id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 13px;
            line-height: 1.4;
            color: #333;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
        }

        .invoice-header table {
            width: 100%;
        }

        .invoice-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .invoice-header p {
            margin: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 12px;
        }

        .table th {
            background: #f7f7f7;
            font-weight: bold;
        }

        .total-table {
            width: 100%;
            margin-top: 10px;
        }

        .total-table td {
            padding: 4px 6px;
        }

        .text-end {
            text-align: right;
        }

        .text-success {
            color: green;
        }

        .footer {
            border-top: 1px dashed #ccc;
            margin-top: 20px;
            padding-top: 10px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="invoice-container">

        <!-- HEADER -->
        <div class="invoice-header">
            <table>
                <tr>
                    <td>
                        @if(!empty($site->logo))
                            <img src="{{ $site->logo }}" style="height:50px;">
                        @endif
                    </td>
                    <td class="text-end">
                        <h5>Invoice #{{ $order->invoice_id }}</h5>
                        <p>Date: {{ $order->created_at->format('d M Y') }}</p>
                        <p>Status: {{ ucfirst($order->status) }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- CUSTOMER + ORDER INFO -->
        <table style="width:100%; margin-top:15px; border-collapse: collapse;">
            <tr>
                <!-- Left Column -->
                {{-- <td style="width:50%; vertical-align: top; padding-right:10px;">
                    <strong>Customer Details</strong><br>
                    Name: {{ $order->user->name ?? '-' }}<br>
                    Email: {{ $order->user->email ?? '-' }}<br>
                    Phone: {{ $order->user->phone ?? '-' }}
                </td> --}}
                @php
                    $shipping = $order->shipping_address;
                @endphp

                <!-- Right Column -->
                <td style="width:50%; vertical-align: top; padding-left:10px;">
                    <strong>Shipping Address</strong><br>

                    Name: {{ $shipping['name'] ?? '-' }}<br>
                    Phone: {{ $shipping['mobile'] ?? '-' }}<br>
                    Email: {{ $shipping['email'] ?? '-' }}<br>

                    Address:
                    {{ $shipping['address'] ?? '' }}
                    {{ !empty($shipping['address_2']) ? ', ' . $shipping['address_2'] : '' }}<br>

                    {{ $shipping['city'] ?? '' }} - {{ $shipping['pincode'] ?? '' }}<br>
                    {{ $shipping['state'] ?? '' }}<br>

                    @if(!empty($shipping['landmark']))
                        Landmark: {{ $shipping['landmark'] }}<br>
                    @endif

                    @if(!empty($shipping['gst']))
                        GST: {{ $shipping['gst'] }}<br>
                    @endif
                </td>

                <!-- Right Column -->
                <td style="width:50%; vertical-align: top; padding-left:10px;">
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
                    {{--
                    <strong>Order Details</strong><br>
                    Order Status: {{ ucfirst($order->status) }}<br>
                    Payment Status: {{ ucfirst($order->payment_status) }}<br> --}}
                    {{-- @if($order->awb)
                    AWB Number: {{ $order->awb }} <br>
                    @endif --}}


                </td>
            </tr>
        </table>
        <!-- ORDER ITEMS -->
        <h3>Order Items</h3>
        <table class="table">
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
                        <td>{{ $item->product_title ?? '-' }}</td>
                        <td>{{ $item->sku ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>₹{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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

        <table class="total-table">
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
            <tr>
                <td><strong>Grand Total</strong></td>
                <td class="text-end text-success"><strong>₹{{ number_format($order->grand_total, 2) }}</strong></td>
            </tr>
        </table>

        <!-- FOOTER -->
        <div class="footer">
            Thank you for your order.<br>
            {{ $site->site_name ?? ' '  }}.
        </div>

    </div>

</body>

</html>