
@extends('layouts.website')

@section('content')

<style>
    .booking-success-message {
    max-width: 700px;
    margin: 20px auto;
    padding: 20px;
    background: #153c2b;
    border: 1px solid #28a66a;
    border-radius: 12px;
    color: #fff;
}

.booking-success-message h3 {
    color: #55e69a;
    margin-bottom: 8px;
}

.booking-error-message {
    padding: 15px;
    background: #451e24;
    color: #ffaaaa;
    border-radius: 10px;
}
</style>

@if(session('success'))
    <div class="booking-success-message">
        <h3>🎉 Booking Submitted Successfully!</h3>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="booking-error-message">
        {{ session('error') }}
    </div>
@endif

@php
    $bookingDetails = session('booking_details', []);

    $minGuests = (int) ($settings->min_guests ?? 1);
    $maxGuests = (int) ($settings->max_guests ?? 12);

    $bookingDateValue = old('booking_date', data_get($bookingDetails, 'booking_date'));
    $guestValue = old('guests', data_get($bookingDetails, 'guests'));
    $timeValue = old('booking_time', data_get($bookingDetails, 'booking_time'));
    $seatingValue = old('seating', data_get($bookingDetails, 'seating'));
    $specialRequestValue = old('special_request', data_get($bookingDetails, 'special_request'));

    $hoursForJs = ($bookingHours ?? collect())
        ->keyBy('day_of_week')
        ->map(function ($hour) {
            return [
                'closed' => (bool) $hour->is_closed,
                'opening' => $hour->opening_time
                    ? substr((string) $hour->opening_time, 0, 5)
                    : null,
                'closing' => $hour->closing_time
                    ? substr((string) $hour->closing_time, 0, 5)
                    : null,
            ];
        })->all();

    $slotDuration = max(15, (int) ($settings->slot_duration ?? 60));
@endphp

<style>
/* =========================================
   BAKASURAN - PREMIUM 3D BOOKING PAGE
========================================= */

.booking-3d {
    --red: #ff2525;
    --red-dark: #a90000;
    --surface: #1d1d25;
    --muted: #a5a5b1;

    position: relative;
    overflow: hidden;
    min-height: 100vh;
    padding: 65px 20px 90px;
    color: #fff;
    background:
        radial-gradient(ellipse at 10% 10%, rgba(255, 30, 30, .12), transparent 35%),
        radial-gradient(ellipse at 90% 80%, rgba(255, 30, 30, .08), transparent 35%),
        #101014;
    perspective: 1400px;
}

.booking-3d,
.booking-3d * {
    box-sizing: border-box;
}

.booking-3d .booking-container {
    position: relative;
    z-index: 1;
    width: min(100%, 1050px);
    margin: auto;
}

/* HEADER */

.booking-3d .booking-header {
    text-align: center;
    margin-bottom: 42px;
}

.booking-3d .eyebrow {
    display: inline-block;
    color: var(--red);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 4px;
}

.booking-3d .booking-header h1 {
    margin: 14px 0;
    color: #fff;
    font-size: clamp(34px, 5vw, 52px);
    line-height: 1.15;
    font-weight: 900;
}

.booking-3d .booking-header h1 span {
    color: var(--red);
    text-shadow: 0 5px 25px rgba(255, 37, 37, .35);
}

.booking-3d .booking-header p {
    color: var(--muted);
    font-size: 14px;
}

/* PROGRESS */

.booking-3d .booking-progress {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 28px;
}

.booking-3d .progress-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: linear-gradient(145deg, #262630, #17171e);
    border: 1px solid #383842;
    border-radius: 13px;
    box-shadow: 0 5px 0 #09090d, 0 12px 20px rgba(0,0,0,.3);
}

.booking-3d .progress-number {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 10px;
    background: #34343d;
    color: #aaa;
    font-weight: 900;
}

.booking-3d .progress-item.active {
    border-color: var(--red);
}

