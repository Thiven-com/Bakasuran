
@extends('layouts.website')

@section('content')

<div class="booking-confirmation-page">
    <div class="confirmation-container">

        <div class="confirmation-heading">
            <span class="step-number">03</span>
            <div>
                <h1>Complete Your Reservation</h1>
                <p>Enter your details to confirm your table booking.</p>
            </div>
        </div>

        <div class="booking-summary">
            <h3>Booking Summary</h3>

            <div class="summary-row">
                <span>Restaurant</span>
                <strong>{{ $restaurantName }}</strong>
            </div>

            <div class="summary-row">
                <span>Date</span>
                <strong>{{ $bookingDetails['booking_date'] }}</strong>
            </div>

            <div class="summary-row">
                <span>Time</span>
                <strong>{{ \Carbon\Carbon::parse($bookingDetails['booking_time'])->format('h:i A') }}</strong>
            </div>

            <div class="summary-row">
                <span>Guests</span>
                <strong>{{ $bookingDetails['guests'] }}</strong>
            </div>

            <div class="summary-row">
                <span>Seating</span>
                <strong>{{ ucfirst($bookingDetails['seating']) }}</strong>
            </div>

            <div class="summary-row">
                <span>Selected Table</span>
                <strong>{{ $selectedTable->table_number }}</strong>
            </div>

            <div class="summary-row">
                <span>Capacity</span>
                <strong>{{ $selectedTable->seating_capacity }} Guests</strong>
            </div>
        </div>

        <form method="POST"
              action="{{ route('restaurant.confirm-booking', ['slug' => $slug]) }}">

            @csrf

            <input type="hidden" name="booking_date"
                   value="{{ $bookingDetails['booking_date'] }}">

            <input type="hidden" name="booking_time"
                   value="{{ $bookingDetails['booking_time'] }}">

            <input type="hidden" name="guests"
                   value="{{ $bookingDetails['guests'] }}">

            <input type="hidden" name="seating"
                   value="{{ $bookingDetails['seating'] }}">

            <input type="hidden" name="table_id"
                   value="{{ $selectedTable->id }}">

            <input type="hidden" name="special_request"
                   value="{{ $bookingDetails['special_request'] ?? '' }}">

            <div class="customer-details">
                <h3>Customer Information</h3>

                <div class="form-group">
                    <label for="customer_name">Full Name *</label>
                    <input type="text"
                           id="customer_name"
                           name="customer_name"
                           value="{{ old('customer_name') }}"
                           placeholder="Enter your full name"
                           maxlength="150"
                           required>
                    @error('customer_name')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_mobile">Mobile Number *</label>
                    <input type="tel"
                           id="customer_mobile"
                           name="customer_mobile"
                           value="{{ old('customer_mobile') }}"
                           placeholder="Enter mobile number"
                           maxlength="20"
                           required>
                    @error('customer_mobile')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_email">Email Address</label>
                    <input type="email"
                           id="customer_email"
                           name="customer_email"
                           value="{{ old('customer_email') }}"
                           placeholder="Enter email address"
                           maxlength="150">
                    @error('customer_email')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            @if ($errors->any())
                <div class="error-summary">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="confirmation-actions">
                <a href="{{ route('restaurant.select-table', ['slug' => $slug]) }}"
                   class="back-button">
                    Back to Tables
                </a>

                <button type="submit" class="confirm-button">
                    CONFIRM BOOKING
                </button>
            </div>

        </form>

    </div>
</div>

<style>
.booking-confirmation-page {
    min-height: 80vh;
    padding: 60px 20px;
    background: #111114;
    color: #fff;
}

.confirmation-container {
    max-width: 850px;
    margin: auto;
    padding: 35px;
    background: linear-gradient(145deg, #292932, #1b1b22);
    border: 1px solid #3b3b45;
    border-radius: 22px;
    box-shadow: 0 20px 55px rgba(0,0,0,.4);
}

.confirmation-heading {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 30px;
}

.step-number {
    background: linear-gradient(145deg, #ff3434, #a90000);
    padding: 15px;
    border-radius: 14px;
    font-size: 22px;
    font-weight: 800;
}

.confirmation-heading h1 {
    margin: 0 0 7px;
    font-size: 27px;
}

.confirmation-heading p {
    color: #aaaab5;
    margin: 0;
}

.booking-summary,
.customer-details {
    background: #17171e;
    border: 1px solid #393943;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 25px;
}

.booking-summary h3,
.customer-details h3 {
    margin: 0 0 20px;
    font-size: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 13px 0;
    border-bottom: 1px solid #303039;
}

.summary-row:last-child {
    border-bottom: 0;
}

.summary-row span {
    color: #aaaab5;
}

.summary-row strong {
    text-align: right;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 9px;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: 15px;
    color: #fff;
    background: #101015;
    border: 1px solid #41414c;
    border-radius: 11px;
    outline: none;
    box-sizing: border-box;
}

.form-group input:focus {
    border-color: #ff2929;
    box-shadow: 0 0 0 3px rgba(255,41,41,.12);
}

.error {
    display: block;
    margin-top: 7px;
    color: #ff7777;
}

.error-summary {
    padding: 12px 18px;
    margin-bottom: 20px;
    color: #ffaaaa;
    background: #391c21;
    border: 1px solid #a33340;
    border-radius: 10px;
}

.confirmation-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.back-button,
.confirm-button {
    padding: 15px 24px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
}

.back-button {
    color: #fff;
    border: 1px solid #555;
}

.confirm-button {
    color: white;
    background: linear-gradient(135deg, #ff3434, #b50000);
    border: 0;
    cursor: pointer;
}

.confirm-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(255,0,0,.25);
}

@media(max-width:600px) {
    .confirmation-container {
        padding: 20px;
    }

    .summary-row {
        font-size: 14px;
    }

    .confirmation-actions > * {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>

@endsection
