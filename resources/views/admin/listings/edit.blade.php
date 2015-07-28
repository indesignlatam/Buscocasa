@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.edit_listing') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
    <style type="text/css">
    	.main-image{
    		border: 4px solid #8AC007;
    	}
    </style>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.edit_listing') }}</h1>
	    
		<hr>
	    
	    <div class="uk-panel">
			<a class="uk-button uk-button-large uk-float-right uk-margin-left" href="{{ url('/admin/listings') }}">{{ trans('admin.close') }}</a>
	        <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-form-width-medium uk-float-right" onclick="blockUI()">{{ trans('admin.save') }}</button>
	    </div>

		<form id="create_form" class="uk-form uk-form-stacked" method="POST" action="{{ url('/admin/listings/'.$listing->id) }}" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">

			<input id="latitude" type="hidden" name="latitude" value="{{ $listing->latitude }}">
        	<input id="longitude" type="hidden" name="longitude" value="{{ $listing->longitude }}">
        	<input id="main_image_id" type="hidden" name="main_image_id" value="{{ $listing->main_image_id }}">
        	<input id="image_path" type="hidden" name="image_path" value="{{ $listing->image_path }}">
        	<input id="save_close" type="hidden" name="save_close" value="0">

			<div class="uk-grid uk-margin-top">

				<div class="uk-width-large-1-10 uk-width-medium-1-10">

					<ul class="uk-list uk-hidden-small" data-uk-sticky="{boundary: true}">
					    <li data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_1') }}">
					    	<a href="#1" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/1.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_2') }}">
					    	<a href="#3" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/2.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_3') }}">
					    	<a href="#4" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/3.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_4') }}">
					    	<a href="#5" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/4.png') }}"></a>
					    </li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_5') }}">
					    	<a href="#6" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/5.png') }}"></a>
					  	</li>
					    <li style="margin-top:-20px" data-uk-tooltip="{pos:'left'}" title="{{ trans('admin.steps_6') }}">
					    	<a href="#7" data-uk-smooth-scroll="{offset: 0}"><img src="{{ asset('/images/support/listings/steps/columna/6.png') }}"></a>
					    </li>
					</ul>
				</div>

				<div class="uk-width-large-9-10 uk-width-medium-9-10" id="1">
					<!-- Categoria - tipo de publicacion - ubicacion -->
					<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_data_location') }}</h2>
					<div class="uk-grid">
						<div class="uk-width-large-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.category') }} <i class="uk-text-danger">*</i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="category" name="category_id" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.category_tooltip') }}">
						                @foreach($categories as $category)
						                	@if($listing->category->id == $category->id)
												<option value="{{ $category->id }}" selected>{{ str_singular($category->name) }}</option>
						                	@else
						                		<option value="{{ $category->id }}">{{ str_singular($category->name) }}</option>
						                	@endif				                	
						                @endforeach
						            </select>
						        </div>
						    </div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.city') }} <i class="uk-text-danger">*</i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="city" type="text" name="city_id">
						                @foreach($cities as $city)
						                	@if($listing->city->id == $city->id)
												<option value="{{ $city->id }}" selected="true">{{ $city->name }} ({{ $city->department->name }})</option>
						                	@else
						                		<option value="{{ $city->id }}">{{ $city->name }} ({{ $city->department->name }})</option>
						                	@endif	
						                @endforeach
					            	</select>
						        </div>
						    </div>

						    <div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.district') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="district" value="{{ $listing->district }}" placeholder="{{ trans('admin.district') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.district_tooltip') }}">
						    </div>
						</div>

						<div class="uk-width-large-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.listing_type') }} <i class="uk-text-danger">*</i></label>
						        <div class="uk-form-controls">
						        	<select class="uk-width-large-10-10 uk-form-large" id="listing_type" name="listing_type" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_type_tooltip') }}">
						                @foreach($listingTypes as $listingType)
						                	@if($listing->listingType->id == $listingType->id)
												<option value="{{ $listingType->id }}" selected>{{ $listingType->name }}</option>
						                	@else
						                		<option value="{{ $listingType->id }}">{{ $listingType->name }}</option>
						                	@endif	
						                @endforeach
						            </select>
						        </div>
						    </div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.address') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="direction" type="text" name="direction" value="{{ $listing->direction }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.direction_tooltip') }}">
							</div>
						</div>
						<!-- Categoria - tipo de publicacion - ubicacion -->

						<!-- Mapa -->
						<div class="uk-margin uk-width-1-1" id="2">
							<p class="uk-text-primary uk-text-bold">{{ trans('admin.select_map_location') }}</p>
							<input class="uk-width-large-5-10 uk-form-large uk-margin-bottom" id="gmap_search" type="text" placeholder="{{ trans('admin.gmap_search') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.gmap_search_tooltip') }}">
							<?php echo $map['html']; ?>
						</div>
						<!-- Mapa -->

						<!-- Informacion basica del inmueble -->
						<div class="uk-width-1-1 uk-margin-bottom">
							<hr>
							<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase" id="3">{{ trans('admin.listing_basic_information') }}</h2>
						</div>

						<div class="uk-width-large-1-3 uk-width-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.price') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="price" type="text" name="price" placeholder="{{ trans('admin.price') }}" value="{{ $listing->price }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.price_tooltip') }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.stratum') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="stratum" placeholder="{{ trans('admin.stratum') }}" value="{{ $listing->stratum }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.area') }} <i class="uk-text-danger">*</i></label>
								<input class="uk-width-large-10-10 uk-form-large" id="area" type="text" name="area" placeholder="{{ trans('admin.area') }}" value="{{ $listing->area }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.area_tooltip') }}" onkeyup="format(this);">
							</div>
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.lot_area') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" id="lot_area" type="text" name="lot_area" placeholder="{{ trans('admin.lot_area') }}" value="{{ $listing->lot_area }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.lot_area_tooltip') }}" onkeyup="format(this);">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.rooms') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="rooms" placeholder="{{ trans('admin.rooms') }}" value="{{ $listing->rooms }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.bathrooms') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="bathrooms" placeholder="{{ trans('admin.bathrooms') }}" value="{{ $listing->bathrooms }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.garages') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="garages" placeholder="{{ trans('admin.garages') }}" value="{{ $listing->garages }}" onkeyup="format(this);">
							</div>
						</div>

						<div class="uk-width-large-1-3 uk-width-1-2">
							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.floor') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="floor" placeholder="{{ trans('admin.floor') }}" value="{{ $listing->floor }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.floor_tooltip') }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.construction_year') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" type="text" name="construction_year" placeholder="{{ trans('admin.construction_year') }}" value="{{ $listing->construction_year }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.construction_year_tooltip') }}" onkeyup="format(this);">
							</div>

							<div class="uk-form-row">
						        <label class="uk-form-label" for="">{{ trans('admin.administration_fees') }}</label>
								<input class="uk-width-large-10-10 uk-form-large" id="administration" type="text" name="administration" placeholder="{{ trans('admin.administration_fees') }}" value="{{ $listing->administration }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.administration_fees_tooltip') }}" onkeyup="format(this);">
							</div>
						</div>
						<!-- Informacion basica del inmueble -->
					</div>

					<hr>

					<!-- Caracteristicas del inmueble -->
					<div id="4">
						<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_caracteristics') }}</h2>

						<h3>{{ trans('admin.interior') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 1)
									<div class="uk-width-large-1-3 uk-width-1-2">
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
											<label><input type="checkbox" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
											<?php $featureChecked = false; ?>
										@else
											<label><input type="checkbox" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif										
									</div>
								@endif
							@endforeach
						</div>

						<h3>{{ trans('admin.exterior') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 2)
									<div class="uk-width-large-1-3 uk-width-1-2">
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
											<label><input type="checkbox" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
											<?php $featureChecked = false; ?>
										@else
											<label><input type="checkbox" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif										
									</div>
								@endif
							@endforeach
						</div>

						<h3>{{ trans('admin.sector') }}</h3>
						<div class="uk-grid">
							@foreach($features as $feature)
								@if($feature->category->id == 3)
									<div class="uk-width-large-1-3 uk-width-1-2">
										<?php $featureChecked = false; ?>
										@foreach($listing->features as $listingFeature)
											@if($feature->id == $listingFeature->id)
												<?php $featureChecked = true; break; ?>
											@endif
										@endforeach
										@if($featureChecked)
											<label><input type="checkbox" name="{{ $feature->id }}" checked> {{ $feature->name }}</label>
											<?php $featureChecked = false; ?>
										@else
											<label><input type="checkbox" name="{{ $feature->id }}"> {{ $feature->name }}</label>
										@endif										
									</div>
								@endif
							@endforeach
						</div>
					</div>
					<!-- Caracteristicas del inmueble -->

					<hr>

					<!-- Informacion adicional -->
					<div id="5">
						<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.listing_description') }}</h2>
						<p class="uk-margin-top-remove">{{ trans('admin.listing_description_help') }}</p>
						<textarea class="uk-width-large-10-10 uk-margin-small-bottom" rows="5" name="description">{{ $listing->description }}</textarea>
					</div>
					<!-- Informacion adicional -->

					<hr>

					<!-- Image upload -->
					<div id="6">
						<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.images') }}</h2>
						<p>{{ trans('admin.add_images_to_listing') }}</p>

						<div id="images_uploaded">
						   
						</div>

				    	<div id="upload-drop" class="uk-placeholder uk-placeholder-large uk-text-center uk-margin-top">
						    <i class="uk-icon-large uk-icon-cloud-upload"></i> {{ trans('admin.drag_listing_images_or') }} <a class="uk-form-file">{{ trans('admin.select_an_image') }}<input id="upload-select" type="file"></a>
						</div>

						<div id="progressbar" class="uk-progress uk-hidden">
						    <div class="uk-progress-bar" style="width: 0%;"></div>
						</div>

						<div class="uk-margin-large-top uk-grid" id="images-div">
							@foreach($listing->images as $image)
								@if($image->id == $listing->main_image_id)
									<div class="uk-width-large-1-4 uk-width-medium-1-3" id="image-{{ $image->id }}">
										<figure class="uk-overlay uk-overlay-hover uk-margin-bottom main-image">
											<img src="{{ asset($image->image_path) }}">
										    <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center">
										    	<i class="uk-icon-large uk-icon-remove" id="{{ $image->id }}" onclick="deleteImage(this)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminate_image') }}"></i> 
										    	<i class="uk-icon-large uk-icon-check" onclick="selectMainImage({{ $image->id }}, '{{ $image->image_path }}')" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.set_as_main_image') }}"></i>
										    </div>
										</figure>
									</div>
								@else
									<div class="uk-width-large-1-4 uk-width-medium-1-3" id="image-{{ $image->id }}">
										<figure class="uk-overlay uk-overlay-hover uk-margin-bottom">
											<img src="{{ asset($image->image_path) }}">
										    <div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center">
										    	<i class="uk-icon-large uk-icon-remove" id="{{ $image->id }}" onclick="deleteImage(this)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminate_image') }}"></i> 
										    	<i class="uk-icon-large uk-icon-check" onclick="selectMainImage({{ $image->id }}, '{{ $image->image_path }}')" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.set_as_main_image') }}"></i>
										    </div>
										</figure>
									</div>
								@endif
							@endforeach
						</div>
				    </div>
				    <!-- Image upload -->

				    <hr>

				    <!-- Share listing -->
				    <div class="uk-margin-large-bottom" id="7">
				    	<h2 class="uk-text-primary uk-text-bold" style="text-transform: uppercase">{{ trans('admin.share_social') }}</h2>

						<div class="uk-flex">
							<a onclick="share('{{ url($listing->path()) }}')" class="uk-icon-button uk-icon-facebook uk-margin-right"></a>
	        				<a class="uk-icon-button uk-icon-twitter twitter-share-button uk-margin-right" href="https://twitter.com/intent/tweet?text=Hello%20world%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
	    					<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus uk-margin-right"></a>
	    					<a href="" class="uk-icon-button uk-icon-envelope uk-margin-right"></a>
						</div>
				    </div>
				    <!-- Share listing -->

					<div class="uk-margin-top uk-flex">
				        <!-- This is a button toggling the modal -->
				        <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-text-bold uk-width-5-10 uk-margin-right" onclick="blockUI()">{{ trans('admin.save') }}</button>
				        <button form="create_form" type="submit" class="uk-button uk-button-large uk-text-bold uk-width-5-10" onclick="saveClose()" >{{ trans('admin.save_close') }}</button>
				    </div>
				</div>

			</div>
		</form>

	</div>
</div>

@if($listing->featured_expires_at && $listing->featured_expires_at < Carbon::now()->addDays(5))
	<!-- This is the modal -->
	<div id="expires_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.listing_expiring_soon') }}
	        </div>

	        <h2 class="uk-text-danger">El inmueble expira {{ Carbon::createFromFormat('Y-m-d H:i:s', $listing->featured_expires_at)->diffForHumans() }}</h2>
	        <a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">Renovar</a>
		    <div class="uk-modal-footer">
		    	<a href="" class="uk-button uk-button-danger uk-modal-close">{{ trans('admin.close') }}</a>
		    </div>
	    </div>
	</div>
@elseif($listing->expires_at && $listing->expires_at < Carbon::now()->addDays(5))
	<div id="expires_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.listing_expiring_soon') }}
	        </div>

	        <h2 class="uk-text-danger">El inmueble expira {{ Carbon::createFromFormat('Y-m-d H:i:s', $listing->expires_at)->diffForHumans() }}</h2>
	        <a href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">Renovar</a>
		    <div class="uk-modal-footer">
		    	<a href="" class="uk-button uk-button-danger uk-modal-close">{{ trans('admin.close') }}</a>
		    </div>
	    </div>
	</div>
@endif

@if(!count($listing->images))
	<!-- This is the modal -->
	<div id="upload_modal" class="uk-modal">
	    <div class="uk-modal-dialog">
	        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
	        <div class="uk-modal-header uk-text-bold">
	        	{{ trans('admin.add_images_to_listing') }}
	        </div>

	        <div id="images_uploaded_modal" class="uk-alert uk-alert-success uk-animation-fade uk-hidden" data-uk-alert>
			    <a href="" class="uk-alert-close uk-close"></a>
			    <p>{{ trans('admin.images_uploaded_succesfuly') }}</p>
			</div>

	        <div class="uk-grid uk-grid-collapse">
	        	{{-- <div class="uk-width-1-1 uk-text-center">
	        		<a onclick="uplink()" class="uk-align-center">
	        			<img src="{{ asset('/images/support/upload-images.png') }}" width="50%">
	        		</a>
	        	</div> --}}

	        	<div class="uk-width-1-1">
	        		<div id="upload_drop_modal" class="uk-placeholder uk-placeholder-large uk-text-center uk-margin-top">
					    <i class="uk-icon-large uk-icon-cloud-upload"></i> {{ trans('admin.drag_listing_images_or') }} <a class="uk-form-file">{{ trans('admin.select_an_image') }}<input id="upload_select_modal" type="file"></a>
					</div>

					<div id="progressbar_modal" class="uk-progress uk-hidden">
					    <div class="uk-progress-bar" style="width: 0%;"></div>
					</div>
	        	</div>
	        </div>

	        <div class="uk-margin-large-top uk-grid" id="images_div_modal">
						
			</div>

		    <div class="uk-modal-footer">
		    	<a href="" class="uk-button uk-button-danger uk-modal-close">{{ trans('admin.close') }}</a>
		    </div>
	    </div>
	</div>
@endif
@endsection

@section('js')
	<link href="{{ asset('/css/components/form-file.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/upload.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/placeholder.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/progress.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/sticky.almost-flat.min.css') }}" rel="stylesheet">

	@parent
	<script src="{{ asset('/js/components/upload.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>

	<script src="{{ asset('/js/accounting.min.js') }}"></script>

	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('/js/select2.min.js') }}"></script>

	<script type="text/javascript">var centreGot = false;</script>
	<?php echo $map['js']; ?>
	<script type="text/javascript">
		$(function() {
			$("#city").select2();

			$('#price').val(accounting.formatNumber(document.getElementById('price').value));
			$('#area').val(accounting.formatNumber(document.getElementById('area').value));
			$('#lot_area').val(accounting.formatNumber(document.getElementById('lot_area').value));
			$('#administration').val(accounting.formatNumber(document.getElementById('administration').value));

		@if($listing->featured_expires_at && $listing->featured_expires_at < Carbon::now()->addDays(5))
			var modal = UIkit.modal("#expires_modal");
			modal.show()
		@elseif($listing->expires_at && $listing->expires_at < Carbon::now()->addDays(5))
			var modal = UIkit.modal("#expires_modal");
			modal.show()
		@elseif(!count($listing->images))
			var modal = UIkit.modal("#upload_modal");
			modal.show()
		@endif


		});

		function blockUI(){
	        var modal = UIkit.modal.blockUI('<h3 class="uk-text-center">Guardando inmueble, porfavor espere.</h3><div class="uk-text-center uk-text-primary"><i class="uk-icon-large uk-icon-spinner uk-icon-spin"</i></div>'); // modal.hide() to unblock
	    }

		function format(field){
	        field.value = accounting.formatNumber(field.value);
	    }

	    function selectMainImage(mainImageId, path){
	    	value = $('#main_image_id').val();

	    	$("#image-"+value+" > figure").removeClass('main-image');
	    	$("#image-"+mainImageId+" > figure").addClass('main-image');

	    	$('#main_image_id').val(mainImageId);
	    	$('#image_path').val(path);
	    }

        function deleteImage(sender) {
	        $.post("{{ url('/admin/images') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	            $("#image-"+sender.id).remove();
	            if(sender.id == $('#main_image_id').val()){
                	$('#main_image_id').val(null);
                	$('#image_path').val(null);
                }
                $("#images_uploaded").prepend('<div id="images_uploaded" class="uk-alert uk-alert-success" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>{{ trans("admin.image_deleted_succesfuly") }}</p></div>');
	        });
	    }

	    // Modal uploader
        $(function(){
	        var progressbar = $("#progressbar_modal"),
	            bar         = progressbar.find('.uk-progress-bar'),
	            settings    = {
		            action: '{{ url("/admin/images") }}', // upload url
		            single: 'false',
		            param: 'image',
		            type: 'json',
		            params: {_token:"{{ csrf_token() }}", listing_id:{{ $listing->id }}},
		            allow : '*.(jpg|jpeg|png)', // allow only images

		            loadstart: function() {
		                bar.css("width", "0%").text("0%");
		                progressbar.removeClass("uk-hidden");
		            },

		            progress: function(percent) {
		                percent = Math.ceil(percent);
		                bar.css("width", percent+"%").text(percent+"%");
		            },

		            error: function(response) {
		                alert("Error uploading: " + response);
		            },

		            complete: function(response) {
		            	if(!response.error && response.image){
		            		$("#images_uploaded_modal").html('<p>Imagen cargada exitosamente</>');
		            		$("#images_uploaded_modal").removeClass('uk-hidden');
		            		$("#images_div_modal").prepend('<div class="uk-width-1-4" id="image-'+response.image.id+'"><figure class="uk-overlay uk-overlay-hover uk-margin-bottom"><img src="{{asset("")}}'+response.image.image_path+'"><div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center uk-vertical-align"><i class="uk-icon-large uk-icon-remove uk-vertical-align-middle" id="'+response.image.id+'" onclick="deleteImage(this)"></i> <i class="uk-icon-large uk-icon-check uk-vertical-align-middle" onclick="selectMainImage('+response.image.id+', '+response.image.image_path+')"></i></div></figure></div>');

		            		// Insite uploader images
		            		$("#images-div").prepend('<div class="uk-width-large-1-4 uk-width-medium-1-3" id="image-'+response.image.id+'"><figure class="uk-overlay uk-overlay-hover uk-margin-bottom"><img src="{{asset("")}}'+response.image.image_path+'"><div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center"><i class="uk-icon-large uk-icon-remove" id="'+response.image.id+'" onclick="deleteImage(this)" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.eliminate_image") }}"></i> <i class="uk-icon-large uk-icon-check" onclick="selectMainImage('+response.image.id+', '+response.image.image_path+')" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.set_as_main_image") }}"></i></div></figure></div>');
		            	}else{
		            		$("#images_uploaded_modal").removeClass('uk-hidden');
		            		$("#images_uploaded_modal").removeClass('uk-alert-success');
		            		$("#images_uploaded_modal").addClass('uk-alert-danger');
		            		if(response.error instanceof Array){
		            			html = '<ul>'
		            			response.error.forEach(function(entry) {
								    html = html+'<li>'+entry['image']+'</li>';
								});
								html = html+'</ul>'
								$("#images_uploaded_modal").html(html);
		            		}else{
		            			$("#images_uploaded_modal").html('<p>'+ response.error +'</p>');
		            		}
		            	}
		            },

		            allcomplete: function(response) {
		                bar.css("width", "100%").text("100%");
		                setTimeout(function(){
		                    progressbar.addClass("uk-hidden");
		                }, 250);

		                $('#images_uploaded_modal').delay(10000).queue(function(next){
						    $(this).addClass("uk-hidden", 500, "fadeOut");
						    next();
						});
		            }
		        };

	        var select = UIkit.uploadSelect($("#upload_select_modal"), settings),
	            drop   = UIkit.uploadDrop($("#upload_drop_modal"), settings);
	    });
		// Modal uploader

		function uplink(){
			$('#upload_select_modal').click();
		}

		// Inpage uploader
		$(function(){
	        var progressbar = $("#progressbar"),
	            bar         = progressbar.find('.uk-progress-bar'),
	            settings    = {
		            action: '{{ url("/admin/images") }}', // upload url
		            single: 'false',
		            param: 'image',
		            type: 'json',
		            params: {_token:"{{ csrf_token() }}", listing_id:{{ $listing->id }}},
		            allow : '*.(jpg|jpeg|png)', // allow only images

		            loadstart: function() {
		                bar.css("width", "0%").text("0%");
		                progressbar.removeClass("uk-hidden");
		            },

		            progress: function(percent) {
		                percent = Math.ceil(percent);
		                bar.css("width", percent+"%").text(percent+"%");
		            },

		            error: function(response) {
		                alert("Error uploading: " + response);
		            },

		            complete: function(response) {
		            	if(!response.error && response.image){
		            		$("#images_uploaded").prepend('<div id="images_uploaded" class="uk-alert uk-alert-success" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>{{ trans("admin.images_uploaded_succesfuly") }}</p></div>');
		            		$("#images-div").prepend('<div class="uk-width-large-1-4 uk-width-medium-1-3" id="image-'+response.image.id+'"><figure class="uk-overlay uk-overlay-hover uk-margin-bottom"><img src="{{asset("")}}'+response.image.image_path+'"><div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center"><i class="uk-icon-large uk-icon-remove" id="'+response.image.id+'" onclick="deleteImage(this)" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.eliminate_image") }}"></i> <i class="uk-icon-large uk-icon-check" onclick="selectMainImage('+response.image.id+', '+response.image.image_path+')" data-uk-tooltip="{pos:"top"}" title="{{ trans("admin.set_as_main_image") }}"></i></div></figure></div>');
		            	}else{
		            		if(response.error instanceof Array){
		            			html = '<div id="images_uploaded" class="uk-alert uk-alert-danger" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><ul>'
		            			response.error.forEach(function(entry) {
								    html = html+'<li>'+entry['image']+'</li>';
								});
								html = html+'</ul></div>'
								$("#images_uploaded").prepend(html);
		            		}else{
		            			$("#images_uploaded").prepend('<div id="images_uploaded" class="uk-alert uk-alert-danger" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>'+response.error+'</p></div>');
		            		}
		            	}
		            },

		            allcomplete: function(response) {
		                bar.css("width", "100%").text("100%");
		                setTimeout(function(){
		                    progressbar.addClass("uk-hidden");
		                }, 250);

		                
		            }
		        };

	        var select = UIkit.uploadSelect($("#upload-select"), settings),
	            drop   = UIkit.uploadDrop($("#upload-drop"), settings);
	    });
		// Inpage uploader


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

       	function share(path){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.shares',
			  	action_properties: JSON.stringify({
			    object:'{{ url('+path+') }}',
			})
			}, function(response){
				$.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key: "shared_listing_"+{{ $listing->id }}, value: true, time:11520}, function(result){
	                
	            });

			  	// Debug response (optional)
			  	console.log(response);
			});
       	}

       	function saveClose(){
       		$("#save_close").val('1');
       		blockUI();
       	}
	</script>
@endsection