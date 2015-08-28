@extends('layouts.home')

@section('head')
    <title>{{ trans('frontend.compare_title') }} - {{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ trans('frontend.compare_title') }}"/>
	<meta property="og:image" content=""/>
	<meta property="og:type" content="article"/>
	<meta property="og:description" content=""/>
	<meta name="description" content="">
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top" id="secondContent">
	<div class="uk-panel">
		<div>
			<h1 style="display:inline">Comparar inmuebles</h1>
			<button class="uk-button uk-button-large uk-button-danger uk-float-right" onclick="forget()">Eliminar inmuebles seleccionados</button>
		</div>
		
		<h3>En esta sección encontraras los ultimos 4 inmuebles que hayas seleccionado para comparar.</h3>

		<div class="uk-flex uk-flex-space-between uk-margin-top uk-margin-large-bottom">
			@if($listings && count($listings) > 0)
				@foreach($listings as $listing)
					<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-width-1-4" style="border-top:none;border-left:none;border-bottom:none;border-radius:0">
						<div style="height:60px; background-color:white;" data-uk-sticky="{boundary: true}">
							<a href="{{ $listing->path() }}" style="text-decoration:none"><h3>{{ $listing->title }}</h3></a>
						</div>
						<div style="height:180px" class="uk-panel">
							<div class="uk-panel-badge uk-badge uk-badge-notification" id="points-{{ $listing->id }}">-</div>
							<a href="{{ $listing->path() }}"><img src="{{ $listing->image_path }}"></a>
						</div>

		    			<ul class="uk-list uk-list-line">
	    					<li><i class="uk-text-muted">{{ trans('admin.price') }}</i> <b class="uk-text-right uk-float-right">{{ money_format('$%!.0i', $listing->price) }}</b></li>

	    					@if($listing->area > 0)
								<li><i class="uk-text-muted">{{ trans('frontend.price_mt') }}</i> <b class="uk-text-right uk-float-right">{{ money_format('$%!.0i', ($listing->price/$listing->area)) }}</b></li>
							@elseif($listing->lot_area > 0)
								<li><i class="uk-text-muted">{{ trans('frontend.price_mt') }}</i> <b class="uk-text-right uk-float-right">{{ money_format('$%!.0i', ($listing->price/$listing->lot_area)) }}</b></li>
							@else
								-
							@endif

							@if($listing->stratum)
								<li><i class="uk-text-muted">{{ trans('admin.stratum') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->stratum }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.stratum') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->area)
								<li><i class="uk-text-muted">{{ trans('admin.area') }}</i> <b class="uk-text-right uk-float-right">{{ number_format($listing->area, 0) }} mt2</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.area') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->lot_area)
								<li><i class="uk-text-muted">{{ trans('admin.lot_area') }}</i> <b class="uk-text-right uk-float-right">{{ number_format($listing->lot_area, 0) }} mt2</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.lot_area') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->rooms)
								<li><i class="uk-text-muted">{{ trans('admin.rooms') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->rooms }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.rooms') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->bathrooms)
								<li><i class="uk-text-muted">{{ trans('admin.bathrooms') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->bathrooms }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.bathrooms') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->garages)
								<li><i class="uk-text-muted">{{ trans('admin.garages') }}</i> <b class="uk-text-right uk-float-right">{{ $listing->garages }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.garages') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif

		    				@if($listing->administration > 0)
								<li><i class="uk-text-muted">{{ trans('admin.administration_fees') }}</i> <b class="uk-text-right uk-float-right">{{ money_format('$%!.0i', $listing->administration) }}</b></li>
		    				@else
								<li><i class="uk-text-muted">{{ trans('admin.administration_fees') }}</i> <b class="uk-text-right uk-float-right">-</b></li>
		    				@endif
	    				</ul>

	    				<div class="uk-h3">
	    					<i>{{ trans('admin.interior') }}</i>
	    					<button class="uk-button uk-button-link interior" style="text-decoration:none;" data-uk-toggle="{target:'.interior'}">Ver</button>
	    					<button class="uk-button uk-button-link uk-hidden interior" style="text-decoration:none;" data-uk-toggle="{target:'.interior'}">Cerrar</button>
	    				</div>
		    			<ul class="uk-list uk-list-line uk-hidden interior">
	    					@foreach($features as $feature)
								@if($feature->category->id == 1)
									<?php $featureChecked = false; ?>
									@foreach($listing->features as $listingFeature)
										@if($feature->id == $listingFeature->id)
											<?php $featureChecked = true; break; ?>
										@endif
									@endforeach
									@if($featureChecked)
										<li><i class="uk-icon-check uk-text-success"></i> {{ $feature->name }}</li>
									@else
										<li><i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i></li>
									@endif
								@endif
							@endforeach
	    				</ul>

	    				<div class="uk-h3">
	    					<i>{{ trans('admin.exterior') }}</i>
	    					<button class="uk-button uk-button-link exterior" style="text-decoration:none;" data-uk-toggle="{target:'.exterior'}">Ver</button>
	    					<button class="uk-button uk-button-link uk-hidden exterior" style="text-decoration:none;" data-uk-toggle="{target:'.exterior'}">Cerrar</button>
	    				</div>
		    			<ul class="uk-list uk-list-line uk-hidden exterior">
	    					@foreach($features as $feature)
								@if($feature->category->id == 2)
									<?php $featureChecked = false; ?>
									@foreach($listing->features as $listingFeature)
										@if($feature->id == $listingFeature->id)
											<?php $featureChecked = true; break; ?>
										@endif
									@endforeach
									@if($featureChecked)
										<li><i class="uk-icon-check uk-text-success"></i> {{ $feature->name }}</li>
									@else
										<li><i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i></li>
									@endif
								@endif
							@endforeach
	    				</ul>

	    				<div class="uk-h3">
	    					<i>{{ trans('admin.sector') }}</i>
	    					<button class="uk-button uk-button-link sector" style="text-decoration:none;" data-uk-toggle="{target:'.sector'}">Ver</button>
	    					<button class="uk-button uk-button-link uk-hidden sector" style="text-decoration:none;" data-uk-toggle="{target:'.sector'}">Cerrar</button>
	    				</div>
		    			<ul class="uk-list uk-list-line uk-hidden sector">
	    					@foreach($features as $feature)
								@if($feature->category->id == 3)
									<?php $featureChecked = false; ?>
									@foreach($listing->features as $listingFeature)
										@if($feature->id == $listingFeature->id)
											<?php $featureChecked = true; break; ?>
										@endif
									@endforeach
									@if($featureChecked)
										<li><i class="uk-icon-check uk-text-success"></i> {{ $feature->name }}</li>
									@else
										<li><i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i></li>
									@endif
								@endif
							@endforeach
	    				</ul>

	    				<h3 id="points2-{{ $listing->id }}" class="uk-text-bold uk-text-center">- puntos</h3>
					</div>
				@endforeach
			@else

			@endif
		</div>
	</div>
</div>
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/components/sticky.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>

	<script type="text/javascript">
		$(function (){
		@if($listings && count($listings) > 0)
			calculatePoints();
		@endif
		});

		function calculatePoints(){
			var listings = {!! $listings !!};
			var points = [];
			var totalPoints = 0;
			
			// Price points
			var lowest = Number.POSITIVE_INFINITY;
			var highest = Number.NEGATIVE_INFINITY;
			var tmp;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].price;
			    if (tmp < lowest) lowest = tmp;
			}
			listings.forEach(function(listing) {
				points[listing.id] = 50 * (lowest/listing.price);
			});
			console.log(points);

			// mt2 price points
			lowest = Number.POSITIVE_INFINITY;
			tmp = null;
			var lowestI;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].area;
			    if (tmp < lowest){
			    	lowest = tmp;
			    	lowestI = i;
			    }
			}
			listings.forEach(function(listing) {
				if(listing.area > 0){
					points[listing.id] += 100 * (listings[lowestI].price/lowest)/(listing.price/listing.area);
				}
			});
			console.log(points);

			// Stratum points
			highest = Number.NEGATIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].stratum;
			    if (tmp > highest){
			    	highest = tmp;
			    }
			}
			listings.forEach(function(listing) {
				points[listing.id] += 10 * (listing.stratum/highest);
			});
			console.log(points);

			// Area points
			highest = Number.NEGATIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].area;
			    if (tmp > highest){
			    	highest = tmp;
			    }
			}
			listings.forEach(function(listing) {
				if(highest > 0){
					points[listing.id] += 20 * (listing.area/highest);
				}
			});
			console.log(points);

			// Lot area points
			highest = Number.NEGATIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].lot_area;
			    if (tmp > highest){
			    	highest = tmp;
			    }
			}
			listings.forEach(function(listing) {
				if(highest > 0){
					points[listing.id] += 20 * (listing.lot_area/highest);
				}
			});
			console.log(points);

			// Rooms points
			highest = Number.NEGATIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].rooms;
			    if (tmp > highest){
			    	highest = tmp;
			    }
			}
			listings.forEach(function(listing) {
				if(highest > 0){
					points[listing.id] += 20 * (listing.rooms/highest);
				}
			});
			console.log(points);

			// Bathrooms points
			highest = Number.NEGATIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].bathrooms;
			    if (tmp > highest){
			    	highest = tmp;
			    }
			}
			listings.forEach(function(listing) {
				if(highest > 0){
					points[listing.id] += 20 * (listing.bathrooms/highest);
				}
			});
			console.log(points);

			// Garages points
			highest = Number.NEGATIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].garages;
			    if (tmp > highest){
			    	highest = tmp;
			    }
			}
			listings.forEach(function(listing) {
				if(highest > 0){
					
					points[listing.id] += 20 * (listing.garages/highest);
				}
			});
			console.log(points);

			// Admin fees points
			lowest = Number.POSITIVE_INFINITY;
			tmp = null;
			for (var i=listings.length-1; i>=0; i--) {
			    tmp = listings[i].administration;
			    if (tmp < lowest){
			    	lowest = tmp;
			    }
			}
			if(lowest == 0){
				lowest = 1;
			}
			listings.forEach(function(listing) {
				if(listing.administration == 0){
					listing.administration = 1;
				}
				points[listing.id] += 20 * (lowest/listing.administration);
			});
			console.log(points);


			listings.forEach(function(listing) {
				$('#points-'+listing.id).html(parseInt((points[listing.id]/280)*100)+'/100');
				$('#points2-'+listing.id).html(parseInt((points[listing.id]/280)*100)+'/100 puntos');
			});
		}

		window.fbAsyncInit = function() {
        	FB.init({
         		appId      : {{ Settings::get('facebook_app_id') }},
          		xfbml      : true,
          		version    : 'v2.3'
        	});
      	};
      	(function(d, s, id){
         	var js, fjs = d.getElementsByTagName(s)[0];
         	if (d.getElementById(id)) {return;}
         	js = d.createElement(s); js.id = id;
         	js.src = "//connect.facebook.net/en_US/sdk.js";
         	fjs.parentNode.insertBefore(js, fjs);
       	}(document, 'script', 'facebook-jssdk'));

       	function share(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.shares',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response, id){
				$.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key: "shared_listing_"+id, value: true, time:11520}, function(result){
	                
	            });
			  	// Debug response (optional)
			  	console.log(response);
			});
       	}

       	function like(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.likes',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response, id){
			  	console.log(response);
			});
       	}

       	function forget(){
       		UIkit.modal.confirm("{{ trans('frontend.listing_selected') }}", function(){
       			$.post("{{ url('/cookie/forget') }}", {_token: "{{ csrf_token() }}", key: "selected_listings"}, function(result){
				 	window.location.replace("{{ url('/compare') }}");
            	});
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center:true});
			
		}
	</script>
@endsection