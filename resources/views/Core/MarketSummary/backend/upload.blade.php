@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Market Summary">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')
	

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Market Summary</h1>
		
  	<form method="post" enctype="multipart/form-data">
  		<div class="col-md-4">
			<div class="form-group">
				<label>Download</label>
				<button type="button" href="{{ route('donwload-market-summary-get') }}" class="btn btn-info form-control" id="download-sample">
					Download Sample
				</button>
			</div>
		</div>
			
		<div class="col-md-6">
			<div class="form-group">
				<label>Date</label><br/>
				<input type="text" name="data[date]" required value=@if(request()->old('data.date')) "{{ request()->old('data.date') }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif class="date" id="as_on_date"><br/>
				@if($errors->has('date'))
					<span class="error-block">
						@foreach($errors->get('date') as $e)
							<p>{{ $e }}</p>
						@endforeach
					</span>
				@endif
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
			let date = $('#as_on_date').val();
			let href = $(this).attr('href')+"?date=" + date;
			window.location.href = href;
		})
	</script>
@stop