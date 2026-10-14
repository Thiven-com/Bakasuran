<?php $page = 'activity-logs'; ?>
@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>Activity Logs</h4>
                <h6>Staff activity tracking</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Module</th>
                                <th>Action</th>
                                <th>Record ID</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $log->user->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst($log->module) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($log->action == 'create')
                                            <span class="badge bg-success">Create</span>
                                        @elseif($log->action == 'update')
                                            <span class="badge bg-warning">Update</span>
                                        @else
                                            <span class="badge bg-danger">Delete</span>
                                        @endif
                                    </td>

                                    <td>#{{ $log->record_id }}</td>

                                    <td>
                                        {{ \Illuminate\Support\Str::limit($log->description, 40) }}
                                    </td>

                                    <td>
                                        {{ $log->created_at->format('d M Y H:i') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.activity.logs.show', $log->id) }}"
                                           class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        <div class="mt-3">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection