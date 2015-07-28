@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.new_message_title') }}</p>

	<div class="uk-text-center">
		<a href="{{ url($userMessage->listing->path()) }}">
			<img src="{{ $message->embed(public_path().$userMessage->listing->image_path()) }}" width="300px">
			{{-- <img src="{{ asset($userMessage->listing->image_path()) }}" width="300px"> --}}
		</a>
	</div>

	<div class="uk-grid">
		<div class="uk-width-large-5-10 uk-margin-top">
		    <h3 class="uk-text-primary">{{ trans('emails.new_message_client_data') }}</h3>
		    <p>
		    	{{ trans('emails.name') }} <b>{{ $userMessage->name }}</b><br>
		    	{{ trans('emails.phone') }} <b>{{ $userMessage->phone }}</b><br>
		    	{{ trans('emails.email') }} <b>{{ $userMessage->email }}</b>
		    </p>

		    <p>{{ $userMessage->comments }}</p>
		    <hr class="uk-visible-small uk-hidden-large">
		</div>

		<div class="uk-width-large-5-10 uk-margin-top">
		    <p>{{ $userMessage->listing->title }}</p>
		    <p>
		    	{{ trans('emails.address') }} <b>{{ $userMessage->listing->direction }}</b><br>
		    	{{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $userMessage->listing->price) }}</b><br>
		    	{{ trans('emails.area') }} <b>{{ $userMessage->listing->area }} mt2</b><br>
		    	{{ trans('emails.stratum') }} <b>{{ $userMessage->listing->stratum }}</b><br>
		    	{{ trans('emails.rooms') }} <b>{{ $userMessage->listing->rooms }}</b><br>
		    	{{ trans('emails.bathrooms') }} <b>{{ $userMessage->listing->bathrooms }}</b><br>
		    </p>
	    </div>
	</div>

	<div class="uk-text-center uk-margin-top">
		<h3 class="uk-text-primary">{{ trans('emails.contact_now') }}<br>
		<b>{{ $userMessage->email }}</b></h3>
	</div>
	<p>{{ trans('emails.answer_to_contact') }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	@parent
@endsection