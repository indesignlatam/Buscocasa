@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.messages') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<style type="text/css">
		.read{
			max-height: 95px;
		}
	</style>
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
	@if(Auth::user()->isAdmin())
		<h1>{{ trans('admin.messages') }}</h1>

		@if(isset($listing))
			<h3 class="uk-margin-remove"><i class="uk-text-primary">{{ $listing->title }}</i></h3>
		@endif

		<hr>
	    @if(count($appointments) > 0)
			<div class="uk-panel">
				<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right">
			        <input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-form-width-small" value="{{ Request::get('search') }}">
					<select name="take" onchange="this.form.submit()">
				    	<option value="">Cantidad de publicaciones</option>
				    	@if(Request::get('take') == 50)
				    		<option value="50" selected>Ver 50</option>
				    	@else
				    		<option value="50">Ver 50</option>
				    	@endif

				    	@if(Request::get('take') == 30)
				    		<option value="30" selected>Ver 30</option>
				    	@else
				    		<option value="30">Ver 30</option>
				    	@endif

				    	@if(Request::get('take') == 10)
				    		<option value="10" selected>Ver 10</option>
				    	@else
				    		<option value="10">Ver 10</option>
				    	@endif
				    </select>

				    <select name="order_by" onchange="this.form.submit()">
				    	<option value="">Ordenar por</option>
				    	
				    	@if(Request::get('order_by') == 'id_desc')
				    		<option value="id_desc" selected>Fecha creación</option>
				    	@else
				    		<option value="id_desc">Fecha creación</option>
				    	@endif

				    	@if(Request::get('order_by') == 'exp_desc')
				    		<option value="exp_desc" selected>Fecha expiración</option>
				    	@else
				    		<option value="exp_desc">Fecha expiración</option>
				    	@endif
				    </select>
				</form>
			</div>
		@endif

	    <div class="uk-panel uk-margin-top">
			@if(count($appointments) > 0)
				<table class="uk-table uk-table-hover uk-table-striped" id="table">
		            <tbody>
		                @foreach($appointments as $appointment)
		                    <tr id="message-{{ $appointment->id }}">
		                        <td style="max-width:100px" class="uk-hidden-small"><img src="{{ asset($appointment->listing->image_path()) }}" style="width:100px"></td>
		                        <td class="uk-visible-small">#{{ $appointment->listing->code }}</td>
		                        <td style="width:20%"><b class="uk-h4">{{ $appointment->name }}</b><br>{{ $appointment->phone }}</td>
		                        <td class="uk-hidden-small">{{ $appointment->email }}</td>
		                        <td>{{ $appointment->comments }}</td>
		                        <td style="max-width:120px" class="uk-text-right">
		                        	<div class="uk-grid uk-grid-small" data-uk-grid-margin>
		                        		<!-- Reply button -->
		                        		@if(Auth::user()->confirmed)
					    					@if(!$appointment->answered)
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
					    							<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success" onclick="answerMessage({{ $appointment->id }})" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.reply') }}"><i class="uk-icon-reply"></i></button>
					    						</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.mark_as_answered') }}"><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@else
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>
						    					</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@endif
						    			@else
						    				@if(!$appointment->answered)
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<a href="{{ url('admin/user/not_confirmed') }}" class="uk-button uk-button-success"><i class="uk-icon-reply"></i></a>
						    					</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)"><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@else
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>
						    					</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@endif
						    			@endif
						    			<!-- Reply button -->
						    			<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
		                            		<a id="delete-{{$appointment->id}}" class="uk-button uk-button-danger" onclick="deleteObject({{ $appointment->id }})" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.delete_message') }}"><i class="uk-icon-remove"></i></a>
		                            	</div>
		                        	</div>
		                        </td>
		                    </tr>
		              	@endforeach
		            </tbody>
				</table>

				<?php echo $appointments->appends(Request::all())->render(); ?>
			@endif
		</div>
	@else
		<h1>{{ trans('admin.my_messages') }}</h1>

		@if(isset($listing))
			<h3 class="uk-margin-remove"><i class="uk-text-primary">{{ $listing->title }}</i></h3>
		@endif

		<hr>
	    <div class="">
	        
	    </div>

		<div class="uk-panel uk-margin-top">
			@if(count($appointments) > 0)
				<!-- Order by -->
				<div class="uk-text-right">
					<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-hidden-small">
			        <input type="text" name="search" placeholder="{{ trans('admin.search') }}" class="uk-form-width-small" value="{{ Request::get('search') }}">
						<select name="take" onchange="this.form.submit()">
					    	<option value="">Cantidad de publicaciones</option>
					    	@if(Request::get('take') == 50)
					    		<option value="50" selected>Ver 50</option>
					    	@else
					    		<option value="50">Ver 50</option>
					    	@endif

					    	@if(Request::get('take') == 30)
					    		<option value="30" selected>Ver 30</option>
					    	@else
					    		<option value="30">Ver 30</option>
					    	@endif

					    	@if(Request::get('take') == 10)
					    		<option value="10" selected>Ver 10</option>
					    	@else
					    		<option value="10">Ver 10</option>
					    	@endif
					    </select>

					    <select name="order_by" onchange="this.form.submit()">
					    	<option value="">Ordenar por</option>
					    	
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
				<!-- Order by -->

				<table class="uk-table uk-table-hover uk-table-striped" id="table">
		            <tbody>
		                @foreach($appointments as $appointment)
		                    <tr id="message-{{ $appointment->id }}">
		                        <td style="max-width:100px" class="uk-hidden-small"><img src="{{ asset($appointment->listing->image_path()) }}" style="width:100px"></td>
		                        <td class="uk-visible-small">#{{ $appointment->listing->code }}</td>
		                        <td style="width:20%"><b class="uk-h4">{{ $appointment->name }}</b><br>{{ $appointment->phone }}</td>
		                        <td class="uk-hidden-small">{{ $appointment->email }}</td>
		                        <td>{{ $appointment->comments }}</td>
		                        <td style="max-width:120px" class="uk-text-right">
		                        	<div class="uk-grid uk-grid-small" data-uk-grid-margin>
		                        		<!-- Reply button -->
		                        		@if(Auth::user()->confirmed)
					    					@if(!$appointment->answered)
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
					    							<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success" onclick="answerMessage({{ $appointment->id }})" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.reply') }}"><i class="uk-icon-reply"></i></button>
					    						</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.mark_as_answered') }}"><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@else
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>
						    					</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@endif
						    			@else
						    				@if(!$appointment->answered)
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<a href="{{ url('admin/user/not_confirmed') }}" class="uk-button uk-button-success"><i class="uk-icon-reply"></i></a>
						    					</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)"><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@else
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success" onclick="answerMessage({{ $appointment->id }})" disabled><i class="uk-icon-reply"></i></button>
						    					</div>
					    						<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
						    						<button id="mark-read-{{$appointment->id}}" class="uk-button" onclick="mark({{ $appointment->id }}, 1)" disabled><i class="uk-icon-check-square-o"></i></button>
						    					</div>
						    				@endif
						    			@endif
						    			<!-- Reply button -->
						    			<div class="uk-width-small-1-1 uk-width-medium-1-3 uk-width-large-1-3">
		                            		<a id="delete-{{$appointment->id}}" class="uk-button uk-button-danger" onclick="deleteObject({{ $appointment->id }})" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.delete_message') }}"><i class="uk-icon-remove"></i></a>
		                            	</div>
		                        	</div>
		                        </td>
		                    </tr>
		              	@endforeach
		            </tbody>
				</table>

				<?php echo $appointments->appends(Request::all())->render(); ?>
			@else
		    	<div class="uk-text-center">
					<h2 class="uk-text-bold uk-text-muted">{{ trans('admin.you_have_no_messages') }}</h2>
					<h3>{{ trans('admin.no_messages_text') }}</h3>

					<div class="uk-grid uk-grid-collapse uk-text-center">
						<div class="uk-width-large-1-5">
							@if(isset($listing))
								<a href="{{ url($listing->pathEdit()) }}"><img src="{{ asset('/images/support/messages/consejo1.png') }}"></a>
							@else
								<img src="{{ asset('/images/support/messages/consejo1.png') }}">
							@endif
						</div>
						<div class="uk-width-large-1-5">
							@if(isset($listing))
								<a href="{{ url($listing->pathEdit().'#7') }}"><img src="{{ asset('/images/support/messages/consejo2.png') }}"></a>
							@else
								<img src="{{ asset('/images/support/messages/consejo2.png') }}">
							@endif
						</div>
						<div class="uk-width-large-1-5">
							@if(isset($listing))
								<a href="{{ url($listing->pathEdit().'#7') }}"><img src="{{ asset('/images/support/messages/consejo3.png') }}"></a>
							@else
								<img src="{{ asset('/images/support/messages/consejo3.png') }}">
							@endif
						</div>
						<div class="uk-width-large-1-5">
							@if(isset($listing))
								<a href="{{ url('/admin/destacar/'.$listing->id) }}"><img src="{{ asset('/images/support/messages/consejo4.png') }}"></a>
							@else
								<img src="{{ asset('/images/support/messages/consejo4.png') }}">
							@endif
						</div>
						<div class="uk-width-large-1-5">
							@if(isset($listing))
								<a href="{{ url('/admin/destacar/'.$listing->id) }}"><img src="{{ asset('/images/support/messages/consejo5.png') }}"></a>
							@else
								<img src="{{ asset('/images/support/messages/consejo5.png') }}">
							@endif
						</div>
					</div>
		    	</div>
			@endif
		</div>
	@endif
	</div>
