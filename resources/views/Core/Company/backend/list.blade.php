@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Company">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Company List</h1>
  	<div class="search-form" style="display:block;">
   		<form method="get" action="{{route('admin-company-list-get')}}" style="float:right">
  	  		<input type="text" placeholder="Search.." name="search">
      		<button type="submit"><i class="fa fa-search"></i></button>
  		</form>
  	</div>
	<a href="{{ route('admin-company-create-get') }}" class="btn btn-info btn-flat">Create</a>
	<a href="#" class="btn btn-danger prabal-checkbox-submit" related-id="multiple-checkbox" related-form="multiple-delete">Delete Multiple</a>
	<a href="{{ route('admin-company-upload-stock-price-get') }}" class="btn btn-info">Upload Stock Price</a>
	<a href="{{ route('admin-company-upload-quote-get') }}" class="btn btn-info">Upload Quotes</a>
	<a href="{{ route('admin-company-upload-financials-get') }}" class="btn btn-info">Upload Financials</a>

	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="multiple-checkbox">
			<thead>
				<tr>
					<th>SN</th>
					<th>Name</th>
					<th>Contact</th>
					<th>Sector</th>
					<th>Industry</th>
					<th>Type</th>
					<th>Fiscal Year End</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $index => $d)
				
				<tr>
					<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
					<td>{{ $d->company_name }} ({{ $d->short_code }})</td>
					<td>{{ substr($d->contact, 0,  50)}}@if(strlen($d->contact)>50)...@endif</td>

					<td>{{ $d->getSector->name }}</td>
					<td>{{ $d->industry }}</td>
					<td>{{ $d->getType->type }}</td>
					<td>{{ $d->fiscal_year_end }}</td>
					<td>
						<div class="btn-group">
                  			<button type="button" class="btn btn-info">Actions</button>
							<button type="button" class="btn btn-info dropdown-toggle" data-toggle="modal" data-target="#myfunctions{{$d->id}}"><span class="caret"></span></button>
                		</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		{{ $data->appends(request()->all())->links() }}
	</div>

	@foreach($data as $index => $d) 
		<form method="post" action="{{ route('admin-company-delete-post', $d->id) }}" id="delete-{{ $d->id }}" class="prabal-confirm">
			{{ csrf_field() }}
		</form>
	@endforeach

	<form id="multiple-delete" action="{{ route('admin-company-delete-multiple-post') }}" method="post" class="prabal-confirm">
		<div class="place-for-id-checkbox">
		</div>
		{{ csrf_field() }}
	</form>

	@foreach($data as $index=>$d) 
	<div id="myfunctions{{$d->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Select Function</strong></h3>
      </div>
      <div class="modal-body">
        <ul>
          <li><a href="{{ route('admin-company-summary-list-get', $d->id) }}">Summary</a></li>
          <li><a href="{{ route('admin-company-edit-get', $d->id) }}">Edit</a></li>
          <li><a href="{{ route('admin-company-upload-balance-sheet-get', $d->id) }}">Upload Balance sheet</a></li>                    			
          <li><a href="{{ route('admin-company-upload-valuation-get', $d->id) }}">Upload Valuation</a></li>
          <li><a href="{{ route('admin-company-upload-performance-get', $d->id) }}">Upload Operating Performance</a></li>
          <li>
            @if($d->dividendType)
			<a href="{{route('admin-company-upload-dividend-get', $d->id)}}">
				@if($d->dividendType->type == 'Dividend')	
					Upload Dividend
				@elseif($d->dividendType->type == 'Right Share')
					Upload Right Share
				@endif
			</a>
			@endif
          </li>
          <li><a href="{{route('admin-company-fair-value-get', $d->id)}}">Fair value</a></li>
          <li><form method="post" action="{{route('adimn-poll-set-poll-post', $d->id)}}">
          	{{ csrf_field() }}
          	<input type="submit" value="Feature Poll">
          </form></li>
		  <li><a href="{{route('admin-company-financial-tabs-headings-get', [$d->id, 1])}}">Select Headings</a></li>
          <li><a href="#" class="a_submit_button" related-id="delete-{{ $d->id }}">Delete</a></li>
        </ul>
        <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
      </div>      
    </div>
  </div>
</div>		

	@endforeach
@stop
