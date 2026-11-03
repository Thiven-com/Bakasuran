
@extends('layout.mainlayout')

@section('content')

<div class="booking-settings-edit-page" style="margin-top:70px;">
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Edit Booking Settings</h3>
                <p class="text-muted mb-0">Update restaurant reservation settings</p>
            </div>

            <a href="{{ route('admin.booking-settings.index') }}"
               class="btn btn-light border">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Reservation Configuration</h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.booking-settings.update', $booking_setting->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Restaurant <span class="text-danger">*</span>
                            </label>

                            <select name="restaurant_id"
                                    class="form-select"
                                    required>
                                <option value="">Select Restaurant</option>

                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}"
                                        {{ old('restaurant_id', $booking_setting->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('restaurant_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Slot Duration <span class="text-danger">*</span>
                            </label>

                            <select name="slot_duration" class="form-select" required>
                                @foreach([15, 30, 45, 60, 90, 120, 180, 240] as $duration)
                                    <option value="{{ $duration }}"
                                        {{ old('slot_duration', $booking_setting->slot_duration) == $duration ? 'selected' : '' }}>
                                        {{ $duration }} Minutes
                                    </option>
                                @endforeach
                            </select>

                            @error('slot_duration')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Maximum Advance Booking Days
                            </label>

                            <input type="number"
                                   name="max_advance_days"
                                   class="form-control"
                                   min="1"
                                   max="365"
                                   value="{{ old('max_advance_days', $booking_setting->max_advance_days) }}"
                                   required>

                            @error('max_advance_days')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Minimum Guests
                            </label>

                            <input type="number"
                                   name="min_guests"
                                   class="form-control"
                                   min="1"
                                   value="{{ old('min_guests', $booking_setting->min_guests) }}"
                                   required>

                            @error('min_guests')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Maximum Guests
                            </label>

                            <input type="number"
                                   name="max_guests"
                                   class="form-control"
                                   min="1"
                                   value="{{ old('max_guests', $booking_setting->max_guests) }}"
                                   required>

                            @error('max_guests')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Enable Table Reservations</h6>
                                    <small class="text-muted">
                                        Allow customers to make table reservations for this restaurant.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_booking_enabled" value="0">

                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_booking_enabled"
                                           value="1"
                                           id="bookingEnabled"
                                           {{ old('is_booking_enabled', $booking_setting->is_booking_enabled) ? 'checked' : '' }}>
                                </div>
                            </div>

                            @error('is_booking_enabled')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.booking-settings.index') }}"
                               class="btn btn-light border px-4">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-save me-1"></i> Update Settings
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingSettingsEditLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-settings-edit-page');

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

    syncBookingSettingsEditLayout();

    window.addEventListener('load', syncBookingSettingsEditLayout);
    window.addEventListener('resize', syncBookingSettingsEditLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingSettingsEditLayout, 100);
        setTimeout(syncBookingSettingsEditLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingSettingsEditLayout);
        observer.observe(sidebar);
    }

});
</script>
@endsection
