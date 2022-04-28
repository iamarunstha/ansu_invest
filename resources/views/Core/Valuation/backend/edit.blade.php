@extends('backend.main')

@section('content')


<h1 class="h3 mb-4 text-gray-800">Valuation of {{$sector->name}}</h1>


<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
@include('Core.Valuation.backend.modal.create-heading', ["sector" => $sector])

<div class="table-responsive">
		<form method="post">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>SN</th>
					<th>Heading</th>
					<th>Ordering</th>
					<th>Show in graph</th>
					<th>Show in summary</th>
					<th>Bold</th>

					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as  $index => $d)
				<tr>
					<td>
						{{ $index + 1 }}
					</td>
					<td><input name="data[{{$d->id}}][heading]" value="{{ $d->heading }}"></td>
					<td><input name="data[{{$d->id}}][ordering]" value="{{ $d->ordering }}"></td>
					<td>
						<select name="data[{{$d->id}}][in_graph]">
							<option value="yes" @if($d->in_graph == 'yes') selected @endif>Yes</option>
							<option value="no" @if($d->in_graph != 'yes') selected @endif>No</option>
						</select>
					</td>

					<td><select name="data[{{$d->id}}][show_in_summary]">
							<option value="yes" @if($d->show_in_summary == 'yes') selected @endif>Yes</option>
							<option value="no" @if($d->show_in_summary != 'yes') selected @endif>No</option>
						</select></td>
					<td>
						<select name="data[{{$d->id}}][style]">
							<option value="bold" @if($d->style == 'bold') selected @endif>Yes</option>
							<option value="" @if($d->style != 'bold') selected @endif>No</option>
						</select>
					</td>
					<td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>	
									
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-valuation-headings-get', $sector->id) }}" class="btn btn-info">Cancel</a>
		{{csrf_field()}}
	</form>
</div>

@foreach($data as $index => $d) 
	<form method="post" action="{{ route('admin-valuation-headings-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>

@endforeach
@stop
