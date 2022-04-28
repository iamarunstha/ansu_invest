@extends('backend.main')

@section('content')
	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-user-info-admins-get')}}">Admin Info</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-user-info-clients-get')}}">Clients</a>
  		</li>
  	</ul>
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Admin History of {{$data->name}} ({{$data->email}})</h1>

      <a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
      <div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th><input id="selectAll" type="checkbox">SN</th>
                    <th>Logged In</th>
                    <th>IP Address</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data->adminHistory as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index+1 }}</td>
					<td>{{ $d->logged_in_at }}</td>
					<td>{{ $d->ip_address }}</td>
					<td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	  </div>

    @foreach($data->adminHistory as $index => $d) 
		<form method="post" action="{{route('admin-user-info-admins-history-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

    <form id="multiple-delete" action="{{ route('admin-user-info-admins-history-multiple-delete-post') }}" method="post" class="prabal-confirm">
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
