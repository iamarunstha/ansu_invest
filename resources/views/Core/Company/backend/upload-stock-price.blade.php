@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<a id ="download-sample" href="{{ route('admin-company-stock-price-upload-excel') }}" class="btn btn-info">Download Sample</a>
	<form method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Start Date</label>
					<input id="start_date" type="text" name="data[start_date]" class="form-control date" required value=@if(request()->old('data.start_date')) "{{ request()->old('data.start_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>End Date</label>
					<input id="end_date"
					type="text" name="data[end_date]" class="form-control date" required value=@if(request()->old('data.end_date')) "{{ request()->old('data.end_date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Excel File</label><br/>
					<input type="file" name="data[excel_file]" required value="{{ request()->old('data.excel_file') }}" class=""><br/>
					<span class="help-block">Please upload xlxs format excel file only</span>
					@if($errors->has('excel_file'))
						<span class="error-block">
							@foreach($errors->get('excel_file') as $e)
								<p>{{ $e }}</p>
							@endforeach
						</span>
					@endif
				</div>
			</div>
		</div>
		
		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Upload">
		<a href="{{ route('admin-company-list-get') }}" class="btn btn-info">Cancel</a>
	</form>
@stop

@section('custom-js')
	<script type="text/javascript">
		$('#download-sample').click(function(e) {
			e.preventDefault();
			let start_date = $('#start_date').val()
			let end_date = $('#end_date').val()

			if(start_date.length === 0) {
				alert('Please select starting Fiscal Year');
				return false;
			}

			if(end_date.length === 0) {
				alert('Please select ending Fiscal Year');
				return false;
			}

			let href = $(this).attr('href');
			href += '?start_date=' + start_date + '&end_date=' + end_date
			window.location.href = href;
		})
	</script>
@stop