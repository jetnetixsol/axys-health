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
                        <h4 class="card-title">Clinic Profile</h4>
                        <!-- <div>
                            <a href="add_clinic.php" class="btn btn-primary me-1">Add Clinic</a>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="grd-dvc-clinic">
                            <div class="dvc-in-clinic">
                                <div class="d-flex">
                                    <div class="">
                                        <i class="lar la-hospital icon-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ isset($clinic[0]['name']) ? $clinic[0]['name'] : '--' }}</strong>
                                        <p class="inf"> <i class="las la-map-marker-alt"></i>{{ isset($clinic[0]['address']) ? $clinic[0]['address'] : '--' }}</p>
                                        <p class="inf"><i class="las la-phone"></i> {{ isset($clinic[0]['mobile_number']) ? $clinic[0]['mobile_number'] : '--' }}</p>
                                        <p class="inf"><i class="las la-envelope"></i> {{ isset($clinic[0]['email']) ? $clinic[0]['email'] : '--' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dvc-in-clinic">
                                <div class="uppercase profile-stat-title">{{ count($doctors) }}</div>
                                <div class="uppercase profile-stat-text"> Total <br> Doctors </div>
                            </div>
                            <div class="dvc-in-clinic">
                                <div class="uppercase profile-stat-title">{{ $clinicDevices }}</div>
                                <div class="uppercase profile-stat-text"> Total <br> Devices </div>
                            </div>
                            <div class="dvc-in-clinic">
                                <div class="uppercase profile-stat-title">{{ $assignDevices }}</div>
                                <div class="uppercase profile-stat-text"> Assigned <br> Devices </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="card-body">
                        <div class="panel tab-border">
                            <header class="panel-heading custom-tab">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a href="#doctor-profile" data-bs-toggle="tab" class="active">Doctor Profile</a>
                                    </li>
                                    <!-- <li class="nav-item"><a href="#product-quantity" data-bs-toggle="tab"
                                            class="">Product Quantity</a>
                                    </li> -->
                                </ul>
                            </header>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="doctor-profile">
                                        <div class="d-flex justify-content-end">
                                            <!-- <div>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import"><i class="las la-cloud-download-alt"></i>Export
                                                    CSV</button>
                                            </div> -->
                                        </div>
                                        <div class="table-responsive table-card mb-1">
                                            <table id="doctorProfileTable" class="display" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Doctor Names</th>
                                                        <th>Mobile Number</th>
                                                        <th>Speciality</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($doctors as $doctor)
                                                    @php
                                                    $doctor['name'] = (isset($doctor['name']) ? $doctor['name'].' ' : '')
                                                    .(isset($doctor['middle_name']) ? $doctor['middle_name'].' ' : '')
                                                    .(isset($doctor['last_name']) ? $doctor['last_name'] : '');
                                                    @endphp
                                                    <tr>
                                                        <td>{{ isset($doctor['name']) ? $doctor['name'] : '--' }}</td>
                                                        <td>{{ isset($doctor['mobile_number']) ? $doctor['mobile_number'] : '--' }}</td>
                                                        <td>{{ isset($doctor['speciality']) ? ucfirst($doctor['speciality']) : '--' }}</td>
                                                        <td><a href="{{ route('doctor.single',['id'=>$doctor['id']]) }}" class="btn btn-primary"><i class="las la-eye"></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="product-quantity">
                                        <!-- <div class="table-responsive table-card mb-1">
                                            <div class="d-flex justify-content-end">
                                                <div>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#import"><i
                                                            class="las la-cloud-download-alt"></i>Export
                                                        CSV</button>
                                                </div>

                                            </div>
                                            <table id="productQuantityTable" class="display" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Device Number</th>
                                                        <th>Patient Name</th>
                                                        <th>Device Serial Number</th>
                                                        <th>Patient Reading</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Walter</td>
                                                        <td>231</td>
                                                        <td>120</td>
                                                        <td><a href="view_device.php" class="btn btn-primary"><i
                                                                    class="las la-eye"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Drake</td>
                                                        <td>741</td>
                                                        <td>140</td>
                                                        <td><a href="view_device.php" class="btn btn-primary"><i
                                                                    class="las la-eye"></i></a></td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div> -->
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
        var table = $('#doctorProfileTable').DataTable();
    });
    // product Quantity Table
    $(document).ready(function() {
        var table = $('#productQuantityTable').DataTable();
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