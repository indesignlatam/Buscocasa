@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.password_reset_title') }}</p>

	<div class="uk-text-center" style="margin-top:60px">
		<p> {{ trans('emails.click_here_to_reset') }}</p>
		<h3 class="uk-margin-top-remove">
			<a href="{{ url('password/reset/'.$token) }}">{{ trans('emails.reset_password') }}</a>
		</h3>
	</div>

	<br>

	<div class="uk-margin-top uk-text-center">
		<p>{{ trans('emails.password_reset_not_working') }}</p>
		<p class="uk-text-center">{{ url('password/reset/'.$token) }}</p>
	</div>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
@endsection

@section('share_unregister')
	@parent
@endsection