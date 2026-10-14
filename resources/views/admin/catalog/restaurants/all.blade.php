@extends('layout.mainlayout')

@section('title', 'All Restaurants')

@section('content')

    <style>
        .restaurant-page {
            padding: 24px;
            background: #f7f7f9;
            min-height: 100vh;
            color: #171717;
            margin-top: 50px;
        }

        .restaurant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .restaurant-header h2 {
            font-size: 25px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .restaurant-header p {
            color: #777;
            margin: 0;
            font-size: 14px;
        }

        .btn-add-restaurant {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            background: #ff0000;
            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: .2s;
        }

        .btn-add-restaurant:hover {
            background: #cc0000;
            color: #fff;
        }

        .restaurant-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .restaurant-stat {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fff0f0;
            color: #ff0000;
            font-size: 21px;
            flex-shrink: 0;
        }

        .stat-label {
            color: #777;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 23px;
            font-weight: 700;
            line-height: 1.2;
        }

        .restaurant-toolbar {
            background: #fff;
            padding: 18px;
            border: 1px solid #eee;
            border-radius: 12px;
            margin-bottom: 22px;
        }

        .restaurant-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .restaurant-search {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .restaurant-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .restaurant-search input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            outline: none;
        }

        .restaurant-search input:focus,
        .restaurant-filters select:focus {
            border-color: #ff0000;
        }

        .restaurant-filters select {
            min-width: 150px;
            padding: 12px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #fff;
            outline: none;
        }

        .restaurant-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .restaurant-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 14px;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .restaurant-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .07);
        }

        .restaurant-cover {
            height: 175px;
            background: #eee;
            position: relative;
            overflow: hidden;
        }

        .restaurant-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .restaurant-status {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 6px 10px;
            border-radius: 6px;
            background: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        .status-active {
            color: #16803d;
        }

        .status-inactive {
            color: #c62828;
        }

        .restaurant-card-body {
            padding: 18px;
        }

        .restaurant-name-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .restaurant-name {
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 7px;
        }

        .restaurant-cuisine {
            font-size: 13px;
            color: #777;
            margin-bottom: 14px;
        }

        .restaurant-rating {
            background: #16803d;
            color: #fff;
            border-radius: 6px;
            padding: 5px 7px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .restaurant-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            border-top: 1px solid #f0f0f0;
            padding-top: 13px;
            color: #666;
            font-size: 12px;
        }

        .restaurant-info span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .restaurant-info i {
            color: #ff0000;
        }

        .restaurant-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-top: 1px solid #f0f0f0;
            gap: 10px;
        }

        .restaurant-location {
            font-size: 12px;
            color: #777;
            overflow-wrap: anywhere;
        }

        .restaurant-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .restaurant-action {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e5e5;
            border-radius: 7px;
            color: #444;
            background: #fff;
            text-decoration: none;
        }

        .restaurant-action:hover {
            border-color: #ff0000;
            color: #ff0000;
        }

        .empty-restaurants {
            grid-column: 1 / -1;
            background: #fff;
            padding: 45px 20px;
            text-align: center;
            border-radius: 12px;
            color: #777;
        }

        @media (max-width: 1100px) {
            .restaurant-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .restaurant-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .restaurant-page {
                padding: 14px;
            }

            .restaurant-grid,
            .restaurant-stats {
                grid-template-columns: 1fr;
            }

            .restaurant-header {
                align-items: flex-start;
            }

            .restaurant-filters select {
                width: 100%;
            }
        }


        .restaurant-cover-placeholder {
            width: 100%;
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: linear-gradient(135deg, #fff1f1, #ffe0e0);
            color: #dc2626;
            text-align: center;
            overflow: hidden;
        }

        .restaurant-cover-placeholder i {
            font-size: 42px;
        }

        .restaurant-cover-placeholder span {
            font-size: 25px;
            font-weight: 700;
            overflow-wrap: anywhere;
            padding: 0 16px;
        }
    </style>

    <div class="restaurant-page">

        {{-- Page Header --}}
        <div class="restaurant-header">
            <div>
                <h2>Restaurants</h2>
                <p>Manage restaurant partners and their availability.</p>
            </div>

            <a href="{{ route('admin.restaurants.create') }}" class="btn-add-restaurant">
                <i class="fas fa-plus"></i>
                Add Restaurant
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistics --}}
        <div class="restaurant-stats">
            <div class="restaurant-stat">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <div class="stat-label">Total Restaurants</div>
                    <div class="stat-value">{{ $restaurants->count() }}</div>
                </div>
            </div>

            <div class="restaurant-stat">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Active Restaurants</div>
                    <div class="stat-value">
                        {{ $restaurants->where('is_active', true)->count() }}
                    </div>
                </div>
            </div>

            <div class="restaurant-stat">
                <div class="stat-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div>
                    <div class="stat-label">Currently Open</div>
                    <div class="stat-value">
                        {{ $restaurants->where('is_open', true)->count() }}
                    </div>
                </div>
            </div>

            <div class="restaurant-stat">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div>
                    <div class="stat-label">Total Food Items</div>
                    <div class="stat-value">
                        {{ $restaurants->sum(fn($restaurant) => $restaurant->products_count ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters --}}
        <div class="restaurant-toolbar">
            <div class="restaurant-filters">

                <div class="restaurant-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="restaurantSearch" placeholder="Search restaurant, cuisine, or location...">
                </div>

                <select id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <select id="openFilter">
                    <option value="">Open & Closed</option>
                    <option value="open">Open Now</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>

        {{-- Restaurant Cards --}}
        <div class="restaurant-grid" id="restaurantGrid">

            @forelse ($restaurants as $restaurant)

                <div class="restaurant-card"
                    data-search="{{ strtolower(($restaurant->name ?? '') . ' ' . ($restaurant->cuisine_type ?? '') . ' ' . ($restaurant->city ?? '') . ' ' . ($restaurant->address ?? '')) }}"
                    data-status="{{ $restaurant->is_active ? 'active' : 'inactive' }}"
                    data-open="{{ $restaurant->is_open ? 'open' : 'closed' }}">

                    <div class="restaurant-cover">

                        @if ($restaurant->cover_image)
                            <img src="{{ asset($restaurant->cover_image) }}" alt="{{ $restaurant->name }}">
                        @else
                            <div class="restaurant-cover-placeholder">
                                <i class="fas fa-utensils"></i>
                                <span>{{ $restaurant->name }}</span>
                            </div>
                        @endif


                        <span class="restaurant-status {{ $restaurant->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $restaurant->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                    </div>

                    <div class="restaurant-card-body">
                        <div class="restaurant-name-row">
                            <div>
                                <h3 class="restaurant-name">
                                    {{ $restaurant->name }}
                                </h3>

                                <div class="restaurant-cuisine">
                                    {{ $restaurant->cuisine_type ?: 'Cuisine not specified' }}
                                </div>
                            </div>

                            <div class="restaurant-rating">
                                <i class="fas fa-star"></i>
                                {{ number_format((float) ($restaurant->rating ?? 0), 1) }}
                            </div>
                        </div>

                        <div class="restaurant-info">
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ $restaurant->delivery_time ?? 'N/A' }}
                                @if (is_numeric($restaurant->delivery_time))
                                    min
                                @endif
                            </span>

                            <span>
                                <i class="fas fa-motorcycle"></i>
                                ₹{{ number_format((float) ($restaurant->delivery_fee ?? 0), 2) }}
                            </span>

                            <span>
                                <i class="fas fa-circle"
                                    style="font-size:8px;color:{{ $restaurant->is_open ? '#16803d' : '#c62828' }}"></i>
                                {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                            </span>
                        </div>
                    </div>

                    <div class="restaurant-card-footer">
                        <div class="restaurant-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $restaurant->city ?: 'Location not specified' }}
                        </div>


                        <div class="restaurant-actions">

                            {{-- Edit Restaurant --}}
                            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="restaurant-action"
                                title="Edit restaurant">
                                <i class="fas fa-pen"></i>
                            </a>

                            {{-- View Restaurant --}}
                            <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="restaurant-action"
                                title="View restaurant">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Delete Restaurant --}}
                            <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST"
                                style="display:inline-flex;"
                                onsubmit="return confirm('Are you sure you want to delete this restaurant?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="restaurant-action" title="Delete restaurant"
                                    style="background:none; border:none; cursor:pointer; color:#dc2626;">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @empty
                <div class="empty-restaurants">
                    <i class="fas fa-store" style="font-size:36px;margin-bottom:15px;"></i>
                    <h3>No restaurants found</h3>
                    <p>Add your first restaurant to get started.</p>
                </div>
            @endforelse

            <div class="empty-restaurants" id="noSearchResults" style="display:none;">
                <h3>No matching restaurants</h3>
                <p>Try changing your search or filters.</p>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('restaurantSearch');
            const statusFilter = document.getElementById('statusFilter');
            const openFilter = document.getElementById('openFilter');
            const cards = document.querySelectorAll('.restaurant-card');
            const noResults = document.getElementById('noSearchResults');

            function filterRestaurants() {
                const search = searchInput.value.toLowerCase().trim();
                const status = statusFilter.value;
                const open = openFilter.value;
                let visibleCount = 0;

                cards.forEach(function (card) {
                    const matchesSearch = card.dataset.search.includes(search);
                    const matchesStatus = !status || card.dataset.status === status;
                    const matchesOpen = !open || card.dataset.open === open;

                    const visible = matchesSearch && matchesStatus && matchesOpen;

                    card.style.display = visible ? '' : 'none';

                    if (visible) {
                        visibleCount++;
                    }
                });

                noResults.style.display = visibleCount === 0 ? '' : 'none';
            }

            searchInput.addEventListener('input', filterRestaurants);
            statusFilter.addEventListener('change', filterRestaurants);
            openFilter.addEventListener('change', filterRestaurants);
        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* =========================================
               RESTAURANT PAGE - RESPONSIVE SIDEBAR SYNC
            ========================================= */

            const sidebar = document.getElementById('sidebar');
            const restaurantPage = document.querySelector('.restaurant-page');

            // Stop if this page or sidebar is unavailable
            if (!sidebar || !restaurantPage) {
                return;
            }

            function syncRestaurantPageWithSidebar() {

                // Mobile layout
                if (window.innerWidth <= 991) {
                    restaurantPage.style.setProperty(
                        'margin-left',
                        '0px',
                        'important'
                    );

                    restaurantPage.style.setProperty(
                        'width',
                        '100%',
                        'important'
                    );

                    return;
                }

                // Read the actual sidebar width
                const sidebarWidth = Math.round(
                    sidebar.getBoundingClientRect().width
                );

                // Position content after the sidebar
                restaurantPage.style.setProperty(
                    'margin-left',
                    sidebarWidth + 'px',
                    'important'
                );

                // Keep content within the available viewport
                restaurantPage.style.setProperty(
                    'width',
                    'calc(100% - ' + sidebarWidth + 'px)',
                    'important'
                );
            }

            // Initial layout
            syncRestaurantPageWithSidebar();

            // Observe sidebar width changes
            if (typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(function () {
                    syncRestaurantPageWithSidebar();
                });

                observer.observe(sidebar);
            }

            // Recalculate after sidebar toggle animations
            document.addEventListener('click', function () {
                setTimeout(syncRestaurantPageWithSidebar, 100);
                setTimeout(syncRestaurantPageWithSidebar, 300);
            });

            // Recalculate when browser size changes
            window.addEventListener('resize', syncRestaurantPageWithSidebar);

        });
    </script>

@endsection