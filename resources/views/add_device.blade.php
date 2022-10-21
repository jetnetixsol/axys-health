<?php $page ='add_device'; require_once 'header.php'?>

	<!-- end color quick setting -->
	<link rel="stylesheet" href="assets/css/tom-select.css">
	<link rel="stylesheet" href="assets/css/daterangepicker.css">
	<!-- end color quick setting -->
	<link href="assets/bundles/flatpicker/css/flatpickr.min.css" rel="stylesheet">

	<!-- start page content -->
	<div class="page-content-wrapper">
		<div class="page-content pdn">
			<div class="row">
				<div class="col-md-8 offset-md-2 col-sm-12">
					<form action="">
						<div class="card card-box">
							<div class="card-head">
								<h4>Add Device</h4>
							</div>
							<div class="card-body" id="bar-parent">
								<div class="form-body">
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group">
												<label class="control-label">Model Number </label>
												<input type="text" class="form-control input-height" />
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="control-label">Serial Number</label>
												<input type="text" class="form-control input-height" />
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="control-label">Created At</label>
												<input type="text" class="form-control" name="datepick" value="4/19/2022" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group">
												<label class="control-label">IMEI </label>
												<input type="text" class="form-control input-height" />
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="control-label">IMSI</label>
												<input type="text" class="form-control input-height" />
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="control-label">ICCID </label>
												<input type="text" class="form-control input-height" />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button type="button" class="btn btn-dark">Cancel</button>
								<button type="button" class="btn btn-primary">Add Device</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- end page content -->


	<script src="assets/js/tom-select.complete.min.js"></script>
	<script src="assets/js/moment.min.js"></script>

	<script src="assets/bundles/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="assets/bundles/jquery-validation/js/additional-methods.min.js"></script>

	<script src="assets/bundles/flatpicker/js/flatpicker.min.js"></script>
	<script src="assets/data/date-time.init.js"></script>

	<script src="assets/data/form-validation.js"></script>
	<script src="assets/js/daterangepicker.min.js"></script>

	<script>
		$(function() {
			$('input[name="datepick"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
				minYear: 1901,
				maxYear: parseInt(moment().format('YYYY'),10)
			}, function(start, end, label) {
				var years = moment().diff(start, 'years');
			});
		});
	</script>
<?php require_once 'footer.php'?>