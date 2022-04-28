<div id="myCreateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Create New Admin</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-create-post') }}">
        	<div class="form-group">
	        	<label>Admin Name</label><br>
	        	<input type="text" name="data[name]" value="{{ request()->old('data.name') }}"  required>
	        </div>
          <div class="form-group">
            <label>Admin Email</label><br>
            <input type="text" name="data[email]" value="{{ request()->old('data.email') }}"  required>
          </div>
          <div class="form-group">
            <label>Admin Password</label><br>
            <input type="password" name="data[password]" value="{{ request()->old('data.password') }}"  required>
            <span>Must contain a digit, a special character [@,$,!,%,*,#,?,&,^] and atleast six characters.</span>
          </div>
        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
        	{{csrf_field()}}
        </form>
      </div>
    </div>
  </div>
</div>
