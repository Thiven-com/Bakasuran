
@extends('layout.mainlayout')

@section('content')

<style>
    .sessions-page {
        margin-top: 50px;
        padding: 25px;
        background: #f5f6fa;
        min-height: 100vh;
        transition: all .3s ease;
    }

    .sessions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .sessions-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #222;
        margin: 0;
    }

    .sessions-header p {
        color: #777;
        margin: 6px 0 0;
    }

    .btn-red {
        background: #ff0000;
        color: #fff;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        display: inline-block;
        font-weight: 600;
    }

    .btn-red:hover {
        background: #d90000;
        color: #fff;
    }

    .btn-back {
        background: #fff;
        color: #333;
        padding: 10px 18px;
        border: 1px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #fff;
        padding: 22px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }

    .stat-card h3 {
        font-size: 14px;
        color: #777;
        margin: 0 0 10px;
    }

    .stat-card h2 {
        font-size: 27px;
        font-weight: 700;
        margin: 0;
        color: #222;
    }

    .sessions-table-card {
        background: #fff;
        padding: 22px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }

    .sessions-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .sessions-search {
        display: flex;
        gap: 8px;
        max-width: 420px;
        width: 100%;
    }

    .sessions-search input {
        flex: 1;
        min-width: 0;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 12px;
    }

    .search-btn {
        background: #ff0000;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 16px;
    }

    .sessions-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sessions-table th {
        background: #f8f8f8;
        color: #555;
        font-size: 13px;
        padding: 14px;
        text-align: left;
        white-space: nowrap;
    }

    .sessions-table td {
        padding: 14px;
        border-bottom: 1px solid #eee;
        color: #444;
        vertical-align: middle;
    }

    .day-heading td {
        background: #f5f6fa;
        padding: 12px 14px;
        border-bottom: 1px solid #e5e5e5;
    }

    .day-heading strong {
        color: #333;
        font-size: 14px;
    }

    .day-heading i {
        color: #ff0000;
        margin-right: 7px;
    }

    .status-active {
        color: #168447;
        background: #e8f7ee;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-inactive {
        color: #b45309;
        background: #fff4df;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .action-btn {
        border: 1px solid #ddd;
        background: #fff;
        padding: 7px 10px;
        border-radius: 5px;
        color: #444;
        text-decoration: none;
        display: inline-block;
        margin-right: 4px;
        cursor: pointer;
    }

    .action-btn:hover {
        background: #f5f5f5;
    }

    .action-delete {
        color: #e00000;
    }

    .empty-state {
        text-align: center;
        padding: 40px 15px;
        color: #777;
    }

    @media (max-width: 767px) {
        .sessions-page {
            padding: 15px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .sessions-table-card {
            padding: 12px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }




.sessions-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    padding: 18px 5px 5px;
    margin-top: 15px;
    border-top: 1px solid #eee;
}

.pagination-info {
    font-size: 13px;
    color: #777;
}

.pagination-info strong {
    color: #222;
}

.pagination-links .pagination {
    margin-bottom: 0;
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
    color: #ff0000;
    border-color: #ff0000;
}

.pagination-links .page-item.active .page-link {
    background: #ff0000;
    border-color: #ff0000;
    color: #fff;
}

.pagination-links .page-item.disabled .page-link {
    color: #aaa;
    background: #f8f8f8;
    border-color: #eee;
}

@media (max-width: 650px) {
    .sessions-pagination {
        flex-direction: column;
        align-items: flex-start;
    }

    .pagination-links {
        width: 100%;
        overflow-x: auto;
    }
}
    

</style>

<div class="sessions-page">

    {{-- PAGE HEADER --}}
    <div class="sessions-header">
        <div>
            <h2>{{ $restaurant->name }} - Booking Sessions</h2>
            <p>Manage booking sessions for this restaurant.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a href="{{ route('admin.booking-sessions.index') }}"
               class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Sessions
            </a>

            <a href="{{ route('admin.booking-sessions.create', [
                'restaurant_id' => $restaurant->id
            ]) }}"
               class="btn-red">
                <i class="fas fa-plus"></i>
                Add New Session
            </a>

        </div>
    </div>

    {{-- STATISTICS --}}
    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Sessions</h3>
            <h2>{{ $totalSessions }}</h2>
        </div>

        <div class="stat-card">
            <h3>Active Sessions</h3>
            <h2>{{ $activeSessions }}</h2>
        </div>

        <div class="stat-card">
            <h3>Inactive Sessions</h3>
            <h2>{{ $inactiveSessions }}</h2>
        </div>

    </div>

    {{-- SESSION TABLE --}}
    <div class="sessions-table-card">

        <div class="sessions-toolbar">

            <h5 class="mb-0">Sessions List</h5>

            <form method="GET"
                  action="{{ route('admin.booking-sessions.restaurant', $restaurant->id) }}"
                  class="sessions-search">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search session name..."
                >

                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>

            </form>

        </div>

        <div class="table-responsive">

            <table class="sessions-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Session Name</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $days = [
                            0 => 'Sunday',
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                        ];

                        $groupedSessions = $sessions->getCollection()
                            ->groupBy('day_of_week');

                        $rowNumber = $sessions->firstItem() ?? 1;
                    @endphp

                    @forelse($groupedSessions as $dayNumber => $daySessions)

                        {{-- DAY HEADING --}}
                        <tr class="day-heading">
                            <td colspan="7">
                                <strong>
                                    <i class="fas fa-calendar-day"></i>
                                    {{ $days[$dayNumber] ?? 'Unknown Day' }}
                                </strong>

                                <span class="text-muted ms-2">
                                    ({{ $daySessions->count() }} sessions on this page)
                                </span>
                            </td>
                        </tr>

                        {{-- DAY SESSIONS --}}
                        @foreach($daySessions as $session)

                            <tr>

                                <td>{{ $rowNumber++ }}</td>

                                <td>
                                    <strong>{{ $session->session_name }}</strong>
                                </td>

                                <td>
                                    {{ $days[$session->day_of_week] ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                </td>

                                <td>
                                    @if($session->is_active)
                                        <span class="status-active">
                                            Active
                                        </span>
                                    @else
                                        <span class="status-inactive">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.booking-sessions.edit', $session->id) }}"
                                       class="action-btn"
                                       title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('admin.booking-sessions.destroy', $session->id) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this session?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            title="Delete"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="empty-state">

                                    <i class="fas fa-calendar-times fa-2x mb-3"></i>

                                    <h5>No Sessions Found</h5>

                                    <p>
                                        No booking sessions are available for this restaurant.
                                    </p>

                                    <a href="{{ route('admin.booking-sessions.create', [
                                        'restaurant_id' => $restaurant->id
                                    ]) }}"
                                       class="btn-red">

                                        <i class="fas fa-plus"></i>
                                        Add First Session

                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($sessions->hasPages())
    <div class="sessions-pagination">

        <div class="pagination-info">
            Showing
            <strong>{{ $sessions->firstItem() }}</strong>
            to
            <strong>{{ $sessions->lastItem() }}</strong>
            of
            <strong>{{ $sessions->total() }}</strong>
            sessions
        </div>

        <div class="pagination-links">
            {{ $sessions->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endif

    </div>

</div>

{{-- SIDEBAR LAYOUT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingSessionsLayout() {

        const sidebar = document.getElementById('sidebar');

        const pages = document.querySelectorAll(
            '.booking-page, .sessions-page'
        );

        pages.forEach(function (page) {

            if (window.innerWidth <= 991) {

                page.style.setProperty('margin-left', '0', 'important');
                page.style.setProperty('width', '100%', 'important');

            } else if (sidebar) {

                const sidebarWidth = sidebar.getBoundingClientRect().width;

                page.style.setProperty(
                    'margin-left',
                    sidebarWidth + 'px',
                    'important'
                );

                page.style.setProperty(
                    'width',
                    'calc(100% - ' + sidebarWidth + 'px)',
                    'important'
                );

            }

        });

    }

    syncBookingSessionsLayout();

    window.addEventListener('load', syncBookingSessionsLayout);
    window.addEventListener('resize', syncBookingSessionsLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingSessionsLayout, 100);
        setTimeout(syncBookingSessionsLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {

        const observer = new ResizeObserver(syncBookingSessionsLayout);

        observer.observe(sidebar);

    }

});
</script>

@endsection
