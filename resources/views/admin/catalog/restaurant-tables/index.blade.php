
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
        grid-template-columns: repeat(4, minmax(0, 1fr));
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

    .table-filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .table-search {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .table-search i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .table-search input,
    .table-filters select {
        width: 100%;
        padding: 12px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        outline: none;
        background: #fff;
    }

    .table-search input {
        padding-left: 40px;
    }

    .table-search input:focus,
    .table-filters select:focus {
        border-color: #ff0000;
    }

    .table-filter-item {
        min-width: 160px;
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
        min-width: 850px;
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

    .table-name {
        font-weight: 700;
        color: #222;
        margin-bottom: 4px;
    }

    .table-number {
        color: #888;
        font-size: 12px;
    }

    .seating-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 6px;
        background: #fff0f0;
        color: #d60000;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .capacity-value {
        font-weight: 700;
        color: #333;
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

    @media (max-width: 1100px) {
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

        .table-filter-item,
        .table-search {
            width: 100%;
            min-width: 100%;
        }
    }
</style>

<div class="table-page">

    {{-- Page Header --}}
    <div class="table-header">
        <div>
            <h2>Restaurant Table Management</h2>
            <p>Manage restaurant tables, seating types and capacity.</p>
        </div>

        <a href="{{ route('admin.restaurant-tables.create') }}"
           class="btn-add-table">
            <i class="fas fa-plus"></i>
            Add New Table
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
    <div class="table-stats">

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
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="stat-label">Active Tables</div>
                <div class="stat-value">{{ $activeTables }}</div>
            </div>
        </div>

        <div class="table-stat">
            <div class="stat-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div>
                <div class="stat-label">Inactive Tables</div>
                <div class="stat-value">{{ $inactiveTables }}</div>
            </div>
        </div>

        <div class="table-stat">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-label">Total Active Seating Capacity</div>
                <div class="stat-value">{{ $totalCapacity }}</div>
            </div>
        </div>

    </div>

    {{-- Search and Filters --}}
    <div class="table-toolbar">

        <form action="{{ route('admin.restaurant-tables.index') }}"
              method="GET">

            <div class="table-filters">

                <div class="table-search">
                    <i class="fas fa-search"></i>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search table name, number or restaurant...">
                </div>

                <div class="table-filter-item">
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

                <div class="table-filter-item">
                    <select name="seating_type">
                        <option value="">All Seating Types</option>
                        <option value="indoor" {{ request('seating_type') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                        <option value="outdoor" {{ request('seating_type') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                        <option value="family" {{ request('seating_type') == 'family' ? 'selected' : '' }}>Family</option>
                        <option value="private" {{ request('seating_type') == 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>

                <div class="table-filter-item">
                    <select name="status">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn-add-table">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a href="{{ route('admin.restaurant-tables.index') }}"
                   class="btn btn-light border">
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- Table Listing --}}
    <div class="table-card">

        <div class="table-responsive">

            <table class="management-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table Details</th>
                        <th>Restaurant</th>
                        <th>Seating Type</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($tables as $table)

                        <tr>
                            <td>
                                {{ $tables->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="table-name">
                                    {{ $table->table_name }}
                                </div>
                                <div class="table-number">
                                    Table No: {{ $table->table_number }}
                                </div>
                            </td>

                            <td>
                                {{ $table->restaurant->name ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="seating-badge">
                                    {{ ucfirst($table->seating_type ?? 'indoor') }}
                                </span>
                            </td>

                            <td>
                                <span class="capacity-value">
                                    {{ $table->seating_capacity }}
                                </span>
                                <small>Guests</small>
                            </td>

                            <td>
                                @if($table->status == 1)
                                    <span class="status-badge status-active">
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="table-actions">

                                    <a href="{{ route('admin.restaurant-tables.edit', $table->id) }}"
                                       class="table-action"
                                       title="Edit Table">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <form action="{{ route('admin.restaurant-tables.destroy', $table->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this table?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="table-action delete"
                                                title="Delete Table">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="empty-tables">
                                    <i class="fas fa-chair"></i>
                                    <h3>No Restaurant Tables Found</h3>
                                    <p>Add your first restaurant table to get started.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($tables->hasPages())
            <div class="pagination-wrapper">
                {{ $tables->links() }}
            </div>
        @endif

    </div>

</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* =========================================
           RESTAURANT TABLE PAGE - RESPONSIVE SIDEBAR SYNC
        ========================================= */

        const sidebar = document.getElementById('sidebar');
        const tablePage = document.querySelector('.table-page');

        // Stop if this page or sidebar is unavailable
        if (!sidebar || !tablePage) {
            return;
        }

        function syncTablePageWithSidebar() {

            // Mobile layout
            if (window.innerWidth <= 991) {
                tablePage.style.setProperty(
                    'margin-left',
                    '0px',
                    'important'
                );

                tablePage.style.setProperty(
                    'width',
                    '100%',
                    'important'
                );

                return;
            }

            // Read actual sidebar width
            const sidebarWidth = Math.round(
                sidebar.getBoundingClientRect().width
            );

            // Position content after sidebar
            tablePage.style.setProperty(
                'margin-left',
                sidebarWidth + 'px',
                'important'
            );

            // Keep content within available viewport
            tablePage.style.setProperty(
                'width',
                'calc(100% - ' + sidebarWidth + 'px)',
                'important'
            );
        }

        // Initial layout
        syncTablePageWithSidebar();

        // Observe sidebar width changes
        if (typeof ResizeObserver !== 'undefined') {
            const observer = new ResizeObserver(function () {
                syncTablePageWithSidebar();
            });

            observer.observe(sidebar);
        }

        // Recalculate after sidebar toggle animations
        document.addEventListener('click', function () {
            setTimeout(syncTablePageWithSidebar, 100);
            setTimeout(syncTablePageWithSidebar, 300);
        });

        // Recalculate when browser resizes
        window.addEventListener('resize', syncTablePageWithSidebar);

    });
</script>


@endsection
