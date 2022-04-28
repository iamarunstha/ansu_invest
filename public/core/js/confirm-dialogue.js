var submit_check = false;

/*$('form').on('disable-event', function(e){
	let elements = this.elements
	elements.forEach(function(item, index){
		item.disable=true
	});
})*/

$(document).on('submit', '.prabal-confirm', function(e)
{
	if(submit_check) {
		//alert('Action in progress, Please wait!')
		e.preventDefault();
		return false;
	}

	let confirm_text = 'Do you confirm this action'
	if(this.hasAttribute("confirm-text")) {
		confirm_text = this.getAttribute('confirm-text')
	}
	let r = confirm(confirm_text);
	if(r == true) {
		submit_check = true;
		return true
	}
	else {
		submit_check = false;
		return false
	}
})

$(document).on('submit', '.submit-once', function(e)
{
	if(submit_check) {
		//alert('Action in progress, Please wait!')
		e.preventDefault();
		return false;
	} else {
		submit_check = true;
	}
})

$(document).on('click', '.prabal-checkbox-submit', function(e) {
	e.preventDefault()
	let related_id = this.getAttribute('related-id');
	let current_element = $(this);
	var ids = []
	let related_form = this.getAttribute('related-form')
	$('#' + related_id).find('.id-checkbox').each(function()
	{
		if($(this).is(':checked')) {
			ids.push($(this).attr('value'))
		}
	})

	if(ids.length == 0) {
		alert('No items selected!')
		return false;
	}

	$('#' + related_form).find('.place-for-id-checkbox').html('')
	for (const id of ids){
	  $('#' + related_form).find('.place-for-id-checkbox').append("<input type='hidden' name='rid[]' value='" + id +"'>")
	}

	$('#' + related_form).submit()
})

$(document).on('click', '.a_submit_button', function(e){
  e.preventDefault();
  let related_id = $(this).attr('related-id')
  $('#' + related_id).submit();
})