
@extends('layouts.website')

@section('content')

<style>
    .tb-page {
        background: #fbf8f3;
        min-height: 100vh;
        padding: 45px 0 70px;
        color: #420916;
    }

    .tb-heading {
        margin-bottom: 25px;
    }

    .tb-heading h2 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 34px;
        font-weight: 700;
        color: #420916;
    }

    .tb-card {
        background: #fff;
        border: 1px solid #eadfce;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(66, 9, 22, .05);
    }

    .tb-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .tb-number {
        font-weight: 700;
        color: #d71313;
    }

    .tb-status {
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .tb-pending {
        background: #fff3cd;
        color: #856404;
    }

    .tb-confirmed {
        background: #d1e7dd;
        color: #0f5132;
    }

    .tb-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .tb-completed {
        background: #cfe2ff;
        color: #084298;
    }

    .tb-no-show {
        background: #e2e3e5;
        color: #41464b;
    }

    .tb-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 18px;
        margin: 20px 0;
    }

    .tb-detail-label {
        color: #8b8177;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .tb-detail-value {
        color: #420916;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .tb-message {
        background: #fbf3e5;
        border-left: 4px solid #c88618;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 13px;
        line-height: 1.6;
        color: #62584f;
    }

    .tb-empty {
        background: #fff;
        border: 1px solid #eadfce;
        border-radius: 16px;
        padding: 45px 20px;
        text-align: center;
    }

    .tb-empty h4 {
        color: #420916;
        margin-bottom: 10px;
    }

    .tb-empty a {
        display: inline-block;
        margin-top: 15px;
        background: #420916;
        color: #fff;
        padding: 11px 22px;
        border-radius: 8px;
        text-decoration: none;
    }

    .tb-pagination {
        margin-top: 25px;
    }

    /* BOOKING ACTION BUTTONS */

    .tb-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .tb-view-btn,
    .tb-dine-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .25s ease;
    }

    .tb-view-btn {
        background: #420916;
        color: #fff;
    }

    .tb-view-btn:hover {
        background: #641b27;
        color: #fff;
    }

    .tb-dine-btn {
        background: #a47724;
        color: #fff;
    }

    .tb-dine-btn:hover {
        background: #420916;
        color: #fff;
    }

    @media(max-width: 576px) {
        .tb-page {
            padding: 30px 0 50px;
        }

        .tb-card {
            padding: 18px;
        }

        .tb-heading h2 {
            font-size: 28px;
        }

        .tb-actions {
            justify-content: stretch;
        }

        .tb-view-btn,
        .tb-dine-btn {
            flex: 1;
            padding: 11px 12px;
        }
    }
</style>

<main class="tb-page">
    <div class="container">

        <div class="tb-heading">
            <p style="color:#a47724; font-size:12px; font-weight:700; letter-spacing:2px;">
                BAKASURAN FOOD
            </p>

            <h2>My Table Bookings</h2>

            <p>View your restaurant reservations and their latest status.</p>
        </div>

        @forelse($bookings as $booking)

            @php
                $status = strtolower($booking->status ?? 'pending');

                $messages = [
                    'pending' => 'Your reservation request is waiting for confirmation.',
                    'confirmed' => 'Your table reservation has been confirmed!',
                    'cancelled' => 'Your reservation has been cancelled. Please contact the restaurant for assistance.',
                    'completed' => 'Your reservation has been completed. Thank you for dining with us!',
                    'no_show' => 'Our records show that you did not arrive for your reservation. Please contact the restaurant if this is incorrect.',
                ];

                $statusClasses = [
                    'pending' => 'tb-pending',
                    'confirmed' => 'tb-confirmed',
                    'cancelled' => 'tb-cancelled',
                    'completed' => 'tb-completed',
                    'no_show' => 'tb-no-show',
                ];
            @endphp

            <div class="tb-card">

                <div class="tb-card-top">

                    <div class="tb-number">
                        Booking #{{ $booking->booking_number }}
                    </div>

                    <span class="tb-status {{ $statusClasses[$status] ?? 'tb-pending' }}">
                        {{ str_replace('_', ' ', $status) }}
                    </span>

                </div>

                <div class="tb-details">

                    <div>
                        <div class="tb-detail-label">Restaurant</div>
                        <div class="tb-detail-value">
                            {{ $booking->restaurant->name ?? 'Restaurant' }}
                        </div>
                    </div>

                    <div>
                        <div class="tb-detail-label">Table</div>
                        <div class="tb-detail-value">
                            {{ $booking->restaurantTable->table_number ?? 'Not available' }}
                        </div>
                    </div>

                    <div>
                        <div class="tb-detail-label">Booking Date</div>
                        <div class="tb-detail-value">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="tb-detail-label">Booking Time</div>
                        <div class="tb-detail-value">
                            {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                        </div>
                    </div>

                    <div>
                        <div class="tb-detail-label">Guests</div>
                        <div class="tb-detail-value">
                            {{ $booking->guest_count }}
                        </div>
                    </div>

                    <div>
                        <div class="tb-detail-label">Seating</div>
                        <div class="tb-detail-value">
                            {{ ucfirst($booking->seating_type ?? 'Standard') }}
                        </div>
                    </div>

                </div>

                <div class="tb-message">
                    <strong>Reservation Update:</strong>
                    {{ $messages[$status] ?? 'Please contact the restaurant for the latest reservation information.' }}
                </div>

                {{-- BOOKING AND DINE-IN ACTIONS --}}

                <div class="tb-actions">

                    <a href="{{ route('restaurant.booking-success', [
                        'slug' => $booking->restaurant->slug,
                        'bookingNumber' => $booking->booking_number
                    ]) }}"
                       class="tb-view-btn">

                        <i class="fas fa-calendar-check"></i>
                        VIEW BOOKING

                    </a>

                    @if(
                        $booking->status === 'confirmed' &&
                        $booking->check_in_status === 'checked_in'
                    )

                        <a href="{{ route('customer.dine-in.menu', $booking->booking_number) }}"
                           class="tb-dine-btn">

                            <i class="fas fa-utensils"></i>
                            OPEN DINE-IN MENU

                        </a>

                    @endif

                </div>

            </div>

        @empty

            <div class="tb-empty">

                <h4>No Table Bookings Yet</h4>

                <p>You haven't made any restaurant table reservations.</p>

                <a href="{{ route('home') }}">
                    Explore Restaurants
                </a>

            </div>

        @endforelse

        <div class="tb-pagination">
            {{ $bookings->links() }}
        </div>

    </div>
</main>

@endsection
