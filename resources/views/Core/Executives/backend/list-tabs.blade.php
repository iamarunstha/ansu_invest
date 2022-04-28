@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Executives tabs</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.Executives.backend.modal.create-tab')

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Tab Name</th>
					<th>Ordering</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($tabs as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{$d->tab_name}}</td>
					<td>{{$d->ordering}}</td>
					<td>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditModal-{{$d->id}}"> Edit </button>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($tabs as $index => $d) 
		<form method="post" action="{{ route('admin-executives-tab-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	@foreach($tabs as $index => $d) 
		<div id="myEditModal-{{$d->id}}" class="modal fade" role="dialog">
  			<div class="modal-dialog">

    			<!-- Modal content-->
    			<div class="modal-content">
      				<div class="modal-header">
        				<h3><strong>Add new tab</strong></h3>
      				</div>
      				<div class="modal-body">
        				<form method="post" action="{{ route('admin-executives-tab-edit-post', $d->id) }}">
        					<div class="form-group">
	        					<label for="data[tab_name]">Tab Name</label>
	        					<input type="text" name="data[tab_name]" value="{{$d->tab_name}}">
	        				</div>

							<div class="form-group">
								<label for="data[ordering]">Ordering</label> 
								<input type="number" name="data[ordering]" value="{{$d->ordering}}">
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