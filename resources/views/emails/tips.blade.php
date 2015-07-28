@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.tips_title') }}</p>

	<div class="uk-grid uk-grid-collapse">
		<div class="uk-width-large-1-5">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo1.png') }}">
				{{-- <img src="{{ asset('/images/support/messages/consejo1.png') }}"> --}}
			</a>
		</div>
		<div class="uk-width-large-1-5">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo2.png') }}">
				{{-- <img src="{{ asset('/images/support/messages/consejo2.png') }}"> --}}
			</a>
		</div>
		<div class="uk-width-large-1-5">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo3.png') }}">
				{{-- <img src="{{ asset('/images/support/messages/consejo3.png') }}"> --}}
			</a>
		</div>
		<div class="uk-width-large-1-5">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo4.png') }}">
				{{-- <img src="{{ asset('/images/support/messages/consejo4.png') }}"> --}}
			</a>
		</div>
		<div class="uk-width-large-1-5">
			<a href="{{ url($listing->pathEdit()) }}">
				<img src="{{ $message->embed(public_path().'/images/support/messages/consejo5.png') }}">
				{{-- <img src="{{ asset('/images/support/messages/consejo5.png') }}"> --}}
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