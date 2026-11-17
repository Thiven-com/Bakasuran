@extends('layout.mainlayout')

@section('content')
    <style>
        .details-card {
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            margin-bottom: 22px;
            background: #fff;
        }

        .details-card .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e8e8e8;
            padding: 14px 20px;
            font-weight: 600;
            font-size: 16px;
        }

        .details-card .card-body {
            padding: 20px;
        }

        .detail-item {
            margin-bottom: 18px;
        }

        .detail-label {
            color: #777;
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            word-break: break-word;
        }

        .document-box {
            margin-bottom: 18px;
        }

        .document-box img {
            width: 100%;
            max-width: 220px;
            height: 150px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 5px;
            background: #fafafa;
        }

        .document-name {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .section-icon {
            margin-right: 8px;
            color: #d71313;
        }
    </style>

    @php
        $partner = $deliveryPartner;

        $imageUrl = function ($path) {
            return $path ? asset($path) : null;
        };

        $display = function ($value) {
            return ($value !== null && $value !== '') ? $value : 'Not Provided';
        };
    @endphp

    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="page-title">Delivery Partner Details</h3>
                    <p class="text-muted mb-0">
                        Partner Code: {{ $partner->partner_code }}
                    </p>
                </div>

                <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

            {{-- Profile Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-user section-icon"></i> Profile Details
                </div>

                <div class="card-body">
                    <div class="row align-items-center">

                        <div class="col-md-2 text-center mb-3">
                            @if($partner->profile_image)
                                <a href="{{ $imageUrl($partner->profile_image) }}" target="_blank">
                                    <img src="{{ $imageUrl($partner->profile_image) }}" class="profile-image"
                                        alt="Profile Image">
                                </a>
                            @else
                                <div class="text-muted">No Profile Image</div>
                            @endif
                        </div>

                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Partner Code</span>
                                    <div class="detail-value">{{ $display($partner->partner_code) }}</div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Full Name</span>
                                    <div class="detail-value">{{ $display($partner->name) }}</div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Email</span>
                                    <div class="detail-value">{{ $display($partner->email) }}</div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Mobile Number</span>
                                    <div class="detail-value">{{ $display($partner->mobile) }}</div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Alternate Mobile</span>
                                    <div class="detail-value">{{ $display($partner->alternate_mobile) }}</div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Date of Birth</span>
                                    <div class="detail-value">
                                        {{ $partner->date_of_birth ? \Carbon\Carbon::parse($partner->date_of_birth)->format('d-m-Y') : 'Not Provided' }}
                                    </div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Gender</span>
                                    <div class="detail-value">{{ $display($partner->gender) }}</div>
                                </div>

                                <div class="col-md-4 detail-item">
                                    <span class="detail-label">Registered At</span>
                                    <div class="detail-value">
                                        {{ $partner->created_at ? $partner->created_at->format('d-m-Y h:i A') : 'Not Provided' }}
                                    </div>
                                </div>
                                <!-- Settlements Button -->
                                {{-- <a href="{{ route('admin.delivery-partners.settlements.show', $partner->id) }}"
                                    class="btn btn-primary">
                                    <i class="fa fa-wallet me-1"></i> Settlements
                                </a> --}}
                                <!-- Settlements Button -->
                                <div class="col-md-4 detail-item">
                                    <a href="{{ route('admin.delivery-partners.settlements.show', $partner->id) }}"
                                        class="btn btn-primary">
                                        <i class="fa fa-wallet me-1"></i> Settlements
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Address Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-map-marker section-icon"></i> Address Details
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach([
                                'Address Line 1' => $partner->address_line_1,
                                'Address Line 2' => $partner->address_line_2,
                                'Landmark' => $partner->landmark,
                                'Area' => $partner->area,
                                'City' => $partner->city,
                                'District' => $partner->district,
                                'State' => $partner->state,
                                'Country' => $partner->country,
                                'Pincode' => $partner->pincode,
                                'Latitude' => $partner->latitude,
                                'Longitude' => $partner->longitude,
                            ] as $label => $value)
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">{{ $label }}</span>
                                <div class="detail-value">{{ $display($value) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Vehicle Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-motorcycle section-icon"></i> Vehicle Details
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Vehicle Type</span>
                            <div class="detail-value">{{ $display($partner->vehicle_type) }}</div>
                        </div>

                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Vehicle Number</span>
                            <div class="detail-value">{{ $display($partner->vehicle_number) }}</div>
                        </div>

                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Vehicle Model</span>
                            <div class="detail-value">{{ $display($partner->vehicle_model) }}</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 document-box">
                            <div class="document-name">Vehicle RC Image</div>
                            @if($partner->vehicle_rc_image)
                                <a href="{{ $imageUrl($partner->vehicle_rc_image) }}" target="_blank">
                                    <img src="{{ $imageUrl($partner->vehicle_rc_image) }}" alt="Vehicle RC">
                                </a>
                            @else
                                <span class="text-muted">Not Uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aadhaar Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-id-card section-icon"></i> Aadhaar Details
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Aadhaar Number</span>
                            <div class="detail-value">{{ $display($partner->aadhar_number) }}</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 document-box">
                            <div class="document-name">Aadhaar Front Image</div>
                            @if($partner->aadhar_front_image)
                                <a href="{{ $imageUrl($partner->aadhar_front_image) }}" target="_blank">
                                    <img src="{{ $imageUrl($partner->aadhar_front_image) }}" alt="Aadhaar Front">
                                </a>
                            @else
                                <span class="text-muted">Not Uploaded</span>
                            @endif
                        </div>

                        <div class="col-md-4 document-box">
                            <div class="document-name">Aadhaar Back Image</div>
                            @if($partner->aadhar_back_image)
                                <a href="{{ $imageUrl($partner->aadhar_back_image) }}" target="_blank">
                                    <img src="{{ $imageUrl($partner->aadhar_back_image) }}" alt="Aadhaar Back">
                                </a>
                            @else
                                <span class="text-muted">Not Uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PAN Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-id-card section-icon"></i> PAN Details
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 detail-item">
                            <span class="detail-label">PAN Number</span>
                            <div class="detail-value">{{ $display($partner->pan_number) }}</div>
                        </div>

                        <div class="col-md-4 document-box">
                            <div class="document-name">PAN Card Image</div>
                            @if($partner->pan_image)
                                <a href="{{ $imageUrl($partner->pan_image) }}" target="_blank">
                                    <img src="{{ $imageUrl($partner->pan_image) }}" alt="PAN Card">
                                </a>
                            @else
                                <span class="text-muted">Not Uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Driving Licence Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-address-card section-icon"></i> Driving Licence Details
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Driving Licence Number</span>
                            <div class="detail-value">{{ $display($partner->driving_license_number) }}</div>
                        </div>

                        <div class="col-md-4 document-box">
                            <div class="document-name">Driving Licence Image</div>
                            @if($partner->driving_license_image)
                                <a href="{{ $imageUrl($partner->driving_license_image) }}" target="_blank">
                                    <img src="{{ $imageUrl($partner->driving_license_image) }}" alt="Driving Licence">
                                </a>
                            @else
                                <span class="text-muted">Not Uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bank Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-university section-icon"></i> Bank Details
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach([
                                'Bank Name' => $partner->bank_name,
                                'Account Holder Name' => $partner->account_holder_name,
                                'Account Number' => $partner->account_number,
                                'IFSC Code' => $partner->ifsc_code,
                                'UPI ID' => $partner->upi_id,
                            ] as $label => $value)
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">{{ $label }}</span>
                                <div class="detail-value">{{ $display($value) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Verification & Account Status --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-shield section-icon"></i> Verification & Account Status
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Verification Status</span>
                            <div class="detail-value">
                                @if($partner->verification_status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($partner->verification_status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Verified</span>
                            <div class="detail-value">
                                {{ $partner->is_verified ? 'Yes' : 'No' }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Availability</span>
                            <div class="detail-value">
                                {{ $partner->is_available ? 'Available' : 'Not Available' }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Account Status</span>
                            <div class="detail-value">{{ ucfirst($display($partner->status)) }}</div>
                        </div>

                        <div class="col-md-12 detail-item">
                            <span class="detail-label">Rejection Reason</span>
                            <div class="detail-value">{{ $display($partner->rejection_reason) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Earnings & Hand Cash Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-money section-icon"></i>
                    Earnings & Hand Cash Details
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Total Earnings</span>
                            <div class="detail-value">
                                ₹{{ number_format($partner->orders()->where('status', 'delivered')->sum('delivery_earning'), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Total Delivered Orders</span>
                            <div class="detail-value">
                                {{ $partner->orders()->where('status', 'delivered')->count() }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Pending Earnings</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->pending_earnings ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Settled Earnings</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->settled_earnings ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Earnings Settlement Limit</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->earnings_settlement_limit ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Total Hand Cash</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->total_hand_cash ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Pending Hand Cash</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->pending_hand_cash ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Submitted Hand Cash</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->submitted_hand_cash ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 detail-item">
                            <span class="detail-label">Hand Cash Limit</span>
                            <div class="detail-value">
                                ₹{{ number_format((float) ($partner->hand_cash_limit ?? 0), 2) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Login & Other Details --}}
            <div class="card details-card">
                <div class="card-header">
                    <i class="fa fa-clock section-icon"></i> Login & Other Details
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Last Login</span>
                            <div class="detail-value">
                                {{ $partner->last_login_at ? \Carbon\Carbon::parse($partner->last_login_at)->format('d-m-Y h:i A') : 'Not Available' }}
                            </div>
                        </div>

                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Created At</span>
                            <div class="detail-value">
                                {{ $partner->created_at ? $partner->created_at->format('d-m-Y h:i A') : 'Not Available' }}
                            </div>
                        </div>

                        <div class="col-md-4 detail-item">
                            <span class="detail-label">Updated At</span>
                            <div class="detail-value">
                                {{ $partner->updated_at ? $partner->updated_at->format('d-m-Y h:i A') : 'Not Available' }}
                            </div>
                        </div>

                        <div class="col-md-12 detail-item">
                            <span class="detail-label">Admin Notes</span>
                            <div class="detail-value">{{ $display($partner->notes) }}</div>
                        </div>

                        @if($partner->trashed())
                            <div class="col-md-12 detail-item">
                                <span class="badge bg-danger">
                                    Deleted: {{ $partner->deleted_at?->format('d-m-Y h:i A') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- Floating Approve & Reject Buttons --}}
        @if($partner->verification_status == 'pending')
            <div class="floating-action-buttons">

                <!-- Approve Button -->
                <form action="{{ route('admin.delivery-partners.approve', $partner->id) }}" method="POST" class="m-0">
                    @csrf

                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve this delivery partner?')">
                        <i class="fa fa-check me-1"></i> Approve
                    </button>
                </form>

                <!-- Reject Button -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#rejectModal{{ $partner->id }}">
                    <i class="fa fa-times me-1"></i> Reject
                </button>

            </div>
        @endif
        <style>
            .floating-action-buttons {
                position: fixed;
                right: 35px;
                bottom: 35px;
                z-index: 99999;
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 9px;
                border-radius: 15px;
                background: rgba(255, 255, 255, 0.96);
                border: 1px solid #e9edf2;
                box-shadow:
                    0 2px 5px rgba(20, 30, 50, .04),
                    0 15px 35px rgba(20, 30, 50, .14);
                animation: actionBarFloat 4s ease-in-out infinite;
            }

            .floating-action-buttons form {
                margin: 0;
                perspective: 700px;
            }

            .floating-action-buttons .btn {
                position: relative;
                min-width: 110px;
                padding: 10px 16px;
                border: none;
                border-radius: 10px;
                color: #fff !important;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: .3px;
                overflow: hidden;
                transform: translateZ(0);
                transition:
                    transform .35s cubic-bezier(.2, .8, .2, 1),
                    box-shadow .35s ease,
                    filter .35s ease;
            }

            /* APPROVE - Emerald 3D */
            .floating-action-buttons .btn-success {
                background: linear-gradient(145deg, #27c987 0%, #07965d 100%);
                box-shadow:
                    0 5px 0 #08764c,
                    0 8px 14px rgba(7, 150, 93, .22);
            }

            /* REJECT - Professional Coral 3D */
            .floating-action-buttons .btn-danger {
                background: linear-gradient(145deg, #ff7777 0%, #e34452 100%);
                box-shadow:
                    0 5px 0 #b82e3b,
                    0 8px 14px rgba(227, 68, 82, .22);
            }

            /* Light reflection */
            .floating-action-buttons .btn::before {
                content: "";
                position: absolute;
                top: 0;
                left: 12%;
                width: 76%;
                height: 1px;
                background: rgba(255, 255, 255, .65);
            }

            /* 3D lift */
            .floating-action-buttons .btn:hover {
                color: #fff !important;
                transform: translateY(-6px) rotateX(8deg);
                filter: brightness(1.07);
            }

            .floating-action-buttons .btn-success:hover {
                box-shadow:
                    0 9px 0 #08764c,
                    0 17px 25px rgba(7, 150, 93, .3);
            }

            .floating-action-buttons .btn-danger:hover {
                box-shadow:
                    0 9px 0 #b82e3b,
                    0 17px 25px rgba(227, 68, 82, .3);
            }

            /* Physical press */
            .floating-action-buttons .btn:active {
                transform: translateY(4px) rotateX(0);
                box-shadow: 0 1px 0 rgba(0, 0, 0, .2);
            }

            /* Floating motion */
            @keyframes actionBarFloat {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-7px);
                }
            }

            /* Mobile */
            @media (max-width: 576px) {
                .floating-action-buttons {
                    right: 12px;
                    bottom: 15px;
                    gap: 9px;
                    padding: 9px;
                }

                .floating-action-buttons .btn {
                    min-width: 112px;
                    padding: 13px 12px;
                    font-size: 12px;
                }
            }

            /* Accessibility */
            @media (prefers-reduced-motion: reduce) {

                .floating-action-buttons,
                .floating-action-buttons .btn {
                    animation: none;
                    transition: none;
                }
            }
        </style>
    </div>
@endsection