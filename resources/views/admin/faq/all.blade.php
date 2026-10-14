<?php $page = 'faqs-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Faq's</h4>
                        <h6>Manage your Faq's</h6>
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
                            class="ti ti-circle-plus me-1"></i>Add Faq</a>
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
                                    <th>Question</th>
                                    <th>Actions</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($data as $item)

                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{ $item->question ?? '_' }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-faq" href="{{ route('admin.faqs.edit', $item->id) }}"
                                                    data-id="{{ $item->id }}">
                                                    <i data-feather="edit" class="feather-edit" style="color:blue;"></i>
                                                </a>
                                                <a class="confirm-delete-blogcategory p-2" href="javascript:void(0);"
                                                    data-id="{{ $item->id }}"
                                                    data-url="{{ route('admin.faqs.destroy', $item->id) }}">
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
                    <div>
                        {{ $data->links('pagination::bootstrap-5') }}
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
                                <h4>Add FAQ</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form action="{{ route('admin.faqs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Question<span class="text-danger"> *</span></label>
                                            <input type="text" name="question" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <label class="form-label">Answer</label>
                                        <textarea class="form-control" name="answer" rows="4" required></textarea>
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
    <div class="modal fade" id="edit-faq" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header">
                            <div class="page-title">
                                <h4>Edit Faq</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="editFaq" method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Question <span class="text-danger"> *</span></label>
                                            <input type="text" name="question" id="edit_question" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Answer <span class="text-danger">
                                                    *</span></label>
                                            <textarea class="form-control" name="answer" id="edit_answer" required rows="4"></textarea>
                                        </div>
                                    </div>
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
        $(document).on('click', '.edit-faq', function (event) {
            console.log("Check");

            event.preventDefault();
            var url = $(this).attr('href');
            console.log("url", url);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log("response", response);
                    $('#edit_question').val(response.faq.question);
                    $('#edit_answer').val(response.faq.answer);
                    $('#editFaq').attr('action', response.update_url);
                    console.log('update-url', response.update_url);
                    $('#edit-faq').modal('show');
                },
                error: function () {
                    alert('Failed to fetch Faq details.');
                }
            });
        });


        $(document).on('click', '.confirm-delete-blogcategory', function () {

            var giftCardId = $(this).data('id');
            var deleteUrl = $(this).data('url');

            console.log("deleteUrl", deleteUrl);

            var confirmation = confirm('Are you sure want to delete this Blog Category?');

            if (confirmation) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        // alert(response.message);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
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