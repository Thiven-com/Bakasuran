
<?php $page = 'testimonial-edit'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Edit Testimonial</h4>
                <h6>Update customer review details</h6>
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

        <form action="{{ route('admin.testimonials.update', $testimonial->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="testimonial-form-card">

            @csrf
            @method('PUT')

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
                                   value="{{ old('name', $testimonial->name) }}"
                                   maxlength="255"
                                   required>
                        </div>

                        {{-- City --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text"
                                   name="city"
                                   class="form-control"
                                   value="{{ old('city', $testimonial->city) }}"
                                   maxlength="100">
                        </div>

                        {{-- Customer ID --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer ID</label>
                            <input type="number"
                                   name="customer_id"
                                   class="form-control"
                                   value="{{ old('customer_id', $testimonial->customer_id) }}"
                                   min="1">
                        </div>

                        {{-- Current Image --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Current Image</label>

                            <div class="mb-2">
                                @if($testimonial->image)
                                    <img
                                        src="{{ asset($testimonial->image) }}"
                                        alt="{{ $testimonial->name }}"
                                        width="100"
                                        height="100"
                                        style="object-fit:cover;border-radius:8px;"
                                    >
                                @else
                                    <p class="text-muted">No image uploaded.</p>
                                @endif
                            </div>

                            <label class="form-label">Upload New Image</label>
                            <input type="file"
                                   name="image"
                                   id="image"
                                   class="form-control"
                                   accept="image/jpeg,image/png,image/webp">

                            <small class="text-muted">
                                JPG, PNG, or WEBP. Maximum 2 MB.
                                Leave empty to keep the current image.
                            </small>
                        </div>

                        {{-- New Image Preview --}}
                        <div class="col-12 mb-3" id="preview-container"
                             style="display:none;">
                            <label class="form-label">New Image Preview</label>
                            <div>
                                <img id="image-preview"
                                     src=""
                                     alt="New image preview"
                                     width="120"
                                     height="120"
                                     style="object-fit:cover;border-radius:8px;">
                            </div>
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
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}"
                                        {{ (int) old('rating', $testimonial->rating) === $i ? 'selected' : '' }}>
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
                                    {{ old('status', $testimonial->status) == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="approved"
                                    {{ old('status', $testimonial->status) == 'approved' ? 'selected' : '' }}>
                                    Approved
                                </option>

                                <option value="rejected"
                                    {{ old('status', $testimonial->status) == 'rejected' ? 'selected' : '' }}>
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
                                      required>{{ old('description', $testimonial->description) }}</textarea>
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
                        Update Testimonial
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- New Image Preview --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('preview-container');

    if (!input || !preview || !container) return;

    let previewUrl = null;

    input.addEventListener('change', function () {
        const file = input.files && input.files[0];

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        if (!file) {
            preview.removeAttribute('src');
            container.style.display = 'none';
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG, PNG, or WEBP image.');
            input.value = '';
            container.style.display = 'none';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must not exceed 2 MB.');
            input.value = '';
            container.style.display = 'none';
            return;
        }

        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        container.style.display = 'block';
    });
});
</script>

@endsection
