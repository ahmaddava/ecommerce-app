@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kelola Kategori</a></li>
                <li class="breadcrumb-item active">Edit: {{ $category->name }}</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="text-gradient">
                    <i class="bi bi-pencil"></i> Edit Kategori
                </h1>
                <p class="text-muted">Perbarui informasi kategori: <strong>{{ $category->name }}</strong></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
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
                <i class="bi bi-exclamation-triangle"></i> <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> Informasi Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                            id="categoryForm">
                            @csrf
                            @method('PUT')

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kategori *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $category->name) }}"
                                    placeholder="Masukkan nama kategori" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Nama kategori harus unik dan tidak boleh sama dengan kategori lain
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Masukkan deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Deskripsi singkat tentang kategori ini</div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Aktifkan Kategori</strong>
                                    </label>
                                </div>
                                <div class="form-text">Kategori aktif akan ditampilkan di website dan dapat digunakan untuk
                                    produk</div>
                                @if ($category->products()->count() > 0 && $category->is_active)
                                    <div class="alert alert-warning mt-2">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <strong>Perhatian:</strong> Kategori ini memiliki
                                        {{ $category->products()->count() }} produk.
                                        Menonaktifkan kategori akan menyembunyikan semua produk dalam kategori ini dari
                                        website.
                                    </div>
                                @endif
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-check-circle"></i> Perbarui Kategori
                                </button>
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
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
                <!-- Category Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle"></i> Info Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">ID Kategori</small>
                            <div class="fw-bold">{{ $category->id }}</div>
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
                            <small class="text-muted">Jumlah Produk</small>
                            <div class="fw-bold">{{ $category->products()->count() }} produk</div>
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

                <!-- Recent Products -->
                @if ($category->products()->count() > 0)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-box"></i> Produk Terbaru
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach ($category->products()->orderBy('created_at', 'desc')->take(5)->get() as $product)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        @if ($product->image)
                                            <img src="{{ asset($product->image) }}" class="rounded"
                                                style="width: 30px; height: 30px; object-fit: cover;"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 30px; height: 30px;">
                                                <i class="bi bi-image text-muted small"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="small fw-bold">{{ Str::limit($product->name, 20) }}</div>
                                        <div class="small text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if ($category->products()->count() > 5)
                                <div class="text-center mt-2">
                                    <small class="text-muted">dan {{ $category->products()->count() - 5 }} produk
                                        lainnya</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-lightning"></i> Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.categories.show', $category->id) }}"
                                class="btn btn-outline-info btn-sm">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>

                            <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn btn-outline-{{ $category->is_active ? 'secondary' : 'success' }} btn-sm w-100">
                                    <i class="bi bi-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                                    {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            @if ($category->products()->count() == 0)
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Hapus Kategori
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Form validation dan debugging
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const submitBtn = document.getElementById('submitBtn');

            // Validasi nama kategori
            if (name.length < 2) {
                e.preventDefault();
                alert('Nama kategori harus minimal 2 karakter');
                document.getElementById('name').focus();
                return false;
            }

            // Tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-spinner fa-spin"></i> Memproses...';

            // Log data yang akan dikirim untuk debugging
            console.log('Form data yang akan dikirim:', {
                name: name,
                description: document.getElementById('description').value,
                is_active: document.getElementById('is_active').checked
            });

            // Re-enable button setelah 10 detik sebagai fallback
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Perbarui Kategori';
            }, 10000);
        });

        // Auto dismiss alerts setelah 5 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush
