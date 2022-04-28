<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new heading</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-balance-sheet-headings-create-post', [$sector->id, $tab->id]) }}">
        	<div class="form-group">
	        	<label for="data[heading]">Heading</label>
	        	<input type="text" name="data[heading]" value="{{ request()->old('data.summary') }}" placeholder="Enter new heading">
	        </div>
	        <div class="form-group">
	        	<label for="data[show_in_summary]">Show In Summary</label> 
	        	<select name="data[show_in_summary]" placeholder="Show in Summary">
					<option value="yes">Yes</option>
					<option value="no" selected>No</option>
				</select>
			</div>
			<div class="form-group">
	        	<label for="data[in_graph]">In Graph</label> 
	        	<select name="data[in_graph]" placeholder="Show in Graph">
					<option value="yes">Yes</option>
					<option value="no" selected>No</option>
				</select>
			</div>

			<div class="form-group">
				<label for="data[style]">Heading Type</label> 
				<select name="data[style]">
					<option value="statement_table_bolder">Main Heading</option>
					<option value="statement_table_bold">Sub Heading</option>
					<option value="statement_table_normal" selected>General Heading</option>
				</select>
			</div>

			<div class="form-group">
				<label for="data[ordering]">Ordering</label> 

				<input type="number" name="data[ordering]" placeholder="Enter ordering" value="{{ request()->old('data.summary') }}">
			</div>

        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>