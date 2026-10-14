@extends('layouts.website')
@section('content')

<style>
    /* =========================================================
       SUDHEERA SAREES
       PRIVACY POLICY
    ========================================================= */

    .sudheera-privacy-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ================= BREADCRUMB ================= */

    .sudheera-privacy-breadcrumb {
        padding-bottom: 10px;
    }

    .sudheera-privacy-breadcrumb .breadcrumb-content {
        padding-top: 50px;
    }

    .sudheera-privacy-breadcrumb .breadcrumb-list {
        display: flex;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 0;
        margin-left: 20px;
    }

    .sudheera-privacy-breadcrumb .breadcrumb-list li {
        font-size: 14px;
        color: #806b70;
    }

    .sudheera-privacy-breadcrumb .breadcrumb-list a {
        color: #30000e;
        text-decoration: none;
        font-weight: 600;
        transition: .3s ease;
    }

    .sudheera-privacy-breadcrumb .breadcrumb-list a:hover {
        color: #c88618;
    }

    .sudheera-privacy-separator {
        margin: 0 10px;
        color: #c88618;
        font-weight: 700;
    }


    /* =========================================================
       MAIN CONTENT
    ========================================================= */

    .sudheera-privacy-content {
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

    .sudheera-privacy-header {
        text-align: center;
        margin-bottom: 42px;
    }

    .sudheera-privacy-label {
        display: inline-block;
        color: #c88618;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .sudheera-privacy-title {
        margin: 0 0 14px;
        color: #30000e;
        font-size: 40px;
        line-height: 1.2;
        font-weight: 600;
    }

    .sudheera-privacy-intro {
        max-width: 720px;
        margin: 0 auto;
        color: #76666a;
        font-size: 16px;
        line-height: 1.8;
    }

    .sudheera-privacy-divider {
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
       POLICY ITEMS
    ========================================================= */

    .sudheera-privacy-item {
        padding: 28px 0;
        border-bottom: 1px solid #eee5df;
    }

    .sudheera-privacy-item:first-of-type {
        padding-top: 0;
    }

    .sudheera-privacy-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }


    /* =========================================================
       SECTION TITLE
    ========================================================= */

    .sudheera-privacy-section-title {
        display: flex;
        align-items: center;
        gap: 13px;
        color: #30000e;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .sudheera-privacy-section-title::before {
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

    .sudheera-privacy-text {
        font-size: 15px;
        line-height: 1.85;
        color: #5e5255;
        margin: 0;
    }

    .sudheera-privacy-text strong {
        color: #3b111c;
        font-weight: 700;
    }


    /* =========================================================
       LIST
    ========================================================= */

    .sudheera-privacy-list {
        margin: 10px 0 0;
        padding-left: 25px;
    }

    .sudheera-privacy-list li {
        font-size: 15px;
        line-height: 1.85;
        color: #5e5255;
        margin-bottom: 8px;
        padding-left: 5px;
    }

    .sudheera-privacy-list li::marker {
        color: #c88618;
        font-size: 14px;
    }


    /* =========================================================
       INTRO HIGHLIGHT
    ========================================================= */

    .sudheera-privacy-highlight {
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

    .sudheera-privacy-highlight p {
        margin: 0;
        font-size: 14px;
        line-height: 1.8;
        color: #5c4b4f;
    }

    .sudheera-privacy-highlight strong {
        color: #30000e;
    }


    /* =========================================================
       PRIVACY CONTACT BOX
    ========================================================= */

    .sudheera-privacy-contact {
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

    .sudheera-privacy-contact::before {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .05);
        right: -90px;
        top: -100px;
    }

    .sudheera-privacy-contact::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(200, 134, 24, .08);
        left: -50px;
        bottom: -50px;
    }

    .sudheera-privacy-contact-content {
        position: relative;
        z-index: 2;
    }

    .sudheera-privacy-contact h5 {
        margin: 0 0 10px;
        color: #e5c27b;
        font-size: 19px;
        font-weight: 700;
    }

    .sudheera-privacy-contact p {
        margin: 0;
        color: rgba(255, 255, 255, .88);
        font-size: 14px;
        line-height: 1.85;
    }

    .sudheera-privacy-contact a {
        color: #e5c27b;
        font-weight: 600;
        text-decoration: none;
    }

    .sudheera-privacy-contact a:hover {
        color: #fff;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 767px) {

        .sudheera-privacy-breadcrumb .breadcrumb-content {
            padding-top: 30px;
        }

        .sudheera-privacy-breadcrumb .breadcrumb-list li {
            font-size: 13px;
        }

        .sudheera-privacy-separator {
            margin: 0 7px;
        }

        .sudheera-privacy-content {
            padding: 30px 20px;
            border-radius: 22px;
        }

        .sudheera-privacy-header {
            margin-bottom: 30px;
        }

        .sudheera-privacy-label {
            font-size: 11px;
            letter-spacing: 2px;
        }

        .sudheera-privacy-title {
            font-size: 29px;
        }

        .sudheera-privacy-intro {
            font-size: 14px;
            line-height: 1.75;
        }

        .sudheera-privacy-item {
            padding: 23px 0;
        }

        .sudheera-privacy-section-title {
            font-size: 17px;
            gap: 10px;
        }

        .sudheera-privacy-section-title::before {
            width: 4px;
            height: 21px;
        }

        .sudheera-privacy-text,
        .sudheera-privacy-list li {
            font-size: 14px;
            line-height: 1.8;
        }

        .sudheera-privacy-list {
            padding-left: 21px;
        }

        .sudheera-privacy-highlight {
            padding: 15px 16px;
        }

        .sudheera-privacy-contact {
            padding: 21px;
            border-radius: 18px;
        }
    }
</style>


<!-- =========================================================
     BREADCRUMB
========================================================= -->


<section class="page-breadcrumb sudheera-privacy-breadcrumb">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <span class="sudheera-privacy-separator">/</span>
                </li>
                <li>Privacy Policy</li>
            </ul>
        </div>
    </div>
</section>

{{-- =========================================================
     PRIVACY POLICY
========================================================= --}}

<div class="section-term-user flat-spacing">
    <div class="container">
        <div class="sudheera-privacy-page">
            <div class="sudheera-privacy-content">

                {{-- HEADER --}}
                <div class="sudheera-privacy-header">
                    <span class="sudheera-privacy-label">
                        BAKASURAN FOOD
                    </span>

                    <h1 class="sudheera-privacy-title font-instrument_serif">
                        Privacy Policy
                    </h1>

                    <p class="sudheera-privacy-intro">
                        At BAKASURAN, we respect your privacy and
                        are committed to handling your personal
                        information responsibly. This policy explains
                        what information may be collected when you
                        browse our platform, create an account,
                        place food orders, or use our services.
                    </p>

                    <div class="sudheera-privacy-divider"></div>
                </div>

                {{-- INFORMATION WE COLLECT --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Information We Collect
                    </h5>

                    <p class="sudheera-privacy-text">
                        When you access BAKASURAN, create an account,
                        place an order, or contact customer support,
                        we may collect information needed to operate
                        our platform and provide the requested services.
                    </p>

                    <ul class="sudheera-privacy-list">
                        <li>Name and account details</li>
                        <li>Email address and mobile number</li>
                        <li>Delivery address and PIN code</li>
                        <li>Order history, selected items, and preferences</li>
                        <li>Payment status and transaction references</li>
                        <li>Customer support requests and feedback</li>
                        <li>
                            Device, browser, and website usage
                            information, where collected
                        </li>
                        <li>
                            Reviews, ratings, and content you choose
                            to submit through the platform
                        </li>
                        <li>
                            Location information, if a location feature
                            is used and permission is provided where required
                        </li>
                    </ul>
                </div>

                {{-- HOW WE USE INFORMATION --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        How We Use Your Information
                    </h5>

                    <ul class="sudheera-privacy-list">
                        <li>
                            Create and manage your customer account
                        </li>
                        <li>
                            Process orders and communicate order status
                        </li>
                        <li>
                            Share necessary delivery details with
                            participating restaurants and delivery partners
                        </li>
                        <li>
                            Facilitate payments and investigate
                            payment-related issues
                        </li>
                        <li>
                            Respond to questions, complaints, and
                            customer support requests
                        </li>
                        <li>
                            Provide order confirmations and delivery updates
                        </li>
                        <li>
                            Improve our website, menu presentation,
                            and user experience
                        </li>
                        <li>
                            Detect suspicious activity, prevent misuse,
                            and protect our platform
                        </li>
                        <li>
                            Send promotional communications where
                            permitted and where applicable consent
                            has been obtained
                        </li>
                    </ul>
                </div>

                {{-- SHARING INFORMATION --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Sharing Your Information
                    </h5>

                    <p class="sudheera-privacy-text">
                        We share personal information only where
                        reasonably necessary to provide our services,
                        fulfil your requests, protect our platform,
                        or meet applicable legal obligations.
                    </p>

                    <ul class="sudheera-privacy-list">
                        <li>
                            With participating restaurants to prepare
                            and fulfil your food orders
                        </li>
                        <li>
                            With delivery partners to complete
                            deliveries and contact customers when needed
                        </li>
                        <li>
                            With payment providers to process
                            transactions and resolve payment issues
                        </li>
                        <li>
                            With technology, hosting, analytics,
                            and customer support providers that
                            help operate our services
                        </li>
                        <li>
                            With authorised authorities when required
                            by applicable law or valid legal process
                        </li>
                        <li>
                            Where necessary to investigate fraud,
                            address security concerns, or protect
                            legal rights
                        </li>
                    </ul>

                    <div class="sudheera-privacy-highlight">
                        <p>
                            <strong>Your Privacy Matters:</strong>
                            We aim to limit information sharing
                            to what is reasonably necessary for
                            the relevant purpose. Information
                            handling is subject to applicable
                            law and our actual service arrangements.
                        </p>
                    </div>
                </div>

                {{-- PAYMENTS --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Payment Information
                    </h5>

                    <p class="sudheera-privacy-text">
                        Payments made through BAKASURAN may be
                        handled by the payment methods and providers
                        enabled on the platform. We may receive
                        transaction details, payment status,
                        and reference numbers needed to manage
                        orders and resolve payment issues.
                    </p>

                    <p class="sudheera-privacy-text">
                        Payment providers may process information
                        under their own privacy and security terms.
                        The payment information actually collected
                        depends on the payment method you choose.
                    </p>
                </div>

                {{-- COOKIES --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Cookies & Similar Technologies
                    </h5>

                    <p class="sudheera-privacy-text">
                        BAKASURAN may use cookies or similar
                        technologies, where implemented, to maintain
                        sessions, support account functionality,
                        remember preferences, improve performance,
                        and understand platform usage.

                        <br><br>

                        You may be able to manage cookies through
                        your browser settings. Disabling certain
                        cookies may affect features that rely on
                        them. Any consent controls offered on the
                        platform should be used according to
                        your preferences.
                    </p>
                </div>

                {{-- THIRD-PARTY SERVICES --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Third-Party Services
                    </h5>

                    <p class="sudheera-privacy-text">
                        Our platform may connect to third-party
                        payment services, maps, delivery services,
                        analytics tools, or social media platforms.

                        <br><br>

                        These services may process information
                        under their own terms and privacy policies.
                        We encourage you to review the relevant
                        policies when using third-party services.
                        Our responsibility for those services
                        depends on our relationship with the
                        provider and applicable law.
                    </p>
                </div>

                {{-- DATA SECURITY --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Data Security & Retention
                    </h5>

                    <p class="sudheera-privacy-text">
                        We aim to use reasonable safeguards to
                        protect personal information against
                        unauthorised access, loss, misuse,
                        or disclosure.
                    </p>

                    <ul class="sudheera-privacy-list">
                        <li>
                            Access to information should be limited
                            to authorised personnel and service
                            providers where appropriate.
                        </li>
                        <li>
                            Information may be retained for as long
                            as needed to provide services, manage
                            orders, resolve disputes, maintain records,
                            or meet legal obligations.
                        </li>
                        <li>
                            Retention periods may differ depending
                            on the type of information and its purpose.
                        </li>
                    </ul>

                    <p class="sudheera-privacy-text">
                        No online system can be guaranteed to be
                        completely secure. We will handle security
                        concerns in accordance with applicable
                        requirements.
                    </p>
                </div>

                {{-- YOUR CHOICES AND RIGHTS --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Your Choices & Privacy Rights
                    </h5>

                    <ul class="sudheera-privacy-list">
                        <li>
                            You may update or correct your account
                            information using available account features.
                        </li>
                        <li>
                            You may opt out of promotional emails
                            using the unsubscribe option where provided.
                        </li>
                        <li>
                            You may manage browser cookies through
                            your browser settings.
                        </li>
                        <li>
                            You may request access to, correction
                            of, or deletion of personal information,
                            subject to applicable law and legitimate
                            record-retention requirements.
                        </li>
                        <li>
                            You may withdraw consent where processing
                            is based on consent, subject to applicable
                            legal and operational requirements.
                        </li>
                        <li>
                            Necessary service communications,
                            including order and payment updates,
                            may still be sent when relevant.
                        </li>
                    </ul>

                    <p class="sudheera-privacy-text">
                        To exercise an applicable privacy right,
                        contact us using the official contact
                        details provided on the platform.
                    </p>
                </div>

                {{-- CHILDREN'S PRIVACY --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Children's Privacy
                    </h5>

                    <p class="sudheera-privacy-text">
                        BAKASURAN is intended to be used in
                        accordance with applicable age and
                        consent requirements. Where the law
                        requires parental or guardian consent
                        for processing a child's personal
                        information, the relevant requirements
                        must be followed.
                    </p>
                </div>

                {{-- POLICY UPDATES --}}
                <div class="sudheera-privacy-item">
                    <h5 class="sudheera-privacy-section-title">
                        Updates to This Privacy Policy
                    </h5>

                    <p class="sudheera-privacy-text">
                        We may update this Privacy Policy to
                        reflect changes in our platform, services,
                        information-handling practices, or
                        applicable legal requirements.

                        <br><br>

                        The updated policy will be published
                        on this page with a revised effective
                        date where appropriate. We encourage
                        you to review this page periodically.
                    </p>
                </div>

                {{-- CONTACT --}}
                <div class="sudheera-privacy-contact">
                    <div class="sudheera-privacy-contact-content">
                        <h5>
                            Questions About Your Privacy?
                        </h5>

                        <p>
                            If you have questions about this
                            Privacy Policy, how your information
                            is handled, or a privacy-related
                            request, please contact BAKASURAN
                            through the official support
                            channels available on our platform.

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