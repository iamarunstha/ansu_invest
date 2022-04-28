@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Fiscal Year">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Fiscal year List</h1>

	<a href="{{ route('admin-fiscal-year-create-get') }}" class="btn btn-info btn-flat">Create</a>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<a href="{{ route('admin-fiscal-year-edit-ordering-get') }}" class="btn btn-info" >Change Ordering</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Fiscal Year</th>
					<th>Historical</th>
					<th>Ordering</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->fiscal_year }}</td>
					<td>
						@if($d->historical)
							Yes
						@else
							No
						@endif
					</td> 
					<td>{{ $d->ordering }}</td>
					<td>
						<a href="{{ route('admin-fiscal-year-edit-get', $d->id) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
						<a href="{{ route('admin-fiscal-year-company-assign-get', $d->id)}}" class="btn btn-info">Assign to companies</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{ $data->links() }}
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-fiscal-year-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>

	@endforeach

	<form id="multiple-delete" action="{{ route('admin-fiscal-year-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop