@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="text-gradient">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </h1>
                <p class="text-muted">Tambahkan produk baru ke katalog</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Product Form -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle"></i> Informasi Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Nama Produk <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Harga <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="price" name="price"
                                            value="{{ old('price') }}" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                        placeholder="Masukkan deskripsi produk...">{{ old('description') }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        value="{{ old('sku') }}" placeholder="Otomatis jika kosong">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktifkan Produk
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock & Weight Section -->
                    <div class="card border-0 shadow-sm mb-4" id="stockWeightSection">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-box"></i> Stok & Berat
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stok <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stock" name="stock"
                                        value="{{ old('stock') }}" min="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Berat (gram) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="weight" name="weight"
                                        value="{{ old('weight') }}" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Size Section (Hidden by default) -->
                    <div class="card border-0 shadow-sm mb-4" id="sizeSection" style="display: none;">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-rulers"></i> Ukuran Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Info:</strong> Produk pakaian menggunakan sistem ukuran. Stok dikelola per ukuran.
                            </div>

                            <input type="hidden" id="has_sizes" name="has_sizes" value="0">
                            <input type="hidden" id="size_type" name="size_type" value="">

                            <div id="sizesContainer">
                                <!-- Sizes will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Images Section -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-images"></i> Gambar Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Legacy Single Image -->
                            <div class="mb-4">
                                <label for="image" class="form-label">Gambar Utama (Legacy)</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 5MB.</div>
                            </div>

                            <!-- Multiple Product Images -->
                            <div class="mb-4">
                                <label for="product_images" class="form-label">Gambar Produk (Multiple)</label>
                                <input type="file" class="form-control" id="product_images" name="product_images[]"
                                    accept="image/*" multiple>
                                <div class="form-text">Pilih beberapa gambar produk. Format: JPG, PNG, GIF. Maksimal 5MB
                                    per file.</div>
                                <div id="productImagesPreview" class="mt-3"></div>
                            </div>

                            <!-- Size Chart Image -->
                            <div class="mb-4" id="sizeChartSection" style="display: none;">
                                <label for="size_chart_image" class="form-label">Gambar Tabel Ukuran</label>
                                <input type="file" class="form-control" id="size_chart_image" name="size_chart_image"
                                    accept="image/*">
                                <div class="form-text">Upload gambar yang menunjukkan detail ukuran produk.</div>
                                <div id="sizeChartPreview" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Preview Card -->
                    <div class="card border-0 shadow-sm mb-4" id="previewCard">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-eye"></i> Preview Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="product-image-preview mb-3">
                                    <img id="previewImage" src="{{ asset('images/no-image.png') }}" alt="Preview"
                                        class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                <h6 id="previewName" class="text-muted">Nama Produk</h6>
                                <p id="previewPrice" class="h5 text-primary">Rp 0</p>
                                <span id="previewCategory" class="badge bg-secondary">Kategori</span>
                            </div>

                            <div id="previewSizes" class="mt-3" style="display: none;">
                                <h6>Ukuran Tersedia:</h6>
                                <div id="previewSizesList"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-lightbulb"></i> Tips
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Gunakan nama produk yang deskriptif
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Upload gambar berkualitas tinggi
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Isi deskripsi produk dengan lengkap
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Untuk pakaian, upload gambar tabel ukuran
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success"></i>
                                    Pastikan stok sesuai dengan ketersediaan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-check-circle"></i> Simpan Produk
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .product-image-preview {
            border: 2px dashed #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .size-input-group {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }

        .size-input-group.has-stock {
            border-color: #198754;
            background-color: #d1e7dd;
        }

        .image-preview {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 2px solid #dee2e6;
        }

        .image-preview .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            cursor: pointer;
        }

        .size-badge {
            display: inline-block;
            margin: 0.25rem;
            padding: 0.5rem 1rem;
            background-color: #e9ecef;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .size-badge.available {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .loading-spinner {
            display: none;
        }

        .loading .loading-spinner {
            display: inline-block;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const stockWeightSection = document.getElementById('stockWeightSection');
            const sizeSection = document.getElementById('sizeSection');
            const sizeChartSection = document.getElementById('sizeChartSection');
            const hasSizesInput = document.getElementById('has_sizes');
            const sizeTypeInput = document.getElementById('size_type');
            const sizesContainer = document.getElementById('sizesContainer');

            // Preview elements
            const previewName = document.getElementById('previewName');
            const previewPrice = document.getElementById('previewPrice');
            const previewCategory = document.getElementById('previewCategory');
            const previewImage = document.getElementById('previewImage');
            const previewSizes = document.getElementById('previewSizes');
            const previewSizesList = document.getElementById('previewSizesList');

            // Form elements
            const nameInput = document.getElementById('name');
            const priceInput = document.getElementById('price');
            const imageInput = document.getElementById('image');
            const productImagesInput = document.getElementById('product_images');
            const sizeChartInput = document.getElementById('size_chart_image');

            // Update preview on input changes
            nameInput.addEventListener('input', updatePreview);
            priceInput.addEventListener('input', updatePreview);
            categorySelect.addEventListener('change', function() {
                updatePreview();
                handleCategoryChange();
            });

            // Image preview handlers
            imageInput.addEventListener('change', handleImagePreview);
            productImagesInput.addEventListener('change', handleMultipleImagesPreview);
            sizeChartInput.addEventListener('change', handleSizeChartPreview);

            function updatePreview() {
                previewName.textContent = nameInput.value || 'Nama Produk';
                previewPrice.textContent = priceInput.value ? 'Rp ' + formatNumber(priceInput.value) : 'Rp 0';

                const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
                previewCategory.textContent = selectedCategory.text !== 'Pilih Kategori' ? selectedCategory.text :
                    'Kategori';
            }

            function handleCategoryChange() {
                const categoryId = categorySelect.value;

                if (!categoryId) {
                    showStockWeightSection();
                    return;
                }

                console.log('Category changed to:', categoryId);

                // Fetch size information
                fetch(`/api/sizes/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('API Response:', data);

                        if (data.success && data.requires_sizes) {
                            showSizeSection(data.size_type, data.sizes);
                        } else {
                            showStockWeightSection();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching sizes:', error);
                        showStockWeightSection();
                    });
            }

            function showStockWeightSection() {
                stockWeightSection.style.display = 'block';
                sizeSection.style.display = 'none';
                sizeChartSection.style.display = 'none';
                previewSizes.style.display = 'none';

                hasSizesInput.value = '0';
                sizeTypeInput.value = '';

                // Make stock and weight required
                document.getElementById('stock').required = true;
                document.getElementById('weight').required = true;

                console.log('Showing stock/weight section');
            }

            function showSizeSection(sizeType, sizes) {
                stockWeightSection.style.display = 'none';
                sizeSection.style.display = 'block';
                sizeChartSection.style.display = 'block';
                previewSizes.style.display = 'block';

                hasSizesInput.value = '1';
                sizeTypeInput.value = sizeType;

                // Make stock and weight not required
                document.getElementById('stock').required = false;
                document.getElementById('weight').required = false;

                generateSizeInputs(sizes);
                updateSizePreview(sizes);

                console.log('Showing size section for type:', sizeType);
            }

            function generateSizeInputs(sizes) {
                sizesContainer.innerHTML = '';

                Object.keys(sizes).forEach(size => {
                    const sizeData = sizes[size];
                    const sizeDiv = document.createElement('div');
                    sizeDiv.className = 'size-input-group';
                    sizeDiv.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h6 class="mb-0">${sizeData.label}</h6>
                        <small class="text-muted">${sizeData.description}</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control size-stock-input" 
                               name="sizes[${Object.keys(sizes).indexOf(size)}][stock]" 
                               value="0" min="0" data-size="${size}">
                        <input type="hidden" name="sizes[${Object.keys(sizes).indexOf(size)}][size]" value="${size}">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input size-available-input" type="checkbox" 
                                   name="sizes[${Object.keys(sizes).indexOf(size)}][is_available]" 
                                   value="1" checked data-size="${size}">
                            <label class="form-check-label">Tersedia</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="size-preview-badge">
                            <span class="badge bg-secondary" id="badge-${size}">Stok: 0</span>
                        </div>
                    </div>
                </div>
            `;
                    sizesContainer.appendChild(sizeDiv);
                });

                // Add event listeners for size inputs
                document.querySelectorAll('.size-stock-input').forEach(input => {
                    input.addEventListener('input', updateSizeInputPreview);
                });

                document.querySelectorAll('.size-available-input').forEach(input => {
                    input.addEventListener('change', updateSizeInputPreview);
                });
            }

            function updateSizeInputPreview() {
                document.querySelectorAll('.size-stock-input').forEach(input => {
                    const size = input.dataset.size;
                    const stock = parseInt(input.value) || 0;
                    const available = document.querySelector(
                        `input[data-size="${size}"].size-available-input`).checked;
                    const badge = document.getElementById(`badge-${size}`);
                    const container = input.closest('.size-input-group');

                    badge.textContent = `Stok: ${stock}`;
                    badge.className = `badge ${stock > 0 && available ? 'bg-success' : 'bg-secondary'}`;

                    container.className = `size-input-group ${stock > 0 && available ? 'has-stock' : ''}`;
                });

                updateSizePreviewList();
            }

            function updateSizePreview(sizes) {
                previewSizesList.innerHTML = '';
                Object.keys(sizes).forEach(size => {
                    const span = document.createElement('span');
                    span.className = 'size-badge';
                    span.id = `preview-${size}`;
                    span.textContent = size;
                    previewSizesList.appendChild(span);
                });
            }

            function updateSizePreviewList() {
                document.querySelectorAll('.size-stock-input').forEach(input => {
                    const size = input.dataset.size;
                    const stock = parseInt(input.value) || 0;
                    const available = document.querySelector(
                        `input[data-size="${size}"].size-available-input`).checked;
                    const previewBadge = document.getElementById(`preview-${size}`);

                    if (previewBadge) {
                        previewBadge.className = `size-badge ${stock > 0 && available ? 'available' : ''}`;
                    }
                });
            }

            function handleImagePreview(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            function handleMultipleImagesPreview(event) {
                const files = event.target.files;
                const previewContainer = document.getElementById('productImagesPreview');
                previewContainer.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageDiv = document.createElement('div');
                            imageDiv.className = 'image-preview';
                            imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <button type="button" class="remove-image" onclick="removeImagePreview(this)">×</button>
                    `;
                            previewContainer.appendChild(imageDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            function handleSizeChartPreview(event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('sizeChartPreview');

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                    <div class="image-preview">
                        <img src="${e.target.result}" alt="Size Chart Preview">
                        <button type="button" class="remove-image" onclick="removeSizeChartPreview()">×</button>
                    </div>
                `;
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Form submission handling
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Simpan Produk';
                }, 10000);
            });

            // Utility functions
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            // Auto dismiss alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Global functions for remove buttons
        function removeImagePreview(button) {
            button.parentElement.remove();
        }

        function removeSizeChartPreview() {
            document.getElementById('sizeChartPreview').innerHTML = '';
            document.getElementById('size_chart_image').value = '';
        }
    </script>
@endpush
