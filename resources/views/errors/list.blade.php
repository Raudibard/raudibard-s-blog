@if ($errors->any())
	
	<div class="alert alert-danger" role="alert">
		<span class="glyphicon glyphicon-remove-sign"></span>Something went wrong:
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>

@endif