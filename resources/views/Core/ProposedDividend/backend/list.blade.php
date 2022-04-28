@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Proposed Dividend">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Proposed dividend table</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.ProposedDividend.backend.modal.create-proposed-dividend', ["sectors" => $sectors, "years" => $years])
	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Symbol</th>
					<th>Company Name</th>
					<th>Sector</th>
					<th>Bonus %</th>
					<th>Cash %</th>
					<th>Year</th>
					<th>Distribution Date</th>
					<th>Book closure Date</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($dividends as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->symbol }}</td>
					<td>{{ $d->company_name }}</td>
					<td>{{ $d->sector->name }}</td>
					<td>{{ $d->bonus }}</td>
					<td>{{ $d->cash }}</td>
					<td>{{ $d->fiscalYear->fiscal_year }}</td>
					<td>{{ $d->distribution_date }}</td>
					<td>{{ $d->book_closure_date }}</td>
					<td>
						<a href="{{ route('admin-proposed-dividend-edit-get', $d->id) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($dividends as $index => $d) 
		<form method="post" action="{{route('admin-proposed-dividend-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{route('admin-proposed-dividend-delete-multiple-post')}}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop