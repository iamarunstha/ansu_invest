<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add new proposed dividend</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-proposed-dividend-create-post') }}">
        	<div class="form-group">
	        	<label>Symbol</label>
	        	<input type="text" name="data[symbol]" value="{{ request()->old('data.symbol') }}" placeholder="Enter new symbol">
	        </div>
	        <div class="form-group">
	        	<label>Company name</label>
	        	<input type="text" name="data[company_name]" value="{{ request()->old('data.company_name') }}" placeholder="Enter company name">
	        </div>

	        <div class="form-group">
	        	<label for="data[bonus]">Bonus %</label> 
				<input type="integer" name="data[bonus]" placeholder="Enter bonus %" value="{{ request()->old('data.bonus') }}" step="0.01" max="100" min="0">
			</div>

			<div class="form-group">
				<label for="data[cash]">Cash %</label> 
				<input type="integer" name="data[cash]" placeholder="Enter cash %" value="{{ request()->old('data.cash') }}" step="0.01" max="100" min="0">
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
				<label>Distribution Date</label>
				<input type="text" name="data[distribution_date]" class="form-control date" value=@if(request()->old('data.distribution_date')) "{{ request()->old('data.distribution_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
			</div>

			<div class="form-group">
				<label>Book Closure Date</label>
				<input type="text" name="data[book_closure_date]" class="form-control date" required value=@if(request()->old('data.book_closure_date')) "{{ request()->old('data.book_closure_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
			</div>

        	<input type="submit" class="btn btn-success" value="Create">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>