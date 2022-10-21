<?php $page = 'all_patients'; ?>
@include('header')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- data tables -->
<link href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<style>
	/* .info_box{
		display: block !important;
	} */

	.pagination {
		float: right;
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
							<h4>All Patients</h4>
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
							<div class="alert alert-danger d-none" id="cust-error">
							</div>
							<div class="card ftr">
								<div class="card-body">
									<div class="ftr-grd">
										<div class="ftr-in">
											<div class="input-group">
												<input type="text" class="form-control" id="search" placeholder="Find here.." aria-label="Username" aria-describedby="basic-addon1">
												<span class="input-group-text" id="basic-addon1"><i class="las la-search"></i></span>
											</div>
										</div>
										<div class="ftr-in">
											<input type="text" name="daterange" class="form-control" value="" id="patientsRange" />
										</div>
										<div class="ftr-in">
											<div class="btn-group wdt">
												<!--																	<button type="button" class="btn btn-primary"></button>-->
												<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-bs-toggle="dropdown">
													Export
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li><a href="javascript:void(0)" id="multi_pdf_download">PDF File<i class="las la-file-pdf"></i></a>
													</li>
													<li><a href="javascript:void(0)" id="multi_jpg_download">JPG File<i class="las la-file-image"></i></a>
													</li>
												</ul>
											</div>
										</div>
										<div class="ftr-in">
											<div class="btn-group wdt">
												<div class="mutli_btn">
													<span class="mutli_btn_upper">
														<a href="javascript:;" class="btn" id="select_multiple">Select Multiple</a>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@if(!empty($patients))
							<div id="patients_container">
								@foreach($patients as $i=>$patient)
								@php
								$patient['name'] = isset($patient['full_name']) ? $patient['full_name'] : '--';
								$patientID = $patient['id'];
								@endphp
								<div class="card bdy-cd" id="patient_container_{{ $patient['id'] }}">
									<div class="card-body">
										<div class="crd-grd">
											<div class="crd-in">
												<div class="pat-pro">
													<div class="multiple-select">
														<div class="form-check">
															<input type="checkbox" class="form-check-input" data-name='<?= isset($patient['name']) ? $patient['name'] : '' ?>' name="patientIds[]" id="select_{{ $patient['id'] }}" value="{{ $patientID }}">
														</div>
													</div>
													<div class="pro-hd">
														<i class="las la-user-injured"></i>
														<h4>{{ $patient['name'] }}
															<span>
																<label for="">Patient IR <b>{{ '#'.$patient['id'] }}</b></label>
															</span>
														</h4>
													</div>
													<div class="pro-det">
														<p><i class="las la-hashtag"></i>{{ isset($patient['mrn']) ? $patient['mrn']  : '-' }}</p>
														<p><i class="las la-birthday-cake"></i> {{ isset($patient['dob']) ? $patient['dob']  : '-' }}</p>
														<p><i class="las la-envelope"></i> <a href="mailto:john@gmail.com">{{ isset($patient['email']) ? $patient['email'] : '-' }}</a></p>
														<p><i class="las la-phone"></i> <a href="tel:(123) 321 654 7895">{{ isset($patient['mobile_number']) ? $patient['mobile_number'] : '--' }}</a></p>
													</div>
													<div class="pro-footer">
														<div class="ft-grd">
															<div class="ic-in tp-ch">
																<span id="{{ $patientID }}_last_systolic">{{ isset($patient['last_record'][0]['systolic']) ? $patient['last_record'][0]['systolic'] : '--' }}</span>
																<i class="las la-chart-line"></i>
															</div>
															<div class="ic-in bt-cht">
																<span id="{{ $patientID }}_last_diastolic">{{ isset($patient['last_record'][0]['diastolic']) ? $patient['last_record'][0]['diastolic'] : '--' }}</span>
																<i class="las la-chart-line"></i>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="crd-in">
												<div class="read-data" id="{{ $patientID }}_last_records">
													@if(!empty($patient['last_record']))
													@foreach($patient['last_record'] as $k=>$last_record)
													<div class="infor {{ $k != 0 ? 'bot-ln' : '' }}">
														<div class="read-grd">
															<div class="read-in">
																<label for="">Date</label>
																<span class="bld">{{ isset($last_record['created_at']) ? Date('d M Y',strtotime($last_record['created_at'])) : '-' }}</span>
															</div>
															<div class="read-in">
																<label for="">Time</label>
																<span>{{ isset($last_record['created_at']) ? Date('h:i A',strtotime($last_record['created_at'])) : '-' }}</span>
															</div>
															<div class="read-in">
																<label for="">Systolic BP</label>
																<span>{{ isset($last_record['systolic']) ? $last_record['systolic'] : '-' }}<i class="las la-chart-line"></i></span>
															</div>
															<div class="read-in">
																<label for="">Diastolic BP</label>
																<span>{{ isset($last_record['diastolic']) ? $last_record['diastolic'] : '-' }}<i class="las la-chart-line rdd"></i></span>
															</div>
															<div class="read-in">
																<label for="">Heart Rate</label>
																<span>{{ isset($last_record['irregular_heartbeat']) ? $last_record['irregular_heartbeat'] : '-' }} <i class="las la-chart-line"></i></span>
															</div>
														</div>
													</div>
													@endforeach
													@else
													<div class="infor">
														<div class="read-grd">
															<div class="read-in">
																<label for="">Date</label>
																<span class="bld">--</span>
															</div>
															<div class="read-in">
																<label for="">Time</label>
																<span>--</span>
															</div>
															<div class="read-in">
																<label for="">Systolic BP</label>
																<span>--<i class="las la-chart-line"></i></span>
															</div>
															<div class="read-in">
																<label for="">Diastolic BP</label>
																<span>--<i class="las la-chart-line rdd"></i></span>
															</div>
															<div class="read-in">
																<label for="">Heart Rate</label>
																<span>--<i class="las la-chart-line"></i></span>
															</div>
														</div>
													</div>
													@endif
												</div>
											</div>
											<div class="crd-in">
												<div class="txt-ar">
													<textarea name="remarks" id="remarks_{{ $patientID }}" onkeypress="remarks_(event,'{{ $patientID }}')" class="form-control" rows="5" placeholder="Enter Remarks and Press Enter to Save">{{ isset($patient['remarks']) ? $patient['remarks'] : '' }}</textarea>
													<div class="btn-group">
														<button type="button" class="btn btn-primary info_btn" onclick="ownToggle('<?= $i  ?>')" id="info_btn<?= $i ?>"><span>View Info</span></button>
														@if(isset($patient['serial_number']) && Auth::guard()->user()->role === 'doctor')
														@php $url = route('session.end',$patientID); @endphp
														<button class="btn btn-dangerr" type="button" onclick="showEndSessionModal('{{ $url }}')"><span>End Session</span></button>
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class="bot-grd" id="info_box<?= $i ?>">
											<div class="sing-ftr">
												<div class="card ftr">
													<div class="card-body">
														<div class="ftr-grd">
															<div class="ftr-in">
																<div class="input-group">
																	<input type="text" name="daterange" class="form-control daterange" value="" id="{{ $patientID }}_report" onchange="changeGraph('{{ $patientID }}')" />
																	<div class="input-group-prepend">
																		<span class="input-group-text" id="basic-addon1"><i class="las la-calendar-day"></i></span>
																	</div>
																</div>
															</div>
															<div class="ftr-in">
																<div class="btn-group wdt">
																	<!--<button type="button" class="btn btn-primary"></button>-->
																	<input type="text" id="{{ $patientID }}_uri" value="" hidden />
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-bs-toggle="dropdown">
																		Export
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu">
																		<!-- downloadFile('{{ $patientID }}','pdf') -->
																		<li><a href="javascript:void(0)" id="down_pdf_{{ $patientID }}">PDF File<i class="las la-file-pdf"></i></a>
																		</li>
																		<!-- onclick="downloadFile('{{ $patientID }}','jpg')" -->
																		<li><a href="javascript:void(0)" id="down_jpg_{{ $patientID }}">JPG File<i class="las la-file-image"></i></a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="grd-dvc-patient">
												<div class="dvc-in-patient">
													<div id="chart<?= $patient['id'] ?>">
														<div id="timeline-chart<?= $patient['id'] ?>"></div>
													</div>
													<!-- Map Script -->
													<script>
														var patientID = '<?= $patient['id'] ?>';
														var patientRecords = '<?= isset($patient['records']) ? json_encode($patient['records']) : 'undefined' ?>';
														patientRecords = patientRecords != 'undefined' ? JSON.parse(patientRecords) : 'undefined';
														var options<?= $patient['id']  ?> = {
															chart: {
																type: "area",
																height: 300,
																foreColor: "#999",
																stacked: true,
																dropShadow: {
																	enabled: true,
																	enabledSeries: [0],
																	top: -2,
																	left: 2,
																	blur: 5,
																	opacity: 0.06
																}
															},
															colors: ['#e6d5ff', '#cbe7fd', '#f5d9b8'],
															stroke: {
																curve: "smooth",
																width: 3
															},
															dataLabels: {
																enabled: false
															},
															series: [{
																	name: 'Systolic BP',
																	data: generateDayWiseTimeSeries('Systolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
																},
																{
																	name: 'Diastolic BP',
																	data: generateDayWiseTimeSeries('Diastolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
																},
																{
																	name: 'Heart Rate',
																	data: generateDayWiseTimeSeries('Heart Rate', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
																}
															],
															markers: {
																size: 0,
																strokeColor: "#fff",
																strokeWidth: 3,
																strokeOpacity: 1,
																fillOpacity: 1,
																hover: {
																	size: 6
																}
															},
															xaxis: {
																type: "datetime",
																axisBorder: {
																	show: false
																},
																axisTicks: {
																	show: false
																}
															},
															yaxis: {
																labels: {
																	offsetX: 14,
																	offsetY: -5
																},
																tooltip: {
																	enabled: true
																}
															},
															grid: {
																padding: {
																	left: -5,
																	right: 5
																}
															},
															tooltip: {
																x: {
																	format: "dd MMM yyyy"
																},
															},
															legend: {
																position: 'top',
																horizontalAlign: 'left'
															},
															fill: {
																type: "solid",
																fillOpacity: 0.7
															}
														};
														var chart<?= $patient['id']  ?> = new ApexCharts(document.querySelector("#timeline-chart" + patientID), options<?= $patient['id']  ?>);
														chart<?= $patient['id'] ?>.render();

														function generateDayWiseTimeSeries(s, count, records) {
															// console.log(s,count,records);
															// var values = [];
															// var values = [
															// 	[
															// 		4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
															// 	],
															// 	[
															// 		2, 3, 8, 7, 22, 16, 23, 7, 11, 5, 12, 5, 10, 4, 15, 2, 6, 10,23,12,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
															// 	],
															// 	[
															// 		5, 6, 4, 9, 25, 18, 28, 10, 15, 8, 13, 7, 12, 6, 17, 4, 8, 4,3,2,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
															// 	]
															// ];
															var i = 0;
															var series = [];
															var x = new Date().getTime();

															if (records != null) {
																switch (s) {
																	case 'Systolic BP':
																		records.map(function(val, index) {
																			// console.log(new Date(val['date']).getTime(),val['date']);
																			//new Date(val['date']).getTime()+86400000
																			series.push([new Date(val['created_at']).getTime(), val['systolic']]);
																		});
																		break;
																	case 'Diastolic BP':
																		records.map(function(val, index) {
																			// console.log(new Date(val['date']).getTime(),val['date']);
																			series.push([new Date(val['created_at']).getTime(), val['diastolic']]);
																		});
																		break;
																	case 'Heart Rate':
																		// console.log(records);
																		records.map(function(val, index) {
																			// console.log("date", val["date"]);
																			series.push([new Date(val['created_at']).getTime(), val['ihb']]);
																		});
																		break;
																}
																// var x = new Date().getTime();
																// while (i < count) {
																// 	series.push([x, values[s][i]]);
																// 	x += 86400000;
																// 	i++;
																// }
															}

															return series;
														}

														$("#down_pdf_" + patientID).on('click', function() {
															downloadFile(chart<?= $patient['id'] ?>, '<?= $patient['id'] ?>', 'pdf', 'single');
														});

														$("#down_jpg_" + patientID).on('click', function() {
															downloadFile(chart<?= $patient['id'] ?>, '<?= $patient['id'] ?>', 'jpg', 'single');
														});
													</script>
												</div>
											</div>
											<div class="pt-out-grd" id="{{ $patientID }}_days_group">
												@if(!empty($patient['days_group']))
												@foreach($patient['days_group'] as $group)
												<div class="pt-out-in">
													<div class="card-header">
														Day {{ $loop->index + 1 }}
													</div>
													<div class="al-rds">
														<div class="grd-pt">
															<div class="pt-in">
																<div class="pt-box ">
																	<span class="bp-style">Systolic BP</span>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box1">
																	<span class="patient-detail-text cont">{{ !empty($group['average_sys']) ? $group['average_sys'] : 00 }}</span>
																	<label for="" class="patient-detail-text">Average</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box1">
																	<span class="patient-detail-text cont">{{ !empty($group['min_sys']) ? ($group['min_sys'] % 1 != 0 ? $group['min_sys'] : round($group['min_sys'])) : 00 }}</span>
																	<label for="" class="patient-detail-text">Min</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box1">
																	<span class="patient-detail-text cont">{{ !empty($group['max_sys']) ? ($group['max_sys'] % 1 != 0 ? $group['max_sys'] : round($group['max_sys'])) : 00 }}</span>
																	<label for="" class="patient-detail-text">Max</label>
																</div>
															</div>
														</div>
														<div class="grd-pt mt-2">
															<div class="pt-in">
																<div class="pt-box ">
																	<span class="bp-style">Diastolic BP</span>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box2">
																	<span class="patient-detail-text2 cont">{{ !empty($group['average_dia']) ? $group['average_dia'] : 00 }}</span>
																	<label for="" class="patient-detail-text2">Average</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box2">
																	<span class="patient-detail-text2 cont">{{ !empty($group['min_dia']) ? ($group['min_dia'] % 1 != 0 ? $group['min_dia'] : round($group['min_dia'])) : 00 }}</span>
																	<label for="" class="patient-detail-text2">Min</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box2">
																	<span class="patient-detail-text2 cont">{{ !empty($group['max_dia']) ? ($group['max_dia'] % 1 != 0 ? $group['max_dia'] : round($group['max_dia'])) : 00 }}</span>
																	<label for="" class="patient-detail-text2">Max</label>
																</div>
															</div>
														</div>
														<div class="grd-pt mt-2">
															<div class="pt-in">
																<div class="pt-box ">
																	<span class="bp-style">Heart Rate</span>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box3">
																	<span class="patient-detail-text3 cont">{{ !empty($group['average_heart']) ? $group['average_heart'] : 00 }}</span>
																	<label for="" class="patient-detail-text3">Average</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box3">
																	<span class="patient-detail-text3 cont">{{ !empty($group['min_heart']) ? ($group['min_heart'] % 1 != 0 ? $group['min_heart'] : round($group['min_heart']))  : 00 }}</span>
																	<label for="" class="patient-detail-text3">Min</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box3">
																	<span class="patient-detail-text3 cont">{{ !empty($group['max_heart']) ? ($group['max_heart'] % 1 != 0 ? $group['max_heart'] : round($group['max_heart'])) : 00 }}</span>
																	<label for="" class="patient-detail-text3">Max</label>
																</div>
															</div>
														</div>
													</div>
												</div>
												@endforeach
												@endif
												<!-- <div class="pt-out-in">
													<div class="card-header">
														Day 2
													</div>
													<div class="al-rds">
														<div class="grd-pt">
															<div class="pt-in">
																<div class="pt-box ">
																	<span class="bp-style">Systolic BP</span>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box1">
																	<span class="patient-detail-text cont">80</span>
																	<label for="" class="patient-detail-text">Average</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box1">
																	<span class="patient-detail-text cont">120</span>
																	<label for="" class="patient-detail-text">Min</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box1">
																	<span class="patient-detail-text cont">108</span>
																	<label for="" class="patient-detail-text">Max</label>
																</div>
															</div>
														</div>
														<div class="grd-pt mt-2">
															<div class="pt-in">
																<div class="pt-box ">
																	<span class="bp-style">Diastolic BP</span>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box2">
																	<span class="patient-detail-text2 cont">80</span>
																	<label for="" class="patient-detail-text2">Average</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box2">
																	<span class="patient-detail-text2 cont">120</span>
																	<label for="" class="patient-detail-text2">Min</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box2">
																	<span class="patient-detail-text2 cont">108</span>
																	<label for="" class="patient-detail-text2">Max</label>
																</div>
															</div>
														</div>
														<div class="grd-pt mt-2">
															<div class="pt-in">
																<div class="pt-box ">
																	<span class="bp-style">Heart Rate</span>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box3">
																	<span class="patient-detail-text3 cont">80</span>
																	<label for="" class="patient-detail-text3">Average</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box3">
																	<span class="patient-detail-text3 cont">120</span>
																	<label for="" class="patient-detail-text3">Min</label>
																</div>
															</div>
															<div class="pt-in">
																<div class="pt-box pt-box3">
																	<span class="patient-detail-text3 cont">108</span>
																	<label for="" class="patient-detail-text3">Max</label>
																</div>
															</div>
														</div>
													</div>
												</div> -->
											</div>
										</div>
									</div>
								</div>
								@endforeach
								{{ $patients->links() }}
							</div>
							@else
							<div class="test">No Record Found!</div>
							@endif
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<!-- end page content -->


<!--	<script src="assets/data/apexcharts.data.js"></script>-->

<script src="{{ asset('assets/bundles/flatpicker/js/flatpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script>
	//	========================
	//	========================
	// for (var i = 0; i < 5; i++) {
	// var patients = '{{-- $patients  --}}';
	// var patients = patients.replace(/&quot;/g, '\"');
	// var zeros = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	// if (patients.length > 0) {
	// 	var patients = JSON.parse(patients);
	// 	for (var pos = 0; pos < patients.length; pos++) {
	// 		var options = {
	// 			chart: {
	// 				type: "area",
	// 				height: 300,
	// 				foreColor: "#999",
	// 				stacked: true,
	// 				dropShadow: {
	// 					enabled: true,
	// 					enabledSeries: [0],
	// 					top: -2,
	// 					left: 2,
	// 					blur: 5,
	// 					opacity: 0.06
	// 				}
	// 			},
	// 			colors: ['#e6d5ff', '#cbe7fd', '#f5d9b8'],
	// 			stroke: {
	// 				curve: "smooth",
	// 				width: 3
	// 			},
	// 			dataLabels: {
	// 				enabled: false
	// 			},
	// 			series: [{
	// 					name: 'Systolic BP',
	// 					data: generateDayWiseTimeSeries('Systolic BP', (patients[pos].records != undefined ? ([pos].records > 30 ? 30 : patients[pos].records.length) : 30), (patients[pos].records != undefined ? patients[pos].records : null))
	// 				},
	// 				{
	// 					name: 'Diastolic BP',
	// 					data: generateDayWiseTimeSeries('Diastolic BP', (patients[pos].records != undefined ? ([pos].records > 30 ? 30 : patients[pos].records.length) : 30), (patients[pos].records != undefined ? patients[pos].records : null))
	// 				},
	// 				{
	// 					name: 'Heart Rate',
	// 					data: generateDayWiseTimeSeries('Heart Rate', (patients[pos].records != undefined ? ([pos].records > 30 ? 30 : patients[pos].records.length) : 30), (patients[pos].records != undefined ? patients[pos].records : null))
	// 				}
	// 			],
	// 			markers: {
	// 				size: 0,
	// 				strokeColor: "#fff",
	// 				strokeWidth: 3,
	// 				strokeOpacity: 1,
	// 				fillOpacity: 1,
	// 				hover: {
	// 					size: 6
	// 				}
	// 			},
	// 			xaxis: {
	// 				type: "datetime",
	// 				axisBorder: {
	// 					show: false
	// 				},
	// 				axisTicks: {
	// 					show: false
	// 				}
	// 			},
	// 			yaxis: {
	// 				labels: {
	// 					offsetX: 14,
	// 					offsetY: -5
	// 				},
	// 				tooltip: {
	// 					enabled: true
	// 				}
	// 			},
	// 			grid: {
	// 				padding: {
	// 					left: -5,
	// 					right: 5
	// 				}
	// 			},
	// 			tooltip: {
	// 				x: {
	// 					format: "dd MMM yyyy"
	// 				},
	// 			},
	// 			legend: {
	// 				position: 'top',
	// 				horizontalAlign: 'left'
	// 			},
	// 			fill: {
	// 				type: "solid",
	// 				fillOpacity: 0.7
	// 			}
	// 		};
	// 		var chart = new ApexCharts(document.querySelector("#timeline-chart" + pos), options);
	// 		chart.render();
	// 	}
	// }
	// }

	// function generateDayWiseTimeSeries(s, count, records) {
	// 	// console.log(s,count,records);
	// 	// var values = [];
	// 	// var values = [
	// 	// 	[
	// 	// 		4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
	// 	// 	],
	// 	// 	[
	// 	// 		2, 3, 8, 7, 22, 16, 23, 7, 11, 5, 12, 5, 10, 4, 15, 2, 6, 10,23,12,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
	// 	// 	],
	// 	// 	[
	// 	// 		5, 6, 4, 9, 25, 18, 28, 10, 15, 8, 13, 7, 12, 6, 17, 4, 8, 4,3,2,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
	// 	// 	]
	// 	// ];
	// 	var i = 0;
	// 	var series = [];
	// 	var x = new Date().getTime();

	// 	if (records != null) {
	// 		switch (s) {
	// 			case 'Systolic BP':
	// 				records.map(function(val, index) {
	// 					// console.log(new Date(val['date']).getTime(),val['date']);
	// 					//new Date(val['date']).getTime()+86400000
	// 					series.push([new Date(val['created_at']).getTime(), val['systolic']]);
	// 				});
	// 				break;
	// 			case 'Diastolic BP':
	// 				records.map(function(val, index) {
	// 					// console.log(new Date(val['date']).getTime(),val['date']);
	// 					series.push([new Date(val['created_at']).getTime(), val['diastolic']]);
	// 				});
	// 				break;
	// 			case 'Heart Rate':
	// 				// console.log(records);
	// 				records.map(function(val, index) {
	// 					// console.log("date", val["date"]);
	// 					series.push([new Date(val['created_at']).getTime(), val['ihb']]);
	// 				});
	// 				break;
	// 		}
	// 		// var x = new Date().getTime();
	// 		// while (i < count) {
	// 		// 	series.push([x, values[s][i]]);
	// 		// 	x += 86400000;
	// 		// 	i++;
	// 		// }
	// 	}

	// 	return series;
	// }

	//		=====================

	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
	$(function() {
		$('#patientsRange').daterangepicker({
			opens: 'left',
			autoUpdateInput: false
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			var search = $("#search").val();
			var recordType = 'whole_record';
			$.ajax({
				url: '{{ route("patients.filter") }}',
				type: 'post',
				// dataType: 'json',
				data: {
					_token,
					startDate,
					endDate,
					recordType
				},
				success: (result) => {
					// console.log(result); return;
					$("#patients_container").html(result);
					applyRangeToAll();
					$("[name='remarks']").each(function() {
						$(this).scrollTop($(this)[0].scrollHeight);
					})
					// if(recordType == 'whole_rec'){
					// }
					// console.log(result); return;
					// console.log(JSON.parse(result));
					// return;
					// var patients = JSON.parse(result);
					// patients.map(function(val, index) {
					// 	$('#' + val.id + '_last_records').html('');
					// 	// $('#' + val.id + '_last_systolic').html('');
					// 	// $('#' + val.id + '_last_dialstolic').html('');
					// 	// console.log(val.last_record);
					// 	if (val.last_record.length > 0) {
					// 		val.last_record.map(function(lastrecord, index2) {
					// 			$('#' + val.id + '_last_records').append(`<div class="infor ${ index2 != 0 ? 'bot-ln' : '' }">
					// 														<div class="read-grd">
					// 															<div class="read-in">
					// 																<label for="">Date</label>
					// 																{{-- isset($last_record['date']) ? Date('d M Y',strtotime($last_record['date'])) : '-' --}}
					// 																<span class="bld">${ lastrecord.created_at != null ? (function formatDate(){ var date = new Date(lastrecord.created_at); return (date.getDate() < 10 ? '0'+date.getDate() : date.getDate()) +" "+(months[date.getMonth()]) +" "+date.getFullYear() }()) : '-' }</span>
					// 															</div>
					// 															<div class="read-in">
					// 																<label for="">Time</label>
					// 																{{-- isset($last_record['created_at']) ? Date('h:i A',strtotime($last_record['created_at'])) : '-' --}}
					// 																<span>${ lastrecord.created_at != null ? (function formatDate(){ 
					// 																	var date = new Date(lastrecord.created_at);
					// 																	date.setHours(date.getHours() - 5);//convert to local time 
					// 																	var hours = date.getHours();
					// 																	var ampm = hours >= 12 ? 'PM' : 'AM';
					// 																	hours = hours > 12 ? hours % 12 : hours; //if 24 hours time conver to 12
					// 																	hours = hours ? hours : 12; // the hour '0' should be '12'
					// 																	return hours+":"+(date.getMinutes() < 10 ? '0'+date.getMinutes() : date.getMinutes())+" "+ampm }()) : '-' }</span>
					// 															</div>
					// 															<div class="read-in">
					// 																<label for="">Systolic BP</label>
					// 																{{-- isset($last_record['systolic']) ? $last_record['systolic'] : '-' --}}
					// 																<span>${ lastrecord.systolic != null ? lastrecord.systolic : '-' }<i class="las la-chart-line"></i></span>
					// 															</div>
					// 															<div class="read-in">
					// 																<label for="">Diastolic BP</label>
					// 																{{-- isset($last_record['diastolic']) ? $last_record['diastolic'] : '-' --}}
					// 																<span>${ lastrecord.diastolic != null ? lastrecord.diastolic : '-' }<i class="las la-chart-line rdd"></i></span>
					// 															</div>
					// 															<div class="read-in">
					// 																<label for="">Heart Rate</label>
					// 																{{-- isset($last_record['irregular_heartbeat']) ? $last_record['irregular_heartbeat'] : '-' --}}
					// 																<span>${ lastrecord.irregular_heartbeat != null ? lastrecord.irregular_heartbeat : '-' }<i class="las la-chart-line"></i></span>
					// 															</div>
					// 														</div>
					// 													</div>`);
					// 		});
					// 		// val.last_record.diastolic
					// 		$('#' + val.id + '_last_systolic').text(val.last_record[0].systolic != null ? val.last_record[0].systolic : '-');
					// 		$('#' + val.id + '_last_diastolic').text(val.last_record[0].diastolic != null ? val.last_record[0].diastolic : '-');

					// 	} else {
					// 		$('#' + val.id + '_last_records').append(`<div style="text-align: center">No Data Found!</div>`);
					// 		$('#' + val.id + '_last_systolic').text('-');
					// 		$('#' + val.id + '_last_diastolic').text('-');
					// 	}

					// 	//Implement Graph Records
					// 	var patientRecords = val.records != null ? val.records : 'undefined';
					// 	// patientRecords = patientRecords != 'undefined' ? JSON.parse(patientRecords) : 'undefined';
					// 	// console.log(patientRecords);
					// 	var options = {
					// 		chart: {
					// 			type: "area",
					// 			height: 300,
					// 			foreColor: "#999",
					// 			stacked: true,
					// 			dropShadow: {
					// 				enabled: true,
					// 				enabledSeries: [0],
					// 				top: -2,
					// 				left: 2,
					// 				blur: 5,
					// 				opacity: 0.06
					// 			}
					// 		},
					// 		colors: ['#e6d5ff', '#cbe7fd', '#f5d9b8'],
					// 		stroke: {
					// 			curve: "smooth",
					// 			width: 3
					// 		},
					// 		dataLabels: {
					// 			enabled: false
					// 		},
					// 		series: [{
					// 				name: 'Systolic BP',
					// 				data: generateDayWiseTimeSeries('Systolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
					// 			},
					// 			{
					// 				name: 'Diastolic BP',
					// 				data: generateDayWiseTimeSeries('Diastolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
					// 			},
					// 			{
					// 				name: 'Heart Rate',
					// 				data: generateDayWiseTimeSeries('Heart Rate', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
					// 			}
					// 		],
					// 		markers: {
					// 			size: 0,
					// 			strokeColor: "#fff",
					// 			strokeWidth: 3,
					// 			strokeOpacity: 1,
					// 			fillOpacity: 1,
					// 			hover: {
					// 				size: 6
					// 			}
					// 		},
					// 		xaxis: {
					// 			type: "datetime",
					// 			axisBorder: {
					// 				show: false
					// 			},
					// 			axisTicks: {
					// 				show: false
					// 			}
					// 		},
					// 		yaxis: {
					// 			labels: {
					// 				offsetX: 14,
					// 				offsetY: -5
					// 			},
					// 			tooltip: {
					// 				enabled: true
					// 			}
					// 		},
					// 		grid: {
					// 			padding: {
					// 				left: -5,
					// 				right: 5
					// 			}
					// 		},
					// 		tooltip: {
					// 			x: {
					// 				format: "dd MMM yyyy"
					// 			},
					// 		},
					// 		legend: {
					// 			position: 'top',
					// 			horizontalAlign: 'left'
					// 		},
					// 		fill: {
					// 			type: "solid",
					// 			fillOpacity: 0.7
					// 		}
					// 	};

					// 	$("#timeline-chart" + val.id).html(``);
					// 	var chart = new ApexCharts(document.querySelector("#timeline-chart" + val.id), options);
					// 	chart.render();

					// 	function generateDayWiseTimeSeries(s, count, records) {
					// 		// console.log(s,count,records);
					// 		// var values = [];
					// 		// var values = [
					// 		// 	[
					// 		// 		4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
					// 		// 	],
					// 		// 	[
					// 		// 		2, 3, 8, 7, 22, 16, 23, 7, 11, 5, 12, 5, 10, 4, 15, 2, 6, 10,23,12,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
					// 		// 	],
					// 		// 	[
					// 		// 		5, 6, 4, 9, 25, 18, 28, 10, 15, 8, 13, 7, 12, 6, 17, 4, 8, 4,3,2,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
					// 		// 	]
					// 		// ];
					// 		var i = 0;
					// 		var series = [];
					// 		var x = new Date().getTime();

					// 		if (records != null) {
					// 			switch (s) {
					// 				case 'Systolic BP':
					// 					records.map(function(val, index) {
					// 						// console.log(new Date(val['date']).getTime(),val['date']);
					// 						//new Date(val['date']).getTime()+86400000
					// 						series.push([new Date(val['created_at']).getTime(), val['systolic']]);
					// 					});
					// 					break;
					// 				case 'Diastolic BP':
					// 					records.map(function(val, index) {
					// 						// console.log(new Date(val['date']).getTime(),val['date']);
					// 						series.push([new Date(val['created_at']).getTime(), val['diastolic']]);
					// 					});
					// 					break;
					// 				case 'Heart Rate':
					// 					// console.log(records);
					// 					records.map(function(val, index) {
					// 						// console.log("date", val["date"]);
					// 						series.push([new Date(val['created_at']).getTime(), val['ihb']]);
					// 					});
					// 					break;
					// 			}
					// 			// var x = new Date().getTime();
					// 			// while (i < count) {
					// 			// 	series.push([x, values[s][i]]);
					// 			// 	x += 86400000;
					// 			// 	i++;
					// 			// }
					// 		}

					// 		return series;
					// 	}

					// 	//Days Records
					// 	// console.log(val.days_group);
					// 	$('#' + val.id + '_days_group').html(``);
					// 	//Days Keys
					// 	var daysKeys = [];
					// 	Object.keys(val.days_group).map(function(key) {
					// 		daysKeys.push(key)
					// 	});
					// 	if (daysKeys.length > 0) {
					// 		daysKeys.map((val3, index3) => {
					// 			val3 = val.days_group[val3] //[2022-06-08]
					// 			console.log(val3.average_dia);
					// 			$('#' + val.id + '_days_group').append(`<div class="pt-out-in">
					// 													<div class="card-header">
					// 														Day ${index3 + 1}
					// 													</div>
					// 													<div class="al-rds">
					// 														<div class="grd-pt">
					// 															<div class="pt-in">
					// 																<div class="pt-box ">
					// 																	<span class="bp-style">Systolic BP</span>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box1">
					// 																	{{-- !empty($group['average_sys']) ? $group['average_sys'] : 00 --}}
					// 																	<span class="patient-detail-text cont">${ val3.average_sys != null ? val3.average_sys : '0' }</span>
					// 																	<label for="" class="patient-detail-text">Average</label>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box1">
					// 																	{{-- !empty($group['min_sys']) ? ($group['min_sys'] % 1 != 0 ? $group['min_sys'] : round($group['min_sys'])) : 00 --}}
					// 																	<span class="patient-detail-text cont">${ val3.min_sys != null ? val3.min_sys : '0' }</span>
					// 																	<label for="" class="patient-detail-text">Min</label>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box1">
					// 																	{{-- !empty($group['max_sys']) ? ($group['max_sys'] % 1 != 0 ? $group['max_sys'] : round($group['max_sys'])) : 00 --}}
					// 																	<span class="patient-detail-text cont">${ val3.max_sys != null ? val3.max_sys : '0' }</span>
					// 																	<label for="" class="patient-detail-text">Max</label>
					// 																</div>
					// 															</div>
					// 														</div>
					// 														<div class="grd-pt mt-2">
					// 															<div class="pt-in">
					// 																<div class="pt-box ">
					// 																	<span class="bp-style">Diastolic BP</span>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box2">
					// 																	{{-- !empty($group['average_dia']) ? $group['average_dia'] : 0 --}}
					// 																	<span class="patient-detail-text2 cont">${ val3.average_dia != null ? val3.average_dia : '0' }</span>
					// 																	<label for="" class="patient-detail-text2">Average</label>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box2">
					// 																	<span class="patient-detail-text2 cont">${ val3.min_dia != null ? val3.min_dia : '0' }{{-- !empty($group['min_dia']) ? ($group['min_dia'] % 1 != 0 ? $group['min_dia'] : round($group['min_dia'])) : 00 --}}</span>
					// 																	<label for="" class="patient-detail-text2">Min</label>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box2">
					// 																	<span class="patient-detail-text2 cont">${ val3.max_dia != null ?  val3.max_dia : '0' }{{-- !empty($group['max_dia']) ? ($group['max_dia'] % 1 != 0 ? $group['max_dia'] : round($group['max_dia'])) : 00 --}}</span>
					// 																	<label for="" class="patient-detail-text2">Max</label>
					// 																</div>
					// 															</div>
					// 														</div>
					// 														<div class="grd-pt mt-2">
					// 															<div class="pt-in">
					// 																<div class="pt-box ">
					// 																	<span class="bp-style">Heart Rate</span>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box3">
					// 																	<span class="patient-detail-text3 cont">${ val3.average_heart != null ?  val3.average_heart : '0' }{{-- !empty($group['average_heart']) ? $group['average_heart'] : 00 --}}</span>
					// 																	<label for="" class="patient-detail-text3">Average</label>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box3">
					// 																	<span class="patient-detail-text3 cont">${ val3.min_heart != null ?  val3.min_heart : '0' }{{-- !empty($group['min_heart']) ? ($group['min_heart'] % 1 != 0 ? $group['min_heart'] : round($group['min_heart']))  : 00 --}}</span>
					// 																	<label for="" class="patient-detail-text3">Min</label>
					// 																</div>
					// 															</div>
					// 															<div class="pt-in">
					// 																<div class="pt-box pt-box3">
					// 																	<span class="patient-detail-text3 cont">${ val3.max_heart != null ?  val3.max_heart : '0' }{{-- !empty($group['max_heart']) ? ($group['max_heart'] % 1 != 0 ? $group['max_heart'] : round($group['max_heart'])) : 00 --}}</span>
					// 																	<label for="" class="patient-detail-text3">Max</label>
					// 																</div>
					// 															</div>
					// 														</div>
					// 													</div>
					// 												</div>`);
					// 		});
					// 	}

					// });
				},
				error: (err) => {
					console.log(err.responseJSON);
				}
			});
		});


		$('#patientsRange').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
		});

		$('#patientsRange').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});


		$("#multi_pdf_download").on('click', function() {
			var patientIDs = [];
			var patientNames = [];
			$("[name='patientIds[]']:checked").each(function(num, element) {
				patientIDs.push($(this).val());
				patientNames.push($(this).data("name"));
			});
			if (patientIDs.length <= 0) {
				$("#cust-error").toggleClass('d-none', false);
				$("#cust-error").text("Please Select atleast one Option to export");
			} else {
				$("#cust-error").toggleClass('d-none', true);
				$("#cust-error").text("");
			}
			downloadFile("", patientIDs, 'pdf', 'multi', patientNames);
		});

		$("#multi_jpg_download").on('click', function() {
			var patientIDs = [];
			var patientNames = [];
			$("[name='patientIds[]']:checked").each(function(num, element) {
				patientIDs.push($(this).val());
				patientNames.push($(this).data("name"));
			});
			if (patientIDs.length <= 0) {
				$("#cust-error").toggleClass('d-none', false);
				$("#cust-error").text("Please Select atleast one Option to export");
			} else {
				$("#cust-error").toggleClass('d-none', true);
				$("#cust-error").text("");
			}
			downloadFile("", patientIDs, 'jpg', 'multi', patientNames);
		});

		// $('').daterangepicker({
		// 	opens: 'left'
		// });
		// $('input[name="daterange"]').daterangepicker({
		// 	opens: 'left'
		// }, function(start, end, label) {
		// console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		// });
	});

	//		$(document).ready(function(){
	//			for(var i=0;i<5;i++)
	//			{
	//				console.log('for')
	//
	//			}
	//		})

	function ownToggle(i) {
		//			$('#info_btn'+i).on('click',function () {
		var attr = $('#info_box' + i).attr('hidden');
		if (typeof attr !== 'undefined' && attr !== false) {
			// $('#info_box'+i).hasClass('info_box')
			// $('#info_box'+i).removeClass('info_box')
			// $('#info_box'+i).attr('style','display: block!important')
			$('#info_box' + i).attr('hidden', false);
		} else {
			// $('#info_box'+i).addClass('info_box');
			// $('#info_box'+i).attr('style','display: none!important')
			$('#info_box' + i).attr('hidden', true);
		}
		//					console.log('Button Clicked',i)
		//			})
	}

	function remarks_(evt, patientID) {
		var text = $("#remarks_" + patientID).val();
		if (event.key === "Enter" && text.length > 0) {
			var getTimeFormat = getJsFormatTimeStamp();
			$("#remarks_" + patientID).val($("#remarks_" + patientID).val() + " (" + getTimeFormat + ")");
			text = $("#remarks_" + patientID).val();
			var _token = '{{ csrf_token() }}';
			$.ajax({
				url: "{{ route('patient.remarks') }}",
				method: "post",
				data: {
					_token,
					text,
					patientID,
				},
				success: function(success) {
					$('#remarks_' + patientID).attr("style", "border:2px solid blue !important");
					setTimeout(() => {
						$('#remarks_' + patientID).attr("style", "border:2px solid #dddddd !important");
					}, 500);
				},
				fail: function(err) {
					console.log(err.jsonResponse);
				}
			});
		}
	}

	function getJsFormatTimeStamp() {
		var date = new Date();
		var hour = date.getHours();
		var minute = date.getMinutes();
		return date.getFullYear() + '-' + (date.getMonth() < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1)) + '-' +
			date.getDate() + " " + (hour <= 12 && hour > 0 ? (hour < 10 ? '0' + hour : hour) : (hour % 12 != 0 ? (hour % 12 < 10 ? '0' + hour % 12 : hour % 12) : '12')) + (minute < 10 ? ':0' + minute : ':' + minute) + (hour < 12 ? 'AM' : 'PM');
	}

	function showEndSessionModal(url) {
		let sessModal = `<div class="modal fade" id="end" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<i class="las la-exclamation-circle"></i>
				<p>Are you sure you want <br> to end this session?</p>
				<div class="btn-group">
					<button type="button" class="btn btn-dangerr" data-bs-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" onclick="window.location.href='${url}'">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>`;
		$(sessModal).modal('show');
	}


	applyRangeToAll();

	function applyRangeToAll() {
		if ($('.daterange').length > 0) {
			$('.daterange').each((index, elem) => {
				// console.log();
				$(elem).daterangepicker({
					opens: 'left',
					autoUpdateInput: false
				});

				var rangePickerID = $(elem).attr("id");
				$('#' + rangePickerID).on('apply.daterangepicker', function(ev, picker) {
					$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
					var startDate = picker.startDate.format('YYYY-MM-DD');
					var endDate = picker.endDate.format('YYYY-MM-DD');
					var patientID = rangePickerID.split("_")[0];
					changeGraph(patientID, startDate, endDate);
				});

				$('#' + rangePickerID).on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
				});
			});
		}
	}

	function changeGraph(patientID, startDate, endDate) {
		var _token = '{{ csrf_token() }}';
		var recordType = 'graph_record';
		$.ajax({
			url: '{{ route("patients.filter") }}',
			type: 'post',
			dataType: 'json',
			data: {
				_token,
				startDate,
				endDate,
				recordType,
				patientID
			},
			success: (result) => {
				var patientRecords = result.length > 0 && result[0].records.length > 0 ? result[0].records : 'undefined';

				var updatedSeries = [{
						name: 'Systolic BP',
						data: generateDayWiseTimeSeries('Systolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
					},
					{
						name: 'Diastolic BP',
						data: generateDayWiseTimeSeries('Diastolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
					},
					{
						name: 'Heart Rate',
						data: generateDayWiseTimeSeries('Heart Rate', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
					}
				];

				//using this['chart'+patientID] to initialize as chart1 variable 
				var chart = this['chart' + patientID];
				chart.updateSeries(updatedSeries, true);

				function generateDayWiseTimeSeries(s, count, records) {
					var i = 0;
					var series = [];
					var x = new Date().getTime();

					if (records != null) {
						switch (s) {
							case 'Systolic BP':
								records.map(function(val, index) {
									// console.log(new Date(val['date']).getTime(),val['date']);
									//new Date(val['date']).getTime()+86400000
									series.push([new Date(val['created_at']).getTime(), val['systolic']]);
								});
								break;
							case 'Diastolic BP':
								records.map(function(val, index) {
									// console.log(new Date(val['date']).getTime(),val['date']);
									series.push([new Date(val['created_at']).getTime(), val['diastolic']]);
								});
								break;
							case 'Heart Rate':
								// console.log(records);
								records.map(function(val, index) {
									// console.log("date", val["date"]);
									series.push([new Date(val['created_at']).getTime(), val['ihb']]);
								});
								break;
						}
					}

					return series;
				}
			},
			error: (err) => {
				console.log(err.responseJSON);
			}
		});
	}

	function downloadFile(chart, patientID, type, size, patientName) {
		if (size == 'single') {
			var canvas = document.getElementById("chart" + patientID);
			var width = canvas.clientWidth;
			var height = canvas.clientHeight;
			chart.dataURI().then(({
				imgURI,
				blob
			}) => {
				var dFormat = new Date();
				var fileFormat = dFormat.getFullYear().toString() + dFormat.getMonth().toString() + dFormat.getSeconds().toString() +
					dFormat.getMilliseconds().toString();
				if (type == "jpg") {
					// Construct the 'a' element
					var link = document.createElement("a");
					link.download = fileFormat + "-chart.jpg";
					link.target = "_blank";

					// Construct the URI
					link.href = imgURI;
					document.body.appendChild(link);
					link.click();

					// Cleanup the DOM
					document.body.removeChild(link);
					delete link;
				} else if (type == "pdf") {
					let pdf = new jsPDF('l', 'px', [width, height]);
					widthWithPadding = width - 20;
					heightWithPadding = height - 20;
					pdf.addImage(imgURI, 'PNG', 10, 10, widthWithPadding, heightWithPadding);
					pdf.save(fileFormat + "-chart.pdf");
					// downloadTextareaPdf(patientID, fileFormat, "");
				}
			});
		} else if (size == 'multi') {
			// console.log(patientID); return;
			patientID.map(function(val, index) {
				var canvas = document.getElementById("chart" + val);
				var width = canvas.clientWidth;
				var height = canvas.clientHeight;
				var chartVar = this['chart' + val]; //created dynamic variable this['chart'+val] => chart1
				chartVar.dataURI().then(({
					imgURI,
					blob
				}) => {
					var dFormat = new Date();
					var fileFormat = dFormat.getFullYear().toString() + dFormat.getMonth().toString() + dFormat.getSeconds().toString() +
						dFormat.getMilliseconds().toString() + '-' + patientName[index];
					if (type == "jpg") {
						// Construct the 'a' element
						var link = document.createElement("a");
						link.download = fileFormat + "-chart.jpg";
						link.target = "_blank";

						// Construct the URI
						link.href = imgURI;
						document.body.appendChild(link);
						link.click();

						// Cleanup the DOM
						document.body.removeChild(link);
						delete link;
					} else if (type == "pdf") {
						let pdf = new jsPDF('l', 'px', [width, height]);
						widthWithPadding = width - 20;
						heightWithPadding = height - 20;
						pdf.addImage(imgURI, 'PNG', 10, 10, widthWithPadding, heightWithPadding);
						pdf.save(fileFormat + "-chart.pdf");
						downloadTextareaPdf(val, fileFormat, patientName[index]);
					}
				});
			});
		}
	}

	function downloadTextareaPdf(textAreaId, fileFormat, patientName) {
		// var HTML_Width = $("#remarks_" + textAreaId).width();
		// var HTML_Height = $("#remarks_" + textAreaId).height();

		// console.log(HTML_Height, HTML_Width);
		// var top_left_margin = 15;
		// var PDF_Width = HTML_Width + (top_left_margin * 2);
		// var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
		// var canvas_image_width = HTML_Width - 2;
		// var canvas_image_height = HTML_Height - 2;

		// var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

		// html2canvas($("#remarks_" + textAreaId)[0]).then(function(canvas1) {
		// 	var imgData = canvas1.toDataURL("image/jpeg", 1.0);
		// 	var pdf = new jsPDF('p', 'pt', [HTML_Width, HTML_Height]);
		// 	pdf.addImage(imgData, 'JPG', 10, 10, canvas_image_width, canvas_image_height);
		// 	pdf.save(fileFormat + "-remarks.pdf");
		// });
		var remarks = $("#remarks_" + textAreaId).val();
		if (remarks.length > 0) {
			var wordLoop = $('#remarks_' + textAreaId).val().split("M)");
			var html = `<div>
			<h2>Remarks of ${patientName.length > 0 ? patientName : ' Patient' }</h2>
		</div>
		<div><ul style="list-style:none">`;
			for (var i = 0; i < wordLoop.length; i++) {
				if (wordLoop[i].trim().length > 0) {
					html += `<li> ${ wordLoop[i] + (wordLoop[i].indexOf(":") != -1 && (wordLoop[i].indexOf("P") != -1 || wordLoop[i].indexOf("A") != -1) && 
						wordLoop[i].indexOf("(") != -1 ? "M)" : '') } </li>`;
				}
			}
			html += `</ul></div>`;
			var doc = new jsPDF();
			var specialElementHandlers = {
				'#elementH': function(element, renderer) {
					return true;
				}
			};
			doc.fromHTML(html, 15, 15, {
				'width': 170,
				'elementHandlers': specialElementHandlers,
			});
			doc.save(fileFormat + "-remarks.pdf");
		}
	}


	$("#search").on('keyup', function(event) {
		var _token = '{{ csrf_token() }}';
		var search = event.target.value;
		var date = $('#patientsRange').val();
		var startDate = date.split(" - ")[0] != undefined ? date.split(" - ")[0] : '';
		var endDate = date.split(" - ")[1] != undefined ? date.split(" - ")[1] : '';
		var recordType = 'whole_record';
		$.ajax({
			url: '{{ route("patients.filter") }}',
			type: 'post',
			// dataType: 'json',
			data: {
				_token,
				startDate,
				endDate,
				recordType,
				search,
			},
			success: (result) => {
				// console.log(result); return;
				$("#patients_container").html(result);
				applyRangeToAll();
				$("[name='remarks']").each(function() {
					$(this).scrollTop($(this)[0].scrollHeight);
				})
			},
			error: (err) => {
				console.log(err.responseJSON);
			}
		});
	});
</script>



<!-- <//?php require_once 'footer.php'?> -->
@include('footer')
<script>
	$(document).ready(function() {
		$("#select_multiple").click(function() {
			$(".multiple-select").toggleClass("multi_select_show");
		})

		$("[name='remarks']").each(function() {
			$(this).scrollTop($(this)[0].scrollHeight);
		})
	})
</script>