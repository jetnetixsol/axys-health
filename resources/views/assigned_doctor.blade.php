<?php $page = 'index';  //require_once 'header.php'?>
@include('header')
	<!-- end color quick setting -->
	<link rel="stylesheet" href="{{ asset('assets/css/tom-select.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
	<!-- data tables -->
	<link href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" rel="stylesheet">


	<!-- start page content -->
	<div class="page-content-wrapper">
		<div class="page-content pdn buy-mon">
			<div class="row">
				<div class="col-md-8 offset-md-2 col-sm-12">
					<form id="purchaseForm" action="{{ route('doctor.buydevice') }}" method="post">
						@csrf
						<div class="card">
							<div class="card-head">
								<h4 class="card-title">Assigned To Doctor</h4>
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
												<div class="col-lg-6">
													<div class="form-group">
														<label for="">Doctor</label>
														<select id="select-beast" name="doctor_name" class="form-control">
															<option value=""></option>
															@if(!empty($doctors))
															@foreach($doctors as $doctor)
																@php $doctor['name'] = (isset($doctor['name']) ? $doctor['name'].' ' : '')
																.(isset($doctor['middle_name']) ? $doctor['middle_name'].' ' : '')
																.(isset($doctor['last_name']) ? $doctor['last_name'] : '');
																@endphp
																<option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
															@endforeach
															@endif
														</select>
														<span id="err" style="font-size: 12px;color: red;"></span>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label for="">Pair Quantity</label>
														<input type="number" name="device_quantity" class="form-control" min="1" value="1"/>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="card-footer">
													<button class="btn btn-primary" type="button" onclick="addDevices()">ADD</button>
												</div>
												<p style="color: red;font-size: 12px;float: right" id="devicesErr"></p>
											</div>
											<div class="totl">
												<div class="card">
													<div class="card-head">
														<h4 class="card-title">Total Bill</h4>
													</div>
													<div class="card-body">
														<div class="lbl-det">
															<label for="">Pair ($420 * <span id="pquant">1</span>)</label>
															<p>$<span id="pair_price">420</span></p>
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
															<p>$<span id="total_price">420</span></p>
															<input type="number" name="total_price" step="0.01" value="420" id="total_price_val" hidden/>
															<input type="number" name="quantity_val" value="1" id="quant" hidden/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="buy-in">
											<div class="card">
												<div class="card-head">
													<h4 class="card-title">Available Monitors</h4>
												</div>
												<div class="card-body">
													<div class="containerOuter">
														<div class="wrpr">
														<ul class="mnt" id="devices_container">
																@if(!empty($devices) && sizeof($devices) >= 4)
																@foreach($devices as $device)
																<li>
																	<input type="select" value="{{ $device['id'] }}" class="hidden" id="input1" name="deviceIds[]" selected>
																	<label class="entry" for="input1">
																		<span class="entry-label">
																			<span class="inr">
																				<b>{{ $device['serial_number'] }}</b>
																				<!-- {{-- <a href="{{ route('device.single',['id'=>$device['id']]) }}" target="_blank">Details</a> --}} -->
																			</span>
																		</span>
																	</label>
																</li>
																@endforeach
																@endif
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
								<button class="btn btn-primary" type="button" onclick="submitForm()">Buy Pair</button>
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

		new TomSelect("#select-beast",{
			create: true,
			sortField: {
				field: "text",
				direction: "asc"
			}
		});

		function addDevices(){
			var quant = $('[name="device_quantity"]').val();
			var _token = '{{ csrf_token() }}';
			if(quant > 0){
				$.ajax({
					url: "{{ route('doctor.device.add') }}",
					method: "POST",
					dataType: "json",
					data:{
						_token,
						quant,
					},
					success: function(res){
						$('#devicesErr').text('');
						if(res.devices.length > 0 && res.exceed_limit == 'no'){
							$('#devices_container').html(``);
							res.devices.map(function(val,index){
								$('#devices_container').append(`<li>
																	<input type="select" value="${ val['id'] }" class="hidden" id="input1" name="deviceIds[]" selected>
																	<label class="entry" for="input1">
																		<span class="entry-label">
																			<span class="inr">
																				<b>${val['serial_number']}</b>
																				{{-- <a href="{{ url('view-single-device') }}${"/"+val['id']}" target="_blank">Details</a> --}}
																			</span>
																		</span>
																	</label>
																</li>`);	
							})
							
							//Pair Price and Total Price
							$('#pair_price').text(parseFloat(quant * 420) );								
							$('#total_price').text(parseFloat(quant * 420));
							$('#total_price_val').val(parseFloat(quant * 420));
							$('#pquant').text(quant);
							$('#quant').val(quant);
						}else if(res.exceed_limit == 'yes'){
							$('#devices_container').html(``);
							$('#devicesErr').text('*Devices Not available for Required Pair!');
						}else if(res.devices.length < 0){
							$('#devicesErr').text('Pair Quantity Required!');
						}
					},
					error: function(err){
						console.log(err.responseJSON);
					}
				})
			}
		}

		function submitForm(){
			var doctor_id = $('[name="doctor_name"]').val();
			$('#err').text("")
			if(doctor_id.length > 0){
			 	$('#purchaseForm').submit();
			}else{
				$('#err').text("Doctor Required!")
			}
		}
	</script>
@include('footer') 	
<?php //require_once 'footer.php'?>