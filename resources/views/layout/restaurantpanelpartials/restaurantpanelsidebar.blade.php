
<!-- Restaurant Panel Sidebar -->

<div class="sidebar" id="sidebar">

    @php
        $restaurant = Auth::guard('restaurant')->user();
    @endphp

    <!-- Logo -->
    <div class="sidebar-logo active">

        <a href="{{ route('restaurant.dashboard') }}" class="logo logo-normal">
            <img src="{{ asset($site->site_logo ?? '') }}" alt="Logo">
        </a>

        <a href="{{ route('restaurant.dashboard') }}" class="logo logo-white">
            <img src="{{ asset($site->site_logo ?? '') }}" alt="Logo">
        </a>

        <a href="{{ route('restaurant.dashboard') }}" class="logo-small">
            <img src="{{ asset($site->site_logo ?? '') }}" alt="Logo">
        </a>

        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>

    </div>
    <!-- /Logo -->

    <div class="sidebar-inner slimscroll">

        <div id="sidebar-menu" class="sidebar-menu">

            <ul>

                <!-- Dashboard -->
                <li class="submenu-open">

                    <h6 class="submenu-hdr">Dashboard</h6>

                    <ul>
                        <li class="{{ Request::is('restaurantpanel/dashboard') ? 'active' : '' }}">

                            <a href="{{ route('restaurant.dashboard') }}">
                                <i class="ti ti-layout-grid fs-16 me-2"></i>
                                <span>Dashboard</span>
                            </a>

                        </li>
                    </ul>

                </li>

                <!-- Restaurant Management -->
                <li class="submenu-open">

                    <h6 class="submenu-hdr">Restaurant Management</h6>

                    <ul>

                        <li>
                            <a href="#">
                                <i class="ti ti-layout-grid fs-16 me-2"></i>
                                <span>Table Management</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-calendar-check fs-16 me-2"></i>
                                <span>Table Bookings</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-clock fs-16 me-2"></i>
                                <span>Booking Hours</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-calendar-time fs-16 me-2"></i>
                                <span>Booking Sessions</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-settings fs-16 me-2"></i>
                                <span>Booking Settings</span>
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Menu Management -->
                <li class="submenu-open">

                    <h6 class="submenu-hdr">Menu Management</h6>

                    <ul>

                        <li>
                            <a href="#">
                                <i class="ti ti-bowl-spoon fs-16 me-2"></i>
                                <span>Food Items</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-list-details fs-16 me-2"></i>
                                <span>Categories</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-layers fs-16 me-2"></i>
                                <span>Add-on Groups</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-plus-circle fs-16 me-2"></i>
                                <span>Add-on Options</span>
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Sales -->
                <li class="submenu-open">

                    <h6 class="submenu-hdr">Sales</h6>

                    <ul>

                        <li>
                            <a href="#">
                                <i class="ti ti-shopping-bag fs-16 me-2"></i>
                                <span>Food Orders</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="ti ti-star fs-16 me-2"></i>
                                <span>Reviews</span>
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Account Settings -->
                <li class="submenu-open">

                    <h6 class="submenu-hdr">Account</h6>

                    <ul>

                        <li>
                            <form action="{{ route('restaurant.logout') }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="btn btn-link text-start text-decoration-none w-100">
                                    <i class="ti ti-logout fs-16 me-2"></i>
                                    <span>Logout</span>
                                </button>

                            </form>
                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</div>
<!-- /Restaurant Panel Sidebar -->
