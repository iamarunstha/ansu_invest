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
					<div class="w3_agileits_news_blog">
						@foreach($data as $index => $d)
						<div class="w3layouts_commodity_news_grid">
							
							@if($index % 2 == 1)
								<div class="w3layouts_commodity_news_grid_left">
									<a href="{{ route('frontend-view-recommendations', $d->id) }}">{{ $d->title }}</a>
									<p>{{ $d->summary }}</p>
									<p><a href="{{ route('frontend-view-recommendations', $d->id) }}">Read More</a></p>
								</div>

								<div class="w3layouts_commodity_news_grid_right">
									@if($d->asset)

										@if($d->asset_type == 'image')	
											<a href="{{ route('frontend-view-recommendations', $d->id) }}"><img src="{{ route('get-image-asset-type-filename', ['recommendations', $d->asset]) }}" alt="{{ $d->title }}" class="img-responsive"></a>
										@else
											<a href="{{ route('frontend-view-recommendations', $d->id) }}">
												<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item" src="{{ $d->asset }}" allowfullscreen></iframe>
												</div>
											</a>
										@endif

									@else
										<a href="{{ route('frontend-view-recommendations', $d->id) }}"><img src="{{ route('get-image-asset-type-filename', ['recommendations', 'no-img']) }}" alt="{{ $d->title }}" class="img-responsive"></a>
									@endif
									
								</div>
							@else
								<div class="w3layouts_commodity_news_grid_left" style="@media (min-width: 415px) { width: 45%; margin-right: 10px; }">
									@if($d->asset)

										@if($d->asset_type == 'image')	
											<a href="{{ route('frontend-view-recommendations', $d->id) }}"><img src="{{ route('get-image-asset-type-filename', ['recommendations', $d->asset]) }}" alt="{{ $d->title }}" class="img-responsive"></a>
										@else
											<a href="{{ route('frontend-view-recommendations', $d->id) }}">
												<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item" src="{{ $d->asset }}" allowfullscreen></iframe>
												</div>
											</a>
										@endif

									@else
										<a href="{{ route('frontend-view-recommendations', $d->id) }}"><img src="{{ route('get-image-asset-type-filename', ['recommendations', 'no-img']) }}" alt="{{ $d->title }}" class="img-responsive"></a>
									@endif
									
								</div>
								<div class="w3layouts_commodity_news_grid_right" style="@media (min-width: 415px) { width: 50%; }">
									<a href="{{ route('frontend-view-recommendations', $d->id) }}">{{ $d->title }}</a>
									<p>{{ $d->summary }}</p>
									<p><a href="{{ route('frontend-view-recommendations', $d->id) }}">Read More</a></p>
								</div>
							@endif
							<div class="clearfix"> </div>
							<p class="agileits_w3layouts_para">{{ \App\HelperController::dateFormat($d->updated_at, 'd M, Y', 'Y-m-d H:i:s') }}</p>
						</div>
						<br/>
						@endforeach
						<div class="clearfix"> </div>
					</div>
					{{ $data->links() }}
				</div>
				
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>

@stop