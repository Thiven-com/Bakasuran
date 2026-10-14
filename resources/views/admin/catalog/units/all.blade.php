<?php $page = 'units'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Units</h4>
                        <h6>Manage your units</h6>
                    </div>
                </div>
                <ul class="table-top-head">

                </ul>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-units"><i
                            class="ti ti-circle-plus me-1"></i>Add Unit</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="no-sort">
                                        S.No
                                    </th>
                                    <th>Unit</th>
                                    <th>Slug</th>
                                    <th>No of Products</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th class="no-sort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td>
                                          {{$loop->iteration}}
                                        </td>
                                        <td class="text-gray-9">{{ $unit->name }}</td>
                                        <td>{{ $unit->slug }}</td>
                                        <td>{{ $unit->productCount() }}</td>
                                        <td>{{ \Carbon\Carbon::parse($unit->created_at)->format('d-m-Y') }}</td>
                                        <td><span
                                                class="badge table-badge bg-success fw-medium fs-10">{{ $unit->status }}</span>
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-unit" href="{{ route('admin.units.edit', $unit->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#edit-unit"
                                                    data-unit-id="{{ $unit->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a data-bs-toggle="modal" data-bs-target="#delete-modal"
                                                    class="p-2 open-delete-unit-modal" href="javascript:void(0);"
                                                    data-id="{{ $unit->id }}"
                                                    data-url="{{ route('admin.units.destroy', $unit->id) }}">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2026 &copy; {{ $site->site_name ?? ' '  }}. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">ThiVen</a></p>
        </div>
    </div>

    <!-- Add Unit -->
    <div class="modal fade" id="add-units">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header">
                            <div class="page-title">
                                <h4>Add Unit</h4>
                            </div>
                            <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin.units.store')}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                {{-- <div class="mb-3">
                                    <label class="form-label">Short Name<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control">
                                </div> --}}
                                <div class="mb-0">
                                    <div
                                        class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                        <span class="status-label">Status</span>
                                        <input type="checkbox" id="user2" class="check" checked="" name="status">
                                        <label for="user2" class="checktoggle"></label>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3">Add Unit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Unit -->
    <div class="modal fade" id="edit-unit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>Edit Unit</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit-unit-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteUnitModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Unit</h5>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">Are you sure you want to delete this unit?</p>
                    <input type="hidden" id="deleteUnitId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteUnitBtn" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.edit-unit', function (event) {
            event.preventDefault();

            var unitId = $(this).data('unit-id');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_name').val(response.unit.name);
                    $('#edit-unit-form').attr('action', response.update_url);
                    $('#edit-unit').modal('show');
                },
                error: function () {
                    alert('Failed to fetch unit details.');
                }
            });
        });

        $(document).on("click", ".open-delete-unit-modal", function () {
            let unitId = $(this).data("id");
            $("#deleteUnitId").val(unitId);
            $("#deleteUnitModal").modal("show");
        });

        $("#confirmDeleteUnitBtn").on("click", function () {
            let unitId = $("#deleteUnitId").val();
            let row = $("button[data-id='" + unitId + "']").closest("tr");

            $.ajax({
                url: "/admin/units/" + unitId,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $("#deleteUnitModal").modal("hide");
                    row.fadeOut(500, function () {
                        $(this).remove();
                    });
                    location.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message ?? "Something went wrong!");
                }
            });
        });
    </script>
@endsection