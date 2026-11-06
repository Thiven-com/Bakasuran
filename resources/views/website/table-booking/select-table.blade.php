@extends('layouts.website')

@section('content')

    @php
        $selectedTableId = data_get($bookingDetails, 'table_id');

        // Data for the 3D floor. Only real table fields are used.
        // Optional: if your tables have pos_x / pos_z columns they are used as the 3D position,
        // otherwise the tables are laid out automatically in neat rows.
        $tables3d = $availableTables->map(function ($table) {
            return [
                'id'       => $table->id,
                'number'   => (string) $table->table_number,
                'capacity' => (int) $table->seating_capacity,
                'type'     => (string) $table->seating_type,
                'x'        => data_get($table, 'pos_x'),
                'z'        => data_get($table, 'pos_z'),
            ];
        })->values();
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

        /* 3D table top (list view) */

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

        .selection-message.warn {
            color: #ffb8b8;
            border-color: #ff3030;
            background: #351c22;
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

        /* =========================================
           3D FLOOR PLAN (new)
        ========================================= */

        .view-toggle {
            display: flex;
            gap: 10px;
            width: fit-content;
            margin: 0 auto 22px;
        }

        .view-btn {
            padding: 11px 20px;
            color: #aaa;
            background: linear-gradient(145deg, #292932, #17171e);
            border: 1px solid #383842;
            border-radius: 10px;
            box-shadow: 0 5px 0 #09090d;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: .2s ease;
        }

        .view-btn:hover {
            color: #fff;
            transform: translateY(-2px);
        }

        .view-btn.active {
            color: #fff;
            border-color: #ff3030;
            background: linear-gradient(145deg, #512326, #24171b);
        }

        .floor-3d-wrap {
            position: relative;
            display: none;
            overflow: hidden;
            border: 1px solid #41414a;
            border-radius: 15px;
            background: #0d0d11;
            box-shadow: inset 0 8px 20px rgba(0, 0, 0, .55), 0 8px 0 #09090d;
        }

        .restaurant-floor.mode-3d .floor-3d-wrap {
            display: block;
        }

        .restaurant-floor.mode-3d .floor-label {
            display: none;
        }

        /* In 3D mode the radio list stays in the form (it holds the real inputs)
           but is visually hidden. Clicking a table in 3D checks the matching radio. */
        .restaurant-floor.mode-3d .table-grid {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip-path: inset(50%);
            white-space: nowrap;
        }

        #floor3d {
            width: 100%;
            height: clamp(380px, 62vh, 620px);
            touch-action: none;
        }

        #floor3d canvas {
            display: block;
            width: 100%;
            height: 100%;
            outline: none;
        }

        .floor-hint {
            position: absolute;
            left: 50%;
            bottom: 12px;
            transform: translateX(-50%);
            padding: 7px 16px;
            color: #bbb;
            background: rgba(10, 10, 14, .75);
            border: 1px solid #383842;
            border-radius: 999px;
            font-size: 11px;
            white-space: nowrap;
            pointer-events: none;
        }

        .floor-tip {
            position: absolute;
            z-index: 3;
            padding: 9px 13px;
            color: #fff;
            background: linear-gradient(145deg, #30303a, #1a1a20);
            border: 1px solid #ff3030;
            border-radius: 10px;
            box-shadow: 0 6px 0 #09090d, 0 10px 20px rgba(0, 0, 0, .45);
            font-size: 12px;
            font-weight: 700;
            pointer-events: none;
            transform: translate(14px, 14px);
            white-space: nowrap;
        }

        .floor-tip small {
            display: block;
            margin-top: 3px;
            color: #ff7777;
            font-size: 10px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .floor-notice {
            display: none;
            margin-bottom: 18px;
            padding: 12px 16px;
            color: #ffb8b8;
            background: #351c22;
            border: 1px solid #8b333d;
            border-radius: 10px;
            font-size: 12px;
            text-align: center;
        }

        .floor-notice.show {
            display: block;
        }

        @media(max-width: 600px) {
            .floor-hint {
                white-space: normal;
                width: calc(100% - 24px);
                text-align: center;
                border-radius: 12px;
            }
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

                    <div class="floor-notice" id="floorNotice">
                        3D view is not available on this device. You can choose your table from the list.
                    </div>

                    <div class="view-toggle" id="viewToggle">
                        <button type="button" class="view-btn active" data-view="3d">◈ 3D VIEW</button>
                        <button type="button" class="view-btn" data-view="list">▦ LIST VIEW</button>
                    </div>

                    <div class="restaurant-floor mode-3d" id="restaurantFloor">

                        <div class="floor-label">
                            RESTAURANT ENTRANCE
                        </div>

                        <form action="{{ route('restaurant.check-availability', ['slug' => $slug]) }}" method="POST"
                            id="selectTableForm" novalidate>

                            @csrf

                            <input type="hidden" name="booking_date" value="{{ $bookingDetails['booking_date'] }}">

                            <input type="hidden" name="booking_time" value="{{ $bookingDetails['booking_time'] }}">

                            <input type="hidden" name="guests" value="{{ $bookingDetails['guests'] }}">

                            <input type="hidden" name="seating" value="{{ $bookingDetails['seating'] }}">

                            <input type="hidden" name="special_request" value="{{ $bookingDetails['special_request'] ?? '' }}">

                            {{-- 3D FLOOR --}}
                            <div class="floor-3d-wrap" id="floor3dWrap">
                                <div id="floor3d" aria-label="3D restaurant floor plan. Click a table to select it."></div>
                                <div class="floor-tip" id="floorTip" hidden></div>
                                <div class="floor-hint">Drag to rotate · Scroll or pinch to zoom · Click a table to select</div>
                            </div>

                            {{-- REAL INPUTS (unchanged): the 3D floor checks these radios --}}
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

    {{-- Existing selection message logic (unchanged) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const tableOptions = document.querySelectorAll('.table-radio');
            const message = document.getElementById('selectionMessage');
            const form = document.getElementById('selectTableForm');

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

                message.classList.remove('warn');
                message.textContent =
                    tableName.trim() + ' selected · ' + capacity.trim();
            }

            tableOptions.forEach(function (radio) {
                radio.addEventListener('change', updateSelection);
            });

            // The form uses novalidate (the radios can be visually hidden in 3D mode),
            // so require a table here instead. The server still validates as before.
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (!document.querySelector('.table-radio:checked')) {
                        e.preventDefault();
                        message.classList.add('warn');
                        message.textContent = 'Please select a table to continue.';
                        message.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }

            updateSelection();

        });
    </script>

    {{-- 3D engine --}}
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const floorEl = document.getElementById('restaurantFloor');
            const host = document.getElementById('floor3d');
            if (!floorEl || !host) return; // no tables available

            const toggle = document.getElementById('viewToggle');
            const notice = document.getElementById('floorNotice');
            const tip = document.getElementById('floorTip');
            const wrap = document.getElementById('floor3dWrap');
            const TABLES = @json($tables3d);

            const RED = 0xff3030;
            const GREEN = 0x19b978;

            let mode = '3d';
            let ready = false;

            function setView(view) {
                mode = view;
                floorEl.classList.toggle('mode-3d', view === '3d');
                toggle.querySelectorAll('.view-btn').forEach(function (b) {
                    b.classList.toggle('active', b.dataset.view === view);
                });
                if (view === '3d' && ready) { resize(); }
            }

            toggle.addEventListener('click', function (e) {
                const btn = e.target.closest('.view-btn');
                if (!btn) return;
                if (btn.dataset.view === '3d' && !ready) return;
                setView(btn.dataset.view);
            });

            function fallback() {
                notice.classList.add('show');
                toggle.querySelector('[data-view="3d"]').disabled = true;
                setView('list');
            }

            if (typeof THREE === 'undefined' || !THREE.OrbitControls) { fallback(); return; }

            let renderer;
            try {
                renderer = new THREE.WebGLRenderer({ antialias: true });
            } catch (err) { fallback(); return; }

            /* ------------------------------------------------ layout */

            const num = function (v) { return v !== null && v !== '' && v !== undefined && isFinite(v); };

            function dimsFor(t) {
                const n = Math.max(1, Math.min(12, t.capacity));
                if (n <= 2) return { shape: 'round', r: 0.8, n: n };
                if (n <= 4) return (t.id % 2 === 0)
                    ? { shape: 'round', r: 1.1, n: n }
                    : { shape: 'square', w: 1.8, d: 1.8, n: n };
                const perSide = Math.ceil((n - (n >= 6 ? 2 : 0)) / 2);
                return { shape: 'rect', w: Math.max(2.6, perSide * 1.05 + 0.6), d: 1.4, n: n };
            }

            function footprint(d) {
                if (d.shape === 'round') return { fw: d.r * 2 + 1.4, fd: d.r * 2 + 1.4 };
                return { fw: d.w + 1.4 + (d.shape === 'rect' && d.n >= 6 ? 1.2 : 0), fd: d.d + 1.4 };
            }

            TABLES.forEach(function (t) { t.dim = dimsFor(t); });

            let maxFW = 0, maxFD = 0;
            TABLES.forEach(function (t) {
                const f = footprint(t.dim);
                maxFW = Math.max(maxFW, f.fw);
                maxFD = Math.max(maxFD, f.fd);
            });

            const SX = maxFW + 2.0;
            const SZ = maxFD + 1.8;
            const n = TABLES.length;
            const cols = Math.max(1, Math.min(5, Math.ceil(Math.sqrt(n * 1.4))));
            const rows = Math.ceil(n / cols);

            let maxAbsX = 0, maxAbsZ = 0;
            TABLES.forEach(function (t, i) {
                if (num(t.x) && num(t.z)) {
                    t.px = +t.x; t.pz = +t.z;
                } else {
                    const r = Math.floor(i / cols), c = i % cols;
                    const inRow = Math.min(cols, n - r * cols);
                    t.px = (c - (inRow - 1) / 2) * SX;
                    t.pz = (r - (rows - 1) / 2) * SZ - 0.6;
                }
                maxAbsX = Math.max(maxAbsX, Math.abs(t.px) + maxFW / 2);
                maxAbsZ = Math.max(maxAbsZ, Math.abs(t.pz) + maxFD / 2);
            });

            const ROOM_W = Math.max(16, (maxAbsX + 2.5) * 2);
            const ROOM_D = Math.max(12, (maxAbsZ + 2.2) * 2 + 2.5);
            const WALL_H = 5;

            /* ------------------------------------------------ scene */

            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0x0d0d11);
            scene.fog = new THREE.Fog(0x0d0d11, 55, 120);

            const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 250);

            renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
            renderer.shadowMap.enabled = true;
            renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            host.appendChild(renderer.domElement);

            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.08;
            controls.maxPolarAngle = Math.PI / 2.1;
            controls.enablePan = true;
            controls.screenSpacePanning = false;

            scene.add(new THREE.HemisphereLight(0xffe2d0, 0x1a1014, 0.6));

            const sun = new THREE.DirectionalLight(0xffeedd, 0.6);
            sun.position.set(-ROOM_W * 0.3, 22, ROOM_D * 0.5);
            sun.castShadow = true;
            sun.shadow.mapSize.set(2048, 2048);
            Object.assign(sun.shadow.camera, {
                left: -ROOM_W / 2 - 2, right: ROOM_W / 2 + 2,
                top: ROOM_D / 2 + 2, bottom: -ROOM_D / 2 - 2, near: 1, far: 70
            });
            scene.add(sun);

            const redGlow = new THREE.PointLight(0xff2525, 0.9, ROOM_W * 1.1, 2);
            redGlow.position.set(0, WALL_H - 0.6, -ROOM_D / 2 + 1.2);
            scene.add(redGlow);

            /* floor with the same 35px-style grid as the page */
            function floorTexture() {
                const c = document.createElement('canvas');
                c.width = c.height = 256;
                const g = c.getContext('2d');
                const grad = g.createLinearGradient(0, 0, 256, 256);
                grad.addColorStop(0, '#24242b');
                grad.addColorStop(1, '#15151b');
                g.fillStyle = grad;
                g.fillRect(0, 0, 256, 256);
                g.strokeStyle = 'rgba(255,255,255,.07)';
                g.lineWidth = 2;
                g.strokeRect(0, 0, 256, 256);
                const tex = new THREE.CanvasTexture(c);
                tex.wrapS = tex.wrapT = THREE.RepeatWrapping;
                tex.repeat.set(ROOM_W / 2.2, ROOM_D / 2.2);
                tex.anisotropy = 4;
                return tex;
            }

            const floor = new THREE.Mesh(
                new THREE.PlaneGeometry(ROOM_W, ROOM_D),
                new THREE.MeshStandardMaterial({ map: floorTexture(), roughness: 0.85, metalness: 0.05 })
            );
            floor.rotation.x = -Math.PI / 2;
            floor.receiveShadow = true;
            scene.add(floor);

            // raised platform edge
            const edge = new THREE.Mesh(
                new THREE.BoxGeometry(ROOM_W + 0.8, 0.3, ROOM_D + 0.8),
                new THREE.MeshStandardMaterial({ color: 0x0a0a0e, roughness: 0.9 })
            );
            edge.position.y = -0.17;
            scene.add(edge);

            // back wall + red neon strip
            const wall = new THREE.Mesh(
                new THREE.BoxGeometry(ROOM_W, WALL_H, 0.4),
                new THREE.MeshStandardMaterial({ color: 0x1b1b22, roughness: 0.9 })
            );
            wall.position.set(0, WALL_H / 2, -ROOM_D / 2 - 0.2);
            wall.receiveShadow = true;
            scene.add(wall);

            const neon = new THREE.Mesh(
                new THREE.BoxGeometry(ROOM_W * 0.7, 0.12, 0.1),
                new THREE.MeshBasicMaterial({ color: 0xff2525 })
            );
            neon.position.set(0, WALL_H - 1.1, -ROOM_D / 2 + 0.05);
            scene.add(neon);

            const panelMat = new THREE.MeshStandardMaterial({ color: 0x24242c, roughness: 0.8 });
            const panelCount = Math.max(3, Math.floor(ROOM_W / 5));
            for (let i = 0; i < panelCount; i++) {
                const p = new THREE.Mesh(new THREE.BoxGeometry(2.6, 2.6, 0.15), panelMat);
                p.position.set((i - (panelCount - 1) / 2) * (ROOM_W / panelCount), 2.3, -ROOM_D / 2 + 0.02);
                scene.add(p);
            }

            // entrance mat at the front (matches the "RESTAURANT ENTRANCE" label)
            const fontFamily = getComputedStyle(document.body).fontFamily || 'Arial, sans-serif';

            function textTexture(w, h, draw) {
                const c = document.createElement('canvas');
                c.width = w; c.height = h;
                draw(c.getContext('2d'), w, h);
                const tex = new THREE.CanvasTexture(c);
                tex.anisotropy = 4;
                return tex;
            }

            function roundRect(g, x, y, w, h, r) {
                g.beginPath();
                g.moveTo(x + r, y);
                g.arcTo(x + w, y, x + w, y + h, r);
                g.arcTo(x + w, y + h, x, y + h, r);
                g.arcTo(x, y + h, x, y, r);
                g.arcTo(x, y, x + w, y, r);
                g.closePath();
            }

            const matTex = textTexture(512, 96, function (g, w, h) {
                const grad = g.createLinearGradient(0, 0, w, h);
                grad.addColorStop(0, '#48434a');
                grad.addColorStop(1, '#25232a');
                g.fillStyle = grad;
                roundRect(g, 4, 4, w - 8, h - 8, 18);
                g.fill();
                g.strokeStyle = '#77717a';
                g.lineWidth = 3;
                g.stroke();
                g.fillStyle = '#fff';
                g.font = '900 34px ' + fontFamily;
                g.textAlign = 'center';
                g.textBaseline = 'middle';
                if ('letterSpacing' in g) g.letterSpacing = '6px';
                g.fillText('RESTAURANT ENTRANCE', w / 2, h / 2 + 2);
            });
            const mat = new THREE.Mesh(
                new THREE.PlaneGeometry(7, 1.31),
                new THREE.MeshBasicMaterial({ map: matTex, transparent: true })
            );
            mat.rotation.x = -Math.PI / 2;
            mat.position.set(0, 0.03, ROOM_D / 2 - 0.9);
            scene.add(mat);

            /* ------------------------------------------------ tables */

            const objs = {};       // id -> object
            const pickables = [];

            const woodMat = function () {
                return new THREE.MeshStandardMaterial({ color: 0xa88f76, roughness: 0.45, metalness: 0.05, emissive: 0x000000 });
            };
            const legMat = new THREE.MeshStandardMaterial({ color: 0x4a3827, roughness: 0.6 });
            const chairMat = new THREE.MeshStandardMaterial({ color: 0x2c2c36, roughness: 0.7 });
            const chairAccent = new THREE.MeshStandardMaterial({ color: 0x3a1519, roughness: 0.7 });

            function chairSpots(d) {
                const out = [];
                const k = d.n;
                const o = 0.6;
                if (d.shape === 'round') {
                    for (let i = 0; i < k; i++) {
                        const a = (i / k) * Math.PI * 2 + Math.PI / k;
                        out.push({ x: Math.sin(a) * (d.r + o), z: Math.cos(a) * (d.r + o), a: a + Math.PI });
                    }
                    return out;
                }
                if (d.shape === 'square') {
                    const spots = [
                        { x: 0, z: d.d / 2 + o, a: Math.PI },
                        { x: 0, z: -d.d / 2 - o, a: 0 },
                        { x: d.w / 2 + o, z: 0, a: -Math.PI / 2 },
                        { x: -d.w / 2 - o, z: 0, a: Math.PI / 2 }
                    ];
                    return spots.slice(0, k);
                }
                const ends = k >= 6 ? 2 : 0;
                const rest = k - ends;
                const top = Math.ceil(rest / 2);
                const bot = rest - top;
                const row = function (count, sgn, a) {
                    const sp = Math.min(1.2, d.w / Math.max(count, 1) * 0.9);
                    for (let i = 0; i < count; i++) {
                        out.push({ x: (i - (count - 1) / 2) * sp, z: sgn * (d.d / 2 + o), a: a });
                    }
                };
                row(top, 1, Math.PI);
                row(bot, -1, 0);
                if (ends >= 1) out.push({ x: d.w / 2 + o, z: 0, a: -Math.PI / 2 });
                if (ends >= 2) out.push({ x: -d.w / 2 - o, z: 0, a: Math.PI / 2 });
                return out;
            }

            function makeLabel(number, capacity) {
                const tex = textTexture(256, 128, function (g, w, h) {
                    g.fillStyle = 'rgba(16,16,20,.88)';
                    roundRect(g, 6, 6, w - 12, h - 12, 22);
                    g.fill();
                    g.strokeStyle = 'rgba(255,255,255,.18)';
                    g.lineWidth = 3;
                    g.stroke();
                    g.fillStyle = '#fff';
                    g.textAlign = 'center';
                    g.textBaseline = 'middle';
                    let size = 46;
                    g.font = '900 ' + size + 'px ' + fontFamily;
                    while (g.measureText(number).width > w - 50 && size > 22) {
                        size -= 2;
                        g.font = '900 ' + size + 'px ' + fontFamily;
                    }
                    g.fillText(number, w / 2, 52);
                    g.fillStyle = '#aaa';
                    g.font = '700 24px ' + fontFamily;
                    g.fillText(capacity + ' Guests', w / 2, 94);
                });
                const sprite = new THREE.Sprite(new THREE.SpriteMaterial({ map: tex, transparent: true, depthTest: false }));
                sprite.scale.set(2.2, 1.1, 1);
                sprite.renderOrder = 10;
                return sprite;
            }

            TABLES.forEach(function (t) {
                const d = t.dim;
                const group = new THREE.Group();
                group.position.set(t.px, 0, t.pz);

                const topMat = woodMat();
                const top = new THREE.Mesh(
                    d.shape === 'round'
                        ? new THREE.CylinderGeometry(d.r, d.r, 0.14, 48)
                        : new THREE.BoxGeometry(d.w, 0.14, d.d),
                    topMat
                );
                top.position.y = 1.0;
                top.castShadow = top.receiveShadow = true;
                group.add(top);

                const leg = new THREE.Mesh(new THREE.CylinderGeometry(0.12, 0.22, 1.0, 14), legMat);
                leg.position.y = 0.5;
                leg.castShadow = true;
                group.add(leg);

                const base = new THREE.Mesh(new THREE.CylinderGeometry(0.55, 0.6, 0.06, 24), legMat);
                base.position.y = 0.03;
                group.add(base);

                // status ring on the floor
                const ringR = (d.shape === 'round' ? d.r : Math.max(d.w, d.d) / 2) + 1.15 + (d.shape === 'rect' && d.n >= 6 ? 0.4 : 0);
                const ringMat = new THREE.MeshBasicMaterial({ color: GREEN, transparent: true, opacity: 0.45, side: THREE.DoubleSide });
                const ring = new THREE.Mesh(new THREE.RingGeometry(ringR - 0.1, ringR, 64), ringMat);
                ring.rotation.x = -Math.PI / 2;
                ring.position.y = 0.03;
                group.add(ring);

                const glowMat = new THREE.MeshBasicMaterial({ color: RED, transparent: true, opacity: 0, side: THREE.DoubleSide });
                const glow = new THREE.Mesh(new THREE.CircleGeometry(ringR - 0.1, 64), glowMat);
                glow.rotation.x = -Math.PI / 2;
                glow.position.y = 0.025;
                group.add(glow);

                const chairs = [];
                chairSpots(d).forEach(function (s) {
                    const chair = new THREE.Group();
                    const seat = new THREE.Mesh(new THREE.BoxGeometry(0.66, 0.1, 0.66), chairMat);
                    seat.position.y = 0.56;
                    const back = new THREE.Mesh(new THREE.BoxGeometry(0.66, 0.72, 0.09), chairAccent);
                    back.position.set(0, 0.97, -0.28);
                    const stem = new THREE.Mesh(new THREE.BoxGeometry(0.16, 0.52, 0.16), chairMat);
                    stem.position.y = 0.26;
                    [seat, back, stem].forEach(function (m) { m.castShadow = true; chair.add(m); });
                    chair.position.set(s.x, 0, s.z);
                    chair.rotation.y = s.a;
                    group.add(chair);
                    chairs.push(back);
                });

                const candle = new THREE.Mesh(
                    new THREE.CylinderGeometry(0.05, 0.05, 0.22, 10),
                    new THREE.MeshBasicMaterial({ color: 0xfff0c8 })
                );
                candle.position.y = 1.19;
                group.add(candle);

                const label = makeLabel(t.number, t.capacity);
                label.position.set(0, 2.35, 0);
                group.add(label);

                scene.add(group);

                group.traverse(function (o) {
                    if (o.isMesh && o !== glow) { o.userData.tableId = t.id; pickables.push(o); }
                });

                objs[t.id] = { data: t, group: group, topMat: topMat, ring: ring, ringMat: ringMat, glowMat: glowMat, chairs: chairs };
            });

            /* ------------------------------------------------ selection sync */

            let hoverId = null;
            let selectedId = null;

            function radioFor(id) { return document.getElementById('table_' + id); }

            function readSelection() {
                const r = document.querySelector('.table-radio:checked');
                return r ? Number(r.value) : null;
            }

            function paint() {
                Object.keys(objs).forEach(function (key) {
                    const id = Number(key);
                    const o = objs[id];
                    const sel = id === selectedId;
                    o.topMat.color.setHex(sel ? 0xe03a3a : 0xa88f76);
                    o.topMat.emissive.setHex(sel ? 0x4a0505 : (id === hoverId ? 0x2a1e12 : 0x000000));
                    o.ringMat.color.setHex(sel ? RED : GREEN);
                    o.ringMat.opacity = sel ? 0.95 : (id === hoverId ? 0.8 : 0.45);
                    o.glowMat.opacity = sel ? 0.12 : 0;
                    if (!sel) o.ring.scale.set(1, 1, 1);
                });
            }

            function selectFrom3D(id) {
                const r = radioFor(id);
                if (!r) return;
                if (!r.checked) {
                    r.checked = true;
                    r.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            document.querySelectorAll('.table-radio').forEach(function (r) {
                r.addEventListener('change', function () {
                    selectedId = readSelection();
                    paint();
                    if (selectedId && objs[selectedId]) flyTo(objs[selectedId].data.px, objs[selectedId].data.pz);
                });
            });

            /* ------------------------------------------------ camera */

            let userMoved = false;
            controls.addEventListener('start', function () { userMoved = true; });

            function fitCamera() {
                const aspect = Math.max(camera.aspect, 0.3);
                const vfov = THREE.MathUtils.degToRad(camera.fov);
                const hfov = 2 * Math.atan(Math.tan(vfov / 2) * aspect);
                const limiting = Math.min(vfov, hfov);
                const span = Math.max(ROOM_W, ROOM_D * 1.15) / 2;
                const dist = (span * 1.22) / Math.tan(limiting / 2);
                const elev = THREE.MathUtils.degToRad(54);
                camera.position.set(0, Math.sin(elev) * dist, Math.cos(elev) * dist + 0.5);
                controls.target.set(0, 0, 0);
                controls.minDistance = Math.max(7, dist * 0.35);
                controls.maxDistance = dist * 1.6;
                camera.far = dist * 6;
                camera.updateProjectionMatrix();
            }

            let flight = null;
            function flyTo(x, z) {
                flight = { from: controls.target.clone(), to: new THREE.Vector3(x * 0.55, 0, z * 0.55), t: 0 };
            }

            /* ------------------------------------------------ interaction */

            const raycaster = new THREE.Raycaster();
            const pointer = new THREE.Vector2();
            let down = null;

            function pick(ev) {
                const rect = renderer.domElement.getBoundingClientRect();
                pointer.set(((ev.clientX - rect.left) / rect.width) * 2 - 1, -((ev.clientY - rect.top) / rect.height) * 2 + 1);
                raycaster.setFromCamera(pointer, camera);
                const hit = raycaster.intersectObjects(pickables, false)[0];
                return hit ? hit.object.userData.tableId : null;
            }

            renderer.domElement.addEventListener('pointerdown', function (e) { down = { x: e.clientX, y: e.clientY }; });

            renderer.domElement.addEventListener('pointerup', function (e) {
                if (!down) return;
                const moved = Math.hypot(e.clientX - down.x, e.clientY - down.y);
                down = null;
                if (moved > 6) return; // drag, not a click
                const id = pick(e);
                if (id !== null) selectFrom3D(id);
            });

            renderer.domElement.addEventListener('pointermove', function (e) {
                const id = pick(e);
                if (id !== hoverId) { hoverId = id; paint(); }
                renderer.domElement.style.cursor = id !== null ? 'pointer' : 'grab';

                if (id !== null) {
                    const t = objs[id].data;
                    const r = wrap.getBoundingClientRect();
                    tip.hidden = false;
                    tip.innerHTML = 'Table ' + escapeHtml(t.number) + ' · ' + t.capacity + ' Guests<small>' + escapeHtml(t.type) + '</small>';
                    tip.style.left = Math.min(e.clientX - r.left, r.width - 190) + 'px';
                    tip.style.top = Math.min(e.clientY - r.top, r.height - 70) + 'px';
                } else {
                    tip.hidden = true;
                }
            });

            renderer.domElement.addEventListener('pointerleave', function () {
                tip.hidden = true; hoverId = null; paint();
            });

            function escapeHtml(s) {
                return String(s).replace(/[&<>"']/g, function (c) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                });
            }

            /* ------------------------------------------------ loop */

            function resize() {
                const w = host.clientWidth, h = host.clientHeight;
                if (!w || !h) return;
                renderer.setSize(w, h);
                camera.aspect = w / h;
                camera.updateProjectionMatrix();
                if (!userMoved) fitCamera();
            }

            window.addEventListener('resize', resize);
            if (window.ResizeObserver) new ResizeObserver(resize).observe(host);

            const clock = new THREE.Clock();

            function animate() {
                requestAnimationFrame(animate);
                if (mode !== '3d' || document.hidden) return;

                const t = clock.getElapsedTime();

                if (flight) {
                    flight.t = Math.min(1, flight.t + 0.045);
                    const k = 1 - Math.pow(1 - flight.t, 3);
                    controls.target.lerpVectors(flight.from, flight.to, k);
                    if (flight.t >= 1) flight = null;
                }

                if (selectedId && objs[selectedId]) {
                    const s = 1 + Math.sin(t * 3.2) * 0.025;
                    objs[selectedId].ring.scale.set(s, s, 1);
                }

                controls.update();
                renderer.render(scene, camera);
            }

            ready = true;
            selectedId = readSelection();
            paint();
            resize();
            fitCamera();
            if (selectedId && objs[selectedId]) {
                controls.target.set(objs[selectedId].data.px * 0.55, 0, objs[selectedId].data.pz * 0.55);
            }
            animate();
        });
    </script>

@endsection