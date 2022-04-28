@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Absolute Valuation">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Absolute valuation List</h1>

	<form>
		<div class="form-group">
			<label>Search</label>
			<input type="text" name="company_name" value="{{ request()->get('company_name') }}" />
		</div>
		<input type="submit" class="btn btn-info" value="Search" />
	</form>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<form method="post" action="{{route('admin-relative-valuation-edit-post')}}">
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
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->company_id }}">{{ $index + 1 }}</td>
					<td>{{ $d->company_name }}</td>
					<td>
						@if(!is_null($d->relative_valuation_id))
							<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->company_id }}">Delete</a>
						@endif
							
						@if(!is_null($d->relative_valuation_id)) 

							<a href="{{route('admin-relative-valuation-create-get', $d->company_id)}}" class=" btn btn-info" related-id="">
								Edit 
							</a>
						@else

							<a href="{{route('admin-relative-valuation-create-get', $d->company_id)}}" class=" btn btn-info" related-id="">
								Create
							</a>
						@endif
						
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $data->appends(request()->all())->links() }}
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-relative-valuation-list-get') }}" class="btn btn-info">Cancel</a>
		{{csrf_field()}}
		</form>
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-relative-valuation-delete-post', $d->company_id) }}" id="delete-{{ $d->company_id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-relative-valuation-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop