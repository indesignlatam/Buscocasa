@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.user_data') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-cover-background uk-position-relative">
    <img class="" src="{{ asset(Image::url('/images/defaults/user_front.jpg',['full_page'])) }}" width="100%" alt="">
    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-visible-small">
        <h1 class="uk-text-contrast uk-text-bold">{{ strtoupper($user->name) }}</h1>
    </div>
    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-hidden-small">
		<h1 class="uk-text-contrast uk-text-bold" style="font-size:60px; margin-left:-30%;">{{ strtoupper($user->name) }}</h1>
    </div>
</div>

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">

		<h1>{{ trans('admin.user_data') }}</h1>

		<form id="create_form" class="uk-form uk-form-stacked" method="POST" action="{{ url('/admin/user/'.Auth::user()->id) }}" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="_method" value="PATCH">

			<div class="uk-grid">
				<div class="uk-width-large-1-3">
					<div class="uk-form-row">
				        <label class="uk-form-label">{{ trans('admin.name') }} <i class="uk-text-danger">*</i></label>
				        <div class="uk-form-controls">
							<input class="uk-width-1-1 uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ $user->name }}">
						</div>
					</div>
				</div>

				<div class="uk-width-large-1-3">
					<div class="uk-form-row">
				        <label class="uk-form-label">{{ trans('auth.username') }} <i class="uk-text-danger">*</i></label>
				        <div class="uk-form-controls">
							<input class="uk-width-1-1 uk-form-large" type="text" name="username" placeholder="{{ trans('admin.username') }}" value="{{ $user->username }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.username_tooltip') }}">
						</div>
					</div>
				</div>

				<div class="uk-width-large-1-3">
					<div class="uk-form-row">
				        <label class="uk-form-label">{{ trans('admin.email') }} <i class="uk-text-danger">*</i></label>
				        <div class="uk-form-controls">
							<input class="uk-width-1-1 uk-form-large" id="email" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ $user->email }}" disabled>
						</div>
					</div>
				</div>

				<div class="uk-width-large-1-3">
					<div class="uk-form-row uk-margin-small-top">
				        <label class="uk-form-label">{{ trans('admin.phone') }} <i class="uk-text-danger">*</i></label>
				        <div class="uk-form-controls">
							<input class="uk-width-1-1 uk-form-large" id="phone_1" type="text" name="phone_1" placeholder="{{ trans('admin.phone') }}" value="{{ $user->phone_1 }}">
						</div>
					</div>
				</div>

				<div class="uk-width-large-1-3">
					<div class="uk-form-row uk-margin-small-top">
				        <label class="uk-form-label">{{ trans('admin.phone_alt') }}</label>
				        <div class="uk-form-controls">
							<input class="uk-width-1-1 uk-form-large" id="phone_2" type="text" name="phone_2" placeholder="{{ trans('admin.alt_phone') }}" value="{{ $user->phone_2 }}">
						</div>
					</div>
				</div>

				<div class="uk-width-1-1">
					<div class="uk-form-row uk-margin-small-top">
				        <label class="uk-form-label">{{ trans('admin.description') }} <i class="uk-text-danger">*</i></label>
				        <div class="uk-form-controls">
				        	<textarea class="uk-width-1-1 uk-margin-top" rows="5" id="description" name="description" placeholder="{{ trans('admin.description') }}">{{ $user->description }}</textarea>
				        </div>
					</div>
				</div>

				<div class="uk-width-1-1">
					<div class="uk-form-row uk-margin-small-top">
				        <label class="uk-form-label">{{ trans('admin.change_image') }}</label>
				        <div class="uk-form-controls">
				        	<input type="file" name="image" id="image">
				        </div>
					</div>
				</div>
			</div>
		</form>

		<div class="uk-margin">
		    <button form="create_form" type="submit" class="uk-button uk-button-large uk-button-success uk-form-width-medium" onclick="blockUI()">{{ trans('admin.save') }}</button>
			<a class="uk-button uk-button-large" href="{{ url('/admin/listings') }}">{{ trans('admin.close') }}</a>
		</div>

	</div>
</div>
@endsection

@section('js')
	@parent

	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
@endsection