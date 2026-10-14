<!DOCTYPE html>
<html>
<head>
    <title>Abandoned Cart Details</title>
    <meta charset="UTF-8">
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; }
        h2, h5 { color: #333333; margin-bottom: 10px; }
        p { color: #555555; line-height: 1.5; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #1d72b8; color: #ffffff; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold; }
        .total-row td { font-weight: bold; }
        @media screen and (max-width: 600px) {
            .container { width: 90% !important; padding: 15px; }
            .btn { width: 100%; text-align: center; padding: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <h2>Abandoned Cart Details</h2>
        <p>Hi {{ $customer->name }},</p>
        <p>We noticed you left some items in your cart. Don’t miss out!</p>

        <!-- Customer Details -->
        <h5>Customer Details</h5>
        <p><b>Name:</b> {{ $customer->name ?? '-' }}</p>
        <p><b>Email:</b> {{ $customer->email ?? '-' }}</p>
        <p><b>Mobile:</b> {{ $customer->mobile ?? '-' }}</p>
        <p><b>Total Amount:</b> ₹{{ $customer->cart_total ?? 0 }}</p>

        <!-- Cart Items -->
        <h5>Cart Items</h5>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $j = 1; @endphp
                @foreach($customer->carts as $item)
                    <tr>
                        <td>{{ $j }}</td>
                        <td>{{ $item->variant->product->title ?? '' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ $item->unit_price }}</td>
                        <td>₹{{ $item->quantity * $item->unit_price }}</td>
                    </tr>
                    @php $j++; @endphp
                @endforeach
                @if(count($customer->carts) == 0)
                    <tr>
                        <td colspan="5" style="text-align:center;">No items in cart</td>
                    </tr>
                @else
                    <tr class="total-row">
                        <td colspan="4" style="text-align:right;">Total Amount:</td>
                        <td>₹{{ $customer->cart_total }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- CTA Button -->
        <a class="btn">Please Complete Your Purchase</a>

        <!-- Footer -->
        <p style="font-size:12px; color:#999999; margin-top:20px;">
            If you did not create this cart, please ignore this email.<br>
            Samruddhi Silk House.
        </p>
    </div>
</body>
</html>