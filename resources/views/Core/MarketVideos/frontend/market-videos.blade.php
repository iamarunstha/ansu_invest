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
				<div class="col-md-9 w3ls_mutual_funds_grid1_jkjk">
					<div class="content">
						<h3 class="title-background">Market Videos</h3><br/>
						<?php $datas = array_chunk((array) $data->toArray()['data'], 3); ?>
						<?php

						?>
						@foreach($datas as $_data)
						<div class="row is-table-row">
							@foreach($_data as $d)
							<div class="col-md-4">
							<a href="{{ route('frontend-view-market-videos', $d['id']) }}"><h3>{{ $d['title'] }}</h3></a>
								<br/>
								<div class="embed-responsive embed-responsive-4by3">
									<iframe class="embed-responsive-item" src="{{ $d['asset'] }}" loading="lazy" allowfullscreen></iframe>
								</div>
								<br/>
								<p>{{ $d['summary'] }} <a href="{{ route('frontend-view-market-videos', $d['id']) }}">Read More</a></p>
							</div>
							@endforeach
						</div>
						@endforeach
						{{ $data->links() }}
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