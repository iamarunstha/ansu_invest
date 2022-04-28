@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Footer">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif

@section('content')
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Footer Links</h1>

	<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Add Links </button>
	@include('Core.Footer.modal.add-links')
	
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<a href="{{ route('admin-footer-contacts-get') }}" class="btn btn-info">Contacts/Disclaimer</a>
	<div class="table-responsive">
    <form method="post" action="{{route('admin-footer-links-edit-post')}}">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Link text</th>
					<th>Link Order</th>
					<th>Link URL</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
                    <td><input name="data[{{$d->id}}][link_text]" size="15" value="{{ $d->link_text }}"></td>
                    <td><input name="data[{{$d->id}}][link_order]" style="width:20%" type="number" min="0" step="1" value="{{ $d->link_order }}"></td>
                    <td><input name="data[{{$d->id}}][link_url]" size="40" value="{{ $d->link_url }}"></td>
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
		<form method="post" action="{{route('admin-footer-links-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{route('admin-footer-links-delete-multiple-post')}}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop
