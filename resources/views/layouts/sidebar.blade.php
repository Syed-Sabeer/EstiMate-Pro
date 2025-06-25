@php
    use App\Helpers\Helper;
@endphp
<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper"><a class="logo-anchor" href="{{ route('login') }}"><img class="img-fluid for-light"
                    src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt=""><img class="img-fluid for-dark"
                    src="{{ asset(\App\Helpers\Helper::getLogoDark()) }}" alt=""></a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="{{ route('dashboard') }}"><img class="img-fluid"
                    src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>{{ __('Back') }}</span><i
                                class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="icon-home"></i>
                            <span>{{ __('Dashboard') }}</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>{{ __('Management') }}</h6>
                        </div>
                    </li>
                    @can(['create package'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i class="icon-plus"></i>
                                <span>{{ __('New Packages') }}</span>
                                <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                            </a>
                            <ul class="sidebar-submenu" style="display: none;">
                                <li><a
                                        href="{{ route('dashboard.packages.send_in_one_click') }}">{{ __('Send In One Click') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.goods_without_inspection') }}">{{ __('Goods Without Inspection') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.goods_with_inspection') }}">{{ __('Goods With Inspection') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can(['view package'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i class="icon-package"></i>
                                <span>{{ __('Packages') }}</span>
                                <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                            </a>
                            <ul class="sidebar-submenu" style="display: none;">
                                <li><a href="{{ route('dashboard.packages.index') }}">{{ __('All') }}</a></li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Awaiting']) }}">{{ __('Awaiting') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=In Warehouse']) }}">{{ __('In Warehouse') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Not Identified']) }}">{{ __('Not Identified') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Collection']) }}">{{ __('Collection') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Sending']) }}">{{ __('Sending') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Send Out']) }}">{{ __('Send Out') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Arrived']) }}">{{ __('Arrived') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.packages.index', ['status=Delivered']) }}">{{ __('Delivered') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @canany(['view recipient address', 'view meest address'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i class="icon-location-arrow"></i>
                                <span>{{ __('Address') }}</span>
                                <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                            </a>
                            <ul class="sidebar-submenu" style="display: none;">
                                @can(['view meest address'])
                                    <li>
                                        <a href="{{ route('dashboard.meest-address.index') }}">{{ __('Meest Addresses Abroad') }}</a>
                                    </li>
                                @endcan
                                @can(['view recipient address'])
                                    <li>
                                        <a href="{{ route('dashboard.recipient-address.index') }}">{{ __('Recipient Addresses') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can(['view shipment'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i class="fa fa-plane"></i>
                                <span>{{ __('Shipments') }}</span>
                                <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                            </a>
                            <ul class="sidebar-submenu" style="display:none;">
                                <li><a href="{{ route('dashboard.shipments.index') }}">{{ __('All') }}</a></li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Awaiting']) }}">{{ __('Awaiting') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=In Warehouse']) }}">{{ __('In Warehouse') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Not Identified']) }}">{{ __('Not Identified') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Collection']) }}">{{ __('Collection') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Sending']) }}">{{ __('Sending') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Send Out']) }}">{{ __('Send Out') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Arrived']) }}">{{ __('Arrived') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('dashboard.shipments.index', ['status=Delivered']) }}">{{ __('Delivered') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    <li class="sidebar-main-title">
                        <div>
                            <h6>{{ __('Setup') }}</h6>
                        </div>
                    </li>
                    @canany(['view recipient country', 'view purchase country'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i class="icon-world"></i>
                                <span>{{ __('Country') }}</span>
                                <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                            </a>
                            <ul class="sidebar-submenu" style="display: none;">
                                @can(['view recipient country'])
                                    <li><a
                                            href="{{ route('dashboard.recipient-country.index') }}">{{ __('Recipient Country') }}</a>
                                    </li>
                                @endcan
                                @can(['view purchase country'])
                                    <li><a
                                            href="{{ route('dashboard.purchase-country.index') }}">{{ __('Purchase Country') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can(['view product'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav"
                                href="{{ route('dashboard.products.index') }}">
                                <i class="icon-shopping-cart-full"></i>
                                <span>{{ __('Products') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can(['view delivery type'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav"
                                href="{{ route('dashboard.delivery-types.index') }}">
                                <i class="icon-truck"></i>
                                <span>{{ __('Delivery Types') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can(['view color'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard.colors.index') }}">
                                <i class="icon-paint-bucket"></i>
                                <span>{{ __('colors') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can(['view user'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard.user.index') }}">
                                <i class="icon-user"></i>
                                <span>{{ __('users') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can(['view role'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}"
                                href="{{ route('dashboard.roles.index') }}">
                                <i class="icon-pin"></i>
                                <span>Roles</span>
                            </a>
                        </li>
                    @endcan
                    @can(['view permission'])
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('dashboard.permissions.*') ? 'active' : '' }}"
                                href="{{ route('dashboard.permissions.index') }}">
                                <i class="icon-key"></i>
                                <span>Permissions</span>
                            </a>
                        </li>
                    @endcan
                    @can(['create setting'])
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard.setting.index') }}">
                            <i class="icon-settings"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    </li>
                    @endcan

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
