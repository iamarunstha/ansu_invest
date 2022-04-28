@extends('backend.main')

@section('content')

	<form method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Starting Fiscal Year</label>
					<select name="start_fiscal_year_id" class="form-control" required id="start_fiscal_year_id">
						<option value="">-- Select --</option>
						@foreach($fiscal_year as $f)
							<option value="{{ $f->id }}">{{ $f->fiscal_year }}</option>
						@endforeach
					</select>
					@if($errors->has('start_fiscal_year_id'))
						<span class="error-block">{{ $errors->first('start_fiscal_year_id') }}</span>
					@endif
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Ending Fiscal Year</label>
					<select name="end_fiscal_year_id" class="form-control" required id="end_fiscal_year_id">
						<option value="">-- Select --</option>
						@foreach($fiscal_year as $f)
							<option value="{{ $f->id }}">{{ $f->fiscal_year }}</option>
						@endforeach
					</select>
					@if($errors->has('end_fiscal_year_id'))
						<span class="error-block">{{ $errors->first('end_fiscal_year_id') }}</span>
					@endif
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Starting sub year</label>
					<select name="start_sub_year_id" class="form-control" id="start_sub_year_id">
						<option value="">-- Select --</option>
						@foreach($sub_fiscal_year as $f)
							<option value="{{ $f->id }}">{{ $f->title }}</option>
						@endforeach
					</select>
					@if($errors->has('start_sub_year_id'))
						<span class="error-block">{{ $errors->first('start_sub_year_id') }}</span>
					@endif
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Ending sub year</label>
					<select name="end_sub_year_id" class="form-control" id="end_sub_year_id">
						<option value="">-- Select --</option>
						@foreach($sub_fiscal_year as $f)
							<option value="{{ $f->id }}">{{ $f->title }}</option>
						@endforeach
					</select>
					@if($errors->has('end_sub_year_id'))
						<span class="error-block">{{ $errors->first('end_sub_year_id') }}</span>
					@endif
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Download</label>
					<button type="button" href="{{route('admin-company-performance-upload-excel', $company_id)}}" class="btn btn-info form-control" id="download-sample">Download Sample</button>
					<span>Please select fiscal year and or type</span>
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
			let start_sub_year_id = $('#start_sub_year_id').val()
			let end_sub_year_id = $('#end_sub_year_id').val()

			if(start_fiscal_year_id.length === 0) {
				alert('Please select starting Fiscal Year');
				return false;
			}

			if(end_fiscal_year_id.length === 0) {
				alert('Please select ending Fiscal Year');
				return false;
			}

			let href = $(this).attr('href');
			href += '?start_fiscal_year_id=' + start_fiscal_year_id + '&end_fiscal_year_id=' + end_fiscal_year_id + '&start_sub_year_id=' + start_sub_year_id + '&end_sub_year_id=' + end_sub_year_id

			window.location.href = href;
		})
	</script>
@stop