
@extends('layout.mainlayout')

@section('title', 'Table Bookings')

@section('content')

<style>
    .booking-page {
        padding: 24px;
        background: #f7f7f9;
        min-height: 100vh;
        color: #171717;
        margin-top: 50px;
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

    .btn-add-booking {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        background: #ff0000;
        color: #fff;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: .2s;
    }

    .btn-add-booking:hover {
        background: #cc0000;
        color: #fff;
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
        line-height: 1.2;
    }

    .booking-toolbar {
        background: #fff;
        padding: 18px;
        border: 1px solid #eee;
        border-radius: 12px;
        margin-bottom: 22px;
    }

    .booking-filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .booking-search {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .booking-search i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .booking-search input,
    .booking-filter-item input,
    .booking-filter-item select {
        width: 100%;
        padding: 12px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        outline: none;
        background: #fff;
        min-height: 44px;
    }

    .booking-search input {
        padding-left: 40px;
    }

    .booking-search input:focus,
    .booking-filter-item input:focus,
    .booking-filter-item select:focus {
        border-color: #ff0000;
    }

    .booking-filter-item {
        min-width: 160px;
    }

    .booking-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        overflow: hidden;
    }

    .booking-responsive {
        overflow-x: auto;
    }

    .management-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1150px;
    }

    .management-table thead {
        background: #fff5f5;
    }

    .management-table th {
        padding: 16px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
        font-weight: 700;
        border-bottom: 1px solid #eee;
        white-space: nowrap;
    }

    .management-table td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
        vertical-align: middle;
    }

    .management-table tbody tr:last-child td {
        border-bottom: none;
    }

    .booking-number {
        color: #d60000;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .customer-name {
        font-weight: 700;
        color: #222;
        margin-bottom: 4px;
    }

    .customer-mobile {
        color: #888;
        font-size: 12px;
    }

    .booking-date {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .booking-time {
        color: #888;
        font-size: 12px;
    }

    .guest-count {
        font-weight: 700;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
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
        background: #e8f3ff;
    }

    .status-no-show {
        color: #6b4c9a;
        background: #f1eaff;
    }

    .table-details {
        font-weight: 600;
        color: #333;
    }

    .table-actions {
        display: flex;
        gap: 8px;
    }

    .table-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e5e5;
        border-radius: 7px;
        color: #444;
        background: #fff;
        text-decoration: none;
        cursor: pointer;
    }

    .table-action:hover {
        border-color: #ff0000;
        color: #ff0000;
    }

    .table-action.delete {
        color: #dc2626;
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
        padding: 16px;
        border-top: 1px solid #eee;
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

        .booking-header {
            align-items: flex-start;
        }

        .booking-header .btn-add-booking {
            width: 100%;
            justify-content: center;
        }

        .booking-filter-item,
        .booking-search {
            width: 100%;
            min-width: 100%;
        }
    }
</style>

<div class="booking-page">

    {{-- Page Header --}}
    <div class="booking-header">
        <div>
            <h2>Table Bookings</h2>
            <p>Manage customer reservations and booking status.</p>
        </div>

        <a href="{{ route('admin.restaurant-bookings.create') }}"
           class="btn-add-booking">
            <i class="fas fa-plus"></i>
            Add New Booking
        </a>
    </div>

    {{-- Success / Error Messages --}}
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

    {{-- Statistics --}}
    <div class="booking-stats">

        <div class="booking-stat">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
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

    {{-- Search and Filters --}}
    <div class="booking-toolbar">

        <form action="{{ route('admin.restaurant-bookings.index') }}"
              method="GET">

            <div class="booking-filters">

                <div class="booking-search">
                    <i class="fas fa-search"></i>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search booking number, customer or mobile...">
                </div>

                <div class="booking-filter-item">
                    <select name="restaurant_id">
                        <option value="">All Restaurants</option>

                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}"
                                {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                {{ $restaurant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="booking-filter-item">
                    <select name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>

                <div class="booking-filter-item">
                    <input type="date"
                           name="booking_date"
                           value="{{ request('booking_date') }}">
                </div>

                <button type="submit" class="btn-add-booking">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a href="{{ route('admin.restaurant-bookings.index') }}"
                   class="btn btn-light border">
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- Booking Listing --}}
    <div class="booking-card">

        <div class="booking-responsive">

            <table class="management-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking Number</th>
                        <th>Customer Details</th>
                        <th>Restaurant</th>
                        <th>Table</th>
                        <th>Booking Date & Time</th>
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
                                {{ $booking->restaurant->name ?? 'N/A' }}
                            </td>

                            <td>
                                <div class="table-details">
                                    {{ $booking->restaurantTable->table_name ?? 'N/A' }}
                                </div>

                                <small class="text-muted">
                                    {{ $booking->restaurantTable->table_number ?? '' }}
                                </small>
                            </td>

                            <td>
                                <div class="booking-date">
                                    {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }}
                                </div>

                                <div class="booking-time">
                                    {{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') : 'N/A' }}

                                    @if($booking->end_time)
                                        - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                    @endif
                                </div>
                            </td>

                            <td>
                                <span class="guest-count">
                                    {{ $booking->guest_count }}
                                </span>
                                <small>Guests</small>
                            </td>

                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'status-pending',
                                        'confirmed' => 'status-confirmed',
                                        'cancelled' => 'status-cancelled',
                                        'completed' => 'status-completed',
                                        'no_show' => 'status-no-show',
                                    ][$booking->status] ?? 'status-pending';
                                @endphp

                                <span class="status-badge {{ $statusClass }}">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="table-actions">

                                    <a href="{{ route('admin.restaurant-bookings.show', $booking->id) }}"
                                       class="table-action"
                                       title="View Booking">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.restaurant-bookings.edit', $booking->id) }}"
                                       class="table-action"
                                       title="Edit Booking">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <form action="{{ route('admin.restaurant-bookings.destroy', $booking->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this booking?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="table-action delete"
                                                title="Delete Booking">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">

                                <div class="empty-bookings">
                                    <i class="fas fa-calendar-times"></i>

                                    <h3>No Table Bookings Found</h3>

                                    <p>
                                        Customer reservations will appear here.
                                    </p>
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
                {{ $bookings->links() }}
            </div>
        @endif

    </div>

</div>

{{-- Responsive Sidebar Sync --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* =========================================
           TABLE BOOKINGS PAGE - RESPONSIVE SIDEBAR SYNC
        ========================================= */

        const sidebar = document.getElementById('sidebar');
        const bookingPage = document.querySelector('.booking-page');

        if (!sidebar || !bookingPage) {
            return;
        }

        function syncBookingPageWithSidebar() {

            if (window.innerWidth <= 991) {

                bookingPage.style.setProperty(
                    'margin-left',
                    '0px',
                    'important'
                );

                bookingPage.style.setProperty(
                    'width',
                    '100%',
                    'important'
                );

                return;
            }

            const sidebarWidth = Math.round(
                sidebar.getBoundingClientRect().width
            );

            bookingPage.style.setProperty(
                'margin-left',
                sidebarWidth + 'px',
                'important'
            );

            bookingPage.style.setProperty(
                'width',
                'calc(100% - ' + sidebarWidth + 'px)',
                'important'
            );
        }

        syncBookingPageWithSidebar();

        if (typeof ResizeObserver !== 'undefined') {

            const observer = new ResizeObserver(function () {
                syncBookingPageWithSidebar();
            });

            observer.observe(sidebar);
        }

        document.addEventListener('click', function () {
            setTimeout(syncBookingPageWithSidebar, 100);
            setTimeout(syncBookingPageWithSidebar, 300);
        });

        window.addEventListener('resize', syncBookingPageWithSidebar);

    });
</script>

@endsection
