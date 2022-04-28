@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Notice">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Notice table</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.Notice.backend.modal.create-notice', ['companies' => $companies])
	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Name</th>
					<th>Published at</th>

					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($notices as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->name }}</td>
					<td>{{ $d->notice_date }}</td>

					<td>
						<a href="{{ route('admin-notice-edit-get', $d->id) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($notices as $index => $d) 
		<form method="post" action="{{route('admin-notice-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{route('admin-notice-delete-multiple-post')}}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop

@section('custom-js')
<script>
$('.company-name').keyup(function(e) {
	e.preventDefault();
	let current_value = $(this).val().toLowerCase();
	$('.list-of-companies').each(function() {
		let company_name = $(this).attr('company_name').toLowerCase();
		let checkbox = $(this).find('input[type="checkbox"]');
		if(checkbox.is(':checked')) {
			$(this).show();
		}
		else if(current_value.length > 0 && company_name.includes(current_value)) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});

})
</script>
@stop