@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kelola Kategori</a></li>
            <li class="breadcrumb-item active">Tambah Kategori</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="text-gradient">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </h1>
            <p class="text-muted">Buat kategori produk baru</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        
                        <!-- Nama Kategori -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Masukkan nama kategori"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nama kategori harus unik dan tidak boleh sama dengan kategori lain</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Masukkan deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Deskripsi singkat tentang kategori ini</div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Aktifkan Kategori</strong>
                                </label>
                            </div>
                            <div class="form-text">Kategori aktif akan ditampilkan di website dan dapat digunakan untuk produk</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan Kategori
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb"></i> Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Nama Kategori</h6>
                        <p class="small text-muted mb-0">
                            Gunakan nama yang jelas dan mudah dipahami. Contoh: "Elektronik", "Fashion Pria", "Peralatan Rumah Tangga"
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Deskripsi</h6>
                        <p class="small text-muted mb-0">
                            Berikan deskripsi singkat yang menjelaskan jenis produk apa saja yang termasuk dalam kategori ini
                        </p>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class="text-primary">Status Aktif</h6>
                        <p class="small text-muted mb-0">
                            Kategori yang tidak aktif tidak akan ditampilkan di website dan tidak dapat dipilih saat menambah produk
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Statistik Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ \App\Models\Category::count() }}</h4>
                                <small class="text-muted">Total Kategori</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">{{ \App\Models\Category::where('is_active', true)->count() }}</h4>
                            <small class="text-muted">Kategori Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from name (if needed in future)
    document.getElementById('name').addEventListener('input', function() {
        // Future enhancement: auto-generate slug
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (name.length < 2) {
            e.preventDefault();
            alert('Nama kategori harus minimal 2 karakter');
            document.getElementById('name').focus();
            return false;
        }
    });
</script>
@endpush

