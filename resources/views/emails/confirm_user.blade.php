@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.user_confirmation_title') }}</p>
	
	<h3 class="uk-text-center">
		{{ trans('emails.click_here_to_confirm') }}
		<br>
		<a href="{{ url('user/'.$user->id.'/confirm/'.$user->confirmation_code) }}" style="color:#d92228">{{ trans('emails.confirm_user') }}</a>
	</h3>

	<p>{{ trans('emails.confirm_user_not_working') }}<br> {{ url('user/'.$user->id.'/confirm/'.$user->confirmation_code) }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	
@endsection

@section('share_unregister')
	@parent
@endsection