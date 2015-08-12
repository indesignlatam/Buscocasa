<!-- This is the modal -->
<div id="new_appointment_modal" class="uk-modal" data-focus-on="input:first">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="uk-modal-header">
            {{ trans('frontend.contact_vendor') }}
        </div>

        <div class="uk-flex uk-margin-bottom">
            <img src="{{ asset(Image::url($listing->image_path(),['mini_front'])) }}" style="width:200px; height:150px" class="uk-width-2-5 uk-margin-right">
            <div class="uk-width-3-5">
                <h3>{{ $listing->title }}</h3>
            </div>
        </div>
        

        <form id="send_message" class="uk-form uk-form-horizontal" method="POST" action="{{ url('/appointments') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="listing_id" value="{{ $listing->id }}">

            <div class="uk-flex uk-flex-center uk-flex-space-between">
                <input class="uk-width-large-5-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="Nombre" value="{{ old('name') }}">

                <div class="uk-hidden">
                    <input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
                </div>
                            
                <input class="uk-width-large-4-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="Telefono" value="{{ old('phone') }}">
            </div>
            
            <input class="uk-width-large-1-1 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="Correo" value="{{ old('email') }}">

            <textarea class="uk-width-large-10-10 uk-form-large" name="comments" placeholder="Comentarios" rows="5">@if(old('comments')){{ old('comments') }}@else{{ trans('frontend.contact_default_text') }}@endif</textarea>

            @if(!Auth::check())
                <!-- ReCaptcha -->
                <div class="uk-form-row uk-width-large-10-10 uk-margin-top uk-align-center">
                    <div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
                    <p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
                </div>
                <!-- ReCaptcha -->
            @endif
		</form>

		<div class="uk-modal-footer">
			<button form="send_message" type="submit" class="uk-button uk-button-primary">{{ trans('frontend.contact_button') }}</button>
	        <a href="" class="uk-button uk-modal-close">{{ trans('admin.cancel') }}</a>
	    </div>
	    
    </div>
</div>