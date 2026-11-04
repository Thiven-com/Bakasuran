
@extends('layout.mainlayout')

@section('title', 'Restaurant Table Management')

@section('content')

<style>
    .table-page {
        padding: 24px;
        background: #f7f7f9;
        min-height: 100vh;
        color: #171717;
        margin-top: 50px;
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

    .btn-add-table {
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

    .btn-add-table:hover {
        background: #cc0000;
        color: #fff;
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
        background: #fff;
        padding: 18px;
        border: 1px solid #eee;
        border-radius: 12px;
        margin-bottom: 22px;
    }

    .table-search {
        position: relative;
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
    }

    .table-search input:focus {
        border-color: #ff0000;
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
        min-width: 750px;
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

    .restaurant-name {
        font-weight: 700;
        color: #222;
        margin-bottom: 4px;
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
        gap: 8px;
    }

    .manage-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 13px;
        border-radius: 7px;
        background: #ff0000;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s;
    }

    .manage-btn:hover {
        background: #cc0000;
        color: #fff;
    }

    .empty-tables {
        padding: 45px 20px;
        text-align: center;
        color: #777;
    }

    .empty-tables i {
        font-size: 38px;
        color: #ff0000;
        margin-bottom: 15px;
    }

    .pagination-wrapper {
        padding: 16px;
        border-top: 1px solid #eee;
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

        .table-header .btn-add-table {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="table-page">

    <div class="table-header">
        <div>
            <h2>Restaurant Table Management</h2>
            <p>Select a restaurant to manage all its tables.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Statistics --}}
    <div class="table-stats">

        <div class="table-stat">
            <div class="stat-icon"><i class="fas fa-store"></i></div>
            <div>
                <div class="stat-label">Total Restaurants</div>
                <div class="stat-value">{{ $totalRestaurants }}</div>
            </div>
        </div>

        <div class="table-stat">
            <div class="stat-icon"><i class="fas fa-chair"></i></div>
            <div>
                <div class="stat-label">Total Tables</div>
                <div class="stat-value">{{ $totalTables }}</div>
            </div>
        </div>

        <div class="table-stat">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-label">Active Seating Capacity</div>
                <div class="stat-value">{{ $totalCapacity }}</div>
            </div>
        </div>

    </div>

    {{-- Search --}}
    <div class="table-toolbar">
        <form action="{{ route('admin.restaurant-tables.index') }}" method="GET">
            <div class="table-search">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search restaurant name..."
                >
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
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($restaurants as $restaurant)

                        <tr>
                            <td>{{ $restaurants->firstItem() + $loop->index }}</td>

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

                            <td>
                                <div class="table-actions">
                                    <a
                                        href="{{ route('admin.restaurant-tables.manage', $restaurant->id) }}"
                                        class="manage-btn"
                                    >
                                        <i class="fas fa-chair"></i>
                                        Manage Tables
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

        @if($restaurants->hasPages())
            <div class="pagination-wrapper">
                {{ $restaurants->links() }}
            </div>
        @endif

    </div>

</div>

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
