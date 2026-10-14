<!DOCTYPE html>
<html>

<body style="margin:0; padding:0; background:#f5f6f8; font-family:Arial, sans-serif;">

    <table width="100%" bgcolor="#f5f6f8" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="700" bgcolor="#ffffff" cellpadding="15" cellspacing="0"
                    style="margin:20px auto; border-radius:6px;">

                    <!-- HEADER -->
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td>
                                        @if($site->logo)
                                            <img src="{{ $site->logo }}" style="height:40px;"><br>
                                        @endif
                                        <strong style="font-size:18px;">{{ $site->site_name ?? ' '  }}</strong><br>
                                        <span style="color:#777;">INVOICE</span>
                                    </td>
                                    <td align="right">
                                        <strong>Invoice #{{ $order->invoice_id }}</strong><br>
                                        Date: {{ $order->created_at->format('d M Y') }}<br>
                                        Status: {{ ucfirst($order->status) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CUSTOMER -->
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td width="50%">
                                        <strong>Shipping Address</strong><br><br>

                                        {{ $order->shipping_address['name'] ?? '-' }}<br>
                                        {{ $order->shipping_address['mobile'] ?? '-' }}<br>
                                        {{ $order->shipping_address['email'] ?? '-' }}<br><br>

                                        {{ $order->shipping_address['address'] ?? '' }},
                                        {{ $order->shipping_address['address_2'] ?? '' }}<br>

                                        {{ $order->shipping_address['city'] ?? '' }} -
                                        {{ $order->shipping_address['pincode'] ?? '' }}<br>

                                        {{ $order->shipping_address['state'] ?? '' }}
                                    </td>

                                    <td width="50%" align="right">
                                        <strong>Order Details</strong><br><br>

                                        Order ID: {{ $order->id }}<br>
                                        Payment: {{ ucfirst($order->payment_status) }}<br>

                                        @if($order->awb)
                                            AWB: {{ $order->awb }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ITEMS -->
                    <tr>
                        <td>
                            <strong>Order Items</strong><br><br>

                            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
                                <tr style="background:#f7f7f7;">
                                    <th align="left">#</th>
                                    <th align="left">Product</th>
                                    <th align="left">SKU</th>
                                    <th align="center">Qty</th>
                                    <th align="right">Price</th>
                                    <th align="right">Total</th>
                                </tr>

                                @foreach($order->items as $index => $item)
                                    <tr style="border-bottom:1px solid #eee;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product_title ?? '-' }}</td>
                                        <td>{{ $item->sku ?? '-' }}</td>
                                        <td align="center">{{ $item->quantity }}</td>
                                        <td align="right">₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td align="right">₹{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <!-- TOTAL -->
                    <tr>
                        <td align="right">
                            <table width="300" align="right">
                                <tr>
                                    <td><strong>Subtotal</strong></td>
                                    <td align="right">₹{{ number_format($order->grand_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Grand Total</strong></td>
                                    <td align="right" style="color:green; font-weight:bold;">
                                        ₹{{ number_format($order->grand_amount, 2) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="color:#888; font-size:12px;">
                            Thank you for your order ❤️<br>
                            © {{ date('Y') }} {{ $site->site_name ?? ' '  }}
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>