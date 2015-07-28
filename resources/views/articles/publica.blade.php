@extends('layouts.home')

@section('head')
    <title>Akainik Inmobiliaria - Inicio</title>
    <meta property="og:title" content="Publica con nosotros - Akainik Inmobiliaria"/>
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content=""/>
@endsection

@section('css')
	@parent
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')
	<div class="uk-cover-background uk-position-relative">
        <img class="" src="{{ asset('/images/backgrounds/publica.jpg') }}" alt="">
	</div>

	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-margin-large-bottom" style="margin-top:30px">
            <div class="uk-width-3-5">
                <h3 class="uk-text-primary">Vende o Arrienda tu inmueble con Akainik</h3>
                <p>
                    Vender o arrendar tu propiedad con Akanik no puede ser más fácil. Simplemente llena el formulario abajo y un asesor te contactará para acompañarte en el proceso de consignación. La consignación y fotografía profesional no tiene costo alguno y no pedimos exclusividad.
                </p>
                <form id="create_form" class="uk-form uk-margin-top" method="POST" action="{{ url('/request') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <input class="uk-width-large-1-1 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="Nombre" value="{{ old('name') }}">

                    <div class="uk-flex uk-flex-center">
                        <input class="uk-width-large-1-2 uk-margin-small-bottom uk-margin-right uk-form-large" type="text" name="phone" placeholder="Telefono" value="{{ old('phone') }}">

                        <input class="uk-width-large-1-2 uk-margin-small-bottom uk-form-large" type="text" name="email" placeholder="Correo" value="{{ old('email') }}">
                    </div>
                    

                    <input class="uk-width-large-1-1 uk-margin-small-bottom uk-form-large" type="text" name="direction" placeholder="Dirección" value="{{ old('direction') }}">

                    <textarea class="uk-form-large uk-width-large-1-1" rows="10" placeholder="Mensaje"></textarea>
                </form>
                
            </div>
            <div class="uk-width-2-5">
                <ul class="uk-list">
                    <li><i class="uk-icon-check uk-text-primary"></i> Consignaciones sin costo</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> Fotografía profesional</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> No pedimos exclusividad</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> Especialistas en inmuebles residenciales</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> Alto nivel de mercadeo</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> Atención personalizado y amable</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> Acompañamiento en todo momento</li>
                    <li><i class="uk-icon-check uk-text-primary"></i> Asesoría basado en años de experiencia</li>
                </ul>
            </div>
        </div>
    </div>
</html>
@endsection

@section('js')
	@parent
@endsection