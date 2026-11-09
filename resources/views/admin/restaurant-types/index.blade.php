
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4>Restaurant Types</h4>
                <h6>Manage your restaurant categories</h6>
            </div>

            <button type="button" class="btn btn-primary"
                data-bs-toggle="modal" data-bs-target="#addTypeModal">
                <i class="ti ti-plus"></i> Add Restaurant Type
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Restaurant Types</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Restaurant Type</th>
                                <th>Description</th>
                                {{-- <th>Restaurants</th> --}}
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($types as $index => $type)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <strong>{{ $type->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $type->description ?: '—' }}
                                    </td>

                                    {{-- <td>
                                        <span class="badge bg-info">
                                            {{ $type->restaurants_count }}
                                        </span>
                                    </td> --}}

                                    <td>
                                        @if($type->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-warning edit-type-btn"
                                            data-id="{{ $type->id }}"
                                            data-name="{{ $type->name }}"
                                            data-description="{{ $type->description }}"
                                            data-status="{{ $type->status }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTypeModal">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>

                                        <form
                                            action="{{ route('admin.restaurant-types.destroy', $type->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this restaurant type?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        No restaurant types found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- ADD TYPE MODAL --}}
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('admin.restaurant-types.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Restaurant Type</h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Restaurant Type Name *</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="e.g. Fine Dining" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                            rows="3" placeholder="Enter description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-primary">
                        Save Restaurant Type
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


{{-- EDIT TYPE MODAL --}}
<div class="modal fade" id="editTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="editTypeForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Restaurant Type</h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Restaurant Type Name *</label>
                        <input type="text" name="name" id="edit_type_name"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_type_description"
                            class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" id="edit_type_status"
                            class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-primary">
                        Update Restaurant Type
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.edit-type-btn').forEach(function(button) {

        button.addEventListener('click', function () {

            const id = this.dataset.id;

            document.getElementById('edit_type_name').value =
                this.dataset.name;

            document.getElementById('edit_type_description').value =
                this.dataset.description || '';

            document.getElementById('edit_type_status').value =
                this.dataset.status;

            document.getElementById('editTypeForm').action =
                "{{ url('admin/restaurant-types') }}/" + id;
        });

    });

});
</script>

@endsection
