@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.message_answer_title') }}</p>

	<div class="uk-text-center">
		<a href="{{ url($messageToAnswer->listing->path()) }}">
			<img src="{{ $message->embed(public_path().'/'.$messageToAnswer->listing->image_path()) }}" style="max-width:300px">
		</a>
	</div>

	<div class="">
		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ trans('emails.message_answer') }}</p>

		    <p><i>{{ $comments }}</i></p>
		</div>

		<hr>

		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ $messageToAnswer->listing->title }}</p>
		    <ul>
		    	<li>{{ trans('emails.address') }} <b>{{ $messageToAnswer->listing->direction }}</li>
		    	<li>{{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $messageToAnswer->listing->price) }}</li>
		    	<li>{{ trans('emails.area') }} <b>{{ $messageToAnswer->listing->area }} mt2</li>
		    	<li>{{ trans('emails.stratum') }} <b>{{ $messageToAnswer->listing->stratum }}</li>
		    	<li>{{ trans('emails.rooms') }} <b>{{ $messageToAnswer->listing->rooms }}</li>
		    	<li>{{ trans('emails.bathrooms') }} <b>{{ $messageToAnswer->listing->bathrooms }}</li>
		    </ul>
	    </div>
	</div>

	<div class="uk-text-center uk-margin-top">
		<h3 class="uk-text-primary uk-text-bold">{{ trans('emails.contact_now') }}<br>
		<b>{{ $messageToAnswer->listing->broker->email }}</b></h3>
	</div>
	
	<p class="uk-text-bold">{{ trans('emails.answer_to_contact') }}</p>
@endsection

@section('greetings')
	@parent
@endsection

@section('footer')
@endsection

@section('share_unregister')
	@parent
@endsection