<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new tab</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-ownership-tabs-create-post')}}">
        	<div class="form-group">
	        	<label for="data[tab_name]">Tab Name</label>
	        	<input type="text" name="data[tab_name]" value="{{ request()->old('data.summary') }}" placeholder="Enter new tab name">
	        </div>
	        <div class="form-group">
	        	<label for="data[ordering]">Ordering</label> 
	        	<input type="number" name="data[ordering]" value="{{ request()->old('data.summary') }}" placeholder="Enter order of tab">
			</div>

        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>