@extends('backend.main')

@section('content')
	
  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Edit Notice</h1>

  	<form method="post" action="{{route('admin-notice-edit-post', $notice->id)}}">
        	<div class="form-group">
	        	<label>Name</label>
	        	<input type="text" name="data[name]" value="{{ $notice->name }}" placeholder="Change name">
	        </div>
	        <div class="form-group">
	        	<label>Published at</label>
	        	<input type="text" name="data[notice_date]" value="{{ $notice->notice_date }}" placeholder="Change published date" class="form-control date">
	        </div>
	        <div class="form-group">
	        	<label for="data[description]">Description</label> 
				<textarea name="data[description]"> {{ $notice->description }}</textarea>
			</div>
			<div class="form-group">
						<label>Select Company</label>
						<input type="text" class="company-name"/>
						<div class="row">
							@foreach($companies as $c)
							@php $show = (in_array($c->id, $selected_companies)) ? true : false; @endphp
							<div class="col-md-4 list-of-companies" company_name="{{$c->company_name}}" @if(!$show) style="display: none;" @endif>
								<input type="checkbox" name="data[company_ids][]" value="{{ $c->id }}" @if($show) checked="checked" @endif/>{{ $c->company_name }}
							</div>
							@endforeach
						</div>
						
					</div>
  		{{csrf_field()}}

  		<input type="submit" class="btn btn-success" value="Update">
       	<a href="{{ route('admin-proposed-dividend-list-get') }}" class="btn btn-info">Cancel</a>
  	</form>
@stop

@section('custom-js')
<script>
$('.company-name').keyup(function(e) {
	e.preventDefault();
	let current_value = $(this).val().toLowerCase();
	$('.list-of-companies').each(function() {
		let company_name = $(this).attr('company_name').toLowerCase();
		let checkbox = $(this).find('input[type="checkbox"]');
		if(checkbox.is(':checked')) {
			$(this).show();
		}
		else if(current_value.length > 0 && company_name.includes(current_value)) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});

})
</script>
@stop