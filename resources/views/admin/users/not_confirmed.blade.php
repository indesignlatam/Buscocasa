@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.user_not_confirmed') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')
<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.user_not_confirmed_title') }}</h1>

		<p>{{ trans('admin.user_not_confirmed_text') }}</p>

		<p class="uk-text-bold">{{ trans('admin.confirmation_sent_spam') }}</p>

		<div class="uk-panel uk-panel-box uk-panel-box-secondary">
			<div class="uk-grid uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}">
				<div class="uk-width-1-3">
					<div class="uk-panel">
						<h2 class="uk-text-contrast"> - </h2>
						<ul class="uk-list uk-list-line uk-margin-large-top">
							<img src="{{ asset('images/support/mail/not_confirmed.png') }}" style="visibility:hidden; width:50%; margin-top:-50px" class="uk-align-center">
							<li class="uk-h3">{{ trans('admin.free_listings') }}</li>
							<li class="uk-h3">{{ trans('admin.max_listing_images') }}</li>
							<li class="uk-h3">{{ trans('admin.dashboard') }}</li>
							<li class="uk-h3">{{ trans('admin.notifications') }}</li>
							<li class="uk-h3">{{ trans('admin.answer_messages') }}</li>
							<li class="uk-h3">{{ trans('admin.print_banner') }}</li>
						</ul>
					</div>
				</div>
					
				<div class="uk-width-1-3">
					<div class="uk-panel">
						<h2 class="uk-text-center">{{ trans('admin.unconfirmed') }}</h2>
						<ul class="uk-list uk-list-line uk-margin-large-top">
							<img src="{{ asset('images/support/mail/not_confirmed.png') }}" style="width:50%; margin-top:-50px" class="uk-align-center">
							<li class="uk-h3 uk-text-center">1</li>
							<li class="uk-h3 uk-text-center">2</li>
							<li class="uk-h3 uk-text-center uk-text-danger"><i class="uk-icon-remove"></i></li>
							<li class="uk-h3 uk-text-center uk-text-danger"><i class="uk-icon-remove"></i></li>
							<li class="uk-h3 uk-text-center uk-text-danger"><i class="uk-icon-remove"></i></li>
							<li class="uk-h3 uk-text-center uk-text-danger"><i class="uk-icon-remove"></i></li>
						</ul>
						<a href="{{ url('/admin/user/send_confirmation_email') }}" class="uk-button uk-button-large uk-button-success uk-width-1-1">{{ trans('admin.send_verification_email') }}</a>
					</div>
				</div>

				<div class="uk-width-1-3">
					<div class="uk-panel">
						<h2 class="uk-text-center">{{ trans('admin.confirmed') }}</h2>
						<ul class="uk-list uk-list-line uk-margin-large-top">
							<img src="{{ asset('images/support/mail/confirmed.png') }}" style="width:50%; margin-top:-50px" class="uk-align-center">
							<li class="uk-h3 uk-text-center">10</li>
							<li class="uk-h3 uk-text-center">10</li>
							<li class="uk-h3 uk-text-center"><i class="uk-icon-check"></i></li>
							<li class="uk-h3 uk-text-center"><i class="uk-icon-check"></i></li>
							<li class="uk-h3 uk-text-center"><i class="uk-icon-check"></i></li>
							<li class="uk-h3 uk-text-center"><i class="uk-icon-check"></i></li>
						</ul>
						<a href="{{ url('/admin/user/send_confirmation_email') }}" class="uk-button uk-button-large uk-button-success uk-width-1-1">{{ trans('admin.send_verification_email') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
	@parent
@endsection
