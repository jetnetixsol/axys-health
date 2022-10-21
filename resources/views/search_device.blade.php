<?php //require_once 'header.php'
$page = 'search_devices';
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
									<p>2022-06-07</p>
								</div>
								<div class="dvc-in">
									<strong>Serial Number</strong>
									<p>11F215200240</p>
								</div>
								<div class="dvc-in">
									<strong>Model Number</strong>
									<p>LS802-GP</p>
								</div>
							</div>
							<div class="grd-dvc">
                            <div class="dvc-in">
									<strong>Clinic Name</strong>
									<p>Demo Name</p>
								</div>
								<div class="dvc-in">
									<strong>Patient Name</strong>
									<p>Donald Lue</p>
								</div>
								<div class="dvc-in">
									<strong>Status</strong>
									<p>Active</p>
									<p>Non Active</p>
								</div>
							</div>
							<div class="grd-dvc">
                            <div class="dvc-in">
									<strong>Total Payement</strong>
									<p>288$</p>
								</div>
								<div class="dvc-in">
									<strong>Payment Status</strong>
									<p>Paid</p>
									<p>Un Paid</p>
								</div>
							</div>
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

<?php //require_once 'footer.php'
?>