<?php $page = 'all_doctors'; //require_once 'header.php'
?>
@include('header')
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- data tables -->
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">

<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-sm-12">
				<form action="">
					<div class="card card-box">
						<div class="card-head">
							<h4>All Doctor</h4>
							<!-- <a href="add_doctor.php" class="btn btn-primary">Add Doctor</a> -->
						</div>
						<div class="card-body" id="bar-parent">
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
							<div class="row">
								<div class="col-md-3">
									<input type="text" name="daterange" class="form-control" value="" />
								</div>
							</div>
							<table id="assignedDevicesTable" class="display table table-bordered" style="width:100%;">
								<thead>
									<tr>
										<th>Doctor Name</th>
										<th>Speciality</th>
										<th>Mobile Number</th>
										<th>Email Address</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($doctors))
									@foreach($doctors as $doctor)
									<tr>
										@php
										//Concat Doctor Name
										$doctor['name'] = (isset($doctor['name']) ? $doctor['name'].' ' : '')
										.(isset($doctor['middle_name']) ? $doctor['middle_name'].' ' : '')
										.(isset($doctor['last_name']) ? $doctor['last_name'] : '');
										@endphp
										<td>{{ isset($doctor['name']) ? $doctor['name'] : '' }}</td>
										<td>{{ isset($doctor['speciality']) ? $doctor['speciality'] : '' }}</td>
										<td><a href="tel:{{ isset($doctor['mobile_number']) ? $doctor['mobile_number'] : '' }}"></a>{{ isset($doctor['mobile_number']) ? $doctor['mobile_number'] : '' }}</td>
										<td><a href="mailto:{{ isset($doctor['email']) ? $doctor['email'] : '' }}">{{ isset($doctor['email']) ? $doctor['email'] : '' }}</a></td>
										<!-- view_doctorProfile.php -->
										<td><a href="{{ route('doctor.single',['id'=>$doctor['id']]) }}" class="btn btn-primary"><i class="las la-eye"></i></a></td>
									</tr>
									@endforeach
									@endif
									<!-- <tr>
										<td>Dr. James Doe</td>
										<td>Neurosurgeon</td>
										<td><a href="tel:13215155321">13215155321</a></td>
										<td><a href="mailto:james@gmail.com">james@gmail.com</a></td>
										<td><a href="view_doctorProfile.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
									</tr>
									<tr>
										<td>Dr. James Doe</td>
										<td>Neurosurgeon</td>
										<td><a href="tel:13215155321">13215155321</a></td>
										<td><a href="mailto:james@gmail.com">james@gmail.com</a></td>
										<td><a href="view_doctorProfile.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
									</tr> -->
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- end page content -->



<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/ColReorderWithResize.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>


<script>
	$(function() {
		$('input[name="daterange"]').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			$.ajax({
				url: '{{ route("doctor.filter") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					startDate,
					endDate,
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
			// console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});
	});



	$(document).ready(function() {
		var table = $('#assignedDevicesTable').DataTable();
	});
	var table = $('#assignedDevicesTable').DataTable({
		// colResize: options
	});
</script>

@include('footer')
<?php //require_once 'footer.php'
?>