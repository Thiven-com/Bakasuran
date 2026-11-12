
@extends('layout.mainlayout')

@section('content')

<div class="restaurant-reviews-page" style="margin-top:70px;">
    <div class="container-fluid">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4>Restaurant Reviews</h4>
                <p class="text-muted mb-0">
                    Manage customer ratings and restaurant feedback.
                </p>
            </div>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERROR MESSAGE -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- REVIEW SUMMARY -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Reviews</h6>
                        <h3>{{ $totalReviews }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Active Reviews</h6>
                        <h3>{{ $activeReviews }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Average Rating</h6>
                        <h3>
                            {{ number_format($averageRating ?? 0, 1) }}
                            <small class="text-warning">★</small>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET"
                    action="{{ route('admin.restaurant-reviews.index') }}"
                    class="row align-items-end">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Restaurant</label>
                        <select name="restaurant_id" class="form-control">
                            <option value="">All Restaurants</option>

                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}"
                                    {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-control">
                            <option value="">All Ratings</option>

                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}"
                                    {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>
                        <a href="{{ route('admin.restaurant-reviews.index') }}"
                            class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- REVIEWS TABLE -->
        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Customer_ID</th>
                            <th>Restaurant</th>
                            <th>Order Number</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    {{ $review->customer_id ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $review->restaurant->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $review->order->order_number ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                    ({{ $review->rating }}/5)
                                </td>

                                <td style="min-width:200px;">
                                    {{ $review->review ?: 'No written review' }}
                                </td>

                                <td>
                                    @if($review->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $review->created_at->format('d M Y, h:i A') }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <form
                                            action="{{ route('admin.restaurant-reviews.update-status', $review->id) }}"
                                            method="POST">

                                            @csrf

                                            <input type="hidden" name="status"
                                                value="{{ $review->status == 1 ? 0 : 1 }}">

                                            <button type="submit"
                                                class="btn btn-sm {{ $review->status == 1 ? 'btn-warning' : 'btn-success' }}">
                                                {{ $review->status == 1 ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.restaurant-reviews.destroy', $review->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this review?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    No restaurant reviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $reviews->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>

    </div>
</div>

<!-- RESPONSIVE SIDEBAR LAYOUT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        function syncRestaurantReviewsLayout() {
            const sidebar = document.getElementById('sidebar');
            const page = document.querySelector('.restaurant-reviews-page');

            if (!sidebar || !page) return;

            if (window.innerWidth <= 991) {
                page.style.marginLeft = '0';
                page.style.width = '100%';
            } else {
                const sidebarWidth = sidebar.getBoundingClientRect().width;

                page.style.marginLeft = sidebarWidth + 'px';
                page.style.width = 'calc(100% - ' + sidebarWidth + 'px)';
            }
        }

        syncRestaurantReviewsLayout();

        window.addEventListener('load', syncRestaurantReviewsLayout);
        window.addEventListener('resize', syncRestaurantReviewsLayout);

        document.addEventListener('click', function () {
            setTimeout(syncRestaurantReviewsLayout, 100);
            setTimeout(syncRestaurantReviewsLayout, 300);
        });

        const sidebar = document.getElementById('sidebar');

        if (sidebar && typeof ResizeObserver !== 'undefined') {
            const observer = new ResizeObserver(syncRestaurantReviewsLayout);
            observer.observe(sidebar);
        }

    });
</script>

@endsection
