@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Balance Sheet Tabs</h1>
  	@include('Core.BalanceSheet.backend.modal.create-tabs', ['parent_tabs'=>$parent_tabs])
	<button class="btn btn-info" data-toggle="modal" data-target="#myTabsModal"> Create Tab</button>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<a href="{{route('admin-balance-sheet-sector-list-get')}}" class="btn btn-info">Go back</a>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Tab Name</th>
					<th>Ordering</th>
					<th>Historical</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->tab_name }}</td>
					<td>{{ $d->ordering }}</td>
					<td>@if($d->historical) yes @else no @endif</td>
					<td>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditTabModal{{$d->id}}">Edit</button>
						<a class="a_submit_button btn btn-danger" href="#" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($data as $index => $d)
		<div id="myEditTabModal{{$d->id}}" class="modal fade" role="dialog">
  			<div class="modal-dialog">

    			<!-- Modal content-->
    			<div class="modal-content">
    	  			<div class="modal-header">
    	    			<h3><strong>Edit Tab</strong></h3>
    	  			</div>
    	  			<div class="modal-body">
        				<form method="post" action="{{ route('admin-balance-sheet-tabs-update-post', $d->id) }}">
        					<div class="form-group">
	        					<label for="data[tab_name]">Tab Name</label>
	        					<input type="text" name="data[tab_name]" value="{{$d->tab_name}}">
	        				</div>
        					<div class="form-group">
	        					<label for="data[ordering]">Ordering</label>
	        					<input type="number" name="data[ordering]" value="{{$d->ordering}}">
	        				</div>
        					<div class="form-group">
	        					<label for="data[historical]">Historical</label>
	        					<select name="data[historical]">
	        						<option value="1" @if($d->historical) selected @endif>Yes</option>
	        						<option value="0"  @if(!$d->historical) selected @endif>No</option>
	        					</select>
	        				</div>

         					<div class="form-group">
            					<label for="data[parent_id]">Select Parent</label><br>
            					@foreach($parent_tabs as $tab)
              						<input type="radio" id="{{$tab->tab_name}}" name="data[parent_id]" value="{{$tab->id}}" @if($d->parent_id == $tab->id) checked @endif>
              						<label for="{{$tab->tab_name}}">{{$tab->tab_name}}</label><br>
            					@endforeach
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

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-balance-sheet-tabs-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-balance-sheet-tabs-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop