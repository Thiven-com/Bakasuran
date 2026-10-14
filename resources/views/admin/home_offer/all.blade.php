<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Home Offers</h4>
                        <h6>Manage your Home Offers</h6>
                    </div>
                </div>
                <div class="page-btn">
                    <a href="{{ route('admin.homeoffers.create')}}" class="btn btn-primary"><i
                            class="ti ti-circle-plus me-1"></i>Add
                        Home Offer</a>
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
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Offer</th>
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
                                        <td>
                                            <img src="{{ asset($item->image) }}" style="height:70px" alt="">
                                        </td>
                                        <td>
                                            {{ $item->title }}
                                        </td>
                                        <td>{{ $item->offer }}</td>
                                        <td>
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 edit-blog"
                                                    href="{{ route('admin.homeoffers.edit', $item->id) }}">
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