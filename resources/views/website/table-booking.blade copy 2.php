@extends('layouts.website')

@section('content')

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

    .tb-page, .tb-page * { box-sizing: border-box; }
    .tb-page {
        color: var(--tb-text);
        background: #f6f6f6;
        min-height: 80vh;
        padding-bottom: 70px;
        font-family: inherit;
    }
    .tb-page button, .tb-page input, .tb-page select, .tb-page textarea { font: inherit; }
    .tb-shell { width: min(1280px, calc(100% - 48px)); margin: 0 auto; }

    .tb-breadcrumb {
        padding: 17px 0;
        color: #777;
        font-size: 13px;
    }
    .tb-breadcrumb a { color: #444; text-decoration: none; }
    .tb-breadcrumb a:hover { color: var(--tb-red); }
    .tb-breadcrumb span { margin: 0 9px; color: var(--tb-red); }

    .tb-hero {
        position: relative;
        overflow: hidden;
        min-height: 285px;
        display: flex;
        align-items: center;
        color: #fff;
        background:
            linear-gradient(90deg, rgba(0,0,0,.94) 0%, rgba(0,0,0,.82) 48%, rgba(0,0,0,.2) 100%),
            url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=2000&q=90') center 52%/cover;
    }
    .tb-hero::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 4px;
        background: linear-gradient(90deg, var(--tb-red), #ff7777, transparent);
    }
    .tb-hero-inner { position: relative; z-index: 1; width: min(1280px, calc(100% - 48px)); margin: auto; padding: 45px 0; }
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
    .tb-eyebrow::before { content: ""; width: 28px; height: 2px; background: var(--tb-red); }
    .tb-hero h1 { margin: 13px 0 10px; max-width: 700px; color: #fff; font-size: clamp(34px, 4.5vw, 56px); line-height: 1.08; font-weight: 800; }
    .tb-hero h1 span { color: var(--tb-red); }
    .tb-hero-copy { max-width: 570px; margin: 0; color: #dedede; font-size: 15px; line-height: 1.8; }
    .tb-hero-meta { display: flex; flex-wrap: wrap; gap: 22px; margin-top: 25px; }
    .tb-hero-meta span { display: inline-flex; align-items: center; gap: 8px; color: #f2f2f2; font-size: 13px; }
    .tb-hero-meta b { color: var(--tb-red); font-size: 17px; }

    .tb-progress-wrap { position: relative; z-index: 2; margin-top: -27px; }
    .tb-progress {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 18px 24px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 16px;
        box-shadow: 0 14px 40px rgba(0,0,0,.09);
    }
    .tb-progress-step { display: flex; align-items: center; gap: 11px; color: #888; font-size: 13px; font-weight: 700; }
    .tb-progress-step:not(:last-child) { border-right: 1px solid #eee; }
    .tb-step-number { display: grid; place-items: center; flex: 0 0 34px; height: 34px; border-radius: 50%; background: #eee; color: #777; font-weight: 800; }
    .tb-progress-step.is-active { color: #171717; }
    .tb-progress-step.is-active .tb-step-number { color: #fff; background: var(--tb-red); box-shadow: 0 5px 14px rgba(255,16,16,.25); }

    .tb-layout { display: grid; grid-template-columns: minmax(0, 1fr) 330px; gap: 24px; align-items: start; margin-top: 25px; }
    .tb-main-card, .tb-summary {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 18px;
        box-shadow: 0 12px 35px rgba(0,0,0,.045);
    }
    .tb-main-card { padding: 28px; }
    .tb-section-heading { display: flex; align-items: flex-start; gap: 13px; margin-bottom: 24px; }
    .tb-section-icon { display: grid; place-items: center; flex: 0 0 43px; height: 43px; border-radius: 12px; background: #fff0f0; color: var(--tb-red); font-size: 20px; font-weight: 800; }
    .tb-section-heading h2 { margin: 0 0 5px; color: #171717; font-size: 22px; font-weight: 800; }
    .tb-section-heading p { margin: 0; color: #777; font-size: 13px; line-height: 1.6; }

    .tb-alert { padding: 15px 17px; margin-bottom: 20px; border-radius: 12px; font-size: 13px; }
    .tb-alert p { margin: 7px 0 0; }
    .tb-alert ul { margin: 8px 0 0; padding-left: 20px; }
    .tb-alert-error { color: #a40012; background: #fff0f1; border: 1px solid #ffc8ce; }
    .tb-alert-success { color: #185c37; background: #edfff4; border: 1px solid #b7ebca; }

    .tb-form-section { padding: 0 0 25px; margin-bottom: 25px; border-bottom: 1px solid #eee; }
    .tb-form-section:last-of-type { border-bottom: 0; }
    .tb-subheading { display: flex; align-items: center; gap: 9px; margin: 0 0 16px; color: #222; font-size: 15px; font-weight: 800; }
    .tb-subheading span { color: var(--tb-red); font-size: 17px; }
    .tb-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 17px; }
    .tb-field { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
    .tb-field label { color: #333; font-size: 12px; font-weight: 800; }
    .tb-field input, .tb-field select, .tb-field textarea {
        width: 100%; min-height: 49px; padding: 12px 14px;
        color: #222; background: #fff; border: 1px solid #dedede;
        border-radius: 10px; outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .tb-field input:focus, .tb-field select:focus, .tb-field textarea:focus {
        border-color: var(--tb-red); box-shadow: 0 0 0 3px rgba(255,16,16,.09);
    }
    .tb-field textarea { min-height: 95px; resize: vertical; }
    .tb-field small { color: #888; font-size: 11px; }

    .tb-time-options { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 9px; }
    .tb-time-option { position: relative; }
    .tb-time-option input { position: absolute; opacity: 0; pointer-events: none; }
    .tb-time-option label {
        display: grid; place-items: center; min-height: 45px; padding: 8px 5px;
        border: 1px solid #e1e1e1; border-radius: 9px; color: #444;
        background: #fff; cursor: pointer; font-size: 12px; font-weight: 700;
        transition: .2s ease;
    }
    .tb-time-option input:checked + label {
        color: #fff; border-color: var(--tb-red); background: var(--tb-red);
        box-shadow: 0 5px 14px rgba(255,16,16,.2);
    }
    .tb-time-option label:hover { border-color: var(--tb-red); transform: translateY(-2px); }

    .tb-seating-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; }
    .tb-seating-option { position: relative; min-width: 0; }
    .tb-seating-option input { position: absolute; opacity: 0; pointer-events: none; }
    .tb-seating-card {
        display: flex; gap: 13px; align-items: center; min-height: 112px; padding: 12px;
        border: 1px solid #e4e4e4; border-radius: 13px; background: #fff; cursor: pointer;
        transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
    }
    .tb-seating-card:hover { transform: translateY(-3px); border-color: #ff8a8a; box-shadow: 0 9px 22px rgba(0,0,0,.06); }
    .tb-seating-option input:checked + .tb-seating-card {
        border: 2px solid var(--tb-red); padding: 11px;
        background: linear-gradient(135deg, #fff, #fff4f4);
        box-shadow: 0 8px 22px rgba(255,16,16,.1);
    }
    .tb-seating-art {
        display: grid; place-items: center; flex: 0 0 76px; height: 82px;
        overflow: hidden; border-radius: 10px; color: #fff; font-size: 28px;
        background: linear-gradient(145deg, #333, #090909);
    }
    .tb-seating-art.indoor { background: linear-gradient(145deg, #5b2525, #171717); }
    .tb-seating-art.outdoor { background: linear-gradient(145deg, #3f6547, #17291c); }
    .tb-seating-art.family { background: linear-gradient(145deg, #76502d, #2b1a10); }
    .tb-seating-art.private { background: linear-gradient(145deg, #4b4053, #17131d); }
    .tb-seating-info { min-width: 0; flex: 1; }
    .tb-seating-info strong { display: block; margin-bottom: 5px; color: #222; font-size: 14px; }
    .tb-seating-info small { display: block; color: #777; font-size: 11px; line-height: 1.5; }
    .tb-seating-info em { display: block; margin-top: 7px; color: var(--tb-red-dark); font-size: 11px; font-style: normal; font-weight: 800; }
    .tb-seating-check { display: grid; place-items: center; flex: 0 0 21px; width: 21px; height: 21px; border: 1px solid #ccc; border-radius: 50%; color: transparent; font-size: 12px; }
    .tb-seating-option input:checked + .tb-seating-card .tb-seating-check { color: #fff; border-color: var(--tb-red); background: var(--tb-red); }

    .tb-summary { position: sticky; top: 24px; overflow: hidden; }
    .tb-summary-head { padding: 21px 22px; color: #fff; background: var(--tb-black); }
    .tb-summary-head small { display: block; margin-bottom: 5px; color: #ff7777; font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; }
    .tb-summary-head h3 { margin: 0; color: #fff; font-size: 19px; font-weight: 800; }
    .tb-summary-body { padding: 20px; }
    .tb-summary-restaurant { display: flex; align-items: center; gap: 11px; padding-bottom: 17px; border-bottom: 1px solid #eee; }
    .tb-summary-logo { display: grid; place-items: center; width: 42px; height: 42px; border-radius: 11px; color: #fff; background: var(--tb-red); font-size: 20px; }
    .tb-summary-restaurant strong { display: block; font-size: 13px; }
    .tb-summary-restaurant small { display: block; margin-top: 4px; color: #888; font-size: 11px; }
    .tb-summary-list { display: grid; gap: 14px; padding: 18px 0; }
    .tb-summary-row { display: flex; justify-content: space-between; gap: 12px; font-size: 12px; }
    .tb-summary-row span { color: #777; }
    .tb-summary-row strong { color: #222; text-align: right; font-weight: 800; }
    .tb-summary-divider { height: 1px; background: #eee; }
    .tb-summary-note { margin: 15px 0 0; color: #888; font-size: 11px; line-height: 1.6; }
    .tb-submit {
        display: flex; justify-content: center; align-items: center; gap: 10px;
        width: 100%; min-height: 53px; margin-top: 17px; padding: 14px 18px;
        color: #fff; background: var(--tb-red); border: 0; border-radius: 10px;
        font-size: 14px; font-weight: 800; cursor: pointer;
        box-shadow: 0 8px 18px rgba(255,16,16,.2); transition: .2s ease;
    }
    .tb-submit:hover { background: var(--tb-red-dark); transform: translateY(-2px); box-shadow: 0 11px 22px rgba(255,16,16,.25); }
    .tb-submit:disabled { opacity: .65; cursor: wait; transform: none; }
    .tb-trust { display: flex; align-items: center; justify-content: center; gap: 7px; margin-top: 13px; color: #888; font-size: 10px; text-align: center; }


    /* Interactive 3D table availability map */
    .tb-floor-section { padding: 0 0 25px; margin-bottom: 25px; border-bottom: 1px solid #eee; }
    .tb-floor-top { display:flex; justify-content:space-between; align-items:flex-start; gap:15px; flex-wrap:wrap; margin-bottom:16px; }
    .tb-floor-top p { margin:5px 0 0; color:#777; font-size:12px; }
    .tb-floor-legend { display:flex; flex-wrap:wrap; gap:13px; align-items:center; }
    .tb-legend-item { display:flex; align-items:center; gap:6px; color:#666; font-size:11px; font-weight:700; }
    .tb-legend-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .tb-legend-dot.available { background:#20a653; box-shadow:0 0 0 3px #e3f7e9; }
    .tb-legend-dot.unavailable { background:#e32929; box-shadow:0 0 0 3px #ffe5e5; }
    .tb-legend-dot.selected { background:#ff1010; box-shadow:0 0 0 3px #ffe0e0; }
    .tb-floor-plan {
        position:relative; min-height:360px; padding:35px 25px;
        border:1px solid #e6e6e6; border-radius:16px; overflow:hidden;
        background:
            radial-gradient(circle at 12% 15%,rgba(255,255,255,.95) 0 2px,transparent 3px),
            linear-gradient(135deg,#f3f0ed,#e7e1dc 48%,#f5f1ed);
        box-shadow:inset 0 0 35px rgba(0,0,0,.06);
    }
    .tb-floor-plan:before {
        content:""; position:absolute; inset:13px; border:2px dashed rgba(80,60,50,.16);
        border-radius:12px; pointer-events:none;
    }
    .tb-floor-plan:after {
        content:"DINING AREA"; position:absolute; top:17px; left:50%; transform:translateX(-50%);
        color:rgba(65,50,45,.42); font-size:9px; font-weight:900; letter-spacing:3px;
    }
    .tb-table-grid { position:relative; z-index:1; display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:30px 22px; align-items:center; justify-items:center; padding-top:20px; }
    .tb-table-choice { position:relative; display:flex; flex-direction:column; align-items:center; gap:8px; width:100%; max-width:150px; }
    .tb-table-button {
        position:relative; width:100%; min-height:112px; padding:0; border:0; background:transparent;
        cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center;
        transition:transform .22s ease,filter .22s ease;
    }
    .tb-table-button:not(:disabled):hover { transform:translateY(-5px); filter:drop-shadow(0 9px 8px rgba(0,0,0,.16)); }
    .tb-table-button:disabled { cursor:not-allowed; opacity:.82; }
    .tb-table-top {
        position:relative; z-index:2; width:75px; height:58px; display:flex; flex-direction:column;
        justify-content:center; align-items:center; border-radius:13px;
        color:#fff; font-size:12px; font-weight:900; letter-spacing:.3px;
        border:2px solid rgba(255,255,255,.8);
        box-shadow:0 8px 0 rgba(0,0,0,.13),0 12px 20px rgba(0,0,0,.12),inset 0 2px 5px rgba(255,255,255,.28);
        transform:perspective(180px) rotateX(8deg);
    }
    .tb-table-top small { display:block; margin-top:3px; font-size:9px; font-weight:700; letter-spacing:0; }
    .tb-table-seat { position:absolute; width:23px; height:23px; border-radius:7px; border:1px solid rgba(0,0,0,.12); background:linear-gradient(145deg,#fff,#cfcfcf); box-shadow:0 3px 4px rgba(0,0,0,.17); }
    .tb-seat-top { top:5px; left:calc(50% - 11px); }
    .tb-seat-bottom { bottom:5px; left:calc(50% - 11px); }
    .tb-seat-left { left:calc(50% - 51px); top:calc(50% - 11px); }
    .tb-seat-right { right:calc(50% - 51px); top:calc(50% - 11px); }
    .tb-table-choice.capacity-6 .tb-seat-extra-left { left:calc(50% - 44px); top:4px; }
    .tb-table-choice.capacity-6 .tb-seat-extra-right { right:calc(50% - 44px); top:4px; }
    .tb-table-button.is-available .tb-table-top { background:linear-gradient(145deg,#35c96e,#12833b); }
    .tb-table-button.is-unavailable .tb-table-top { background:linear-gradient(145deg,#ff6565,#bd1717); }
    .tb-table-button.is-selected .tb-table-top { background:linear-gradient(145deg,#ff4a4a,#c90000); outline:4px solid rgba(255,16,16,.16); box-shadow:0 8px 0 rgba(100,0,0,.2),0 0 0 3px #fff,0 0 0 6px #ff1010,0 15px 25px rgba(255,16,16,.25); }
    .tb-table-status { color:#555; font-size:10px; font-weight:800; text-align:center; }
    .tb-table-status.available { color:#16813b; }
    .tb-table-status.unavailable { color:#c51d1d; }
    .tb-table-status.selected { color:#d00000; }
    .tb-table-button:focus-visible { outline:3px solid #111; outline-offset:6px; border-radius:12px; }
    .tb-floor-empty { position:relative; z-index:1; padding:70px 15px; text-align:center; color:#777; }
    .tb-floor-empty strong { display:block; margin-bottom:7px; color:#222; font-size:15px; }
    .tb-floor-empty span { font-size:12px; line-height:1.6; }
    .tb-selected-table-note { margin-top:13px; padding:12px 14px; border-radius:10px; background:#f7f7f7; color:#555; font-size:12px; }
    .tb-selected-table-note strong { color:#c90000; }
    @media(max-width:600px) {
        .tb-floor-plan { padding:30px 12px; min-height:320px; }
        .tb-table-grid { gap:18px 8px; }
        .tb-table-choice { max-width:105px; }
        .tb-table-top { width:65px; height:51px; }
        .tb-table-seat { width:18px; height:18px; }
        .tb-seat-left { left:calc(50% - 42px); }
        .tb-seat-right { right:calc(50% - 42px); }
    }

    @media (max-width: 1000px) {
        .tb-layout { grid-template-columns: minmax(0,1fr) 290px; gap: 16px; }
        .tb-main-card { padding: 22px; }
        .tb-time-options { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (max-width: 760px) {
        .tb-shell, .tb-hero-inner { width: min(100% - 28px, 600px); }
        .tb-hero { min-height: 260px; background-position: 60% center; }
        .tb-hero-inner { padding: 35px 0 48px; }
        .tb-hero-meta { gap: 12px; }
        .tb-progress { padding: 13px 10px; gap: 5px; }
        .tb-progress-step { gap: 6px; font-size: 10px; }
        .tb-step-number { flex-basis: 28px; height: 28px; }
        .tb-layout { grid-template-columns: 1fr; }
        .tb-summary { position: static; grid-row: 1; }
        .tb-main-card { padding: 20px 16px; }
    }
    @media (max-width: 480px) {
        .tb-form-grid, .tb-seating-grid { grid-template-columns: 1fr; }
        .tb-time-options { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .tb-progress-step { flex-direction: column; text-align: center; }
        .tb-progress-step:not(:last-child) { border-right: 0; }
        .tb-section-heading h2 { font-size: 19px; }
        .tb-seating-art { flex-basis: 68px; height: 72px; }
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
            <p class="tb-hero-copy">Choose your preferred date, time and seating. We’ll check availability at your selected restaurant.</p>
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
                <div class="tb-progress-step is-active"><span class="tb-step-number">01</span><span>Choose your visit</span></div>
                <div class="tb-progress-step"><span class="tb-step-number">02</span><span>Check availability</span></div>
                <div class="tb-progress-step"><span class="tb-step-number">03</span><span>Confirm reservation</span></div>
            </div>
        </div>

        <form action="{{ route('restaurant.check-availability', ['slug' => $slug]) }}" method="POST" id="tableBookingForm">
            @csrf
            <input type="hidden" name="table_id" id="selected_table_id" value="{{ old('table_id') }}">

            <div class="tb-layout">
                <main class="tb-main-card">
                    <div class="tb-section-heading">
                        <span class="tb-section-icon">01</span>
                        <div>
                            <h2>Plan your visit</h2>
                            <p>Tell us when you’re coming and how you’d like to dine.</p>
                        </div>
                    </div>

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

                    @if (session('availability_checked'))
                        <div class="tb-alert tb-alert-success" role="status">
                            <strong>Your availability request was received.</strong>
                            <p>Your details were validated. This message does not mean a table is confirmed; confirmation depends on the availability check and reservation process.</p>
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

                    <section class="tb-form-section">
                        <h3 class="tb-subheading"><span>◷</span> Date &amp; time</h3>
                        <div class="tb-form-grid">
                            <div class="tb-field">
                                <label for="booking_date">Booking date</label>
                                <input type="date" id="booking_date" name="booking_date"
                                    min="{{ date('Y-m-d') }}" value="{{ old('booking_date') }}" required>
                            </div>
                            <div class="tb-field">
                                <label for="guests">Number of guests</label>
                                <select id="guests" name="guests" required>
                                    <option value="">Select guests</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (string) old('guests') === (string) $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}
                                        </option>
                                    @endfor
                                </select>
                                <small>For parties larger than 12, please contact the restaurant.</small>
                            </div>
                        </div>
                        <div class="tb-field" style="margin-top:17px;">
                            <label>Preferred time</label>
                            <div class="tb-time-options">
                                @php
                                    $timeSlots = [
                                        '12:00' => '12:00 PM', '13:00' => '1:00 PM',
                                        '14:00' => '2:00 PM', '18:00' => '6:00 PM',
                                        '19:00' => '7:00 PM', '20:00' => '8:00 PM',
                                        '21:00' => '9:00 PM',
                                    ];
                                @endphp
                                @foreach ($timeSlots as $value => $label)
                                    <div class="tb-time-option">
                                        <input type="radio" id="time_{{ str_replace(':', '', $value) }}"
                                            name="booking_time" value="{{ $value }}"
                                            {{ old('booking_time') === $value ? 'checked' : '' }} required>
                                        <label for="time_{{ str_replace(':', '', $value) }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <small>These are preferred time slots. Final availability must be checked by the server.</small>
                        </div>
                    </section>

                    <section class="tb-form-section">
                        <h3 class="tb-subheading"><span>▣</span> Choose your seating</h3>
                        <div class="tb-seating-grid">
                            <div class="tb-seating-option">
                                <input type="radio" id="seating_indoor" name="seating" value="indoor" {{ old('seating') === 'indoor' ? 'checked' : '' }} required>
                                <label class="tb-seating-card" for="seating_indoor">
                                    <span class="tb-seating-art indoor">♨</span>
                                    <span class="tb-seating-info"><strong>Indoor Dining</strong><small>Comfortable air-conditioned dining.</small><em>Cozy &amp; comfortable</em></span>
                                    <span class="tb-seating-check">✓</span>
                                </label>
                            </div>
                            <div class="tb-seating-option">
                                <input type="radio" id="seating_outdoor" name="seating" value="outdoor" {{ old('seating') === 'outdoor' ? 'checked' : '' }}>
                                <label class="tb-seating-card" for="seating_outdoor">
                                    <span class="tb-seating-art outdoor">☘</span>
                                    <span class="tb-seating-info"><strong>Outdoor Seating</strong><small>Fresh air and an open dining setting.</small><em>Relaxed atmosphere</em></span>
                                    <span class="tb-seating-check">✓</span>
                                </label>
                            </div>
                            <div class="tb-seating-option">
                                <input type="radio" id="seating_family" name="seating" value="family" {{ old('seating') === 'family' ? 'checked' : '' }}>
                                <label class="tb-seating-card" for="seating_family">
                                    <span class="tb-seating-art family">♧</span>
                                    <span class="tb-seating-info"><strong>Family Seating</strong><small>More room for family and friends.</small><em>Great for groups</em></span>
                                    <span class="tb-seating-check">✓</span>
                                </label>
                            </div>
                            <div class="tb-seating-option">
                                <input type="radio" id="seating_private" name="seating" value="private" {{ old('seating') === 'private' ? 'checked' : '' }}>
                                <label class="tb-seating-card" for="seating_private">
                                    <span class="tb-seating-art private">✦</span>
                                    <span class="tb-seating-info"><strong>Private Dining</strong><small>A more personal dining experience.</small><em>For special occasions</em></span>
                                    <span class="tb-seating-check">✓</span>
                                </label>
                            </div>
                        </div>
                    </section>


                    <section class="tb-floor-section">
                        <div class="tb-floor-top">
                            <div>
                                <h3 class="tb-subheading" style="margin-bottom:0;"><span>▦</span> Select your table</h3>
                                <p>Green tables are available. Red tables are already reserved for this slot.</p>
                            </div>
                            <div class="tb-floor-legend">
                                <span class="tb-legend-item"><i class="tb-legend-dot available"></i> Available</span>
                                <span class="tb-legend-item"><i class="tb-legend-dot unavailable"></i> Not available</span>
                                <span class="tb-legend-item"><i class="tb-legend-dot selected"></i> Selected</span>
                            </div>
                        </div>

                        @php
                            /*
                             * The controller should pass $availableTables after checking the selected
                             * date, time, guest count and seating preference.
                             * Expected table fields: id, table_number, capacity, status.
                             * status should be "available" or "unavailable".
                             */
                            $bookingTables = $availableTables ?? collect();
                            $oldTableId = old('table_id');
                        @endphp

                        <div class="tb-floor-plan" id="tbFloorPlan">
                            @if($bookingTables->count())
                                <div class="tb-table-grid">
                                    @foreach($bookingTables as $table)
                                        @php
                                            $tableStatus = strtolower((string) data_get($table, 'status', 'unavailable'));
                                            $isAvailable = $tableStatus === 'available' || (bool) data_get($table, 'is_available', false);
                                            $tableId = data_get($table, 'id');
                                            $tableNumber = data_get($table, 'table_number', 'Table');
                                            $capacity = (int) data_get($table, 'capacity', 2);
                                            $isSelected = (string) $oldTableId === (string) $tableId;
                                        @endphp
                                        <div class="tb-table-choice {{ $capacity >= 6 ? 'capacity-6' : '' }}">
                                            <button
                                                type="button"
                                                class="tb-table-button {{ $isSelected ? 'is-selected' : ($isAvailable ? 'is-available' : 'is-unavailable') }}"
                                                data-table-id="{{ $tableId }}"
                                                data-table-number="{{ $tableNumber }}"
                                                data-capacity="{{ $capacity }}"
                                                data-available="{{ $isAvailable ? '1' : '0' }}"
                                                aria-label="{{ $tableNumber }}, capacity {{ $capacity }}, {{ $isAvailable ? 'available' : 'not available' }}"
                                                {{ !$isAvailable ? 'disabled' : '' }}
                                                {{ $isSelected ? 'aria-pressed=true' : 'aria-pressed=false' }}>
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
                                            <span class="tb-table-status {{ $isSelected ? 'selected' : ($isAvailable ? 'available' : 'unavailable') }}">
                                                {{ $isSelected ? 'Selected' : ($isAvailable ? 'Available' : 'Not available') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="tb-floor-empty">
                                    <strong>Check availability to view the seating plan</strong>
                                    <span>Select your date, time, guests and seating preference, then submit the availability check.<br>
                                    The live table layout will appear here when the server returns table availability.</span>
                                </div>
                            @endif
                        </div>
                        <div class="tb-selected-table-note" id="selectedTableNote">
                            @if($oldTableId)
                                <strong>Table selection:</strong> Your previously selected table will be rechecked when you submit.
                            @else
                                <strong>No table selected.</strong> Choose one of the green available tables after the availability check.
                            @endif
                        </div>
                    </section>

                    <section class="tb-form-section" style="margin-bottom:0;padding-bottom:0;">
                        <h3 class="tb-subheading"><span>✎</span> Anything we should know?</h3>
                        <div class="tb-field">
                            <label for="special_request">Special requests <span style="color:#888;font-weight:400;">(optional)</span></label>
                            <textarea id="special_request" name="special_request" rows="3" maxlength="1000"
                                placeholder="Birthday celebration, accessibility needs, high chair, or other requests...">{{ old('special_request') }}</textarea>
                        </div>
                    </section>
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
                            <div class="tb-summary-row"><span>Date</span><strong id="summaryDate">Choose a date</strong></div>
                            <div class="tb-summary-row"><span>Time</span><strong id="summaryTime">Choose a time</strong></div>
                            <div class="tb-summary-row"><span>Guests</span><strong id="summaryGuests">Not selected</strong></div>
                            <div class="tb-summary-row"><span>Seating</span><strong id="summarySeating">Not selected</strong></div>
                            <div class="tb-summary-row"><span>Table</span><strong id="summaryTable">Not selected</strong></div>
                        </div>
                        <div class="tb-summary-divider"></div>
                        <p class="tb-summary-note">Your preferences will be sent to the restaurant for an availability check. Your reservation is confirmed only after the server completes the confirmation process.</p>
                        <button type="submit" class="tb-submit" id="tbSubmit">
                            Check Availability <span aria-hidden="true">→</span>
                        </button>
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
    const summaryDate = document.getElementById('summaryDate');
    const summaryTime = document.getElementById('summaryTime');
    const summaryGuests = document.getElementById('summaryGuests');
    const summarySeating = document.getElementById('summarySeating');
    const summaryTable = document.getElementById('summaryTable');
    const selectedTableInput = document.getElementById('selected_table_id');
    const selectedTableNote = document.getElementById('selectedTableNote');
    const submitButton = document.getElementById('tbSubmit');

    function selectedValue(name) {
        const selected = form.querySelector('input[name="' + name + '"]:checked');
        return selected ? selected.value : '';
    }

    function formatDate(value) {
        if (!value) return 'Choose a date';
        const date = new Date(value + 'T00:00:00');
        return date.toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function formatTime(value) {
        if (!value) return 'Choose a time';
        const parts = value.split(':');
        let hour = Number(parts[0]);
        const suffix = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12 || 12;
        return hour + ':' + parts[1] + ' ' + suffix;
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
        summaryTable.textContent = chosenTable ? chosenTable.dataset.tableNumber : 'Not selected';
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
            selectedTableNote.innerHTML = '<strong>Selected:</strong> ' +
                button.dataset.tableNumber + ' · ' + button.dataset.capacity + ' seats';
            updateSummary();
        });
    });

    // A change to booking criteria invalidates a previously chosen table.
    [dateInput, guestsInput].forEach(function (field) {
        field.addEventListener('change', function () {
            if (!selectedTableInput.value) return;
            selectedTableInput.value = '';
            document.querySelectorAll('.tb-table-button.is-selected').forEach(function (button) {
                button.classList.remove('is-selected');
                button.classList.add('is-available');
                button.setAttribute('aria-pressed', 'false');
                const status = button.closest('.tb-table-choice').querySelector('.tb-table-status');
                status.className = 'tb-table-status available';
                status.textContent = 'Available';
            });
            selectedTableNote.innerHTML = '<strong>Selection cleared.</strong> Recheck availability for the updated booking details.';
            updateSummary();
        });
    });

    form.addEventListener('change', updateSummary);
    form.addEventListener('input', updateSummary);
    updateSummary();

    form.addEventListener('submit', function (event) {
        const tableButtons = document.querySelectorAll('.tb-table-button');
        const hasLiveTableChoices = tableButtons.length > 0;
        if (hasLiveTableChoices && !selectedTableInput.value) {
            event.preventDefault();
            selectedTableNote.innerHTML = '<strong>Please select an available table</strong> before continuing.';
            selectedTableNote.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        if (!form.checkValidity()) return;
        submitButton.disabled = true;
        submitButton.innerHTML = 'Checking availability <span aria-hidden="true">…</span>';
    });
});
</script>

@endsection
