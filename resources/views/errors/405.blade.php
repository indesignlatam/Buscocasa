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
	<link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
	<style>
		body {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
		}
		.container {
			text-align: center;
			vertical-align: middle;
			color: #B0BEC5;
			font-weight: 300;
			font-family: 'Lato';
		}
		.title {
			font-size: 72px;
			line-height:100%;
		}
	</style>
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')
	
	<div class="container uk-container uk-margin-large" style="height:200px">
		<div class="content">
			<div class="title">A request was made of a resource using a request method not supported by that resource.</div>
		</div>
	</div>

@endsection

@section('js')
@endsection