<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title>Axys Health</title>
	<!-- google font -->
	<link href="{{ asset('assets/css/css.css') }}" rel="stylesheet" type="text/css" />
	<!-- icons -->
	<link href="{{ asset('assets/fonts/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/fonts/font-awesome/v6/css/all.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/fonts/material-design-icons/material-icon.css') }}" rel="stylesheet" type="text/css" />
	<!--bootstrap -->
	<link href="{{ asset('assets/bundles/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

	<!-- Material Design Lite CSS -->
	<link rel="stylesheet" href="{{ asset('assets/bundles/material/material.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/material_style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/sass/my-style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
	<!-- Theme Styles -->
	<link href="{{ asset('assets/css/theme_style.css') }}" rel="stylesheet" id="rt_style_components" type="text/css" />
	<link href="{{ asset('assets/css/plugins.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/theme-color.css') }}" rel="stylesheet" type="text/css" />
	<!-- favicon -->
	<link rel="shortcut icon" href="{{ asset('assets/img/my-img/favicon.png') }}" />

	<script src="{{ asset('assets/bundles/jquery/jquery.min.js') }}"></script>
</head>
<!-- END HEAD -->

<body class="">
<section class="user lgn" style="background-image:url('<?= asset('assets/img/my-img/bp.jpg')?>')">
	<div class="user_options-container">
		<div class="user_options-text">
			<div class="user_options-unregistered">
				<a href="index.php"><div class="lgo-img" style="background-image:url(assets/img/my-img/white-logo.png)"></div></a>
				<h2 class="user_unregistered-title">Axys Health</h2>
				<p class="user_unregistered-text">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium asperiores aut dicta dolores
					eveniet facere inventore laudantium libero nostrum optio quas quidem quo, reiciendis, sed sequi
					similique suscipit veniam voluptatum.
				</p>
			</div>

			<div class="user_options-registered">
				<h2 class="user_registered-title">Have an account?</h2>
				<p class="user_registered-text">Banjo tote bag bicycle rights, High Life sartorial cray craft beer whatever street art fap.</p>
				<button class="user_registered-login" id="login-button">Login</button>
			</div>
		</div>

		<div class="user_options-forms" id="user_options-forms">
			<div class="user_forms-login">
				<h2 class="forms_title" style="margin-bottom: 20px!important;">Login</h2>
				@if(session()->has('fail'))
				<span class="help-block">
					<span style="font-size:12px; color:red;">{{ session()->get('fail') }}</span>
				</span>
				@endif
				<form class="forms_form" method="post" action="{{ route('signin') }}">
					@csrf
					<fieldset class="forms_fieldset">
						@if($errors->has('email'))
						<span class="help-block">
							<span style="font-size:12px; color:red;">{{ $errors->first('email') }}</span>
						</span>
						@endif
						<div class="forms_field">
							<input type="email" placeholder="Email" name="email" class="form-control" autofocus />
						</div>
						@if($errors->has('password'))
						<span class="help-block">
							<span style="font-size:12px; color:red;">{{ $errors->first('password') }}</span>
						</span>
						@endif
						<div class="forms_field">
							<input type="password" placeholder="Password" name="password" class="form-control" />
						</div>
					</fieldset>
					<div class="forms_buttons">
						<input type="submit" value="Log In" class="forms_buttons-action">
					</div>
				</form>
			</div>
		</div>
	</div>
</section>


<script>
</script>


<!-- start js include path -->
<script src="{{ asset('assets/bundles/popper/popper.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery-blockUI/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery.slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/bundles/feather/feather.min.js') }}"></script>

<!-- bootstrap -->
<script src="{{ asset('assets/bundles/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

<!-- counterup -->
<script src="{{ asset('assets/bundles/counterup/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/bundles/counterup/jquery.counterup.min.js') }}"></script>

<!-- Common js-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
<script src="{{ asset('assets/js/theme-color.js') }}"></script>
<!-- material -->
<script src="{{ asset('assets/bundles/material/material.min.js') }}"></script>

<!-- end js include path -->
</body>

</html>