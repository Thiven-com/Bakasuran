<?php $page = 'varriant-attributes'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Variant Attributes</h4>
                    <h6>Manage your variant attributes</h6>
                </div>
            </div>
            <ul class="table-top-head">
               
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-attribute"><i class="ti ti-circle-plus me-1"></i>Add Attribute</a>
            </div>
        </div>
        <!-- /product list -->
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
                                <th>Attribute</th>
                                <th>Unit</th>
                                 {{-- <th>Products Count</th> --}}
                                {{-- <th class="no-sort"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($attributes as $attribute)

                            <tr>
                                <td>
                                   {{ $loop->iteration }}
                                </td>
                                <td class="text-gray-9">{{ $attribute->name }}</td>
                                <td>{{ $attribute->attribute->name ?? '' }}</td>
                                {{-- <td>{{ $attribute->productCount() }}</td> --}}
                                {{-- <td class="action-table-data">
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-variant">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2" href="javascript:void(0);">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                    </div>

                                </td> --}}
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
		<div class="modal fade" id="add-attribute">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="page-title">
							<h4>Add Variant</h4>
						</div>
						<button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
                    <form action="{{ route('admin.attributes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

						<div class="modal-body">
                            <div class="mb-3">
                                        <div class="input-blocks">
                                            <label>Unit</label>
                                            @php
                                                $units = \App\Models\Attribute::get();
                                            @endphp
                                            <select class="select" name="unit_id">
                                                @if(isset($units))
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}">
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
							<div class="mb-3">
								<label class="form-label">Attribute<span class="text-danger ms-1">*</span></label>
								<input type="text" class="form-control" name="name">
							</div>
							{{-- <div class="mb-3">
								<label class="form-label">Values<span class="text-danger ms-1">*</span></label>
								<input class="fs-14 bg-secondary-transparent" type="text" data-role="tagsinput"  name="specialist" value="">
								<span class="tag-text mt-2 d-flex">Enter value separated by comma</span>
							</div> --}}
							<div class="mb-0 mt-4">
								<div class="status-toggle modal-status d-flex justify-content-between align-items-center">
									<span class="status-label">Status</span>
									<input type="checkbox" id="user2" class="check" checked="" name="status">
									<label for="user2" class="checktoggle"></label>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Add Attribute</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Add Unit -->
@endsection
