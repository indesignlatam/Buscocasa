@extends('layouts.home')

@section('head')
    <title>{{ $listing->title }} - {{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ $listing->title }}"/>
	<meta property="og:image" content="{{ asset($listing->image_path()) }}"/>
	<meta property="og:type" content="article"/>
	@if(strlen($listing->description) > 100)
		<meta property="og:description" content="{{ $listing->description }}"/>
		<meta name="description" content="{{ $listing->description }}">
	@else
		<meta property="og:description" content="{{ $listing->description. '. ' . Settings::get('listings_description') }}" />
		<meta name="description" content="{{ $listing->description. '. ' . Settings::get('listings_description') }}">
	@endif
@endsection

@section('css')
	@parent
	<script type="text/javascript">
		loadCSS("{{ asset('/css/components/slideshow.min.css') }}");
		loadCSS("{{ asset('/css/components/slidenav.almost-flat.min.css') }}");
		loadCSS("{{ asset('/css/components/tooltip.min.css') }}");
		loadCSS("{{ asset('/css/selectize.min.css') }}");
	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top" id="secondContent">
	<div class="uk-panel">
		<div>
			<h2 class="uk-hidden-small" style="display:inline">{{ $listing->title }}</h2>
			<h2 class="uk-visible-small">{{ $listing->title }}</h2>
			<div style="display:inline" class="uk-hidden-small uk-float-right">
				<i class="uk-h2 uk-text-right">{{ trans('admin.price') }} </i>
				<i class="uk-h2 uk-text-primary uk-text-right"> ${{ money_format('%!.0i', $listing->price) }}</i>
			</div>
		</div>

		<div class="uk-grid uk-margin-small-top">
			<div class="uk-width-large-7-10 uk-width-medium-7-10 uk-width-small-1-1">	
				@if(count($listing->images) > 0)
					<div class="uk-slidenav-position" data-uk-slideshow="{autoplay:true, autoplayInterval:7000}">
					    <ul class="uk-slideshow">
					    	@foreach($listing->images->sortBy('ordering') as $image)
					    		<li>
					    			<img src="{{ asset($image->image_path) }}" alt="{{ $listing->title }}" style="max-width:960px; max-height:540px">
					    		</li>
					    	@endforeach		    	
					    </ul>
					    @if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
					    	<a onclick="like()"><i style="position:absolute; top:5px; right:5px" class="uk-icon-heart uk-icon-large uk-text-primary" id="like_button_image"></i></a>
					    @else
					    	<a onclick="like()"><i style="position:absolute; top:5px; right:5px" class="uk-icon-heart uk-icon-large uk-text-contrast" id="like_button_image"></i></a>
					    @endif

					    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
					    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
					</div>
				@else
					<img src="{{ asset($listing->image_path()) }}" alt="{{ $listing->title }}" >
				@endif
			</div>

			<div class="uk-width-3-10 uk-hidden-small">
				<div class="uk-panel uk-panel-box">
					@if (Session::has('success'))
						<div class="uk-alert uk-alert-success" data-uk-alert>
			    			<a href="" class="uk-alert-close uk-close"></a>
							<ul class="uk-list">
								@foreach (Session::get('success') as $error)
									<li><i class="uk-icon-check"></i> {{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					@if(!Cookie::get('listing_message_'.$listing->id) || Cookie::get('listing_message_'.$listing->id) > Carbon::now())
						<h3>{{ trans('frontend.contact_vendor') }}</h3>

						@if (count($errors) > 0)
							<div class="uk-alert uk-alert-danger" data-uk-alert>
				    			<a href="" class="uk-alert-close uk-close"></a>
								<ul class="uk-list">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form id="send_message_inpage" class="uk-form uk-form-horizontal" method="POST" action="{{ url('/appointments') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
				            <input type="hidden" name="listing_id" value="{{ $listing->id }}">

				            @if(Auth::check())
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ Auth::user()->name }}">
			                @else
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}">
			                @endif

			                <div class="uk-hidden">
			                	<input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
			                </div>
			                
		                	@if(Auth::check())
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ Auth::user()->phone_1 }}">
			                @else
			                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ old('phone') }}">
			                @endif

			                @if(Auth::check())
			            		<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ Auth::user()->email }}">
			                @else
			            		<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ old('email') }}" onchange="showCaptcha()">
			                @endif
			            
			                
				            <textarea class="uk-width-large-10-10 uk-form-large" name="comments" placeholder="{{ trans('frontend.contact_comments') }}" rows="5">@if(old('comments')){{ old('comments') }}@else{{ trans('frontend.contact_default_text') }}@endif</textarea>

				            @if(!Auth::check())
				                <!-- ReCaptcha -->
				                <div class="uk-form-row uk-width-large-10-10 uk-margin-top uk-align-center uk-hidden" id="captcha">
				                    <div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
				                    <p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
				                </div>
				                <!-- ReCaptcha -->
				            @endif

				            <button form="send_message_inpage" type="submit" class="uk-button uk-button-large uk-width-1-1 uk-button-primary uk-margin-top">{{ trans('frontend.contact_button') }}</button>
						</form>
					@else
						<h3 class="uk-text-primary">{{ trans('frontend.already_contacted_vendor') }}</h3>
					@endif
					
					<div class="uk-margin-small-top uk-flex">
						<button class="uk-button uk-button-large uk-width-1-1" data-uk-toggle="{target:'#phones'}"><i class="uk-icon-phone"></i></button>
						<button onclick="like()" class="uk-button uk-button-large uk-width-1-1 uk-margin-small-left">
					    	@if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
								<i id="like_button" class="uk-icon-heart uk-text-primary"></i>
							@else
								<i id="like_button" class="uk-icon-heart"></i>
							@endif
						</button>
					</div>

					<div id="phones" class="uk-hidden">
						@if(!$listing->broker->phone_1 && !$listing->broker->phone_2)
							<div class="uk-text-warning">
								El usuario no tiene ningun telefono registrado. Intenta escribirle un mensaje.
							</div>
						@else
							@if($listing->broker->phone_1)
								<div class="uk-h3">
									Tel 1: <b id="phone_1">{{ $listing->broker->phone_1 }}</b>
								</div>
							@endif
							@if($listing->broker->phone_2)
								<div class="uk-h3">
									Tel 2: <b id="phone_2">{{ $listing->broker->phone_2 }}</b>
								</div>
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>
			
		<hr>

	    <div class="uk-grid uk-margin uk-margin-bottom">
	    	<div class="uk-width-large-1-4 uk-width-medium-1-4 uk-width-small-1-1">
	    		<!-- Social share links -->
	    		<div class="uk-flex uk-flex-space-between">
    				<a onclick="like('{{ url($listing->path()) }}', {{ $listing->id }})" class="uk-icon-button uk-icon-facebook-square"></a> 
    				<a onclick="share('{{ url($listing->path()) }}', {{ $listing->id }})" class="uk-icon-button uk-icon-facebook"></a> 
    				<a class="uk-icon-button uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text={{ $listing->title }}%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
					<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus"></a>
				    <a href="#send_mail" class="uk-icon-button uk-icon-envelope" onclick="setListing({{ $listing->id }})" data-uk-modal="{center:true}"></a>
	    		</div>
				<!-- Social share links -->

	    		<ul class="uk-list uk-list-line">
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

				<button class="uk-button uk-button-large uk-button-success uk-width-1-1" onclick="select(this)" id="{{ $listing->id }}">{{ trans('frontend.compare') }}</button>
    			<a href="{{ url($listing->broker->path()) }}" class="uk-button uk-button-large uk-width-1-1 uk-margin-small-top">{{ trans('frontend.other_user_listings') }}</a>

    			<hr>

    			@if(count($related) > 0)
	    			<div class="uk-margin-medium-top uk-hidden-small">
	    				<h2 class="uk-text-bold">{{ trans('frontend.similar_listings') }}</h2>
	    				@foreach($related as $rlisting)
		    				<div class="uk-overlay uk-overlay-hover uk-margin-small">
		    					<img src="{{ asset(Image::url( $rlisting->image_path(), ['map_mini']) ) }}" alt="{{$rlisting->title}}" data-uk-scrollspy="{cls:'uk-animation-fade'}">
							    <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade">
							    	<h4 class="uk-margin-remove">{{ $rlisting->title }}</h4>
							    	<h4 class="uk-margin-top-remove uk-margin-small-bottom uk-text-bold">{{ money_format('$%!.0i', $rlisting->price) }}</h4>
							    	<ul style="list-style-type: none;margin-top:-5px; margin-left:-30px" class="uk-text-contrast">
					    				@if($rlisting->rooms)
					    				<li><i class="uk-icon-check"></i> {{ $rlisting->rooms }} {{ trans('admin.rooms') }}</li>
					    				@endif

					    				@if($rlisting->bathrooms)
					    				<li><i class="uk-icon-check"></i> {{ $rlisting->bathrooms }} {{ trans('admin.bathrooms') }}</li>
					    				@endif

					    				@if($rlisting->stratum)
					    				<li><i class="uk-icon-check"></i> {{ trans('admin.stratum') }} {{ $rlisting->stratum }}</li>
					    				@endif

					    				@if($rlisting->area)
					    				<li><i class="uk-icon-check"></i> {{ number_format($rlisting->area, 0, ',', '.') }} mt2</li>
					    				@endif

					    				@if($rlisting->lot_area)
					    				<li id="lot_area"><i class="uk-icon-check"></i> {{ number_format($rlisting->lot_area, 0, ',', '.') }} {{ trans('frontend.lot_area') }}</li>
					    				@endif
					    			</ul>
							    </div>
							    <a class="uk-position-cover" href="{{ url($rlisting->path()) }}"></a>
							</div>
						@endforeach
	    			</div>
    			@endif
	    	</div>

	    	<div class="uk-width-large-3-4 uk-width-medium-3-4 uk-width-small-1-1">
	    		<div class="uk-margin-bottom uk-h3">
	    			{{ $listing->description }}
	    		</div>

	    		<hr>

	    		<h3>{{ trans('admin.interior') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 1)
							<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
								<?php $featureChecked = false; ?>
								@foreach($listing->features as $listingFeature)
									@if($feature->id == $listingFeature->id)
										<?php $featureChecked = true; break; ?>
									@endif
								@endforeach
								@if($featureChecked)
									<i class="uk-icon-check uk-text-primary"></i> {{ $feature->name }}
								@else
									<i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i>
								@endif
							</div>
						@endif
					@endforeach
				</div>

				<h3>{{ trans('admin.exterior') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 2)
							<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
								<?php $featureChecked = false; ?>
								@foreach($listing->features as $listingFeature)
									@if($feature->id == $listingFeature->id)
										<?php $featureChecked = true; break; ?>
									@endif
								@endforeach
								@if($featureChecked)
									<i class="uk-icon-check uk-text-primary"></i> {{ $feature->name }}
								@else
									<i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i>
								@endif										
							</div>
						@endif
					@endforeach
				</div>

				<h3>{{ trans('admin.sector') }}</h3>
				<div class="uk-grid uk-margin-bottom">
					@foreach($features as $feature)
						@if($feature->category->id == 3)
							<div class="uk-width-large-1-3 uk-width-medium-1-3 uk-width-small-1-1">
								<?php $featureChecked = false; ?>
								@foreach($listing->features as $listingFeature)
									@if($feature->id == $listingFeature->id)
										<?php $featureChecked = true; break; ?>
									@endif
								@endforeach
								@if($featureChecked)
									<i class="uk-icon-check uk-text-primary"></i> {{ $feature->name }}
								@else
									<i class="uk-icon-minus-circle uk-text-muted"> {{ $feature->name }}</i>
								@endif										
							</div>
						@endif
					@endforeach
				</div>

				<hr>

				@if(count($compare) > 0)
	    		<div class="uk-width-1-1" id="compare">
	    			<h2>{{ trans('frontend.near_listings') }}</h2>
	    			<table class="uk-table uk-table-condensed uk-table-striped" style="margin-top:-10px">
	    				<thead>
					        <tr>
					            <th>{{ trans('frontend.listing') }}</th>
					            <th>{{ trans('admin.stratum') }}</th>
					            <th>{{ trans('frontend.distance') }}</th>
					            <th style="width:50px" class="uk-hidden-small">{{ trans('admin.area') }}</th>
					            <th style="width:70px" class="uk-hidden-small">{{ trans('admin.lot_area') }}</th>
					            <th style="width:110px">{{ trans('frontend.price_mt') }}</th>
					        </tr>
					    </thead>
    				@foreach($compare as $cListing)
    					<tr>
    						<td><a href="{{ url($cListing->path()) }}">{{ $cListing->title }}</a></td>
    						<td>{{ $cListing->stratum }}</td>
    						<td class="uk-text-right">{{ number_format($cListing->distance*1000) }} mts</td>
    						<td class="uk-text-right uk-hidden-small">{{ number_format($cListing->area, 0, '.', ',') }}</td>
    						<td class="uk-text-right uk-hidden-small">{{ number_format($cListing->lot_area, 0, '.', ',') }}</td>
    						@if($cListing->area > 0)
    							<td>
    							@if($listing->area > 0 && ($cListing->price/$cListing->area) > ($listing->price/$listing->area))
    								<i class="uk-icon-caret-up uk-text-danger uk-icon-align-justify" data-uk-tooltip title="{{ trans('frontend.price_higher') }}"> </i>
    							@elseif($listing->area > 0)
    								<i class="uk-icon-caret-down uk-text-success uk-icon-align-justify" data-uk-tooltip title="{{ trans('frontend.price_lower') }}"> </i>
    							@endif
    								{{ money_format('$%!.0i', $cListing->price/$cListing->area) }}
    							</td>
		    				@elseif($cListing->lot_area > 0)
		    					<td>
		    					@if($listing->lot_area > 0 && ($cListing->price/$cListing->lot_area) > ($listing->price/$listing->lot_area))
    								<i class="uk-icon-caret-up uk-text-danger uk-icon-align-justify" data-uk-tooltip title="{{ trans('frontend.price_higher') }}"> </i>
    							@elseif($listing->lot_area > 0)
    								<i class="uk-icon-caret-down uk-text-success uk-icon-align-justify" data-uk-tooltip title="{{ trans('frontend.price_lower') }}"> </i>
    							@endif
    								{{ money_format('$%!.0i', $cListing->price/$cListing->lot_area) }}
    							</td>
		    				@else
		    					<td>{{ money_format('$%!.0i', $cListing->price) }}</td>
		    				@endif
    					</tr>
    				@endforeach
	    			</table>
	    		</div>
	    		@endif

	    		<div>
	    			<h2 class="uk-display-inline">{{ trans('frontend.location') }}</h2>
	    			<button class="uk-button uk-align-right" onclick="toggleStreetView()"><i class="uk-icon-street-view"></i>Street View</button>
	    		</div>
	    		<div id="map" class="uk-width-1-1" style="height:350px"></div>

	    		<hr>
	    		
	    		<div class="uk-width-1-1" id="places">
	    			<h2>{{ trans('frontend.near_places') }}</h2>
	    			<div class="uk-grid" data-uk-grid-match="{target:'.uk-panel'}">
	    				<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small">
	    					<div class="uk-panel uk-panel-box uk-panel-box-primary">
	    						<h3 class="uk-panel-title uk-margin-remove"><i class="uk-icon-university uk-icon-align-justify"></i> Colegios y Universidades</h3>
	    						<hr class="uk-margin-top-remove">
	    						<table class="uk-table uk-table-condensed uk-table-hover" style="margin-top:-10px" id="schools">
	    						</table>
	    					</div>
	    				</div>
	    				<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small">
	    					<div class="uk-panel uk-panel-box uk-panel-box-primary">
	    						<h3 class="uk-panel-title uk-margin-remove"><i class="uk-icon-bus uk-icon-align-justify"></i> Estaciones</h3>
	    						<hr class="uk-margin-top-remove">
	    						<table class="uk-table uk-table-condensed uk-table-hover" style="margin-top:-10px" id="bus_stops">
	    						</table>
	    					</div>
	    				</div>

	    				<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small">
	    					<div class="uk-panel uk-panel-box uk-panel-box-primary">
	    						<h3 class="uk-panel-title uk-margin-remove"><i class="uk-icon-shopping-cart uk-icon-align-justify"></i> Supermercados y C.C.</h3>
	    						<hr class="uk-margin-top-remove">
	    						<table class="uk-table uk-table-condensed uk-table-hover" style="margin-top:-10px" id="malls">
	    						</table>
	    					</div>
	    				</div>
	    				<div class="uk-width-medium-1-2 uk-width-large-1-2 uk-margin-small">
	    					<div class="uk-panel uk-panel-box uk-panel-box-primary">
	    						<h3 class="uk-panel-title uk-margin-remove"><i class="uk-icon-map-marker uk-icon-align-justify"></i> Otros</h3>
	    						<hr class="uk-margin-top-remove">
	    						<table class="uk-table uk-table-condensed uk-table-hover" style="margin-top:-10px" id="others">
	    						</table>
	    					</div>
	    				</div>
	    			</div>
	    		</div>

	    	</div>
	    	
	    </div>
	</div>
</div>

@include('modals.email_listing')

@endsection

@section('js')
	@parent

	<!-- CSS -->
	<noscript><link href="{{ asset('/css/components/slideshow.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/selectize.min.css') }}" rel="stylesheet"/></noscript>
	<!-- CSS -->

	<!-- JS -->
    <script src="{{ asset('/js/components/slideshow.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap"></script>
    <script async defer src="{{ asset('js/case.js') }}"></script>
	<script src="{{ asset('/js/selectize.min.js') }}"></script>

    @if(!Auth::check())
	<script async defer src='https://www.google.com/recaptcha/api.js'></script>
	@endif
	<!-- JS -->
	
	<script type="text/javascript">
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
				phone = phone.replace(/(\d{1})(\d{3})(\d{4})/, "(+$1) $2-$3");
			}else if(phone.length == 7){
				phone = phone.replace(/[^0-9]/g, '');
				phone = phone.replace(/(\d{3})(\d{4})/, "$1-$2");
			}
			
			return phone;
		}

		function showCaptcha(){
			$('#captcha').removeClass('uk-hidden', 1000);
		}

		function select(sender){
			$.post("{{ url('/cookie/select') }}", {_token: "{{ csrf_token() }}", key: "selected_listings", value: sender.id}, function(result){
				UIkit.modal.confirm("{{ trans('frontend.listing_selected') }}", function(){
				    window.location.href = "{{ url('/compare') }}";
				}, {labels:{Ok:'{{trans("frontend.compare_now")}}', Cancel:'{{trans("frontend.keep_looking")}}'}, center:true});
            });
		}

		$(function (){
			$('#phone_1').html(phoneFormat($('#phone_1').html()));
			$('#phone_2').html(phoneFormat($('#phone_2').html()));

			var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                  '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

			$('#emails').selectize({
			    persist: false,
			    maxItems: 5,
			    valueField: 'email',
			    labelField: 'name',
			    searchField: ['name', 'email'],
			    options: [],
			    render: {
			        item: function(item, escape) {
			            return '<div>' +
			                (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
			                (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
			            '</div>';
			        },
			        option: function(item, escape) {
			            var label = item.name || item.email;
			            var caption = item.name ? item.email : null;
			            return '<div>' +
			                '<span class="label">' + escape(label) + '</span>' +
			                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
			            '</div>';
			        }
			    },
			    createFilter: function(input) {
			        var match, regex;

			        // email@address.com
			        regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
			        match = input.match(regex);
			        if (match) return !this.options.hasOwnProperty(match[0]);

			        // name <email@address.com>
			        regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
			        match = input.match(regex);
			        if (match) return !this.options.hasOwnProperty(match[2]);

			        return false;
			    },
			    create: function(input) {
			        if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
			            return {email: input};
			        }
			        var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
			        if (match) {
			            return {
			                email : match[2],
			                name  : $.trim(match[1])
			            };
			        }
			        alert('Correo electrónico invalido.');
			        return false;
			    }
			});
		});

		var pyrmont = {lat: {{ $listing->latitude }}, lng: {{ $listing->longitude }}};
		function initMap() {
		  
		  	var map = new google.maps.Map(document.getElementById('map'), {
		    	center: pyrmont,
		    	zoom: 15,
		    	scrollwheel: false,
	    		navigationControl: false,
			    mapTypeControl: false,
			    scaleControl: false,
			    draggable: false,
		  	});

		  	var icon = { url: "{{ asset('/images/maps/marker_icon.png') }}", scaledSize: new google.maps.Size(50, 30) };
		  	var marker = new google.maps.Marker({
		    	map: map,
		    	icon: icon,
		    	position: pyrmont
		  	});

		  	var service = new google.maps.places.PlacesService(map);
		  	service.nearbySearch({
		    	location: pyrmont,
		    	radius: 500,
		    	types: ['university', 'grocery_or_supermarket', 'department_store', 'school', 'shopping_mall']
		  	}, callback);
		  	service.nearbySearch({
		    	location: pyrmont,
		    	radius: 500,
		    	types: ['subway_station', 'train_station', 'bus_station', 'gym', 'park', 'police',]
		  	}, callback);

		  	// We get the map's default panorama and set up some defaults.
			// Note that we don't yet set it visible.
			panorama = map.getStreetView();
			panorama.setPosition(pyrmont);
			panorama.setPov(/** @type {google.maps.StreetViewPov} */({
				heading: 265,
				pitch: 0
			}));
		}

		function callback(results, status) {
			if(results.length == 0){
	  			$('#places').addClass('uk-hidden');
	  		}

	  		results.sort(
	  			function(a, b){
	  				return parseInt(getDistance(a.geometry.location, pyrmont))-parseInt(getDistance(b.geometry.location, pyrmont))
	  			}
	  		);

		  	if (status === google.maps.places.PlacesServiceStatus.OK) {
			  	schools = 	results.filter(function (el) {
								return 	el.types[0] == 'school' ||
										el.types[0] == 'university';
							});

			  	stores 	= 	results.filter(function (el) {
								return 	el.types[0] == 'department_store' || 
										el.types[0] == 'shopping_mall' || 
										el.types[0] == 'grocery_or_supermarket' ||
										el.types[1] == 'department_store' || 
										el.types[1] == 'shopping_mall' || 
										el.types[1] == 'grocery_or_supermarket';
							});

			  	bus 	= 	results.filter(function (el) {
								return 	el.types[0] == 'bus_station' ||
										el.types[1] == 'train_station' || 
										el.types[1] == 'subway_station';
							});

			  	others 	= 	results.filter(function (el) {
								return 	el.types[0] == 'gym' ||
										el.types[0] == 'airport' ||
										el.types[0] == 'hospital' ||
										el.types[0] == 'park' ||
										el.types[0] == 'police';
							});

		    	for (var i = 0; i < bus.length ; i++) {
		    		if(i <= 4){
		    			$('#bus_stops').append('<tr><td  style="width:75%">'+Case.title(bus[i].name.substring(0, 40))+'</td><td>'+parseInt(getDistance(bus[i].geometry.location, pyrmont))+' mts</tb><tr>');
		    		}
		    	}

		    	for (var i = 0; i < others.length ; i++) {
		    		if(i <= 4){
		    			$('#others').append('<tr><td  style="width:75%">'+Case.title(others[i].name.substring(0, 40))+'</td><td>'+parseInt(getDistance(others[i].geometry.location, pyrmont))+' mts</tb><tr>');
		    		}
		    	}

		    	for (var i = 0; i < stores.length ; i++) {
		    		if(i <= 4){
		    			$('#malls').append('<tr><td style="width:75%">'+Case.title(stores[i].name.substring(0, 40))+'</td><td>'+parseInt(getDistance(stores[i].geometry.location, pyrmont))+' mts</tb><tr>');
		    		}
		    	}

		    	for (var i = 0; i < schools.length ; i++) {
		    		if(i <= 4){
		    			$('#schools').append('<tr><td  style="width:75%">'+Case.title(schools[i].name.substring(0, 40))+'</td><td>'+parseInt(getDistance(schools[i].geometry.location, pyrmont))+' mts</tb><tr>');
		    		}
		    	}
		  	}
		}

		function rad(x) {
		  	return x * Math.PI / 180;
		};

		function getDistance(p1, p2) {
		  	var R = 6378137; // Earth’s mean radius in meter
		  	var dLat = rad(p2.lat - p1.lat());
		  	var dLong = rad(p2.lng - p1.lng());
		  	var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
		    	Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat)) *
		    	Math.sin(dLong / 2) * Math.sin(dLong / 2);
		  	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
		  	var d = R * c;
		  	return d; // returns the distance in meter
		};

       	function like(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.likes',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response){
			  	console.log(response);
			});
       	}

       	function setListing(id){
			$('#listingId').val(id);
			$("#emails").val('');
			$("#message").val('');
		}

		function sendMail(sender) {
	    	$('#sendMail').prop('disabled', true);
	    	var message = $('#message').val();
	    	var emails = $('#emails').val().replace(/ /g,'').split(',');
	    	var validemails = [];
	    	$.each(emails, function( index, value ) {
			  	if(validateEmail(value)){
			  		validemails.push(value);
			  	}
			});

			if(validemails.length < 1){
				UIkit.modal.alert('<h2 class="uk-text-center"><i class="uk-icon-check-circle uk-icon-large"></i><br>{{ trans('admin.no_emails') }}</h2>', {center: true});
				$('#sendMail').prop('disabled', false);
				return;
			}

			if(message.length < 1){
				UIkit.modal.alert('<h2 class="uk-text-center"><i class="uk-icon-check-circle uk-icon-large"></i><br>{{ trans('admin.no_message') }}</h2>', {center: true});
				$('#sendMail').prop('disabled', false);
				return;
			}

	    	$.post("{{ url('/admin/listings') }}/"+ $('#listingId').val() +"/share", {_token: "{{ csrf_token() }}", email: validemails, message: message}, function(result){
		    	$('#sendMail').prop('disabled', false);
		    	if(result.success){
		    		UIkit.modal("#send_mail").hide();
					UIkit.modal.alert('<h2 class="uk-text-center"><i class="uk-icon-check-circle uk-icon-large"></i><br>'+result.success+'</h2>', {center: true});
		    	}else if(result.error || !result){
					UIkit.modal.alert('<h2 class="uk-text-center"><i class="uk-icon-check-circle uk-icon-large"></i><br>'+result.error+'</h2>', {center: true});
		    	}
	        });
	    }

	    function validateEmail(email) {
		    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		    return re.test(email);
		}

		@if(isset(Cookie::get('likes')[$listing->id]) && Cookie::get('likes')[$listing->id] || $listing->like)
		var liked = true;
		@else
		var liked = false;
		@endif

		function like() {
			if(!liked){
				$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
				$('#like_button').addClass('uk-text-primary');
			}else{
				$('#like_button_image').removeClass('uk-text-primary').addClass('uk-text-contrast');
				$('#like_button').removeClass('uk-text-primary');
			}
		    

		    $.post("{{ url('/listings/'.$listing->id.'/like') }}", {_token: "{{ csrf_token() }}"}, function(result){
		    	if(result.success){
		    		if(result.like){
		    			liked = true;
		    			$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
						$('#like_button').addClass('uk-text-primary');
						UIkit.modal.confirm('<h3 class="uk-text-center">{{ trans('frontend.goto_favorites') }}</h3>', function(){
						    // will be executed on confirm.
							window.location.href = "{{ url('/listings/liked') }}";
						}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
		    		}else{
		    			liked = false;
		    			$('#like_button_image').removeClass('uk-text-primary').addClass('uk-text-contrast');
						$('#like_button').removeClass('uk-text-primary');
		    		}
		    	}else if(result.error || !result){
					if(liked){
						$('#like_button_image').removeClass('uk-text-contrast').addClass('uk-text-primary');
						$('#like_button').addClass('uk-text-primary');
					}else{
						$('#like_button_image').removeClass('uk-text-primary').addClass('uk-text-contrast');
						$('#like_button').removeClass('uk-text-primary');
					}
		    	}
	        });
		}

		function toggleStreetView() {
			var toggle = panorama.getVisible();
			if (toggle == false) {
				panorama.setVisible(true);
			}else{
				panorama.setVisible(false);
			}
		}
    </script>
@endsection