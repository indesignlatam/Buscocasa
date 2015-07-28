@extends('layouts.master')

@section('head')
    <title>Listing Types - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<link href="{{ asset('/css/floatinglabel.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h3 class="uk-panel-title">Listing Types</h3>
	    <hr>
	    <div class="">
	        <!-- This is a button toggling the modal -->
	        <button class="uk-button" data-uk-modal="{target:'#new_object_modal'}">New</button>
	        <button class="uk-button uk-button-danger" onclick="deleteObjects()"><i class="uk-icon-trash"></i></button>
	    </div>

		<div class="uk-panel uk-panel-box uk-margin-top">
			<table class="uk-table uk-table-hover uk-table-striped">
				<thead>
	                <tr>
	                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
	                    <th style="width:15px">id</th>
	                    <th style="width:20px">Published</th>
	                    <th>Name</th>
	                    <th style="width:120px">Actions</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($types as $type)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$type->id}}"/></td>
	                        <td>{{ $type->id }}</td>
	                        <td class="uk-text-center">@if($type->published)<i class="uk-icon-check"></i>@else<i class="uk-icon-remove"></i>@endif</td>
	                        <td>{{ $type->name }}</td>
	                        <td>
	                            <!-- This is the container enabling the JavaScript -->
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <!-- This is the button toggling the dropdown -->
	                                <button class="uk-button">Actions <i class="uk-icon-caret-down"></i></button>
	                                <!-- This is the dropdown -->
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="bikes/types/{{ $type->id }}">Edit</a></li>
	                                        <li><a href="bikes/types/clone/{{ $type->id }}">Clone</a></li>
	                                        <li><a id="{{ $type->id }}" onclick="deleteObject(this)">Delete</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $types->render(); ?>
		</div>
		@include('admin.listing-types.new')
	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="{{ asset('/js/floatinglabel.min.js') }}"></script>
	<script type="text/javascript">
		$('#create_form').floatinglabel({ ignoreId: ['ignored'] });

	    function toggle(source){
	        checkboxes = document.getElementsByName('checkedLine');
	        for(var i=0, n=checkboxes.length;i<n;i++) {
	            checkboxes[i].checked = source.checked;
	        }
	    }

	    function deleteObject(sender) {
	        $.post("{{ url('/admin/feature-categories') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	            location.reload();
	        });
	    }

	    function deleteObjects() {
            var checkedValues = $('input[name="checkedLine"]:checked').map(function() {
                return this.value;
            }).get();
            $.post("{{ url('/admin/feature-categories/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
                location.reload();
            });
        }
	</script>
@endsection