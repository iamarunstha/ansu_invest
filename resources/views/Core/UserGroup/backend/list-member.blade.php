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
  	<h1 class="h3 mb-4 text-gray-800">{{$group_name}}</h1>
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
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $d->admin->name }}</td>
					<td>{{ $d->admin->email }}</td>
					<td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Remove</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<a href="{{ route('admin-user-groups-members-add-get', $group_id) }}"><button class="btn btn-info"> Add New Admin</button></a>

	@foreach($data as $index => $d) 
		<form method="post" action="{{route('admin-user-groups-members-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach
@stop
