@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.transaction_result') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h1>{{ trans('admin.transaction_result') }}</h1>

	    <div class="uk-grid">
			
	    	<div class="uk-width-1-1">
			@if($signature == Request::get('signature'))
				<h3>{{ trans('admin.transaction_info') }}</h3>
				<table class="uk-table uk-table-striped uk-table-hover">
					<tr>
						<td>{{ trans('admin.transaction_state') }}</td>
						<td>
							@if(Request::get('transactionState') == 4)
								<b class="uk-text-success uk-h3">{{ trans('admin.transaction_approved') }}</b>
							@elseif(Request::get('transactionState') == 6)
								<b class="uk-text-warning">"{{ trans('admin.transaction_denied') }}</b>
							@elseif(Request::get('transactionState') == 104)
								<b class="uk-text-danger">{{ trans('admin.transaction_error') }}</b>
							@elseif(Request::get('transactionState') == 7)
								<b class="uk-text-bold">{{ trans('admin.transaction_pending') }}</b>
							@else
								{{Request::get('mensaje')}}
							@endif
						</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_description') }}</td>
						<td>{{Request::get('description')}}</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_id') }}</td>
						<td>{{Request::get('transactionId')}}</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_sale_reference') }}</td>
						<td>{{Request::get('reference_pol')}}</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_reference') }}</td>
						<td>{{Request::get('referenceCode')}}</td>
					</tr>
				@if(Request::get('pseBank')) {
					<tr>
						<td>{{ trans('admin.transaction_cus') }}</td>
						<td>{{Request::get('cus')}}</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_bank') }}</td>
						<td>{{Request::get('pseBank')}}</td>
					</tr>
				@endif
					<tr>
						<td>{{ trans('admin.transaction_total') }}</td>
						<td>{{ money_format('$%!.1i', Request::get('TX_VALUE')) }}</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_currency') }}</td>
						<td>{{Request::get('currency')}}</td>
					</tr>
					<tr>
						<td>{{ trans('admin.transaction_entity') }}</td>
						<td>{{Request::get('lapPaymentMethod')}}</td>
					</tr>
				</table>
			@else
				<h3 class="uk-text-warning">{{ trans('admin.error_validating_signature') }}</h3>
			@endif
			</div>
	    </div>
	</div>
</div>
@endsection

@section('js')
	@parent
@endsection