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
    <!--    <link href="assets/css/plugins.min.css" rel="stylesheet" type="text/css"/>-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/theme-color.css') }}" rel="stylesheet" type="text/css" />
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/my-img/favicon.png') }}" />

    <script src="{{ asset('assets/bundles/jquery/jquery.min.js') }}"></script>

    <style>
        .search-bar {
            margin-top: 10px;

        }

        .search-top-header {
            width: 50% !important;
        }

        .top-menu {
            position: absolute;
            top: 0;
            right: 0;
        }

        .page-header.navbar .top-menu .navbar-nav>li.dropdown-user .dropdown-menu>li>a i {
            display: none;
        }

        .hdr-wallet i {
            font-size: 20px;
        }

        .navbar-nav>li.hdr-wallet>a {
            padding: 5px 10px !important;
            margin-top: 15px;
            border-radius: 5px;
            width: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .topup-btn {
            font-size: 12px;
            height: 30px;
            display: flex;
            align-items: center;
            border-radius: 5px;
        }
    </style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white white-sidebar-color logo-indigo">
    <div class="page-wrapper">
        <!-- start header -->
        <div class="page-header navbar navbar-fixed-top">
            <div class="page-header-inner ">
                <!-- logo start -->
                <div class="page-logo">
                    <a href="index.php">
                        <div class="lgo-img logo-default" style="background-image:url('<?= asset('assets/img/my-img/white-logo.png') ?>')"></div>
                        <div class="lgo-ico logo-icon" style="background-image:url('<?= asset('assets/img/my-img/color-icon.png') ?>')"></div>
                    </a>
                </div>
                <!-- logo end -->
                <ul class="nav navbar-nav navbar-left in">
                    <li><a href="#" class="menu-toggler sidebar-toggler"><i data-feather="menu"></i></a></li>
                </ul>
                <!-- start mobile menu -->
                <a class="menu-toggler responsive-toggler" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
                    <span></span>
                </a>
                <!-- end mobile menu -->
                <!-- start header menu -->
                <!-- <div class="search-bar d-flex justify-content-center align-items-center">
                    <input type="text" placeholder="Search Device" class="form-control search-top-header">
                    <a href="search_device.blade.php" class="btn btn-primary m-0 ms-2" >Search</a>
                </div> -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="device_req">
                        </li>
                        @if(Auth::guard()->user()->role === 'admin')
                        <li>

                        </li>
                        @endif

                        @if(Auth::guard()->user()->role === 'clinic')
                        <li class="hdr-wallet">
                            <!-- session()->has('clinicBalance')  -->
                            <a href="{{ url('/wallet') }}" class="btn btn-primary"><i class="las la-wallet"></i> $<?php if (session()->has('clinicBalance')) {
                                                                                                                        $balance = session()->get('clinicBalance');
                                                                                                                        switch ($balance) {
                                                                                                                            case $balance >= 10000 && $balance < 1000000:
                                                                                                                                $fBalance = $balance / 1000;
                                                                                                                                echo $fBalance . 'K';
                                                                                                                                break;
                                                                                                                            case $balance >= 1000000 && $balance < 1000000000:
                                                                                                                                $fBalance = $balance / 1000000;
                                                                                                                                echo $fBalance . 'M';
                                                                                                                                break;
                                                                                                                            case $balance >= 1000000000:
                                                                                                                                $fBalance = $balance / 1000000000;
                                                                                                                                echo $fBalance . 'B';
                                                                                                                                break;
                                                                                                                            default:
                                                                                                                                echo $balance;
                                                                                                                                break;
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 0.0;
                                                                                                                    }
                                                                                                                    ?>
                            </a>
                        </li>
                        @endif
                        <!-- start notification dropdown -->
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i data-feather="bell"></i>
                                <span class="badge headerBadgeColor1"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3><span class="bold">Notifications</span></h3>
                                    <span class="notification-label purple-bgcolor">New <span id="new-count">0</span></span>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283" id="notif-container">
                                        <!-- <li>
                                        <a href="order_request.php" class="unread">
                                            <span class="time red-pri">High Priority</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>ABC Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 20 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li> -->
                                        <!-- <li>
                                        <a href="order_request.php" class="unread">
                                            <span class="time grn-pri">New Request</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>GCD Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 3 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="order_request.php" class="unread">
                                            <span class="time del-pri">Sent Order</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>NBP Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 50 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="order_request.php">
                                            <span class="time grn-pri">New Request</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>GCD Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 3 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="order_request.php">
                                            <span class="time del-pri">Sent Order</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>NBP Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 50 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="order_request.php">
                                            <span class="time grn-pri">New Request</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>GCD Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 3 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="order_request.php">
                                            <span class="time del-pri">Sent Order</span>
                                            <span class="details">
                                                <span class="notification-icon circle deepPink-bgcolor">
                                                    <i class="las la-gavel"></i>
                                                </span>
                                                <span class="noti-det">
                                                    <b>NBP Clinic</b>
                                                    <span class="noti-pra">
                                                        Request 50 Devices
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li> -->
                                    </ul>
                                    <!-- <div class="dropdown-menu-footer">
                                        <a href="javascript:void(0)"> All notifications </a>
                                    </div> -->
                                </li>
                            </ul>
                        </li>
                        <!-- end notification dropdown -->
                        <!-- start message dropdown -->
                        <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                            <!-- <a class="dropdown-toggle" data-bs-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <i data-feather="mail"></i>
                            <span class="badge headerBadgeColor2"> 2 </span>
                        </a> -->
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3><span class="bold">Messages</span></h3>
                                    <span class="notification-label cyan-bgcolor">New 2</span>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="assets/img/doc/doc2.jpg" class="img-circle" alt="">
                                                </span>
                                                <span class="subject">
                                                    <span class="from"> Sarah Smith </span>
                                                    <span class="time">Just Now </span>
                                                </span>
                                                <span class="message"> Order Ready? </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="assets/img/doc/doc3.jpg" class="img-circle" alt="">
                                                </span>
                                                <span class="subject">
                                                    <span class="from"> John Deo </span>
                                                    <span class="time">16 mins </span>
                                                </span>
                                                <span class="message"> Fwd: Important Notice Regarding Your Device #215... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="assets/img/doc/doc1.jpg" class="img-circle" alt="">
                                                </span>
                                                <span class="subject">
                                                    <span class="from"> Rajesh </span>
                                                    <span class="time">2 hrs </span>
                                                </span>
                                                <span class="message"> pls take a print of attachments. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="assets/img/doc/doc8.jpg" class="img-circle" alt="">
                                                </span>
                                                <span class="subject">
                                                    <span class="from"> Lina Smith </span>
                                                    <span class="time">40 mins </span>
                                                </span>
                                                <span class="message"> Apply for Ortho Surgeon </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="assets/img/doc/doc5.jpg" class="img-circle" alt="">
                                                </span>
                                                <span class="subject">
                                                    <span class="from"> Jacob Ryan </span>
                                                    <span class="time">46 mins </span>
                                                </span>
                                                <span class="message"> I wan to request for new device. </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="dropdown-menu-footer">
                                        <a href="#"> All Messages </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- end message dropdown -->
                        <!-- start manage user dropdown -->
                        <li class="dropdown dropdown-user">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <div class="usr-ar">
                                    <div class="usr-dp" style="background-image: url('<?= asset('assets/img/my-img/usr.png') ?>')"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <!-- <li>
                                    <a href="#">
                                        <i class="icon-user"></i> Profile
                                    </a>
                                </li> -->
                                <li class="divider"> </li>
                                <li>
                                    <a href="{{ route('logout') }}">
                                        <i class="icon-logout"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end manage user dropdown -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- end header -->

        <!-- start page container -->
        @php $role = Auth::guard()->user()->role; @endphp
        <div class="page-container">
            <!-- start sidebar menu -->
            <div class="sidebar-container">
                <div class="sidemenu-container navbar-collapse collapse fixed-menu">
                    <div id="remove-scroll" class="left-sidemenu">
                        <ul class="sidemenu  page-header-fixed slimscroll-style" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <li class="sidebar-user-panel">
                                <div class="sidebar-user">
                                    <div class="sidebar-user-picture">
                                        <div class="usr-bg">
                                            <div class="usr-img" style="background-image:url('<?= asset('assets/img/my-img/usr.png') ?>')"></div>
                                        </div>
                                    </div>
                                    <div class="sidebar-user-details">
                                        <div class="user-name">{{ isset(Auth::guard()->user()->name) ? Auth::guard()->user()->name : '-' }}</div>
                                        <div class="user-role">{{ ucfirst($role) }}</div>
                                    </div>
                                </div>
                            </li>
                            @if($role === 'admin' || $role === 'clinic' || $role === 'doctor')
                            <li class="nav-item <?php if ($page == 'index') {
                                                    echo 'active';
                                                } ?>">
                                <a href="{{ route('index') }}" class="nav-link nav-toggle">
                                    <i class="las la-tachometer-alt"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            @endif

                            @if($role === 'admin')
                            <li class="nav-item <?php if ($page == 'add_clinic' || $page == 'all_clinics') {
                                                    echo 'active';
                                                } ?>">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="las la-hospital"></i>
                                    <span class="title">Clinic</span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item <?php if ($page == 'add_clinic') {
                                                            echo 'active';
                                                        } ?>">
                                        <a href="{{ route('add.clinic') }}" class="nav-link nav-toggle">
                                            <i class="lar la-circle"></i> Add Clinic
                                        </a>
                                    </li>
                                    <li class="nav-item <?php if ($page == 'all_clinics') {
                                                            echo 'active';
                                                        } ?>">
                                        <a href="{{ route('clinics') }}" class="nav-link nav-toggle">
                                            <i class="lar la-circle"></i> All Clinics
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if($role === 'admin' || $role === 'clinic')
                            <li class="nav-item <?php if ($page == 'add_doctor' || $page == 'all_doctors') {
                                                    echo 'active';
                                                } ?>">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="las la-user-tie"></i>
                                    <span class="title">Doctor</span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item <?php if ($page == 'add_doctor') {
                                                            echo 'active';
                                                        } ?>">
                                        <a href="{{ route('add.doctor') }}" class="nav-link nav-toggle">
                                            <i class="lar la-circle"></i> Add Doctor
                                        </a>
                                    </li>
                                    <li class="nav-item <?php if ($page == 'all_doctors') {
                                                            echo 'active';
                                                        } ?>">
                                        <a href="{{ route('doctors') }}" class="nav-link nav-toggle">
                                            <i class="lar la-circle"></i> All Doctors
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if($role === 'admin' || $role === 'clinic' || $role === 'doctor')
                            <li class="nav-item <?php if ($page == 'all_patients' || $page == 'add_patient') {
                                                    echo 'active';
                                                } ?>">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="las la-user-tie"></i>
                                    <span class="title">Patients</span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item <?php if ($page == 'add_doctor') {
                                                            echo 'active';
                                                        } ?>">
                                        <a href="{{ route('add.patient') }}" class="nav-link nav-toggle">
                                            <i class="lar la-circle"></i> Add Patient
                                        </a>
                                    </li>
                                    <li class="nav-item <?php if ($page == 'all_doctors') {
                                                            echo 'active';
                                                        } ?>">
                                        <a href="{{ route('patients') }}" class="nav-link nav-toggle">
                                            <i class="lar la-circle"></i> All Patients
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            <!-- {{-- <li class="nav-item </?php if($page=='all_patients' || $page=='add_patient'){echo 'active';} ?>">
                                <a href="{{ route('patients') }}" class="nav-link nav-toggle">
                                    <i class="las la-user-injured"></i>
                                    <span class="title">Patients</span>
                                </a>
                            </li> --}} -->
                            @if($role === 'admin' || $role === 'clinic' || $role === 'doctor')
                            <li class="nav-item <?php if ($page == 'all_devices') {
                                                    echo 'active';
                                                } ?>">
                                <a href="{{ route('devices') }}" class="nav-link nav-toggle">
                                    <i class="las la-heartbeat"></i>
                                    <span class="title">Devices</span>
                                </a>
                            </li>
                            @endif

                            @if($role === 'admin' || $role === 'clinic' || $role === 'doctor')
                            <li class="nav-item <?php if ($page == 'reminders') {
                                                    echo 'active';
                                                } ?>">
                                <a href="{{ route('reminders') }}" class="nav-link nav-toggle">
                                    <i class="las la-calendar"></i>
                                    <span class="title">Reminders</span>
                                </a>
                            </li>
                            @endif

                            @if($role === 'admin')
                            {{-- <!-- <li class="nav-item <?php if ($page == 'ready_bills') {
                                                                echo 'active';
                                                            } ?>">
                                <a href="{{ route('readytobill') }}" class="nav-link nav-toggle">
                            <i class="las la-file-invoice-dollar"></i>
                            <span class="title">Ready To Bill</span>
                            </a>
                            </li> --> --}}
                            <li class="nav-item  <?php if ($page == 'admin-billing') {
                                                        echo 'active';
                                                    } ?>">
                                <a href="{{ route('admin-billing') }}" class="nav-link nav-toggle">
                                    <i class="las la-file-invoice-dollar"></i>
                                    <span class="title">Billing Admin</span>
                                </a>
                            </li>
                            <li class="nav-item  <?php if ($page == 'wallet') {
                                                        echo 'active';
                                                    } ?>">
                                <a href="{{ route('wallet') }}" class="nav-link nav-toggle">
                                    <i class="las la-wallet"></i>
                                    <span class="title">Wallet</span>
                                </a>
                            </li>
                            <li class="nav-item" <?php if ($page == 'config') {
                                                        echo 'active';
                                                    } ?>>
                                <a href="{{ route('config') }}" class="nav-link nav-toggle">
                                    <i class="las la-cog"></i>
                                    <span class="title">Config</span>
                                </a>
                            </li>
                            @endif


                            @if($role === 'clinic')
                            <li class="nav-item  <?php if ($page == 'billing') {
                                                        echo 'active';
                                                    } ?>">
                                <a href="{{ route('billing') }}" class="nav-link nav-toggle">
                                    <i class="las la-file-invoice-dollar"></i>
                                    <span class="title">Billing</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <script>
                //Notification Work
                var currentSize = 0;
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: '{{ route("initNotif") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token
                    },
                    success: function(result) {
                        // console.log(result);
                        if (result.notifications.length > currentSize) {
                            // console.log("here");
                            if (result.notifications.length > 0) {
                                // console.log("Here1");
                                concat = ``;
                                result.notifications.map(function(val, index) {
                                    concat += `<li>
                                                    <a href="javascript:void(0);" onclick="openNotif('${val.id}','${val.action}')" ${ val.status = 'unread' ? 'class="unread"' : '' }>
                                                        <span class="details">
                                                            <span class="notification-icon circle deepPink-bgcolor">
                                                                <i class="las la-gavel"></i>
                                                            </span>
                                                            <span class="noti-det">
                                                                <!-- <b>ABC Clinic</b> -->
                                                                <span class="noti-pra">
                                                                    ${val.notification}
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                               </li>`;
                                });
                                currentSize = result.notifications.length;
                                $('#notif-container').css('height', '269px');
                                $('#notif-container').html(concat);
                            }

                            //message count display
                            if (result.new - message - count > 0) {
                                $('.badge').text(result.new_message_count);
                                $('#new-count').text(result.new_message_count);
                            } else {
                                $('.badge').text('');
                                $('#new-count').text('0');
                            }

                            // console.log(concat);
                            // console.log(currentSize);
                        } else if (result.notifications.length == 0) {
                            // console.log("here1");
                            concat = ``;
                            concat += `<div style="text-align: center;margin-top:20px;">No Notification!</div>`;
                            $('.badge').text(''); //unread message count
                            $('#new-count').text('0');
                            currentSize = result.notifications.length;
                            $('#notif-container').css('height', '60px');
                            $('#notif-container').html(concat);
                        }
                        // console.log(result.notifications.length);
                    },
                    error: function(error) {
                        console.log(error.responseJSON);
                    }
                });

                //Repeat Check Notification
                setInterval(function() {
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: '{{ route("initNotif") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token
                        },
                        success: function(result) {
                            // console.log('from interval',result);
                            if (result.notifications.length > currentSize) {
                                // if (result.notifications.length > 0) {
                                concat = ``;
                                result.notifications.map(function(val, index) {
                                    concat += `<li>
                                                    <a href="javascript:void(0);" onclick="openNotif('${val.id}','${val.action}')" ${ val.status = 'unread' ? 'class="unread"' : '' }>
                                                        <span class="details">
                                                            <span class="notification-icon circle deepPink-bgcolor">
                                                                <i class="las la-gavel"></i>
                                                            </span>
                                                            <span class="noti-det">
                                                                <!-- <b>ABC Clinic</b> -->
                                                                <span class="noti-pra">
                                                                    ${val.notification}
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>`;
                                });
                                currentSize = result.notifications.length;
                                $('#notif-container').css('height', '269px');
                                $('#notif-container').html(concat);
                                // }
                                //message count display
                                if (result.new - message - count > 0) {
                                    $('.badge').text(result.new_message_count);
                                    $('#new-count').text(result.new_message_count);
                                } else {
                                    $('.badge').text('');
                                    $('#new-count').text('0');
                                }

                            } else if (result.notifications.length == 0) {
                                concat = ``;
                                concat += `<div style="text-align: center;margin-top:20px;">No Notification!</div>`;
                                $('.badge').text(''); //unread message count
                                $('#new-count').text('0');
                                currentSize = result.notifications.length;
                                $('#notif-container').css('height', '60px');
                                $('#notif-container').html(concat);
                            }
                        },
                        error: function(error) {
                            console.log(error.responseJSON);
                        }
                    });
                }, 3000);

                function openNotif(notifID, action) {
                    $.ajax({
                        url: '{{ route("openNotif") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token,
                            notifID,
                            action
                        },
                        success: function(result) {
                            //if role Admin or sale reps 
                            if (action.length > 0) {
                                window.location.href = action;
                            }

                        },
                        fail: function(error) {
                            console.log(error.responseJSON);
                        }
                    })
                }
            </script>