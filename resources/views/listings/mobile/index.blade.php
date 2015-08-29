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
	<script type="text/javascript">
		loadCSS("{{ asset('/css/jquery/jquery-slider.min.css') }}");
		loadCSS("{{ asset('/css/select2.min.css') }}");
	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
	<div class="uk-panel">
		@if($listingType == 'Buscar')
			<h5 class="uk-panel-title">{{ trans('frontend.search_listings') }}</h5>
		@else
			<h5 class="uk-panel-title">{{ trans('frontend.listings_on') }} {{ $listingType }}</h5>
		@endif
	    
	    <hr>

	    <!-- Search bar for pc -->
    	<div class="uk-panel uk-panel-box uk-panel-box-secondary">
    		<button class="uk-button uk-button-large uk-width-1-1 search" data-uk-toggle="{target:'.search'}"><i class="uk-icon-search"></i> Buscar</button>
    		<button class="uk-button uk-button-large uk-width-1-1 uk-hidden search" data-uk-toggle="{target:'.search'}">Cerrar</button>

			<form id="search_form" class="uk-form uk-form-stacked uk-margin-top uk-hidden search" method="GET" action="{{ url(Request::path()) }}">
				<input class="uk-width-1-1 uk-margin-bottom uk-form-large" type="text" name="listing_code" placeholder="{{ trans('frontend.search_field') }}" value>

				<div class="uk-form-row">
					<label class="uk-form-label">{{ trans('frontend.search_category') }}</label>
					<select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="category" name="category_id">
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
		            <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="city" name="city_id" style="width:100%">
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
		            <select class="uk-width-1-1 uk-margin-small-bottom uk-form-large" id="type" name="listing_type_id">
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
				  	<input type="text" id="price_range" class="uk-width-1-1 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
				</p>
				<div id="slider-range-price"></div>
				<input type="hidden" id="price_min" name="price_min" value="{{Request::get('price_min')}}">
				<input type="hidden" id="price_max" name="price_max" value="{{Request::get('price_max')}}">

				<p>
				  	<label for="room_range" class="uk-form-label">{{ trans('admin.rooms') }}</label>
				  	<input type="text" id="room_range" class="uk-width-1-1 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
				</p>
				<div id="slider-range-rooms"></div>
				<input type="hidden" id="rooms_min" name="rooms_min" value="{{Request::get('rooms_min')}}">
				<input type="hidden" id="rooms_max" name="rooms_max" value="{{Request::get('rooms_max')}}">

				<p>
				  	<label for="area_range" class="uk-form-label">{{ trans('admin.area') }}</label>
				  	<input type="text" id="area_range" class="uk-width-1-1 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
				</p>
	            <div id="slider-range-area"></div>
	            <input type="hidden" id="area_min" name="area_min" value="{{Request::get('area_min')}}">
				<input type="hidden" id="area_max" name="area_max" value="{{Request::get('area_max')}}">

				<p>
				  	<label for="lot_area_range" class="uk-form-label">{{ trans('admin.lot_area') }}</label>
				  	<input type="text" id="lot_area_range" class="uk-width-1-1 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
				</p>
	            <div id="slider-range-lot-area"></div>
	            <input type="hidden" id="lot_area_min" name="lot_area_min" value="{{Request::get('lot_area_min')}}">
				<input type="hidden" id="lot_area_max" name="lot_area_max" value="{{Request::get('lot_area_max')}}">

				<p>
				  	<label for="stratum_range" class="uk-form-label">{{ trans('admin.stratum') }}</label>
				  	<input type="text" id="stratum_range" class="uk-width-1-1 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
				</p>
	            <div id="slider-range-stratum"></div>
	            <input type="hidden" id="stratum_min" name="stratum_min" value="{{Request::get('stratum_min')}}">
				<input type="hidden" id="stratum_max" name="stratum_max" value="{{Request::get('stratum_max')}}">

				<p>
				  	<label for="garages_range" class="uk-form-label">{{ trans('admin.garages') }}</label>
				  	<input type="text" id="garages_range" class="uk-width-1-1 uk-text-primary" readonly style="border:0; font-weight:bold; background-color:#fff; font-size:12px; margin-bottom:-10px">
				</p>
	            <div id="slider-range-garages"></div>
	            <input type="hidden" id="garages_min" name="garages_min" value="{{Request::get('garages_min')}}">
				<input type="hidden" id="garages_max" name="garages_max" value="{{Request::get('garages_max')}}">

            	<button type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1 uk-margin-large-top">{{ trans('frontend.search_button') }}</button>
			</form>
    	</div>
    	<!-- End search bar -->

	    <div class="uk-margin-top" id="secondContent">
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
				
		    	@foreach($listings as $listing)
		    		<!-- Listing list view -->
		    		@include('listings.list')
		    		<!-- Listing list view -->
			    @endforeach
			    <div class="uk-margin-small-top">
			    	<?php echo $listings->appends(Request::all())->render(); ?>
			    </div>
		    @else
		    	<div class="uk-text-center">
		    		<h3 class="uk-text-primary">{{ trans('frontend.sorry') }}<br>{{ trans('frontend.no_listings_found') }}</h3>
		    		<h4>{{ trans('frontend.try_other_parameters') }}</h4>
		    	</div>
		    @endif
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent

	<!-- CSS -->
	<noscript><link href="{{ asset('/css/jquery/jquery-slider.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet"/></noscript>
	<!-- CSS -->

	<!-- JS -->
	<script src="{{ asset('/js/jquery/jquery-slider.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<!-- JS -->

	<script type="text/javascript">
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
		      	}
		    });
		    $( "#garages_range" ).val( accounting.formatNumber($( "#slider-range-garages" ).slider( "values", 0 )) +
		      	" - " + accounting.formatNumber($( "#slider-range-garages" ).slider( "values", 1 )) + "+" );
	  	});
	</script>
@endsection