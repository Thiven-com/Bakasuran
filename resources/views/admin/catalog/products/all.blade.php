<?php $page = 'product-list'; ?>
@extends('layout.mainlayout')
@section('content')

    <style>
        img {
            max-width: fit-content;
        }

        .img-hover-preview {
            position: relative;
            display: inline-block;
        }

        .img-hover-preview .thumb {
            width: 100px;
            height: 50px;
            object-fit: cover;
            cursor: pointer;
        }

        .img-hover-preview .preview-box {
            display: none;
            position: absolute;
            top: 0;
            left: 60px;
            /* adjust position */
            z-index: 999;
            border: 1px solid #ddd;
            background: #fff;
            padding: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .img-hover-preview .preview-box img {
            width: 300px;
            height: 300px;
        }

        .img-hover-preview:hover .preview-box {
            display: block;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Product List</h4>
                        <h6>Manage your products</h6>
                    </div>
                </div>
                <ul class="table-top-head">

                </ul>
                <div class="page-btn">
                    <a href="{{route('admin.products.create')}}" class="btn btn-primary"><i
                            class="ti ti-circle-plus me-1"></i>Add Product</a>
                </div>
                {{-- <div class="page-btn import">
                    <a href="#" class="btn btn-secondary color" data-bs-toggle="modal" data-bs-target="#view-notes"><i
                            data-feather="download" class="me-1"></i>Import Product</a>
                </div> --}}
            </div>

            <!-- /product list -->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <form action="" method="GET" class="row g-2 align-items-end w-100">

                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search"
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                Search
                            </button>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm w-100">
                                Clear
                            </a>
                        </div>
                    </form>


                </div>
                <div class="d-flex gap-2 align-items-center p-2 justify-content-end">

                    <button type="button" class="btn btn-success btn-sm" id="bulkShowBtn">
                        Show Selected
                    </button>

                    <button type="button" class="btn btn-warning btn-sm" id="bulkHideBtn">
                        Hide Selected
                    </button>

                    <span id="selected-count" class="fw-bold text-primary"></span>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                        <span id="selected-count" class="ms-2"></span>
                                    </th>
                                    <th class="no-sort">
                                        S.No
                                    </th>
                                    {{-- <th>SKU </th> --}}
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Product Code</th>
                                    <th>Stock</th>
                                    {{-- <th>Tax</th> --}}
                                    <th>Status</th>
                                    <th class="no-sort text-center">Action</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                        </td>
                                        <td>{{ $product->id }}</td>
                                        {{-- <td>PT001 </td> --}}
                                        <td>
                                            <div class="d-flex align-items-center">

                                                <div class="img-hover-preview me-2">
                                                    <a href="{{route('admin.products.show', $product->id)}}">
                                                        <img src="{{asset($product->image)}}" alt="product" class="thumb">
                                                    </a>

                                                    <div class="preview-box">
                                                        <img src="{{asset($product->image)}}" alt="preview">
                                                    </div>
                                                </div>

                                                <a href="{{route('admin.products.show', $product->id)}}">
                                                    {{$product->title}}
                                                </a>

                                            </div>
                                        </td>
                                        <td>{{$product->category->title ?? '--'}}</td>
                                        {{-- <td>{{$product->brand->name ?? '--'}}</td> --}}
                                        <td>Selling Price : ₹{{$product->variant->price ?? '--'}}</td>
                                        <td>{{$product->hsn_code ?? '--'}}</td>
                                        <td>
                                            @php
                                                $stock = $product->variants->sum('stock');
                                            @endphp

                                            @if($stock == 0)
                                                <span class="badge badge-danger">Out of Stock</span>
                                            @elseif($stock <= 2)
                                                <span class="badge badge-danger">{{ $stock }} left</span>
                                            @else
                                                <span class="badge badge-success">{{ $stock }}</span>
                                            @endif
                                        </td>
                                        {{-- <td>Pc</td> --}}
                                        <td>
                                            @if($product->status == 'show')
                                                <span class="badge badge-success"> {{$product->status ?? '--'}}</span>
                                            @else
                                                <span class="badge badge-cyan"> {{$product->status ?? '--'}}</span>
                                            @endif
                                        </td>

                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 edit-icon  p-2"
                                                    href="{{route('admin.products.show', $product->id)}}">
                                                    <i data-feather="eye" class="feather-eye"></i>
                                                </a>
                                                <a class="me-2 p-2" href="{{route('admin.products.edit', $product->id)}}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a class="p-2 delete-btn" href="javascript:void(0);"
                                                    data-id="{{ $product->id }}"
                                                    data-url="{{ route('admin.products.destroy', $product->id) }}">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($product->created_at)->format('d-m-Y') }}
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
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">Are you sure you want to delete this product?</p>
                    <input type="hidden" id="deleteProductId">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set up global CSRF header for jQuery AJAX (if using jQuery)
            if (window.jQuery) {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
            }

            // Helper: safe show/hide using bootstrap 5 API fallback
            function safeShowModal(id) {
                const el = document.getElementById(id);
                if (!el) return;
                try {
                    if (window.bootstrap && bootstrap.Modal && typeof bootstrap.Modal.getOrCreateInstance === 'function') {
                        bootstrap.Modal.getOrCreateInstance(el).show();
                    } else if (window.jQuery && typeof $(el).modal === 'function') {
                        $(el).modal('show');
                    } else {
                        el.classList.add('show');
                        el.style.display = 'block';
                        document.body.classList.add('modal-open');
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                } catch (err) {
                    console.error('safeShowModal error', err);
                }
            }
            function safeHideModal(id) {
                const el = document.getElementById(id);
                if (!el) return;
                try {
                    if (window.bootstrap && bootstrap.Modal && typeof bootstrap.Modal.getOrCreateInstance === 'function') {
                        bootstrap.Modal.getOrCreateInstance(el).hide();
                    } else if (window.jQuery && typeof $(el).modal === 'function') {
                        $(el).modal('hide');
                    } else {
                        el.classList.remove('show');
                        el.style.display = 'none';
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                        document.body.classList.remove('modal-open');
                    }
                } catch (err) {
                    console.error('safeHideModal error', err);
                }
            }

            // open modal and set data
            document.querySelectorAll('.delete-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    const url = this.getAttribute('data-url');
                    if (!id || !url) {
                        console.error('delete-btn missing data-id or data-url');
                        return;
                    }
                    // store data on modal's hidden input and use dataset for url
                    document.getElementById('deleteProductId').value = id;
                    // store URL as dataset on modal element for later
                    const modalEl = document.getElementById('delete-modal');
                    modalEl.dataset.deleteUrl = url;

                    safeShowModal('delete-modal');
                });
            });

            // confirm delete
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn && confirmBtn.addEventListener('click', function () {
                const modalEl = document.getElementById('delete-modal');
                const id = document.getElementById('deleteProductId').value;
                const url = modalEl.dataset.deleteUrl;
                if (!id || !url) return alert('Invalid product');

                const row = document.querySelector('a.delete-btn[data-id="' + id + '"]')?.closest('tr');

                // Use fetch API (no jQuery required). Send POST + _method=DELETE
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' }),
                    credentials: 'same-origin'
                })
                    .then(async (res) => {
                        if (!res.ok) {
                            const err = await res.json().catch(() => ({ message: 'Server error' }));
                            throw err;
                        }
                        return res.json().catch(() => ({ success: 1 }));
                    })
                    .then((data) => {
                        // expect { success: 1 } from controller
                        safeHideModal('delete-modal');
                        if (data && (data.success === 1 || data.success === true)) {
                            if (row) row.remove();
                            else location.reload(); // fallback
                        } else {
                            alert(data.message || 'Deleted but unexpected response; reloading.');
                            location.reload();
                        }
                    })
                    .catch((err) => {
                        safeHideModal('delete-modal');
                        console.error('Delete error', err);
                        alert(err.message || 'Unable to delete product.');
                    });
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const selectAll = document.getElementById("select-all");
            const checkboxes = document.querySelectorAll(".product-checkbox");
            const countBox = document.getElementById("selected-count");

            function updateCount() {
                let checked = document.querySelectorAll(".product-checkbox:checked").length;
                countBox.innerHTML = checked + " Selected";
            }

            selectAll.addEventListener("change", function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateCount();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener("change", updateCount);
            });

            function bulkUpdate(status) {

                let ids = [];

                document.querySelectorAll(".product-checkbox:checked").forEach(cb => {
                    ids.push(cb.value);
                });

                if (ids.length == 0) {
                    alert("Select products first");
                    return;
                }

                fetch("{{ route('admin.products.bulk.status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        ids: ids,
                        status: status
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    });

            }

            document.getElementById("bulkShowBtn").onclick = () => bulkUpdate("show");
            document.getElementById("bulkHideBtn").onclick = () => bulkUpdate("hide");

        });
    </script>
@endsection