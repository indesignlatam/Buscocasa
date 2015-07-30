@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.new_message_title') }}</p>

	<div class="uk-text-center">
		<a href="{{ url($userMessage->listing->path()) }}">
			<img src="{{ $message->embed(public_path().$userMessage->listing->image_path()) }}" width="300px" style="max-width:300px">
		</a>
	</div>

	<div class="">
		<div class="uk-margin-top">
		    <h3>{{ trans('emails.new_message_client_data') }}</h3>
		    <ul style="list-style-type:none;padding:0;margin:0;">
		    	<li>{{ trans('emails.name') }} <b>{{ $userMessage->name }}</li>
		    	<li>{{ trans('emails.phone') }} <b>{{ $userMessage->phone }}</li>
		    	<li>{{ trans('emails.email') }} <b>{{ $userMessage->email }}</li>
		    </ul>

		    <p>{{ $userMessage->comments }}</p>
		    <hr class="uk-visible-small uk-hidden-large">
		</div>

		<div class="uk-margin-top">
		    <h3>{{ $userMessage->listing->title }}</h3>
		    <ul>
		    	<li>{{ trans('emails.address') }} <b>{{ $userMessage->listing->direction }}</li>
		    	<li>{{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $userMessage->listing->price) }}</li>
		    	<li>{{ trans('emails.area') }} <b>{{ $userMessage->listing->area }} mt2</li>
		    	<li>{{ trans('emails.stratum') }} <b>{{ $userMessage->listing->stratum }}</li>
		    	<li>{{ trans('emails.rooms') }} <b>{{ $userMessage->listing->rooms }}</li>
		    	<li>{{ trans('emails.bathrooms') }} <b>{{ $userMessage->listing->bathrooms }}</li>
		    </ul>
	    </div>
	</div>

	<div class="uk-text-center uk-margin-top">
		<h3 class="uk-text-primary uk-text-bold">{{ trans('emails.contact_now') }}<br>
		<b>{{ $userMessage->email }}</b></h3>
	</div>
	
	<p class="uk-text-bold">{{ trans('emails.answer_to_contact') }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
	@parent
@endsection