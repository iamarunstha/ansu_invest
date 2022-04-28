@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<form method="post">
		<div class="form-group">
			<label>Symbol</label>
			<input type="text" name="data[symbol]" required value="{{ request()->old('data.symbol') }}" class="form-control">
			@if($errors->has('symbol'))
				<span class="error-block">
					@foreach($errors->get('symbol') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Company Name</label>
			<input type="text" name="data[company_name]" required value="{{ request()->old('data.company_name') }}" class="form-control">
			@if($errors->has('company_name'))
				<span class="error-block">
					@foreach($errors->get('company_name') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		@if($tab->tab_name == 'Right Share')
			<div class="form-group">
				<label>Ratio</label>
				<input type="text" name="data[ratio]" required value="{{ request()->old('data.ratio') }}" class="form-control">
				@if($errors->has('ratio'))
					<span class="error-block">
						@foreach($errors->get('ratio') as $e)
							<p>{{ $e }}</p>
						@endforeach
					</span>
				@endif
			</div>
		@endif

		<div class="form-group">
			<label>Units</label>
			<input type="number" name="data[units]" required value="{{ request()->old('data.units') }}" class="form-control">
			@if($errors->has('units'))
				<span class="error-block">
					@foreach($errors->get('units') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Price</label>
			<input type="number" name="data[price]" required value="{{ request()->old('data.price') }}" class="form-control">
			@if($errors->has('price'))
				<span class="error-block">
					@foreach($errors->get('price') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>
		
		<div class="form-group">
			<label>Opening Date</label>
			<input type="text" name="data[opening_date]" class="form-control date" required value=@if(request()->old('data.opening_date')) "{{ request()->old('data.opening_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
		</div>

		<div class="form-group">
			<label>Closing Date</label>
			<input type="text" name="data[closing_date]" class="form-control date" required value=@if(request()->old('data.closing_date')) "{{ request()->old('data.closing_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
		</div>

		@if($tab->tab_name != 'Right Share')
			<div class="form-group">
				<label>Last Closing Date</label>
				<input type="text" name="data[last_closing_date]" class="form-control date" required value=@if(request()->old('data.last_closing_date')) "{{ request()->old('data.last_closing_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
			</div>
		@else
			<div class="form-group">
				<label>Book Closure Date</label>
				<input type="text" name="data[book_closure_date]" class="form-control date" required value=@if(request()->old('data.book_closure_date')) "{{ request()->old('data.book_closure_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
			</div>
		@endif

		<div class="form-group">
			<label>Issue Manager</label>
			<input type="text" name="data[issue_manager]" class="form-control" required value=@if(request()->old('data.issue_manager')) "{{ request()->old('data.issue_manager') }}" @endif>
		</div>

		<div class="form-group">
			<label>Status</label>
			<select name="data[status]" class="form-control" required>
				<option value="">-- Select --</option>
				<option value="open">Open</option>
				<option value="closed">Closed</option>
			</select>
			@if($errors->has('status'))
				<span class="error-block">
					@foreach($errors->get('status') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Paste Link</label>
			<textarea name="data[view]" class="form-control">{{request()->old('data.view')}}</textarea>
			@if($errors->has('view'))
				<span class="error-block">
					@foreach($errors->get('view') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>
		@if($tab->tab_name == 'Right Share')
			<div class="form-group">
				<label>Eligibility Check</label>
				<select name="data[eligibility_check]" class="form-control">
					<option value="">-- Select --</option>
					<option value="unavailable">Unavailable</option>
					<option value="closed">Closed</option>
					<option value="open">Open</option>
				</select>
			</div>
		@endif

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Create">
		<a href="{{ route('admin-investment-list-get', $tab->id) }}" class="btn btn-info">Cancel</a>
	</form>
@stop
