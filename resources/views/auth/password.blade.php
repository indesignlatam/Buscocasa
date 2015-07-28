@extends('layouts.master')

@section('head')
    <title>{{ trans('auth.password_reset') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	<link href="{{ asset('/css/uikit.buscocasa.min.css') }}" rel="stylesheet">
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
		<div class="uk-h1 uk-text-center uk-text-primary uk-margin-top">
			{{ trans('auth.password_reset') }}
		</div>

		@if (count($errors) > 0)
			<div class="uk-alert uk-alert-danger uk-width-large-8-10 uk-align-center" data-uk-alert>
    			<a href="" class="uk-alert-close uk-close"></a>
				<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br><br>
				<ul class="uk-list">
					@foreach ($errors->all() as $error)
						<li><i class="uk-icon-remove"></i> {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@if (Session::has('success') && count(Session::get('success')) > 0)
			<div class="uk-alert uk-alert-danger">
				<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br><br>
				<ul class="uk-list">
					@foreach (Session::get('success') as $error)
						<li><i class="uk-icon-check"></i> {{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form class="uk-form uk-form-horizontal uk-margin-large-top uk-text-center" role="form" method="POST" action="{{ url('/password/email') }}">
			@if (session('status'))
				<div class="uk-alert uk-alert-success uk-width-large-8-10 uk-align-center">
					<a href="" class="uk-alert-close uk-close"></a>
					{{ session('status') }}
				</div>
			@endif
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="uk-form-row">
				<input type="email" class="uk-width-large-8-10 uk-form-large" name="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}">
			</div>

			<div class="uk-form-row uk-hidden">
				<input type="text" name="username" placeholder="Username" value="{{ old('username') }}">
			</div>

			<!-- ReCaptcha -->
			<div class="uk-form-row uk-width-large-8-10 uk-align-center">
				<div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
				<p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
			</div>
			<!-- ReCaptcha -->

			<div class="uk-form-row">
				<button type="submit" class="uk-button uk-button-success uk-width-large-8-10 uk-button-large">{{ trans('auth.send_password_reset_link') }}</button>
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
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/uikit.min.js') }}"></script>
@endsection
