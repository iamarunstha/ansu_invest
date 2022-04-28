@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Recommendations">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Recommendations List</h1>

	<a href="{{ route('admin-recommendations-create-get') }}" class="btn btn-info btn-flat">Create</a>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Title</th>
					<th>Summary</th>
					<th>Posted At</th>
					<th>Posted By</th>
					<th>Published</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->title }}</td>
					<td>{{ $d->summary }}</td>
					<td>{{ $d->posted_at }}</td>
					<td>{{ $d->posted_by }}</td>
					<td>{{ $d->is_active }}</td>
					<td>
						<a href="{{ route('admin-recommendations-edit-get', $d->id) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
						<a href="#" class="a_submit_button btn btn-success" related-id="top-recommendations-{{ $d->id }}">@if($d->is_top_recommendations == 'yes') Unfeature @else Featute @endif</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{ $data->links() }}
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-recommendations-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
		<form method="post" action="{{ route('admin-recommendations-set-as-top-recommendations-post', $d->id) }}" id="top-recommendations-{{ $d->id }}" class="">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-recommendations-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop