
@extends('layout.mainlayout')

@section('title', 'Add Restaurant Table')

@section('content')

<style>
    .table-form-page {
        padding: 24px;
        margin-top: 50px;
        min-height: 100vh;
        background: #f7f7f9;
        color: #171717;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .form-header h2 {
        font-size: 25px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .form-header p {
        color: #777;
        margin: 0;
        font-size: 14px;
    }

    .btn-back {
        padding: 10px 16px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #f1f1f1;
        color: #111;
    }

    .table-form-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 25px;
    }

    .section-title {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #eee;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .form-control,
    .form-select {
        min-height: 46px;
        border: 1px solid #e2e2e2;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff0000;
        box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.08);
    }

    textarea.form-control {
        min-height: 110px;
    }

    .required {
        color: #ff0000;
    }

    .btn-save {
        background: #ff0000;
        color: #fff;
        border: none;
        padding: 12px 22px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-save:hover {
        background: #cc0000;
        color: #fff;
    }

    .btn-cancel {
        padding: 12px 22px;
        border: 1px solid #ddd;
        background: #fff;
        color: #333;
        border-radius: 8px;
        text-decoration: none;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    @media(max-width: 650px) {
        .table-form-page {
            padding: 14px;
        }

        .table-form-card {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions button,
        .form-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="table-form-page">

    {{-- Header --}}
    <div class="form-header">
        <div>
            <h2>Add Restaurant Table</h2>
            <p>Create and configure a new restaurant table.</p>
        </div>

        <a href="{{ route('admin.restaurant-tables.index') }}"
           class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Tables
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-form-card">

        <form action="{{ route('admin.restaurant-tables.store') }}"
              method="POST">

            @csrf

            <h5 class="section-title">
                <i class="fas fa-chair me-2 text-danger"></i>
                Table Information
            </h5>

            <div class="row g-4">

                {{-- Restaurant --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Restaurant <span class="required">*</span>
                    </label>

                    <select name="restaurant_id"
                            class="form-select @error('restaurant_id') is-invalid @enderror"
                            required>

                        <option value="">Select Restaurant</option>

                        @foreach($restaurants as $restaurant)
                           <option value="{{ $restaurant->id }}"
    {{ old('restaurant_id', $selectedRestaurantId ?? '') == $restaurant->id ? 'selected' : '' }}>
    {{ $restaurant->name }}
</option>
                        @endforeach

                    </select>

                    @error('restaurant_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Table Name --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Table Name <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="table_name"
                           class="form-control @error('table_name') is-invalid @enderror"
                           placeholder="Example: Family Table"
                           value="{{ old('table_name') }}"
                           maxlength="100"
                           required>

                    @error('table_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Table Number --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Table Number <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="table_number"
                           class="form-control @error('table_number') is-invalid @enderror"
                           placeholder="Example: TBL-001"
                           value="{{ old('table_number') }}"
                           maxlength="50"
                           required>

                    @error('table_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Capacity --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Seating Capacity <span class="required">*</span>
                    </label>

                    <input type="number"
                           name="seating_capacity"
                           class="form-control @error('seating_capacity') is-invalid @enderror"
                           placeholder="Number of guests"
                           value="{{ old('seating_capacity', 4) }}"
                           min="1"
                           required>

                    @error('seating_capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Seating Type --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Seating Type <span class="required">*</span>
                    </label>

                    <select name="seating_type"
                            class="form-select @error('seating_type') is-invalid @enderror"
                            required>

                        <option value="">Select Seating Type</option>

                        <option value="indoor"
                            {{ old('seating_type', 'indoor') == 'indoor' ? 'selected' : '' }}>
                            Indoor
                        </option>

                        <option value="outdoor"
                            {{ old('seating_type') == 'outdoor' ? 'selected' : '' }}>
                            Outdoor
                        </option>

                        <option value="family"
                            {{ old('seating_type') == 'family' ? 'selected' : '' }}>
                            Family
                        </option>

                        <option value="private"
                            {{ old('seating_type') == 'private' ? 'selected' : '' }}>
                            Private
                        </option>

                    </select>

                    @error('seating_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Table Status <span class="required">*</span>
                    </label>

                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                        <option value="1"
                            {{ old('status', '1') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('status') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Enter table description..."
                              rows="4">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="form-actions">

                <a href="{{ route('admin.restaurant-tables.index') }}"
                   class="btn-cancel">
                    Cancel
                </a>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>
                    Save Table
                </button>

            </div>

        </form>

    </div>

</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* =========================================
           ADD RESTAURANT TABLE PAGE - SIDEBAR SYNC
        ========================================= */

        const sidebar = document.getElementById('sidebar');
        const tableFormPage = document.querySelector('.table-form-page');

        // Stop if page or sidebar is unavailable
        if (!sidebar || !tableFormPage) {
            return;
        }

        function syncTableFormPageWithSidebar() {

            // Mobile layout
            if (window.innerWidth <= 991) {

                tableFormPage.style.setProperty(
                    'margin-left',
                    '0px',
                    'important'
                );

                tableFormPage.style.setProperty(
                    'width',
                    '100%',
                    'important'
                );

                return;
            }

            // Get actual sidebar width
            const sidebarWidth = Math.round(
                sidebar.getBoundingClientRect().width
            );

            // Adjust content position
            tableFormPage.style.setProperty(
                'margin-left',
                sidebarWidth + 'px',
                'important'
            );

            // Adjust content width
            tableFormPage.style.setProperty(
                'width',
                'calc(100% - ' + sidebarWidth + 'px)',
                'important'
            );
        }

        // Initial layout
        syncTableFormPageWithSidebar();

        // Observe sidebar width changes
        if (typeof ResizeObserver !== 'undefined') {

            const observer = new ResizeObserver(function () {
                syncTableFormPageWithSidebar();
            });

            observer.observe(sidebar);
        }

        // Recalculate after sidebar toggle
        document.addEventListener('click', function () {

            setTimeout(syncTableFormPageWithSidebar, 100);
            setTimeout(syncTableFormPageWithSidebar, 300);

        });

        // Browser resize
        window.addEventListener(
            'resize',
            syncTableFormPageWithSidebar
        );

    });
</script>


@endsection
