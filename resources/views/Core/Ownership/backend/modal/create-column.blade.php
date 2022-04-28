<div id="myCreateColumnModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new column</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-ownership-columns-create-post')}}">  
        	<div class="form-group">
	        	<label for="data[display_name]">Column Name (Display Name)</label>
	        	<input class="form-control" type="text" name="data[display_name]" value="{{ request()->old('data.summary') }}" placeholder="Enter new column name" required>
	        </div>
          <div class="form-group">
            <label for="data[column_type]">Column Type</label>
            <select class="form-control" name="data[column_type]">
              <option value="decimal">Numeric</option>
              <option value="string">Text</option>
              <option value="date">Date</option>
            </select>
          </div>
	        <div class="form-group">
	        	<label for="data[ordering]">Ordering</label> 
	        	<input class="form-control" type="number" name="data[ordering]" value="{{ request()->old('data.summary') }}" placeholder="Enter order of column">
			    </div>
          <div class="form-group">
            <label>Is required</label> 
            <select class="form-control" name="data[is_required]">
              <option value="1">Yes</option>
              <option value="0" selected>No</option>
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