@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">{{ $company->company_name }} Summary List</h1>

	<a href="{{ route('admin-company-summary-create-get', $company->id) }}" class="btn btn-info btn-flat">Create</a>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">
	Delete Multiple</a>
	<a href="{{ route('admin-company-list-get') }}" class="btn btn-info">Go Back</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Analyst</th>
					<th>Title</th>
					<th>Summary</th>
					<th>Is Active</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->posted_by }} ({{ $d->analyst_post }})</td>
					<td>{{ $d->title }}</td>
					<td>{!! nl2br($d->summary) !!}</td>
					<td>{{ $d->is_active }}</td>
					<td>
						<a href="{{ route('admin-company-summary-edit-get', [$company->id, $d->id]) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{ $data->links() }}
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-company-summary-delete-post', [$company->id, $d->id]) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-company-summary-delete-multiple-post', $company->id) }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop