
@extends('layout.mainlayout')

@section('content')

<div class="booking-sessions-page" style="margin-top:70px;">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="mb-1">Booking Sessions</h3>
                <p class="text-muted mb-0">
                    Manage breakfast, lunch, dinner and other reservation sessions.
                </p>
            </div>

            <a href="{{ route('admin.booking-sessions.create') }}"
               class="btn btn-danger px-4">
                <i class="fas fa-plus me-1"></i> Add Booking Session
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- STATISTICS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Sessions</p>
                        <h3 class="mb-0">{{ $sessions->total() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Active Sessions</p>
                        <h3 class="mb-0 text-success">
                            {{ (clone $sessions)->where('is_active', true)->count() }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Schedule Type</p>
                        <h5 class="mb-0">Flexible Sessions</h5>
                        <small class="text-muted">Restaurant-wise timings</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- BOOKING SESSIONS TABLE --}}
        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Restaurant Booking Sessions</h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table align-middle table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Restaurant</th>
                                <th>Session Name</th>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($sessions as $session)

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
                                @endphp

                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $session->restaurant->name ?? 'Restaurant unavailable' }}
                                        </div>
                                        <small class="text-muted">
                                            Restaurant ID: {{ $session->restaurant_id }}
                                        </small>
                                    </td>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $session->session_name }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $days[$session->day_of_week] ?? 'Unknown' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                    </td>

                                    <td>
                                        @if($session->is_active)
                                            <span class="badge bg-success-subtle text-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end">

                                        <a href="{{ route('admin.booking-sessions.edit', $session->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Session">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.booking-sessions.destroy', $session->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this booking session?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Session">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-clock fs-1 text-muted mb-3"></i>
                                        <h5>No Booking Sessions Found</h5>
                                        <p class="text-muted">
                                            Create breakfast, lunch or dinner sessions for your restaurant.
                                        </p>

                                        <a href="{{ route('admin.booking-sessions.create') }}"
                                           class="btn btn-danger">
                                            <i class="fas fa-plus me-1"></i>
                                            Add Booking Session
                                        </a>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                <div class="mt-3">
                    {{ $sessions->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

{{-- SIDEBAR LAYOUT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingSessionsLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-sessions-page');

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
