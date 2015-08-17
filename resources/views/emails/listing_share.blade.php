@extends('emails.layouts.master')
@section('header')
	<a href="{{ url('/') }}"><img src="{{ $message->embed(public_path('/images/emails/welcome.jpg')) }}"></a>
	<h3>Buen día,</h3>
@endsection

@section('content')
	@if($messageText)
		<p>{{ $messageText }}</p>
	@else
		<p>{{ trans('emails.listing_share_title', ['name' => $listing->broker->name]) }}</p>
	@endif

	<div class="">
		<div class="uk-margin-top">
		    <p class="uk-text-bold">{{ $listing->title }}</p>
		    <ul class="uk-list">
				<li><i class="uk-text-muted">{{ trans('admin.price') }}</i> {{ money_format('$%!.0i', $listing->price) }}</li>

				@if($listing->area > 0)
					<li><i class="uk-text-muted">{{ trans('frontend.price_mt') }}</i> {{ money_format('$%!.0i', $listing->price/$listing->area) }}</li>
				@elseif($listing->lot_area > 0)
					<li><i class="uk-text-muted">{{ trans('frontend.price_mt') }}</i> {{ money_format('$%!.0i', $listing->price/$listing->lot_area) }}</li>
				@endif

				@if($listing->rooms)
					<li><i class="uk-text-muted">{{ trans('admin.rooms') }}</i> {{ $listing->rooms }}</li>
				@endif

				@if($listing->bathrooms)
					<li><i class="uk-text-muted">{{ trans('admin.bathrooms') }}</i> {{ $listing->bathrooms }}</li>
				@endif

				@if($listing->area)
					<li><i class="uk-text-muted">{{ trans('admin.area') }}</i> {{ number_format($listing->area, 0, ',', '.') }} mt2</li>
				@endif

				@if($listing->lot_area)
					<li><i class="uk-text-muted">{{ trans('admin.lot_area') }}</i> {{ number_format($listing->lot_area, 0, ',', '.') }} mt2</li>
				@endif

				@if($listing->garages)
					<li><i class="uk-text-muted">{{ trans('admin.garages') }}</i> {{ $listing->garages }}</li>
				@endif

				@if($listing->stratum)
					<li><i class="uk-text-muted">{{ trans('admin.stratum') }}</i> {{ $listing->stratum }}</li>
				@endif

				@if($listing->administration > 0)
					<li><i class="uk-text-muted">{{ trans('admin.administration_fees') }}</i> {{ money_format('$%!.0i', $listing->administration) }}</li>
				@endif

				@if($listing->construction_year > 0)
					<li><i class="uk-text-muted">{{ trans('admin.construction_year') }}</i> {{ $listing->construction_year }}</li>
				@endif

				<li><i class="uk-text-muted">{{ trans('admin.code') }}</i> <b>#{{ $listing->code }}</b></li>
			</ul>
			<p>{{ $listing->description }}</p>
	    </div>
	</div>

	<div class="uk-text-center">
		@foreach($listing->images->sortBy('ordering') as $image)
			<a href="{{ url($listing->path()) }}"><img src="{{ $message->embed(public_path($image->image_path)) }}" style="max-width:300px; margin-right:10px; margin-bottom:10px"></a>
		@endforeach
	</div>
@endsection

@section('greetings')
	<p class="uk-text-bold">{{ trans('emails.answer_to_contact_vendor') }}</p>
	<p class="uk-margin-large-top">
		Cordialmente,
		<br>
		<br>
		<br>
		<b>{{ $listing->broker->name }}<b>
	</p>
@endsection

@section('footer')
	{{-- <p class="uk-text-bold">{{ trans('emails.answer_to_contact_vendor') }}</p> --}}
@endsection

@section('share_unregister')
	@parent
@endsection