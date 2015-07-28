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
		<h1>{{ trans('admin.messages') }}</h1>

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
					<form action="{{url(Request::path())}}" method="GET" class="uk-form">
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
				<ul class="uk-list">
					@foreach($appointments as $appointment)
					@if($appointment->read || $appointment->answered)
						<li class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom read" id="message-{{ $appointment->id }}">
							<div class="uk-grid">
		                		<div class="uk-width-2-10">
		                			<img src="{{ asset(Image::url($appointment->listing->image_path(),['mini_front'])) }}" id="image-{{ $appointment->id }}" class="read">
		                		</div>
		                		<div class="uk-width-6-10">
		                			<h3 class="uk-margin-small-bottom">{{ $appointment->name }}</h3>
					@else
						<li class="uk-panel uk-panel-box uk-margin-bottom" id="message-{{ $appointment->id }}">
							<div class="uk-grid">
		                		<div class="uk-width-2-10">
		                			<img src="{{ asset(Image::url($appointment->listing->image_path(),['mini_front'])) }}" id="image-{{ $appointment->id }}">
		                		</div>
		                		<div class="uk-width-6-10">
		                			<h3 class="uk-margin-small-bottom uk-text-bold">{{ $appointment->name }}</h3>
					@endif							
		                		
		                			<div class="uk-grid">
		                				<div class="uk-width-5-10">
		                					<ul class="uk-list" style="margin-left:-20px">
		                						<li>{{ trans('admin.email') }}: <b>{{ $appointment->email }}</b></li>
		                						<li>{{ trans('admin.phone') }}: <b>{{ $appointment->phone }}</b></li>
		                						<li><b>{{ ucfirst(Carbon::createFromFormat('Y-m-d H:i:s', $appointment->created_at)->diffForHumans()) }}</b></li>
		                					</ul>
		                				</div>
		                				<div class="uk-width-5-10">
		                					{{ $appointment->comments }}
		                				</div>
		                			</div>
		                		</div>

		                		<!-- Buttons start -->
		                		<div class="uk-width-2-10" id="buttons-{{ $appointment->id }}">
		                			@if(Auth::user()->confirmed)
		                				@if(!$appointment->answered)
		                					<button id="answer-{{$appointment->id}}" class="uk-button uk-button-success uk-width-1-1 uk-margin-small-bottom" onclick="answerMessage({{ $appointment->id }})">{{ trans('admin.answer') }}</button>
		                				@endif
		                			@else
		                				<a href="{{ url('admin/user/not_confirmed') }}" class="uk-button uk-button-success uk-width-1-1 uk-margin-small-bottom">{{ trans('admin.answer') }}</a>
		                			@endif

		                			@if(!$appointment->read)
		                				<button id="mark-read-{{$appointment->id}}" class="uk-button uk-button uk-width-1-1 uk-margin-small-bottom" onclick="mark({{ $appointment->id }}, 1)">{{ trans('admin.mark_as_read') }}</button>
		                			@else
		                				<button id="mark-unread-{{$appointment->id}}" class="uk-button uk-button uk-width-1-1 uk-margin-small-bottom" onclick="mark({{ $appointment->id }}, 0)">{{ trans('admin.mark_as_unread') }}</button>
		                			@endif
	                				
		                            <a class="uk-button uk-button-danger uk-width-1-1" onclick="deleteObject({{ $appointment->id }})">{{ trans('admin.delete') }}</a>
		                        </div>
							</div>
						</li>
					@endforeach
				</ul>
			@else
		    	<div class="uk-text-center uk-margin-top">
					<h2 style="color:#95979a" class="uk-text-bold">{{ trans('admin.you_have_no_messages') }}</h2>
					<h3>Sigue estos pasos para mejorar la visibilidad de tu publicación</h3>

					<div class="uk-grid uk-grid-collapse uk-text-center">
						<div class="uk-width-1-5">
							<img src="{{ asset('/images/support/messages/consejo1.png') }}">
						</div>
						<div class="uk-width-1-5">
							<img src="{{ asset('/images/support/messages/consejo2.png') }}">
						</div>
						<div class="uk-width-1-5">
							<img src="{{ asset('/images/support/messages/consejo3.png') }}">
						</div>
						<div class="uk-width-1-5">
							<img src="{{ asset('/images/support/messages/consejo4.png') }}">
						</div>
						<div class="uk-width-1-5">
							<img src="{{ asset('/images/support/messages/consejo5.png') }}">
						</div>
					</div>

					
		    		
		    		<div class="uk-margin-large-top">
		    			<a href="{{ url('/admin/messages?deleted=true') }}">{{ trans('admin.show_deleted_messages') }}</a>
		    		</div>
		    	</div>
			@endif
			

			{{-- <table class="uk-table uk-table-hover uk-table-striped">
				<thead>
	                <tr>
	                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
	                    <th style="width:15px">id</th>
	                    <th>Name</th>
	                    <th style="width:250px">email</th>
	                    <th style="width:100px">Phone</th>
	                    <th style="width:100px">Listing</th>
	                    <th style="width:120px">Actions</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($appointments as $appointment)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$appointment->id}}"/></td>
	                        <td>{{ $appointment->id }}</td>
	                        <td>{{ $appointment->name }}</td>
	                        <td>{{ $appointment->email }}</td>
	                        <td>{{ $appointment->phone }}</td>
	                        <td><img src="{{ $appointment->listing->image_path() }}" style="width:100px"></td>
	                        <td>
	                            <!-- This is the container enabling the JavaScript -->
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <!-- This is the button toggling the dropdown -->
	                                <button class="uk-button">Actions <i class="uk-icon-caret-down"></i></button>
	                                <!-- This is the dropdown -->
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="bikes/types/{{ $appointment->id }}">Edit</a></li>
	                                        <li><a href="bikes/types/clone/{{ $appointment->id }}">Clone</a></li>
	                                        <li><a id="{{ $appointment->id }}" onclick="deleteObject(this)">Delete</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table> --}}
			<?php echo $appointments->render(); ?>
		</div>
	</div>
