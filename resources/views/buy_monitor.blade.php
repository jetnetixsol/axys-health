<?php //require_once 'header.php'
$page = 'index';
?>
@include('header')
<!-- end color quick setting -->
<link rel="stylesheet" href="{{ asset('assets/css/tom-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- data tables -->
<link href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" rel="stylesheet">

<style>
	.monitor-icons {
		border-radius: 10px;
		height: 30px;
		padding: 0px 10px;
		width: 70px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.monitor-icons-2 {
		border-radius: 10px;
		height: 30px;
		padding: 0px 10px;
		width: 90px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.monitor-icons i {
		font-size: 15px;
		/* padding: 5px 10px; */
	}

	.buy-in .card-head h4.card-title {
		width: 100%;
	}

	.buy-mon .wrpr {
		height: 221px;
	}

	.buy-in .card-head .form-control {
		text-align: left;
	}

	.card.total-bill {
		min-height: 436px;
	}
</style>
<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn buy-mon">
		<div class="row">
			<div class="col-md-8 offset-md-2 col-sm-12">
				<form action="{{ route('buy.device') }}" method="post" id="purchaseForm">
					@csrf
					<div class="card">
						<div class="card-head">
							<h4 class="card-title">Purchaser Details</h4>
						</div>
						<div class="card-body">
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
							<div class="form-body">
								<div class="grd-buy">
									<div class="buy-in">
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label for="">Clinic Name</label>
													<select id="select-beast" name="clinic_name" class="form-control">
														<option value=""></option>
														@if(!empty($clinics))
														@foreach($clinics as $clinic)
														<option value="{{ $clinic['id'] }}">{{ $clinic['name'] }}</option>
														@endforeach
														@endif
														<!-- <option value="1">Nikola</option>
															<option value="3">Nikola Tesla</option>
															<option value="5">Arnold Schwarzenegger</option> -->
													</select>
												</div>
											</div>
											<!-- <div class="col-lg-4">
													<div class="form-group">
														<label for="">Quantity</label>
														<input type="number" name="device_quantity" class="form-control" min="1" value="1"/>
													</div>
												</div> -->
										</div>
										<!-- <div class="row">
												<div class="card-footer">
													<button type="button" onclick="addDevices()" class="btn btn-primary">ADD</button>
												</div>
												<p style="color: red;font-size: 12px;float: right" id="devicesErr"></p>
											</div> -->
										<div class="totl">
											<div class="card total-bill">
												<div class="card-head">
													<h4 class="card-title">Total Bill</h4>
												</div>
												<div class="card-body">
													<div class="lbl-det2">
														<div>
															<label for="">Clinic Email:</label>
															<p id="c-email">--</p>
														</div>
														<div>
															<label for="">Address:</label>
															<p id="c-address">--</p>
														</div>
														<div>
															<label for="">City:</label>
															<p id="c-city">--</p>
														</div>
														<div>
															<label for="">State:</label>
															<p id="c-state">--</p>
														</div>
													</div>
													<div class="lbl-det">
														<label for="">Device ($30 * <span id="pquant">0</span>)</label>
														<p>$<span id="device_price">0</span></p>
													</div>
													<!-- <div class="lbl-det">
														<label for="">Discount</label>
														<p>0%</p>
													</div> -->
													<!-- <div class="lbl-det">
															<label for="">Tax</label>
															<p>$12</p>
														</div> -->
												</div>
												<div class="card-footer">
													<div class="lbl-det">
														<label for="">Total</label>
														<p>$<span id="total_price">0</span></p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="buy-in">
										<div class="card">
											<div class="card-head">
												<h4 class="card-title">
													<!-- <form action=""> -->
													<input type="text" placeholder="Search..." id="srch" onkeyup="setDevices()" class="form-control" id="solo-search">
													<!-- </form> -->
												</h4>
											</div>
											<div class="card-body">
												<div class="containerOuter">
													<div class="wrpr">
														<ul class="mnt" id="devices">
															{{-- @if(!empty($devices) && sizeof($devices) >= 4) --}}
															@foreach($devices as $device)
															<li>
																<label class="entry" for="input1">
																	<span class="entry-label">
																		<span class="inr" id="{{ $device['id'] }}-container">
																			<b>{{ $device['serial_number'] }}</b>
																			@php $deviceID = $device['id'] @endphp
																			<button type="button" class="btn btn-primary monitor-icons" id="{{ $deviceID }}-btn" onclick="<?= $device['clinic_id'] != null ? 'javascript:void(0)' : 'addToSelected(' . '&#39;' . $deviceID . '&#39;,&#39;' . $device['serial_number'] . '&#39;' . ')' ?>" style="<?= $device['clinic_id'] != null ? "width:90px" : '' ?>">
																				{{ $device['clinic_id'] != null ? 'Assigned' : 'Add'  }}<i class='<?= $device['clinic_id'] != null ? "las la-check-circle" : "las la-plus-circle" ?>' id="{{ $deviceID }}-btn"></i>
																			</button>
																			<!-- <a href="{{ route('device.single',['id'=>$device['id']]) }}" target="_blank"><i class="las la-plus-circle btn btn-primary  monitor-icons"></i></a> -->
																		</span>
																	</span>
																</label>
															</li>
															@endforeach
															{{-- @endif --}}
														</ul>
													</div>
												</div>
											</div>
										</div>


										<div class="card">
											<div class="card-head">
												<h4 class="card-title">Selected Devices</h4>
											</div>
											<div class="card-body">
												<div class="containerOuter">
													<div class="wrpr">
														<ul class="mnt" id="devices_container">
															{{-- @if(!empty($devices) && sizeof($devices) >= 4)
															@foreach($devices as $device) 
															<!-- <li>
																<input type="select" value="{{ $device['id'] }}" class="hidden" id="input1" name="deviceIds[]" selected>
															<label class="entry" for="input1">
																<span class="entry-label">
																	<span class="inr">
																		<b>{{ $device['serial_number'] }}</b>
																		<button type="button" class="btn btn-primary monitor-icons-2">
																			Remove <i class="las la-trash"></i>
																		</button>
																	</span>
																</span>
															</label>
															</li> -->
															@endforeach
															@endif --}}
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div style="width: 25%;">
								<button class="btn btn-primary" type="button" onclick="submitForm()" style="width: 100%;float: right;">Register Device</button>
								<span id="err" class="err" style="font-size: 12px;color: red;"></span>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- end page content -->



<script src="{{ asset('assets/js/tom-select.complete.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<!-- data tables -->
<script src="{{ asset('assets/data/table-data.js') }}"></script>
<script src="{{ asset('assets/bundles/flatpicker/js/flatpicker.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-inputmask/bootstrap-inputmask.min.js') }}"></script>
<script>
	$(function() {
		$('input[name="daterange"]').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});
	});

	new TomSelect("#select-beast", {
		create: true,
		sortField: {
			field: "text",
			direction: "asc"
		}
	});

	// function addDevices() {
	// 	// var quant = $('[name="device_quantity"]').val();
	// 	// var _token = '{{ csrf_token() }}';
	// 	// if (quant > 0) {
	// 	// 	$.ajax({
	// 	// 		url: "{{ route('more.device.add') }}",
	// 	// 		method: "POST",
	// 	// 		dataType: "json",
	// 	// 		data: {
	// 	// 			_token,
	// 	// 			quant,
	// 	// 		},
	// 	// 		success: function(res) {
	// 	// 			$('#devicesErr').text('');
	// 	// 			if (res.devices.length > 0 && res.exceed_limit == 'no') {
	// 	// 				$('#devices_container').html(``);
	// 	// 				res.devices.map(function(val, index) {
	// 	// 					$('#devices_container').append(`<li>
	// 	// 															<input type="select" value="${ val['id'] }" class="hidden" id="input1" name="deviceIds[]" selected>
	// 	// 															<label class="entry" for="input1">
	// 	// 																<span class="entry-label">
	// 	// 																	<span class="inr">
	// 	// 																		<b>${val['serial_number']}</b>
	// 	// 																		<a href="{{ url('view-single-device') }}${"/"+val['id']}" target="_blank">Details</a>
	// 	// 																	</span>
	// 	// 																</span>
	// 	// 															</label>
	// 	// 														</li>`);
	// 	// 				})

	// 	// 				//Pair Price and Total Price
	// 	// 				$('#pair_price').text(parseFloat(quant * 420));
	// 	// 				$('#total_price').text(parseFloat(quant * 420));
	// 	// 				$('#total_price_val').val(parseFloat(quant * 420));
	// 	// 				$('#pquant').text(quant);
	// 	// 				$('#quant').val(quant);
	// 	// 			} else if (res.exceed_limit == 'yes') {
	// 	// 				$('#devices_container').html(``);
	// 	// 				$('#devicesErr').text('*Devices Not available for Required Pair!');
	// 	// 			} else if (res.devices.length < 0) {
	// 	// 				$('#devicesErr').text('Pair Quantity Required!');
	// 	// 			}
	// 	// 		},
	// 	// 		error: function(err) {
	// 	// 			console.log(err.responseJSON);
	// 	// 		}
	// 	// 	})
	// 	// }
	// }

	function submitForm() {
		var clinic_id = $('[name="clinic_name"]').val();
		let addedDevices = $('[name="deviceIds[]"]').map(function() {
			return parseInt($(this).val());
		}).get();
		$('#err').text("")
		if (clinic_id.length > 0 && addedDevices.length > 0) {
			$('#purchaseForm').submit();
		} else {
			$('#err').text("Clinic and Devices Required!")
		}
	}


	function setDevices() {
		let search = $('#srch').val();
		let _token = '{{ csrf_token() }}';
		// let addedDevices = [];
		let addedDevices = $('[name="deviceIds[]"]').map(function() {
			return parseInt($(this).val());
		}).get();
		// console.log(addedDevices); 
		$.ajax({
			url: "{{ route('devices.search') }}",
			dataType: "json",
			type: 'post',
			data: {
				_token,
				search
			},
			success: function(result) {
				var htmlCode = ``;
				result.map(function(val, index) {
					// if (addedDevices.indexOf(val.deviceID) == -1) {
					htmlCode += `<li>
										<label class="entry" for="input1">
											<span class="entry-label">
												<span class="inr" id="${val.id}-container">
													<b>${ val.serial_number }</b>`;
					// console.log(addedDevices.indexOf(val.id),addedDevices,val.id);
					if (addedDevices.indexOf(val.id) == -1) {
						htmlCode += `<button type="button" class="btn btn-primary monitor-icons" id="${val.id}-btn" onclick="${val.clinic_id != null ? 'javascript:void(0)' : 'addToSelected(' + '&#39;' + val.id + '&#39;,&#39;' + val.serial_number + '&#39;' + ')' }" style="${ val.clinic_id != null ? "width:90px" : "" }">
															${ val.clinic_id != null ? 'Assigned' : 'Add'  }<i class='${ val.clinic_id != null ? "las la-check-circle" : "las la-plus-circle" }' id="${val.id}-btn"></i>
														</button>`;
					} else {
						htmlCode += `<button type="button" class="btn btn-primary monitor-icons" id="${val.id}-btn" onclick="javascript:void(0)" style="width: 90px">
															Added <i class='las la-check-circle' id="${val.id}-btn"></i>
														</button>`;
					}
					htmlCode += `
												</span>
											</span>
										</label>
									</li>`;
				});
				$('#devices').html(htmlCode);
			},
			error: function(error) {
				console.log(error.responseJSON);
			}
		});
	}

	// let selectedDevices = [];

	function addToSelected(deviceID, serialNo) {
		//change button to added when added
		$('#' + deviceID + '-container').html(`<b>${ serialNo }</b>
												<button type="button" class="btn btn-primary monitor-icons" id="${deviceID}-btn" onclick="javascript:void(0)" style="width: 90px">
													Added <i class='las la-check-circle' id="${deviceID}-btn"></i>
												</button>`);
		//set item to selected device
		let item = `<li id="item-${deviceID}">
						<input type="select" value="${ deviceID }" class="hidden" id="input1" name="deviceIds[]" selected>
						<label class="entry" for="input1">
							<span class="entry-label">
								<span class="inr">
									<b>${serialNo}</b>
									<button type="button" class="btn btn-primary monitor-icons-2" onclick="removeFromSelected('${deviceID}','${serialNo}')">
										Remove <i class="las la-trash"></i>
									</button>
								</span>
							</span>
						</label>
					</li>`;
		$('#devices_container').append(item);

		let addedDevices = $('[name="deviceIds[]"]').map(function() {
			return parseInt($(this).val());
		}).get();
		$('#pquant').text(addedDevices.length);
		$('#device_price').text(addedDevices.length * 30);
		$('#total_price').text(addedDevices.length * 30);
	}


	function removeFromSelected(deviceID, serialNo) {
		// console.log(deviceID,serialNo); return;
		//revert button to back when remove
		$('#' + deviceID + '-container').html(`<b>${serialNo}</b>
													<button type="button" class="btn btn-primary monitor-icons" id="${deviceID}-btn" onclick="addToSelected('${deviceID}','${serialNo}')">
														Add <i class='las la-plus-circle' id="${deviceID}-btn"></i>
													</button>`);
		//remove item to selected device
		$('#devices_container').find('#item-' + deviceID).remove();

		let addedDevices = $('[name="deviceIds[]"]').map(function() {
			return parseInt($(this).val());
		}).get();
		$('#pquant').text(addedDevices.length);
		$('#device_price').text(addedDevices.length * 30);
		$('#total_price').text(addedDevices.length * 30);
	}

	$(document).ready(() => {
		$("[name='clinic_name']").on("change", (event) => {
			// console.log(event.target.value);
			setClinicInfo();
		})
	});

	var errorCount = 0;

	function setClinicInfo() {
		if (errorCount < 2) {
			errorCount += 1;
			var clinicID = event.target.value;
			var _token = '{{ csrf_token() }}';
			var work = 'get_clinic_details';
			$.ajax({
				url: "{{ route('ajax.perform') }}",
				type: "post",
				data: {
					_token,
					clinicID,
					work
				},
				success: (res) => {
					if (res.data.user_data.length > 0) {
						console.log(res.data.user_data);
						$("#c-email").text(res.data.user_data[0].email != null ? res.data.user_data[0].email : 'Not Exists');
						$("#c-address").text(res.data.user_data[0].address != null ? res.data.user_data[0].address : 'Not Exists');
						$("#c-city").text(res.data.user_data[0].city != null ? res.data.user_data[0].city : 'Not Exists');
						$("#c-state").text(res.data.user_data[0].state != null ? res.data.user_data[0].state : 'Not Exists');
					}
				},
				error: (error) => {
					// console.log(error.responseJSON);
					setClinicInfo();
				}
			});
		}
	}
</script>
@include('footer')
<?php //require_once 'footer.php'
?>