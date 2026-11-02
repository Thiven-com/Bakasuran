
<?php $page = 'addon-options'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="page-header d-flex align-items-center justify-content-between">
            <div class="page-title">
                <h4 class="fw-bold">Edit Add-on Option</h4>
                <h6>Update food add-on option details</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.addon-options.index') }}"
                   class="btn btn-light">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add-on Option Details</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.addon-options.update', $option->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Add-on Group -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Add-on Group <span class="text-danger">*</span>
                            </label>

                            <select name="addon_group_id"
                                    class="form-select @error('addon_group_id') is-invalid @enderror"
                                    required>

                                <option value="">Select Add-on Group</option>

                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('addon_group_id', $option->addon_group_id) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('addon_group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Option Name -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Option Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $option->name) }}"
                                   placeholder="Example: Extra Cheese"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Price (₹) <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="price"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $option->price) }}"
                                   min="0"
                                   step="0.01"
                                   required>

                            <small class="text-muted">
                                Enter 0 for a free add-on.
                            </small>

                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Tracking -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label d-block">
                                Track Stock
                            </label>

                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       name="track_stock"
                                       value="1"
                                       class="form-check-input"
                                       id="track_stock"
                                       {{ old('track_stock', $option->track_stock) ? 'checked' : '' }}>

                                <label class="form-check-label" for="track_stock">
                                    Enable quantity-based stock tracking
                                </label>
                            </div>
                        </div>

                        <!-- Stock Quantity -->
                        <div class="col-lg-6 col-sm-12 mb-3 stock-field">
                            <label class="form-label">
                                Available Stock <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="stock"
                                   id="stock"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $option->stock) }}"
                                   min="0"
                                   step="1">

                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Low Stock Alert -->
                        <div class="col-lg-6 col-sm-12 mb-3 stock-field">
                            <label class="form-label">
                                Low Stock Alert Quantity
                            </label>

                            <input type="number"
                                   name="low_stock_alert"
                                   class="form-control @error('low_stock_alert') is-invalid @enderror"
                                   value="{{ old('low_stock_alert', $option->low_stock_alert ?? 5) }}"
                                   min="0"
                                   step="1">

                            @error('low_stock_alert')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Availability -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label d-block">
                                Availability
                            </label>

                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       name="is_available"
                                       value="1"
                                       class="form-check-input"
                                       id="is_available"
                                       {{ old('is_available', $option->is_available) ? 'checked' : '' }}>

                                <label class="form-check-label" for="is_available">
                                    Available for customers
                                </label>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <label class="form-label">
                                Sort Order
                            </label>

                            <input type="number"
                                   name="sort_order"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', $option->sort_order) }}"
                                   min="0"
                                   step="1">

                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            Update Add-on Option
                        </button>

                        <a href="{{ route('admin.addon-options.index') }}"
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const trackStock = document.getElementById('track_stock');
    const stockFields = document.querySelectorAll('.stock-field');
    const stockInput = document.getElementById('stock');

    function toggleStockFields() {

        const enabled = trackStock.checked;

        stockFields.forEach(function (field) {
            field.style.display = enabled ? '' : 'none';
        });

        stockInput.required = enabled;
    }

    trackStock.addEventListener('change', toggleStockFields);

    toggleStockFields();

});
</script>
@endpush
