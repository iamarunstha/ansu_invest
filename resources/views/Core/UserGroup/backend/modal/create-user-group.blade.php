<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new User Group</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-user-groups-create-post') }}">
        	<div class="form-group">
	        	<label>Group Name</label>
	        	<input type="text" name="data[group_name]" value="{{ request()->old('data.group_name') }}" placeholder="Enter new group name" required>
	        </div>
        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
        	{{csrf_field()}}
        </form>
      </div>
    </div>
  </div>
</div>