.booking-3d .progress-item.active .progress-number {
    color: white;
    background: linear-gradient(145deg, #ff4848, #b50000);
    box-shadow: 0 4px 0 #720000;
}

.booking-3d .progress-item span:last-child {
    color: #ccc;
    font-size: 12px;
    font-weight: 700;
}

/* MAIN CARD */

.booking-3d .booking-card {
    padding: 38px;
    background: linear-gradient(145deg, #292932, #17171e);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 23px;
    box-shadow:
        0 25px 50px rgba(0,0,0,.55),
        0 9px 0 #09090d,
        inset 0 1px 1px rgba(255,255,255,.1);
    transform: rotateX(1deg);
    transition: .35s ease;
}

.booking-3d .booking-card:hover {
    transform: rotateX(0deg) translateY(-3px);
}

.booking-3d .section-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
}

.booking-3d .section-icon {
    display: grid;
    place-items: center;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 14px;
    background: linear-gradient(145deg, #ff4545, #a90000);
    box-shadow: 0 6px 0 #700000, 0 12px 20px rgba(255,0,0,.15);
    color: white;
    font-weight: 900;
}

.booking-3d .section-heading h2 {
    margin: 0 0 5px;
    color: #fff;
    font-size: 21px;
    font-weight: 900;
}

.booking-3d .section-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}

/* FORM */

.booking-3d .form-section {
    padding-bottom: 27px;
    margin-bottom: 27px;
    border-bottom: 1px solid rgba(255,255,255,.09);
}

.booking-3d .form-section:last-child {
    border-bottom: 0;
    margin-bottom: 0;
}

.booking-3d .section-title {
    margin-bottom: 19px;
    color: #fff;
    font-size: 15px;
    font-weight: 800;
}

.booking-3d .section-title span {
    margin-right: 8px;
    color: var(--red);
}

.booking-3d .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.booking-3d .form-group {
    min-width: 0;
    margin-bottom: 20px;
}

.booking-3d .form-group label {
    display: block;
    margin-bottom: 10px;
    color: #ddd;
    font-size: 13px;
    font-weight: 700;
}

.booking-3d .form-control {
    display: block;
    width: 100%;
    min-height: 54px;
    padding: 13px 16px;
    color: #fff;
    background: #111117;
    border: 1px solid #41414d;
    border-radius: 11px;
    box-shadow:
        inset 0 4px 9px rgba(0,0,0,.45),
        0 4px 0 #09090d;
    transition: .25s ease;
}

.booking-3d .form-control:focus {
    outline: none;
    border-color: var(--red);
    box-shadow:
        0 0 0 3px rgba(255,37,37,.14),
        inset 0 3px 7px rgba(0,0,0,.4);
}

.booking-3d select.form-control option {
    color: white;
    background: #1b1b22;
}

.booking-3d input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}

.booking-3d .field-help {
    display: block;
    margin-top: 8px;
    color: #888894;
    font-size: 11px;
}

/* SEATING */

.booking-3d .seating-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px;
}

.booking-3d .seating-option {
    position: relative;
}

