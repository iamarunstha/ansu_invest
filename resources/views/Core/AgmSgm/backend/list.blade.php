@extends('backend.main')


@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="AGM-SGM">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif




@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Agm Sgm table</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.AgmSgm.backend.modal.create-agm-sgm', ["sectors" => $sectors, "years" => $years])
	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Symbol</th>
					<th>Company Name</th>
					<th>AGM</th>
					<th>Venue</th>
					<th>Time</th>
					<th>AGM Date</th>
					<th>Year</th>
					<th>Sector</th>
					<th>Book closure Date</th>
					<th>Agenda</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($agms as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->symbol }}</td>
					<td>{{ $d->company_name }}</td>
					<td>{{ $d->agm }}</td>
					<td>{{ $d->venue }}</td>
					<td>{{ $d->time }}</td>
					<td>{{ $d->agm_date }}</td>
					<td>{{ $d->fiscalYear->fiscal_year }}</td>
					<td>{{ $d->sector->name }}</td>
					<td>{{ $d->book_closure_date }}</td>
					<td>{{ $d->agenda }}</td>
					<td>
						<a href="{{ route('admin-agm-sgm-edit-get', $d->id) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($agms as $index => $d) 
		<form method="post" action="{{route('admin-agm-sgm-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{route('admin-agm-sgm-delete-multiple-post')}}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop