
@extends('layouts.website')

@section('content')

<style>
    /* =========================================================
       SUDHEERA SAREES - TERMS OF SERVICE
    ========================================================= */

    .sudheera-terms-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ================= BREADCRUMB ================= */

    .sudheera-terms-breadcrumb {
        padding-bottom: 10px;
    }

    .sudheera-terms-breadcrumb .breadcrumb-content {
        padding-top: 50px;
    }

    .sudheera-terms-breadcrumb .breadcrumb-list {
        display: flex;
        align-items: center;
        gap: 0;
        list-style: none !important;
        padding: 0;
        margin: 0;
        margin-left: 20px;
    }

    .sudheera-terms-breadcrumb .breadcrumb-list li {
        list-style: none !important;
        font-size: 14px;
        color: #7a686b;
    }

    .sudheera-terms-breadcrumb .breadcrumb-list a {
        color: #30000e;
        text-decoration: none;
        font-weight: 600;
        transition: .3s ease;
    }

    .sudheera-terms-breadcrumb .breadcrumb-list a:hover {
        color: #c88618;
    }

    .sudheera-terms-separator {
        margin: 0 10px;
        color: #c88618;
        font-weight: 700;
    }


    /* ================= MAIN CARD ================= */

    .sudheera-terms-content {
        max-width: 1050px;
        margin: 0 auto;
        background: #fff;
        /* border: 1px solid #eee2d8; */
        /* border-radius: 30px; */
        padding: 48px 52px;
        /* box-shadow: 0 15px 45px rgba(48, 0, 14, .06); */
    }


    /* ================= HEADER ================= */

    .sudheera-terms-header {
        text-align: center;
        margin-bottom: 42px;
    }

    .sudheera-terms-label {
        display: inline-block;
        margin-bottom: 12px;
        color: #c88618;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .sudheera-terms-title {
        margin: 0 0 14px;
        color: #30000e;
        font-size: 40px;
        line-height: 1.2;
        font-weight: 600;
    }

    .sudheera-terms-intro {
        max-width: 760px;
        margin: 0 auto;
        color: #76666a;
        font-size: 16px;
        line-height: 1.8;
    }

    .sudheera-terms-divider {
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


    /* ================= TERM ITEMS ================= */

    .sudheera-term-item {
        padding: 28px 0;
        border-bottom: 1px solid #eee5df;
    }

    .sudheera-term-item:first-of-type {
        padding-top: 0;
    }

    .sudheera-term-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }


    /* ================= SECTION TITLE ================= */

    .sudheera-term-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 14px;
        color: #30000e;
        font-size: 19px;
        font-weight: 700;
    }

    .sudheera-term-heading::before {
        content: '';
        width: 5px;
        height: 25px;
        flex-shrink: 0;
        border-radius: 10px;
        background: linear-gradient(
            180deg,
            #30000e,
            #c88618
        );
    }


    /* ================= TEXT ================= */

    .sudheera-term-text {
        margin: 0;
        color: #5e5255;
        font-size: 15px;
        line-height: 1.85;
    }

    .sudheera-term-text strong {
        color: #3b111c;
        font-weight: 700;
    }


    /* ================= LIST ================= */

    .sudheera-term-list {
        margin: 10px 0 0;
        padding-left: 25px;
    }

    .sudheera-term-list li {
        margin-bottom: 8px;
        padding-left: 5px;
        color: #5e5255;
        font-size: 15px;
        line-height: 1.85;
    }

    .sudheera-term-list li::marker {
        color: #c88618;
        font-size: 14px;
    }


    /* ================= HIGHLIGHT BOX ================= */

    .sudheera-terms-highlight {
        margin-top: 18px;
        padding: 20px 22px;
        background: linear-gradient(
            135deg,
            #fffaf4,
            #fbf1e7
        );
        border-left: 4px solid #c88618;
        border-radius: 13px;
    }

    .sudheera-terms-highlight p {
        margin: 0;
        color: #5c4b4f;
        font-size: 14px;
        line-height: 1.8;
    }


    /* ================= ADDRESS BOX ================= */

    .sudheera-address-box {
        margin-top: 18px;
        padding: 22px 24px;
        border-radius: 18px;
        background: #fffaf5;
        border: 1px solid #eee0d2;
    }

    .sudheera-address-box p {
        margin: 0;
        color: #5e5255;
        font-size: 15px;
        line-height: 1.85;
    }

    .sudheera-address-box strong {
        color: #30000e;
    }


    /* ================= MOBILE ================= */

    @media (max-width: 767px) {

        .sudheera-terms-breadcrumb .breadcrumb-content {
            padding-top: 30px;
        }

        .sudheera-terms-breadcrumb .breadcrumb-list li {
            font-size: 13px;
        }

        .sudheera-terms-separator {
            margin: 0 7px;
        }

        .sudheera-terms-content {
            padding: 30px 20px;
            border-radius: 22px;
        }

        .sudheera-terms-header {
            margin-bottom: 30px;
        }

        .sudheera-terms-label {
            font-size: 11px;
            letter-spacing: 2px;
        }

        .sudheera-terms-title {
            font-size: 29px;
        }

        .sudheera-terms-intro {
            font-size: 14px;
            line-height: 1.75;
        }

        .sudheera-term-item {
            padding: 23px 0;
        }

        .sudheera-term-heading {
            font-size: 17px;
            gap: 10px;
        }

        .sudheera-term-heading::before {
            width: 4px;
            height: 21px;
        }

        .sudheera-term-text,
        .sudheera-term-list li {
            font-size: 14px;
            line-height: 1.8;
        }

        .sudheera-term-list {
            padding-left: 21px;
        }

        .sudheera-terms-highlight {
            padding: 15px 16px;
        }

        .sudheera-address-box {
            padding: 18px;
        }
    }
