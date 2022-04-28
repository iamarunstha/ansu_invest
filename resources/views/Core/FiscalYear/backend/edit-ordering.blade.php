@extends('backend.main')

@section('content')

<div class="table-responsive">
	<form method="post" action="{{ route('admin-fiscal-year-edit-ordering-post') }}">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>SN</th>
					<th>Fiscal Year</th>
					<th>Ordering</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)

				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $d->fiscal_year }}</td>
					<td><input type="number" name="data[{{$d->id}}]" value="{{ $d->ordering }}"></td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-fiscal-year-list-get') }}" class="btn btn-info">Cancel</a>
	</form>
</div>

@stop
