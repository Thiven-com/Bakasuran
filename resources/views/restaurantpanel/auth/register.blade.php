<?php $page = 'signin-2'; ?>

@extends('layout.restaurantpanelmainlayout')

@section('content')

<div class="account-content">
    <div class="row login-wrapper m-0">

        {{-- Left Side: Registration Form --}}
        <div class="col-lg-6 p-0">
            <div class="login-content">

                <form action="{{ route('restaurant.register.submit') }}" method="POST">
                    @csrf

                    <div class="login-userset">

                        <div class="final-logo logo-normal">
                            <a href="{{ route('restaurant.login') }}"
                               class="logo logo-normal d-flex align-items-center">
                                <img src="{{ asset($site->logo ?? '') }}"
                                     alt="Logo"
                                     style="width:400px; margin-left:30px;">
                            </a>
                        </div>

                        <div class="login-userheading">
                            <h3>Create Restaurant Account</h3>
                            <h3>{{ $settings->site_name ?? '' }}</h3>
                            <p>Register your restaurant to get started.</p>
                        </div>

                        {{-- Restaurant Name --}}
                        <div class="mb-3">
                            <label class="form-label">Restaurant Name</label>
                            <div class="input-group">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name') }}"
                                       placeholder="Enter restaurant name"
                                       required>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Owner Name --}}
                        <div class="mb-3">
                            <label class="form-label">Owner Name</label>
                            <div class="input-group">
                                <input type="text"
                                       name="owner_name"
                                       class="form-control"
                                       value="{{ old('owner_name') }}"
                                       placeholder="Enter owner name"
                                       required>
                            </div>
                            @error('owner_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email') }}"
                                       placeholder="Enter email address"
                                       required>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Mobile Number --}}
                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="{{ old('phone') }}"
                                       placeholder="Enter mobile number"
                                       required>
                            </div>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password"
                                       name="password"
                                       class="pass-input form-control"
                                       placeholder="Enter password"
                                       required>
                                <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <div class="pass-group">
                                <input type="password"
                                       name="password_confirmation"
                                       class="pass-input form-control"
                                       placeholder="Confirm password"
                                       required>
                                <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                            </div>
                        </div>

                        {{-- Register Button --}}
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">
                                Register
                            </button>
                        </div>

                        {{-- Login Link --}}
                        <div class="signinform text-center mt-3">
                            <h4>
                                Already have an account?
                                <a href="{{ route('restaurant.login') }}"
                                   class="hover-a">
                                    Sign In
                                </a>
                            </h4>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        {{-- Right Side Image --}}
        <div class="col-lg-6 p-0">
            <div class="login-img">
                <img src="{{ asset('build/img/pro.png') }}" alt="Restaurant Registration">
            </div>
        </div>

    </div>
</div>

@endsection
