@extends('backend.main')


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Ownership table</h1>

	<a href="{{ route('admin-ownership-create-get', [$company->id, $tab_id]) }}" class="btn btn-info btn-flat">Create</a>	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Name</th>
					@foreach($headings as $h)
						<th>{{ $h->display_name }}</th>
					@endforeach
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($ownerships as $index=>$ownership)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{$ownership['name']}}">{{$index}}</td>
					<td>{{$ownership['name']}}</td>
					@foreach($headings as $h)
						<td>
							@if(isset($ownership[$h->column_name]))
								{{$ownership[$h->column_name]}}
							@else
						 		- 
							@endif
						</td>
					@endforeach
					<td>
						<a class="btn btn-info" href="{{route('admin-ownership-edit-get',[$company->id,$tab_id,$ownership['name_id']])}}">Edit</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{$ownership['name_id']}}">Delete</a>
					</td>
				</tr>					  
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($ownerships as $index => $d) 
		<form method="post" action="{{route('admin-ownership-delete-post', [$company->id,$tab_id,$d['name_id']])}}" id="delete-{{$d['name_id']}}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{route('admin-ownership-delete-multiple-post')}}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop