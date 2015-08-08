@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.password_reset_title') }}</p>

	<img src="{{ $message->embed(public_path().'/images/support/password/reset.png') }}" style="max-width:600px" class="uk-align-center">

	<div class="uk-text-center">
		<h3> {{ trans('emails.click_here_to_reset') }}</h3>
		<a class="uk-button uk-button-large uk-button-primary" href="{{ url('password/reset/'.$token) }}">{{ trans('emails.reset_password') }}</a>
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