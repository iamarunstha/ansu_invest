@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Investment">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800"> Investment/Existing Issues Tabs</h1>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Tab name</th>
					<th>Ordering</th>
					<th>Show in small table</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($tabs as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->tab_name }}</td>
					<td>{{ $d->ordering }}</td>
					<td>@if($d->show_in_half_table)
							yes
						@else
							no
						@endif
					</td>
					<td>
						<a href="{{route('admin-investment-list-get', $d->id)}}" class="btn btn-info">View</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop