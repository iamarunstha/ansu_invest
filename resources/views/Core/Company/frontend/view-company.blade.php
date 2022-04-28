@extends('frontend.main')

@section('custom-css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" rel="stylesheet" type="text/css" media="all" />
@stop
@section('content')
<!-- single -->

	<div class="news-original">
		<div class="container">
			<div class="agileinfo_news_original_grids w3_agile_news_market_grids">
				<div class="col-md-9 w3ls_mutual_funds_grid1_jkjk">
					
					<div class="content"><h1>{{ $data->company_name }} ({{ $data->short_code }}) </h1></div>
					<br/>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div id="exTab1" class="col-md-12">	
									<ul  class="nav nav-pills">
										<li class="active"><a  href="#prabal-tab-1" data-toggle="tab">Quote</a></li>
										<li><a href="#prabal-tab-2" data-toggle="tab">Stock Analysis</a></li>
										<li><a href="#prabal-tab-3" data-toggle="tab">News</a></li>
									  	<li><a href="#prabal-tab-4" data-toggle="tab">Price vs Fair Value</a></li>
									  	<li><a href="#prabal-tab-5" data-toggle="tab">Trailing Returns</a></li>
									  	<li><a href="#prabal-tab-6" data-toggle="tab">Financials</a></li>
									</ul>
									<br/>
									<div class="tab-content clearfix">
										<div class="tab-pane active" id="prabal-tab-1">
							          		<div class="content">
								          		<div class="row">
								          			<div class="col-md-12">
								          				<div class="row">
								          					<div class="col-md-6 w3_equity_market_analysis_grid_sub w3_equity_market_agileits">
																<h4>Chart</h4>
																<div class="w3_agileits_gain_list">
								          							<canvas id="chart-1"></canvas>						
								          						</div>
								          					</div>
								          					<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
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
																			labels: [0,1,2,3,4,5,6,7,8,9,10],
																			datasets: [{
																				backgroundColor: '#ff0000',
																				borderColor: '#ff00ff',
																				data: [2,3,8,6,5,4,2,3,1,11,15],
																				label: 'Time',
																				fill: 'start'
																			}]
																		},
																		options: Chart.helpers.merge(options, {
																			title: {
																				text: 'Stock of ' + stock,
																				display: true
																			}
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
															<div class="col-md-6">
																<div class="w3_equity_market_analysis_grid_sub w3_equity_market_agileits">
																	<h4>Capital Goods and Engg.</h4>
																	<div class="w3_agileits_gain_list">
																		<table class="w3_agile_all_trade w3_agile_all_trade_funds w3_table_trade">
																			<tbody>
																				<tr>
																					<th class="w3_agileits_head"><b>Company</b></th>
																					<td class="w3_agileits_head1"><b>LTP(Rs.)</b></td>
																					<td class="w3_agileits_head1"><b>Chg.</b></td>
																					<td class="w3_agileits_head1"><b>Chg.(%)</b></td>
																					<td class="w3_agileits_head1"><b>Volume(000)</b></td>
																					<td class="w3_agileits_head1"><b>Value(Rs.)</b></td>
																				</tr>
																				<tr>
																					<th class="w3_agile_th">
																						<a href="single.html">JBM Auto</a>
																					</th>
																					<td class="agileits_w3layouts_td">223.00</td>
																					<td class="agileits_w3layouts_td">12.35</td>
																					<td class="agileits_w3layouts_td">5.86</td>
																					<td class="agileits_w3layouts_td">119.2</td>
																					<td class="agileits_w3layouts_td">26581600</td>
																				</tr>
																				<tr>
																					<th class="w3_agile_th">
																						<a href="single.html">Guj. Toolroom</a>
																					</th>
																					<td class="agileits_w3layouts_td">8.85</td>
																					<td class="agileits_w3layouts_td">0.42</td>
																					<td class="agileits_w3layouts_td">4.98</td>
																					<td class="agileits_w3layouts_td">0.01</td>
																					<td class="agileits_w3layouts_td">88.5</td>
																				</tr>
																				<tr>
																					<th class="w3_agile_th">
																						<a href="single.html">Lippi Systems</a>
																					</th>
																					<td class="agileits_w3layouts_td">17.31</td>
																					<td class="agileits_w3layouts_td">0.82</td>
																					<td class="agileits_w3layouts_td">4.97</td>
																					<td class="agileits_w3layouts_td">2</td>
																					<td class="agileits_w3layouts_td">34620</td>
																				</tr>
																				<tr>
																					<th class="w3_agile_th">
																						<a href="single.html">Sancia Global Infra</a>
																					</th>
																					<td class="agileits_w3layouts_td">0.81</td>
																					<td class="agileits_w3layouts_td">0.03</td>
																					<td class="agileits_w3layouts_td">3.85</td>
																					<td class="agileits_w3layouts_td">2.237</td>
																					<td class="agileits_w3layouts_td">1811.97</td>
																				</tr>
																				<tr>
																					<th class="w3_agile_th">
																						<a href="single.html">Mold-Tek Tech</a>
																					</th>
																					<td class="agileits_w3layouts_td">52.65</td>
																					<td class="agileits_w3layouts_td">0.45</td>
																					<td class="agileits_w3layouts_td">0.86</td>
																					<td class="agileits_w3layouts_td">2.464</td>
																					<td class="agileits_w3layouts_td">129729.6</td>
																				</tr>
																			</tbody>
																		</table>
																		<div style="padding-top:10px;font-size:13px;"><a href="single.html" style="color:#337ab7;">More »</a></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
								          		<br/>
								          		<br/>
								          		<div class="row">
								          			<div class="col-md-4">
								          				<div class="w3ls_gauge_chart">
															<h3><i class="fa fa-file-text-o" aria-hidden="true"></i>selector</h3>
															<h4>Fair Value</h4>
															<div class="js-gauge js-gauge--1 gauge"></div>
															<script type="text/javascript" src="{{ asset('frontend/js/raphael-min.js') }}"></script>
															<script type="text/javascript" src="{{ asset('frontend/js/kuma-gauge.jquery.js') }}"></script>
															
															<script>
																$('.js-gauge--1').kumaGauge({
																	value : 80,
																	showNeedle :false,
																	fill : '0-#fa4133:0-#fdbe37:50-#1cb42f:100',
																	label : {
																		display : true,
															            left : 'Low',
															            right : 'High',
															            fontFamily : 'Arial',
															            fontColor : '#000',
															            fontSize : '12',
															            fontWeight : 'normal'
																	},
																	title : {
															            display : true,
															            value : '',
															            fontFamily : 'Arial',
															            fontColor : '#000',
															            fontSize : '20',
															            fontWeight : 'normal'
															        },
																});
															</script>

														</div>
								          			</div>
								          			<div class="col-md-8">
								          				<div class="panel panel-default">
								          					<div class="panel-heading">Summary</div>
														  	<div class="panel-body">
														  		@if($summary)
														    	<div class="row">
														    		<div class="col-md-2" style="overflow: hidden;">
														    			<img src="{{ route('get-image-asset-type-filename', ['company-summary', $summary->asset]) }}" width="50px" height="50px" style="border-radius: 50%">
														    		</div>
														    		<div class="col-md-6">
														    			<p><strong>{{ $summary->posted_by }}</strong><p>
														    			<p>{{ $summary->analyst_post }}</p>
														    		</div>
														    		<div class="col-md-12">
														    			<strong>Analyst Note: </strong> by {{ $summary->posted_by }} | Updated at: {{ \App\HelperController::dateFormat($summary->updated_at, 'd M, Y', 'Y-m-d H:i:s') }}
														    		</div>
														    	</div>
														    	<br/>
														    	<div class="row">
														    		
														    		<div class="col-md-12">
														    			<h3 class="title-background" style="text-transform: none;">{{ $summary->title }}</h3>
														    			<p>{{ $summary->summary }}</p>
														    		</div>
														    	</div>
														    	@endif
														  	</div>
														  	<div class="panel-footer text-center">
														  		<a href="#">View Full Summary</a>
														  	</div>
								          				</div>
								          			</div>
								          		</div>
								          		<br/>
								          		<div class="row">
								          			<div class="col-md-12">
								          				<h3>Company Profile</h3><br/>
								          				<div class="panel panel-default">
															<div class="panel-heading">Business Description</div>
														  	<div class="panel-body">
														    	{!! nl2br($data->profile) !!}
														  	</div>
														</div>
								          			</div>
								          			<div class="col-md-6">
														<p><strong>Contact</strong></p>
													  	{!! nl2br($data->contact) !!}
								          			</div>
								          			<div class="col-md-6">
								          				<div class="row">
									          				<div class="col-md-6">
										          				<p><strong>Sector</strong></p>
																{{ $data->sector }}
															</div>
															<div class="col-md-6">
										          				<p><strong>Industry</strong></p>
																{{ $data->industry }}
															</div>
										          			<div class="col-md-6">
										          				<p><strong>Fiscal Year Ends</strong></p>
										          				{{ $data->fiscal_year_end }}
										          			</div>
										          		</div>
								          			</div>
								          		</div>
							          		</div>
										</div>
										<div class="tab-pane" id="prabal-tab-2">
						          			<h3>We use the class nav-pills instead of nav-tabs which automatically creates a background color for the tab</h3>
										</div>
						        		<div class="tab-pane" id="prabal-tab-3">
						          			<h3>We applied clearfix to the tab-content to rid of the gap between the tab and the content</h3>
										</div>
						          		<div class="tab-pane" id="prabal-tab-4">
						          			<h3>We use css to change the background color of the content to be equal to the tab</h3>
										</div>
										<div class="tab-pane" id="prabal-tab-5">
						          			<h3>We use css to change the background color of the content to be equal to the tab</h3>
										</div>
										<div class="tab-pane" id="prabal-tab-6">
						          			<h3>We use css to change the background color of the content to be equal to the tab</h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>
<!-- //single -->
@stop

@section('custom-js')

@stop