@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.highlight_listing') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.highlight_listing') }}</h1>

	    <hr>

	   	<p>{{ trans('admin.highlight_listing_text') }}</p>

	    <div class="uk-grid">
	    	<div class="uk-width-small-1-1 uk-width-medium-1-1 uk-width-large-7-10">
	    		<div class="uk-grid">
	    			@foreach($featuredTypes as $type)
	    			<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
	    				<div class="uk-panel uk-panel-box uk-panel-box-secondary">
	    					<h3>{{ $type->name }}</h3>
	    					<div class="uk-text-center"><img src="{{ asset($type->icon) }}" width="80%"></div>
	    					<p>{{ $type->description }}</p>
	    					<ul class="uk-list">
	    						@if($type->id >= 3)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.homepage_rotation_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.homepage_rotation') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.homepage_rotation_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.homepage_rotation') }}</li>
								@endif

								@if($type->id >= 3)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.social_publish_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.social_publish') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.social_publish_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.social_publish') }}</li>
								@endif

								@if($type->id >= 2)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.better_search_positions_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.better_search_positions') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.better_search_positions_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.better_search_positions') }}</li>
								@endif

								@if($type->id >= 1)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_container_ribbon_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.listing_container_ribbon') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_container_ribbon_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.listing_container_ribbon') }}</li>
								@endif

								@if($type->id >= 1)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.outstanding_container_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.outstanding_container') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.outstanding_container_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.outstanding_container') }}</li>
								@endif

								@if($type->id)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_expiring_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.listing_expiring_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
								@endif

								@if($type->id)
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.featured_image_limit_tooltip') }}"><i class="uk-icon-check uk-text-success"></i> {{ Settings::get('featured_image_limit') }} {{ trans('admin.photos') }}</li>
								@else
									<li data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.featured_image_limit_tooltip') }}"><i class="uk-icon-remove uk-text-danger"></i> {{ Settings::get('featured_image_limit') }} {{ trans('admin.photos') }}</li>
								@endif

	    						<li class="uk-margin-top uk-h2 uk-text-center" id="price-{{ $type->id }}">{{ money_format('$%!.0i', $type->price) }}</li>
	    					</ul>

	    					<a href="#preview" class="uk-button uk-button-primary uk-button-large uk-width-1-1" style="background-color:{{$type->color}}" onclick="feature({{$type->id}})" data-uk-smooth-scroll>{{ trans('admin.select') }}</a>
	    				</div>
	    			</div>
	    			<div class="uk-visible-small uk-margin-top"></div>
	    			@endforeach

	    			<div class="uk-width-1-1 uk-margin-top" id="preview">
	    				<hr>
	    				<h3>{{ trans('admin.listing_preview') }}</h3>
			    		<a href="#" style="text-decoration:none">
							<div class="uk-panel uk-panel-box uk-panel-box uk-margin-bottom" style="border-left-width:4px; border-left-color:#ff4d53; border-left-style: solid;" id="listing">
								<img src="{{ asset(Image::url($listing->image_path(),['mini_image_2x'])) }}" style="width:350px; max-height:200px; float:left" class="uk-margin-right">
								<div class="uk-visible-small uk-width-1-1 uk-panel"></div>
								<h4 class="uk-margin-remove">{{ $listing->title }}</h4>
								<h4 style="margin-top:0px" class="uk-text-primary">${{ money_format('%!.0i', $listing->price) }}</h4>
								<ul style="list-style-type: none;margin-top:-5px" class="uk-text-muted uk-text-small">
									@if($listing->rooms)
									<li><i class="uk-icon-check"></i> {{ $listing->rooms }} {{ trans('admin.rooms') }}</li>
									@endif

									@if($listing->bathrooms)
									<li><i class="uk-icon-check"></i> {{ $listing->bathrooms }} {{ trans('admin.bathrooms') }}</li>
									@endif

									@if($listing->garages)
									<li><i class="uk-icon-check"></i> {{ $listing->garages }} {{ trans('admin.garages') }}</li>
									@endif

									@if($listing->stratum)
									<li><i class="uk-icon-check"></i> {{ trans('admin.stratum') }} {{ $listing->stratum }}</li>
									@endif

									@if($listing->area)
									<li><i class="uk-icon-check"></i> {{ number_format($listing->area, 0, ',', '.') }} mt2</li>
									@endif

									@if($listing->lot_area)
									<li id="lot_area"><i class="uk-icon-check"></i> {{ number_format($listing->lot_area, 0, ',', '.') }} {{ trans('frontend.lot_area') }}</li>
									@endif

									@if((int)$listing->administration != 0)
									<li><i class="uk-icon-check"></i> {{ money_format('$%!.0i', $listing->administration) }} {{ trans('admin.administration_fees') }}</li>
									@endif
								</ul>
							</div>
						</a>
	    			</div>
	    		</div>
	    	</div>

	    	<div class="uk-hidden-large uk-margin-top"></div>

	    	<div class="uk-width-small-1-1 uk-width-medium-1-1 uk-width-large-3-10">
	    		<div class="uk-panel uk-panel-box uk-panel-box-primary" data-uk-sticky="{boundary: true}">
	    		<h3 class="uk-panel-title">{{ trans('admin.shop_basket') }}</h3>
		    		<table class="uk-table uk-table-striped">
		    			<thead>
		    				<tr>
					            <th style="width:65%">{{ trans('admin.item') }}</th>
					            <th style="width:35%">{{ trans('admin.price') }}</th>
					        </tr>
		    			</thead>
		    			<tbody>
		    				<tr>
		    					@if(!is_null(old('featured_id')))
			    					@foreach($featuredTypes as $type)
			    						@if(old('featured_id') == $type->id)
				    						<td id="name">{{$type->name}}</td>
				    						<td id="price">{{$type->price}}</td>
				    					@endif
				    				@endforeach
				    			@else
				    				<td id="name"></td>
				    				<td id="price"></td>
				    			@endif
		    				</tr>
		    				<tr><td></td><td></td></tr>
		    			</tbody>

		    			<tfoot>
					        <tr>
					            <td>{{ trans('admin.tax') }}</td>
					            <td id="iva"></td>
					        </tr>
					        <tr class="uk-text-bold">
					            <td>{{ trans('admin.total') }}</td>
					            <td id="total"></td>
					        </tr>
					    </tfoot>
		    		</table>

	    			<form id="create_form" class="uk-form uk-form-stacked uk-margin-top" method="POST" action="{{ url('/admin/pagos') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input name="listing_id"   	type="hidden"  value="{{ $listing->id }}">
						<input name="featured_id"   type="hidden"  value="" id="featured_id">
						<div class="uk-margin-top">
					        <!-- This is a button toggling the modal -->
					        <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-1">{{ trans('admin.pay') }}</button>
					    </div>
					</form>
	    		</div>
	    	</div>

	    </div>

	</div>
</div>
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/components/sticky.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">

	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>


	<script type="text/javascript">
		function selectFeature(input){
			$("#featured_id").val(input);
			document.forms["featured"].submit();
		}


		function feature(input){
			types = {!! json_encode($featuredTypes) !!}
			$("#featured_id").val(input);
			tag = '<div id="tag" style="background-color:'+types[input-1].color+'; position:absolute; top:15px; left:15px;" class="uk-text-center uk-text-contrast uk-h3"><p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right"><i class="'+types[input-1].uk_class+'"></i> '+types[input-1].name+'</p></div>';

			$("#tag").remove();
	        $("#listing").prepend(tag).css('border-left-color',types[input-1].color);


	        price	= accounting.formatMoney(types[input-1].price/1.16, "$", 0, ",", ".");
	        tax 	= accounting.formatMoney((types[input-1].price/1.16)*0.16, "$", 0, ",", ".");
	        total 	= accounting.formatMoney(types[input-1].price, "$", 0, ",", ".");
	        $("#name").html(types[input-1].name);
	        $("#price").html(price);
	        $("#iva").html(tax);
	        $("#total").html(total);
	    }

	    function format(field){
	        field.value = accounting.formatNumber(field.value);
	    }
		
	    
	</script>
@endsection