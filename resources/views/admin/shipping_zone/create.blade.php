<?php $page = 'add-Zone'; ?>
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Shipping Zone</h4>
                        <h6>Manage your Shipping Zone</h6>
                    </div>
                </div>
            </div>
            <!-- /add -->
            <form action="{{route('admin.shippingZones.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span> Zone Information</span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 ">
                                                    <label class="form-label">Zone Name<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="zone_name" name="zone_name"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 ">
                                                    <label class="form-label">Shipping Method<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="shipping_method"
                                                        name="shipping_method" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 ">
                                                    <label class="form-label">States<span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-select select2" name="regions[]" multiple required>
                                                        <option value="">Select States</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->name }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 ">
                                                    <label class="form-label">Free Shipping<span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-select" name="free_shipping" required>
                                                        <option value="">Select</option>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <a href="{{ route('admin.shippingZones.index') }}" class="btn btn-cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-submit">Save Zone</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Your HTML form here -->

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select States",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection