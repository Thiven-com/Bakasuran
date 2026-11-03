
@extends('layout.mainlayout')

@section('content')

<div class="booking-edit-page">

    <style>
        .booking-edit-page {
            margin-top: 70px;
            padding: 20px;
            background: #f7f7f9;
            min-height: 100vh;
        }

        .edit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 22px;
        }

        .edit-header h4 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .edit-header p {
            color: #777;
            margin: 0;
        }

        .edit-card {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
        }

        .edit-card h5 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            border-radius: 7px;
            border: 1px solid #ddd;
        }

        textarea.form-control {
            min-height: 100px;
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
            padding: 10px 22px;
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

        @media(max-width: 767px) {
            .booking-edit-page {
                padding: 12px;
            }

            .edit-card {
                padding: 16px;
            }
        }
    </style>

    <div class="edit-header">
        <div>
            <h4>Edit Table Booking</h4>
            <p>
                Booking:
                <strong style="color:#ff0000;">
                    {{ $restaurant_booking->booking_number }}
                </strong>
            </p>
        </div>

        <a href="{{ route('admin.restaurant-bookings.index') }}"
           class="btn-light-custom">
            <i class="ti ti-arrow-left"></i> Back
        </a>
    </div>

    <div class="edit-card">

        <form action="{{ route('admin.restaurant-bookings.update', $restaurant_booking->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            {{-- Restaurant and Table --}}
            <h5>
                <i class="ti ti-building-store me-2"></i>
                Restaurant & Table
            </h5>

            <div class="row">

                <div class="col-md-6 mb-3">
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
                                {{ old('restaurant_id', $restaurant_booking->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                {{ $restaurant->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('restaurant_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Restaurant Table <span class="required">*</span>
                    </label>

                    <select name="restaurant_table_id"
                            id="restaurant_table_id"
                            class="form-select @error('restaurant_table_id') is-invalid @enderror"
                            required>

                        <option value="">Select Table</option>

                        @foreach($tables as $table)
                            <option value="{{ $table->id }}"
                                {{ old('restaurant_table_id', $restaurant_booking->restaurant_table_id) == $table->id ? 'selected' : '' }}>
                                {{ $table->table_name }}
                                ({{ $table->table_number }})
                                - {{ $table->seating_capacity }} Guests
                            </option>
                        @endforeach

                    </select>

                    @error('restaurant_table_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Customer Details --}}
            <h5 class="mt-4">
                <i class="ti ti-user me-2"></i>
                Customer Information
            </h5>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Customer Name <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="customer_name"
                           class="form-control @error('customer_name') is-invalid @enderror"
                           value="{{ old('customer_name', $restaurant_booking->customer_name) }}"
                           placeholder="Enter customer name"
                           required>

                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Mobile Number <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="customer_mobile"
                           class="form-control @error('customer_mobile') is-invalid @enderror"
                           value="{{ old('customer_mobile', $restaurant_booking->customer_mobile) }}"
                           placeholder="Enter mobile number"
                           required>

                    @error('customer_mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Email Address</label>

                    <input type="email"
                           name="customer_email"
                           class="form-control @error('customer_email') is-invalid @enderror"
                           value="{{ old('customer_email', $restaurant_booking->customer_email) }}"
                           placeholder="Enter email address">

                    @error('customer_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Occasion</label>

                    <select name="occasion" class="form-select">
                        <option value="">Select Occasion</option>

                        @foreach(['Birthday', 'Anniversary', 'Family Dinner', 'Business Meeting', 'Other'] as $occasion)
                            <option value="{{ $occasion }}"
                                {{ old('occasion', $restaurant_booking->occasion) == $occasion ? 'selected' : '' }}>
                                {{ $occasion }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            {{-- Booking Details --}}
            <h5 class="mt-4">
                <i class="ti ti-calendar-event me-2"></i>
                Booking Details
            </h5>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Booking Date <span class="required">*</span>
                    </label>

                    <input type="date"
                           name="booking_date"
                           class="form-control @error('booking_date') is-invalid @enderror"
                           value="{{ old('booking_date', $restaurant_booking->booking_date ? \Carbon\Carbon::parse($restaurant_booking->booking_date)->format('Y-m-d') : '') }}"
                           required>

                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Guest Count <span class="required">*</span>
                    </label>

                    <input type="number"
                           name="guest_count"
                           class="form-control @error('guest_count') is-invalid @enderror"
                           value="{{ old('guest_count', $restaurant_booking->guest_count) }}"
                           min="1"
                           required>

                    @error('guest_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Seating Preference</label>

                    <select name="seating_type" class="form-select">
                        <option value="">Select Seating</option>

                        @foreach(['indoor', 'outdoor', 'family', 'private'] as $type)
                            <option value="{{ $type }}"
                                {{ old('seating_type', $restaurant_booking->seating_type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Start Time <span class="required">*</span>
                    </label>

                    <input type="time"
       name="booking_time"
       class="form-control @error('booking_time') is-invalid @enderror"
       value="{{ old('booking_time', $restaurant_booking->booking_time ? \Carbon\Carbon::parse($restaurant_booking->booking_time)->format('H:i') : '') }}"
       required>

@error('booking_time')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">End Time</label>

                    <input type="time"
       name="end_time"
       class="form-control @error('end_time') is-invalid @enderror"
       value="{{ old('end_time', $restaurant_booking->end_time ? \Carbon\Carbon::parse($restaurant_booking->end_time)->format('H:i') : '') }}">

@error('end_time')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Booking Status <span class="required">*</span>
                    </label>

                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                        @foreach(['pending', 'confirmed', 'cancelled', 'completed', 'no_show'] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $restaurant_booking->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Requests and Notes --}}
            <h5 class="mt-4">
                <i class="ti ti-notes me-2"></i>
                Additional Information
            </h5>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Special Requests</label>

                    <textarea name="special_requests"
                              class="form-control"
                              placeholder="Enter customer requests">{{ old('special_requests', $restaurant_booking->special_requests) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Admin Notes</label>

                    <textarea name="admin_notes"
                              class="form-control"
                              placeholder="Enter internal admin notes">{{ old('admin_notes', $restaurant_booking->admin_notes) }}</textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">

                <a href="{{ route('admin.restaurant-bookings.index') }}"
                   class="btn-light-custom">
                    Cancel
                </a>

                <button type="submit" class="btn-red">
                    <i class="ti ti-device-floppy me-1"></i>
                    Update Booking
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    function syncBookingEditLayout() {
        const sidebar = document.getElementById('sidebar');
        const page = document.querySelector('.booking-edit-page');

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

    syncBookingEditLayout();

    window.addEventListener('load', syncBookingEditLayout);
    window.addEventListener('resize', syncBookingEditLayout);

    document.addEventListener('click', function () {
        setTimeout(syncBookingEditLayout, 100);
        setTimeout(syncBookingEditLayout, 300);
    });

    const sidebar = document.getElementById('sidebar');

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncBookingEditLayout);
        observer.observe(sidebar);
    }

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const restaurantSelect = document.getElementById('restaurant_id');
    const tableSelect = document.getElementById('restaurant_table_id');

    if (!restaurantSelect || !tableSelect) return;

    const oldTableId = @json(old('restaurant_table_id'));

    function loadTables(restaurantId, selectedTableId = null) {

        tableSelect.innerHTML = '<option value="">Loading tables...</option>';
        tableSelect.disabled = true;

        if (!restaurantId) {
            tableSelect.innerHTML = '<option value="">Select Restaurant First</option>';
            tableSelect.disabled = false;
            return;
        }

        fetch("{{ url('admin/restaurant-booking-tables') }}/" + restaurantId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Unable to load tables');
                }
                return response.json();
            })
            .then(tables => {

                tableSelect.innerHTML = '<option value="">Select Table</option>';

                if (tables.length === 0) {
                    tableSelect.innerHTML +=
                        '<option value="">No active tables available</option>';
                    return;
                }

                tables.forEach(table => {

                    const option = document.createElement('option');

                    option.value = table.id;
                    option.textContent =
                        table.table_name + ' (' + table.table_number + ') - ' +
                        table.seating_capacity + ' Guests';

                    if (String(table.id) === String(selectedTableId)) {
                        option.selected = true;
                    }

                    tableSelect.appendChild(option);
                });

            })
            .catch(error => {
                console.error(error);
                tableSelect.innerHTML =
                    '<option value="">Unable to load tables</option>';
            })
            .finally(() => {
                tableSelect.disabled = false;
            });
    }

    restaurantSelect.addEventListener('change', function () {
        loadTables(this.value);
    });

    // Preserve the selected table when the edit page first loads.
    // The controller has already supplied the existing restaurant's tables.
    const currentTableId = @json($restaurant_booking->restaurant_table_id);

    if (oldTableId) {
        loadTables(restaurantSelect.value, oldTableId);
    } else if (restaurantSelect.value) {
        // Existing table options are already rendered by Blade.
        tableSelect.value = currentTableId;
    }

});
</script>

@endsection
