<?php $page = 'wallet'; //require_once 'header.php'
?>
@include('header')
<link rel="stylesheet" href="{{ asset('assets/css/tom-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- data tables -->
<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">

<style>
	.succ {
		color: #2aff00;
		font-weight: 600;
	}

	.dng {
		color: #ff0000;
		font-weight: 600;
	}

	tfoot {
		background: #f1f1f1;
	}

	tfoot td {
		border-top: 1px solid #dddddd;
	}

	.mx-ltr {
		text-align: right;
		width: 100%;
		font-size: 10px;
		color: #999999;
		display: block;
	}

	#topup .nice-select {
		width: 100% !important;
	}
</style>

<!-- start page content -->
@php $role = Auth::guard()->user()->role; @endphp
<div class="page-content-wrapper">
	<div class="page-content pdn">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-sm-12">
				<form action="">
					<div class="card card-box">
						<div class="card-head">
							<h4>Wallet History</h4>
							@if($role === 'admin')
							<a class="btn btn-primary topup-btn" href="javascript:void(0)" data-bs-target="#topup" data-bs-toggle="modal">Topup Wallet</a>
							@endif
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
							<table id="walletTable" class="display table table-bordered" style="width:100%;">
								<thead>
									<tr>
										<th>Description</th>
										<th>Quantity</th>
										<th>Date</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody id="t_body">
									@if(!empty($walletHistory))
									@foreach($walletHistory as $wallet)
									<tr>
										<td>{{ $wallet['description'] }}</td>
										<td>{{ $wallet['quantity'] }}</td>
										<td>{{ Date('d/m/Y',strtotime($wallet['created_at'])) }}</td>
										@if($wallet['incr_decr'] === "increment")
										<td class="succ"><i class="las la-arrow-down"></i> + ${{ $wallet['amount'] }}</td>
										@else
										<td class="dng"><i class="las la-arrow-up"></i> - ${{ $wallet['amount'] }}</td>
										@endif
									</tr>
									@endforeach
									@endif
									<!-- <tr>
										<td>Devices Bought</td>
										<td>2</td>
										<td>1/8/2022</td>
										<td class="dng"><i class="las la-arrow-up"></i> - $60</td>
									</tr> -->
								</tbody>
								@if($role === 'clinic')
								<tfoot>
									<tr>
										<td colspan="3" style="text-align:right; font-weight:bold;color:#8155ec">Current Wallet Credit </th>
										<td style="text-align:left; font-weight:bold;color:#000000">${{ isset($totalCredit[0]['wallet_amount']) ? $totalCredit[0]['wallet_amount'] : 0 }}</th>
									</tr>
								</tfoot>
								@endif
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- end page content -->

@if($role === 'admin')
<div class="modal fade" id="topup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addEventTitle">Topup Wallet</h5>
				<!-- <h5 class="modal-title" id="editEventTitle">Edit Reminder</h5> -->
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" id="topup-form" action="{{ route('wallet.topup') }}">
					@csrf
					<div class="row">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label>Clininc Name</label>
									<select id="" name="user_id" required class="form-control">
										@if(!empty($clinics))
										@foreach($clinics as $clinic)
										<option value="{{ $clinic['id'] }}">{{ $clinic['name'] }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Amount</label>
									<input type="text" name="amount" value="1" onkeypress="onlyNumber(event)" required class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-4">
								<label>Short Description</label>
								<input type="text" name="description" placeholder="Topup By Admin" value="Clinic Top Up From Admin" max="150" required class="form-control">
								<span class="mx-ltr">*Max 150 Letters</span>
							</div>
						</div>
						<div class="modal-footer bg-whitesmoke pr-0">
							<button type="button" onclick="showConfirmPayModal()" class="btn btn-round btn-primary">Add To Wallet</button>
							<button type="button" id="close" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	function showConfirmPayModal() {
		let confirmPayModal = `<div class="modal fade" id="topup-confirmation" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addEventTitle">Alert!</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="row">
						<p>Are You sure you want to pay ${ '$' + $('[name="amount"]').val() } ?</p>
					</div>
					<div class="modal-footer bg-whitesmoke pr-0">
						<button type="button" onclick="(function(){ $('#topup-form').submit(); }())" class="btn btn-round btn-primary">Confirm</button>
						<button type="button" id="close" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
		$(confirmPayModal).modal('show');
	}
</script>
@endif

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/ColReorderWithResize.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>

<script src="{{ asset('assets/js/tom-select.complete.min.js') }}"></script>

<script>
	$(function() {
		// $('#assignedDevicesTable').DataTable();
		$('input[name="daterange"]').daterangepicker({
			opens: 'left'
		}, function(start, end, label) {
			var _token = '{{ csrf_token() }}';
			var startDate = start.format('YYYY-MM-DD');
			var endDate = end.format('YYYY-MM-DD');
			var work = 'filter_wallet_history';
			// console.log(startDate, endDate); return;
			$.ajax({
				url: '{{ route("ajax.perform") }}',
				type: 'post',
				dataType: 'json',
				data: {
					_token,
					work,
					startDate,
					endDate,
				},
				success: (result) => {
					var dt = $('#walletTable').DataTable();
					dt.clear();
					// $("#t_body").html(result.data.tableData);
					if (result.data.tr.length > 0) {
						result.data.tr.forEach(function(val, index) {
							dt.row.add(val);
						});
					}
					dt.draw();

					$("tr").each(function(index, val) {
						// console.log($(this).children().eq(3));
						if ($(this).children().eq(3).length > 0 && $(this).children().eq(3)[0].innerHTML != "Amount" &&
							$(this).children().eq(3)[0] != undefined) {
							// $(this).children().eq(3) get the 4th children <td></td> with index 3 or parent tr
							if ($(this).children().eq(3)[0].innerHTML.substring(0, 31) === '<i class="las la-arrow-up"></i>') {
								$(this).children().eq(3).toggleClass("dng", true);
							} else if ($(this).children().eq(3)[0].innerHTML.substring(0, 23) === '<i class="las la-arrow-down"></i>') {
								$(this).children().eq(3).toggleClass("succ", true);
							}
						}
					});
				},
				error: (err) => {
					console.log(err.responseJSON);
				}
			});
			// console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});
	});

	var table = $('#walletTable').DataTable({
		// colResize: options
	});

	// new TomSelect("#select-beast", {
	// 	create: true,
	// 	sortField: {
	// 		field: "text",
	// 		direction: "asc"
	// 	}
	// });

	// $(document).ready(function() {
	// 	var table = $('#walletTable').DataTable();
	// });
	// var table = $('#walletTable').DataTable({
	// 	// colResize: options
	// });
</script>


@include('footer')
<?php //require_once 'footer.php'
?>