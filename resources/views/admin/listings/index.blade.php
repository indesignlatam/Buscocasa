@extends('layouts.master')

@section('head')
    <title>{{ trans('admin.my_listings') }} - {{ Settings::get('site_name') }}</title>
@endsection

@section('css')
	@parent
@endsection

@section('content')

<div class="uk-container uk-container-center uk-margin-top">
	<div id="alert">
	</div>

	<div class="uk-panel">
	
		@if(Auth::user()->isAdmin())
			<h1>{{ trans('admin.listings') }}</h1>

		    <div class="">
		        <!-- This is a button toggling the modal -->
		        <a class="uk-button" href="{{ url('/admin/listings/create') }}">{{ trans('admin.new') }}</a>
				<button class="uk-button uk-button-danger" onclick="deleteObjects()"><i class="uk-icon-trash"></i></button>	
				<form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right">
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
		@else
			<h1>{{ trans('admin.my_listings') }}</h1>

			<hr>
			@if(count($listings) > 0)
			    <div class="">
			        <!-- This is a button toggling the modal -->
			        <a class="uk-button uk-button-large uk-button-primary uk-text-bold" href="{{ url('/admin/listings/create') }}">{{ trans('admin.publish_property') }}</a>
			        {{-- <button class="uk-button uk-button-large uk-button-danger" onclick="deleteObjects()"><i class="uk-icon-trash"></i></button> --}}
			        <a class="uk-button uk-button-large" href="{{ url('/admin/listings/?deleted=true') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminated_listings') }}"><i class="uk-icon-trash"></i></a>

			        <form action="{{url(Request::path())}}" method="GET" class="uk-form uk-align-right">
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
		@endif
		
		@if(Auth::user()->isAdmin())
			<div class="uk-panel uk-panel-box uk-margin-top">
				<table class="uk-table uk-table-hover uk-table-striped">
					<thead>
		                <tr>
		                  	<th style="width:15px"><input type="checkbox" id="checkedLineHeader" onclick="toggle(this)"/></th>
		                    <th style="width:15px">{{ trans('admin.id') }}</th>
		                    <th style="width:20px">{{ trans('admin.published') }}</th>
		                    <th style="width:20px">{{ trans('admin.image') }}</th>
		                    <th>{{ trans('admin.title') }}</th>
		                    <th style="width:20px">{{ trans('admin.category') }}</th>
		                    <th style="width:20px">{{ trans('admin.type') }}</th>
		                    <th style="width:80px">{{ trans('admin.status') }}</th>
		                    <th style="width:20px">{{ trans('admin.city') }}</th>
		                    <th style="width:120px">{{ trans('admin.actions_button') }}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($listings as $listing)
		                    <tr>
		                      	<td><input type="checkbox" name="checkedLine" value="{{$listing->id}}"/></td>
		                        <td>{{ $listing->id }}</td>
		                        <td class="uk-text-center">@if($listing->published)<i class="uk-icon-check"></i>@else<i class="uk-icon-remove"></i>@endif</td>
		                        <td><img src="{{ asset(Image::url($listing->image_path(),['map_mini'])) }}"></td>
		                        <td><a href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">{{ $listing->title }}</a></td>
		                        <td>{{ $listing->category->name }}</td>
		                        <td>{{ $listing->listingType->name }}</td>
		                        <td>{{ $listing->listingStatus->name }}</td>
		                        <td>{{ $listing->city->name }}</td>
		                        <td>
		                            <!-- This is the container enabling the JavaScript -->
		                            <div class="uk-button-dropdown" data-uk-dropdown>
		                                <!-- This is the button toggling the dropdown -->
		                                <button class="uk-button">{{ trans('admin.actions_button') }} <i class="uk-icon-caret-down"></i></button>
		                                <!-- This is the dropdown -->
		                                <div class="uk-dropdown uk-dropdown-small">
		                                    <ul class="uk-nav uk-nav-dropdown">
		                                        <li><a href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">{{ trans('admin.edit') }}</a></li>
		                                        <li><a href="bikes/types/clone/{{ $listing->id }}">{{ trans('admin.clone') }}</a></li>
		                                        <li><a id="{{ $listing->id }}" onclick="deleteObject(this)">{{ trans('admin.delete') }}</a></li>
		                                    </ul>
		                                </div>
		                            </div>
		                        </td>
		                    </tr>
		              	@endforeach
		            </tbody>
				</table>
				<?php echo $listings->render(); ?>
			</div>
		@else
			@if(!Request::get('deleted') && count($listings) > 0)
				<div class="uk-panel uk-margin-top">					
					<ul class="uk-list">
						@foreach($listings as $listing)
			                <li class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom" id="listing-{{ $listing->id }}">
			                	<div class="uk-grid">
			                		<div class="uk-width-2-10">
			                			<a href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">
				                			<!-- Featured tag -->
						                	@if($listing->featured_expires_at && Carbon::createFromFormat('Y-m-d H:i:s', $listing->featured_expires_at, 'America/Bogota') > Carbon::now())
						                		<img src="{{asset($listing->featuredType->image_path)}}" style="position:absolute; top:0; left:0; max-width:150px">
						                	@endif
						                	<!-- Featured tag -->
			                				<img src="{{ asset(Image::url($listing->image_path(),['map_mini'])) }}">
			                			</a>
			                		</div>

			                		<div class="uk-width-6-10">
			                			<!-- Listing title -->
			                			<a class="uk-h3 uk-text-bold" style="color:black;" href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">{{ $listing->title }}</a>
			                			<!-- Listing title -->

			                			<!-- Listing info and share -->
			                			<div class="uk-grid uk-margin-top">

			                    			<ul class="uk-list uk-list-line uk-width-4-10">
			                    				<li><i class="uk-text-muted">{{ trans('admin.price') }}</i> {{ money_format('$%!.0i', $listing->price) }}</li>
			                    				@if($listing->area > 0)
			                    					<li><i class="uk-text-muted">{{ trans('admin.mt2_price') }}</i> {{ money_format('$%!.0i', $listing->price/$listing->area) }}</li>
			                    					<li><i class="uk-text-muted">{{ trans('admin.area') }}</i> {{ number_format($listing->area, 0) }} mt2</li>
			                    				@elseif($listing->lot_area > 0)
			                    					<li><i class="uk-text-muted">{{ trans('admin.mt2_price') }}</i> {{ money_format('$%!.0i', $listing->price/$listing->lot_area) }}</li>
			                    					<li><i class="uk-text-muted">{{ trans('admin.area') }}</i> {{ number_format($listing->lot_area, 0) }} mt2</li>
			                    				@endif
			                    				<li><i class="uk-text-muted">{{ trans('admin.code') }}</i> #{{ $listing->code }}</li>
			                    			</ul>

			                    			<ul class="uk-list uk-list-line uk-width-4-10">
			                    				<li>
			                    					<a href="{{ $listing->pathEdit() }}#6" style="text-decoration: none">
			                    					@if(count($listing->images)>0)
			                    					 	<i class="uk-icon-check uk-text-success"> </i>
			                    					@else
			                    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.images_check_tooltip') }}"> </i>
			                    					@endif
													<i class="uk-text-muted">{{ trans('admin.images') }}</i>
													</a>
			                    				</li>
			                    				<li>
			                    					<a href="{{ $listing->pathEdit() }}#7" style="text-decoration: none">
			                    					@if(Cookie::get('shared_listing_'.$listing->id))
														<i class="uk-icon-check uk-text-success"> </i>
			                    					@else
			                    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.shared_check_tooltip') }}"> </i>
			                    					@endif
			                    					<i class="uk-text-muted">{{ trans('admin.shared') }}</i>
			                    					</a>
			                    				<li>
			                    					<a href="{{ $listing->pathEdit() }}#5" style="text-decoration: none">
			                    					@if(strlen($listing->description) > 50)
														<i class="uk-icon-check uk-text-success"> </i>
			                    					@else
			                    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.aditional_check_tooltip') }}"> </i>
			                    					@endif
			                    					<i class="uk-text-muted">{{ trans('admin.description') }}</i>
			                    					</a>
			                    				</li>
			                    				<li>
			                    					<a href="{{ $listing->pathEdit() }}#4" style="text-decoration: none">
			                    					@if(count($listing->features) > 5)
														<i class="uk-icon-check uk-text-success"> </i>
			                    					@else
			                    						<i class="uk-icon-remove uk-text-danger" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.features_check_tooltip') }}"> </i>
			                    					@endif
			                    					<i class="uk-text-muted">{{ trans('admin.features') }}</i>
			                    					</a>
			                    				</li>
			                    			</ul>
			                    			
			                    			<!-- Share buttons -->
			                    			<div class="uk-width-2-10 uk-text-center">
			                    				<h4 class="uk-text-bold" style="color:black">{{ trans('admin.share') }}</h4>
			                    				<ul class="uk-list" style="list-style: none;">
				                    				<li style="margin-left:-20px;">
					                    				<a onclick="share('{{ url($listing->path()) }}', {{ $listing->id }})" class="uk-icon-button uk-icon-facebook"></a> 
					                    				<a class="uk-icon-button uk-icon-twitter twitter-share-button" href="https://twitter.com/intent/tweet?text=Hello%20world%20{{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=440,width=600');return false;"></a>
				                    				</li>
				                    				<li class="uk-margin-small-top" style="margin-left:-20px;">
				                    					<a href="https://plus.google.com/share?url={{ url($listing->path()) }}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="uk-icon-button uk-icon-google-plus"></a>
				                    					<a href="" class="uk-icon-button uk-icon-envelope"></a>
				                    				</li>
				                    			</ul>
			                    			</div>
			                    			<!-- Share buttons -->
			                			</div>
			                			<!-- Listing info and share -->
			                		</div>

			                		<div class="uk-width-2-10">
			                			@if(!$listing->deleted_at)
			                				<!-- If listing is featured and is not expired yet -->
				                			@if($listing->featured_expires_at && $listing->featured_expires_at > Carbon::now())
				                				<!-- If listing iexpires in the next 5 days -->
				                				@if($listing->featured_expires_at <= Carbon::now()->addDays(5))
					                				<a class="uk-text-danger uk-text-bold uk-h4" href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.featured_expires') }} {{ $listing->featured_expires_at->diffForHumans() }}</a>
					                				<a class="uk-button uk-button-large uk-button-success uk-width-1-1 uk-margin-small-bottom" href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.renovate') }}</a>
						                        @else
					                				<b>{{ trans('admin.featured_expires') }} {{ $listing->featured_expires_at->diffForHumans() }}</b>

						                			<!-- View messages button -->
					                				<a class="uk-button uk-width-1-1 uk-margin-small-bottom" href="{{ url('/admin/messages/'.$listing->id) }}">{{ trans('admin.view_messages') }}</a>
						                			<!-- View messages button -->
					                			@endif
				                			@else
				                				@if($listing->expires_at <= Carbon::now()->addDays(5))
					                				<a class="uk-text-danger uk-text-bold uk-h4" href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.expires') }} {{ $listing->expires_at->diffForHumans() }}</a>
					                				<a class="uk-button uk-button-large uk-button-success uk-width-1-1 uk-margin-small-bottom" href="{{ url('/admin/listings/'.$listing->id.'/renovate') }}">{{ trans('admin.renovate') }}</a>
						                        @else
					                				<b>{{ trans('admin.expires') }} {{ $listing->expires_at->diffForHumans() }}</b>

					                				<!-- Featured button -->
					                				<a class="uk-button uk-button-success uk-width-1-1 uk-margin-small-bottom" href="{{ url('admin/destacar/'.$listing->id) }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.feature_listing') }}">{{ trans('admin.feature') }}</a>
						                			<!-- Featured button -->

						                			<!-- View messages button -->
					                				<a class="uk-button uk-width-1-1 uk-margin-small-bottom" href="{{ url('/admin/messages/'.$listing->id) }}">{{ trans('admin.view_messages') }}</a>
						                			<!-- View messages button -->
					                			@endif
				                			@endif

				                			<!-- View in frontend button -->
				                            <a class="uk-button uk-width-1-1 uk-margin-small-bottom" href="{{ url($listing->path()) }}" target="_blank">{{ trans('admin.view_listing') }}</a>
				                			<!-- View in frontend button -->

				                			<!-- Edit and delete buttons -->
					                		<div class="uk-flex uk-flex-center uk-flex-space-between">
					                			<a class="uk-button" href="{{ url('/admin/listings/'.$listing->id.'/edit') }}">{{ trans('admin.edit') }}</a>
					                			<a class="uk-button" href="{{ url('/admin/banners/create') }}" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.print_banner') }}"><i class="uk-icon-print"></i></a>
					                            <a class="uk-button uk-button-danger" id="{{ $listing->id }}" onclick="deleteObject(this)" data-uk-tooltip="{pos:'top'}" title="{{ trans('admin.eliminate_listing') }}"><i class="uk-icon-trash"></i></a>
				                			</div>
				                			<!-- Edit and delete buttons -->
				                		@endif
			                        </div>
			                	</div>
			                </li>
			          	@endforeach
					</ul>
					<?php echo $listings->render(); ?>
				</div>
			@elseif(Request::get('deleted'))
				@if(count($listings))
					<table class="uk-table">
						<thead>
					        <tr>
					            <th style="width:15px">{{ trans('admin.id') }}</th>
					            <th style="width:40px">{{ trans('admin.image') }}</th>
					            <th style="width:50%">{{ trans('admin.title') }}</th>
					            <th style="width:50px">{{ trans('admin.area') }}</th>
					            <th style="width:50px">{{ trans('admin.price') }}</th>
					            <th style="width:9%">{{ trans('admin.recover') }}</th>
					        </tr>
					    </thead>
					    <tbody>
						@foreach($listings as $listing)
					        <tr id="listing-{{ $listing->id }}">
					            <td>{{ $listing->id }}</td>
					            <td><img src="{{ asset(Image::url($listing->image_path(),['map_mini'])) }}" style="width:40px"></td>
					            <td class="uk-text-bold">{{ $listing->title }}</td>
					            <td>{{ number_format($listing->area, 0) }} mt2</td>
					            <td>{{ money_format('$%!.0i', $listing->price) }}</td>
					            <td>
					            	<div class="uk-flex uk-flex-space-between">
					            		<a href="{{ url('/admin/listings/'.$listing->id.'/recover') }}" class="uk-button uk-button-success"><i class="uk-icon-undo"></i></a>
					            		<button class="uk-button uk-button-danger" onclick="deleteObject(this)" id="{{ $listing->id }}"><i class="uk-icon-remove"></i></button>
					            	</div>
					            </td>
					        </tr>
						@endforeach
						</tbody>
					</table>
				@else
					<div class="uk-text-center uk-margin-top">
						<h2 style="color:#95979a" class="uk-text-bold">{{ trans('admin.you_have_no_deleted_listings') }}</h2>

						<div class="" style="margin-top:35px">
			    			<a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('admin.publish_property') }}</a>
			    			<br>
			    			<br>
			    			<a href="{{ url('/admin/listings') }}">{{ trans('admin.go_back_listings') }}</a>
			    		</div>
					</div>
				@endif
			@else
				<div class="uk-text-center uk-margin-top">
					<h2 style="color:#95979a" class="uk-text-bold">{{ trans('admin.you_have_no_listings') }}</h2>
					<a href="{{ url('/admin/listings/create') }}" class="uk-h3">{{ trans('admin.publish_property_4_steps') }}</a>
					<br>
					<br>
					<a href="{{ url('/admin/listings/create') }}">
						<img src="{{ asset('/images/support/listings/publica.png') }}" width="75%">
					</a>
		    		
		    		<div class="" style="margin-top:35px">
		    			<a href="{{ url('/admin/listings/create') }}" class="uk-button uk-button-large uk-button-primary">{{ trans('admin.publish_property') }}</a>
		    			<br>
		    			<br>
		    			<a href="{{ url('/admin/listings?deleted=true') }}">{{ trans('admin.show_deleted') }}</a>
		    		</div>
		    	</div>
			@endif
		@endif
		
	</div>
</div>
@endsection

@section('js')
	<link href="{{ asset('/css/components/tooltip.almost-flat.min.css') }}" rel="stylesheet">
	@parent
	<script src="{{ asset('/js/components/tooltip.min.js') }}"></script>

	<script type="text/javascript">
		window.fbAsyncInit = function() {
        	FB.init({
         		appId      : {{ Settings::get('facebook_app_id') }},
          		xfbml      : true,
          		version    : 'v2.3'
        	});
      	};
      	(function(d, s, id){
         	var js, fjs = d.getElementsByTagName(s)[0];
         	if (d.getElementById(id)) {return;}
         	js = d.createElement(s); js.id = id;
         	js.src = "//connect.facebook.net/en_US/sdk.js";
         	fjs.parentNode.insertBefore(js, fjs);
       	}(document, 'script', 'facebook-jssdk'));

       	function share(path, id){
       		FB.ui({
			  	method: 'share_open_graph',
			  	action_type: 'og.shares',
			  	action_properties: JSON.stringify({
			    object: path,
			})
			}, function(response, id){
				$.post("{{ url('/cookie/set') }}", {_token: "{{ csrf_token() }}", key: "shared_listing_"+id, value: true, time:11520}, function(result){
	                
	            });
			  	// Debug response (optional)
			  	console.log(response);
			});
       	}

	    function deleteObject(sender) {
	    	UIkit.modal.confirm("{{ trans('admin.sure') }}", function(){
			    // will be executed on confirm.
			    $.post("{{ url('/admin/listings') }}/" + sender.id, {_token: "{{ csrf_token() }}", _method:"DELETE"}, function(result){
			    	if(result.success){
			    		$('#alert').append('<div class="uk-alert uk-alert-success" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>'+result.success+'</p></div>');
			    		$('#listing-'+sender.id).fadeOut(500, function() { $(this).remove(); });
			    	}else if(result.error){
			    		$('#alert').append('<div class="uk-alert uk-alert-danger" data-uk-alert><a href="" class="uk-alert-close uk-close"></a><p>'+result.error+'</p></div>');
			    	}
		        });
			}, {labels:{Ok:'{{trans("admin.yes")}}', Cancel:'{{trans("admin.cancel")}}'}});
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
     //        $.post("{{ url('/admin/listings/delete') }}", {_token: "{{ csrf_token() }}", ids: checkedValues}, function(result){
     //            location.reload();
     //        });
     //    }
	</script>
@endsection