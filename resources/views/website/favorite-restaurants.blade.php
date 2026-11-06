@extends('layouts.website')

@section('content')

    <style>
        .fav-page {
            background: #f8f8f8;
            min-height: 100vh;
            padding-bottom: 70px;
        }

        /* Hero */
        .fav-hero {
            position: relative;
            padding: 75px 20px 85px;
            background: linear-gradient(120deg, #171717, #38302a);
            color: #fff;
            text-align: center;
            overflow: hidden;
        }

        .fav-hero::before {
            content: "♥";
            position: absolute;
            font-size: 260px;
            color: rgba(255, 255, 255, .035);
            right: 8%;
            top: -80px;
        }

        .fav-hero h1 {
            font-size: clamp(30px, 5vw, 46px);
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
        }

        .fav-hero p {
            color: #ddd;
            font-size: 16px;
            margin: 0;
            position: relative;
        }

        .fav-content {
            max-width: 1200px;
            margin: -35px auto 0;
            padding: 0 18px;
            position: relative;
            z-index: 2;
        }

        .fav-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .07);
            margin-bottom: 30px;
        }

        .fav-topbar h3 {
            font-size: 19px;
            font-weight: 750;
            margin: 0;
            color: #222;
        }

        .fav-count {
            background: #fff0ee;
            color: #e64b35;
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
        }

        /* Restaurant cards */
        .fav-card {
            background: #fff;
            border-radius: 17px;
            overflow: hidden;
            height: 100%;
            border: 1px solid #eee;
            transition: .3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .045);
        }

        .fav-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, .11);
        }

        .fav-image-wrap {
            height: 225px;
            position: relative;
            overflow: hidden;
            background: #eee;
        }

        .fav-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .fav-card:hover .fav-image-wrap img {
            transform: scale(1.07);
        }

        .fav-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .35), transparent 60%);
            pointer-events: none;
        }

        .fav-heart {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            color: #e84c43;
            font-size: 23px;
            text-decoration: none;
            border: none;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .15);
            cursor: pointer;
        }

        .fav-heart:hover {
            background: #fff0ef;
            color: #d93630;
        }

        .fav-card-body {
            padding: 22px;
        }

        .fav-card-title {
            color: #222;
            font-size: 20px;
            font-weight: 750;
            margin-bottom: 9px;
        }

        .fav-cuisine {
            color: #777;
            font-size: 14px;
            margin-bottom: 17px;
        }

        .fav-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 19px;
        }

        .fav-rating {
            background: #e9f7ed;
            color: #198754;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 750;
        }

        .fav-rating i {
            margin-right: 4px;
        }

        .fav-rating-text {
            color: #888;
            font-size: 13px;
        }

        .fav-actions {
            display: flex;
            gap: 10px;
        }

        .fav-view-btn,
        .fav-remove-btn {
            flex: 1;
            text-align: center;
            padding: 11px 9px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .fav-view-btn {
            background: #e94e36;
            color: #fff;
            border: 1px solid #e94e36;
        }

        .fav-view-btn:hover {
            background: #ca3826;
            border-color: #ca3826;
            color: #fff;
        }

        .fav-remove-btn {
            background: #fff;
            color: #dc3545;
            border: 1px solid #f0c5c9;
            cursor: pointer;
        }

        .fav-remove-btn:hover {
            background: #dc3545;
            color: #fff;
        }

        /* Empty state */
        .fav-empty {
            background: #fff;
            padding: 65px 20px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .05);
        }

        .fav-empty-icon {
            width: 95px;
            height: 95px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0ee;
            color: #e94e36;
            border-radius: 50%;
            font-size: 42px;
            margin: 0 auto 22px;
        }

        .fav-empty h3 {
            font-weight: 800;
            color: #222;
            margin-bottom: 10px;
        }

        .fav-empty p {
            color: #777;
            margin-bottom: 25px;
        }

        .fav-explore-btn {
            display: inline-block;
            background: #e94e36;
            color: #fff;
            padding: 13px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
        }

        .fav-explore-btn:hover {
            background: #ca3826;
            color: #fff;
        }

        .fav-pagination {
            margin-top: 35px;
        }

        @media(max-width: 576px) {
            .fav-hero {
                padding: 55px 15px 70px;
            }

            .fav-topbar {
                padding: 16px;
            }

            .fav-topbar h3 {
                font-size: 16px;
            }

            .fav-image-wrap {
                height: 205px;
            }

            .fav-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="fav-page">

        {{-- Hero Section --}}
        <div class="fav-hero">
            <h1>My Favourite Restaurants <span style="color:#ff6655;">♥</span></h1>
            <p>Your favourite food destinations, all in one place.</p>
        </div>

        <div class="fav-content">

            {{-- Top Bar --}}
            <div class="fav-topbar">
                <h3>Saved Restaurants</h3>

                <span class="fav-count">
                    ♥ {{ $restaurants->total() }} Favourites
                </span>
            </div>

            <div class="row">

                @forelse ($restaurants as $restaurant)

                        <div class="col-lg-4 col-md-6 mb-4" id="favorite-card-{{ $restaurant->id }}">

                            <div class="fav-card">

                                {{-- Restaurant Image --}}
                                <div class="fav-image-wrap">

                                    <img src="{{ !empty($restaurant->cover_image)
                    ? asset($restaurant->cover_image)
                    : asset('images/restaurant-placeholder.jpg') }}" alt="{{ $restaurant->name }}"
                                        loading="lazy">

                                    <div class="fav-image-overlay"></div>

                                    <button type="button" class="fav-heart" title="Remove from favourites"
                                        onclick="removeFavourite({{ $restaurant->id }}, this)">
                                        ♥
                                    </button>

                                </div>

                                {{-- Restaurant Details --}}
                                <div class="fav-card-body">

                                    <h4 class="fav-card-title">
                                        {{ $restaurant->name }}
                                    </h4>

                                    <p class="fav-cuisine">
                                        <i class="fas fa-utensils"></i>
                                        {{ $restaurant->cuisine_type ?: 'Various cuisines' }}
                                    </p>

                                    <div class="fav-meta">

                                        <span class="fav-rating">
                                            ★ {{ number_format((float) ($restaurant->average_rating ?? 0), 1) }}
                                        </span>

                                        <span class="fav-rating-text">
                                            Customer Rating
                                        </span>

                                    </div>

                                    <div class="fav-actions">

                                        <a href="{{ route('restaurant.details', ['slug' => $restaurant->slug]) }}"
                                            class="fav-view-btn">
                                            View Restaurant <i class="fas fa-arrow-right"></i>
                                        </a>

                                        <button type="button" class="fav-remove-btn"
                                            onclick="removeFavourite({{ $restaurant->id }}, this)">
                                            <i class="fas fa-trash-alt"></i>
                                            Remove
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                @empty

                    <div class="col-12">

                        <div class="fav-empty">

                            <div class="fav-empty-icon">♥</div>

                            <h3>No Favourite Restaurants Yet</h3>

                            <p>
                                You haven't saved any restaurants yet.
                                Explore our restaurants and save the ones you love!
                            </p>

                            <a href="{{ route('restaurants') }}" class="fav-explore-btn">
                                <i class="fas fa-utensils"></i>
                                Explore Restaurants
                            </a>

                        </div>

                    </div>

                @endforelse

            </div>

            {{-- Pagination --}}
            @if ($restaurants->hasPages())
                <div class="fav-pagination d-flex justify-content-center">
                    {{ $restaurants->links() }}
                </div>
            @endif

        </div>

    </div>

    {{-- Remove Favourite AJAX --}}
    <script>
        async function removeFavourite(restaurantId, button) {

            if (!confirm('Remove this restaurant from your favourites?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page.');
                return;
            }

            button.disabled = true;

            try {

                const response = await fetch(
                    "{{ url('/favorite-restaurants') }}/" + restaurantId,
                    {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                // Redirect to home page if not authenticated
                if (response.status === 401 || response.status === 403 || response.redirected) {
                    window.location.href = "{{ route('favorite-restaurants') }}";
                    return;
                }

                const data = await response.json();

                if (!response.ok || data.status === false) {
                    throw new Error(data.message || 'Unable to remove favourite restaurant.');
                }

                const card = document.getElementById('favorite-card-' + restaurantId);

                if (card) {
                    card.style.transition = 'all .3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(.9)';

                    setTimeout(() => {
                        card.remove();

                        const remainingCards = document.querySelectorAll('[id^="favorite-card-"]');

                        if (remainingCards.length === 0) {
                            window.location.reload();
                        }
                    }, 300);
                }

            } catch (error) {

                console.error(error);
                alert(error.message);

                button.disabled = false;
            }
        }
    </script>

@endsection