</div>
@endsection

@section('js')
	@parent
	<script type="text/javascript">
	    function toggle(source){
	        checkboxes = document.getElementsByName('checkedLine');
	        for(var i=0, n=checkboxes.length;i<n;i++) {
	            checkboxes[i].checked = source.checked;
	        }
	    }

	    function answerMessage(objectID){
	    	UIkit.modal.prompt("{{ trans('admin.answer_message_prompt') }}", '', function(newvalue){
	    		$("#message-"+objectID).addClass('uk-panel-box-secondary read');
	    		$("#image-"+objectID).addClass('read');
	    		$("#answer-"+objectID).fadeOut(500, function(){ $(this).remove();});
	    		$("#mark-read-"+objectID).fadeOut(500, function(){ $(this).remove();});
	    		$("#buttons-"+objectID).prepend('<button id="mark-unread-'+objectID+'" class="uk-button uk-button uk-width-1-1 uk-margin-small-bottom" onclick="mark('+objectID+', 1)">{{ trans("admin.mark_as_unread") }}</button>');
			    // will be executed on submit.
			    $.post("{{ url('/admin/messages') }}/"+objectID+"/answer", {_token: "{{ csrf_token() }}", comments : newvalue}, function(result){
		        	console.log(result);
		        });
			}, {row:5, labels:{Ok:'{{trans("admin.send")}}', Cancel:'{{trans("admin.cancel")}}'}});
	    }

	    function mark(objectID, read){
	    	if(read){
	    		console.log('read: '+objectID);	    		
	    		$("#mark-read-"+objectID).fadeOut(500, function(){
	    			console.log(objectID);
	    			$(this).remove();
	    			$("#message-"+objectID).addClass('uk-panel-box-secondary read');
    				$("#image-"+objectID).addClass('read');
	    			$("#answer-"+objectID).after('<button id="mark-unread-'+objectID+'" class="uk-button uk-button uk-width-1-1 uk-margin-small-bottom" onclick="mark('+objectID+', 0)">{{ trans("admin.mark_as_unread") }}</button>');
	    		});
	    	}else{
	    		console.log('unread: '+objectID);	    		
	    		$("#mark-unread-"+objectID).fadeOut(500, function(){ 
	    			console.log(objectID);
	    			$(this).remove();
	    			$("#message-"+objectID).removeClass('uk-panel-box-secondary read');
    				$("#image-"+objectID).removeClass('read');
	    			$("#answer-"+objectID).after('<button id="mark-read-'+objectID+'" class="uk-button uk-button uk-width-1-1 uk-margin-small-bottom" onclick="mark('+objectID+', 1)">{{ trans("admin.mark_as_read") }}</button>');
	    		});
	    	}
	    	
		    // will be executed on submit.
		    $.post("{{ url('/admin/messages') }}/"+objectID+"/mark", {_token: "{{ csrf_token() }}", mark : read}, function(result){
	        	console.log(result);
	        });
	    }



	    function deleteObject(objectID) {
	    	UIkit.modal.confirm("{{ trans('admin.sure') }}", function(){
			    // will be executed on confirm.
			    $("#message-"+objectID).fadeOut(500, function(){ $(this).remove();});

		        $.post("{{ url('/admin/messages') }}/" + objectID, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
		        	console.log(result);
		            //location.reload();
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}});
	    	
	    }

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