@extends('layouts.website')

@section('content')

<!-- <style>
    /* =========================================================
       LOGIN PAGE
    ========================================================= */

    .sudheera-login-section {
        min-height: 70vh;
        padding: 60px 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #faf7f2;
    }

    .sudheera-login-wrapper {
        width: 100%;
        max-width: 430px;
    }

    .sudheera-login-card {
        background: #fff;
        border-radius: 18px;
        padding: 40px 35px;
        box-shadow: 0 12px 40px rgba(80, 50, 15, 0.10);
        border: 1px solid #eee3d3;
    }

    .sudheera-login-icon {
        width: 75px;
        height: 75px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: #76001f;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        box-shadow: 0 10px 25px rgba(165, 106, 22, .22);
    }

    .sudheera-login-heading {
        text-align: center;
        margin-bottom: 28px;
    }

    .sudheera-login-heading h2 {
        margin: 0 0 8px;
        font-family: "Instrument Serif", serif;
        font-size: 29px;
        font-weight: 600;
        color: #76001f;
    }

    .sudheera-login-heading p {
        margin: 0;
        color: #777;
        font-size: 14px;
        line-height: 1.6;
    }

    .sudheera-form-group {
        margin-bottom: 20px;
    }

    .sudheera-form-group label {
        display: block;
        margin-bottom: 8px;
        color: #403a34;
        font-size: 13px;
        font-weight: 600;
    }

    .sudheera-mobile-wrapper {
        display: flex;
        align-items: stretch;
        border: 1px solid #ddd1c0;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        transition: .3s ease;
    }

    .sudheera-mobile-wrapper:focus-within {
        border-color: #76001f;
        box-shadow: 0 0 0 3px rgba(165, 106, 22, .08);
    }

    .sudheera-country-code {
        display: flex;
        align-items: center;
        padding: 0 13px;
        background: #faf7f2;
        border-right: 1px solid #ddd1c0;
        color: #555;
        font-size: 14px;
        font-weight: 600;
    }

    .sudheera-mobile-input {
        width: 100%;
        border: 0;
        outline: 0;
        padding: 13px 14px;
        font-size: 14px;
        color: #333;
        background: transparent;
    }

    .sudheera-mobile-input::placeholder {
        color: #aaa;
    }

    .sudheera-mobile-input:read-only {
        background: #f8f8f8;
        cursor: not-allowed;
    }

    /* OTP */

    .sudheera-otp-group {
        display: none;
        margin-bottom: 20px;
    }

    .sudheera-otp-input {
        width: 100%;
        border: 1px solid #ddd1c0;
        border-radius: 8px;
        padding: 13px 14px;
        outline: none;
        text-align: center;
        letter-spacing: 8px;
        font-size: 20px;
        font-weight: 600;
        color: #333;
        transition: .3s ease;
    }

    .sudheera-otp-input:focus {
        border-color: #76001f;
        box-shadow: 0 0 0 3px rgba(165, 106, 22, .08);
    }

    .sudheera-otp-input::placeholder {
        letter-spacing: 5px;
        color: #aaa;
    }

    /* Button */

    .sudheera-login-btn {
        width: 100%;
        border: 0;
        border-radius: 8px;
        padding: 14px 20px;
        background: #76001f;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .5px;
        cursor: pointer;
        transition: .3s ease;
    }

    .sudheera-login-btn:hover {
        background: #76001f;
        transform: translateY(-1px);
    }

    .sudheera-login-btn:disabled {
        opacity: .65;
        cursor: not-allowed;
        transform: none;
    }

    /* Resend */

    .sudheera-resend {
        display: none;
        text-align: center;
        margin-top: 15px;
        font-size: 13px;
        color: #777;
    }

    .sudheera-resend a {
        color: #76001f;
        font-weight: 600;
        text-decoration: none;
    }

    .sudheera-resend a:hover {
        text-decoration: underline;
    }

    /* Change number */

    .sudheera-change-number {
        display: none;
        text-align: center;
        margin-top: 10px;
    }

    .sudheera-change-number a {
        color: #777;
        font-size: 12px;
        text-decoration: none;
    }

    .sudheera-change-number a:hover {
        color: #76001f;
    }

    /* Message */

    .sudheera-login-message {
        display: none;
        padding: 10px 12px;
        margin-bottom: 18px;
        border-radius: 6px;
        font-size: 13px;
        text-align: center;
    }

    .sudheera-login-message.success {
        display: block;
        color: #3d6b27;
        background: #f1f8ec;
        border: 1px solid #d5e8c8;
    }

    .sudheera-login-message.error {
        display: block;
        color: #a33b25;
        background: #fff1ed;
        border: 1px solid #f1cfc5;
    }

    /* Terms */

    .sudheera-login-terms {
        text-align: center;
        margin-top: 22px;
        font-size: 11px;
        color: #999;
        line-height: 1.6;
    }

    .sudheera-login-terms a {
        color: #76001f;
        text-decoration: none;
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 575px) {

        .sudheera-login-section {
            min-height: 75vh;
            padding: 35px 15px;
        }

        .sudheera-login-card {
            padding: 30px 20px;
            border-radius: 15px;
        }

        .sudheera-login-icon {
            width: 65px;
            height: 65px;
            font-size: 26px;
            margin-bottom: 15px;
        }

        .sudheera-login-heading {
            margin-bottom: 22px;
        }

        .sudheera-login-heading h2 {
            font-size: 20px;
        }

        .sudheera-login-heading p {
            font-size: 13px;
        }
    }
</style> -->

<style>

/* =========================================================
   BAKASURAN LOGIN PAGE — BRAND COLOURS
========================================================= */

.sudheera-login-section {
    min-height: 70vh;
    padding: 60px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0A0A0A;
}

.sudheera-login-wrapper {
    width: 100%;
    max-width: 430px;
}

.sudheera-login-card {
    background: #171717;
    border-radius: 18px;
    padding: 40px 35px;
    border: 1px solid #303030;
    box-shadow: 0 18px 50px rgba(0, 0, 0, 0.45);
}

/* LOGIN ICON */

.sudheera-login-icon {
    width: 75px;
    height: 75px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: #FF0000;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    box-shadow: 0 8px 25px rgba(255, 0, 0, 0.22);
}

/* HEADING */

.sudheera-login-heading {
    text-align: center;
    margin-bottom: 28px;
}

.sudheera-login-heading h2 {
    margin: 0 0 8px;
    font-family: "Instrument Serif", serif;
    font-size: 29px;
    font-weight: 600;
    color: #FFFFFF;
}

.sudheera-login-heading p {
    margin: 0;
    color: #B8B8B8;
    font-size: 14px;
    line-height: 1.6;
}

/* FORM */

.sudheera-form-group {
    margin-bottom: 20px;
}

.sudheera-form-group label,
.sudheera-otp-group label {
    display: block;
    margin-bottom: 8px;
    color: #FFFFFF;
    font-size: 13px;
    font-weight: 600;
}

/* MOBILE INPUT */

.sudheera-mobile-wrapper {
    display: flex;
    align-items: stretch;
    border: 1px solid #383838;
    border-radius: 8px;
    overflow: hidden;
    background: #0A0A0A;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.sudheera-mobile-wrapper:focus-within {
    border-color: #FF0000;
    box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.12);
}

.sudheera-country-code {
    display: flex;
    align-items: center;
    padding: 0 13px;
    background: #202020;
    border-right: 1px solid #383838;
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 600;
}

.sudheera-mobile-input {
    width: 100%;
    min-width: 0;
    border: 0;
    outline: 0;
    padding: 13px 14px;
    font-size: 14px;
    color: #FFFFFF;
    background: transparent;
}

.sudheera-mobile-input::placeholder {
    color: #888888;
}

.sudheera-mobile-input:read-only {
    background: #202020;
    color: #B8B8B8;
    cursor: not-allowed;
}

/* OTP */

.sudheera-otp-group {
    display: none;
    margin-bottom: 20px;
}

.sudheera-otp-input {
    box-sizing: border-box;
    width: 100%;
    border: 1px solid #383838;
    border-radius: 8px;
    padding: 13px 14px;
    outline: none;
    text-align: center;
    letter-spacing: 8px;
    font-size: 20px;
    font-weight: 600;
    color: #FFFFFF;
    background: #0A0A0A;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.sudheera-otp-input:focus {
    border-color: #FF0000;
    box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.12);
}

.sudheera-otp-input::placeholder {
    letter-spacing: 3px;
    color: #888888;
    font-size: 13px;
}

/* PRIMARY BUTTON */

.sudheera-login-btn {
    width: 100%;
    border: 1px solid #FF0000;
    border-radius: 8px;
    padding: 14px 20px;
    background: #FF0000;
    color: #FFFFFF;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    transition: background 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                transform 0.25s ease;
}

.sudheera-login-btn:hover {
    background: #CC0000;
    border-color: #CC0000;
    box-shadow: 0 6px 20px rgba(255, 0, 0, 0.20);
    transform: translateY(-1px);
}

.sudheera-login-btn:focus-visible {
    outline: 2px solid #FFC928;
    outline-offset: 3px;
}

.sudheera-login-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* RESEND OTP */

.sudheera-resend {
    display: none;
    text-align: center;
    margin-top: 15px;
    font-size: 13px;
    color: #B8B8B8;
}

.sudheera-resend a {
    color: #FFC928;
    font-weight: 600;
    text-decoration: none;
}

.sudheera-resend a:hover {
    color: #FFFFFF;
    text-decoration: underline;
}

/* CHANGE MOBILE NUMBER */

.sudheera-change-number {
    display: none;
    text-align: center;
    margin-top: 12px;
}

.sudheera-change-number a {
    color: #B8B8B8;
    font-size: 12px;
    text-decoration: none;
    transition: color 0.2s ease;
}

.sudheera-change-number a:hover {
    color: #FF0000;
}

/* SUCCESS AND ERROR MESSAGES */

.sudheera-login-message {
    display: none;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 8px;
    font-size: 13px;
    text-align: center;
    line-height: 1.5;
}

.sudheera-login-message.success {
    display: block;
    color: #86EFAC;
    background: #10251A;
    border: 1px solid #246B3D;
}

.sudheera-login-message.error {
    display: block;
    color: #FF9999;
    background: #2A1111;
    border: 1px solid #7F2525;
}

/* TERMS AND CONDITIONS */

.sudheera-login-terms {
    text-align: center;
    margin-top: 22px;
    font-size: 11px;
    color: #888888;
    line-height: 1.8;
}

.sudheera-login-terms a {
    color: #FFC928;
    text-decoration: none;
}

.sudheera-login-terms a:hover {
    color: #FFFFFF;
    text-decoration: underline;
}

/* MOBILE RESPONSIVENESS */

@media (max-width: 575px) {
    .sudheera-login-section {
        min-height: 75vh;
        padding: 35px 15px;
    }

    .sudheera-login-card {
        padding: 30px 20px;
        border-radius: 15px;
    }

    .sudheera-login-icon {
        width: 65px;
        height: 65px;
        font-size: 26px;
        margin-bottom: 15px;
    }

    .sudheera-login-heading {
        margin-bottom: 22px;
    }

    .sudheera-login-heading h2 {
        font-size: 24px;
    }

    .sudheera-login-heading p {
        font-size: 13px;
    }
}

</style>
<!-- =========================================================
     LOGIN SECTION
========================================================= -->

<section class="sudheera-login-section">

    <div class="sudheera-login-wrapper">

        <div class="sudheera-login-card">

            <!-- Icon -->

            <div class="sudheera-login-icon">
                <i class="fa fa-mobile"></i>
            </div>

            <!-- Heading -->

            <div class="sudheera-login-heading">

                <h2>
                    Welcome to BAKASURAN
                </h2>

                <p id="loginSubtitle">
                    Enter your mobile number to get started
                </p>

            </div>

            <!-- Message -->

            <div
                id="loginMessage"
                class="sudheera-login-message">
            </div>

            <!-- Login Form -->

            <form id="otpLoginForm">

                @csrf

                <!-- Mobile -->

                <div class="sudheera-form-group">

                    <label for="mobile">
                        Mobile Number
                    </label>

                    <div class="sudheera-mobile-wrapper">

                        <span class="sudheera-country-code">
                            +91
                        </span>

                        <input
                            type="tel"
                            id="mobile"
                            name="mobile"
                            class="sudheera-mobile-input"
                            placeholder="Enter your 10-digit mobile number"
                            maxlength="10"
                            inputmode="numeric"
                            autocomplete="tel"
                        >

                    </div>

                </div>

                <!-- OTP -->

                <div
                    class="sudheera-otp-group"
                    id="otpGroup"
                    style="display: none;">

                    <label for="otp">
                        Enter OTP
                    </label>

                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        class="sudheera-otp-input"
                        placeholder="Enter 4-digit OTP"
                        maxlength="4"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                    >

                </div>

                <!-- Button -->

                <button
                    type="submit"
                    class="sudheera-login-btn"
                    id="otpButton">

                    SEND OTP

                </button>

                <!-- Resend -->

                <div
                    class="sudheera-resend"
                    id="resendOtp"
                    style="display: none;">

                    Didn't receive the OTP?

                    <a
                        href="javascript:void(0)"
                        id="resendBtn">

                        Resend OTP

                    </a>

                </div>

                <!-- Change Number -->

                <div
                    class="sudheera-change-number"
                    id="changeNumber"
                    style="display: none;">

                    <a
                        href="javascript:void(0)"
                        id="changeNumberBtn">

                        ← Change mobile number

                    </a>

                </div>

            </form>

            <!-- Terms -->

            <div class="sudheera-login-terms">

                By continuing, you agree to our

                <a href="{{ url('/terms-of-service') }}">
                    Terms & Conditions
                </a>

                and

                <a href="{{ url('/privacy-policy') }}">
                    Privacy Policy
                </a>.

            </div>

        </div>

    </div>

</section>

<!-- =========================================================
     JQUERY
