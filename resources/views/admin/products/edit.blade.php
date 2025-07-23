@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Kelola Produk</a></li>
            <li class="breadcrumb-item active">Edit: {{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="text-gradient">
                <i class="bi bi-pencil"></i> Edit Produk
            </h1>
            <p class="text-muted">Perbarui informasi produk: <strong>{{ $product->name }}</strong></p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Produk
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Produk -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   placeholder="Masukkan nama produk"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Masukkan deskripsi produk"
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Harga -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga *</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $product->price) }}" 
                                           placeholder="0"
                                           min="0"
                                           step="1000"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stok -->
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok *</label>
                                <input type="number" 
                                       class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', $product->stock) }}" 
                                       placeholder="0"
                                       min="0"
                                       required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- SKU -->
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" 
                                       class="form-control @error('sku') is-invalid @enderror" 
                                       id="sku" 
                                       name="sku" 
                                       value="{{ old('sku', $product->sku) }}" 
                                       placeholder="Kode produk unik">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Berat -->
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Berat (gram)</label>
                                <input type="number" 
                                       class="form-control @error('weight') is-invalid @enderror" 
                                       id="weight" 
                                       name="weight" 
                                       value="{{ old('weight', $product->weight) }}" 
                                       placeholder="0"
                                       min="0">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset($product->image) }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px; max-height: 200px;" 
                                         alt="Current image">
                                    <div class="form-text">Gambar saat ini</div>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload gambar baru untuk mengganti gambar saat ini. Format: JPG, PNG, maksimal 2MB</div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Aktifkan Produk</strong>
                                </label>
                            </div>
                            <div class="form-text">Produk aktif akan ditampilkan di website dan dapat dibeli oleh customer</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Perbarui Produk
                            </button>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i> Lihat Produk
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Product Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle"></i> Info Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">ID Produk</small>
                        <div class="fw-bold">{{ $product->id }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">SKU</small>
                        <div class="fw-bold">{{ $product->sku ?: 'Belum diset' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Dibuat</small>
                        <div>{{ $product->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    
                    <div class="mb-0">
                        <small class="text-muted">Terakhir Diperbarui</small>
                        <div>{{ $product->updated_at->format('d F Y, H:i') }}</div>
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
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info">
                            <i class="bi bi-eye"></i> Lihat di Website
                        </a>
                        
                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Hapus Produk
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb"></i> Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Gambar Produk</h6>
                        <p class="small text-muted mb-0">
                            Gunakan gambar berkualitas tinggi dengan rasio 1:1 untuk hasil terbaik
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Deskripsi</h6>
                        <p class="small text-muted mb-0">
                            Tulis deskripsi yang detail dan menarik untuk meningkatkan penjualan
                        </p>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class="text-primary">Stok</h6>
                        <p class="small text-muted mb-0">
                            Pastikan stok selalu update untuk menghindari overselling
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create or update preview
                let preview = document.getElementById('image-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'image-preview';
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxWidth = '200px';
                    preview.style.maxHeight = '200px';
                    document.getElementById('image').parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Format price input
    document.getElementById('price').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
</script>
@endpush

