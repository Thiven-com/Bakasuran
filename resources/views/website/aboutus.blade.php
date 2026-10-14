@extends('layouts.website')

@section('content')

<style>
    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .sudheera-breadcrumb {
        background: #faf7f2;
        border-bottom: 1px solid #eee4d7;
        padding-bottom: 10px;
    }

    .sudheera-breadcrumb .breadcrumb-content {
        padding-top: 50px;
    }

    .sudheera-breadcrumb .breadcrumb-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-left: 20px;
        margin-right: 20px;
    }

    .sudheera-breadcrumb .breadcrumb-list li,
    .sudheera-breadcrumb .breadcrumb-list a {
        font-size: 13px;
        color: #756c63;
        text-decoration: none;
        font-weight: 500;
    }

    .sudheera-breadcrumb .breadcrumb-list .current {
        color: #a56a16;
    }


    /* =========================================================
       ABOUT PAGE
    ========================================================= */

    .sudheera-about-page {
        background: #fff;
    }

    .sudheera-content-container {
        max-width: 1200px;
        margin: 0 auto;
    }


    /* =========================================================
       MAIN TITLE
    ========================================================= */

    .sudheera-section-title {
        text-align: center;
        margin: 15px 0 22px;
    }

    .sudheera-section-title h2 {
        margin: 0 0 10px;
        font-family: "Instrument Serif", serif;
        font-size: 42px;
        font-weight: 600;
        color: #8b4f10;
        line-height: 1.2;
    }

    .sudheera-section-title p {
        margin: 0 auto;
        max-width: 700px;
        color: #77716a;
        font-size: 15px;
        line-height: 1.7;
    }


    /* =========================================================
       ABOUT CONTENT
    ========================================================= */

    .sudheera-text-content {
        margin-top: 10px;
    }

    .sudheera-text-content p {
        font-size: 16px;
        line-height: 1.85;
        color: #38332e;
        margin-bottom: 14px;
        text-align: justify;
    }

    .sudheera-text-content strong {
        color: #8b4f10;
    }


    /* =========================================================
       WHY CHOOSE US
    ========================================================= */

    .sudheera-why-heading {
        text-align: center;
        margin: 35px 0 20px;
    }

    .sudheera-why-heading h3 {
        margin: 0;
        font-family: "Instrument Serif", serif;
        font-size: 34px;
        color: #8b4f10;
        font-weight: 600;
    }

    .sudheera-why-heading p {
        margin: 6px 0 0;
        color: #777;
        font-size: 14px;
    }

    .sudheera-points-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .sudheera-points-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px 35px;
    }

    .sudheera-points-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        font-size: 15px;
        line-height: 1.5;
        color: #3e3934;
        font-weight: 500;
    }

    .sudheera-points-list li i {
        color: #b57920;
        font-size: 17px;
        margin-top: 3px;
        flex-shrink: 0;
    }


    /* =========================================================
       BRAND FOOTER
    ========================================================= */

    .sudheera-brand-footer {
        padding: 35px 25px 15px;
        text-align: center;
    }

    .sudheera-brand-footer-title {
        margin: 0 0 10px;
        font-family: "Instrument Serif", serif;
        font-size: 38px;
        font-weight: 600;
        color: #8b4f10;
    }

    .sudheera-brand-divider {
        width: 90px;
        height: 3px;
        border-radius: 50px;
        background: #b57920;
        margin: 0 auto 16px;
    }

    .sudheera-brand-tagline {
        margin: 0 0 10px;
        font-size: 17px;
        color: #7d511e;
        letter-spacing: .5px;
    }

    .sudheera-brand-desc {
        max-width: 650px;
        margin: 0 auto;
        font-size: 15px;
        line-height: 1.8;
        color: #716b65;
    }


    /* =========================================================
       COMMITMENT SECTION
    ========================================================= */

    .sudheera-values-box {
        border: 1px solid #d7b477;
        border-radius: 30px;
        overflow: hidden;
        background: #fffdf9;
        box-shadow: 0 15px 40px rgba(70, 45, 15, .08);
    }

    .sudheera-values-header {
        padding: 30px 35px;
    }

    .sudheera-commitment-content {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .sudheera-commitment-icon {
        width: 95px;
        height: 95px;
        min-width: 95px;
        border-radius: 50%;
        background: #a56a16;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        box-shadow: 0 12px 28px rgba(165, 106, 22, .25);
    }

    .sudheera-commitment-title {
        margin: 0 0 7px;
        font-family: "Instrument Serif", serif;
        font-size: 38px;
        color: #6f4210;
        font-weight: 600;
    }

    .sudheera-commitment-divider {
        width: 80px;
        height: 3px;
        border-radius: 50px;
        background: #b57920;
        margin-bottom: 13px;
    }

    .sudheera-commitment-text {
        margin: 0;
        font-size: 15px;
        line-height: 1.7;
        color: #62594f;
    }


    /* =========================================================
       VALUES GRID
    ========================================================= */

    .sudheera-values-grid {
        border-top: 1px solid #eadcc8;
    }

    .sudheera-value-item {
        height: 100%;
        text-align: center;
        padding: 30px 20px;
        transition: .35s ease;
    }

    .sudheera-value-item:hover {
        background: #fbf1e3;
        transform: translateY(-4px);
    }

    .sudheera-value-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #c4944d;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9a5d0d;
        font-size: 32px;
        box-shadow: 0 8px 20px rgba(80, 50, 15, .08);
    }

    .sudheera-value-item h5 {
        margin: 0 0 10px;
        font-size: 15px;
        color: #70420e;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .sudheera-value-item p {
        max-width: 220px;
        margin: 0 auto;
        font-size: 13px;
        line-height: 1.7;
        color: #70675e;
    }


    /* =========================================================
       FOOTER STRIP
    ========================================================= */

    .sudheera-values-footer {
        background: #a56a16;
        color: #fff;
        padding: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 14px;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .sudheera-values-footer .dot {
        font-size: 20px;
        color: #f2d59d;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 991px) {

        .sudheera-points-list {
            grid-template-columns: 1fr;
        }

        .sudheera-commitment-content {
            flex-direction: column;
            text-align: center;
        }

        .sudheera-commitment-divider {
            margin-left: auto;
            margin-right: auto;
        }

        .sudheera-commitment-title {
            font-size: 34px;
        }
    }


    @media (max-width: 767px) {

        .sudheera-breadcrumb .breadcrumb-content {
            padding-top: 30px;
        }

        .sudheera-section-title {
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .sudheera-section-title h2 {
            font-size: 30px;
        }

        .sudheera-section-title p {
            font-size: 13px;
        }

        .sudheera-text-content p {
            font-size: 14px;
            line-height: 1.7;
            text-align: left;
        }

        .sudheera-why-heading {
            margin-top: 28px;
        }

        .sudheera-why-heading h3 {
            font-size: 27px;
        }

        .sudheera-points-list li {
            font-size: 14px;
        }

        .sudheera-brand-footer {
            padding: 30px 15px 10px;
        }

        .sudheera-brand-footer-title {
            font-size: 30px;
        }

        .sudheera-brand-tagline {
            font-size: 15px;
        }

        .sudheera-brand-desc {
            font-size: 13px;
        }

        .sudheera-values-box {
            border-radius: 22px;
        }

        .sudheera-values-header {
            padding: 25px 18px;
        }

        .sudheera-commitment-icon {
            width: 80px;
            height: 80px;
            min-width: 80px;
            font-size: 34px;
        }

        .sudheera-commitment-title {
            font-size: 29px;
        }

        .sudheera-commitment-text {
            font-size: 14px;
        }

        .sudheera-value-item {
            padding: 25px 15px;
        }

        .sudheera-value-icon {
            width: 70px;
            height: 70px;
            font-size: 28px;
        }

        .sudheera-value-item h5 {
            font-size: 14px;
        }

        .sudheera-value-item p {
            font-size: 13px;
        }

        .sudheera-values-footer {
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12px;
            letter-spacing: 1.5px;
        }
    }
</style>


{{-- =========================================================
    BREADCRUMB
========================================================= --}}

<section class="sudheera-breadcrumb">

    <div class="container">

        <div class="breadcrumb-content">

            <ul class="breadcrumb-list">

                <li>
                    <a href="{{ route('home') }}">
                        Home
                    </a>
                </li>

                <li>
                    /
                </li>

                <li class="current">
                    About Us
                </li>

            </ul>

        </div>

    </div>

</section>


{{-- =========================================================
    ABOUT SECTION
========================================================= --}}

<section class="flat-spacing">

    <div class="container">

        <div class="sudheera-about-page">

            <div class="sudheera-content-container">

                {{-- TITLE --}}

                <div class="sudheera-section-title">

                    <h2>
                        About BAKASURAN
                    </h2>

                    <p>
                        Bold flavours, delicious food and a better way
                        to discover your favourite meals.
                    </p>

                </div>


                {{-- CONTENT --}}

                <div class="sudheera-text-content">

                    <p>
                        At <strong>BAKASURAN</strong>, we believe that food
                        is more than just a meal. It is an experience that
                        brings people together, creates memories and makes
                        every moment more enjoyable.
                    </p>

                    <p>
                        BAKASURAN is a restaurant commerce platform designed
                        to help customers explore delicious food options,
                        discover new favourites and enjoy a convenient
                        food-ordering experience. From flavourful biryanis
                        and sizzling burgers to cheesy pizzas, noodles,
                        sandwiches and delightful desserts, we bring a
                        variety of food choices together in one place.
                    </p>

                    <p>
                        We believe that choosing food should be simple,
                        enjoyable and convenient. Our platform presents
                        food products with clear descriptions, images and
                        pricing so customers can explore available options
                        and choose meals that suit their tastes and
                        preferences.
                    </p>

                    <p>
                        Whether you are planning a family meal, enjoying
                        a quick lunch, sharing food with friends or craving
                        something delicious after a long day, BAKASURAN
                        helps you discover food for different occasions
                        and everyday cravings.
                    </p>

                </div>


                {{-- WHY CHOOSE --}}

                <div class="sudheera-why-heading">

                    <h3>
                        Why Choose BAKASURAN?
                    </h3>

                    <p>
                        Big flavours. Easy ordering. Food worth enjoying.
                    </p>

                </div>


                <div class="sudheera-points-wrapper">

                    <ul class="sudheera-points-list">

                        <li>
                            <i class="fa fa-check-circle"></i>
                            A variety of food options across multiple categories
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            Discover biryanis, burgers, pizzas, noodles and more
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            Easy browsing of food products and menu details
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            Clear product descriptions and pricing
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            A convenient platform for exploring food choices
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            Options for everyday meals, snacks and desserts
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            A user-friendly food discovery and ordering experience
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            A platform focused on making food selection simple
                        </li>

                    </ul>

                </div>


                {{-- BRAND FOOTER --}}

                <div class="sudheera-brand-footer">

                    <h3 class="sudheera-brand-footer-title">
                        BAKASURAN
                    </h3>

                    <div class="sudheera-brand-divider"></div>

                    <p class="sudheera-brand-tagline">
                        <strong>
                            Big Flavours • Bold Cravings • Better Experiences
                        </strong>
                    </p>

                    <p class="sudheera-brand-desc">
                        BAKASURAN brings food discovery and convenience
                        together through a platform where customers can
                        explore their favourite dishes, discover new tastes
                        and find something delicious for every craving.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



@endsection