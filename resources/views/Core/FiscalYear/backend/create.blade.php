@extends('backend.main')

@section('content')

	<form method="post" class="submit-once">
		<div class="form-group">
			<label>Fiscal Year</label>
			<input type="text" name="data[fiscal_year]" required value="{{ request()->old('data.fiscal_year') }}" class="form-control">
			@if($errors->has('fiscal_year'))
				<span class="error-block">
					@foreach($errors->get('fiscal_year') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Ordering</label>
			<input type="text" name="data[ordering]" value="{{ request()->old('data.ordering') }}" class="form-control">
			@if($errors->has('ordering'))
				<span class="error-block">
					@foreach($errors->get('ordering') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Historical</label>
			<select name="data[historical]" class="form-control" required>
				<option value="1">Yes</option>
				<option value="0" selected>No</option>
			</select>
			@if($errors->has('historical'))
				<span class="error-block">
					@foreach($errors->get('historical') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Create">
		<a href="{{ route('admin-fiscal-year-list-get') }}" class="btn btn-info">Cancel</a>
	</form>
	
@stop

@section('custom-js')
	
	
@stop