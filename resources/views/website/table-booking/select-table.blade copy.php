@extends('layouts.website')

@section('content')

    @php
        $selectedTableId = data_get($bookingDetails, 'table_id');
    @endphp

    <style>
        .table-page {
            min-height: 100vh;
            padding: 55px 18px 80px;
            color: #fff;
            background:
                radial-gradient(ellipse at 10% 10%, rgba(255, 30, 30, .12), transparent 35%),
                #101014;
        }

        .table-container {
            max-width: 1100px;
            margin: auto;
        }

        .table-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .table-header .eyebrow {
            color: #ff3030;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 4px;
        }

        .table-header h1 {
            margin: 12px 0;
            color: #fff;
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 900;
        }

        .table-header h1 span {
            color: #ff3030;
        }

        .table-header p {
            color: #aaa;
        }

        /* Progress */

        .table-progress {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .table-progress-item {
            padding: 14px;
            text-align: center;
            border: 1px solid #383842;
            border-radius: 12px;
            background: linear-gradient(145deg, #292932, #17171e);
            box-shadow: 0 5px 0 #09090d;
            color: #aaa;
            font-size: 12px;
            font-weight: 700;
        }

        .table-progress-item.active {
            color: #fff;
            border-color: #ff3030;
        }

        /* Summary */

        .booking-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .summary-item {
            padding: 17px;
            border: 1px solid #363640;
            border-radius: 13px;
            background: linear-gradient(145deg, #292932, #191920);
            box-shadow: 0 5px 0 #09090d;
        }

        .summary-item small {
            display: block;
            margin-bottom: 7px;
            color: #999;
            font-size: 11px;
        }

        .summary-item strong {
            color: #fff;
            font-size: 14px;
        }

        /* Floor */

        .floor-card {
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 22px;
            background: linear-gradient(145deg, #292932, #17171e);
            box-shadow: 0 20px 45px rgba(0, 0, 0, .5), 0 8px 0 #09090d;
        }

        .floor-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 25px;
        }

        .floor-heading h2 {
            margin: 0 0 7px;
            color: #fff;
            font-size: 21px;
            font-weight: 900;
        }

        .floor-heading p {
            margin: 0;
            color: #999;
            font-size: 12px;
        }

        .floor-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            color: #bbb;
            font-size: 11px;
        }

        .legend-dot {
            display: inline-block;
            width: 9px;
            height: 9px;
            margin-right: 5px;
            border-radius: 50%;
            background: #19b978;
        }

        .legend-dot.selected {
            background: #ff3030;
        }

        /* Restaurant floor surface */

        .restaurant-floor {
            position: relative;
            padding: 35px 22px;
            border: 1px solid #55515a;
            border-radius: 18px;
            background:
                linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(145deg, #24242b, #15151b);
            background-size: 35px 35px, 35px 35px, auto;
            box-shadow:
                inset 0 8px 20px rgba(0, 0, 0, .5),
                0 8px 0 #09090d;
        }

        .floor-label {
            width: fit-content;
            margin: 0 auto 30px;
            padding: 10px 28px;
            color: #fff;
            background: linear-gradient(145deg, #48434a, #25232a);
            border: 1px solid #77717a;
            border-radius: 9px;
            box-shadow: 0 5px 0 #111;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .table-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 30px 22px;
        }

        .table-option {
            position: relative;
            min-width: 0;
        }

        .table-radio {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .table-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 18px 10px;
            cursor: pointer;
            border: 1px solid #41414a;
            border-radius: 15px;
            background: linear-gradient(145deg, #30303a, #1a1a20);
            box-shadow:
                0 8px 0 #0b0b10,
                0 13px 20px rgba(0, 0, 0, .35);
            transition: .25s ease;
        }

        .table-label:hover {
            transform: translateY(-6px);
            border-color: #ff4848;
        }

        .table-radio:checked+.table-label {
            transform: translateY(-5px);
            border: 2px solid #ff3030;
            background: linear-gradient(145deg, #512326, #24171b);
            box-shadow:
                0 5px 0 #900d0d,
                0 15px 28px rgba(255, 30, 30, .2);
        }

        .table-radio:focus-visible+.table-label {
            outline: 2px solid white;
            outline-offset: 4px;
        }

        /* 3D table top */

        .table-3d {
            position: relative;
            display: grid;
            place-items: center;
            width: 92px;
            height: 70px;
            margin: 5px 0 17px;
            border-radius: 17px;
            color: #fff;
            font-size: 17px;
            font-weight: 900;
            background: linear-gradient(145deg, #b7a18a, #69513d);
            border: 2px solid #d1b99f;
            box-shadow:
                0 9px 0 #493727,
                0 15px 20px rgba(0, 0, 0, .45),
                inset 0 3px 7px rgba(255, 255, 255, .3);
            transform: rotateX(12deg);
            transition: .25s ease;
        }

        .table-radio:checked+.table-label .table-3d {
            background: linear-gradient(145deg, #ff5555, #a80000);
            border-color: #ff9999;
            box-shadow:
                0 9px 0 #6c0000,
                0 15px 20px rgba(255, 0, 0, .25);
        }

        .table-3d::before,
        .table-3d::after {
            content: "";
            position: absolute;
            bottom: -13px;
            width: 9px;
            height: 14px;
            background: #614831;
            border-radius: 3px;
            box-shadow: 0 3px 0 #322315;
        }

        .table-3d::before {
            left: 13px;
        }

        .table-3d::after {
            right: 13px;
        }

        .table-name {
            color: #fff;
            font-size: 14px;
            font-weight: 900;
        }

        .table-capacity {
            margin-top: 5px;
            color: #aaa;
            font-size: 11px;
        }

        .table-seating {
            margin-top: 7px;
            color: #ff7777;
            font-size: 10px;
            text-transform: capitalize;
        }

        /* Selection */

        .selection-message {
            margin-top: 25px;
            padding: 17px;
            color: #ddd;
            border: 1px solid #49414a;
            border-radius: 12px;
            background: #202027;
            font-size: 13px;
        }

        .table-submit {
            width: 100%;
            min-height: 56px;
            margin-top: 25px;
            color: #fff;
            background: linear-gradient(135deg, #ff4141, #bd0000);
            border: 0;
            border-radius: 12px;
            box-shadow: 0 7px 0 #780000, 0 15px 25px rgba(255, 0, 0, .2);
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .2s ease;
        }

        .table-submit:hover {
            transform: translateY(-3px);
        }

        .continue-link {
            display: block;
            margin-top: 18px;
            padding: 17px;
            text-align: center;
            color: white;
            background: #25252d;
            border: 1px solid #ff3030;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .continue-link:hover {
            color: white;
            background: #3a2023;
        }

        .no-tables {
            padding: 35px;
            text-align: center;
            color: #ffb4b4;
            border: 1px solid #773333;
            border-radius: 12px;
            background: #351c22;
        }

        @media(max-width: 750px) {
            .booking-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 25px 12px;
            }

            .floor-card {
                padding: 20px 14px;
            }

            .floor-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media(max-width: 420px) {
            .table-grid {
                grid-template-columns: 1fr 1fr;
            }

            .table-3d {
                width: 75px;
                height: 62px;
            }
        }

        .back-booking-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
    padding: 13px 22px;
    color: #fff;
    background: #25252d;
    border: 1px solid #ff3030;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    transition: .25s ease;
}

.back-booking-btn:hover {
    color: #fff;
    background: #512326;
    transform: translateY(-2px);
}
    </style>

    <div class="table-page">

        <div class="table-container">

            <div class="table-header">
                <div class="eyebrow">BAKASURAN EXPERIENCE</div>
                <h1>Select Your <span>Table</span></h1>
                <p>Choose your preferred table at {{ $restaurantName }}</p>
            </div>

            <a href="{{ route('restaurant.table-booking', ['slug' => $slug]) }}" class="back-booking-btn">
                ← BACK TO EDIT BOOKING DETAILS
            </a>

            <div class="table-progress">
                <div class="table-progress-item">01 · Booking Details</div>
                <div class="table-progress-item active">02 · Select Table</div>
                <div class="table-progress-item">03 · Confirmation</div>
            </div>

            {{-- BOOKING SUMMARY --}}
            <div class="booking-summary">

                <div class="summary-item">
                    <small>Booking Date</small>
                    <strong>{{ $bookingDetails['booking_date'] }}</strong>
                </div>

                <div class="summary-item">
                    <small>Booking Time</small>
                    <strong>{{ \Carbon\Carbon::parse($bookingDetails['booking_time'])->format('h:i A') }}</strong>
                </div>

                <div class="summary-item">
                    <small>Guests</small>
                    <strong>{{ $bookingDetails['guests'] }}</strong>
                </div>

                <div class="summary-item">
                    <small>Seating</small>
                    <strong>{{ ucfirst($bookingDetails['seating']) }}</strong>
                </div>

            </div>

            <div class="floor-card">

                <div class="floor-heading">
                    <div>
                        <h2>Restaurant Floor Plan</h2>
                        <p>Select one of the available tables below.</p>
                    </div>

                    <div class="floor-legend">
                        <span><i class="legend-dot"></i> Available</span>
                        <span><i class="legend-dot selected"></i> Selected</span>
                    </div>
                </div>

                @if($availableTables->isEmpty())

                    <div class="no-tables">
                        <h3>No tables available</h3>
                        <p>Please return and check another date or time.</p>

                        <a href="{{ route('restaurant.table-booking', ['slug' => $slug]) }}" class="continue-link">
                            Change Booking Details
                        </a>
                    </div>

                @else

                    <div class="restaurant-floor">

                        <div class="floor-label">
                            RESTAURANT ENTRANCE
                        </div>

                        <form action="{{ route('restaurant.check-availability', ['slug' => $slug]) }}" method="POST"
                            id="selectTableForm">

                            @csrf

                            <input type="hidden" name="booking_date" value="{{ $bookingDetails['booking_date'] }}">

                            <input type="hidden" name="booking_time" value="{{ $bookingDetails['booking_time'] }}">

                            <input type="hidden" name="guests" value="{{ $bookingDetails['guests'] }}">

                            <input type="hidden" name="seating" value="{{ $bookingDetails['seating'] }}">

                            <input type="hidden" name="special_request" value="{{ $bookingDetails['special_request'] ?? '' }}">

                            <div class="table-grid">

                                @foreach($availableTables as $table)

                                    @php
                                        $isSelected = (string) $selectedTableId === (string) $table->id;
                                    @endphp

                                    <div class="table-option">

                                        <input class="table-radio" type="radio" name="table_id" id="table_{{ $table->id }}"
                                            value="{{ $table->id }}" {{ $isSelected ? 'checked' : '' }} required>

                                        <label class="table-label" for="table_{{ $table->id }}">

                                            <div class="table-3d">
                                                {{ $table->table_number }}
                                            </div>

                                            <div class="table-name">
                                                Table {{ $table->table_number }}
                                            </div>

                                            <div class="table-capacity">
                                                {{ $table->seating_capacity }} Guests
                                            </div>

                                            <div class="table-seating">
                                                {{ $table->seating_type }}
                                            </div>

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                            <div class="selection-message" id="selectionMessage">
                                Select a table to continue.
                            </div>

                            <button type="submit" class="table-submit">
                                CONFIRM TABLE SELECTION →
                            </button>

                        </form>

                    </div>

                @endif

                {{-- Continue only after a table has been checked and stored --}}
                @if(!empty($selectedTableId))
                    <a href="{{ route('restaurant.confirm-page', ['slug' => $slug]) }}" class="continue-link">
                        CONTINUE TO CUSTOMER DETAILS →
                    </a>
                @endif

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const tableOptions = document.querySelectorAll('.table-radio');
            const message = document.getElementById('selectionMessage');

            function updateSelection() {

                const selected = document.querySelector('.table-radio:checked');

                if (!selected || !message) {
                    return;
                }

                const label = document.querySelector(
                    'label[for="' + selected.id + '"]'
                );

                const tableName = label.querySelector('.table-name').textContent;
                const capacity = label.querySelector('.table-capacity').textContent;

                message.textContent =
                    tableName + ' selected · ' + capacity;
            }

            tableOptions.forEach(function (radio) {
                radio.addEventListener('change', updateSelection);
            });

            updateSelection();

        });
    </script>

@endsection