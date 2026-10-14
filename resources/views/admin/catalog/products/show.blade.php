@extends('layout.mainlayout')

@section('content')

    <style>
        .sticky-sidebar {
            position: sticky;
            top: 90px;
        }

        .product-img-main {
            max-height: 330px;
            object-fit: contain;
            background: #f7f7f7;
            border-radius: 8px;
            padding: 10px;
        }

        .zoom:hover {
            transform: scale(1.04);
            transition: 0.3s ease;
        }

        .variant-badge {
            font-size: 13px;
        }

        .chart-container {
            height: 260px;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            @component('components.breadcrumb')
            @slot('title') Product Details @endslot
            @slot('li_1') Catalog @endslot
            @slot('li_2') Products @endslot
            @slot('li_3') Details @endslot
            @endcomponent


            <div class="row">

                <!-- STICKY SIDEBAR -->
                <div class="col-lg-3">
                    <div class="card shadow-sm sticky-sidebar">
                        <div class="card-body text-center">

                            <!-- PRODUCT IMAGE -->
                            <a href="{{ asset($product->image) }}" data-lightbox="main">
                                <img src="{{ asset($product->image) }}" class="img-fluid product-img-main zoom" alt="">
                            </a>

                            <h4 class="fw-bold mt-3">{{ $product->title }}</h4>

                            <!-- STATUS BADGES -->
                            <p class="mt-2">
                                <span class="badge bg-{{ $product->status == 'show' ? 'success' : 'danger' }}">
                                    {{ ucfirst($product->status) }}
                                </span>

                                <span class="badge bg-{{ $product->is_feature == 'yes' ? 'success' : 'secondary' }}">
                                    {{ $product->is_feature == 'yes' ? 'Featured' : 'Standard' }}
                                </span>
                            </p>

                            <!-- QR CODE -->
                            {{-- <div class="mt-3">
                                <h6 class="fw-bold mb-2">QR Code</h6>
                                {!! QrCode::size(150)->generate(url('/product/'.$product->slug)) !!}
                            </div> --}}

                            <!-- ACTION BUTTONS -->
                            <div class="mt-4">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-edit"></i> Edit Product
                                </a>

                                {{-- <a href="" class="btn btn-warning w-100 mb-2">
                                    <i class="fas fa-copy"></i> Duplicate Product
                                </a> --}}

                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">
                                    Back to List
                                </a>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- MAIN TABS SECTION -->
                <div class="col-lg-9">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <!-- NAVIGATION TABS -->
                            <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                        href="#tab-info">Info</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        href="#tab-variants">Variants</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        href="#tab-gallery">Gallery</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        href="#tab-analytics">Analytics</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-logs">Activity
                                        Log</a></li>
                            </ul>


                            <div class="tab-content">

                                <!-- TAB 1: PRODUCT INFO -->
                                <div class="tab-pane fade show active" id="tab-info">
                                    <h4 class="fw-bold">Short Description</h4>
                                    <div class="border p-3 bg-white rounded mt-2">
                                        {{ $product->short_description }}
                                    </div>
                                    <h4 class="fw-bold">Description</h4>
                                    <div class="border p-3 bg-white rounded mt-2">
                                        {!! $product->description !!}
                                    </div>
                                </div>


                                <!-- TAB 2: VARIANTS WITH IMAGE CAROUSEL -->
                                <div class="tab-pane fade" id="tab-variants">
                                    <h4 class="fw-bold mb-3">Variants</h4>

                                    @foreach($product->variants as $variant)
                                        @php
                                            $orderQty = App\Models\OrderItem::where('product_variant_id', $variant->id)->whereHas('order', function ($query) {
                                                $query->where('status', '!=', 'cancelled'); })->sum('quantity');
                                            $addedPiece = ((int) $variant->stock + (int) $orderQty) ?? 0;
                                        @endphp
                                        <div class="card shadow-sm mb-3">
                                            <div class="card-body">

                                                <h5 class="fw-bold">Variant: {{ $variant->sku }}</h5>
                                                @foreach($variant->attributeMappings as $map)
                                                    <span class="badge bg-info text-dark variant-badge">
                                                        {{ $map->attribute->name }}: {{ $map->value->name }}
                                                    </span>
                                                @endforeach

                                                <!-- SWIPER CAROUSEL -->

                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="swiper mySwiper mt-3 mb-3">
                                                            <div class="swiper-wrapper">

                                                                <!-- Main Variant Image -->
                                                                @if($variant->image)
                                                                    <div class="swiper-slide">
                                                                        <a href="{{ asset($variant->image) }}"
                                                                            data-lightbox="variant{{ $variant->id }}">
                                                                            <img src="{{ asset($variant->image) }}"
                                                                                class="img-fluid rounded"
                                                                                style="height:auto; object-fit:cover;">
                                                                        </a>
                                                                    </div>
                                                                @endif

                                                                <!-- Variant Gallery -->
                                                                @foreach($variant->media as $media)
                                                                    <div class="swiper-slide">
                                                                        <a href="{{ asset($media->url) }}"
                                                                            data-lightbox="variant{{ $variant->id }}">
                                                                            <img src="{{ asset($media->url) }}"
                                                                                class="img-fluid rounded"
                                                                                style="height:140px; object-fit:cover;">
                                                                        </a>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                            <div class="swiper-pagination"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">

                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th>Actual Price</th>
                                                                <td>₹{{ $variant->actual_price }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Sale Price</th>
                                                                <td>₹{{ $variant->price }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Stock</th>
                                                                <td>
                                                                    @if($variant->stock <= $variant->low_stock_alert)
                                                                        <span class="badge bg-danger">{{ $variant->stock }}
                                                                            (Low)</span>
                                                                    @else
                                                                        <span class="badge bg-success">{{ $variant->stock }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Order Limits</th>
                                                                <td>{{ $variant->product_min_order }} -
                                                                    {{ $variant->product_max_order }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status</th>
                                                                <td>
                                                                    @if($variant->status == 'show')
                                                                        <span class="badge bg-success">Active</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Inactive</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Preorder Stock</th>
                                                                <td>{{ $variant->preorder_stock }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Preorder</th>
                                                                <td>
                                                                    @if($variant->preorder == '0')
                                                                        <span class="badge bg-danger">no</span>
                                                                    @else
                                                                        <span class="badge bg-success">yes</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Added Piece</th>
                                                                <td>{{ $addedPiece }}</td>
                                                            </tr>

                                                        </table>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                    @endforeach

                                </div>


                                <!-- TAB 3: PRODUCT GALLERY -->
                                <div class="tab-pane fade" id="tab-gallery">
                                    <h4 class="fw-bold mb-3">Gallery</h4>

                                    <div class="row">
                                        @foreach($product->media as $media)
                                            <div class="col-lg-2 col-md-3 col-6 mb-3">
                                                <a href="{{ asset($media->url) }}" data-lightbox="gallery">
                                                    <img src="{{ asset($media->url) }}" class="img-fluid rounded zoom shadow-sm"
                                                        style="height:120px; object-fit:cover;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                                <!-- TAB 4: ANALYTICS -->
                                <div class="tab-pane fade" id="tab-analytics">
                                    <h4 class="fw-bold mb-3">Sales Analytics</h4>

                                    <canvas id="salesChart" class="chart-container"></canvas>
                                </div>


                                <!-- TAB 5: ACTIVITY LOG -->
                                <div class="tab-pane fade" id="tab-logs">
                                    <h4 class="fw-bold mb-3">Activity Log</h4>

                                    <ul class="list-group">

                                        @foreach($product->activity_logs ?? [] as $log)
                                            <li class="list-group-item">
                                                <b>{{ $log->admin_name }}</b>
                                                {{ $log->action }}
                                                <span
                                                    class="text-muted float-end">{{ $log->created_at->diffForHumans() }}</span>
                                            </li>
                                        @endforeach

                                        @if(empty($product->activity_logs) || count($product->activity_logs) == 0)
                                            <li class="list-group-item text-muted">No activity recorded.</li>
                                        @endif

                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- INIT SWIPER -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            pagination: { el: ".swiper-pagination" },
            slidesPerView: 3,
            spaceBetween: 10,
        });
    </script>

    <!-- SALES CHART (dummy) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales (Units)',
                    data: [30, 45, 60, 40, 70, 90],
                    borderWidth: 3
                }]
            }
        });
    </script>

@endsection