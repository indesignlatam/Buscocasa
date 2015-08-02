@extends('layouts.home')

@section('head')
    <title>{{ $user->name }} - {{ Settings::get('site_name') }}</title>
    <meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:type" content="article"/>
	<meta property="og:description" content=""/>
	<meta name="description" content="">
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-cover-background uk-position-relative">
    <img class="" src="{{ asset(Image::url('/images/defaults/user_front.jpg',['full_page'])) }}" width="100%" alt="">
    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-visible-small">
        <h1 class="uk-text-contrast uk-text-bold">{{ strtoupper($user->name) }}</h1>
    </div>
    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-hidden-small">
        <h1 class="uk-text-contrast uk-text-bold" style="font-size:60px; margin-left:-30%;">{{ strtoupper($user->name) }}</h1>
    </div>
</div>

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel uk-margin-bottom">
		<h2 id="description">{{ $user->description }}</h2>

		<!-- user listings -->
        @if(count($user->listings) > 0)
            <h1 class="uk-text-bold">{{ trans('frontend.user_listings') }}</h1>
            <div class="uk-grid">
                @foreach($user->listings as $listing)
                    <div class="uk-width-large-1-4 uk-width-medium-1-3 uk-width-small-1-1">
                        <a href="{{ url($listing->path()) }}">
                            <img src="{{ asset(Image::url($listing->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
                        </a>
                        <br class="uk-visible-small">
                        <a href="{{ url($listing->path()) }}">{{ $listing->title }}</a>
                        <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $listing->area }} mt2 - {{ money_format('$%!.0i', $listing->price) }}</p>
                        <hr class="uk-visible-small uk-margin-bottom">
                    </div>
                @endforeach
            </div>
        @endif
		<!-- user listings end -->
		
	</div>
</div>
@endsection

@section('js')
	<link href="{{ asset('/css/components/slideshow.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/slideshow.min.js') }}"></script>
	<!--
	<link href="{{ asset('/css/medium.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/undo.min.js') }}"></script>
    <script src="{{ asset('/js/rangy-core.min.js') }}"></script>
    <script src="{{ asset('/js/medium.min.js') }}"></script>
    -->
	@parent
    
	<?php //echo $map['js']; ?>
	<script type="text/javascript">
		function open(url){
			window.open(url, "Popup", "location=1,status=1,scrollbars=1, resizable=1, directories=1, toolbar=1, titlebar=1, width=800, height=600");
		}

		function phoneFormat(phone) {
			phone = phone.replace(/\D/g,'');
			if(phone.length == 10){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 9){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{2})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 8){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{1})(\d{3})(\d{4})/, "($1) $2-$3");
			}else if(phone.length == 7){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{4})/, "$1-$2");
			}
			
			return phone;
		}

		$(function (){

		});
	</script>
	
@endsection