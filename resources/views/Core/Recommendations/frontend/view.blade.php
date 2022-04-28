@extends('frontend.main')
@section('custom-css')
	<style type="text/css">
		.clearfix  {
			margin-bottom: 1.5em;
		}

	</style>
@stop

@section('content')
	<div class="news-original">
		<div class="container">
			<div class="agileinfo_news_original_grids w3_agile_news_market_grids">
				<h3 class="title-background">Recommendations</h3><br/>
				<div class="col-md-9 w3_agile_news_market">
					<div class="w3_agileits_single_grids">
						<h3>{{ $data->title }}</h3>
						<h4>"{{  $data->summary }}"</h4>
						<div class="agileits_w3layouts_comments">
							<p><span>Posted By :</span> <a href="#"><i class="fa fa-user" aria-hidden="true"></i> {{ $data->posted_by }}</a> &nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> {{ $data->posted_at }} &nbsp;&nbsp;</p>
							@if($data->asset)
								@if($data->asset_type == 'image')
									<img src="{{ route('get-image-asset-type-filename', ['Recommendations', $data->asset]) }}" alt="{{ $data->title }}" class="img-responsive" />
								@else
									<div class="embed-responsive embed-responsive-16by9">
									  <iframe class="embed-responsive-item" src="{{ $data->asset }}"></iframe>
									</div>
								@endif
							@endif
						</div>
						<div class="agile_trade_figure_bottom">
							{!! $data->description !!}
						</div>
					</div>
				</div>
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>

	<script src="{{ asset('frontend/js/jquery.magnific-popup.js') }}" type="text/javascript"></script>
	<!--//pop-up-box -->
	<script>
		$(document).ready(function() {
		$('.w3_play_icon').magnificPopup({
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
@stop