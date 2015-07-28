@extends('layouts.master')

@section('head')
    <title>Create banner - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<link href="{{ asset('/css/floatinglabel.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h3 class="uk-panel-title">Print a banner to stick in to your windows</h3>
	    <hr>
	    <div class="">
	        <!-- This is a button toggling the modal -->
	        <button form="create_form" type="submit" class="uk-button">Save</button>
	        <a class="uk-button uk-button-danger" href="{{ url('/admin/listings') }}">Cancel</a>
	    </div>


	    <div class="uk-panel uk-panel-box uk-margin-top">
	    	<form id="create_form" class="uk-form">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="uk-grid">
					<div class="uk-width-1-2 uk-text-center">
	                    <span class="uk-form-label">En Venta o Arriendo</span>
	                    <div class="uk-form-controls">
	                        <input type="radio" id="form-s-r" name="radio"> <label for="form-s-r">Venta</label>
	                        <input type="radio" id="form-s-r1" name="radio"> <label for="form-s-r1">Arriendo</label>
	                    </div>
	                </div>

	                <div class="uk-width-1-2 uk-text-center">
	                    <span class="uk-form-label">Tamaño impresion</span>
	                    <div class="uk-form-controls">
	                        <input type="radio" id="form-s-r" name="radio"> <label for="form-s-r">Carta</label>
	                        <input type="radio" id="form-s-r1" name="radio"> <label for="form-s-r1">Cuarto de pliego</label>
	                        <input type="radio" id="form-s-r1" name="radio"> <label for="form-s-r1">Medio pliego</label>
	                        <input type="radio" id="form-s-r1" name="radio"> <label for="form-s-r1">Pliego</label>
	                    </div>
	                </div>
				</div>

				<input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="title" placeholder="Title" value="{{ old('title') }}">
			</form>

			<h3 class="uk-text-center">Banner preview</h3>
			<div id="image" class="uk-panel uk-panel-box uk-panel-box-secondary uk-align-center" style="height:300px; width:500px">
				
			</div>
	    </div>
		

	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="{{ asset('/js/floatinglabel.min.js') }}"></script>
	<script src="{{ asset('/js/jspdf.min.js') }}"></script>
	
	<script type="text/javascript">
		$('#create_form').floatinglabel({ ignoreId: ['category', 'listing_type', 'listing_status', 'city'] });


		var doc = new jsPDF();
		doc.setFontSize(40);
		doc.text(35, 25, "Paranyan loves jsPDF");
	</script>
@endsection