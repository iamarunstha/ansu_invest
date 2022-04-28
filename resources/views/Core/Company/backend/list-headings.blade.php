@extends('backend.main')

@section('content')
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Company Headings List</h1>
  	
    <ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link @if($tab_id == 1) active @endif" href="{{route('admin-company-financial-tabs-headings-get', [$company_id, 1])}}">Balance Sheet</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link @if($tab_id == 2) active @endif" href="{{route('admin-company-financial-tabs-headings-get', [$company_id, 2])}}">Income Statement</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link @if($tab_id == 3) active @endif" href="{{route('admin-company-financial-tabs-headings-get', [$company_id, 3])}}">Cash Flow Statement</a>
  		</li>
		@foreach($tabs as $t)
			<li class="nav-item">
	    		<a class="nav-link @if($tab_id == $t->tab_id) active @endif" href="{{route('admin-company-financial-tabs-headings-get', [$company_id, $t->tab_id])}}">{{$t->tab->tab_name}}</a>
			</li>
		@endforeach
  	</ul>
	<form method="post" action="{{route('admin-company-financial-tabs-headings-refresh-post', $company_id)}}">
		{{ csrf_field() }}
		<input type="submit" class="btn btn-info" value="Refresh Headings">(Note: Useful if heading not found)
	</form>
	<form method="post" action="{{ route('admin-company-financial-tabs-headings-post', [$company_id, $tab_id]) }}" class="prabal-confirm">
		<input type="submit" class="btn btn-info" style="float:right;" value="Alter Linked Status for selected headings">
		<div class="table-responsive">
			<table class="table table-bordered table-striped" id="multiple-checkbox">
				<thead>
					<tr>
						<th><input id="selectAll" type="checkbox">SN</th>
						<th>Heading</th>
						<th>Is Linked</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $index => $d)
					@if($d->heading->tab->id == $tab_id)
					<tr>
						<td><input class="id-checkbox" type="checkbox" name="rid[]" value="{{ $d->id }}">{{ $index + 1 }}</td>
						<td>{{ $d->heading->heading }}</td>
						<td>{{ $d->is_linked }}</td>
					</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-info" value="Alter Linked Status for selected headings">
	</form>

	@section('custom-js')
		<script>
			$(function() {

    			$('#selectAll').click(function() {
        			if ($(this).prop('checked')) {
            			$('.id-checkbox').prop('checked', true);
        			} else {
            			$('.id-checkbox').prop('checked', false);
        			}
    			});
			});
		</script>
	@endsection
@stop
