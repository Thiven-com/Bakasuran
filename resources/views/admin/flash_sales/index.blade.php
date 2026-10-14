{{-- resources/views/admin/flash_sales/index.blade.php --}}
<?php $page = 'flash-sales'; ?>
@extends('layout.mainlayout')

@section('styles')
  <style>
    /* reuse the same polished styles as categories UI */
    .card--soft {
      border: 0;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
    }

    .table tbody tr:hover {
      background: #fbfbff;
    }

    .avatar-sm {
      width: 48px;
      height: 48px;
      border-radius: 8px;
      object-fit: cover;
    }

    .action-table-data .btn {
      color: #6b7280;
    }

    .action-table-data .btn:hover {
      color: #111827;
    }

    .brand-pic {
      border-radius: 8px;
      background-color: #f8fafc;
    }

    .badge-status {
      font-weight: 600;
      padding: .45rem .6rem;
      border-radius: 0.45rem;
    }

    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: #6b7280;
    }

    .table-top .search-input {
      max-width: 340px;
    }

    @media (max-width:767px) {
      .table thead {
        display: none;
      }

      .table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border-radius: 8px;
        padding: .6rem;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
      }

      .table tbody td {
        display: flex;
        justify-content: space-between;
      }

      .action-table-data {
        display: block;
        text-align: right;
      }
    }
  </style>
@endsection

@section('content')
  <div class="page-wrapper">
    <div class="content">

      {{-- header --}}
      <div class="d-flex align-items-start justify-content-between mb-3 gap-3">
        <div>
          <h4 class="mb-1 fw-bold">Flash Sales</h4>
          <p class="text-muted mb-0">Create and manage flash sale events (banners, schedule, status)</p>
        </div>

        <div class="d-flex align-items-center gap-2">
         
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFlashModal">
            <i class="ti ti-circle-plus me-1"></i> Add Flash Sale
          </button>
        </div>
      </div>

      {{-- filters & search --}}
      <div
        class="card card--soft mb-3 p-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 w-100">
          <div class="search-input flex-grow-1 me-2">
            <input type="search" id="flashSearch" class="form-control" placeholder="Search title / status / date">
          </div>

          <div class="d-flex gap-2">
            <select id="filterStatus" class="form-select">
              <option value="">All status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>

            <button class="btn btn-outline-primary" id="btnApplyFilters">Apply</button>
          </div>
        </div>
      </div>

      {{-- table --}}
      <div class="card card--soft">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="thead-light">
                <tr>
                  <th style="width:40px">S.No</th>
                  <th>Banner</th>
                  <th>Title</th>
                  <th>Starts At</th>
                  <th>Ends At</th>
                  <th>Status</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="flashTableBody">
                @forelse($flashSales as $fs)
                  <tr id="row-fs-{{ $fs->id }}" data-id="{{ $fs->id }}" data-status="{{ $fs->status }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                      @if($fs->banner)
                        <img src="{{ asset($fs->banner) }}" alt="banner" class="avatar-sm me-2"
                          style="max-width:120px; object-fit:cover;">
                      @else
                        <div class="avatar-sm me-2 brand-pic d-flex align-items-center justify-content-center text-muted">No
                          Image</div>
                      @endif
                    </td>
                    <td class="fw-semibold">{{ $fs->title ?? '—' }}</td>
                    <td>{{ optional($fs->starts_at)->format('d M Y H:i') ?? '—' }}</td>
                    <td>{{ optional($fs->ends_at)->format('d M Y H:i') }}</td>
                    <td>
                      @if($fs->status)
                        <span class="badge bg-success">Active</span>
                      @else
                        <span class="badge bg-secondary">Inactive</span>
                      @endif
                    </td>
                    <td class="action-table-data text-end">
                      <div class="d-inline-flex gap-1">
                        <button class="btn btn-sm btn-light view-btn" data-id="{{ $fs->id }}" title="Edit">
                          <i data-feather="edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $fs->id }}"
                          data-url="{{ route('admin.flash-sales.destroy', $fs->id) }}">
                          <i data-feather="trash-2"></i> Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4">No flash sales found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="p-3 d-flex justify-content-between align-items-center">
            <div><small class="text-muted">Showing {{ $flashSales->firstItem() ?? 0 }} -
                {{ $flashSales->lastItem() ?? 0 }} of {{ $flashSales->total() }}</small></div>
            <div>{{ $flashSales->links() }}</div>
          </div>
        </div>
      </div>

    </div>

    {{-- Footer --}}
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
      <p class="mb-0 text-gray-9">2025 &copy; {{ $site->site_name ?? ' '  }}</p>
      <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">ThiVen</a></p>
    </div>

  </div>

  {{-- Add Flash Sale Modal --}}
  <div class="modal fade" id="addFlashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('admin.flash-sales.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add Flash Sale</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input name="title" class="form-control" value="{{ old('title') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="row g-2">
              <div class="col-md-6 mb-3">
                <label class="form-label">Starts At (optional)</label>
                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ends At</label>
                <input type="datetime-local" name="ends_at" class="form-control" required value="{{ old('ends_at') }}">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Banner (image)</label>
              <input type="file" name="banner" accept="image/*" class="form-control" id="add_banner_input">
              <div id="add_banner_preview" class="mt-2"></div>
            </div>
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" id="addStatus" name="status" checked>
              <label class="form-check-label" for="addStatus">Active</label>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Edit Flash Sale Modal --}}
  <div class="modal fade" id="editFlashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form id="editFlashForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title">Edit Flash Sale</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="edit_id" name="id">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input id="edit_title" name="title" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="row g-2">
              <div class="col-md-6 mb-3">
                <label class="form-label">Starts At (optional)</label>
                <input id="edit_starts_at" type="datetime-local" name="starts_at" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Ends At</label>
                <input id="edit_ends_at" type="datetime-local" name="ends_at" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Banner (replace)</label>
              <input id="edit_banner" type="file" name="banner" accept="image/*" class="form-control">
              <div class="mt-2" id="currentBannerWrap"></div>
            </div>
            <div class="form-check form-switch mb-2">
              <input id="edit_status" class="form-check-input" type="checkbox" name="status">
              <label class="form-check-label" for="edit_status">Active</label>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" id="saveEditBtn" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Delete modal --}}
  <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center p-4">
          <h5 class="mb-3">Delete Flash Sale</h5>
          <p>Are you sure you want to delete this flash sale?</p>
          <input type="hidden" id="deleteFlashId">
          <div class="d-flex justify-content-center gap-2 mt-3">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.feather) feather.replace();

      const csrf = '{{ csrf_token() }}';

      // Add banner preview
      document.getElementById('add_banner_input')?.addEventListener('change', function (e) {
        const f = e.target.files[0];
        const wrap = document.getElementById('add_banner_preview');
        wrap.innerHTML = '';
        if (!f) return;
        const r = new FileReader();
        r.onload = function (ev) {
          const img = document.createElement('img');
          img.src = ev.target.result;
          img.style.maxHeight = '80px';
          img.style.objectFit = 'cover';
          wrap.appendChild(img);
        };
        r.readAsDataURL(f);
      });

      // Edit button -> fetch data and show modal
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.id;
          fetch('{{ url('flash-sales') }}/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(res => {
              if (!res || res.success !== 1) return alert('Unable to load data');
              const d = res.data;
              document.getElementById('edit_id').value = d.id;
              document.getElementById('edit_title').value = d.title ?? '';
              document.getElementById('edit_description').value = d.description ?? '';
              function toLocalDT(val) {
                if (!val) return '';
                const dt = new Date(val);
                const pad = n => n.toString().padStart(2, '0');
                return `${dt.getFullYear()}-${pad(dt.getMonth() + 1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
              }
              document.getElementById('edit_starts_at').value = toLocalDT(d.starts_at);
              document.getElementById('edit_ends_at').value = toLocalDT(d.ends_at);
              document.getElementById('edit_status').checked = !!d.status;
              const wrap = document.getElementById('currentBannerWrap');
              wrap.innerHTML = '';
              if (d.banner) {
                const img = document.createElement('img');
                img.src = d.banner.startsWith('http') ? d.banner : '{{ asset('') }}' + d.banner;
                img.style.maxHeight = '80px';
                img.style.objectFit = 'cover';
                wrap.appendChild(img);
              }
              const form = document.getElementById('editFlashForm');
              form.action = '{{ url('flash-sales') }}/' + d.id;
              const el = document.getElementById('editFlashModal');
              if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(el).show();
            }).catch(err => {
              console.error(err);
              alert('Unable to fetch record');
            });
        });
      });

      // Delete handling (modal + fetch)
      const deleteModalEl = document.getElementById('delete-modal');
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.id;
          const url = this.dataset.url;
          deleteModalEl.dataset.deleteUrl = url;
          document.getElementById('deleteFlashId').value = id;
          if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(deleteModalEl).show();
        });
      });

      document.getElementById('confirmDeleteBtn')?.addEventListener('click', function () {
        const url = deleteModalEl.dataset.deleteUrl;
        const id = document.getElementById('deleteFlashId').value;
        if (!url) return alert('Invalid request');
        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest'
          },
          credentials: 'same-origin',
          body: JSON.stringify({ _method: 'DELETE' })
        }).then(async res => {
          if (!res.ok) {
            const err = await res.json().catch(() => ({ message: 'Server error' }));
            throw err;
          }
          return res.json().catch(() => ({ success: 1 }));
        }).then(data => {
          if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(deleteModalEl).hide();
          if (data && (data.success === 1 || data.success === true)) {
            const row = document.getElementById('row-fs-' + id);
            if (row) row.remove(); else location.reload();
          } else {
            alert(data.message || 'Deleted but unexpected response');
            location.reload();
          }
        }).catch(err => {
          console.error(err);
          alert(err?.message || 'Unable to delete');
          if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(deleteModalEl).hide();
        });
      });

      // Quick client-side search
      document.getElementById('flashSearch')?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        document.querySelectorAll('#flashTableBody tr[id^="row-fs-"]').forEach(tr => {
          const txt = tr.textContent.toLowerCase();
          tr.style.display = txt.includes(q) ? '' : 'none';
        });
      });

      // select-all
      document.getElementById('selectAll')?.addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
      });
    });
  </script>
@endsection