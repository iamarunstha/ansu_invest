@extends('backend.main')

@section('content')
	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link @if(url()->current() == route('admin-user-groups-get')) active @endif" href="{{route('admin-user-groups-get')}}">Groups</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-list-get')}}">Admins</a>
  		</li>
  	</ul>
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Admins</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myCreateModal"> Create </button>
	@include('Core.UserGroup.backend.modal.create-new-admin')

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
					<td>{{ $d->name }}</td>
					<td>{{ $d->email }}</td>
					<td>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditModal-{{$d->id}}" >Edit</button>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{route('admin-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>

		<div id="myEditModal-{{$d->id}}" class="modal fade" role="dialog">
		  <div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <h3><strong>Edit Admin</strong></h3>
		      </div>
		      <div class="modal-body">
		        <form method="post" action="{{ route('admin-edit-post', $d->id) }}">
		        	<div class="form-group">
			        	<label>Admin Name</label><br>
			        	<input type="text" name="data[name]" value="{{ $d->name }}"  required><br>
			        </div>
		          <div class="form-group">
		            <label>Admin Email</label><br>
		            <input type="text" name="data[email]" value="{{ $d->email }}"  required>
		          </div>
		          <div class="form-group">
		            <label>New Password</label><br>
		            <input type="password" name="data[password]"  placeholder="Unchanged">
					<span>Must be empty to keep unchanged</span>
					<span>If changed, password must contain a digit, a special character [@,$,!,%,*,#,?,&,^] and atleast six characters.</span>
				  </div>
		        	<input type="submit" class="btn btn-success" value="Update">
		        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
		        	{{csrf_field()}}
		        </form>
		      </div>
		    </div>
		  </div>
		</div>
	@endforeach
@stop
