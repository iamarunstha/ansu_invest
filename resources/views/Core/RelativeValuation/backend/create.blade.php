@extends('backend.main')

@section('content')
	<h1>Valuation of {{ $data->company_name }}</h1>
	<form method="post">
	<div class="form-group">
			<label>Title</label>
			<input name="data[title]" class="form-control" @if($data->title) 		value="{!! $data->title !!}"
			@else value="{{ $data->title }}" @endif>
			@if($errors->has('title'))
				<span class="error-block">
					@foreach($errors->get('title') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Summary</label>
			<textarea name="data[summary]" class="form-control">@if($data->summary) {!! $data->summary !!}
			@else 
				{!! request()->old('data.summary') !!}
			@endif
			</textarea>
			@if($errors->has('summary'))
				<span class="error-block">
					@foreach($errors->get('summary') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>
		<div class="form-group">
			<label>Description</label>
			<textarea name="data[description]" class="summernote">@if($data->description) 		{!! $data->description !!}
			@else 
				{!! request()->old('data.description') !!}
			@endif
			</textarea>
			@if($errors->has('description'))
				<span class="error-block">
					@foreach($errors->get('description') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>
		<div class="form-group">
			<label>Posted By</label>
			<input name="data[posted_by]" class="form-control" @if($data->posted_by) 		value="{!! $data->posted_by !!}"
			@else 
				value="{!! request()->old('data.posted_by') !!}"
			@endif
			</textarea>
			@if($errors->has('posted_by'))
				<span class="error-block">
					@foreach($errors->get('posted_by') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Update">
		<a href="{{ route('admin-relative-valuation-list-get') }}" class="btn btn-info">Cancel</a>
	</form>

	<input type="hidden" id="prabal-ajax-upload-image-post" value="{{ route('ajax-upload-image-post') }}">
	<input type="hidden" id="prabal-ajax-upload-image-directory" value="relative-valuation">
	<input type="hidden" id="prabal-ajax-upload-image-asset-type" value="relative-valuation">
	<input type="hidden" id="prabal-ajax-upload-set-sizes" value="no">

@stop

@section('custom-js')
	
	<script src="{{ asset('core/tinymce/js/tinymce/tinymce.min.js') }}"></script>
	<script>
		tinymce.init({
		  selector: 'textarea.summernote',
		  relative_urls: false,
		  remove_script_host : false,
		  document_base_url : "{{ config()->get('app.url') }}/",
		  plugins: 'print preview powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount tinymcespellchecker a11ychecker imagetools textpattern help formatpainter permanentpen pageembed tinycomments mentions linkchecker',
	  toolbar: 'fontsizeselect | fontselect | formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
	  image_advtab: true,
		    height : 480,
		    style_formats: [
		    {
	        title: 'Image Left',
	        selector: 'img',
	        styles: {
	            'float': 'left', 
	            'margin': '0 10px 0 10px'
	        }
	     },
	     {
	         title: 'Image Right',
	         selector: 'img', 
	         styles: {
	             'float': 'right', 
	             'margin': '0 0 10px 10px'
	         }
	        }],
	    	images_upload_handler: function (blobInfo, success, failure) {
		    	data = new FormData();
		        data.append("image", blobInfo.blob());
		        data.append('directory', $('#prabal-ajax-upload-image-directory').val())
				data.append('asset_type', $('#prabal-ajax-upload-image-asset-type').val())
				data.append('set-sizes', $('#prabal-ajax-upload-set-sizes').val())
		        data.append("_token", $('input[name="_token"]').val())
		        $.ajax({
		            data: data,
		            type: 'POST',
		            xhr: function() {
		                var myXhr = $.ajaxSettings.xhr();
		                if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
		                return myXhr;
		            },
		            url: $('#prabal-ajax-upload-image-post').val(),
		            cache: false,
		            contentType: false,
		            processData: false,
		            success: function(url) {
		            	console.log(url)
		            	//$('.summernote').summernote('insertImage', url.url);
		            	success(url.url);
		                //editor.insertImage(welEditable, url);
		            },
			        error: function(data) {
			            console.log(data)
			        }
		        });
	  }

	  
		});

		function progressHandlingFunction(e){
		    if(e.lengthComputable){
		        $('progress').attr({value:e.loaded, max:e.total});
		        // reset progress on complete
		        if (e.loaded == e.total) {
		            $('progress').attr('value','0.0');
		        }
		    }
		}

	</script>
@stop
