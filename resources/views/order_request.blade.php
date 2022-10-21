<?php require_once 'header.php'?>

	<!-- end color quick setting -->
	<link rel="stylesheet" href="assets/css/tom-select.css">
	<link rel="stylesheet" href="assets/css/daterangepicker.css">
	<!-- data tables -->
	<link href="assets/bundles/datatables/plugins/bootstrap/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="assets/bundles/flatpicker/css/flatpickr.min.css" rel="stylesheet">

	<link href="assets/css/formlayout.css" rel="stylesheet" type="text/css" />


	<!-- start page content -->
	<div class="page-content-wrapper">
		<div class="page-content pdn buy-mon">
			<div class="row">
				<div class="col-md-8 offset-md-2 col-sm-12">
					<form action="">
						<div class="card">
							<div class="card-head">
								<h4 class="card-title">Order Request</h4>
							</div>
							<div class="card-body">
								<div class="form-body">
									<div class="grd-buy grd-dt">
										<div class="buy-in">
											<div class="card crd_one">
												<div class="card-head">
													<h4 class="card-title">Available Monitors</h4>
													<div class="form-group">
<!--														<input type="number" class="form-control" min="1" value="1"/>-->
														<div class="input-group spinner">
															<span class="input-group-btn">
																<button class="btn btn-primary bg-ad" data-dir="dwn" type="button">
																	<span class="fa fa-minus"></span>
																</button>
															</span>
																<input type="text" class="form-control text-center" min="1" value="9">
															<span class="input-group-btn">
																<button class="btn btn-primary bg-ad" data-dir="up" type="button">
																	<span class="fa fa-plus"></span>
																</button>
															</span>
														</div>
													</div>
												</div>
												<div class="card-body">
													<div class="containerOuter">
														<div class="wrpr">
															<ul class="mnt">
																<li>
																	<input type="radio" class="hidden" id="input1" name="inputs" checked>
																	<label class="entry" for="input1">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 1</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input2" name="inputs">
																	<label class="entry" for="input2">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 2</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input3" name="inputs">
																	<label class="entry" for="input3">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 3</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input4" name="inputs">
																	<label class="entry" for="input4">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 4</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input5" name="inputs">
																	<label class="entry" for="input5">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 5</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input6" name="inputs">
																	<label class="entry" for="input6">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 6</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input7" name="inputs">
																	<label class="entry" for="input7">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 7</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input8" name="inputs">
																	<label class="entry" for="input8">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 8</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
																<li>
																	<input type="radio" class="hidden" id="input9" name="inputs">
																	<label class="entry" for="input9">
<!--																		<span class="circle"></span>-->
																		<span class="entry-label">
																			<span class="inr">
																				<b>Device 9</b>
																				<a href="#">Details</a>
																			</span>
																		</span>
																	</label>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="buy-mid">
											<div class="btn-group">
												<button class="btn btn-primary" type="button" id="clk_btn"><i class="las la-link"></i></button>
												<button class="btn btn-dark" type="button" id="clk_unlink"><i class="las la-unlink"></i></button>
											</div>
										</div>
										<div class="buy-in">
											<div class="clc-nm">
												<div class="card crd_one">
													<div class="card-head">
														<h4 class="card-title">Clinic Details</h4>
													</div>
													<div class="card-body">
														<div class="row">
															<div class="col-lg-12">
																<div class="form-group">
																	<label for="">Clinic Name</label>
																	<p>ABC Clinic</p>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="totl">
												<div class="card crd_one">
													<div class="card-head">
														<h4 class="card-title">Total Bill</h4>
													</div>
													<div class="card-body">
														<div class="lbl-det">
															<label for="">Device 1</label>
															<p>$420</p>
														</div>
														<!-- <div class="lbl-det">
															<label for="">Discount</label>
															<p>2%</p>
														</div> -->
														<div class="lbl-det">
															<label for="">Tax</label>
															<p>$12</p>
														</div>
													</div>
													<div class="card-footer">
														<div class="lbl-det">
															<label for="">Total</label>
															<p>$500</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<button class="btn btn-primary" type="button">Done</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- end page content -->



<!--	<script src="assets/js/tom-select.complete.min.js"></script>-->
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/daterangepicker.min.js"></script>
	<!-- data tables -->
	<script src="assets/bundles/datatables/jquery.dataTables.min.js"></script>
	<script src="assets/bundles/datatables/plugins/bootstrap/dataTables.bootstrap5.min.js"></script>
	<script src="assets/data/table-data.js"></script>
	<script src="assets/bundles/flatpicker/js/flatpicker.min.js"></script>
	<script src="assets/bundles/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<script src="assets/bundles/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
	<script>


		$(function() {
			$('input[name="daterange"]').daterangepicker({
				opens: 'left'
			}, function(start, end, label) {
				console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			});
		});

		$('#clk_btn').click(function(){
			$('.crd_one').css('border','3px solid #8155ec');
			$('.crd_one').css('box-shadow','0 0 30px rgba(0,0,0,0.2)');
			$('.crd_one').css('transition','.3s');
		});
		$('#clk_unlink').click(function(){
			$('.crd_one').css('border','1px solid #deebfd');
			$('.crd_one').css('box-shadow','none');
			$('.crd_one').css('transition','.3s');
		});
	</script>
<?php require_once 'footer.php'?>