<?php $page = 'product-list'; ?>

@extends('layout.mainlayout')

@section('content')
<style>
    .product-page {
        --food-primary: #e96b2c;
        --food-primary-dark: #cf5520;
        --food-ink: #202938;
        --food-muted: #7b8492;
        --food-line: #edf0f3;
        --food-soft: #fff7f2;
    }
    .product-page .page-header {
        display:flex; justify-content:space-between; align-items:center;
        gap:16px; flex-wrap:wrap; margin-bottom:22px;
    }
    .product-page .page-title h4 { color:var(--food-ink); font-size:24px; font-weight:750; margin:0 0 5px; }
    .product-page .page-title h6 { color:var(--food-muted); font-size:13px; font-weight:400; margin:0; }
    .product-page .btn-add-product {
        display:inline-flex; align-items:center; gap:8px; border:0; border-radius:9px;
        padding:11px 17px; background:var(--food-primary); color:#fff; font-weight:600;
        text-decoration:none; box-shadow:0 5px 14px rgba(233,107,44,.18);
    }
    .product-page .btn-add-product:hover { background:var(--food-primary-dark); color:#fff; }
    .product-page .product-summary {
        display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; margin-bottom:18px;
    }
    .product-page .summary-card {
        background:#fff; border:1px solid var(--food-line); border-radius:12px;
        padding:16px 18px; display:flex; align-items:center; gap:13px;
    }
    .product-page .summary-icon {
        width:42px; height:42px; display:grid; place-items:center; border-radius:11px;
        background:var(--food-soft); color:var(--food-primary); font-size:19px;
    }
    .product-page .summary-card small { display:block; color:var(--food-muted); font-size:12px; margin-bottom:3px; }
    .product-page .summary-card strong { color:var(--food-ink); font-size:20px; line-height:1.2; }
    .product-page .product-panel {
        border:1px solid var(--food-line); border-radius:13px; background:#fff;
        box-shadow:0 4px 18px rgba(28,39,54,.035); overflow:hidden;
    }
    .product-page .filter-panel { padding:18px; border-bottom:1px solid var(--food-line); background:#fff; }
    .product-page .form-label { color:#4b5563; font-size:12px; font-weight:650; margin-bottom:6px; }
    .product-page .form-control { min-height:40px; border-color:#e5e9ef; border-radius:8px; font-size:13px; }
    .product-page .form-control:focus { border-color:#f0a078; box-shadow:0 0 0 3px rgba(233,107,44,.10); }
    .product-page .btn-filter {
        min-height:40px; border-radius:8px; padding:8px 17px; background:var(--food-primary);
        border-color:var(--food-primary); color:#fff; font-size:13px; font-weight:600;
    }
    .product-page .btn-filter:hover { background:var(--food-primary-dark); color:#fff; }
    .product-page .btn-clear { min-height:40px; border-radius:8px; font-size:13px; }
    .product-page .bulk-toolbar {
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;
        gap:12px; padding:13px 18px; border-bottom:1px solid var(--food-line); background:#fcfcfd;
    }
    .product-page .bulk-actions { display:flex; align-items:center; flex-wrap:wrap; gap:8px; }
    .product-page .bulk-actions .btn { border-radius:8px; font-size:12px; font-weight:600; padding:8px 12px; }
    .product-page .selected-label { color:var(--food-primary); font-size:12px; font-weight:700; }
    .product-page .table-responsive { padding:0 12px 10px; }
    .product-page table { min-width:950px; margin:0; }
    .product-page .table thead th {
        background:#fafbfc; color:#687384; border-bottom:1px solid var(--food-line);
        font-size:11px; font-weight:700; letter-spacing:.045em; text-transform:uppercase;
        padding:15px 12px; white-space:nowrap;
    }
    .product-page .table tbody td {
        color:#3c4655; font-size:13px; padding:14px 12px; vertical-align:middle;
        border-bottom:1px solid #f0f2f5;
    }
    .product-page .table tbody tr:hover { background:#fffaf7; }
    .product-page .product-cell { display:flex; align-items:center; gap:12px; min-width:210px; }
    .product-page .product-thumb {
        width:48px; height:48px; border-radius:9px; object-fit:cover; background:#f4f5f7;
        border:1px solid #edf0f3; flex:0 0 auto;
    }
    .product-page .product-name { color:var(--food-ink); font-weight:650; text-decoration:none; }
    .product-page .product-name:hover { color:var(--food-primary); }
    .product-page .product-meta { display:block; color:#9aa2ad; font-size:11px; margin-top:3px; }
    .product-page .price-text { color:var(--food-ink); font-weight:700; white-space:nowrap; }
    .product-page .stock-badge, .product-page .status-badge {
        display:inline-flex; align-items:center; gap:5px; padding:6px 9px;
        border-radius:20px; font-size:11px; font-weight:650; white-space:nowrap;
    }
    .product-page .stock-good, .product-page .status-show { background:#eaf8f0; color:#218653; }
    .product-page .stock-low { background:#fff3e5; color:#bd6b12; }
    .product-page .stock-empty, .product-page .status-hide { background:#fff0ef; color:#c44943; }
    .product-page .action-group { display:flex; align-items:center; justify-content:center; gap:5px; }
    .product-page .action-btn {
        width:32px; height:32px; display:inline-grid; place-items:center; border-radius:8px;
        color:#667085; background:#f6f7f9; text-decoration:none; border:0;
    }
    .product-page .action-btn:hover { color:var(--food-primary); background:var(--food-soft); }
    .product-page .action-btn.delete-btn:hover { color:#d04444; background:#fff0ef; }
    .product-page .date-text { color:#87909d; font-size:12px; white-space:nowrap; }
    .product-page .select-column { width:38px; }
    .product-page input[type="checkbox"] { accent-color:var(--food-primary); width:15px; height:15px; cursor:pointer; }
    .product-page .empty-state { text-align:center; padding:45px 15px; color:var(--food-muted); }
    .product-page .empty-state i { display:block; color:#e9a17d; font-size:30px; margin-bottom:9px; }
    .product-page .modal-content { border:0; border-radius:13px; overflow:hidden; }
    .product-page .modal-header { border-bottom:1px solid var(--food-line); padding:17px 20px; }
    .product-page .modal-title { color:var(--food-ink); font-weight:700; }
    .product-page .modal-footer, .product-page .modal-body { padding:18px 20px; }
    @media(max-width:767px) {
        .product-page .product-summary { grid-template-columns:1fr; gap:9px; }
        .product-page .page-title h4 { font-size:21px; }
        .product-page .filter-panel { padding:14px; }
        .product-page .bulk-toolbar { padding:12px 14px; }
    }
</style>

<div class="page-wrapper product-page">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Products</h4>
                <h6>Manage your food menu, pricing, stock and availability</h6>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-add-product">
                <i class="ti ti-plus"></i> Add Product
            </a>
        </div>

        <div class="product-summary">
            <div class="summary-card">
                <span class="summary-icon"><i class="ti ti-tools-kitchen-2"></i></span>
                <div><small>Products listed</small><strong>{{ $products->count() }}</strong></div>
            </div>
            <div class="summary-card">
                <span class="summary-icon"><i class="ti ti-calendar-check"></i></span>
                <div><small>Current view</small><strong>{{ $products->count() }}</strong></div>
            </div>
            <div class="summary-card">
                <span class="summary-icon"><i class="ti ti-filter"></i></span>
                <div><small>Search results</small><strong>{{ request('search') ? 'Filtered' : 'All products' }}</strong></div>
            </div>
        </div>

        <div class="product-panel">
            <div class="filter-panel">
                <form action="" method="GET" class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Search products</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by product name or code..." value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label class="form-label">From date</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <label class="form-label">To date</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>
                    <div class="col-lg-2 col-md-6 col-6">
                        <button type="submit" class="btn btn-filter w-100"><i class="ti ti-search me-1"></i> Search</button>
                    </div>
                    <div class="col-lg-2 col-md-6 col-6">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-clear w-100">Clear filters</a>
                    </div>
                </form>
            </div>

            <div class="bulk-toolbar">
                <div>
                    <strong class="text-dark">Product inventory</strong>
                    <span class="text-muted small ms-2">Select products to update visibility</span>
                </div>
                <div class="bulk-actions">
                    <span id="selected-count" class="selected-label">0 selected</span>
                    <button type="button" class="btn btn-success" id="bulkShowBtn"><i class="ti ti-eye me-1"></i> Show selected</button>
                    <button type="button" class="btn btn-outline-warning" id="bulkHideBtn"><i class="ti ti-eye-off me-1"></i> Hide selected</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th class="select-column"><input type="checkbox" id="select-all" aria-label="Select all products"></th>
                            <th class="no-sort">ID</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Product code</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="no-sort text-center">Actions</th>
                            <th>Date added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @php $stock = (int) $product->variants->sum('stock'); @endphp
                            <tr>
                                <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}" aria-label="Select {{ $product->title }}"></td>
                                <td><span class="text-muted">#{{ $product->id }}</span></td>
                                <td>
                                    <div class="product-cell">
                                        <a href="{{ route('admin.products.show', $product->id) }}">
                                            <img src="{{ $product->image ? asset($product->image) : asset('assets/img/no-image.png') }}" alt="{{ $product->title }}" class="product-thumb">
                                        </a>
                                        <div>
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="product-name">{{ $product->title }}</a>
                                            <span class="product-meta">{{ ucfirst(str_replace('_', ' ', $product->food_type ?? 'food')) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category->title ?? '--' }}</td>
                                <td><span class="price-text">₹{{ $product->variant->price ?? '--' }}</span></td>
                                <td>{{ $product->hsn_code ?? '--' }}</td>
                                <td>
                                    @if($stock <= 0)
                                        <span class="stock-badge stock-empty">Out of stock</span>
                                    @elseif($stock <= 2)
                                        <span class="stock-badge stock-low">{{ $stock }} left</span>
                                    @else
                                        <span class="stock-badge stock-good">{{ $stock }} available</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->status == 'show')
                                        <span class="status-badge status-show"><i class="ti ti-check"></i> Active</span>
                                    @else
                                        <span class="status-badge status-hide"><i class="ti ti-eye-off"></i> Hidden</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-group">
                                        <a class="action-btn" title="View product" href="{{ route('admin.products.show', $product->id) }}"><i data-feather="eye"></i></a>
                                        <a class="action-btn" title="Edit product" href="{{ route('admin.products.edit', $product->id) }}"><i data-feather="edit-2"></i></a>
                                        <a class="action-btn delete-btn" title="Delete product" href="javascript:void(0);" data-id="{{ $product->id }}" data-url="{{ route('admin.products.destroy', $product->id) }}"><i data-feather="trash-2"></i></a>
                                    </div>
                                </td>
                                <td><span class="date-text">{{ \Carbon\Carbon::parse($product->created_at)->format('d M Y') }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="10"><div class="empty-state"><i class="ti ti-package-off"></i><strong>No products found</strong><div class="small mt-1">Try changing your search filters or add a new product.</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3 text-danger"><i class="ti ti-alert-circle" style="font-size:38px"></i></div>
                <p class="mb-3">Are you sure you want to delete this product?</p>
                <input type="hidden" id="deleteProductId">
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete product</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery) {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    }

    function safeShowModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        } else if (window.jQuery && typeof $(el).modal === 'function') {
            $(el).modal('show');
        }
    }

    function safeHideModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).hide();
        } else if (window.jQuery && typeof $(el).modal === 'function') {
            $(el).modal('hide');
        }
    }

    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const url = this.getAttribute('data-url');
            if (!id || !url) return;
            document.getElementById('deleteProductId').value = id;
            document.getElementById('delete-modal').dataset.deleteUrl = url;
            safeShowModal('delete-modal');
        });
    });

    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            const modalEl = document.getElementById('delete-modal');
            const id = document.getElementById('deleteProductId').value;
            const url = modalEl.dataset.deleteUrl;
            if (!id || !url) return alert('Invalid product');

            const row = document.querySelector('a.delete-btn[data-id="' + id + '"]')?.closest('tr');
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Deleting...';

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
            .then(async function (res) {
                if (!res.ok) {
                    const err = await res.json().catch(() => ({ message: 'Server error' }));
                    throw err;
                }
                return res.json().catch(() => ({ success: true }));
            })
            .then(function (data) {
                safeHideModal('delete-modal');
                if (data && (data.success === 1 || data.success === true)) {
                    if (row) row.remove();
                    else location.reload();
                } else {
                    alert(data.message || 'Unable to confirm deletion.');
                    location.reload();
                }
            })
            .catch(function (err) {
                safeHideModal('delete-modal');
                alert(err.message || 'Unable to delete product.');
            })
            .finally(function () {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Delete product';
            });
        });
    }

    const selectAll = document.getElementById('select-all');
    const countBox = document.getElementById('selected-count');

    function updateCount() {
        const boxes = document.querySelectorAll('.product-checkbox');
        const checked = document.querySelectorAll('.product-checkbox:checked').length;
        if (countBox) countBox.textContent = checked + ' selected';
        if (selectAll) {
            selectAll.checked = boxes.length > 0 && checked === boxes.length;
            selectAll.indeterminate = checked > 0 && checked < boxes.length;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = this.checked);
            updateCount();
        });
    }

    document.querySelectorAll('.product-checkbox').forEach(cb => cb.addEventListener('change', updateCount));

    function bulkUpdate(status) {
        const ids = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
        if (!ids.length) {
            alert('Select products first');
            return;
        }

        fetch("{{ route('admin.products.bulk.status') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: ids, status: status })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Product status updated');
            location.reload();
        })
        .catch(() => alert('Unable to update product status. Please try again.'));
    }

    const showBtn = document.getElementById('bulkShowBtn');
    const hideBtn = document.getElementById('bulkHideBtn');
    if (showBtn) showBtn.addEventListener('click', () => bulkUpdate('show'));
    if (hideBtn) hideBtn.addEventListener('click', () => bulkUpdate('hide'));

    updateCount();
});
</script>
@endsection
