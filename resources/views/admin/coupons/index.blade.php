<?php $page = 'coupons'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Coupons</h4>
                        <h6>Manage Your Coupons</h6>
                    </div>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i
                            class="ti ti-circle-plus me-1"></i>Add Coupons</a>
                </div>
            </div>
            <!-- /product list -->
            <div class="card">

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="no-sort">
                                        #
                                    </th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    {{-- <th>Description</th> --}}
                                    <th>Type</th>
                                    <th>Discount</th>
                                    <th>Valid</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($data as $coupon)
                                    <tr>
                                        <td>
                                            {{$i++}}
                                        </td>
                                        <td class="text-gray-9">{{ $coupon->name }}</td>
                                        <td><span class="badge purple-badge">{{ $coupon->code }}</span></td>
                                        {{-- <td>
                                            {{ $coupon->description }}
                                        </td> --}}
                                        <td>{{ ucwords($coupon->type) }}</td>
                                        <td>
                                            {{$coupon->discount}}
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($coupon->expiry_date)->format('d M Y') }}</td>
                                        <td>
                                            @if ($coupon->status == 'active')
                                                <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
                                            @else
                                                <span class="badge table-badge bg-danger fw-medium fs-10">InActive</span>
                                            @endif

                                        </td>
                                        <td class="action-table-data">
                                            {{-- <div class="edit-delete-action">

                                                <a data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2"
                                                    href="javascript:void(0);">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                            </div> --}}
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure to Delete Coupon?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 border-0 bg-transparent">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2026 &copy; {{ $site->site_name ?? ' '  }}. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">ThiVen</a></p>
        </div>
    </div>

@endsection