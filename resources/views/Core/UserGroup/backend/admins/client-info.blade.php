@extends('backend.main')

@section('content')
	<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link" href="{{route('admin-user-info-admins-get')}}">Admin Info</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link active" href="{{route('admin-user-info-clients-get')}}">Clients</a>
  		</li>
  	</ul>
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Client Info</h1>
	@if(!$blocked)
		<a class="btn btn-info" href="{{route('admin-user-info-clients-get', ['blocked'=> 'blocked'])}}">View Blacklisted</a>
    @else
		<a class="btn btn-info" href="{{route('admin-user-info-clients-get')}}">View Whitelisted</a>
	@endif
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Client Name</th>
					<th>Email</th>
					@if($blocked) <th>Blocked At</th>@else
                    <th>Registered At</th>@endif
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index+1 }}</td>
					<td>{{ $d->name }}</td>
					<td>{{ $d->email }}</td>
					@if($blocked) <th>{{ $d->blocked_at }}</th>@else
					<td>{{ $d->created_at }}</td>@endif
                    <td>
                        <button class="btn btn-info" data-toggle="modal" data-target="#clientDetailModal-{{ $d->id }}">View Details</button>
						@if(!$blocked)
							<a href="#" class="a_submit_button btn btn-success" related-id="block-{{ $d->id }}">Blacklist</a>
						@else
							<a href="#" class="a_submit_button btn btn-success" related-id="unblock-{{ $d->id }}">Whitelist</a>
						@endif
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@foreach($data as $index => $d)
	<div id="clientDetailModal-{{ $d->id }}" class="modal fade" role="dialog">	  
		<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">
    		    <div class="modal-header">
    		    	<h3><strong>{{$d->name}}'s Details</strong></h3>
    		    </div>
    		    <div class="modal-body">
    		    	<p><strong>Email:</strong><span style="margin-left:7.5rem">{{$d->email}}</span></p>
    		    	<p><strong>Phone:</strong><span style="margin-left:7.2rem">{{$d->phone}}</span></p>        
    		    	<p><strong>Registered At:</strong><span style="margin-left:3.7rem">{{$d->created_at}}</span></p>
    		    	@if($d->username)
    		        	<p><strong>Username:</strong><span style="margin-left:5.5rem">{{$d->username}}</span></p>
    		    	@endif
    		    	@if($d->secondary_email)
    		        	<p><strong>Secondary Email:</strong><span style="margin-left:2.3rem">{{$d->secondary_email}}</span></p>
    		    	@endif
    		    	@if($d->is_subscribed)
    		        	<p><strong>Subscription</strong></p>
    		        	<p><strong>Subscribed Plan:</strong><span style="margin-left:2.5rem">{{$d->subscribed_plan['plan_name']}}</span></p>
    		        	<p><strong>Start Date:</strong><span style="margin-left:5.1rem">{{$d->subscribed_plan['subscription_date']}}</span></p>
    		        	<p><strong>Expiry Date:</strong><span style="margin-left:4.5rem">{{$d->subscribed_plan['expiry_date']}}</span></p>
    		    	@endif
					@if($d->blocked_at)
    		    		<p><strong>Blocked At:</strong><span style="margin-left:4.9rem">{{$d->blocked_at}}</span></p>
					@endif
				</div>
    		</div>
  		</div>
	</div>
	@if(!$blocked)
	<form method="post" action="{{route('admin-user-info-clients-block-post', $d->id)}}" id="block-{{ $d->id }}" class="prabal-confirm">
		<input type="hidden" name="task" value="block">
		{{ csrf_field() }}
	</form>
	@else
	<form method="post" action="{{route('admin-user-info-clients-block-post', $d->id)}}" id="unblock-{{ $d->id }}" class="prabal-confirm">
		<input type="hidden" name="task" value="unblock">
		{{ csrf_field() }}
	</form>
	@endif

	<form method="post" action="{{route('admin-user-info-clients-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>
	@endforeach
@stop
