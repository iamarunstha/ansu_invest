@extends('backend.main')

@section('content')
	<h1>Create executive</h1>
	<form method="post">
		@foreach ($columns as $c)
		<div class="form-group">
			<label>{{ucwords($c->column_name)}}</label>
			@if ($c->type == 'varchar')
				<input type="text" name="data[{{$c->column_name}}]" value="{{ request()->old('data.'.$c->column_name) }}" class="form-control">
			@elseif($c->type=='integer')
				<input type="number" name="data[{{$c->column_name}}]" value="{{ request()->old('data.'.$c->column_name) }}" class="form-control">
			@elseif($c->type=='float')
				<input type="number" step="0.01" name="data[{{$c->column_name}}]" value="{{ request()->old('data.'.$c->column_name) }}" class="form-control">
			@elseif($c->type=='text')
				<textarea name="data[{{$c->column_name}}]" class="form-control">{{ request()->old('data.'.$c->column_name) }}</textarea>
			@endif
			@if($errors->has('$c->column_name'))
				<span class="error-block">
					@foreach($errors->get('$c->column_name') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>
		@endforeach

		<div class="form-group">
			<label>Tab</label>
			<select name="data[tab_id]" class="form-control">
				<option value="">----</option>
				@foreach($tabs as $tab)
					<option value={{$tab->id}}>{{$tab->tab_name}}</option>
				@endforeach
			</select>
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Create">
		<a href="{{ route('admin-executives-list-get', $company->id) }}" class="btn btn-info">Cancel</a>
	</form>

@stop
