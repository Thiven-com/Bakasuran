
@extends('layout.mainlayout')

@section('title', 'Manage Restaurant Tables')

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
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-add-table:hover {
        background: #cc0000;
        color: #fff;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        color: #333;
        text-decoration: none;
        margin-bottom: 18px;
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

        .table-header .btn-add-table {
            width: 100%;
            justify-content: center;
        }
    }


    .pagination-wrapper {
    padding: 18px 20px;
    border-top: 1px solid #eee;
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
}

.custom-pagination .pagination {
    margin: 0;
    gap: 5px;
}

.custom-pagination .page-link {
    border: 1px solid #e5e5e5;
    color: #333;
    border-radius: 7px !important;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 600;
    background: #fff;
    box-shadow: none;
}

.custom-pagination .page-link:hover {
    background: #fff0f0;
    border-color: #ff0000;
    color: #ff0000;
}

.custom-pagination .page-item.active .page-link {
    background: #ff0000;
    border-color: #ff0000;
    color: #fff;
}

.custom-pagination .page-item.disabled .page-link {
    background: #f8f8f8;
    color: #aaa;
    border-color: #eee;
}

@media (max-width: 650px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }

    .custom-pagination {
        width: 100%;
        overflow-x: auto;
    }
}
</style>

<div class="table-page">

    <a href="{{ route('admin.restaurant-tables.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i>
        Back to Restaurants
    </a>

    <div class="table-header">
        <div>
            <h2>{{ $restaurant->name }} - Tables</h2>
            <p>Manage all tables belonging to this restaurant.</p>
        </div>

        <a
            href="{{ route('admin.restaurant-tables.create', ['restaurant_id' => $restaurant->id]) }}"
            class="btn-add-table"
        >
            <i class="fas fa-plus"></i>
            Add New Table
        </a>
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
            <div class="stat-icon"><i class="fas fa-chair"></i></div>
            <div>
                <div class="stat-label">Total Tables</div>
                <div class="stat-value">{{ $totalTables }}</div>
            </div>
        </div>

        <div class="table-stat">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-label">Active Tables</div>
                <div class="stat-value">{{ $activeTables }}</div>
            </div>
        </div>

        <div class="table-stat">
            <div class="stat-icon"><i class="fas fa-ban"></i></div>
            <div>
                <div class="stat-label">Inactive Tables</div>
                <div class="stat-value">{{ $inactiveTables }}</div>
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

    {{-- Table Listing --}}
    <div class="table-card">

        <div class="table-responsive">
            <table class="management-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table Details</th>
                        <th>Seating Type</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($tables as $table)

                        <tr>
                            <td>{{ $tables->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="table-name">
                                    {{ $table->table_name }}
                                </div>
                                <div class="table-number">
                                    Table No: {{ $table->table_number }}
                                </div>
                            </td>

                            <td>
                                <span class="seating-badge">
                                    {{ ucfirst($table->seating_type ?? 'indoor') }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $table->seating_capacity }}</strong>
                                <small>Guests</small>
                            </td>

                            <td>
                                @if($table->status == 1)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="table-actions">

                                    <a
                                        href="{{ route('admin.restaurant-tables.edit', $table->id) }}"
                                        class="table-action"
                                        title="Edit Table"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.restaurant-tables.destroy', $table->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this table?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="table-action delete"
                                            title="Delete Table"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="empty-tables">
                                    <i class="fas fa-chair"></i>
                                    <h3>No Tables Found</h3>
                                    <p>Add the first table for {{ $restaurant->name }}.</p>

                                    <a
                                        href="{{ route('admin.restaurant-tables.create', ['restaurant_id' => $restaurant->id]) }}"
                                        class="btn-add-table"
                                    >
                                        <i class="fas fa-plus"></i>
                                        Add First Table
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if($tables->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Showing
            <strong>{{ $tables->firstItem() }}</strong>
            to
            <strong>{{ $tables->lastItem() }}</strong>
            of
            <strong>{{ $tables->total() }}</strong>
            tables
        </div>

        <div class="custom-pagination">
            {{ $tables->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
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
