<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add New Links</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-footer-links-add-post') }}">
        	<div class="form-group">
	        	<label for="data[link_text]">Link Text</label>
	        	<input type="text" name="data[link_text]" value="{{ request()->old('data.link_text') }}" placeholder="Enter new Link Text">
	        </div>
			<div class="form-group">
				<label for="data[link_order]">Ordering</label> 
				<input type="number" step="1" min="0" name="data[link_order]" placeholder="Enter link order" value="{{ request()->old('data.link_order') }}">
			</div>
            <div class="form-group">
				<label for="data[link_url]">Link URL</label> 
				<input type="text" name="data[link_url]" placeholder="Enter link URL" value="{{ request()->old('data.link_url') }}">
			</div>
        	<input type="submit" class="btn btn-success" value="Add">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>
