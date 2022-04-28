@extends('frontend.main')

@section('content')
<!-- single -->
	<div class="news-original">
		<div class="container">
			<div class="agileinfo_news_original_grids w3_agile_news_market_grids">
				<div class="col-md-9 w3ls_mutual_funds_grid1_jkjk">
					<div class="w3_agileits_single_grids">
						<h3>{{ $data->title }}</h3>
						<h4>{{  $data->summary }}</h4>
						
						<div class="agileits_w3layouts_comments">
							<p><span>Posted By :</span> <a href="#"><i class="fa fa-user" aria-hidden="true"></i> {{ $data->posted_by }}</a> &nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> {{ $data->posted_at }} &nbsp;&nbsp;</p>
							@if($data->asset)
								
								<div class="embed-responsive embed-responsive-16by9">
								  <iframe class="embed-responsive-item" src="{{ $data->asset }}"></iframe>
								</div>
								
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
<!-- //single -->
@stop