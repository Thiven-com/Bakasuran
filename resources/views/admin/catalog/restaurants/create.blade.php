@extends('layout.mainlayout')

@section('title', 'Add Restaurant')

@section('content')

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css"
/>


<style>
    .restaurant-map-section {
    margin: 25px 0;
}

.restaurant-map-section h3 {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 10px;
}

#restaurantMap {
    width: 100%;
    height: 400px;
    border: 1px solid #ddd;
    border-radius: 12px;
    z-index: 1;
}

.map-instructions {
    font-size: 13px;
    color: #777;
    margin-bottom: 12px;
}

.leaflet-control-geocoder {
    max-width: 320px;
}

@media (max-width: 600px) {
    #restaurantMap {
        height: 320px;
    }
}
</style>

    <style>

        .restaurant-type-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.restaurant-type-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    background: #fff;
    font-size: 13px;
    transition: 0.2s ease;
}

.restaurant-type-checkbox:has(input:checked) {
    border-color: #ff0000;
    background: #fff5f5;
    color: #cc0000;
}

.restaurant-type-checkbox input {
    width: auto;
    margin: 0;
    accent-color: #ff0000;
}
        /* Restaurant Page Header */
        .restaurant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 24px 28px;
            margin-bottom: 24px;
            margin-top: 80px;
            background: #ffffff;
            border: 1px solid #eeeeee;
            border-left: 5px solid #ff0000;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.04);
        }

        .restaurant-header h2 {
            position: relative;
            margin: 0 0 8px;
            color: #171717;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .restaurant-header p {
            margin: 0;
            color: #777777;
            font-size: 14px;
            line-height: 1.6;
        }

        .btn-add-restaurant {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            padding: 12px 20px;
            background: #ff0000;
            color: #ffffff;
            border: 1px solid #ff0000;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(255, 0, 0, 0.15);
            transition: background 0.25s ease,
                color 0.25s ease,
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }

        .btn-add-restaurant:hover {
            background: #cc0000;
            border-color: #cc0000;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 7px 18px rgba(255, 0, 0, 0.22);
        }

        .btn-add-restaurant:focus-visible {
            outline: 3px solid rgba(255, 0, 0, 0.25);
            outline-offset: 3px;
        }

        /* Tablet */
        @media (max-width: 768px) {
            .restaurant-header {
                padding: 20px;
                gap: 16px;
            }

            .restaurant-header h2 {
                font-size: 23px;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            .restaurant-header {
                align-items: stretch;
                flex-direction: column;
                padding: 18px;
            }

            .restaurant-header h2 {
                font-size: 21px;
            }

            .btn-add-restaurant {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>

    <div class="restaurant-page">

        <div class="restaurant-header">
            <div>
                <h2>Add Restaurant</h2>
                <p>Add a new restaurant to BAKASURAN.</p>
            </div>

            <a href="{{ route('admin.restaurants.index') }}" class="btn-add-restaurant">
                <i class="fas fa-arrow-left"></i>
                Back to Restaurants
            </a>
        </div>


        <div class="restaurant-form-card">

            <style>
                .restaurant-form-card {
                    background: #fff;
                    padding: 24px;
                    border: 1px solid #eee;
                    border-radius: 14px;
                }

                .form-section-title {
                    font-size: 17px;
                    font-weight: 700;
                    margin: 0 0 20px;
                    padding-bottom: 12px;
                    border-bottom: 1px solid #eee;
                }

                .restaurant-form-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 18px;
                    margin-bottom: 28px;
                }

                .restaurant-form-group {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                }

                .restaurant-form-group.full-width {
                    grid-column: 1 / -1;
                }

                .restaurant-form-group label {
                    font-size: 13px;
                    font-weight: 600;
                }

                .restaurant-form-group input,
                .restaurant-form-group select,
                .restaurant-form-group textarea {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    outline: none;
                    box-sizing: border-box;
                }

                .restaurant-form-group input:focus,
                .restaurant-form-group select:focus,
                .restaurant-form-group textarea:focus {
                    border-color: #ff0000;
                }

                .restaurant-form-actions {
                    display: flex;
                    justify-content: flex-end;
                    gap: 12px;
                    border-top: 1px solid #eee;
                    padding-top: 20px;
                }

                .restaurant-btn {
                    padding: 12px 20px;
                    border-radius: 8px;
                    font-weight: 600;
                    text-decoration: none;
                    cursor: pointer;
                }

                .restaurant-btn-primary {
                    background: #ff0000;
                    color: #fff;
                    border: none;
                }

                .restaurant-btn-secondary {
                    background: #fff;
                    color: #333;
                    border: 1px solid #ddd;
                }

                @media (max-width: 650px) {
                    .restaurant-form-grid {
                        grid-template-columns: 1fr;
                    }

                    .restaurant-form-group.full-width {
                        grid-column: auto;
                    }
                }
            </style>


            <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="form-section-title">Basic Information</h3>

                <div class="restaurant-form-grid">

                    <div class="restaurant-form-group">
                        <label for="name">Restaurant Name *</label>
                        <input type="text" id="name" name="name" placeholder="Enter restaurant name" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="cuisine_type">Cuisine Types</label>
                        <input type="text" id="cuisine_type" name="cuisine_type" placeholder="e.g. Indian, Chinese, Italian"
                            value="{{ old('cuisine_type') }}">
                        <small>Enter multiple cuisines separated by commas.</small>
                    </div>
                   <div class="restaurant-form-group full-width">
    <label>Restaurant Types *</label>

    <div class="restaurant-type-checkboxes">
        @foreach ($types as $type)
            @if ($type->status)
                <label class="restaurant-type-checkbox">
                    <input
                        type="checkbox"
                        name="restaurant_types[]"
                        value="{{ $type->id }}"
                        {{ in_array($type->id, old('restaurant_types', [])) ? 'checked' : '' }}
                    >

                    <span>{{ $type->name }}</span>
                </label>
            @endif
        @endforeach
    </div>

    @error('restaurant_types')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    @error('restaurant_types.*')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

                    <div class="restaurant-form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Describe the restaurant"></textarea>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="logo">Restaurant Logo</label>
                        <input type="file" id="logo" name="logo" accept="image/*">
                    </div>

                    <div class="restaurant-form-group">
                        <label for="cover_image">Cover Image</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*">
                    </div>

                </div>

                <h3 class="form-section-title">Address Information</h3>

                <div class="restaurant-form-grid">

                    <div class="restaurant-form-group full-width">
                        <label for="address">Street Address *</label>
                        <textarea id="address" name="address" rows="2" placeholder="Enter full address" required></textarea>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" placeholder="Enter city" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="state">State *</label>
                        <input type="text" id="state" name="state" placeholder="Enter state" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="pincode">Pincode *</label>
                        <input type="text" id="pincode" name="pincode" maxlength="10" placeholder="Enter pincode" required>
                    </div>

                    <div class="restaurant-form-group">
    <label for="latitude">Latitude</label>
    <input
        type="number"
        step="any"
        id="latitude"
        name="latitude"
        value="{{ old('latitude') }}"
        placeholder="Enter latitude manually"
    >
</div>

<div class="restaurant-form-group">
    <label for="longitude">Longitude</label>
    <input
        type="number"
        step="any"
        id="longitude"
        name="longitude"
        value="{{ old('longitude') }}"
        placeholder="Enter longitude manually"
    >
</div>

                </div>

                <!-- Restaurant Location Map -->
                <div class="restaurant-map-section">

                    <h3>Restaurant Location</h3>

                    <p class="map-instructions">
                        Search for your restaurant address and select the correct location.
                        You can also drag the marker to adjust the position.
                    </p>

                    <div id="restaurantMap"></div>

                </div>

                <h3 class="form-section-title">Contact Information</h3>

                <div class="restaurant-form-grid">

                    <div class="restaurant-form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="restaurant@example.com">
                    </div>

                </div>

                <h3 class="form-section-title">Delivery & Timings</h3>

                <div class="restaurant-form-grid">

                    <div class="restaurant-form-group">
                        <label for="opening_time">Opening Time *</label>
                        <input type="time" id="opening_time" name="opening_time" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="closing_time">Closing Time *</label>
                        <input type="time" id="closing_time" name="closing_time" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="delivery_time">Delivery Time (minutes) *</label>
                        <input type="number" id="delivery_time" name="delivery_time" min="1" placeholder="e.g. 30" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="delivery_fee">Delivery Fee (₹) *</label>
                        <input type="number" id="delivery_fee" name="delivery_fee" min="0" step="0.01" value="0" required>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="minimum_order">Minimum Order (₹) *</label>
                        <input type="number" id="minimum_order" name="minimum_order" min="0" step="0.01" value="0" required>
                    </div>

                    {{-- Price for Two --}}
                    <div class="restaurant-form-group">
                        <label for="price_for_two">Price for Two (₹) *</label>
                        <input type="number" id="price_for_two" name="price_for_two" min="0" step="0.01"
                            value="{{ old('price_for_two', 0) }}" placeholder="e.g. 500" required>
                        @error('price_for_two')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Discount Percentage --}}
                    <div class="restaurant-form-group">
                        <label for="discount_percentage">Discount Percentage (%)</label>
                        <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" step="1"
                            value="{{ old('discount_percentage', 0) }}" placeholder="e.g. 20">
                        @error('discount_percentage')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Display Order --}}
                    <div class="restaurant-form-group">
                        <label for="sort_order">Display Order</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" step="1"
                            value="{{ old('sort_order', 0) }}" placeholder="e.g. 1">
                        @error('sort_order')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Featured Restaurant --}}
                    <div class="restaurant-form-group">
                        <label for="is_featured">Featured Restaurant</label>
                        <select id="is_featured" name="is_featured">
                            <option value="0" {{ old('is_featured', '0') == '0' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                        @error('is_featured')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="restaurant-form-group">
                        <label for="average_rating">Initial Rating</label>
                        <input type="number" id="average_rating" name="average_rating" min="0" max="5" step="0.1" value="0">
                    </div>

                    <div class="restaurant-form-group">
                        <label for="is_active">Restaurant Status *</label>
                        <select id="is_active" name="is_active" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="restaurant-form-group">
                        <label for="is_open">Availability *</label>
                        <select id="is_open" name="is_open" required>
                            <option value="1" selected>Open</option>
                            <option value="0">Closed</option>
                        </select>
                    </div>

                </div>

                <div class="restaurant-form-actions">
                    <a href="{{ route('admin.restaurants.index') }}" class="restaurant-btn restaurant-btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="restaurant-btn restaurant-btn-primary">
                        Save Restaurant
                    </button>
                </div>

            </form>
        </div>


    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* =====================================
               RESTAURANT FORM SCRIPT
            ===================================== */

            // Sidebar responsive synchronization
            const sidebar = document.getElementById('sidebar');
            const restaurantPage = document.querySelector('.restaurant-page');

            function syncRestaurantPage() {
                if (!sidebar || !restaurantPage) return;

                if (window.innerWidth <= 991) {
                    restaurantPage.style.setProperty('margin-left', '0px', 'important');
                    restaurantPage.style.setProperty('width', '100%', 'important');
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


            /* =====================================
               IMAGE PREVIEW
            ===================================== */

            function addImagePreview(inputId) {
                const input = document.getElementById(inputId);

                if (!input) return;

                const preview = document.createElement('img');

                preview.style.display = 'none';
                preview.style.width = '120px';
                preview.style.height = '90px';
                preview.style.objectFit = 'cover';
                preview.style.marginTop = '10px';
                preview.style.borderRadius = '8px';
                preview.style.border = '1px solid #ddd';

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
                        alert('Please select a valid image file.');
                        input.value = '';
                        preview.style.display = 'none';
                        return;
                    }

                    previousUrl = URL.createObjectURL(file);
                    preview.src = previousUrl;
                    preview.style.display = 'block';
                });
            }

            addImagePreview('logo');
            addImagePreview('cover_image');


            /* =====================================
               RATING VALIDATION
            ===================================== */

            const ratingInput = document.getElementById('average_rating');

            if (ratingInput) {
                ratingInput.addEventListener('input', function () {
                    const rating = Number(ratingInput.value);

                    if (ratingInput.value !== '' &&
                        (rating < 0 || rating > 5)) {
                        ratingInput.setCustomValidity(
                            'Rating must be between 0 and 5.'
                        );
                    } else {
                        ratingInput.setCustomValidity('');
                    }
                });
            }


            /* =====================================
               FORM SUBMISSION CHECK
            ===================================== */

            const form = document.querySelector('.restaurant-form-card form');

            if (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        form.reportValidity();
                    }
                });
            }

        });
    </script>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================
       1. GET FORM ELEMENTS
    ===================================== */

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    const stateInput = document.getElementById('state');
    const pincodeInput = document.getElementById('pincode');

    const mapElement = document.getElementById('restaurantMap');

    if (!mapElement || !latitudeInput || !longitudeInput) {
        return;
    }

    if (typeof L === 'undefined' || !L.Control.Geocoder) {
        console.error('Leaflet or Leaflet Geocoder is not loaded.');
        return;
    }


    /* =====================================
       2. DEFAULT MAP LOCATION
    ===================================== */

    // Default location: Bengaluru
const defaultLat = 12.9716;
const defaultLng = 77.5946;

    const initialLat = parseFloat(latitudeInput.value);
    const initialLng = parseFloat(longitudeInput.value);

    function isValidCoordinates(lat, lng) {
        return Number.isFinite(lat) &&
            Number.isFinite(lng) &&
            lat >= -90 && lat <= 90 &&
            lng >= -180 && lng <= 180;
    }

    const hasInitialLocation = isValidCoordinates(
        initialLat,
        initialLng
    );

    const startLat = hasInitialLocation ? initialLat : defaultLat;
    const startLng = hasInitialLocation ? initialLng : defaultLng;


    /* =====================================
       3. INITIALIZE LEAFLET MAP
    ===================================== */

   const map = L.map('restaurantMap').setView(
    [startLat, startLng],
    hasInitialLocation ? 16 : 12
);

// Bengaluru map boundaries
const bangaloreBounds = L.latLngBounds(
    [12.75, 77.35],
    [13.20, 77.85]
);

map.setMaxBounds(bangaloreBounds);
map.options.maxBoundsViscosity = 1.0;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);


    /* =====================================
       4. CREATE DRAGGABLE MARKER
    ===================================== */

    const marker = L.marker(
        [startLat, startLng],
        { draggable: true }
    ).addTo(map);

    if (hasInitialLocation) {
        marker.bindPopup('Restaurant Location');
    }


    /* =====================================
       5. UPDATE COORDINATE INPUTS
    ===================================== */

    function updateCoordinates(lat, lng) {

        latitudeInput.value = Number(lat).toFixed(7);
        longitudeInput.value = Number(lng).toFixed(7);
    }


    /* =====================================
       6. UPDATE ADDRESS INFORMATION
    ===================================== */

    function updateAddressFields(data) {

        if (!data) return;

        const location = data.address || {};

        if (addressInput && data.display_name) {
            addressInput.value = data.display_name;
        }

        if (cityInput) {
            cityInput.value =
                location.city ||
                location.town ||
                location.village ||
                location.municipality ||
                location.county ||
                '';
        }

        if (stateInput) {
            stateInput.value = location.state || '';
        }

        if (pincodeInput) {
            pincodeInput.value = location.postcode || '';
        }
    }


    /* =====================================
       7. ADDRESS SEARCH
    ===================================== */

    
const geocoder = L.Control.geocoder({

    defaultMarkGeocode: false,

    placeholder: 'Search Bengaluru areas, streets, locations...',

    position: 'topleft',

    geocoder: L.Control.Geocoder.nominatim({

        geocodingQueryParams: {

            // India only
            countrycodes: 'in',

            // Bengaluru approximate geographic boundary
            viewbox: '77.35,13.20,77.85,12.75',

            // Restrict results to the specified viewbox
            bounded: 1,

            // Return complete address information
            addressdetails: 1,

            // Maximum search results
            limit: 10

        }

    })

}).addTo(map);


    // When admin selects a search result

    geocoder.on('markgeocode', function (event) {

        const result = event.geocode;

        const lat = result.center.lat;
        const lng = result.center.lng;

        map.setView([lat, lng], 17);

        marker.setLatLng([lat, lng]);

        updateCoordinates(lat, lng);

        updateAddressFields({
            display_name: result.name,
            address: result.properties.address
        });

        marker.bindPopup('Selected Restaurant Location')
            .openPopup();

    });


    /* =====================================
       8. REVERSE GEOCODING
    ===================================== */

    async function reverseGeocode(lat, lng) {

        try {

            const url =
                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&addressdetails=1`;

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Unable to fetch address');
            }

            const data = await response.json();

            updateAddressFields(data);

        } catch (error) {

            console.error('Reverse geocoding error:', error);

        }
    }


    /* =====================================
       9. SELECT LOCATION BY MAP CLICK
    ===================================== */

    map.on('click', function (event) {

        const lat = event.latlng.lat;
        const lng = event.latlng.lng;

        marker.setLatLng([lat, lng]);

        updateCoordinates(lat, lng);

        reverseGeocode(lat, lng);

        marker.bindPopup('Selected Restaurant Location')
            .openPopup();

    });


    /* =====================================
       10. DRAG MARKER TO SELECT LOCATION
    ===================================== */

    marker.on('dragend', function (event) {

        const position = event.target.getLatLng();

        updateCoordinates(position.lat, position.lng);

        reverseGeocode(position.lat, position.lng);

        marker.bindPopup('Selected Restaurant Location')
            .openPopup();

    });


    /* =====================================
       11. MANUAL LATITUDE & LONGITUDE
    ===================================== */

    function updateMapFromInputs() {

        const lat = parseFloat(latitudeInput.value);
        const lng = parseFloat(longitudeInput.value);

        // Wait until both coordinates are valid
        if (!isValidCoordinates(lat, lng)) {
            return;
        }

        const newPosition = [lat, lng];

        // Move marker
        marker.setLatLng(newPosition);

        // Move map
        map.setView(newPosition, 17);

        marker.bindPopup('Manually Entered Location')
            .openPopup();

    }


    // Update map when latitude or longitude is entered

    latitudeInput.addEventListener('input', updateMapFromInputs);

    longitudeInput.addEventListener('input', updateMapFromInputs);


    /* =====================================
       12. MAP RESIZE FIX
    ===================================== */

    setTimeout(function () {

        map.invalidateSize();

    }, 300);


});

</script>


@endsection