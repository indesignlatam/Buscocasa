@extends('layouts.master')

@section('head')
    <title>Roles - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<link href="/css/floatinglabel.css" rel="stylesheet">
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<a href="#create_role_modal" class="uk-button uk-button-primary uk-align-right" data-uk-modal>New</a>
		<h3 class="uk-panel-title">Roles</h3>
		
		<hr>

		<div class="uk-panel uk-panel-box">
			<table class="uk-table uk-table-hover uk-table-striped">
				<thead>
	                <tr>
	                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
	                    <th style="width:15px">id</th>
	                    <th>Name</th>
	                    <th>Slug</th>
	                    <th>Description</th>
	                    <th style="width:30px">Level</th>
	                    <th style="width:120px">Actions</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($roles as $role)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$role->id}}" /></td>
	                        <td>{{ $role->id }}</td>
	                        <td>{{ $role->name }}</td>
	                        <td>{{ $role->slug }}</td>
	                        <td>{{ $role->description }}</td>
	                        <td>{{ $role->level }}</td>
	                        <td>
	                            <!-- This is the container enabling the JavaScript -->
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <!-- This is the button toggling the dropdown -->
	                                <button class="uk-button">Actions <i class="uk-icon-caret-down"></i></button>
	                                <!-- This is the dropdown -->
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="bikes/types/{{ $role->id }}">Edit</a></li>
	                                        <li><a href="bikes/types/clone/{{ $role->id }}">Clone</a></li>
	                                        <li><a id="{{ $role->id }}" onclick="deleteObject(this)">Delete</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $roles->render(); ?>
		</div>
		@include('admin.roles.new')
	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="/js/floatinglabel.min.js"></script>
	<script> 
		$('#create_form').floatinglabel({ ignoreId: ['ignored'] });
	</script>

	<script type="text/javascript">
	    function toggle(source){
	        checkboxes = document.getElementsByName('checkedLine');
	        for(var i=0, n=checkboxes.length;i<n;i++) {
	            checkboxes[i].checked = source.checked;
	        }
	    }

	    function deleteObject(sender) {
	        $.post("/admin/roles/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	        });
	    }
	</script>
@endsection