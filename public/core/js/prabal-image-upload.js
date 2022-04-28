$(function()
{
	setDraggableDroppable()	

	function countMaxImages(added_images)
	{
		let max_no_of_images = parseInt($('#prabal-ajax-upload-image-max-no').val())
		let no_of_images = document.getElementById('prabal-ajax-upload-image').querySelectorAll('#prabal-ajax-upload-image .prabal-added-images').length;
		
		if(max_no_of_images == 0 || (no_of_images + added_images) <= max_no_of_images) {
			return true
		}
		else {
			alert('Maximum of ' + max_no_of_images + ' can be uploaded.')
			return false
		}
	}

	$(document).on('click', '#prabal-ajax-upload-image .btn-remove', function(e)
	{
		$(this).parent().remove();
		if(parseInt($('#prabal-ajax-upload-image-max-no').val()) == 1) {
			$('#main-upload-btn-wrapper').show();
		}
	})

	function rotateImage(e, value)
	{
		var rotate = parseInt(value * 90)
		e.css({
		  '-webkit-transform' : 'rotate('+ rotate +'deg)',
		  '-moz-transform'    : 'rotate('+ rotate +'deg)',
		  '-ms-transform'     : 'rotate('+ rotate +'deg)',
		  '-o-transform'      : 'rotate('+ rotate +'deg)',
		  'transform'         : 'rotate('+ rotate +'deg)'
		});
	}

	$(document).on('click touchstart', '#prabal-ajax-upload-image .btn-rotate', function(){
		var parent = $(this).parent();
		var pra_clockwise_value = parseInt(parent.find('.pra_clockwise_value').val())
		    pra_clockwise_value = pra_clockwise_value + 1
		    console.log(pra_clockwise_value)
		parent.find('.pra_clockwise_value').val(pra_clockwise_value)
		var image_object = parent.find('img');
		rotateImage(image_object, pra_clockwise_value)
	});

	function setDraggableDroppable() {
		$( ".uploaded_image" ).draggable({
						revert: true
					});
		$( ".uploaded_image" ).droppable({
		  drop: function( event, ui ) {
		    var temp;
		    temp = ui.draggable.html();
		    ui.draggable.html($(this).html())
		    $(this).html(temp)
		  }
		});	
	}

	function progress(i, e)
	{
		i.parentElement.getElementsByClassName('progress')[0].style.display = 'flex'
		if(e.lengthComputable){
	        var max = e.total;
	        var current = e.loaded;

	        //i.classList.remove("clean");
	        //i.classList.remove("fa-cloud-upload");

	        var Percentage = parseInt((current * 100)/max);

	        var max = e.total;
	        var current = e.loaded;

	        var Percentage = parseInt((current * 100)/max);

	        if(Percentage >= 95)
	        {
	        	Percentage = 95


	        }

	        var jquery_element = $(i);
	        jquery_element.animate({
				"value": Percentage + "%"
				}, 
				{
				duration: 600,
				easing: 'linear'
			});
	        
	        //if(Percentage 105)
	        //{

		        for(var j=i.parentElement.getElementsByClassName('progress-bar')[0].getAttribute('aria-valuenow'); j<=Percentage; j++)
		        {
	        		console.log(j)
	        		i.parentElement.getElementsByClassName('progress-bar')[0].style.width = j + '%'
	        		i.parentElement.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', j)

		        }	
	        //}
	          
	        
		}
	}

	function fadeOutEffect(target) {
	    var fadeTarget = target
	    var fadeEffect = setInterval(function () {
	        if (!fadeTarget.style.opacity) {
	            fadeTarget.style.opacity = 1;
	        }
	        if (fadeTarget.style.opacity < 0.1) {
	            clearInterval(fadeEffect);
	            
	            target.parentElement.style.visibility = 'hidden'
	        } else {
	            fadeTarget.style.opacity -= 0.1;
	        }
	    }, 200);


	}

	function progressComplete(i)
	{
		var Percentage = 100;
		i.parentElement.getElementsByClassName('progress-bar')[0].style.width = Percentage + '%'
		i.parentElement.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', Percentage)	
		i.parentElement.getElementsByClassName('progress-bar')[0].classList.add("bg-success");
	    fadeOutEffect(i.parentElement.getElementsByClassName('progress-bar')[0])
	    setDraggableDroppable()
	       // i.style.background = successBackground
			
	}
	$('#prabal-ajax-upload-image').on('change', '.input-upload-file', function(e)
	{
			if(parseInt($('#prabal-ajax-upload-image-max-no').val()) == 1) {
				$('#main-upload-btn-wrapper').hide();
			}
			
			var formData = new FormData;
			var files = e.target.files;
			var current_element = this;
			let count = files.length
			if(countMaxImages(count)) {
				current_element.parentElement.getElementsByClassName('error-block')[0].innerHTML = ''

				for(let i = 0; i < count; i++) {
					$('#prabal-ajax-upload-image').append($('#prabal-ajax-upload-image-add-element-add-images').html())
				}

				let images = document.getElementById('prabal-ajax-upload-image').getElementsByClassName('input-upload-file-prabal-added-images')
				for(let i=0; i< images.length; i++) {
					var formData = new FormData;

					//var current_element = clean[i];
					//current_element.parentElement.classList.add("remove-image");
					formData.append('image', files[i])
					formData.append('_token', $('#prabal-ajax-upload-image-csrf-token').val())
					formData.append('directory', $('#prabal-ajax-upload-image-directory').val())
					formData.append('asset_type', $('#prabal-ajax-upload-image-asset-type').val())

					//let img_block = $(current_element).parent().find('.img-block');

					//console.log($(current_element).find('img').attr('src'))
					$(images[i]).parent().find('img').attr('src', $('#prabal-ajax-upload-image-loading-image').val());
					ajax(images[i].parentElement, formData);
				}	
			}
	})

	function ajax(current_element, formData)
	{
		var c = $(current_element)
		//c.find('img').hide()
		//current_element.style.background = loadingBackground
		$.ajax(
		{
			url: $('#prabal-ajax-upload-image-post').val(), // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			xhr: function() {
	                var myXhr = $.ajaxSettings.xhr();
	                if(myXhr.upload){
	                    myXhr.upload.addEventListener('progress',progress.bind(null, current_element), false);
	                }
	                return myXhr;
	        },
			success: function(data)   // A function to be called if request succeeds
			{
				console.log(data)
				//data = JSON.parse(data);	

				//console.log(data);

				if(data.status == true)
				{
					//$('#loading_image').html('<p>' + 'Images successfully uploaded' + '</p>');
					var images = '';
					var image_src = data.url;
					var image_name = data.filename;

					current_element.parentElement.classList.add('uploaded_image')
					
					current_element.innerHTML = '<button type="button" class="btn-image btn-rotate"><i class="fas fa-undo"></i></button><div class="img-block"><img src="'+ image_src +'"></div><input type="hidden" name="image[]" value="' + image_name + '"><input type="hidden" name="clockwise[]" class="pra_clockwise_value" value="0">';
					progressComplete(current_element)
					//current_element.parentElement.getElementsByClassName('progress')[0].style.display = 'none'
				}	
				else
				{
					c.find('img').show()
					var error_html = '';
					error_html += '<p>' + data.message + '</p>';

					$.each(data.data, function(key, value)
					{
						error_html += '<p>' + value + '</p>';
					});
					current_element.parentElement.getElementsByClassName('error-message')[0].innerHTML = error_html
					current_element.style.background = uploadBackground


					//$(form_element).parent().find('.error-message').html(error_html);
				}
			},
			error: function(request, status, error) {
		        console.log(request);
		        console.log(error);
		        console.log(status);
		    }
		});
	}
})