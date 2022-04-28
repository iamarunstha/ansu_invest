@extends('backend.main')

@if(Auth::user()->group_id == 1)
	@section('role-button')
	<form method="post" action="{{route('admin-register-permissions-post')}}">
		{{ csrf_field() }}
		<input type="hidden" name="module" value="Sector">
		<input type="submit" class="btn btn-success" value="Register/Unregister Permissions">
	</form>
	@endsection
@endif


@section('content')


<h1 class="h3 mb-4 text-gray-800">Balance Sheet</h1>

@include('Core.BalanceSheet.backend.modal.create-sector')
<button class="btn btn-info" data-toggle="modal" data-target="#myModal"> Create Sector</button>

<a class="btn btn-info" href="{{route('admin-balance-sheet-tabs-list-get')}}">Go to tabs</a>
<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>SN</th>
					<th>Sector</th>
					<th>Tabs</th>
					<th>Historical tabs</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($sectors as $index => $sector)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>{{ $sector->name }}</td>
					<td>
						<div class="btn-group">
                  			<button type="button" class="btn btn-info">Tabs</button>
                  			<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                  			<ul class="dropdown-menu">
								@foreach($sector_tabs as $tab)
									@if ($sector->id == $tab->sector_id && !$tab->tab->historical)
										<li><a href="{{ route('admin-balance-sheet-headings-get', [$sector->id, $tab->tab_id]) }}">{{$tab->tab->tab_name}}</a></li>
										<li><a href="#" class="a_submit_button" related-id="delete-{{$tab->sector_id}}-{{$tab->tab_id}}">Delete</a></li>
									@endif
								@endforeach
								@foreach ($permanent_tabs as $tab)
									<li><a href="{{route('admin-balance-sheet-headings-get', [$sector->id, $tab->id])}}">{{$tab->tab_name}}</a></li>
								@endforeach
								<li><a href="{{route('admin-valuation-headings-get', $sector->id)}}">
									Valuation
								</a></li>
								<li><a href="{{route('admin-performance-headings-get',$sector->id)}}">
									Performance
								</a></li>
							</ul>
						</div>
					</td>

						@if(!count($sector->tabs))
							<td>N/A</td>
						@else
							<td>
								<?php $count = 0; ?>
									@foreach($sector_tabs as $tab)
										@if ($sector->id == $tab->sector_id && $tab->tab->historical)
											<?php $count++; ?>
											@break
										@endif
									@endforeach
								@if($count)
								<div class="btn-group">
                  					<button type="button" class="btn btn-info">Historical Tabs</button>
                  					<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                  					<ul class="dropdown-menu">
										@foreach($sector_tabs as $tab)
											@if ($sector->id == $tab->sector_id && $tab->tab->historical)
												<li><a href="{{ route('admin-balance-sheet-headings-get', [$sector->id, $tab->tab_id]) }}">{{$tab->tab->tab_name}}</a></li>
												<li><a href="#" class="a_submit_button" related-id="delete-{{$tab->sector_id}}-{{$tab->tab_id}}">Delete</a></li>
											@endif
										@endforeach
									</ul>
								</div>
								@else
									N/A
								@endif
							</td>
						@endif
					<td>
						<a href="#" class="a_submit_button btn btn-danger" related-id="delete-{{$sector->id}}">Delete</a>
						<button class="btn btn-info" data-toggle="modal" data-target="#myEditModal{{$sector->id}}" >Edit</button>
						<button class="btn btn-info" data-toggle="modal" data-target="#myAddTabModal{{$sector->id}}" >Add tabs</button>
						<a href="{{ route('admin-performance-headings-get', $sector->id) }}" class="btn btn-info">Add Operating Performance</a>
						
					</td>	
				</tr>
				@endforeach
			</tbody>
		</table>
</div>

@foreach($sectors as $index => $sector)
	<form method="post" action="{{ route('admin-balance-sheet-sector-delete-post', $sector->id) }}" id="delete-{{ $sector->id }}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>
@endforeach
@foreach($sector_tabs as $index => $tab)
	<form method="post" action="{{ route('admin-balance-sheet-sector-tabs-delete-post', [$tab->sector_id, $tab->tab_id])}}" id="delete-{{$tab->sector_id}}-{{$tab->tab_id}}" class="prabal-confirm">
		{{ csrf_field() }}
	</form>
@endforeach

@foreach($sectors as $index => $sector)
<div id="myEditModal{{$sector->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Edit Sector</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-balance-sheet-sector-update-post', $sector->id) }}">
        	<div class="form-group">
	        	<label for="data[name]">Sector Name</label>
	        	<input type="text" name="data[name]" value="{{$sector->name}}">
	        </div>

	        <input type="submit" class="btn btn-success" value="Update">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
       </div>
    </div>
   </div>
</div>

<div id="myAddTabModal{{$sector->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3><strong>Add tab</strong></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('admin-balance-sheet-tabs-add-post', $sector->id) }}">
        	<div class="form-group">
	        	<h3>Select Tabs</h3>
	        	@if(count($tabs))
	        	<h4>Normal tabs:</h4>
	        	@foreach($tabs as $index=>$tab)
	        		<input type="checkbox" name="data[{{$tab->id}}]" value="{{$tab->id}}">{{$tab->tab_name}}{!! nl2br('
	        			') !!}
	        	@endforeach
	        	@endif
	        	@if(count($historical_tabs))
	        	<h4>Historical Tabs:</h4>
	        	@foreach($historical_tabs as $index=>$tab)
	        		<input type="checkbox" name="data[{{$tab->id}}]" value="{{$tab->id}}">{{$tab->tab_name}}{!! nl2br('
	        			') !!}
	        	@endforeach
	        	@endif	        	
	        </div>

	        <input type="submit" class="btn btn-success" value="Update">
        	<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

        	{{csrf_field()}}
        </form>
       </div>
    </div>
   </div>
</div>


@endforeach
@stop
@section('custom-js')
<script type="text/javascript">
$(function() {
	$(".open-modal").click(function(e){
		let data_target = $(this).attr('data-target')
		$('#' + data_target).modal('show');		
	})
	
})
</script>

@stop
