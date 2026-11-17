@extends('layout.mainlayout')

@section('content')

    <style>
        .setting-card {
            border: 1px solid #eee;
            border-radius: 10px;
            background: #fff;
        }

        .setting-card .card-header {
            background: #f8f9fa;
            padding: 16px 20px;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            font-size: 13px;
        }

        .table th {
            white-space: nowrap;
            font-size: 13px;
        }

        .table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            white-space: nowrap;
        }
    </style>

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="page-title">Settings Management</h3>
                    <p class="text-muted">Manage application settings</p>
                </div>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                    <i class="fa fa-plus"></i> Add Setting
                </button>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Settings Table --}}
            <div class="card setting-card">
                <div class="card-header">
                    All Settings
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>Data Type</th>
                                    <th>Category ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($settings as $key => $setting)
                                    <tr>
                                        <td>{{ $settings->firstItem() + $key }}</td>
                                        <td>{{ $setting->name ?? '-' }}</td>
                                        <td>{{ $setting->key ?? '-' }}</td>
                                        <td>
                                            {{ \Illuminate\Support\Str::limit($setting->value, 35) }}
                                        </td>
                                        <td>{{ $setting->dataType ?? '-' }}</td>
                                        <td>{{ $setting->category_id ?? '-' }}</td>
                                        <td>
                                            @if($setting->status == 'show')
                                                <span class="badge bg-success">Show</span>
                                            @else
                                                <span class="badge bg-secondary">Hide</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-sm btn-info text-white"
                                                    data-bs-toggle="modal" data-bs-target="#editSetting{{ $setting->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <form action="{{ route('admin.setting.destroy', $setting->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editSetting{{ $setting->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">

                                                <form action="{{ route('admin.setting.update', $setting->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Setting</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        @include('admin.setting.form', [
                                                            'setting' => $setting
                                                        ])
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>

                                                        <button type="submit" class="btn btn-primary">
                                                            Update Setting
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No settings found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $settings->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Add Setting Modal --}}
    <div class="modal fade" id="addSettingModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form action="{{ route('admin.setting.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add New Setting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.setting.form', [
                            'setting' => null
                        ])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary">
                            Save Setting
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection