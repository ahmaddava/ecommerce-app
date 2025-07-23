@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-gradient">
                    <i class="bi bi-speedometer2"></i> Admin Dashboard
                </h1>
                <p class="text-muted">Selamat datang di panel admin {{ config('app.name') }}</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-box fs-5"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $totalProducts }}</h3>
                                <small class="text-muted">Total Produk</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
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
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-people fs-5"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $totalCustomers }}</h3>
                                <small class="text-muted">Total Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-currency-dollar fs-5"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        {{-- <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning-fill"></i> Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 mb-3">
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-3">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-box-seam"></i> Kelola Produk
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-3">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-warning w-100">
                                    <i class="bi bi-tags"></i> Kelola Kategori
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-3">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-receipt"></i> Kelola Pesanan
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-3">
                                <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-inbox"></i> Kelola Pesan
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 mb-3">
                                <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-success w-100">
                                    <i class="bi bi-graph-up"></i> Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Pesanan Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'secondary') }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum ada pesanan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle text-warning"></i> Stok Menipis
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($lowStockProducts->count() > 0)
                            @foreach ($lowStockProducts as $product)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        @if ($product->image)
                                            <img src="{{ asset($product->image) }}" class="rounded"
                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ Str::limit($product->name, 20) }}</h6>
                                        <small class="text-danger">
                                            <i class="bi bi-box"></i> {{ $product->stock }} tersisa
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-warning btn-sm">
                                    Lihat Semua
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Semua produk stok aman</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
