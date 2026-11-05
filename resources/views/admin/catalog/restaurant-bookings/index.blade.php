@extends('layout.mainlayout')

@section('title', 'Restaurant Bookings')

@section('content')
    <style>
        .booking-page {
            padding: 24px;
            background: #f7f7f9;
            min-height: 100vh;
            color: #171717;
            margin-top: 50px
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px
        }

        .booking-header h2 {
            font-size: 25px;
            font-weight: 700;
            margin: 0 0 6px
        }

        .booking-header p {
            color: #777;
            margin: 0;
            font-size: 14px
        }

        .btn-add-booking,
        .view-bookings-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            background: #ff0000;
            color: #fff;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
            white-space: nowrap
        }

        .btn-add-booking:hover,
        .view-bookings-btn:hover {
            background: #cc0000;
            color: #fff
        }

        .booking-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px
        }

        .booking-stat {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px
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
            flex-shrink: 0
        }

        .stat-label {
            color: #777;
            font-size: 13px;
            margin-bottom: 5px
        }

        .stat-value {
            font-size: 23px;
            font-weight: 700;
            line-height: 1.2
        }

        .booking-toolbar {
            background: #fff;
            padding: 18px;
            border: 1px solid #eee;
            border-radius: 12px;
            margin-bottom: 22px
        }

        .booking-search {
            position: relative;
            max-width: 460px
        }

        .booking-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888
        }

        .booking-search input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            outline: none;
            background: #fff;
            min-height: 44px
        }

        .booking-search input:focus {
            border-color: #ff0000
        }

        .booking-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden
        }

        .booking-responsive {
            overflow-x: auto
        }

        .management-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px
        }

        .management-table thead {
            background: #fff5f5
        }

        .management-table th {
            padding: 16px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            font-weight: 700;
            border-bottom: 1px solid #eee;
            white-space: nowrap
        }

        .management-table td {
            padding: 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            vertical-align: middle
        }

        .management-table tbody tr:last-child td {
            border-bottom: none
        }

        .restaurant-name {
            font-weight: 700;
            color: #222;
            margin-bottom: 4px
        }

        .restaurant-subtitle {
            color: #888;
            font-size: 12px
        }

        .count-badge {
            display: inline-block;
            min-width: 38px;
            text-align: center;
            padding: 7px 11px;
            border-radius: 6px;
            background: #fff0f0;
            color: #d60000;
            font-size: 12px;
            font-weight: 700
        }

        .count-muted {
            display: inline-block;
            min-width: 38px;
            text-align: center;
            padding: 7px 11px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #666;
            font-size: 12px;
            font-weight: 700
        }

        .booking-counts {
            display: flex;
            gap: 7px;
            flex-wrap: wrap
        }

        .booking-counts span {
            font-size: 11px;
            color: #555;
            background: #f7f7f9;
            padding: 6px 8px;
            border-radius: 6px
        }

        .booking-counts strong {
            color: #222
        }

        .view-bookings-btn {
            padding: 9px 13px;
            font-size: 12px
        }

        .empty-bookings {
            padding: 45px 20px;
            text-align: center;
            color: #777
        }

        .empty-bookings i {
            font-size: 38px;
            color: #ff0000;
            margin-bottom: 15px
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 18px 20px;
            border-top: 1px solid #eee;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-info {
            font-size: 13px;
            color: #777;
        }

        .pagination-info strong {
            color: #222;
            font-weight: 700;
        }

        .pagination-links .pagination {
            margin: 0;
            gap: 5px;
        }

        .pagination-links .page-link {
            border: 1px solid #e5e5e5;
            color: #333;
            background: #fff;
            border-radius: 7px !important;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: none;
        }

        .pagination-links .page-link:hover {
            background: #fff0f0;
            border-color: #ff0000;
            color: #ff0000;
        }

        .pagination-links .page-item.active .page-link {
            background: #ff0000;
            border-color: #ff0000;
            color: #fff;
        }

        .pagination-links .page-item.disabled .page-link {
            background: #f8f8f8;
            border-color: #eee;
            color: #aaa;
        }

        @media (max-width: 650px) {
            .pagination-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }

            .pagination-links {
                width: 100%;
                overflow-x: auto;
            }
        }

        @media(max-width:900px) {
            .booking-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:650px) {
            .booking-page {
                padding: 14px
            }

            .booking-stats {
                grid-template-columns: 1fr
            }

            .booking-header {
                align-items: flex-start
            }

            .booking-header .btn-add-booking {
                width: 100%
            }

            .booking-search {
                max-width: 100%
            }
        }
    </style>

    <div class="booking-page">
        <div class="booking-header">
            <div>
                <h2>Restaurant Bookings</h2>
                <p>Select a restaurant to view and manage its table bookings.</p>
            </div>
            <a href="{{ route('admin.restaurant-bookings.create') }}" class="btn-add-booking">
                <i class="fas fa-plus"></i> Add New Booking
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="booking-stats">
            <div class="booking-stat">
                <div class="stat-icon"><i class="fas fa-store"></i></div>
                <div>
                    <div class="stat-label">Total Restaurants</div>
                    <div class="stat-value">{{ $totalRestaurants }}</div>
                </div>
            </div>
            <div class="booking-stat">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-value">{{ $totalBookings }}</div>
                </div>
            </div>
            <div class="booking-stat">
                <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <div class="stat-label">Restaurants With Bookings</div>
                    <div class="stat-value">{{ $restaurantsWithBookings }}</div>
                </div>
            </div>
        </div>

        <div class="booking-toolbar">
            <form action="{{ route('admin.restaurant-bookings.index') }}" method="GET">
                <div class="booking-search">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search restaurant name...">
                </div>
            </form>
        </div>

        <div class="booking-card">
            <div class="booking-responsive">
                <table class="management-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Restaurant Details</th>
                            <th>Total Bookings</th>
                            <th>Pending</th>
                            <th>Confirmed</th>
                            <th>Cancelled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurants->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="restaurant-name">{{ $restaurant->name }}</div>
                                    <div class="restaurant-subtitle">Restaurant ID: #{{ $restaurant->id }}</div>
                                </td>
                                <td>
                                    @if($restaurant->bookings_count > 0)
                                        <span class="count-badge">{{ $restaurant->bookings_count }}</span>
                                    @else
                                        <span class="count-muted">0</span>
                                    @endif
                                </td>
                                <td><span class="count-muted">{{ $restaurant->pending_bookings_count }}</span></td>
                                <td><span class="count-muted">{{ $restaurant->confirmed_bookings_count }}</span></td>
                                <td><span class="count-muted">{{ $restaurant->cancelled_bookings_count }}</span></td>
                                <td>
                                    <a href="{{ route('admin.restaurant-bookings.restaurant', $restaurant->id) }}"
                                        class="view-bookings-btn">
                                        <i class="fas fa-calendar-check"></i> View Bookings
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-bookings">
                                        <i class="fas fa-store"></i>
                                        <h3>No Restaurants Found</h3>
                                        <p>No restaurants match your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($restaurants->hasPages())
                <div class="pagination-wrapper">

                    <div class="pagination-info">
                        Showing
                        <strong>{{ $restaurants->firstItem() }}</strong>
                        to
                        <strong>{{ $restaurants->lastItem() }}</strong>
                        of
                        <strong>{{ $restaurants->total() }}</strong>
                        restaurants
                    </div>

                    <div class="pagination-links">
                        {{ $restaurants->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const bookingPage = document.querySelector('.booking-page');
            if (!sidebar || !bookingPage) return;

            function syncBookingPageWithSidebar() {
                if (window.innerWidth <= 991) {
                    bookingPage.style.setProperty('margin-left', '0px', 'important');
                    bookingPage.style.setProperty('width', '100%', 'important');
                    return;
                }
                const sidebarWidth = Math.round(sidebar.getBoundingClientRect().width);
                bookingPage.style.setProperty('margin-left', sidebarWidth + 'px', 'important');
                bookingPage.style.setProperty('width', 'calc(100% - ' + sidebarWidth + 'px)', 'important');
            }

            syncBookingPageWithSidebar();
            if (typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(syncBookingPageWithSidebar);
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