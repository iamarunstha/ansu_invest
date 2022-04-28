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
					
						<h1>List of Companies</h1>
						@foreach($company_types as $type)
							<h5 class="title-background">{{ $type->type }}</h5>
							<div class="row" >
							@foreach($type->getCompany as $company)
								<div class="col-md-3">
									<a href="{{ route('frontend-view-company', $company->id) }}"><p>{{ $company->company_name }} ({{ $company->short_code }})</p></a>
								</div>
							@endforeach
							</div>
							<br/>
						@endforeach
				</div>
				
				@include('frontend.include.right-side')
			</div>
		</div>
	</div>
<!-- //single -->
@stop

@section('custom-js')

@stop