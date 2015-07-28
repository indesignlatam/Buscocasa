@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.message_answer_title') }}</p>

	<div class="uk-text-center">
		<a href="{{ url($messageToAnswer->listing->path()) }}">
			<img src="{{ $message->embed(public_path().$messageToAnswer->listing->image_path()) }}" width="300px">
			{{-- <img src="{{ asset($messageToAnswer->listing->image_path()) }}" width="300px"> --}}
		</a>
	</div>

	<div class="uk-grid">
		<div class="uk-width-large-5-10 uk-margin-top">
		    <h3 class="uk-text-primary">{{ trans('emails.message_answer') }}</h3>
		    <p>
		    	{{ $comments }}
		    </p>
		    <hr class="uk-visible-small">
		</div>

		<div class="uk-width-large-5-10 uk-margin-top">
		    <h3 class="uk-text-primary">{{ trans('emails.listing_message') }}</h3>
		    <p>{{ $messageToAnswer->listing->title }}</p>
		    <p>
		    	{{ trans('emails.address') }} <b>{{ $messageToAnswer->listing->direction }}</b><br>
		    	{{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $messageToAnswer->listing->price) }}</b><br>
		    	{{ trans('emails.area') }} <b>{{ $messageToAnswer->listing->area }} mt2</b><br>
		    	{{ trans('emails.stratum') }} <b>{{ $messageToAnswer->listing->stratum }}</b><br>
		    	{{ trans('emails.rooms') }} <b>{{ $messageToAnswer->listing->rooms }}</b><br>
		    	{{ trans('emails.bathrooms') }} <b>{{ $messageToAnswer->listing->bathrooms }}</b><br>
		    </p>
	    </div>
	</div>

	<div class="uk-margin-top uk-text-center">
		<h3 class="uk-text-primary">{{ trans('emails.contact_now') }} <b>{{ $messageToAnswer->listing->broker->email }}</b></h3>
	</div>
	<p>{{ trans('emails.answer_to_contact') }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	@parent
@endsection