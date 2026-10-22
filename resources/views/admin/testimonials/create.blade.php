
<?php $page = 'testimonial-create'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Add Testimonial</h4>
                <h6>Create a customer testimonial</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.testimonials.index') }}"
                   class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back to List
                </a>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.testimonials.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="testimonial-form-card">

            @csrf

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Customer Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Customer Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Enter customer name"
                                   maxlength="255"
                                   required>
                        </div>

                        {{-- City --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text"
                                   name="city"
                                   class="form-control"
                                   value="{{ old('city') }}"
                                   placeholder="Enter city"
                                   maxlength="100">
                        </div>

                        {{-- Customer ID --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer ID</label>
                            <input type="number"
                                   name="customer_id"
                                   class="form-control"
                                   value="{{ old('customer_id') }}"
                                   placeholder="Optional customer ID"
                                   min="1">
                        </div>

                        {{-- Image --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Image</label>
                            <input type="file"
                                   name="image"
                                   id="image"
                                   class="form-control"
                                   accept="image/jpeg,image/png,image/webp">
                            <small class="text-muted">
                                JPG, PNG, or WEBP; maximum 2 MB.
                            </small>
                        </div>

                        {{-- Rating --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Rating <span class="text-danger">*</span>
                            </label>
                            <select name="rating"
                                    id="rating"
                                    class="form-select"
                                    required>
                                <option value="">Select rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}"
                                        {{ old('rating', 5) == $i ? 'selected' : '' }}>
                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status"
                                    class="form-select"
                                    required>
                                <option value="pending"
                                    {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="approved"
                                    {{ old('status') == 'approved' ? 'selected' : '' }}>
                                    Approved
                                </option>
                                <option value="rejected"
                                    {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>
                            </select>
                        </div>

                        {{-- Review --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Review Description <span class="text-danger">*</span>
                            </label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Enter customer review"
                                      required>{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Buttons --}}
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.testimonials.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>
                        Save Testimonial
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- Image Preview --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');

    if (!imageInput) return;

    const preview = document.createElement('img');
    preview.style.display = 'none';
    preview.style.width = '120px';
    preview.style.height = '120px';
    preview.style.objectFit = 'cover';
    preview.style.borderRadius = '8px';
    preview.style.marginTop = '10px';

    imageInput.insertAdjacentElement('afterend', preview);

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            preview.style.display = 'none';
            preview.removeAttribute('src');
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG, PNG, or WEBP image.');
            this.value = '';
            preview.style.display = 'none';
            preview.removeAttribute('src');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must not exceed 2 MB.');
            this.value = '';
            preview.style.display = 'none';
            preview.removeAttribute('src');
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    });
});
</script>

@endsection
