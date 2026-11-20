
@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title">Restaurant Owners</h4>
                    <p class="text-muted mb-0">
                        Manage registered restaurants and owner approvals
                    </p>
                </div>

                <div class="col-auto">
                    <a href="{{ route('admin.restaurants.create') }}"
                       class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i>
                        Create Restaurant
                    </a>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Restaurant Owners Card --}}
        <div class="card">
            <div class="card-body">

                {{-- Search --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET"
                              action="{{ route('admin.restaurant-owners.index') }}">

                            <div class="input-group">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Search restaurant or owner..."
                                       value="{{ request('search') }}">

                                @if(request('status'))
                                    <input type="hidden"
                                           name="status"
                                           value="{{ request('status') }}">
                                @endif

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i>
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Status Filters --}}
                <div class="mb-4">

                    <a href="{{ route('admin.restaurant-owners.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
                       class="btn btn-sm {{ !request('status') || request('status') === 'all' ? 'btn-primary' : 'btn-outline-primary' }} me-1">
                        All
                    </a>

                    <a href="{{ route('admin.restaurant-owners.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
                       class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }} me-1">
                        Pending
                    </a>

                    <a href="{{ route('admin.restaurant-owners.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}"
                       class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }} me-1">
                        Approved
                    </a>

                    <a href="{{ route('admin.restaurant-owners.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}"
                       class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} me-1">
                        Rejected
                    </a>

                    <a href="{{ route('admin.restaurant-owners.index', array_merge(request()->except('page'), ['status' => 'inactive'])) }}"
                       class="btn btn-sm {{ request('status') === 'inactive' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        Inactive
                    </a>

                </div>

                {{-- Restaurant Owners Table --}}
                <div class="table-responsive">

                    <table class="table table-striped table-hover">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Restaurant</th>
                                <th>Owner</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Approval</th>
                                <th>Account</th>
                                <th>Registered</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($restaurants as $restaurant)

                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $restaurants->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Restaurant --}}
                                    <td>
                                        <strong>{{ $restaurant->name }}</strong>
                                    </td>

                                    {{-- Owner --}}
                                    <td>
                                        {{ $restaurant->owner_name ?: '-' }}
                                    </td>

                                    {{-- Email --}}
                                    <td>
                                        {{ $restaurant->email ?: '-' }}
                                    </td>

                                    {{-- Mobile --}}
                                    <td>
                                        {{ $restaurant->phone ?: '-' }}
                                    </td>

                                    {{-- Approval Status --}}
                                    <td>
                                        @if($restaurant->approval_status === 'pending')
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        @elseif($restaurant->approval_status === 'approved')
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                        @elseif($restaurant->approval_status === 'rejected')
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Unknown
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Account Status --}}
                                    <td>
                                        @if($restaurant->approval_status === 'approved' && $restaurant->is_active)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Registered Date --}}
                                    <td>
                                        {{ $restaurant->created_at
                                            ? $restaurant->created_at->format('d M Y')
                                            : '-' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                                            {{-- Show --}}
                                            <a href="{{ route('admin.restaurants.show', $restaurant->id) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View Restaurant">
                                                <i class="fa fa-eye"></i>
                                                Show
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Restaurant">
                                                <i class="fa fa-edit"></i>
                                                Edit
                                            </a>

                                            {{-- Approval Actions --}}
                                            @if($restaurant->approval_status === 'pending')

                                                {{-- Approve --}}
                                                <form method="POST"
                                                      action="{{ route('admin.restaurants.approve', $restaurant->id) }}"
                                                      onsubmit="return confirm('Approve this restaurant?')">

                                                    @csrf

                                                    <button type="submit"
                                                            class="btn btn-sm btn-success">
                                                        Approve
                                                    </button>
                                                </form>

                                                {{-- Reject --}}
                                                <form method="POST"
                                                      action="{{ route('admin.restaurants.reject', $restaurant->id) }}"
                                                      onsubmit="return confirm('Reject this registration?')">

                                                    @csrf

                                                    <input type="hidden"
                                                           name="rejection_reason"
                                                           value="Registration rejected by admin">

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">
                                                        Reject
                                                    </button>
                                                </form>

                                            @elseif($restaurant->approval_status === 'approved')

                                                @if($restaurant->is_active)

                                                    {{-- Deactivate --}}
                                                    <form method="POST"
                                                          action="{{ route('admin.restaurants.deactivate', $restaurant->id) }}"
                                                          onsubmit="return confirm('Deactivate this restaurant?')">

                                                        @csrf

                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger">
                                                            Deactivate
                                                        </button>
                                                    </form>

                                                @else

                                                    {{-- Activate --}}
                                                    <form method="POST"
                                                          action="{{ route('admin.restaurants.activate', $restaurant->id) }}"
                                                          onsubmit="return confirm('Activate this restaurant?')">

                                                        @csrf

                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-success">
                                                            Activate
                                                        </button>
                                                    </form>

                                                @endif

                                            @else

                                                <span class="text-muted small">
                                                    Rejected
                                                </span>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        No restaurant registrations found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Custom Pagination --}}
                @if($restaurants->hasPages())

                    <div class="restaurant-pagination">

                        <div class="restaurant-pagination-info">
                            Showing {{ $restaurants->firstItem() }} to
                            {{ $restaurants->lastItem() }} of
                            {{ $restaurants->total() }} restaurants
                        </div>

                        <nav aria-label="Restaurant pagination">

                            <ul class="restaurant-pagination-list">

                                {{-- Previous --}}
                                <li class="{{ $restaurants->onFirstPage() ? 'disabled' : '' }}">

                                    @if($restaurants->onFirstPage())
                                        <span>Previous</span>
                                    @else
                                        <a href="{{ $restaurants->previousPageUrl() }}">
                                            Previous
                                        </a>
                                    @endif

                                </li>

                                {{-- Page Numbers --}}
                                @for($page = 1; $page <= $restaurants->lastPage(); $page++)

                                    <li class="{{ $restaurants->currentPage() == $page ? 'active' : '' }}">

                                        <a href="{{ $restaurants->url($page) }}">
                                            {{ $page }}
                                        </a>

                                    </li>

                                @endfor

                                {{-- Next --}}
                                <li class="{{ !$restaurants->hasMorePages() ? 'disabled' : '' }}">

                                    @if(!$restaurants->hasMorePages())
                                        <span>Next</span>
                                    @else
                                        <a href="{{ $restaurants->nextPageUrl() }}">
                                            Next
                                        </a>
                                    @endif

                                </li>

                            </ul>

                        </nav>

                    </div>

                @endif

            </div>
        </div>

    </div>
</div>

<style>

/* RESTAURANT PAGINATION */

.restaurant-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    padding: 18px 5px 5px;
    margin-top: 15px;
    border-top: 1px solid #e9edf3;
}

.restaurant-pagination-info {
    color: #687389;
    font-size: 13px;
}

.restaurant-pagination-list {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.restaurant-pagination-list li a,
.restaurant-pagination-list li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border: 1px solid #e1e7ef;
    border-radius: 6px;
    background: #ffffff;
    color: #071b3d;
    font-size: 13px;
    text-decoration: none;
    transition: all .2s ease;
}

.restaurant-pagination-list li.active a {
    background: #071b3d;
    border-color: #071b3d;
    color: #ffffff;
}

.restaurant-pagination-list li a:hover {
    background: #eef3ff;
    border-color: #3157a6;
}

.restaurant-pagination-list li.disabled span {
    color: #a0a9b7;
    background: #f5f7fa;
    cursor: not-allowed;
}

@media(max-width: 576px) {

    .restaurant-pagination {
        flex-direction: column;
        justify-content: center;
    }

    .restaurant-pagination-info {
        text-align: center;
    }

    .restaurant-pagination-list {
        justify-content: center;
    }
}

</style>

@endsection
