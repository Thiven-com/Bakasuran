<?php $page = 'add-product'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
            @slot('title')
            New Coupon
            @endslot
            @slot('li_1')
            Create new Coupon
            @endslot
            @endcomponent
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Add Coupon</h5>

                        <div class="row">
                            {{-- Coupon Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Coupon Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required
                                    placeholder="New Year Offer">
                            </div>

                            {{-- Coupon Code --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Coupon Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" class="form-control text-uppercase"
                                    placeholder="NY2026" required>
                            </div>

                            {{-- Discount Type --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed (₹)</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage
                                        (%)</option>
                                </select>
                            </div>

                            {{-- Discount --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Value</label>
                                <input type="number" step="0.01" value="{{ old('discount') }}" name="discount" required
                                    class="form-control" placeholder="100 or 10%">
                            </div>

                            {{-- Minimum Purchase --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Minimum Purchase Amount</label>
                                <input type="number" step="0.01" value="{{ old('minimum_purchase') }}"
                                    name="minimum_purchase" class="form-control" placeholder="500">
                            </div>

                            {{-- Usage Limit --}}
                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Usage Limit</label>
                                <input type="number" name="limit" class="form-control" value="{{ old('limit') }}"
                                    placeholder="10">
                            </div> --}}

                            {{-- Expiry Date --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"
                                    placeholder="Coupon description...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Save Coupon
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- /add -->

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection