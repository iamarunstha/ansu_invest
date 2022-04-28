@extends('backend.main')

@section('content')

<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Client Subscription History</h1>

	<p>Client Name: {{$data[0]->getClient->name}}</p>
	<p>Email: {{$data[0]->getClient->email}}</p>
	<p>Phone: {{$data[0]->getClient->phone}}</p>


  	<a href="{{ route('admin-subscription-list-get') }}" class="btn btn-info btn-flat">Subscription List</a>
  	<a href="{{ route('admin-subscripton-requests-get') }}" class="btn btn-info btn-flat">Subscription Requests</a>

  	@if(!count($data))
  		No subscription History Found!
  	@else

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Subscription Plan</th>
					<th>Payment Mode</th>
					<th>Payment Date</th>
					<th>Start Date</th>
					<th>Expiration Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index=>$d)
				<tr>
					<td>{{$index+1}}</td>
					<td>{{$d->subscription_plan}}</td>
					<td>{{$d->payment_mode}}</td>
					<td>{{$d->payment_date}}</td>
					<td>{{$d->start_date}}</td>
					<td>{{$d->expiration_date}}</td>
				</tr>					  
				@endforeach
			</tbody>
		</table>
	</div>
	@endif
@stop
