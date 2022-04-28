@extends('backend.main')

@section('content')


  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Ownership Columns</h1>
	<button class="btn btn-info" data-toggle="modal" data-target="#myCreateColumnModal"> Create </button>
	<a class="btn btn-info" href="{{route('admin-ownership-tabs-list-get')}}">Go to tabs</a>
	@include('Core.Ownership.backend.modal.create-column')
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Column Name</th>
					<th>Column Type</th>
					<th>Ordering</th>
					<th>Is required</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($columns as $index => $c)
				
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $c->display_name }}</td>
					<td>{{ $c->column_type }}</td>
					<td>{{ $c->ordering }}</td>
					<td>@if($c->is_required) yes @else no @endif</td>
					<td>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditColumnModal{{$c->id}}">Edit</button>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $c->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($columns as $index => $d) 
		<form method="post" action="{{route('admin-ownership-columns-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>

		<div id="myEditColumnModal{{$d->id}}" class="modal fade" role="dialog">
  		  <div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
     		  <div class="modal-header">
     		   	<h3><strong>Update column</strong></h3>
     		  </div>
     		  <div class="modal-body">
     		    <form method="post" action="{{ route('admin-ownership-columns-update-post', $d->id)}}">	
     		   	  <div class="form-group">
	 		       	<label for="data[display_name]">Column Display Name</label>
	 		       	<input class="form-control" type="text" name="data[display_name]" value="{{$d->display_name}}" placeholder="Enter new column display name">
	        	  </div>
     		   	  <div class="form-group">
	 		       	<label for="data[column_type]">Column Type</label>
            		<select class="form-control" name="data[column_type]">
              			<option value="decimal" @if($d->column_type=='decimal')selected @endif>Numeric</option>
              			<option value="string" @if($d->column_type=='string')selected @endif>Text</option>
              			<option value="date" @if($d->column_type=='date')selected @endif>Date</option>
            		</select>
	        	  </div>
	        	  <div class="form-group">
	        		<label for="data[ordering]">Ordering</label> 
	        		<input class="form-control" type="number" name="data[ordering]" value="{{$d->ordering}}" placeholder="Enter order of column">
				  </div>
				  <div class="form-group">
            		<label>Is required</label> 
            		<select class="form-control" name="data[is_required]">
              			<option value="1" @if($d->is_required) selected @endif>Yes</option>
              			<option value="0" @if(!$d->is_required) selected @endif>No</option>
            		</select>
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