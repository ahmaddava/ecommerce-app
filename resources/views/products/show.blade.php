@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('products.category', $product->category->id) }}">{{ $product->category->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-images">
                    @if ($product->has_multiple_images && $product->images->count() > 0)
                        <!-- Main Image Display -->
                        <div class="main-image-container mb-3">
                            <img id="mainProductImage"
                                src="{{ $product->primaryImage() ? $product->primaryImage()->image_url : $product->image_url }}"
                                alt="{{ $product->name }}" class="img-fluid rounded shadow-sm main-product-image">

                            <!-- Image Type Badge -->
                            <span id="imageTypeBadge" class="badge bg-primary image-type-badge">Gambar Utama</span>
                        </div>

                        <!-- Image Thumbnails -->
                        <div class="image-thumbnails">
                            <div class="row g-2">
                                @foreach ($product->productImages()->get() as $index => $image)
                                    <div class="col-3">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}"
                                            class="img-fluid rounded thumbnail-image {{ $index === 0 ? 'active' : '' }}"
                                            data-main-src="{{ $image->image_url }}" data-type="product"
                                            data-alt="{{ $image->alt_text }}" onclick="changeMainImage(this)">
                                    </div>
                                @endforeach

                                @if ($product->sizeChartImage())
                                    <div class="col-3">
                                        <img src="{{ $product->sizeChartImage()->image_url }}" alt="Tabel Ukuran"
                                            class="img-fluid rounded thumbnail-image size-chart-thumb"
                                            data-main-src="{{ $product->sizeChartImage()->image_url }}"
                                            data-type="size_chart" data-alt="Tabel Ukuran" onclick="changeMainImage(this)">
                                        <div class="thumbnail-overlay">
                                            <small>Tabel Ukuran</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Single Image Display -->
                        <div class="main-image-container">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="img-fluid rounded shadow-sm main-product-image">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Title & Category -->
                    <div class="mb-3">
                        <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <p class="text-muted mb-0">SKU: {{ $product->sku }}</p>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <h2 class="price text-primary">{{ $product->formatted_price }}</h2>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if ($product->has_sizes)
                            <div class="stock-info">
                                @if ($product->isInStock())
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Tersedia
                                    </span>
                                    <small class="text-muted ms-2">Total stok: {{ $product->total_stock }} pcs</small>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Habis
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="stock-info">
                                @if ($product->isInStock())
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Tersedia
                                    </span>
                                    <small class="text-muted ms-2">Stok: {{ $product->stock }} pcs</small>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Habis
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Size Selection (for clothing products) -->
                    @if ($product->has_sizes && $product->sizes->count() > 0)
                        <div class="mb-4">
                            <h5>Pilih Ukuran:</h5>
                            <div class="size-selection">
                                <div class="row g-2">
                                    @foreach ($product->sizes as $size)
                                        <div class="col-auto">
                                            <input type="radio" class="btn-check size-option" name="selected_size"
                                                id="size_{{ $size->size }}" value="{{ $size->size }}"
                                                data-stock="{{ $size->stock }}"
                                                data-available="{{ $size->isInStock() ? 'true' : 'false' }}"
                                                {{ !$size->isInStock() ? 'disabled' : '' }}>
                                            <label
                                                class="btn btn-outline-primary size-btn {{ !$size->isInStock() ? 'disabled' : '' }}"
                                                for="size_{{ $size->size }}">
                                                {{ $size->size }}
                                                @if (!$size->isInStock())
                                                    <small class="d-block text-muted">Habis</small>
                                                @else
                                                    <small class="d-block text-muted">{{ $size->stock }} pcs</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Size Details -->
                            <div id="sizeDetails" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="mb-1">Detail Ukuran:</h6>
                                    <p class="mb-0" id="sizeDetailsText"></p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quantity Selection -->
                    <div class="mb-4">
                        <h5>Jumlah:</h5>
                        <div class="quantity-selector">
                            <div class="input-group" style="max-width: 150px;">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseQty">-</button>
                                <input type="number" class="form-control text-center" id="quantity" value="1"
                                    min="1" max="1">
                                <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
                            </div>
                            <small class="text-muted d-block mt-1">Maksimal: <span id="maxStock">1</span> pcs</small>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="mb-4">
                        @auth
                            @if (auth()->user()->isCustomer())
                                <button type="button" class="btn btn-primary btn-lg w-100" id="addToCartBtn" disabled>
                                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                </button>
                                @if ($product->has_sizes)
                                    <small class="text-muted d-block mt-2">Pilih ukuran terlebih dahulu</small>
                                @endif
                            @else
                                <button type="button" class="btn btn-secondary btn-lg w-100" disabled>
                                    <i class="bi bi-shield-lock"></i> Hanya untuk Customer
                                </button>
                                <small class="text-muted d-block mt-2">Akun admin tidak dapat berbelanja</small>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                            </a>
                            <small class="text-muted d-block mt-2">Silakan login terlebih dahulu untuk menambahkan produk ke
                                keranjang</small>
                        @endauth
                    </div>

                    <!-- Product Description -->
                    @if ($product->description)
                        <div class="mb-4">
                            <h5>Deskripsi Produk:</h5>
                            <div class="product-description">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Product Specifications -->
                    <div class="mb-4">
                        <h5>Spesifikasi:</h5>
                        <div class="specifications">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Kategori:</strong><br>
                                    <span class="text-muted">{{ $product->category->name }}</span>
                                </div>
                                @if (!$product->has_sizes && $product->weight)
                                    <div class="col-6">
                                        <strong>Berat:</strong><br>
                                        <span class="text-muted">{{ $product->weight }} gram</span>
                                    </div>
                                @endif
                                @if ($product->has_sizes)
                                    <div class="col-6">
                                        <strong>Tipe Ukuran:</strong><br>
                                        <span class="text-muted">{{ $product->size_type_label }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Size Chart Modal -->
        @if ($product->has_sizes && $product->sizeChartImage())
            <div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sizeChartModalLabel">
                                <i class="bi bi-rulers"></i> Tabel Ukuran - {{ $product->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ $product->sizeChartImage()->image_url }}" alt="Tabel Ukuran" class="img-fluid">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Related Products -->
        @if ($relatedProducts && $relatedProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4">Produk Terkait</h3>
                    <div class="row">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 product-card">
                                    <img src="{{ $relatedProduct->image_url }}" class="card-img-top"
                                        alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ Str::limit($relatedProduct->name, 50) }}</h6>
                                        <p class="card-text text-primary fw-bold">{{ $relatedProduct->formatted_price }}
                                        </p>
                                        <div class="mt-auto">
                                            <a href="{{ route('products.show', $relatedProduct->id) }}"
                                                class="btn btn-outline-primary btn-sm w-100">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .main-product-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            position: relative;
        }

        .main-image-container {
            position: relative;
        }

        .image-type-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .thumbnail-image {
            width: 100%;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail-image:hover {
            border-color: #0d6efd;
            transform: scale(1.05);
        }

        .thumbnail-image.active {
            border-color: #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        }

        .thumbnail-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 4px;
            font-size: 10px;
            text-align: center;
        }

        .size-chart-thumb {
            position: relative;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .size-btn {
            min-width: 60px;
            text-align: center;
        }

        .size-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .size-btn small {
            font-size: 0.7rem;
            line-height: 1;
        }

        .quantity-selector .input-group {
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .quantity-selector .form-control {
            border-left: none;
            border-right: none;
        }

        .quantity-selector .btn {
            border-radius: 0;
        }

        .product-description {
            line-height: 1.6;
            color: #6c757d;
        }

        .specifications {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stock-info {
            display: flex;
            align-items: center;
        }

        .size-selection {
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.5rem;
            }

            .price {
                font-size: 2rem;
            }

            .main-product-image {
                height: 300px;
            }

            .thumbnail-image {
                height: 60px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizeOptions = document.querySelectorAll('.size-option');
            const quantityInput = document.getElementById('quantity');
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const addToCartBtn = document.getElementById('addToCartBtn');
            const maxStockSpan = document.getElementById('maxStock');
            const sizeDetails = document.getElementById('sizeDetails');
            const sizeDetailsText = document.getElementById('sizeDetailsText');

            const hasSize = {{ $product->has_sizes ? 'true' : 'false' }};
            const productStock = {{ $product->has_sizes ? 0 : $product->stock ?? 0 }};

            let selectedSize = null;
            let maxStock = hasSize ? 0 : productStock;

            // Initialize for non-size products
            if (!hasSize && productStock > 0) {
                maxStock = productStock;
                maxStockSpan.textContent = maxStock;
                quantityInput.max = maxStock;
                addToCartBtn.disabled = false;
            }

            // Size selection handling
            sizeOptions.forEach(option => {
                option.addEventListener('change', function() {
                    if (this.checked) {
                        selectedSize = this.value;
                        const stock = parseInt(this.dataset.stock);
                        const available = this.dataset.available === 'true';

                        if (available && stock > 0) {
                            maxStock = stock;
                            maxStockSpan.textContent = maxStock;
                            quantityInput.max = maxStock;
                            quantityInput.value = Math.min(parseInt(quantityInput.value), maxStock);
                            addToCartBtn.disabled = false;

                            // Show size details
                            showSizeDetails(selectedSize);
                        } else {
                            maxStock = 0;
                            addToCartBtn.disabled = true;
                        }

                        updateQuantityButtons();
                    }
                });
            });

            // Quantity controls
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateQuantityButtons();
                }
            });

            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue < maxStock) {
                    quantityInput.value = currentValue + 1;
                    updateQuantityButtons();
                }
            });

            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value < 1) {
                    this.value = 1;
                } else if (value > maxStock) {
                    this.value = maxStock;
                }
                updateQuantityButtons();
            });

            function updateQuantityButtons() {
                const currentValue = parseInt(quantityInput.value);
                decreaseBtn.disabled = currentValue <= 1;
                increaseBtn.disabled = currentValue >= maxStock;
            }

            function showSizeDetails(size) {
                // Fetch size details via API
                const sizeType = '{{ $product->size_type }}';

                fetch(`/api/size-details/${sizeType}/${size}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            sizeDetailsText.textContent = data.size_details.description;
                            sizeDetails.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching size details:', error);
                    });
            }

            // Add to cart functionality
            @auth
            @if (auth()->user()->isCustomer())
                addToCartBtn.addEventListener('click', function() {
                    if (hasSize && !selectedSize) {
                        alert('Silakan pilih ukuran terlebih dahulu');
                        return;
                    }

                    const quantity = parseInt(quantityInput.value);
                    if (quantity < 1 || quantity > maxStock) {
                        alert('Jumlah tidak valid');
                        return;
                    }

                    // Show loading state
                    const originalText = addToCartBtn.innerHTML;
                    addToCartBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Menambahkan...';
                    addToCartBtn.disabled = true;

                    // Prepare form data
                    const formData = new FormData();
                    formData.append('product_id', {{ $product->id }});
                    formData.append('quantity', quantity);
                    if (selectedSize) {
                        formData.append('size', selectedSize);
                    }

                    // Send AJAX request
                    fetch('{{ route('cart.add') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success state
                                addToCartBtn.innerHTML =
                                    '<i class="bi bi-check-circle"></i> Berhasil Ditambahkan!';
                                addToCartBtn.classList.remove('btn-primary');
                                addToCartBtn.classList.add('btn-success');

                                // Update cart count in navbar if exists
                                const cartCount = document.getElementById('cart-count');
                                if (cartCount && data.cart && data.cart.count) {
                                    cartCount.textContent = data.cart.count;
                                    cartCount.style.display = 'block';
                                }

                                // Reset button after 3 seconds
                                setTimeout(() => {
                                    addToCartBtn.innerHTML = originalText;
                                    addToCartBtn.classList.remove('btn-success');
                                    addToCartBtn.classList.add('btn-primary');
                                    addToCartBtn.disabled = false;
                                }, 3000);
                            } else {
                                // Show error
                                alert(data.message ||
                                'Terjadi kesalahan saat menambahkan ke keranjang');
                                addToCartBtn.innerHTML = originalText;
                                addToCartBtn.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menambahkan ke keranjang');
                            addToCartBtn.innerHTML = originalText;
                            addToCartBtn.disabled = false;
                        });
                });
            @endif
        @endauth

        // Initialize quantity buttons
        updateQuantityButtons();
        });

        // Image gallery functions
        function changeMainImage(thumbnail) {
            const mainImage = document.getElementById('mainProductImage');
            const imageTypeBadge = document.getElementById('imageTypeBadge');
            const newSrc = thumbnail.dataset.mainSrc;
            const imageType = thumbnail.dataset.type;
            const altText = thumbnail.dataset.alt;

            // Update main image
            mainImage.src = newSrc;
            mainImage.alt = altText;

            // Update badge
            const typeLabels = {
                'product': 'Gambar Produk',
                'size_chart': 'Tabel Ukuran',
                'detail': 'Detail Produk'
            };
            imageTypeBadge.textContent = typeLabels[imageType] || 'Gambar';

            // Update active thumbnail
            document.querySelectorAll('.thumbnail-image').forEach(img => {
                img.classList.remove('active');
            });
            thumbnail.classList.add('active');

            // Show size chart modal if it's a size chart image
            if (imageType === 'size_chart') {
                const sizeChartModal = new bootstrap.Modal(document.getElementById('sizeChartModal'));
                sizeChartModal.show();
            }
        }

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush
