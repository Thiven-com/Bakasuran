@extends('layout.mainlayout')

@section('title', 'Restaurant Table Management')

@section('content')

    <style>
        .table-page {
            padding: 24px;
            margin-top: 50px;
            min-height: 100vh;
            box-sizing: border-box;
            background: #f7f7f9;
            color: #171717;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .table-header h2 {
            font-size: 25px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .table-header p {
            color: #777;
            margin: 0;
            font-size: 14px;
        }

        .table-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .table-stat {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: .2s;
        }

        .table-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0, 0, 0, .05);
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

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            background: #fff;
            padding: 18px;
            border: 1px solid #eee;
            border-radius: 12px;
            margin-bottom: 22px;
        }

        .table-search {
            position: relative;
            width: 100%;
        }

        .table-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .table-search input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            outline: none;
            background: #fff;
            box-sizing: border-box;
            font-size: 14px;
        }

        .table-search input:focus {
            border-color: #ff0000;
            box-shadow: 0 0 0 3px rgba(255, 0, 0, .06);
        }

        .table-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .management-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
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

        .management-table tbody tr:hover {
            background: #fffafa;
        }

        .management-table tbody tr:last-child td {
            border-bottom: none;
        }

        .restaurant-name {
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .restaurant-subtitle {
            color: #888;
            font-size: 12px;
        }

        .count-badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 6px;
            background: #fff0f0;
            color: #d60000;
            font-size: 12px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-active {
            color: #16803d;
            background: #e8f7ed;
        }

        .status-inactive {
            color: #c62828;
            background: #fff0f0;
        }

        .table-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            min-width: 280px;
            max-width: 440px;
        }

        .manage-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 12px;
            border-radius: 7px;
            background: #ff0000;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            transition: .2s;
            white-space: nowrap;
            border: none;
        }

        .manage-btn:hover {
            background: #cc0000;
            color: #fff;
            transform: translateY(-1px);
        }

        .booking-btn {
            background: #222;
        }

        .booking-btn:hover {
            background: #444;
        }

        .session-btn {
            background: #1769aa;
        }

        .session-btn:hover {
            background: #12548a;
        }

        .hours-btn {
            background: #16803d;
        }

        .hours-btn:hover {
            background: #11632e;
        }

        .settings-btn {
            background: #7542a8;
        }

        .settings-btn:hover {
            background: #593183;
        }

        .empty-tables {
            padding: 55px 20px;
            text-align: center;
            color: #777;
        }

        .empty-tables i {
            font-size: 42px;
            color: #ff0000;
            margin-bottom: 15px;
        }

        .empty-tables h3 {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .empty-tables p {
            margin: 0;
            font-size: 13px;
        }

        .pagination-wrapper {
            padding: 16px;
            border-top: 1px solid #eee;
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 8px;
        }

        @media (max-width: 900px) {
            .table-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .table-page {
                padding: 14px;
            }

            .table-stats {
                grid-template-columns: 1fr;
            }

            .table-header {
                align-items: flex-start;
            }

            .table-toolbar {
                padding: 13px;
            }

            .management-table {
                min-width: 1050px;
            }
        }


        /* Pagination */
        .pagination-wrapper {
            padding: 18px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            background: #fff;
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
    </style>

    <div class="table-page">

        {{-- Page Header --}}
        <div class="table-header">
            <div>
                <h2>Restaurant Table Management</h2>
                <p>Manage restaurant tables, reservations, booking sessions and settings.</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        {{-- Statistics --}}
        <div class="table-stats">

            <div class="table-stat">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <div class="stat-label">Total Restaurants</div>
                    <div class="stat-value">{{ $totalRestaurants }}</div>
                </div>
            </div>

            <div class="table-stat">
                <div class="stat-icon">
                    <i class="fas fa-chair"></i>
                </div>
                <div>
                    <div class="stat-label">Total Tables</div>
                    <div class="stat-value">{{ $totalTables }}</div>
                </div>
            </div>

            <div class="table-stat">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-label">Active Seating Capacity</div>
                    <div class="stat-value">{{ $totalCapacity }}</div>
                </div>
            </div>

        </div>

        {{-- Search --}}
        <div class="table-toolbar">
            <form action="{{ route('admin.restaurant-tables.index') }}" method="GET" class="w-100">

                <div class="table-search">
                    <i class="fas fa-search"></i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search restaurant name...">
                </div>

            </form>
        </div>

        {{-- Restaurant Listing --}}
        <div class="table-card">

            <div class="table-responsive">

                <table class="management-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Restaurant Details</th>
                            <th>Total Tables</th>
                            <th>Active Tables</th>
                            <th>Inactive Tables</th>
                            <th>Management Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($restaurants as $restaurant)

                            <tr>

                                <td>
                                    {{ $restaurants->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="restaurant-name">
                                        {{ $restaurant->name }}
                                    </div>

                                    <div class="restaurant-subtitle">
                                        Restaurant ID: #{{ $restaurant->id }}
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $restaurant->tables_count }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge status-active">
                                        {{ $restaurant->tables->where('status', 1)->count() }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge status-inactive">
                                        {{ $restaurant->tables->where('status', 0)->count() }}
                                    </span>
                                </td>

                                {{-- Management Actions --}}
                                <td>
                                    <div class="table-actions">

                                        {{-- Manage Tables --}}
                                        <a href="{{ route('admin.restaurant-tables.manage', $restaurant->id) }}"
                                            class="manage-btn">
                                            <i class="fas fa-chair"></i>
                                            Manage Tables
                                        </a>

                                        {{-- View Bookings --}}
                                        <a href="{{ route('admin.restaurant-bookings.restaurant', $restaurant->id) }}"
                                            class="manage-btn booking-btn">
                                            <i class="fas fa-calendar-check"></i>
                                            View Bookings
                                        </a>

                                        {{-- Booking Sessions --}}
                                        <a href="{{ route('admin.booking-sessions.restaurant', $restaurant->id) }}"
                                            class="manage-btn session-btn">
                                            <i class="fas fa-clock"></i>
                                            Booking Sessions
                                        </a>

                                        {{-- Booking Hours --}}
                                        @if($restaurant->booking_hours_count > 0)

                                            <a href="{{ route('admin.booking-hours.edit', $restaurant->id) }}"
                                                class="manage-btn hours-btn">
                                                <i class="fas fa-business-time"></i>
                                                Manage Hours
                                            </a>

                                        @else

                                            <a href="{{ route('admin.booking-hours.create', ['restaurant_id' => $restaurant->id]) }}"
                                                class="manage-btn hours-btn">
                                                <i class="fas fa-plus-circle"></i>
                                                Add Booking Hours
                                            </a>

                                        @endif

                                        {{-- Booking Settings --}}
                                        <a href="{{ route('admin.booking-settings.restaurant', $restaurant->id) }}"
                                            class="manage-btn settings-btn">
                                            <i class="fas fa-cog"></i>
                                            Manage Settings
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">
                                    <div class="empty-tables">
                                        <i class="fas fa-store"></i>
                                        <h3>No Restaurants Found</h3>
                                        <p>No restaurants are available to manage.</p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
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

    {{-- Sidebar Layout Adjustment --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('sidebar');
            const tablePage = document.querySelector('.table-page');

            if (!sidebar || !tablePage) return;

            function syncTablePageWithSidebar() {

                if (window.innerWidth <= 991) {
                    tablePage.style.setProperty('margin-left', '0px', 'important');
                    tablePage.style.setProperty('width', '100%', 'important');
                    return;
                }

                const sidebarWidth = Math.round(
                    sidebar.getBoundingClientRect().width
                );

                tablePage.style.setProperty(
                    'margin-left',
                    sidebarWidth + 'px',
                    'important'
                );

                tablePage.style.setProperty(
                    'width',
                    'calc(100% - ' + sidebarWidth + 'px)',
                    'important'
                );
            }

            syncTablePageWithSidebar();

            if (typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(syncTablePageWithSidebar);
                observer.observe(sidebar);
            }

            document.addEventListener('click', function () {
                setTimeout(syncTablePageWithSidebar, 100);
                setTimeout(syncTablePageWithSidebar, 300);
            });

            window.addEventListener('resize', syncTablePageWithSidebar);

        });
    </script>

@endsection