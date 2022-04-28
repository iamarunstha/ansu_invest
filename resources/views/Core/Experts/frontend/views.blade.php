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
				<h3 class="title-background">Experts</h3><br/>
				<div class="col-md-9 w3_agile_news_market">
					<div class="w3_agileits_news_blog">
						@foreach($data as $d)
						<div class="w3layouts_commodity_news_grid">
							
							<div class="w3layouts_commodity_news_grid_left">
								<a href="{{ route('frontend-view-experts', $d->id) }}">{{ $d->title }}</a>
								<p>{{ $d->summary }}</p>
								<p><a href="{{ route('frontend-view-experts', $d->id) }}">Read More</a></p>
							</div>

							<div class="w3layouts_commodity_news_grid_right">
								<a href="{{ route('frontend-view-experts', $d->id) }}"><img src="{{ route('get-image-asset-type-filename', ['experts', $d->asset]) }}" alt="{{ $d->title }}" class="img-responsive"></a>
							</div>
							<div class="clearfix"> </div>
							<p class="agileits_w3layouts_para">{{ \App\HelperController::dateFormat($d->updated_at, 'd M, Y', 'Y-m-d H:i:s') }}</p>
						</div>
						<br/>
						@endforeach
						<div class="clearfix"> </div>
					</div>
				</div>
				{{ $data->links() }}
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