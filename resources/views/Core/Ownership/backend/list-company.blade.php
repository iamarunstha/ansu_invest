@extends('backend.main')

@section('content')


  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Ownership</h1>
  	<div class="search-form" style="display:block;">
   		<form method="get" action="{{route('admin-ownership-company-list-get', $tab->id)}}" style="float:right">
  	  		<input type="text" placeholder="Search.." name="search">
      		<button type="submit"><i class="fa fa-search"></i></button>
  		</form>
  	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Company</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($companies as $index => $c)
				
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $c->company_name }}</td>
					<td>
						<a href="{{route('admin-ownership-list-get', [$c->id, $tab->id])}}" class=" btn btn-info" related-id="">View</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

@stop