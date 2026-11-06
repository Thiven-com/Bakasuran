
@extends('layout.mainlayout')

@section('content')

<div class="booking-hours-edit-page" style="margin-top:70px;">
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="mb-1">Edit Booking Hours</h3>
                <p class="text-muted mb-0">
                    Update weekly reservation timings for {{ $restaurant->name }}.
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

        <form action="{{ route('admin.booking-hours.update', $restaurant->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm rounded-3">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="far fa-clock me-2 text-danger"></i>
                        Weekly Restaurant Schedule
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- RESTAURANT DETAILS --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Restaurant
                            </label>

                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $restaurant->name }}"
                                   readonly>
                        </div>
                    </div>

                    {{-- WEEKLY SCHEDULE --}}
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

                                    @php
                                        $savedHour = $bookingHours->get($day);
                                        $isClosed = (bool) ($savedHour?->is_closed ?? false);

                                        $openingTime = $savedHour?->opening_time
                                            ? substr($savedHour->opening_time, 0, 5)
                                            : '09:00';

                                        $closingTime = $savedHour?->closing_time
                                            ? substr($savedHour->closing_time, 0, 5)
                                            : '22:00';
                                    @endphp

                                    <tr class="booking-day-row">

                                        <td class="fw-semibold">
                                            {{ $dayName }}
                                        </td>

                                        <td>
                                            <input type="time"
                                                   name="hours[{{ $day }}][opening_time]"
                                                   class="form-control opening-time"
                                                   value="{{ old("hours.$day.opening_time", $openingTime) }}"
                                                   required>
                                        </td>

                                        <td>
                                            <input type="time"
                                                   name="hours[{{ $day }}][closing_time]"
                                                   class="form-control closing-time"
                                                   value="{{ old("hours.$day.closing_time", $closingTime) }}"
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
               {{ old("hours.$day.is_closed", $isClosed) ? 'checked' : '' }}>

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

                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('admin.booking-hours.index') }}"
                           class="btn btn-light border px-4">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-danger px-4">
                            <i class="fas fa-save me-1"></i>
                            Update Booking Hours
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

        if (!isClosed) {

            if (!opening.value) {
                opening.value = '09:00';
            }

            if (!closing.value) {
                closing.value = '22:00';
            }

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

    function syncBookingHoursEditLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-hours-edit-page');

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

    syncBookingHoursEditLayout();

    window.addEventListener('load', syncBookingHoursEditLayout);
    window.addEventListener('resize', syncBookingHoursEditLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingHoursEditLayout, 100);
        setTimeout(syncBookingHoursEditLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingHoursEditLayout);
        observer.observe(sidebar);
    }

});
</script>
@endsection
