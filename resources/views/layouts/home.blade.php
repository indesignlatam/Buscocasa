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
                                </p>

                                <div class="" style="margin-top:25px">
                                    <a onclick="share('{{ url('') }}')" class="uk-icon-hover uk-icon-large uk-icon-facebook"></a> 
                                    <a class="uk-icon-hover uk-icon-large uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text={{ Settings::get('site_name') }}%20{{ url('/') }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
                                    <a href="https://plus.google.com/share?url={{ url('/') }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-hover uk-icon-large uk-icon-google-plus"></a>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">VENTAS</h3>
                            <ul class="uk-list">
                                <li><a href="{{ url('/ventas?category_id=1') }}" style="text-decoration:none" class="uk-text-contrast">Casas</a></li>
                                <li><a href="{{ url('/ventas?category_id=2') }}" style="text-decoration:none" class="uk-text-contrast">Apartamentos</a></li>
                                <li><a href="{{ url('/ventas?category_id=3') }}" style="text-decoration:none" class="uk-text-contrast">Oficinas</a></li>
                                <li><a href="{{ url('/ventas?category_id=4') }}" style="text-decoration:none" class="uk-text-contrast">Lotes</a></li>
                                <li><a href="{{ url('/ventas?category_id=5') }}" style="text-decoration:none" class="uk-text-contrast">Fincas</a></li>
                                <li><a href="{{ url('/ventas?category_id=6') }}" style="text-decoration:none" class="uk-text-contrast">Bodegas</a></li>
                            </ul>
                        </div>
                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">ARRIENDOS</h3>
                            <ul class="uk-list">
                                <li><a href="{{ url('/arriendos?category_id=1') }}" style="text-decoration:none" class="uk-text-contrast">Casas</a></li>
                                <li><a href="{{ url('/arriendos?category_id=2') }}" style="text-decoration:none" class="uk-text-contrast">Apartamentos</a></li>
                                <li><a href="{{ url('/arriendos?category_id=3') }}" style="text-decoration:none" class="uk-text-contrast">Oficinas</a></li>
                                <li><a href="{{ url('/arriendos?category_id=4') }}" style="text-decoration:none" class="uk-text-contrast">Lotes</a></li>
                                <li><a href="{{ url('/arriendos?category_id=5') }}" style="text-decoration:none" class="uk-text-contrast">Fincas</a></li>
                                <li><a href="{{ url('/arriendos?category_id=6') }}" style="text-decoration:none" class="uk-text-contrast">Bodegas</a></li>
                            </ul>
                        </div>
                        
                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">NOSOTROS</h3>
                            <ul class="uk-list">
                                <li class="uk-text-contrast">Quienes Somos</li>
                                <li class="uk-text-contrast">Nuestros Servicios</li>
                                <li class="uk-text-contrast">Preguntas Frequentes</li>
                                <li class="uk-text-contrast">Blog</li>
                                <li class="uk-text-contrast">Publíca</li>
                            </ul>
                        </div>

                        <div class="uk-width-large-2-10 uk-width-medium-1-6 uk-hidden-small uk-margin-large-top uk-text-right">
                            <h3 class="uk-text-contrast">OTROS SITIOS</h3>
                            <ul class="uk-list">
                                <li><a href="{{ url('/') }}" style="text-decoration:none" class="uk-text-contrast">BuscoCasa</a></li>
                                <li><a href="{{ url('/') }}" style="text-decoration:none" class="uk-text-contrast">BuscoCarro</a></li>
                                <li><a href="{{ url('/') }}" style="text-decoration:none" class="uk-text-contrast">BuscoMoto</a></li>
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

            <script type="text/javascript">
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : {{ Settings::get('facebook_app_id') }},
                        xfbml      : true,
                        version    : 'v2.3'
                    });
                };
                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));

                function share(path){
                    FB.ui({
                        method: 'share_open_graph',
                        action_type: 'og.shares',
                        action_properties: JSON.stringify({
                        object: path,
                    })
                    }, function(response){
                    });
                }
            </script>

            <!-- Other Scripts -->
            {!! Analytics::render() !!}
        @show
    </body>
</html>