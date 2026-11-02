
<?php $page = 'addon-options'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="page-header d-flex align-items-center justify-content-between">
            <div class="page-title">
                <h4 class="fw-bold">Add-on Options</h4>
                <h6>Manage food add-on options</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.addon-options.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>
                    Add Add-on Option
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Add-on Options Table -->
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">All Add-on Options</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>S.No</th>
                                <th>Option Name</th>
                                <th>Add-on Group</th>
                                <th>Price</th>
                                <th>Track Stock</th>
                                <th>Stock</th>
                                <th>Availability</th>
                                <th>Sort Order</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($options as $option)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $option->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $option->group?->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $option->price, 2) }}
                                    </td>

                                    <td>
                                        @if($option->track_stock)
                                            <span class="badge badge-info">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($option->track_stock)
                                            {{ $option->stock ?? 0 }}

                                            @if(
                                                $option->low_stock_alert !== null &&
                                                $option->stock !== null &&
                                                $option->stock <= $option->low_stock_alert
                                            )
                                                <span class="badge badge-warning">
                                                    Low Stock
                                                </span>
                                            @endif
                                        @else
                                            Unlimited
                                        @endif
                                    </td>

                                    <td>
                                        @if($option->is_available)
                                            <span class="badge badge-success">Available</span>
                                        @else
                                            <span class="badge badge-danger">Unavailable</span>
                                        @endif
                                    </td>

                                    <td>{{ $option->sort_order }}</td>

                                    <td class="text-center">
                                        <div class="edit-delete-action">

                                            <!-- Edit -->
                                            <a class="me-2 p-2"
                                               href="{{ route('admin.addon-options.edit', $option->id) }}"
                                               title="Edit">
                                                <i data-feather="edit"></i>
                                            </a>

                                            <!-- Delete -->
                                            <form
                                                action="{{ route('admin.addon-options.destroy', $option->id) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this add-on option?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-link text-danger p-2"
                                                        title="Delete">
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
    