@extends('backend.main')

@section('content')
<?php

?>

	<a href="#" id="restore" class="btn btn-info" default-checkbox-assigned="company">Restore selected</a>
	<form method="post">
		<div class="form-group">
			<label><strong>Select Companies for year {{$year->fiscal_year}}</strong></label><br>
			<input type="checkbox" id="master-check" slave-checkbox-class="company">Check All
			
			<div class="row">
			@foreach ($companies as $c)
				<div class="col-md-4">
				<input class="company" type="checkbox" name="data[company_ids][]" value="{{ $c->id }}" assigned=@if(in_array($c->id, $assigned_companies))
						"true"
					@else
						"false"
				 	@endif
					@if(in_array($c->id, $assigned_companies))
						checked
				 	@endif>
				<label>{{$c->company_name}}</label><br>
			</div>
			@endforeach
			</div>
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Assign">
		<a href="{{ route('admin-fiscal-year-list-get') }}" class="btn btn-info">Cancel</a>
	</form>


@stop

@section('custom-js')
	<script type="text/javascript">
		$('#master-check').change(function(e) {
			e.preventDefault();
			let is_checked = $('#master-check').is(":checked");

			let slave_checkboxes = $(this).attr('slave-checkbox-class');
			
			$('.' + slave_checkboxes).each(function() {
				if(is_checked) {
					$(this).prop('checked', true);
				} else {
					$(this).prop('checked', false);
				}
			});
		})
		$('#restore').click(function(e) {
			e.preventDefault();

			let slave_checkboxes = $(this).attr('default-checkbox-assigned');
			
			$('.' + slave_checkboxes).each(function() {
				if($(this).attr('assigned') == 'true') {
					$(this).prop('checked', true);
				} else {
					$(this).prop('checked', false);
				}
			});
		})
	</script>
@stop
