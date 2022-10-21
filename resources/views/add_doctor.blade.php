<?php $page = 'add_doctor'; ?>
@include('header')
<!-- end color quick setting -->
<link href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" rel="stylesheet">

<style>
	.nice-select,
	.nice-select.open .list {
		width: 100%;
		/* width: 325px; */
		border-radius: 8px;
	}

	.nice-select .list::-webkit-scrollbar {
		width: 0
	}

	.nice-select .list {
		margin-top: 5px;
		top: 100%;
		border-top: 0;
		border-radius: 0 0 5px 5px;
		max-height: 210px;
		overflow-y: scroll;
		padding: 52px 0 0
	}

	.nice-select.has-multiple {
		white-space: inherit;
		height: auto;
		padding: 7px 12px;
		min-height: 53px;
		line-height: 22px
	}

	.nice-select.has-multiple span.current {
		border: 1px solid #CCC;
		background: #EEE;
		padding: 0 10px;
		border-radius: 3px;
		display: inline-block;
		line-height: 24px;
		font-size: 14px;
		margin-bottom: 3px;
		margin-right: 3px
	}

	.nice-select.has-multiple .multiple-options {
		display: block;
		line-height: 37px;
		/* margin-left: 30px; */
		padding: 0
	}

	.nice-select .nice-select-search-box {
		box-sizing: border-box;
		position: absolute;
		width: 100%;
		margin-top: 5px;
		top: 100%;
		left: 0;
		z-index: 8;
		padding: 5px;
		background: #FFF;
		opacity: 0;
		pointer-events: none;
		border-radius: 5px 5px 0 0;
		box-shadow: 0 0 0 1px rgba(68, 88, 112, .11);
		-webkit-transform-origin: 50% 0;
		-ms-transform-origin: 50% 0;
		transform-origin: 50% 0;
		-webkit-transform: scale(.75) translateY(-21px);
		-ms-transform: scale(.75) translateY(-21px);
		transform: scale(.75) translateY(-21px);
		-webkit-transition: all .2s cubic-bezier(.5, 0, 0, 1.25), opacity .15s ease-out;
		transition: all .2s cubic-bezier(.5, 0, 0, 1.25), opacity .15s ease-out
	}

	.nice-select .nice-select-search {
		box-sizing: border-box;
		background-color: #fff;
		border: 1px solid #ddd;
		border-radius: 3px;
		box-shadow: none;
		color: #333;
		display: inline-block;
		vertical-align: middle;
		padding: 7px 12px;
		margin: 0 10px 0 0;
		width: 100% !important;
		min-height: 36px;
		line-height: 22px;
		height: auto;
		outline: 0 !important
	}

	.nice-select.open .nice-select-search-box {
		opacity: 1;
		z-index: 10;
		pointer-events: auto;
		-webkit-transform: scale(1) translateY(0);
		-ms-transform: scale(1) translateY(0);
		transform: scale(1) translateY(0)
	}

	.remove:hover {
		color: red
	}
</style>

<!-- start page content -->
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-8 offset-md-2 col-sm-12">
				<form action="{{ route('insert.doctor') }}" method="post">
					@csrf
					<div class="card card-box">
						<div class="card-head">
							<h4>Doctor Registration</h4>
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
											<label class="control-label">First Name </label>
											<input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Middle Name</label>
											<input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Last Name </label>
											<input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control input-height" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Mobile Number </label>
											<input type="text" name="mobile_number" value="{{ old('mobile_number') }}" onkeypress="onlyNumber(event)" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Email Address</label>
											<input type="email" name="email" value="{{ old('email') }}" class="form-control input-height" />
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Speciality </label>
											<input type="text" name="speciality" value="{{ old('speciality') }}" class="form-control input-height" />
										</div>
									</div>
								</div>
								@if(Auth::guard()->user()->role === "admin")
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="clinic_id" class="form-label m-0">Select Clinic</label>
											<div class="mb-3">
												<select id="clinic_id" name="clinic_id" class="mySelect form-control">
													@if(!empty($clinics))
													@foreach($clinics as $clinic)
													<option value="{{ $clinic['id'] }}">{{ $clinic['name'] }}</option>
													@endforeach
													@endif
												</select>
											</div>
										</div>
									</div>
								</div>
								@endif
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

