<?php $page ='all_clinics'; //require_once 'header.php'?>
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
								<h4>All Clinics</h4>
							</div>
							<div class="card-body" id="bar-parent">
							<div class="row">
									<div class="col-md-3">
									<input type="text" name="daterange" class="form-control daterange-restyle" value=""/>
									</div>
								</div>
								<table id="assignedDevicesTable" class="display table table-bordered" style="width:100%;">
									<thead>
									<tr>
										<th>Clinic Name</th>
										<th>Clinic Manager Name</th>
										<th>Mobile Number</th>
										<th>Clinic Address</th>
										<th>Actions</th>
									</tr>
									</thead>
									<tbody>
									@if(!empty($clinics))
									@foreach($clinics as $clinic)	
									<tr>
										<td>{{ isset($clinic['name']) ? $clinic['name'] : '--' }}</td>
										<td><a href="#">{{ isset($clinic['manager_name']) ? $clinic['manager_name'] : '--' }}</a></td>
										<td><a href="tel:{{ isset($clinic['mobile_number']) ? $clinic['mobile_number'] : 'javascript:void(0)' }}">{{ isset($clinic['mobile_number']) ? $clinic['mobile_number'] : '--' }}</a></td>
										<td>{{ isset($clinic['address']) ? $clinic['address'] : '--' }}</td>
										<!-- view_clinicProfile.php -->
										<td><a href="{{ route('clinic.single',['id'=>$clinic['id']]) }}" class="btn btn-primary"><i class="las la-eye"></i></a></td>
									</tr>
									@endforeach
									@endif
									<!-- <tr>
										<td>ABC Clinic</td>
										<td><a href="#">Jane N. Menzel</a></td>
										<td><a href="tel:13215155321">13215155321</a></td>
										<td>597 Lincoln St. Ronkonkoma, NY 11779</td>
										<td><a href="view_clinicProfile.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
									</tr>
									<tr>
										<td>ABC Clinic</td>
										<td><a href="#">Jane N. Menzel</a></td>
										<td><a href="tel:13215155321">13215155321</a></td>
										<td>597 Lincoln St. Ronkonkoma, NY 11779</td>
										<td><a href="view_clinicProfile.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
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
					url: '{{ route("clinic.filter") }}',
					type: 'post',
					dataType: 'json',
					data: {
						_token,
						startDate,
						endDate,
					},
					success: (result)=>{	
						var dt = $('#assignedDevicesTable').DataTable();
						dt.clear();
						if(result.tr.length > 0){
							result.tr.forEach(function(val,index){
								dt.row.add(val);
							});
						}
						dt.draw();
					},
					error: (err)=>{
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
<?php //require_once 'footer.php'?>