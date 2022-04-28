@extends('backend.main')

@section('content')
<?php
	echo '<pre>';
	print_r($errors->all());
	echo '</pre>';
?>
	<form method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label>Company Name</label>
			<input type="text" name="data[company_name]" required value="{{ request()->old('data.company_name') }}" class="form-control">
			@if($errors->has('company_name'))
				<span class="error-block">
					@foreach($errors->get('company_name') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Short Code</label>
			<input type="text" name="data[short_code]" required value="{{ request()->old('data.short_code') }}" class="form-control">
			@if($errors->has('short_code'))
				<span class="error-block">
					@foreach($errors->get('short_code') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group" id="image">
			<label>Logo</label>
			<input type="file" name="data[asset]">
			@if($errors->has('data.asset'))
				<span class="error-block">
					@foreach($errors->get('asset') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Type</label>
			<select name="data[type_id]" class="form-control" required>
				<option value="">Select</option>
				@foreach($types as $t)
					<option value="{{ $t->id }}" @if($t->id == request()->old('data.type_id')) selected @endif>{{ $t->type }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label>Profile</label>
			<textarea name="data[profile]" rows="10" class="form-control">{{ request()->old('data.profile') }}</textarea>
			@if($errors->has('profile'))
				<span class="error-block">
					@foreach($errors->get('profile') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Contact</label>
			<textarea name="data[contact]" rows="10" class="form-control">{{ request()->old('data.contact') }}</textarea>
			@if($errors->has('contact'))
				<span class="error-block">
					@foreach($errors->get('contact') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Sector</label>
			<select class="form-control" name="data[sector_id]" required>
				<option value="">-- Select --</option>
				@foreach($sectors as $s)
					<option value="{{ $s->id }}" @if($s->id == request()->old('data.sector_id')) selected @endif>{{ $s->name }}</option>
				@endforeach
			</select>
			@if($errors->has('sector'))
				<span class="error-block">
					@foreach($errors->get('sector_id') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Industry</label>
			<input type="text" name="data[industry]" required value="{{ request()->old('data.industry') }}" class="form-control">
			@if($errors->has('industry'))
				<span class="error-block">
					@foreach($errors->get('industry') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Fiscal Year End</label>
			<input type="text" name="data[fiscal_year_end]" required value="{{ request()->old('data.fiscal_year_end') }}" class="form-control">
			@if($errors->has('fiscal_year_end'))
				<span class="error-block">
					@foreach($errors->get('fiscal_year_end') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Employees</label>
			<input type="text" name="data[employees]" required value="{{ request()->old('data.employees') }}" class="form-control">
			@if($errors->has('employees'))
				<span class="error-block">
					@foreach($errors->get('employees') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Recent Earning</label>
			<input type="text" name="data[recent_earning]" required value="{{ request()->old('data.recent_earning') }}" class="form-control">
			@if($errors->has('recent_earning'))
				<span class="error-block">
					@foreach($errors->get('recent_earning') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Dividend Type</label>
			<select class="form-control" name="data[dividend_type_id]" required>
				<option value="">-- Select --</option>
				@foreach($dividend_types as $d)
					<option value="{{ $d->id }}" @if($d->id == request()->old('data.dividend_type_id')) selected @endif>{{ $d->type }}</option>
				@endforeach
			</select>
			@if($errors->has('dividend_type_id'))
				<span class="error-block">
					@foreach($errors->get('dividend_type_id') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Create">
		<a href="{{ route('admin-company-list-get') }}" class="btn btn-info">Cancel</a>
	</form>

	<input type="hidden" id="prabal-ajax-upload-image-post" value="{{ route('ajax-upload-image-post') }}">
	<input type="hidden" id="prabal-ajax-upload-image-directory" value="news">
	<input type="hidden" id="prabal-ajax-upload-image-asset-type" value="news">
	<input type="hidden" id="prabal-ajax-upload-set-sizes" value="no">
@stop