========================================================= -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    let otpSent = false;

    // Mobile - only numbers

    $('#mobile').on('input', function () {

        this.value = this.value
            .replace(/[^0-9]/g, '')
            .substring(0, 10);

    });

    // OTP - only numbers / 4 digits

    $('#otp').on('input', function () {

        this.value = this.value
            .replace(/[^0-9]/g, '')
            .substring(0, 4);

    });

    // Form submit

    $('#otpLoginForm').on('submit', function (e) {

        e.preventDefault();

        let mobile = $('#mobile').val().trim();

        // Send OTP

        if (!otpSent) {

            if (!/^[6-9][0-9]{9}$/.test(mobile)) {

                showMessage(
                    'Please enter a valid 10-digit Indian mobile number.',
                    'error'
                );

                return;
            }

            $('#otpButton')
                .prop('disabled', true)
                .text('SENDING OTP...');

            $.ajax({

                url: "{{ route('sendOtp') }}",

                type: "POST",

                data: {
                    _token: "{{ csrf_token() }}",
                    mobile: mobile
                },

                success: function (response) {

                    if (response.status) {

                        otpSent = true;

                        // Show OTP field

                        $('#otpGroup').slideDown();
                        $('#resendOtp').slideDown();
                        $('#changeNumber').slideDown();

                        // Update button

                        $('#otpButton')
                            .prop('disabled', false)
                            .text('VERIFY OTP');

                        // Update subtitle

                        $('#loginSubtitle').text(
                            'Enter the OTP sent to +91 ' + mobile
                        );

                        // Lock mobile number

                        $('#mobile').prop('readonly', true);

                        // Clear and focus OTP

                        $('#otp').val('').focus();

                        showMessage(
                            response.message ||
                            'OTP sent successfully. Please check your messages.',
                            'success'
                        );

                    } else {

                        $('#otpButton')
                            .prop('disabled', false)
                            .text('SEND OTP');

                        showMessage(
                            response.message ||
                            'Unable to send OTP. Please try again.',
                            'error'
                        );
                    }
                },

                error: function (xhr) {

                    $('#otpButton')
                        .prop('disabled', false)
                        .text('SEND OTP');

                    let message =
                        'Unable to send OTP. Please try again.';

                    if (xhr.responseJSON) {

                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON.errors) {

                            let errors = xhr.responseJSON.errors;
                            let firstError = Object.values(errors)[0];

                            if (firstError) {
                                message = firstError[0];
                            }
                        }
                    }

                    showMessage(message, 'error');
                }

            });

        }

        // Verify OTP

        else {

            let otp = $('#otp').val().trim();

            if (!/^[0-9]{4}$/.test(otp)) {

                showMessage(
                    'Please enter the 4-digit OTP.',
                    'error'
                );

                return;
            }

            $('#otpButton')
                .prop('disabled', true)
                .text('VERIFYING...');

            $.ajax({

                url: "{{ route('verifyOtp') }}",

                type: "POST",

                data: {
                    _token: "{{ csrf_token() }}",
                    mobile: mobile,
                    otp: otp
                },

                success: function (response) {

                    if (response.status) {

                        showMessage(
                            response.message ||
                            'Welcome to BAKASURAN! Redirecting...',
                            'success'
                        );

                        // Redirect after successful login

                        setTimeout(function () {

                            window.location.href =
                                response.redirect || '/';

                        }, 700);

                    } else {

                        $('#otpButton')
                            .prop('disabled', false)
                            .text('VERIFY OTP');

                        showMessage(
                            response.message ||
                            'Invalid OTP. Please try again.',
                            'error'
                        );
                    }

                },

                error: function (xhr) {

                    $('#otpButton')
                        .prop('disabled', false)
                        .text('VERIFY OTP');

                    let message =
                        'OTP verification failed. Please try again.';

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {
                        message = xhr.responseJSON.message;
                    }

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0];

                        if (firstError) {
                            message = firstError[0];
                        }
                    }

                    showMessage(message, 'error');
                }

            });

        }

    });

    // Resend OTP

    $('#resendBtn').on('click', function (e) {

        e.preventDefault();

        let mobile = $('#mobile').val().trim();

        if (!/^[6-9][0-9]{9}$/.test(mobile)) {

            showMessage(
                'Invalid mobile number. Please check your number.',
                'error'
            );

            return;
        }

        $('#resendBtn')
            .css('pointer-events', 'none')
            .text('Sending...');

        $.ajax({

            url: "{{ route('sendOtp') }}",

            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",
                mobile: mobile
            },

            success: function (response) {

                $('#resendBtn')
                    .css('pointer-events', 'auto')
                    .text('Resend OTP');

                if (response.status) {

                    $('#otp').val('').focus();

                    showMessage(
                        response.message ||
                        'A new OTP has been sent.',
                        'success'
                    );

                } else {

                    showMessage(
                        response.message ||
                        'Unable to resend OTP.',
                        'error'
                    );
                }

            },

            error: function (xhr) {

                $('#resendBtn')
                    .css('pointer-events', 'auto')
                    .text('Resend OTP');

                let message = 'Unable to resend OTP. Please try again.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {
                    message = xhr.responseJSON.message;
                }

                showMessage(message, 'error');
            }

        });

    });

    // Change mobile number

    $('#changeNumberBtn').on('click', function (e) {

        e.preventDefault();

        otpSent = false;

        // Enable mobile input

        $('#mobile')
            .prop('readonly', false)
            .val('')
            .focus();

        // Clear OTP

        $('#otp').val('');

        // Hide OTP and related controls

        $('#otpGroup').slideUp();
        $('#resendOtp').hide();
        $('#changeNumber').hide();

        // Reset button

        $('#otpButton')
            .prop('disabled', false)
            .text('SEND OTP');

        // Reset subtitle

        $('#loginSubtitle').text(
            'Enter your mobile number to get started'
        );

        // Clear messages

        $('#loginMessage')
            .hide()
            .removeClass('success error')
            .text('');

    });

    // Show message

    function showMessage(message, type) {

        $('#loginMessage')
            .removeClass('success error')
            .addClass(type)
            .text(message)
            .show();

    }

});
</script>


@endsection