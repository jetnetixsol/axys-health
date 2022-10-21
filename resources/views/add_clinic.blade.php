<?php $page = 'add_clinic'; ?>
@include('header')
<!-- end color quick setting -->
<link href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" rel="stylesheet">

<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-8 offset-md-2 col-sm-12">
				<form action="{{ route('insert.clinic') }}" method="post">
					@csrf
					<div class="card card-box">
						<div class="card-head">
							<h4>Clinic Registration</h4>
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
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Clinic Name </label>
											<input type="text" name="clinic_name" value="{{ old('clinic_name') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Clinic Manager Name</label>
											<input type="text" name="manager_name" value="{{ old('manager_name') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Clinic Phone Number </label>
											<input type="text" name="mobile_number" onkeypress="onlyNumber(event)" value="{{ old('mobile_number') }}" class="form-control input-height" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Clinic Email</label>
											<input type="email" name="email" value="{{ old('email') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Clinic Address </label>
											<input type="text" name="address" value="{{ old('address') }}" class="form-control input-height" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Clinic City</label>
											<input type="text" name="city" max="100" value="{{ old('city') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Clinic State </label>
											<input type="text" name="state" max="100" value="{{ old('state') }}" class="form-control input-height" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-header crd-hd">
							<h4>Create Credentials</h4>
						</div>
						<div class="card-body">
							<div class="form-body">
								<!-- <div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label class="control-label">User Name</label>
												<input type="text" class="form-control input-height" />
											</div>
										</div>
									</div> -->
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Password </label>
											<input type="password" name="password" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Re-Type Password </label>
											<input type="password" name="retype_password" class="form-control input-height" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<!-- <button type="button" class="btn btn-dark">Cancel</button> -->
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
			</div>
		
		</div>
	</div>
</div>
<!-- end page content -->



<script src="{{ asset('assets/bundles/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery-validation/js/additional-methods.min.js') }}"></script>

<script src="{{ asset('assets/bundles/flatpicker/js/flatpicker.min.js') }}"></script>
<script src="{{ asset('assets/data/date-time.init.js') }}"></script>

<script src="{{ asset('assets/data/form-validation.js') }}"></script>
@include('footer')
<!-- <//?php require_once 'footer.php' ?> -->