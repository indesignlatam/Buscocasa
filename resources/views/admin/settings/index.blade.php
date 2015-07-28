@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.configuration') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')
<div class="uk-container uk-container-center">
	<div class="uk-panel">
		<h1>{{ trans('admin.configuration') }}</h1>
		<hr>
		<form id="config" class="uk-form uk-form-stacked">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">
			<div class="uk-grid uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}">
				<!-- First Row -->
				<div class="uk-width-1-2">
					<div class="uk-panel">
						<h2>{{ trans('admin.system') }}</h2>
						<hr>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.site_name') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="site_name" type="text" value="{{ Settings::get('site_name') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.site_description') }} <i class="uk-text-danger">*</i></label>
							<textarea class="uk-width-large-10-10 uk-form-large" id="site_description" rows="3" onchange="updateSetting(this.id, this.value)">{{ Settings::get('site_description') }}</textarea>
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.listings_description') }} <i class="uk-text-danger">*</i></label>
							<textarea class="uk-width-large-10-10 uk-form-large" id="listings_description" rows="3" onchange="updateSetting(this.id, this.value)">{{ Settings::get('listings_description') }}</textarea>
						</div>	
					</div>
				</div>

				<!-- Other -->
				<div class="uk-width-1-2">
					<div class="uk-panel">
						<h2>{{ trans('admin.other') }}</h2>
						<hr>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.query_cache_time') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="query_cache_time" type="number" value="{{ Settings::get('query_cache_time') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.query_cache_time_short') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="query_cache_time_short" type="number" value="{{ Settings::get('query_cache_time_short') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.query_cache_time_extra_short') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="query_cache_time_extra_short" type="number" value="{{ Settings::get('query_cache_time_extra_short') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.pagination_objects') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="pagination_objects" type="number" value="{{ Settings::get('pagination_objects') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.facebook_app_id') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="facebook_app_id" type="number" value="{{ Settings::get('facebook_app_id') }}" onchange="updateSetting(this.id, this.value)">
						</div>
					</div>
				</div>

				<div class="uk-width-1-2  uk-margin-top">
					<div class="uk-panel">
						<h2>{{ trans('admin.dashboard') }}</h2>
						<hr>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.views_spent_ratio_success') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="views_spent_ratio_success" type="number" value="{{ Settings::get('views_spent_ratio_success') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.messages_spent_ratio_success') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="messages_spent_ratio_success" type="number" value="{{ Settings::get('messages_spent_ratio_success') }}" onchange="updateSetting(this.id, this.value)">
						</div>
					</div>
				</div>

				<div class="uk-width-1-2 uk-margin-top">
					<div class="uk-panel">
						<h2>{{ trans('admin.payu') }}</h2>
						<hr>
						
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.currency') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="currency" type="text" value="{{ Settings::get('currency') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.test') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="payu_test" type="number" value="{{ Settings::get('payu_test') }}" onchange="updateSetting(this.id, this.value)">
						</div>
					</div>
				</div>
				
				<div class="uk-width-1-2  uk-margin-top">
					<div class="uk-panel">
						<h2>{{ trans('admin.listings') }}</h2>
						<hr>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.listing_expiring') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="listing_expiring" type="number" value="{{ Settings::get('listing_expiring') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.free_listings_limit') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="free_listings_limit" type="number" value="{{ Settings::get('free_listings_limit') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.free_image_limit') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="free_image_limit" type="number" value="{{ Settings::get('free_image_limit') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.featured_image_limit') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="featured_image_limit" type="number" value="{{ Settings::get('featured_image_limit') }}" onchange="updateSetting(this.id, this.value)">
						</div>
					</div>
				</div>

				<div class="uk-width-1-2  uk-margin-top">
					<div class="uk-panel">
						<h2>{{ trans('admin.mail') }}</h2>
						<hr>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.email_from') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="email_from" type="email" value="{{ Settings::get('email_from') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.email_from_name') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="email_from_name" type="text" value="{{ Settings::get('email_from_name') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.tips_days_from_created') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="tips_days_from_created" type="text" value="{{ Settings::get('tips_days_from_created') }}" onchange="updateSetting(this.id, this.value)">
						</div>
						<div class="uk-form-row">
					        <label class="uk-form-label" for="">{{ trans('admin.tips_min_views') }} <i class="uk-text-danger">*</i></label>
							<input class="uk-width-large-10-10 uk-form-large" id="tips_min_views" type="text" value="{{ Settings::get('tips_min_views') }}" onchange="updateSetting(this.id, this.value)">
						</div>
					</div>
				</div>

			</div>
		</form>
	
	</div>
</div>
@endsection

@section('js')
	@parent
	<script> 
		function updateSetting(key, value){
			console.log(key+' - '+value);
			$.post("{{ url('/admin/config') }}", {_token: "{{ csrf_token() }}", key: key, value: value}, function(result){
                console.log(result);
                if(result.success){
                	$('#'+key).addClass("uk-form-success", 1000, "easeInQuad");
                }
            });
		}
	</script>
@endsection
