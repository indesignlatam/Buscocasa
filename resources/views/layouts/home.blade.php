<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="BuscoCasa.co">
        @section('head')
            <title>{{ Settings::get('site_name') }}</title>
            <meta name="description" content="{{ Settings::get('site_description') }}">
            <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
            <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
            <meta property="og:type" content="website"/>
            <meta property="og:description" content="{{ Settings::get('site_description') }}" />
        @show
        <meta property="fb:app_id" content="{{ Settings::get('facebook_app_id') }}"/>
        <meta property="og:site_name" content="{{ Settings::get('site_name') }}"/>

        @section('css')
            <link href="{{ asset('/css/uikit.buscocasa.min.css') }}" rel="stylesheet">
        @show
    </head>

    <style>
    	nav{
    		z-index:2;
            
            height: 50px;
            position: absolute;
            top:50px;
    	}
        .uk-navbar-brand{
            text-shadow: none;
            color:white;
        }
        #footer{
            width: 100%;
            height: 350px;
            background-color: #2e3234;
        }
    </style>

    <body>
        @section('navbar')
            @include('includes.navbarHome')
        @show

        @section('header')
        @show
        
        @yield ('content')
        
        @section('footer')
            <div class="" id="footer">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid">
                        <div class="uk-width-large-2-10 uk-width-small-1-1 uk-margin-large-top">
                            <div class="uk-text-center-small">
                                <img src="{{ asset('/images/logo_h_contrast.png') }}">
                                <br>
                                <p class="uk-text-contrast">
                                    Mail: comercial@buscocasa.co<br>
                                    Tel:  (+57 1) 8796436<br>
                                    Whatsapp: (+57) 3203999043
                                </p>

                                {{-- <div class="" style="margin-top:25px">
                                    <a onclick="share('{{ '/'.strtolower(str_plural($listing->listingType->name)).'/'.$listing->slug }}')" class="uk-icon-button uk-icon-facebook"></a> 
                                    <a class="uk-icon-button uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text=Hello%20world%20{{ url('/'.strtolower(str_plural($listing->listingType->name)).'/'.$listing->slug) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
                                    <a href="https://plus.google.com/share?url={{ url('/'.strtolower(str_plural($listing->listingType->name)).'/'.$listing->slug) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus"></a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">VENTAS</h3>
                            <ul class="uk-list">
                                <li class="uk-text-contrast">Casas</li>
                                <li class="uk-text-contrast">Apartamentos</li>
                                <li class="uk-text-contrast">Oficinas</li>
                                <li class="uk-text-contrast">Lotes</li>
                                <li class="uk-text-contrast">Fincas</li>
                                <li class="uk-text-contrast">Bodegas</li>
                            </ul>
                        </div>
                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">ARRIENDOS</h3>
                            <ul class="uk-list">
                                <li class="uk-text-contrast">Casas</li>
                                <li class="uk-text-contrast">Apartamentos</li>
                                <li class="uk-text-contrast">Oficinas</li>
                                <li class="uk-text-contrast">Lotes</li>
                                <li class="uk-text-contrast">Fincas</li>
                                <li class="uk-text-contrast">Bodegas</li>
                            </ul>
                        </div>
                        
                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">NOSOTROS</h3>
                            <ul class="uk-list">
                                <li class="uk-text-contrast">Quienes Somos</li>
                                <li class="uk-text-contrast">Nuestros Servicios</li>
                                <li class="uk-text-contrast">Preguntas Frequentes</li>
                                <li class="uk-text-contrast">Tarifas</li>
                                <li class="uk-text-contrast">Publíca</li>
                            </ul>
                        </div>

                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">OTROS SITIOS</h3>
                            <ul class="uk-list">
                                <li class="uk-text-contrast">BuscoCasa</li>
                                <li class="uk-text-contrast">BuscoCarro</li>
                                <li class="uk-text-contrast">BuscoMoto</li>
                            </ul>
                        </div>
                    </div>

                    <div class="footer uk-margin-large-top"><!--/data-uk-scrollspy="{cls:'uk-animation-slide-bottom'}"-->
                        <div class="uk-text-center uk-text-middle uk-margin-bottom uk-text-contrast uk-text-small">
                            <a href="http://www.indesigncolombia.com">
                                <img src="{{ asset('/images/indesign/logo_h_contrast.png') }}" alt="logo" width="100px">
                            </a>
                            <br>
                            Designed and developed by <a href="http://www.indesigncolombia.com">Indesign Colombia</a>
                        </div>
                    </div><!--/footer-->

                </div>
            </div>
        @show

        @section('alerts')
            
        @show
        

        <!-- Scripts -->
        @section('js')
            <!-- Necessary Scripts -->
            <script src="{{ asset('/js/jquery.min.js') }}"></script>
            <script src="{{ asset('/js/uikit.min.js') }}"></script>

            <!-- Other Scripts -->
            {!! Analytics::render() !!}
        @show
    </body>
</html>