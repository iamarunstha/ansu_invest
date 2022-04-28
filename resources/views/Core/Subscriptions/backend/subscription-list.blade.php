@extends('backend.main')

@section('content')

<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Subscriptions</h1>


  	<a href="{{ route('admin-subscripton-requests-get') }}" class="btn btn-info btn-flat">Subscription Requests</a>
  	<a href="{{ route('admin-subscription-rejected-list-get') }}" class="btn btn-info btn-flat">Rejected List</a>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Client Name</th>
					<th>Subscription Plan</th>
					<th>Start Date</th>
					<th>Expiration Date</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($subscriptions as $index=>$d)
				<tr>
					<td>{{$index+1}}</td>
					<td>{{$d->getClient->name}}</td>
					<td>{{$d->subscription_plan}}</td>
					<td>{{$d->start_date}}</td>
					<td>{{$d->expiration_date}}</td>
					<td>
						<a class="btn btn-info" href="{{route('admin-client-history-get', $d->getClient->id)}}">Client History</a>
					</td>
				</tr>					  
				@endforeach
			</tbody>
		</table>
	</div>
@endsection()
