@extends('backend.main')

@section('content')
	
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Edit Proposed Dividend</h1>

  	<form method="post" action="{{route('admin-proposed-dividend-edit-post', $dividend->id)}}">
        	<div class="form-group">
	        	<label>Symbol</label>
	        	<input type="text" name="data[symbol]" value="{{ $dividend->symbol }}" placeholder="Enter new symbol">
	        </div>
	        <div class="form-group">
	        	<label>Company name</label>
	        	<input type="text" name="data[company_name]" value="{{ $dividend->company_name }}" placeholder="Enter company name">
	        </div>

	        <div class="form-group">
	        	<label for="data[bonus]">Bonus %</label> 
				<input type="integer" name="data[bonus]" placeholder="Enter bonus %" value="{{ $dividend->bonus }}" step="0.01" max="100" min="0">
			</div>

			<div class="form-group">
				<label for="data[cash]">Cash %</label> 
				<input type="integer" name="data[cash]" placeholder="Enter cash %" value="{{ $dividend->cash }}" step="0.01" max="100" min="0">
			</div>

			<div class="form-group">
				<label for="data[fiscal_year_id]">Fiscal Year</label> 
				<select name="data[fiscal_year_id]">
					@foreach($years as $year)
						<option value="{{$year->id}}">{{$year->fiscal_year}}
							@if($year->id == $dividend->fiscal_year_id)
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
							@if($sector->id == $dividend->sector_id)
								selected
							@endif
						</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label>Distribution Date</label>
				<input type="text" name="data[distribution_date]" class="form-control date" value="{{ $dividend->distribution_date }}">
			</div>

			<div class="form-group">
				<label>Book Closure Date</label>
				<input type="text" name="data[book_closure_date]" class="form-control date" required value="{{ $dividend->book_closure_date }}">
			</div>

  		{{csrf_field()}}

  		<input type="submit" class="btn btn-success" value="Update">
       	<a href="{{ route('admin-proposed-dividend-list-get') }}" class="btn btn-info">Cancel</a>
  	</form>
@stop