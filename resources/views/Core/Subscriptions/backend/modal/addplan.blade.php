<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add New Subscription Plan</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-subscripton-plans-add-post') }}">
        	<div class="form-group">
	        	<label for=>Plan Name</label>
	        	<input type="text" name="data[plan_name]" value="{{ request()->old('data.plan_name') }}" required>
	        </div>
			<div class="form-group">
				<label>Duration</label> 
				<input type="number" step="1" min="0" name="data[duration]" value="{{ request()->old('data.duration') }}" required>
				<select name="data[duration_unit]"> 
                    <option value="day">Day</option>
                    <option value="week">Week</option>
                    <option value="month" selected>Month</option>
                    <option value="year">Year</option>
                </select>
			</div>
            <div class="form-group">
				<label>Price</label> 
				<input type="number" step="0.01" name="data[price]" value="{{ request()->old('data.price') }}" required>
			</div>
            <div class="form-group">
				<label>Ordering</label> 
				<input type="number" step="1" name="data[ordering]" value="{{ request()->old('data.ordering') }}" required>
			</div>
            <div class="form-group">
				<label>Is It An Offer?</label> 
                <select name="data[is_offer]"> 
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>
        	<input type="submit" class="btn btn-success" value="Add">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>
