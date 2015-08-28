<div id="send_mail" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
        <div class="uk-modal-header uk-text-bold">
        	Enviar publicación por email
        </div>

        <form class="uk-form uk-form-stacked">
        	<input type="hidden" name="listingId" id="listingId">

        	<div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.emails') }} <i class="uk-text-danger">*</i></label>
        		<input type="text" name="emails" id="emails" placeholder="{{ trans('admin.emails') }}" class="uk-width-1-1 uk-form-large">
		    </div>

		    <div class="uk-form-row">
		        <label class="uk-form-label" for="">{{ trans('admin.message') }} <i class="uk-text-danger">*</i></label>
        		<textarea class="uk-width-1-1" id="message" rows="4" placeholder="{{ trans('admin.message') }}"></textarea>
		    </div>
        </form>

        En el correo se adjuntaran las imagenes y la información del inmueble.

        <div class="uk-modal-footer">
        	<button class="uk-button uk-button-success" onclick="sendMail()" id="sendMail">Enviar</button>
        	<button class="uk-button uk-modal-close">Cancelar</button>
        </div>
    </div>
</div>