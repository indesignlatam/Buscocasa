@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.tips_title') }}</p>

	<div class="uk-text-center">
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo1.png') }}" style="width:300px; max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo2.png') }}" style="width:300px; max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo3.png') }}" style="width:300px; max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo4.png') }}" style="width:300px; max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo5.png') }}" style="width:300px; max-width:300px">
			</a>
		</div>
	</div>

	<p>{{ trans('emails.tips_conclution') }}</p>
	
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
@endsection

@section('share_unregister')
	@parent
@endsection