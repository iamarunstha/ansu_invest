@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<div class="qoute-link">
		<a href="{{ route('admin-company-quote-headings-list-get',1)}}" class="btn btn-info">View Headings</a>
	</div>
	<div class="download-link" style="margin-top:5px">
		<a href="{{ route('admin-company-download-quote-upload-excel') }}" class="btn btn-info">Download Sample</a>
	</div>
	<form method="post" enctype="multipart/form-data">
		<div class="row">
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