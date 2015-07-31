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
	    	<div class="uk-width-7-10">
	    		<div class="uk-grid">
	    			@foreach($featuredTypes as $type)
	    			<div class="uk-width-1-3">
	    				<div class="uk-panel uk-panel-box uk-panel-box-secondary">
	    					<h3>{{ $type->name }}</h3>
	    					<div class="uk-text-center"><img src="{{ asset($type->icon) }}" width="80%"></div>
	    					<p>{{ $type->description }}</p>
	    					<ul class="uk-list">
	    						@if($type->id >= 3)
									<li class=""><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.homepage_rotation') }}</li>
	    						@else
									<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.homepage_rotation') }}</li>
	    						@endif

	    						@if($type->id >= 2)
									<li class=""><i class="uk-icon-check uk-text-success"></i> {{ trans('admin.outstanding_container') }}</li>
	    						@else
									<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.outstanding_container') }}</li>
	    						@endif

	    						@if($type->id)
									<li class=""><i class="uk-icon-check uk-text-success"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
	    						@else
									<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
	    						@endif

	    						@if($type->id)
									<li class=""><i class="uk-icon-check uk-text-success"></i> {{ Settings::get('featured_image_limit') }} {{ trans('admin.photos') }}</li>
	    						@else
									<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ Settings::get('featured_image_limit') }} {{ trans('admin.photos') }}</li>
	    						@endif

								<li class="uk-margin-top uk-h2 uk-text-center" id="price-{{ $type->id }}">{{ money_format('$%!.0i', $type->price*1.16) }}</li>
	    					</ul>
	    					<button class="uk-button uk-button-success uk-button-large uk-width-1-1" onclick="feature({{$type->id}})" style="background-color:{{$type->color}}" data-uk-smooth-scroll="{offset: -300}">{{ trans('admin.select') }}</button>
	    				</div>
	    			</div>
	    			@endforeach

	    			<div class="uk-width-1-1 uk-margin-top">
	    				<hr>
	    				<h3>{{ trans('admin.listing_preview') }}</h3>
			    		<a style="text-decoration:none" >
							<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin-remove" id="listing">
								<img src="{{ asset(Image::url($listing->image_path(),['mini_image_2x'])) }}" style="width:350px; height:200px; float:left" class="uk-margin-right">
								<h4 class="uk-margin-remove">{{ $listing->title }}</h4>
								{{-- <p style="margin-top:-2px" class="uk-text-muted">{{ $listing->city->name .", ". $listing->direction }}</p> --}}
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

	    	<div class="uk-width-3-10">
	    		<div class="uk-panel uk-panel-box" data-uk-sticky="{boundary: true}">
	    		<h3 class="uk-panel-title">{{ trans('admin.shop_basket') }}</h3>
		    		<table class="uk-table">
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
	<link href="{{ asset('/css/components/sticky.almost-flat.min.css') }}" rel="stylesheet">
	@parent
	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>


	<script type="text/javascript">
		// $(function() {
		// 	$('#price').val(accounting.formatNumber(document.getElementById('price').value));
		// 	$('#area').val(accounting.formatNumber(document.getElementById('area').value));
		// 	$('#lot_area').val(accounting.formatNumber(document.getElementById('lot_area').value));
		// 	$('#administration').val(accounting.formatNumber(document.getElementById('administration').value));
		// });

		$(function() {
			
		});

		function selectFeature(input){
			$("#featured_id").val(input);
			document.forms["featured"].submit();
		}


		function feature(input){
			types = {!! json_encode($featuredTypes) !!}
			tag = "";
			if(input == 1){
				console.log(1);
				$("#featured_id").val(1);
				tag = "<img src=\"{{asset('/images/defaults/featured.png')}}\" style=\"position:absolute; top:0; left:0; max-width:150px\" id=\"tag\">";
			}else if(input == 2){
				console.log(2);
				$("#featured_id").val(2);
				tag = "<img src=\"{{asset('/images/defaults/oportunidad.png')}}\" style=\"position:absolute; top:0; left:0; max-width:150px\" id=\"tag\">";
			}else if(input == 3){
				console.log(3);
				$("#featured_id").val(3);
				tag = "<img src=\"{{asset('/images/defaults/featured_full.png')}}\" style=\"position:absolute; top:0; left:0; max-width:150px\" id=\"tag\">";
			}
			$("#tag").remove();
	        $("#listing").prepend(tag);

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