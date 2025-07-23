@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content fade-in">
                    <h1 class="hero-title">Selamat Datang di {{ config('app.name') }}</h1>
                    <p class="hero-subtitle">Temukan berbagai produk berkualitas dengan harga terbaik. Belanja mudah, aman, dan terpercaya.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-3">
                            <i class="bi bi-grid"></i> Lihat Produk
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-person-plus"></i> Daftar Sekarang
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center slide-in-right">
                    <i class="bi bi-cart-check" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-gradient">Kategori Produk</h2>
                <p class="text-muted">Jelajahi berbagai kategori produk pilihan kami</p>
            </div>
        </div>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <a href="{{ route('products.category', $category->id) }}" class="text-decoration-none">
                        <div class="card category-card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-title mb-0">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->products_count ?? 0 }} produk</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-gradient">Produk Unggulan</h2>
                <p class="text-muted">Produk terpilih dengan kualitas terbaik dan harga terjangkau</p>
            </div>
        </div>
        <div class="row">
            @foreach($featuredProducts as $product)
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
                            
                            @if($product->stock <= 10)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                    Stok Terbatas
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-box"></i> {{ $product->stock }} tersedia
                                </small>
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
        </div>
        
        <div class="row">
            <div class="col-12 text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-gradient btn-lg">
                    <i class="bi bi-grid"></i> Lihat Semua Produk
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-gradient">Mengapa Memilih Kami?</h2>
                <p class="text-muted">Keunggulan berbelanja di {{ config('app.name') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-truck fs-2"></i>
                    </div>
                    <h5>Pengiriman Cepat</h5>
                    <p class="text-muted">Pengiriman ke seluruh Indonesia dengan jaminan aman dan cepat</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <h5>Produk Berkualitas</h5>
                    <p class="text-muted">Semua produk telah melalui quality control yang ketat</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-credit-card fs-2"></i>
                    </div>
                    <h5>Pembayaran Aman</h5>
                    <p class="text-muted">Berbagai metode pembayaran yang aman dan terpercaya</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-headset fs-2"></i>
                    </div>
                    <h5>Customer Service 24/7</h5>
                    <p class="text-muted">Tim customer service siap membantu Anda kapan saja</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3>Dapatkan Update Terbaru</h3>
                <p class="mb-0">Berlangganan newsletter kami untuk mendapatkan info produk terbaru dan penawaran menarik</p>
            </div>
            <div class="col-lg-6">
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Masukkan email Anda" required>
                    <button type="submit" class="btn btn-light">
                        <i class="bi bi-envelope"></i> Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
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
</script>
@endpush

