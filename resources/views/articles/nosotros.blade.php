@extends('layouts.home')

@section('head')
    <title>Akainik Inmobiliaria - Inicio</title>
    <meta property="og:title" content="Akainik Inmobiliaria"/>
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
        <img class="" src="{{ asset('/images/backgrounds/publica.jpg') }}" width="100%" alt="">
	</div>

	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-margin-large-top uk-margin-large-bottom">
			<h3 class="uk-margin-bottom uk-margin-top-remove">Publíca con nosotros</h3>
        </div>
    </div>
</html>
@endsection

@section('js')
	@parent
@endsection