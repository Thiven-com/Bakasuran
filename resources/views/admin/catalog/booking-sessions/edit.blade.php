
@extends('layout.mainlayout')

@section('content')

<div class="booking-sessions-page" style="margin-top:70px;">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="mb-1">Edit Booking Session</h3>
                <p class="text-muted mb-0">
                    Update restaurant reservation session details.
                </p>
            </div>

            <a href="{{ route('admin.booking-sessions.index') }}"
               class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

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

        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Session Details</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.booking-sessions.update', $bookingSession->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- RESTAURANT --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Select Restaurant <span class="text-danger">*</span>
                            </label>

                            <select name="restaurant_id"
                                    class="form-select @error('restaurant_id') is-invalid @enderror"
                                    required>

                                <option value="">Choose Restaurant</option>

                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}"
                                        {{ old('restaurant_id', $bookingSession->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('restaurant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SESSION NAME --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Session Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="session_name"
                                   class="form-control @error('session_name') is-invalid @enderror"
                                   value="{{ old('session_name', $bookingSession->session_name) }}"
                                   maxlength="100"
                                   required>

                            @error('session_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DAY --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Select Day <span class="text-danger">*</span>
                            </label>

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

                            <select name="day_of_week"
                                    class="form-select @error('day_of_week') is-invalid @enderror"
                                    required>

                                @foreach($days as $key => $day)
                                    <option value="{{ $key }}"
                                        {{ old('day_of_week', $bookingSession->day_of_week) == $key ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach

                            </select>

                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- START TIME --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Session Start Time <span class="text-danger">*</span>
                            </label>

                            <input type="time"
                                   name="start_time"
                                   class="form-control @error('start_time') is-invalid @enderror"
                                   value="{{ old('start_time', \Carbon\Carbon::parse($bookingSession->start_time)->format('H:i')) }}"
                                   required>

                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- END TIME --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Session End Time <span class="text-danger">*</span>
                            </label>

                            <input type="time"
                                   name="end_time"
                                   class="form-control @error('end_time') is-invalid @enderror"
                                   value="{{ old('end_time', \Carbon\Carbon::parse($bookingSession->end_time)->format('H:i')) }}"
                                   required>

                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ACTIVE STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block">
                                Session Status
                            </label>

                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="is_active" value="0">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="is_active"
                                       {{ old('is_active', $bookingSession->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>

                                <label class="form-check-label" for="is_active">
                                    Active Session
                                </label>
                            </div>

                            <small class="text-muted">
                                Inactive sessions will not be available for customer bookings.
                            </small>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('admin.booking-sessions.index') }}"
                           class="btn btn-light border px-4">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-save me-1"></i>
                            Update Session
                        </button>

                    </div>

                </form>

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
