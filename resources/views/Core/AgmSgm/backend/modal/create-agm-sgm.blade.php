<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new AGM-SGM</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-agm-sgm-create-post') }}">
        	<div class="form-group">
	        	<label>Symbol</label>
	        	<input type="text" name="data[symbol]" value="{{ request()->old('data.summary') }}" placeholder="Enter new symbol">
	        </div>
	        <div class="form-group">
	        	<label>Company name</label>
	        	<input type="text" name="data[company_name]" value="{{ request()->old('data.summary') }}" placeholder="Enter company name">
	        </div>
	        <div class="form-group">
	        	<label for="data[agm]">AGM</label> 
				<input type="number" name="data[agm]" placeholder="Enter no of AGM of company" value="{{ request()->old('data.summary') }}" min="1">
			</div>

			<div class="form-group">
				<label for="data[venue]">Venue</label> 
				<input type="text" name="data[venue]" placeholder="Enter AGM veneu" value="{{ request()->old('data.summary') }}">
			</div>

			<div class="form-group">
				<label for="data[time]">Time</label> 
				<input type="time" name="data[time]" placeholder="Enter AGM time" value=@if(request()->old('data.time')) "{{ request()->old('data.time') }}" @else "{{ \Carbon\Carbon::now()->format('H-m-s') }}" @endif>
			</div>

			<div class="form-group">
				<label for="data[fiscal_year_id]">Fiscal Year</label> 
				<select name="data[fiscal_year_id]">
					@foreach($years as $year)
						<option value="{{$year->id}}">{{$year->fiscal_year}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="form-group">
				<label for="data[sector_id]">Sector</label> 
				<select name="data[sector_id]">
					@foreach($sectors as $sector)
						<option value="{{$sector->id}}">{{$sector->name}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label>AGM Date</label>
				<input type="text" name="data[agm_date]" class="form-control date" required value=@if(request()->old('data.agm_date')) "{{ request()->old('data.agm_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
			</div>

			<div class="form-group">
				<label>Book Closure Date</label>
				<input type="text" name="data[book_closure_date]" class="form-control date" required value=@if(request()->old('data.book_closure_date')) "{{ request()->old('data.book_closure_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
			</div>

			<div class="form-group">
				<label>Agenda</label>
				<input type="textbox" name="data[agenda]" class="form-control" required value=@if(request()->old('data.agenda')) "{{ request()->old('data.agenda') }}"@endif>
			</div>			


        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>