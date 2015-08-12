@if (count($errors) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach ($errors->all() as $error)
				UIkit.notify('<i class="uk-icon-remove"></i> {{ $error }}', {pos:'top-right', status:'danger', timeout: 10000});
	    	@endforeach
		});
    </script>
@endif

@if(Session::has('success') && count(Session::get('success')) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach (Session::get('success') as $success)
				UIkit.notify('<i class="uk-icon-check-circle"></i> {{ $success }}', {pos:'top-right', status:'success', timeout: 10000});
	    	@endforeach
		});
	</script>
@endif

@if(Session::has('notice') && count(Session::get('notice')) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach (Session::get('notice') as $notice)
				UIkit.notify('<i class="uk-icon-circle-o"></i> {{ $notice }}', {pos:'top-right', status:'info', timeout: 10000});
	    	@endforeach
		});
	</script>
@endif

@if(Session::has('warning') && count(Session::get('warning')) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach (Session::get('warning') as $warning)
				UIkit.notify('<i class="uk-icon-minus-square"></i> {{ $warning }}', {pos:'top-right', status:'warning', timeout: 10000});
	    	@endforeach
		});
	</script>
@endif