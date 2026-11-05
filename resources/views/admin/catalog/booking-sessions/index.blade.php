
@extends('layout.mainlayout')

@section('title', 'Booking Sessions')

@section('content')

<style>
    .booking-page{padding:24px;background:#f7f7f9;min-height:100vh;color:#171717;margin-top:50px}
    .booking-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px}
    .booking-header h2{font-size:25px;font-weight:700;margin:0 0 6px}
    .booking-header p{color:#777;margin:0;font-size:14px}
    .btn-add,.view-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 16px;background:#ff0000;color:#fff;border:0;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;white-space:nowrap}
    .btn-add:hover,.view-btn:hover{background:#cc0000;color:#fff}
    .booking-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:24px}
    .booking-stat{background:#fff;border:1px solid #eee;border-radius:12px;padding:20px;display:flex;align-items:center;gap:14px}
    .stat-icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#fff0f0;color:#ff0000;font-size:21px}
    .stat-label{color:#777;font-size:13px;margin-bottom:5px}
    .stat-value{font-size:23px;font-weight:700}
    .booking-toolbar{background:#fff;padding:18px;border:1px solid #eee;border-radius:12px;margin-bottom:22px}
    .booking-search{position:relative;max-width:460px}
    .booking-search i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#888}
    .booking-search input{width:100%;padding:12px 12px 12px 40px;border:1px solid #e5e5e5;border-radius:8px;outline:none;min-height:44px}
    .booking-card{background:#fff;border:1px solid #eee;border-radius:12px;overflow:hidden}
    .booking-responsive{overflow-x:auto}
    .management-table{width:100%;border-collapse:collapse;min-width:700px}
    .management-table thead{background:#fff5f5}
    .management-table th{padding:16px;text-align:left;font-size:12px;text-transform:uppercase;color:#666;border-bottom:1px solid #eee}
    .management-table td{padding:16px;border-bottom:1px solid #f0f0f0;font-size:13px;vertical-align:middle}
    .restaurant-name{font-weight:700;color:#222;margin-bottom:4px}
    .restaurant-subtitle{color:#888;font-size:12px}
    .count-badge{display:inline-block;padding:7px 11px;border-radius:6px;background:#fff0f0;color:#d60000;font-weight:700}
    .count-muted{display:inline-block;padding:7px 11px;border-radius:6px;background:#f3f4f6;color:#666;font-weight:700}
    .empty-bookings{padding:45px 20px;text-align:center;color:#777}
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

@media(max-width:650px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }

    .pagination-links {
        width: 100%;
        overflow-x: auto;
    }
}
    @media(max-width:900px){.booking-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:650px){.booking-page{padding:14px}.booking-stats{grid-template-columns:1fr}.booking-header .btn-add{width:100%}}
</style>

<div class="booking-page">

    <div class="booking-header">
        <div>
            <h2>Booking Sessions</h2>
            <p>Select a restaurant to view and manage its booking sessions.</p>
        </div>

        <a href="{{ route('admin.booking-sessions.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Add New Session
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
                <div class="stat-label">Total Sessions</div>
                <div class="stat-value">{{ $totalSessions }}</div>
            </div>
        </div>

        <div class="booking-stat">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-label">Active Sessions</div>
                <div class="stat-value">{{ $activeSessions }}</div>
            </div>
        </div>
    </div>

    <div class="booking-toolbar">
        <form action="{{ route('admin.booking-sessions.index') }}" method="GET">
            <div class="booking-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search"
                    value="{{ request('search') }}"
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
                        <th>Total Sessions</th>
                        <th>Session Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($restaurants as $restaurant)
                        <tr>
                            <td>{{ $restaurants->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="restaurant-name">{{ $restaurant->name }}</div>
                                <div class="restaurant-subtitle">
                                    Restaurant ID: #{{ $restaurant->id }}
                                </div>
                            </td>

                            <td>
                                <span class="{{ $restaurant->booking_sessions_count > 0 ? 'count-badge' : 'count-muted' }}">
                                    {{ $restaurant->booking_sessions_count }}
                                </span>
                            </td>

                            <td>
                                @if($restaurant->booking_sessions_count > 0)
                                    <span class="badge bg-success-subtle text-success">Sessions Available</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">No Sessions</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.booking-sessions.restaurant', $restaurant->id) }}"
                                   class="view-btn">
                                    <i class="fas fa-calendar-check"></i>
                                    View Sessions
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-bookings">
                                    <i class="fas fa-store fs-1 text-danger"></i>
                                    <h5>No Restaurants Found</h5>
                                    <p>No restaurants match your search.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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

    function syncBookingSessionsLayout() {

        const sidebar = document.getElementById('sidebar');

        const page = document.querySelector(
            '.booking-page, .sessions-page'
        );

        if (!sidebar || !page) return;

        if (window.innerWidth <= 991) {

            page.style.setProperty(
                'margin-left',
                '0px',
                'important'
            );

            page.style.setProperty(
                'width',
                '100%',
                'important'
            );

        } else {

            const sidebarWidth = Math.round(
                sidebar.getBoundingClientRect().width
            );

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
    }

    // Initial layout
    syncBookingSessionsLayout();

    // Window resize
    window.addEventListener(
        'resize',
        syncBookingSessionsLayout
    );

    // Page load
    window.addEventListener(
        'load',
        syncBookingSessionsLayout
    );

    // Sidebar toggle
    document.addEventListener('click', function () {

        setTimeout(syncBookingSessionsLayout, 100);

        setTimeout(syncBookingSessionsLayout, 300);

    });

    // Detect sidebar width changes
    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {

        const observer = new ResizeObserver(
            syncBookingSessionsLayout
        );

        observer.observe(sidebar);
    }

});
</script>
@endsection
