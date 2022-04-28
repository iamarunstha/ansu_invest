@extends('backend.main')

@section('content')
	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link @if(url()->current() == route('admin-user-groups-get')) active @endif" href="{{route('admin-user-groups-get')}}">Groups</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-list-get')}}">Admins</a>
  		</li>
  	</ul>


  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Admin Groups</h1>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Group Name</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $d->group_name }}</td>
					<td>
						<a href="{{route('admin-user-groups-members-get', $d->id)}}" class="btn btn-info">View</a>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditModal-{{$d->id}}">Edit</button>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.UserGroup.backend.modal.create-user-group')

	@foreach($data as $index => $d) 
		<form method="post" action="{{route('admin-user-groups-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	@foreach($data as $index => $d)
		<div id="myEditModal-{{$d->id}}" class="modal fade" role="dialog">
    	  <div class="modal-dialog">
    	  <!-- Modal content-->
        	<div class="modal-content">
        	  <div class="modal-header">
        	    <h3><strong>Edit User Group</strong></h3>
        	  </div>
        	  <div class="modal-body">
        	    <form method="post" action="{{route('admin-user-groups-edit-post', $d->id)}}">
	          	  <div class="form-group">
		        	<label>Group Name</label>
		        	<input type="text" name="data[group_name]" value="{{ $d->group_name }}" placeholder="Enter new group name" required>
		          </div>
	        	  <input type="submit" class="btn btn-success" value="Edit">
	         	  <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
	        	  {{csrf_field()}}
	        	</form>
	      	  </div>
	    	</div>
	  	  </div>
		</div>
	@endforeach
@stop
