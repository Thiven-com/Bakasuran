<!-- Sidebar -->
<div class="sidebar" id="sidebar">
        @php
                $admin = Auth::guard('admin')->user();
        @endphp
        <!-- Logo -->
        <div class="sidebar-logo active">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-normal">
                        <img src="{{asset($site->site_logo ?? ' ' ?? ' ')}}" alt="Img">
                </a>
                <a href="{{ route('admin.dashboard') }}" class="logo logo-white">
                        <img src="{{asset($site->site_logo ?? ' ' ?? ' ')}}" alt="Img">
                </a>
                <a href="{{ route('admin.dashboard') }}" class="logo-small">
                        <img src="{{asset($site->site_logo ?? ' ' ?? ' ')}}" alt="Img">
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                        <i data-feather="chevrons-left" class="feather-16"></i>
                </a>
        </div>
        <!-- /Logo -->

        <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                        <ul>
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Dashboard</h6>
                                        <ul>
                                                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.dashboard') }}"><i
                                                                        class="ti ti-layout-grid fs-16 me-2"></i><span>Dashboard</span></a>
                                                </li>
                                                @if (isset($admin->role) && $admin->role == 'super_admin')

                                                        <li class="{{ Request::is('admin/todayDeals') ? 'active' : '' }}">
                                                                <a href="{{route('admin.todayDeals')}}">
                                                                        <i class="ti ti-cards fs-16 me-2"></i><span>Today
                                                                                Deals</span></a>
                                                        </li>
                                                        <li class="{{ Request::is('admin/sections') ? 'active' : '' }}">
                                                                <a href="{{route('admin.sections.index')}}">
                                                                        <i
                                                                                class="ti ti-cards fs-16 me-2"></i><span>Sections</span></a>
                                                        </li>
                                                        <li class="{{ Request::is('admin/createOrder') ? 'active' : '' }}">
                                                                <a href="{{route('admin.createOrder')}}">
                                                                        <i class="ti ti-cards fs-16 me-2"></i><span>Create
                                                                                Manual Order</span></a>
                                                        </li>
                                                @endif

                                        </ul>
                                </li>
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Sales</h6>
                                        <ul>
                                                <li class="{{ Request::is('admin/orders') ? 'active' : '' }}"><a
                                                                href="{{route('admin.orders.index', ['payment_status' => 'paid'])}}"><i
                                                                        class="ti ti-box fs-16 me-2"></i>Orders</a></li>
                                                <li class="{{ Request::is('admin/preorders') ? 'active' : '' }}"><a
                                                                href="{{route('admin.preorders', ['payment_status' => 'paid'])}}"><i
                                                                        class="ti ti-box fs-16 me-2"></i>PreOrders</a>
                                                </li>
                                                <li class="{{ Request::is('admin/manualorders') ? 'active' : '' }}"><a
                                                                href="{{route('admin.manualorders', ['payment_status' => 'paid'])}}"><i
                                                                        class="ti ti-box fs-16 me-2"></i>ManualOrders</a>
                                                </li>
                                                <li class="{{ Request::is('admin/manualorders') ? 'active' : '' }}"><a
                                                                href="{{route('admin.sku.report')}}"><i
                                                                        class="ti ti-box fs-16 me-2"></i>SKU Order Reports</a>
                                                </li>

                                                <li class="{{ Request::is('admin/abandonedCart') ? 'active' : '' }}"><a
                                                                href="{{route('admin.abandonedCart')}}"><i
                                                                        class="ti ti-basket fs-16 me-2"></i><span>Abandoned
                                                                        Cart</span></a>
                                                </li>
                                        </ul>
                                </li>
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Main</h6>
                                        <ul>
                                                <li class="submenu">
                                                        <a href="javascript:void(0);"
                                                                class="{{ Request::is('admin/products', 'admin/categories', 'admin/brands', 'admin/banners', 'admin/units', 'admin/attributes') ? 'active subdrop' : '' }}"><i
                                                                        class="ti ti-article fs-16 me-2"></i><span>Inventory</span><span
                                                                        class="menu-arrow"></span></a>

                                                        <ul>
                                                                <li
                                                                        class="{{ Request::is('admin/products') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.products.index')}}"><i
                                                                                        data-feather="box"></i><span>Products</span></a>
                                                                </li>
                                                                <li
                                                                        class="{{ Request::is('admin/categories') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.categories.index')}}"><i
                                                                                        class="ti ti-list-details fs-16 me-2"></i><span>Category</span></a>
                                                                </li>

                                                                <li
                                                                        class="{{ Request::is('admin/brands') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.brands.index')}}"><i
                                                                                        class="ti ti-triangles fs-16 me-2"></i><span>Brands</span></a>
                                                                </li>
                                                                <li
                                                                        class="{{ Request::is('admin/banners') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.banners.index')}}"><i
                                                                                        class="ti ti-triangles fs-16 me-2"></i><span>Banners</span></a>
                                                                </li>

                                                                <li
                                                                        class="{{ Request::is('admin/units') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.units.index')}}"><i
                                                                                        class="ti ti-brand-unity fs-16 me-2"></i><span>Units</span></a>
                                                                </li>
                                                                <li
                                                                        class="{{ Request::is('admin/attributes') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.attributes.index')}}"><i
                                                                                        class="ti ti-checklist fs-16 me-2"></i><span>Attributes</span></a>
                                                                </li>
                                                                <li
                                                                        class="{{ Request::is('admin/coupons') ? 'active' : '' }}">
                                                                        <a href="{{route('admin.coupons.index')}}"><i
                                                                                        class="ti ti-ticket fs-16 me-2"></i><span>Coupons</span></a>
                                                                </li>

                                                        </ul>
                                                </li>
                                                @if (isset($admin->role) && $admin->role == 'super_admin')

                                                        <li class="{{ Request::is('admin/customers') ? 'active' : '' }}"><a
                                                                        href="{{route('admin.customers.index')}}"><i
                                                                                class="ti ti-users-group fs-16 me-2"></i><span>Customers</span></a>
                                                        </li>
                                                        <li class="{{ Request::is('admin/staffs') ? 'active' : '' }}"><a
                                                                        href="{{route('admin.staffs.index')}}"><i
                                                                                class="ti ti-users-group fs-16 me-2"></i><span>Staffs</span></a>
                                                        </li>
                                                        <li class="{{ Request::is('admin/shippingZones') ? 'active' : '' }}"><a
                                                                        href="{{route('admin.shippingZones.index')}}"><i
                                                                                class="ti ti-shopping-cart fs-16 me-2"></i><span>Shipping
                                                                                Zones</span></a>
                                                        </li>
                                                        <li class="{{ Request::is('admin/homeoffers') ? 'active' : '' }}"><a
                                                                        href="{{route('admin.homeoffers.index')}}"><i
                                                                                class="ti ti-shopping-cart fs-16 me-2"></i><span>Home
                                                                                Offers</span></a>
                                                        </li>
                                                @endif
                                                {{-- <li class="{{ Request::is('admin/flash-sales') ? 'active' : '' }}">
                                                        <a href="{{route('admin.flash-sales.index')}}">
                                                                <i class="ti ti-cards fs-16 me-2"></i><span>Flash
                                                                        Sales</span></a>
                                                </li> --}}

                                        </ul>
                                </li>
                                @if (isset($admin->role) && $admin->role == 'super_admin')

                                        <li class="submenu-open">
                                                <h6 class="submenu-hdr">Additional</h6>
                                                <ul>
                                                        <li class="submenu">
                                                                <a href="javascript:void(0);"
                                                                        class="{{ Request::is('admin/blogs', 'admin/blog/categories') ? 'active subdrop' : '' }}"><i
                                                                                class="ti ti-article fs-16 me-2"></i><span>Blogs</span><span
                                                                                class="menu-arrow"></span></a>
                                                                <ul>
                                                                        <li
                                                                                class="{{ Request::is('admin/blogs') ? 'active' : '' }}">
                                                                                <a href="{{ route('admin.blogs.index') }}"><i
                                                                                                class="ti ti-article fs-16 me-2"></i><span>Blogs</span></a>
                                                                        </li>
                                                                        <li
                                                                                class="{{ Request::is('admin/blog/categories') ? 'active' : '' }}">
                                                                                <a
                                                                                        href="{{ route('admin.blog.categories.index') }}"><i
                                                                                                class="ti ti-folder fs-16 me-2"></i></i><span>Blog
                                                                                                Category</span></a>
                                                                        </li>
                                                                </ul>
                                                        </li>
                                                        <li class="{{ Request::is('admin/faqs') ? 'active' : '' }}"><a
                                                                        href="{{route('admin.faqs.index')}}"><i
                                                                                class="ti ti-folder fs-16 me-2"></i><span>Faq's</span></a>
                                                        </li>
                                                </ul>
                                        </li>
                                @endif
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Settings</h6>
                                        <ul>
                                                @if (isset($admin->role) && $admin->role == 'super_admin')

                                                        <li class="{{ Request::is('admin/settings/company') ? 'active' : '' }}">
                                                                <a href="{{route('admin.settings.company')}}"><i
                                                                                class="ti ti-world fs-16 me-2"></i><span>Company
                                                                                Settings</span><span
                                                                                class="menu-arr ow"></span></a>
                                                        </li>
                                                        <li class="{{ Request::is('admin/activity.logs') ? 'active' : '' }}">
                                                                <a href="{{route('admin.activity.logs')}}"><i
                                                                                class="ti ti-menu fs-16 me-2"></i><span>Staff
                                                                                Activity</span><span
                                                                                class="menu-arr ow"></span></a>
                                                        </li>
                                                @endif
                                                <li>
                                                        <a href="{{ route('admin.logout') }}"
                                                                class="{{ Request::is('signin') ? 'active' : '' }}"><i
                                                                        class="ti ti-logout fs-16 me-2"></i><span>Logout</span>
                                                        </a>
                                                </li>
                                        </ul>
                                </li>
                        </ul>
                </div>
        </div>
</div>