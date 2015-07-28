@extends('layouts.master')

@section('head')
    <title>Users - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<link href="/css/components/datepicker.almost-flat.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<button class="uk-button uk-button-primary uk-align-right" type="submit" form="config">New</button>
		<h3 class="uk-panel-title">Users</h3>
		<hr>

		<div class="uk-panel uk-panel-box">
			<table class="uk-table uk-table-hover uk-table-striped">
				<thead>
	                <tr>
	                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
	                    <th style="width:15px">id</th>
	                    <th>Name</th>
	                    <th>email</th>
	                    <th>Username</th>
	                    <th style="width:140px">Updated</th>
	                    <th style="width:120px">Actions</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($users as $user)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$user->id}}" /></td>
	                        <td>{{ $user->id }}</td>
	                        <td><a href="users/profile/{{$user->id}}">{{$user->name}}</a></td>
	                        <td>{{ $user->email }}</td>
	                        <td>{{ $user->username}}</td>
	                        <td>{{ date ( 'F d, Y', strtotime($user->updated_at)) }}</td>
	                        <td>
	                            <!-- This is the container enabling the JavaScript -->
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <!-- This is the button toggling the dropdown -->
	                                <button class="uk-button">Actions <i class="uk-icon-caret-down"></i></button>
	                                <!-- This is the dropdown -->
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="bikes/types/{{ $user->id }}">Edit</a></li>
	                                        <li><a href="bikes/types/clone/{{ $user->id }}">Clone</a></li>
	                                        <li><a href="bikes/types/delete/{{ $user->id }}">Delete</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $users->render(); ?>
		</div>
	
	</div>
</div>
@endsection

@section('js')
	@parent
	<script src="/js/components/datepicker.min.js"></script>
@endsection
