<?php require_once 'header.php'?>

	<!-- end color quick setting -->
	<link rel="stylesheet" href="assets/css/tom-select.css">
	<link rel="stylesheet" href="assets/css/daterangepicker.css">
	<!-- data tables -->
	<link href="assets/bundles/flatpicker/css/flatpickr.min.css" rel="stylesheet">

	<style>
		.show-2{
			display: none;
		}
	</style>
	<!-- start page content -->
	<div class="page-content-wrapper">
		<div class="page-content pdn buy-mon">
			<div class="container">
				<div class="form-group">
					<label for="">Name</label>
					<div><span id="ns">Mubashir</span> <a href="#" class="btn btn-danger" onclick="toggleEdit('ns','ni')"> <i class="las la-edit"></i></a></div>
					<div class="show-2" id="ni">
						<input type="text" class="form-control">
<!--						<button class="btn btn-primary">Save</button>-->
					</div>
				</div>
				<div class="form-group">
					<label for="">Name</label>
					<div><span id="es">Mubashir</span> <a href="#" class="btn btn-danger" onclick="toggleEdit('es','ei')"> <i class="las la-edit"></i></a></div>
					<div class="show-2" id="ei">
						<input type="text" class="form-control">
						<!--						<button class="btn btn-primary">Save</button>-->
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	function toggleEdit(sp,inp){
		if($('#'+sp).hasClass('show-2')){
			$('#'+sp).removeClass('show-2');
			$('#'+inp).addClass('show-2');
		}else{
			$('#'+inp).removeClass('show-2');
			$('#'+sp).addClass('show-2');
		}
	}
</script>
	<!-- end page content -->




<?php require_once 'footer.php'?>