@extends('layout.restaurantpanelmainlayout')

@section('title', 'Restaurant Bookings')

@section('content')

    <style>
        .booking-page {
            padding: 24px;
            margin-top: 50px;
            min-height: 100vh;
            background: #f7f7f9;
            color: #171717;
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .booking-header h2 {
            font-size: 25px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .booking-header p {
            color: #777;
            margin: 0;
            font-size: 14px;
        }

        .booking-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .booking-stat {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fff0f0;
            color: #ff0000;
            font-size: 21px;
            flex-shrink: 0;
        }

        .stat-label {
            color: #777;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 23px;
            font-weight: 700;
        }

        .booking-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
        }

        .booking-filters {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .booking-filters form {
            display: flex;
            align-items: center;
            /* flex-wrap: wrap; */
            gap: 12px;
        }

        .booking-filters .form-control,
        .booking-filters .form-select {
            min-width: 160px;
            font-size: 13px;
            padding: 10px 12px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .management-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        .management-table thead {
            background: #fff5f5;
        }

        .management-table th,
        .management-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
        }

        .management-table th {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
        }

        .booking-number {
            font-weight: 700;
            color: #222;
            margin-bottom: 4px;
        }

        .customer-name {
            font-weight: 600;
            color: #222;
            margin-bottom: 4px;
        }

        .customer-mobile,
        .booking-subtext {
            color: #888;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .status-pending {
            color: #9a6700;
            background: #fff4d6;
        }

        .status-confirmed {
            color: #16803d;
            background: #e8f7ed;
        }

        .status-cancelled {
            color: #c62828;
            background: #fff0f0;
        }

        .status-completed {
            color: #1769aa;
            background: #e7f2ff;
        }

        .status-no_show {
            color: #6b4c9a;
            background: #f1eaff;
        }

        .empty-bookings {
            padding: 45px 20px;
            text-align: center;
            color: #777;
        }

        .empty-bookings i {
            font-size: 38px;
            color: #ff0000;
            margin-bottom: 15px;
        }

        .pagination-wrapper {
            padding: 18px 20px;
            border-top: 1px solid #eee;
        }

        .booking-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        @media (max-width: 1100px) {
            .booking-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .booking-page {
                padding: 14px;
            }

            .booking-stats {
                grid-template-columns: 1fr;
            }

            .booking-filters form>* {
                width: 100%;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="booking-page">

            {{-- Page Header --}}
            <div class="booking-header">
                <div>
                    <h2>Restaurant Bookings</h2>
                    <p>View and manage your restaurant table reservations.</p>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif


            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following issue:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Booking Statistics --}}
            <div class="booking-stats">

                <div class="booking-stat">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Bookings</div>
                        <div class="stat-value">{{ $totalBookings }}</div>
                    </div>
                </div>

                <div class="booking-stat">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-label">Pending Bookings</div>
                        <div class="stat-value">{{ $pendingBookings }}</div>
                    </div>
                </div>

                <div class="booking-stat">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Confirmed Bookings</div>
                        <div class="stat-value">{{ $confirmedBookings }}</div>
                    </div>
                </div>

                <div class="booking-stat">
                    <div class="stat-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <div class="stat-label">Cancelled Bookings</div>
                        <div class="stat-value">{{ $cancelledBookings }}</div>
                    </div>
                </div>

            </div>

            {{-- Booking Listing --}}
            <div class="booking-card">

                {{-- Search and Filters --}}
                <div class="booking-filters">
                    <form method="GET" action="{{ route('restaurant.table-bookings.index') }}">

                        <input type="text" name="search" class="form-control" placeholder="Search booking or customer..."
                            value="{{ request('search') }}">

                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>

                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>

                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>
                                No Show
                            </option>
                        </select>

                        <input type="date" name="booking_date" class="form-control" value="{{ request('booking_date') }}">

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-search me-1"></i> Search
                        </button>

                        <a href="{{ route('restaurant.table-bookings.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </form>
                </div>

                {{-- Bookings Table --}}
                <div class="table-responsive">
                    <table class="management-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Details</th>
                                <th>Customer</th>
                                <th>Table</th>
                                <th>Booking Date</th>
                                <th>Time</th>
                                <th>Guests</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bookings as $booking)

                                <tr>
                                    <td>
                                        {{ $bookings->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <div class="booking-number">
                                            {{ $booking->booking_number }}
                                        </div>
                                        <div class="booking-subtext">
                                            Booking ID: {{ $booking->id }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="customer-name">
                                            {{ $booking->customer_name }}
                                        </div>
                                        <div class="customer-mobile">
                                            {{ $booking->customer_mobile }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($booking->restaurantTable)
                                            <div class="customer-name">
                                                {{ $booking->restaurantTable->table_name }}
                                            </div>
                                            <div class="booking-subtext">
                                                Table No:
                                                {{ $booking->restaurantTable->table_number }}
                                            </div>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        <div>
                                            {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                        </div>

                                        @if($booking->end_time)
                                            <div class="booking-subtext">
                                                To {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <strong>{{ $booking->guest_count }}</strong>
                                        <small>Guests</small>
                                    </td>

                                    <td>
                                        <span class="status-badge status-{{ $booking->status }}">
                                            {{ str_replace('_', ' ', $booking->status) }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="booking-actions">

                                            <a href="{{ route('restaurant.table-bookings.show', $booking->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-eye"></i> View
                                            </a>

                                            @if($booking->status === 'pending')

                                                <form action="{{ route('restaurant.table-bookings.status', $booking->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to confirm this booking?');">
                                                    @csrf

                                                    <input type="hidden" name="status" value="confirmed">

                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Confirm
                                                    </button>
                                                </form>

                                                <form action="{{ route('restaurant.table-bookings.status', $booking->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    @csrf

                                                    <input type="hidden" name="status" value="cancelled">

                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Cancel
                                                    </button>
                                                </form>

                                            @endif

                                        </div>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="9">
                                        <div class="empty-bookings">
                                            <i class="fas fa-calendar-times"></i>
                                            <h3>No Bookings Found</h3>
                                            <p>No restaurant bookings are available.</p>
                                        </div>
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if($bookings->hasPages())
                    <div class="pagination-wrapper">

                        Showing
                        {{ $bookings->firstItem() }}
                        to
                        {{ $bookings->lastItem() }}
                        of
                        {{ $bookings->total() }} bookings

                        {{ $bookings->onEachSide(1)->links('pagination::bootstrap-5') }}

                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection