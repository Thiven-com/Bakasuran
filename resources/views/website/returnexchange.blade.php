@extends('layouts.website')
@section('content')

<style>
    /* =========================================================
       SUDHEERA SAREES
       CANCELLATION & REFUND POLICY
    ========================================================= */

    .sudheera-refund-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ================= BREADCRUMB ================= */

    .sudheera-refund-breadcrumb {
        padding-bottom: 10px;
    }

    .sudheera-refund-breadcrumb .breadcrumb-content {
        padding-top: 50px;
    }

    .sudheera-refund-breadcrumb .breadcrumb-list {
        display: flex;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 0;
        margin-left: 20px;
    }

    .sudheera-refund-breadcrumb .breadcrumb-list li {
        font-size: 14px;
        color: #806b70;
    }

    .sudheera-refund-breadcrumb .breadcrumb-list a {
        color: #30000e;
        text-decoration: none;
        font-weight: 600;
        transition: .3s ease;
    }

    .sudheera-refund-breadcrumb .breadcrumb-list a:hover {
        color: #c88618;
    }

    .sudheera-refund-separator {
        margin: 0 10px;
        color: #c88618;
        font-weight: 700;
    }


    /* =========================================================
       MAIN POLICY CARD
    ========================================================= */

    .sudheera-refund-content {
        max-width: 1050px;
        margin: 0 auto;
        background: #fff;
        /* border: 1px solid #eee2d8; */
        /* border-radius: 30px; */
        padding: 48px 52px;
        /* box-shadow: 0 15px 45px rgba(48, 0, 14, .06); */
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .sudheera-refund-header {
        text-align: center;
        margin-bottom: 42px;
    }

    .sudheera-refund-label {
        display: inline-block;
        color: #c88618;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .sudheera-refund-title {
        margin: 0 0 14px;
        color: #30000e;
        font-size: 40px;
        line-height: 1.2;
        font-weight: 600;
    }

    .sudheera-refund-subtitle {
        max-width: 700px;
        margin: 0 auto;
        color: #76666a;
        font-size: 16px;
        line-height: 1.8;
    }

    .sudheera-refund-divider {
        width: 75px;
        height: 3px;
        margin: 20px auto 0;
        border-radius: 50px;
        background: linear-gradient(
            90deg,
            #30000e,
            #c88618
        );
    }


    /* =========================================================
       POLICY ITEM
    ========================================================= */

    .sudheera-refund-item {
        padding: 28px 0;
        border-bottom: 1px solid #eee5df;
    }

    .sudheera-refund-item:first-of-type {
        padding-top: 0;
    }

    .sudheera-refund-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }


    /* =========================================================
       SECTION TITLE
    ========================================================= */

    .sudheera-refund-section-title {
        display: flex;
        align-items: center;
        gap: 13px;
        color: #30000e;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .sudheera-refund-section-title::before {
        content: '';
        width: 5px;
        height: 25px;
        border-radius: 10px;
        background: linear-gradient(
            180deg,
            #30000e,
            #c88618
        );
        flex-shrink: 0;
    }


    /* =========================================================
       TEXT
    ========================================================= */

    .sudheera-refund-text {
        font-size: 15px;
        line-height: 1.85;
        color: #5e5255;
        margin: 0;
    }

    .sudheera-refund-text strong {
        color: #3b111c;
        font-weight: 700;
    }


    /* =========================================================
       LIST
    ========================================================= */

    .sudheera-refund-list {
        margin: 10px 0 0;
        padding-left: 25px;
    }

    .sudheera-refund-list li {
        font-size: 15px;
        line-height: 1.85;
        color: #5e5255;
        margin-bottom: 8px;
        padding-left: 5px;
    }

    .sudheera-refund-list li::marker {
        color: #c88618;
        font-size: 14px;
    }


    /* =========================================================
       IMPORTANT NOTE
    ========================================================= */

    .sudheera-refund-note {
        margin-top: 16px;
        padding: 18px 20px;
        background: linear-gradient(
            135deg,
            #fffaf4,
            #fbf1e7
        );
        border-left: 4px solid #c88618;
        border-radius: 12px;
    }

    .sudheera-refund-note p {
        margin: 0;
        font-size: 14px;
        line-height: 1.75;
        color: #5c4b4f;
    }

    .sudheera-refund-note strong {
        color: #30000e;
    }


    /* =========================================================
       CONTACT BOX
    ========================================================= */

    .sudheera-refund-contact {
        margin-top: 34px;
        padding: 28px;
        border-radius: 22px;
        background: linear-gradient(
            135deg,
            #30000e 0%,
            #65001b 45%,
            #8a4023 100%
        );
        position: relative;
        overflow: hidden;
    }

    .sudheera-refund-contact::before {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .05);
        right: -90px;
        top: -100px;
    }

    .sudheera-refund-contact::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(200, 134, 24, .08);
        left: -50px;
        bottom: -50px;
    }

    .sudheera-refund-contact-content {
        position: relative;
        z-index: 2;
    }

    .sudheera-refund-contact h5 {
        margin: 0 0 10px;
        color: #e5c27b;
        font-size: 19px;
        font-weight: 700;
    }

    .sudheera-refund-contact p {
        margin: 0;
        color: rgba(255, 255, 255, .88);
        font-size: 14px;
        line-height: 1.85;
    }

    .sudheera-refund-contact a {
        color: #e5c27b;
        font-weight: 600;
        text-decoration: none;
    }

    .sudheera-refund-contact a:hover {
        color: #fff;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 767px) {

        .sudheera-refund-breadcrumb .breadcrumb-content {
            padding-top: 30px;
        }

        .sudheera-refund-breadcrumb .breadcrumb-list li {
            font-size: 13px;
        }

        .sudheera-refund-separator {
            margin: 0 7px;
        }

        .sudheera-refund-content {
            padding: 30px 20px;
            border-radius: 22px;
        }

        .sudheera-refund-header {
            margin-bottom: 30px;
        }

        .sudheera-refund-label {
            font-size: 11px;
            letter-spacing: 2px;
        }

        .sudheera-refund-title {
            font-size: 29px;
        }

        .sudheera-refund-subtitle {
            font-size: 14px;
            line-height: 1.75;
        }

        .sudheera-refund-item {
            padding: 23px 0;
        }

        .sudheera-refund-section-title {
            font-size: 17px;
            gap: 10px;
        }

        .sudheera-refund-section-title::before {
            width: 4px;
            height: 21px;
        }

        .sudheera-refund-text,
        .sudheera-refund-list li {
            font-size: 14px;
            line-height: 1.8;
        }

        .sudheera-refund-list {
            padding-left: 21px;
        }

        .sudheera-refund-note {
            padding: 15px 16px;
        }

        .sudheera-refund-contact {
            padding: 21px;
            border-radius: 18px;
        }
    }
