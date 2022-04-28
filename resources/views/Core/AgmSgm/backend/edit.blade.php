@extends('backend.main')

@section('content')
	
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Edit AGM SGM</h1>

  	<form method="post" action="{{route('admin-agm-sgm-edit-post', $agm->id)}}">
        	<div class="form-group">
	        	<label>Symbol</label>
	        	<input type="text" name="data[symbol]" value="{{ $agm->symbol }}" placeholder="Enter new symbol">
	        </div>
	        <div class="form-group">
	        	<label>Company name</label>
	        	<input type="text" name="data[company_name]" value="{{ $agm->company_name }}" placeholder="Enter company name">
	        </div>
	        <div class="form-group">
	        	<label for="data[agm]">AGM</label> 
				<input type="number" name="data[agm]" placeholder="Enter no of AGM" value="{{ $agm->agm }}" min="1">
			</div>

			<div class="form-group">
				<label for="data[venue]">Venue %</label> 
				<input type="text" name="data[venue]" placeholder="Enter venue" value="{{ $agm->venue }}">
			</div>

			<div class="form-group">
				<label for="data[time]">Time</label> 
				<input type="time" name="data[time]" placeholder="Enter AGM time" value="{{ $agm->time }}">
			</div>

			<div class="form-group">
				<label for="data[fiscal_year_id]">Fiscal Year</label> 
				<select name="data[fiscal_year_id]">
					@foreach($years as $year)
						<option value="{{$year->id}}">{{$year->fiscal_year}}
							@if($year->id == $agm->fiscal_year_id)
								selected
							@endif
						</option>
					@endforeach
				</select>
			</div>
			
			<div class="form-group">
				<label for="data[sector_id]">Sector</label> 
				<select name="data[sector_id]">
					@foreach($sectors as $sector)
						<option value="{{$sector->id}}">{{$sector->name}}
							@if($sector->id == $agm->sector_id)
								selected
							@endif
						</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label>AGM Date</label>
				<input type="text" name="data[agm_date]" class="form-control date" required value="{{ $agm->agm_date }}">
			</div>

			<div class="form-group">
				<label>Book Closure Date</label>
				<input type="text" name="data[book_closure_date]" class="form-control date" required value="{{ $agm->book_closure_date }}">
			</div>

			<div class="form-group">
				<label>Agenda</label>
				<input type="textbox" name="data[agenda]" class="form-control" required value="{{ $agm->agenda }}">
			</div>

  		{{csrf_field()}}

  		<input type="submit" class="btn btn-success" value="Update">
       	<a href="{{ route('admin-agm-sgm-list-get') }}" class="btn btn-info">Cancel</a>
  	</form>
@stop