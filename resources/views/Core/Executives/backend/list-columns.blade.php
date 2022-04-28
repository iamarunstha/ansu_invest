@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Executives Columns</h1>

	<a href="{{route('admin-executives-column-create-get')}}" class=" btn btn-info" related-id="">Create</a>
	<a href="{{route('admin-executives-column-delete-multiple-post')}}" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Name</th>
					<th>Ordering</th>
					<th>Type</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($columns as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{$d->column_name}}</td>
					<td>{{$d->ordering}}</td>
					<td>{{$d->type}}</td>
					<td>
						<a href="{{route('admin-executives-column-edit-get', $d->id)}}" class=" btn btn-info" related-id="">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($columns as $index => $d) 
		<form method="post" action="{{ route('admin-executives-column-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-executives-column-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop