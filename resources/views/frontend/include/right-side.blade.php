<div class="col-md-3 agileinfo_news_original_grids_right">
	<div class="w3layouts_add_market">
		<img src="{{ asset('frontend/images/sponsored-classified-post.png') }}" alt="Sponsored Classified Post" class="img-responsive" />
		<div class="w3layouts_add_market_pos">
			<h3>pay demat dues online</h3>
		</div>
	</div>
	<?php 
		$parameters['types'] = \App\Http\Controllers\Core\Company\CompanyTypeModel::with('getCompany')->get(); 
		$parameters['price'] = (new \App\Http\Controllers\Core\Company\CompanyPriceModel)->getPrice(\Carbon\Carbon::now()->format('Y-m-d'));
	?>
	<div class="w3_stocks w3l_your_stocks">
		<h3><i class="fa fa-stack-exchange" aria-hidden="true"></i>My Stocks</h3>
		<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
			<ul id="myTab2" class="nav nav-tabs nav-tabs1" role="tablist">
				@foreach($parameters['types'] as $index => $t)
				<li role="presentation" @if($index == 0) class="active" @endif><a href="#my-stock-{{ $t->id }}" id="my-stock-tab-{{ $t->id }}" role="tab" data-toggle="tab" aria-controls="control-my-stock-tab-{{ $t->id }}" @if($index == 0) aria-expanded="true" @endif>{{ $t->type }}</a></li>
				@endforeach
			</ul>
			<div id="myTabContent2" class="tab-content">
				@foreach($parameters['types'] as $index => $t)
					<div role="tabpanel" class="tab-pane fade in @if($index == 0) active @endif" id="my-stock-{{ $t->id }}" aria-labelledby="my-stock-tab-{{ $t->id }}">
						<div class="w3l_stocks">
							@foreach($t->getCompany as $d)
							<div class="w3l_stocks1">
								<a href="{{ route('frontend-view-company', $d->id) }}"><h4>{{ $d->company_name }}</h4></a>
								<p>
									@if(!is_null($parameters['price'][$d->id]['price'])) {{ $parameters['price'][$d->id]['price'] }} @else - @endif
									<i @if($parameters['price'][$d->id]['difference'] > 0) style="color:#00AA00;" @endif>
										@if(!is_null($parameters['price'][$d->id]['difference']))
										<span class="caret 
														@if($parameters['price'][$d->id]['difference'] > 0) 
															caret1 
														@endif">
										</span>
										{{ $parameters['price'][$d->id]['difference'] }}
										<label>
											({{ $parameters['price'][$d->id]['percentage'] }}%)
										</label>
										@endif
									</i>
								</p>
							</div>
							@endforeach
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
	<?php unset($parameters); ?>
	<div class="w3l_your_stocks">
		<h3><i class="fa fa-stack-exchange" aria-hidden="true"></i>Add My Stocks</h3>
		<form action="#" method="post">
			<span>
				<label>Name</label>
				<input type="text" name="Name" placeholder=" " required="">
			</span>
			<span>
				<label>Mobile</label>
				<input type="text" name="Mobile" placeholder=" " required="">
			</span>
			<span>
				<label>Email</label>
				<input type="email" name="Email" placeholder=" " required="">
			</span>
			<span>
				<label>Location</label>
				<input type="text" name="Location" placeholder=" " required="">
			</span>
			<span>
				<label>Pin</label>
				<input type="text" name="Pin" placeholder=" " required="">
			</span>
			<input type="submit" value="Submit Now">
		</form>
	</div>
	<div class="wthree_international">
		<img src="{{ asset('frontend/images/sponsored-classified-post.png') }}" alt="Sponsored Classified Post" class="img-responsive" />
		<div class="wthree_international_pos">
			<p>international markets</p>
		</div>
	</div>
	<div class="w3layouts_newsletter">
		<h3><i class="fa fa-envelope" aria-hidden="true"></i>Newsletter</h3>
		<form action="#" method="post">
			<input class="email" name="Email" type="email" placeholder="Email" required="">
			<input type="submit" value="Send">
		</form>
		<p>Trade market offers you a choice of email alerts on your investments for FREE!</p>
	</div>
</div>
<div class="clearfix"> </div>