</style>


<!-- =========================================================
     BREADCRUMB
========================================================= -->


<section class="page-breadcrumb sudheera-refund-breadcrumb">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <span class="sudheera-refund-separator">/</span>
                </li>
                <li>Cancellation & Refund Policy</li>
            </ul>
        </div>
    </div>
</section>

{{-- =========================================================
     CANCELLATION & REFUND POLICY
========================================================= --}}

<div class="section-term-user flat-spacing">
    <div class="container">
        <div class="sudheera-refund-page">
            <div class="sudheera-refund-content">

                {{-- HEADER --}}
                <div class="sudheera-refund-header">
                    <span class="sudheera-refund-label">
                        BAKASURAN FOOD
                    </span>

                    <h1 class="sudheera-refund-title font-instrument_serif">
                        Cancellation & Refund Policy
                    </h1>

                    <p class="sudheera-refund-subtitle">
                        At BAKASURAN, we want your food ordering
                        experience to be convenient and transparent.
                        This policy explains how order cancellations,
                        refund requests, payment issues, and food-related
                        complaints are handled.
                    </p>

                    <div class="sudheera-refund-divider"></div>
                </div>

                {{-- COMMUNICATION & REFUNDS --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Communication & Refunds
                    </h5>

                    <ul class="sudheera-refund-list">
                        <li>
                            We will communicate relevant order
                            cancellation or refund updates through
                            the contact details or notification
                            channels available on our platform.
                        </li>

                        <li>
                            Refund eligibility depends on the reason
                            for the request, order status, payment
                            status, and applicable policy.
                        </li>

                        <li>
                            Where a refund is approved, the processing
                            time depends on the payment method,
                            payment provider, and banking institution.
                        </li>

                        <li>
                            The time taken for the amount to appear
                            in your account may differ from the date
                            on which the refund is initiated.
                        </li>

                        <li>
                            If an approved refund has not appeared
                            within the communicated timeframe,
                            contact customer support with your
                            order number and payment reference.
                        </li>

                        <li>
                            Missing, incorrect, damaged, or potentially
                            unsafe food should be reported promptly
                            so the issue can be reviewed.
                        </li>
                    </ul>
                </div>

                {{-- CANCELLATION OF UNPROCESSED ORDERS --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Cancellation of Unprocessed Orders
                    </h5>

                    <p class="sudheera-refund-text">
                        If you need to cancel an order, contact
                        customer support or use the cancellation
                        option available on the platform, where
                        supported, as soon as possible.

                        <br><br>

                        Cancellation may be possible before the
                        restaurant accepts the order or begins
                        preparing the food. The available options
                        depend on the current order status.

                        <br><br>

                        If the cancellation is approved and payment
                        has already been collected, any eligible
                        refund will be initiated according to the
                        applicable refund policy.
                    </p>
                </div>

                {{-- PAYMENT INFORMATION --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Payment Information
                    </h5>

                    <div class="sudheera-refund-note">
                        <p>
                            <strong>Important:</strong>
                            Please check the payment options displayed
                            at checkout before placing your order.
                            Available payment methods, including
                            online payments or Cash on Delivery,
                            depend on the options enabled for your
                            BAKASURAN account and delivery location.
                            An order should not be treated as
                            successfully paid unless the payment
                            status confirms it.
                        </p>
                    </div>
                </div>

                {{-- PROCESSED ORDERS --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Cancellation of Processed Orders
                    </h5>

                    <p class="sudheera-refund-text">
                        Once a restaurant has accepted an order
                        and started preparing the food, cancellation
                        may no longer be available because food
                        preparation has already begun.

                        <br><br>

                        If your order has been prepared or dispatched,
                        contact customer support to explain your
                        situation. Any available resolution will
                        depend on the circumstances, order status,
                        and applicable policy.

                        <br><br>

                        Please do not assume that a cancellation
                        or refund has been approved until you
                        receive confirmation.
                    </p>
                </div>

                {{-- FOOD QUALITY & ORDER VARIATIONS --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Food Quality & Order Issues
                    </h5>

                    <ul class="sudheera-refund-list">
                        <li>
                            Food appearance, presentation, and
                            garnishing may vary slightly between
                            orders and restaurants.
                        </li>

                        <li>
                            Ingredients and preparation methods
                            may vary by dish. Review the available
                            menu description and ingredient
                            information before ordering.
                        </li>

                        <li>
                            If you receive a different dish from
                            the one ordered, report the issue
                            to customer support promptly.
                        </li>

                        <li>
                            Missing items, incorrect quantities,
                            damaged packaging, and other order
                            discrepancies may be reviewed for
                            an appropriate resolution.
                        </li>

                        <li>
                            If you have an allergy or dietary
                            requirement, review the available
                            information and contact the restaurant
                            where appropriate before ordering.
                        </li>
                    </ul>
                </div>

                {{-- REFUNDS FOR FOOD ORDERS --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Refund Eligibility
                    </h5>

                    <p class="sudheera-refund-text">
                        Refund requests are reviewed based on
                        the specific issue and the applicable
                        order terms. Circumstances that may
                        be considered include:
                    </p>

                    <ul class="sudheera-refund-list">
                        <li>
                            An order is cancelled by the platform
                            or restaurant after payment has been
                            collected.
                        </li>

                        <li>
                            A payment is successfully collected
                            but the order is not confirmed.
                        </li>

                        <li>
                            The delivered order contains missing
                            or incorrect items.
                        </li>

                        <li>
                            The order arrives with a reported
                            quality or packaging issue.
                        </li>

                        <li>
                            A duplicate payment or other verified
                            payment issue occurs.
                        </li>
                    </ul>

                    <div class="sudheera-refund-note">
                        <p>
                            <strong>Please Note:</strong>
                            The examples above do not guarantee
                            an automatic refund. Each request
                            is subject to verification and the
                            applicable policy. Any rights or
                            remedies required by applicable law
                            remain unaffected.
                        </p>
                    </div>
                </div>

                {{-- CANCELLATION BY BAKASURAN OR RESTAURANT --}}
                <div class="sudheera-refund-item">
                    <h5 class="sudheera-refund-section-title">
                        Order Cancellation by BAKASURAN or Restaurant
                    </h5>

                    <p class="sudheera-refund-text">
                        An order may need to be cancelled in
                        circumstances including:
                    </p>

                    <ul class="sudheera-refund-list">
                        <li>
                            The selected food items are unavailable.
                        </li>

                        <li>
                            The restaurant is unable to accept
                            or fulfil the order.
                        </li>

                        <li>
                            A payment or order verification issue
                            prevents the order from proceeding.
                        </li>

                        <li>
                            The delivery location is outside the
                            available service area.
                        </li>

                        <li>
                            Delivery or fulfilment becomes impossible
                            because of operational or unforeseen
                            circumstances.
                        </li>

                        <li>
                            A suspected fraudulent transaction
                            or other legitimate issue requires
                            the order to be reviewed.
                        </li>
                    </ul>

                    <div class="sudheera-refund-note">
                        <p>
                            <strong>Refund:</strong>
                            If an order is cancelled after payment
                            has been collected, any eligible refund
                            will be initiated using the original
                            payment method or another appropriate
                            method where required.
                        </p>
                    </div>
                </div>

                {{-- NEED HELP --}}
                <div class="sudheera-refund-contact">
                    <div class="sudheera-refund-contact-content">
                        <h5>
                            Need Help With a Cancellation or Refund?
                        </h5>

                        <p>
                            For assistance with cancellations,
                            refunds, failed payments, missing items,
                            or food order issues, contact the
                            BAKASURAN customer support team through
                            the official contact details available
                            on our platform.

                            <br><br>

                            <strong>Order Details:</strong>
                            Please keep your order number, payment
                            reference, and a description of the
                            issue ready when contacting support.

                            <br><br>

                            <strong>Website:</strong>
                            <a href="{{ url('/') }}">
                                Visit BAKASURAN
                            </a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



@endsection