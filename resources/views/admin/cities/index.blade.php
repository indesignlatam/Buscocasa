@extends('layouts.master')

@section('head')
    <title>Cities - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
	<link href="{{asset('/css/floatinglabel.css')}}" rel="stylesheet">
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div class="uk-panel">
		<h3 class="uk-panel-title">Cities</h3>
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
	                    <th>Name</th>
	                    <th>Country</th>
	                    <th style="width:120px">Actions</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($cities as $city)
	                    <tr>
	                      	<td><input type="checkbox" name="checkedLine" value="{{$city->id}}"/></td>
	                        <td>{{ $city->id }}</td>
	                        <td>{{ $city->name }}</td>
	                        <td>{{ $city->country->name }}</td>
	                        <td>
	                            <!-- This is the container enabling the JavaScript -->
	                            <div class="uk-button-dropdown" data-uk-dropdown>
	                                <!-- This is the button toggling the dropdown -->
	                                <button class="uk-button">Actions <i class="uk-icon-caret-down"></i></button>
	                                <!-- This is the dropdown -->
	                                <div class="uk-dropdown uk-dropdown-small">
	                                    <ul class="uk-nav uk-nav-dropdown">
	                                        <li><a href="bikes/types/{{ $city->id }}">Edit</a></li>
	                                        <li><a href="bikes/types/clone/{{ $city->id }}">Clone</a></li>
	                                        <li><a id="{{ $city->id }}" onclick="deleteObject(this)">Delete</a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>
	                    </tr>
	              	@endforeach
	            </tbody>
			</table>
			<?php echo $cities->render(); ?>
		</div>
		@include('admin.cities.new')
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
	        $.post("{{ url('/admin/cities') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
	            location.reload();
	        });
	    }

	    function deleteObjects() {
            var checkedValues = $('input[name="checkedLine"]:checked').map(function() {
                return this.value;
            }).get();
            $.post("{{ url('/admin/cities/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
                location.reload();
            });
        }
	</script>
@endsection