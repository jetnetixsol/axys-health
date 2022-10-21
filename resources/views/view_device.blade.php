<?php //require_once 'header.php'
$page = 'all_devices';
?>
@include('header')
<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-8 offset-md-2 col-sm-12">
				<div class="cardbox">
					<div class="header">
						<div class="lgo-img" style="background-image:url('<?= asset('assets/img/my-img/color-logo.png') ?>')"></div>
					</div>
					<div class="body">
						<div class="user-btm-box">
							<div class="grd-dvc">
								<div class="dvc-in">
									<strong>Created At</strong>
									<p>{{ Date('Y-m-d',strtotime($device[0]['created_at'])) }}</p>
								</div>
								<div class="dvc-in">
									<strong>Serial Number</strong>
									<p>{{ isset($device[0]['serial_number']) ? $device[0]['serial_number'] : '--' }}</p>
								</div>
								<div class="dvc-in">
									<strong>Model Number</strong>
									<p>{{ isset($device[0]['model_number']) ? $device[0]['model_number'] : '--' }}</p>
								</div>
							</div>
							<div class="grd-dvc">
								<div class="dvc-in">
									<strong>IMEI</strong>
									<p>{{ isset($device[0]['imei']) ? $device[0]['imei'] : '--' }}</p>
								</div>
								<div class="dvc-in">
									<strong>IMSI</strong>
									<p>{{ isset($device[0]['imsi']) ? $device[0]['imsi'] : '--' }}</p>
								</div>
								<div class="dvc-in">
									<strong>ICCID</strong>
									<p> {{ isset($device[0]['iccid']) ? $device[0]['iccid'] : '--' }}</p>
								</div>
							</div>
							@if(isset($device[0]['clinic_id']))
							<div class="grd-dvc" style="grid-template-columns: 1.5fr 1.5fr">
								<div class="dvc-in">
									<strong>Clinic</strong>
									<p>{{ isset($device[0]['clinicName']) ? $device[0]['clinicName'] : '--' }}</p>
								</div>
								<div class="dvc-in">
									<strong>Clinic Email</strong>
									<p>{{ isset($device[0]['clinicEmail']) ? $device[0]['clinicEmail'] : '--' }}</p>
								</div>
							</div>
							@endif
							@if(isset($device[0]['doctor_id']))
							<div class="grd-dvc" style="grid-template-columns: 1.5fr 1.5fr">
								<div class="dvc-in">
									<strong>Doctor</strong>
									<p>{{ isset($device[0]['doctorName']) ? $device[0]['doctorName'] : '--' }}</p>
								</div>
								<div class="dvc-in">
									<strong>Doctor Email</strong>
									<p>{{ isset($device[0]['doctorEmail']) ? $device[0]['doctorEmail'] : '--' }}</p>
								</div>
							</div>
							@endif
							@if(isset($device[0]['patient_id']))
							<div class="grd-dvc" style="grid-template-columns: 1.5fr 1.5fr">
								<div class="dvc-in">
									<strong>Patient</strong>
									<p>{{ isset($device[0]['full_name']) ? $device[0]['full_name'] : '--' }}</p>
								</div>
								<div class="dvc-in">
									<strong>Patient Email</strong>
									<p>{{ isset($device[0]['email']) ? $device[0]['email'] : '--' }}</p>
								</div>
							</div>
							@endif
						</div>
					</div>
					<div class="footer">
						<!-- <div class="btn-group">
							<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#active">Deactivate</button>
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#active">Activate</button>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end page content -->


<div class="modal fade" id="active">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				<i class="las la-exclamation-triangle"></i>
				<p>Are you sure you want to do this?</p>
				<div class="btn-group">
					<button type="button" class="btn btn-danger">No</button>
					<button type="button" class="btn btn-primary">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>

@include('footer')
<?php //require_once 'footer.php'?>