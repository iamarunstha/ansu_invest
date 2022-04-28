@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Trailing Returns">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Upload Trailing Returns</h1>
		
  	<form method="post" enctype="multipart/form-data">
  		<div class="col-md-4">
			<div class="form-group">
				<label>Download</label>
				<button type="button" href="{{ route('admin-trailing-returns-download-excel-get') }}" class="btn btn-info form-control" id="download-sample">
					Download Sample
				</button>
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
		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Upload">
	</form>
@stop

@section('custom-js')
	<script type="text/javascript">
		$('#download-sample').click(function(e) {
			e.preventDefault();

			let href = $(this).attr('href');
			window.location.href = href;
		})
	</script>
@stop