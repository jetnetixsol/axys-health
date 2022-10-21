<?php $page = 'billing'; //require_once 'header.php'
?>
@include('header')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">

<style>
	.range {
		width: 40%;
		position: absolute;
		z-index: 999;
	}

	.device-rate-sec .amount {
		font-weight: bold;
		color: blueviolet;
		font-size: 16px;
	}

	.device-rate-sec {
		width: 100%;
		height: 50px;
		display: flex;
		justify-content: start;
		padding-left: 15px;
		border: 1px solid #d5d5d5;
		align-items: center;
		box-shadow: 0px 0px 14px -7px;
		margin-bottom: 13px;
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
</style>

<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-sm-12">
				<form action="">
					<div class="card card-box">
						<div class="card-head">
							<h4>Billing</h4>
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
										{{-- @if($role === 'admin') --}}
										<li class="nav-item"><a href="#stock" data-bs-toggle="tab" class="active">Active Devices</a>
										</li>
										{{-- @endif --}}
										{{-- @if($role === 'admin' || $role === 'clinic' || $role === 'doctor') --}}
										<li class="nav-item"><a href="#assigned" data-bs-toggle="tab">Inactive Devices</a>
										</li>
										{{-- @endif --}}
									</ul>
								</header>
								<div class="panel-body">

									<!-- <div class="row">
									<div class="col-md-3 offset-md-9">
									</div>
								</div> -->
									<div class="tab-content">
										<div class="tab-pane active" id="stock">
											<div class="device-rate-sec">
												<p class="m-0">Active Device Per Day Cost <span class="amount">1$</span></p>
											</div>
											<input type="text" name="daterange1" class="form-control range" id="stock-range" value="" />
											<table id="activeDevices" class="display table table-border" style="width:100%;">
												<thead>
													<tr>
														<th>Start Date</th>
														<th>Serial Number</th>
														<th>Total Payment</th>
														<th>Duration</th>
														<!-- <th>Payment Status</th>
														<th>Pay Now</th> -->
														<!-- <th>Actions</th> -->
													</tr>
												</thead>
												@if(!empty($active_devices))
												@foreach($active_devices as $active_device)
												<tr>
													<td>{{ Date('Y-m-d',strtotime($active_device['start_date'])) }}</td>
													<td>{{ $active_device['serial_number'] }}</td>
													<td>{{ isset($active_device['total_payment']) ? $active_device['total_payment'] : 0 }}$</td>
													<td>{{ $active_device['duration'] }} Days</td>
												</tr>
												@endforeach
												@endif
												<!-- <td>
														<span class="active-paid">paid</span>
														<span class="active-unpaid">unpaid</span>
													</td>
													<td> <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paid_modal">$ Paid</button></td> -->
												</tbody>
											</table>
										</div>
										<div class="tab-pane" id="assigned">
											<input type="text" name="daterange2" class="form-control range" id="assign-range" value="" />
											<table id="inActiveDevices" class="display table table-border" style="width: 100%">
												<thead>
													<tr>
														<th>Start Date</th>
														<th>Serial Number</th>
														<th>Total Payment</th>
														<th>Paid Amount</th>
														<th>Due</th>
														<th>Payment Status</th>
														<!-- <th>Pay Now</th> -->
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													@if(!empty($ready_to_bill))
													@foreach($ready_to_bill as $bill)
													<tr>
														<td>{{ Date('Y-m-d',strtotime($bill['from'])) }}</td>
														<td>{{ $bill['device_ids'] }}</td>
														<td>{{ $bill['charges'] }}$</td>
														<td>{{ $bill['paid'] }}$</td>
														<td>{{ $bill['charges'] - $bill['paid'] }}$</td>
														<td>
															@if($bill['payment_status'] === 'paid')
															<span class="active-paid">paid</span>
															@elseif($bill['payment_status'] === 'unpaid')
															<span class="active-unpaid">unpaid</span>
															@endif
														</td>
														<td>
															@if($bill['payment_status'] === 'unpaid' && isset($stripeConfig["stripe_key"]))
															<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paid_modal" onclick="(function(){ $('#b_i').val('<?= urlencode(base64_encode($bill['id'])) ?>'); }())">Pay Now</button>
															@elseif($bill['payment_status'] === 'unpaid' && !isset($stripeConfig["stripe_key"]))
															<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#keys_alert">Pay Now</button>
															@elseif($bill['payment_status'] === 'paid')
															<button type="button" class="btn btn-primary"><i class="fa fa-check"></i>Paid</button>
															@endif
														</td>
													</tr>
													@endforeach
													@endif
													<!-- <td> <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paid_modal">$ Paid</button></td> -->
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
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLongTitle">Pay Now</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form method="post" id="paymentForm" action="{{ route('stripe.pay') }}" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<!-- <select name="" id="" class="form-control paid-select">
						<option value="paid">Paid</option>
						<option value="unpaid">Unpaid</option>
					</select> -->
					<input type="text" name="b_i" id="b_i" hidden />
					<div id="paymentResponse" style="color: red!important;"></div>
					<div class="form-group">
						<label>CARD NUMBER</label>
						<div id="card_number" class="field"></div>
					</div>
					<div class="form-group">
						<label>EXPIRY DATE</label>
						<div id="card_expiry" class="field"></div>
					</div>
					<div class="form-group">
						<label>CVC CODE</label>
						<div id="card_cvc" class="field"></div>
					</div>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-dark" data-dismiss="paid_modal">Close</button> -->
					<!-- <button type="submit" class="btn btn-primary">Done</button> -->
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="payBtn">Pay</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="keys_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLongTitle">Alert!</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- <select name="" id="" class="form-control paid-select">
						<option value="paid">Paid</option>
						<option value="unpaid">Unpaid</option>
					</select> -->
				<p>Please add the Stripe Keys, reload the page and retry!</p>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-dark" data-dismiss="paid_modal">Close</button> -->
				<!-- <button type="submit" class="btn btn-primary">Done</button> -->
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
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
<script src="https://js.stripe.com/v3/"></script>

<!--	<script src="assets/js/jquery.dataTables.colResize.js"></script>-->
<script>
	$(document).ready(function() {
		$('select').niceSelect();
	});

	$(function() {
		// $('#stock-range').daterangepicker({
		// 	opens: 'left'
		// }, function(start, end, label) {
		// 	var _token = '{{ csrf_token() }}';
		// 	var startDate = start.format('YYYY-MM-DD');
		// 	var endDate = end.format('YYYY-MM-DD');
		// 	var table = 'assignedDevicesTable';
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
		// 			var dt = $('#assignedDevicesTable').DataTable();
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
		// 	// console.log("!st A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		// });

		$('[name="daterange1"]').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var dateRange = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
			var work = 'active_device_filter'
			$.ajax({
				url: '{{ route("ajax.perform") }}', 
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					dateRange,
					work
				},
				success: (result) => {
					var dt = $('#activeDevices').DataTable();
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

		$('[name="daterange2"]').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var table = 'inActiveDevices';
			var work = 'filter_billing';
			var dateRange = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
			$.ajax({
				url: '{{ route("ajax.perform") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					dateRange,
					work
				},
				success: (result) => {
					var dt = $('#inActiveDevices').DataTable();
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
			// console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});


	});

	$(document).ready(function() {
		var table = $('#activeDevices').DataTable();
	});

	$(document).ready(function() {
		var table = $('#inActiveDevices').DataTable();
	});



	$('.dropify').dropify({
		messages: {
			'default': 'Drag and drop a file here or click',
			'replace': 'Drag and drop or click to replace',
			'remove': 'Remove',
			'error': 'Ooops, something wrong happended.'
		}
	});


	//------------ Stripe Payment  -----//
	//Before this add composer require "stripe/stripe-php": "7.92"
	//Than add -> <script src="https://js.stripe.com/v3/" />
	// Set your publishable API key
	var stripePublicKey = '{{ isset($stripeConfig["stripe_key"]) ? $stripeConfig["stripe_key"] : "" }}';
	let stripe = Stripe(stripePublicKey);

	// Create an instance of elements
	let elements = stripe.elements();
	let tierElements = stripe.elements();

	let style = {
		base: {
			fontWeight: 400,
			fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
			fontSize: '16px',
			lineHeight: '1.4',
			color: '#555',
			backgroundColor: '#fff',
			'::placeholder': {
				color: '#888',
			},
		},
		invalid: {
			color: '#eb1c26',
		}
	};

	let cardElement = elements.create('cardNumber', {
		style: style
	});

	cardElement.mount('#card_number');

	let exp = elements.create('cardExpiry', {
		'style': style
	});
	exp.mount('#card_expiry');

	let cvc = elements.create('cardCvc', {
		'style': style
	});
	cvc.mount('#card_cvc');


	// Validate input of the card elements
	let resultContainer = document.getElementById('paymentResponse');
	cardElement.addEventListener('change', function(event) {
		if (event.error) {
			resultContainer.innerHTML = '<p style="color: red!important;">' + event.error.message + '</p>';
		} else {
			resultContainer.innerHTML = '';
		}
	});

	// Get payment form element
	let form = document.getElementById('paymentForm');

	// Create a token when the form is submitted.
	form.addEventListener('submit', function(e) {
		e.preventDefault();
		createToken();
	});

	// Create single-use token to charge the user
	function createToken() {
		stripe.createToken(cardElement).then(function(result) {
			if (result.error) {
				// Inform the user if there was an error
				resultContainer.innerHTML = '<p style="color: red">' + result.error.message + '</p>';
			} else {
				// Send the token to your server
				stripeTokenHandler(result.token);
			}
		});
	}

	// Callback to handle the response from stripe
	function stripeTokenHandler(token) {
		// Insert the token ID into the form so it gets submitted to the server
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);

		// Submit the form
		form.submit();
	}

	//--------------------- Stripe Payment End -------------------------//

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