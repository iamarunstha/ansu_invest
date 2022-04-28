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
  	<h1 class="h3 mb-4 text-gray-800">Admin Info</h1>

      <div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Admin Name</th>
					<th>Admin Email</th>
                    <th>Last Logged In</th>
                    <th>IP Address</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index+1 }}</td>
					<td>{{ $d->name }}</td>
					<td>{{ $d->email }}</td>
					<td>@if($d->adminHistory->first()) {{ $d->adminHistory->first()->logged_in_at }} @endif</td>
					<td>@if($d->adminHistory->first()) {{ $d->adminHistory->first()->ip_address }} @endif</td>
                    <td>
						<a href="{{ route('admin-user-info-admins-history-get', $d->id ) }}" class="btn btn-info">View History</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	  </div>
@stop
