@extends('layouts.website')

@section('content')

    @php

        $bookingTables = collect($availableTables ?? []);

        $bookingDetails = $bookingDetails ?? session('booking_details', []);

        $bookingDateValue = old('booking_date', data_get($bookingDetails ?? [], 'booking_date'));

        $guestValue = old('guests', data_get($bookingDetails ?? [], 'guests'));

        $timeValue = old('booking_time', data_get($bookingDetails ?? [], 'booking_time'));

        $timeValue = $timeValue ? substr((string) $timeValue, 0, 5) : '';

        $seatingValue = old('seating', data_get($bookingDetails ?? [], 'seating'));

        $specialRequestValue = old('special_request', data_get($bookingDetails ?? [], 'special_request'));

        $oldTableId = old('table_id', data_get($bookingDetails ?? [], 'table_id'));

        $availabilityChecked = $availabilityChecked ?? session('availability_checked', false);

        $bookingEnabled = (bool) ($settings->is_booking_enabled ?? false);

        $minGuests = (int) ($settings->min_guests ?? 1);

        $maxGuests = (int) ($settings->max_guests ?? 12);

        $slotDuration = (int) ($settings->slot_duration ?? 60);

        $hoursForJs = ($bookingHours ?? collect())->keyBy('day_of_week')->map(function ($hour) {

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

    @endphp

    <style>
        :root {

            --tb-red: #ff1010;

            --tb-red-dark: #b80000;

            --tb-black: #080808;

            --tb-charcoal: #171717;

            --tb-text: #242424;

            --tb-muted: #777;

            --tb-border: #e8e8e8;

            --tb-soft: #f7f7f7;

            --tb-white: #fff;

        }

        .tb-page,
        .tb-page * {
            box-sizing: border-box;
        }

        .tb-page {

            color: var(--tb-text);

            background: #f6f6f6;

            min-height: 80vh;

            padding-bottom: 70px;

            font-family: inherit;

        }

        .tb-page button,
        .tb-page input,
        .tb-page select,
        .tb-page textarea {
            font: inherit;
        }

        .tb-shell {
            width: min(1280px, calc(100% - 48px));
            margin: 0 auto;
        }

        .tb-breadcrumb {

            padding: 17px 0;

            color: #777;

            font-size: 13px;

        }

        .tb-breadcrumb a {
            color: #444;
            text-decoration: none;
        }

        .tb-breadcrumb a:hover {
            color: var(--tb-red);
        }

        .tb-breadcrumb span {
            margin: 0 9px;
            color: var(--tb-red);
        }

        .tb-hero {

            position: relative;

            overflow: hidden;

            min-height: 285px;

            display: flex;

            align-items: center;

            color: #fff;

            background:

                linear-gradient(90deg, rgba(0, 0, 0, .94) 0%, rgba(0, 0, 0, .82) 48%, rgba(0, 0, 0, .2) 100%),

                url('https\://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=2000&q=90') center 52%/cover;

        }

        .tb-hero::after {

            content: "";

            position: absolute;

            inset: auto 0 0;

            height: 4px;

            background: linear-gradient(90deg, var(--tb-red), #ff7777, transparent);

        }

        .tb-hero-inner {
            position: relative;
            z-index: 1;
            width: min(1280px, calc(100% - 48px));
            margin: auto;
            padding: 45px 0;
        }

        .tb-eyebrow {

            display: inline-flex;

            align-items: center;

            gap: 10px;

            color: #ff4545;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 3px;

            text-transform: uppercase;

        }

        .tb-eyebrow::before {
            content: "";
            width: 28px;
            height: 2px;
            background: var(--tb-red);
        }

        .tb-hero h1 {
            margin: 13px 0 10px;
            max-width: 700px;
            color: #fff;
            font-size: clamp(34px, 4.5vw, 56px);
            line-height: 1.08;
            font-weight: 800;
        }

        .tb-hero h1 span {
            color: var(--tb-red);
        }

        .tb-hero-copy {
            max-width: 570px;
            margin: 0;
            color: #dedede;
            font-size: 15px;
            line-height: 1.8;
        }

        .tb-hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            margin-top: 25px;
        }

        .tb-hero-meta span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #f2f2f2;
            font-size: 13px;
        }

        .tb-hero-meta b {
            color: var(--tb-red);
            font-size: 17px;
        }

        .tb-progress-wrap {
            position: relative;
            z-index: 2;
            margin-top: -27px;
        }

        .tb-progress {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 10px;

            padding: 18px 24px;

            background: #fff;

            border: 1px solid #eee;

            border-radius: 16px;

            box-shadow: 0 14px 40px rgba(0, 0, 0, .09);

        }

        .tb-progress-step {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #888;
            font-size: 13px;
            font-weight: 700;
        }

        .tb-progress-step:not(:last-child) {
            border-right: 1px solid #eee;
        }

        .tb-step-number {
            display: grid;
            place-items: center;
            flex: 0 0 34px;
            height: 34px;
            border-radius: 50%;
            background: #eee;
            color: #777;
            font-weight: 800;
        }

        .tb-progress-step.is-active {
            color: #171717;
        }

        .tb-progress-step.is-active .tb-step-number {
            color: #fff;
            background: var(--tb-red);
            box-shadow: 0 5px 14px rgba(255, 16, 16, .25);
        }

        .tb-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 24px;
            align-items: start;
            margin-top: 25px;
        }

        .tb-main-card,
        .tb-summary {

            background: #fff;

            border: 1px solid #eaeaea;

            border-radius: 18px;

            box-shadow: 0 12px 35px rgba(0, 0, 0, .045);

        }

        .tb-main-card {
            padding: 28px;
        }

        .tb-section-heading {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-bottom: 24px;
        }

        .tb-section-icon {
            display: grid;
            place-items: center;
            flex: 0 0 43px;
            height: 43px;
            border-radius: 12px;
            background: #fff0f0;
            color: var(--tb-red);
            font-size: 20px;
            font-weight: 800;
        }

        .tb-section-heading h2 {
            margin: 0 0 5px;
            color: #171717;
            font-size: 22px;
            font-weight: 800;
        }

        .tb-section-heading p {
            margin: 0;
            color: #777;
            font-size: 13px;
            line-height: 1.6;
        }

        .tb-alert {
            padding: 15px 17px;
            margin-bottom: 20px;
            border-radius: 12px;
            font-size: 13px;
        }

        .tb-alert p {
            margin: 7px 0 0;
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

        .tb-form-section {
            padding: 0 0 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .tb-form-section:last-of-type {
            border-bottom: 0;
        }

        .tb-subheading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 0 0 16px;
            color: #222;
            font-size: 15px;
            font-weight: 800;
        }

        .tb-subheading span {
            color: var(--tb-red);
            font-size: 17px;
        }

        .tb-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
        }

        .tb-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .tb-field label {
            color: #333;
            font-size: 12px;
            font-weight: 800;
        }

        .tb-field input,
        .tb-field select,
        .tb-field textarea {

            width: 100%;
            min-height: 49px;
            padding: 12px 14px;

            color: #222;
            background: #fff;
            border: 1px solid #dedede;

            border-radius: 10px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;

        }

        .tb-field input:focus,
        .tb-field select:focus,
        .tb-field textarea:focus {

            border-color: var(--tb-red);
            box-shadow: 0 0 0 3px rgba(255, 16, 16, .09);

        }

        .tb-field textarea {
            min-height: 95px;
            resize: vertical;
        }

        .tb-field small {
            color: #888;
            font-size: 11px;
        }

        .tb-time-options {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 9px;
        }

        .tb-time-option {
            position: relative;
        }

        .tb-time-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .tb-time-option label {

            display: grid;
            place-items: center;
            min-height: 45px;
            padding: 8px 5px;

            border: 1px solid #e1e1e1;
            border-radius: 9px;
            color: #444;

            background: #fff;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;

            transition: .2s ease;

        }

        .tb-time-option input:checked+label {

            color: #fff;
            border-color: var(--tb-red);
            background: var(--tb-red);

            box-shadow: 0 5px 14px rgba(255, 16, 16, .2);

        }

        .tb-time-option label:hover {
            border-color: var(--tb-red);
            transform: translateY(-2px);
        }

        .tb-seating-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .tb-seating-option {
            position: relative;
            min-width: 0;
        }

        .tb-seating-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .tb-seating-card {

            display: flex;
            gap: 13px;
            align-items: center;
            min-height: 112px;
            padding: 12px;

            border: 1px solid #e4e4e4;
            border-radius: 13px;
            background: #fff;
            cursor: pointer;

            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;

        }

        .tb-seating-card:hover {
            transform: translateY(-3px);
            border-color: #ff8a8a;
            box-shadow: 0 9px 22px rgba(0, 0, 0, .06);
        }

        .tb-seating-option input:checked+.tb-seating-card {

            border: 2px solid var(--tb-red);
            padding: 11px;

            background: linear-gradient(135deg, #fff, #fff4f4);

            box-shadow: 0 8px 22px rgba(255, 16, 16, .1);

        }

        .tb-seating-art {

            display: grid;
            place-items: center;
            flex: 0 0 76px;
            height: 82px;

            overflow: hidden;
            border-radius: 10px;
            color: #fff;
            font-size: 28px;

            background: linear-gradient(145deg, #333, #090909);

        }

        .tb-seating-art.indoor {
            background: linear-gradient(145deg, #5b2525, #171717);
        }

        .tb-seating-art.outdoor {
            background: linear-gradient(145deg, #3f6547, #17291c);
        }

        .tb-seating-art.family {
            background: linear-gradient(145deg, #76502d, #2b1a10);
        }

        .tb-seating-art.private {
            background: linear-gradient(145deg, #4b4053, #17131d);
        }

        .tb-seating-info {
            min-width: 0;
            flex: 1;
        }

        .tb-seating-info strong {
            display: block;
            margin-bottom: 5px;
            color: #222;
            font-size: 14px;
        }

        .tb-seating-info small {
            display: block;
            color: #777;
            font-size: 11px;
            line-height: 1.5;
        }

        .tb-seating-info em {
            display: block;
            margin-top: 7px;
            color: var(--tb-red-dark);
            font-size: 11px;
            font-style: normal;
            font-weight: 800;
        }

        .tb-seating-check {
            display: grid;
            place-items: center;
            flex: 0 0 21px;
            width: 21px;
            height: 21px;
            border: 1px solid #ccc;
            border-radius: 50%;
            color: transparent;
            font-size: 12px;
        }

        .tb-seating-option input:checked+.tb-seating-card .tb-seating-check {
            color: #fff;
            border-color: var(--tb-red);
            background: var(--tb-red);
        }

        .tb-summary {
            position: sticky;
            top: 24px;
            overflow: hidden;
        }

        .tb-summary-head {
            padding: 21px 22px;
            color: #fff;
            background: var(--tb-black);
        }

        .tb-summary-head small {
            display: block;
            margin-bottom: 5px;
            color: #ff7777;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .tb-summary-head h3 {
            margin: 0;
            color: #fff;
            font-size: 19px;
            font-weight: 800;
        }

        .tb-summary-body {
            padding: 20px;
        }

        .tb-summary-restaurant {
            display: flex;
            align-items: center;
            gap: 11px;
            padding-bottom: 17px;
            border-bottom: 1px solid #eee;
        }

        .tb-summary-logo {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 11px;
            color: #fff;
            background: var(--tb-red);
            font-size: 20px;
        }

        .tb-summary-restaurant strong {
            display: block;
            font-size: 13px;
        }

        .tb-summary-restaurant small {
            display: block;
            margin-top: 4px;
            color: #888;
            font-size: 11px;
        }

        .tb-summary-list {
            display: grid;
            gap: 14px;
            padding: 18px 0;
        }

        .tb-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
        }

        .tb-summary-row span {
            color: #777;
        }

        .tb-summary-row strong {
            color: #222;
            text-align: right;
            font-weight: 800;
        }

        .tb-summary-divider {
            height: 1px;
            background: #eee;
        }

        .tb-summary-note {
            margin: 15px 0 0;
            color: #888;
            font-size: 11px;
            line-height: 1.6;
        }

        .tb-submit {

            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;

            width: 100%;
            min-height: 53px;
            margin-top: 17px;
            padding: 14px 18px;

            color: #fff;
            background: var(--tb-red);
            border: 0;
            border-radius: 10px;

            font-size: 14px;
            font-weight: 800;
            cursor: pointer;

            box-shadow: 0 8px 18px rgba(255, 16, 16, .2);
            transition: .2s ease;

        }

        .tb-submit:hover {
            background: var(--tb-red-dark);
            transform: translateY(-2px);
            box-shadow: 0 11px 22px rgba(255, 16, 16, .25);
        }

        .tb-submit:disabled {
            opacity: .65;
            cursor: wait;
            transform: none;
        }

        .tb-trust {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 13px;
            color: #888;
            font-size: 10px;
            text-align: center;
        }

        /* Interactive 3D table availability map */

        .tb-floor-section {
            padding: 0 0 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .tb-floor-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .tb-floor-top p {
            margin: 5px 0 0;
            color: #777;
            font-size: 12px;
        }

        .tb-floor-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
            align-items: center;
        }

        .tb-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #666;
            font-size: 11px;
            font-weight: 700;
        }

        .tb-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .tb-legend-dot.available {
            background: #20a653;
            box-shadow: 0 0 0 3px #e3f7e9;
        }

        .tb-legend-dot.unavailable {
            background: #e32929;
            box-shadow: 0 0 0 3px #ffe5e5;
        }

        .tb-legend-dot.selected {
            background: #ff1010;
            box-shadow: 0 0 0 3px #ffe0e0;
        }

        .tb-floor-plan {

            position: relative;
            min-height: 360px;
            padding: 35px 25px;

            border: 1px solid #e6e6e6;
            border-radius: 16px;
            overflow: hidden;

            background:

                radial-gradient(circle at 12% 15%, rgba(255, 255, 255, .95) 0 2px, transparent 3px),

                linear-gradient(135deg, #f3f0ed, #e7e1dc 48%, #f5f1ed);

            box-shadow: inset 0 0 35px rgba(0, 0, 0, .06);

        }

        .tb-floor-plan:before {

            content: "";
            position: absolute;
            inset: 13px;
            border: 2px dashed rgba(80, 60, 50, .16);

            border-radius: 12px;
            pointer-events: none;

        }

        .tb-floor-plan:after {

            content: "DINING AREA";
            position: absolute;
            top: 17px;
            left: 50%;
            transform: translateX(-50%);

            color: rgba(65, 50, 45, .42);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 3px;

        }

        .tb-table-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 30px 22px;
            align-items: center;
            justify-items: center;
            padding-top: 20px;
        }

        .tb-table-choice {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 150px;
        }

        .tb-table-button {

            position: relative;
            width: 100%;
            min-height: 112px;
            padding: 0;
            border: 0;
            background: transparent;

            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            transition: transform .22s ease, filter .22s ease;

        }

        .tb-table-button:not(:disabled):hover {
            transform: translateY(-5px);
            filter: drop-shadow(0 9px 8px rgba(0, 0, 0, .16));
        }

        .tb-table-button:disabled {
            cursor: not-allowed;
            opacity: .82;
        }

        .tb-table-top {

            position: relative;
            z-index: 2;
            width: 75px;
            height: 58px;
            display: flex;
            flex-direction: column;

            justify-content: center;
            align-items: center;
            border-radius: 13px;

            color: #fff;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .3px;

            border: 2px solid rgba(255, 255, 255, .8);

            box-shadow: 0 8px 0 rgba(0, 0, 0, .13), 0 12px 20px rgba(0, 0, 0, .12), inset 0 2px 5px rgba(255, 255, 255, .28);

            transform: perspective(180px) rotateX(8deg);

        }

        .tb-table-top small {
            display: block;
            margin-top: 3px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0;
        }

        .tb-table-seat {
            position: absolute;
            width: 23px;
            height: 23px;
            border-radius: 7px;
            border: 1px solid rgba(0, 0, 0, .12);
            background: linear-gradient(145deg, #fff, #cfcfcf);
            box-shadow: 0 3px 4px rgba(0, 0, 0, .17);
        }

        .tb-seat-top {
            top: 5px;
            left: calc(50% - 11px);
        }

        .tb-seat-bottom {
            bottom: 5px;
            left: calc(50% - 11px);
        }

        .tb-seat-left {
            left: calc(50% - 51px);
            top: calc(50% - 11px);
        }

        .tb-seat-right {
            right: calc(50% - 51px);
            top: calc(50% - 11px);
        }

        .tb-table-choice.capacity-6 .tb-seat-extra-left {
            left: calc(50% - 44px);
            top: 4px;
        }

        .tb-table-choice.capacity-6 .tb-seat-extra-right {
            right: calc(50% - 44px);
            top: 4px;
        }

        .tb-table-button.is-available .tb-table-top {
            background: linear-gradient(145deg, #35c96e, #12833b);
        }

        .tb-table-button.is-unavailable .tb-table-top {
            background: linear-gradient(145deg, #ff6565, #bd1717);
        }

        .tb-table-button.is-selected .tb-table-top {
            background: linear-gradient(145deg, #ff4a4a, #c90000);
            outline: 4px solid rgba(255, 16, 16, .16);
            box-shadow: 0 8px 0 rgba(100, 0, 0, .2), 0 0 0 3px #fff, 0 0 0 6px #ff1010, 0 15px 25px rgba(255, 16, 16, .25);
        }

        .tb-table-status {
            color: #555;
            font-size: 10px;
            font-weight: 800;
            text-align: center;
        }

        .tb-table-status.available {
            color: #16813b;
        }

        .tb-table-status.unavailable {
            color: #c51d1d;
        }

        .tb-table-status.selected {
            color: #d00000;
        }

        .tb-table-button:focus-visible {
            outline: 3px solid #111;
            outline-offset: 6px;
            border-radius: 12px;
        }

        .tb-floor-empty {
            position: relative;
            z-index: 1;
            padding: 70px 15px;
            text-align: center;
            color: #777;
        }

        .tb-floor-empty strong {
            display: block;
            margin-bottom: 7px;
            color: #222;
            font-size: 15px;
        }

        .tb-floor-empty span {
            font-size: 12px;
            line-height: 1.6;
        }

        .tb-selected-table-note {
            margin-top: 13px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f7f7f7;
            color: #555;
            font-size: 12px;
        }

        .tb-selected-table-note strong {
            color: #c90000;
        }

        @media(max-width:600px) {

            .tb-floor-plan {
                padding: 30px 12px;
                min-height: 320px;
            }

            .tb-table-grid {
                gap: 18px 8px;
            }

            .tb-table-choice {
                max-width: 105px;
            }

            .tb-table-top {
                width: 65px;
                height: 51px;
            }

            .tb-table-seat {
                width: 18px;
                height: 18px;
            }

            .tb-seat-left {
                left: calc(50% - 42px);
            }

            .tb-seat-right {
                right: calc(50% - 42px);
            }

        }

        @media (max-width: 1000px) {

            .tb-layout {
                grid-template-columns: minmax(0, 1fr) 290px;
                gap: 16px;
            }

            .tb-main-card {
                padding: 22px;
            }

            .tb-time-options {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

        }

        @media (max-width: 760px) {

            .tb-shell,
            .tb-hero-inner {
                width: min(100% - 28px, 600px);
            }

            .tb-hero {
                min-height: 260px;
                background-position: 60% center;
            }

            .tb-hero-inner {
                padding: 35px 0 48px;
            }

            .tb-hero-meta {
                gap: 12px;
            }

            .tb-progress {
                padding: 13px 10px;
                gap: 5px;
            }

            .tb-progress-step {
                gap: 6px;
                font-size: 10px;
            }

            .tb-step-number {
                flex-basis: 28px;
                height: 28px;
            }

            .tb-layout {
                grid-template-columns: 1fr;
            }

            .tb-summary {
                position: static;
                grid-row: 1;
            }

            .tb-main-card {
                padding: 20px 16px;
            }

        }

        @media (max-width: 480px) {

            .tb-form-grid,
            .tb-seating-grid {
                grid-template-columns: 1fr;
            }

            .tb-time-options {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tb-progress-step {
                flex-direction: column;
                text-align: center;
            }

            .tb-progress-step:not(:last-child) {
                border-right: 0;
            }

            .tb-section-heading h2 {
                font-size: 19px;
            }

            .tb-seating-art {
                flex-basis: 68px;
                height: 72px;
            }

        }

        .tb-step-panel {
            animation: tbStepIn .28s ease both;
        }

        .tb-step-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 4px 0 25px;
        }

        .tb-step-heading h2 {
            margin: 0 0 5px;
            font-size: 23px;
            font-weight: 800;
            color: #171717;
        }

        .tb-step-heading p {
            margin: 0;
            color: #777;
            font-size: 13px;
        }

        .tb-step-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .tb-step-submit {
            max-width: 360px;
            margin-left: auto;
        }

        .tb-secondary-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            color: #333;
            font-weight: 800;
            cursor: pointer;
        }

        .tb-secondary-button:hover {
            border-color: var(--tb-red);
            color: var(--tb-red);
        }

        .tb-recheck-button {
            width: 100%;
            margin-top: 10px;
        }

        .tb-step-actions-between {
            justify-content: space-between;
            align-items: center;
        }

        .tb-confirm-card {
            padding: 22px;
            border: 1px solid #eee;
            border-radius: 14px;
            background: #fff;
        }

        .tb-confirm-card h3 {
            margin: 0 0 18px;
            font-size: 17px;
            font-weight: 800;
        }

        .tb-confirm-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .tb-confirm-grid div {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .tb-confirm-grid small {
            color: #888;
            font-size: 11px;
        }

        .tb-confirm-grid strong {
            color: #222;
            font-size: 14px;
        }

        .tb-confirm-notice {
            margin-top: 18px;
            padding: 14px;
            border-radius: 10px;
            background: #fff7e8;
            color: #815500;
            font-size: 12px;
            line-height: 1.6;
        }

        .tb-customer-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 22px;
        }

        .tb-customer-fields label {
            font-weight: 800;
            font-size: 12px;
        }

        .tb-customer-fields input {
            margin-top: -6px;
        }

        .tb-confirm-footnote {
            margin-top: 12px;
            color: #888;
            font-size: 11px;
            line-height: 1.6;
        }

        .tb-progress-step.is-complete .tb-step-number {
            color: #fff;
            background: #16813b;
        }

        @keyframes tbStepIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media(max-width:600px) {

            .tb-confirm-grid,
            .tb-customer-fields {
                grid-template-columns: 1fr;
            }

            .tb-step-actions-between {
                flex-direction: column;
                align-items: stretch;
            }

            .tb-step-actions-between button {
                width: 100%;
            }

        }
    </style>

    <div class="tb-page">

        <div class="tb-shell">

            <nav class="tb-breadcrumb" aria-label="Breadcrumb">

                <a href="{{ route('home') }}">Home</a>

                <span>›</span>

                <a href="{{ route('restaurant.details', ['slug' => $slug]) }}">Restaurant</a>

                <span>›</span>

                <strong>Book a Table</strong>

            </nav>

        </div>

        <section class="tb-hero">

            <div class="tb-hero-inner">

                <span class="tb-eyebrow">BAKASURAN DINING EXPERIENCE</span>

                <h1>Make it a meal<br>to <span>remember.</span></h1>

                <p class="tb-hero-copy">Choose your preferred date, time and seating. We’ll check availability at your
                    selected restaurant.</p>

                <div class="tb-hero-meta">

                    <span><b>✦</b> Great food, great moments</span>

                    <span><b>✦</b> Easy reservations</span>

                    <span><b>✦</b> Family friendly</span>

                </div>

            </div>

        </section>

        <div class="tb-shell">

            <div class="tb-progress-wrap">

                <div class="tb-progress">

                    <div class="tb-progress-step {{ !$availabilityChecked ? 'is-active' : 'is-complete' }}"
                        data-progress-step="1"><span class="tb-step-number">01</span><span>Choose your visit</span></div>

                    <div class="tb-progress-step {{ $availabilityChecked ? 'is-active' : '' }}" data-progress-step="2"><span
                            class="tb-step-number">02</span><span>Check availability</span></div>

                    <div class="tb-progress-step" data-progress-step="3"><span class="tb-step-number">03</span><span>Confirm
                            reservation</span></div>

                </div>

            </div>

            <form action="{{ route('restaurant.check-availability', ['slug' => $slug]) }}" method="POST"
                id="tableBookingForm">

                @csrf

                <input type="hidden" name="table_id" id="selected_table_id" value="{{ $oldTableId }}">

                <div class="tb-layout">

                    <main class="tb-main-card">

                        <!-- <div class="tb-section-heading" style="{{ $availabilityChecked ? 'display:none;' : '' }}">

                            <span class="tb-section-icon">01</span>

                            <div>

                                <h2>Plan your visit</h2>

                                <p>Tell us when you’re coming and how you’d like to dine.</p>

                            </div>

                        </div> -->

                        @if ($errors->any())

                            <div class="tb-alert tb-alert-error" role="alert">

                                <strong>Please check your booking details.</strong>

                                <ul>

                                    @foreach ($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        @if (session('success'))
    <div class="tb-alert tb-alert-success" role="alert">
        <strong>Reservation Submitted Successfully!</strong>
        <p>{{ session('success') }}</p>
        <p>Your reservation is waiting for restaurant confirmation.</p>
    </div>
@endif

                        @if (session('availability_checked'))

                            <div class="tb-alert tb-alert-success" role="status">

                                <strong>Your availability request was received.</strong>

                                <p>Your details were validated. This message does not mean a table is confirmed; confirmation
                                    depends on the availability check and reservation process.</p>

                                @php $details = session('booking_details', []); @endphp

                                @if (!empty($details))

                                    <p>

                                        Date: {{ $details['booking_date'] ?? '—' }} ·

                                        Time: {{ $details['booking_time'] ?? '—' }} ·

                                        Guests: {{ $details['guests'] ?? '—' }} ·

                                        Seating: {{ ucfirst($details['seating'] ?? '—') }}

                                    </p>

                                @endif

                            </div>

                        @endif

                        <div class="tb-step-panel" id="bookingStep1"
                            style="{{ $availabilityChecked ? 'display:none;' : '' }}">

                            <div class="tb-step-heading">

                                <span class="tb-section-icon">01</span>

                                <div>
                                    <h2>Plan your visit</h2>
                                    <p>Choose your date, time, guest count and preferred seating.</p>
                                </div>

                            </div>

                            

                            <section class="tb-form-section">

                                <h3 class="tb-subheading"><span>◷</span> Date &amp; time</h3>

                                <div class="tb-form-grid">

                                    <div class="tb-field">

                                        <label for="booking_date">Booking date</label>

                                        <input type="date" id="booking_date" name="booking_date" min="{{ date('Y-m-d') }}"
                                            value="{{ $bookingDateValue }}" required>

                                    </div>

                                    <div class="tb-field">

                                        <label for="guests">Number of guests</label>

                                        <select id="guests" name="guests" required>

                                            <option value="">Select guests</option>

                                            @for ($i = $minGuests; $i <= $maxGuests; $i++)

                                                <option value="{{ $i }}" {{ (string) $guestValue === (string) $i ? 'selected' : '' }}>

                                                    {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}

                                                </option>

                                            @endfor

                                        </select>

                                        <small>Choose between {{ $minGuests }} and {{ $maxGuests }} guests.</small>

                                    </div>

                                </div>

                                <div class="tb-field" style="margin-top:17px;">

                                    <label>Preferred time</label>

                                    <div class="tb-time-options" id="tbTimeOptions">

                                        <p class="tb-floor-empty" id="tbTimeMessage" style="grid-column:1/-1;padding:18px;">

                                            Select a booking date to view available time slots.

                                        </p>

                                    </div>

                                    <small>Time slots are generated from this restaurant's Booking Hours and Slot
                                        Duration.</small>

                                </div>

                            </section>

                            <section class="tb-form-section">

                                <h3 class="tb-subheading"><span>▣</span> Choose your seating</h3>

                                <div class="tb-seating-grid">

                                    <div class="tb-seating-option">

                                        <input type="radio" id="seating_indoor" name="seating" value="indoor" {{ $seatingValue === 'indoor' ? 'checked' : '' }} required>

                                        <label class="tb-seating-card" for="seating_indoor">

                                            <span class="tb-seating-art indoor">♨</span>

                                            <span class="tb-seating-info"><strong>Indoor Dining</strong><small>Comfortable
                                                    air-conditioned dining.</small><em>Cozy &amp; comfortable</em></span>

                                            <span class="tb-seating-check">✓</span>

                                        </label>

                                    </div>

                                    <div class="tb-seating-option">

                                        <input type="radio" id="seating_outdoor" name="seating" value="outdoor" {{ $seatingValue === 'outdoor' ? 'checked' : '' }}>

                                        <label class="tb-seating-card" for="seating_outdoor">

                                            <span class="tb-seating-art outdoor">☘</span>

                                            <span class="tb-seating-info"><strong>Outdoor Seating</strong><small>Fresh air
                                                    and an open dining setting.</small><em>Relaxed atmosphere</em></span>

                                            <span class="tb-seating-check">✓</span>

                                        </label>

                                    </div>

                                    <div class="tb-seating-option">

                                        <input type="radio" id="seating_family" name="seating" value="family" {{ $seatingValue === 'family' ? 'checked' : '' }}>

                                        <label class="tb-seating-card" for="seating_family">

                                            <span class="tb-seating-art family">♧</span>

                                            <span class="tb-seating-info"><strong>Family Seating</strong><small>More room
                                                    for family and friends.</small><em>Great for groups</em></span>

                                            <span class="tb-seating-check">✓</span>

                                        </label>

                                    </div>

                                    <div class="tb-seating-option">

                                        <input type="radio" id="seating_private" name="seating" value="private" {{ $seatingValue === 'private' ? 'checked' : '' }}>

                                        <label class="tb-seating-card" for="seating_private">

                                            <span class="tb-seating-art private">✦</span>

                                            <span class="tb-seating-info"><strong>Private Dining</strong><small>A more
                                                    personal dining experience.</small><em>For special occasions</em></span>

                                            <span class="tb-seating-check">✓</span>

                                        </label>

                                    </div>

                                </div>

                            </section>

                        </div>

                        <div class="tb-step-panel" id="bookingStep2"
                            style="{{ $availabilityChecked ? '' : 'display:none;' }}">

                            <div class="tb-step-heading">

                                <span class="tb-section-icon">02</span>

                                <div>
                                    <h2>Choose your table</h2>
                                    <p>Review live availability and select the table that suits your group.</p>
                                </div>

                            </div>

                            <section class="tb-floor-section">

                                <div class="tb-floor-top">

                                    <div>

                                        <h3 class="tb-subheading" style="margin-bottom:0;"><span>▦</span> Select your table
                                        </h3>

                                        <p>Choose one of the tables returned as available for your selected date and time.
                                        </p>

                                    </div>

                                    <div class="tb-floor-legend">

                                        <span class="tb-legend-item"><i class="tb-legend-dot available"></i>
                                            Available</span>

                                        <span class="tb-legend-item"><i class="tb-legend-dot selected"></i> Selected</span>

                                    </div>

                                </div>

                                <div class="tb-floor-plan" id="tbFloorPlan">

                                    @if($bookingTables->count())

                                        <div class="tb-table-grid">

                                            @foreach($bookingTables as $table)

                                                @php

                                                    $tableStatus = strtolower((string) data_get($table, 'status', 'unavailable'));

                                                    $explicitAvailability = data_get($table, 'is_available');
                                                    $isAvailable = $explicitAvailability !== null
                                                        ? (bool) $explicitAvailability
                                                        : in_array($tableStatus, ['available', '1', 'active'], true);

                                                    $tableId = data_get($table, 'id');

                                                    $tableNumber = data_get($table, 'table_number', 'Table');

                                                    $capacity = (int) data_get($table, 'seating_capacity', data_get($table, 'capacity', 2));

                                                    $isSelected = (string) $oldTableId === (string) $tableId;

                                                @endphp

                                                <div class="tb-table-choice {{ $capacity >= 6 ? 'capacity-6' : '' }}">

                                                    <button type="button"
                                                        class="tb-table-button {{ $isSelected ? 'is-selected' : ($isAvailable ? 'is-available' : 'is-unavailable') }}"
                                                        data-table-id="{{ $tableId }}" data-table-number="{{ $tableNumber }}"
                                                        data-capacity="{{ $capacity }}"
                                                        data-available="{{ $isAvailable ? '1' : '0' }}"
                                                        aria-label="{{ $tableNumber }}, capacity {{ $capacity }}, {{ $isAvailable ? 'available' : 'not available' }}"
                                                        {{ !$isAvailable ? 'disabled' : '' }} {{ $isSelected ? 'aria-pressed=true' : 'aria-pressed=false' }}>

                                                        <i class="tb-table-seat tb-seat-top"></i>

                                                        <i class="tb-table-seat tb-seat-bottom"></i>

                                                        <i class="tb-table-seat tb-seat-left"></i>

                                                        <i class="tb-table-seat tb-seat-right"></i>

                                                        @if($capacity >= 6)

                                                            <i class="tb-table-seat tb-seat-extra-left"></i>

                                                            <i class="tb-table-seat tb-seat-extra-right"></i>

                                                        @endif

                                                        <span class="tb-table-top">

                                                            {{ $tableNumber }}

                                                            <small>{{ $capacity }} Seats</small>

                                                        </span>

                                                    </button>

                                                    <span
                                                        class="tb-table-status {{ $isSelected ? 'selected' : ($isAvailable ? 'available' : 'unavailable') }}">

                                                        {{ $isSelected ? 'Selected' : ($isAvailable ? 'Available' : 'Not available') }}

                                                    </span>

                                                </div>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="tb-floor-empty">

                                            <strong>No available tables to display</strong>

                                            <span>@if($availabilityChecked) No matching tables are available for these
                                            details.<br>@else Select your date, time, guests and seating preference, then
                                                check availability.<br>@endif

                                                The live table layout will appear here when the server returns table
                                                availability.</span>

                                        </div>

                                    @endif

                                </div>

                                <div class="tb-selected-table-note" id="selectedTableNote">

                                    @if($oldTableId)

                                        <strong>Table selection:</strong> Your previously selected table will be rechecked when
                                        you submit.

                                    @else

                                        <strong>No table selected.</strong> Choose one of the green available tables after the
                                        availability check.

                                    @endif

                                </div>

                            </section>

                            <section class="tb-form-section" style="margin-bottom:0;padding-bottom:0;">

                                <h3 class="tb-subheading"><span>✎</span> Anything we should know?</h3>

                                <div class="tb-field">

                                    <label for="special_request">Special requests <span
                                            style="color:#888;font-weight:400;">(optional)</span></label>

                                    <textarea id="special_request" name="special_request" rows="3" maxlength="1000"
                                        placeholder="Birthday celebration, accessibility needs, high chair, or other requests...">{{ $specialRequestValue }}</textarea>

                                </div>

                            </section>

                        </div>

                        <div class="tb-step-panel" id="bookingStep3" style="display:none;">

                            <div class="tb-step-heading">

                                <span class="tb-section-icon">03</span>

                                <div>
                                    <h2>Review your reservation</h2>
                                    <p>Check the details below before confirming your table request.</p>
                                </div>

                            </div>

                            <div class="tb-confirm-card">

                                <h3>Reservation details</h3>

                                <div class="tb-confirm-grid">

                                    <div><small>Restaurant</small><strong>{{ $restaurantName ?? 'Restaurant' }}</strong>
                                    </div>

                                    <div><small>Booking date</small><strong id="confirmDate">—</strong></div>

                                    <div><small>Time</small><strong id="confirmTime">—</strong></div>

                                    <div><small>Guests</small><strong id="confirmGuests">—</strong></div>

                                    <div><small>Seating</small><strong id="confirmSeating">—</strong></div>

                                    <div><small>Selected table</small><strong id="confirmTable">—</strong></div>

                                </div>

                                <div class="tb-confirm-notice">

                                    Your table has passed the availability check. Final reservation confirmation must be
                                    completed by the booking confirmation endpoint.

                                </div>

                            </div>

                            <div class="tb-field tb-customer-fields">

                                <label for="confirm_customer_name">Customer name</label>

                                <input type="text" id="confirm_customer_name" name="customer_name" placeholder="Enter your full name"
    autocomplete="name"
    
>

                                <label for="confirm_customer_mobile">Mobile number</label>

                                <input
    type="tel"
    id="confirm_customer_mobile"
    name="customer_mobile"
    placeholder="Enter your mobile number"
    autocomplete="tel"
   
>

                                <label for="confirm_customer_email">Email address (optional)</label>

                                <input
    type="email"
    id="confirm_customer_email"
    name="customer_email"
    placeholder="Enter your email"
    autocomplete="email"
>

                            </div>

                            <div class="tb-step-actions tb-step-actions-between">

                                <button type="button" class="tb-secondary-button" id="backToTables">← Back to
                                    tables</button>

                                <button type="button" class="tb-submit" id="finalConfirmButton">
    Confirm Reservation <span aria-hidden="true">→</span>
</button>

                            </div>

                            <p class="tb-confirm-footnote">
    Your reservation will be submitted to the restaurant for admin confirmation.
</p>

                        </div>

                    </main>

                    <aside class="tb-summary">

                        <div class="tb-summary-head">

                            <small>Your reservation</small>

                            <h3>Booking summary</h3>

                        </div>

                        <div class="tb-summary-body">

                            <div class="tb-summary-restaurant">

                                <span class="tb-summary-logo">♨</span>

                                <div>

                                    <strong>{{ $restaurantName ?? 'Bakasuran Signature' }}</strong>

                                    <small>BAKASURAN · Food &amp; Beverages</small>

                                </div>

                            </div>

                            <div class="tb-summary-list">

                                <div class="tb-summary-row"><span>Date</span><strong id="summaryDate">Choose a date</strong>
                                </div>

                                <div class="tb-summary-row"><span>Time</span><strong id="summaryTime">Choose a time</strong>
                                </div>

                                <div class="tb-summary-row"><span>Guests</span><strong id="summaryGuests">Not
                                        selected</strong></div>

                                <div class="tb-summary-row"><span>Seating</span><strong id="summarySeating">Not
                                        selected</strong></div>

                                <div class="tb-summary-row"><span>Table</span><strong id="summaryTable">Not
                                        selected</strong></div>

                            </div>

                            <div class="tb-summary-divider"></div>

                            <p class="tb-summary-note">Your preferences will be sent to the restaurant for an availability
                                check. Your reservation is confirmed only after the server completes the confirmation
                                process.</p>

                            @if(!$availabilityChecked)

                                <button type="submit" class="tb-submit" id="tbSubmit" {{ !$bookingEnabled ? 'disabled' : '' }}>

                                    Find Available Tables <span aria-hidden="true">→</span>

                                </button>

                            @else

                                <button type="button" class="tb-submit" id="continueToConfirm" {{ !$oldTableId ? 'disabled' : '' }}>

                                    Continue to Confirmation <span aria-hidden="true">→</span>

                                </button>

                                <button type="submit" class="tb-secondary-button tb-recheck-button" id="recheckAvailability">

                                    Change details / Recheck

                                </button>

                            @endif

                            <div class="tb-trust"><span>●</span> No payment is taken at this step</div>

                        </div>

                    </aside>

                </div>

            </form>

        </div>

    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('tableBookingForm');

            if (!form) return;

            const dateInput = document.getElementById('booking_date');

            const guestsInput = document.getElementById('guests');

            const timeOptions = document.getElementById('tbTimeOptions');

            const timeMessage = document.getElementById('tbTimeMessage');

            const selectedTableInput = document.getElementById('selected_table_id');

            const selectedTableNote = document.getElementById('selectedTableNote');

            const submitButton = document.getElementById('tbSubmit');

            const continueToConfirmButton = document.getElementById('continueToConfirm');

            const summaryDate = document.getElementById('summaryDate');

            const summaryTime = document.getElementById('summaryTime');

            const summaryGuests = document.getElementById('summaryGuests');

            const summarySeating = document.getElementById('summarySeating');

            const summaryTable = document.getElementById('summaryTable');

            const bookingHours = @json($hoursForJs);

            const slotDuration = {{ max(15, (int) ($settings->slot_duration ?? 60)) }};

            function selectedValue(name) {

                const selected = form.querySelector('input[name="' + name + '"]:checked');

                return selected ? selected.value : '';

            }

            function formatDate(value) {

                if (!value) return 'Choose a date';

                const date = new Date(value + 'T00:00:00');

                return date.toLocaleDateString(undefined, {

                    day: 'numeric', month: 'short', year: 'numeric'

                });

            }

            function formatTime(value) {

                if (!value) return 'Choose a time';

                const parts = value.split(':');

                let hour = Number(parts[0]);

                const suffix = hour >= 12 ? 'PM' : 'AM';

                hour = hour % 12 || 12;

                return hour + ':' + parts[1] + ' ' + suffix;

            }

            function clearTableSelection(message) {

                selectedTableInput.value = '';

                if (continueToConfirmButton) continueToConfirmButton.disabled = true;

                document.querySelectorAll('.tb-table-button.is-selected').forEach(function (button) {

                    button.classList.remove('is-selected');

                    button.classList.add('is-available');

                    button.setAttribute('aria-pressed', 'false');

                    const status = button.closest('.tb-table-choice').querySelector('.tb-table-status');

                    status.className = 'tb-table-status available';

                    status.textContent = 'Available';

                });

                if (selectedTableNote) {
                    selectedTableNote.innerHTML = '<strong>Selection cleared.</strong> ' + message;
                }

                updateSummary();

            }

            function generateTimeSlots() {

                timeOptions.innerHTML = '';

                if (!dateInput.value) {

                    showTimeMessage('Select a booking date to view available time slots.');

                    return;

                }

                // Parse date in local time to get Sunday=0 ... Saturday=6.

                const dateParts = dateInput.value.split('-').map(Number);

                const selectedDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

                const dayIndex = selectedDate.getDay();

                const hours = bookingHours[dayIndex] || bookingHours[String(dayIndex)];

                if (!hours || hours.closed || !hours.opening || !hours.closing) {

                    showTimeMessage('The restaurant is closed on this day.');

                    return;

                }

                const toMinutes = function (time) {

                    const parts = time.split(':').map(Number);

                    return parts[0] * 60 + parts[1];

                };

                const opening = toMinutes(hours.opening);

                const closing = toMinutes(hours.closing);

                const oldTime = @json($timeValue);

                const now = new Date();

                const todayString = [

                    now.getFullYear(),

                    String(now.getMonth() + 1).padStart(2, '0'),

                    String(now.getDate()).padStart(2, '0')

                ].join('-');

                let count = 0;

                for (let minute = opening; minute + slotDuration <= closing; minute += slotDuration) {

                    const hour = Math.floor(minute / 60);

                    const mins = minute % 60;

                    const value = String(hour).padStart(2, '0') + ':' + String(mins).padStart(2, '0');

                    if (dateInput.value === todayString) {

                        const slotDate = new Date(

                            dateParts[0], dateParts[1] - 1, dateParts[2], hour, mins

                        );

                        if (slotDate <= now) continue;

                    }

                    const id = 'time_' + value.replace(':', '');

                    const wrapper = document.createElement('div');

                    wrapper.className = 'tb-time-option';

                    const input = document.createElement('input');

                    input.type = 'radio';

                    input.name = 'booking_time';

                    input.id = id;

                    input.value = value;

                    input.required = true;

                    input.checked = oldTime === value;

                    input.addEventListener('change', function () {

                        clearTableSelection('Time changed. Check availability again.');

                        updateSummary();

                    });

                    const label = document.createElement('label');

                    label.htmlFor = id;

                    label.textContent = formatTime(value);

                    wrapper.appendChild(input);

                    wrapper.appendChild(label);

                    timeOptions.appendChild(wrapper);

                    count++;

                }

                if (!count) {

                    showTimeMessage('No future booking slots are available for this date.');

                }

                updateSummary();

            }

            function showTimeMessage(message) {

                timeOptions.innerHTML = '';

                const paragraph = document.createElement('p');

                paragraph.className = 'tb-floor-empty';

                paragraph.style.cssText = 'grid-column:1/-1;padding:18px;';

                paragraph.textContent = message;

                timeOptions.appendChild(paragraph);

            }

            function updateSummary() {

                summaryDate.textContent = formatDate(dateInput.value);

                summaryTime.textContent = formatTime(selectedValue('booking_time'));

                summaryGuests.textContent = guestsInput.value

                    ? guestsInput.value + (guestsInput.value === '1' ? ' Guest' : ' Guests')

                    : 'Not selected';

                const seating = selectedValue('seating');

                const labels = {

                    indoor: 'Indoor Dining',

                    outdoor: 'Outdoor Seating',

                    family: 'Family Seating',

                    private: 'Private Dining'

                };

                summarySeating.textContent = labels[seating] || 'Not selected';

                const chosenTable = form.querySelector('.tb-table-button.is-selected');

                summaryTable.textContent = chosenTable

                    ? chosenTable.dataset.tableNumber

                    : 'Not selected';

            }

            document.querySelectorAll('.tb-table-button[data-available="1"]').forEach(function (button) {

                button.addEventListener('click', function () {

                    document.querySelectorAll('.tb-table-button').forEach(function (item) {

                        item.classList.remove('is-selected');

                        item.setAttribute('aria-pressed', 'false');

                        const status = item.closest('.tb-table-choice').querySelector('.tb-table-status');

                        if (item.dataset.available === '1') {

                            item.classList.add('is-available');

                            status.className = 'tb-table-status available';

                            status.textContent = 'Available';

                        }

                    });

                    button.classList.remove('is-available');

                    button.classList.add('is-selected');

                    button.setAttribute('aria-pressed', 'true');

                    const status = button.closest('.tb-table-choice').querySelector('.tb-table-status');

                    status.className = 'tb-table-status selected';

                    status.textContent = 'Selected';

                    selectedTableInput.value = button.dataset.tableId;

                    if (continueToConfirmButton) continueToConfirmButton.disabled = false;

                    selectedTableNote.innerHTML = '<strong>Selected:</strong> ' +

                        button.dataset.tableNumber + ' · ' + button.dataset.capacity + ' seats';

                    updateSummary();

                });

            });

            [dateInput, guestsInput].forEach(function (field) {

                field.addEventListener('change', function () {

                    clearTableSelection('Your booking details changed. Check availability again.');

                    if (field === dateInput) generateTimeSlots();

                });

            });

            form.querySelectorAll('input[name="seating"]').forEach(function (input) {

                input.addEventListener('change', function () {

                    clearTableSelection('Your seating preference changed. Check availability again.');

                });

            });

            form.addEventListener('change', updateSummary);

            form.addEventListener('input', updateSummary);

            if (continueToConfirmButton) {
                continueToConfirmButton.disabled = !selectedTableInput.value;
            }

            generateTimeSlots();

            updateSummary();

            form.addEventListener('submit', function (event) {
                const clickedButton = event.submitter || submitButton;
                if (clickedButton) {
                    clickedButton.disabled = true;
                    clickedButton.innerHTML = 'Checking availability <span aria-hidden="true">…</span>';
                }
            });

        });

    </script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const step1 = document.getElementById('bookingStep1');

            const step2 = document.getElementById('bookingStep2');

            const step3 = document.getElementById('bookingStep3');

            if (!step1 || !step2 || !step3) return;

            const progress = Array.from(document.querySelectorAll('[data-progress-step]'));

            const continueButton = document.getElementById('continueToConfirm');

            const backButton = document.getElementById('backToTables');

            const tableInput = document.getElementById('selected_table_id');

            const form = document.getElementById('tableBookingForm');

const confirmButton = document.getElementById('finalConfirmButton');

const confirmUrl = @json(route('restaurant.confirm-booking', ['slug' => $slug]));

            function setStep(number) {

                step1.style.display = number === 1 ? '' : 'none';

                step2.style.display = number === 2 ? '' : 'none';

                step3.style.display = number === 3 ? '' : 'none';

                progress.forEach(function (item) {

                    const n = Number(item.dataset.progressStep);

                    item.classList.toggle('is-active', n === number);

                    item.classList.toggle('is-complete', n < number);

                });

                if (number === 3) updateConfirmationSummary();

                const top = document.querySelector('.tb-progress-wrap');

                if (top) top.scrollIntoView({ behavior: 'smooth', block: 'start' });

            }

            function updateConfirmationSummary() {

                const getText = id => document.getElementById(id)?.textContent || '—';

                document.getElementById('confirmDate').textContent = getText('summaryDate');

                document.getElementById('confirmTime').textContent = getText('summaryTime');

                document.getElementById('confirmGuests').textContent = getText('summaryGuests');

                document.getElementById('confirmSeating').textContent = getText('summarySeating');

                document.getElementById('confirmTable').textContent = getText('summaryTable');

            }

            if (continueButton) {

                continueButton.addEventListener('click', function () {

                    if (!tableInput || !tableInput.value) {

                        alert('Please select an available table first.');

                        return;

                    }

                    setStep(3);

                });

            }

            if (backButton) backButton.addEventListener('click', function () { setStep(2); });

            // Server-returned availability starts the user on step 2.

            setStep(step2.style.display === 'none' ? 1 : 2);


            if (confirmButton) {
    confirmButton.addEventListener('click', function () {

        const customerName = document.getElementById('confirm_customer_name').value.trim();
        const customerMobile = document.getElementById('confirm_customer_mobile').value.trim();
        const customerEmail = document.getElementById('confirm_customer_email').value.trim();

        if (!tableInput || !tableInput.value) {
            alert('Please select an available table.');
            setStep(2);
            return;
        }

        if (!customerName) {
            alert('Please enter your name.');
            return;
        }

        if (!customerMobile) {
            alert('Please enter your mobile number.');
            return;
        }

        if (!/^[0-9]{10}$/.test(customerMobile)) {
            alert('Please enter a valid 10-digit mobile number.');
            return;
        }

        if (customerEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerEmail)) {
            alert('Please enter a valid email address.');
            return;
        }

        form.action = confirmUrl;
        confirmButton.disabled = true;
        confirmButton.innerHTML = 'Submitting Reservation...';

        form.submit();
    });
}

        });

    </script>

@endsection