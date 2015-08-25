@extends('layouts.master')

@section('head')
    <title>{{ trans('auth.reset_password') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	<link href="{{ asset('/css/uikit.flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/strength.min.css') }}" rel="stylesheet">
	<style type="text/css">
		body {
		    background-color: #2089cf;
		    height:100vh;
		}
	</style>
@endsection

@section('content')
<div class="uk-container uk-container-center">
	<a href="{{url('/')}}"><img src="{{ asset('/images/logo_h_contrast.png') }}" class="uk-align-center uk-margin-top uk-width-large-4-10"></a>
	<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-panel-header uk-width-large-4-10 uk-align-center">
		<div class="uk-h1 uk-text-center uk-text-success uk-margin-top">
			{{ trans('auth.reset_password') }}
		</div>
		@if (count($errors) > 0)
			<div class="uk-alert uk-alert-danger" data-uk-alert>
				<a href="" class="uk-alert-close uk-close"></a>
				<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br><br>
				<ul class="uk-list">
					@foreach ($errors->all() as $error)
						<li><i class="uk-icon-remove"></i> {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form class="uk-form uk-form-horizontal uk-margin-large-top uk-margin-bottom uk-text-center" id="myform" method="POST" action="{{ url('/password/reset') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="token" value="{{ $token }}">

			<div class="uk-form-row">
				<input type="email" class="uk-width-large-8-10 uk-form-large" name="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}">
			</div>

			<div class="uk-form-row">
				<input type="password" id="password" class="uk-width-large-8-10 uk-form-large" placeholder="{{ trans('auth.password') }}" name="password">
			</div>

			<div class="uk-form-row">
				<input type="password" class="uk-width-large-8-10 uk-form-large" placeholder="{{ trans('auth.confirm_password') }}" name="password_confirmation">
			</div>

			<div class="uk-form-row">
				<button type="submit" class="uk-button uk-button-success uk-width-large-8-10 uk-button-large">{{ trans('auth.reset_button') }}</button>
			</div>
		</form>
	</div>
</div>
@endsection

@section('navbar')
@endsection

@section('alerts')
@endsection

@section('footer')
@endsection

@section('js')
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/uikit.min.js') }}"></script>
    <script src="{{ asset('/js/strength.min.js') }}"></script>

    <script>
		$(document).ready(function($) {
			$('#password').strength({
	            strengthClass: 'strength',
	            strengthMeterClass: 'strength_meter',
	            strengthButtonClass: 'button_strength',
	            strengthButtonText: 'Show Password',
	            strengthButtonTextToggle: 'Hide Password'
	        });
		});
	</script>
@endsection