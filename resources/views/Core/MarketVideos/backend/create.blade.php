@extends('backend.main')

@section('content')

	<form method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="data[title]" required value="{{ request()->old('data.title') }}" class="form-control">
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
			<input type="text" name="data[summary]" required value="{{ request()->old('data.summary') }}" class="form-control">
			@if($errors->has('summary'))
				<span class="error-block">
					@foreach($errors->get('summary') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Main Asset</label>
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<select name="data[asset_type]">
						<option value="">Select</option>
						<option value="video">Video</option>
					</select>
				</div>

				<div class="col-md-6 col-sm-12" id="prabal-asset-type">
				</div>
			</div>

		</div>

		<div class="form-group">
			<label>Description</label>
			<textarea name="data[description]" class="summernote">{{ request()->old('data.description') }}</textarea>
			@if($errors->has('description'))
				<span class="error-block">
					@foreach($errors->get('description') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Posted At</label>
			<input type="text" name="data[posted_at]" class="form-control datetime" value=@if(request()->old('data.posted_at')) "{{ request()->old('data.posted_at')  }}" @else "{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}" @endif>
			@if($errors->has('posted_at'))
				<span class="error-block">
					@foreach($errors->get('posted_at') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Posted By</label>
			<input type="text" name="data[posted_by]" class="form-control" value="{{ request()->old('data.posted_by')  }}">
			@if($errors->has('posted_by'))
				<span class="error-block">
					@foreach($errors->get('posted_by') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Ordering</label>
			<input type="number" name="data[ordering]" class="form-control" value="{{ request()->old('data.ordering')  }}" min=0 step=1>
			@if($errors->has('ordering'))
				<span class="error-block">
					@foreach($errors->get('ordering') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group">
			<label>Publish</label>
			<select name="data[is_active]" class="form-control" required>
				<option value="">Select</option>
				<option value="yes" @if(request()->old('data.is_active') == 'yes') selected @endif>Yes</option>
				<option value="no" @if(request()->old('data.is_active') == 'no') selected @endif>No</option>
			</select> 
			@if($errors->has('posted_by'))
				<span class="error-block">
					@foreach($errors->get('posted_by') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		{{ csrf_field() }}
		<input type="submit" class="btn btn-success" value="Create">
		<a href="{{ route('admin-market-videos-list-get') }}" class="btn btn-info">Cancel</a>
	</form>

	<div id="hidden-block" style="display:none">
		<div class="form-group" id="image">
			
			<input type="file" name="data[asset]">
			@if($errors->has('data.asset'))
				<span class="error-block">
					@foreach($errors->get('asset') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>

		<div class="form-group" id="video">
			<input type="text" name="data[asset]" class="form-control">
			@if($errors->has('data.asset'))
				<span class="error-block">
					@foreach($errors->get('asset') as $e)
						<p>{{ $e }}</p>
					@endforeach
				</span>
			@endif
		</div>
	</div>

	<input type="hidden" id="prabal-ajax-upload-image-post" value="{{ route('ajax-upload-image-post') }}">
	<input type="hidden" id="prabal-ajax-upload-image-directory" value="market-videos">
	<input type="hidden" id="prabal-ajax-upload-image-asset-type" value="market-videos">
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

	<script type="text/javascript">
		$(document).ready(function(){
			getAssetType($('select[name="data[asset_type]"]').val())
		})

		$('select[name="data[asset_type]"]').change(function() {
			getAssetType($(this).val())
		})

		function getAssetType(asset_value) {
			$('#prabal-asset-type').html($('#' + asset_value).html())
		}
	</script>
@stop