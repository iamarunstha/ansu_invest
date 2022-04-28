@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="News">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">{{ ucwords($type[0]) }} List</h1>

	<a href="{{ route('admin-news-create-get', ['type'=>$type[0]]) }}" class="btn btn-info btn-flat">Create</a>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<a href="{{ route('admin-news-list-get', $type[1]) }}" class="btn btn-info btn-flat">{{ ucwords($type[1]) }}</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Title</th>
					<th>Summary</th>
					<th>Posted At</th>
					<th>Posted By</th>
					<th>Published</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->title }}</td>
					<td>{{ $d->summary }}</td>
					<td>{{ $d->posted_at }}</td>
					<td>{{ $d->posted_by }}</td>
					<td>{{ $d->is_active }}</td>
					<td>
						<div class="btn-group">
                  			<button type="button" class="btn btn-info">Actions</button>
                  			<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                  			<ul class="dropdown-menu">
								<li><a href="{{ route('admin-news-edit-get', $d->id, ['news_type' => $type[0]]) }}">Edit</a></li>
								<li><a href="#" class="a_submit_button" related-id="delete-{{ $d->id }}">Delete</a></li>
								<li><a href="#" class="a_submit_button set_top_news_button" related-id="top-news-{{ $d->id }}">@if($d->is_newsboard == 'yes') Unset Top News @else Set Top News @endif</a></li>
								<li><a href="#" class="a_submit_button set_newsboard_button" related-id="news-board-{{ $d->id }}">@if($d->is_newsboard == 'yes') Unset Newsboard @else Set Newsboard @endif</a></li>
							</ul>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{ $data->links() }}
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-news-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
		<form method="post" action="{{ route('admin-news-set-as-top-news-post', $d->id) }}" id="top-news-{{ $d->id }}" class="">
			{{ csrf_field() }}
		</form>
		<form method="post" action="{{ route('admin-news-set-as-news-board-post', $d->id) }}" id="news-board-{{ $d->id }}" class="">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-news-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>	
@stop