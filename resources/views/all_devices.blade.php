<?php $page = 'all_devices'; //require_once 'header.php'
?>
@include('header')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dropify.min.css') }}">
<style>
	.range{
		width: 40%;
	}
</style>

<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-sm-12">
				<form action="">
					<div class="card card-box">
						<div class="card-head">
							<h4>All Devices</h4>
							<div class="btn-group">
								@if(Auth::guard()->user()->role === 'admin')
								<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import"><i class="las la-cloud-download-alt"></i>Import CSV</button>
								@endif
							</div>
						</div>
						<div style="margin: 8px;">
							@if(session()->has('success'))
							<div class="alert alert-success">
								{{ session()->get('success') }}
							</div>
							@endif

							@if(session()->has('fail'))
							<div class="alert alert-danger">
								{{ session()->get('fail') }}
							</div>
							@endif

							@if($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
							@endif
						</div>
						@php $role = Auth::guard()->user()->role; @endphp
						<div class="card-body">
							<div class="panel tab-border">
								<header class="panel-heading custom-tab">
									<ul class="nav nav-tabs">
										@if($role === 'admin')
										<li class="nav-item"><a href="#stock" data-bs-toggle="tab" <?= $role == 'admin' ? 'class="active"' : '' ?>>In Stock</a>
										</li>
										@endif
										@if($role === 'admin' || $role === 'clinic' || $role === 'doctor')
										<li class="nav-item"><a href="#assigned" data-bs-toggle="tab" <?= $role != 'admin' ? 'class="active"' : ''  ?>>Assigned</a>
										</li>
										@endif
									</ul>
								</header>
								<div class="panel-body">

									<!-- <div class="row">
									<div class="col-md-3 offset-md-9">
									</div>
								</div> -->
									<div class="tab-content">
										<div class="tab-pane  <?= $role == 'admin' ? "active" : '' ?>" id="stock">
											<input type="text" name="daterange" class="form-control range" id="stock-range" value=""/>
											<table id="assignedDevicesTable" class="display table table-border" style="width:100%;">
												<thead>
													<tr>
														<th>Created At</th>
														<th>Serial Number</th>
														<th>IMEI</th>
														<th>Model Number</th>
														<th>Signal</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													@if(!empty($devices))
													@foreach($devices as $device)
													<tr>
														<td>{{ Date('Y-m-d',strtotime($device['created_at'])) }}</td>
														<td>{{ $device['serial_number'] }}</td>
														<td>{{ $device['imei'] }}</td>
														<td>{{ $device['model_number'] }}</td>
														@php if($device['signal'] < 10){ echo '<td class="txt-red">Weak</td>' ; }else if($device['signal']>= 10 && $device['signal'] < 20){ echo '<td>Medium</td>' ; }else if($device['signal']>= 20){
																echo '<td>Strong</td>';
																}
																@endphp
																<td><a href="{{ route('device.single',['id'=>$device['id']]) }}" class="btn btn-primary"><i class="las la-eye"></i></a></td>
													</tr>
													@endforeach
													@endif
												</tbody>
											</table>
										</div>
										<div class="tab-pane  <?= $role != 'admin' ? "active" : '' ?>" id="assigned">
											<input type="text" name="daterange" class="form-control range" id="assign-range" value=""/>
											<table id="assignedDevicesTable2" class="display table table-border" style="width: 100%">
												<thead>
													<tr>
														<th>Created At</th>
														<th>Serial Number</th>
														<th>IMEI</th>
														<th>Model Number</th>
														<th>Signal</th>
														<!-- <th>Status</th> -->
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													@if(!empty($assigned_devices))
													@foreach($assigned_devices as $assigned_device)
													<tr>
														<td>{{ Date('Y-m-d',strtotime($assigned_device['created_at'])) }}</td>
														<td>{{ $assigned_device['serial_number'] }}</td>
														<td>{{ $assigned_device['imei'] }}</td>
														<td>{{ $assigned_device['model_number'] }}</td>
														@php if($assigned_device['signal'] < 10){ echo '<td class="txt-red">Weak</td>' ; }else if($assigned_device['signal']>= 10 && $assigned_device['signal'] < 20){ echo '<td>Medium</td>' ; }else if($assigned_device['signal']>= 20){
																echo '<td>Strong</td>';
																}
																@endphp
																<!-- <td><div class="red-pri">Not Active</div></td> -->
																<td><a href="{{ route('device.single',['id'=>$assigned_device['id']]) }}" class="btn btn-primary"><i class="las la-eye"></i></a></td>
													</tr>
													@endforeach
													@endif
													<!-- <tr>
														<td>4/19/2022</td>
														<td>11F215200240</td>
														<td>864351056302161</td>
														<td>LS802-GP</td>
														<td class="txt-red">Weak</td>
														<td><div class="grn-pri">Active</div></td>
														<td><a href="view_device.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
													</tr>
													<tr>
														<td>4/19/2022</td>
														<td>11F215200240</td>
														<td>864351056302161</td>
														<td>LS802-GP</td>
														<td>Medium</td>
														<td><div class="grn-pri">Active</div></td>
														<td><a href="view_device.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
													</tr>
													<tr>
														<td>4/19/2022</td>
														<td>11F215200240</td>
														<td>864351056302161</td>
														<td>LS802-GP</td>
														<td class="txt-red">Weak</td>
														<td><div class="red-pri">Not Active</div></td>
														<td><a href="view_device.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
													</tr> -->
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- end page content -->

<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form method="post" action="{{ route('import') }}" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLongTitle">Upload CSV File</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="file" accept=".csv" name="csv_file" class="dropify" data-height="300" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="las la-cloud-download-alt"></i> Import</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- data tables -->
<!--	<script src="assets/js/ColReorderWithResize.js"></script>-->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/ColReorderWithResize.js') }}"></script>
<!--	<script src="assets/data/table-data.js"></script>-->
<script src="{{ asset('assets/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<!--	<script src="assets/js/jquery.dataTables.colResize.js"></script>-->

<script>
	$(function() {
		$('#stock-range').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			var table = 'assignedDevicesTable';
			$.ajax({
				url: '{{ route("devices.filter") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					startDate,
					endDate,
					table,
				},
				success: (result) => {
					var dt = $('#assignedDevicesTable').DataTable();
					dt.clear();
					if (result.tr.length > 0) {
						result.tr.forEach(function(val, index) {
							dt.row.add(val);
						});
					}
					dt.draw();
				},
				error: (err) => {
					console.log(err.responseJSON);
				}
			});
			// console.log("!st A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});

		$('#assign-range').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			var table = 'assignedDevicesTable2';
			$.ajax({
				url: '{{ route("devices.filter") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					startDate,
					endDate,
					table,
				},
				success: (result) => {
					var dt = $('#assignedDevicesTable2').DataTable();
					dt.clear();
					if (result.tr.length > 0) {
						result.tr.forEach(function(val, index) {
							dt.row.add(val);
						});
					}
					dt.draw();
				},
				error: (err) => {
					console.log(err.responseJSON);
				}
			});
			// console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});


	});

	$(document).ready(function() {
		var table = $('#assignedDevicesTable').DataTable();
	});

	$(document).ready(function() {
		var table = $('#assignedDevicesTable2').DataTable();
	});
	$('.dropify').dropify({
		messages: {
			'default': 'Drag and drop a file here or click',
			'replace': 'Drag and drop or click to replace',
			'remove': 'Remove',
			'error': 'Ooops, something wrong happended.'
		}
	});


	//		$(document).ready(function () {
	//			$('#example').DataTable({
	//				scrollX: true,
	//				colResize: {
	//					isEnabled: true,
	//					hoverClass: 'dt-colresizable-hover',
	//					hasBoundCheck: true,
	//					minBoundClass: 'dt-colresizable-bound-min',
	//					maxBoundClass: 'dt-colresizable-bound-max',
	//					saveState: true,
	//					isResizable: function (column) {
	//						return column.idx !== 2;
	//					},
	//					stateSaveCallback: function (settings, data) {
	//						let stateStorageName = window.location.pathname + "/colResizeStateData";
	//						localStorage.setItem(stateStorageName, JSON.stringify(data));
	//					},
	//					stateLoadCallback: function (settings) {
	//						let stateStorageName = window.location.pathname + "/colResizeStateData",
	//							data = localStorage.getItem(stateStorageName);
	//						return data != null ? JSON.parse(data) : null;
	//					}
	//				}
	//			});
	//		});
</script>
@include('footer')
<?php //require_once 'footer.php'
?>