@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<a href="{{ route('admin-company-download-financials-upload-excel') }}" class="btn btn-info">Download Sample</a>
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