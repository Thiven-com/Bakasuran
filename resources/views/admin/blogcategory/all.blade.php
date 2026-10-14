<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Blog Category</h4>
                        <h6>Manage your blog Categories</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                   
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-gift-type"><i
                            class="ti ti-circle-plus me-1"></i>Add Blog Category</a>
                </div>
            </div>
            <!-- /product list -->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.no</th>
                                    <th>Parent</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($blogCategories as $data)

                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            <span class="text-gray-900">{{ $data->parent->name ?? '_' }}</span>
                                        </td>

                                        <td>{{ $data->name ?? '_' }}</td>
                                        <td>{{  \Illuminate\Support\Str::words($data->description, 6, '...') ?? '-'}}</td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if(!empty($data->image))
                                                    <img src="{{ asset($data->image) }}" style="width: 60px;" class="img-fluid" alt="img">
                                                @else
                                                    <img src="{{ URL::asset('build/img/users/user-32.jpg') }}" style="width: 60px;" class="img-fluid"
                                                        alt="img">
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $data->status }}</td>
                                       <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-blog-category"
                                                    href="{{ route('admin.blog.categories.edit', $data->id) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#edit-blog-category"
                                                    data-blogcategory-id="{{ $data->id }}">
                                                    <i data-feather="edit" class="feather-edit" style="color:blue;"></i>
                                                </a>

                                                <a class="confirm-delete-blogcategory p-2" href="javascript:void(0);" data-id="{{ $data->id }}" data-url="{{ route('admin.blog.categories.destroy', $data->id) }}">
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
                </div>
            </div>
            <!-- /product list -->

        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0">2026 &copy; {{$site->site_name ?? ' '  }}. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">{{$site->site_name ?? ' '  }}</a></p>
        </div>
    </div>


<!-- Add Blog Category -->
<div class="modal fade" id="add-gift-type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header">
                        <div class="page-title">
                            <h4>Add Blog Category</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('admin.blog.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                   <div class="mb-3">
                                            @php
                                                $blogCategory = \App\Models\BlogCategory::where('parent_id', 0)->get();
                                            @endphp
                                           <label class="form-label">Parent Blog Category</label>
                                            <select class="select" name="parent_id">
                                                <option value="" selected> This is Parent Blog Category</option>
                                                @foreach ($blogCategory as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="name" required id="name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4"></textarea>
                                </div>

                                <label class="form-label">Image</label>
                                <div class="profile-pic-upload mb-3">
                                    <div class="profile-pic brand-pic" id="imagePreview">
                                        <span id="uploadText">
                                            <i data-feather="plus-circle"></i> Add Image
                                        </span>
                                    </div>
                                    <button type="button" class="remove-btn" id="removeImage"
                                        style="display:none;">&times;</button>
                                    <input type="file" name="image" id="imageInput" accept="image/*"
                                        style="display:none;">
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label">Status</label>
                                    <select class="select" name="status">
                                        <option value="show">Show</option>
                                        <option value="hide">Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Blog Category -->

<!-- Edit Add Blog Category -->
  <div class="modal fade" id="edit-blog-category" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header">
                            <div class="page-title">
                                <h4>Edit Blog Category</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="editBlogCategory" method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Parent Blog Category <span class="text-danger"> *</span></label>
                                            <select id="edit_parent_id" name="edit_parent_id" class="form-control select" required>
                                                <option value="" selected disabled>Select an option</option>
                                                @foreach($blogCategories as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Name <span class="text-danger"> *</span></label>
                                            <input type="text" name="name" id="edit_blogCat_name" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Description <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="description" id="edit_blogCat_description">
                                        </div>
                                    </div>

                                    {{-- Image --}}
                                    <label class="form-label"> Image</label>
                                    <div class="profile-pic-upload mb-3">
                                        <div class="profile-pic-upload">
                                            <div class="profile-pic brand-pic" id="editImagePreview">
                                                <img id="editBlogCategoryImage" src="" alt="image"
                                                    style="width: 100px; height: 100px; object-fit: cover; display: none;">
                                                <span id="editUploadText"><i data-feather="plus-circle"></i> Add Image</span>
                                            </div>
                                            <button type="button" class="remove-btn" id="removeEditImage">&times;</button>
                                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                                            <input style="display: none;" type="file" name="image" class="image-upload-file"
                                                id="editImageInput" accept="image/*">
                                        </div>
                                    </div>

                                    <label class="form-label">Status</label>
                                    <select id="status" name="status" class="select">
                                        <option value="show">Show</option>
                                        <option value="hide">Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /Edit Add Blog Category -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>


 $(document).on('click', '.edit-blog-category', function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            console.log("url", url);

        $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log("response", response);
                    $('#edit_parent_id').val(response.blog_category.parent_id).trigger('change');
                    $('#edit_blogCat_name').val(response.blog_category.name);
                    $('#edit_blogCat_description').val(response.blog_category.description);
                    if(response.blog_category.image) {
                        var imageUrl = "{{  url('/') }}/" +response.blog_category.image;
                        $('#editBlogCategoryImage').attr('src', imageUrl).show();
                        $('#editUploadText').hide();
                    } else {
                        $('#editBlogCategoryImage').hide();
                        $('#editUploadText').show();
                    }

                    $('#editBlogCategory').attr('action', response.update_url);
                    console.log('update-url', response.update_url);
                    $('#edit-blog-category').modal('show');
                },
                error: function () {
                    alert('Failed to fetch Biller Category details.');
                }
        });
    });

    // Remove image
    $(document).ready(function () {
        $('#editImageInput').on('change', function (event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#editBlogCategoryImage').attr('src', e.target.result).show();
                    $('#editUploadText').hide();
                };
                reader.readAsDataURL(file);
                $('#remove_image').val('0');
            }
        });

        $('#removeEditImage').on('click', function () {
            $('#editBlogCategoryImage').hide();
            $('#editUploadText').show();
            $('#editImageInput').val('');
            $('#remove_image').val('1');
        });

        $('#editUploadText').on('click', function () {
            $('#editImageInput').click();
        });
    });
     $(document).on('click', '.confirm-delete-blogcategory', function() {

        var giftCardId = $(this).data('id');
        var deleteUrl = $(this).data('url');

        console.log("deleteUrl", deleteUrl);

        var confirmation = confirm('Are you sure want to delete this Blog Category?');

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

@endsection
