<?php $page = '' //require_once 'header.php'
?>
@include('header')
<!-- end color quick setting -->
<link rel="stylesheet" href="{{ asset('assets/css/tom-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- data tables -->
<link href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" rel="stylesheet">


<!-- start page content -->
<div class="page-content-wrapper">
    <div class="page-content pdn buy-mon">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-sm-12">
                <form id="assign_patient_form" action="{{ route('patient.assign.device') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-head">
                            <h4 class="card-title">Assign Patient</h4>
                        </div>
                        <div class="card-body">
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
                            <div class="form-body">
                                <div class="grd-buy">
                                    <div class="buy-in">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="">Select Patient</label>
                                                    <select id="select-beast" name="patient" required class="form-control">
                                                        <option value=""></option>
                                                        @if(!empty($patients))
                                                        @foreach($patients as $patient)
                                                        <option value="{{ $patient['id'] }}">{{ isset($patient['full_name']) ? $patient['full_name'] : '--' }}</option>
                                                        @endforeach
                                                        @endif
                                                        <!-- <option value="4">Thomas Edison</option>
                                                        <option value="1">Nikola</option>
                                                        <option value="3">Nikola Tesla</option>
                                                        <option value="5">Arnold Schwarzenegger</option> -->
                                                    </select>
                                                    <span id="err"></span>
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="">IR Number</label>
                                                    <input type="number" class="form-control" min="1" value="1"/>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div id="appent_filed"></div>
                                        <div class="row">
                                            <div class="card-footer">
                                                <!-- <button type="button" class="btn btn-primary" id="assign_remove_patient_btn">Remove</button>
                                                <button type="button" class="btn btn-primary" id="assign_patient_btn">Add</button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buy-in">
                                        <div class="card">
                                            <div class="card-head">
                                                <h4 class="card-title">Available Monitors</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="containerOuter">
                                                    <div class="wrpr">
                                                        <input type="number" name="device_id" hidden />
                                                        <ul class="mnt" id="device_container">
                                                            @if(!empty($devices))
                                                            @foreach($devices as $device)
                                                            <li>
                                                                <label class="entry" for="input9">
                                                                    <span class="entry-label">
                                                                        <span class="inr">
                                                                            <b>{{ $device['serial_number'] }}</b>
                                                                            @php $deviceID = $device['id']; @endphp
                                                                            <button type="button" href="javascript:void(0);" onclick="setDeviceID('{{ $deviceID }}')" class="btn btn-primary dis-btn">Assign</button>
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                            </li>
                                                            @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- <button class="btn btn-primary" type="button">Send</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end page content -->



<script src="{{ asset('assets/js/tom-select.complete.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<!-- data tables -->
<!-- data/table-data.js -->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bundles/flatpicker/js/flatpicker.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-inputmask/bootstrap-inputmask.min.js') }}"></script>
<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });

    new TomSelect("#select-beast", {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

    $('#assign_patient_btn').click(function() {
        $('#appent_filed').append(
            `<div class="row" >
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">Clinic Name</label>
                        <select id="select-beast" class="form-control">
                            <option value=""></option>
                            <option value="4">Thomas Edison</option>
                            <option value="1">Nikola</option>
                            <option value="3">Nikola Tesla</option>
                            <option value="5">Arnold Schwarzenegger</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">IR Number</label>
                        <input type="number" class="form-control" min="1" value="1"/>
                    </div>
                </div>
            </div>`
        )
    })

    $('#assign_remove_patient_btn').click(function() {
        $('#appent_filed').remove()
    })

    function setDeviceID(deviceID) {
        // console.log(deviceID);
        $('#err').text('');
        var patient = $('[name="patient"]').val();
        if (patient.length > 0) {
            $('.dis-btn').each(function(index, val) {
                $(this).attr('disabled', true);
            });
            $('[name="device_id"]').val(deviceID);
            $('#assign_patient_form').submit();
        } else {
            $('#err').text('Patient Required!');
        }
    }

    //var role = '{{ "admin" }}'; //Auth::guard()->user()->role
    // if (role == 'admin') {
    //     $('#select-beast').change(function(event) {
    //         var patientID = $(this).val();
    //         $('#device_container').html(``);
    //         var _token = '{{ csrf_token() }}';
    //         $.ajax({
    //             type: 'post',
    //             url: '{{ route("patient.get.devices") }}',
    //             data: {
    //                 _token,
    //                 patientID,
    //             },
    //             dataType: 'json',
    //             success: function(result) {
    //                 if (result.devices.length > 0) {
    //                     for (var pos = 0; pos < result.devices.length; pos++) {
    //                         $('#device_container').append(`<li>
    //                                                             <label class="entry" for="input9">
    //                                                                 <span class="entry-label">
    //                                                                     <span class="inr">
    //                                                                         <b>${ result.devices[pos]['serial_number'] }</b>
    //                                                                         <button type="button" href="javascript:void(0);" onclick="setDeviceID('${ result.devices[pos]['id'] }')" class="btn btn-primary dis-btn">Assign</button>
    //                                                                     </span>
    //                                                                 </span>
    //                                                             </label>
    //                                                         </li>`);
    //                     }
    //                     // result.device.map(function(index,val){
    //                     //     console.log(val);
    //                     // });
    //                 } else {
    //                     $('#device_container').html(`<li>No Devices Available!</li>`);
    //                 }
    //             },
    //             fail: function(err) {
    //                 console.log(err.responseJSON);
    //             }
    //         })
    //     });
    // }
</script>
@include('footer')
<?php //require_once 'footer.php'
?>