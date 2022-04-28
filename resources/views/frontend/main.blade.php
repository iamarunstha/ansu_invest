<!DOCTYPE html>
<html lang="en">
<head>
	<!-- <base href="http://localhost:8000/frontend/" target="_blank"> -->
<title>Trade Market a Corporate Business Category Flat Bootstrap Responsive Website Template | Home :: w3layouts</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Trade Market Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<link href="{{ asset('/frontend/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />
<style>
#preloader {
   width: 100%;
   height: 100%;
   top: 0;
   right: 0;
   bottom: 0;
   left: 0;
   background: #ffffff;
   z-index: 11000;
   position: fixed;
   display: block;
 display: flex;
 align-items: center;
 justify-content: center;
}
.preloader {
   position: absolute;
   margin: 0 auto;
   left: 1%;
   right: 1%;
   top: 45%;
   width: 95px;
   height: 95px;
   background: center center no-repeat none;
   background-size: 95px 95px;
   -webkit-border-radius: 50%;
   -moz-border-radius: 50%;
   -ms-border-radius: 50%;
   -o-border-radius: 50%;
   border-radius: 50%
}
.loader {
 position: absolute;
   margin: 0 auto;
   
}
</style>
<link href="{{ asset('/frontend/css/style.css') }}" rel="stylesheet" type="text/css" media="all" />
<link href="{{ asset('/frontend/css/custom.css') }}" rel="stylesheet" type="text/css" media="all" />

<link class="include" rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/jquery.jqplot.css') }}" />
<!-- calender -->
<link type="text/css" href="{{ asset('/frontend/css/jquery.simple-dtpicker.css') }}" rel="stylesheet" />
<!-- //calender -->
<!-- font-awesome icons -->
<link rel="stylesheet" href="{{ asset('/frontend/css/font-awesome.min.css') }}" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- //font-awesome icons -->
<!-- pop-up -->
<link href="{{ asset('/frontend/css/popuo-box.css') }}" rel="stylesheet" type="text/css" media="all" />
<!-- //pop-up -->
<!-- js -->
<script src="{{ asset('/frontend/js/jquery-1.11.1.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ asset('/frontend/js/jquery.marquee.min.js') }}"></script>
<!-- js -->

@yield('custom-css')

<link href="//fonts.googleapis.com/css?family=Muli:300,300i,400,400i" rel="stylesheet">
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="{{ asset('/frontend/js/move-top.js') }}"></script>
<script type="text/javascript" src="{{ asset('/frontend/js/easing.js') }}"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->

</head>
	
<body>
	<!-- <div id="preloader">
        <div class="loader">
            <img src="{{ asset('frontend/images/preloader.gif') }}" alt="preloader">
        </div>
    </div> -->

@include('frontend.include.header')
@include('frontend.include.menu')
@include('frontend.include.slider')
@include('frontend.include.nepse')
<!-- news-original -->
	@yield('content')

@include('frontend.include.footer')
<!-- script for marque -->


<script type="text/javascript">
       $(window).load(function() {
       $("#preloader").on(500).fadeOut();
       $(".loader").on(600).fadeOut("slow");
    });
</script>
	
<!-- //script for marque -->
<!-- area-chart -->

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script>
$(document).ready(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideDown("fast");
            $(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
        }
    );
});
</script>
<!-- //Bootstrap Core JavaScript -->
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->
	@yield('custom-js')
</body>
</html>