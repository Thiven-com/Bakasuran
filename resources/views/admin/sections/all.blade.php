<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Sections</h4>
                        <h6>Manage your Sections</h6>
                    </div>
                </div>
            </div>

            <!-- product list -->
            <div class="card">

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.no</th>
                                    {{-- <th>Page</th> --}}
                                    <th>Section Name</th>
                                    <th>Title</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        {{-- <td>{{ucwords($item->page_name)}}</td> --}}
                                        <td>{{ $item->section_name ?? '' }}</td>
                                        <td>
                                            {{ $item->title }}
                                        </td>
                                        {{-- <td>{{ $item->status }}</td> --}}
                                        <td>
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-blog"
                                                    href="{{ route('admin.sections.edit', $item->id) }}">
                                                    <i data-feather="edit" class="feather-edit" style="color:blue;"></i>
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
        </div>

    </div>


@endsection