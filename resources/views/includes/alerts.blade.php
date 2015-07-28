<div class="uk-container uk-container-center uk-margin-top">
	@if (count($errors) > 0)
		<div class="uk-alert uk-alert-danger" data-uk-alert>
			<a href="" class="uk-alert-close uk-close"></a>
			<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_error') }}<br><br>
			<ul class="uk-list">
				@foreach ($errors->all() as $error)
					<li><i class="uk-icon-remove"></i> {{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@if(Session::has('success') && count(Session::get('success')) > 0)
	    <div class="uk-alert uk-alert-success" data-uk-alert>
	    	<a href="" class="uk-alert-close uk-close"></a>
			<ul class="uk-list">
				@foreach (Session::get('success') as $error)
					<li><i class="uk-icon-check"></i> {{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@if(Session::has('notice') && count(Session::get('notice')) > 0)
	    <div class="uk-alert uk-alert-warning" data-uk-alert>
	    	<a href="" class="uk-alert-close uk-close"></a>
			<strong>{{ trans('frontend.oops') }}</strong> {{ trans('frontend.input_warning') }}<br><br>
			<ul class="uk-list">
				@foreach (Session::get('notice') as $error)
					<li><i class="uk-icon-minus"></i> {{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
</div>

{{-- @if (count($errors) > 0)
    <script type="text/javascript">
	    window.onload = function(e) {
	    	@foreach ($errors->all() as $error)
	            $.UIkit.notify('{{ $error }}', 'danger');
	    	@endforeach
	   	}
    </script>
@endif

@if(Session::has('success'))
	@if (count(Session::get('success')) > 0)
	    <script type="text/javascript">
	    	window.onload = function(e) {
			    @foreach (Session::get('success') as $success1)
			        $.UIkit.notify('{{ $success1 }}', 'success');
			    @endforeach
			}
	    </script>
	@endif
@endif

@if(Session::has('notice'))
	@if (count(Session::get('notice')) > 0)
	    <script type="text/javascript">
	    	window.onload = function(e) {
			    @foreach (Session::get('notice') as $notice1)
			        $.UIkit.notify('{{ $notice1 }}', 'info');
			    @endforeach
			}
	    </script>
	@endif
@endif --}}