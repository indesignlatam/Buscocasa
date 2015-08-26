@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.renovate_listing') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.renovate_listing') }}</h1>

	    <hr>

	   	<p>{{ trans('admin.renovate_listing_text') }}</p>

		<div class="uk-grid uk-grid-small" data-uk-grid-margin>
			<div class="uk-width-small-1-1 uk-width-medium-1-4 uk-width-large-1-4">
				<div class="uk-panel uk-panel-box uk-panel-box-primary">
					<h2>{{ trans('admin.free') }}</h2>
					<div class="uk-text-center"><img src="{{ asset('/images/support/highlight/free.png') }}" width="80%"></div>
					<p>{{ trans('admin.free_text') }}</p>
					<ul class="uk-list">
						<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.homepage_rotation') }}</li>
						<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.social_publish') }}</li>
						<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.better_search_positions') }}</li>
						<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.listing_container_ribbon') }}</li>
						<li class=""><i class="uk-icon-remove uk-text-danger"></i> {{ trans('admin.outstanding_container') }}</li>
						<li class=""><i class="uk-icon-check uk-text-success"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
						<li class=""><i class="uk-icon-check uk-text-success"></i> {{ Settings::get('free_image_limit') }} {{ trans('admin.photos') }}</li>
						<li class="uk-margin-top uk-h2 uk-text-center">{{ trans('admin.free') }}</li>
					</ul>
					<button class="uk-button uk-button-large uk-width-1-1" type="submit" form="free">{{ trans('admin.renovate') }}</button>
				</div>
			</div>

			@foreach($featuredTypes as $type)
			<div class="uk-width-small-1-1 uk-width-medium-1-4 uk-width-large-1-4">
				<div class="uk-panel uk-panel-box uk-panel-box-primary">
					<h2>{{ $type->name }}</h2>
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

						@if($type->id >= 2)
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

						@if($type->id > 2)
							<li class="uk-margin-top uk-h2 uk-text-center" id="price-{{ $type->id }}">{{ money_format('$%!.0i', $type->price) }}</li>
						@else
							<li class="uk-margin-top uk-h2 uk-text-center" id="price-{{ $type->id }}">{{ money_format('$%!.0i', $type->price) }}</li>
						@endif
					</ul>

					@if($type->id > 1)
						<button class="uk-button uk-button-success uk-button-large uk-width-1-1" onclick="selectFeature({{$type->id}})" style="background-color:{{$type->color}}">{{ trans('admin.renovate') }}</button>
					@else
						<button class="uk-button uk-button-large uk-width-1-1" onclick="selectFeature({{$type->id}})">{{ trans('admin.renovate') }}</button>
					@endif
				</div>
			</div>
			@endforeach
		</div>

		<div id="forms" class="uk-hidden">
			<form action="{{ Request::url() }}" method="POST" id="free">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>

			<form action="{{ url('/admin/pagos') }}" method="POST" id="featured">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input name="listing_id"   	type="hidden"  value="{{ $listing->id }}">
				<input name="featured_id"   type="hidden"  value="" id="featured_id">
			</form>
		</div>

	</div>
</div>
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">

    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>


	<script type="text/javascript">
		$(function() {
			@foreach($featuredTypes as $type)
				$('#price-{{ $type->id }}').html('$'+accounting.formatNumber($('#price-{{ $type->id }}').html()));
			@endforeach
		});

		function selectFeature(input){
			$("#featured_id").val(input);
			document.forms["featured"].submit();
		}	    
	</script>
@endsection