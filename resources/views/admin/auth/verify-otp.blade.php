<?php $page = 'signin-2'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="account-content">
        <div class="row login-wrapper m-0">
            <div class="col-lg-6 p-0">

                <div class="login-content">
                    <form method="POST" action="{{ route('admin.password.verifyOtp') }}">
                        @csrf
                        <div class="login-userset">
                            <div class="final-logo logo-normal">
                                <a href="{{ url('admin/dashboard') }}" class="logo logo-normal d-flex align-items-center">
                                    <img src="{{ asset('website') }}/images/thirdeye.png" alt="Logo"
                                      style="width:400px; margin-left: 30px; ">
                                    
                                </a>
                            </div>

                            <div class="login-userheading">
                                <h3>Verify OTP</h3>
                                <h4>Enter the OTP sent to your email</h4>
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

                            <input type="hidden" name="email" value="{{ session('email') }}">

                            <div class="mb-3">
                                <label class="form-label">OTP Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-end-0 @error('otp') is-invalid @enderror"
                                        name="otp" placeholder="Enter 6-digit OTP">
                                    <span class="input-group-text border-start-0">
                                        <i class="ti ti-key"></i>
                                    </span>
                                </div>
                                @error('otp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-login">
                                <button type="submit" class="btn btn-login">Verify OTP</button>
                            </div>

                            <div class="signinform text-center">
                                <h4>
                                    Didn't receive OTP?
                                    <a href="{{ route('admin.password.request') }}" class="hover-a">
                                        Try Again
                                    </a>
                                </h4>
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