@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h4>SKU Report</h4>
                    <h6 class="text-muted">Shipped SKU analytics (date-wise)</h6>
                </div>
            </div>

            <div class="card mt-3">

                {{-- 🔍 FILTER --}}
                <div class="card-header">
                    <form method="GET" class="row g-2 align-items-end">

                        <div class="col-md-3">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="SKU / Product" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label>Date</label>
                            <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}"
                                class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-secondary btn-sm w-100">
                                Search
                            </button>

                            <button type="submit" formaction="{{ route('admin.sku.export') }}"
                                class="btn btn-success btn-sm w-100">
                                Export
                            </button>

                            <a href="{{ route('admin.sku.report') }}" class="btn btn-light btn-sm w-100">
                                Clear
                            </a>
                        </div>

                    </form>
                </div>

                {{-- 📋 TABLE --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">

                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Product</th>
                                    <th>Orders</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>

                            <tbody>

                                @php
                                    $grouped = $skus->groupBy('batch_no');
                                @endphp

                                @forelse($grouped as $batch => $items)

                                    {{-- 🔷 Batch Header --}}
                                    <tr style="background:#e9f5ff;">
                                        <td colspan="5">
                                            <strong>
                                                🚚 Batch {{ $batch }}
                                            </strong>
                                            -
                                            {{ \Carbon\Carbon::parse($items->first()->batch_time)->format('d M Y h:i A') }}

                                            <span class="text-muted ms-2">
                                                ({{ count($items) }} SKUs)
                                            </span>
                                        </td>
                                    </tr>

                                    {{-- 🔹 Rows --}}
                                    @foreach($items as $key => $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                <strong>{{ $row->sku }}</strong>
                                            </td>

                                            <td style="max-width:300px">
                                                {{ $row->product_title }}
                                            </td>

                                            <td>
                                                <span class="badge bg-danger">
                                                    {{ $row->order_count }}
                                                </span>
                                            </td>

                                            <td>
                                                <strong>{{ $row->total_qty }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            No data found
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection