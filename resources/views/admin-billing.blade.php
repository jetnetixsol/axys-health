<?php $page = 'admin-billing'; //require_once 'header.php'
?>
@include('header')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">

<style>
	.range {
		width: 100%;
		height: 100%;
	}

	.col-lg-6,
	.col-lg-4,
	.col-lg-2 {
		margin-bottom: 13px !important;
	}

	.device-rate-sec .amount {
		font-weight: bold;
		color: blueviolet;
		font-size: 16px;
	}

	.admin-billing-form {
		align-items: center;
		/* box-shadow: 0px 0px 14px -7px; */
		margin-bottom: 13px;
	}

	.admin-billing-form .fliter-search {
		margin-right: 10px !important;
	}

	.paid-select {
		width: 100% !important;
	}

	.clinic-billing .active-paid {
		background-color: #8155ec !important;
		padding: 2px 20px;
		text-transform: capitalize;
		color: white;
	}

	.clinic-billing .active-unpaid {
		background-color: red;
		padding: 2px 20px;
		text-transform: capitalize;
		color: white;
	}

	.nice-select {
		width: 100%;
	}

	.filter-btn {
		width: 100%;
		height: 100%;
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
							<h4>Billing Admin</h4>
							<!-- <div class="btn-group">
								@if(Auth::guard()->user()->role === 'admin')
								<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import"><i class="las la-cloud-download-alt"></i>Import CSV</button>
								@endif
							</div> -->
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
						<div class="card-body clinic-billing">
							<div class="panel tab-border">
								<header class="panel-heading custom-tab">
									<ul class="nav nav-tabs">
										@if($role === 'admin')
										<li class="nav-item"><a href="#stock" data-bs-toggle="tab" <?= $role == 'admin' ? 'class="active"' : '' ?>>Check Billing</a>
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
											<div class="row">
												<div class="col-lg-4 admin-billing-form">
													<select id="select-beast" name="clinic_name" class="form-control">
														<option value="">Clinic Name</option>
														@if(!empty($clinics))
														@foreach($clinics as $clinic)
														<option value="{{ $clinic['id'] }}">{{ $clinic['name'] }}</option>
														@endforeach
														@endif
													</select>
												</div>
												<div class="col-lg-6">
													<input type="text" name="daterange" class="form-control range" value="" id="stock-range" />
												</div>
												<div class="col-lg-2">
													<button type="button" class="btn btn-primary filter-btn" onclick="filterBilling()">Filter</button>
												</div>
											</div>
											<table id="billingTable" class="display table table-border" style="width:100%;">
												<thead>
													<tr>
														<th>From</th>
														<th>To</th>
														<th>Clinic Name</th>
														<th>Device Number</th>
														<th>Duration</th>
														<th>Paid</th>
														<th>Due</th>
														<!-- <th>Device Status</th> -->
														<th>Payment Status</th>
														<!-- <th>Action</th> -->
														<!-- <th>Actions</th> -->
													</tr>
												</thead>
												<tbody>
													@if(!empty($bills))
													@foreach($bills as $bill)
													<tr>
														<td>{{ isset($bill['from']) ? Date('Y-m-d',strtotime($bill['from'])) : '--' }}</td>
														<td>{{ isset($bill['to']) ? Date('y-m-d',strtotime($bill['to'])) : '--' }}</td>
														<td>{{ isset($bill['clinic_name']) ? $bill['clinic_name'] : '--' }}</td>
														{{-- @php $deviceIDs = json_decode($bill['device_ids'],true); @endphp
														<td>
															@if(!empty($deviceIDs))
															{{ count($deviceIDs) }}
														@else
														0
														@endif
														</td> --}}
														<td>{{ $bill['device_ids'] }}</td>
														@php $days = isset($bill['from']) && isset($bill['to']) ? intval(abs(Date(strtotime($bill['to'].'+1 day')) - Date(strtotime($bill['from']))) / 86400) : 0 @endphp
														<td>{{ $days }} Day{{ $days > 1 ? 's' : '' }}</td>
														<td>{{ $bill['paid'] }}$</td>
														<td>{{ $bill['charges'] - $bill['paid'] }}$</td>
														<!-- <td>
															<span class="active-unpaid">unactive</span>
														</td> -->
														<td>
															@if($bill['payment_status'] === "paid")
															<span class="active-paid">paid</span>
															@else
															<span class="active-unpaid">unpaid</span>
															@endif
														</td>
														<!-- <td> <button type="button" class="btn btn-primary">Notify</button></td> -->
													</tr>
													@endforeach
													@endif
													<!-- <tr>
														<td>11-Dec-2022</td>
														<td>Awad</td>
														<td>14</td>
														<td>7 Days</td>
														<td>24$</td>
														<td>
															<span class="active-unpaid">unactive</span>
														</td>
														<td>
															<span class="active-paid">paid</span>
															<span class="active-unpaid">unpaid</span>
														</td>
														<td> <button type="button" class="btn btn-primary">Notify</button></td>
													</tr>
													<tr>
														<td>11-Dec-2022</td>
														<td>Jinnah</td>
														<td>17</td>
														<td>8 Days</td>
														<td>142$</td>
														<td>
															<span class="active-paid">active</span>
															<span class="active-unpaid">unactive</span>
														</td>
														<td>
															<span class="active-paid">paid</span>
															<span class="active-unpaid">unpaid</span>
														</td>
														<td> <button type="button" class="btn btn-primary">Notify</button></td>
													</tr>
													<tr>
														<td>11-Dec-2022</td>
														<td>Indus</td>
														<td>18</td>
														<td>9 Days</td>
														<td>2$</td>
														<td>
															<span class="active-paid">active</span>
															<span class="active-unpaid">unactive</span>
														</td>
														<td>
															<span class="active-paid">paid</span>
															<span class="active-unpaid">unpaid</span>
														</td>
														<td> <button type="button" class="btn btn-primary">Notify</button></td>
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



<div class="modal fade" id="paid_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form method="post" action="{{ route('import') }}" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLongTitle"></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<select name="" id="" class="form-control paid-select">
						<option value="paid">Paid</option>
						<option value="unpaid">Unpaid</option>
					</select>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-dark" data-dismiss="paid_modal">Close</button> -->
					<button type="submit" class="btn btn-primary">Done</button>
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
<script src="{{ asset('assets/js/jquery.nice-select.js') }}"></script>

<!--	<script src="assets/js/jquery.dataTables.colResize.js"></script>-->

<script>
	$(document).ready(() => {
		$('#billingTable').DataTable();
	});

	function filterBilling() {
		var _token = '{{ csrf_token() }}';
		var work = 'filter_billing';
		var clinicID = $('[name="clinic_name"]').val();
		var dateRange = $('[name="daterange"]').val();
		$.ajax({
			url: "{{ route('ajax.perform') }}",
			dataType: "json",
			type: 'post',
			data: {
				_token,
				work,
				clinicID,
				dateRange,
			},
			success: (result) => {
				var dt = $('#billingTable').DataTable();
				dt.clear();
				if (result.data.tr.length > 0) {
					result.data.tr.forEach(function(val, index) {
						dt.row.add(val);
					});
				}
				dt.draw();
			},
			error: (err) => {
				console.log(err.responseJSON);
			}
		});
	}

	$(document).ready(function() {
		$('select').niceSelect();
	});

	$(function() {
		$('#stock-range').daterangepicker({
			opens: 'left',
			autoUpdateInput: false
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			// $(this).val(picker.startDate.format('MM/DD/YYYY'));
			// var table = 'assignedDevicesTable';
			// $.ajax({
			// 	url: '{{ route("devices.filter") }}',
			// 	type: 'post',
			// 	dataType: 'json',
			// 	data: {
			// 		_token,
			// 		startDate,
			// 		endDate,
			// 		table,
			// 	},
			// 	success: (result) => {
			// 		var dt = $('#assignedDevicesTable').DataTable();
			// 		dt.clear();
			// 		if (result.tr.length > 0) {
			// 			result.tr.forEach(function(val, index) {
			// 				dt.row.add(val);
			// 			});
			// 		}
			// 		dt.draw();
			// 	},
			// 	error: (err) => {
			// 		console.log(err.responseJSON);
			// 	}
			// });
			// console.log("!st A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});

		$('#stock-range').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
		});

		$('#stock-range').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});


		// $('#assign-range').daterangepicker({
		// 	opens: 'left',
		// }, function(start, end, label) {
		// 	var _token = '{{ csrf_token() }}';
		// 	var startDate = start.format('YYYY-MM-DD');
		// 	var endDate = end.format('YYYY-MM-DD');
		// 	var table = 'assignedDevicesTable2';
		// 	$.ajax({
		// 		url: '{{ route("devices.filter") }}',
		// 		type: 'post',
		// 		dataType: 'json',
		// 		data: {
		// 			_token,
		// 			startDate,
		// 			endDate,
		// 			table,
		// 		},
		// 		success: (result) => {
		// 			var dt = $('#assignedDevicesTable2').DataTable();
		// 			dt.clear();
		// 			if (result.tr.length > 0) {
		// 				result.tr.forEach(function(val, index) {
		// 					dt.row.add(val);
		// 				});
		// 			}
		// 			dt.draw();
		// 		},
		// 		error: (err) => {
		// 			console.log(err.responseJSON);
		// 		}
		// 	});
		// 	// console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		// });


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