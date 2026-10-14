<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>AbandonedCart</h4>
                        <h6>Manage your AbandonedCart</h6>
                    </div>
                </div>


            </div>

            <!-- product list -->
            <div class="card">
                <div class="card mb-3 p-3">
                    <form method="GET" class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search...">
                        </div>


                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('admin.abandonedCart') }}"><button type="button"
                                    class="btn btn-outline-light w-100">Clear</button></a>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('admin.sendBulkAbandonedCartMail') }}" class="btn btn-success w-100"
                                onclick="return confirm('Are you sure you want to send emails to all listed customers?')">
                                Send Bulk Mail
                            </a>
                        </div>
                    </form>

                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.no</th>
                                    <th>Customer Details</th>
                                    <th>Amount</th>
                                    <th>Cart Items Count</th>
                                    <th>View</th>
                                    <th>Mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($customers as $data)

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <p class="text-dark mb-0">
                                                        {{ $data->name ?? ''}}<br>
                                                        <span style="font-size:12px">{{ $data->mobile ?? '-' }}</span> <br>
                                                        <span style="font-size:12px">{{ $data->email ?? '-' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- <b>Email:</b>{{ $data->email ?? '-' }} <br>
                                            <b>Mobile:</b>{{ $data->mobile ?? '-' }} <br> --}}

                                        </td>
                                        <td>
                                            {{ $data->cart_total ?? 0 }}
                                        </td>
                                        <td>
                                            {{ $data->cart_items_count ?? 0 }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.viewAbandonedCart', $data->id) }}">
                                                <i class="fa fa-eye"></i>View
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.sendAbandonedCartMail', $data->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Send Mail
                                            </a>
                                        </td>

                                    </tr>
                                    @php
                                        $i++;
                                    @endphp

                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    @if(method_exists($customers, 'links'))
                        <div class="p-3">
                            {{ $customers->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
            <!-- /product list -->

        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            {{-- <p class="mb-0">2025 &copy; {{$->site_name }}. All Right Reserved</p> --}}
            {{-- <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">{{$site->site_name ?? ' '  }}</a>
            </p> --}}
        </div>
    </div>
@endsection