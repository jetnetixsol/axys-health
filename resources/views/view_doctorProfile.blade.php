<?php $page = 'all_clinics'; //require_once 'header.php'
?>
@include('header')

<link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.colResize.css') }}">

<!-- start page content -->
<div class="page-content-wrapper">
    <div class="page-content pdn">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-sm-12">
                <div class="card cardbox">
                    <div class="card-head">
                        <h4 class="card-title">Doctor Profile</h4>
                        <!-- <div>
                            <a href="add_doctor.php" class="btn btn-primary me-1">Add Doctor</a>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="grd-dvc-doctor">
                            <div class="dvc-in-doctor">
                                <div class="d-flex">
                                    <div class="">
                                        <i class="lar la-user icon-user"></i>
                                        <!-- <i class="lar la-hospital icon-hospital"></i> -->
                                    </div>
                                    <div class="dr-info">
                                        @php
                                            //Concat Doctor Name
                                            $doctor['name'] = (isset($doctor[0]['name']) ? $doctor[0]['name'].' ' : '')
                                            .(isset($doctor[0]['middle_name']) ? $doctor[0]['middle_name'].' ' : '')
                                            .(isset($doctor[0]['last_name']) ? $doctor[0]['last_name'] : '');
                                        @endphp
                                        <strong style="margin-bottom: 5px;">{{ isset($doctor['name']) ? ucfirst($doctor['name']) : '--' }}</strong>
                                        <p class="inf"><i class="las la-stethoscope"></i>{{ isset($doctor[0]['speciality']) ? $doctor[0]['speciality'] : '--' }}</p>
                                        <p class="inf"><i class="las la-phone"></i>{{ isset($doctor[0]['mobile_number']) ? $doctor[0]['mobile_number'] : '--' }}</p>
                                        <p class="inf"><i class="las la-envelope"></i>{{ isset($doctor[0]['email']) ? $doctor[0]['email'] : '--' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dvc-in-doctor">
                                <div class="uppercase profile-stat-title"> {{ sizeof($patients) }} </div>
                                <div class="uppercase profile-stat-text"> Patients </div>
                            </div>
                            <div class="dvc-in-doctor">
                                <div class="uppercase profile-stat-title"> {{ sizeof($devices) }} </div>
                                <div class="uppercase profile-stat-text"> Devices </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="card-body">
                        <div class="panel tab-border">
                            <header class="panel-heading custom-tab">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a href="#assigned-devices" data-bs-toggle="tab" class="active">Assigned Devices</a>
                                    </li>
                                    <li class="nav-item"><a href="#previous-patient" data-bs-toggle="tab" class="">Previous Patient</a>
                                    </li>
                                </ul>
                            </header>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="assigned-devices">
                                        <div class="table-responsive table-card mb-1">
                                            <div class="d-flex justify-content-end">
                                                <!-- <div>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import"><i class="las la-cloud-download-alt"></i>Export
                                                        CSV</button>
                                                </div> -->
                                            </div>
                                            <table id="assignedDevicesTable" class="display table table-border" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Device Serial Number</th>
                                                        <th>IMEI</th>
                                                        <th>Model Number</th>
                                                        <th>Signal</th>
                                                        <th>Battery</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($devices as $device)
                                                    <tr>
                                                        <td>{{ isset($device['serial_number']) ? $device['serial_number'] : '' }}</td>
                                                        <td>{{ isset($device['imei']) ? $device['imei'] : '' }}</td>
                                                        <td>{{ isset($device['model_number']) ? $device['model_number'] : '' }}</td>
                                                        <td>{{ isset($device['signal']) ? $device['signal'] : '' }}</td>
                                                        <td>{{ isset($device['battery']) ? $device['battery'] : '' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="previous-patient">
                                        <div class="table-responsive table-card mb-1">
                                            <div class="d-flex justify-content-end">
                                                <!-- <div>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import"><i class="las la-cloud-download-alt"></i>Export
                                                        CSV</button>
                                                </div> -->
                                            </div>
                                            <table id="previousPatientTable" class="display" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Patient Name</th>
                                                        <th>Patient Number</th>
                                                        <th>Patient Email</th>
                                                        <th>Patient DOB</th>
                                                        <th>Recent Systolic</th>
                                                        <th>Recent Diastolic</th>
                                                        <th>Recent Heart Rate</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($patients as $patient)
                                                    <tr>
                                                        <td>{{ isset($patient['full_name']) ? $patient['full_name'] : '--' }}</td>
                                                        <td>{{ isset($patient['mobile_number']) ? $patient['mobile_number'] : '--' }}</td>
                                                        <td>{{ isset($patient['email']) ? $patient['email'] : '--' }}</td>
                                                        <td>{{ isset($patient['dob']) ? $patient['dob'] : '--' }}</td>
                                                        <td>{{ isset($patient['current'][0]['systolic']) ? $patient['current'][0]['systolic'] : (isset($patient['sys_bp']) ? $patient['sys_bp'] : '--') }}</td>
                                                        <td>{{ isset($patient['current'][0]['diastolic']) ? $patient['current'][0]['diastolic'] : (isset($patient['dia_bp']) ? $patient['dia_bp'] : '--') }}</td>
                                                        <td>{{ isset($patient['current'][0]['irregular_heartbeat']) ? $patient['current'][0]['irregular_heartbeat'] : (isset($patient['heart_rate']) ? $patient['heart_rate'] : '--') }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <!-- <tr>
                                                        <td>2</td>
                                                        <td>Drake</td>
                                                        <td>741</td>
                                                        <td>140</td>
                                                        <td><a href="view_device.php" class="btn btn-primary"><i class="las la-eye"></i></a></td>
                                                    </tr> -->
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<!-- end page content -->
<!-- Modal -->

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/ColReorderWithResize.js') }}"></script>
<script>
    // doctor profile Table
    $(document).ready(function() {
        var table = $('#assignedDevicesTable').DataTable();
    });
    // product Quantity Table
    $(document).ready(function() {
        var table = $('#previousPatientTable').DataTable();
    });
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
</script>

@include('footer')
<?php //require_once 'footer.php'
?>