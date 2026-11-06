@extends('layout.mainlayout')

@section('title', 'Restaurant Details')

@section('content')
    <div class="restaurant-details-page" style="margin-top: 80px;">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2>Restaurant Details</h2>
                <p class="text-muted">View complete restaurant information.</p>
            </div>

            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Restaurants
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            @if ($restaurant->cover_image)
                <img src="{{ asset($restaurant->cover_image) }}" alt="{{ $restaurant->name }}"
                     style="width: 100%; height: 260px; object-fit: cover;">
            @else
                <div style="height: 180px; display: flex; align-items: center;
                            justify-content: center; background: #fff1f1;
                            color: #dc2626; font-size: 24px; font-weight: 700;">
                    <i class="fas fa-utensils me-2"></i>
                    {{ $restaurant->name }}
                </div>
            @endif

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        @if ($restaurant->logo)
                            <img src="{{ asset($restaurant->logo) }}" alt="{{ $restaurant->name }} logo"
                                 style="width: 76px; height: 76px; object-fit: cover; border-radius: 12px; border: 1px solid #eee;">
                        @endif
                        <div>
                            <h3>{{ $restaurant->name }}</h3>
                            <p class="text-muted mb-1">
                                {{ $restaurant->cuisine_type ?: 'Cuisine not specified' }}
                            </p>
                            <p class="text-muted mb-0">
                                {{ $restaurant->city }}{{ $restaurant->state ? ', ' . $restaurant->state : '' }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge {{ $restaurant->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                        </span>
                        @if ($restaurant->is_featured)
                            <span class="badge bg-danger">Featured</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
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
                        <strong>Street Address</strong>
                        <p>{{ $restaurant->address ?: 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Phone</strong>
                        <p>{{ $restaurant->phone ?: 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Email</strong>
                        <p>{{ $restaurant->email ?: 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Opening Time</strong>
                        <p>{{ $restaurant->opening_time ? substr((string) $restaurant->opening_time, 0, 5) : 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Closing Time</strong>
                        <p>{{ $restaurant->closing_time ? substr((string) $restaurant->closing_time, 0, 5) : 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Delivery Time</strong>
                        <p>{{ $restaurant->delivery_time !== null ? $restaurant->delivery_time . ' minutes' : 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Delivery Fee</strong>
                        <p>₹{{ number_format((float) ($restaurant->delivery_fee ?? 0), 2) }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Minimum Order</strong>
                        <p>₹{{ number_format((float) ($restaurant->minimum_order ?? 0), 2) }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Price for Two</strong>
                        <p>₹{{ number_format((float) ($restaurant->price_for_two ?? 0), 2) }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Discount Percentage</strong>
                        <p>{{ (int) ($restaurant->discount_percentage ?? 0) }}%</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Average Rating</strong>
                        <p>
                            <i class="fas fa-star text-warning"></i>
                            {{ number_format((float) ($restaurant->average_rating ?? 0), 1) }} / 5
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Featured Restaurant</strong>
                        <p>{{ $restaurant->is_featured ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Display Order</strong>
                        <p>{{ $restaurant->sort_order ?? 0 }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Total Products</strong>
                        <p>{{ $restaurant->products_count ?? $restaurant->products()->count() }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Pincode</strong>
                        <p>{{ $restaurant->pincode ?: 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Latitude</strong>
                        <p>{{ $restaurant->latitude ?? 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Longitude</strong>
                        <p>{{ $restaurant->longitude ?? 'Not provided' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Created At</strong>
                        <p>{{ $restaurant->created_at ? $restaurant->created_at->format('d M Y, h:i A') : 'Not available' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Last Updated</strong>
                        <p>{{ $restaurant->updated_at ? $restaurant->updated_at->format('d M Y, h:i A') : 'Not available' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 mb-4 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-danger">
                <i class="fas fa-edit"></i> Edit Restaurant
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
                Back to Restaurants
            </a>
        </div>
    </div>

    <style>
        .restaurant-details-page {
            box-sizing: border-box;
            padding: 0 24px 30px;
            color: #252525;
            min-width: 0;
        }

        .restaurant-details-page .card {
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .035);
        }

        .restaurant-details-page .card-header {
            padding: 16px 20px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .restaurant-details-page .card-body {
            padding: 22px;
        }

        .restaurant-details-page .card-body strong {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-size: 13px;
        }

        .restaurant-details-page .card-body p {
            color: #666;
            overflow-wrap: anywhere;
        }

        @media (max-width: 768px) {
            .restaurant-details-page {
                padding: 0 15px 24px;
                margin-top: 30px !important;
            }

            .restaurant-details-page .card-body {
                padding: 16px;
            }
        }
    </style>

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

                const sidebarWidth = Math.round(sidebar.getBoundingClientRect().width);
                restaurantPage.style.setProperty('margin-left', sidebarWidth + 'px', 'important');
                restaurantPage.style.setProperty('width', 'calc(100% - ' + sidebarWidth + 'px)', 'important');
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
