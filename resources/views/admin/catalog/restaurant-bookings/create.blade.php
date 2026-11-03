
@extends('layout.mainlayout')

@section('title', 'Add Table Booking')

@section('content')

<style>
    .booking-form-page {
        padding: 24px;
        margin-top: 50px;
        min-height: 100vh;
        background: #f7f7f9;
        color: #171717;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .booking-header h2 {
        font-size: 25px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .booking-header p {
        color: #777;
        margin: 0;
        font-size: 14px;
    }

    .btn-back {
        padding: 10px 16px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
    }

    .booking-form-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 25px;
    }

    .section-title {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #eee;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .required {
        color: #ff0000;
    }

    .form-control,
    .form-select {
        min-height: 46px;
        border: 1px solid #e2e2e2;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff0000;
        box-shadow: 0 0 0 3px rgba(255, 0, 0, .08);
    }

    textarea.form-control {
        min-height: 100px;
    }

    .btn-save {
        background: #ff0000;
        color: #fff;
        border: none;
        padding: 12px 22px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-save:hover {
        background: #cc0000;
        color: #fff;
    }

    .btn-cancel {
        padding: 12px 22px;
        border: 1px solid #ddd;
        background: #fff;
        color: #333;
        border-radius: 8px;
        text-decoration: none;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    @media(max-width: 650px) {
        .booking-form-page {
            padding: 14px;
        }

        .booking-form-card {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions button,
        .form-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="booking-form-page">

    {{-- Header --}}
    <div class="booking-header">
        <div>
            <h2>Add Table Booking</h2>
            <p>Create a new customer restaurant reservation.</p>
        </div>

        <a href="{{ route('admin.restaurant-bookings.index') }}"
           class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Bookings
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="booking-form-card">

        <form action="{{ route('admin.restaurant-bookings.store') }}"
              method="POST">

            @csrf

            {{-- Restaurant and Table --}}
            <h5 class="section-title">
                <i class="fas fa-utensils text-danger me-2"></i>
                Restaurant & Table Information
            </h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">
                        Restaurant <span class="required">*</span>
                    </label>

                    <select name="restaurant_id"
                            id="restaurant_id"
                            class="form-select @error('restaurant_id') is-invalid @enderror"
                            required>

                        <option value="">Select Restaurant</option>

                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}"
                                {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                {{ $restaurant->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('restaurant_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Restaurant Table <span class="required">*</span>
                    </label>

                    <select name="restaurant_table_id"
                            id="restaurant_table_id"
                            class="form-select @error('restaurant_table_id') is-invalid @enderror"
                            required>

                        <option value="">Select a restaurant first</option>

                    </select>

                    @error('restaurant_table_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Customer Information --}}
            <h5 class="section-title mt-4">
                <i class="fas fa-user text-danger me-2"></i>
                Customer Information
            </h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">
                        Customer Name <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="customer_name"
                           class="form-control @error('customer_name') is-invalid @enderror"
                           value="{{ old('customer_name') }}"
                           placeholder="Enter customer name"
                           required>

                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Mobile Number <span class="required">*</span>
                    </label>

                    <input type="tel"
                           name="customer_mobile"
                           class="form-control @error('customer_mobile') is-invalid @enderror"
                           value="{{ old('customer_mobile') }}"
                           placeholder="Enter mobile number"
                           required>

                    @error('customer_mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email Address</label>

                    <input type="email"
                           name="customer_email"
                           class="form-control @error('customer_email') is-invalid @enderror"
                           value="{{ old('customer_email') }}"
                           placeholder="Enter email address">

                    @error('customer_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Occasion</label>

                    <select name="occasion"
                            class="form-select @error('occasion') is-invalid @enderror">

                        <option value="">Select Occasion</option>
                        <option value="Birthday" {{ old('occasion') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                        <option value="Anniversary" {{ old('occasion') == 'Anniversary' ? 'selected' : '' }}>Anniversary</option>
                        <option value="Family Dinner" {{ old('occasion') == 'Family Dinner' ? 'selected' : '' }}>Family Dinner</option>
                        <option value="Business Meeting" {{ old('occasion') == 'Business Meeting' ? 'selected' : '' }}>Business Meeting</option>
                        <option value="Other" {{ old('occasion') == 'Other' ? 'selected' : '' }}>Other</option>

                    </select>

                    @error('occasion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Booking Details --}}
            <h5 class="section-title mt-4">
                <i class="fas fa-calendar-check text-danger me-2"></i>
                Booking Details
            </h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">
                        Booking Date <span class="required">*</span>
                    </label>

                    <input type="date"
                           name="booking_date"
                           class="form-control @error('booking_date') is-invalid @enderror"
                           value="{{ old('booking_date') }}"
                           min="{{ date('Y-m-d') }}"
                           required>

                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Number of Guests <span class="required">*</span>
                    </label>

                    <input type="number"
                           name="guest_count"
                           class="form-control @error('guest_count') is-invalid @enderror"
                           value="{{ old('guest_count', 2) }}"
                           min="1"
                           required>

                    @error('guest_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Booking Start Time <span class="required">*</span>
                    </label>

                    <input type="time"
                           name="booking_time"
                           class="form-control @error('booking_time') is-invalid @enderror"
                           value="{{ old('booking_time') }}"
                           required>

                    @error('booking_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Booking End Time</label>

                    <input type="time"
                           name="end_time"
                           class="form-control @error('end_time') is-invalid @enderror"
                           value="{{ old('end_time') }}" required>

                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Seating Preference</label>

                    <select name="seating_type"
                            class="form-select @error('seating_type') is-invalid @enderror">

                        <option value="">Select Seating Type</option>

                        <option value="indoor" {{ old('seating_type') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                        <option value="outdoor" {{ old('seating_type') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                        <option value="family" {{ old('seating_type') == 'family' ? 'selected' : '' }}>Family</option>
                        <option value="private" {{ old('seating_type') == 'private' ? 'selected' : '' }}>Private</option>

                    </select>

                    @error('seating_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Booking Status <span class="required">*</span>
                    </label>

                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Special Requests</label>

                    <textarea name="special_requests"
                              class="form-control @error('special_requests') is-invalid @enderror"
                              rows="4"
                              placeholder="Enter any special customer requests...">{{ old('special_requests') }}</textarea>

                    @error('special_requests')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="form-actions">

                <a href="{{ route('admin.restaurant-bookings.index') }}"
                   class="btn-cancel">
                    Cancel
                </a>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>
                    Create Booking
                </button>

            </div>

        </form>

    </div>

</div>

{{-- Restaurant Table Dropdown --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const restaurantSelect = document.getElementById('restaurant_id');
        const tableSelect = document.getElementById('restaurant_table_id');

        const oldTableId = @json(old('restaurant_table_id'));

        // Tables supplied by the controller would be needed here.
        // Load tables from the server using the route added below.

        restaurantSelect.addEventListener('change', function () {

            const restaurantId = this.value;

            tableSelect.innerHTML = '<option value="">Loading tables...</option>';

            if (!restaurantId) {
                tableSelect.innerHTML = '<option value="">Select a restaurant first</option>';
                return;
            }

            fetch("{{ url('admin/restaurant-booking-tables') }}/" + restaurantId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Unable to load tables.');
                    }
                    return response.json();
                })
                .then(tables => {

                    tableSelect.innerHTML = '<option value="">Select Table</option>';

                    tables.forEach(table => {

                        const option = document.createElement('option');

                        option.value = table.id;
                        option.textContent =
                            table.table_name + ' (' +
                            table.table_number + ') - Capacity: ' +
                            table.seating_capacity;

                        if (String(oldTableId) === String(table.id)) {
                            option.selected = true;
                        }

                        tableSelect.appendChild(option);
                    });

                    if (tables.length === 0) {
                        tableSelect.innerHTML =
                            '<option value="">No active tables available</option>';
                    }

                })
                .catch(error => {
                    console.error(error);
                    tableSelect.innerHTML =
                        '<option value="">Unable to load tables</option>';
                });

        });

        // Load old selected restaurant after validation failure
        if (restaurantSelect.value) {
            restaurantSelect.dispatchEvent(new Event('change'));
        }

    });
</script>

{{-- Responsive Sidebar Sync --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const sidebar = document.getElementById('sidebar');
        const bookingPage = document.querySelector('.booking-form-page');

        if (!sidebar || !bookingPage) {
            return;
        }

        function syncBookingPageWithSidebar() {

            if (window.innerWidth <= 991) {

                bookingPage.style.setProperty('margin-left', '0px', 'important');
                bookingPage.style.setProperty('width', '100%', 'important');

                return;
            }

            const sidebarWidth = Math.round(
                sidebar.getBoundingClientRect().width
            );

            bookingPage.style.setProperty(
                'margin-left',
                sidebarWidth + 'px',
                'important'
            );

            bookingPage.style.setProperty(
                'width',
                'calc(100% - ' + sidebarWidth + 'px)',
                'important'
            );
        }

        syncBookingPageWithSidebar();

        if (typeof ResizeObserver !== 'undefined') {
            const observer = new ResizeObserver(function () {
                syncBookingPageWithSidebar();
            });

            observer.observe(sidebar);
        }

        document.addEventListener('click', function () {
            setTimeout(syncBookingPageWithSidebar, 100);
            setTimeout(syncBookingPageWithSidebar, 300);
        });

        window.addEventListener('resize', syncBookingPageWithSidebar);

    });
</script>

@endsection
