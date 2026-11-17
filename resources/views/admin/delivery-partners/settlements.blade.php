@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="page-title">Delivery Partner Settlements</h3>
                    <p class="text-muted mb-0">
                        {{ $partner->name }} ({{ $partner->partner_code }})
                    </p>
                </div>

                <div class="d-flex gap-2 flex-wrap">

                    @if($pendingEarnings > 0)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#settlementModal">
                            <i class="fa fa-wallet me-1"></i> Settle Now
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="fa fa-check me-1"></i> No Pending Earnings
                        </button>
                    @endif

                    <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>

                </div>
            </div>

            {{-- Settlement Modal --}}
            <div class="modal fade" id="settlementModal" tabindex="-1" aria-labelledby="settlementModalLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <form action="{{ route('admin.delivery-partners.settlements.store', $partner->id) }}" method="POST">

                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="settlementModalLabel">
                                    <i class="fa fa-wallet text-success me-2"></i>
                                    Settle Partner Earnings
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                {{-- Pending Amount --}}
                                <div class="alert alert-success">
                                    <div class="d-flex justify-content-between">
                                        <span>Available Pending Earnings</span>
                                        <strong>
                                            ₹{{ number_format($pendingEarnings, 2) }}
                                        </strong>
                                    </div>
                                </div>

                                {{-- Total Earnings --}}
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Earnings</span>
                                    <strong>₹{{ number_format($totalEarnings, 2) }}</strong>
                                </div>

                                {{-- Already Settled --}}
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Already Settled</span>
                                    <strong>₹{{ number_format($settledEarnings, 2) }}</strong>
                                </div>

                                <hr>

                                {{-- Settlement Amount --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Settlement Amount (₹)
                                    </label>

                                    <input type="number" name="amount" class="form-control"
                                        placeholder="Enter settlement amount" min="0.01"
                                        max="{{ number_format($pendingEarnings, 2, '.', '') }}" step="0.01"
                                        value="{{ old('amount') }}" required>

                                    <small class="text-muted">
                                        Maximum settlement amount:
                                        ₹{{ number_format($pendingEarnings, 2) }}
                                    </small>

                                    @error('amount')
                                        <div class="text-danger mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Admin Notes --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Admin Notes
                                    </label>

                                    <textarea name="notes" class="form-control" rows="3"
                                        placeholder="Enter settlement notes">{{ old('notes') }}</textarea>

                                    @error('notes')
                                        <div class="text-danger mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="alert alert-warning mb-0">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Please verify the payment before confirming settlement.
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check me-1"></i>
                                    Confirm Settlement
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>


            {{-- Earnings Summary --}}
            <div class="row">

                {{-- Total Earnings --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Total Earnings</h6>
                            <h4 class="text-success">
                                ₹{{ number_format($totalEarnings, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Delivered Orders --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Total Delivered Orders</h6>
                            <h4>{{ $totalDeliveredOrders }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Pending Earnings --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Pending Earnings</h6>
                            <h4 class="text-warning">
                                ₹{{ number_format($pendingEarnings, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Settled Earnings --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Settled Earnings</h6>
                            <h4 class="text-primary">
                                ₹{{ number_format($settledEarnings, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Earnings Settlement Limit --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Earnings Settlement Limit</h6>
                            <h4>
                                ₹{{ number_format((float) ($partner->earnings_settlement_limit ?? 0), 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Total Hand Cash --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Total Hand Cash</h6>
                            <h4>
                                ₹{{ number_format((float) ($partner->total_hand_cash ?? 0), 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Pending Hand Cash --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Pending Hand Cash</h6>
                            <h4 class="text-warning">
                                ₹{{ number_format((float) ($partner->pending_hand_cash ?? 0), 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Submitted Hand Cash --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Submitted Hand Cash</h6>
                            <h4 class="text-success">
                                ₹{{ number_format((float) ($partner->submitted_hand_cash ?? 0), 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Hand Cash Limit --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Hand Cash Limit</h6>
                            <h4>
                                ₹{{ number_format((float) ($partner->hand_cash_limit ?? 0), 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

            </div>



            {{-- Settled Earnings History --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-wallet text-success me-2"></i>
                        Settled Earnings History
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Reference Number</th>
                                    <th>Settlement Date</th>
                                    <th>Settlement Amount</th>
                                    <th>Status</th>
                                    <th>Admin Notes</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($settlements as $key => $settlement)

                                                        <tr>

                                                            <td>
                                                                {{ $settlements->firstItem() + $key }}
                                                            </td>

                                                            <td>
                                                                {{ $settlement->reference_number ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{ $settlement->settled_at
                                    ? \Carbon\Carbon::parse($settlement->settled_at)->format('d-m-Y h:i A')
                                    : 'N/A' }}
                                                            </td>

                                                            <td class="fw-bold text-success">
                                                                ₹{{ number_format((float) $settlement->amount, 2) }}
                                                            </td>

                                                            <td>
                                                                @if($settlement->settlement_status == 'completed')
                                                                    <span class="badge bg-success">Completed</span>
                                                                @elseif($settlement->settlement_status == 'pending')
                                                                    <span class="badge bg-warning">Pending</span>
                                                                @elseif($settlement->settlement_status == 'rejected')
                                                                    <span class="badge bg-danger">Rejected</span>
                                                                @else
                                                                    <span class="badge bg-secondary">
                                                                        {{ ucfirst($settlement->settlement_status ?? 'N/A') }}
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                {{ $settlement->notes ?? 'N/A' }}
                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No settlement history found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-3">
                        {{ $settlements->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>


        </div>
    </div>

    {{-- Reopen Modal After Validation Errors --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('settlementModal');

                if (modalElement && typeof bootstrap !== 'undefined') {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>
    @endif

@endsection