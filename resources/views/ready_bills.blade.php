<?php $page = 'ready_bills'; ?>
@include('header')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- data tables -->
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">
<style>
	.daterange{
		width: 25%!important;
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
							<span>
								<h4>Ready To Bills</h4>
							</span>
							<span>
								<a href="javascript:;" class="btn btn-primary" onclick="showGenBill([],'multiple')">Generate Bill</a>
								<!-- Modal -->

								<a href="{{ route('patients') }}" class="btn btn-primary">See All Patients</a>
								<a href="javascript:;" id="multiselect2" class="btn btn-primary">Select Multiple</a>
							</span>
						</div>
						<div class="card-body fr-icn" id="bar-parent">
							<div class="panel tab-border">
								<header class="panel-heading custom-tab">
									<ul class="nav nav-tabs">
										<li class="nav-item"><a href="#ready" data-bs-toggle="tab" class="active">Ready To Bill</a>
										</li>
										<li class="nav-item"><a href="#generated" data-bs-toggle="tab">Generated Bill</a>
										</li>
									</ul>
								</header>
								<div class="panel-body">
									<div class="tab-content">
										<div class="tab-pane active" id="ready">	
											<input type="text" name="daterange" style="position: unset!important;" class="form-control daterange" id="ready-date-range" value="" />
											<!-- <div class="row">
													<div class="col-md-3 offset-md-9">
													<input type="text" name="daterange" class="form-control" value="04/28/2022 - 04/28/2022" />
													</div>
												</div> -->
											<table id="ready_bill_table" class="display table table-bordered" style="width:100%;">
												<thead>
													<tr>
														<th><input type="checkbox" class="form-check-input multiple-select2" name="" id="select-all" onchange="selectMultiple()"></th>
														<th>From Date</th>
														<th>To Date</th>
														<th>IR Number</th>
														<th>Patient Name</th>
														<th>Total Reading</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													@if(!empty($readyBills))
													@foreach($readyBills as $readyBill)
													<tr>
														<td class="d-flex">
															<div class="multiple-select2">
																<div class="form-check">
																	<input type="checkbox" class="form-check-input billIds" name="billIds[]" id="" value="{{ $readyBill['id'] }}">
																</div>
															</div>
														</td>
														<td>{{ isset($readyBill['from']) ? Date('d-M-Y',strtotime($readyBill['from'])) : '--' }}</td>
														<td>{{ isset($readyBill['to']) ? Date('d-M-Y',strtotime($readyBill['to'])) : '--' }}</td>
														<td>{{ isset($readyBill['patient_id']) ? '#'.$readyBill['patient_id'] : '--' }}</td>
														@php
														$readyBill['patient_name'] = (isset($readyBill['full_name']) ? $readyBill['full_name'] . ' ' : '');
														@endphp
														<td><a>{{ isset($readyBill['patient_name']) ? $readyBill['patient_name'] : "--" }}</a></td>
														<td class="cn">16
															<!--<i class="las la-chart-line"></i> -->
														</td>
														<!-- data-bs-toggle="modal" data-bs-target="#generateBill"  -->
														@php $encRBill = json_encode($readyBill); @endphp
														<td><button type="button" class="btn btn-primary" onclick="showGenBill('{{ $encRBill }}','single')">Generate Bill</button></td>
													</tr>
													@endforeach
													@endif
													<!-- <tr>

														<td class="d-flex">
															<div class="multiple-select2">
																<div class="form-check ">
																	<input type="checkbox" class="form-check-input" name="" id="" value="checkedValue">
																</div>
															</div>
														</td>
														<td> 2-Jan-2022</td>
														<td>13215155321</td>
														<td><a href="view_patients.php">Ivan A. Davis</a></td>
														<td class="cn">658 <i class="las la-chart-line"></i></td>
														<td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generate">Generate Bill</button></td>
													</tr>
													<tr>

														<td class="d-flex">
															<div class="multiple-select2">
																<div class="form-check ">
																	<input type="checkbox" class="form-check-input" name="" id="" value="checkedValue">
																</div>
															</div>
														</td>
														<td>10-Oct-2022</td>
														<td>897542136125</td>
														<td><a href="view_patients.php">Walter S. Hobs</a></td>
														<td class="cn">235 <i class="las la-chart-line"></i></td>
														<td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generate">Generate Bill</button></td>
													</tr> -->
												</tbody>
											</table>
										</div>
										<div class="tab-pane" id="generated">
											<input type="text" name="daterange" style="position: unset!important;" class="form-control daterange" id="generated-date-range" value=""/>
											<!-- <div class="row">
													<div class="col-md-3 offset-md-9">
													<input type="text" name="daterange" class="form-control" value="04/28/2022 - 04/28/2022" />
													</div>
												</div> -->
											<table id="generated_bill_table" class="display table table-bordered" style="width:100%;">
												<thead>
													<tr>
														<th><input type="checkbox" class="form-check-input multiple-select2" name="" id="select-all" onchange="selectMultiple()"></th>
														<th>From Date</th>
														<th>To Date</th>
														<th>IR Number</th>
														<th>Patient Name</th>
														<th>Total Reading</th>
														<th>Blink Code</th>
														<th>Charges</th>
													</tr>
												</thead>
												<tbody>
													@if(!empty($generatedBills))
													@foreach($generatedBills as $generatedBill)
													<tr>
														<td class="d-flex">
															<div class="multiple-select2">
																<div class="form-check ">
																	<input type="checkbox" class="form-check-input billIds" name="billIds[]" id="" value="{{ $generatedBill['id'] }}">
																</div>
															</div>
														</td>
														<td>{{ isset($generatedBill['from']) ? Date('d-M-Y',strtotime($generatedBill['from'])) : '--' }}</td>
														<td>{{ isset($generatedBill['to']) ? Date('d-M-Y',strtotime($generatedBill['to'])) : '--' }}</td>
														<td>{{ isset($generatedBill['patient_id']) ? '#'.$generatedBill['patient_id'] : '--' }}</td>
														@php
														$generatedBill['patient_name'] = (isset($generatedBill['full_name']) ? $generatedBill['full_name'] . ' ' : '');
														@endphp
														<td><a>{{ isset($generatedBill['patient_name']) ? $generatedBill['patient_name'] : "--" }}</a></td>
														<td class="cn">16
															<!--<i class="las la-chart-line"></i> -->
														</td>
														<td>{{ isset($generatedBill['code']) ? $generatedBill['code'] : "--" }}</td>
														<td>{{ isset($generatedBill['charges']) ? $generatedBill['charges'] : "--" }}</td>
														<!-- data-bs-toggle="modal" data-bs-target="#generateBill"  -->
														{{-- <!-- @php $encRBill = json_encode($generatedBill); @endphp
														<td><button type="button" class="btn btn-primary" onclick="showGenBill('{{ $encRBill }}','single')">Generate Bill</button></td> --> --}}
													</tr>
													@endforeach
													@endif
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



<!-- data tables -->

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/ColReorderWithResize.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>

<script>
	$(document).ready(function() {
		var table = $('#ready_bill_table').DataTable();
		var table2 = $('#generated_bill_table').DataTable();
	});
	// var table = $('#ready_bill_table').DataTable({
	// 	colResize: options
	// });
	// var table = $('#generated_bill_table').DataTable({
	// 	colResize: options
	// });
</script>

@include('footer')
<!-- </?php require_once 'footer.php'?> -->


<script>
	$(function() {
		$('#ready-date-range').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			var table = 'ready_bill_table';
			$.ajax({
				url: '{{ route("bills.filter") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					startDate,
					endDate,
					table,
				},
				success: (result) => {
					var dt = $('#ready_bill_table').DataTable();
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

		$('#generated-date-range').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			var table = 'generated_bill_table';
			$.ajax({
				url: '{{ route("bills.filter") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					startDate,
					endDate,
					table,
				},
				success: (result) => {
					var dt = $('#generated_bill_table').DataTable();
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
			// console.log("A new date selection was madsse: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});

	});

	$(document).ready(function() {
		$("#multiselect2").click(function() {
			$(".multiple-select2").toggleClass("multi_select_show");

		})
	})

	//multi select
	var checked = false;
	function selectMultiple(){
		if(checked){
			checked = false;
			$(".billIds").each(function(index,ele){
				$(this).attr('checked',false);
			});
		}else{
			checked = true;
			$(".billIds").each(function(index,ele){
				$(this).attr('checked',true);
			});
		}
	}

	function showGenBill(data, type) {
		var month= ["January","February","March","April","May","June","July",
            "August","September","October","November","December"];
		if(type == "single"){
			var data = JSON.parse(data); 
		}
		let billModal = `<div class="modal fade" id="generateBill" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Generate Bill</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="post" action={{ route('generateBill') }}>
					@csrf
					<div class="modal-body">
						<div class="card">
							<table class="table">
								<thead>
									<tr>
										<th scope="col">From Date</th>
										<th scope="col">To Date</th>
										<th scope="col">IR Number</th>
										<th scope="col">Patient Name</th>
										<th scope="col">Total Reading</th>`;
										if(type != 'single'){
											billModal += `<th scope="col">Action</th>`
										}
								billModal +=`</tr>
								</thead>
								<tbody>
									<input hidden name="billIDs[]" value="${ data.id }" type="number"/>
									<tr>
										<td scope="row">${(function c(){
											var newDate = new Date(data.from);
											return newDate.getDate()+'-'+month[newDate.getMonth()]+'-'+newDate.getFullYear();
										}())}</td>
										<td scope="row">${(function c(){
											var newDate = new Date(data.to);
											return newDate.getDate()+'-'+month[newDate.getMonth()]+'-'+newDate.getFullYear();
										}())}</td>
										<td>${ '#'+data.id }</td>
										<td><a>${ (data.full_name.length > 0 ? data.full_name+' ' : '') }</a></td>
										<td class="cn">16 <!-- <i class="las la-chart-line"></i> --></td>`;
										if(type != 'single'){
											billModal += `<td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generate" id="remove__btn">Remove</button></td>`;
										}
									billModal += `</tr>
								</tbody>
							</table>
							<div class="row">
								<div class="col-md-6 col-12">
									<div class="form-group">
										<input type="text" name="blink_code" required class="form-control" placeholder="BlinkCode" id="">
									</div>
								</div>
								<div class="col-md-6 col-12">
									<input type="text" name="charges" onkeypress="onlyNumber(event)" required class="form-control" placeholder="Charges" id="">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
					</form>
				</div>
			</div>
		</div>`;
		$(billModal).modal('show');
	}

	
</script>