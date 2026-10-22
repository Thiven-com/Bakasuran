
<?php $page = 'testimonial-list'; ?>

@extends('layout.mainlayout')

@section('content')

<style>
    .testimonial-list-page {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .testimonial-list-page .table-responsive {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
    }

    .testimonial-list-page .table {
        min-width: 900px;
    }
</style>

<div class="page-wrapper">
    <div class="content testimonial-list-page">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Customer Testimonials</h4>
                    <h6>Manage customer reviews and ratings</h6>
                </div>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.testimonials.create') }}"
                   class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>
                    Add Testimonial
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

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

        {{-- Testimonials Table --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Customer</th>
                                <th>City</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($testimonials as $testimonial)
                                <tr>
                                    <td>{{ $testimonial->id }}</td>

                                    <td>
                                        @if($testimonial->image)
                                            <img
                                                src="{{ asset($testimonial->image) }}"
                                                alt="{{ $testimonial->name }}"
                                                width="55"
                                                height="55"
                                                style="object-fit:cover;border-radius:50%;"
                                            >
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>

                                    <td>{{ $testimonial->name }}</td>

                                    <td>{{ $testimonial->city ?? '--' }}</td>

                                    <td>
                                        <span style="color:#f5a623;">
                                            {{ str_repeat('★', max(0, min(5, (int) $testimonial->rating))) }}
                                        </span>
                                        <span class="text-muted">
                                            ({{ $testimonial->rating }}/5)
                                        </span>
                                    </td>

                                    <td>
                                        {{ \Illuminate\Support\Str::limit($testimonial->description, 60) }}
                                    </td>

                                    <td>
                                        {{ ucfirst($testimonial->status ?? 'Unknown') }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}"
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('admin.testimonials.destroy', $testimonial->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        No testimonials found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
