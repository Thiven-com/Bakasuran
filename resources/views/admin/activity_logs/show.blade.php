<?php $page = 'activity-logs'; ?>
@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Activity Log Details</h4>
                    <h6>Full details view</h6>
                </div>

                <a href="{{ route('admin.activity.logs') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <p><strong>User:</strong> {{ $log->user->name ?? 'N/A' }}</p>
                            <p><strong>Module:</strong> {{ ucfirst($log->module) }}</p>
                            <p><strong>Action:</strong> {{ ucfirst($log->action) }}</p>
                        </div>
                        <div class="col-md-6">

                            <p><strong>Record ID:</strong> #{{ $log->record_id }}</p>

                            <p><strong>Record Ref:</strong>
                                @if ($log->module == 'order')
                                    {{ $log->order->invoice_id ?? '-' }}

                                @elseif ($log->module == 'product')
                                    {{ $log->product->title ?? '-' }}

                                @elseif ($log->module == 'productvariant')
                                    {{ $log->productvariant->sku ?? '-' }}
                                @elseif ($log->module == 'coupon')
                                    {{ $log->coupon->code ?? '-' }}

                                @else
                                    -
                                @endif
                            </p>

                            <p><strong>Date:</strong> {{ $log->created_at->format('d M Y H:i:s') }}</p>

                        </div>
                    </div>

                    <hr>

                    <div>
                        <h5>Description</h5>
                        <p>{{ $log->description }}</p>
                    </div>

                    @php
                        $changes = json_decode($log->changes, true);
                        $old = $changes['old'] ?? [];
                        $new = $changes['new'] ?? [];
                    @endphp

                    @if(!empty($changes))

                        <div class="table-responsive">

                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field</th>
                                        <th class="text-danger">Old Value</th>
                                        <th class="text-success">New Value</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($new as $key => $newValue)
                                        <tr>
                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>

                                            <td class="{{ ($old[$key] ?? null) != $newValue ? 'text-warning fw-bold' : '' }}">
                                                {{ $old[$key] ?? '-' }}
                                            </td>

                                            <td class="text-success">
                                                {{ $newValue ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection