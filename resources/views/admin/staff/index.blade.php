<?php $page = 'setting'; ?>
@extends('layout.mainlayout')
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            transition: .4s;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #28a745;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
    </style>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header d-flex align-items-center justify-content-between">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Staff</h4>
                        <h6>Manage your Staff</h6>
                    </div>
                </div>
                <div class="page-btn">
                    <button class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#add-customer">
                        <i class="ti ti-circle-plus me-1"></i>Add Staff
                    </button>
                </div>
            </div>

            {{-- flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <!-- Customer List -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customersTableBody">
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($data as $list)
                                    <tr data-item='@json($list)'>
                                        <td>{{$i++}}</td>
                                        <td>{{ $list->name ?? '—' }}</td>
                                        <td>{{ $list->mobile ?? '—' }}</td>
                                        <td>{{ $list->email ?? '—' }}</td>
                                        <td>{{ $list->role ?? '—' }}</td>
                                        <td>
                                            <form action="{{ route('admin.staffs.update', $list->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <label class="switch">
                                                    <input type="checkbox" name="status" value="1" {{ $list->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <span class="slider round"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ url('/admin/staffs/' . $list->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $data->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="add-customer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data"
                id="addCustomerForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Add Staff</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input name="name" value="{{ old('name') }}" class="form-control" placeholder="Name"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    placeholder="Email" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mobile <span class="text-danger">*</span></label>
                                <input name="mobile" value="{{ old('mobile') }}" class="form-control" placeholder="Mobile"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" value="{{ old('password') }}" class="form-control"
                                    placeholder="Password" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm Password" required>

                            </div>
                            <input type="hidden" name="role" value="staff">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Add Staff</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Setting Modal -->
    <div class="modal fade" id="edit-setting" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="editStaffForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Edit Staff</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label">Name</label>
                                <input name="name" id="edit_name" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Email</label>
                                <input name="email" id="edit_email" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Mobile</label>
                                <input name="mobile" id="edit_mobile" class="form-control" required>
                            </div>

                            <!-- PASSWORD (OPTIONAL) -->
                            <div class="col-md-12">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Leave blank to keep old password">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Update Staff</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-edit-setting').forEach(button => {
            button.addEventListener('click', function () {

                const item = JSON.parse(this.dataset.item);
                console.log(item);

                document.getElementById('edit_id').value = item.id;
                document.getElementById('edit_name').value = item.name;
                document.getElementById('edit_email').value = item.email;
                document.getElementById('edit_mobile').value = item.mobile;

                // Update route
                document.getElementById('editStaffForm').action =
                    `/admin/staffs/${item.id}`;
            });
        });
    </script>
@endpush