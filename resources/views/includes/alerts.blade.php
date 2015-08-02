@if (count($errors) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach ($errors->all() as $error)
				UIkit.notify('<i class="uk-icon-remove"></i> {{ $error }}', {pos:'top-right', status:'danger', timeout: 15000});
	    	@endforeach
		});
    </script>
@endif

@if(Session::has('success') && count(Session::get('success')) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach (Session::get('success') as $success)
				UIkit.notify('<i class="uk-icon-check-circle"></i> {{ $success }}', {pos:'top-right', status:'success', timeout: 15000});
	    	@endforeach
		});
	</script>
@endif

@if(Session::has('notice') && count(Session::get('notice')) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach (Session::get('notice') as $notice)
				UIkit.notify('<i class="uk-icon-circle-o"></i> {{ $notice }}', {pos:'top-right', status:'info', timeout: 15000});
	    	@endforeach
		});
	</script>
@endif

@if(Session::has('warning') && count(Session::get('warning')) > 0)
	<script type="text/javascript">
		$(function() {
			@foreach (Session::get('warning') as $warning)
				UIkit.notify('<i class="uk-icon-minus-square"></i> {{ $warning }}', {pos:'top-right', status:'warning', timeout: 15000});
	    	@endforeach
		});
	</script>
@endif

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