<script src="{{ asset('assets/js/jquery.nice-select.js') }}"></script>
<script>
	(function($) {

		$.fn.niceSelect = function(method) {

			// Methods
			if (typeof method == 'string') {
				if (method == 'update') {
					this.each(function() {
						var $select = $(this);
						var $dropdown = $(this).next('.nice-select');
						var open = $dropdown.hasClass('open');

						if ($dropdown.length) {
							$dropdown.remove();
							create_nice_select($select);

							if (open) {
								$select.next().trigger('click');
							}
						}
					});
				} else if (method == 'destroy') {
					this.each(function() {
						var $select = $(this);
						var $dropdown = $(this).next('.nice-select');

						if ($dropdown.length) {
							$dropdown.remove();
							$select.css('display', '');
						}
					});
					if ($('.nice-select').length == 0) {
						$(document).off('.nice_select');
					}
				} else {
					console.log('Method "' + method + '" does not exist.')
				}
				return this;
			}

			// Hide native select
			this.hide();

			// Create custom markup
			this.each(function() {
				var $select = $(this);

				if (!$select.next().hasClass('nice-select')) {
					create_nice_select($select);
				}
			});

			function create_nice_select($select) {
				$select.after($('<div></div>')
					.addClass('nice-select')
					.addClass($select.attr('class') || '')
					.addClass($select.attr('disabled') ? 'disabled' : '')
					.addClass($select.attr('multiple') ? 'has-multiple' : '')
					.attr('tabindex', $select.attr('disabled') ? null : '0')
					.html($select.attr('multiple') ? '<span class="multiple-options"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Clinics"/></div><ul class="list"></ul>' : '<span class="current"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Clinics"/></div><ul class="list"></ul>')
				);

				var $dropdown = $select.next();
				var $options = $select.find('option');
				if ($select.attr('multiple')) {
					var $selected = $select.find('option:selected');
					var $selected_html = '';
					$selected.each(function() {
						$selected_option = $(this);
						$selected_text = $selected_option.data('display') || $selected_option.text();

						if (!$selected_option.val()) {
							return;
						}

						$selected_html += '<span class="current">' + $selected_text + '</span>';
					});
					$select_placeholder = $select.data('js-placeholder') || $select.attr('js-placeholder');
					$select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
					$selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
					$dropdown.find('.multiple-options').html($selected_html);
				} else {
					var $selected = $select.find('option:selected');
					$dropdown.find('.current').html($selected.data('display') || $selected.text());
				}


				$options.each(function(i) {
					var $option = $(this);
					var display = $option.data('display');

					$dropdown.find('ul').append($('<li></li>')
						.attr('data-value', $option.val())
						.attr('data-display', (display || null))
						.addClass('option' +
							($option.is(':selected') ? ' selected' : '') +
							($option.is(':disabled') ? ' disabled' : ''))
						.html($option.text())
					);
				});
			}

			/* Event listeners */

			// Unbind existing events in case that the plugin has been initialized before
			$(document).off('.nice_select');

			// Open/close
			$(document).on('click.nice_select', '.nice-select', function(event) {
				var $dropdown = $(this);

				$('.nice-select').not($dropdown).removeClass('open');
				$dropdown.toggleClass('open');

				if ($dropdown.hasClass('open')) {
					$dropdown.find('.option');
					$dropdown.find('.nice-select-search').val('');
					$dropdown.find('.nice-select-search').focus();
					$dropdown.find('.focus').removeClass('focus');
					$dropdown.find('.selected').addClass('focus');
					$dropdown.find('ul li').show();
				} else {
					$dropdown.focus();
				}
			});

			$(document).on('click', '.nice-select-search-box', function(event) {
				event.stopPropagation();
				return false;
			});
			$(document).on('keyup.nice-select-search', '.nice-select', function() {
				var $self = $(this);
				var $text = $self.find('.nice-select-search').val();
				var $options = $self.find('ul li');
				if ($text == '')
					$options.show();
				else if ($self.hasClass('open')) {
					$text = $text.toLowerCase();
					var $matchReg = new RegExp($text);
					if (0 < $options.length) {
						$options.each(function() {
							var $this = $(this);
							var $optionText = $this.text().toLowerCase();
							var $matchCheck = $matchReg.test($optionText);
							$matchCheck ? $this.show() : $this.hide();
						})
					} else {
						$options.show();
					}
				}
				$self.find('.option'),
					$self.find('.focus').removeClass('focus'),
					$self.find('.selected').addClass('focus');
			});

			// Close when clicking outside
			$(document).on('click.nice_select', function(event) {
				if ($(event.target).closest('.nice-select').length === 0) {
					$('.nice-select').removeClass('open').find('.option');
				}
			});

			// Option click
			$(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {

				var $option = $(this);
				var $dropdown = $option.closest('.nice-select');
				if ($dropdown.hasClass('has-multiple')) {
					if ($option.hasClass('selected')) {
						$option.removeClass('selected');
					} else {
						$option.addClass('selected');
					}
					$selected_html = '';
					$selected_values = [];
					$dropdown.find('.selected').each(function() {
						$selected_option = $(this);
						var attrValue = $selected_option.data('value');
						var text = $selected_option.data('display') || $selected_option.text();
						$selected_html += (`<span class="current" data-id=${attrValue}> ${text} <span class="remove">X</span></span>`);
						$selected_values.push($selected_option.data('value'));
					});
					$select_placeholder = $dropdown.prev('select').data('js-placeholder') || $dropdown.prev('select').attr('js-placeholder');
					$select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
					$selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
					$dropdown.find('.multiple-options').html($selected_html);
					$dropdown.prev('select').val($selected_values).trigger('change');
				} else {
					$dropdown.find('.selected').removeClass('selected');
					$option.addClass('selected');
					var text = $option.data('display') || $option.text();
					$dropdown.find('.current').text(text);
					$dropdown.prev('select').val($option.data('value')).trigger('change');
				}
				console.log($('.mySelect').val())
			});
			//---------remove item
			$(document).on('click', '.remove', function() {
				var $dropdown = $(this).parents('.nice-select');
				var clickedId = $(this).parent().data('id')
				$dropdown.find('.list li').each(function(index, item) {
					if (clickedId == $(item).attr('data-value')) {
						$(item).removeClass('selected')
					}
				})
				$selected_values.forEach(function(item, index, object) {
					if (item === clickedId) {
						object.splice(index, 1);
					}
				});
				$(this).parent().remove();
				console.log($('.mySelect').val())
			})

			// Keyboard events
			$(document).on('keydown.nice_select', '.nice-select', function(event) {
				var $dropdown = $(this);
				var $focused_option = $($dropdown.find('.focus') || $dropdown.find('.list .option.selected'));

				// Space or Enter
				if (event.keyCode == 32 || event.keyCode == 13) {
					if ($dropdown.hasClass('open')) {
						$focused_option.trigger('click');
					} else {
						$dropdown.trigger('click');
					}
					return false;
					// Down
				} else if (event.keyCode == 40) {
					if (!$dropdown.hasClass('open')) {
						$dropdown.trigger('click');
					} else {
						var $next = $focused_option.nextAll('.option:not(.disabled)').first();
						if ($next.length > 0) {
							$dropdown.find('.focus').removeClass('focus');
							$next.addClass('focus');
						}
					}
					return false;
					// Up
				} else if (event.keyCode == 38) {
					if (!$dropdown.hasClass('open')) {
						$dropdown.trigger('click');
					} else {
						var $prev = $focused_option.prevAll('.option:not(.disabled)').first();
						if ($prev.length > 0) {
							$dropdown.find('.focus').removeClass('focus');
							$prev.addClass('focus');
						}
					}
					return false;
					// Esc
				} else if (event.keyCode == 27) {
					if ($dropdown.hasClass('open')) {
						$dropdown.trigger('click');
					}
					// Tab
				} else if (event.keyCode == 9) {
					if ($dropdown.hasClass('open')) {
						return false;
					}
				}
			});

			// Detect CSS pointer-events support, for IE <= 10. From Modernizr.
			var style = document.createElement('a').style;
			style.cssText = 'pointer-events:auto';
			if (style.pointerEvents !== 'auto') {
				$('html').addClass('no-csspointerevents');
			}

			return this;

		};

	}(jQuery));

	$(document).ready(function() {
		$('.mySelect').niceSelect();
	});
</script>

<script src="{{ asset('assets/bundles/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery-validation/js/additional-methods.min.js') }}"></script>

<script src="{{ asset('assets/bundles/flatpicker/js/flatpicker.min.js') }}"></script>
<script src="{{ asset('assets/data/date-time.init.js') }}"></script>

<script src="{{ asset('assets/data/form-validation.js') }}"></script>
@include('footer')
<!-- </?php require_once 'footer.php'?> -->