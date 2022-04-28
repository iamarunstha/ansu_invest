@extends('backend.main')

@section('content')
	
	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-user-groups-get')}}">Groups</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-list-get')}}">Admins</a>
  		</li>
  	</ul>
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">{{$group->group_name}}</h1>
  	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-user-groups-members-get', $group->id)}}">Members</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-user-groups-permission-get', $group->id)}}">Permissions</a>
  		</li>
  	</ul>

	<ul class="nav nav-tabs">
		@foreach($modules as $m)
  			<li class="nav-item">
    			<a class="nav-link @if($m->module == $module) active @endif" href="{{route('admin-user-groups-permission-get', [$group->id, $m->module])}}">{{$m->module}}</a>
  			</li>
  		@endforeach
  	</ul>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th><input id="selectAll" type="checkbox">SN</th>
					<th>Permission Name</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index=>$d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->name }}</td>
					<td>
						@if($d->groups->where('id', $group->id)->first())
							✓
						@else
							☓
						@endif
					</td>

					<td>@if(!$d->groups->where('id', $group->id)->first())
							<a href="#" class="a_submit_button btn btn-success" related-id="add-{{ $d->id }}">Authorize</a>
						@else
							<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Unauthorize</a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $data->appends(request()->all())->links() }}
	</div>
	<div class="btn-group">
		<a href="#" class="btn btn-success prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-add">Authorize Selected</a>
		<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Unauthorize Selected</a>
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{route('admin-group-permission-add-post', [$group->id, $d->id])}}" id="add-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
		<form method="post" action="{{route('admin-group-permission-delete-post', [$group->id, $d->id])}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form method="post" action="{{route('admin-group-permission-multiple-add-post', $group->id)}}" id="multiple-add" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>
	<form method="post" action="{{route('admin-group-permission-multiple-delete-post', $group->id)}}" id="multiple-delete" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>

	@section('custom-js')
		<script>
			$(function() {

    			$('#selectAll').click(function() {
        			if ($(this).prop('checked')) {
            			$('.id-checkbox').prop('checked', true);
        			} else {
            			$('.id-checkbox').prop('checked', false);
        			}
    			});
			});
		</script>
	@endsection
@stop
