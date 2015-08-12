<nav class="uk-navbar uk-navbar-attached" style="border-style: solid; border-bottom: 1px solid #e1e1e1;">
    <div class="uk-container uk-container-center">

    	<a href="#menuoffcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
        
        <a class="uk-navbar-brand uk-hidden-small" href="{{ url('/') }}">
            <img src="{{ asset('/images/logo_h.png') }}" alt="logo" class="uk-margin-right" style="height:30px; margin-left:-20px">
        </a>

        <a class="uk-navbar-brand uk-visible-small" href="{{ url('/') }}">
            <img src="{{ asset('/images/logo_h.png') }}" alt="logo" style="max-height:17px">
        </a>

        <!-- This is the off-canvas sidebar -->
        <div id="menuoffcanvas" class="uk-offcanvas">
            <div class="uk-offcanvas-bar">
                <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                    <li class="uk-parent" data-uk-dropdown>
                        <a href="{{ url('/admin') }}">{{ trans('admin.dashboard') }}</a>
                    </li>
                    <li class="uk-parent" data-uk-dropdown>
                        <a href="{{ url('/admin/listings') }}">{{ trans('admin.my_listings_menu') }}</a>
                    </li>
                    <li class="uk-parent" data-uk-dropdown>
                        <a href="{{ url('/admin/messages') }}">{{ trans('admin.my_messages_menu') }}</a>
                    </li>
                    <li class="uk-parent" data-uk-dropdown>
                        <a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}</a>
                    </li>

                    @if(Auth::check())
                    <li class="uk-nav-divider"></li>
                    <li><a href="{{ url('/') }}" target="_blank">{{ trans('admin.live_site') }}</a></li>
                    <li><a href="{{ url('/admin/user/'.Auth::user()->id.'/edit') }}">{{ trans('admin.profile_menu') }}</a></li>
                    <li><a href="{{ url('/auth/logout') }}">{{ trans('admin.logout') }}</a></li>
                    @endif            
                </ul>
            </div>
        </div>
        <!-- This is the off-canvas sidebar -->

        <ul class="uk-navbar-nav uk-vertical-align uk-hidden-small">
            @if(Auth::check())
                @role('admin')
                    <li class="uk-parent" data-uk-dropdown="">
                        <a href="{{ url('/admin/config') }}">{{ trans('admin.system') }}<b class="uk-icon-caret-down uk-margin-small-left"></b></a>
                        <div class="uk-dropdown uk-dropdown-navbar">
                            <ul class="uk-nav uk-nav-navbar">
                                <li><a href="{{ url('/admin/config') }}">{{ trans('admin.configuration') }}</a></li>
                                <li class="uk-nav-divider"></li>
                                <li><a href="{{ url('/admin/users') }}">{{ trans('admin.users') }}</a></li>
                                <li><a href="{{ url('/admin/roles') }}">{{ trans('admin.roles') }}</a></li>
                                <li><a href="{{ url('/admin/permissions') }}">{{ trans('admin.permissions') }}</a></li> 
                            </ul>
                        </div>
                    </li>

                    <li class="uk-parent" data-uk-dropdown="">
                        <a href="{{ url('/admin/categories') }}">{{ trans('admin.categories') }}<b class="uk-icon-caret-down uk-margin-small-left"></b></a>
                        <div class="uk-dropdown uk-dropdown-navbar">
                            <ul class="uk-nav uk-nav-navbar">
                                <li><a href="{{ url('/admin/categories') }}">{{ trans('admin.listing_categories') }}</a></li>
                                <li><a href="{{ url('/admin/feature-categories') }}">{{ trans('admin.feature_categories') }}</a></li>
                                <li><a href="{{ url('/admin/listing-types') }}">{{ trans('admin.listing_types') }}</a></li>
                                <li><a href="{{ url('/admin/listing-statuses') }}">{{ trans('admin.listing_status') }}</a></li>
                                <li><a href="{{ url('/admin/cities') }}">{{ trans('admin.cities') }}</a></li>
                                <li><a href="{{ url('/admin/features') }}">{{ trans('admin.features') }}</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="uk-parent" data-uk-dropdown="">
                        <a href="{{ url('/admin/listings') }}">{{ trans('admin.listings') }}<b class="uk-icon-caret-down uk-margin-small-left"></b></a>
                        <div class="uk-dropdown uk-dropdown-navbar">
                            <ul class="uk-nav uk-nav-navbar">
                                <li><a href="{{ url('/admin/listings') }}">{{ trans('admin.listings') }}</a></li>
                                <li><a href="{{ url('/admin/messages') }}">{{ trans('admin.messages') }}</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="uk-parent" data-uk-dropdown="">
                        <a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}<b class="uk-icon-caret-down uk-margin-small-left"></b></a>
                        <div class="uk-dropdown uk-dropdown-navbar">
                            <ul class="uk-nav uk-nav-navbar">
                                <li><a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}</a></li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li>
                        <a href="{{ url('/admin') }}">{{ trans('admin.home') }}</a>
                    </li>

                    <li>
                        <a href="{{ url('/admin/listings') }}">{{ trans('admin.my_listings_menu') }}</a>
                    </li>

                    <li>
                        <a href="{{ url('/admin/messages') }}">{{ trans('admin.my_messages_menu') }}</a>
                    </li>

                    <li>
                        <a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}</a>
                    </li>
                @endrole

            @endif
        </ul>

    	<div class="uk-navbar-flip uk-hidden-small">
            <ul class="uk-navbar-nav">
                @if (!Auth::check())
                    <li><a href="{{ url('/auth/login') }}">{{ trans('admin.login') }}</a></li>
                    <li><a href="{{ url('/auth/register') }}">{{ trans('admin.register') }}</a></li>
                @else
                    <li class="uk-parent" data-uk-dropdown="">
                        <a href="{{ url('/admin/listings') }}">{{ Auth::user()->name }}<b class="uk-icon-caret-down uk-margin-small-left"></b></a>
                        <div class="uk-dropdown uk-dropdown-navbar">
                            <ul class="uk-nav uk-nav-navbar">
                                <li><a href="{{ url('/admin') }}">{{ trans('admin.dashboard') }}</a></li>
                                <li><a href="{{ url('/admin/listings') }}">{{ trans('admin.my_listings_menu') }}</a></li>
                                <li><a href="{{ url('/admin/messages') }}">{{ trans('admin.my_messages_menu') }}</a></li>
                                <li><a href="{{ url('/admin/pagos') }}">{{ trans('admin.payments') }}</a></li>
                                <li><a href="{{ url('/admin/user/'.Auth::user()->id.'/edit') }}">{{ trans('admin.user_data') }}</a></li>
                                @role('admin')
                                    <li><a href="{{ url('/admin/config') }}">{{ trans('admin.configuration') }}</a></li>
                                @endrole
                                <li class="uk-nav-divider"></li>
                                <li><a target="_blank" href="{{ url('/') }}">{{ trans('admin.live_site') }}</a></li>
                                <li><a href="{{ url('/auth/logout') }}">{{ trans('admin.logout') }}</a></li>
                            </ul><!--/uk-nav-->
                        </div><!--/uk-dropdown-navbar-->
                    </li><!--/uk-parent-->
                @endif
            </ul>
        </div>
    </div>
</nav>