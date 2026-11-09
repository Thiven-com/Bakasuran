@extends('layouts.website')

@section('content')

    <style>
        .success-page {
            min-height: 100vh;
            padding: 55px 15px 75px;
            background: #fbf8f3;
            color: #420916;
        }

        .success-wrapper {
            max-width: 850px;
            margin: 0 auto;
        }

        .success-card {
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 12px 35px rgba(66, 9, 22, .07);
            text-align: center;
        }

        .success-icon {
            width: 82px;
            height: 82px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e5f5e9;
            color: #16813b;
            font-size: 42px;
            font-weight: 700;
        }

        .success-eyebrow {
            color: #a47724;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .success-card h1 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #420916;
        }

        .success-description {
            color: #81766d;
            font-size: 15px;
            line-height: 1.7;
        }

        .booking-number {
            display: inline-block;
            margin: 22px 0;
            padding: 13px 24px;
            background: #fbf3e5;
            border: 1px dashed #c88618;
            border-radius: 10px;
        }

        .booking-number small {
            display: block;
            color: #8b8177;
            font-size: 11px;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .booking-number strong {
            color: #d71313;
            font-size: 19px;
            letter-spacing: 1px;
        }

        .booking-status {
            display: inline-block;
            padding: 7px 15px;
            border-radius: 20px;
            background: #fff3cd;
            color: #856404;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .success-divider {
            border: 0;
            border-top: 1px solid #eee5da;
            margin: 30px 0;
        }

        .booking-summary {
            text-align: left;
        }

        .booking-summary h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #420916;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .booking-item small {
            display: block;
            color: #8b8177;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .booking-item strong {
            display: block;
            color: #420916;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .success-note {
            margin-top: 28px;
            padding: 15px;
            background: #fbf3e5;
            border-left: 4px solid #c88618;
            border-radius: 8px;
            text-align: left;
            color: #62584f;
            font-size: 13px;
            line-height: 1.7;
        }

        .success-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .success-btn {
            display: inline-block;
            padding: 13px 24px;
            border-radius: 8px;
            text-decoration: none !important;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .success-btn-primary {
            background: #420916;
            color: #fff !important;
        }

        .success-btn-secondary {
            border: 1px solid #420916;
            color: #420916 !important;
        }

        .success-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 28px 20px;
            }

            .success-card h1 {
                font-size: 30px;
            }

            .booking-grid {
                grid-template-columns: 1fr;
                gap: 17px;
            }
        }
    </style>

    <main class="success-page">
        <div class="success-wrapper">

            <div class="success-card">

                <div class="success-icon">✓</div>

                <p class="success-eyebrow">BAKASURAN FOOD</p>

                <h1>Booking Submitted Successfully!</h1>

                <p class="success-description">
                    Thank you for choosing
                    <strong>{{ $restaurant->name }}</strong>.
                    Your table reservation request has been received.
                </p>

                <div class="booking-number">
                    <small>YOUR BOOKING NUMBER</small>
                    <strong>{{ $booking->booking_number }}</strong>
                </div>

                <div>
                    <span class="booking-status">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>

                <hr class="success-divider">

                <div class="booking-summary">

                    <h3>Reservation Details</h3>

                    <div class="booking-grid">

                        <div class="booking-item">
                            <small>Restaurant</small>
                            <strong>{{ $restaurant->name }}</strong>
                        </div>

                        <div class="booking-item">
                            <small>Table Number</small>
                            <strong>
                                {{ $booking->restaurantTable->table_number ?? 'Not available' }}
                            </strong>
                        </div>

                        <div class="booking-item">
                            <small>Booking Date</small>
                            <strong>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                            </strong>
                        </div>

                        <div class="booking-item">
                            <small>Booking Time</small>
                            <strong>
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                            </strong>
                        </div>

                        <div class="booking-item">
                            <small>Number of Guests</small>
                            <strong>{{ $booking->guest_count }} Guests</strong>
                        </div>

                        <div class="booking-item">
                            <small>Seating Preference</small>
                            <strong>{{ ucfirst($booking->seating_type ?? 'Standard') }}</strong>
                        </div>

                        <div class="booking-item">
                            <small>Customer Name</small>
                            <strong>{{ $booking->customer_name }}</strong>
                        </div>

                        <div class="booking-item">
                            <small>Contact Number</small>
                            <strong>{{ $booking->customer_mobile }}</strong>
                        </div>

                    </div>

                </div>

                <div class="success-note">
                    <strong>Reservation Update:</strong>

                    @if($booking->status === 'confirmed')
                        Your table reservation is confirmed. We look forward to welcoming you!
                    @elseif($booking->status === 'pending')
                        Your reservation is currently pending restaurant confirmation.
                        Please wait for the restaurant to confirm your booking.
                    @elseif($booking->status === 'cancelled')
                        This reservation has been cancelled. Please contact the restaurant for assistance.
                    @else
                        Your current reservation status is
                        {{ str_replace('_', ' ', $booking->status) }}.
                        Please contact the restaurant if you need assistance.
                    @endif
                </div>

                <div class="success-actions">

                    <a href="{{ route('customer.table-bookings') }}" class="success-btn success-btn-primary">
                        My Table Bookings
                    </a>

                    <a href="{{ route('home') }}" class="success-btn success-btn-secondary">
                        Explore Restaurants
                    </a>

                </div>

            </div>

        </div>
    </main>

@endsection