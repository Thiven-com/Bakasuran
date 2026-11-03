
@extends('layout.mainlayout')

@section('content')

<div class="booking-hours-page" style="margin-top:70px;">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="mb-1">Booking Hours</h3>
                <p class="text-muted mb-0">
                    Manage restaurant reservation timings for each day.
                </p>
            </div>

            <a href="{{ route('admin.booking-hours.create') }}"
               class="btn btn-danger px-4">
                <i class="fas fa-plus me-1"></i> Add Booking Hours
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- STATISTICS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Restaurants With Schedules</p>
                                <h3 class="mb-0">{{ $restaurants->total() }}</h3>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-utensils text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Schedule Days</p>
                        <h3 class="mb-0">7</h3>
                        <small class="text-muted">Sunday to Saturday</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Schedule Type</p>
                        <h5 class="mb-0">Weekly Schedule</h5>
                        <small class="text-muted">Restaurant-wise timings</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- RESTAURANT SCHEDULE TABLE --}}
        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Restaurant Booking Schedules</h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table align-middle table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Restaurant</th>
                                <th>Schedule Days</th>
                                <th>Opening Days</th>
                                <th>Closed Days</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($restaurants as $restaurant)

                                @php
                                    $hours = $restaurant->bookingHours;
                                    $openDays = $hours->where('is_closed', false)->count();
                                    $closedDays = $hours->where('is_closed', true)->count();
                                @endphp

                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($restaurants->currentPage() - 1) * $restaurants->perPage() }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $restaurant->name }}
                                        </div>
                                        <small class="text-muted">
                                            Restaurant ID: {{ $restaurant->id }}
                                        </small>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $hours->count() }} / 7 Days
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $openDays }} Open
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-danger-subtle text-danger">
                                            {{ $closedDays }} Closed
                                        </span>
                                    </td>

                                    <td class="text-end">

                                        <a href="{{ route('admin.booking-hours.edit', $restaurant->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Schedule">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.booking-hours.destroy', $restaurant->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this restaurant booking schedule?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Schedule">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-clock fs-1 text-muted mb-3"></i>
                                        <h5>No Booking Hours Found</h5>
                                        <p class="text-muted">
                                            Create a weekly schedule for your restaurant.
                                        </p>

                                        <a href="{{ route('admin.booking-hours.create') }}"
                                           class="btn btn-danger">
                                            <i class="fas fa-plus me-1"></i>
                                            Add Booking Hours
                                        </a>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                <div class="mt-3">
                    {{ $restaurants->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingHoursLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-hours-page');

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

    syncBookingHoursLayout();

    window.addEventListener('load', syncBookingHoursLayout);
    window.addEventListener('resize', syncBookingHoursLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingHoursLayout, 100);
        setTimeout(syncBookingHoursLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingHoursLayout);
        observer.observe(sidebar);
    }

});
</script>

@endsection
