
@extends('layout.mainlayout')

@section('content')

<div class="booking-hours-create-page" style="margin-top:70px;">
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="mb-1">Add Booking Hours</h3>
                <p class="text-muted mb-0">
                    Configure weekly reservation timings for a restaurant.
                </p>
            </div>

            <a href="{{ route('admin.booking-hours.index') }}"
               class="btn btn-light border">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.booking-hours.store') }}" method="POST">
            @csrf

            <div class="card border-0 shadow-sm rounded-3">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="far fa-clock me-2 text-danger"></i>
                        Weekly Restaurant Schedule
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- RESTAURANT --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Select Restaurant <span class="text-danger">*</span>
                            </label>

                            <select name="restaurant_id"
                                    class="form-select"
                                    required
                                    {{ $restaurants->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Select Restaurant</option>

                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}"
                                        {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('restaurant_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if($restaurants->isEmpty())
                                <small class="text-danger d-block mt-2">
                                    All restaurants already have booking schedules.
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- WEEKLY HOURS --}}
                    <div class="table-responsive">
                        <table class="table align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:150px;">Day</th>
                                    <th style="min-width:200px;">Opening Time</th>
                                    <th style="min-width:200px;">Closing Time</th>
                                    <th style="min-width:120px;">Closed</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($days as $day => $dayName)

                                    <tr class="booking-day-row"
                                        data-day="{{ $day }}">

                                        <td class="fw-semibold">
                                            {{ $dayName }}
                                        </td>

                                        <td>
                                            <input type="time"
                                                   name="hours[{{ $day }}][opening_time]"
                                                   class="form-control opening-time"
                                                   value="{{ old("hours.$day.opening_time", '09:00') }}"
                                                   required>
                                        </td>

                                        <td>
                                            <input type="time"
                                                   name="hours[{{ $day }}][closing_time]"
                                                   class="form-control closing-time"
                                                   value="{{ old("hours.$day.closing_time", '22:00') }}"
                                                   required>
                                        </td>

                                        <td>
                                            <input type="hidden"
                                                   name="hours[{{ $day }}][is_closed]"
                                                   value="0">

                                            <div class="form-check">
                                                <input type="checkbox"
                                                       name="hours[{{ $day }}][is_closed]"
                                                       value="1"
                                                       class="form-check-input closed-checkbox"
                                                       id="closed_{{ $day }}"
                                                       {{ old("hours.$day.is_closed") ? 'checked' : '' }}>

                                                <label class="form-check-label"
                                                       for="closed_{{ $day }}">
                                                    Closed
                                                </label>
                                            </div>
                                        </td>

                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('admin.booking-hours.index') }}"
                           class="btn btn-light border px-4">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-danger px-4"
                                {{ $restaurants->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-save me-1"></i>
                            Save Booking Hours
                        </button>

                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

{{-- CLOSED DAY FUNCTIONALITY --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    function handleClosedDay(row) {
        const checkbox = row.querySelector('.closed-checkbox');
        const opening = row.querySelector('.opening-time');
        const closing = row.querySelector('.closing-time');

        const isClosed = checkbox.checked;

        opening.disabled = isClosed;
        closing.disabled = isClosed;

        opening.required = !isClosed;
        closing.required = !isClosed;

        if (isClosed) {
            opening.value = '';
            closing.value = '';
        } else {
            if (!opening.value) opening.value = '09:00';
            if (!closing.value) closing.value = '22:00';
        }
    }

    document.querySelectorAll('.booking-day-row').forEach(function (row) {

        const checkbox = row.querySelector('.closed-checkbox');

        handleClosedDay(row);

        checkbox.addEventListener('change', function () {
            handleClosedDay(row);
        });

    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingHoursCreateLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-hours-create-page');

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

    syncBookingHoursCreateLayout();

    window.addEventListener('load', syncBookingHoursCreateLayout);
    window.addEventListener('resize', syncBookingHoursCreateLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingHoursCreateLayout, 100);
        setTimeout(syncBookingHoursCreateLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingHoursCreateLayout);
        observer.observe(sidebar);
    }

});
</script>

@endsection
