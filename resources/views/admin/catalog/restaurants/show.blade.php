@extends('layout.mainlayout')

@section('title', 'Restaurant Details')

@section('content')
    <div class="restaurant-details-page " style="margin-top: 80px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Restaurant Details</h2>
                <p class="text-muted">View complete restaurant information.</p>
            </div>

            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Restaurants
            </a>
        </div>

        <div class="card mb-4">
            @if ($restaurant->cover_image)
                <img src="{{ asset($restaurant->cover_image) }}" alt="{{ $restaurant->name }}"
                    style="width:100%; height:260px; object-fit:cover;">
            @else
                <div style="height:180px; display:flex; align-items:center;
                                justify-content:center; background:#fff1f1;
                                color:#dc2626; font-size:24px; font-weight:700;">
                    <i class="fas fa-utensils me-2"></i>
                    {{ $restaurant->name }}
                </div>
            @endif

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h3>{{ $restaurant->name }}</h3>
                        <p class="text-muted mb-1">
                            {{ $restaurant->cuisine_type ?: 'Cuisine not specified' }}
                        </p>
                        <p class="text-muted">
                            {{ $restaurant->city }}, {{ $restaurant->state }}
                        </p>
                    </div>

                    <div>
                        <span class="badge {{ $restaurant->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Restaurant Information</h4>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <strong>Description</strong>
                        <p>{{ $restaurant->description ?: 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Address</strong>
                        <p>{{ $restaurant->address }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Phone</strong>
                        <p>{{ $restaurant->phone }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Email</strong>
                        <p>{{ $restaurant->email ?: 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Opening Time</strong>
                        <p>{{ $restaurant->opening_time }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Closing Time</strong>
                        <p>{{ $restaurant->closing_time }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Delivery Time</strong>
                        <p>{{ $restaurant->delivery_time }} minutes</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Delivery Fee</strong>
                        <p>₹{{ number_format((float) $restaurant->delivery_fee, 2) }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Minimum Order</strong>
                        <p>₹{{ number_format((float) $restaurant->minimum_order, 2) }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Average Rating</strong>
                        <p>
                            <i class="fas fa-star text-warning"></i>
                            {{ number_format((float) $restaurant->average_rating, 1) }} / 5
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Products</strong>
                        <p>{{ $restaurant->products_count }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Pincode</strong>
                        <p>{{ $restaurant->pincode }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Latitude</strong>
                        <p>{{ $restaurant->latitude ?? 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Longitude</strong>
                        <p>{{ $restaurant->longitude ?? 'Not provided' }}</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-danger">
                <i class="fas fa-edit"></i> Edit Restaurant
            </a>
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('sidebar');
            const restaurantPage = document.querySelector('.restaurant-details-page');

            function syncRestaurantPage() {
                if (!sidebar || !restaurantPage) return;

                if (window.innerWidth <= 991) {
                    restaurantPage.style.setProperty('margin-left', '0px', 'important');
                    restaurantPage.style.setProperty('width', '100%', 'important');
                    return;
                }

                const sidebarWidth = Math.round(
                    sidebar.getBoundingClientRect().width
                );

                restaurantPage.style.setProperty(
                    'margin-left',
                    sidebarWidth + 'px',
                    'important'
                );

                restaurantPage.style.setProperty(
                    'width',
                    'calc(100% - ' + sidebarWidth + 'px)',
                    'important'
                );
            }

            syncRestaurantPage();

            if (sidebar && typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(syncRestaurantPage);
                observer.observe(sidebar);
            }

            window.addEventListener('resize', syncRestaurantPage);

        });
    </script>

@endsection