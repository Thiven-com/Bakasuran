@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Abandoned Cart Details</h4>
                    <h6>Customer and Cart Items</h6>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="card p-3 mb-3">
                <h5>Customer Details</h5>
                <p><b>Name:</b> {{ $customer->name ?? '-' }}</p>
                <p><b>Email:</b> {{ $customer->email ?? '-' }}</p>
                <p><b>Mobile:</b> {{ $customer->mobile ?? '-' }}</p>
                <p><b>Total Amount:</b> {{ $customer->cart_total ?? 0 }}</p>
            </div>

            <!-- Cart Items -->
            <div class="card p-3">
                <h5>Cart Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $j = 1;
                            @endphp
                            @foreach($customer->carts as $item)
                               
                                <tr>
                                    <td>{{ $j }}</td>
                                    <td>{{ $item->variant->product->title ?? '' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit_price }}</td>
                                    <td>₹{{ $item->quantity * $item->unit_price }}</td>
                                </tr>
                                @php $j++; @endphp
                            @endforeach
                            @if(count($customer->carts) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">No items in cart</td>
                                </tr>
                            @else
                                <!-- Total Row -->
                                <tr>
                                    <td colspan="4" class="text-end"><b>Total Amount:</b></td>
                                    <td><b>₹{{ $customer->cart_total }}</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.abandonedCart') }}" class="btn btn-secondary mt-2">Back to List</a>
            </div>
        </div>
    </div>
@endsection