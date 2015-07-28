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
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
	<div class="uk-panel">
		<div class="uk-grid">
			<div class="uk-width-7-10">
				@if($listingType == 'Buscar')
					<h5 class="uk-panel-title">{{ trans('frontend.search_listings') }}</h5>
				@else
					<h5 class="uk-panel-title">{{ trans('frontend.listings_on') }} {{ $listingType }}</h5>
				@endif
			</div>
			<div class="uk-width-3-10 uk-hidden-small">
				@if(Cookie::get('hide_map'))
					<a id="map_button_hide" class="uk-align-right uk-hidden" data-uk-toggle="{target:'#map_div, #map_button_show, #map_button_hide', animation:'uk-animation-fade'}" onclick="setHideMap(1)">{{ trans('frontend.hide_map') }}</a>
					<a id="map_button_show" class="uk-align-right" data-uk-toggle="{target:'#map_div, #map_button_show, #map_button_hide', animation:'uk-animation-fade'}" onclick="setHideMap(0)">{{ trans('frontend.show_map') }}</a>
				@else
					<a id="map_button_hide" class="uk-align-right" data-uk-toggle="{target:'#map_div, #map_button_show, #map_button_hide', animation:'uk-animation-fade'}" onclick="setHideMap(1)">{{ trans('frontend.hide_map') }}</a>
					<a id="map_button_show" class="uk-align-right uk-hidden" data-uk-toggle="{target:'#map_div, #map_button_show, #map_button_hide', animation:'uk-animation-fade'}" onclick="setHideMap(0)">{{ trans('frontend.show_map') }}</a>
				@endif
			</div>
		</div>

		@if(count($listings))
			@if(Cookie::get('hide_map'))
				<div id="map_div" class="uk-hidden uk-hidden-small">
					<?php echo $map['html']; ?>
				</div>
			@else
				<div id="map_div" class="uk-hidden-small">
					<?php echo $map['html']; ?>
				</div>
			@endif
		@endif
	    
	    <hr>

	    <div class="uk-flex uk-margin-top" id="secondContent">
	    	<!-- Search bar for pc -->
	    	<div class="uk-width-large-1-4 uk-panel uk-panel-box uk-panel-box-secondary uk-visible-large">
    		@if($listingType == 'Buscar')
				<form id="search_form" class="uk-form uk-form-stacked" method="GET" action="{{ url(Request::path()) }}">
			@else
				<form id="search_form" class="uk-form uk-form-stacked" method="GET" action="{{ url(Request::path()) }}">
			@endif	    		
					{{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}

					<input class="uk-width-large-10-10 uk-margin-large-bottom uk-form-large" type="text" name="listing_code" placeholder="{{ trans('frontend.search_field') }}" value="">

					<div class="uk-form-row">
						<label class="uk-form-label">{{ trans('frontend.search_category') }}</label>
						<select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="category" name="category_id">
			                <option value="">{{ trans('frontend.search_select_option') }}</option>
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
			            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="city" name="city_id">
			                <option value="0">{{ trans('frontend.search_select_option') }}</option>
			                @foreach($cities as $city)
			                	@if($city->id == Request::get('city_id'))
			                		<option value="{{ $city->id }}" selected="true">{{ $city->name }}</option>
			                	@else
			                		<option value="{{ $city->id }}">{{ $city->name }}</option>
			                	@endif
			                @endforeach
			            </select>
			        </div>

			        @if(isset($listingTypes) && $listingTypes)
			        <div class="uk-form-row">
						<label class="uk-form-label">{{ trans('frontend.search_listing_types') }}</label>
			            <select class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" id="type" name="listing_type_id">
			                <option value="">{{ trans('frontend.search_select_option') }}</option>
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
					<input type="hidden" id="area_max" name="area_max" value="{{Request::get('area_min')}}">

                	<button type="submit" class="uk-button uk-button-primary uk-align-right uk-margin-large-top">{{ trans('frontend.search_button') }}</button>
				</form>
	    	</div>
	    	<!-- End search bar -->

	    	<div class="uk-width-large-3-4 uk-width-small-1-1 uk-margin-left">
	    		<div class="uk-form uk-align-right">
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
				
	    		@if(count($listings))
		    		<!-- This is the container of the toggling elements -->
					<ul class="uk-tab" data-uk-switcher="{connect:'#my-id'}">
						@if(!Cookie::get('show_mosaic'))
							<li class="uk-tab-active uk-active"><a href=""><i class="uk-icon-bars"></i> {{ trans('frontend.tab_list') }}</a></li>
					    	<li class="uk-hidden-small" onclick="setListingView(1)"><a href=""><i class="uk-icon-th-large"></i> {{ trans('frontend.tab_mosaic') }}</a></li>
					    	{{-- <li class="uk-visible-small"><a href=""><i class="uk-icon-map-marker"></i> {{ trans('frontend.tab_map') }}</a></li> --}}
						@else
							<li class="" onclick="setListingView(0)"><a href=""><i class="uk-icon-bars"></i> {{ trans('frontend.tab_list') }}</a></li>
					    	<li class="uk-tab-active uk-active uk-hidden-small"><a href=""><i class="uk-icon-th-large"></i> {{ trans('frontend.tab_mosaic') }}</a></li>
					    	{{-- <li class="uk-visible-small"><a href=""><i class="uk-icon-map-marker"></i> {{ trans('frontend.tab_map') }}</a></li> --}}
						@endif
					    
					</ul>
					<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin">
						<div class="uk-grid">
							@foreach($listings1 = array_slice($featuredListings->all(), 0, 4) as $listing)
								<div class="uk-width-large-1-4 uk-width-medium-1-2 uk-width-small-1-1" style="position:relative;">
									<!-- Tags start -->
						    		@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
						    			<img src="{{asset($listing->featuredType->image_path)}}" style="position:absolute; top:0; left:30; max-width:100px">
						    		@else
							    		@if(Carbon::createFromFormat('Y-m-d H:i:s', $listing->created_at)->diffInDays(Carbon::now()) < 5)
							    			<img src="{{asset('/images/defaults/new.png')}}" style="position:absolute; top:0; left:0; max-width:100px">
							    		@endif
						    		@endif
							    	<!-- Tags end -->
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
					</div>
					<!-- This is the container of the content items -->
					<ul id="my-id" class="uk-switcher">
						<!-- Full list -->
					    <li>
					    	<?php $i = 0; ?>
					    	@foreach($listings as $listing)
					    		@if($i == ceil(count($listings)/2))
					    			<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin">
										<div class="uk-grid">
											@foreach($listings1 = array_slice($featuredListings->all(), -4, 4) as $listing)
												<div class="uk-width-large-1-4 uk-width-medium-1-2 uk-width-small-1-1" style="position:relative;">
													<!-- Tags start -->
										    		@if($listing->featuredType && $listing->featured_expires_at > Carbon::now())
										    			<img src="{{asset($listing->featuredType->image_path)}}" style="position:absolute; top:0; left:30; max-width:100px">
										    		@else
											    		@if(Carbon::createFromFormat('Y-m-d H:i:s', $listing->created_at)->diffInDays(Carbon::now()) < 5)
											    			<img src="{{asset('/images/defaults/new.png')}}" style="position:absolute; top:0; left:0; max-width:100px">
											    		@endif
										    		@endif
											    	<!-- Tags end -->
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
					    	<div class="uk-grid">
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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>

	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('/js/select2.min.js') }}"></script>

	<script type="text/javascript">var centreGot = false;</script>
	{!! $map['js'] !!}

	<script type="text/javascript">
		function setHideMap(hideMap) {
            $.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key:'hide_map', value:hideMap}, function(response){
            	if(hideMap == false){
            		initialize_map();
            	}
                console.log(response);
            });
        }

        function setListingView(showMosaic) {
            $.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key:'show_mosaic', value:showMosaic}, function(response){
                console.log(response);
            });
        }

        function getMarkers() {
            // $.post("{{ url('/listingView') }}", {_token: "{{ csrf_token() }}", status: showMosaic}, function(response){
            	// console.log(map.getBounds());
            	// marker.setMap(null);
                
             //    marker.setMap(map);
            // });
        }

        $(document).ready(function() {
		  	$("#city").select2();
		});




		$(function() {
		    $( "#slider-range-price" ).slider({
		      	range: true,
		      	step: 5000000,
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
		      	min: 1,// TODO get from settings
		      	max: 501,// TODO get from settings

				@if(Request::has('area_min') && Request::has('area_max'))
					values: [{{Request::get('area_min')}}, {{Request::get('area_max')}}],
				@else
					values: [1, 501],// TODO get from settings
		      	@endif
		      	slide: function( event, ui ) {
		      		if(ui.values[ 1 ] == 501){
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
	  	});
	</script>
@endsection