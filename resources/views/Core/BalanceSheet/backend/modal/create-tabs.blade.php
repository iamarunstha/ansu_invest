<div id="myTabsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new Tab</strong></h3>
      </div>
      <div class="modal-body">
        <form class="submit-once" method="post" action="{{ route('admin-balance-sheet-tabs-create-post')}}">
        	<div class="form-group">
	        	<label for="data[tab_name]"><strong>Tab Name</strong></label>
	        	<input type="text" name="data[tab_name]" value="{{ request()->old('data.summary') }}" placeholder="Enter new tab name" required>
	        </div>
          <div class="form-group">
            <label for="data[ordering]"><strong>Ordering</strong></label>
            <input type="number" name="data[ordering]" value="{{ request()->old('data.summary') }}" placeholder="Enter order">
          </div>
          
          <div class="form-group"> 
            <label for="data[historical]"><strong>Historical</strong></label>
            <select name="data[historical]">
              <option value="1">Yes</option>
              <option value="0" selected>No</option>
            </select>
          </div>

          <div class="form-group"> 
            <label for="data[is_parent]"><strong>Is parent</strong></label>
            <select name="data[is_parent]">
              <option value="yes">Yes</option>
              <option value="no" selected>No</option>
            </select>
          </div>

          <div class="form-group">
            <label for="data[parent_id]"><strong>Select Parent</strong></label><br>
            @foreach($parent_tabs as $tab)
              <input type="radio" id="{{$tab->tab_name}}" name="data[parent_id]" value="{{$tab->id}}">
              <label for="{{$tab->tab_name}}">{{$tab->tab_name}}</label><br>
            @endforeach
          </div>
  
	        <input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
       </div>
    </div>
   </div>
</div>