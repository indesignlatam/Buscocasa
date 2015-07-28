@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.confirm_payment') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.confirm_payment') }}</h1>

	    <hr>

	    <div class="uk-grid">
	    	<div class="uk-width-7-10">
			   	<div>
					<h3>{{ trans('admin.confirm_payment_title') }}</h3>
					<p>{{ trans('admin.confirm_payment_text') }}</p>
				</div>

    			<a href="#" style="text-decoration:none">
			    	<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin-remove" id="listing">
			    		<img src="{{asset($payment->featuredType->image_path)}}" style="position:absolute; top:0; left:0; max-width:150px">

			    		<img src="{{ asset(Image::url($payment->listing->image_path(),['mini_image_2x'])) }}" style="width:350px; height:200px; float:left" class="uk-margin-right">
			    		<h4 class="uk-margin-top-remove">{{ $payment->listing->title }}</h4>
		    			<h4 style="margin-top:-10px" class="uk-text-primary">${{ money_format('%!.0i', $payment->listing->price) }}</h4>
		    			<ul style="list-style-type: none;margin-top:-5px" class="uk-text-muted uk-text-small">

		    				@if($payment->listing->rooms)
		    				<li><i class="uk-icon-check"></i> {{ $payment->listing->rooms }} {{ trans('admin.rooms') }}</li>
		    				@endif

		    				@if($payment->listing->bathrooms)
		    				<li><i class="uk-icon-check"></i> {{ $payment->listing->bathrooms }} {{ trans('admin.bathrooms') }}</li>
		    				@endif

		    				@if($payment->listing->garages)
		    				<li><i class="uk-icon-check"></i> {{ $payment->listing->garages }} {{ trans('admin.garages') }}</li>
		    				@endif

		    				@if($payment->listing->stratum)
		    				<li><i class="uk-icon-check"></i> {{ trans('admin.stratum') }} {{ $payment->listing->stratum }}</li>
		    				@endif

		    				@if($payment->listing->area)
		    				<li><i class="uk-icon-check"></i> {{ number_format($payment->listing->area, 0, ',', '.') }} mt2</li>
		    				@endif

		    				@if($payment->listing->lot_area)
		    				<li id="lot_area"><i class="uk-icon-check"></i> {{ number_format($payment->listing->lot_area, 0, ',', '.') }} {{ trans('frontend.lot_area') }}</li>
		    				@endif

		    				@if((int)$payment->listing->administration != 0)
		    				<li><i class="uk-icon-check"></i> {{ money_format('$%!.0i', $payment->listing->administration) }} {{ trans('admin.administration_fees') }}</li>
		    				@endif

		    			</ul>
			    	</div>
			    </a>
	    	</div>

	    	<div class="uk-width-3-10">
	    		<div class="uk-panel uk-panel-box">
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
		    					<td id="name">{{ $payment->description }}</td>
		    					<td id="price">{{ money_format('$%!.1i', $payment->tax_return_base) }}</td>
		    				</tr>
		    				<tr><td></td><td></td></tr>
		    			</tbody>

		    			<tfoot>
					        <tr>
					            <td>{{ trans('admin.tax') }}</td>
					            <td id="iva">{{ money_format('$%!.1i', $payment->tax) }}</td>
					        </tr>
					        <tr class="uk-text-bold">
					            <td>{{ trans('admin.total') }}</td>
					            <td id="total">{{ money_format('$%!.1i', $payment->amount) }}</td>
					        </tr>
					    </tfoot>
		    		</table>

		    		@if(Settings::get('payu_test', 1))
		    			<form id="payu" class="uk-form uk-form-stacked uk-margin-top" method="POST" action="{{ config('payu.test_url') }}">
							<input name="merchantId" 	type="hidden"  value="{{ config('payu.test_merchant_id') }}"/>
							<input name="accountId" 	type="hidden"  value="{{ config('payu.test_account_id') }}"/>
						    <input name="description"   type="hidden"  value="{{ $payment->description }}"  />
						    <input name="referenceCode" type="hidden"  value="{{ $payment->reference_code }}" />
						    <input name="amount"        type="hidden"  value="{{ number_format($payment->amount, 2, '.', '') }}"   />
						    <input name="tax"           type="hidden"  value="{{ number_format($payment->tax, 2, '.', '') }}"  />
						    <input name="taxReturnBase" type="hidden"  value="{{ number_format($payment->tax_return_base, 2, '.', '') }}" />
						    <input name="currency"      type="hidden"  value="{{ $payment->currency }}" />
						    <input name="signature"     type="hidden"  value="{{ $payment->signature }}"  />
						    <input name="test"          type="hidden"  value="1" />
						    <input name="buyerEmail"    type="hidden"  value="{{ $payment->user->email }}" />
						    <input name="lng" 			type="hidden"  value="es"/>
							<input name="sourceUrl"  	type="hidden"  value="{{ Request::url() }}"/>
						    <input name="responseUrl"   type="hidden"  value="{{ config('payu.response_url') }}" />
						    <input name="confirmationUrl" type="hidden"  value="{{ config('payu.confirmation_url') }}" />

							<div class="uk-margin-top uk-text-center">
						        <button form="payu" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-1" onclick="blockUI()">{{ trans('admin.pay') }}</button>
								<a class="uk-button uk-margin-top uk-width-1-1" onclick="cancelPayment()">{{ trans('admin.cancel') }}</a>
						    </div>
						</form>
					@else	
						<form id="payu" class="uk-form uk-form-stacked uk-margin-top" method="POST" action="{{ config('payu.url') }}">
							<input name="merchantId" 	type="hidden"  value="{{ config('payu.merchant_id') }}"/>
							<input name="accountId" 	type="hidden"  value="{{ config('payu.account_id') }}"/>
						    <input name="description"   type="hidden"  value="{{ $payment->description }}"  />
						    <input name="referenceCode" type="hidden"  value="{{ $payment->reference_code }}" />
						    <input name="amount"        type="hidden"  value="{{ number_format($payment->amount, 2, '.', '') }}"   />
						    <input name="tax"           type="hidden"  value="{{ number_format($payment->tax, 2, '.', '') }}"  />
						    <input name="taxReturnBase" type="hidden"  value="{{ number_format($payment->tax_return_base, 2, '.', '') }}" />
						    <input name="currency"      type="hidden"  value="{{ $payment->currency }}" />
						    <input name="signature"     type="hidden"  value="{{ $payment->signature }}"  />
						    <input name="test"          type="hidden"  value="0" />
						    <input name="buyerEmail"    type="hidden"  value="{{ $payment->user->email }}" />
						    <input name="lng" 			type="hidden"  value="es"/>
							<input name="sourceUrl"  	type="hidden"  value="{{ Request::url() }}"/>
						    <input name="responseUrl"   type="hidden"  value="{{ config('payu.response_url') }}" />
						    <input name="confirmationUrl" type="hidden"  value="{{ config('payu.confirmation_url') }}" />

							<div class="uk-margin-top uk-text-center">
						        <button form="payu" type="submit" class="uk-button uk-button-large uk-button-success uk-width-1-1" onclick="blockUI()">{{ trans('admin.pay') }}</button>
								<a class="uk-button uk-margin-top uk-width-1-1" onclick="cancelPayment()">{{ trans('admin.cancel') }}</a>
						    </div>
						</form>
					@endif

					
	    		</div>
	    	</div>
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="{{ asset('/js/accounting.min.js') }}"></script>

	<script type="text/javascript">
		$(function() {
			// window.onbeforeunload = function() {
			// 	return "Estas seguro que deseas abandonar el pago?";
			// }
		});

		function blockUI(){
	        var modal = UIkit.modal.blockUI('<h3 class="uk-text-center">{{ trans("admin.wait_while_redirect_payment") }}</h3><div class="uk-text-center uk-text-primary"><i class="uk-icon-large uk-icon-spinner uk-icon-spin"</i></div>'); // modal.hide() to unblock
	    }

	    function cancelPayment() {
	    	UIkit.modal.confirm("{{ trans('admin.cancel_payment_sure') }}", function(){
			    // will be executed on confirm.
			    $.post("{{ url('/admin/pagos/'.$payment->id) }}", {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(response){
	                console.log(response);
	                window.location.href = "{{url('/admin/pagos')}}";
	            });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}});
            
        }
	</script>
@endsection