@extends('frontend.main')

@section('content')
<!-- single -->
	<div class="news-original">
		<div class="container">
			<div class="agileinfo_news_original_grids w3_agile_news_market_grids">
				<div class="col-md-9 w3ls_mutual_funds_grid1_jkjk">
					<div class="w3_agileits_single_grids">
						<h3>{{ $data->title }}</h3>
						<h4>"{{  $data->summary }}"</h4>
						{{-- <div class="news-shar-buttons">
							<ul>
								<li>
									<div class="fb-like" data-href="https://www.facebook.com/w3layouts" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
									<script>(function(d, s, id) {
									  var js, fjs = d.getElementsByTagName(s)[0];
									  if (d.getElementById(id)) return;
									  js = d.createElement(s); js.id = id;
									  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.7";
									  fjs.parentNode.insertBefore(js, fjs);
									}(document, 'script', 'facebook-jssdk'));</script>
								</li>
								<li>
									<div class="fb-share-button" data-href="https://www.facebook.com/w3layouts" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.facebook.com%2Fw3layouts&amp;src=sdkpreparse">Share</a></div>
								</li>
							</ul>
						</div> --}}
						<div class="agileits_w3layouts_comments">
							<p><span>Posted By :</span> <a href="#"><i class="fa fa-user" aria-hidden="true"></i> {{ $data->posted_by }}</a> &nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> {{ $data->posted_at }} &nbsp;&nbsp;</p>
							@if($data->asset)
								@if($data->asset_type == 'image')
									<img src="{{ route('get-image-asset-type-filename', ['news', $data->asset]) }}" alt="{{ $data->title }}" class="img-responsive" />
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
						<div class="w3_agile_tags">
							<ul>

								<li>Tags</li>
								@foreach($tags as $t)
									@if($t)
									<li><a href="#">{{ $t }}</a></li>
									@endif
								@endforeach
							</ul>
						</div>
						<div class="w3_agile_tags">
							<ul>
								<li>Companies</li>
								@foreach($companies as $c)
									@if(in_array($c->id, $related_companies))
										<li><a href="#">{{ $c->company_name }}</a></li>
									@endif
								@endforeach
							</ul>
						</div>
					</div>
					<div class="w3_single_trade_comments">
						<h3><i class="fa fa-comments-o" aria-hidden="true"></i>Comments</h3>
						<div class="fb-comments" data-href="https://w3layouts.com/" data-width="100%" data-numposts="5"></div>
					</div>


					
				</div>
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>
<!-- //single -->
@stop