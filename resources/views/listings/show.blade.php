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
		loadCSS("{{ asset('/css/components/slideshow.almost-flat.min.css') }}");
		loadCSS("{{ asset('/css/components/slidenav.almost-flat.min.css') }}");
	</script>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top" id="secondContent">
	<div class="uk-panel">
		<h2 class="uk-hidden-small" style="float:left; display:inline">{{ $listing->title }}</h2>
		<h2 class="uk-visible-small">{{ $listing->title }}</h2>
		<div style="float:right; display:inline" class="uk-hidden-small">
			<i class="uk-h2 uk-text-right">{{ trans('admin.price') }} </i>
			<i class="uk-h2 uk-text-primary uk-text-right"> ${{ money_format('%!.0i', $listing->price) }}</i>
		</div>

		<div class="uk-grid" style="width:100%">
			<div class="uk-width-large-7-10 uk-width-medium-7-10">	
				@if(count($listing->images) > 0)
					<div class="uk-slidenav-position" data-uk-slideshow="{autoplay:true, autoplayInterval:7000}">
					    <ul class="uk-slideshow">
					    	@foreach($listing->images->sortBy('ordering') as $image)
					    		<li>
					    			<img src="{{ asset($image->image_path) }}" alt="{{ $listing->title }}" style="max-width:800px; max-height:540px">
					    		</li>
					    	@endforeach		    	
					    </ul>
					    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
					    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
					</div>
				@else
					<img src="{{ asset($listing->image_path()) }}" alt="{{ $listing->title }}" >
				@endif
				
			</div>
			<div class="uk-width-3-10 uk-hidden-small">
				<div class="uk-panel uk-panel-box" style="width:100%">
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

			                <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}">

			                <div class="uk-hidden">
			                	<input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
			                </div>
			                
		                	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ old('phone') }}">
			            
			            	<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ old('email') }}" onchange="showCaptcha()">
			                
				            <textarea class="uk-width-large-10-10 uk-form-large" name="comments" placeholder="{{ trans('frontend.contact_comments') }}" rows="5">@if(old('comments')){{ old('comments') }}@else{{ trans('frontend.contact_default_text') }}@endif</textarea>

				            @if(!Auth::check())
				                <!-- ReCaptcha -->
				                <div class="uk-form-row uk-width-large-10-10 uk-margin-top uk-align-center uk-hidden" id="captcha">
				                    <div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
				                    <p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
				                </div>
				                <!-- ReCaptcha -->
				            @endif

				            <button form="send_message_inpage" type="submit" class="uk-button uk-button-large uk-width-1-1  uk-button-primary uk-margin-top">{{ trans('frontend.contact_button') }}</button>
						</form>
					@else
						<h3 class="uk-text-primary">{{ trans('frontend.already_contacted_vendor') }}</h3>
					@endif
					
					<div class="uk-margin-top">
						<button id="my-id2" class="uk-button uk-button-large uk-width-1-1" data-uk-toggle="{target:'#my-id, #my-id2'}">{{ trans('frontend.contact_show_info') }}</button>

						<div id="my-id" class="uk-hidden">
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
					<button class="uk-button uk-button-large uk-width-1-1 uk-margin-top" onclick="select(this)" id="{{ $listing->id }}">{{ trans('frontend.compare') }}</button>
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
					<a href="#" class="uk-icon-button uk-icon-envelope"></a>
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

    				<li><i class="uk-text-muted">{{ trans('admin.code') }}</i> <b>#{{ $listing->code }}</b></li>
    			</ul>

    			<a href="#new_appointment_modal" class="uk-button uk-button-large uk-button-primary uk-width-1-1" data-uk-modal>{{ trans('frontend.contact_vendor') }}</a>
    			<a href="{{ url($listing->broker->path()) }}" class="uk-button uk-button-large uk-width-1-1 uk-margin-top">{{ trans('frontend.other_user_listings') }}</a>

    			<hr>

    			@if(count($related) > 0)
	    			<div class="uk-margin-medium-top uk-hidden-small">
	    				<h2 class="uk-text-bold">{{ trans('frontend.similar_listings') }}</h2>
	    				@foreach($related as $rlisting)
		    				<div class="uk-overlay uk-overlay-hover uk-margin-small">
		    					<img class="uk-border-rounded" src="{{ asset(Image::url( $rlisting->image_path(), ['map_mini']) ) }}" alt="{{$rlisting->title}}" data-uk-scrollspy="{cls:'uk-animation-slide-left'}">
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

	    		<?php echo $map['html']; ?>
	    	</div>
	    	
	    </div>
	</div>
	@include('appointments.new')
</div>
@endsection

@section('js')
	@parent

	<!-- CSS -->
	<noscript><link href="{{ asset('/css/components/slideshow.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<noscript><link href="{{ asset('/css/components/slidenav.almost-flat.min.css') }}" rel="stylesheet"></noscript>
	<!-- CSS -->

	<!-- JS -->
	@if(!Auth::check())
	<script async src='https://www.google.com/recaptcha/api.js'></script>
	@endif
    <script src="{{ asset('/js/components/slideshow.min.js') }}"></script>
	<!-- JS -->
	
	<script type="text/javascript">
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
				}, {labels:{Ok:'{{trans("frontend.compare_now")}}', Cancel:'{{trans("frontend.keep_looking")}}'}});
            });
		}

		$(function (){
			$('#phone_1').html(phoneFormat($('#phone_1').html()));
			$('#phone_2').html(phoneFormat($('#phone_2').html()));
		});
	</script>

	<!-- Google maps js -->
	<?php echo $map['js']; ?>
	<!-- Google maps js -->
@endsection