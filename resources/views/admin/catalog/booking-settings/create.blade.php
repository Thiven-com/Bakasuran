@extends('layout.mainlayout')

@section('content')

    <div class="booking-settings-create-page">

        <style>
            .booking-settings-create-page {
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
                padding: 24px;
                border-radius: 12px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
                margin-bottom: 20px;
            }

            .settings-card h5 {
                font-size: 16px;
                font-weight: 700;
                padding-bottom: 13px;
                border-bottom: 1px solid #eee;
                margin-bottom: 22px;
            }

            .form-label {
                font-size: 13px;
                font-weight: 600;
                color: #444;
            }

            .form-control,
            .form-select {
                min-height: 44px;
                border-radius: 7px;
                border: 1px solid #ddd;
            }

            .setting-description {
                font-size: 12px;
                color: #888;
                margin-top: 6px;
            }

            .booking-toggle-card {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 15px;
                padding: 18px;
                background: #fff8f8;
                border: 1px solid #ffe0e0;
                border-radius: 10px;
            }

            .booking-toggle-card h6 {
                font-weight: 700;
                margin-bottom: 5px;
            }

            .booking-toggle-card p {
                font-size: 12px;
                color: #777;
                margin: 0;
            }

            .booking-toggle-card .form-check-input {
                width: 48px;
                height: 24px;
                cursor: pointer;
            }

            .booking-toggle-card .form-check-input:checked {
                background-color: #ff0000;
                border-color: #ff0000;
            }

            .btn-red {
                background: #ff0000;
                color: #fff;
                border: none;
                padding: 10px 22px;
                border-radius: 7px;
            }

            .btn-red:hover {
                background: #d90000;
                color: #fff;
            }

            .btn-light-custom {
                background: #eee;
                color: #333;
                padding: 10px 20px;
                border-radius: 7px;
                text-decoration: none;
            }

            .btn-light-custom:hover {
                background: #ddd;
                color: #222;
            }

            .required {
                color: #ff0000;
            }

            .settings-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                flex-wrap: wrap;
                margin-top: 25px;
            }

            @media(max-width: 767px) {
                .booking-settings-create-page {
                    padding: 12px;
                }

                .settings-card {
                    padding: 16px;
                }
            }
        </style>

        {{-- Header --}}
        <div class="settings-header">
            <div>
                <h4>Add Booking Settings</h4>
                <p>Configure reservation rules for a restaurant.</p>
            </div>

            <a href="{{ route('admin.booking-settings.index') }}" class="btn-light-custom">
                <i class="ti ti-arrow-left me-1"></i>
                Back
            </a>
        </div>

        {{-- Validation Errors --}}
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

        <form action="{{ route('admin.booking-settings.store') }}" method="POST">

            @csrf

            {{-- Restaurant Selection --}}
            <div class="settings-card">

                <h5>
                    <i class="ti ti-building-store me-2"></i>
                    Restaurant Information
                </h5>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Select Restaurant <span class="required">*</span>
                        </label>

                        <select name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror"
                            required {{ $restaurants->isEmpty() ? 'disabled' : '' }}>
                            <option value="">Choose Restaurant</option>

                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ (string) old('restaurant_id', $selectedRestaurantId ?? '') === (string) $restaurant->id ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('restaurant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="setting-description">
                            Only restaurants without existing booking settings are listed.
                        </div>

                    </div>

                </div>

                @if($restaurants->isEmpty())
                    <div class="alert alert-info mb-0">
                        All existing restaurants already have booking settings.
                        Create another restaurant first if you need additional settings.
                    </div>
                @endif

            </div>

            {{-- General Settings --}}
            <div class="settings-card">

                <h5>
                    <i class="ti ti-settings me-2"></i>
                    General Booking Settings
                </h5>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Slot Duration <span class="required">*</span>
                        </label>

                        <select name="slot_duration" class="form-select @error('slot_duration') is-invalid @enderror"
                            required>

                            @foreach([15, 30, 45, 60, 90, 120, 180, 240] as $duration)
                                <option value="{{ $duration }}" {{ old('slot_duration', 60) == $duration ? 'selected' : '' }}>
                                    {{ $duration }} Minutes
                                </option>
                            @endforeach

                        </select>

                        @error('slot_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="setting-description">
                            Duration of each reservation slot.
                        </div>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Maximum Advance Booking Days
                            <span class="required">*</span>
                        </label>

                        <input type="number" name="max_advance_days"
                            class="form-control @error('max_advance_days') is-invalid @enderror"
                            value="{{ old('max_advance_days', 30) }}" min="1" max="365" required>

                        @error('max_advance_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="setting-description">
                            Maximum number of days customers can book in advance.
                        </div>

                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Booking Duration <span class="required">*</span>
                        </label>

                        <select name="booking_duration" class="form-select @error('booking_duration') is-invalid @enderror"
                            required>

                            @foreach([30, 60, 90, 120, 150, 180, 240, 300, 360, 480] as $duration)
                                <option value="{{ $duration }}" {{ old('booking_duration', 120) == $duration ? 'selected' : '' }}>
                                    {{ $duration }} Minutes
                                </option>
                            @endforeach

                        </select>

                        @error('booking_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="setting-description">
                            Total time allocated for each customer reservation.
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Cleaning Duration <span class="required">*</span>
                        </label>

                        <select name="cleaning_duration"
                            class="form-select @error('cleaning_duration') is-invalid @enderror" required>

                            @foreach([0, 10, 15, 20, 30, 45, 60] as $duration)
                                <option value="{{ $duration }}" {{ old('cleaning_duration', 15) == $duration ? 'selected' : '' }}>
                                    {{ $duration }} Minutes
                                </option>
                            @endforeach

                        </select>

                        @error('cleaning_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="setting-description">
                            Time required to clean the table after a customer leaves.
                        </div>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Minimum Booking Notice <span class="required">*</span>
                        </label>

                        <select name="min_notice_minutes"
                            class="form-select @error('min_notice_minutes') is-invalid @enderror" required>

                            @foreach([0, 15, 30, 60, 120, 180, 360, 720, 1440] as $minutes)
                                <option value="{{ $minutes }}" {{ old('min_notice_minutes', 60) == $minutes ? 'selected' : '' }}>

                                    @if($minutes < 60)
                                        {{ $minutes }} Minutes
                                    @elseif($minutes % 1440 === 0)
                                        {{ $minutes / 1440 }} Day(s)
                                    @else
                                        {{ $minutes / 60 }} Hour(s)
                                    @endif

                                </option>
                            @endforeach

                        </select>

                        @error('min_notice_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="setting-description">
                            Minimum time required between booking and reservation start.
                        </div>
                    </div>

                </div>

            </div>

            {{-- Guest Settings --}}
            <div class="settings-card">

                <h5>
                    <i class="ti ti-users me-2"></i>
                    Guest Capacity Settings
                </h5>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Minimum Guests <span class="required">*</span>
                        </label>

                        <input type="number" name="min_guests"
                            class="form-control @error('min_guests') is-invalid @enderror"
                            value="{{ old('min_guests', 1) }}" min="1" required>

                        @error('min_guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Maximum Guests <span class="required">*</span>
                        </label>

                        <input type="number" name="max_guests"
                            class="form-control @error('max_guests') is-invalid @enderror"
                            value="{{ old('max_guests', 20) }}" min="1" required>

                        @error('max_guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- Availability --}}
            <div class="settings-card">

                <h5>
                    <i class="ti ti-calendar-check me-2"></i>
                    Booking Availability
                </h5>

                <div class="booking-toggle-card">

                    <div>
                        <h6>Enable Table Reservations</h6>
                        <p>Allow customers to make table reservations.</p>
                    </div>

                    <div class="form-check form-switch mb-0">

                        <input type="hidden" name="is_booking_enabled" value="0">

                        <input type="checkbox" name="is_booking_enabled" value="1" id="is_booking_enabled"
                            class="form-check-input" {{ old('is_booking_enabled', '1') == '1' ? 'checked' : '' }}>

                    </div>

                </div>

            </div>

            {{-- Actions --}}
            <div class="settings-actions">

                <a href="{{ route('admin.booking-settings.index') }}" class="btn-light-custom">
                    Cancel
                </a>

                <button type="submit" class="btn-red" {{ $restaurants->isEmpty() ? 'disabled' : '' }}>
                    <i class="ti ti-device-floppy me-1"></i>
                    Save Booking Settings
                </button>

            </div>

        </form>

    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function syncBookingSettingsCreateLayout() {
                const sidebar = document.getElementById('sidebar');
                const page = document.querySelector('.booking-settings-create-page');

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

            syncBookingSettingsCreateLayout();

            window.addEventListener('load', syncBookingSettingsCreateLayout);
            window.addEventListener('resize', syncBookingSettingsCreateLayout);

            document.addEventListener('click', function () {
                setTimeout(syncBookingSettingsCreateLayout, 100);
                setTimeout(syncBookingSettingsCreateLayout, 300);
            });

            const sidebar = document.getElementById('sidebar');

            if (sidebar && typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(syncBookingSettingsCreateLayout);
                observer.observe(sidebar);
            }

        });
    </script>
@endsection