</div>
@endsection

@section('js')
	@parent
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>

	<script type="text/javascript">
	    function answerMessage(objectID){
	    	UIkit.modal.prompt("{{ trans('admin.answer_message_prompt') }}", '', function(newvalue){
	    		$("#answer-"+objectID).prop('disabled', true);
			    $("#mark-read-"+objectID).prop('disabled', true);
			    $("#delete-"+objectID).prop('disabled', true);
			    // will be executed on submit.
			    $.post("{{ url('/admin/messages') }}/"+objectID+"/answer", {_token: "{{ csrf_token() }}", comments : newvalue}, function(result){
			    	if(result.success){
			    		$("#message-"+objectID).appendTo($('table'));
			    		$("#delete-"+objectID).prop('disabled', false);
			    		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 15000});
			    	}else{
			    		$("#answer-"+objectID).prop('disabled', false);
			    		$("#mark-read-"+objectID).prop('disabled', false);
			    		$("#delete-"+objectID).prop('disabled', false);
			    		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 15000});
			    	}
		        });
			}, {row:5, labels:{Ok:'{{trans("admin.send")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }

	    function mark(objectID, read){
	    	$("#answer-"+objectID).prop('disabled', true);
			$("#mark-read-"+objectID).prop('disabled', true);
			$("#delete-"+objectID).prop('disabled', true); 	
		    // will be executed on submit.
		    $.post("{{ url('/admin/messages') }}/"+objectID+"/mark", {_token: "{{ csrf_token() }}", mark : read}, function(result){
		    	if(result.success){
		        	$("#message-"+objectID).appendTo($('table'));
				    $("#answer-"+objectID).prop('disabled', result.mark);
				    $("#mark-read-"+objectID).prop('disabled', result.mark);
					$("#delete-"+objectID).prop('disabled', false);
				    UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 15000});
				}else{
					if(read){
						$("#answer-"+objectID).prop('disabled', false);
				    	$("#mark-read-"+objectID).prop('disabled', false);
				    	$("#delete-"+objectID).prop('disabled', false);
					}else{
						$("#answer-"+objectID).prop('disabled', true);
				    	$("#mark-read-"+objectID).prop('disabled', true);
				    	$("#delete-"+objectID).prop('disabled', false);
					}
					UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 15000});
				}
	        });
	    }

	    function deleteObject(objectID) {
	    	UIkit.modal.confirm("{{ trans('admin.sure') }}", function(){
			    // will be executed on confirm.
			    $("#message-"+objectID).fadeOut(500);

		        $.post("{{ url('/admin/messages') }}/" + objectID, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
		        	if(result.success){
		        		$("#message-"+objectID).remove();
		        		UIkit.notify('<i class="uk-icon-check-circle"></i> '+result.success, {pos:'top-right', status:'success', timeout: 15000});
		        	}else if(result.error){
		        		$("#message-"+objectID).fadeIn(500);
		        		UIkit.notify('<i class="uk-icon-remove"></i> '+result.error, {pos:'top-right', status:'danger', timeout: 15000});
		        	}
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}, center: true});
	    }

	    // function toggle(source){
	    //     checkboxes = document.getElementsByName('checkedLine');
	    //     for(var i=0, n=checkboxes.length;i<n;i++) {
	    //         checkboxes[i].checked = source.checked;
	    //     }
	    // }

	    // function deleteObjects() {
     //        var checkedValues = $('input[name="checkedLine"]:checked').map(function() {
     //            return this.value;
     //        }).get();
     //        $.post("{{ url('/admin/appointments/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
     //            location.reload();
     //        });
     //    }
	</script>
@endsection