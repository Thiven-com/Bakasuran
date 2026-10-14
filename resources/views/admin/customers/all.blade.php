<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Customers</h4>
                        <h6>Manage your customers</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    
                  
                </ul>
                {{-- <div class="page-btn">
                    <a href="{{ route('admin.staff.import.form') }}" class="btn btn-primary"><i
                            class="ti ti-arrow-up me-1"></i>Import</a>
                </div> --}}

                {{-- <div class="page-btn">
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-danger"><i
                            class="ti ti-circle-plus me-1"></i>Add Employee</a>
                </div> --}}
            </div>

            <!-- product list -->
            <div class="card">
                <div class="card mb-3 p-3">
                    <form method="GET" class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
                        </div>
                        

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('admin.customers.index') }}"><button type="button"
                                    class="btn btn-outline-light w-100">Clear</button></a>
                        </div>
                    </form>

                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($customers as $data)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->mobile }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</td>
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