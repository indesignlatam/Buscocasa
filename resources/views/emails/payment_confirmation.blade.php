@extends('emails.layouts.master')
@section('header')
	@parent
@endsection

@section('content')
	<p>{{ trans('emails.payment_confirmation_title') }}</p>

	<div id="invoice" style="width:80%;" class="uk-align-center">

		<h3>Factura de venta: <i class="uk-text-primary">FACT 1029384</i></h3>

		<table style="border:#e1e1e1 solid 1px" id="items" class="uk-table uk-table-striped">
			<thead>
		        <tr>
		            <th style="text-align:left; width:70% ">Concepto</th>
		            <th style="text-align:left">Cantidad</th>
		            <th style="text-align:left">Valor</th>
		        </tr>
		    </thead>
		    
		    <tbody >
		        <tr>
		            <td>{{ $payment->description }}</td>
		            <td>1</td>
		            <td>${{ number_format($payment->tax_return_base, 2) }}</td>
		        </tr>
		    </tbody>
		</table>
		
		<table style="width:20%; margin-top:-5px; border:#e1e1e1 solid 1px" align="right" id="total" class="uk-table uk-table-striped">
		    <tbody>
		        <tr>
		            <td>Subtotal</td>
		            <td>${{ number_format($payment->tax_return_base, 2) }}</td>
		        </tr>
		        <tr>
		            <td>IVA</td>
		            <td>${{ number_format($payment->tax, 2) }}</td>
		        </tr>
		        <tr class="uk-text-bold">
		            <td>Total</td>
		            <td>${{ number_format($payment->amount, 2) }}</td>
		        </tr>
		    </tbody>
		</table>

	</div>
	
@endsection

@section('greetings')
	<div style="margin-top:140px">
        <h3>{{ trans('emails.greetings_from') }} {{ trans('emails.from_name') }}</h3>
    </div>
@endsection

@section('footer')
	
@endsection