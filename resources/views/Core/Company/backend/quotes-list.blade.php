@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Quote Headings</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<!-- Nav tabs -->
	<ul class="nav nav-tabs">
  		@foreach($tabs as $index=>$tab)
  			<li class="nav-item">
    			<a class="nav-link @if(url()->current() == route('admin-company-quote-headings-list-get', $tab->id)) active @endif" href="{{route('admin-company-quote-headings-list-get', $tab->id)}}">{{$tab->tab_name}}</a>
  			</li>
  		@endforeach
  	</ul>

  	@if($selected_tab)
		<div class="table-responsive">
			<table class="table table-bordered table-striped" id="multiple-checkbox">
				<thead>
					<tr>
						<th>SN</th>
						<th>Name</th>
						<th>Ordering</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($quotes as $index=>$d)				
					<tr>
						<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
						<td>{{ $d->display_name }}</td>
						<td>{{ $d->ordering }}</td>
						<td>
                    		<button class="btn btn-info" data-toggle="modal" data-target="#myEditModal{{$d->id}}"> Edit</button>
                    		<a class="btn btn-danger a_submit_button" href="#" related-id="delete-{{$d->id}}">Delete</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	  


		@foreach($quotes as $index => $d)
			<form method="post" action="{{ route('admin-company-quote-headings-delete-post', $d->id) }}" id="delete-{{$d->id}}" class="prabal-confirm">
				{{ csrf_field() }}
			</form>
		@endforeach

		@foreach($quotes as $index => $d)	
			<div id="myEditModal{{$d->id}}" class="modal fade" role="dialog">
  			<div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<h3><strong>Edit heading</strong></h3>
      			</div>
      			<div class="modal-body">
        			<form method="post" action="{{ route('admin-company-quote-headings-edit-post', $d->id) }}">
        				<div class="form-group">
	        				<label for="data[display_name]">Heading</label>
	        				<input type="text" name="data[display_name]" value="{{$d->display_name}}" placeholder="Enter new heading">
	        			</div>

						<div class="form-group">
							<label for="data[ordering]">Ordering</label> 
							<input type="number" name="data[ordering]" placeholder="Enter ordering" value="{{$d->ordering}}">
						</div>

			        	<div class="form-group">
	        				<label for="data[tab_id]">Select Tab</label> 
	        				<select name="data[tab_id]">
	        				@foreach($tabs as $tab)
								<option value="{{$tab->id}}" @if($tab->id == $d->tab_id) selected @endif>{{$tab->tab_name}}</option>
							@endforeach
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

	<form id="multiple-delete" action="{{ route('admin-company-quote-headings-multiple-delete-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>

	<div id="myModal" class="modal fade" role="dialog">
  		<div class="modal-dialog">

    	<!-- Modal content-->
    	<div class="modal-content">
      		<div class="modal-header">
        		<h3><strong>Add new heading</strong></h3>
      		</div>
      		<div class="modal-body">
        		<form method="post" action="{{ route('admin-company-quote-headings-create-post') }}">
        			<div class="form-group">
	        			<label for="data[display_name]">Heading</label>
	        			<input type="text" name="data[display_name]" value="{{ request()->old('data.summary') }}" placeholder="Enter new heading">
	        		</div>

					<div class="form-group">
						<label for="data[ordering]">Ordering</label> 
						<input type="number" name="data[ordering]" placeholder="Enter ordering" value="{{ request()->old('data.summary') }}">
					</div>

			        <div class="form-group">
	        			<label for="data[tab_id]">Select Tab</label> 
	        			<select name="data[tab_id]">
	        				@foreach($tabs as $tab)
								<option value="{{$tab->id}}">{{$tab->tab_name}}</option>
							@endforeach
						</select>
					</div>

        			<input type="submit" class="btn btn-success" value="Create">
        			<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        			{{csrf_field()}}
        		</form>
      		</div>
    	</div>
  		</div>
	</div>
	@endif
@stop
