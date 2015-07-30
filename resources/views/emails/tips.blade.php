@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.tips_title') }}</p>

	<div class="uk-text-center">
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo1.png') }}" width="300px" style="max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo2.png') }}" width="300px" style="max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo3.png') }}" width="300px" style="max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo4.png') }}" width="300px" style="max-width:300px">
			</a>
		</div>
		<div class="uk-text-center">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo5.png') }}" width="300px" style="max-width:300px">
			</a>
		</div>
	</div>

	<p>{{ trans('emails.tips_conclution') }}</p>
	
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	@parent
@endsection

@section('share_unregister')
	@parent
@endsection