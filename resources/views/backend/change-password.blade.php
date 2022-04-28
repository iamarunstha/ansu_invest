@extends('backend.main')

@section('content')
	<form method="post">
		<div class="form-group">
			<label>Current Password</label>
			<input name="current_password" type="password" class="form-control">
		</div>

		<div class="form-group">
			<label>New Password</label>
			<input name="new_password" type="password" class="form-control">
		</div>

		<div class="form-group">
			<label>Confirm Password</label>
			<input name="confirm_password" type="password" class="form-control">
		</div>

		<input type="submit" class="btn btn-flat btn-success">
		{{ csrf_field() }}
	</form>
@stop