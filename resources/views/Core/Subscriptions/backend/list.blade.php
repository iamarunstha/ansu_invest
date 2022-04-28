@extends('backend.main')

@section('content')

<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Subscription Requests table</h1>

  	<a href="{{ route('admin-subscription-list-get') }}" class="btn btn-info btn-flat">Subscription List</a>
  	<a href="{{ route('admin-subscription-rejected-list-get') }}" class="btn btn-info btn-flat">Rejected List</a>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Client Name</th>
					<th>Submitted At</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index=>$d)
				<tr>
					<td>{{$index+1}}</td>
					<td>{{$d->getClient->name}}</td>
					<td>{{$d->uploaded_at}}</td>
					<td>
						<a href="#" class="a_submit_button btn btn-success" related-id="approve-{{$d->id}}">Approve</a>
						<a href="#" class="a_submit_button btn btn-danger" related-id="reject-{{$d->id}}">Reject</a>
						<a class="btn btn-info" href="{{asset('storage/subscriptions/'.$d->bank_voucher)}}" target="_blank">Voucher Image</a>
						{{-- Storage::get($d->bank_voucher) --}}
						{{-- {{route('admin-client-history-get')}} --}}
					</td>
				</tr>					  
				@endforeach
			</tbody>
		</table>
	</div>


	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-subscripton-requests-approve-post', $d->id) }}" id="approve-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>

		<form method="post" action="{{ route('admin-subscripton-requests-reject-post', $d->id) }}" id="reject-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach
@stop
