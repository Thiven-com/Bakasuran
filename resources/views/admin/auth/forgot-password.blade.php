<?php $page = 'signin-2'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="account-content">
        <div class="row login-wrapper m-0">
            <div class="col-lg-6 p-0">

                <div class="login-content">
                    <form method="POST" action="{{ route('admin.password.sendOtp') }}">
                        @csrf
                        <div class="login-userset">
                            <div class="final-logo logo-normal">
                                <a href="{{ url('admin/dashboard') }}" class="logo logo-normal d-flex align-items-center">
                                    <img src="{{ asset('website') }}/images/thirdeye.png" alt="Logo"
                                      style="width:400px; margin-left: 30px; ">
                                </a>
                            </div>
                            <div class="login-userheading">
                                <h3>Forgot Password</h3>
                            </div>
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-end-0" name="email">
                                    <span class="input-group-text border-start-0">
                                        <i class="ti ti-mail"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-login">
                                <button type="submit" class="btn btn-login">Send OTP</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="login-img">
                    <img src="{{ asset('website') }}/images/thirdlog.png" alt="img">
                </div>
            </div>
        </div>
    </div>
@endsection