.booking-3d .seating-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.booking-3d .seating-card {
    display: flex;
    align-items: center;
    gap: 16px;
    min-height: 105px;
    padding: 19px;
    cursor: pointer;
    background: linear-gradient(145deg, #30303b, #191920);
    border: 1px solid #41414c;
    border-radius: 16px;
    box-shadow:
        0 7px 0 #0a0a0e,
        0 13px 22px rgba(0,0,0,.25);
    transition: .25s ease;
}

.booking-3d .seating-card:hover {
    transform: translateY(-5px);
    border-color: #ff4545;
}

.booking-3d .seating-option input:checked + .seating-card {
    border-color: var(--red);
    background: linear-gradient(145deg, #502326, #24171b);
    box-shadow:
        0 5px 0 #8e1010,
        0 15px 28px rgba(255,30,30,.15);
    transform: translateY(-3px);
}

.booking-3d .seating-option input:focus-visible + .seating-card {
    outline: 2px solid white;
    outline-offset: 4px;
}

.booking-3d .seat-icon {
    display: grid;
    place-items: center;
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    color: white;
    font-size: 23px;
    border-radius: 14px;
    background: linear-gradient(145deg, #ff4545, #a90000);
    box-shadow: 0 5px 0 #710000;
}

.booking-3d .seat-info strong {
    display: block;
    color: #fff;
    font-size: 14px;
    font-weight: 800;
}

.booking-3d .seat-info small {
    display: block;
    margin-top: 6px;
    color: #aaa;
    font-size: 11px;
}

/* SPECIAL REQUEST */

.booking-3d textarea.form-control {
    min-height: 110px;
    resize: vertical;
}

/* SUBMIT */

.booking-3d .booking-submit {
    display: block;
    width: 100%;
    min-height: 59px;
    margin-top: 15px;
    color: #fff;
    font-size: 15px;
    font-weight: 900;
    letter-spacing: 1px;
    cursor: pointer;
    border: 0;
    border-radius: 13px;
    background: linear-gradient(135deg, #ff4141, #bd0000);
    box-shadow:
        0 7px 0 #780000,
        0 16px 28px rgba(255,0,0,.2);
    transition: .2s ease;
}

.booking-3d .booking-submit:hover {
    transform: translateY(-3px);
    box-shadow:
        0 10px 0 #780000,
        0 20px 32px rgba(255,0,0,.3);
}

.booking-3d .booking-submit:active {
    transform: translateY(4px);
    box-shadow: 0 2px 0 #780000;
}

/* ALERT */

.booking-3d .booking-alert {
    padding: 16px 18px;
    margin-bottom: 24px;
    color: #ffb8b8;
    background: #351c22;
    border: 1px solid #8b333d;
    border-radius: 12px;
    font-size: 13px;
}

.booking-3d .booking-alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

.booking-3d .booking-alert p {
    margin: 5px 0;
}

/* RESPONSIVE */

@media(max-width: 700px) {
    .booking-3d {
        padding: 40px 14px 65px;
    }

    .booking-3d .booking-card {
        padding: 23px 17px;
    }

    .booking-3d .form-grid,
    .booking-3d .seating-grid {
        grid-template-columns: 1fr;
    }

    .booking-3d .booking-progress {
        gap: 6px;
    }

    .booking-3d .progress-item {
        flex-direction: column;
        text-align: center;
        padding: 10px 5px;
        gap: 7px;
    }

    .booking-3d .progress-item span:last-child {
        font-size: 10px;
    }
}
</style>

<div class="booking-3d">

    <div class="booking-container">

        {{-- HEADER --}}
        <div class="booking-header">

            <div class="eyebrow">BAKASURAN EXPERIENCE</div>

            <h1>Reserve Your <span>Table</span></h1>

            <p>
                Enjoy an unforgettable dining experience at
                {{ $restaurantName }}
            </p>

        </div>

        {{-- PROGRESS --}}
        <div class="booking-progress">

            <div class="progress-item active">
                <span class="progress-number">01</span>
                <span>Booking Details</span>
            </div>

            <div class="progress-item">
                <span class="progress-number">02</span>
                <span>Select Table</span>
            </div>

            <div class="progress-item">
                <span class="progress-number">03</span>
                <span>Confirmation</span>
            </div>

        </div>

        {{-- MAIN BOOKING CARD --}}
        <div class="booking-card">

            <div class="section-heading">

                <div class="section-icon">01</div>

                <div>
                    <h2>Plan Your Visit</h2>
                    <p>
                        Choose your date, time, guest count and preferred seating.
                    </p>
                </div>

            </div>

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())

                <div class="booking-alert" role="alert">

                    <strong>Please check your booking details.</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            {{-- BOOKING FORM --}}
            <form
                action="{{ route('restaurant.check-availability', ['slug' => $slug]) }}"
                method="POST"
                id="bookingDetailsForm"
            >

                @csrf

                {{-- DATE AND GUESTS --}}
                <div class="form-section">

                    <h3 class="section-title">
                        <span>◷</span> Date & Time
                    </h3>

                    <div class="form-grid">

                        <div class="form-group">

                            <label for="booking_date">Booking Date</label>

                            <input
                                type="date"
                                id="booking_date"
                                name="booking_date"
                                class="form-control"
                                min="{{ date('Y-m-d') }}"
                                value="{{ $bookingDateValue }}"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label for="guests">Number of Guests</label>

                            <select
                                name="guests"
                                id="guests"
                                class="form-control"
                                required
                            >

                                <option value="">Select guests</option>

                                @for ($i = $minGuests; $i <= $maxGuests; $i++)

                                    <option
                                        value="{{ $i }}"
                                        {{ (string) $guestValue === (string) $i ? 'selected' : '' }}
                                    >
                                        {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}
                                    </option>

                                @endfor

                            </select>

                            <small class="field-help">
                                Choose between {{ $minGuests }} and {{ $maxGuests }} guests.
                            </small>

                        </div>

                    </div>

                    <div class="form-group" style="margin-top:20px;">

                        <label for="booking_time">Preferred Time</label>

                        <select
                            name="booking_time"
                            id="booking_time"
                            class="form-control"
                            required
                        >

                            <option value="">Select a booking date first</option>

                        </select>

                        <small class="field-help">
                            Time slots follow this restaurant's Booking Hours and Slot Duration.
                        </small>

                    </div>

                </div>

                {{-- SEATING --}}
                <div class="form-section">

                    <h3 class="section-title">
                        <span>▣</span> Choose Your Seating
                    </h3>

                    <div class="seating-grid">

                        <div class="seating-option">

                            <input
                                type="radio"
                                name="seating"
                                id="seating_indoor"
                                value="indoor"
                                {{ $seatingValue === 'indoor' ? 'checked' : '' }}
                                required
                            >

                            <label for="seating_indoor" class="seating-card">

                                <span class="seat-icon">♨</span>

                                <span class="seat-info">
                                    <strong>Indoor Dining</strong>
                                    <small>Comfortable indoor seating</small>
                                </span>

                            </label>

                        </div>

                        <div class="seating-option">

                            <input
                                type="radio"
                                name="seating"
                                id="seating_outdoor"
                                value="outdoor"
                                {{ $seatingValue === 'outdoor' ? 'checked' : '' }}
                            >

                            <label for="seating_outdoor" class="seating-card">

                                <span class="seat-icon">☘</span>

                                <span class="seat-info">
                                    <strong>Outdoor Dining</strong>
                                    <small>Fresh air dining experience</small>
                                </span>

                            </label>

                        </div>

                        <div class="seating-option">

                            <input
                                type="radio"
                                name="seating"
                                id="seating_family"
                                value="family"
                                {{ $seatingValue === 'family' ? 'checked' : '' }}
                            >

                            <label for="seating_family" class="seating-card">

                                <span class="seat-icon">♧</span>

                                <span class="seat-info">
                                    <strong>Family Seating</strong>
                                    <small>Perfect for family gatherings</small>
                                </span>

                            </label>

                        </div>

                        <div class="seating-option">

                            <input
                                type="radio"
                                name="seating"
                                id="seating_private"
                                value="private"
                                {{ $seatingValue === 'private' ? 'checked' : '' }}
                            >

                            <label for="seating_private" class="seating-card">

                                <span class="seat-icon">✦</span>

                                <span class="seat-info">
                                    <strong>Private Dining</strong>
                                    <small>Exclusive dining experience</small>
                                </span>

                            </label>

                        </div>

                    </div>

                </div>

                {{-- SPECIAL REQUEST --}}
                <div class="form-section">

                    <h3 class="section-title">
                        <span>✎</span> Special Requests
                    </h3>

                    <div class="form-group">

                        <label for="special_request">
                            Anything we should know?
                        </label>

                        <textarea
                            name="special_request"
                            id="special_request"
                            class="form-control"
                            rows="4"
                            maxlength="1000"
                            placeholder="Birthday celebration, special occasion, accessibility needs..."
                        >{{ $specialRequestValue }}</textarea>

                    </div>

                </div>

                {{-- SUBMIT --}}
                <button type="submit" class="booking-submit">
                    CHECK AVAILABILITY &nbsp; →
                </button>

            </form>

        </div>

    </div>

</div>

{{-- DYNAMIC BOOKING TIME SLOTS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dateInput = document.getElementById('booking_date');
    const timeSelect = document.getElementById('booking_time');

    const bookingHours = @json($hoursForJs);
    const slotDuration = @json($slotDuration);
    const oldTime = @json(old('booking_time', data_get($bookingDetails, 'booking_time')));

    function formatTime(time) {

        let [hour, minute] = time.split(':').map(Number);

        const suffix = hour >= 12 ? 'PM' : 'AM';

        hour = hour % 12 || 12;

        return `${hour}:${String(minute).padStart(2, '0')} ${suffix}`;
    }

    function generateTimeSlots() {

        timeSelect.innerHTML = '<option value="">Select time</option>';

        if (!dateInput.value) {
            timeSelect.innerHTML =
                '<option value="">Select a booking date first</option>';
            return;
        }

        const parts = dateInput.value.split('-').map(Number);

        const selectedDate = new Date(
            parts[0],
            parts[1] - 1,
            parts[2]
        );

        const dayIndex = selectedDate.getDay();

        const hours = bookingHours[dayIndex] ??
                      bookingHours[String(dayIndex)];

        if (!hours || hours.closed || !hours.opening || !hours.closing) {

            timeSelect.innerHTML =
                '<option value="">Restaurant closed on this day</option>';

            return;
        }

        const toMinutes = function (time) {

            const [hour, minute] = time.split(':').map(Number);

            return hour * 60 + minute;
        };

        const opening = toMinutes(hours.opening);
        const closing = toMinutes(hours.closing);

        const now = new Date();

        const today = [
            now.getFullYear(),
            String(now.getMonth() + 1).padStart(2, '0'),
            String(now.getDate()).padStart(2, '0')
        ].join('-');

        let count = 0;

        for (
            let minute = opening;
            minute + slotDuration <= closing;
            minute += slotDuration
        ) {

            const hour = Math.floor(minute / 60);
            const mins = minute % 60;

            const value =
                String(hour).padStart(2, '0') + ':' +
                String(mins).padStart(2, '0');

            // Exclude past time slots for today's date.
            if (dateInput.value === today) {

                const slotDate = new Date(
                    parts[0],
                    parts[1] - 1,
                    parts[2],
                    hour,
                    mins
                );

                if (slotDate <= now) {
                    continue;
                }
            }

            const option = document.createElement('option');

            option.value = value;
            option.textContent = formatTime(value);

            if (oldTime && oldTime.substring(0, 5) === value) {
                option.selected = true;
            }

            timeSelect.appendChild(option);

            count++;
        }

        if (count === 0) {

            timeSelect.innerHTML =
                '<option value="">No future booking slots available</option>';
        }
    }

    dateInput.addEventListener('change', generateTimeSlots);

    generateTimeSlots();

});
</script>

@endsection
