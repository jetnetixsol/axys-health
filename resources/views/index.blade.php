<?php $page = 'index'; //require_once 'header.php'
?>
@include('header')
<!-- end color quick setting -->
<style>
    .fr-cl-det{
        margin-bottom:20px!important;
    }
    .clinic-info {
        height: 200px;
        overflow: auto!important;
    }
    .clinic-info::-webkit-scrollbar{
        width: 5px;
    }
    .clinic-info::-webkit-scrollbar-track{
        background-color: transparent;
    }
    .clinic-info::-webkit-scrollbar-thumb{
        background-color: #dddddd;
    }
    #carddetail {
        width: 20rem;
    }
</style>
<!-- end sidebar menu -->
<!-- start page content -->
<div class="page-content-wrapper indx">
    <div class="page-content">
        <div class="page-bar">
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
            <div class="page-title-breadcrumb">
                <div class="page-title">Dashboard
                    <div style="display: flex;">
                        @php $role = Auth::guard()->user()->role; @endphp
                        @if($role === 'clinic')
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#device_req"> Request Devices</button>
                        <a class="btn btn-primary me-2" href="{{ route('clinic.patient.assign') }}">Assign To Patient</a>
                        @endif

                        @if($role === 'doctor')
                        <a class="btn btn-primary me-2" href="{{ route('patient.assign') }}">Assign To Patient</a>
                        @endif

                        {{-- @if($role === 'clinic')
                        <!-- <a href="{{ route('doctor.assigndevice') }}" class="btn btn-primary me-2"><i class="las la-user"></i> Assigned To Doctor</a> -->
                        @endif --}}

                        @if($role === 'admin')
                        <a href="{{ route('buy.monitor') }}" class="btn btn-primary"><i class="las la-comment-dollar"></i> Send Quotation</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- start widget -->
        <div class="state-overview">
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="info-box bg-blue">
                        <span class="info-box-icon push-bottom"><i class="las la-briefcase-medical pb-1"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Doctors</span>
                            <span class="info-box-number">{{ sizeof($doctors) }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="info-box bg-orange">
                        <span class="info-box-icon push-bottom"><i class="las la-procedures"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Patients</span>
                            <span class="info-box-number">{{ $totalPatientCount }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="info-box bg-purple">
                        <span class="info-box-icon push-bottom"><i class="las la-clinic-medical pb-1"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Clinic</span>
                            <span class="info-box-number">{{ $clinicsCount }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="info-box bg-success">
                        <span class="info-box-icon push-bottom"><i class="las la-weight"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Machines</span>
                            <span class="info-box-number">{{ $devicesCount }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- end widget -->
        <!-- chart start -->
        <div class="row">
            <div class="col-md-8">
                <div class="card card-box">
                    <div class="card-head">
                        <header>Readings</header>
                        <div class="tools">
                            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                        </div>
                    </div>
                    <div class="card-body no-padding height-9">
                        <div class="recent-report__chart">
                            <div id="chart4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-box">
                    <div class="card-head">
                        <header>Patients</header>
                        <div class="tools">
                            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                        </div>
                    </div>
                    <div class="card-body no-padding height-9">
                        <div class="recent-report__charts">
                            <div id="chart2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Chart end -->
        <style>
            
           

            #dlistcardp {
                background-color: #eaeef3;
            }

            header.hd-requ {
                padding: 0;
            }

            header.hd-requ .nav-tabs {
                margin-bottom: 0 !important;
                border: none;
            }

            header.hd-requ .nav-tabs>li>a {
                font-size: 14px;
                text-transform: capitalize !important;
                border-radius: 10px;
                padding-top: 5px;
                padding-bottom: 5px;
                color: #ffffff;
            }

            header.hd-requ .nav-tabs>li>a.active {
                color: #000000;
            }
        </style>
        <div class="row">
            @if($role === "admin" )
            <div class="col-md-6 col-sm-12">
                <div class="card  card-box crd-pr">
                    <div class="card-head">
                        <header class="hd-requ">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#all" data-bs-toggle="tab" class="active">{{-- $role === 'admin' ? 'Clinic' : 'Doctor' --}}Buyer Requests</a>
                                </li>
                                <li class="nav-item"><a href="#archive" data-bs-toggle="tab" class="">Archived</a>
                                </li>
                            </ul>
                        </header>
                        <div class="tools">
                            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="all">
                            <div class="card-body no-padding height-9">
                                <div class="row">
                                    <ul class="docListWindow" id="requestContainer">
                                        @if(!empty($requests))
                                        @foreach($requests as $request)
                                        <li id="request{{ $request['id'] }}">
                                            <div class="details">
                                                <div class="title">
                                                    <h5>{{ $role === 'admin' ? $request['name'] : ((isset($request['name']) ? $request['name'].' ' : '')
                                                        .(isset($request['middle_name']) ? $request['middle_name'].' ' : '')
                                                        .(isset($request['last_name']) ? $request['last_name'] : '')) }}
                                                        <a><span>{{ $request['quantity'] }}</span> Device{{ $request['quantity'] > 1 ? 's' : ''}}</a>
                                                    </h5>
                                                </div>
                                                @php
                                                $encGroup = json_encode($request);
                                                $requestBy = $request['request_by'];
                                                @endphp
                                                <div class="btn-group">
                                                    <button class="btn btn-primary" id="assign-btn{{ $request['id'] }}" onclick="assignDevModal('{{ $encGroup  }}','{{ $requestBy }}')" type="button">Assign to Clinic</button>
                                                    <div style="display: flex;" id="ac-btn-container{{ $request['id'] }}">
                                                        <button class="btn btn-dark" id="archive-btn{{ $request['id'] }}" onclick="moveToArchive('<?= $request['id'] ?>','archive')" type="button">Archive</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        @else
                                        <li id="no-request" style="text-align: center;">No Request Now!</li>
                                        @endif
                                    </ul>
                                    <div class="text-center full-width">
                                        <!-- <a href="#">View all</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="archive">
                            <div class="card-body no-padding height-9">
                                <div class="row">
                                    <ul class="docListWindow" id="archiveContainer">
                                        @if(!empty($archiveRequests))
                                        @foreach($archiveRequests as $request)
                                        <li id="archive{{ $request['id'] }}">
                                            <div class="details">
                                                <div class="title">
                                                    <h5>{{ $role === 'admin' ? $request['name'] : ((isset($request['name']) ? $request['name'].' ' : '')
                                                        .(isset($request['middle_name']) ? $request['middle_name'].' ' : '')
                                                        .(isset($request['last_name']) ? $request['last_name'] : '')) }}
                                                        <a><span>{{ $request['quantity'] }}</span> Device{{ $request['quantity'] > 1 ? 's' : ''}}</a>
                                                    </h5>
                                                </div>
                                                @php
                                                $encGroup = json_encode($request);
                                                $requestBy = $request['request_by'];
                                                @endphp
                                                <button class="btn btn-primary" id="assign-btn{{ $request['id'] }}" hidden onclick="assignDevModal('{{ $encGroup  }}','{{ $requestBy }}')" type="button">Assign to Clinic</button>
                                                <div style="display: flex;" id="ac-btn-container{{ $request['id'] }}">
                                                    <button class="btn btn-dark" id="archive-btn{{ $request['id'] }}" onclick="moveToArchive('<?= $request['id'] ?>','unarchive')" type="button">Unarchive</button>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        @else
                                        <li id="no_archive" style="text-align: center;">No Archived Request Now!</li>
                                        @endif
                                    </ul>
                                    <div class="text-center full-width">
                                        <!-- <a href="#">View all</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- start admited patient list -->
            </div>
            @if($role === 'admin' || $role === 'clinic')
            <div id="dlistcardp" class="{{ $role === 'admin' ?  'col-md-6' : 'col-md-12'}} col-sm-12">
                <div id="{{ $role === 'admin' ?  '' : 'dlistcard'}}" class="card card-box">
                    <div class="card-head">
                        <header>DOCTORS LIST</header>
                        <div class="tools">
                            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                        </div>
                    </div>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                    <div class="card-body no-padding height-9">
                        <div class="row">
                            <ul class="docListWindow">
                                @if(!empty($doctors))
                                @foreach($doctors as $doctor)
                                @php
                                //Concat Doctor Name
                                $doctor['name'] = (isset($doctor['name']) ? $doctor['name'].' ' : '')
                                .(isset($doctor['middle_name']) ? $doctor['middle_name'].' ' : '')
                                .(isset($doctor['last_name']) ? $doctor['last_name'] : '');
                                @endphp
                                <li>
                                    <div class="prog-avatar">
                                        <!-- <img src="{{ asset('assets/img/doc/doc1.jpg') }}" alt="" width="40" height="40"> -->
                                        <i class="fa fa-user-doctor" style="font-size: 40px;"></i>
                                    </div>
                                    <div class="details">
                                        <div class="title">
                                            <a href="{{ route('doctor.single',['id'=>$doctor['id']]) }}" target="_blank">{{ $doctor['name'] }}</a>{{ isset($doctor['speciality']) ? ' -'.'('.$doctor['speciality'].')' : '' }}
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                @else
                                <li style="text-align: center;">No Doctors Available Now!</li>
                                @endif
                                <!-- <li>
                                                <div class="prog-avatar">
                                                    <img src="assets/img/doc/doc2.jpg" alt="" width="40" height="40">
                                                </div>
                                                <div class="details">
                                                    <div class="title">
                                                        <a href="#">Dr.Sarah Smith</a> -(MBBS,MD)
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="prog-avatar">
                                                    <img src="assets/img/doc/doc3.jpg" alt="" width="40" height="40">
                                                </div>
                                                <div class="details">
                                                    <div class="title">
                                                        <a href="#">Dr.John Deo</a> - (BDS,MDS)
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="prog-avatar">
                                                    <img src="assets/img/doc/doc4.jpg" alt="" width="40" height="40">
                                                </div>
                                                <div class="details">
                                                    <div class="title">
                                                        <a href="#">Dr.Jay Soni</a> - (BHMS)
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="prog-avatar">
                                                    <img src="assets/img/doc/doc5.jpg" alt="" width="40" height="40">
                                                </div>
                                                <div class="details">
                                                    <div class="title">
                                                        <a href="#">Dr.Jacob Ryan</a> - (MBBS,MS)
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="prog-avatar">
                                                    <img src="assets/img/doc/doc6.jpg" alt="" width="40" height="40">
                                                </div>
                                                <div class="details">
                                                    <div class="title">
                                                        <a href="#">Dr.Megha Trivedi</a> - (MBBS,MS)
                                                    </div>
                                                </div>
                                            </li> -->
                            </ul>
                            <div class="text-center full-width">
                                <a href="{{ route('doctors') }}">View all</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- end page content -->


        @if($role === 'admin' || $role === 'clinic')
        <div class="modal fade" id="assignDev" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Assign To <span id="mod-title">Clinic</span></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body buy-mon">
                        <form action="{{ route('assign.device') }}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="grd-buy grd-dt">
                                            <div class="buy-in">
                                                <div  class="card crd_one">
                                                    <div class="card-head">
                                                        <h4 class="card-title">Devices</h4>
                                                        <div class="form-group">
                                                            <!-- <div class="input-group spinner">
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
                                                                </div> -->
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="containerOuter">
                                                            <div class="wrpr">
                                                                <ul class="mnt" id="selected-devices">
                                                                    <!-- <li>
                                                                            <input type="radio" class="hidden" id="input1" name="inputs" checked>
                                                                            <label class="entry" for="input1">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Device 1</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li> -->
                                                                    <!-- <li>
                                                                            <input type="radio" class="hidden" id="input2" name="inputs">
                                                                            <label class="entry" for="input2">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 2</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input3" name="inputs">
                                                                            <label class="entry" for="input3">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 3</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input4" name="inputs">
                                                                            <label class="entry" for="input4">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 4</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input5" name="inputs">
                                                                            <label class="entry" for="input5">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 5</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input6" name="inputs">
                                                                            <label class="entry" for="input6">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 6</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input7" name="inputs">
                                                                            <label class="entry" for="input7">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 7</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input8" name="inputs">
                                                                            <label class="entry" for="input8">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 8</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" class="hidden" id="input9" name="inputs">
                                                                            <label class="entry" for="input9">
                                                                                <span class="entry-label">
                                                                                    <span class="inr">
                                                                                        <b>Pair 9</b>
                                                                                        <a href="#">Details</a>
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                                                        </li> -->
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="buy-mid">
                                                <div class="btn-group">
                                                    <button class="btn btn-primary" type="button" id="clk_btn" style="background-color:rgba(93, 109, 233,0.7)!important" data-toggle="tooltip" data-placement="top" title="Link Device"><i class="las la-link"></i></button>
                                                    <button class="btn btn-dark titletip" type="button" id="clk_unlink" style="background-color:#000000 !important" data-bs-toggle="popover" data-bs-trigger="hover" title="Dismissible popover" data-bs-content="And here's some amazing content. It's very engaging. Right?"><i class="las la-unlink"></i></button>
                                                    <input value="no" name="linked" id="linked" hidden />
                                                </div>
                                            </div>
                                            <div class="buy-in">
                                                <div class="clc-nm">

                                                    <div id="carddetail" class="fr-cl-det card crd_one">
                                                        <div class="card-head">
                                                            <h4 class="card-title"><span id="mod-card-title">Clinic</span> Details</h4>
                                                        </div>

                                                        <div class="card-body clinic-info ">
                                                            <div class="row ">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="" id="mod-label">Clinic</label>
                                                                        <p id="clinic_name">--</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="" id="mod-label">Email</label>
                                                                        <p id="c_email">--</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="" id="mod-label">Address</label>
                                                                        <p id="c_address">--</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="" id="mod-label">City</label>
                                                                        <p id="c_city">--</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="" id="mod-label">State</label>
                                                                        <p id="c_state">--</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="totl">
                                                    <div class="card crd_one cardbill">
                                                        <div class="card-head">
                                                            <h4 class="card-title">Total Bill</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="lbl-det">
                                                                <label for="">Device <span id="quantiy_price"></span></label>
                                                                <p>$<span id="pair_price">30</span></p>
                                                            </div>
                                                            <!-- <div class="lbl-det">
                                                                <label for="">Discount</label>
                                                                <p>0%</p>
                                                            </div> -->
                                                            <!-- <div class="lbl-det">
                                                                    <label for="">Tax</label>
                                                                    <p>$12</p>
                                                                </div> -->
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="lbl-det">
                                                                <label for="">Total</label>
                                                                <p>$<span id="total_price">00</span></p>
                                                                <input type="number" name="total_price" step="0.01" value="0" id="total_price_val" hidden />
                                                                <input type="number" name="quantity_val" value="" id="quant" hidden />
                                                                <input type="number" name="clinic_id" id="clinic_id" value="" hidden>
                                                                <input type="text" name="request_by" id="request_by" value="" hidden>
                                                                <input type="number" name="request_id" id="request_id" value="" hidden>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" type="submit">Done</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($role === 'clinic')
        <!-- Request Device Modal -->
        <div class="modal fade" id="device_req" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">Device Request</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body buy-mon">
                        <form action="{{ route('request.device') }}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="grd-buy grd-dt">
                                            <div class="buy-in">
                                                <div class="card crd_one">
                                                    <div class="card-head">
                                                        <h4 class="card-title">Devices</h4>
                                                        <div class="form-group">
                                                            <div class="input-group spinner">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-primary bg-ad" data-dir="dwn" type="button">
                                                                        <span class="fa fa-minus"></span>
                                                                    </button>
                                                                </span>
                                                                <input type="text" required name="quantity" value="1" onkeypress="onlyNumber(event)" max="{{ $availableDeviceCount }}" class="form-control text-center" min="1">{{-- {{ isset($availableDeviceCount) ? $availableDeviceCount : 0 }} --}}
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-primary bg-ad" data-dir="up" type="button">
                                                                        <span class="fa fa-plus"></span>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" type="submit">Done</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Request Device Modal End -->
        @endif


        <!-- chart js -->
        <!-- Page Specific JS File -->
        <script>
            
            $('#clk_btn').click(function() {
                $('#clk_btn').attr('style', 'background-color:rgba(93, 109, 233)!important');
                $('#clk_unlink').attr('style', 'background-color:#999999 !important');
                $('.crd_one').css('border', '3px solid #8155ec');
                $('.crd_one').css('box-shadow', '0 0 30px rgba(0,0,0,0.2)');
                $('.crd_one').css('transition', '.3s');
                $('#linked').val('yes');
            });
            $('#clk_unlink').click(function() {
                $('#clk_btn').attr('style', 'background-color:rgba(93, 109, 233,0.7)!important');
                $('#clk_unlink').attr('style', 'background-color:#000000 !important');
                $('.crd_one').css('border', '1px solid #deebfd');
                $('.crd_one').css('box-shadow', 'none');
                $('.crd_one').css('transition', '.3s');
                $('#linked').val('no');
            });
        </script>
        <script src="{{ asset('assets/bundles/chart-js/Chart.min.js') }}"></script>
        <script src="{{ asset('assets/bundles/chart-js/utils.js') }}"></script>
        <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
        <!-- <script src="{{ asset('assets/data/apexcharts.data.js') }}"></script> -->
        <!--	<script src="assets/data/apex-home.js"></script>-->
        <script>
            function assignDevModal(request, requestBy) {
                var request = JSON.parse(request);
                // console.log(request);
                if (Object.keys(request).length > 0) {
                    $('#selected-devices').html('');
                    var count = request.quantity; //Devices Count
                    var clinicName = (requestBy == 'clinic' ? request.name : request.name + (request.middle_name.length > 0 ? ' ' + request.middle_name : '') + (request.last_name.length > 0 ? ' ' + request.last_name : ''));
                    var clinicID = (requestBy == 'clinic' ? request.clinic_id : request.doctor_clinic_id);
                    var userID = (requestBy == 'clinic' ? request.clinic_id : request.doctor_id);

                    var _token = '{{ csrf_token() }}';

                    // console.log("Here",request.name);
                    $.ajax({
                        url: '{{ route("fetch.devices") }}',
                        dataType: 'json',
                        method: 'post',
                        data: {
                            _token,
                            count,
                            clinicID,
                            requestBy
                        },
                        success: function(result) {
                            Object.keys(result.devices).map(function(key) {
                                // console.log(result.devices[key]['id']);
                                $('#selected-devices').append(`<li>
														<input type="select" class="hidden" value="${ result.devices[key]['id'] }" id="input1" name="deviceIds[]" selected>
														<label class="entry" for="input1">
															<span class="entry-label">
																<span class="inr">
																	<b>${ result.devices[key]['serial_number'] }</b>
																	<a href="{{ url('view-single-device') }}${"/"+result.devices[key]['id']}" target="_blank">Details</a>
																</span>
															</span>
														</label>
													</li>`);
                            });
                            // console.log(devicesArr);
                            // result.devices.foreach(function(val,index){

                            // });
                        },
                        fail: function(error) {
                            console.log(error.responseJSON)
                        }
                    })

                    // requestGroup.forEach(function(val, index) {
                    // 	if (index == 0) {
                    // 		clinicName = val['name'];
                    // 		clinicID = val['clinic_id'];
                    // 	}

                    // 	count += 1;
                    // });

                    $("#quantiy_price").text("(" + count + " * $" + 30 + ")");
                    $('#pair_price').text(parseFloat(count * 30));
                    $('#total_price').text(parseFloat(count * 30));
                    $('#total_price_val').val(parseFloat(count * 30));
                    $('#quant').val(count);

                    if (requestBy == 'clinic') {
                        $('#mod-title').text('Clinic');
                        $('#mod-card-title').text('Clinic');
                        $('#mod-label').text('Clinic');
                    } else if (requestBy == 'doctor') {
                        $('#mod-title').text('Doctor');
                        $('#mod-card-title').text('Doctor');
                        $('#mod-label').text('Doctor');
                    }

                    $('#request_by').val(requestBy);
                    $('#clinic_name').text(clinicName); //clinic name or doctor name
                    $('#c_email').text(request.email != null ? request.email : '--'); //clinic name or doctor name
                    $('#c_address').text(request.address != null ? request.address : '--'); //clinic address
                    $('#c_city').text(request.city != null ? request.city : '--'); //clinic address
                    $('#c_state').text(request.state != null ? request.state : '--'); //clinic address
                    $('#clinic_id').val(userID); //Either clinic ID or doctor ID
                    $('#request_id').val(request.id);
                }
                $('#assignDev').modal('show');
            }

            //Reaing Charts Render
            $(document).ready(function() {
                var categoriesArr = '{{ $readings }}';
                var datesData = [];
                var sysData = [];
                var diaData = [];
                if (categoriesArr.length > 0) {
                    categoriesArr = JSON.parse(categoriesArr.replace(/&quot;/g, '"'));
                    categoriesArr.map(function(element, index) {
                        if (element.created_at != null) {
                            var formatDate = new Date(element.created_at); //month-date-year
                            datesData.push((formatDate.getMonth() + 1) + "/" + formatDate.getDate() + "/" +
                                formatDate.getFullYear());
                            sysData.push(element.systolic != null ? element.systolic : 0);
                            diaData.push(element.diastolic != null ? element.diastolic : 0);
                        }
                    });
                }

                var options = {
                    chart: {
                        height: 350,
                        type: 'line',
                        shadow: {
                            enabled: false,
                            color: '#bbb',
                            top: 3,
                            left: 2,
                            blur: 3,
                            opacity: 1,
                        },
                    },
                    stroke: {
                        width: 7,
                        curve: 'smooth',
                    },
                    series: [{
                        name: 'Systolic',
                        // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5],
                        data: sysData,
                    }, {
                        name: 'Diastolic',
                        // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5],
                        data: diaData,
                    }],
                    xaxis: {
                        type: 'datetime',
                        // categories: [
                        // 	'1/11/2000',
                        // 	'2/11/2000',
                        // 	'3/11/2000',
                        // 	'4/11/2000',
                        // 	'5/11/2000',
                        // 	'6/11/2000',
                        // 	'7/11/2000',
                        // 	'8/11/2000',
                        // 	'9/11/2000',
                        // 	'10/11/2000',
                        // 	'11/11/2000',
                        // 	'12/11/2000',
                        // 	'1/11/2001',
                        // 	'2/11/2001',
                        // 	'3/11/2001',
                        // 	'4/11/2001',
                        // 	'5/11/2001',
                        // 	'6/11/2001',
                        // ],
                        categories: datesData,
                        labels: {
                            style: {
                                colors: '#9aa0ac',
                            },
                        },
                    },
                    title: {
                        text: '',
                        align: 'left',
                        style: {
                            fontSize: '16px',
                            color: '#666',
                        },
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            gradientToColors: ['#8155ec'],
                            shadeIntensity: 1,
                            type: 'horizontal',
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100, 100, 100],
                        },
                    },
                    markers: {
                        size: 4,
                        opacity: 0.9,
                        colors: ['#FFA41B'],
                        strokeColor: '#fff',
                        strokeWidth: 2,

                        hover: {
                            size: 7,
                        },
                    },
                    yaxis: {
                        min: 50,
                        max: 250,
                        title: {
                            text: 'Engagement',
                        },
                        labels: {
                            style: {
                                colors: '#9aa0ac',
                            },
                        },
                    },
                    tooltip: {
                        theme: 'dark',
                        marker: {
                            show: true,
                        },
                        x: {
                            show: true,
                        },
                    },
                };

                var chart = new ApexCharts(document.querySelector('#chart4'), options);

                chart.render();
            });

            $(document).ready(function() {
                var noOfPatients = '{{ json_encode($noOfPatients) }}';
                var totalPatient = '{{ $totalPatientCount }}';
                var patientData = [];
                if (noOfPatients.length > 0) {
                    noOfPatients = JSON.parse(noOfPatients.replace(/&quot;/g, '"'));
                    $.each(noOfPatients, function(index, val) {
                        // console.log(val, parseFloat(totalPatient));
                        var percent = (parseFloat(val) * 100) / parseFloat(totalPatient);
                        patientData.push(percent);
                    });
                }
                var options = {
                    chart: {
                        height: 350,
                        type: 'bar',
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top', // top, center, bottom
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val + '%';
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ['#9aa0ac'],
                        },
                    },
                    series: [{
                        name: 'Patients Comparison',
                        // data: [2.3, 3.1, 4.0, 10.0, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2],
                        data: patientData,
                    }, ],
                    xaxis: {
                        categories: [
                            'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec',
                        ],
                        position: 'bottom',
                        labels: {
                            style: {
                                colors: '#9aa0ac',
                            },
                        },
                        labels: {
                            style: {
                                colors: '#9aa0ac',
                            },
                            offsetY: -5,
                        },
                        axisBorder: {
                            show: true,
                        },
                        axisTicks: {
                            show: false,
                        },
                        crosshairs: {
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    colorFrom: '#D8E3F0',
                                    colorTo: '#BED1E6',
                                    stops: [0, 100],
                                    opacityFrom: 0.4,
                                    opacityTo: 0.5,
                                },
                            },
                        },
                        tooltip: {
                            enabled: true,
                            offsetY: -35,
                        },
                    },
                    fill: {
                        gradient: {
                            shade: 'light',
                            type: 'horizontal',
                            shadeIntensity: 0.25,
                            gradientToColors: undefined,
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [50, 0, 100, 100],
                        },
                    },
                    yaxis: {
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: true,
                        },
                        labels: {
                            show: true,
                            formatter: function(val) {
                                return val + '%';
                            },
                            style: {
                                colors: '#9aa0ac',
                            },
                        },
                    },
                    title: {
                        text: '',
                        floating: true,
                        offsetY: 330,
                        align: 'center',
                        style: {
                            color: '#9aa0ac',
                        },
                    },
                    tooltip: {
                        theme: 'dark',
                        marker: {
                            show: true,
                        },
                        x: {
                            show: true,
                        },
                    },
                };

                var chart = new ApexCharts(document.querySelector('#chart2'), options);

                chart.render();
            });

            function moveToArchive(requestID, action) {
                var work = 'move_archive';
                let _token = '{{ csrf_token() }}';
                $.ajax({
                    url: "{{ route('ajax.perform') }}",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        work,
                        _token,
                        action,
                        requestID
                    },
                    success: (result) => {
                        if (result.success == 1) {
                            if (action == 'archive') {
                                var item = $("#request" + requestID).html();
                                $("#requestContainer").find('#request' + requestID).remove();
                                $("#archiveContainer").append(`<li id="archive${ requestID }">
                                ${item}
                            </li>`);
                                $("#assign-btn" + requestID).attr("hidden", true);
                                $("#ac-btn-container" + requestID).html(`<button class="btn btn-dark" id="archive-btn${requestID}" onclick="moveToArchive('${requestID}','unarchive')" type="button">Unarchive</button>`);
                                $('#archiveContainer').find('#no_archive').remove();
                                $("#requestContainer li").length > 0 ? '' : $('#requestContainer').html(`<li id="no-request" style="text-align: center;">No Request Now!</li>`);
                            } else if (action == 'unarchive') {
                                var item = $("#archive" + requestID).html();
                                $("#archiveContainer").find('#archive' + requestID).remove();
                                $("#requestContainer").append(`<li id="request${ requestID }">
                                    ${item}
                                </li>`);
                                $("#assign-btn" + requestID).attr("hidden", false);
                                $("#ac-btn-container" + requestID).html(`<button class="btn btn-dark" id="archive-btn${requestID}" onclick="moveToArchive('${requestID}','archive')" type="button">Archive</button>`);
                                $('#requestContainer').find('#no-request').remove();
                                $("#archiveContainer li").length > 0 ? '' : $('#archiveContainer').html(`<li id="no_archive" style="text-align: center;">No Archived Request Now!</li>`);
                                // setTimeout(function() {
                                //     console.log($('#archiveContainer').find('#no_archive'));
                                // }, 300);
                            }
                        }
                    },
                    error: (error) => {
                        console.log(error.responseJSON);
                    }
                });
            }
        </script>

        @include('footer')
        <!-- </?php require_once 'footer.php'?> -->