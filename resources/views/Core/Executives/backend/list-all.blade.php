@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Executives">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Executives List</h1>
  	<div class="search-form" style="display:block;">
   		<form method="get" action="{{route('admin-executives-list-all-get')}}">
  	  		<input type="text" placeholder="Search.." name="search">
      		<button type="submit"><i class="fa fa-search"></i></button>
  		</form>
  	</div>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Company</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $d->company_name }}</td>
					<td>
						<a href="{{route('admin-executives-list-get', $d->company_id)}}" class=" btn btn-info" related-id="">View</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $data->appends(request()->all())->links() }}
	</div>

@stop