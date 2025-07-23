@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
            <h1 class="text-gradient">Semua Produk</h1>
            <p class="text-muted">Temukan produk yang Anda cari dengan mudah</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Cari Produk</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Nama produk...">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Urutkan</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="">Terbaru</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @if($products->count() > 0)
            @foreach($products as $product)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Stock Badge -->
                            @if($product->stock <= 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    Stok Habis
                                </span>
                            @elseif($product->stock <= 10)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                    Stok Terbatas
                                </span>
                            @endif

                            <!-- Category Badge -->
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-box"></i> {{ $product->stock }} tersedia
                                </small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-2">SKU: {{ $product->sku }}</small>
                                @if($product->weight)
                                    <small class="text-muted">{{ $product->weight }}kg</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                                @auth
                                    @if(auth()->user()->isCustomer() && $product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @elseif($product->stock <= 0)
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="bi bi-x-circle"></i> Stok Habis
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-cart-plus"></i> Login untuk Beli
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
                    <h3 class="text-muted mt-3">Produk Tidak Ditemukan</h3>
                    <p class="text-muted">Coba ubah kata kunci pencarian atau filter yang Anda gunakan</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Lihat Semua Produk
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Results Info -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="text-center text-muted">
                <small>
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                    dari {{ $products->total() }} produk
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add to cart functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartForms = document.querySelectorAll('.add-to-cart-form');
        
        addToCartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambahkan...';
                button.disabled = true;
                
                // Submit form
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.text())
                .then(data => {
                    // Update cart count
                    updateCartCount();
                    
                    // Show success message
                    button.innerHTML = '<i class="bi bi-check"></i> Ditambahkan!';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                        button.disabled = false;
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            });
        });
    });

    // Auto-submit form on filter change
    document.getElementById('category').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('sort').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush

