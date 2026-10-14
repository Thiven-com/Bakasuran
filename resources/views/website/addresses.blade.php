@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
                                                   SUDHEERA SAREES - ADDRESS PAGE
                                                ========================================================= */

        .sudheera-address-page {
            background: #fbf8f3;
            min-height: 100vh;
            padding: 10px 0 70px;
            color: #420916;
        }

        /* PAGE HEADER */
        .sudheera-address-header {
            text-align: center;
            padding-top: 25px;
            margin-bottom: 42px;
        }

        .sudheera-address-eyebrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #a47a25;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .sudheera-address-eyebrow::before,
        .sudheera-address-eyebrow::after {
            content: "";
            width: 42px;
            height: 1px;
            background: #c9a35b;
        }

        .sudheera-address-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 54px;
            line-height: 1;
            font-weight: 600;
            color: #420916;
            margin: 0 0 15px;
        }

        .sudheera-address-subtitle {
            max-width: 570px;
            margin: 0 auto 16px;
            color: #756c64;
            font-size: 15px;
            line-height: 1.7;
        }

        .sudheera-breadcrumb {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            font-size: 13px;
        }

        .sudheera-breadcrumb a {
            color: #76001f;
            text-decoration: none;
            font-weight: 600;
        }

        .sudheera-breadcrumb a:hover {
            color: #c88618;
        }

        .sudheera-breadcrumb span {
            color: #b6a99c;
        }

        .sudheera-breadcrumb .active {
            color: #83786e;
        }

        .sudheera-header-line {
            width: 70px;
            height: 3px;
            border-radius: 50px;
            background: linear-gradient(90deg,
                    #420916,
                    #c88618,
                    #420916);
            margin: 22px auto 0;
        }

        /* TOP ACTION */
        .sudheera-address-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .sudheera-add-address-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 45px;
            padding: 10px 20px;
            background: #420916;
            border: 1px solid #420916;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all .3s ease;
        }

        .sudheera-add-address-btn i {
            color: #c88618;
        }

        .sudheera-add-address-btn:hover {
            background: linear-gradient(135deg,
                    #420916,
                    #76001f,
                    #a85c17);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(66, 9, 22, .15);
        }

        /* ADDRESS GRID */
        .sudheera-address-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        /* ADDRESS CARD */
        .sudheera-address-card {
            position: relative;
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 8px 30px rgba(66, 9, 22, .055);
            overflow: hidden;
            transition: all .3s ease;
        }

        .sudheera-address-card::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 3px;
            background: linear-gradient(90deg,
                    #420916,
                    #8f3b16,
                    #c88618);
        }

        .sudheera-address-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(66, 9, 22, .09);
        }

        /* CARD HEADER */
        .sudheera-address-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .sudheera-address-type {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 24px;
            font-weight: 700;
            color: #420916;
            margin: 0;
        }

        .sudheera-default-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 50px;
            background: #edf5ed;
            color: #52743d;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .sudheera-address-actions-small {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sudheera-icon-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eadfce;
            border-radius: 8px;
            background: #fff;
            color: #76001f;
            cursor: pointer;
            transition: all .3s ease;
        }

        .sudheera-icon-btn:hover {
            background: #420916;
            border-color: #420916;
            color: #fff;
        }

        .sudheera-delete-btn {
            color: #a52d2d;
        }

        /* ADDRESS CONTENT */
        .sudheera-address-content {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 18px;
            background: #fcfaf7;
            border: 1px solid #eee6dc;
            border-radius: 13px;
        }

        .sudheera-location-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #420916;
            color: #c88618;
            font-size: 15px;
        }

        .sudheera-address-info {
            min-width: 0;
        }

        .sudheera-address-name {
            color: #420916;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .sudheera-address-text {
            color: #70675e;
            font-size: 13px;
            line-height: 1.75;
            margin: 0;
        }

        .sudheera-address-phone {
            color: #420916;
            font-weight: 700;
        }

        /* DEFAULT BUTTON */
        .sudheera-set-default {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 42px;
            margin-top: 16px;
            border: 1px solid #c88618;
            border-radius: 9px;
            background: #fff;
            color: #8a5c13;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: all .3s ease;
        }

        .sudheera-set-default:hover {
            background: #420916;
            border-color: #420916;
            color: #fff;
        }

        /* MODAL */
        .sudheera-address-modal {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(66, 9, 22, .20);
        }

        .sudheera-address-modal .modal-header {
            background: #420916;
            color: #fff;
            padding: 20px 24px;
            border: 0;
        }

        .sudheera-address-modal .modal-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 26px;
            font-weight: 700;
        }

        .sudheera-address-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .9;
        }

        .sudheera-address-modal .modal-body {
            padding: 25px;
            background: #fff;
        }

        .sudheera-address-modal .modal-footer {
            padding: 18px 25px;
            border-top: 1px solid #eee6dc;
            background: #fcfaf7;
        }

        .sudheera-address-modal .form-label {
            color: #420916;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .sudheera-address-modal .form-control,
        .sudheera-address-modal .form-select {
            border: 1px solid #dfd3c3;
            border-radius: 9px;
            min-height: 44px;
            font-size: 13px;
            color: #420916;
        }

        .sudheera-address-modal textarea.form-control {
            min-height: 90px;
            resize: vertical;
        }

        .sudheera-address-modal .form-control:focus,
        .sudheera-address-modal .form-select:focus {
            border-color: #c88618;
            box-shadow: 0 0 0 3px rgba(200, 134, 24, .10);
        }

        .sudheera-save-btn {
            background: #420916;
            border: 1px solid #420916;
            color: #fff;
            border-radius: 9px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .sudheera-save-btn:hover {
            background: linear-gradient(135deg,
                    #420916,
                    #76001f,
                    #a85c17);
            border-color: #76001f;
            color: #fff;
        }

        .sudheera-cancel-btn {
            border: 1px solid #d8cbb9;
            background: #fff;
            color: #70675e;
            border-radius: 9px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .sudheera-cancel-btn:hover {
            background: #f5efe7;
            color: #420916;
        }

        /* MOBILE */
        @media (max-width: 991px) {

            .sudheera-address-title {
                font-size: 46px;
            }

            .sudheera-address-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {

            .sudheera-address-page {
                padding: 25px 0 55px;
            }

            .sudheera-address-header {
                padding-top: 15px;
                margin-bottom: 30px;
            }

            .sudheera-address-eyebrow {
                font-size: 9px;
                letter-spacing: 2px;
            }

            .sudheera-address-eyebrow::before,
            .sudheera-address-eyebrow::after {
                width: 25px;
            }

            .sudheera-address-title {
                font-size: 38px;
            }

            .sudheera-address-subtitle {
                font-size: 13px;
                padding: 0 15px;
            }

            .sudheera-breadcrumb {
                font-size: 12px;
            }

            .sudheera-header-line {
                margin-top: 18px;
            }

            .sudheera-address-actions {
                justify-content: stretch;
            }

            .sudheera-add-address-btn {
                width: 100%;
            }

            .sudheera-address-card {
                padding: 20px;
                border-radius: 16px;
            }

            .sudheera-address-card-header {
                align-items: flex-start;
            }

            .sudheera-address-type {
                font-size: 22px;
            }

            .sudheera-address-content {
                padding: 14px;
            }

            .sudheera-address-text {
                font-size: 12px;
            }

            .sudheera-address-modal .modal-body {
                padding: 20px;
            }

            .sudheera-address-modal .modal-footer {
                padding: 15px 20px;
            }
        }

        @media (max-width: 480px) {

            .sudheera-address-title {
                font-size: 34px;
            }

            .sudheera-address-card {
                padding: 17px;
            }

            .sudheera-address-card-header {
                flex-wrap: wrap;
            }

            .sudheera-address-actions-small {
                width: 100%;
            }

            .sudheera-icon-btn {
                flex: 1;
            }

            .sudheera-address-modal .modal-title {
                font-size: 23px;
            }
        }


        /* =========================================================
               EDIT ADDRESS FORM
            ========================================================= */

        #editAddressForm {
            width: 100%;
        }

        /* FORM ROW */
        #editAddressForm .edit-form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        /* FORM GROUP */
        #editAddressForm .edit-form-group {
            margin-bottom: 18px;
        }

        #editAddressForm .edit-form-group.full-width {
            grid-column: 1 / -1;
        }

        /* LABEL */
        #editAddressForm .edit-form-label {
            display: block;
            margin-bottom: 7px;
            color: #420916;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .2px;
        }

        /* INPUT */
        #editAddressForm input[type="text"],
        #editAddressForm input[type="email"],
        #editAddressForm input[type="tel"],
        #editAddressForm select,
        #editAddressForm textarea {
            width: 100%;
            border: 1px solid #dfd3c3;
            border-radius: 9px;
            background: #fff;
            color: #420916;
            font-size: 13px;
            padding: 10px 13px;
            outline: none;
            transition: all .25s ease;
            box-sizing: border-box;
        }

        /* INPUT HEIGHT */
        #editAddressForm input[type="text"],
        #editAddressForm input[type="email"],
        #editAddressForm input[type="tel"],
        #editAddressForm select {
            min-height: 44px;
            margin-bottom: 10px
        }

        /* TEXTAREA */
        #editAddressForm textarea {
            min-height: 95px;
            resize: vertical;
            line-height: 1.6;
        }

        /* PLACEHOLDER */
        #editAddressForm input::placeholder,
        #editAddressForm textarea::placeholder {
            color: #aaa098;
            opacity: 1;
        }

        /* FOCUS */
        #editAddressForm input:focus,
        #editAddressForm select:focus,
        #editAddressForm textarea:focus {
            border-color: #c88618;
            box-shadow: 0 0 0 3px rgba(200, 134, 24, .10);
            background: #fff;
        }

        /* SELECT */
        #editAddressForm select {
            cursor: pointer;
            appearance: auto;
        }

        /* REQUIRED STAR */
        #editAddressForm .required-star {
            color: #a52d2d;
            margin-left: 2px;
        }

        /* INPUT ICON WRAPPER */
        #editAddressForm .edit-input-wrapper {
            position: relative;
        }

        #editAddressForm .edit-input-wrapper i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #c88618;
            font-size: 13px;
            pointer-events: none;
        }

        #editAddressForm .edit-input-wrapper input {
            padding-left: 38px;
        }

        /* TEXTAREA ICON */
        #editAddressForm .edit-textarea-wrapper {
            position: relative;
        }

        #editAddressForm .edit-textarea-wrapper i {
            position: absolute;
            left: 13px;
            top: 14px;
            color: #c88618;
            font-size: 13px;
            pointer-events: none;
        }

        #editAddressForm .edit-textarea-wrapper textarea {
            padding-left: 38px;
        }

        /* HIDE THE INLINE BUTTON INSIDE FORM
               We use the modal footer button instead */
        #editAddressForm>.sudheera-save-btn {
            display: none;
        }

        /* MODAL FOOTER */
        #modalEditAddress .modal-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        /* SAVE BUTTON */
        #modalEditAddress .sudheera-save-btn {
            min-width: 145px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #420916;
            border: 1px solid #420916;
            color: #fff;
            border-radius: 9px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
            transition: all .3s ease;
        }

        #modalEditAddress .sudheera-save-btn:hover {
            background: linear-gradient(135deg,
                    #420916,
                    #76001f,
                    #a85c17);
            border-color: #76001f;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(66, 9, 22, .15);
        }

        /* CANCEL BUTTON */
        #modalEditAddress .sudheera-cancel-btn {
            min-width: 100px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* SECTION TITLE */
        #editAddressForm .edit-section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 3px 0 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee6dc;
            color: #420916;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 21px;
            font-weight: 700;
        }

        #editAddressForm .edit-section-title i {
            color: #c88618;
            font-size: 15px;
        }

        /* MOBILE */
        @media (max-width: 767px) {

            #editAddressForm .edit-form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            #editAddressForm .edit-form-group.full-width {
                grid-column: auto;
            }

            #editAddressForm .edit-form-group {
                margin-bottom: 15px;
            }

            #editAddressForm input[type="text"],
            #editAddressForm input[type="email"],
            #editAddressForm input[type="tel"],
            #editAddressForm select {
                min-height: 46px;
            }

            #editAddressForm textarea {
                min-height: 100px;
            }

            #modalEditAddress .modal-footer {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            #modalEditAddress .sudheera-save-btn,
            #modalEditAddress .sudheera-cancel-btn {
                width: 100%;
            }
        }
    </style>

    <!-- =========================================================
                                                 ADDRESS PAGE
                                            ========================================================= -->

    <main class="sudheera-address-page">


        <div class="container">

            <!-- PAGE HEADER -->

            <div class="sudheera-address-header">

                <div class="sudheera-address-eyebrow">
                    BAKASURAN FOOD
                </div>

                <h1 class="sudheera-address-title">
                    My Addresses
                </h1>

                <p class="sudheera-address-subtitle">
                    Manage your saved delivery addresses and
                    choose where you want your favourite food delivered.
                </p>

                <div class="sudheera-breadcrumb">

                    <a href="#">
                        Home
                    </a>

                    <span>/</span>

                    <span class="active">
                        Address
                    </span>

                </div>

                <div class="sudheera-header-line"></div>

            </div>


            <!-- ADD ADDRESS BUTTON -->

            <div class="sudheera-address-actions">

                <a href="#modalAddAddress" data-bs-toggle="modal" class="sudheera-add-address-btn">

                    <i class="fa fa-plus"></i>

                    Add New Address

                </a>

            </div>


            <!-- =====================================================
                                                     ADDRESS CARDS
                                                ====================================================== -->


            <div class="sudheera-address-grid">

                @forelse($addresses as $address)

                    <div class="sudheera-address-card">

                        <div class="sudheera-address-card-header">

                            <div class="d-flex align-items-center gap-2">

                                <h5 class="sudheera-address-type">
                                    {{ $address->address_type ?? 'Address' }}
                                </h5>

                                @if($address->is_default)

                                    <span class="sudheera-default-badge">

                                        <i class="fa fa-check"></i>

                                        Default

                                    </span>

                                @endif

                            </div>


                            <div class="sudheera-address-actions-small">

                                <!-- EDIT -->

                                <button type="button" class="sudheera-icon-btn" onclick="openEditAddress(this)"
                                    data-id="{{ $address->id }}" data-type="{{ $address->address_type ?? 'Home' }}"
                                    data-name="{{ $address->name }}" data-mobile="{{ $address->mobile }}"
                                    data-email="{{ $address->email }}" data-address="{{ $address->address }}"
                                    data-address2="{{ $address->address_2 }}" data-city="{{ $address->city }}"
                                    data-state-id="{{ $address->state_id }}" data-pincode="{{ $address->pincode }}"
                                    title="Edit">

                                    <i class="fa fa-edit"></i>

                                </button>


                                <!-- DELETE -->

                                <form action="{{ route('addresses.delete', $address->id) }}" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this address?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="sudheera-icon-btn sudheera-delete-btn" title="Delete">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </div>


                        <div class="sudheera-address-content">

                            <div class="sudheera-location-icon">

                                @if(strtolower($address->address_type ?? '') === 'office')

                                    <i class="fa fa-building"></i>

                                @elseif(strtolower($address->address_type ?? '') === 'other')

                                    <i class="fa fa-map-marker"></i>

                                @else

                                    <i class="fa fa-home"></i>

                                @endif

                            </div>


                            <div class="sudheera-address-info">

                                <div class="sudheera-address-name">

                                    {{ $address->name }}

                                </div>


                                <p class="sudheera-address-text">

                                    <span class="sudheera-address-phone">

                                        {{ $address->mobile }}

                                    </span>

                                    <br>


                                    {{ $address->address }}

                                    @if($address->address_2)

                                        , {{ $address->address_2 }}

                                    @endif

                                    @if($address->landmark)

                                        , {{ $address->landmark }}

                                    @endif

                                    <br>


                                    {{ $address->city }}

                                    @if($address->state)

                                        , {{ $address->state }}

                                    @endif

                                    - {{ $address->pincode }}

                                    <br>

                                    India

                                </p>

                            </div>

                        </div>


                        @if(!$address->is_default)

                            <form action="{{ route('addresses.default', $address->id) }}" method="POST">

                                @csrf

                                <button type="submit" class="sudheera-set-default">

                                    Set as Default

                                </button>

                            </form>

                        @endif

                    </div>

                @empty

                    <div class="sudheera-address-card" style="grid-column: 1 / -1; text-align:center;">

                        <div style="padding:40px 20px;">

                            <i class="fa fa-map-marker" style="font-size:40px;color:#c88618;margin-bottom:15px;">
                            </i>

                            <h5 style="color:#420916;">
                                No Addresses Found
                            </h5>

                            <p style="color:#756c64;">
                                Add your first delivery address to enjoy a smooth food ordering experience with BAKASURAN.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </main>

    <!-- =========================================================
                                                 ADD ADDRESS MODAL
                                            ========================================================= -->

    <div class="modal fade" id="modalAddAddress" tabindex="-1" aria-hidden="true">


        <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 120px;">

            <div class="modal-content sudheera-address-modal">

                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add New Address
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <!-- BODY -->

                <div class="modal-body">

                    <form action="{{ route('addresses.store') }}" method="POST" id="addAddressForm">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Full Name *
                                </label>

                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $customer->name) }}" placeholder="Enter full name" required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Phone Number *
                                </label>

                                <input type="text" name="mobile" class="form-control"
                                    value="{{ old('mobile', $customer->mobile) }}" placeholder="Enter phone number"
                                    required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Alternate Phone Number
                                </label>

                                <input type="text" name="alternate_mobile" class="form-control"
                                    placeholder="Enter alternate phone number">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $customer->email) }}" placeholder="Enter email">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Pincode *
                                </label>

                                <input type="text" name="pincode" class="form-control" placeholder="Enter pincode" required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    City *
                                </label>

                                <input type="text" name="city" class="form-control" placeholder="Enter city" required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Landmark
                                </label>

                                <input type="text" name="landmark" class="form-control" placeholder="Enter landmark">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    State *
                                </label>

                                <select name="state_id" class="form-select" required>

                                    <option value="">
                                        Select State
                                    </option>

                                    {{-- Replace these IDs with your actual states table IDs --}}

                                    <option value="1">Andhra Pradesh</option>
                                    <option value="2">Arunachal Pradesh</option>
                                    <option value="3">Assam</option>
                                    <option value="4">Bihar</option>
                                    <option value="5">Chhattisgarh</option>
                                    <option value="6">Goa</option>
                                    <option value="7">Gujarat</option>
                                    <option value="8">Haryana</option>
                                    <option value="9">Himachal Pradesh</option>
                                    <option value="10">Jharkhand</option>
                                    <option value="11">Karnataka</option>
                                    <option value="12">Kerala</option>
                                    <option value="13">Madhya Pradesh</option>
                                    <option value="14">Maharashtra</option>
                                    <option value="15">Manipur</option>
                                    <option value="16">Meghalaya</option>
                                    <option value="17">Mizoram</option>
                                    <option value="18">Nagaland</option>
                                    <option value="19">Odisha</option>
                                    <option value="20">Punjab</option>
                                    <option value="21">Rajasthan</option>
                                    <option value="22">Sikkim</option>
                                    <option value="23">Tamil Nadu</option>
                                    <option value="24">Telangana</option>
                                    <option value="25">Tripura</option>
                                    <option value="26">Uttar Pradesh</option>
                                    <option value="27">Uttarakhand</option>
                                    <option value="28">West Bengal</option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Address Type
                                </label>

                                <select name="address_type" class="form-select">

                                    <option value="Home">
                                        Home
                                    </option>

                                    <option value="Office">
                                        Office
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    GST
                                </label>

                                <input type="text" name="gst" class="form-control" placeholder="Enter GST Number">

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    House No, Street *
                                </label>

                                <textarea name="address" class="form-control" rows="3"
                                    placeholder="House No, Street, Area..." required></textarea>

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    Address 2
                                </label>

                                <textarea name="address_2" class="form-control" rows="3"
                                    placeholder="Apartment, Area, Additional Address"></textarea>

                            </div>

                        </div>

                    </form>

                </div>


                <!-- FOOTER -->

                <div class="modal-footer">

                    <button type="button" class="sudheera-cancel-btn" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit" form="addAddressForm" class="sudheera-save-btn">

                        <i class="fa fa-check me-1"></i>

                        Save Address

                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================================================
                                                 EDIT ADDRESS MODAL
                                            ========================================================= -->

    <div class="modal fade" id="modalEditAddress" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 120px;">

            <div class="modal-content sudheera-address-modal">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Address
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">


                    <form id="editAddressForm" method="POST">

                        @csrf
                        @method('PUT')

                        <input type="text" id="editName" name="name" required>

                        <input type="text" id="editMobile" name="mobile" required>

                        <input type="email" id="editEmail" name="email">

                        <input type="text" id="editPincode" name="pincode" required>

                        <input type="text" id="editCity" name="city" required>

                        <select id="editState" name="state_id" required>

                            {{-- Your actual states here --}}

                        </select>

                        <select id="editType" name="address_type">

                            <option value="Home">Home</option>
                            <option value="Office">Office</option>
                            <option value="Other">Other</option>

                        </select>

                        <textarea id="editAddress" name="address" required></textarea>

                        <textarea id="editAddress2" name="address_2"></textarea>

                        <button type="button" onclick="updateAddress()" class="sudheera-save-btn">

                            <i class="fa fa-check me-1"></i>
                            Update Address

                        </button>

                    </form>
                </div>


                <div class="modal-footer">

                    <button type="button" class="sudheera-cancel-btn" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit" form="editAddressForm" class="sudheera-save-btn">

                        <i class="fa fa-check me-1"></i>

                        Update Address

                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================================================
                                                 STATIC JAVASCRIPT
                                            ========================================================= -->

    <script>

        /*
        |--------------------------------------------------------------------------
        | OPEN EDIT ADDRESS
        |--------------------------------------------------------------------------
        */

        function openEditAddress(button) {

            const id = button.dataset.id;

            document.getElementById('editType').value =
                button.dataset.type || 'Home';

            document.getElementById('editName').value =
                button.dataset.name || '';

            document.getElementById('editMobile').value =
                button.dataset.mobile || '';

            document.getElementById('editEmail').value =
                button.dataset.email || '';

            document.getElementById('editAddress').value =
                button.dataset.address || '';

            document.getElementById('editAddress2').value =
                button.dataset.address2 || '';

            document.getElementById('editCity').value =
                button.dataset.city || '';

            document.getElementById('editState').value =
                button.dataset.stateId || '';

            document.getElementById('editPincode').value =
                button.dataset.pincode || '';

            /*
             * Set Laravel update URL dynamically
             *
             * Example:
             * /addresses/5
             */
            document.getElementById('editAddressForm').action =
                "{{ url('/addresses') }}/" + id;

            const modalElement =
                document.getElementById('modalEditAddress');

            const modal =
                bootstrap.Modal.getOrCreateInstance(modalElement);

            modal.show();
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE ADDRESS
        |--------------------------------------------------------------------------
        */

        function deleteAddress(button) {

            if (!confirm('Are you sure you want to delete this address?')) {
                return;
            }

                            /*
                             * Delete is handled by Laravel.
                             * The button should be inside:
                             *
                             * <form method="POST">
                             *     @csrf
                             *     @method('DELETE')
                             * </form >
                             *
                             * Submit that form.
                             */

            const form = button.closest('form');

            if (form) {
                form.submit();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ADD ADDRESS
        |--------------------------------------------------------------------------
        */

        function saveAddress() {

            const form =
                document.getElementById('addAddressForm');

            if (!form) {
                return;
            }

            /*
             * Browser validation
             */
            if (!form.checkValidity()) {

                form.reportValidity();

                return;
            }

            /*
             * Submit to Laravel.
             *
             * Do NOT use preventDefault().
             * Laravel will store the address in the database.
             */
            form.submit();
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE ADDRESS
        |--------------------------------------------------------------------------
        */

        function updateAddress() {

            const form =
                document.getElementById('editAddressForm');

            if (!form) {
                return;
            }

            /*
             * Browser validation
             */
            if (!form.checkValidity()) {

                form.reportValidity();

                return;
            }

            /*
             * Submit to Laravel.
             *
             * The form action was already set inside
             * openEditAddress().
             */
            form.submit();
        }


        /*
        |--------------------------------------------------------------------------
        | SET DEFAULT ADDRESS
        |--------------------------------------------------------------------------
        */

        function setDefaultAddress(event, button) {

            /*
             * If this button is inside a Laravel form,
             * let the form submit normally.
             */

            if (event) {
                event.preventDefault();
            }

            const form = button.closest('form');

            if (form) {
                form.submit();
            }
        }

    </script>

@endsection