@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<form method="post" action="{{route('admin-investment-edit-post', $investment->id)}}">
		<div class="form-group">
			<label>Symbol</label>
			<input type="text" name="data[symbol]" required value="{{$investment->symbol}}" class="form-control">
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
			<input type="text" name="data[company_name]" required value="{{$investment->company_name}}" class="form-control">
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
				<input type="text" name="data[ratio]" required value="{{$investment->ratio}}" class="form-control">
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
			<input type="number" name="data[units]" required value="{{$investment->units}}" class="form-control">
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
			<input type="number" name="data[price]" value="{{$investment->price}}" class="form-control">
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
			<input type="text" name="data[opening_date]" class="form-control date" required value="{{$investment->opening_date}}">
			@if($errors->has('opening_date'))
				<span class="error-block">
					@foreach($errors->get('opening_date') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Closing Date</label>
			<input type="text" name="data[closing_date]" class="form-control date" required value="{{$investment->closing_date}}">
			@if($errors->has('opening_date'))
				<span class="error-block">
					@foreach($errors->get('opening_date') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		@if($tab->tab_name != 'Right Share')
			<div class="form-group">
				<label>Last Closing Date</label>
				<input type="text" name="data[last_closing_date]" class="form-control date" required value="{{$investment->last_closing_date}}">
				@if($errors->has('last_closing_date'))
					<span class="error-block">
						@foreach($errors->get('last_closing_date') as $e)
							<p>{{ $e }}</p>
						@endforeach
					</span>
				@endif
			</div>
		@else
			<div class="form-group">
				<label>Book Closure Date</label>
				<input type="text" name="data[book_closure_date]" class="form-control date" required value="{{$investment->book_closure_date}}">
				@if($errors->has('book_closure_date'))
					<span class="error-block">
						@foreach($errors->get('book_closure_date') as $e)
							<p>{{ $e }}</p>
						@endforeach
					</span>
				@endif
			</div>
		@endif

		<div class="form-group">
			<label>Issue Manager</label>
			<input type="text" name="data[issue_manager]" class="form-control" required value="{{$investment->issue_manager}}">
			@if($errors->has('issue_manager'))
				<span class="error-block">
					@foreach($errors->get('issue_manager') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Status</label>
			<select name="data[status]" class="form-control" required>
				<option value="">-- Select --</option>
				<option value="open" @if($investment->status == 'open') selected @endif>Open</option>
				<option value="closed" @if($investment->status == 'closed') selected @endif>Closed</option>
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
			<label>Change Link</label>
			<textarea name="data[view]" class="form-control">{{$investment->view}}</textarea>
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
					<option value="unavailable" @if($investment->eligibility_check == 'unavailable') selected @endif>Unavailable</option>
					<option value="closed" @if($investment->eligibility_check == 'closed') selected @endif>Closed</option>
					<option value="open" @if($investment->eligibility_check == 'open') selected @endif>Open</option>
				</select>
			</div>
		@endif

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-investment-list-get', $tab->id) }}" class="btn btn-info">Cancel</a>
	</form>
@stop
