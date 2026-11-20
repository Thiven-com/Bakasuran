@extends('layout.restaurantpanelmainlayout')

@section('title', 'Table Management')

@section('content')

    <style>
        .table-page {
            padding: 24px;
            margin-top: 50px;
            min-height: 100vh;
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

        .management-table th,
        .management-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .management-table th {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
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

        .seating-badge,
        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .seating-badge {
            background: #fff0f0;
            color: #d60000;
            text-transform: capitalize;
        }

        .status-active {
            color: #16803d;
            background: #e8f7ed;
        }

        .status-inactive {
            color: #c62828;
            background: #fff0f0;
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
            padding: 18px 20px;
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
        }
    </style>
    <div class="page-wrapper">

        <div class="table-page">

            <div class="table-header">
                <div>
                    <h2>Table Management</h2>
                    <p>Manage your restaurant tables and seating capacity.</p>
                </div>


                <a href="{{ route('restaurant.tables.create') }}" class="btn btn-danger">
                    <i class="ti ti-plus me-1"></i>
                    Add New Table
                </a>
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
                                        <a href="{{ route('restaurant.tables.edit', $table->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('restaurant.tables.destroy', $table->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this table?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-tables">
                                            <i class="fas fa-chair"></i>
                                            <h3>No Tables Found</h3>
                                            <p>You have not added any restaurant tables yet.</p>
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
                        Showing
                        {{ $tables->firstItem() }} to
                        {{ $tables->lastItem() }} of
                        {{ $tables->total() }} tables

                        {{ $tables->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>

        </div>
    </div>

@endsection