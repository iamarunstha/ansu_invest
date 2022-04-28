@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" value="Definitions">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Pages</h1>
  	
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Page</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $d->page }}</td>
					<td>
						<div class="btn-group">
                  			<a href="{{ route('admin-static-update-get', $d->id) }}" class="btn btn-info">Update</a>
                		</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop