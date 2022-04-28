@extends('frontend.main')

@section('custom-css')
<!-- left-chart -->
<script src="{{ asset('/frontend/js/jquery.flot.min.js') }}" type="text/javascript"></script> 
<script src="{{ asset('/frontend/js/jquery.flot.animator.min.js') }}" type="text/javascript"></script>
<!-- //left-chart -->

@stop

@section('content')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" rel="stylesheet" type="text/css" media="all" />
	<div class="news-original">
		<div class="container">
			<div class="agileinfo_news_original_grids">
				<div class="col-md-4 agileinfo_news_original_grids_left">
					<div class="w3layouts_market_movers">
						<h3><i class="fa fa-bar-chart" aria-hidden="true"></i>Market Movers</h3>
						<div class="content">
							
						</div>
						<?php $parameters['all_gainers_losers'] = (new \App\Http\Controllers\Core\Company\CompanyPriceModel)->getAllAndGainersAndLosers(); ?>
						<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab1" class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#home1" id="home1-tab" role="tab" data-toggle="tab" aria-controls="home1" aria-expanded="true">Company</a></li>
								<li role="presentation"><a href="#latest1" role="tab" id="latest1-tab" data-toggle="tab" aria-controls="latest1">All</a></li>
								<li role="presentation"><a href="#experts1" role="tab" id="experts1-tab" data-toggle="tab" aria-controls="experts1">Gainers/Losers</a></li>
							</ul>
							<div id="myTabContent1" class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="home1" aria-labelledby="home1-tab">
									<div class="w3_nifty">
										<div class="w3_sensex_left">
											<h4>nifty</h4>
										</div>
										<div class="w3_sensex_right">
											<p><b>8677.60</b><i class="wthree_i"><span class="caret caret1"></span>53.55(0.62%)</i></p>
										</div>
										<div class="clearfix"> </div>
										<div class="wrapper col-2" style="width:360px;height:350px;padding:5px;"><canvas id="chart-1"></canvas></div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="latest1" aria-labelledby="latest1-tab">
									<div class="agile_market_trade">
										<table class="w3_agile_all_trade w3_table_trade">
											@foreach($parameters['all_gainers_losers']['all'] as $company_id => $c)
											<tr>
												<th>
													<a href="{{ route('frontend-view-company', $c['company_id']) }}">
														<span>{{ $c['name'] }}</span>
													</a>
												</th>
												<td class="agileits_w3layouts_td">{{ $c['price'] }}</td>
												<td class="agileits_w3layouts_td" style= @if($c['percentage'] > 0 ) "color:#00AA00" @else "color:#ff5000" @endif>{{ $c['difference'] }}</td>
											</tr>
											@endforeach
										</table>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="experts1" aria-labelledby="experts1-tab">
									<div class="w3_agileits_gainers">
										<ul>
											<li><a href="{{ route('frontend-view-company', $c['company_id']) }}">Gainers</a></li>
											
										</ul>
										<div class="w3_agileits_gain_list">
											<table class="w3_agile_all_trade w3_table_trade">
												<tr>
													<th class="w3_agileits_head"><b>Company</b></th>
													<td class="w3_agileits_head1"><b>Price</b></td>
													<td class="w3_agileits_head1"><b>% Gain</b></td>
												</tr>
												@foreach($parameters['all_gainers_losers']['gainers'] as $company_id => $c)
												<tr>
													<th>
														<a href="{{ route('frontend-view-company', $c['company_id']) }}">{{ $c['name'] }}</a>
													</th>
													<td class="agileits_w3layouts_td">{{ $c['price'] }}</td>
													<td class="agileits_w3layouts_td"><span style="color:#00AA00">{{ $c['difference'] }}</span>
													</td>
												</tr>
												@endforeach
											</table>
										</div>
										<ul>
											<li><a href="#">Losers</a></li>
										</ul>
										<div class="w3_agileits_gain_list">
											<table class="w3_agile_all_trade w3_table_trade">
												<tr>
													<th class="w3_agileits_head"><b>Company</b></th>
													<td class="w3_agileits_head1"><b>Price</b></td>
													<td class="w3_agileits_head1"><b>% Loss</b></td>
												</tr>
												@foreach($parameters['all_gainers_losers']['losers'] as $company_id => $c)
												<tr>
													<th>
														<a href="{{ route('frontend-view-company', $c['company_id']) }}">{{ $c['name'] }}</a>
													</th>
													<td class="agileits_w3layouts_td">{{ $c['price'] }}</td>
													<td class="agileits_w3layouts_td"><span style="color:#ff5000">{{ $c['difference'] }}</span>
													</td>
												</tr>
												@endforeach
												
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="w3layouts_sponsored_links">
						<p>Sponsored Links</p>
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						  <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingOne">
							  <h4 class="panel-title asd">
								<a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								  <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span><i class="glyphicon glyphicon-menu-up" aria-hidden="true"></i>stocks to buy today
								</a>
							  </h4>
							</div>
							<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							  <div class="panel-body panel_text">
									Ut quis venenatis neque, sit amet sagittis lorem. Quisque dapibus dui 
									non urna suscipit ultricies.
							  </div>
							</div>
						  </div>
						  <?php $parameters['headings'] = [
						  	'Stocks Most Profitable',
						  	'Why invest in stock market',
						  	'Price of F1Soft increases 10 folds',
						  	'Stocks Most Profitable',
						  	'Why invest in stock market',
						  	'Price of F1Soft increases 10 folds',
						  	'Gold Price on rise'
						  ];

						  ?>
						  @for($i=0; $i<=6; $i++)
						  <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="sponsored-links-{{ $i }}">
							  <h4 class="panel-title asd">
								<a class="pa_italic collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-sponsored-links-{{ $i }}" aria-expanded="false" aria-controls="collapse-sponsored-links-{{ $i }}">
								  <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span><i class="glyphicon glyphicon-menu-up" aria-hidden="true"></i>{{ $parameters['headings'][$i] }}
								</a>
							  </h4>
							</div>
							<div id="collapse-sponsored-links-{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="sponsored-links-{{ $i }}">
							   <div class="panel-body panel_text">
									Ut quis venenatis neque, sit amet sagittis lorem. Quisque dapibus dui 
									non urna suscipit ultricies.
							  </div>
							</div>
						  </div>
						  @endfor
						</div>
					</div>
					<div class="agileits_newsletter">
						<h3>subscribe to our newsletter</h3>
						<form action="#" method="post">
							<input type="email" name="Email" placeholder="Email" required="">
							<input type="submit" value="Submit">
						</form>
					</div>
				</div>
				<div class="col-md-5 agileinfo_news_original_grids_left1">
					<div class="w3l_news_board">
						<h2><i class="fa fa-file-text-o" aria-hidden="true"></i>Newsboard</h2>
						<?php $parameters['newsboard'] = \App\Http\Controllers\Core\News\NewsModel::where('is_newsboard', 'yes')->where('is_active', 'yes')->first(); ?>
						@if($parameters['newsboard'])
						<div class="w3ls_tabs">
							<a href="{{ route('frontend-view-news', $parameters['newsboard']->id) }}"><h3>{{ $parameters['newsboard']->summary }}</h3></a>
						</div>
						@endif
						<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Top News</a></li>
								<li role="presentation"><a href="#latest" role="tab" id="latest-tab" data-toggle="tab" aria-controls="latest">Latest</a></li>
								<li role="presentation"><a href="#read" role="tab" id="read-tab" data-toggle="tab" aria-controls="read">Most Read</a></li>
							</ul>
							<div id="myTabContent" class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
									<?php $parameters['top_news'] = (new \App\Http\Controllers\Core\News\NewsModel)->getTopNews(); ?>
									<ol class="w3_tab_list">
										@foreach($parameters['top_news'] as $n)
											<li><span>{{ \App\HelperController::dateFormat($n->posted_at, 'd M, Y') }}</span> <a href="{{ route('frontend-view-news', $n->id) }}">{{ $n->title }}</a></li>
										@endforeach
									</ol>
									<div class="panel-footer text-center">
										<a href="{{ route('frontend-news') }}">View All</a>
									</div>
								</div>
								
								<div role="tabpanel" class="tab-pane fade" id="latest" aria-labelledby="latest-tab">
									<ol class="w3_tab_list">
										<?php $parameters['latest_news'] = (new \App\Http\Controllers\Core\News\NewsModel)->getLatestNews(); ?>
										@foreach($parameters['latest_news'] as $n)
											<li><span>{{ \App\HelperController::dateFormat($n->posted_at, 'd M, H:i') }}</span> <a href="{{ route('frontend-view-news', $n->id) }}">{{ $n->title }}</a></li>
										@endforeach
									</ol>
									<div class="panel-footer text-center">
										<a href="{{ route('frontend-news') }}">View All</a>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane fade" id="read" aria-labelledby="read-tab">
									<ol class="w3_tab_list">
										<?php $parameters['most_read'] = (new \App\Http\Controllers\Core\News\NewsModel)->getMostRead(); ?>
										@foreach($parameters['most_read'] as $n)
											<li><span>{{ \App\HelperController::dateFormat($n->posted_at, 'd M, Y') }}</span> <a href="{{ route('frontend-view-news', $n->id) }}">{{ $n->title }}</a></li>
										@endforeach
									</ol>
									<div class="panel-footer text-center">
										<a href="{{ route('frontend-news') }}">View All</a>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<br/>
					
					<div class="w3layouts_research">
						<h3><i class="fa fa-eye" aria-hidden="true"></i>Views & Recommendations</h3>
						<?php $parameters['recommendations'] = \App\Http\Controllers\Core\Recommendations\RecommendationsModel::where('is_active', 'yes')->where('is_top_recommendations', 'yes')->orderBy('ordering', 'ASC')->take(9)->get(); ?>
						<div class="w3layouts_research_grid scrollbar" id="style-2">
							
							@foreach($parameters['recommendations'] as $r)
							<div class="w3layouts_research_grid1">
								<a href="{{ route('frontend-view-recommendations', $r->id) }}"><h4>{{ $r->title }}</h4></a>
								<p>{{ $r->summary }} <a href="{{ route('frontend-view-recommendations', $r->id) }}">more&nbsp;»</a><span>{{ \App\HelperController::dateFormat($r->updated_at, 'd M, Y', 'Y-m-d H:i:s') }}</span></p>
							</div>
							@endforeach
						</div>
						<div class="row" style="padding: .5em 1em">
							<div class="col-md-12 text-center" style="background-color: #f5f5f5">
								<a href="{{ route('frontend-recommendations') }}">View All</a>
							</div>
						</div>

					</div>
					
					<div class="agile_chat">
						<h3><img class="blink_me" src="{{ asset('frontend/images/1.png') }}" alt="Expert Opinions" />Expert Opinions</h3>
						<div class="agile_chat_grids">
							<?php $parameters['experts'] = \App\Http\Controllers\Core\Experts\ExpertsModel::where('feature', 'yes')->where('is_active', 'yes')->orderBy('ordering', 'ASC')->take(3)->get(); ?>

							@foreach($parameters['experts'] as $e)
							<div class="agile_chat_grid">
								<div class="agile_chat_grid1">
									<img src="{{ route('get-image-asset-type-filename', ['experts', $e->asset]) }}" alt="{{ $e->title }}" class="img-responsive" />
									<div class="agile_p_mask">
										<h4>{{ $e->posted_by }}</h4>
									</div>
								</div>
								<p><a href="{{ route('frontend-view-experts', $e->id) }}">{{ $e->summary }}</a></p>
							</div>
							@endforeach
							<div class="clearfix"> </div>
						</div>
						<div class="row" style="padding: .5em 1em">
							<div class="col-md-12 text-center" style="background-color: #f5f5f5">
								<a href="{{ route('frontend-experts') }}">View All</a>
							</div>
						</div>
					</div>
					<div class="w3ls_market_videos">
						<h3><i class="fa fa-video-camera" aria-hidden="true"></i>Market Videos</h3>
						<?php $parameters['videos'] = \App\Http\Controllers\Core\MarketVideos\MarketVideosModel::where('is_active', 'yes')->orderBy('ordering', 'ASC')->where('feature', 'yes')->take(3)->get(); ?>
						
						@foreach($parameters['videos'] as $v)
						<div class="w3ls_market_video_grid">
							<div class="w3ls_market_video_grid1">
								<strong>{{ $v->title }}</strong>
								<div style="height: 94px; width: 142px;">
									<img src="{{ asset('frontend/images/logo.jpg') }}" alt="{{ $v->title }}" style="position: absolute;
  																									top: 50%;
  																									left: 50%;
  																									width: 100%;
  																									transform: translate(-50%, -50%);" />
								</div>
								<a class="w3_play_icon" href="{{ route('frontend-view-market-videos', $v->id) }}">
									<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
								</a>
							</div>
							<div class="w3ls_market_video_grid2">
								<a href="{{ route('frontend-view-market-videos', $v->id) }}">{{ $v->summary }}</a>
							</div>
						</div>
						@endforeach
						<div class="row" style="padding: .5em 1em">
							<div class="col-md-12 text-center" style="background-color: #f5f5f5">
								<a href="{{ route('frontend-market-videos') }}">View All</a>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>
<!-- //news-original -->
@stop

@section('custom-js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<script type="text/javascript">
    $(window).load(function () {
	
    });

    var presets = window.chartColors;
		var inputs = {
			min: -100,
			max: 100,
			count: 8,
			decimals: 2,
			continuity: 1
		};

		var options = {
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			plugins: {
				filler: {
					propagate: false
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						autoSkip: false,
						maxRotation: 0
					}
				}]
			}
		};

		//[false, 'origin', 'start', 'end'].forEach(function(boundary, index) {
			let stock = 'Apple'
			new Chart('chart-1', {
				type: 'line',
				data: {
					labels: [0,1,2,3,4,5,6,7,8,9,10, 11],
					datasets: [{
						//backgroundColor: '#ff0000',
						borderColor: '#ff00ff',
						data: [-2,3000,3253,3100,2000,4000,3222,2145,1052,3120,4222, 4101],
						label: 'Time',
						fill: 'start'
					}]
				},
				options: Chart.helpers.merge(options, {
					/*title: {
						text: 'Stock of ' + stock,
						display: true
					}*/
		        }),

		        animation: {
		        	duration: 1000,
		        	easing: 'easeInSine'
		        }
				
			});

    Chart.helpers.each(Chart.instances, function(chart) {
		//alert(chart.chart.canvas.id)
		//chart.options.elements.line.tension = value ? 0.4 : 0.000001;
		data = [['A',4], ['B',3], ['C',10] ]

		data.forEach(function (value, label) {
			setTimeout(function(){  }, 100);
			chart.data.labels.push(value[0]);
		
			chart.data.datasets.forEach((dataset) => {
    			dataset.data.push(value[1]);
			})	
		})
		

		chart.update();
	});
</script> 
	
	<script>
		var presets = window.chartColors;
		var inputs = {
			min: -100,
			max: 100,
			count: 8,
			decimals: 2,
			continuity: 1
		};

		var options = {
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			plugins: {
				filler: {
					propagate: false
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						autoSkip: false,
						maxRotation: 0
					}
				}]
			}
		};

		//[false, 'origin', 'start', 'end'].forEach(function(boundary, index) {
			let stock = 'Apple'
			new Chart('chart-1', {
				type: 'line',
				data: {
					labels: [0,1,2,3,4,5,6,7,8,9,10, 11],
					datasets: [{
						//backgroundColor: '#ff0000',
						borderColor: '#ff00ff',
						data: [-2,3,8,6,5,4,2,3,1,11,15, 30],
						label: 'Time',
						fill: 'start'
					}]
				},
				options: Chart.helpers.merge(options, {
					/*title: {
						text: 'Stock of ' + stock,
						display: true
					}*/
		        }),

		        animation: {
		        	duration: 1000,
		        	easing: 'easeInSine'
		        }
				
			});


			Chart.helpers.each(Chart.instances, function(chart) {
				//alert(chart.chart.canvas.id)
				//chart.options.elements.line.tension = value ? 0.4 : 0.000001;
				data = [['A',4], ['B',3], ['C',10] ]

				data.forEach(function (value, label) {
					setTimeout(function(){  }, 100);
					chart.data.labels.push(value[0]);
    			
	    			chart.data.datasets.forEach((dataset) => {
	        			dataset.data.push(value[1]);
					})	
				})
				

				chart.update();
			});

	</script>
@stop