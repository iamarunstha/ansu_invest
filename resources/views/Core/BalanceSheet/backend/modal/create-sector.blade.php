<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new Sector</strong></h3>
      </div>
      <div class="modal-body">
        <form class="submit-once" method="post" action="{{ route('admin-balance-sheet-sector-create-post') }}">
        	<div class="form-group">
	        	<label for="data[name]">Sector Name</label>
	        	<input type="text" name="data[name]" value="{{ request()->old('data.summary') }}" placeholder="Enter new Sector">
	        </div>

	        <input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
       </div>
    </div>
   </div>
</div>