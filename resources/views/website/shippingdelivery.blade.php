@extends('layouts.website')
@section('content')

<style>
    /* =========================================================
       SUDHEERA SAREES - SHIPPING & DELIVERY PAGE
    ========================================================= */

    .sudheera-policy-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ================= BREADCRUMB ================= */

    .sudheera-policy-breadcrumb {
        padding-bottom: 10px;
    }

    .sudheera-policy-breadcrumb .breadcrumb-content {
        padding-top: 50px;
    }

    .sudheera-policy-breadcrumb .breadcrumb-list {
        display: flex;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 0;
        margin-left: 20px;
    }

    .sudheera-policy-breadcrumb .breadcrumb-list li {
        font-size: 14px;
        color: #806b70;
    }

    .sudheera-policy-breadcrumb .breadcrumb-list a {
        color: #30000e;
        text-decoration: none;
        font-weight: 600;
        transition: .3s ease;
    }

    .sudheera-policy-breadcrumb .breadcrumb-list a:hover {
        color: #c88618;
    }

    .sudheera-policy-breadcrumb .breadcrumb-separator {
        margin: 0 10px;
        color: #c88618;
        font-weight: 700;
    }

    /* ================= MAIN CONTENT ================= */

    .sudheera-policy-content {
        max-width: 1050px;
        margin: 0 auto;
        background: #fff;
        /* border: 1px solid #eee2d8; */
        /* border-radius: 30px; */
        padding: 48px 52px;
        box-shadow: 0 15px 45px rgba(48, 0, 14, .06);
    }

    /* ================= HEADER ================= */

    .sudheera-policy-header {
        text-align: center;
        margin-bottom: 42px;
        position: relative;
    }

    .sudheera-policy-label {
        display: inline-block;
        font-size: 12px;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-weight: 700;
        color: #c88618;
        margin-bottom: 12px;
    }

    .sudheera-policy-title {
        font-size: 40px;
        line-height: 1.2;
        color: #30000e;
        margin: 0 0 15px;
        font-weight: 600;
    }

    .sudheera-policy-intro {
        max-width: 700px;
        margin: 0 auto;
        color: #76666a;
        font-size: 16px;
        line-height: 1.8;
    }

    .sudheera-policy-divider {
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

    /* ================= POLICY ITEM ================= */

    .sudheera-policy-item {
        padding: 28px 0;
        border-bottom: 1px solid #eee5df;
    }

    .sudheera-policy-item:first-of-type {
        padding-top: 0;
    }

    .sudheera-policy-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    /* ================= SECTION TITLE ================= */

    .sudheera-policy-section-title {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 14px;
        color: #30000e;
        font-size: 19px;
        font-weight: 700;
    }

    .sudheera-policy-section-title::before {
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

    /* ================= TEXT ================= */

    .sudheera-policy-text {
        font-size: 15px;
        line-height: 1.85;
        color: #5e5255;
        margin: 0;
    }

    .sudheera-policy-text strong {
        color: #3b111c;
    }

    /* ================= LIST ================= */

    .sudheera-policy-list {
        margin: 10px 0 0;
        padding-left: 25px;
    }

    .sudheera-policy-list li {
        font-size: 15px;
        line-height: 1.85;
        color: #5e5255;
        margin-bottom: 8px;
        padding-left: 5px;
    }

    .sudheera-policy-list li::marker {
        color: #c88618;
        font-size: 14px;
    }

    /* ================= HIGHLIGHT BOX ================= */

    .sudheera-policy-highlight {
        margin-top: 15px;
        padding: 18px 20px;
        background: linear-gradient(
            135deg,
            #fffaf4,
            #fbf1e7
        );
        border-left: 4px solid #c88618;
        border-radius: 12px;
    }

    .sudheera-policy-highlight p {
        margin: 0;
        font-size: 14px;
        line-height: 1.7;
        color: #5c4b4f;
    }

    .sudheera-policy-highlight strong {
        color: #30000e;
    }

    /* ================= CONTACT BOX ================= */

    .sudheera-policy-contact {
        margin-top: 32px;
        padding: 24px;
        border-radius: 20px;
        background: linear-gradient(
            135deg,
            #30000e,
            #65001b,
            #8a4023
        );
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .sudheera-policy-contact::after {
        content: '';
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        right: -60px;
        top: -70px;
    }

    .sudheera-policy-contact-content {
        position: relative;
        z-index: 2;
    }

    .sudheera-policy-contact h5 {
        color: #e5c27b;
        font-size: 18px;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .sudheera-policy-contact p {
        margin: 0;
        color: rgba(255,255,255,.88);
        font-size: 14px;
        line-height: 1.8;
    }

    .sudheera-policy-contact a {
        color: #e5c27b;
        font-weight: 600;
        text-decoration: none;
    }

    .sudheera-policy-contact a:hover {
        color: #fff;
    }

    /* ================= MOBILE ================= */

    @media (max-width: 767px) {

        .sudheera-policy-breadcrumb .breadcrumb-content {
            padding-top: 30px;
        }

        .sudheera-policy-breadcrumb .breadcrumb-list li {
            font-size: 13px;
        }

        .sudheera-policy-breadcrumb .breadcrumb-separator {
            margin: 0 7px;
        }

        .sudheera-policy-content {
            padding: 30px 20px;
            border-radius: 22px;
        }

        .sudheera-policy-header {
            margin-bottom: 30px;
        }

        .sudheera-policy-label {
            font-size: 11px;
            letter-spacing: 2px;
        }

        .sudheera-policy-title {
            font-size: 29px;
        }

        .sudheera-policy-intro {
            font-size: 14px;
            line-height: 1.75;
        }

        .sudheera-policy-item {
            padding: 23px 0;
        }

        .sudheera-policy-section-title {
            font-size: 17px;
            gap: 10px;
        }

        .sudheera-policy-section-title::before {
            width: 4px;
            height: 21px;
        }

        .sudheera-policy-text,
        .sudheera-policy-list li {
            font-size: 14px;
            line-height: 1.8;
        }

        .sudheera-policy-list {
            padding-left: 21px;
        }

        .sudheera-policy-highlight {
            padding: 15px 16px;
        }

        .sudheera-policy-contact {
            padding: 20px;
            border-radius: 17px;
        }
    }
</style>



{{-- =========================================================
     BREADCRUMB
========================================================= --}}

<section class="page-breadcrumb sudheera-policy-breadcrumb">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li>
                    <a href="/">Home</a>
                </li>

                <li>
                    <span class="breadcrumb-separator">/</span>
                </li>

                <li>
                    Shipping & Delivery Policy
                </li>
            </ul>
        </div>
    </div>
</section>


{{-- =========================================================
     SHIPPING & DELIVERY POLICY
========================================================= --}}

<div class="section-term-user flat-spacing">
    <div class="container">
        <div class="sudheera-policy-container">
            <div class="sudheera-policy-content">

                {{-- HEADER --}}
                <div class="sudheera-policy-header">

                    <span class="sudheera-policy-label">
                        BAKASURAN FOOD
                    </span>

                    <h1 class="sudheera-policy-title font-instrument_serif">
                        Shipping & Delivery Policy
                    </h1>

                    <p class="sudheera-policy-intro">
                        At BAKASURAN, we aim to bring your favourite
                        meals to your doorstep safely and conveniently.
                        This policy explains how food orders are
                        processed, dispatched, delivered, and handled
                        when delivery issues arise.
                    </p>

                    <div class="sudheera-policy-divider"></div>

                </div>


                {{-- ORDER PROCESSING --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Order Processing
                    </h5>

                    <p class="sudheera-policy-text">
                        Once your order is successfully placed,
                        the restaurant receives the order details
                        and begins preparing your selected food items.
                    </p>

                    <ul class="sudheera-policy-list">

                        <li>
                            Orders are sent for preparation after
                            successful order confirmation, subject
                            to restaurant availability.
                        </li>

                        <li>
                            Food preparation time depends on the
                            restaurant, selected dishes, order size,
                            and current demand.
                        </li>

                        <li>
                            During peak hours, weekends, holidays,
                            and promotional periods, preparation
                            may take longer than expected.
                        </li>

                        <li>
                            Please review your order details,
                            delivery address, and contact information
                            carefully before confirming your order.
                        </li>

                        <li>
                            Restaurant acceptance and order status
                            may be subject to availability and
                            operational conditions.
                        </li>

                    </ul>

                </div>


                {{-- DELIVERY WITHIN INDIA --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Food Delivery Within Service Areas
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            Delivery is available only in locations
                            where BAKASURAN and its participating
                            restaurant or delivery partners provide
                            service.
                        </li>

                        <li>
                            Estimated delivery times are displayed
                            where available and may vary depending
                            on distance, food preparation time,
                            traffic, weather, and order volume.
                        </li>

                        <li>
                            Delivery charges, where applicable,
                            will be displayed during checkout
                            before the order is confirmed.
                        </li>

                        <li>
                            Customers should provide a complete
                            delivery address, a reachable phone
                            number, and any useful delivery
                            instructions.
                        </li>

                    </ul>

                    <div class="sudheera-policy-highlight">

                        <p>
                            <strong>Please Note:</strong>
                            Delivery estimates are not guaranteed
                            arrival times. Delays may occur because
                            of restaurant preparation, traffic,
                            severe weather, operational disruptions,
                            or other circumstances beyond our control.
                        </p>

                    </div>

                </div>


                {{-- FOOD DELIVERY HANDLING --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Food Packaging & Delivery Handling
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            Participating restaurants are responsible
                            for preparing and packaging food items
                            according to their applicable procedures.
                        </li>

                        <li>
                            Food is handed over for delivery using
                            the applicable restaurant or delivery
                            partner process.
                        </li>

                        <li>
                            Customers should check the delivered
                            order for missing, incorrect, or visibly
                            damaged items as soon as reasonably possible.
                        </li>

                        <li>
                            Food quality and temperature at arrival
                            may be affected by distance, weather,
                            traffic, and delivery conditions.
                        </li>

                        <li>
                            Follow any storage, refrigeration,
                            reheating, or consumption instructions
                            provided with your food.
                        </li>

                    </ul>

                </div>


                {{-- DELIVERY CHARGES --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Delivery Charges & Additional Fees
                    </h5>

                    <p class="sudheera-policy-text">
                        Applicable delivery charges and other
                        order-related fees, if any, are shown
                        during checkout.
                    </p>

                    <ul class="sudheera-policy-list">

                        <li>
                            Delivery charges may vary according
                            to the delivery location, order value,
                            distance, and applicable platform rules.
                        </li>

                        <li>
                            Any applicable taxes or additional
                            charges will be presented as part of
                            the order total where required.
                        </li>

                        <li>
                            Promotional discounts, free-delivery
                            offers, and coupon codes are subject
                            to their individual terms and conditions.
                        </li>

                        <li>
                            Please review the final payable amount
                            before placing your order.
                        </li>

                    </ul>

                </div>


                {{-- ORDER TRACKING --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Order Tracking & Delivery Updates
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            Where supported, customers can check
                            the status of their orders through
                            the BAKASURAN platform.
                        </li>

                        <li>
                            Order status updates may include
                            confirmation, food preparation,
                            dispatch, and delivery.
                        </li>

                        <li>
                            Live tracking and delivery-partner
                            details are available only where
                            the relevant feature is supported.
                        </li>

                        <li>
                            If an expected delivery update is
                            delayed or unavailable, please use
                            the available customer support channel.
                        </li>

                    </ul>

                </div>


                {{-- ORDER CHANGES & CANCELLATION --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Order Changes & Cancellation
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            Please verify food items, quantities,
                            customisation requests, delivery address,
                            and payment details before placing
                            your order.
                        </li>

                        <li>
                            Changes to an order may not be possible
                            after the restaurant has started preparing
                            the food.
                        </li>

                        <li>
                            Cancellation eligibility depends on
                            the order status and the applicable
                            cancellation policy.
                        </li>

                        <li>
                            Once food preparation or dispatch has
                            started, cancellation may be restricted
                            or may not be available.
                        </li>

                        <li>
                            If you need to cancel or correct an order,
                            contact customer support as soon as
                            possible. A request does not guarantee
                            that the order can be changed or cancelled.
                        </li>

                    </ul>

                </div>


                {{-- FAILED DELIVERY --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Failed Delivery & Unavailable Customers
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            Customers should ensure that the
                            delivery address is accurate and
                            that the registered phone number
                            is reachable.
                        </li>

                        <li>
                            If the customer is unavailable at
                            the delivery location, the delivery
                            partner may attempt to contact the
                            customer using the provided details.
                        </li>

                        <li>
                            Incorrect addresses, inaccessible
                            locations, or an unavailable recipient
                            may prevent successful delivery.
                        </li>

                        <li>
                            If delivery cannot be completed,
                            contact customer support for assistance
                            with the available resolution options.
                        </li>

                        <li>
                            Any refund or redelivery decision
                            will be handled according to the
                            applicable order and refund policies.
                        </li>

                    </ul>

                </div>


                {{-- DELIVERY ISSUES --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Missing, Incorrect or Damaged Orders
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            If your order contains missing items,
                            incorrect dishes, or packaging damage,
                            report the issue through the available
                            customer support channel as soon as
                            reasonably possible.
                        </li>

                        <li>
                            Provide your order number and a clear
                            description of the issue.
                        </li>

                        <li>
                            Photographs of the food or packaging
                            may be requested to help investigate
                            your complaint.
                        </li>

                        <li>
                            Keep the order details and relevant
                            packaging until the complaint has
                            been reviewed, where practical.
                        </li>

                        <li>
                            Eligible complaints will be reviewed
                            according to the applicable customer
                            support and refund policies.
                        </li>

                    </ul>

                    <div class="sudheera-policy-highlight">

                        <p>
                            <strong>Food Safety Concern:</strong>
                            Do not consume food that appears
                            spoiled, contaminated, or otherwise
                            unsafe. Contact customer support
                            promptly and provide details about
                            the concern.
                        </p>

                    </div>

                </div>


                {{-- WEATHER & UNEXPECTED DELAYS --}}
                <div class="sudheera-policy-item">

                    <h5 class="sudheera-policy-section-title">
                        Delays & Unforeseen Circumstances
                    </h5>

                    <ul class="sudheera-policy-list">

                        <li>
                            Delivery may be delayed by heavy
                            traffic, severe weather, public events,
                            local restrictions, or other unexpected
                            circumstances.
                        </li>

                        <li>
                            Restaurant demand, ingredient availability,
                            and delivery-partner availability may
                            also affect delivery times.
                        </li>

                        <li>
                            Where possible, customers may receive
                            updates when an order is delayed.
                        </li>

                        <li>
                            If a delay significantly affects your
                            order, contact support for assistance
                            under the applicable order policy.
                        </li>

                    </ul>

                </div>


                {{-- CONTACT --}}
                <div class="sudheera-policy-contact">

                    <div class="sudheera-policy-contact-content">

                        <h5>
                            Need Help With Your Food Order?
                        </h5>

                        <p>
                            Our team is here to help with order
                            status, delivery delays, missing items,
                            incorrect orders, and other delivery
                            concerns.

                            <br><br>

                            Please use the official customer
                            support contact details available
                            on the BAKASURAN platform and
                            include your order number when
                            requesting assistance.

                            <br><br>

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