</style>


<!-- =========================================================
     BREADCRUMB
========================================================= -->


<section class="page-breadcrumb sudheera-terms-breadcrumb">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <span class="sudheera-terms-separator">/</span>
                </li>
                <li>Terms of Service</li>
            </ul>
        </div>
    </div>
</section>

{{-- =========================================================
     TERMS OF SERVICE
========================================================= --}}

<div class="section-term-user flat-spacing">
    <div class="container">
        <div class="sudheera-terms-page">
            <div class="sudheera-terms-content">

                {{-- HEADER --}}
                <div class="sudheera-terms-header">
                    <span class="sudheera-terms-label">
                        BAKASURAN FOOD
                    </span>

                    <h1 class="sudheera-terms-title font-instrument_serif">
                        Terms of Service
                    </h1>

                    <p class="sudheera-terms-intro">
                        Welcome to BAKASURAN. These Terms of Service
                        explain the conditions for accessing and
                        using our food ordering platform. By using
                        our website or placing an order, you agree
                        to these terms and the applicable policies
                        published on our platform.
                    </p>

                    <div class="sudheera-terms-divider"></div>
                </div>

                {{-- ORDERS --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Orders
                    </h5>

                    <ul class="sudheera-term-list">
                        <li>
                            All food orders are subject to restaurant
                            acceptance, menu availability, and
                            delivery service availability.
                        </li>

                        <li>
                            Please check the selected food items,
                            quantities, customisation requests,
                            delivery address, and final order total
                            before confirming your order.
                        </li>

                        <li>
                            An order confirmation does not guarantee
                            that every item will remain available
                            if circumstances change.
                        </li>

                        <li>
                            BAKASURAN or the participating restaurant
                            may cancel an order when fulfilment is
                            not possible or when a legitimate
                            operational, payment, or security issue
                            arises.
                        </li>

                        <li>
                            Eligible refunds for cancelled and
                            prepaid orders will be handled according
                            to the Cancellation & Refund Policy.
                        </li>
                    </ul>
                </div>

                {{-- FOOD ITEMS --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Food Items & Menu Information
                    </h5>

                    <ul class="sudheera-term-list">
                        <li>
                            Menu images are intended to illustrate
                            food items. Actual presentation,
                            garnishing, and portion appearance
                            may vary.
                        </li>

                        <li>
                            Ingredients, preparation methods,
                            portion sizes, and availability may
                            differ between restaurants and dishes.
                        </li>

                        <li>
                            Menu prices and applicable charges
                            are displayed on the platform and
                            should be reviewed before checkout.
                        </li>

                        <li>
                            Customers should review available
                            ingredient and allergen information
                            before ordering. If you have a food
                            allergy or dietary requirement,
                            contact the restaurant where
                            appropriate before placing an order.
                        </li>

                        <li>
                            Customers should not assume that
                            a dish is free from a particular
                            allergen unless this has been
                            appropriately confirmed.
                        </li>
                    </ul>
                </div>

                {{-- ACCOUNT --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Customer Account
                    </h5>

                    <p class="sudheera-term-text">
                        You are responsible for providing accurate
                        information when creating and using your
                        account. Please keep your contact details
                        and delivery address up to date.

                        <br><br>

                        You should take reasonable steps to
                        protect your account credentials and
                        notify us if you suspect unauthorised
                        access or activity.

                        <br><br>

                        You must not use the platform to provide
                        false information, misuse promotions,
                        interfere with platform operations, or
                        engage in fraudulent or unlawful activity.
                    </p>
                </div>

                {{-- DELIVERY --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Delivery & Order Fulfilment
                    </h5>

                    <p class="sudheera-term-text">
                        Delivery availability and estimated
                        arrival times depend on the delivery
                        address, restaurant preparation time,
                        delivery-partner availability, traffic,
                        weather, and other operational conditions.

                        <br><br>

                        Customers are responsible for entering
                        a correct delivery address and providing
                        a reachable contact number. Delivery
                        estimates are not guaranteed arrival times.

                        <br><br>

                        For more information about service areas,
                        delivery charges, delays, and delivery
                        issues, please refer to our Shipping &
                        Delivery Policy.
                    </p>

                    <div class="sudheera-terms-highlight">
                        <p>
                            <strong>Important:</strong>
                            If you notice a missing item, incorrect
                            order, damaged packaging, or a possible
                            food safety concern, contact customer
                            support promptly with your order details.
                        </p>
                    </div>
                </div>

                {{-- ORDER ISSUES --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Issues With Your Order
                    </h5>

                    <p class="sudheera-term-text">
                        If your order is incomplete, incorrect,
                        damaged, or otherwise different from what
                        you ordered, please report the issue
                        through the available customer support
                        channel as soon as reasonably possible.

                        <br><br>

                        Please provide your order number and
                        a description of the issue. Photographs
                        or other relevant details may be requested
                        to help review the complaint.

                        <br><br>

                        Refunds, cancellations, and other
                        resolutions will be considered under
                        the applicable policies and legal
                        requirements.
                    </p>

                    <div class="sudheera-terms-highlight">
                        <p>
                            <strong>Food Safety:</strong>
                            Do not consume food that appears
                            spoiled, contaminated, or unsafe.
                            Contact customer support promptly
                            to report the concern.
                        </p>
                    </div>
                </div>

                {{-- PAYMENTS --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Payments & Pricing
                    </h5>

                    <ul class="sudheera-term-list">
                        <li>
                            Available payment methods are
                            displayed during checkout.
                        </li>

                        <li>
                            Customers must review the total
                            amount, including applicable
                            delivery charges, taxes, and
                            other fees, before confirming
                            the order.
                        </li>

                        <li>
                            Orders requiring online payment
                            are subject to payment confirmation
                            by the applicable payment provider.
                        </li>

                        <li>
                            Discounts, coupons, and promotional
                            offers are subject to their stated
                            eligibility requirements and validity.
                        </li>

                        <li>
                            Suspected duplicate payments or
                            payment discrepancies should be
                            reported to customer support with
                            the relevant transaction details.
                        </li>
                    </ul>
                </div>

                {{-- CANCELLATION & REFUNDS --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Cancellation & Refunds
                    </h5>

                    <p class="sudheera-term-text">
                        Cancellation eligibility depends on
                        the order status and whether the
                        restaurant has accepted or started
                        preparing the food.

                        <br><br>

                        Refund requests are reviewed according
                        to the circumstances of the order,
                        payment status, applicable policy,
                        and legal requirements.

                        <br><br>

                        For complete information about
                        cancellation eligibility, refund
                        processing, and order-related complaints,
                        please review our Cancellation & Refund
                        Policy.
                    </p>
                </div>

                {{-- INTELLECTUAL PROPERTY --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Intellectual Property
                    </h5>

                    <p class="sudheera-term-text">
                        Content available on the BAKASURAN
                        platform may include logos, branding,
                        photographs, graphics, text, website
                        designs, and other materials owned
                        by BAKASURAN or their respective owners.

                        <br><br>

                        Unless permitted by applicable law
                        or authorised by the relevant rights
                        holder, you may not reproduce,
                        distribute, modify, or commercially
                        use protected content without
                        appropriate permission.
                    </p>
                </div>

                {{-- PROHIBITED USE --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Acceptable Use
                    </h5>

                    <ul class="sudheera-term-list">
                        <li>
                            Do not place fraudulent orders
                            or intentionally provide misleading
                            information.
                        </li>

                        <li>
                            Do not misuse promotional codes,
                            discounts, or platform features.
                        </li>

                        <li>
                            Do not attempt to gain unauthorised
                            access to accounts, systems,
                            or customer information.
                        </li>

                        <li>
                            Do not upload unlawful, abusive,
                            or harmful content through any
                            platform feature.
                        </li>

                        <li>
                            Do not interfere with the normal
                            operation, security, or availability
                            of the platform.
                        </li>
                    </ul>
                </div>

                {{-- THIRD-PARTY SERVICES --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Restaurants & Third-Party Services
                    </h5>

                    <p class="sudheera-term-text">
                        BAKASURAN may work with participating
                        restaurants, payment providers, and
                        delivery partners to facilitate orders.

                        <br><br>

                        The availability and operation of
                        these services may depend on the
                        relevant provider. Where a third-party
                        service has its own applicable terms
                        or privacy policy, those terms may
                        also apply to your use of that service.

                        <br><br>

                        Nothing in these terms excludes
                        responsibilities that cannot lawfully
                        be excluded.
                    </p>
                </div>

                {{-- LIABILITY --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Liability
                    </h5>

                    <p class="sudheera-term-text">
                        BAKASURAN aims to provide a reliable
                        platform, but service availability,
                        menu information, preparation times,
                        and delivery estimates may occasionally
                        be affected by circumstances beyond
                        our reasonable control.

                        <br><br>

                        Nothing in these terms is intended
                        to exclude or limit any liability,
                        consumer right, or remedy that cannot
                        be excluded or limited under applicable
                        law.

                        <br><br>

                        Any limitation of liability, where
                        legally permitted, will be interpreted
                        subject to applicable law and the
                        circumstances of the claim.
                    </p>
                </div>

                {{-- CHANGES --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Changes to These Terms
                    </h5>

                    <p class="sudheera-term-text">
                        We may update these Terms of Service
                        when our platform, services, or
                        applicable requirements change.

                        <br><br>

                        Revised terms will be published
                        on this page with an updated effective
                        date where appropriate. Your use
                        of the platform after an update is
                        subject to the applicable terms
                        and legal requirements.
                    </p>
                </div>

                {{-- GOVERNING LAW --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Governing Law
                    </h5>

                    <p class="sudheera-term-text">
                        These terms are subject to the
                        applicable laws of India.

                        <br><br>

                        Any dispute relating to the platform
                        or these terms will be handled by
                        the appropriate forum or courts
                        in accordance with applicable law.
                    </p>
                </div>

                {{-- BUSINESS INFORMATION --}}
                <div class="sudheera-term-item">
                    <h5 class="sudheera-term-heading">
                        Business Information
                    </h5>

                    <div class="sudheera-address-box">
                        <p>
                            <strong>BAKASURAN</strong>
                            <br>
                            Food Ordering Platform
                            <br>
                            India
                            <br><br>
                            For official business details
                            and customer support, please
                            refer to the contact information
                            published on our platform.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



@endsection