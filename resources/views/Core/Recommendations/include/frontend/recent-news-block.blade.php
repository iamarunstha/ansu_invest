<div class="agile_commodity_videos">
	<h3><i class="fa fa-video-camera" aria-hidden="true"></i>Recent News</h3>
	<ul id="flexiselDemo1">	
		<li>
			<div class="agile_commodity_videos_grid">
				<div class="w3ls_market_video_grid1">
					<img src="images/41.jpg" alt=" " class="img-responsive" />
					<a class="w3_play_icon w3ls_play_icon" href="#small-dialog1">
						<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
					</a>
				</div>
				<div class="w3ls_market_video_grid2">
					<a href="single.html">Rupee closes stronger against US dollar at 67.40</a>
				</div>
			</div>
		</li>
		<li>
			<div class="agile_commodity_videos_grid">
				<div class="w3ls_market_video_grid1">
					<img src="images/42.jpg" alt=" " class="img-responsive" />
					<a class="w3_play_icon w3ls_play_icon" href="#small-dialog1">
						<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
					</a>
				</div>
				<div class="w3ls_market_video_grid2">
					<a href="single.html">As per industry sources, Cotton imports may see a new high this fiscal as the overall area where co</a>
				</div>
			</div>
		</li>
		<li>
			<div class="agile_commodity_videos_grid">
				<div class="w3ls_market_video_grid1">
					<img src="images/38.jpg" alt=" " class="img-responsive" />
					<a class="w3_play_icon w3ls_play_icon" href="#small-dialog1">
						<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
					</a>
				</div>
				<div class="w3ls_market_video_grid2">
					<a href="single.html">Growth in petroleum sector driven by growth in Indian eco</a>
				</div>
			</div>
		</li>
		<li>
			<div class="agile_commodity_videos_grid">
				<div class="w3ls_market_video_grid1">
					<img src="images/39.jpg" alt=" " class="img-responsive" />
					<a class="w3_play_icon w3ls_play_icon" href="#small-dialog1">
						<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
					</a>
				</div>
				<div class="w3ls_market_video_grid2">
					<a href="single.html">Gold slips as investors await central bank decisions</a>
				</div>
			</div>
		</li>
		<li>
			<div class="agile_commodity_videos_grid">
				<div class="w3ls_market_video_grid1">
					<img src="images/40.jpg" alt=" " class="img-responsive" />
					<a class="w3_play_icon w3ls_play_icon" href="#small-dialog1">
						<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
					</a>
				</div>
				<div class="w3ls_market_video_grid2">
					<a href="single.html">Crude prices trade flat, strong dollar weighs on gold</a>
				</div>
			</div>
		</li>
	</ul>
	<!-- pop-up-box -->  
		<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
	<!--//pop-up-box -->
	<div id="small-dialog1" class="mfp-hide">
		<iframe src="https://player.vimeo.com/video/6495257"></iframe>
	</div>
	<script>
		$(document).ready(function() {
		$('.w3ls_play_icon').magnificPopup({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-zoom-in'
		});
																		
		});
	</script>
	<script type="text/javascript">
		$(window).load(function() {
			$("#flexiselDemo1").flexisel({
				visibleItems: 4,
				animationSpeed: 1000,
				autoPlay: true,
				autoPlaySpeed: 3000,    		
				pauseOnHover: true,
				enableResponsiveBreakpoints: true,
				responsiveBreakpoints: { 
					portrait: { 
						changePoint:480,
						visibleItems: 2
					}, 
					landscape: { 
						changePoint:640,
						visibleItems:3
					},
					tablet: { 
						changePoint:768,
						visibleItems: 4
					}
				}
			});
			
		});
	</script>
	<script type="text/javascript" src="{{ asset('frontend/js/jquery.flexisel.js') }}"></script>
</div>