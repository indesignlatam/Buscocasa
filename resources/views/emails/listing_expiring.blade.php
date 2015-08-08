@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.expiring_listing_title') }}</p>

	<div style="text-align:center; margin-top:20px">
		<h3><a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('emails.renovate_listing_now') }}</a></h3>
	</div>

	<div style="text-align:center;">
		<a href="{{ url($listing->path()) }}">
			<img src="{{ $message->embed(public_path().'/'.$listing->image_path()) }}" style="width:300px; max-width:300px">
		</a>
	</div>

	<h3>{{ $listing->title }}</h3>
    <p>
    	{{ trans('emails.address') }} <b>{{ $listing->direction }}</b><br>
    	{{ trans('emails.price') }} <b>{{ money_format('$%!.0i', $listing->price) }}</b><br>
    	{{ trans('emails.area') }} <b>{{ $listing->area }} mt2</b><br>
    	{{ trans('emails.stratum') }} <b>{{ $listing->stratum }}</b><br>
    	{{ trans('emails.rooms') }} <b>{{ $listing->rooms }}</b><br>
    	{{ trans('emails.bathrooms') }} <b>{{ $listing->bathrooms }}</b><br>
    </p>

	<div style="text-align:center;">
		<h3><a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('emails.renovate_listing_now') }}</a></h3>
	</div>
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