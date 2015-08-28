@extends('layouts.home')

@section('head')
    <title>{{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
    <meta property="og:image" content="{{ asset('/images/defaults/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('site_description') }}"/>
@endsection

@section('css')
	@parent
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')
	<div class="uk-container uk-container-center" style="max-width:800px">
		<div class="uk-text-center">
			<img class="" src="{{ asset('/images/support/password/reset.png') }}" width="600px">
		</div>
		<div class="uk-margin-top">
			<h1>Politicas de Privacidad</h1>

			<div>
				Sus datos personales serán incluidos en una base de datos y serán utilizados para las siguientes finalidades:
				<ul>
					<li>Lograr una eficiente comunicación relacionada con nuestros productos, servicios, ofertas, promociones, alianzas, estudios, concursos, contenidos, así como los de nuestras compañías vinculadas, y para facilitarle el acceso general a la información de éstos</li>
					<li>Proveer nuestros servicios y productos</li>
					<li>Informar sobre nuevos productos o servicios que estén relacionados con el o los contratado(s) o adquirido(s)</li>
					<li>Dar cumplimiento a obligaciones contraídas con nuestros clientes, proveedores y empleados</li>
					<li>Informar sobre cambios de nuestros productos o servicios</li>
					<li>Evaluar la calidad del servicio</li>
					<li>Realizar estudios internos sobre hábitos de consumo</li>
				</ul>
			</div>

		</div>
 
    </div>
</html>
@endsection

@section('js')
	@parent
@endsection