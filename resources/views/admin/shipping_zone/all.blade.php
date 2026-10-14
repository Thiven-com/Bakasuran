<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Shipping Zones</h4>
                        <h6>Manage your Shipping Zones</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <div class="d-flex me-2 pe-2 border-end">

                        </div>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="{{ route('admin.shippingZones.create')}}" class="btn btn-primary"><i
                            class="ti ti-circle-plus me-1"></i>Add Zone</a>
                </div>

            </div>

            <!-- product list -->
            <div class="card">

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.no</th>
                                    <th>Zone Name</th>
                                    <th>Regions</th>
                                    <th>Shipping Methods</th>
                                    <th>Free Shipping</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($data as $zone)
                                    <tr>
                                        @php
                                            $regions = json_decode($zone->regions, true) ?? [];
                                        @endphp
                                        <td>{{ $i }}</td>
                                        <td>{{$zone->zone_name}}</td>
                                        <td>
                                            @if(!empty($regions))
                                                {{ implode(', ', $regions) }}
                                            @else
                                                <span class="text-muted">No Regions</span>
                                            @endif
                                        </td>
                                        <td>{{ $zone->shipping_method }}</td>
                                        <td>
                                            {{ $zone->free_shipping }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">

                                                <!-- Edit -->
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('admin.shippingZones.edit', $zone->id) }}">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <!-- Delete -->
                                                <button type="button" class="btn btn-sm btn-danger confirm-delete-zone"
                                                    data-url="{{ route('admin.shippingZones.destroy', $zone->id) }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp

                                @endforeach
                            </tbody>
                        </table>
                    </div>
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).on('click', '.confirm-delete-zone', function () {

            let deleteUrl = $(this).data('url');

            if (confirm('Are you sure you want to delete this Shipping Zone?')) {

                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        alert(response.message || 'Deleted successfully');
                        location.reload();
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Something went wrong');
                    }
                });

            }
        });
    </script>
@endsection