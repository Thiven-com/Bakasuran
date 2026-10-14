@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div>
                <h4 class="mb-1">DTDC Tracking</h4>
                <h6 class="text-muted">Shipment Journey Timeline</h6>
            </div>
        </div>

        @php

            $data = $response ?? [];

            $header = $data['trackHeader'] ?? [];
            $details = $data['trackDetails'] ?? [];

            $currentStatus = strtolower($header['strStatus'] ?? '');

            $statusColor = match(true) {
                str_contains($currentStatus, 'delivered') => 'success',
                str_contains($currentStatus, 'return') => 'danger',
                str_contains($currentStatus, 'cancel') => 'danger',
                str_contains($currentStatus, 'instruction') => 'danger',
                str_contains($currentStatus, 'transit') => 'info',
                default => 'warning'
            };

        @endphp

        {{-- Shipment Header --}}
        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <strong>AWB Number</strong>
                        <div class="mt-1">
                            {{ $header['strShipmentNo'] ?? $order->awb }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <strong>Reference No</strong>
                        <div class="mt-1">
                            {{ $header['strRefNo'] ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <strong>Origin</strong>
                        <div class="mt-1">
                            {{ $header['strOrigin'] ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <strong>Status</strong>
                        <div class="mt-1">
                            <span class="badge bg-{{ $statusColor }}">
                                {{ $header['strStatus'] ?? '-' }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- Current Status --}}
        <div class="alert alert-{{ $statusColor }} mt-3">
            <strong>Current Status :</strong>
            {{ $header['strStatus'] ?? '-' }}
        </div>

        {{-- Timeline --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tracking Timeline</h5>
            </div>

            <div class="card-body">

                @if(count($details))

                    <div class="timeline">

                        @foreach(collect($details)->reverse() as $item)

                            @php

                                $action = strtolower($item['strAction'] ?? '');

                                $color = match(true) {

                                    str_contains($action, 'delivered')
                                        => 'success',

                                    str_contains($action, 'return')
                                        => 'danger',

                                    str_contains($action, 'cancel')
                                        => 'danger',

                                    str_contains($action, 'instruction')
                                        => 'danger',

                                    str_contains($action, 'pickup')
                                        => 'primary',

                                    str_contains($action, 'transit')
                                        => 'info',

                                    default
                                        => 'secondary'
                                };

                                $date = '';

                                if(!empty($item['strActionDate'])) {
                                    try {
                                        $date = \Carbon\Carbon::createFromFormat(
                                            'dmY',
                                            $item['strActionDate']
                                        )->format('d M Y');
                                    } catch (\Exception $e) {
                                        $date = $item['strActionDate'];
                                    }
                                }

                            @endphp

                            <div class="d-flex mb-4">

                                <div class="me-3">
                                    <span class="badge bg-{{ $color }}">
                                        &nbsp;
                                    </span>
                                </div>

                                <div class="flex-grow-1">

                                    <h6 class="mb-1">
                                        {{ $item['strAction'] ?? '-' }}
                                    </h6>

                                    <small class="text-muted">
                                        {{ $date }}
                                        {{ $item['strActionTime'] ?? '' }}
                                    </small>

                                    <div class="mt-1 text-muted">

                                        @if(!empty($item['strOrigin']))
                                            <div>
                                                <strong>Location:</strong>
                                                {{ $item['strOrigin'] }}
                                            </div>
                                        @endif

                                        @if(!empty($item['strManifestNo']))
                                            <div>
                                                <strong>Manifest:</strong>
                                                {{ $item['strManifestNo'] }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-4">
                        No tracking updates found.
                    </div>

                @endif

            </div>
        </div>

    </div>
</div>

@endsection