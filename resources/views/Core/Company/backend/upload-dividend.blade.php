@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<form method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Start Fiscal Year*</label>
					<select name="start_fiscal_year_id" class="form-control" required id="start_fiscal_year_id">
						<option value="">-- Select --</option>
						@foreach($fiscal_year as $f)
							<option value="{{ $f->id }}">{{ $f->fiscal_year }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>End Fiscal Year*</label>
					<select name="end_fiscal_year_id" class="form-control" required id="end_fiscal_year_id">
						<option value="">-- Select --</option>
						@foreach($fiscal_year as $f)
							<option value="{{ $f->id }}">{{ $f->fiscal_year }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Download</label>
					<button type="button" href="{{ route('admin-company-dividend-upload-excel', $company_id) }}" class="btn btn-info form-control" id="download-sample">Download Sample</button>
					<span>Please select fiscal year and or type</span>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>View Details</label><br/>
					<button type="button" href="{{ route('admin-company-dividend-details-list', $company_id) }}" class="btn btn-info form-control" id="dividend-details">Dividend Details</button>
					<span class="help-block">Click here to view dividend details</span>
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
			let start_fiscal_year_id = $('#start_fiscal_year_id').val()
			let end_fiscal_year_id = $('#end_fiscal_year_id').val()

			if(start_fiscal_year_id.length === 0) {
				alert('Please select starting Fiscal Year');
				return false;
			}

			if(end_fiscal_year_id.length === 0) {
				alert('Please select ending Fiscal Year');
				return false;
			}

			let href = $(this).attr('href');
			href += '?start_fiscal_year_id=' + start_fiscal_year_id + '&end_fiscal_year_id=' + end_fiscal_year_id
			window.location.href = href;
		})

		$('#dividend-details').click(function(e) {
			e.preventDefault();
			let href = $(this).attr('href');
			window.location.href = href;
		})
	</script>
@stop