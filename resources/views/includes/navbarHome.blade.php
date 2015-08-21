<div class="uk-container uk-container-center uk-margin-top" style="background-color: #fff; max-height:103px; width:100%">
    <div>
        <a class="uk-hidden-small" href="{{ url('/') }}" style="height:45px">
            <img src="{{ asset('/images/logo_h.png') }}" alt="logo" style="height:45px">
        </a>

        @if(!Auth::check())
            <a href="{{ url('/auth/register') }}" class="uk-button uk-button-primary uk-button-large uk-align-right uk-margin-small-top uk-hidden-small">{{ trans('admin.register_publish_free') }}</a>
        @else
        <a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-primary uk-button-large uk-align-right uk-margin-small-top uk-hidden-small">{{ trans('admin.publish_property') }}</a>
        @endif
    </div>

    <!-- This is the off-canvas sidebar -->
    <div id="menuoffcanvas" class="uk-offcanvas">
        <div class="uk-offcanvas-bar">
            <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                <li class="uk-parent" data-uk-dropdown="">
                    <a href="{{ url('/') }}">{{ trans('frontend.menu_home') }}</a>
                </li>
                <li class="uk-parent" data-uk-dropdown="">
                    <a href="{{ url('/ventas') }}">{{ trans('frontend.menu_sales') }}</a>
                </li>
                <li class="uk-parent" data-uk-dropdown="">
                    <a href="{{ url('/arriendos') }}">{{ trans('frontend.menu_leases') }}</a>
                </li>
                <li class="uk-parent" data-uk-dropdown="">
                    <a href="{{ url('#') }}">{{ trans('frontend.menu_new_proyects') }}</a>
                </li>

                <li class="uk-nav-divider"></li>

                @if (!Auth::check())
                    <li><a href="{{ url('/auth/login') }}">{{ trans('frontend.menu_login') }}</a></li>
                    <li><a href="{{ url('/auth/register') }}">{{ trans('frontend.menu_register') }}</a></li>
                @else
                    <li><a href="{{ url('/admin/user/'.Auth::user()->id.'/edit') }}">{{ Auth::user()->name }}</a></li>
                    <li><a href="{{ url('/admin') }}">{{ trans('frontend.menu_my_properties') }}</a></li>
                    <li><a href="{{ url('/admin/user/'.Auth::user()->id.'/edit') }}">{{ trans('frontend.menu_user_data') }}</a></li>
                    <li><a href="{{ url('/auth/logout') }}">{{ trans('frontend.menu_logout') }}</a></li>                    
                @endif
            </ul>
        </div>
    </div>
    

    <nav class="uk-navbar uk-margin-top" style="width:100%; position:inherit;">
        <a href="#menuoffcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
        
        <a class="uk-visible-small" href="{{ url('/') }}">
            <img src="{{ asset('/images/logo_h.png') }}" alt="logo" style="width:80%">
        </a>

        <ul class="uk-navbar-nav uk-vertical-align uk-hidden-small">
        @if(Request::is('/'))
            <li class="uk-active">
        @else
            <li>
        @endif
                <a href="{{ url('/') }}">{{ trans('frontend.menu_home') }}</a>
            </li>

        @if(Request::is('ventas') || Request::is('ventas/*'))
            <li class="uk-active">
        @else
            <li>
        @endif
                <a href="{{ url('/ventas') }}">{{ trans('frontend.menu_sales') }}</a>
            </li>

        @if(Request::is('arriendos') || Request::is('arriendos/*'))
            <li class="uk-active">
        @else
            <li>
        @endif
                <a href="{{ url('/arriendos') }}">{{ trans('frontend.menu_leases') }}</a>
            </li>

        @if(Request::is('#') || Request::is('#/*'))
            <li class="uk-active">
        @else
            <li>
        @endif
                <a href="{{ url('#') }}">{{ trans('frontend.menu_new_proyects') }}</a>
            </li>
        </ul>

        <div class="uk-navbar-flip uk-hidden-small">
            <ul class="uk-navbar-nav">
                @if (!Auth::check())
                    <li><a href="{{ url('/auth/login') }}">{{ trans('frontend.menu_login') }}</a></li>
                    <li><a href="{{ url('/auth/register') }}">{{ trans('frontend.menu_register') }}</a></li>
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
                                <li class="uk-nav-divider"></li>
                                <li><a href="{{ url('/auth/logout') }}">{{ trans('frontend.menu_logout') }}</a></li>
                            </ul><!--/uk-nav-->
                        </div><!--/uk-dropdown-navbar-->
                    </li><!--/uk-parent-->
                @endif
            </ul>
        </div>
    </nav>
</div>