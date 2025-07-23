@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item"><a href="{{-- route('products.category', $product->category->id) --}}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- Product Image --}}
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @if ($product->image)
                            <img src="{{ asset($product->image) }}" class="img-fluid w-100" alt="{{ $product->name }}"
                                style="max-height: 500px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="col-lg-6">
                <div class="product-info">
                    <div class="mb-3">
                        <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                        <h1 class="h2 text-gradient">{{ $product->name }}</h1>
                        <p class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="product-price mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                        @if ($product->weight)
                            <small class="text-muted">Berat: {{ $product->weight }} gram</small>
                        @endif
                    </div>

                    <div class="mb-4">
                        @if ($product->stock > 0)
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-success fw-bold">Tersedia (Stok: {{ $product->stock }})</span>
                            </div>
                            @if ($product->stock <= 10)
                                <small class="text-warning d-block mt-1">
                                    <i class="bi bi-exclamation-triangle"></i> Stok terbatas, segera pesan!
                                </small>
                            @endif
                        @else
                            <div class="d-flex align-items-center">
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                <span class="text-danger fw-bold">Stok Habis</span>
                            </div>
                        @endif
                    </div>

                    {{-- Add to Cart & User State Logic --}}
                    @if ($product->stock > 0)
                        @auth
                            @if (Auth::user()->role == 'customer')
                                <form id="add-to-cart-form" class="mb-4">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-5 col-lg-4">
                                            <label for="quantity" class="form-label">Jumlah</label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" id="quantity-minus">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="form-control text-center" id="quantity"
                                                    name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                                    required>
                                                <button type="button" class="btn btn-outline-secondary" id="quantity-plus">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-lg-8">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-lg mt-3">
                                                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info"><i class="bi bi-info-circle"></i> Fitur keranjang hanya tersedia
                                    untuk customer.</div>
                            @endif
                        @else
                            <div class="alert alert-primary"><i class="bi bi-person-circle"></i> <a href="{{ route('login') }}"
                                    class="alert-link">Login</a> atau <a href="{{ route('register') }}"
                                    class="alert-link">daftar</a> untuk membeli.</div>
                        @endauth
                    @endif

                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <div class="border rounded p-3"><i class="bi bi-truck text-primary fs-4"></i>
                                <div class="mt-2 small">Gratis Ongkir</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-3"><i class="bi bi-shield-check text-success fs-4"></i>
                                <div class="mt-2 small">Garansi Resmi</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-3"><i class="bi bi-arrow-clockwise text-warning fs-4"></i>
                                <div class="mt-2 small">Easy Return</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-card-text"></i> Deskripsi Produk</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $product->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($relatedProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="text-gradient mb-4">Produk Terkait</h3>
                </div>
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-6 mb-4">
                        {{-- Di sini Anda bisa menggunakan Blade Component untuk product card jika ada --}}
                        @include('partials._product-card', ['product' => $relatedProduct])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const addToCartForm = document.getElementById('add-to-cart-form');

            // --- Logic untuk Tombol Kuantitas (+/-) ---
            if (quantityInput) {
                document.getElementById('quantity-plus').addEventListener('click', function() {
                    const max = parseInt(quantityInput.max);
                    if (parseInt(quantityInput.value) < max) {
                        quantityInput.value = parseInt(quantityInput.value) + 1;
                    }
                });

                document.getElementById('quantity-minus').addEventListener('click', function() {
                    const min = parseInt(quantityInput.min);
                    if (parseInt(quantityInput.value) > min) {
                        quantityInput.value = parseInt(quantityInput.value) - 1;
                    }
                });
            }

            // --- Logic untuk Tombol Add to Cart (AJAX) ---
            if (addToCartForm) {
                addToCartForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const button = this.querySelector('button[type="submit"]');
                    const originalHtml = button.innerHTML;
                    const formData = new FormData(this);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    // Tampilkan state loading
                    button.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menambahkan...`;
                    button.disabled = true;

                    fetch('{{ route('cart.add') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            // Cek jika response tidak ok (misal: error 400/500)
                            if (!response.ok) {
                                // Ambil pesan error dari JSON jika ada, jika tidak, lempar error umum
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Update UI jika berhasil
                                button.innerHTML = `<i class="bi bi-check-circle"></i> Berhasil!`;
                                button.classList.remove('btn-primary');
                                button.classList.add('btn-success');

                                // Update counter di navbar
                                const cartCountEl = document.getElementById('cart-count');
                                cartCountEl.textContent = data.cart.count;
                                cartCountEl.style.display = data.cart.count > 0 ? 'block' : 'none';

                                // Reset tombol setelah beberapa detik
                                setTimeout(() => {
                                    button.innerHTML = originalHtml;
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-primary');
                                    button.disabled = false;
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            // Tangani error (baik dari network atau dari controller)
                            console.error('Error:', error);
                            alert(error.message || 'Terjadi kesalahan. Stok mungkin tidak cukup.');

                            // Kembalikan tombol ke state semula
                            button.innerHTML = originalHtml;
                            button.disabled = false;
                        });
                });
            }
        });
    </script>
@endpush
