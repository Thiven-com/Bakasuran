
@extends('layout.mainlayout')

@section('content')

<div class="booking-settings-page">

    <style>
        .booking-settings-page {
            margin-top: 70px;
            padding: 20px;
            background: #f7f7f9;
            min-height: 100vh;
        }

        .settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 22px;
        }

        .settings-header h4 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .settings-header p {
            color: #777;
            margin: 0;
        }

        .settings-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
        }

        .settings-stat {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .settings-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0f0;
            color: #ff0000;
            font-size: 22px;
        }

        .settings-stat h4 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }

        .settings-stat p {
            color: #888;
            margin: 3px 0 0;
            font-size: 13px;
        }

        .settings-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .settings-toolbar .form-control,
        .settings-toolbar .form-select {
            min-height: 42px;
            border-radius: 7px;
        }

        .settings-table {
            width: 100%;
            border-collapse: collapse;
        }

        .settings-table th {
            background: #f8f8f8;
            color: #555;
            font-size: 12px;
            font-weight: 700;
            padding: 14px;
            white-space: nowrap;
        }

        .settings-table td {
            padding: 15px 14px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
        }

        .restaurant-name {
            font-weight: 700;
            color: #333;
        }

        .restaurant-sub {
            color: #999;
            font-size: 11px;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-enabled {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-disabled {
            background: #f8d7da;
            color: #842029;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 6px;
            margin-right: 4px;
            text-decoration: none;
            cursor: pointer;
        }

        .edit-btn {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .delete-btn {
            background: #ffe7e7;
            color: #dc3545;
        }

        .btn-red {
            background: #ff0000;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-red:hover {
            background: #d90000;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 45px 15px;
            color: #888;
        }

        .empty-state i {
            font-size: 40px;
            color: #ccc;
            display: block;
            margin-bottom: 12px;
        }

        @media(max-width: 767px) {
            .booking-settings-page {
                padding: 12px;
            }

            .settings-card {
                padding: 14px;
            }
        }
    </style>

    {{-- Header --}}
    <div class="settings-header">
        <div>
            <h4>Booking Settings</h4>
            <p>Manage reservation settings for all restaurants.</p>
        </div>

        <a href="{{ route('admin.booking-settings.create') }}"
           class="btn-red">
            <i class="ti ti-plus me-1"></i>
            Add Booking Settings
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="row mb-3">

        <div class="col-md-4 mb-3">
            <div class="settings-card settings-stat">
                <div class="settings-stat-icon">
                    <i class="ti ti-settings"></i>
                </div>
                <div>
                    <h4>{{ $totalSettings }}</h4>
                    <p>Total Configured Restaurants</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="settings-card settings-stat">
                <div class="settings-stat-icon"
                     style="background:#e7f7ed;color:#198754;">
                    <i class="ti ti-check"></i>
                </div>
                <div>
                    <h4>{{ $enabledSettings }}</h4>
                    <p>Reservations Enabled</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="settings-card settings-stat">
                <div class="settings-stat-icon"
                     style="background:#fff3cd;color:#856404;">
                    <i class="ti ti-ban"></i>
                </div>
                <div>
                    <h4>{{ $disabledSettings }}</h4>
                    <p>Reservations Disabled</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Settings Table --}}
    <div class="settings-card">

        <div class="settings-toolbar">

            <form method="GET"
                  action="{{ route('admin.booking-settings.index') }}"
                  class="d-flex gap-2 flex-wrap">

                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search restaurant..."
                       value="{{ request('search') }}"
                       style="min-width:220px;">

                <select name="status" class="form-select"
                        style="min-width:160px;">

                    <option value="">All Status</option>

                    <option value="enabled"
                        {{ request('status') === 'enabled' ? 'selected' : '' }}>
                        Enabled
                    </option>

                    <option value="disabled"
                        {{ request('status') === 'disabled' ? 'selected' : '' }}>
                        Disabled
                    </option>

                </select>

                <button type="submit" class="btn btn-dark">
                    <i class="ti ti-search"></i> Search
                </button>

                <a href="{{ route('admin.booking-settings.index') }}"
                   class="btn btn-light">
                    Reset
                </a>

            </form>

        </div>

        <div class="table-responsive">

            <table class="settings-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Restaurant</th>
                        <th>Slot Duration</th>
                        <th>Advance Days</th>
                        <th>Guest Limits</th>
                        <th>Reservation Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($settings as $setting)

                        <tr>

                            <td>
                                {{ $settings->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="restaurant-name">
                                    {{ $setting->restaurant->name ?? 'Restaurant Deleted' }}
                                </div>

                                <div class="restaurant-sub">
                                    Restaurant ID: {{ $setting->restaurant_id }}
                                </div>
                            </td>

                            <td>
                                {{ $setting->slot_duration }} Minutes
                            </td>

                            <td>
                                {{ $setting->max_advance_days }} Days
                            </td>

                            <td>
                                {{ $setting->min_guests }}
                                -
                                {{ $setting->max_guests }} Guests
                            </td>

                            <td>
                                @if($setting->is_booking_enabled)
                                    <span class="status-badge status-enabled">
                                        Enabled
                                    </span>
                                @else
                                    <span class="status-badge status-disabled">
                                        Disabled
                                    </span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.booking-settings.edit', $setting->id) }}"
                                   class="action-btn edit-btn"
                                   title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <form action="{{ route('admin.booking-settings.destroy', $setting->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete these booking settings?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="action-btn delete-btn"
                                            title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>

                                </form>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="ti ti-settings"></i>
                                    <h5>No Booking Settings Found</h5>
                                    <p>
                                        Add booking settings for your first restaurant.
                                    </p>

                                    <a href="{{ route('admin.booking-settings.create') }}"
                                       class="btn-red">
                                        Add Booking Settings
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $settings->links() }}
        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingSettingsLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-settings-page');

        if (!sidebar || !page) return;

        if (window.innerWidth <= 991) {
            page.style.marginLeft = '0';
            page.style.width = '100%';
        } else {
            const sidebarWidth = sidebar.getBoundingClientRect().width;

            page.style.marginLeft = sidebarWidth + 'px';
            page.style.width = 'calc(100% - ' + sidebarWidth + 'px)';
        }
    }

    syncBookingSettingsLayout();

    window.addEventListener('load', syncBookingSettingsLayout);
    window.addEventListener('resize', syncBookingSettingsLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingSettingsLayout, 100);
        setTimeout(syncBookingSettingsLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingSettingsLayout);
        observer.observe(sidebar);
    }

});
</script>

@endsection
