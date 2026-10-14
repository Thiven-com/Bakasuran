@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <form action="{{ route('admin.shippingZones.update', $zone->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">

                        <div class="row">

                            <!-- Zone Name -->
                            <div class="col-md-6">
                                <label>Zone Name</label>
                                <input type="text" name="zone_name" class="form-control" value="{{ $zone->zone_name }}"
                                    required>
                            </div>

                            <!-- Shipping Method -->
                            <div class="col-md-6">
                                <label>Shipping Method</label>
                                <input type="text" name="shipping_method" class="form-control"
                                    value="{{ $zone->shipping_method }}" required>
                            </div>

                            <!-- Regions -->
                            @php
                                $selectedRegions = json_decode($zone->regions, true) ?? [];
                            @endphp

                            <div class="col-md-6 mt-3">
                                <label>States</label>
                                <select class="form-select select2" name="regions[]" multiple required>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->name }}" {{ in_array($state->name, $selectedRegions) ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Free Shipping -->
                            <div class="col-md-6 mt-3">
                                <label>Free Shipping</label>
                                <select class="form-select" name="free_shipping" required>
                                    <option value="yes" {{ $zone->free_shipping == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $zone->free_shipping == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Freight Charges -->
                <div class="card mt-3">
                    <div class="card-body">

                        <h5>Freight Charges</h5>

                        <table class="table" id="freightTable">
                            <thead>
                                <tr>
                                    <th>Min Weight (in Kgs)</th>
                                    <th>Max Weight (in Kgs)</th>
                                    <th>Charge</th>
                                    <th>
                                        <button type="button" class="btn btn-success btn-sm" id="addRow">+</button>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($zone->charges as $charge)
                                    <tr>
                                        <input type="hidden" name="charge_id[]" value="{{ $charge->id }}">
                                        <td><input type="number" name="min_weight[]" step="0.001" class="form-control"
                                                value="{{ $charge->min_weight }}" required></td>
                                        <td><input type="number" name="max_weight[]" step="0.001" class="form-control"
                                                value="{{ $charge->max_weight }}" required></td>
                                        <td><input type="number" name="charge[]" class="form-control"
                                                value="{{ $charge->charge }}" required></td>
                                        <td><button type="button" class="btn btn-danger removeRow">X</button></td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-primary">Update Zone</button>
                    <a href="{{ route('admin.shippingZones.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </form>

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

            $('.select2').select2();

            $('#addRow').click(function () {
                $('#freightTable tbody').append(`
                            <tr>
                                <input type="hidden" name="charge_id[]" value="">
                                <td><input type="number" placeholder="Min Weight" name="min_weight[]" step="0.001" class="form-control" required></td>
                                <td><input type="number" placeholder="Max Weight" name="max_weight[]" step="0.001" class="form-control" required></td>
                                <td><input type="number" placeholder="Charge" name="charge[]" step="0.01" class="form-control" required></td>
                                <td><button type="button" class="btn btn-danger removeRow">X</button></td>
                            </tr>
                        `);
            });

            $(document).on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
            });

        });
    </script>
@endsection