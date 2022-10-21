<?php $page = 'config'; //require_once 'header.php' 
?>
@include('header')
<style>
</style>
<!-- Page Content Start  -->
<div class="page-content-wrapper">
    <div class="page-content pdn">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="card card-box">
                    <div class="card-head">
                        <h4>Change Password</h4>
                    </div>
                    <form action="{{ route('password.change') }}" method="post">
                        @csrf
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
                                            <label class="control-label">Old Password </label>
                                            <input type="password" required name="old_password" class="form-control input-height" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">New Password </label>
                                            <input type="password" required name="password" class="form-control input-height" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">Re-Type Password </label>
                                            <input type="password" required name="confirm_password" class="form-control input-height" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" style="float: right;">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('config.keys') }}" method="post">
                        @csrf
                        <div class="card-header crd-hd">
                            <h4>Stripe Configuration</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label">Stripe Key </label>
                                            <input type="text" required value="{{ isset($keys['stripe_key']) ? $keys['stripe_key'] : '' }}" placeholder="pk_test_ABC...." name="stripe_key" class="form-control input-height" />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label">Stripe Secret </label>
                                            <input type="text" required value="{{ isset($keys['stripe_secret']) ? $keys['stripe_secret'] : '' }}" placeholder="sk_test_ABC....." name="stripe_secret" class="form-control input-height" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- <button type="button" class="btn btn-dark">Cancel</button> -->
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Content End -->
@include('footer')