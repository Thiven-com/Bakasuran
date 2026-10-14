{{-- resources/views/admin/orders/upload-awb.blade.php --}}

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="page-header">
                <div>
                    <h4>Upload AWB CSV</h4>
                    <h6 class="text-muted">Import Delhivery Waybill File</h6>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.importAwbCsv') }}" method="POST" enctype="multipart/form-data"
                        class="row g-3 align-items-end">

                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">Upload AWB CSV File</label>
                            <input type="file" name="file" class="form-control" accept=".csv" required>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                Upload CSV
                            </button>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-light w-100">
                                Back to Orders
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection