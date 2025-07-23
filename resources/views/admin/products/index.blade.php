@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="text-gradient">
                <i class="bi bi-box"></i> Kelola Produk
            </h1>
            <p class="text-muted">Manajemen produk dan inventori</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.products.index') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Cari Produk</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Nama produk...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list"></i> Daftar Produk
                        </h5>
                        <span class="badge bg-primary">{{ $products->total() }} produk</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
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
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span>
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
                                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger btn-sm" 
                                                                title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} 
                                        dari {{ $products->total() }} produk
                                    </div>
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-box text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum Ada Produk</h4>
                            <p class="text-muted">Mulai tambahkan produk pertama Anda</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto submit form when category changes
    document.getElementById('category').addEventListener('change', function() {
        this.form.submit();
    });

    // Confirm delete
    function confirmDelete(productName) {
        return confirm(`Yakin ingin menghapus produk "${productName}"? Tindakan ini tidak dapat dibatalkan.`);
    }
</script>
@endpush

