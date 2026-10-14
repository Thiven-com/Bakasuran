
@extends('layouts.website')

@section('content')

<style>
    /* =========================================================
       SUDHEERA SAREES - FAQ PAGE
    ========================================================= */

    .sudheera-faq-page {
        padding: 10px 0 70px;
    }

    /* ================= BREADCRUMB ================= */

    .sudheera-faq-breadcrumb {
        padding-bottom: 10px;
    }

    .sudheera-faq-breadcrumb .breadcrumb-content {
        padding-top: 50px;
    }

    .sudheera-faq-breadcrumb .breadcrumb-list {
        display: flex;
        align-items: center;
        list-style: none !important;
        padding: 0;
        margin: 0;
        margin-left: 20px;
    }

    .sudheera-faq-breadcrumb .breadcrumb-list li {
        list-style: none !important;
        font-size: 14px;
        color: #7a686b;
    }

    .sudheera-faq-breadcrumb .breadcrumb-list a {
        color: #30000e;
        text-decoration: none;
        font-weight: 600;
        transition: .3s ease;
    }

    .sudheera-faq-breadcrumb .breadcrumb-list a:hover {
        color: #c88618;
    }

    .sudheera-faq-separator {
        margin: 0 10px;
        color: #c88618;
        font-weight: 700;
    }


    /* ================= MAIN WRAPPER ================= */

    .sudheera-faq-center {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .sudheera-faq-wrapper {
        width: 70%;
        max-width: 900px;
        background: #fff;
        /* border: 1px solid #eee2d8; */
        /* border-radius: 30px; */
        padding: 42px 46px;
        /* box-shadow: 0 15px 45px rgba(48, 0, 14, .07); */
    }


    /* ================= HEADING ================= */

    .sudheera-faq-heading {
        text-align: center;
        margin-bottom: 35px;
    }

    .sudheera-faq-eyebrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #c88618;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .sudheera-faq-eyebrow::before,
    .sudheera-faq-eyebrow::after {
        content: '';
        width: 30px;
        height: 1px;
        background: #c88618;
    }

    .sudheera-faq-heading h1 {
        margin: 0 0 12px;
        color: #30000e;
        font-size: 40px;
        font-weight: 600;
        line-height: 1.2;
        font-family: 'Cormorant Garamond', serif;
    }

    .sudheera-faq-heading p {
        max-width: 600px;
        margin: 0 auto;
        color: #77676b;
        font-size: 15px;
        line-height: 1.8;
    }


    /* ================= FAQ ACCORDION ================= */

    .sudheera-faq-accordion {
        display: flex;
        flex-direction: column;
    }

    .sudheera-faq-item {
        border-bottom: 1px solid #eadfd8;
    }

    .sudheera-faq-item:last-child {
        border-bottom: 0;
    }


    /* ================= QUESTION ================= */

    .sudheera-faq-question {
        width: 100%;
        padding: 20px 0;
        border: 0;
        background: transparent;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        cursor: pointer;
        text-align: left;
    }

    .sudheera-faq-question:hover .sudheera-faq-title {
        color: #c88618;
    }

    .sudheera-faq-question-content {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .sudheera-faq-number {
        min-width: 35px;
        color: #c88618;
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
    }

    .sudheera-faq-title {
        margin: 0;
        color: #30000e;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.45;
        transition: .25s ease;
    }


    /* ================= TOGGLE ================= */

    .sudheera-faq-toggle {
        width: 40px;
        height: 40px;
        min-width: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;
        border: 1px solid #ead9c7;
        background: #fffaf5;

        position: relative;
        transition: all .3s ease;
    }

    .sudheera-faq-toggle::before,
    .sudheera-faq-toggle::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 1.5px;
        background: #30000e;
        transition: transform .3s ease;
    }

    .sudheera-faq-toggle::after {
        transform: rotate(90deg);
    }

    .sudheera-faq-question[aria-expanded="true"]
    .sudheera-faq-toggle {
        background: #30000e;
        border-color: #30000e;
    }

    .sudheera-faq-question[aria-expanded="true"]
    .sudheera-faq-toggle::before,
    .sudheera-faq-question[aria-expanded="true"]
    .sudheera-faq-toggle::after {
        background: #fff;
    }

    .sudheera-faq-question[aria-expanded="true"]
    .sudheera-faq-toggle::after {
        transform: rotate(0deg);
    }


    /* ================= ANSWER ================= */

    .sudheera-faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height .4s ease;
    }

    .sudheera-faq-answer.show {
        max-height: 500px;
    }

    .sudheera-faq-answer-inner {
        padding: 0 55px 24px 51px;
    }

    .sudheera-faq-answer-inner p {
        margin: 0;
        color: #625559;
        font-size: 15px;
        line-height: 1.85;
    }


    /* ================= HIGHLIGHT ================= */

    .sudheera-faq-highlight {
        margin-top: 15px;
        padding: 16px 18px;
        border-left: 3px solid #c88618;
        background: #fffaf4;
        border-radius: 8px;
    }

    .sudheera-faq-highlight p {
        color: #5c4b4f;
        font-size: 14px;
        line-height: 1.75;
    }


    /* ================= BOTTOM NOTE ================= */

    .sudheera-faq-note {
        margin-top: 35px;
        padding: 24px;
        border-radius: 20px;

        background: linear-gradient(
            135deg,
            #30000e 0%,
            #65001b 50%,
            #8a4023 100%
        );

        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .sudheera-faq-note::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(200, 134, 24, .08);
        right: -60px;
        top: -70px;
    }

    .sudheera-faq-note h5 {
        position: relative;
        z-index: 2;
        margin: 0 0 7px;
        color: #e5c27b;
        font-size: 18px;
        font-weight: 700;
    }

    .sudheera-faq-note p {
        position: relative;
        z-index: 2;
        margin: 0;
        color: rgba(255,255,255,.88);
        font-size: 14px;
        line-height: 1.7;
    }


    /* ================= TABLET ================= */

    @media (max-width: 991px) {

        .sudheera-faq-wrapper {
            width: 85%;
            padding: 32px 30px;
        }

        .sudheera-faq-heading h1 {
            font-size: 34px;
        }

        .sudheera-faq-title {
            font-size: 17px;
        }

        .sudheera-faq-answer-inner {
            padding-left: 51px;
        }
    }


    /* ================= MOBILE ================= */

    @media (max-width: 576px) {

        .sudheera-faq-page {
            padding-bottom: 40px;
        }

        .sudheera-faq-breadcrumb .breadcrumb-content {
            padding-top: 30px;
        }

        .sudheera-faq-breadcrumb .breadcrumb-list li {
            font-size: 13px;
        }

        .sudheera-faq-separator {
            margin: 0 7px;
        }

        .sudheera-faq-wrapper {
            width: 100%;
            padding: 25px 18px;
            border-radius: 22px;
        }

        .sudheera-faq-heading {
            margin-bottom: 25px;
        }

        .sudheera-faq-eyebrow {
            font-size: 10px;
            letter-spacing: 1.8px;
            gap: 7px;
        }

        .sudheera-faq-eyebrow::before,
        .sudheera-faq-eyebrow::after {
            width: 20px;
        }

        .sudheera-faq-heading h1 {
            font-size: 28px;
        }

        .sudheera-faq-heading p {
            font-size: 13px;
        }

        .sudheera-faq-question {
            padding: 17px 0;
            gap: 10px;
        }

        .sudheera-faq-question-content {
            gap: 10px;
        }

        .sudheera-faq-number {
            min-width: 27px;
            font-size: 16px;
        }

        .sudheera-faq-title {
            font-size: 15px;
            line-height: 1.45;
        }

        .sudheera-faq-toggle {
            width: 34px;
            height: 34px;
            min-width: 34px;
        }

        .sudheera-faq-toggle::before,
        .sudheera-faq-toggle::after {
            width: 12px;
        }

        .sudheera-faq-answer-inner {
            padding: 0 0 20px 37px;
        }

        .sudheera-faq-answer-inner p {
            font-size: 14px;
            line-height: 1.8;
        }

        .sudheera-faq-note {
            padding: 20px 16px;
            border-radius: 16px;
        }
    }
</style>


<!-- =========================================================
     BREADCRUMB
========================================================= -->


<section class="page-breadcrumb sudheera-faq-breadcrumb">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <span class="sudheera-faq-separator">/</span>
                </li>
                <li>FAQs</li>
            </ul>
        </div>
    </div>
</section>

<!-- =========================================================
     FAQ PAGE
========================================================= -->

<div class="sudheera-faq-page">
    <div class="container">
        <div class="sudheera-faq-center">
            <div class="sudheera-faq-wrapper">

                <!-- ================= HEADING ================= -->

                <div class="sudheera-faq-heading">
                    <div class="sudheera-faq-eyebrow">
                        BAKASURAN FOOD
                    </div>

                    <h1>
                        Frequently Asked Questions
                    </h1>

                    <p>
                        Everything you need to know about ordering food,
                        menu items, delivery, payments, cancellations
                        and your BAKASURAN food experience.
                    </p>
                </div>

                <!-- ================= FAQ ================= -->

                <div class="sudheera-faq-accordion">

                    <!-- FAQ 1 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="true"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">01</span>

                                <h3 class="sudheera-faq-title">
                                    How can I place a food order?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer show">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    Browse the available restaurants and food
                                    items on BAKASURAN. Select your favourite
                                    dishes, choose the available options,
                                    add them to your cart and proceed to
                                    checkout. Enter your delivery details
                                    and complete the order using a payment
                                    method available on the platform.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">02</span>

                                <h3 class="sudheera-faq-title">
                                    How can I find my favourite food or restaurant?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    Explore the food categories, browse
                                    restaurants and check the available menu
                                    items. You can also use the search feature,
                                    if available, to find a particular dish,
                                    cuisine or restaurant. Menu availability
                                    may vary by restaurant and delivery area.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">03</span>

                                <h3 class="sudheera-faq-title">
                                    How long does food delivery take?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    Delivery time depends on your location,
                                    restaurant preparation time, delivery
                                    availability and current order demand.
                                    Please check the estimated delivery
                                    information displayed on the platform,
                                    where available. Unexpected delays may
                                    occur during busy periods or due to
                                    circumstances outside our control.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">04</span>

                                <h3 class="sudheera-faq-title">
                                    Can I cancel my food order?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    Cancellation depends on the current
                                    status of your order and the applicable
                                    cancellation policy. Food preparation
                                    may begin shortly after an order is
                                    confirmed, which can limit cancellation
                                    options. Please check the cancellation
                                    terms and contact customer support
                                    as soon as possible if you need help.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">05</span>

                                <h3 class="sudheera-faq-title">
                                    What should I do if my order is incorrect or damaged?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    If you receive the wrong food item,
                                    notice missing items or find that your
                                    order packaging is damaged, contact
                                    BAKASURAN customer support as soon as
                                    possible. Provide your order details
                                    and clear photographs of the issue,
                                    where appropriate, so your request
                                    can be reviewed.
                                </p>

                                <div class="sudheera-faq-highlight">
                                    <p>
                                        Please keep your order details and
                                        any relevant photos until your
                                        concern has been reviewed. If you
                                        believe food is unsafe to consume,
                                        do not eat it.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 6 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">06</span>

                                <h3 class="sudheera-faq-title">
                                    Can I request a refund for my order?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    Refund eligibility depends on the
                                    circumstances of the order and the
                                    applicable Cancellation and Refund
                                    Policy. If your order is incorrect,
                                    incomplete or has another issue,
                                    contact customer support with your
                                    order details. Each request will be
                                    reviewed according to the applicable
                                    policy and payment process.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 7 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">07</span>

                                <h3 class="sudheera-faq-title">
                                    What payment methods are accepted?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    The payment methods available for your
                                    order are displayed during checkout.
                                    Select one of the available options
                                    and follow the payment instructions
                                    to complete your order. Your order
                                    confirmation and payment status should
                                    be checked on the platform.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 8 -->

                    <div class="sudheera-faq-item">
                        <button
                            type="button"
                            class="sudheera-faq-question"
                            aria-expanded="false"
                            onclick="toggleSudheeraFaq(this)"
                        >
                            <div class="sudheera-faq-question-content">
                                <span class="sudheera-faq-number">08</span>

                                <h3 class="sudheera-faq-title">
                                    How can I contact BAKASURAN customer support?
                                </h3>
                            </div>

                            <span class="sudheera-faq-toggle"></span>
                        </button>

                        <div class="sudheera-faq-answer">
                            <div class="sudheera-faq-answer-inner">
                                <p>
                                    You can reach BAKASURAN through the
                                    contact details provided on our
                                    Contact Us page. When contacting
                                    support about an order, please include
                                    your order number and a brief
                                    description of the issue so the team
                                    can understand your request.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================= BOTTOM NOTE ================= -->

                <div class="sudheera-faq-note">
                    <h5>
                        Need More Help?
                    </h5>

                    <p>
                        Have a question about your food, restaurant,
                        payment or delivery? BAKASURAN is here to help
                        you with your ordering experience. Visit our
                        Contact Us page for the available support details.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleSudheeraFaq(button) {
        const item = button.closest('.sudheera-faq-item');
        const answer = item.querySelector('.sudheera-faq-answer');
        const isOpen =
            button.getAttribute('aria-expanded') === 'true';

        // Close all FAQ items
        document
            .querySelectorAll('.sudheera-faq-question')
            .forEach(function (btn) {
                btn.setAttribute('aria-expanded', 'false');
            });

        document
            .querySelectorAll('.sudheera-faq-answer')
            .forEach(function (el) {
                el.classList.remove('show');
            });

        // Open selected FAQ
        if (!isOpen) {
            button.setAttribute('aria-expanded', 'true');
            answer.classList.add('show');
        }
    }
</script>


@endsection