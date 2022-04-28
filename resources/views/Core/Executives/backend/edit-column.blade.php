@extends('backend.main')

@section('content')

	<form method="post" class="submit-once">
		<div class="form-group">
			<label>Column Name</label>
			<input type="text" name="data[column_name]" required value="{{$column->column_name}}" class="form-control">
			@if($errors->has('column_name'))
				<span class="error-block">
					@foreach($errors->get('column_name') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Ordering</label>
			<input type="text" name="data[ordering]" value="{{$column->ordering}}" class="form-control">
			@if($errors->has('ordering'))
				<span class="error-block">
					@foreach($errors->get('ordering') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Type</label>
			<select name="data[type]" class="form-control" required>
				<option value="integer" @if($column->type=='integer') selected @endif>Integer</option>
				<option value="varchar" @if($column->type=='varchar') selected @endif>String</option>
				<option value="text" @if($column->type=='text') selected @endif>Long Text</option>				
				<option value="float" @if($column->type=='float') selected @endif>Decimal</option>
			</select>
			@if($errors->has('type'))
				<span class="error-block">
					@foreach($errors->get('type') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-executives-column-list-get') }}" class="btn btn-info">Cancel</a>
	</form>
	
@stop