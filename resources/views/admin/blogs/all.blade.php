<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Blogs</h4>
                        <h6>Manage your blogs</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <div class="d-flex me-2 pe-2 border-end">
                            {{-- <a href="{{ route('admin.staff.index') }}" class="btn-list active bg-primary me-2"><i data-feather="list" class="feather-user text-white"></i></a> --}}
                            {{-- <a href="{{ url('employees-grid') }}" class="btn-grid me-2"><i data-feather="grid" class="feather-user"></i></a> --}}
                        </div>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="{{ route('admin.blogs.create')}}" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>Add Blog</a>
                </div>
                {{-- <div class="page-btn">
                    <a href="{{ route('admin.staff.import.form') }}" class="btn btn-primary"><i class="ti ti-arrow-up me-1"></i>Import</a>
                </div> --}}

                {{-- <div class="page-btn">
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-danger"><i class="ti ti-circle-plus me-1"></i>Add Employee</a>
                </div> --}}
            </div>

            <!-- product list -->
            <div class="card">

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.no</th>
                                    <th>Title</th>
                                    {{-- <th>Short Description</th> --}}
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Banner</th>
                                    <th>Tags</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach($blogs as $data)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{$data->title}}</td>
                                    {{-- <td>{{ $data->short_description ?? '-' }}</td> --}}
                                    <td>{{ $data->category->name ?? '' }}</td>
                                     <td>
                                        <div class="d-flex align-items-center">
                                                @if(!empty($data->image))
                                                    <img src="{{ asset($data->image) }}" style="width:80px; height:80px; object-fit:cover; border-radius:6px;" alt="img">
                                                @else
                                                    <img src="{{ URL::asset('build/img/users/user-32.jpg') }}" class="img-fluid" alt="img">
                                                @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                                @if(!empty($data->banner))
                                                    <img src="{{ asset($data->banner) }}" style="width:80px; height:80px; object-fit:cover; border-radius:6px;" alt="img">
                                                @else
                                                    <img src="{{ URL::asset('build/img/users/user-32.jpg') }}" class="img-fluid" alt="img">
                                                @endif
                                        </div>
                                    </td>
                                    <td>{{ $data->tags }}</td>
                                    <td>{{ $data->status }}</td>
                                    <td>
                                        <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-blog"
                                                    href="{{ route('admin.blogs.edit', $data->id) }}"
                                                >
                                                <i data-feather="edit" class="feather-edit" style="color:blue;"></i>
                                                </a>

                                                <a class="confirm-delete-blog p-2" href="javascript:void(0);" data-id="{{ $data->id }}" data-url="{{ route('admin.blogs.destroy', $data->id) }}">
                                                    <i data-feather="trash-2" class="feather-trash-2" style="color:red;"></i>
                                                </a>
                                            </div>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- pagination (if $staffs is LengthAwarePaginator) --}}
                    {{-- @if(method_exists($data, 'links')) --}}
                        {{-- <div class="p-3">
                            {{ $staffs->links() }}
                        </div> --}}
                    {{-- @endif --}}
                </div>
            </div>
            <!-- /product list -->

        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            {{-- <p class="mb-0">2025 &copy; {{$->site_name }}. All Right Reserved</p> --}}
            {{-- <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">{{$site->site_name ?? ' '  }}</a></p> --}}
        </div>
    </div>


@endsection

@push('scripts')
<script>
     $(document).on('click', '.confirm-delete-blog', function() {

        var giftCardId = $(this).data('id');
        var deleteUrl = $(this).data('url');

        console.log("deleteUrl", deleteUrl);

        var confirmation = confirm('Are you sure want to delete this Blog?');

        if(confirmation) {
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // console.error('Error', error);
                    alert(xhr.responseJSON.message);
                }
            });
        } else {
            console.log('Deletion cancelled');
        }
    });




</script>
@endpush
