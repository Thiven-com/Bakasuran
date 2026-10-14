<?php $page = 'signin-2'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="account-content">
        <div class="row login-wrapper m-0">
            <div class="col-lg-6 p-0">
                <div class="login-content">
                    <form action="{{ route('admin.loginAction') }}" method="POST">
                        @csrf
                        <div class="login-userset">
                            <div class="final-logo logo-normal">
                                <a href="{{ url('admin/dashboard') }}" class="logo logo-normal d-flex align-items-center">
                                    <img src="{{ asset('website') }}/images/thirdeye.png" alt="Logo"
                                      style="width:400px; margin-left: 30px; ">
                                </a>
                            </div>
                            {{-- <a href="{{url('index')}}" class="login-logo logo-white">
                                <a href="{{ url('admin/dashboard') }}" class="logo logo-normal d-flex align-items-center">
                                    <img src="{{ asset('website/images/logo.png') }}" alt="Logo"
                                        style="width:50px; margin-right:-10px;">
                                    <img src="{{ asset('website/images/vishwa.png') }}" alt="Vishwa" style="height:50px;">
                                </a> --}}
                            </a><br>
                            <div class="login-userheading">
                                <h3>Sign In</h3>
                                <h3>{{ $settings->site_name ?? '' }}</h3>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <input type="text" id="email" class="form-control border-end-0" name="email"
                                        value="{{ $email ?? '' }}" required>
                                    <span class="input-group-text border-start-0">
                                        <i class="ti ti-mail"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="pass-group">
                                    <input type="password" class="pass-input form-control" name="password"
                                        value="{{ old('password', $password ?? '') }}" maxlength="15" minlength="6"
                                        required>
                                    <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                                </div>
                            </div>
                            <div class="form-login authentication-check">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                                <input type="checkbox" name="remember" value="1" {{ !empty(old('email', $email ?? '')) ? 'checked' : '' }}>
                                                <span class="checkmarks"></span>Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a class="forgot-link" href="{{ route('admin.password.request') }}">Forgot
                                            Password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-login">
                                <button type="submit" class="btn btn-login">Sign In</button>
                            </div>
                            {{-- <div class="signinform">
                                <h4>New on our platform?<a href="{{url('register-2')}}" class="hover-a"> Create an
                                        account</a></h4>
                            </div> --}}
                            {{-- <div class="form-setlogin or-text">
                                <h4>OR</h4>
                            </div> --}}
                            {{-- <div class="form-sociallink">
                                <div class="d-flex align-items-center justify-content-center flex-wrap">
                                    <div class="text-center me-2 flex-fill">
                                        <a href="javascript:void(0);"
                                            class="br-10 p-2 btn btn-info d-flex align-items-center justify-content-center">
                                            <img class="img-fluid m-1"
                                                src="{{URL::asset('build/img/icons/facebook-logo.svg')}}" alt="Facebook">
                                        </a>
                                    </div>
                                    <div class="text-center me-2 flex-fill">
                                        <a href="javascript:void(0);"
                                            class="btn btn-white br-10 p-2  border d-flex align-items-center justify-content-center">
                                            <img class="img-fluid m-1"
                                                src="{{URL::asset('build/img/icons/google-logo.svg')}}" alt="Facebook">
                                        </a>
                                    </div>
                                    <div class="text-center flex-fill">
                                        <a href="javascript:void(0);"
                                            class="bg-dark br-10 p-2 btn btn-dark d-flex align-items-center justify-content-center">
                                            <img class="img-fluid m-1"
                                                src="{{URL::asset('build/img/icons/apple-logo.svg')}}" alt="Apple">
                                        </a>
                                    </div>
                                </div>
                                <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                                    <p>Copyright &copy; 2025 {{ $site->site_name ?? ' '  }}</p>
                                </div>
                            </div> --}}
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
    <script>
        document.getElementById('email').addEventListener('input', function () {
            const value = this.value;
            const index = value.indexOf('.com');

            if (index !== -1) {
                this.value = value.substring(0, index + 4);
            }
        });
    </script>
@endsection