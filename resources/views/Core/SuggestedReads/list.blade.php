@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Suggested Reads">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif

@section('content')
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Suggested Reads</h1>

	<div class="table-responsive">
    <form method="post" action="{{route('suggested-reads-update-post')}}">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Category</th>
					<th>Title</th>
                    <th>Ordering</th>
                    <th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td>{{ $index + 1 }}</td>
                    <td>{{ $d->category }}</td>
                    <td>@if($d->post->title) {{ $d->post->title }} @else {{ $d->post->company->company_name }} @endif</td>
                    <td><input name="data[{{$d->id}}][ordering]" style="width:35%" type="number" min="0" step="1" value="{{ $d->ordering }}" required></td>
                    <td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{ $d->id }}">Remove</a>
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
		<form method="post" action="{{route('suggested-reads-delete-post', $d->id)}}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach
@stop
