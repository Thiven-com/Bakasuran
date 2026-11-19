<?php $page = 'addon-groups'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header d-flex align-items-center justify-content-between">
                <div class="page-title">
                    <h4 class="fw-bold">Add-on Groups</h4>
                    <h6>Manage food add-on groups</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.addon-groups.create') }}" class="btn btn-primary">
                        <i class="ti ti-circle-plus me-1"></i>
                        Add Add-on Group
                    </a>
                </div>
            </div>

            <!-- Add-on Groups Table -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">All Add-on Groups</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.addon-groups.index') }}" method="GET">
                        <div class="row">

                            <!-- Group Name -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Group Name</label>
                                <input type="text" name="search" class="form-control" placeholder="Search group name"
                                    value="{{ request('search') }}">
                            </div>

                            <!-- Selection Type -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Selection Type</label>
                                <select name="selection_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="single" {{ request('selection_type') == 'single' ? 'selected' : '' }}>
                                        Single
                                    </option>
                                    <option value="multiple" {{ request('selection_type') == 'multiple' ? 'selected' : '' }}>
                                        Multiple
                                    </option>
                                </select>
                            </div>

                            <!-- Required -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Required</label>
                                <select name="is_required" class="form-select">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_required') === '1' ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0" {{ request('is_required') === '0' ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="show" {{ request('status') == 'show' ? 'selected' : '' }}>
                                        Show
                                    </option>
                                    <option value="hide" {{ request('status') == 'hide' ? 'selected' : '' }}>
                                        Hide
                                    </option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ti ti-search me-1"></i> Filter
                                </button>

                                <a href="{{ route('admin.addon-groups.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.No</th>
                                    <th>Group Name</th>
                                    <th>Selection Type</th>
                                    <th>Required</th>
                                    <th>Min Selection</th>
                                    <th>Max Selection</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($groups as $group)

                                    <tr>
                                        <!-- Serial Number -->
                                        <td>{{ $loop->iteration }}</td>

                                        <!-- Group Name -->
                                        <td>
                                            <strong>{{ $group->name }}</strong>
                                        </td>

                                        <!-- Selection Type -->
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst($group->selection_type) }}
                                            </span>
                                        </td>

                                        <!-- Required -->
                                        <td>
                                            @if($group->is_required)
                                                <span class="badge badge-success">
                                                    Yes
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    No
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Minimum Selection -->
                                        <td>
                                            {{ $group->min_select }}
                                        </td>

                                        <!-- Maximum Selection -->
                                        <td>
                                            {{ $group->max_select ?? 'Unlimited' }}
                                        </td>

                                        <!-- Options Count -->
                                        <td>
                                            {{ $group->options_count }}
                                        </td>

                                        <!-- Status -->
                                        <td>
                                            @if($group->status === 'show')
                                                <span class="badge badge-success">
                                                    Show
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    Hide
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Sort Order -->
                                        <td>
                                            {{ $group->sort_order }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <div class="edit-delete-action">

                                                <!-- Edit -->
                                                <a class="me-2 p-2" href="{{ route('admin.addon-groups.edit', $group->id) }}">
                                                    <i data-feather="edit"></i>
                                                </a>

                                                <!-- Delete -->
                                                <form action="{{ route('admin.addon-groups.destroy', $group->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this add-on group?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-link text-danger p-2">
                                                        <i data-feather="trash-2"></i>
                                                    </button>

                                                </form>

                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection