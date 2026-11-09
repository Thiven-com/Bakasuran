<?php $page = 'index'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Welcome Banner --}}
        <div class="bakasuran-welcome mb-4">
            <div>
                <span class="welcome-label">BAKASURAN ADMIN PANEL</span>
                <h2>Welcome, Admin 👋</h2>
                <p>Manage your restaurants, tables, bookings and orders from one place.</p>
                <div class="welcome-date">
                    <i class="ti ti-calendar"></i>
                    {{ now()->format('l, d F Y') }}
                </div>
            </div>

            <div class="welcome-icon">
                <i class="ti ti-chef-hat"></i>
            </div>
        </div>

        {{-- Main Statistics --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="bak-stat-card red">
                    <div class="bak-stat-info">
                        <p>Total Restaurants</p>
                        <h3>{{ number_format($counts['restaurants']) }}</h3>
                        <small>Registered restaurants</small>
                    </div>
                    <div class="bak-stat-icon">
                        <i class="ti ti-building-store"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="bak-stat-card dark">
                    <div class="bak-stat-info">
                        <p>Total Restaurant Tables</p>
                        <h3>{{ number_format($counts['tables']) }}</h3>
                        <small>{{ $activeTables }} active tables</small>
                    </div>
                    <div class="bak-stat-icon">
                        <i class="ti ti-armchair"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="bak-stat-card orange">
                    <div class="bak-stat-info">
                        <p>Total Menu Items</p>
                        <h3>{{ number_format($counts['menu_items']) }}</h3>
                        <small>{{ $counts['variants'] }} product variants</small>
                    </div>
                    <div class="bak-stat-icon">
                        <i class="ti ti-tools-kitchen-2"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="bak-stat-card purple">
                    <div class="bak-stat-info">
                        <p>Total Categories</p>
                        <h3>{{ number_format($counts['categories']) }}</h3>
                        <small>Menu categories</small>
                    </div>
                    <div class="bak-stat-icon">
                        <i class="ti ti-category"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Booking and Order Statistics --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="bak-small-card">
                    <div class="bak-small-icon red-bg">
                        <i class="ti ti-calendar-check"></i>
                    </div>
                    <div>
                        <p>Today's Bookings</p>
                        <h3>{{ number_format($bookingsToday) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="bak-small-card">
                    <div class="bak-small-icon orange-bg">
                        <i class="ti ti-clock-hour-4"></i>
                    </div>
                    <div>
                        <p>Pending Bookings</p>
                        <h3>{{ number_format($pendingBookings) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="bak-small-card">
                    <div class="bak-small-icon green-bg">
                        <i class="ti ti-shopping-bag"></i>
                    </div>
                    <div>
                        <p>Today's Orders</p>
                        <h3>{{ number_format($ordersToday) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="bak-small-card">
                    <div class="bak-small-icon blue-bg">
                        <i class="ti ti-currency-rupee"></i>
                    </div>
                    <div>
                        <p>Today's Revenue</p>
                        <h3>₹{{ number_format($todayRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>

        </div>

        {{-- Revenue and Booking Overview --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-8">
                <div class="card bak-panel h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Monthly Booking Overview</h5>
                            <small class="text-muted">Daily restaurant booking statistics</small>
                        </div>

                        <span class="bak-month-label">
                            {{ now()->format('F Y') }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div style="height:300px;">
                            <canvas id="bookingChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card bak-panel h-100">
                    <div class="card-header">
                        <h5 class="mb-1">Booking Configuration</h5>
                        <small class="text-muted">Restaurant booking setup</small>
                    </div>

                    <div class="card-body">

                        <div class="bak-config-item">
                            <div class="bak-config-icon red-bg">
                                <i class="ti ti-calendar-event"></i>
                            </div>
                            <div>
                                <h6>Enabled Booking Settings</h6>
                                <small>Restaurants with booking enabled</small>
                            </div>
                            <strong>{{ $bookingSettingsCount }}</strong>
                        </div>

                        <div class="bak-config-item">
                            <div class="bak-config-icon orange-bg">
                                <i class="ti ti-clock"></i>
                            </div>
                            <div>
                                <h6>Active Booking Sessions</h6>
                                <small>Available booking sessions</small>
                            </div>
                            <strong>{{ $bookingSessionsCount }}</strong>
                        </div>

                        <div class="bak-config-item">
                            <div class="bak-config-icon green-bg">
                                <i class="ti ti-calendar-check"></i>
                            </div>
                            <div>
                                <h6>Total Bookings</h6>
                                <small>All registered bookings</small>
                            </div>
                            <strong>{{ $totalBookings }}</strong>
                        </div>

                        <div class="bak-revenue-box mt-4">
                            <small>Monthly Revenue</small>
                            <h3>₹{{ number_format($monthRevenue, 2) }}</h3>
                            <span>This month's paid orders</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Recent Bookings --}}
        <div class="card bak-panel mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Recent Restaurant Bookings</h5>
                    <small class="text-muted">Latest customer table reservations</small>
                </div>
                <span class="bak-count-label">{{ $totalBookings }} Total</span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bak-table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
                                <th>Guests</th>
                                <th>Table</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>

                                    <td>
                                        <strong>
                                            {{ data_get($booking, 'customer_name')
                                                ?? data_get($booking, 'name')
                                                ?? data_get($booking, 'user_name')
                                                ?? 'Customer' }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ data_get($booking, 'booking_date')
                                            ? \Carbon\Carbon::parse(data_get($booking, 'booking_date'))->format('d M Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ data_get($booking, 'booking_time') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ data_get($booking, 'guests') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ data_get($booking, 'table_id')
                                            ? 'Table #' . data_get($booking, 'table_id')
                                            : '-' }}
                                    </td>

                                    <td>
                                        @php
                                            $bookingStatus = strtolower(
                                                data_get($booking, 'status')
                                                ?? data_get($booking, 'booking_status')
                                                ?? 'pending'
                                            );
                                        @endphp

                                        <span class="bak-status {{ $bookingStatus }}">
                                            {{ ucfirst(str_replace('_', ' ', $bookingStatus)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="ti ti-calendar-off fs-2 d-block mb-2"></i>
                                        No bookings available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Restaurant Tables --}}
        <div class="card bak-panel mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Restaurant Tables</h5>
                    <small class="text-muted">Recently added restaurant tables</small>
                </div>

                <span class="bak-count-label">{{ $counts['tables'] }} Tables</span>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    @forelse($restaurantTables as $table)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="bak-table-card">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="bak-table-icon">
                                        <i class="ti ti-armchair"></i>
                                    </div>

                                    @php
                                        $tableStatus = strtolower(
                                            data_get($table, 'status')
                                            ?? (data_get($table, 'is_active') !== null
                                                ? (data_get($table, 'is_active') ? 'active' : 'inactive')
                                                : 'available')
                                        );
                                    @endphp

                                    <span class="bak-status {{ $tableStatus }}">
                                        {{ ucfirst($tableStatus) }}
                                    </span>
                                </div>

                                <h5 class="mt-3 mb-1">
                                    Table {{ data_get($table, 'table_number') ?? $table->id }}
                                </h5>

                                <p class="text-muted mb-0">
                                    <i class="ti ti-users me-1"></i>
                                    {{ data_get($table, 'seating_capacity') ?? 0 }} Seats
                                </p>

                                <small class="text-muted">
                                    {{ ucfirst(data_get($table, 'seating_type') ?? 'Standard') }}
                                </small>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted">
                            No restaurant tables found
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="card bak-panel mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Recent Orders</h5>
                    <small class="text-muted">Latest restaurant orders</small>
                </div>

                <span class="bak-count-label">{{ $totalOrders }} Total Orders</span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bak-table align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Order Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong>
                                            #{{ data_get($order, 'invoice_id')
                                                ?? data_get($order, 'order_number')
                                                ?? $order->id }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ data_get($order, 'customer_name')
                                            ?? data_get($order, 'name')
                                            ?? 'Customer' }}
                                    </td>

                                    <td>
                                        ₹{{ number_format(
                                            (float) (
                                                data_get($order, 'grand_total')
                                                ?? data_get($order, 'total_amount')
                                                ?? data_get($order, 'total')
                                                ?? data_get($order, 'amount')
                                                ?? 0
                                            ),
                                            2
                                        ) }}
                                    </td>

                                    <td>
                                        {{ ucfirst(data_get($order, 'payment_status') ?? '-') }}
                                    </td>

                                    <td>
                                        @php
                                            $orderStatus = strtolower(
                                                data_get($order, 'status')
                                                ?? data_get($order, 'order_status')
                                                ?? 'pending'
                                            );
                                        @endphp

                                        <span class="bak-status {{ $orderStatus }}">
                                            {{ ucfirst(str_replace('_', ' ', $orderStatus)) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ data_get($order, 'created_at')
                                            ? \Carbon\Carbon::parse(data_get($order, 'created_at'))->format('d M Y')
                                            : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No orders available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="copyright-footer d-flex align-items-center justify-content-between border-top bg-white gap-3 flex-wrap">
        <p class="fs-13 text-gray-9 mb-0">
            {{ now()->year }} &copy;
            {{ data_get($site, 'site_name') ?? config('app.name') }}.
            All Rights Reserved
        </p>

        <p>Designed & Developed By
            <a href="javascript:void(0);" class="link-primary">ThiVen</a>
        </p>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('bookingChart');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const ctx = canvas.getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(215, 19, 19, 0.30)');
        gradient.addColorStop(1, 'rgba(215, 19, 19, 0.01)');

        new Chart(ctx, {
            type: 'line',

            data: {
                labels: @json($data['month_dates']),

                datasets: [{
                    label: 'Bookings',
                    data: @json($data['month_values']),
                    borderColor: '#d71313',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#d71313'
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: 'index'
                },

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#171717',
                        padding: 12
                    }
                },

                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Day of Month'
                        }
                    },

                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.06)'
                        },
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    :root {
        --bak-red: #d71313;
        --bak-dark: #171717;
        --bak-muted: #777;
        --bak-border: #eeeeee;
    }

    .bakasuran-welcome {
        background: linear-gradient(120deg, #d71313, #850909);
        border-radius: 18px;
        padding: 30px 35px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(215, 19, 19, .16);
    }

    .welcome-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        opacity: .8;
    }

    .bakasuran-welcome h2 {
        color: #fff;
        font-size: 30px;
        font-weight: 800;
        margin: 10px 0;
    }

    .bakasuran-welcome p {
        color: #fff;
        opacity: .9;
        margin-bottom: 12px;
    }

    .welcome-date {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        background: rgba(255,255,255,.14);
        padding: 8px 13px;
        border-radius: 30px;
        font-size: 12px;
    }

    .welcome-icon {
        width: 85px;
        height: 85px;
        border-radius: 22px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 43px;
        color: #fff;
    }

    .bak-stat-card {
        min-height: 145px;
        background: #fff;
        border-radius: 15px;
        padding: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid var(--bak-border);
        box-shadow: 0 5px 20px rgba(0,0,0,.035);
        transition: .3s ease;
        height: 100%;
    }

    .bak-stat-card:hover,
    .bak-small-card:hover,
    .bak-table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,.09);
    }

    .bak-stat-info p,
    .bak-small-card p {
        color: var(--bak-muted);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .bak-stat-info h3 {
        color: var(--bak-dark);
        font-size: 29px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .bak-stat-info small {
        color: #999;
        font-size: 11px;
    }

    .bak-stat-icon {
        width: 58px;
        height: 58px;
        min-width: 58px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .bak-stat-card.red {
        border-bottom: 4px solid #d71313;
    }

    .bak-stat-card.red .bak-stat-icon {
        color: #d71313;
        background: #fff0f0;
    }

    .bak-stat-card.dark {
        border-bottom: 4px solid #242424;
    }

    .bak-stat-card.dark .bak-stat-icon {
        color: #242424;
        background: #f1f1f1;
    }

    .bak-stat-card.orange {
        border-bottom: 4px solid #f28b24;
    }

    .bak-stat-card.orange .bak-stat-icon {
        color: #f28b24;
        background: #fff4e8;
    }

    .bak-stat-card.purple {
        border-bottom: 4px solid #805ad5;
    }

    .bak-stat-card.purple .bak-stat-icon {
        color: #805ad5;
        background: #f3edff;
    }

    .bak-small-card {
        height: 100%;
        background: #fff;
        border: 1px solid var(--bak-border);
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,.035);
        transition: .3s ease;
    }

    .bak-small-card h3 {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        color: #222;
    }

    .bak-small-card p {
        margin-bottom: 5px;
    }

    .bak-small-icon,
    .bak-config-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .red-bg {
        color: #d71313;
        background: #fff0f0;
    }

    .orange-bg {
        color: #e58b21;
        background: #fff5e8;
    }

    .green-bg {
        color: #198754;
        background: #eaf8f0;
    }

    .blue-bg {
        color: #2878c8;
        background: #edf5ff;
    }

    .bak-panel {
        border: 1px solid var(--bak-border);
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,.035);
        overflow: hidden;
    }

    .bak-panel .card-header {
        background: #fff;
        padding: 20px;
        border-bottom: 1px solid var(--bak-border);
    }

    .bak-panel .card-header h5 {
        color: #222;
        font-weight: 750;
    }

    .bak-panel .card-body {
        padding: 20px;
    }

    .bak-month-label,
    .bak-count-label {
        background: #fff0f0;
        color: #d71313;
        padding: 8px 13px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }

    .bak-config-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .bak-config-item h6 {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .bak-config-item small {
        color: #999;
        font-size: 11px;
    }

    .bak-config-item strong {
        margin-left: auto;
        font-size: 20px;
        color: #222;
    }

    .bak-revenue-box {
        background: linear-gradient(120deg, #d71313, #850909);
        border-radius: 13px;
        padding: 20px;
        color: #fff;
    }

    .bak-revenue-box small,
    .bak-revenue-box span {
        color: rgba(255,255,255,.8);
        font-size: 12px;
    }

    .bak-revenue-box h3 {
        color: #fff;
        font-size: 27px;
        font-weight: 800;
        margin: 8px 0;
    }

    .bak-table {
        margin-bottom: 0;
    }

    .bak-table thead th {
        background: #fff7f7;
        color: #777;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 13px;
        white-space: nowrap;
    }

    .bak-table tbody td {
        padding: 15px 13px;
        font-size: 13px;
        color: #555;
        border-bottom: 1px solid #f3f3f3;
        white-space: nowrap;
    }

    .bak-table tbody tr:hover {
        background: #fffafa;
    }

    .bak-status {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        background: #f1f1f1;
        color: #555;
    }

    .bak-status.active,
    .bak-status.available,
    .bak-status.confirmed,
    .bak-status.completed,
    .bak-status.paid {
        color: #16834b;
        background: #e9f8ef;
    }

    .bak-status.pending,
    .bak-status.inactive,
    .bak-status.processing {
        color: #c17a13;
        background: #fff5df;
    }

    .bak-status.cancelled,
    .bak-status.rejected,
    .bak-status.failed {
        color: #d71313;
        background: #fff0f0;
    }

    .bak-table-card {
        border: 1px solid var(--bak-border);
        border-radius: 14px;
        padding: 18px;
        background: #fff;
        transition: .3s ease;
        height: 100%;
    }

    .bak-table-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        background: #fff0f0;
        color: #d71313;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .bak-table-card h5 {
        color: #222;
        font-weight: 750;
    }

    @media (max-width: 767px) {
        .bakasuran-welcome {
            padding: 23px 20px;
            border-radius: 14px;
        }

        .bakasuran-welcome h2 {
            font-size: 22px;
        }

        .bakasuran-welcome p {
            font-size: 12px;
        }

        .welcome-icon {
            width: 55px;
            height: 55px;
            min-width: 55px;
            font-size: 27px;
            border-radius: 15px;
        }

        .bak-stat-card {
            min-height: 120px;
            padding: 17px;
        }

        .bak-stat-info h3 {
            font-size: 25px;
        }

        .bak-stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            font-size: 22px;
        }

        .bak-small-card {
            padding: 16px;
        }

        .bak-panel .card-header,
        .bak-panel .card-body {
            padding: 15px;
        }
    }
</style>

@endsection