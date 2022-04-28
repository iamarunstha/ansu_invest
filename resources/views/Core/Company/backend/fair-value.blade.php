@extends('backend.main')

@section('content')
	<div class="header">
		<h3>{{$company->company_name}} ({{$company->short_code}})</h3>
	</div>

	<div class="rating-section">
		<div class="rating-view">
			<label>Ratings:</label>
			@if($company->fair_value_rating)
				<span>{{$company->fair_value_rating}} {{ $company->percent }}</span>
			@else
				<span>Not rated yet!</span>
			@endif
			@for($i=0; $i<$company->fair_value_rating; $i++)
				<i class="fa fa-star"></i>
			@endfor
		</div>
		<div class="rating-update">
			<button class="btn btn-info" data-toggle="modal" data-target="#myRatingModal">Update Rating</button>
		</div>
	</div>

	<div style="margin-top:10px"class="expert-section">
		@if($company->expert)
			{{$company->expert->title}}
		@else
{{--			<span>No expert link found!</span>--}}
		@endif

		{{--<div class="expert-update">
			<button class="btn btn-info" data-toggle="modal" data-target="#myExpertModal">Update expert's link</button>
		</div>--}}
	</div>

<div id="myRatingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Select Rating</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-company-rating-update-post', [$company->id]) }}">
        	<div class="form-group">
	        	<label for="data[rating]">Rating</label>
            <select class="form-control" name="data[fair_value_rating]">
              <option value="">-- Please Select --</option>
              @foreach((new \App\Http\Controllers\Core\Company\CompanyModel)->rating as $rating)
                <option value="{{ $rating }}" @if($company->fair_value_rating == $rating) selected @endif>{{ $rating }}</option>
              @endforeach
            </select>
            <br/>
	        	<input type="text" name="data[percent]" value="{{$company->percent}}" placeholder="Enter rating" class="form-control">
	        </div>

        	<input type="submit" class="btn btn-success" value="Update">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>
{{--
<div id="myExpertModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Select Expert's link</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-company-expert-update-post', [$company->id]) }}">

        	<table id="expert-list">
        		<thead>
        			<tr>
        				<th>SN</th>
        				<th>Title</th>
        				<th>Select</th>
        			</tr>
        		</thead>
        		<tbody>
        			@foreach($experts as $index=>$e)
        				<tr>
        					<td>{{$index}}</td>
        					<td>{{$e->title}}</td>
        					<td><input type="radio" name="data[expert_id]" value="{{$e->id}}"></td>
        				</tr>
        			@endforeach
        		</tbody>		
        	</table>
        	<input type="submit" class="btn btn-success" value="Update">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
      </div>
      
    </div>

  </div>
</div>
--}}
@stop

@section('custom-js')
	<script type="text/javascript" charset="utf8" src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.js')}}"></script>
	<script type="text/javascript" charset="utf8" src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
	<script type="text/javascript" charset="utf8" src="{{asset('backend/vendor/datatables/jquery.dataTables.js')}}"></script>
	<script type="text/javascript" charset="utf8" src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
	<script>
    	$(document).ready( function () {
    		$('#expert-list').DataTable();
		} );
	</script>
@stop
