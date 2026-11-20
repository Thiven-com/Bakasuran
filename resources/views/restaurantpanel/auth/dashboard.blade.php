
@extends('layout.restaurantpanelmainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>Restaurant Dashboard</h4>
                <h6>Welcome to BAKASURAN Restaurant Panel</h6>
            </div>
        </div>

        <div class="row">

            {{-- Total Orders --}}
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Orders</h6>
                        <h3>{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>

            {{-- Pending Orders --}}
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Pending Orders</h6>
                        <h3>{{ $pendingOrders }}</h3>
                    </div>
                </div>
            </div>

            {{-- Completed Orders --}}
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Completed Orders</h6>
                        <h3>{{ $completedOrders }}</h3>
                    </div>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h3>₹{{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
