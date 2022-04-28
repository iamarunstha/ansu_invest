@extends('backend.main')

@section('content')


<h1 class="h3 mb-4 text-gray-800">{{$tab->tab_name}} of {{$sector->name}}</h1>

@include('Core.BalanceSheet.backend.modal.create-heading', ["sector" => $sector, "tab" => $tab])
<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>



<div class="table-responsive">
		<form method="post">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>SN</th>
					<th>Heading</th>
					<th>Ordering</th>
					<th>Show in summary</th>
					<th>In Graph</th>
					<th>Style</th>
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
					<td><select name="data[{{$d->id}}][show_in_summary]">
							<option value="yes" @if($d->show_in_summary == 'yes') selected @endif>Yes</option>
							<option value="no" @if($d->show_in_summary != 'yes') selected @endif>No</option>
						</select></td>
					<td>
						<select name="data[{{$d->id}}][in_graph]">
							<option value="yes" @if($d->in_graph == 'yes') selected @endif>Yes</option>
							<option value="no" @if($d->in_graph != 'yes') selected @endif>No</option>
						</select>
					</td>
					<td>
						<select name="data[{{$d->id}}][style]">
							<option value="">Not Set</option>
							<option value="statement_table_bolder" @if($d->style == 'statement_table_bolder') selected @endif>Main Heading</option>
							<option value="statement_table_bold" @if($d->style == 'statement_table_bold') selected @endif>Sub Heading</option>
							<option value="statement_table_normal" @if($d->style == 'statement_table_normal') selected @endif>General Heading</option>
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
		<a href="{{ route('admin-balance-sheet-sector-list-get') }}" class="btn btn-info">Cancel</a>
		{{csrf_field()}}
	</form>
</div>

@foreach($data as $index => $d) 
	<form method="post" action="{{ route('admin-balance-sheet-headings-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>

@endforeach
@stop
