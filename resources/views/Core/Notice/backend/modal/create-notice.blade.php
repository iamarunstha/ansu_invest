<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new Notice</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-notice-create-post') }}">
        	<div class="form-group">
	        	<label>Name of notice</label>
	        	<input type="text" name="data[name]" value="{{ request()->old('data.summary') }}" placeholder="Enter a name">
	        </div>
	       	<div class="form-group">
	        	<label>Date Published</label>
	        	<input type="text" name="data[notice_date]" placeholder="Enter date of publication" class="form-control datetime">
	        </div>	
	        <div class="form-group">
	        	<label>Description</label>
	        	<textarea style="min-width:'80%'" name="data[description]"></textarea>
	        </div>		

					<div class="form-group">
						<label>Select Company</label>
						<input type="text" class="company-name"/>
						<div class="row">
							@foreach($companies as $c)
							<div class="col-md-4 list-of-companies" company_name="{{$c->company_name}}" style="display: none;">
								<input type="checkbox" name="data[company_ids][]" value="{{ $c->id }}"/>{{ $c->company_name }}
							</div>
							@endforeach
						</div>	
					</div>

        	{{ csrf_field() }}
        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        </form>
      </div>
      
    </div>

  </div>
</div>
