@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.user_data') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-cover-background uk-position-relative">
	@if(Auth::user()->image_path)
    	<img class="" src="{{ asset(Auth::user()->image_path) }}" width="100%" alt="Auth::user()->name">
	@else
    	<img class="" src="{{ asset(Image::url('/images/defaults/user_front.jpg',['full_page'])) }}" width="100%" alt="Auth::user()->name">
	@endif

    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-visible-small">
        <h1 class="uk-text-contrast uk-text-bold">{{ strtoupper($user->name) }}</h1>
    </div>
    <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle uk-hidden-small">
		<h1 class="uk-text-contrast uk-text-bold" style="font-size:60px; margin-left:-30%;">{{ strtoupper($user->name) }}</h1>

		<div style="position:absolute; right:0px; top:0px; width:5%" class="uk-margin-top uk-margin-right uk-text-center">
			<a href="#upload_modal" data-uk-modal="{center:true}">
	    		<i class="uk-icon-image uk-icon-large uk-text-contrast"></i>
	    	</a>
	    </div>
    </div>
</div>

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">

		<h1>{{ trans('admin.user_data') }}</h1>

		<form id="create_form" class="uk-form uk-form-stacked" method="POST" action="{{ url('/admin/user/'.Auth::user()->id) }}" enctype="multipart/form-data">
			<div class="uk-grid">
				
				<div class="uk-width-3-4">
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

					</div>
				</div>

				<div class="uk-width-1-4">
					<div class="uk-panel uk-panel-box uk-panel-box-primary">
						<h3 class="uk-panel-title">{{ trans('admin.configuration') }}</h3>

						<div class="uk-width-1-1">
							<div class="uk-form-row uk-margin-small-top">
						        <label class="uk-form-label">{{ trans('admin.email_notifications') }}</label>
						        <div class="uk-form-controls">
						        	@if(Auth::user()->email_notifications)
						        		Activado <input type="radio" name="email_notifications" value="1" checked>
										Desactivado <input type="radio" name="email_notifications" value="0">
						        	@else
						        		Activado <input type="radio" name="email_notifications" value="1">
										Desactivado <input type="radio" name="email_notifications" value="0" checked>
						        	@endif
								</div>
							</div>
						</div>

						<div class="uk-width-1-1">
							<div class="uk-form-row uk-margin-small-top">
						        <label class="uk-form-label">{{ trans('admin.privacy_name') }}</label>
						        <div class="uk-form-controls">
						        	@if(Auth::user()->privacy_name)
						        		Activado <input type="radio" name="privacy_name" value="1" checked>
										Desactivado <input type="radio" name="privacy_name" value="0">
						        	@else
						        		Activado <input type="radio" name="privacy_name" value="1">
										Desactivado <input type="radio" name="privacy_name" value="0" checked>
						        	@endif
									
								</div>
							</div>
						</div>

						<div class="uk-width-1-1">
							<div class="uk-form-row uk-margin-small-top">
						        <label class="uk-form-label">{{ trans('admin.privacy_phone') }}</label>
						        <div class="uk-form-controls">
						        	@if(Auth::user()->privacy_phone)
						        		Activado <input type="radio" name="privacy_phone" value="1" checked>
										Desactivado <input type="radio" name="privacy_phone" value="0">
						        	@else
						        		Activado <input type="radio" name="privacy_phone" value="1">
										Desactivado <input type="radio" name="privacy_phone" value="0" checked>
						        	@endif
								</div>
							</div>
						</div>
					</div>
					<div class="uk-margin-top">
						<a href="#password_modal" class="uk-button uk-width-1-1" data-uk-modal="{center:true}">{{ trans('admin.change_password') }}</a>
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

<!-- This is the modal -->
<div id="upload_modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
        <div class="uk-modal-header uk-text-bold">
        	{{ trans('admin.change_profile_image') }}
        </div>

        <div class="uk-grid uk-grid-collapse">
        	<div class="uk-width-1-1">
        		<div id="upload_drop_modal" class="uk-placeholder uk-placeholder-large uk-text-center uk-margin-top">
				    <i class="uk-icon-large uk-icon-cloud-upload"></i> {{ trans('admin.drag_user_image_or') }} <a class="uk-form-file">{{ trans('admin.select_an_image') }}<input id="upload_select_modal" type="file" multiple></a>
				</div>

				<div id="progressbar_modal" class="uk-progress uk-hidden">
				    <div class="uk-progress-bar" style="width: 0%;"></div>
				</div>
        	</div>
        </div>

        <div class="uk-margin-large-top uk-grid" id="images_div_modal">
					
		</div>

	    <div class="uk-modal-footer">
	    	<a href="" class="uk-button uk-button-success uk-modal-close">{{ trans('admin.save') }}</a>
	    </div>
    </div>
