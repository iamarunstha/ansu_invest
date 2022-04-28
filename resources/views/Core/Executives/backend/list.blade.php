@extends('backend.main')

@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Executives List of {{$company->company_name}}</h1>

	<a href="{{route('admin-executives-create-get', $company->id)}}" class=" btn btn-info" related-id="">Create</a>
	<a href="{{route('admin-executives-delete-multiple-post')}}" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<a href="{{route('admin-executives-column-list-get')}}" class=" btn btn-info" related-id="">Go to Columns</a>
	<a href="{{route('admin-executives-tab-list-get')}}" class=" btn btn-info" related-id="">Go to Tabs</a>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					@foreach($columns as $c)
						<th>{{$c->column_name}}</th>
					@endforeach
					<th>Tab</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($executives as $index => $executive)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $index }}">{{ $index}}</td>
					@foreach($executive as $id=>$e)
						@foreach($columns as $c)
							@if($e->column_id == $c->id)
								@if($c->type == 'varchar')
									<td>{{$e->value_string}}</td>
									<?php continue; ?>
								@elseif($c->type == 'integer')
									<td>{{$e->value_int}}</td>
									<?php continue; ?>
								@elseif($c->type == 'float')
									<td>{{$e->value_float}}</td>
									<?php continue; ?>
								@elseif($c->type == 'text')
									<td>{{$e->value_text}}</td>
									<?php continue; ?>
								@endif
							@endif
						@endforeach
					@endforeach
					<td>@if($executive[0]->tab){{$executive[0]->tab->tab_name}}@endif</td>
					<td>
						<a href="{{route('admin-executives-edit-get', $executive[0]->row_id)}}" class=" btn btn-info" related-id="">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $executive[0]->row_id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($executives as $index => $d) 
		<form method="post" action="{{ route('admin-executives-delete-post', $d[0]->row_id) }}" id="delete-{{ $d[0]->row_id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-executives-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop