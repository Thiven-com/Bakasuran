
@extends('layout.mainlayout')

@section('content')

<div class="booking-details-page">

    <style>
        .booking-details-page {
            margin-top: 70px;
            padding: 20px;
            background: #f7f7f9;
            min-height: 100vh;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .details-header h4 {
            font-weight: 700;
            margin-bottom: 5px;
            color: #222;
        }

        .details-header p {
            color: #777;
            margin: 0;
        }

        .details-card {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
        }

        .details-card h5 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .detail-item {
            margin-bottom: 18px;
        }

        .detail-label {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .detail-value {
            color: #222;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .booking-number {
            color: #ff0000;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #842029;
        }

        .status-completed {
            background: #cfe2ff;
            color: #084298;
        }

        .status-no_show {
            background: #e2e3e5;
            color: #41464b;
        }

        .details-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-red {
            background: #ff0000;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 7px;
            text-decoration: none;
        }

        .btn-red:hover {
            background: #d90000;
            color: #fff;
        }

        .btn-light-custom {
            background: #eee;
            color: #333;
            padding: 9px 18px;
            border-radius: 7px;
            text-decoration: none;
        }

        .btn-light-custom:hover {
            background: #ddd;
            color: #222;
        }

        @media(max-width: 767px) {
            .booking-details-page {
                padding: 12px;
            }

            .details-card {
                padding: 16px;
            }
        }
    </style>

    {{-- Header --}}
    <div class="details-header">
        <div>
            <h4>Booking Details</h4>
            <p>
                Booking number:
                <span class="booking-number">
                    {{ $restaurant_booking->booking_number }}
                </span>
            </p>
        </div>

        <div class="details-actions">
            <a href="{{ route('admin.restaurant-bookings.index') }}"
               class="btn-light-custom">
                <i class="ti ti-arrow-left"></i> Back
            </a>

            <a href="{{ route('admin.restaurant-bookings.edit', $restaurant_booking->id) }}"
               class="btn-red">
                <i class="ti ti-edit"></i> Edit Booking
            </a>
        </div>
    </div>

    <div class="row">

        {{-- Booking Information --}}
        <div class="col-lg-8">

            <div class="details-card">
                <h5>
                    <i class="ti ti-calendar-event me-2"></i>
                    Booking Information
                </h5>

                <div class="row">

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Booking Number</span>
                        <div class="detail-value booking-number">
                            {{ $restaurant_booking->booking_number }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Booking Status</span>
                        <div class="detail-value">
                            @php
                                $status = $restaurant_booking->status ?? 'pending';
                            @endphp

                            <span class="status-badge status-{{ $status }}">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Number of Guests</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->guest_count }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Booking Date</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->booking_date
                                ? \Carbon\Carbon::parse($restaurant_booking->booking_date)->format('d M Y')
                                : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Start Time</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->booking_time
                                ? \Carbon\Carbon::parse($restaurant_booking->booking_time)->format('h:i A')
                                : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">End Time</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->end_time
                                ? \Carbon\Carbon::parse($restaurant_booking->end_time)->format('h:i A')
                                : 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Seating Preference</span>
                        <div class="detail-value">
                            {{ ucfirst($restaurant_booking->seating_type ?? 'N/A') }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Occasion</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->occasion ?: 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Created On</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->created_at
                                ? $restaurant_booking->created_at->format('d M Y, h:i A')
                                : 'N/A' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- Customer Information --}}
            <div class="details-card">
                <h5>
                    <i class="ti ti-user me-2"></i>
                    Customer Information
                </h5>

                <div class="row">

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Customer Name</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->customer_name }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Mobile Number</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->customer_mobile }}
                        </div>
                    </div>

                    <div class="col-md-4 detail-item">
                        <span class="detail-label">Email Address</span>
                        <div class="detail-value">
                            {{ $restaurant_booking->customer_email ?: 'N/A' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- Requests and Notes --}}
            <div class="details-card">
                <h5>
                    <i class="ti ti-notes me-2"></i>
                    Requests & Admin Notes
                </h5>

                <div class="detail-item">
                    <span class="detail-label">Special Requests</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->special_requests ?: 'No special requests' }}
                    </div>
                </div>

                <div class="detail-item mb-0">
                    <span class="detail-label">Admin Notes</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->admin_notes ?: 'No admin notes' }}
                    </div>
                </div>
            </div>

        </div>

        {{-- Restaurant and Table --}}
        <div class="col-lg-4">

            <div class="details-card">
                <h5>
                    <i class="ti ti-building-store me-2"></i>
                    Restaurant Information
                </h5>

                <div class="detail-item">
                    <span class="detail-label">Restaurant Name</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->restaurant->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Restaurant Phone</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->restaurant->phone ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Restaurant Address</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->restaurant->address ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="details-card">
                <h5>
                    <i class="ti ti-layout-grid me-2"></i>
                    Table Information
                </h5>

                <div class="detail-item">
                    <span class="detail-label">Table Name</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->restaurantTable->table_name ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Table Number</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->restaurantTable->table_number ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Seating Capacity</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->restaurantTable->seating_capacity ?? 'N/A' }}
                        {{ $restaurant_booking->restaurantTable ? 'Guests' : '' }}
                    </div>
                </div>
            </div>

            <div class="details-card">
                <h5>
                    <i class="ti ti-clock-check me-2"></i>
                    Booking Timeline
                </h5>

                <div class="detail-item">
                    <span class="detail-label">Confirmed At</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->confirmed_at
                            ? \Carbon\Carbon::parse($restaurant_booking->confirmed_at)->format('d M Y, h:i A')
                            : 'Not confirmed' }}
                    </div>
                </div>

                <div class="detail-item mb-0">
                    <span class="detail-label">Cancelled At</span>
                    <div class="detail-value">
                        {{ $restaurant_booking->cancelled_at
                            ? \Carbon\Carbon::parse($restaurant_booking->cancelled_at)->format('d M Y, h:i A')
                            : 'Not cancelled' }}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingDetailsLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-details-page');

        if (!sidebar || !page) return;

        if (window.innerWidth <= 991) {
            page.style.marginLeft = '0';
            page.style.width = '100%';
        } else {
            const sidebarWidth = sidebar.getBoundingClientRect().width;

            page.style.marginLeft = sidebarWidth + 'px';
            page.style.width = 'calc(100% - ' + sidebarWidth + 'px)';
        }
    }

    syncBookingDetailsLayout();

    window.addEventListener('load', syncBookingDetailsLayout);
    window.addEventListener('resize', syncBookingDetailsLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingDetailsLayout, 100);
        setTimeout(syncBookingDetailsLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingDetailsLayout);
        observer.observe(sidebar);
    }

});
</script>

@endsection