</div>

<!-- This is the modal -->
<div id="password_modal" class="uk-modal">
    <div class="uk-modal-dialog" style="max-width:400px">
        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
        <div class="uk-modal-header uk-text-bold">
        	{{ trans('admin.change_password') }}
        </div>
        <hr>
        <form id="password" class="uk-form uk-form-stacked" action="{{ url('admin/user/'.Auth::user()->id.'/password') }}" method="POST">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<div class="uk-width-large-1-2 uk-align-center uk-text-center">
				<div class="uk-form-row uk-margin-small-top">
			        <label class="uk-form-label">{{ trans('admin.current_password') }}</label>
			        <div class="uk-form-controls">
			        	<input class="uk-form-large" type="password" name="current_password">
					</div>
				</div>
			</div>
        	<div class="uk-width-large-1-2 uk-align-center uk-text-center">
				<div class="uk-form-row uk-margin-small-top">
			        <label class="uk-form-label">{{ trans('admin.new_password') }}</label>
			        <div class="uk-form-controls">
			        	<input class="uk-form-large" type="password" name="password">
					</div>
				</div>
			</div>
        	<div class="uk-width-large-1-2 uk-align-center uk-text-center">
				<div class="uk-form-row uk-margin-small-top">
			        <label class="uk-form-label">{{ trans('admin.confirm_new_password') }}</label>
			        <div class="uk-form-controls">
			        	<input class="uk-form-large" type="password" name="password_confirmation">
					</div>
				</div>
			</div>
        </form>

	    <div class="uk-modal-footer">
	    	<button class="uk-button uk-button-success" type="submit" form="password">{{ trans('admin.change_password') }}</button>
	    	<a href="" class="uk-button uk-modal-close">{{ trans('admin.cancel') }}</a>
	    </div>
    </div>
</div>
@endsection

@section('js')
	@parent

	<!-- CSS -->
	<link href="{{ asset('/css/components/form-file.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/upload.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/placeholder.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/progress.almost-flat.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<!-- CSS -->

	<!-- JS -->
    <script src="{{ asset('/js/components/sortable.min.js') }}"></script>
	<script src="{{ asset('/js/components/upload.min.js') }}"></script>
    <script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<script src="{{ asset('/js/components/sticky.min.js') }}"></script>
	<script src="{{ asset('/js/accounting.min.js') }}"></script>
	<script src="{{ asset('/js/select2.min.js') }}"></script>
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	<!-- JS -->

	<script type="text/javascript">
		// Modal uploader
        $(function(){
	        var progressbar = $("#progressbar_modal"),
	            bar         = progressbar.find('.uk-progress-bar'),
	            settings    = {
		            action: '{{ url("/admin/user/". Auth::user()->id ."/images") }}', // upload url
		            single: 'true',
		            param: 'image',
		            type: 'json',
		            params: {_token:"{{ csrf_token() }}"},
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
		            	if(response.image_path && response.success){
		            		UIkit.notify('<i class="uk-icon-check-circle"></i> '+response.success, {pos:'top-right', status:'success', timeout: 5000});

		            		$("#images_div_modal").html('');
		            		$("#images_div_modal").prepend('<div class="uk-width-1-1"><img src="{{asset("")}}'+response.image_path+'"></div>');
		            	}else if(response.error){
		            		if(response.error instanceof Array){
		            			response.error.forEach(function(entry) {
		            				UIkit.notify('<i class="uk-icon-remove"></i> '+entry['image'], {pos:'top-right', status:'danger', timeout: 5000});
								});
		            		}else{
		            			UIkit.notify('<i class="uk-icon-remove"></i> '+response.error, {pos:'top-right', status:'danger', timeout: 5000});
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

	        var select = UIkit.uploadSelect($("#upload_select_modal"), settings),
	            drop   = UIkit.uploadDrop($("#upload_drop_modal"), settings);
	    });
		// Modal uploader
	</script>
@endsection

@section('alerts')
	@parent
@endsection