@extends('backend.main')

@section('content')
    <!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Contact and Disclaimer Section</h1>
    <form method="post">
		<h3>Contact Info</h3>
		<div class="form-group">
			<label>Address</label><br>
			<textarea name="data[address]" rows="2" cols="50">{{ $data['address'] }}</textarea>
		</div>
		<div class="form-group">
			<label>Phone</label><br>
			<input type="text" size="50" name="data[phone]" value="{{ $data['phone'] }}">
		</div>
		<div class="form-group">
			<label>Email</label><br>
			<input type="email" size="50" name="data[email]" value="{{$data['email']}}">
		</div>
		<div class="form-group">
	        <label>Disclaimer</label><br>
	        <textarea name="data[disclaimer]" rows="5" cols="100">{{ $data['disclaimer'] }}</textarea>
	    </div>
        {{csrf_field()}}
  		<input type="submit" class="btn btn-success" value="Update">
    </form>
@stop
