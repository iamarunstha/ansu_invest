@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Ownership">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800"> Ownership Tabs</h1>
	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.Ownership.backend.modal.create-tab')
	<a href="{{ route('admin-ownership-columns-list-get') }}" class="btn btn-info btn-flat">Go to columns</a>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Tab name</th>
					<th>Ordering</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($tabs as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $d->tab_name }}</td>
					<td>{{ $d->ordering }}</td>
					<td>
						<a href="{{route('admin-ownership-company-list-get', $d->id)}}" class="btn btn-info">View</a>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditModal{{$d->id}}">Edit</button>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($tabs as $index => $d) 
		<form method="post" action="{{route('admin-ownership-tabs-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>

		<div id="myEditModal{{$d->id}}" class="modal fade" role="dialog">
  		  <div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
     		  <div class="modal-header">
     		   	<h3><strong>Update tab</strong></h3>
     		  </div>
     		  <div class="modal-body">
     		    <form method="post" action="{{ route('admin-ownership-tabs-update-post', $d->id)}}">
     		   	  <div class="form-group">
	 		       	<label for="data[tab_name]">Tab Name</label>
	 		       	<input type="text" name="data[tab_name]" value="{{$d->tab_name}}" placeholder="Enter new tab name">
	        	  </div>
	        	  <div class="form-group">
	        		<label for="data[ordering]">Ordering</label> 
	        		<input type="number" name="data[ordering]" value="{{$d->ordering}}" placeholder="Enter order of tab">
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