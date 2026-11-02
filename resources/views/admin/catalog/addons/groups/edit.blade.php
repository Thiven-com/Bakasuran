
<?php $page = 'addon-groups'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="page-header d-flex align-items-center justify-content-between">
            <div class="page-title">
                <h4 class="fw-bold">Edit Add-on Group</h4>
                <h6>Update food add-on group details</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.addon-groups.index') }}"
                   class="btn btn-light">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add-on Group Details</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.addon-groups.update', $group->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Group Name -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Group Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $group->name) }}"
                                   placeholder="Example: Extra Toppings"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selection Type -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Selection Type <span class="text-danger">*</span>
                            </label>

                            <select name="selection_type"
                                    class="form-select @error('selection_type') is-invalid @enderror"
                                    required>

                                <option value="multiple"
                                    {{ old('selection_type', $group->selection_type) == 'multiple' ? 'selected' : '' }}>
                                    Multiple Selection
                                </option>

                                <option value="single"
                                    {{ old('selection_type', $group->selection_type) == 'single' ? 'selected' : '' }}>
                                    Single Selection
                                </option>

                            </select>

                            @error('selection_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Minimum Selection -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Minimum Selection
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="min_select"
                                   class="form-control @error('min_select') is-invalid @enderror"
                                   value="{{ old('min_select', $group->min_select) }}"
                                   min="0"
                                   required>

                            @error('min_select')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Maximum Selection -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Maximum Selection
                            </label>

                            <input type="number"
                                   name="max_select"
                                   class="form-control @error('max_select') is-invalid @enderror"
                                   value="{{ old('max_select', $group->max_select) }}"
                                   min="1"
                                   placeholder="Leave empty for unlimited">

                            @error('max_select')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Required -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label d-block">
                                Is this group required?
                            </label>

                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       name="is_required"
                                       value="1"
                                       class="form-check-input"
                                       id="is_required"
                                       {{ old('is_required', $group->is_required) ? 'checked' : '' }}>

                                <label class="form-check-label" for="is_required">
                                    Required
                                </label>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>

                                <option value="show"
                                    {{ old('status', $group->status) == 'show' ? 'selected' : '' }}>
                                    Show
                                </option>

                                <option value="hide"
                                    {{ old('status', $group->status) == 'hide' ? 'selected' : '' }}>
                                    Hide
                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">Sort Order</label>

                            <input type="number"
                                   name="sort_order"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', $group->sort_order) }}"
                                   min="0">

                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            Update Add-on Group
                        </button>

                        <a href="{{ route('admin.addon-groups.index') }}"
                           class="btn btn-light">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
