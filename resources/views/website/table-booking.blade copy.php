@extends('layouts.website')

@section('content')

    <style>
        .tb-page {
            --tb-red: #ff0718;
            color: #171717;
            background: #f7f7f8;
            padding: 0 0 50px;
            font-family: Arial, sans-serif;
            min-height: 80vh;
        }

        .tb-page * {
            box-sizing: border-box;
        }

        .tb-breadcrumb {
            padding: 18px 5%;
            font-size: 13px;
            background: #fff;
        }

        .tb-breadcrumb a {
            color: #333;
            text-decoration: none;
        }

        .tb-breadcrumb span {
            color: var(--tb-red);
            margin: 0 10px;
        }

        .tb-hero {
            padding: 55px 7%;
            color: #fff;
            background:
                linear-gradient(90deg, #080808 10%, #080808d9 65%, #08080888),
                url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1800&q=85') center/cover;
        }

        .tb-label {
            color: #ff3947;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        .tb-hero h1 {
            margin: 12px 0;
            font-size: clamp(32px, 4vw, 48px);
            font-weight: 800;
        }

        .tb-hero>div>p {
            max-width: 560px;
            color: #ddd;
            line-height: 1.7;
        }

        .tb-restaurant {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 25px;
        }

        .tb-restaurant>span {
            display: grid;
            place-items: center;
            width: 55px;
            height: 55px;
            border: 1px solid var(--tb-red);
            border-radius: 12px;
            font-size: 27px;
        }

        .tb-restaurant h2,
        .tb-restaurant p {
            margin: 4px 0;
        }

        .tb-restaurant h2 {
            font-size: 18px;
        }

        .tb-restaurant p {
            color: #ccc;
            font-size: 13px;
        }

        .tb-card {
            max-width: 1050px;
            margin: -25px auto 0;
            position: relative;
            padding: 32px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 35px #00000010;
        }

        .tb-card-heading {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 28px;
        }

        .tb-card-heading>span {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--tb-red);
            color: #fff;
            font-weight: 800;
        }

        .tb-card-heading h2 {
            margin: 0 0 5px;
            font-size: 23px;
        }

        .tb-card-heading p {
            margin: 0;
            color: #777;
            font-size: 13px;
        }

        .tb-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .tb-field {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-bottom: 20px;
        }

        .tb-field label {
            font-size: 13px;
            font-weight: 700;
        }

        .tb-field input,
        .tb-field select,
        .tb-field textarea {
            width: 100%;
            min-height: 48px;
            padding: 12px;
            border: 1px solid #dedee2;
            border-radius: 8px;
            background: #fff;
            color: #222;
            outline-color: var(--tb-red);
            font: inherit;
        }

        .tb-field textarea {
            resize: vertical;
        }

        .tb-full {
            margin-top: 5px;
        }

        .tb-submit {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 14px;
            width: 100%;
            padding: 16px;
            border: 0;
            border-radius: 8px;
            background: var(--tb-red);
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
        }

        .tb-submit:hover {
            background: #d90012;
        }

        .tb-note {
            margin: 15px 0 0;
            text-align: center;
            color: #777;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            .tb-hero {
                padding: 38px 20px 50px;
            }

            .tb-card {
                margin: -20px 12px 0;
                padding: 22px 16px;
            }

            .tb-form-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .tb-card-heading h2 {
                font-size: 20px;
            }

            .tb-breadcrumb {
                padding: 14px;
            }
        }
    </style>
<style>
    .tb-alert {
    padding: 16px;
    margin-bottom: 20px;
    border-radius: 10px;
    font-size: 14px;
}

.tb-alert p {
    margin: 8px 0 0;
}

.tb-alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

.tb-alert-error {
    color: #a40012;
    background: #fff0f1;
    border: 1px solid #ffc8ce;
}

.tb-alert-success {
    color: #185c37;
    background: #edfff4;
    border: 1px solid #b7ebca;
}
</style>

    <div class="tb-page">

        {{-- Breadcrumb --}}
        <div class="tb-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>›</span>
            <a href="{{ route('restaurant.details', ['slug' => $slug]) }}">
                Restaurant
            </a>
            <span>›</span>
            <strong>Book a Table</strong>
        </div>

        {{-- Restaurant Header --}}
        <section class="tb-hero">
            <div>
                <span class="tb-label">BAKASURAN DINING</span>
                <h1>Reserve Your Table</h1>
                <p>
                    Enjoy your favourite food in a premium dining experience.
                    Choose your date, time and number of guests.
                </p>

                <div class="tb-restaurant">
                    <span>🍽️</span>
                    <div>
                        <h2>{{ $restaurantName ?? 'Bakasuran Signature' }}</h2>
                        <p>Indian · Biryani · Family Dining</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Booking Form --}}
        <section class="tb-card">
            <div class="tb-card-heading">
                <span>01</span>
                <div>
                    <h2>Plan Your Visit</h2>
                    <p>Choose your preferred dining schedule.</p>
                </div>
            </div>

            <form action="{{ route('restaurant.check-availability', ['slug' => $slug]) }}" method="POST"
                id="tableBookingForm">
                @csrf

                @if ($errors->any())
                    <div class="tb-alert tb-alert-error">
                        <strong>Please check your details.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('availability_checked'))
                    <div class="tb-alert tb-alert-success">
                        <strong>Request received!</strong>
                        <p>
                            Your selected booking details have been validated.
                            Actual table availability has not yet been confirmed.
                        </p>

                        @php
                            $details = session('booking_details');
                        @endphp

                        <p>Date: {{ $details['booking_date'] }}</p>
                        <p>Time: {{ $details['booking_time'] }}</p>
                        <p>Guests: {{ $details['guests'] }}</p>
                        <p>Seating: {{ ucfirst($details['seating']) }}</p>
                    </div>
                @endif

                <div class="tb-form-grid">

                    <div class="tb-field">
                        <label for="booking_date">Booking Date</label>
                        <input type="date" id="booking_date" name="booking_date" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="tb-field">
                        <label for="booking_time">Preferred Time</label>
                        <select id="booking_time" name="booking_time" required>
                            <option value="">Select time</option>
                            <option value="12:00">12:00 PM</option>
                            <option value="13:00">1:00 PM</option>
                            <option value="14:00">2:00 PM</option>
                            <option value="18:00">6:00 PM</option>
                            <option value="19:00">7:00 PM</option>
                            <option value="20:00">8:00 PM</option>
                            <option value="21:00">9:00 PM</option>
                        </select>
                    </div>

                    <div class="tb-field">
                        <label for="guests">Number of Guests</label>
                        <select id="guests" name="guests" required>
                            <option value="">Select guests</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">
                                    {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="tb-field">
                        <label for="seating">Seating Preference</label>
                        <select id="seating" name="seating" required>
                            <option value="">Choose seating</option>
                            <option value="indoor">Indoor Dining</option>
                            <option value="outdoor">Outdoor Seating</option>
                            <option value="family">Family Seating</option>
                            <option value="private">Private Dining</option>
                        </select>
                    </div>

                </div>

                <div class="tb-field tb-full">
                    <label for="special_request">
                        Special Requests (Optional)
                    </label>
                    <textarea id="special_request" name="special_request" rows="3"
                        placeholder="Birthday celebration, preferred seating, accessibility needs..."></textarea>
                </div>

                <button type="submit" class="tb-submit">
                    Check Availability <span>→</span>
                </button>

                <p class="tb-note">
                    Your table is not confirmed until availability is checked
                    and your reservation is completed.
                </p>

            </form>
        </section>

    </div>

@endsection