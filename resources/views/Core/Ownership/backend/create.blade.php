@extends('backend.main')

@section('content')

	<form method="post" action="{{route('admin-ownership-create-post', [$company_id, $tab_id])}}">
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="data[name]" required value="{{ request()->old('data.name') }}" class="form-control">
			@if($errors->has('name'))
				<span class="error-block">
					@foreach($errors->get('name') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		@foreach($columns as $col)
		<div class="form-group">
			<label>{{$col->display_name}}</label>
			@if($col->column_type=='decimal')
				<input type="number" name="data[{{$col->column_name}}]" @if($col->is_required) required @endif step="0.01" value="{{ request()->old('data.$col->column_name') }}" class="form-control">
			@elseif($col->column_type=='string')
				<input class="form-control" type="text" name="data[{{$col->column_name}}]" @if($col->is_required) required @endif value="{{ request()->old('data.$col->column_name') }}">
			@elseif($col->column_type=='date')
				<input class="form-control" type="date" name="data[{{$col->column_name}}]" @if($col->is_required) required @endif value="{{ request()->old('data.$col->column_name') }}">
			@endif
		</div>
		@endforeach


		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Create">
		<a href="{{ route('admin-ownership-list-get', [$company_id, $tab_id]) }}" class="btn btn-info">Cancel</a>
	</form>
@stop
