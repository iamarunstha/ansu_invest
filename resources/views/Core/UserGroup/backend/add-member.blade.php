@extends('backend.main')

@section('content')
	
	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-user-groups-get')}}">Groups</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-list-get')}}">Admins</a>
  		</li>
  	</ul>
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Select Admins</h1>
  	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-user-groups-members-get', $group_id)}}">Members</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-user-groups-permission-get', $group_id)}}">Permissions</a>
  		</li>
  	</ul>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Admin Name</th>
					<th>Admin Email</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				@if($d->status == 'Not a member')
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->name }}</td>
					<td>{{ $d->email }}</td>
					<td>
						@if($d->status == 'Not a member')
							<a href="#" class="a_submit_button btn btn-info" related-id="add-{{ $d->id }}">Add</a>
						@else
							<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Remove</a>
						@endif
					</td>
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
	</div>
	<a href="#" class="btn btn-success prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-add">Add Multiple</a>
	@include('Core.UserGroup.backend.modal.create-new-admin')

	@foreach($data as $index => $d) 
		<form method="post" action="{{route('admin-user-groups-members-delete-post', [$group_id, $d->id])}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
		<form method="post" action="{{route('admin-user-groups-members-add-post', [$group_id, $d->id])}}" id="add-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
		<form id="multiple-add" action="{{ route('admin-user-groups-member-add-multiple-post', $group_id) }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
	@endforeach
@stop
