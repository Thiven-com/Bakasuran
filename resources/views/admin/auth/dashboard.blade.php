<?php $page = 'index'; ?>
@extends('layout.mainlayout')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    <div style="background:linear-gradient(135deg,#76001f,#4f8dfd);padding:35px;border-radius:18px;display:flex;justify-content:space-between;align-items:center;color:#fff;box-shadow:0 10px 30px rgba(13,110,253,.25);margin-top: 100px;
                            margin-left: 270px; margin-bottom:-150px; margin-right: 20px;">

        <div>
            <h2 style="margin:0;font-size:32px;font-weight:700;">
                👋 Welcome, Admin
            </h2>

            <p style="margin:10px 0 0;font-size:16px;opacity:.9;">
                Manage your dashboard, monitor enquiries, and keep everything organized in one place.
            </p>
        </div>

        <div
            style="width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:42px;">
            <i class="fas fa-user"></i>
        </div>

    </div>


    <div class="dashboard-wrapper">
        <div class="row g-4 w-100" style="margin-left: 250px;     justify-content: start;">

            <div class="col-lg-4 col-md-5 col-sm-6">
                <div class="dashboard-card primary">
                    <div class="card-content">
                        <div>
                            <span class="card-title">Contact Enquiries</span>
                            <h2 class="card-count">#</h2>
                        </div>

                        <div class="card-icon">
                            <i class="ti ti-address-book"></i>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>


    <style>
        /* Wrapper */
        .dashboard-wrapper {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* Card */
        .dashboard-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: .3s ease;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, .12);
        }

        /* Left Border */
        .dashboard-card.primary {
            border-left: 6px solid #76001f;
        }

        .dashboard-card.success {
            border-left: 6px solid #198754;
        }

        /* Content */
        .card-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Title */
        .card-title {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 8px;
        }

        /* Count */
        .card-count {
            font-size: 38px;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }

        /* Icon */
        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: #fff;
        }

        .primary .card-icon {
            background: #76001f;
        }

        .success .card-icon {
            background: #198754;
        }

        /* =========================================
               RESPONSIVE ONLY
            ========================================= */

        @media (max-width: 768px) {

            .dashboard-wrapper {
                min-height: auto;
                width: 100%;
                padding: 30px 15px;
                box-sizing: border-box;
                overflow-x: hidden;
            }

            .dashboard-wrapper .row {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .dashboard-wrapper .col-lg-4,
            .dashboard-wrapper .col-md-5,
            .dashboard-wrapper .col-sm-6 {
                width: 100%;
                max-width: 100%;
            }

            .dashboard-card {
                width: 100%;
                box-sizing: border-box;
            }

            .card-content {
                width: 100%;
                gap: 15px;
            }

            .card-count {
                font-size: 30px;
            }

            .card-icon {
                width: 60px;
                height: 60px;
                min-width: 60px;
                font-size: 26px;
            }
        }


        /* =========================================
               SMALL MOBILE
            ========================================= */

        @media (max-width: 480px) {

            .dashboard-wrapper {
                padding: 20px 10px;
            }

            .dashboard-wrapper .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .dashboard-card {
                padding: 20px;
                border-radius: 14px;
            }

            .card-content {
                gap: 10px;
            }

            .card-title {
                font-size: 13px;
            }

            .card-count {
                font-size: 28px;
            }

            .card-icon {
                width: 52px;
                height: 52px;
                min-width: 52px;
                font-size: 23px;
            }
        }


        /* =========================================
               VERY SMALL MOBILE
            ========================================= */

        @media (max-width: 360px) {

            .dashboard-wrapper {
                padding: 15px 8px;
            }

            .dashboard-card {
                padding: 16px;
            }

            .card-title {
                font-size: 12px;
            }

            .card-count {
                font-size: 25px;
            }

            .card-icon {
                width: 48px;
                height: 48px;
                min-width: 48px;
                font-size: 21px;
            }
        }
    </style>

    <style>
        /* =========================================
           WELCOME BANNER RESPONSIVE
        ========================================= */

        /* =========================================
       MOBILE
    ========================================= */

        @media (max-width: 768px) {

            /* Target the welcome banner */

            [style*="margin-left: 270px"] {
                margin-top: 80px !important;
                margin-left: 15px !important;
                margin-right: 15px !important;
                margin-bottom: 20px !important;

                padding: 25px !important;

                box-sizing: border-box;

                width: auto !important;

                gap: 20px;
            }

            [style*="margin-left: 270px"] h2 {
                font-size: 24px !important;
                line-height: 1.3;
            }

            [style*="margin-left: 270px"] p {
                font-size: 13px !important;
                line-height: 1.5;
            }

            [style*="margin-left: 270px"]>div:last-child {
                width: 65px !important;
                height: 65px !important;
                min-width: 65px !important;
                font-size: 30px !important;
            }
        }


        /* =========================================
       SMALL MOBILE
    ========================================= */

        @media (max-width: 480px) {

            [style*="margin-left: 270px"] {
                margin-top: 80px !important;
                margin-left: 8px !important;
                margin-right: 8px !important;
                margin-bottom: 15px !important;

                padding: 18px !important;

                border-radius: 14px !important;

                gap: 12px;
            }

            [style*="margin-left: 270px"] h2 {
                font-size: 20px !important;
            }

            [style*="margin-left: 270px"] p {
                font-size: 11px !important;
                line-height: 1.45;
                margin-top: 7px !important;
            }

            [style*="margin-left: 270px"]>div:last-child {
                width: 52px !important;
                height: 52px !important;
                min-width: 52px !important;
                font-size: 23px !important;
            }
        }


        /* =========================================
       VERY SMALL MOBILE
    ========================================= */

        @media (max-width: 360px) {

            [style*="margin-left: 270px"] {
                margin-top: 80px !important;
                margin-left: 6px !important;
                margin-right: 6px !important;
                margin-bottom: 12px !important;

                padding: 15px !important;
            }

            [style*="margin-left: 270px"] h2 {
                font-size: 18px !important;
            }

            [style*="margin-left: 270px"] p {
                font-size: 10px !important;
            }

            [style*="margin-left: 270px"]>div:last-child {
                width: 45px !important;
                height: 45px !important;
                min-width: 45px !important;
                font-size: 20px !important;
            }
        }
    </style>
@endsection