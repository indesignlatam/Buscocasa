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

		<div class="uk-grid">
			<div class="uk-width-1-4">
				<div class="uk-panel uk-panel-box uk-panel-box-secondary">
					<h2>{{ trans('admin.free') }}</h2>
					<div class="uk-text-center"><img src="{{ asset('/images/support/highlight/destacado.png') }}" width="80%"></div>
					<p>{{ trans('admin.free_text') }}</p>
					<ul class="uk-list">
						<li class="uk-text-contrast"> - </li>
						<li class="uk-text-contrast"> - </li>
						<li class=""><i class="uk-icon-check"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
						<li class=""><i class="uk-icon-check"></i> {{ Settings::get('free_image_limit') }} {{ trans('admin.photos') }}</li>
						<li class="uk-margin-top uk-h2 uk-text-center">{{ trans('admin.free') }}</li>
					</ul>
					<button class="uk-button uk-button-success uk-button-large uk-width-1-1" style="background-color:#1481e3" type="submit" form="free">{{ trans('admin.renovate') }}</button>
				</div>
			</div>

			@foreach($featuredTypes as $type)
			<div class="uk-width-1-4">
				<div class="uk-panel uk-panel-box uk-panel-box-secondary">
					<h2>{{ $type->name }}</h2>
					<div class="uk-text-center"><img src="{{ asset($type->icon) }}" width="80%"></div>
					<p>{{ $type->description }}</p>
					<ul class="uk-list">
						<li class=""><i class="uk-icon-check"></i> {{ trans('admin.homepage_rotation') }}</li>
						<li class=""><i class="uk-icon-check"></i> {{ trans('admin.outstanding_container') }}</li>
						<li class=""><i class="uk-icon-check"></i> {{ Settings::get('listing_expiring') }} {{ trans('admin.days') }}</li>
						<li class=""><i class="uk-icon-check"></i> {{ Settings::get('featured_image_limit') }} {{ trans('admin.photos') }}</li>
						<li class="uk-margin-top uk-h2 uk-text-center" id="price-{{ $type->id }}">{{ $type->price }}</li>
					</ul>
					<button class="uk-button uk-button-success uk-button-large uk-width-1-1" onclick="selectFeature({{$type->id}})" style="background-color:{{$type->color}}">{{ trans('admin.renovate') }}</button>
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