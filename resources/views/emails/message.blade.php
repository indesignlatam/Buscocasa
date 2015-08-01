@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.new_message_title') }}</p>

	<div class="uk-text-center">
		<a href="{{ url($userMessage->listing->path()) }}">
			<img src="{{ $message->embed(public_path().'/'.$userMessage->listing->image_path()) }}" style="max-width:300px">
		</a>
	</div>

	<div class="">
		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ trans('emails.new_message_client_data') }}</p>
	    	<p>	{{ trans('emails.name') }} <b>{{ $userMessage->name }}</b><br>
	    		{{ trans('emails.phone') }} <b>{{ $userMessage->phone }}</b><br>
	    		{{ trans('emails.email') }} <b>{{ $userMessage->email }}</b>
	    	</p>

		    <p><i>{{ $userMessage->comments }}</i></p>
		</div>

		<hr>

		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ $userMessage->listing->title }}</p>
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

@section('share_unregister')
	@parent
@endsection