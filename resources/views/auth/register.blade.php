@extends('layouts.master')

@section('head')
    <title>{{ trans('auth.register') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	<link href="{{ asset('/css/uikit.buscocasa.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/strength.min.css') }}" rel="stylesheet">
	<style type="text/css">
		html{
		    background:#2089cf;
		    height:100vh;
		}
	</style>
@endsection

@section('content')
<div class="uk-container uk-container-center">
	<a href="{{url('/')}}"><img src="{{ asset('/images/logo_h_contrast.png') }}" class="uk-align-center uk-margin-top uk-width-large-4-10"></a>
	<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-panel-header uk-width-large-4-10 uk-align-center">
		<div class="uk-h1 uk-text-center uk-text-primary uk-margin-top">
			{{ trans('auth.register') }}
		</div>
		@if (count($errors) > 0)
			<div class="uk-alert uk-alert-danger uk-width-large-8-10 uk-align-center" data-uk-alert>
				<a href="" class="uk-alert-close uk-close"></a>
				<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br>
				<ul class="uk-list">
					@foreach ($errors->all() as $error)
						<li><i class="uk-icon-remove"></i> {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form class="uk-form uk-form-horizontal uk-margin-large-top uk-margin-bottom uk-text-center" id="myform" method="POST" action="{{ url('/auth/register') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="uk-form-row">
				<input type="text" class="uk-width-large-8-10 uk-form-large" name="name" placeholder="{{ trans('auth.name') }}" value="{{ old('name') }}">
			</div>

			<div class="uk-form-row uk-hidden">
				<input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
			</div>

			<div class="uk-form-row">
				<input type="email" class="uk-width-large-8-10 uk-form-large" name="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}">
			</div>

			<div class="uk-form-row">
				<input type="text" class="uk-width-large-8-10 uk-form-large" name="phone" placeholder="{{ trans('auth.phone') }}" value="{{ old('phone') }}">
			</div>

			<div class="uk-form-row">
				<input type="password" id="password" class="uk-width-large-8-10 uk-form-large" placeholder="{{ trans('auth.password') }}" name="password" style="z-index:10;">
			</div>

			<!-- ReCaptcha -->
			<div class="uk-form-row uk-width-large-8-10 uk-align-center">
				<div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
				<p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
			</div>
			<!-- ReCaptcha -->

			<div class="uk-form-row">
				<button type="submit" class="uk-button uk-button-success uk-width-large-8-10 uk-button-large">{{ trans('auth.register_button') }}</button>
			</div>

			<a class="uk-button uk-button-success uk-button-large uk-width-large-8-10 uk-margin-small-top" href="{{ url('/social-auth/facebook') }}" style="background-color:#2089cf;"><i class="uk-icon-facebook"></i> {{ trans('auth.facebook_register') }}</a>

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
	<script async src='https://www.google.com/recaptcha/api.js'></script>
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/uikit.min.js') }}"></script>
    <script async src="{{ asset('/js/strength.min.js') }}"></script>

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
