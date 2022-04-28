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
				<h3 class="title-background">News</h3><br/>
				<div class="col-md-9 w3_agile_news_market">
					<div class="w3_agileits_news_blog row">
						
						@foreach($latest_news as $l)
						<div class="col-md-4 w3_agileits_news_blog1" style="overflow: hidden; ">
							<div style="height: 50px; overflow: hidden">
								<a href="{{ route('frontend-view-news', $l->id) }}">{{ $l->title }}</a>
							</div>
							<br/>
							<div style="height: 200px;">
							@if($l->asset)
								@if($l->asset_type == 'image')
									<a href="{{ route('frontend-view-news', $l->id) }}"><img src="{{ route('get-image-asset-type-filename', ['news', $l->asset]) }}" alt="{{ $l->title }}" class="img-responsive" style="height: 200px" /></a>
								@else
									<div class="embed-responsive embed-responsive-4by3">
									  <iframe class="embed-responsive-item" src="{{ $l->asset }}"></iframe>
									</div>
								@endif
							@else
								<a href="{{ route('frontend-view-news', $l->id) }}"><img src="{{ route('get-image-asset-type-filename', ['news', 'no-img']) }}" alt="No Image" class="img-responsive" /></a>
							@endif
							</div>
							<br/>
							<a href="{{ route('frontend-view-news', $l->id) }}"><p>{{ $l->summary }}</p></a>
							<br/>
						</div>
						@endforeach
						
						<div class="clearfix"> </div>
					</div>
					{{ $latest_news->links() }}
				</div>
				
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>
@stop