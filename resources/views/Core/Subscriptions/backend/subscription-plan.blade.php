@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Subscription">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif

@section('content')
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Subscription Plans</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Add Plan </button>
	@include('Core.Subscriptions.backend.modal.addplan')

	<div class="table-responsive">
    <form method="post" action="{{route('admin-subscripton-plans-update-post')}}">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Plan Name</th>
					<th>Duration</th>
					<th>Price (in NRs)</th>
                    <th>Ordering</th>
					<th>Is offer?</th>
                    <th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
                    <td><input name="data[{{$d->id}}][plan_name]" size="15" value="{{ $d->plan_name }}" required></td>
                    <td>
                        <input name="data[{{$d->id}}][duration]" style="width:20%" type="number" min="0" step="1" value="{{ $d->duration }}" required>
                        <select name="data[{{$d->id}}][duration_unit]">
                            <option value="day" @if($d->duration_unit == 'day') selected @endif >Day</option>
                            <option value="week" @if($d->duration_unit == 'week') selected @endif>Week</option>
                            <option value="month" @if($d->duration_unit == 'month') selected @endif>Month</option>
                            <option value="year" @if($d->duration_unit == 'year') selected @endif>Year</option>
                        <select>
                    </td>
                    <td><input name="data[{{$d->id}}][price]" style="width:45%" type="number" min="0" step="0.01" value="{{ $d->price }}" required></td>
                    <td><input name="data[{{$d->id}}][ordering]" style="width:20%" type="number" min="0" step="1" value="{{ $d->ordering }}" required></td>
					<td>
                        <select name="data[{{$d->id}}][is_offer]">
                            <option value="1" @if($d->is_offer)selected @endif>Yes</option>
                            <option value="0" @if(!$d->is_offer)selected @endif>No</option>
                        </select>
                    </td>
                    <td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
        {{csrf_field()}}
        <input type="submit" class="btn btn-success" value="Update">
    </form>
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{route('admin-subscription-plans-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach
@stop
