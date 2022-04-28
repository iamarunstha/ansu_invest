<!-- header -->
	<div class="header">
		<div class="w3ls_header_top">
			<div class="container">
				<div class="w3l_header_left">
					<ul class="w3layouts_header">
						<li class="w3layouts_header_list">
							<a href="#">Login To Trade</a><i>|</i>
						</li>
						<li class="w3layouts_header_list">
							<a href="#">FAQ</a><i>|</i>
						</li>
						<li class="w3layouts_header_list">
							<a href="#">Contact Us</a>
						</li>
					</ul>
				</div>
				<div class="w3l_header_right">
					<h2><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+(000) 123 456 678</h2>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="w3ls_header_middle">
			<div class="container">
				<div class="agileits_logo">
					<a href="{{ route('index') }}"><img src="{{ asset('frontend/images/logo.jpg') }}" class="img img-responsive" width="300px"></a>
					
				</div>
				<div class="agileits_search">
					<form action="{{ route('search-ajax-auto-suggest-search') }}" id="auto-suggest-form">
						<input name="auto_suggest_title" type="text" placeholder="Search" required="" class="auto">
						<select id="agileinfo_search" name="auto_suggest_type">
							<option value="company">Company</option>
							{{-- <option value="quotes">Expert Opnions</option>
							<option value="videos">Market Videos</option>
							<option value="news">News</option>
							<option value="recommendations">Recommendations</option> --}}
						</select>
					</form>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
<!-- //header -->
<script>
$(document).on('keyup.autocomplete', '.auto', function()
    {
      let type = $('input[name="auto_suggest_type"]').val();
      $(this).autocomplete({select: function( event, ui ) 
              {
                
              },
      source: "{{URL::route('search-ajax-auto-suggest-search')}}" + "?type=" + type,
      minLength: 2
      })
      .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
      					if(!item.is_all) {
      						return $( "<a href='" + item.url + "'>" )
		                      .append( "<li><span class='company-name'>" + item.label + "<span></li>")
		                      .appendTo( ul );		
      					} else {
      						return $( "<a href='" + item.url + "'>" )
		                      .append( "<li class='text-center' style='background: #EFEFEF'><span class='company-name'>" + item.label + "<span></li>")
		                      .appendTo( ul );		
      					}
            };
    });
</script>