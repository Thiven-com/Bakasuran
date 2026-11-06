@extends('layout.mainlayout')

@section('content')
<style>
    .food-product-detail {
        --food-primary: #e96b2c;
        --food-primary-dark: #cf5520;
        --food-ink: #202938;
        --food-muted: #7b8492;
        --food-line: #edf0f3;
        --food-soft: #fff6f0;
    }
    .food-product-detail .detail-heading {
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;
        gap:14px; margin-bottom:20px;
    }
    .food-product-detail .detail-heading h4 { margin:0 0 5px; color:var(--food-ink); font-size:23px; font-weight:750; }
    .food-product-detail .detail-heading p { margin:0; color:var(--food-muted); font-size:13px; }
    .food-product-detail .detail-heading-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .food-product-detail .btn-food-primary {
        background:var(--food-primary); border-color:var(--food-primary); color:#fff;
        border-radius:8px; padding:9px 15px; font-size:13px; font-weight:650;
    }
    .food-product-detail .btn-food-primary:hover { background:var(--food-primary-dark); color:#fff; }
    .food-product-detail .detail-card {
        border:1px solid var(--food-line); border-radius:13px; background:#fff;
        box-shadow:0 4px 18px rgba(28,39,54,.035); overflow:hidden;
    }
    .food-product-detail .sticky-sidebar { position:sticky; top:90px; }
    .food-product-detail .product-visual {
        background:linear-gradient(145deg,#fff8f3,#f8f9fb); border-radius:11px;
        padding:16px; display:flex; align-items:center; justify-content:center; min-height:245px;
    }
    .food-product-detail .product-img-main {
        max-height:260px; width:100%; object-fit:contain; border-radius:9px;
        transition:transform .25s ease;
    }
    .food-product-detail .product-visual:hover .product-img-main { transform:scale(1.035); }
    .food-product-detail .product-title { color:var(--food-ink); font-size:20px; font-weight:750; line-height:1.35; }
    .food-product-detail .product-id { color:var(--food-muted); font-size:12px; }
    .food-product-detail .status-pill {
        display:inline-flex; align-items:center; gap:5px; border-radius:30px;
        padding:6px 10px; font-size:11px; font-weight:700;
    }
    .food-product-detail .pill-active { color:#218653; background:#eaf8f0; }
    .food-product-detail .pill-inactive { color:#c44943; background:#fff0ef; }
    .food-product-detail .pill-featured { color:#b96a18; background:#fff4e5; }
    .food-product-detail .pill-standard { color:#687384; background:#f1f3f6; }
    .food-product-detail .sidebar-actions { display:grid; gap:9px; }
    .food-product-detail .sidebar-actions .btn { border-radius:8px; padding:10px; font-size:13px; font-weight:650; }
    .food-product-detail .section-caption {
        color:var(--food-ink); font-size:16px; font-weight:750; margin:0 0 13px;
    }
    .food-product-detail .detail-tabs {
        display:flex; flex-wrap:wrap; gap:7px; border:0; padding-bottom:14px;
        margin-bottom:20px; border-bottom:1px solid var(--food-line);
    }
    .food-product-detail .detail-tabs .nav-link {
        border:0; border-radius:8px; color:#687384; font-size:12px; font-weight:650;
        padding:9px 13px; background:#f6f7f9;
    }
    .food-product-detail .detail-tabs .nav-link:hover { color:var(--food-primary); background:var(--food-soft); }
    .food-product-detail .detail-tabs .nav-link.active {
        color:#fff; background:var(--food-primary); box-shadow:0 4px 10px rgba(233,107,44,.16);
    }
    .food-product-detail .description-box {
        border:1px solid var(--food-line); background:#fcfcfd; border-radius:10px;
        padding:16px; color:#596273; font-size:13px; line-height:1.75;
        overflow-wrap:anywhere;
    }
    .food-product-detail .description-box + .section-caption { margin-top:22px; }
    .food-product-detail .variant-card {
        border:1px solid var(--food-line); border-radius:11px; overflow:hidden; margin-bottom:15px;
    }
    .food-product-detail .variant-card-head {
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;
        gap:10px; padding:14px 16px; background:#fcfcfd; border-bottom:1px solid var(--food-line);
    }
    .food-product-detail .variant-card-head h6 { margin:0; color:var(--food-ink); font-weight:750; }
    .food-product-detail .variant-card-body { padding:16px; }
    .food-product-detail .variant-badge {
        display:inline-block; background:var(--food-soft); color:#b95725;
        border:1px solid #ffe3d3; border-radius:20px; padding:5px 9px;
        font-size:11px; font-weight:650; margin:4px 3px 0 0;
    }
    .food-product-detail .variant-swiper {
        background:#f8f9fb; border:1px solid var(--food-line); border-radius:10px;
        padding:10px 10px 28px; min-height:160px;
    }
    .food-product-detail .variant-swiper .swiper-slide {
        height:125px; display:flex; align-items:center; justify-content:center;
    }
    .food-product-detail .variant-swiper img {
        max-width:100%; width:100%; height:125px; object-fit:contain; border-radius:7px;
    }
    .food-product-detail .swiper-pagination { bottom:5px !important; }
    .food-product-detail .variant-data {
        width:100%; margin:0; border:1px solid var(--food-line); border-radius:9px;
        overflow:hidden; font-size:12px;
    }
    .food-product-detail .variant-data th {
        width:42%; color:#687384; font-weight:650; background:#fafbfc;
    }
    .food-product-detail .variant-data td { color:var(--food-ink); font-weight:600; }
    .food-product-detail .variant-data th,
    .food-product-detail .variant-data td { padding:9px 11px; border-color:var(--food-line); }
    .food-product-detail .gallery-image {
        display:block; width:100%; height:145px; object-fit:cover;
        border:1px solid var(--food-line); border-radius:10px; background:#f7f8fa;
        transition:transform .2s ease, box-shadow .2s ease;
    }
    .food-product-detail .gallery-image:hover { transform:translateY(-2px); box-shadow:0 7px 18px rgba(20,30,45,.10); }
    .food-product-detail .empty-panel {
        text-align:center; padding:38px 15px; color:var(--food-muted);
        background:#fcfcfd; border:1px dashed #dfe4ea; border-radius:10px; font-size:13px;
    }
    .food-product-detail .empty-panel i { display:block; color:#e8a17d; font-size:27px; margin-bottom:8px; }
    .food-product-detail .activity-item { border:1px solid var(--food-line); border-radius:9px; padding:13px 15px; margin-bottom:9px; }
    .food-product-detail .activity-item strong { color:var(--food-ink); font-size:13px; }
    .food-product-detail .activity-item p { color:#687384; font-size:12px; margin:4px 0 0; }
    .food-product-detail .activity-time { color:#9aa2ad; font-size:11px; white-space:nowrap; }
    .food-product-detail .chart-wrap { height:280px; position:relative; }
    @media(max-width:991px) {
        .food-product-detail .sticky-sidebar { position:static; }
    }
    @media(max-width:575px) {
        .food-product-detail .detail-heading h4 { font-size:20px; }
        .food-product-detail .detail-heading-actions { width:100%; }
        .food-product-detail .detail-heading-actions .btn { flex:1; }
        .food-product-detail .variant-card-body { padding:12px; }
        .food-product-detail .variant-data th,
        .food-product-detail .variant-data td { padding:8px; }
    }
</style>

<div class="page-wrapper food-product-detail">
    <div class="content">
        @component('components.breadcrumb')
            @slot('title') Product Details @endslot
            @slot('li_1') Catalog @endslot
            @slot('li_2') Products @endslot
            @slot('li_3') Details @endslot
        @endcomponent

        <div class="detail-heading">
            <div>
                <h4>Product Overview</h4>
                <p>View product information, variants, gallery and activity.</p>
            </div>
            <div class="detail-heading-actions">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i> Back to products
                </a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-food-primary">
                    <i class="ti ti-edit me-1"></i> Edit product
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-3">
                <div class="detail-card sticky-sidebar">
                    <div class="card-body p-3 p-xl-4">
                        <a href="{{ asset($product->image) }}" data-lightbox="main" class="product-visual d-block">
                            <img src="{{ asset($product->image) }}" class="product-img-main" alt="{{ $product->title }}">
                        </a>

                        <div class="mt-3">
                            <div class="product-id">PRODUCT #{{ $product->id }}</div>
                            <h4 class="product-title mt-1 mb-2">{{ $product->title }}</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="status-pill {{ $product->status == 'show' ? 'pill-active' : 'pill-inactive' }}">
                                    <i class="ti {{ $product->status == 'show' ? 'ti-check' : 'ti-eye-off' }}"></i>
                                    {{ $product->status == 'show' ? 'Active' : 'Hidden' }}
                                </span>
                                <span class="status-pill {{ $product->is_feature == 'yes' || $product->is_feature == 1 ? 'pill-featured' : 'pill-standard' }}">
                                    <i class="ti ti-star"></i>
                                    {{ $product->is_feature == 'yes' || $product->is_feature == 1 ? 'Featured' : 'Standard' }}
                                </span>
                            </div>
                        </div>

                        <div class="sidebar-actions mt-4">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-food-primary">
                                <i class="ti ti-edit me-1"></i> Edit Product
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                                <i class="ti ti-list me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="detail-card">
                    <div class="card-body p-3 p-lg-4">
                        <ul class="nav nav-tabs detail-tabs" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab-info" role="tab"> <i class="ti ti-info-circle me-1"></i> Information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-variants" role="tab"><i class="ti ti-layers-sub me-1"></i> Variants ({{ $product->variants->count() }})</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-gallery" role="tab"><i class="ti ti-photo me-1"></i> Gallery</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-analytics" role="tab"><i class="ti ti-chart-line me-1"></i> Analytics</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-logs" role="tab"><i class="ti ti-history me-1"></i> Activity Log</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
                                <h5 class="section-caption">Short Description</h5>
                                <div class="description-box">
                                    {{ $product->short_description ?: 'No short description available.' }}
                                </div>

                                <h5 class="section-caption">Product Description</h5>
                                <div class="description-box">
                                    {!! $product->description ?: '<span class="text-muted">No product description available.</span>' !!}
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-variants" role="tabpanel">
                                <h5 class="section-caption">Product Variants</h5>

                                @forelse($product->variants as $variant)
                                    @php
                                        $orderQty = App\Models\OrderItem::where('product_variant_id', $variant->id)
                                            ->whereHas('order', function ($query) {
                                                $query->where('status', '!=', 'cancelled');
                                            })->sum('quantity');
                                        $addedPiece = (int) $variant->stock + (int) $orderQty;
                                    @endphp

                                    <div class="variant-card">
                                        <div class="variant-card-head">
                                            <h6><i class="ti ti-package me-1 text-warning"></i> {{ $variant->sku }}</h6>
                                            <span class="status-pill {{ $variant->status == 'show' ? 'pill-active' : 'pill-inactive' }}">
                                                {{ $variant->status == 'show' ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="variant-card-body">
                                            @foreach($variant->attributeMappings as $map)
                                                <span class="variant-badge">{{ $map->attribute->name }}: {{ $map->value->name }}</span>
                                            @endforeach

                                            <div class="row g-3 mt-1 align-items-start">
                                                <div class="col-md-4">
                                                    @if($variant->image || $variant->media->count())
                                                        <div class="swiper variantSwiper variant-swiper">
                                                            <div class="swiper-wrapper">
                                                                @if($variant->image)
                                                                    <div class="swiper-slide">
                                                                        <a href="{{ asset($variant->image) }}" data-lightbox="variant{{ $variant->id }}">
                                                                            <img src="{{ asset($variant->image) }}" alt="Variant image">
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @foreach($variant->media as $media)
                                                                    <div class="swiper-slide">
                                                                        <a href="{{ asset($media->url) }}" data-lightbox="variant{{ $variant->id }}">
                                                                            <img src="{{ asset($media->url) }}" alt="Variant gallery image">
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="swiper-pagination"></div>
                                                        </div>
                                                    @else
                                                        <div class="empty-panel"><i class="ti ti-photo-off"></i>No variant image</div>
                                                    @endif
                                                </div>

                                                <div class="col-md-8">
                                                    <table class="table table-bordered variant-data">
                                                        <tbody>
                                                            <tr><th>Actual Price</th><td>₹{{ $variant->actual_price }}</td></tr>
                                                            <tr><th>Sale Price</th><td>₹{{ $variant->price }}</td></tr>
                                                            <tr>
                                                                <th>Stock</th>
                                                                <td>
                                                                    @if($variant->stock <= $variant->low_stock_alert)
                                                                        <span class="status-pill pill-inactive">{{ $variant->stock }} (Low)</span>
                                                                    @else
                                                                        <span class="status-pill pill-active">{{ $variant->stock }} available</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr><th>Order Limits</th><td>{{ $variant->product_min_order }} - {{ $variant->product_max_order }}</td></tr>
                                                            <tr><th>Preorder Stock</th><td>{{ $variant->preorder_stock }}</td></tr>
                                                            <tr>
                                                                <th>Preorder</th>
                                                                <td>
                                                                    @if($variant->preorder == '0')
                                                                        <span class="text-muted">No</span>
                                                                    @else
                                                                        <span class="text-success fw-semibold">Yes</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr><th>Added Pieces</th><td>{{ $addedPiece }}</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-panel"><i class="ti ti-layers-off"></i>No variants have been added to this product.</div>
                                @endforelse
                            </div>

                            <div class="tab-pane fade" id="tab-gallery" role="tabpanel">
                                <h5 class="section-caption">Product Gallery</h5>
                                @if($product->media->count())
                                    <div class="row g-3">
                                        @foreach($product->media as $media)
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                                <a href="{{ asset($media->url) }}" data-lightbox="gallery">
                                                    <img src="{{ asset($media->url) }}" class="gallery-image" alt="Product gallery image">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-panel"><i class="ti ti-photo-off"></i>No gallery images available.</div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="tab-analytics" role="tabpanel">
                                <h5 class="section-caption">Sales Analytics</h5>
                                <div class="description-box mb-3">Sample sales trend display. Replace the sample values with actual order data when analytics are connected.</div>
                                <div class="chart-wrap">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-logs" role="tabpanel">
                                <h5 class="section-caption">Activity Log</h5>
                                @forelse($product->activity_logs ?? [] as $log)
                                    <div class="activity-item d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <strong>{{ $log->admin_name }}</strong>
                                            <p>{{ $log->action }}</p>
                                        </div>
                                        <span class="activity-time">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                @empty
                                    <div class="empty-panel"><i class="ti ti-history"></i>No activity recorded for this product.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper !== 'undefined') {
        document.querySelectorAll('.variantSwiper').forEach(function (element) {
            new Swiper(element, {
                pagination: {
                    el: element.querySelector('.swiper-pagination'),
                    clickable: true
                },
                slidesPerView: 1,
                spaceBetween: 10,
                observer: true,
                observeParents: true
            });
        });
    }

    const chartCanvas = document.getElementById('salesChart');
    if (chartCanvas && typeof Chart !== 'undefined') {
        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales (Units)',
                    data: [30, 45, 60, 40, 70, 90],
                    borderWidth: 3,
                    tension: 0.35,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
});
</script>
@endsection
