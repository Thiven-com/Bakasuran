@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                <h4>Delivery Partners</h4>
                <span class="badge bg-primary">
                    Total: {{ $deliveryPartners->total() }}
                </span>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Partner</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Vehicle</th>
                                    <th>Verification</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($deliveryPartners as $partner)

                                    <tr>
                                        <td>{{ $deliveryPartners->firstItem() + $loop->index }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">

                                                {{-- @if($partner->profile_image)
                                                <img src="{{ asset($partner->profile_image) }}" width="40" height="40"
                                                    style="border-radius:50%; object-fit:cover;">
                                                @else
                                                <img src="{{ asset('images/default-user.png') }}" width="40" height="40"
                                                    style="border-radius:50%;">
                                                @endif --}}

                                                <div>
                                                    <strong>{{ $partner->name }}</strong>
                                                    <br>
                                                    <small>{{ $partner->partner_code }}</small>
                                                </div>

                                            </div>
                                        </td>

                                        <td>{{ $partner->mobile }}</td>

                                        <td>{{ $partner->email ?? '-' }}</td>

                                        <td>
                                            {{ $partner->city ?? '-' }},
                                            {{ $partner->state ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $partner->vehicle_type ?? '-' }}
                                            <br>
                                            <small>{{ $partner->vehicle_number ?? '-' }}</small>
                                        </td>

                                        <td>
                                            @if($partner->verification_status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($partner->verification_status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($partner->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($partner->status == 'blocked')
                                                <span class="badge bg-danger">Blocked</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 flex-nowrap">

                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#verificationModal{{ $partner->id }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>

                                                @if($partner->verification_status == 'pending')

                                                    <!-- Approve Button -->
                                                    <form action="{{ route('admin.delivery-partners.approve', $partner->id) }}"
                                                        method="POST" class="m-0">
                                                        @csrf

                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Approve this delivery partner?')">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <!-- Reject Button -->
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $partner->id }}">
                                                        Reject
                                                    </button>

                                                @endif
                                                <a href="{{ route('admin.delivery-partners.show', $partner->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> View
                                                </a>

                                            </div>

                                            <div class="modal fade" id="verificationModal{{ $partner->id }}" tabindex="-1"
                                                aria-labelledby="verificationModalLabel{{ $partner->id }}" aria-hidden="true">

                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <form
                                                            action="{{ route('admin.delivery-partners.update-verification', $partner->id) }}"
                                                            method="POST">

                                                            @csrf
                                                            @method('PUT')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="verificationModalLabel{{ $partner->id }}">
                                                                    Edit Verification Status
                                                                </h5>

                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <div class="mb-3">
                                                                    <label class="form-label">Partner Name</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $partner->name }}" readonly>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Partner Code</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $partner->partner_code }}" readonly>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Verification Status <span
                                                                            class="text-danger">*</span></label>

                                                                    <select name="verification_status" class="form-select"
                                                                        required>
                                                                        <option value="pending" {{ $partner->verification_status == 'pending' ? 'selected' : '' }}>
                                                                            Pending
                                                                        </option>

                                                                        <option value="approved" {{ $partner->verification_status == 'approved' ? 'selected' : '' }}>
                                                                            Approved
                                                                        </option>

                                                                        <option value="rejected" {{ $partner->verification_status == 'rejected' ? 'selected' : '' }}>
                                                                            Rejected
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Rejection Reason</label>

                                                                    <textarea name="rejection_reason" class="form-control"
                                                                        rows="3"
                                                                        placeholder="Enter rejection reason">{{ $partner->rejection_reason }}</textarea>
                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>

                                                                <button type="submit" class="btn btn-primary">
                                                                    Update
                                                                </button>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $partner->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <form
                                                            action="{{ route('admin.delivery-partners.reject', $partner->id) }}"
                                                            method="POST">

                                                            @csrf

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reject Delivery Partner</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <p>
                                                                    Partner Name: <strong>{{ $partner->name }}</strong>
                                                                </p>

                                                                <div class="mb-3">
                                                                    <label class="form-label">
                                                                        Rejection Reason <span class="text-danger">*</span>
                                                                    </label>

                                                                    <textarea name="rejection_reason" class="form-control"
                                                                        rows="4" placeholder="Enter rejection reason"
                                                                        required></textarea>
                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>

                                                                <button type="submit" class="btn btn-danger">
                                                                    Confirm Reject
                                                                </button>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="9" class="text-center">
                                            No delivery partners found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $deliveryPartners->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection