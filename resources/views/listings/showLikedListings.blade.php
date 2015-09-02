@extends('layouts.home')

@section('head')
	<title>{{ trans('frontend.liked_listings') }} - {{ Settings::get('site_name') }}</title>
	<meta property="og:title" content="{{ trans('frontend.liked_listings') }} - {{ Settings::get('site_name') }}"/>

	<meta name="description" content="{{ Settings::get('listings_description') }}">
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('listings_description') }}" />
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('frontend.liked_listings') }}</h1>
		<hr>

		<div class="uk-grid">
		@if(Auth::check())
			@foreach($likes as $like)
				<?php 	$listing 		= $like->listing; 
						$mosaicClass 	= "uk-width-medium-1-3 uk-width-large-1-3 uk-margin-small-bottom"; 
				?>
				@include('listings.mosaic')
			@endforeach
		@else
			@foreach($likes as $listing)
				<?php $mosaicClass = "uk-width-medium-1-3 uk-width-large-1-3 uk-margin-small-bottom"; ?>
				@include('listings.mosaic')
			@endforeach
		@endif
		</div>
	</div>
</div>

@include('modals.email_listing')

@endsection

@section('js')
	@parent
	<script type="text/javascript">
		function like(sender) {
			$('#like_button_image').removeClass('uk-text-primary').addClass('uk-text-contrast');
			$('#like_button').removeClass('uk-text-primary');

		    $.post("{{ url('/listings/') }}"+ sender.id +"/like", {_token: "{{ csrf_token() }}"}, function(result){
		    	if(result.success){
		    		if(result.like){
		    			$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
						$('#like_button').addClass('uk-text-primary');
		    		}else{
		    			$('listing-'+sender.id).fadeOut(500, function() { $(this).remove();});
		    			$('#like_button_image').removeClass('uk-text-primary').addClass('uk-text-contrast');
						$('#like_button').removeClass('uk-text-primary');
		    		}
		    	}else if(result.error || !result){
					$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
					$('#like_button').addClass('uk-text-primary');
		    	}
	        });
		}
	</script>
@endsection