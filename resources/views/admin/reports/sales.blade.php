@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="text-gradient">
                <i class="bi bi-graph-up"></i> Laporan Penjualan
            </h1>
            <p class="text-muted">Analisis penjualan dan performa bisnis</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf"></i> PDF</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.sales') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-currency-dollar fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                            <small class="text-muted">Total Penjualan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-receipt fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $totalOrders }}</h3>
                            <small class="text-muted">Total Pesanan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-graph-up fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                            <small class="text-muted">Rata-rata Pesanan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar fs-5"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $startDate->diffInDays($endDate) + 1 }}</h3>
                            <small class="text-muted">Hari Periode</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Grafik Penjualan Harian
                    </h5>
                </div>
                <div class="card-body">
                    @if($dailySales->count() > 0)
                        <canvas id="salesChart" height="100"></canvas>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Tidak Ada Data</h4>
                            <p class="text-muted">Belum ada penjualan pada periode ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy"></i> Produk Terlaris
                    </h5>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        @foreach($topProducts as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" 
                                             class="rounded" 
                                             style="width: 40px; height: 40px; object-fit: cover;" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ Str::limit($product->name, 25) }}</h6>
                                    <small class="text-muted">{{ $product->order_items_count }} terjual</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-primary">{{ $loop->iteration }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-box text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada produk terjual</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales Table -->
    @if($dailySales->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-table"></i> Detail Penjualan Harian
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Total Penjualan</th>
                                        <th>Jumlah Pesanan</th>
                                        <th>Rata-rata Pesanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailySales as $sale)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($sale->date)->format('d F Y') }}</td>
                                            <td><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td>
                                            <td>{{ $sale->orders_count ?? 0 }} pesanan</td>
                                            <td>Rp {{ number_format($sale->orders_count > 0 ? $sale->total / $sale->orders_count : 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($dailySales->count() > 0)
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($dailySales as $sale)
                        '{{ \Carbon\Carbon::parse($sale->date)->format("d/m") }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: [
                        @foreach($dailySales as $sale)
                            {{ $sale->total }},
                        @endforeach
                    ],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });
    @endif
</script>
@endpush

