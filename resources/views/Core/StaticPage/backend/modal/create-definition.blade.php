<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Create new term</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-definition-create-post' , $page_id) }}">
        	<div class="form-group">
	        	<label for="data[term]">Term</label>
	        	<input type="text" name="data[term]" value="{{ request()->old('data.term') }}" placeholder="Enter term">
	        </div>
            <div class="form-group">
				<label for="data[definition]">Definition</label>
                <td><textarea rows="4" cols="49" name="data[definition]" required>{{ request()->old('data.definition') }}</textarea></td>
			</div>
        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
    </div>
  </div>
</div>
