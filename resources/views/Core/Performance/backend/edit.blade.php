@extends('backend.main')

@section('content')


<h1 class="h3 mb-4 text-gray-800">Operating Performance</h1>


<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
@include('Core.Performance.backend.modal.create-heading', ["sector" => $sector, 'headers' => $headers])

<div class="table-responsive">
		<form method="post">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>SN</th>
					<th>Heading</th>
					<th>Under</th>
					<th>Ordering</th>
					

					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as  $index => $d)
				<tr>
					<td>
						{{ $index + 1 }}
						<input type="hidden" name="data[{{$d->id}}][show_in_summary]" value="{{ $d->show_in_summary }}">
						<input type="hidden" name="data[{{$d->id}}][style]" value="{{ $d->style }}">
						<input type="hidden" name="data[{{$d->id}}][in_graph]" value="{{ $d->in_graph }}">
					</td>
					<td><input name="data[{{$d->id}}][heading]" value="{{ $d->heading }}"></td>
					<td>
						<select name="data[{{$d->id}}][parent_id]">
							<option value="">-- Select --</option>
							@foreach($headers as $h)
								<option value="{{ $h->id }}" @if($h->id == $d->parent_id) selected @endif>{{ $h->heading }}</option>
							@endforeach
						</select>
					</td>
					<td><input name="data[{{$d->id}}][ordering]" value="{{ $d->ordering }}"></td>

					<!-- <td><select name="data[{{$d->id}}][show_in_summary]">
							<option value="yes" @if($d->show_in_summary == 'yes') selected @endif>Yes</option>
							<option value="no" @if($d->show_in_summary != 'yes') selected @endif>No</option>
						</select></td>
					<td>
						<select name="data[{{$d->id}}][style]">
							<option value="bold" @if($d->style == 'bold') selected @endif>Yes</option>
							<option value="" @if($d->style != 'bold') selected @endif>No</option>
						</select>
					</td> -->
					<td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>	
									
					</td>
				</tr>
				@foreach($d->subheadings as $_index => $_d)
				<tr>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;{{ $index + 1 }} - {{ $_index + 1 }}
						<input type="hidden" name="data[{{$_d->id}}][show_in_summary]" value="{{ $_d->show_in_summary }}">
						<input type="hidden" name="data[{{$_d->id}}][style]" value="{{ $_d->style }}">
						<input type="hidden" name="data[{{$_d->id}}][in_graph]" value="{{ $_d->in_graph }}">
					</td>
					<td><input name="data[{{$_d->id}}][heading]" value="{{ $_d->heading }}"></td>
					<td>
						<select name="data[{{$_d->id}}][parent_id]">
							<option value="">-- Select --</option>
							@foreach($headers as $h)
								<option value="{{ $h->id }}" @if($h->id == $_d->parent_id) selected @endif>{{ $h->heading }}</option>
							@endforeach
						</select>
					</td>
					<td><input name="data[{{$_d->id}}][ordering]" value="{{ $_d->ordering }}"></td>

					<!-- <td><select name="data[{{$d->id}}][show_in_summary]">
							<option value="yes" @if($d->show_in_summary == 'yes') selected @endif>Yes</option>
							<option value="no" @if($d->show_in_summary != 'yes') selected @endif>No</option>
						</select></td>
					<td>
						<select name="data[{{$d->id}}][style]">
							<option value="bold" @if($d->style == 'bold') selected @endif>Yes</option>
							<option value="" @if($d->style != 'bold') selected @endif>No</option>
						</select>
					</td> -->
					<td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $_d->id }}">Delete</a>	
									
					</td>
				</tr>
				@endforeach

				@endforeach
			</tbody>
		</table>
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-performance-headings-get', $sector->id) }}" class="btn btn-info">Cancel</a>
		{{csrf_field()}}
	</form>
</div>

@foreach($data as $index => $d) 
	<form method="post" action="{{ route('admin-performance-headings-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>
	@foreach($d->subheadings as $_index => $_d)
		<form method="post" action="{{ route('admin-performance-headings-delete-post', $_d->id) }}" id="delete-{{ $_d->id }}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>
	@endforeach
@endforeach
@stop
