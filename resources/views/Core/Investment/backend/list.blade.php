@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Investment/Existing Issues table</h1>

	<a href="{{ route('admin-investment-create-get', $tab->id) }}" class="btn btn-info btn-flat">Create</a>	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Symbol</th>
					<th>Company Name</th>
					@if($tab->tab_name == 'Right Share')
						<th>Ratio</th>
					@endif
					<th>Units</th>
					<th>Price</th>
					<th>Opening Date</th>
					<th>Closing Date</th>
					@if($tab->tab_name != 'Right Share')
						<th>Last Closing Date</th>
					@else
						<th>Book Closure Date</th>
					@endif
					<th>Issue Manager</th>
					<th>Status</th>
					@if($tab->tab_name == 'Right Share')
						<th>Eligibility Check</th>
					@endif
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($investments as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->symbol }}</td>
					<td>{{ $d->company_name }}</td>
					@if($tab->tab_name == 'Right Share')
						<td>{{ $d->ratio }}</td>
					@endif
					<td>{{ $d->units }}</td>
					<td>{{ $d->price }}</td>
					<td>{{ $d->opening_date }}</td>
					<td>{{ $d->closing_date }}</td>
					@if($tab->tab_name != 'Right Share')
						<td>Last Closing Date</td>
					@else
						<td>Book Closure Date</td>
					@endif
					<td>{{ $d->issue_manager }}</td>
					<td>{{ $d->status }}</td>
					@if($tab->tab_name == 'Right Share')
						<td>{{ $d->eligibility_check }}</td>
					@endif
					<td>
						<a href="{{ route('admin-investment-edit-get', $d->id) }}" class="btn btn-info btn-flat">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($investments as $index => $d) 
		<form method="post" action="{{route('admin-investment-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{route('admin-investment-delete-multiple-post')}}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop