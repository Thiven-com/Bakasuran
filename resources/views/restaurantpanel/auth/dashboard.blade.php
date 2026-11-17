
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
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Orders</h6>
                        <h3>0</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Pending Orders</h6>
                        <h3>0</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Completed Orders</h6>
                        <h3>0</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h3>₹0</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
