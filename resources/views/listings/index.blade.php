@extends('layouts.home')

@section('head')
	@if($listingType == 'Buscar')
		<title>{{ trans('frontend.search_listings') }} - {{ Settings::get('site_name') }}</title>
		<meta property="og:title" content="{{ trans('frontend.search_listings') }} - {{ Settings::get('site_name') }}"/>
	@else
		<title>{{ trans('frontend.listings_on') }} {{ $listingType }} - {{ Settings::get('site_name') }}</title>
		<meta property="og:title" content="{{ trans('frontend.listings_on') }} {{ $listingType }} - {{ Settings::get('site_name') }}"/>
	@endif

	<meta name="description" content="{{ Settings::get('listings_description') }}">
    <meta property="og:image" content="{{ asset('/images/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('listings_description') }}" />
@endsection

@section('css')
	@parent
	<style type="text/css">
	.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default{
		border-radius: 10px;
	}
	</style>
	<script type="text/javascript">
		loadCSS("//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css");
        loadCSS("{{ asset('/css/components/slidenav.almost-flat.min.css') }}");
		loadCSS("{{ asset('/css/select2.min.css') }}");
	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
	<div class="uk-panel">
		@if($listingType == 'Buscar')
			<h1>{{ trans('frontend.search_listings') }}</h1>
		@else
			<h1>{{ trans('frontend.listings_on') }} {{ $listingType }}</h1>
		@endif
	    
	    <hr>

	    <div class="uk-flex uk-margin-top" id="secondContent">
	    	<!-- Search bar for pc -->
	    	<div class="uk-width-large-1-4 uk-panel uk-panel-box uk-panel-box-secondary uk-visible-large uk-margin-right">
				<form id="search_form" class="uk-form uk-form-stacked" method="GET" action="{{ url(Request::path()) }}">

					<input class="uk-width-large-10-10 uk-margin-bottom uk-form-large" type="text" name="listing_code" placeholder="{{ trans('frontend.search_field') }}" value>

					<div class="uk-form-row">
						<label class="uk-form-label">{{ trans('frontend.search_category') }}</label>
						<select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="category" name="category_id" onchange="getMarkers(true)">
			                <option value>{{ trans('frontend.search_select_option') }}</option>
			                @foreach($categories as $category)
			                	@if($category->id == Request::get('category_id'))
			                		<option value="{{ $category->id }}" selected>{{ $category->name }}</option>
			                	@else
			                		<option value="{{ $category->id }}">{{ $category->name }}</option>
			                	@endif
			                @endforeach
			            </select>
			        </div>

			        <div class="uk-form-row">
						<label class="uk-form-label">{{ trans('frontend.search_city') }}</label>
			            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="city" name="city_id" onchange="getMarkers(true)">
			                <option value>{{ trans('frontend.search_select_option') }}</option>
			                @foreach($cities as $city)
			                	@if($city->id == Request::get('city_id'))
			                		<option value="{{ $city->id }}" selected>{{ $city->name }}</option>
			                	@else
			                		<option value="{{ $city->id }}">{{ $city->name }}</option>
			                	@endif
			                @endforeach
			            </select>
			        </div>

			        @if(isset($listingTypes) && $listingTypes)
			        <div class="uk-form-row">
						<label class="uk-form-label">{{ trans('frontend.search_listing_types') }}</label>
			            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="type" name="listing_type_id" onchange="getMarkers(true)">
			                <option value>{{ trans('frontend.search_select_option') }}</option>
			                @foreach($listingTypes as $listingType)
			                	@if($listingType->id == Request::get('listing_type_id'))
			                		<option value="{{ $listingType->id }}" selected>{{ $listingType->name }}</option>
			                	@else
			                		<option value="{{ $listingType->id }}">{{ $listingType->name }}</option>
			                	@endif
			                @endforeach
			            </select>
			        </div>
			        @endif

		            <p>
					  	<label for="price_range" class="uk-form-label">{{ trans('admin.price') }}</label>
					  	<input type="text" id="price_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
					</p>
					<div id="slider-range-price"></div>
					<input type="hidden" id="price_min" name="price_min" value="{{Request::get('price_min')}}">
					<input type="hidden" id="price_max" name="price_max" value="{{Request::get('price_max')}}">

					<p>
					  	<label for="room_range" class="uk-form-label">{{ trans('admin.rooms') }}</label>
					  	<input type="text" id="room_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
					</p>
					<div id="slider-range-rooms"></div>
					<input type="hidden" id="rooms_min" name="rooms_min" value="{{Request::get('rooms_min')}}">
					<input type="hidden" id="rooms_max" name="rooms_max" value="{{Request::get('rooms_max')}}">

					<p>
					  	<label for="area_range" class="uk-form-label">{{ trans('admin.area') }}</label>
					  	<input type="text" id="area_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
					</p>
		            <div id="slider-range-area"></div>
		            <input type="hidden" id="area_min" name="area_min" value="{{Request::get('area_min')}}">
					<input type="hidden" id="area_max" name="area_max" value="{{Request::get('area_max')}}">

					<p>
					  	<label for="lot_area_range" class="uk-form-label">{{ trans('admin.lot_area') }}</label>
					  	<input type="text" id="lot_area_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
					</p>
		            <div id="slider-range-lot-area"></div>
		            <input type="hidden" id="lot_area_min" name="lot_area_min" value="{{Request::get('lot_area_min')}}">
					<input type="hidden" id="lot_area_max" name="lot_area_max" value="{{Request::get('lot_area_max')}}">

					<p>
					  	<label for="stratum_range" class="uk-form-label">{{ trans('admin.stratum') }}</label>
					  	<input type="text" id="stratum_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
					</p>
		            <div id="slider-range-stratum"></div>
		            <input type="hidden" id="stratum_min" name="stratum_min" value="{{Request::get('stratum_min')}}">
					<input type="hidden" id="stratum_max" name="stratum_max" value="{{Request::get('stratum_max')}}">

					<p>
					  	<label for="garages_range" class="uk-form-label">{{ trans('admin.garages') }}</label>
					  	<input type="text" id="garages_range" class="uk-width-large-10-10 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
					</p>
		            <div id="slider-range-garages"></div>
		            <input type="hidden" id="garages_min" name="garages_min" value="{{Request::get('garages_min')}}">
					<input type="hidden" id="garages_max" name="garages_max" value="{{Request::get('garages_max')}}">

                	<button type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-large-top">{{ trans('frontend.search_button') }}</button>
				</form>
	    	</div>
	    	<!-- End search bar -->
	    	
	    	<div class="uk-width-large-3-4 uk-width-small-1-1">
	    		@if(count($listings) > 0)
	    			<div class="uk-form uk-align-right uk-hidden-small">
		    			<select form="search_form" name="take" onchange="this.form.submit()">
					    	<option value="">Cantidad de publicaciones</option>
					    	@if(Request::get('take') == 50)
					    		<option value="50" selected>Ver 50</option>
					    	@elseif(Cookie::get('listings_take') == 50)
					    		<option value="50" selected>Ver 50</option>
					    	@else
					    		<option value="50">Ver 50</option>
					    	@endif

					    	@if(Request::get('take') == 30)
					    		<option value="30" selected>Ver 30</option>
					    	@elseif(Cookie::get('listings_take') == 30)
					    		<option value="30" selected>Ver 30</option>
					    	@else
					    		<option value="30">Ver 30</option>
					    	@endif

					    	@if(Request::get('take') == 10)
					    		<option value="10" selected>Ver 10</option>
					    	@elseif(Cookie::get('listings_take') == 10)
					    		<option value="10" selected>Ver 10</option>
					    	@else
					    		<option value="10">Ver 10</option>
					    	@endif
					    </select>

					    <select form="search_form" name="order_by" onchange="this.form.submit()">
					    	<option value="">Ordenar por</option>
					    	@if(Request::get('order_by') && Request::get('order_by') == 'id_desc')
					    		<option value="id_desc" selected>Fecha creación</option>
					    	@elseif(Cookie::get('listings_order_by') == 'id_desc')
					    		<option value="id_desc" selected>Fecha creación</option>
					    	@else
					    		<option value="id_desc">Fecha creación</option>
					    	@endif

					    	@if(Request::get('order_by') && Request::get('order_by') == 'price_max')
					    		<option value="price_max" selected>Mayor a menor valor</option>
					    	@elseif(Cookie::get('listings_order_by') == 'price_max')
					    		<option value="price_max" selected>Mayor a menor valor</option>
					    	@else
					    		<option value="price_max">Mayor a menor valor</option>
					    	@endif

					    	@if(Request::get('order_by') && Request::get('order_by') == 'price_min')
					    		<option value="price_min" selected>Menor a mayor valor</option>
					    	@elseif(Cookie::get('listings_order_by') == 'price_min')
					    		<option value="price_min" selected>Menor a mayor valor</option>
					    	@else
					    		<option value="price_min">Menor a mayor valor</option>
					    	@endif
					    </select>
					</div>
					
		    		<!-- This is the container of the toggling elements -->
					<ul class="uk-tab" data-uk-switcher="{connect:'#my-id'}">
						@if(!Cookie::get('listings_view') || Cookie::get('listings_view') == 0)
							<li class="uk-tab-active uk-active" onclick="setListingView(0)"><a href=""><i class="uk-icon-bars"></i> {{ trans('frontend.tab_list') }}</a></li>
					    	<li class="uk-hidden-small" onclick="setListingView(1)"><a href=""><i class="uk-icon-th-large"></i> {{ trans('frontend.tab_mosaic') }}</a></li>
					    	<li class="uk-hidden" onclick="setListingView(2)"><a href=""><i class="uk-icon-map-marker"></i> {{ trans('frontend.tab_map') }}</a></li>
						@elseif(Cookie::get('listings_view') == 1)
							<li class="" onclick="setListingView(0)"><a href=""><i class="uk-icon-bars"></i> {{ trans('frontend.tab_list') }}</a></li>
					    	<li class="uk-hidden-small uk-tab-active uk-active" onclick="setListingView(1)"><a href=""><i class="uk-icon-th-large"></i> {{ trans('frontend.tab_mosaic') }}</a></li>
					    	<li class="uk-hidden" onclick="setListingView(2)"><a href=""><i class="uk-icon-map-marker"></i> {{ trans('frontend.tab_map') }}</a></li>
					    @elseif(Cookie::get('listings_view') == 2)
					    	<li class="" onclick="setListingView(0)"><a href=""><i class="uk-icon-bars"></i> {{ trans('frontend.tab_list') }}</a></li>
					    	<li class="uk-hidden-small" onclick="setListingView(1)"><a href=""><i class="uk-icon-th-large"></i> {{ trans('frontend.tab_mosaic') }}</a></li>
					    	<li class="uk-hidden uk-tab-active uk-active" onclick="setListingView(2)"><a href=""><i class="uk-icon-map-marker"></i> {{ trans('frontend.tab_map') }}</a></li>
						@endif
					</ul>
					<!-- This is the container of the content items -->
					<ul id="my-id" class="uk-switcher uk-margin-top">
						<!-- Full list -->
					    <li>
					    	<div class="uk-panel uk-margin">
								<!-- Featured listings top -->
								<div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
					                <ul class="uk-grid uk-slideset">
					                    @foreach($featuredListings as $listing)
					                    <li>
					                        <a href="{{ url($listing->path()) }}">
					                            <img src="{{ asset(Image::url($listing->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
					                        </a>
					                        <a href="{{ url($listing->path()) }}">{{ $listing->title }}</a>
					                        <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $listing->area }} mt2 - {{ money_format('$%!.0i', $listing->price) }}</p>
					                    </li>
					                    @endforeach
					                </ul>
					                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
					                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
					            </div>
								<!-- Featured listings top -->
							</div>
					    	<?php $i = 0; ?>
					    	@foreach($listings as $listing)
					    		@if(count($listings) >= 10 && $i == ceil(count($listings)/2))
					    			<div class="uk-panel uk-margin">
										<div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
							                <ul class="uk-grid uk-slideset">
							                    @foreach($featuredListings as $listing)
							                    <li>
							                        <a href="{{ url($listing->path()) }}">
							                            <img src="{{ asset(Image::url($listing->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
							                        </a>
							                        <a href="{{ url($listing->path()) }}">{{ $listing->title }}</a>
							                        <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $listing->area }} mt2 - {{ money_format('$%!.0i', $listing->price) }}</p>
							                    </li>
							                    @endforeach
							                </ul>
							                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
							                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
							            </div>
									</div>
									<hr>
					    		@endif
					    		<?php $i++; ?>
					    		<!-- Listing list view -->
					    		@include('listings.list')
					    		<!-- Listing list view -->
						    @endforeach
						    <div class="uk-margin-small-top">
						    	<?php echo $listings->appends(Request::all())->render(); ?>
						    </div>
					    </li>

					    <!-- Image Mosaic -->
					    <li>
					    	<!-- Featured listings top -->
							<div class="uk-slidenav-position" data-uk-slideset="{small: 1, medium: 4, large: 4, autoplay: true}">
				                <ul class="uk-grid uk-slideset">
				                    @foreach($featuredListings as $listing)
				                    <li>
				                        <a href="{{ url($listing->path()) }}">
				                            <img src="{{ asset(Image::url($listing->image_path(),['mini_front'])) }}" class="uk-margin-small-bottom" style="max-width=150px">
				                        </a>
				                        <a href="{{ url($listing->path()) }}">{{ $listing->title }}</a>
				                        <p class="uk-text-muted" style="font-size:10px;margin-top:-4px">{{ $listing->area }} mt2 - {{ money_format('$%!.0i', $listing->price) }}</p>
				                    </li>
				                    @endforeach
				                </ul>
				                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-previous uk-slidenav-contrast" data-uk-slideset-item="previous"></a>
				                <a href="" style="margin-top:-60px" class="uk-slidenav uk-slidenav-next uk-slidenav-contrast" data-uk-slideset-item="next"></a>
				            </div>
							<!-- Featured listings top -->
					    	<div class="uk-grid uk-margin-top-remove">
					    	@foreach($listings as $listing)
					    		<!-- Listing list view -->
					    		@include('listings.mosaic')
					    		<!-- Listing list view -->
						    @endforeach
						    </div>
						    <div class="uk-margin-small-top">
						    	<?php echo $listings->appends(Request::all())->render(); ?>
						    </div>
					    </li>

					    <!-- Map -->
					    <li>
					    	<div id="map" style="height:550px" class="uk-width-1-1"></div>
					    </li>
					</ul>
		    		
			    @else
			    	<div class="uk-text-center">
			    		<h3 class="uk-text-primary">{{ trans('frontend.sorry') }}<br>{{ trans('frontend.no_listings_found') }}</h3>
			    		<h4>{{ trans('frontend.try_other_parameters') }}</h4>
			    	</div>
			    @endif
	    	</div>
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent

	<!-- CSS -->
	<noscript><link href="{{ asset('/css/jquery/jquery-ui.theme.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet"/></noscript>
	<noscript><link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet"/></noscript>
	<!-- CSS -->

	<script type="text/javascript">
        function setListingView(view) {
        	if(view == 2){
        		setTimeout(initMap, 50);
        	}
            $.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key:'listings_view', value:view}, function(response){
                console.log(response);
            });
        }

		$(function() {
			$("#city").select2();

		    $( "#slider-range-price" ).slider({
		      	range: true,
		      	step: 10000000,
		      	min: 0,// TODO get from settings
		      	max: 2000000000,// TODO get from settings

		      	@if(Request::has('price_min') && Request::has('price_max'))
					values: [{{Request::get('price_min')}}, {{Request::get('price_max')}}],
				@else
					values: [0, 2000000000],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		tag = "";
		      		if(ui.values[ 1 ] == 2000000000){// TODO get from settings
		      			tag = "+";
		      		}
		        	$( "#price_range" ).val( "$" + accounting.formatNumber(ui.values[ 0 ]) + " - $" + accounting.formatNumber(ui.values[ 1 ]) + tag );
		        	$( "#price_min" ).val(ui.values[ 0 ]);
		        	$( "#price_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getMarkers(true);
		      	}
		    });
		    $( "#price_range" ).val( "$" + accounting.formatNumber($( "#slider-range-price" ).slider( "values", 0 )) +
		      	" - $" + accounting.formatNumber($( "#slider-range-price" ).slider( "values", 1 )) + "+" );

		    $( "#slider-range-rooms" ).slider({
		      	range: true,
		      	min: 1,// TODO get from settings
		      	max: 10,// TODO get from settings

		      	@if(Request::has('rooms_min') && Request::has('rooms_max'))
					values: [{{Request::get('rooms_min')}}, {{Request::get('rooms_max')}}],
				@else
					values: [1, 10],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		tag = "";
		      		if(ui.values[ 1 ] == 10){
		      			tag = "+";
		      		}
		        	$( "#room_range" ).val( accounting.formatNumber(ui.values[ 0 ]) + " - " + accounting.formatNumber(ui.values[ 1 ]) + tag);
		        	$( "#rooms_min" ).val(ui.values[ 0 ]);
		        	$( "#rooms_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getMarkers(true);
		      	}
		    });
		    $( "#room_range" ).val( accounting.formatNumber($( "#slider-range-rooms" ).slider( "values", 0 )) +
		      	" - " + accounting.formatNumber($( "#slider-range-rooms" ).slider( "values", 1 )) + "+");

		    $( "#slider-range-area" ).slider({
		      	range: true,
		      	animate: "fast",
		      	step: 10,
		      	min: 0,// TODO get from settings
		      	max: 500,// TODO get from settings

				@if(Request::has('area_min') && Request::has('area_max'))
					values: [{{Request::get('area_min')}}, {{Request::get('area_max')}}],
				@else
					values: [0, 500],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		if(ui.values[ 1 ] == 500){
		      			tag = "+ mt2";
		      		}else{
		      			tag = " mt2";
		      		}
		        	$( "#area_range" ).val( accounting.formatNumber(ui.values[ 0 ]) + " mt2" + " - " + accounting.formatNumber(ui.values[ 1 ]) + tag );
		        	$( "#area_min" ).val(ui.values[ 0 ]);
		        	$( "#area_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getMarkers(true);
		      	}
		    });
		    $( "#area_range" ).val( accounting.formatNumber($( "#slider-range-area" ).slider( "values", 0 )) + " mt2" +
		      	" - " + accounting.formatNumber($( "#slider-range-area" ).slider( "values", 1 )) + "+ mt2" );

		    $( "#slider-range-lot-area" ).slider({
		      	range: true,
		      	animate: "fast",
		      	step: 50,
		      	min: 0,// TODO get from settings
		      	max: 2000,// TODO get from settings

				@if(Request::has('lot_area_min') && Request::has('lot_area_max'))
					values: [{{Request::get('lot_area_min')}}, {{Request::get('lot_area_max')}}],
				@else
					values: [1, 2000],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		if(ui.values[ 1 ] == 2000){
		      			tag = "+ mt2";
		      		}else{
		      			tag = " mt2";
		      		}
		        	$( "#lot_area_range" ).val( accounting.formatNumber(ui.values[ 0 ]) + " mt2" + " - " + accounting.formatNumber(ui.values[ 1 ]) + tag );
		        	$( "#lot_area_min" ).val(ui.values[ 0 ]);
		        	$( "#lot_area_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getMarkers(true);
		      	}
		    });
		    $( "#lot_area_range" ).val( accounting.formatNumber($( "#slider-range-lot-area" ).slider( "values", 0 )) + " mt2" +
		      	" - " + accounting.formatNumber($( "#slider-range-lot-area" ).slider( "values", 1 )) + "+ mt2" );

		    $( "#slider-range-stratum" ).slider({
		      	range: true,
		      	animate: "fast",
		      	step: 1,
		      	min: 1,// TODO get from settings
		      	max: 6,// TODO get from settings

				@if(Request::has('stratum_min') && Request::has('stratum_max'))
					values: [{{Request::get('stratum_min')}}, {{Request::get('stratum_max')}}],
				@else
					values: [1, 6],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		        	$( "#stratum_range" ).val( accounting.formatNumber(ui.values[ 0 ]) + " - " + accounting.formatNumber(ui.values[ 1 ]) );
		        	$( "#stratum_min" ).val(ui.values[ 0 ]);
		        	$( "#stratum_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getMarkers(true);
		      	}
		    });
		    $( "#stratum_range" ).val( accounting.formatNumber($( "#slider-range-stratum" ).slider( "values", 0 )) +
		      	" - " + accounting.formatNumber($( "#slider-range-stratum" ).slider( "values", 1 )) );

		    $( "#slider-range-garages" ).slider({
		      	range: true,
		      	animate: "fast",
		      	step: 1,
		      	min: 0,// TODO get from settings
		      	max: 5,// TODO get from settings

				@if(Request::has('garages_min') && Request::has('garages_max'))
					values: [{{Request::get('garages_min')}}, {{Request::get('garages_max')}}],
				@else
					values: [0, 5],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		if(ui.values[ 1 ] == 5){
		      			tag = "+";
		      		}else{
		      			tag = "";
		      		}
		        	$( "#garages_range" ).val( accounting.formatNumber(ui.values[ 0 ]) + " - " + accounting.formatNumber(ui.values[ 1 ]) + tag );
		        	$( "#garages_min" ).val(ui.values[ 0 ]);
		        	$( "#garages_max" ).val(ui.values[ 1 ]);
		      	},
		      	change: function(){
		      		getMarkers(true);
		      	}
		    });
		    $( "#garages_range" ).val( accounting.formatNumber($( "#slider-range-garages" ).slider( "values", 0 )) +
		      	" - " + accounting.formatNumber($( "#slider-range-garages" ).slider( "values", 1 )) + "+" );
	  	});
	</script>

	<!-- JS -->
	<script src="{{ asset('/js/jquery/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('/js/components/slideset.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<!-- JS -->
@endsection