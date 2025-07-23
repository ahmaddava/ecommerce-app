@extends('layouts.app')

@section('title', 'Detail Kategori: ' . $category->name)

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kelola Kategori</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="text-gradient">
                <i class="bi bi-tag"></i> {{ $category->name }}
                <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }} ms-2">
                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </h1>
            <p class="text-muted">{{ $category->description ?: 'Tidak ada deskripsi' }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Category Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-box text-primary" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0">{{ $category->products()->count() }}</h3>
                            <small class="text-muted">Total Produk</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0">{{ $category->products()->where('is_active', true)->count() }}</h3>
                            <small class="text-muted">Produk Aktif</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-archive text-warning" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0">{{ $category->products()->sum('stock') }}</h3>
                            <small class="text-muted">Total Stok</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in Category -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list"></i> Produk dalam Kategori
                        </h5>
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($category->products()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products()->orderBy('created_at', 'desc')->get() as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if($product->image)
                                                            <img src="{{ asset($product->image) }}" 
                                                                 class="rounded" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                                 alt="{{ $product->name }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="bi bi-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                                        <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $product->stock <= 10 ? 'danger' : ($product->stock <= 20 ? 'warning' : 'success') }}">
                                                    {{ $product->stock }} unit
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('products.show', $product->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-box text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum Ada Produk</h4>
                            <p class="text-muted">Kategori ini belum memiliki produk</p>
                            <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Category Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle"></i> Detail Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">ID Kategori</small>
                        <div class="fw-bold">{{ $category->id }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Nama Kategori</small>
                        <div class="fw-bold">{{ $category->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Deskripsi</small>
                        <div>{{ $category->description ?: 'Tidak ada deskripsi' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Dibuat</small>
                        <div>{{ $category->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    
                    <div class="mb-0">
                        <small class="text-muted">Terakhir Diperbarui</small>
                        <div>{{ $category->updated_at->format('d F Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Kategori
                        </a>
                        
                        <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }} w-100">
                                <i class="bi bi-{{ $category->is_active ? 'pause' : 'play' }}"></i> 
                                {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </a>
                        
                        @if($category->products()->count() == 0)
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> Hapus Kategori
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-danger w-100" disabled title="Tidak dapat dihapus (masih ada produk)">
                                <i class="bi bi-trash"></i> Hapus Kategori
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Category Performance -->
            @if($category->products()->count() > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up"></i> Performa Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Produk Aktif</small>
                                <small class="fw-bold">{{ round(($category->products()->where('is_active', true)->count() / $category->products()->count()) * 100) }}%</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ ($category->products()->where('is_active', true)->count() / $category->products()->count()) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Stok Tersedia</small>
                                <small class="fw-bold">{{ $category->products()->where('stock', '>', 0)->count() }}/{{ $category->products()->count() }}</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" 
                                     style="width: {{ $category->products()->count() > 0 ? ($category->products()->where('stock', '>', 0)->count() / $category->products()->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-0">
                            <small class="text-muted">Rata-rata Harga</small>
                            <div class="fw-bold">Rp {{ number_format($category->products()->avg('price') ?: 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

