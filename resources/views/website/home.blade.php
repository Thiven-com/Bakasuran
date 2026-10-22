@extends('layouts.website')
@section('content')
    <style>
        /* =========================================
                                                                                           HERO BANNER
                                                                                        ========================================= */

        .hero-banner {
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .hero-slide {
            width: 100%;
            height: 430px;
            position: relative;
            overflow: hidden;
            display: none;
        }

        .hero-slide.active {
            display: block;
        }

        .hero-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            max-width: none;
            object-fit: fill;
            object-position: center;
            display: block;
        }


        /* =========================================
                                                                                           HERO CONTENT
                                                                                        ========================================= */

        .hero-content {
            position: absolute;

            top: 0;
            left: 0;

            width: 50%;
            height: 100%;

            padding-left: 12%;
            padding-top: 42px;

            display: flex;
            flex-direction: column;
            justify-content: flex-start;

            color: #5a1825;
        }


        /* Small Heading */

        .hero-small-title {
            font-size: 11px;
            letter-spacing: 3px;

            color: #6d5a50;

            margin-bottom: 8px;
        }


        /* Main Heading */

        .hero-content h1 {
            margin: 0;

            font-family: Georgia, "Times New Roman", serif;

            font-size: 40px;
            line-height: 1.05;

            font-weight: 500;

            color: #541521;
        }

        .hero-content h1 span {
            font-family: "Brush Script MT", "Segoe Script", cursive;

            font-size: 64px;
            font-weight: 400;

            color: #8b2b1d;
        }


        /* Description */

        .hero-content p {
            margin-top: 15px;
            margin-bottom: 18px;

            font-size: 13px;
            line-height: 1.5;

            color: #514944;
        }


        /* =========================================
                                                                                           BUTTONS
                                                                                        ========================================= */

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-btn {
            min-width: 153px;
            height: 36px;

            padding: 0 17px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            text-decoration: none;

            font-size: 9px;
            font-weight: 600;

            letter-spacing: .3px;

            transition: all .25s ease;
        }

        .hero-btn span {
            font-size: 16px;
            margin-left: 10px;
        }


        /* Primary */

        .hero-btn-primary {
            background: #76001f;
            color: #fff;

            border: 1px solid #76001f;
        }

        .hero-btn-primary:hover {
            background: #5c0018;
        }


        /* Secondary */

        .hero-btn-secondary {
            background: rgba(255, 255, 255, .65);

            color: #4e2026;

            border: 1px solid #8c6d68;
        }

        .hero-btn-secondary:hover {
            background: #fff;
        }


        /* =========================================
                                                                                           FEATURES
                                                                                        ========================================= */

        .hero-features {
            display: flex;

            align-items: center;

            gap: 20px;

            margin-top: 27px;
        }

        .hero-feature {
            display: flex;
            align-items: center;

            gap: 6px;
        }

        .feature-icon {
            width: 27px;
            height: 27px;

            border: 1px solid #9b795e;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #815d42;

            font-size: 13px;
        }

        .hero-feature strong {
            display: block;

            font-size: 8px;
            font-weight: 600;

            color: #493d36;
        }

        .hero-feature small {
            display: block;

            font-size: 8px;

            color: #5e554e;
        }


        /* =========================================
                                                                                           ARROWS
                                                                                        ========================================= */

        .hero-arrow {
            position: absolute;

            top: 50%;

            transform: translateY(-50%);

            width: 28px;
            height: 28px;

            border: none;
            border-radius: 50%;

            background: rgba(255, 255, 255, .9);

            color: #4d3430;

            font-size: 25px;
            line-height: 20px;

            cursor: pointer;

            z-index: 5;

            transition: all .2s ease;
        }

        .hero-arrow:hover {
            background: #ffffff3f;
        }

        .hero-prev {
            left: 15px;
        }

        .hero-next {
            right: 15px;
        }


        /* =========================================
                                                                                           DOTS
                                                                                        ========================================= */

        .hero-dots {
            position: absolute;

            bottom: 12px;
            left: 50%;

            transform: translateX(-50%);

            display: flex;
            gap: 6px;
        }

        .hero-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: rgba(100, 30, 40, .3);

            cursor: pointer;
        }

        .hero-dot.active {
            background: #76001f;
        }


        /* =========================================
                                                                                           TABLET
                                                                                        ========================================= */

        @media (max-width: 1100px) {

            .hero-slide {
                height: 390px;
            }

            .hero-content {
                padding-left: 8%;
            }

            .hero-content h1 {
                font-size: 34px;
            }

            .hero-content h1 span {
                font-size: 54px;
            }

            .hero-features {
                gap: 12px;
            }
        }


        /* =========================================
                                                                                           MOBILE
                                                                                        ========================================= */

        @media (max-width: 768px) {

            .hero-slide {
                height: 500px;
            }

            .hero-image {
                object-position: 65% center;
            }

            .hero-content {
                width: 100%;
                padding: 35px 25px;

                background: linear-gradient(to right,
                        rgba(255, 248, 240, .96),
                        rgba(255, 248, 240, .72),
                        rgba(255, 248, 240, 0));
            }

            .hero-content h1 {
                font-size: 32px;
            }

            .hero-content h1 span {
                font-size: 48px;
            }

            .hero-content p {
                font-size: 12px;
            }

            .hero-buttons {
                flex-wrap: wrap;
            }

            .hero-btn {
                min-width: 140px;
            }

            .hero-features {
                gap: 10px;
                flex-wrap: wrap;
                max-width: 380px;
            }

            .hero-feature {
                width: 150px;
            }

            .hero-arrow {
                width: 26px;
                height: 26px;
            }
        }


        /* =========================================
                                                                                           SMALL MOBILE
                                                                                        ========================================= */

        @media (max-width: 480px) {

            .hero-slide {
                height: 460px;
            }

            .hero-content {
                padding: 28px 20px;
            }

            .hero-small-title {
                font-size: 9px;
                letter-spacing: 2px;
            }

            .hero-content h1 {
                font-size: 27px;
            }

            .hero-content h1 span {
                font-size: 42px;
            }

            .hero-content p {
                font-size: 11px;
            }

            .hero-buttons {
                gap: 7px;
            }

            .hero-btn {
                height: 34px;
                min-width: 130px;
                font-size: 8px;
            }

            .hero-features {
                margin-top: 20px;
            }

            .hero-feature {
                width: 135px;
            }
        }


        /* =========================================
                                                                                           SHOP BY CATEGORY
                                                                                        ========================================= */

        .shop-category-section {
            width: 100%;
            padding: 35px 24px 40px;
            background:
                radial-gradient(circle at 50% 0%,
                    rgba(190, 145, 90, 0.08),
                    transparent 45%),
                #fffdf9;
            overflow: hidden;
        }


        /* =========================================
                                                                                           HEADING
                                                                                        ========================================= */

        .category-heading {
            max-width: 1100px;
            margin: 0 auto 22px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
        }

        .heading-content {
            text-align: center;
            flex-shrink: 0;
        }

        .heading-content h2 {
            margin: 0;

            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
            line-height: 1.1;
            font-weight: 600;
            letter-spacing: -0.4px;

            color: #171717;

            position: relative;
        }

        .heading-content h2::before {
            content: "";
            position: absolute;

            left: -25px;
            top: 50%;

            width: 15px;
            height: 1px;

            background: #9b6b32;
        }

        .heading-content h2::after {
            content: "";
            position: absolute;

            right: -25px;
            top: 50%;

            width: 15px;
            height: 1px;

            background: #9b6b32;
        }

        .heading-content p {
            margin: 7px 0 0;

            font-family: Arial, sans-serif;
            font-size: 11px;
            letter-spacing: 0.15px;

            color: #777;
        }

        .heading-line {
            width: 45px;
            height: 1px;
            background: #9b6b32;
            opacity: 0.75;
        }


        /* =========================================
                                                                                           CATEGORY WRAPPER
                                                                                        ========================================= */

        .category-wrapper {
            width: 100%;
            max-width: 1480px;
            margin: auto;

            display: grid;
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 8px;
        }


        /* =========================================
                                                                                           CARD
                                                                                        ========================================= */

        .category-card {
            min-width: 0;

            display: block;

            text-decoration: none;

            background: #fff;

            border: 1px solid #eadfd2;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 3px 12px rgba(80, 50, 20, 0.07);

            transition:
                transform 0.35s ease,
                box-shadow 0.35s ease,
                border-color 0.35s ease;
        }

        .category-card:hover {
            transform: translateY(-6px);

            border-color: #d4b07b;

            box-shadow:
                0 12px 28px rgba(80, 50, 20, 0.14);
        }


        /* =========================================
                                                                                           IMAGE
                                                                                        ========================================= */

        .category-image {
            width: 100%;
            aspect-ratio: 0.78;

            overflow: hidden;

            background: #f3eee7;
        }

        .category-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            transition:
                transform 0.6s cubic-bezier(0.2, 0.7, 0.2, 1),
                filter 0.4s ease;
        }

        .category-card:hover .category-image img {
            transform: scale(1.06);
            filter: saturate(1.05);
        }


        /* =========================================
                                                                                           INFO
                                                                                        ========================================= */

        .category-info {
            min-height: 62px;

            padding: 8px 9px 9px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            background:
                linear-gradient(180deg,
                    #fffefa 0%,
                    #faf6ef 100%);
        }

        .category-info h3 {
            margin: 0 0 3px;

            font-family: Georgia, "Times New Roman", serif;

            font-size: 13px;
            line-height: 1.1;

            font-weight: 600;

            color: #24201d;

            white-space: nowrap;
        }

        .category-info p {
            margin: 0;

            font-family: Arial, sans-serif;

            font-size: 10px;

            color: #777;

            white-space: nowrap;
        }


        /* =========================================
                                                                                           ARROW
                                                                                        ========================================= */

        .category-arrow {
            width: 23px;
            height: 23px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            border: 1px solid #e6d8c7;

            color: #8e642f;

            font-size: 17px;
            line-height: 1;

            transition:
                background 0.3s ease,
                color 0.3s ease,
                transform 0.3s ease;
        }

        .category-card:hover .category-arrow {
            background: #9b6b32;
            color: #fff;

            transform: translateX(2px);
        }


        /* =========================================
                                                                                           TABLET
                                                                                        ========================================= */

        @media (max-width: 1100px) {

            .category-wrapper {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
            }

            .category-image {
                aspect-ratio: 0.85;
            }

        }


        /* =========================================
                                                                                           MOBILE
                                                                                        ========================================= */

        @media (max-width: 700px) {

            .shop-category-section {
                padding: 28px 14px 4px;
            }

            .category-heading {
                margin-bottom: 18px;
                gap: 10px;
            }

            .heading-line {
                width: 25px;
            }

            .heading-content h2 {
                font-size: 22px;
            }

            .heading-content p {
                font-size: 10px;
            }

            .category-wrapper {
                display: flex;

                overflow-x: auto;

                gap: 10px;

                padding: 5px 2px 15px;

                scroll-snap-type: x mandatory;

                scrollbar-width: none;
            }

            .category-wrapper::-webkit-scrollbar {
                display: none;
            }

            .category-card {
                flex: 0 0 145px;

                scroll-snap-align: start;

                border-radius: 16px;
            }

            .category-image {
                aspect-ratio: 0.78;
            }

            .category-info {
                min-height: 58px;
                padding: 7px 8px;
            }

            .category-info h3 {
                font-size: 12px;
            }

            .category-info p {
                font-size: 9px;
            }

            .category-arrow {
                width: 20px;
                height: 20px;
                font-size: 15px;
            }

        }


        /* =========================================
                                                                                           SMALL MOBILE
                                                                                        ========================================= */

        @media (max-width: 400px) {

            .category-card {
                flex-basis: 132px;
            }

        }

        /* =========================================
                                                                                           PROMOTIONAL COLLECTION SECTION
                                                                                        ========================================= */

        .promo-section {
            width: 100%;
            padding: 30px 18px;

            background: #fffdf9;
        }

        .promo-grid {
            width: 100%;
            max-width: 1500px;

            height: 350px;

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                1.02fr 0.54fr 0.54fr;

            grid-template-rows:
                1fr 1fr;

            gap: 8px;
        }


        /* =========================================
                                                                                           COMMON CARD
                                                                                        ========================================= */

        .promo-card {
            position: relative;

            display: block;

            overflow: hidden;

            border-radius: 11px;

            text-decoration: none;

            background: #f5eee6;

            isolation: isolate;

            box-shadow:
                0 2px 8px rgba(70, 40, 20, 0.08);

            transition:
                transform 0.35s ease,
                box-shadow 0.35s ease;
        }

        .promo-card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 10px 25px rgba(70, 40, 20, 0.14);
        }


        /* =========================================
                                                                                           IMAGE
                                                                                        ========================================= */

        .promo-card img {
            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

            transition:
                transform 0.65s cubic-bezier(0.2,
                    0.7,
                    0.2,
                    1);
        }

        .promo-card:hover img {
            transform: scale(1.045);
        }


        /* =========================================
                                                                                           OVERLAY
                                                                                        ========================================= */

        .promo-overlay {
            position: absolute;

            inset: 0;

            z-index: 1;

            pointer-events: none;
        }


        /* =========================================
                                                                                           WEDDING CARD
                                                                                        ========================================= */

        .wedding-card {
            grid-column: 1;
            grid-row: 1 / 3;
        }

        /* .wedding-card .promo-overlay {
                        background:
                            linear-gradient(90deg,
                                rgba(70, 0, 20, 0.96) 0%,
                                rgba(95, 0, 25, 0.88) 32%,
                                rgba(75, 0, 20, 0.30) 62%,
                                rgba(50, 0, 10, 0.03) 100%);
                    } */

        .wedding-content {
            position: absolute;

            z-index: 2;

            left: 7%;

            top: 50%;

            transform: translateY(-50%);

            color: #fff;

            width: 48%;
        }

        .wedding-content h2 {
            margin: 0;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: clamp(28px,
                    3vw,
                    43px);

            line-height: 0.94;

            font-weight: 500;

            font-style: italic;

            letter-spacing: -1px;

            color: #f3d39b;
        }

        .wedding-content p {
            margin: 20px 0 17px;

            font-family: Arial, sans-serif;

            font-size: 13px;

            line-height: 1.55;

            color: rgba(255, 255, 255, 0.88);
        }

        .promo-button {
            display: inline-flex;

            align-items: center;
            justify-content: space-between;

            gap: 17px;

            min-width: 126px;

            padding: 9px 13px;

            border: 1px solid rgba(255,
                    255,
                    255,
                    0.75);

            border-radius: 5px;

            font-family: Arial, sans-serif;

            font-size: 11px;

            font-weight: 600;

            letter-spacing: 0.5px;

            color: #fff;

            transition:
                background 0.3s ease,
                color 0.3s ease;
        }

        .promo-button span {
            font-size: 14px;
        }

        .wedding-card:hover .promo-button {
            background: #fff;
            color: #5c1025;
        }


        /* =========================================
                                                                                           DECORATIVE FLOWERS
                                                                                        ========================================= */

        .decor-flower {
            position: absolute;

            color: rgba(230,
                    184,
                    111,
                    0.55);

            font-size: 30px;

            font-family: Georgia, serif;

            pointer-events: none;
        }

        .flower-one {
            left: -5px;
            top: 4px;

            transform: rotate(-25deg);
        }

        .flower-two {
            right: 0;
            bottom: 20px;

            font-size: 25px;

            transform: rotate(20deg);
        }


        /* =========================================
                                                                                           FESTIVE CARD
                                                                                        ========================================= */

        .festive-card {
            grid-column: 2;
            grid-row: 1;
        }

        /* .festive-card .promo-overlay {
                        background:
                            linear-gradient(90deg,
                                rgba(255, 222, 216, 0.96) 0%,
                                rgba(255, 222, 216, 0.88) 32%,
                                rgba(255, 222, 216, 0.30) 62%,
                                rgba(255, 222, 216, 0.03) 100%)
                    } */

        .festive-content {
            position: absolute;

            z-index: 2;

            left: 8%;

            top: 50%;

            transform: translateY(-50%);

            width: 65%;
        }

        .festive-content h3 {
            margin: 0 0 7px;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 21px;

            line-height: 1;

            font-weight: 500;

            color: #64142b;
        }

        .offer {
            display: flex;

            flex-direction: column;

            margin-bottom: 8px;
        }

        .offer small {
            font-family: Arial, sans-serif;

            font-size: 9px;

            letter-spacing: 1px;

            color: #7c5260;
        }

        .offer strong {
            font-family:
                Georgia,
                serif;

            font-size: 22px;

            line-height: 1;

            font-weight: 500;

            color: #79142c;
        }

        .small-button {
            display: inline-flex;

            align-items: center;

            gap: 10px;

            padding: 7px 10px;

            border: 1px solid rgba(100,
                    20,
                    40,
                    0.45);

            background: rgba(255,
                    255,
                    255,
                    0.35);

            color: #64142b;

            font-family: Arial, sans-serif;

            font-size: 10px;
            border-radius: 5px;

            font-weight: 700;

            letter-spacing: 0.4px;

            transition:
                background 0.3s ease,
                color 0.3s ease;
        }

        .small-button span {
            font-size: 12px;
        }

        .promo-card:hover .small-button {
            background: #64142b;

            color: #fff;
        }


        /* =========================================
                                                                                           COTTON CARD
                                                                                        ========================================= */

        .cotton-card {
            grid-column: 3;
            grid-row: 1;
        }

        /* .cotton-card .promo-overlay {
                        background:
                            linear-gradient(90deg,
                                rgba(215, 232, 238, 0.95) 0%,
                                rgba(215, 232, 238, 0.88) 32%,
                                rgba(215, 232, 238, 0.30) 62%,
                                rgba(215, 232, 238, 0.03) 100%);
                    } */

        .cotton-content {
            position: absolute;

            z-index: 2;

            left: 8%;

            top: 50%;

            transform: translateY(-50%);

            width: 72%;
        }

        .cotton-content h3 {
            margin: 0;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 19px;

            line-height: 1.02;

            font-weight: 600;

            color: #182b36;
        }

        .cotton-content p {
            margin: 8px 0 12px;

            font-family: Arial, sans-serif;

            font-size: 11px;

            color: #53656d;
        }


        /* =========================================
                                                                                           NEW ARRIVALS
                                                                                        ========================================= */

        .arrivals-card {
            grid-column: 2 / 4;
            grid-row: 2;
        }

        /* /* .arrivals-card .promo-overlay { */
        background: linear-gradient(90deg,
            rgba(255, 236, 216, 0.97) 0%,
            rgba(255, 236, 216, 0.85) 32%,
            rgba(255, 236, 216, 0.30) 62%,
            rgba(255, 236, 216, 0.03) 100%);
        }

        .arrivals-content {
            position: absolute;

            z-index: 2;

            left: 7%;

            top: 50%;

            transform: translateY(-50%);

            display: flex;

            align-items: center;

            gap: 20px;

            width: 88%;
        }

        .arrivals-content h3 {
            margin: 0;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 23px;

            line-height: 0.85;

            font-weight: 500;

            color: #641f18;
        }

        .arrivals-content p {
            margin: 0;

            font-family: Arial, sans-serif;

            font-size: 11px;

            line-height: 1.4;

            color: #765d50;
        }

        .arrivals-content .small-button {
            margin-left: 5px;

            flex-shrink: 0;
        }


        /* =========================================
                                                                                           TABLET
                                                                                        ========================================= */

        @media (max-width: 900px) {

            .promo-grid {
                height: 280px;

                grid-template-columns:
                    1fr 0.75fr;

                grid-template-rows:
                    1fr 1fr;
            }

            .wedding-card {
                grid-column: 1;
                grid-row: 1 / 3;
            }

            .festive-card {
                grid-column: 2;
                grid-row: 1;
            }

            .cotton-card {
                display: none;
            }

            .arrivals-card {
                grid-column: 2;
                grid-row: 2;
            }

            .wedding-content {
                width: 65%;
            }

        }


        /* =========================================
                                                                                           MOBILE
                                                                                        ========================================= */

        @media (max-width: 600px) {

            .promo-section {
                padding: 5px 12px;
            }

            .promo-grid {
                height: auto;

                display: grid;

                grid-template-columns: 1fr 1fr;

                grid-template-rows:
                    230px 125px 125px;

                gap: 7px;
            }

            /* Wedding */
            .wedding-card {
                grid-column: 1 / 3;
                grid-row: 1;
            }

            .wedding-content {
                left: 6%;

                width: 48%;
            }

            .wedding-content h2 {
                font-size: 29px;
            }

            .wedding-content p {
                margin: 12px 0;

                font-size: 9px;
            }


            /* Festive */
            .festive-card {
                grid-column: 1;
                grid-row: 2;
            }

            .festive-content h3 {
                font-size: 15px;
            }

            .offer strong {
                font-size: 17px;
            }


            /* Cotton */
            .cotton-card {
                display: block;

                grid-column: 2;
                grid-row: 2;
            }

            .cotton-content h3 {
                font-size: 14px;
            }

            .cotton-content p {
                font-size: 7px;

                margin: 5px 0 8px;
            }


            /* Arrivals */
            .arrivals-card {
                grid-column: 1 / 3;
                grid-row: 3;
            }

            .arrivals-content {
                gap: 10px;
            }

            .arrivals-content h3 {
                font-size: 18px;
            }

            .arrivals-content p {
                font-size: 8px;
            }

            .small-button {
                padding: 6px 8px;
                font-size: 6px;
            }

        }


        /* =========================================
                                                                                           VERY SMALL DEVICES
                                                                                        ========================================= */

        @media (max-width: 380px) {

            .promo-grid {
                grid-template-rows:
                    210px 115px 115px;
            }

            .wedding-content h2 {
                font-size: 25px;
            }

            .arrivals-content {
                gap: 7px;
            }

            .arrivals-content p {
                display: none;
            }

        }

        /* =========================================================
                                               MOBILE HERO BANNER
                                            ========================================================= */

        @media (max-width: 767px) {

            /* Hide text/content in mobile */
            .hero-content {
                display: none !important;
            }

            /* Keep Previous / Next buttons visible */
            .hero-arrow {
                display: flex !important;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;

                width: 30px;
                height: 30px;

                align-items: center;
                justify-content: center;

                border: none;
                border-radius: 50%;

                background: rgba(255, 255, 255, 0.42);
                color: #30000e;

                font-size: 20px;
                line-height: 1;

                cursor: pointer;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            }

            /* Previous button */
            .hero-prev {
                left: 10px;
            }

            /* Next button */
            .hero-next {
                right: 10px;
            }

            /* Hide slider dots */
            .hero-dots {
                display: none !important;
            }

            /* Hero section */
            .hero-banner {
                width: 94%;
                height: 30vh;
                min-height: 0;
                padding: 0;
                margin: 0;
                overflow: hidden;
                position: relative;
                margin: 10px;
            }

            /* Slides */
            .hero-slide {
                width: 100%;
                height: 30vh;
                min-height: 0;
                padding: 0;
                margin: 0;
                position: relative;
                overflow: hidden;
            }

            /* Banner image */
            .hero-image {
                width: 100%;
                height: 30vh;
                min-height: 0;
                max-height: none;
                display: block;
                object-fit: cover;
                object-position: center;
                border-radius: 20px;
            }

        }

        .wishlist.active {
            color: #a92d0f;
        }

        .wishlist.active .wishlist-icon {
            color: #a92d0f;
        }

        .wishlist.active .wishlist-icon {
            font-size: 18px;
        }
    </style>


    <!-- HERO BANNER -->
    <section class="hero-banner">

        @forelse($banners as $index => $banner)

            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">

                {{-- Banner Image --}}
                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title ?? 'Delicious Food' }}" class="hero-image">

                {{-- Dark overlay --}}
                <div class="hero-overlay"></div>

                <div class="hero-content">

                    {{-- Dynamic Banner Title --}}
                    @if($banner->title)
                        <span class="hero-small-title">
                            <i class="fas fa-fire"></i>
                            {{ strtoupper($banner->title) }}
                        </span>
                    @endif

                    <!-- <h1>
                                                        Good Food.<br>
                                                        <span>Bigger Moments.</span>
                                                    </h1>

                                                    <p>
                                                        From delicious biryanis to sizzling pizzas,<br>
                                                        discover your next favourite meal.
                                                    </p>


                                                    <div class="hero-buttons">

                                                        <a href="{{ $banner->link_url ?: url('/shop') }}" class="hero-btn hero-btn-primary">
                                                            ORDER NOW
                                                            <span>→</span>
                                                        </a>

                                                        <a href="{{ url('/shop') }}" class="hero-btn hero-btn-secondary">
                                                            EXPLORE MENU
                                                        </a>

                                                    </div>


                                                    <div class="hero-features">

                                                        <div class="hero-feature">
                                                            <div class="feature-icon">
                                                                <i class="fas fa-motorcycle"></i>
                                                            </div>
                                                            <div>
                                                                <strong>Fast Delivery</strong>
                                                                <small>At your doorstep</small>
                                                            </div>
                                                        </div>

                                                        <div class="hero-feature">
                                                            <div class="feature-icon">
                                                                <i class="fas fa-utensils"></i>
                                                            </div>
                                                            <div>
                                                                <strong>Fresh Food</strong>
                                                                <small>Made with love</small>
                                                            </div>
                                                        </div>

                                                        <div class="hero-feature">
                                                            <div class="feature-icon">
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                            <div>
                                                                <strong>Top Quality</strong>
                                                                <small>Every single bite</small>
                                                            </div>
                                                        </div>

                                                    </div> -->

                </div>

            </div>

        @empty

            {{-- Fallback when no banners exist --}}
            <div class="hero-slide active">

                <img src="{{ asset('images/bakasuran.jpg') }}" alt="BAKASURAN Food" class="hero-image">

                <div class="hero-overlay"></div>

                <div class="hero-content">
                    <span class="hero-small-title">
                        <i class="fas fa-fire"></i>
                        WELCOME TO BAKASURAN
                    </span>

                    <h1>
                        Good Food.<br>
                        <span>Bigger Moments.</span>
                    </h1>

                    <p>
                        Discover delicious food, exciting flavours<br>
                        and unforgettable dining experiences.
                    </p>

                    <div class="hero-buttons">
                        <a href="{{ url('/shop') }}" class="hero-btn hero-btn-primary">
                            ORDER NOW <span>→</span>
                        </a>

                        <!-- <a href="{{ url('/shop') }}" class="hero-btn hero-btn-secondary">
                                                            EXPLORE MENU
                                                        </a> -->
                    </div>
                </div>

            </div>

        @endforelse

        {{-- Slider Controls --}}
        @if($banners->count() > 1)

            <button class="hero-arrow hero-prev" type="button" aria-label="Previous banner">
                &#10094;
            </button>

            <button class="hero-arrow hero-next" type="button" aria-label="Next banner">
                &#10095;
            </button>

            <div class="hero-dots">
                @foreach($banners as $index => $banner)
                    <button type="button" class="hero-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"
                        aria-label="Go to banner {{ $index + 1 }}"></button>
                @endforeach
            </div>

        @endif

    </section>


    <section class="shop-category-section">

        <div class="category-heading">

            <div class="heading-line"></div>

            <div class="heading-content">
                <h2>Explore Food Categories</h2>
                <p>Discover your favourites, from street food to delicious feasts</p>
            </div>

            <div class="heading-line"></div>

        </div>

        <div class="category-wrapper">

            @forelse($categories as $category)

                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="category-card">

                    <div class="category-image">

                        @if($category->image)

                            <img src="{{ asset($category->image) }}" alt="{{ $category->title }}">

                        @else

                            <img src="{{ asset('website/images/category-placeholder.png') }}" alt="{{ $category->title }}">

                        @endif

                    </div>

                    <div class="category-info">

                        <div>

                            <h3>
                                {{ $category->title }}
                            </h3>

                            <p>
                                {{ $category->description ?? 'Discover Delicious Food' }}
                            </p>

                        </div>

                        <span class="category-arrow">
                            ›
                        </span>

                    </div>

                </a>

            @empty

                <div class="no-category">
                    <p>No food categories available. Check back soon for delicious new options!</p>
                </div>

            @endforelse

        </div>

    </section>




    <section class="promo-section">

        <div class="promo-grid">

            {{-- LEFT - SIGNATURE FOOD --}}
            <a href="{{ url('/shop') }}" class="promo-card wedding-card">

                <img src="{{ asset('website/images/signature-food.jpg') }}" alt="BAKASURAN Signature Food" />

                <div class="promo-overlay"></div>

                <!-- <div class="wedding-content">

                                <span class="decor-flower flower-one">✦</span>
                                <span class="decor-flower flower-two">✧</span>

                                <h2>
                                    Good Food.<br>
                                    Bigger Moments.
                                </h2>

                                <p>
                                    Big flavours<br>
                                    unforgettable cravings
                                </p>

                                <span class="promo-button">
                                    ORDER NOW
                                    <span>→</span>
                                </span>

                            </div> -->

            </a>


            {{-- RIGHT TOP - SPECIAL OFFERS --}}
            <a href="{{ url('/offers-deals') }}" class="promo-card festive-card">

                <img src="{{ asset('website/images/food-offers.jpg') }}" alt="Special Food Offers" />

                <div class="promo-overlay"></div>

                <!-- <div class="festive-content">

                                <h3>Feast Specials</h3>

                                <div class="offer">
                                    <small>DEALS UP TO</small>
                                    <strong>50% OFF</strong>
                                </div>

                                <span class="small-button">
                                    VIEW OFFERS
                                    <span>→</span>
                                </span>

                            </div> -->

            </a>


            {{-- RIGHT TOP - BURGERS --}}
            <a href="{{ url('/shop') }}" class="promo-card cotton-card">

                <img src="{{ asset('website/images/burger-special.jpg') }}" alt="BAKASURAN Burger Specials" />

                <div class="promo-overlay"></div>

                <!-- <div class="cotton-content">

                                <h3>
                                    Juicy Burgers<br>
                                    Big Flavours
                                </h3>

                                <p>
                                    Freshly made. Always delicious.
                                </p>

                                <span class="small-button">
                                    EXPLORE MENU
                                    <span>→</span>
                                </span>

                            </div> -->

            </a>


            {{-- RIGHT BOTTOM - NEW ARRIVALS --}}
            <a href="{{ url('/shop') }}" class="promo-card arrivals-card">

                <img src="{{ asset('website/images/food-favourites.jpg') }}" alt="BAKASURAN Food Favourites" />

                <div class="promo-overlay"></div>

                <!-- <div class="arrivals-content">

                                <h3>
                                    Cravings<br>
                                    Delivered
                                </h3>

                                <p>
                                    Discover your next<br>
                                    favourite meal
                                </p>

                                <span class="small-button">
                                    EXPLORE FOOD
                                    <span>→</span>
                                </span>

                            </div> -->

            </a>

        </div>

    </section>




    <!-- =========================
                                                                                             SWIPER CSS
                                                                                        ========================= -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">


    <style>
        /* =========================================
                                                                                               BESTSELLER SECTION
                                                                                            ========================================= */

        .bestseller-section {
            width: 100%;
            padding: 42px 24px 45px;
            background: #fffdf9;
            overflow: hidden;
        }


        /* =========================================
                                                                                               HEADER
                                                                                            ========================================= */

        .bestseller-header {
            max-width: 1180px;
            margin: 0 auto 20px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
        }


        /* =========================================
                                                                                               TITLE
                                                                                            ========================================= */

        .bestseller-title-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sun-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            color: red;
        }

        .trending-label {
            margin-bottom: 3px;
            font-family: Arial, sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            color: red;
        }

        .trending-label span {
            margin-right: 5px;
        }

        .bestseller-title-wrap h2 {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            line-height: 1;
            font-weight: 600;
            color: #1e1a17;
        }

        .bestseller-title-wrap p {
            margin: 5px 0 0;
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #77716c;
        }


        /* =========================================
                                                                                               FILTERS
                                                                                            ========================================= */

        .bestseller-filters {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn {
            height: 35px;
            padding: 0 15px;
            border: 1px solid #e2ddd7;
            border-radius: 20px;
            background: #fff;
            color: #4f4a45;
            font-family: Arial, sans-serif;
            font-size: 12px;
            cursor: pointer;

            transition:
                background 0.25s ease,
                color 0.25s ease,
                border-color 0.25s ease,
                transform 0.25s ease;
        }

        .filter-btn:hover {
            transform: translateY(-1px);
            border-color: red;
        }

        .filter-btn.active {
            background: red;
            border-color: red;
            color: #fff;
        }

        .view-all {
            margin-left: 12px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #302b27;
            white-space: nowrap;
        }

        .view-all span {
            font-size: 14px;
            transition: transform 0.25s ease;
        }

        .view-all:hover span {
            transform: translateX(4px);
        }


        /* =========================================
                                                                                               SLIDER
                                                                                            ========================================= */

        .bestseller-slider {
            width: 100%;
            max-width: 1480px;
            margin: 0 auto;
            overflow: hidden;
        }

        .bestseller-slider .swiper-wrapper {
            align-items: stretch;
        }

        .bestseller-slider .swiper-slide {
            height: auto;
        }


        /* =========================================
                                                                                               PRODUCT CARD
                                                                                            ========================================= */

        .product-card {
            width: 100%;
            min-width: 0;
            height: 100%;

            background: #fff;

            border: 1px solid #e7dfd7;
            border-radius: 9px;

            overflow: hidden;

            box-shadow:
                0 2px 8px rgba(70, 45, 20, 0.06);

            transition:
                transform 0.35s ease,
                box-shadow 0.35s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);

            box-shadow:
                0 12px 25px rgba(70, 45, 20, 0.13);
        }


        /* =========================================
                                                                                               PRODUCT IMAGE
                                                                                            ========================================= */

        .product-image {
            position: relative;
            width: 100%;
            aspect-ratio: 0.79;
            overflow: hidden;
            background: #eee8df;
        }

        .product-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: fill;

            transition: transform 0.55s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.045);
        }


        /* =========================================
                                                                                               BADGES
                                                                                            ========================================= */

        .product-badge {
            position: absolute;

            top: 7px;
            left: 7px;

            z-index: 3;

            padding: 4px 7px;

            border-radius: 4px;

            font-family: Arial, sans-serif;
            font-size: 7px;
            font-weight: 700;
        }

        .product-badge.bestseller {
            background: #f4e8cf;
            color: #735020;
        }

        .product-badge.new {
            background: #3ca94b;
            color: #fff;
        }


        /* =========================================
                                                                                               WISHLIST
                                                                                            ========================================= */

        .wishlist {
            position: absolute;

            top: 7px;
            right: 7px;

            z-index: 4;

            width: 25px;
            height: 25px;

            border: 0;
            border-radius: 50%;

            background: rgba(255, 255, 255, 0.94);

            box-shadow:
                0 2px 5px rgba(0, 0, 0, 0.12);

            color: #4b302f;

            font-size: 16px;
            line-height: 1;

            cursor: pointer;

            transition:
                transform 0.25s ease,
                background 0.25s ease;
        }

        .wishlist:hover {
            transform: scale(1.12);
            background: #fff;
        }


        /* =========================================
                                                                                               QUICK ADD
                                                                                            ========================================= */

        .quick-add {
            position: absolute;

            right: 7px;
            bottom: 7px;

            z-index: 4;

            width: 28px;
            height: 28px;

            border: 0;
            border-radius: 7px;

            background: #d71313;
            color: #fff;

            font-size: 20px;
            line-height: 1;

            cursor: pointer;

            box-shadow:
                0 3px 7px rgba(80, 0, 20, 0.25);

            transition:
                transform 0.25s ease,
                background 0.25s ease;
        }

        .quick-add:hover {
            transform: scale(1.08);
            background: #8a1235;
        }


        /* =========================================
                                                                                               DETAILS
                                                                                            ========================================= */

        .product-details {
            padding: 7px 8px 8px;
        }

        .product-details h3 {
            margin: 0 0 5px;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 18px;
            line-height: 1.15;
            font-weight: 600;

            color: #27221f;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =========================================
                                                                                               FABRIC TAG
                                                                                            ========================================= */

        .fabric-tag {
            display: inline-block;

            padding: 3px 6px;

            border-radius: 3px;

            background: #f4f0ea;
            color: #67615b;

            font-family: Arial, sans-serif;
            font-size: 12px;
        }


        /* =========================================
                                                                                               META
                                                                                            ========================================= */

        .product-meta {
            min-height: 23px;

            display: flex;
            align-items: center;

            gap: 4px;

            margin-top: 4px;
        }

        .stars {
            font-size: 13px;
            letter-spacing: -1px;
            color: #d99a16;
        }

        .rating {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #55504b;
            white-space: nowrap;
        }


        /* =========================================
                                                                                               COLOR DOTS
                                                                                            ========================================= */

        .color-dots {
            margin-left: auto;

            display: flex;
            align-items: center;

            gap: 8px;
        }

        .color-dots i {
            width: 7px;
            height: 7px;

            display: block;

            border-radius: 50%;

            border: 1px solid rgba(0, 0, 0, 0.12);

            background: #7d2744;
        }

        .color-dots i:nth-child(2) {
            background: #d8a0aa;
        }

        .color-dots i:nth-child(3) {
            background: #324c47;
        }

        .color-dots i:nth-child(4) {
            background: #d1a746;
        }


        /* =========================================
                                                                                               PRICE
                                                                                            ========================================= */

        .price-row {
            display: flex;
            align-items: center;

            gap: 5px;

            padding-top: 5px;

            border-top: 1px solid #eee8e1;
        }

        .price-row strong {
            font-family: Arial, sans-serif;

            font-size: 15px;
            font-weight: 700;

            color: #211d19;
        }

        .price-row del {
            font-family: Arial, sans-serif;

            font-size: 7px;

            color: #9b9690;
        }

        .discount {
            margin-left: auto;

            font-family: Arial, sans-serif;

            font-size: 11px;
            font-weight: 700;

            color: #c75c48;

            white-space: nowrap;
        }


        /* =========================================
                                                                                               SLIDER FOOTER
                                                                                            ========================================= */

        .bestseller-slider-footer {
            max-width: 1480px;
            margin: 18px auto 0;

            display: flex;
            align-items: center;
            gap: 20px;
        }

        .bestseller-progress {
            flex: 1;
        }

        .bestseller-scrollbar {
            height: 3px !important;
            background: #eadfd5 !important;
        }

        .bestseller-scrollbar .swiper-scrollbar-drag {
            background: #000 !important;
        }

        .bestseller-navigation {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bestseller-prev,
        .bestseller-next {
            position: static;

            width: 32px;
            height: 32px;

            margin: 0;

            border: 1px solid #ddd2c8;

            border-radius: 50%;

            background: #fff;

            color: #000;
        }

        .bestseller-prev::after,
        .bestseller-next::after {
            font-size: 12px;
            font-weight: 700;
        }


        /* =========================================
                                                                                               TABLET
                                                                                            ========================================= */

        @media (max-width: 1050px) {

            .bestseller-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .bestseller-filters {
                width: 100%;

                overflow-x: auto;

                padding-bottom: 5px;

                scrollbar-width: none;
            }

            .bestseller-filters::-webkit-scrollbar {
                display: none;
            }

            .bestseller-slider {
                padding: 0;
            }
        }


        /* =========================================
                                                                                               MOBILE
                                                                                            ========================================= */

        @media (max-width: 650px) {

            .bestseller-section {
                padding: 5px 12px;
            }

            .bestseller-header {
                margin-bottom: 15px;
            }

            .bestseller-title-wrap {
                gap: 9px;
            }

            .sun-icon {
                width: 35px;
                height: 35px;
                font-size: 27px;
            }

            .bestseller-title-wrap h2 {
                font-size: 21px;
            }

            .bestseller-title-wrap p {
                font-size: 9px;
            }

            .bestseller-filters {
                gap: 6px;
            }

            .filter-btn {
                flex-shrink: 0;

                height: 26px;

                padding: 0 13px;
            }

            .view-all {
                margin-left: 5px;
            }

            .bestseller-slider-footer {
                margin-top: 14px;
                gap: 10px;
            }

            .bestseller-prev,
            .bestseller-next {
                width: 29px;
                height: 29px;
            }
        }

        /* =========================================
                                                                                           WHY CHOOSE SUDHEERA
                                                                                        ========================================= */

        .why-sudheera {
            position: relative;

            /* width: 100%; */

            max-width: 1300px;

            margin: 10px auto;

            padding: 18px 28px 22px;

            overflow: hidden;

            border-radius: 14px;

            background:
                linear-gradient(90deg,
                    rgba(250, 231, 231, 0.95) 0%,
                    rgba(255, 245, 242, 0.98) 50%,
                    rgba(250, 231, 231, 0.95) 100%);

            border: 1px solid rgba(191,
                    132,
                    132,
                    0.12);
        }


        /* =========================================
                                                                                           HEADER
                                                                                        ========================================= */

        .why-header {
            position: relative;

            z-index: 2;

            text-align: center;

            margin-bottom: 13px;
        }

        .why-header h2 {
            margin: 0;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 25px;

            line-height: 1.2;

            font-weight: 600;

            color: #211c19;
        }

        .why-header p {
            margin: 2px 0 0;

            font-family: Arial, sans-serif;

            font-size: 12px;

            color: #514945;
        }


        /* =========================================
                                                                                           FEATURES
                                                                                        ========================================= */

        .why-features {
            position: relative;

            z-index: 2;

            width: 100%;

            display: grid;

            grid-template-columns:
                repeat(5, minmax(0, 1fr));

            gap: 8px;
        }


        /* =========================================
                                                                                           FEATURE CARD
                                                                                        ========================================= */

        .why-card {
            min-height: 43px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 9px;

            padding: 7px 9px;

            border-radius: 7px;

            background:
                rgba(255,
                    255,
                    255,
                    0.65);

            border: 1px solid rgba(255,
                    255,
                    255,
                    0.75);

            box-shadow:
                0 2px 8px rgba(90,
                    35,
                    40,
                    0.035);

            transition:
                transform 0.25s ease,
                background 0.25s ease,
                box-shadow 0.25s ease;
        }

        .why-card:hover {
            transform: translateY(-3px);

            background:
                rgba(255,
                    255,
                    255,
                    0.9);

            box-shadow:
                0 7px 18px rgba(90,
                    35,
                    40,
                    0.09);
        }


        /* =========================================
                                                                                           ICON
                                                                                        ========================================= */

        .why-icon {
            flex-shrink: 0;

            width: 27px;
            height: 27px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 20px;

            color: #6c1d31;
        }


        /* =========================================
                                                                                           CONTENT
                                                                                        ========================================= */

        .why-content {
            min-width: 0;
        }

        .why-content h3 {
            margin: 0;

            font-family: Arial, sans-serif;

            font-size: 15px;

            line-height: 1.2;

            font-weight: 700;

            color: #29221f;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }

        .why-content p {
            margin: 2px 0 0;

            font-family: Arial, sans-serif;

            font-size: 10px;

            line-height: 1.2;

            color: #77706b;

            white-space: nowrap;
        }


        /* =========================================
                                                                                           DECORATIVE FLOWERS
                                                                                        ========================================= */

        .why-decoration {
            position: absolute;

            z-index: 1;

            color: rgba(145,
                    90,
                    88,
                    0.18);

            font-size: 72px;

            line-height: 1;

            pointer-events: none;
        }

        .why-decoration-left {
            left: -18px;

            bottom: -27px;

            transform:
                rotate(-18deg);
        }

        .why-decoration-right {
            right: -18px;

            bottom: -27px;

            transform:
                rotate(18deg);
        }


        /* =========================================
                                                                                           TABLET
                                                                                        ========================================= */

        @media (max-width: 900px) {

            .why-sudheera {
                margin-left: 15px;
                margin-right: 15px;

                padding-left: 20px;
                padding-right: 20px;
            }

            .why-features {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

        }


        /* =========================================
                                                                                           MOBILE
                                                                                        ========================================= */

        @media (max-width: 600px) {

            .why-sudheera {
                margin: 25px 12px;

                padding: 18px 12px;

                border-radius: 12px;
            }

            .why-header {
                margin-bottom: 14px;
            }

            .why-header h2 {
                font-size: 17px;
            }

            .why-header p {
                font-size: 8px;
            }

            .why-features {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

                gap: 7px;
            }

            .why-card {
                min-height: 45px;

                justify-content: flex-start;

                padding: 7px 8px;

                gap: 7px;
            }

            .why-icon {
                width: 25px;
                height: 25px;

                font-size: 18px;
            }

            .why-content h3 {
                font-size: 8px;
            }

            .why-content p {
                font-size: 7px;
            }

        }


        /* =========================================
                                                                                           SMALL MOBILE
                                                                                        ========================================= */

        @media (max-width: 380px) {

            .why-features {
                grid-template-columns: 1fr;
            }

            .why-card {
                justify-content: center;
                margin-left: 50px;
                margin-right: 50px;
            }

        }

        /* =========================================
                                                                                           TESTIMONIALS SECTION
                                                                                        ========================================= */

        .testimonials-section {
            width: 100%;

            padding: 12px 20px 18px;

            background:
                linear-gradient(90deg,
                    rgba(255, 250, 246, 0.98) 0%,
                    rgba(255, 247, 244, 1) 50%,
                    rgba(255, 250, 246, 0.98) 100%);

            border-top: 1px solid rgba(105,
                    15,
                    35,
                    0.06);

            border-bottom: 1px solid rgba(105,
                    15,
                    35,
                    0.06);

            overflow: hidden;
        }


        /* =========================================
                                                                                           MAIN CONTAINER
                                                                                        ========================================= */

        .testimonials-container {
            position: relative;

            width: 100%;

            max-width: 1380px;

            min-height: 250px;

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                58% 42%;

            align-items: center;

            gap: 8px;
        }


        /* =========================================
                                                                                           CUSTOMER GALLERY
                                                                                        ========================================= */

        .customer-gallery {
            position: relative;

            min-width: 0;

            display: flex;

            align-items: center;

            gap: 7px;
        }

        .customer-photos {
            width: 100%;

            display: flex;

            align-items: center;

            gap: 5px;

            overflow: hidden;
        }


        /* =========================================
                                                                                           CUSTOMER PHOTO
                                                                                        ========================================= */

        /* Image scroll container */
        .customer-photos {
            display: flex;
            flex-wrap: nowrap;
            gap: 25px;

            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;

            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;

            padding: 5px 5px 15px;

            /* Hide scrollbar */
            scrollbar-width: none;
        }

        .customer-photos::-webkit-scrollbar {
            display: none;
        }


        /* Individual image */
        .customer-photo {
            flex: 0 0 calc((100% - 75px) / 4);

            min-width: calc((100% - 75px) / 4);

            height: 200px;

            overflow: hidden;

            border-radius: 25px;

            border: 1px solid rgba(100, 50, 40, 0.10);

            background: #eee;

            cursor: pointer;

            transition:
                transform 0.3s ease,
                opacity 0.3s ease,
                box-shadow 0.3s ease;
        }

        .customer-photo img {
            width: 100%;
            height: 120%;

            display: block;

            object-fit: fill;

            transition: transform 0.45s ease;
        }

        .customer-photo:hover {
            transform: translateY(-3px);

            box-shadow: 0 6px 14px rgba(80, 30, 30, 0.15);
        }

        .customer-photo:hover img {
            transform: scale(1.05);
        }

        .customer-photo.active {
            border-color: #73122e;

            box-shadow:
                0 0 0 1px rgba(115, 18, 46, 0.15);
        }


        /* Tablet */
        @media (max-width: 991px) {

            .customer-photo {
                flex: 0 0 calc((100% - 25px) / 2);
                min-width: calc((100% - 25px) / 2);
            }
        }


        /* Mobile */
        @media (max-width: 575px) {

            .customer-photos {
                gap: 15px;
            }

            .customer-photo {
                flex: 0 0 80%;
                min-width: 80%;
                height: 190px;
            }
        }






        /* =========================================
                                                                                           HEADING
                                                                                        ========================================= */

        .testimonial-content {
            min-width: 0;

            display: flex;

            flex-direction: column;

            align-items: center;
        }

        .testimonial-heading {
            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            margin-bottom: 5px;
        }

        .heading-heart {
            font-size: 16px;

            color: #c42c5a;

            line-height: 1;
        }

        .testimonial-heading h2 {
            margin: 0;

            font-family:
                Georgia,
                "Times New Roman",
                serif;

            font-size: 25px;

            line-height: 1;

            font-weight: 600;

            color: #27201d;
        }


        /* =========================================
                                                                                           TESTIMONIAL CARD
                                                                                        ========================================= */

        .testimonial-card {
            width: 100%;

            max-width: 400px;

            min-height: 69px;

            display: flex;

            align-items: flex-start;

            gap: 7px;

            padding: 9px 14px 8px;

            border-radius: 8px;

            background:
                rgba(255,
                    255,
                    255,
                    0.88);

            border: 1px solid rgba(100,
                    55,
                    45,
                    0.08);

            box-shadow:
                0 3px 14px rgba(70,
                    25,
                    30,
                    0.07);
        }


        /* =========================================
                                                                                           QUOTE
                                                                                        ========================================= */

        .quote-mark {
            flex-shrink: 0;

            margin-top: -2px;

            font-family:
                Georgia,
                serif;

            font-size: 27px;

            line-height: 1;

            font-weight: 700;

            color: #a98145;
        }


        /* =========================================
                                                                                           REVIEW
                                                                                        ========================================= */

        .testimonial-text {
            min-width: 0;
        }

        .testimonial-text p {
            margin: 0;

            font-family:
                Arial,
                sans-serif;

            font-size: 15px;

            line-height: 1.45;

            color: #3e3733;
        }


        /* =========================================
                                                                                           STARS
                                                                                        ========================================= */

        .testimonial-stars {
            margin-top: 4px;

            font-size: 15px;

            letter-spacing: 1px;

            color: #d29a21;
        }


        /* =========================================
                                                                                           CUSTOMER INFO
                                                                                        ========================================= */

        .customer-info {
            margin-top: 2px;

            display: flex;

            align-items: center;

            gap: 5px;
        }

        .customer-info strong {
            font-family: Arial, sans-serif;

            font-size: 14px;

            font-weight: 700;

            color: #322a26;
        }

        .customer-info span {
            position: relative;

            padding-left: 5px;

            font-family: Arial, sans-serif;

            font-size: 12px;

            color: #817872;
        }

        .customer-info span::before {
            content: "•";

            position: absolute;

            left: 0;

            color: #b3a49b;
        }


        /* =========================================
                                                                                           ARROWS
                                                                                        ========================================= */

        .testimonial-arrow {
            flex-shrink: 0;

            width: 26px;
            height: 26px;

            display: flex;

            align-items: center;
            justify-content: center;

            border: 0;

            border-radius: 50%;

            background: transparent;

            color: #3c312c;

            font-size: 25px;

            line-height: 1;

            cursor: pointer;

            transition:
                background 0.25s ease,
                color 0.25s ease,
                transform 0.25s ease;
        }

        .testimonial-arrow:hover {
            background: rgba(115,
                    18,
                    46,
                    0.08);

            color: #73122e;
        }

        .testimonial-arrow:active {
            transform: scale(0.9);
        }

        .gallery-prev {
            margin-right: 2px;
        }

        .testimonial-next {
            position: absolute;

            right: -5px;

            top: 50%;

            transform:
                translateY(12px);
        }

        .product-title-link {
            color: inherit;
            text-decoration: none !important;
        }

        .product-title-link:hover {
            color: #650019;
            text-decoration: none !important;
        }

        .product-title-link h3 {
            text-decoration: none !important;
        }

        /* =========================================
                                                                                           TABLET
                                                                                        ========================================= */

        @media (max-width: 900px) {

            .testimonials-container {
                grid-template-columns:
                    1fr;

                gap: 12px;
            }

            .customer-gallery {
                width: 100%;
            }

            .testimonial-content {
                width: 100%;
            }

            .testimonial-card {
                max-width: 600px;
            }

            .testimonial-next {
                right: 0;

                top: 50%;

                transform:
                    translateY(-50%);
            }

        }


        /* =========================================
                                                                                           MOBILE
                                                                                        ========================================= */

        /* =========================================================
                                                TESTIMONIALS - MOBILE RESPONSIVE
                                             ========================================================= */

        @media (max-width: 600px) {

            /* Main Section */
            .testimonials-section {
                width: 100%;
                padding: 30px 12px 35px;
                overflow: hidden;
                background: #fffdf9;
            }

            /* Main Container */
            .testimonials-container {
                width: 100%;
                min-height: auto;
                display: flex;
                flex-direction: column;
                gap: 15px;
                position: relative;
            }

            /* =====================================================
                                                    CUSTOMER GALLERY
                                                 ===================================================== */

            .customer-gallery {
                width: 100%;
                position: relative;
            }

            /* Customer Images - Horizontal Scroll */
            .customer-photos {
                width: 100%;
                display: flex;
                gap: 9px;
                overflow-x: auto;
                overflow-y: hidden;
                padding: 5px 3px 10px;

                scroll-behavior: smooth;
                scroll-snap-type: x mandatory;

                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .customer-photos::-webkit-scrollbar {
                display: none;
            }

            /* Customer Image */
            .customer-photo {
                flex: 0 0 72px;
                width: 72px;
                min-width: 72px;
                height: 72px;

                border-radius: 50%;
                overflow: hidden;

                scroll-snap-align: center;

                border: 2px solid transparent;

                background: #f5eee7;

                transition:
                    transform 0.25s ease,
                    border-color 0.25s ease,
                    box-shadow 0.25s ease;
            }

            .customer-photo img {
                width: 100%;
                height: 100%;
                display: block;
                object-fit: cover;
            }

            /* Active Customer */
            .customer-photo.active {
                border-color: #9b6b32;

                transform: scale(1.06);

                box-shadow:
                    0 4px 12px rgba(100, 55, 30, 0.18);
            }

            /* Previous Gallery Button */
            .gallery-prev {
                display: none !important;
            }

            /* =====================================================
                                                    TESTIMONIAL CONTENT
                                                 ===================================================== */

            .testimonial-content {
                width: 100%;
                min-width: 0;

                display: flex;
                flex-direction: column;
                align-items: center;
            }

            /* Heading */
            .testimonial-heading {
                width: 100%;

                display: flex;
                align-items: center;
                justify-content: center;

                gap: 6px;
                margin: 0 0 12px;
            }

            .heading-heart {
                font-size: 17px;
                color: #c42c5a;
            }

            .testimonial-heading h2 {
                margin: 0;

                font-family: Georgia, "Times New Roman", serif;

                font-size: 21px;
                line-height: 1.2;

                font-weight: 600;

                color: #27201d;

                text-align: center;
            }

            /* =====================================================
                                                    TESTIMONIAL CARD
                                                 ===================================================== */

            .testimonial-card {
                width: 100%;
                max-width: 100%;

                min-height: auto;

                display: flex;
                align-items: flex-start;

                gap: 8px;

                padding: 15px 14px;

                border-radius: 12px;

                background: rgba(255, 255, 255, 0.95);

                border: 1px solid #eadfd5;

                box-shadow:
                    0 4px 16px rgba(70, 25, 30, 0.08);
            }

            /* Quote */
            .quote-mark {
                flex-shrink: 0;

                margin-top: -3px;

                font-size: 27px;
                line-height: 1;

                color: #a98145;
            }

            /* Review */
            .testimonial-text {
                min-width: 0;
                width: 100%;
            }

            .testimonial-text p {
                margin: 0;

                font-family: Arial, sans-serif;

                font-size: 12px;
                line-height: 1.55;

                color: #3e3733;
            }

            /* Stars */
            .testimonial-stars {
                margin-top: 7px;

                font-size: 12px;
                letter-spacing: 1px;

                color: #d29a21;
            }

            /* Customer Name */
            .customer-info {
                margin-top: 5px;

                display: flex;
                align-items: center;

                gap: 5px;
                flex-wrap: wrap;
            }

            .customer-info strong {
                font-size: 11px;
            }

            .customer-info span {
                font-size: 10px;
            }

            /* =====================================================
                                                    NEXT BUTTON
                                                 ===================================================== */

            .testimonial-next {
                position: absolute;

                right: 5px;
                top: 28px;

                width: 32px;
                height: 32px;

                display: flex;
                align-items: center;
                justify-content: center;

                border: 1px solid rgba(155, 107, 50, 0.25);

                border-radius: 50%;

                background: rgba(255, 255, 255, 0.95);

                color: #6d3b22;

                font-size: 18px;

                cursor: pointer;

                z-index: 5;

                box-shadow:
                    0 3px 10px rgba(70, 30, 20, 0.12);
            }

            .testimonial-next:active {
                transform: scale(0.94);
            }
        }


        /* =========================================================
                                                VERY SMALL MOBILE
                                             ========================================================= */

        @media (max-width: 380px) {

            .testimonials-section {
                padding: 25px 10px 30px;
            }

            .customer-photos {
                gap: 7px;
            }

            .customer-photo {
                flex: 0 0 62px;
                width: 62px;
                min-width: 62px;
                height: 62px;
            }

            .testimonial-heading h2 {
                font-size: 18px;
            }

            .testimonial-card {
                padding: 13px 11px;
            }

            .testimonial-text p {
                font-size: 11px;
                line-height: 1.5;
            }

            .testimonial-next {
                width: 29px;
                height: 29px;
                font-size: 16px;
                display: none;
            }

        }
    </style>


    <style>
        /* Restaurant name on product card */
        .product-restaurant {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-top: 8px;
            color: #E85D04;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.5;
        }

        .product-restaurant i {
            font-size: 13px;
            flex-shrink: 0;
        }

        .product-restaurant span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>


    <!-- =========================================================
                                                                                             BESTSELLER SECTION
                                                                                        ========================================================= -->

    <section class="bestseller-section">

        {{-- HEADER --}}
        <div class="bestseller-header">

            <div class="bestseller-title-wrap">

                <div class="sun-icon">
                    ☀
                </div>

                <div>

                    <div class="trending-label">
                        <span>♨</span>
                        TRENDING NOW
                    </div>

                    <h2>
                        Bestselling Foods
                    </h2>

                    <p>
                        Loved by food lovers, freshly prepared for you
                    </p>

                </div>

            </div>

            {{-- FILTER BUTTONS --}}
            <div class="bestseller-filters">

                <button type="button" class="filter-btn active" data-category="all">
                    All
                </button>

                <button type="button" class="filter-btn" data-category="biryani">
                    Biryani
                </button>

                <button type="button" class="filter-btn" data-category="burger">
                    Burgers
                </button>

                <button type="button" class="filter-btn" data-category="pizza">
                    Pizzas
                </button>

                <button type="button" class="filter-btn" data-category="dessert">
                    Desserts
                </button>

                <a href="{{ route('shop') }}" class="view-all">
                    View All
                    <span>→</span>
                </a>

            </div>

        </div>

        {{-- ALL PRODUCTS SLIDER --}}
        <div class="bestseller-slider swiper" id="bestseller-all">

            <div class="swiper-wrapper">

                @forelse($products as $product)

                    @php

                        // VARIANT
                        $variant = $product->variant;

                        // PRICE
                        $sellingPrice = $variant?->price;
                        $actualPrice = $variant?->actual_price;

                        // DISCOUNT
                        $discount = 0;

                        if (
                            is_numeric($actualPrice) &&
                            is_numeric($sellingPrice) &&
                            $actualPrice > 0 &&
                            $actualPrice > $sellingPrice
                        ) {
                            $discount = round(
                                (($actualPrice - $sellingPrice) / $actualPrice) * 100
                            );
                        }

                        // PRODUCT IMAGE
                        $productImage = null;

                        if ($variant && !empty($variant->image)) {

                            $productImage = $variant->image;

                        } elseif (!empty($product->image)) {

                            $productImage = $product->image;

                        }

                        // CATEGORY FILTER
                        $categoryTitle = strtolower(
                            $product->category?->title ?? ''
                        );

                        $categorySlug = strtolower(
                            $product->category?->slug ?? ''
                        );

                        $filterText =
                            $categoryTitle . ' ' .
                            $categorySlug . ' ' .
                            strtolower($product->title ?? '');

                        // BADGE
                        $badge = null;

                        if ($loop->iteration <= 2) {
                            $badge = 'Bestseller';
                        }

                    @endphp

                    <div class="swiper-slide bestseller-product-slide" data-category="{{ $filterText }}">

                        <article class="product-card">

                            {{-- PRODUCT IMAGE --}}
                            <div class="product-image">

                                <a href="{{ route('productdetails', ['slug' => $product->slug]) }}">

                                    @if($productImage)

                                        <img src="{{ asset($productImage) }}" alt="{{ $product->title }}">

                                    @else

                                        <img src="{{ asset('website/images/product-placeholder.png') }}"
                                            alt="{{ $product->title }}">

                                    @endif

                                </a>

                                @if($badge)

                                    <span class="product-badge bestseller">
                                        {{ $badge }}
                                    </span>

                                @endif

                                {{-- WISHLIST --}}
                                <button type="button"
                                    class="wishlist {{ $variant && in_array($variant->id, $wishlistVariantIds ?? []) ? 'active' : '' }}"
                                    data-variant-id="{{ $variant?->id }}" onclick="event.stopPropagation();">

                                    <span class="wishlist-icon">
                                        {{ $variant && in_array($variant->id, $wishlistVariantIds ?? []) ? '♥' : '♡' }}
                                    </span>

                                </button>

                                {{-- QUICK ADD --}}
                                <button type="button" class="quick-add" data-product-id="{{ $product->id }}"
                                    data-variant-id="{{ $product->variant?->id }}" onclick="event.stopPropagation();">
                                    +
                                </button>

                            </div>

                            {{-- PRODUCT DETAILS --}}
                            <div class="product-details">

                                <a href="{{ route('productdetails', ['slug' => $product->slug]) }}" class="product-title-link">

                                    <h3>
                                        {{ $product->title }}
                                    </h3>

                                </a>

                                {{-- CATEGORY --}}
                                @if($product->category)

                                    <span class="fabric-tag">
                                        {{ $product->category->title }}
                                    </span>

                                @endif

                                {{-- RESTAURANT NAME --}}
                                @if($product->restaurant)
                                    <div class="product-restaurant">
                                        <i class="fa fa-cutlery" aria-hidden="true"></i>
                                        <span>{{ $product->restaurant->name }}</span>
                                    </div>
                                @endif

                                {{-- PRODUCT META --}}
                                <div class="product-meta">

                                    <div class="stars">
                                        ★★★★★
                                    </div>

                                    <span class="rating">
                                        Customer Favourite
                                    </span>

                                    <div class="color-dots">

                                        @if($variant)

                                            <i></i>
                                            <i></i>
                                            <i></i>

                                        @endif

                                    </div>

                                </div>

                                {{-- PRICE --}}
                                <div class="price-row">

                                    @if(is_numeric($sellingPrice))

                                        <strong>
                                            ₹{{ number_format((float) $sellingPrice, 0) }}
                                        </strong>

                                    @endif

                                    @if(
                                            is_numeric($actualPrice) &&
                                            is_numeric($sellingPrice) &&
                                            $actualPrice > $sellingPrice
                                        )

                                        <del>
                                            ₹{{ number_format((float) $actualPrice, 0) }}
                                        </del>

                                    @endif

                                    @if($discount > 0)

                                        <span class="discount">
                                            {{ $discount }}% OFF
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </article>

                    </div>

                @empty

                    <div class="no-products">

                        <p>
                            No food products available right now.
                        </p>

                    </div>

                @endforelse

            </div>

            {{-- SLIDER CONTROLS --}}
            <div class="bestseller-slider-footer">

                <div class="bestseller-progress">

                    <div class="swiper-scrollbar bestseller-scrollbar" style="width: 92%;"></div>

                </div>

                <div class="bestseller-navigation">

                    <div class="swiper-button-prev bestseller-prev"></div>

                    <div class="swiper-button-next bestseller-next"></div>

                </div>

            </div>

        </div>

    </section>


    <!-- 
        <section class="why-sudheera">

                {{-- Decorative elements --}}
                <div class="why-decoration why-decoration-left">
                    ✦
                </div>

                <div class="why-decoration why-decoration-right">
                    ✧
                </div>

                {{-- Heading --}}
                <div class="why-header">

                    <h2>
                        Why Choose BAKASURAN?
                    </h2>

                    <p>
                        Great food, great service, unforgettable moments
                    </p>

                </div>

                {{-- Features --}}
                <div class="why-features">

                    {{-- Fresh Food --}}
                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fa-solid fa-utensils"></i>
                        </div>

                        <div class="why-content">

                            <h3>
                                Fresh & Delicious
                            </h3>

                            <p>
                                Made fresh, served with love
                            </p>

                        </div>

                    </div>

                    {{-- Restaurant Variety --}}
                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fa-solid fa-bowl-food"></i>
                        </div>

                        <div class="why-content">

                            <h3>
                                Multiple Cuisines
                            </h3>

                            <p>
                                Explore flavours from everywhere
                            </p>

                        </div>

                    </div>

                    {{-- Easy Ordering --}}
                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fa-solid fa-mobile-screen-button"></i>
                        </div>

                        <div class="why-content">

                            <h3>
                                Easy Online Ordering
                            </h3>

                            <p>
                                Your favourite food in a few clicks
                            </p>

                        </div>

                    </div>

                    {{-- Fast Delivery --}}
                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fa-solid fa-motorcycle"></i>
                        </div>

                        <div class="why-content">

                            <h3>
                                Fast Food Delivery
                            </h3>

                            <p>
                                Deliciousness delivered to your door
                            </p>

                        </div>

                    </div>

                    {{-- Secure Payments --}}
                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>

                        <div class="why-content">

                            <h3>
                                Secure Payments
                            </h3>

                            <p>
                                Convenient and secure checkout
                            </p>

                        </div>

                    </div>

                </div>

        </section> -->


    <section class="bakasuran-why-section" aria-label="Why Choose BAKASURAN">

        <img src="{{ asset('website/images/whychoose.png') }}"
            alt="Why Choose BAKASURAN — Fresh and delicious food, multiple cuisines, easy online ordering, fast food delivery and secure payments."
            class="bakasuran-why-banner" loading="lazy">

    </section>

    <style>
        /* ==========================================
           BAKASURAN CINEMATIC WHY CHOOSE SECTION
        ========================================== */


        .bakasuran-why-section {
            width: 90%;
            max-width: 1400px;
            margin: 20px auto;
            overflow: hidden;
            background: #080202;
            border-radius: 12px;
            /* height: 360px; */
        }

        .bakasuran-why-banner {
            display: block;
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Tablet */
        @media (max-width: 991px) {
            .bakasuran-why-section {
                width: 95%;
            }
        }

        /* Mobile */
        @media (max-width: 576px) {
            .bakasuran-why-section {
                width: 100%;
                margin: 10px auto;
                border-radius: 0;
            }
        }
    </style>



    {{-- ==========================================
    DYNAMIC CUSTOMER TESTIMONIALS
    ========================================== --}}

    <section class="testimonials-section">
        <div class="testimonials-container">

            {{-- LEFT: CUSTOMER PHOTOS --}}
            <div class="customer-gallery">

                <button class="testimonial-arrow gallery-prev" type="button" aria-label="Previous customers">
                    ←
                </button>

                <div class="customer-photos">

                    @foreach($testimonials as $index => $testimonial)
                        <div class="customer-photo {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">

                            @if($testimonial->image)
                                <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}" loading="lazy">
                            @else
                                <div class="testimonial-photo-placeholder">
                                    {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>
            </div>

            {{-- CENTER / RIGHT: CUSTOMER REVIEWS --}}
            <div class="testimonial-content">

                <div class="testimonial-heading">
                    <span class="heading-heart">♥</span>
                    <h2>Our Happy Foodies</h2>
                </div>

                <div class="testimonial-card">

                    <div class="quote-mark">“</div>

                    <div class="testimonial-text">

                        <p id="testimonialText">
                            {{ $testimonials->first()->description ?? 'Our customers make BAKASURAN special. Be the first to share your experience!' }}
                        </p>

                        <div class="testimonial-stars" id="testimonialStars" aria-label="Customer rating">
                            @if($testimonials->isNotEmpty())
                                {{ str_repeat('★', (int) $testimonials->first()->rating) }}
                            @endif
                        </div>

                        <div class="customer-info">

                            <strong id="customerName">
                                {{ $testimonials->first()->name ?? 'Our Customer' }}
                            </strong>

                            <span id="customerLocation">
                                {{ $testimonials->first()->city ?? '' }}
                            </span>

                        </div>

                    </div>
                </div>
            </div>

            {{-- NEXT ARROW --}}
            <button class="testimonial-arrow testimonial-next" type="button" aria-label="Next testimonial">
                →
            </button>

        </div>
    </section>




    <!-- =========================
                                                                                             SWIPER JS
                                                                                        ========================= -->

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            /* =====================================================
               BESTSELLER SWIPER
            ====================================================== */

            const bestsellerElement =
                document.querySelector("#bestseller-all");

            if (!bestsellerElement) {
                return;
            }


            const bestsellerSwiper = new Swiper(
                "#bestseller-all",
                {
                    slidesPerView: 6,

                    spaceBetween: 10,

                    speed: 600,

                    watchOverflow: true,

                    navigation: {
                        nextEl: ".bestseller-next",
                        prevEl: ".bestseller-prev"
                    },

                    scrollbar: {
                        el: ".bestseller-scrollbar",
                        draggable: true
                    },

                    breakpoints: {

                        0: {
                            slidesPerView: 2,
                            spaceBetween: 9
                        },

                        400: {
                            slidesPerView: 2,
                            spaceBetween: 9
                        },

                        651: {
                            slidesPerView: 3,
                            spaceBetween: 10
                        },

                        1051: {
                            slidesPerView: 4,
                            spaceBetween: 10
                        },

                        1300: {
                            slidesPerView: 5,
                            spaceBetween: 10
                        },

                        1450: {
                            slidesPerView: 6,
                            spaceBetween: 10
                        }

                    }
                }
            );


            /* =====================================================
               FILTER BUTTONS
            ====================================================== */

            const filterButtons =
                document.querySelectorAll(
                    ".bestseller-filters .filter-btn"
                );


            filterButtons.forEach(function (button) {

                button.addEventListener("click", function () {

                    /* Remove active */

                    filterButtons.forEach(function (btn) {

                        btn.classList.remove("active");

                    });


                    /* Add active */

                    this.classList.add("active");


                    /* Selected category */

                    const selectedCategory =
                        (
                            this.getAttribute("data-category") || "all"
                        ).toLowerCase();


                    /* =================================================
                       ALL PRODUCTS
                    ================================================== */

                    const allSlides =
                        document.querySelectorAll(
                            "#bestseller-all .swiper-slide"
                        );


                    /* =================================================
                       FILTER SLIDES
                    ================================================== */

                    allSlides.forEach(function (slide) {

                        const slideCategory =
                            (
                                slide.getAttribute("data-category") || ""
                            ).toLowerCase();


                        if (
                            selectedCategory === "all" ||
                            slideCategory.includes(selectedCategory)
                        ) {

                            slide.style.display = "";

                        } else {

                            slide.style.display = "none";

                        }

                    });


                    /* =================================================
                       UPDATE SWIPER
                    ================================================== */

                    bestsellerSwiper.update();

                    bestsellerSwiper.slideTo(0, 0);

                });

            });

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const slides = document.querySelectorAll(".hero-slide");
            const dots = document.querySelectorAll(".hero-dot");

            const prevButton = document.querySelector(".hero-prev");
            const nextButton = document.querySelector(".hero-next");

            let currentSlide = 0;
            let autoSlide;

            function showSlide(index) {

                // Loop slides
                if (index >= slides.length) {
                    currentSlide = 0;
                } else if (index < 0) {
                    currentSlide = slides.length - 1;
                } else {
                    currentSlide = index;
                }

                // Remove active class
                slides.forEach(function (slide) {
                    slide.classList.remove("active");
                });

                dots.forEach(function (dot) {
                    dot.classList.remove("active");
                });

                // Add active class
                slides[currentSlide].classList.add("active");
                dots[currentSlide].classList.add("active");
            }


            // Next button
            nextButton.addEventListener("click", function () {

                showSlide(currentSlide + 1);

                resetAutoSlide();

            });


            // Previous button
            prevButton.addEventListener("click", function () {

                showSlide(currentSlide - 1);

                resetAutoSlide();

            });


            // Dots
            dots.forEach(function (dot, index) {

                dot.addEventListener("click", function () {

                    showSlide(index);

                    resetAutoSlide();

                });

            });


            // Automatic slide
            function startAutoSlide() {

                autoSlide = setInterval(function () {

                    showSlide(currentSlide + 1);

                }, 5000);

            }


            // Reset automatic slider
            function resetAutoSlide() {

                clearInterval(autoSlide);

                startAutoSlide();

            }


            // Start slider
            showSlide(0);
            startAutoSlide();

        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load testimonial data prepared by PageControllers.php
            const testimonials = {{ Illuminate\Support\Js::from($testimonialCarousel) }};

            let currentTestimonial = 0;
            let autoSlide = null;

            const testimonialText = document.getElementById('testimonialText');
            const customerName = document.getElementById('customerName');
            const customerLocation = document.getElementById('customerLocation');
            const testimonialStars = document.getElementById('testimonialStars');

            const customerPhotos = Array.from(
                document.querySelectorAll('.customer-photo')
            );

            const nextButton = document.querySelector('.testimonial-next');
            const prevButton = document.querySelector('.gallery-prev');

            // Hide arrows only when there are no testimonials.
            if (testimonials.length === 0) {
                if (nextButton) nextButton.style.display = 'none';
                if (prevButton) prevButton.style.display = 'none';

                if (testimonialText) {
                    testimonialText.textContent =
                        'No approved customer reviews available yet.';
                }

                if (customerName) customerName.textContent = 'BAKASURAN';
                if (customerLocation) customerLocation.textContent = '';
                if (testimonialStars) testimonialStars.textContent = '';

                customerPhotos.forEach(function (photo) {
                    photo.classList.remove('active');
                });

                return;
            }

            // Show arrows when there are testimonials.
            if (nextButton) nextButton.style.display = '';
            if (prevButton) prevButton.style.display = '';

            // Update the currently displayed testimonial.
            function showTestimonial(index, shouldScroll = false) {
                if (testimonials.length === 0) return;

                // Wrap around at the beginning and end.
                currentTestimonial =
                    (index + testimonials.length) % testimonials.length;

                const item = testimonials[currentTestimonial];

                if (testimonialText) {
                    testimonialText.style.opacity = '0';
                }

                window.setTimeout(function () {
                    if (testimonialText) {
                        testimonialText.textContent = item.text || '';
                        testimonialText.style.opacity = '1';
                    }

                    if (customerName) {
                        customerName.textContent = item.name || 'Customer';
                    }

                    if (customerLocation) {
                        customerLocation.textContent = item.location || '';
                    }

                    if (testimonialStars) {
                        const rating = Math.max(
                            0,
                            Math.min(5, Number(item.rating) || 0)
                        );

                        testimonialStars.textContent = '★'.repeat(rating);
                        testimonialStars.setAttribute(
                            'aria-label',
                            rating + ' out of 5 stars'
                        );
                    }
                }, 180);

                // Highlight the matching customer photo, if available.
                customerPhotos.forEach(function (photo, photoIndex) {
                    photo.classList.toggle(
                        'active',
                        photoIndex === currentTestimonial
                    );
                });

                // Scroll the selected photo into view after manual navigation.
                if (shouldScroll && customerPhotos[currentTestimonial]) {
                    customerPhotos[currentTestimonial].scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                }
            }

            // Start or restart the automatic carousel.
            function startAutoSlide() {
                window.clearInterval(autoSlide);
                autoSlide = null;

                if (testimonials.length > 1) {
                    autoSlide = window.setInterval(function () {
                        showTestimonial(currentTestimonial + 1);
                    }, 5000);
                }
            }

            function restartAutoSlide() {
                startAutoSlide();
            }

            // Next arrow.
            if (nextButton) {
                nextButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    showTestimonial(currentTestimonial + 1, true);
                    restartAutoSlide();
                });
            }

            // Previous arrow.
            if (prevButton) {
                prevButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    showTestimonial(currentTestimonial - 1, true);
                    restartAutoSlide();
                });
            }

            // Click a customer photo to display that customer's review.
            customerPhotos.forEach(function (photo, index) {
                photo.style.cursor = 'pointer';

                photo.addEventListener('click', function () {
                    if (index < testimonials.length) {
                        showTestimonial(index, true);
                        restartAutoSlide();
                    }
                });
            });

            if (testimonialText) {
                testimonialText.style.transition = 'opacity 0.18s ease';
            }

            // Display the first testimonial and start auto-sliding.
            showTestimonial(0);
            startAutoSlide();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.wishlist').forEach(function (button) {

                button.addEventListener('click', function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    const wishlistButton = this;
                    const variantId = wishlistButton.getAttribute('data-variant-id');

                    if (!variantId) {
                        alert('Product variant not found.');
                        return;
                    }

                    fetch('{{ route('wishlist.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_variant_id: variantId
                        })
                    })
                        .then(async function (response) {

                            const data = await response.json();

                            if (response.status === 401) {
                                window.location.href = '{{ route('login') }}';
                                return;
                            }

                            if (!response.ok) {
                                throw new Error(
                                    data.message || 'Something went wrong.'
                                );
                            }

                            if (data.status) {

                                // Change wishlist button to active
                                wishlistButton.classList.add('active');

                                // Change ♡ to ♥
                                const icon = wishlistButton.querySelector('.wishlist-icon');

                                if (icon) {
                                    icon.textContent = '♥';
                                }

                                alert(data.message);
                            }

                        })
                        .catch(function (error) {

                            console.error('Wishlist Error:', error);

                            alert(
                                error.message ||
                                'Something went wrong.'
                            );

                        });

                });

            });

        });
    </script>
@endsection