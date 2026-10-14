
@extends('layout.mainlayout')

@section('title', 'Edit Restaurant')

@section('content')


<style>
/* Restaurant edit page */
.restaurant-page {
    width: 100%;
    box-sizing: border-box;
    padding: 20px 24px 35px;
    color: #252525;
}

.restaurant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    padding: 24px 28px;
    margin: 60px 0 24px;
    background: #fff;
    border: 1px solid #eee;
    border-left: 5px solid #ff0000;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
}

.restaurant-header h2 {
    margin: 0 0 8px;
    color: #171717;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -.5px;
}

.restaurant-header p {
    margin: 0;
    color: #777;
    font-size: 14px;
    line-height: 1.6;
}

.restaurant-form-card {
    background: #fff;
    padding: 26px;
    border: 1px solid #eee;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, .035);
}

.form-section-title {
    color: #222;
    font-size: 17px;
    font-weight: 750;
    margin: 0 0 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.restaurant-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.restaurant-form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.restaurant-form-group.full-width {
    grid-column: 1 / -1;
}

.restaurant-form-group label {
    font-size: 13px;
    font-weight: 650;
    color: #333;
}

.restaurant-form-group input,
.restaurant-form-group select,
.restaurant-form-group textarea {
    width: 100%;
    padding: 12px 13px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    color: #222;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
    transition: border-color .2s, box-shadow .2s;
}

.restaurant-form-group input:focus,
.restaurant-form-group select:focus,
.restaurant-form-group textarea:focus {
    border-color: #ff0000;
    box-shadow: 0 0 0 3px rgba(255, 0, 0, .08);
}

.restaurant-form-group input[type="file"] {
    padding: 9px;
    background: #fffafa;
}

.restaurant-form-group small {
    color: #777;
    font-size: 12px;
    line-height: 1.5;
}

.restaurant-current-image {
    display: block;
    max-width: 100%;
    width: 160px;
    height: 110px;
    object-fit: cover;
    border-radius: 9px;
    border: 1px solid #eee;
}

.restaurant-image-preview {
    display: none;
    width: 160px;
    height: 110px;
    object-fit: cover;
    margin-top: 8px;
    border-radius: 9px;
    border: 1px solid #ddd;
}

.restaurant-form-actions {
    display: flex;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 12px;
    border-top: 1px solid #eee;
    padding-top: 22px;
}

.restaurant-form-actions .btn {
    padding: 11px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 650;
}

.restaurant-form-actions .btn-danger {
    background: #ff0000;
    border-color: #ff0000;
}

.restaurant-form-actions .btn-danger:hover {
    background: #cc0000;
    border-color: #cc0000;
}

.restaurant-page .alert-danger {
    border-radius: 10px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .restaurant-page {
        padding: 15px;
    }

    .restaurant-header {
        margin-top: 30px;
        padding: 20px;
    }

    .restaurant-header h2 {
        font-size: 23px;
    }

    .restaurant-form-card {
        padding: 18px;
    }
}

@media (max-width: 600px) {
    .restaurant-form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .restaurant-form-group.full-width {
        grid-column: auto;
    }

    .restaurant-header {
        flex-direction: column;
        align-items: stretch;
    }

    .restaurant-form-actions {
        flex-direction: column-reverse;
    }

    .restaurant-form-actions .btn {
        width: 100%;
    }
}
</style>


<div class="restaurant-page">

    <div class="restaurant-header">
        <div>
            <h2>Edit Restaurant</h2>
            <p>Update restaurant details for BAKASURAN.</p>
        </div>

        <a href="{{ route('admin.restaurants.index') }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Restaurants
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="restaurant-form-card">

        <form action="{{ route('admin.restaurants.update', $restaurant->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <h3 class="form-section-title">Basic Information</h3>

            <div class="restaurant-form-grid">

                <div class="restaurant-form-group">
                    <label for="name">Restaurant Name *</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $restaurant->name) }}" required>
                </div>

                <div class="restaurant-form-group">
                    <label for="cuisine_type">Cuisine Type</label>
                    <input type="text" id="cuisine_type" name="cuisine_type"
                           value="{{ old('cuisine_type', $restaurant->cuisine_type) }}">
                </div>

                <div class="restaurant-form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                              rows="4">{{ old('description', $restaurant->description) }}</textarea>
                </div>

                <div class="restaurant-form-group">
                    <label for="logo">Restaurant Logo</label>

                    @if ($restaurant->logo)
                        <img src="{{ asset($restaurant->logo) }}"
                             alt="Current logo"
                             style="width:100px;height:100px;object-fit:cover;margin-bottom:10px;">
                    @endif

                    <input type="file" id="logo" name="logo"
                           accept="image/jpeg,image/png,image/webp">
                    <small>Leave empty to keep the current logo.</small>
                </div>

                <div class="restaurant-form-group">
                    <label for="cover_image">Cover Image</label>

                    @if ($restaurant->cover_image)
                        <img src="{{ asset($restaurant->cover_image) }}"
                             alt="Current cover"
                             style="width:160px;height:100px;object-fit:cover;margin-bottom:10px;">
                    @endif

                    <input type="file" id="cover_image" name="cover_image"
                           accept="image/jpeg,image/png,image/webp">
                    <small>Leave empty to keep the current cover image.</small>
                </div>

            </div>

            <h3 class="form-section-title">Address Information</h3>

            <div class="restaurant-form-grid">

                <div class="restaurant-form-group full-width">
                    <label for="address">Street Address *</label>
                    <textarea id="address" name="address"
                              rows="2" required>{{ old('address', $restaurant->address) }}</textarea>
                </div>

                <div class="restaurant-form-group">
                    <label for="city">City *</label>
                    <input type="text" id="city" name="city"
                           value="{{ old('city', $restaurant->city) }}" required>
                </div>

                <div class="restaurant-form-group">
                    <label for="state">State *</label>
                    <input type="text" id="state" name="state"
                           value="{{ old('state', $restaurant->state) }}" required>
                </div>

                <div class="restaurant-form-group">
                    <label for="pincode">Pincode *</label>
                    <input type="text" id="pincode" name="pincode"
                           value="{{ old('pincode', $restaurant->pincode) }}"
                           maxlength="10" required>
                </div>

                <div class="restaurant-form-group">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="any" id="latitude" name="latitude"
                           value="{{ old('latitude', $restaurant->latitude) }}">
                </div>

                <div class="restaurant-form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude"
                           value="{{ old('longitude', $restaurant->longitude) }}">
                </div>

            </div>

            <h3 class="form-section-title">Contact Information</h3>

            <div class="restaurant-form-grid">

                <div class="restaurant-form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone"
                           value="{{ old('phone', $restaurant->phone) }}" required>
                </div>

                <div class="restaurant-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $restaurant->email) }}">
                </div>

            </div>

            <h3 class="form-section-title">Delivery & Timings</h3>

            <div class="restaurant-form-grid">

                <div class="restaurant-form-group">
                    <label for="opening_time">Opening Time *</label>
                    <input type="time" id="opening_time" name="opening_time"
                           value="{{ old('opening_time', $restaurant->opening_time ? substr((string) $restaurant->opening_time, 0, 5) : '') }}"
                           required>
                </div>

                <div class="restaurant-form-group">
                    <label for="closing_time">Closing Time *</label>
                    <input type="time" id="closing_time" name="closing_time"
                           value="{{ old('closing_time', $restaurant->closing_time ? substr((string) $restaurant->closing_time, 0, 5) : '') }}"
                           required>
                </div>

                <div class="restaurant-form-group">
                    <label for="delivery_time">Delivery Time (minutes) *</label>
                    <input type="number" id="delivery_time" name="delivery_time"
                           min="1" value="{{ old('delivery_time', $restaurant->delivery_time) }}"
                           required>
                </div>

                <div class="restaurant-form-group">
                    <label for="delivery_fee">Delivery Fee (₹) *</label>
                    <input type="number" id="delivery_fee" name="delivery_fee"
                           min="0" step="0.01"
                           value="{{ old('delivery_fee', $restaurant->delivery_fee) }}"
                           required>
                </div>

                <div class="restaurant-form-group">
                    <label for="minimum_order">Minimum Order (₹) *</label>
                    <input type="number" id="minimum_order" name="minimum_order"
                           min="0" step="0.01"
                           value="{{ old('minimum_order', $restaurant->minimum_order) }}"
                           required>
                </div>

                <div class="restaurant-form-group">
                    <label for="average_rating">Average Rating</label>
                    <input type="number" id="average_rating" name="average_rating"
                           min="0" max="5" step="0.1"
                           value="{{ old('average_rating', $restaurant->average_rating ?? 0) }}">
                </div>

                <div class="restaurant-form-group">
                    <label for="is_active">Restaurant Status *</label>
                    <select id="is_active" name="is_active" required>
                        <option value="1" {{ (string) old('is_active', (int) $restaurant->is_active) === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ (string) old('is_active', (int) $restaurant->is_active) === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="restaurant-form-group">
                    <label for="is_open">Availability *</label>
                    <select id="is_open" name="is_open" required>
                        <option value="1" {{ (string) old('is_open', (int) $restaurant->is_open) === '1' ? 'selected' : '' }}>Open</option>
                        <option value="0" {{ (string) old('is_open', (int) $restaurant->is_open) === '0' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

            </div>

            <div class="restaurant-form-actions">
                <a href="{{ route('admin.restaurants.index') }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save"></i> Update Restaurant
                </button>
            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Preview newly selected images
    function setupImagePreview(inputId) {
        const input = document.getElementById(inputId);

        if (!input) return;

        const preview = document.createElement('img');
        preview.className = 'restaurant-image-preview';
        preview.alt = 'Selected image preview';

        input.insertAdjacentElement('afterend', preview);

        let previousUrl = null;

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];

            if (previousUrl) {
                URL.revokeObjectURL(previousUrl);
                previousUrl = null;
            }

            if (!file) {
                preview.style.display = 'none';
                preview.removeAttribute('src');
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image.');
                input.value = '';
                preview.style.display = 'none';
                return;
            }

            previousUrl = URL.createObjectURL(file);
            preview.src = previousUrl;
            preview.style.display = 'block';
        });
    }

    setupImagePreview('logo');
    setupImagePreview('cover_image');

    // Validate rating
    const ratingInput = document.getElementById('average_rating');

    if (ratingInput) {
        ratingInput.addEventListener('input', function () {
            const rating = Number(ratingInput.value);

            if (
                ratingInput.value !== '' &&
                (rating < 0 || rating > 5)
            ) {
                ratingInput.setCustomValidity(
                    'Rating must be between 0 and 5.'
                );
            } else {
                ratingInput.setCustomValidity('');
            }
        });
    }

    // Prevent accidental repeated submission
    const form = document.querySelector('.restaurant-form-card form');

    if (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            const button = form.querySelector('button[type="submit"]');

            if (button) {
                button.disabled = true;
                button.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Updating...';
            }
        });
    }

});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('sidebar');
    const restaurantPage = document.querySelector('.restaurant-page');

    function syncRestaurantPage() {
        if (!sidebar || !restaurantPage) return;

        if (window.innerWidth <= 991) {
            restaurantPage.style.setProperty(
                'margin-left', '0px', 'important'
            );
            restaurantPage.style.setProperty(
                'width', '100%', 'important'
            );
            return;
        }

        const sidebarWidth = Math.round(
            sidebar.getBoundingClientRect().width
        );

        restaurantPage.style.setProperty(
            'margin-left',
            sidebarWidth + 'px',
            'important'
        );

        restaurantPage.style.setProperty(
            'width',
            'calc(100% - ' + sidebarWidth + 'px)',
            'important'
        );
    }

    syncRestaurantPage();

    if (sidebar && typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(syncRestaurantPage);
        observer.observe(sidebar);
    }

    window.addEventListener('resize', syncRestaurantPage);

    document.addEventListener('click', function () {
        setTimeout(syncRestaurantPage, 100);
        setTimeout(syncRestaurantPage, 300);
    });

});
</script>


@endsection
