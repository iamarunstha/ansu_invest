@extends('backend.main')

@section('content')
	<ul class="nav nav-tabs">
		@foreach(range('A', 'Z') as $a)
	  		<li class="nav-item">
    			<a class="nav-link @if($initial == $a) active @endif" href="{{route('admin-static-update-get', [$data->id, $a])}}">{{$a}}</a>
  			</li>
		@endforeach
	</ul>
	
	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create </button>
	@include('Core.StaticPage.backend.modal.create-definition', ['page_id' => $data->id])
	<a href="{{ route('admin-static-list-get') }}" class="btn btn-info">Go Back</a>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<form method="post" enctype="multipart/form-data" action="{{ route('admin-static-update-post', [$data->id, $initial]) }}">
		<div class="table-responsive">
			<table class="table table-bordered table-striped" id="multiple-checkbox">
				<thead>
					<tr>
						<th>SN</th>
						<th>Term</th>
						<th>Definition</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($definitions as $index => $d)
					<tr>
						<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
						<td><input name="data[{{$d->id}}][term]" size="15" value="{{ $d->term }}" required></td>
                    	<td><textarea cols="69" name="data[{{$d->id}}][definition]" required>{{ $d->definition }}</textarea></td>
						<td>
							<div class="btn-group">
    	              			<a href="#" class="btn btn-danger a_submit_button" related-id="delete-{{ $d->id }}">Delete</a>
    	    		 		</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		{{ csrf_field() }}
		<br/>
		<input type="submit" class="btn btn-success" value="Update">
	</form>

	@foreach($definitions as $index => $d) 
		<form method="post" action="{{ route('admin-definition-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-definitions-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>
@stop