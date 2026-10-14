@extends('layout.mainlayout')

@section('title', 'Add Restaurant')

@section('content')



    <style>
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
                        <label for="cuisine_type">Cuisine Type</label>
                        <input type="text" id="cuisine_type" name="cuisine_type"
                            placeholder="e.g. Indian, Chinese, Italian">
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
                        <input type="number" step="any" id="latitude" name="latitude" placeholder="Optional">
                    </div>

                    <div class="restaurant-form-group">
                        <label for="longitude">Longitude</label>
                        <input type="number" step="any" id="longitude" name="longitude" placeholder="Optional">
                    </div>

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


@endsection