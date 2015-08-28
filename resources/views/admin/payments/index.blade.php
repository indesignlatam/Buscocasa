@extends('layouts.master')

@section('head')
	@if(Auth::user()->is('admin'))
    	<title>{{ trans('admin.payments') }} - {{ Settings::get('site_name') }}</title>
    @else
    	<title>{{ trans('admin.my_payments') }} - {{ Settings::get('site_name') }}</title>
    @endif
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.my_payments') }}</h1>
		<hr>
	    <div class="">
	        
	    </div>

		<div class="uk-panel uk-margin-top">
			@if(count($payments) > 0)
				<div class="">
					<a class="uk-button uk-button-large" href="{{ url('/admin/pagos/?unconfirmed=true') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.unconfirmed_payments') }}"><i class="uk-icon-trash"></i></a>

					<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right uk-hidden-small">
					    <select name="order_by" onchange="this.form.submit()">
					    	<option value="">{{ trans('admin.order_by') }}</option>
					    	
					    	@if(Request::get('order_by') == 'id_desc')
					    		<option value="id_desc" selected>Recientes primero</option>
					    	@else
					    		<option value="id_desc">Recientes primero</option>
					    	@endif

					    	@if(Request::get('order_by') == 'id_asc')
					    		<option value="id_asc" selected>Antiguos primero</option>
					    	@else
					    		<option value="id_asc">Antiguos primero</option>
					    	@endif
					    </select>
					</form>
				</div>
				<div class="uk-panel uk-margin-top">
					@if(count($payments) > 0)
						<table class="uk-table uk-table-hover">
							<thead>
								<tr>
									<th style="width:15px"></th>
									<th>{{ trans('admin.reference') }}</th>
									<th>{{ trans('admin.price') }}</th>
									<th style="width:15%" class="uk-hidden-small">{{ trans('admin.payment_method') }}</th>
									<th style="width:12%" class="uk-hidden-small">{{ trans('admin.updated') }}</th>
									<th style="width:5%"></th>
								</tr>
							</thead>
							@foreach($payments as $payment)
								<tr id="payment-{{ $payment->id }}">
									@if($payment->confirmed)
										<td class="uk-text-center uk-text-success"><i class="uk-icon-check-circle" data-uk-tooltip="{pos:'top'}" title="El pago ya fue confirmado por la entidad financiera."></i></td>
									@elseif($payment->canceled)
										<td class="uk-text-center uk-text-danger"><i class="uk-icon-remove" data-uk-tooltip="{pos:'top'}" title="El pago fue cancelado."></i></td>
									@elseif($payment->state_pol == 6)
										<td class="uk-text-center uk-text-danger"><i class="uk-icon-minus-circle" data-uk-tooltip="{pos:'top'}" title="El pago fue rechazado por la entidad financiera."></i></td>
									@else
										<td class="uk-text-center uk-text-warning"><i class="uk-icon-spinner uk-icon-spin" data-uk-tooltip="{pos:'top'}" title="El pago esta pendiente de confirmación por parte de la entidad financiera."></i></td>
									@endif
									<td>{{ $payment->description }}</td>
									<td>{{ money_format('$%!.1i', $payment->amount) }}</td>
									<td class="uk-hidden-small">{{ $payment->payment_method_name }}</td>								
									<td class="uk-hidden-small">{{ $payment->updated_at->diffForHumans() }}</td>

									@if($payment->confirmed || $payment->canceled || $payment->state_pol)
										<td><button class="uk-button uk-button-small uk-button-danger" id="{{ $payment->id }}" onclick="cancel(this)" disabled><i class="uk-icon-remove"></i></button></td>
									@else
										<td><button class="uk-button uk-button-small uk-button-danger" id="{{ $payment->id }}" onclick="cancel(this)"><i class="uk-icon-remove"></i></button></td>
									@endif
								</tr>
							@endforeach
						</table>
					@endif
				</div>
			@else
				<div class="uk-text-center">
		    		<h3 class="uk-text-primary">{{ trans('admin.you_have_no_payments') }}</h3>
		    	</div>
			@endif
			
			<?php echo $payments->render(); ?>
		</div>
	</div>
</div>
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/components/tooltip.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>
	
	<script type="text/javascript">
		function cancel(sender) {
	    	UIkit.modal.confirm("{{ trans('admin.cancel_payment_sure') }}", function(){
			    $.post("{{ url('/admin/pagos') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
			    	if(result.success){
		            	$( "#payment-"+sender.id ).animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
		            	UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 10000});
			    	}else if(result.error){
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 10000});
			    	}
		        });			    
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }
	</script>
@endsection