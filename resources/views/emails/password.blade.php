@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.password_reset_title') }}</p>
	<p> {{ trans('emails.click_here_to_reset') }}</p>

	<h3 class="uk-text-center"><a href="{{ url('password/reset/'.$token) }}"> {{ trans('emails.reset_password') }}</a></h3>

	<br>
	<p>{{ trans('emails.password_reset_not_working') }}</p>
	<p class="uk-text-center">{{ url('password/reset/'.$token) }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	
@endsection