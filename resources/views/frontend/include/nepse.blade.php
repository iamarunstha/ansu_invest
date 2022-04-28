<!-- banner-bottom -->
	<?php 
		$parameters['price'] = (new \App\Http\Controllers\Core\Company\CompanyPriceModel)->getPrice(\Carbon\Carbon::now()->format('Y-m-d'));
		$parameters['price'] = array_chunk($parameters['price'], 4, true);

	?>
	<div class="banner-bottom">
		<div class="panel panel-default agile_panel">
			<div class="panel-body agile_panel_body">
				<ul class="demo1">
					@foreach($parameters['price'] as $index => $price)
					<li class="news-item">
						<table class="w3_table_trade">
							<tr>
								@foreach($price as $company_id => $p)
								<td class="@if($index == 0) w3_agileits_td @endif demo1_w3_table_trade">
									<table class="agileits_w3layouts_table">
										<tr>
											<td style="color:#01A9CE; @if($p['difference'] < 0) text-transform:uppercase; @endif ">{{ $p['name'] }}</td>
										</tr>
										<tr>
											<td>@if(!is_null( $p['price'] )) 
													{{ $p['price'] }}
													<i @if($p['difference'] > 0) class="wthree_i" @endif>
														<span class="caret @if($p['difference'] > 0) caret1 @endif"></span>
														{{ $p['difference'] }}({{ $p['percentage'] }}%)
													</i>
												@else
													-
												@endif
											</td>
										</tr>
									</table>
								</td>
								@endforeach
							</tr>
						</table>
					</li>
					@endforeach
				</ul>
			</div>
		<div class="panel-footer"> </div>
		</div>
		<script type="text/javascript">
			$(function () {
				$(".demo1").bootstrapNews({
					newsPerPage: 1,
					autoplay: true,
					pauseOnHover:true,
					direction: 'up',
					newsTickerInterval: 3000,
					onToDo: function () {
						//console.log(this);
					}
				});
			});
		</script>
		<script src="{{ asset('frontend/js/jquery.bootstrap.newsbox.min.js') }}" type="text/javascript"></script>
		<?php  $news = \App\Http\Controllers\Core\News\NewsModel::whereBetween('posted_at', [\Carbon\Carbon::now()->subDay()->format('Y-m-d'), \Carbon\Carbon::now()->addDay()->format('Y-m-d')])->select('title', 'id')->get() ?>

		@if(!empty($news))
		<div class='agileinfo_marquee'>
			<div data-speed="10" class="marquee">
				<ul>
					@foreach($news as $n)
						<li><a href="{{ route('frontend-view-news', $n->id) }}">{{ $n->title }}<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a></li>
					@endforeach
				</ul>
			</div>
		</div>
		@endif

		<script type="text/javascript">
		  $('.marquee').marquee({
			duration: 10000,
			gap: 50,
			delayBeforeStart: 0,
			direction: 'left',
			duplicated: true,
			pauseOnHover: true
		});
		</script>
	</div>
<!-- //banner-bottom -->