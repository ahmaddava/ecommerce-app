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
                        <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                            @csrf

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kategori *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
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
                                    rows="4" maxlength="1000" placeholder="Masukkan deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="charCount">0</span>/1000 karakter. Deskripsi singkat tentang kategori ini
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Aktifkan Kategori</strong>
                                    </label>
                                </div>
                                <div class="form-text">Kategori aktif akan ditampilkan di website dan dapat digunakan untuk
                                    produk</div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-check-circle"></i> Simpan Kategori
                                </button>
                                <button type="reset" class="btn btn-outline-warning" id="resetBtn">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
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
                                Gunakan nama yang jelas dan mudah dipahami. Contoh: "Elektronik", "Fashion Pria", "Peralatan
                                Rumah Tangga"
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-primary">Deskripsi</h6>
                            <p class="small text-muted mb-0">
                                Berikan deskripsi singkat yang menjelaskan jenis produk apa saja yang termasuk dalam
                                kategori ini
                            </p>
                        </div>

                        <div class="mb-0">
                            <h6 class="text-primary">Status Aktif</h6>
                            <p class="small text-muted mb-0">
                                Kategori yang tidak aktif tidak akan ditampilkan di website dan tidak dapat dipilih saat
                                menambah produk
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
                                <h4 class="text-success mb-0">
                                    {{ \App\Models\Category::where('is_active', true)->count() }}</h4>
                                <small class="text-muted">Kategori Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="card border-0 shadow-sm mt-4" id="previewCard" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-eye"></i> Preview Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <h6 class="mb-0" id="previewName">-</h6>
                                <small class="text-muted" id="previewDescription">-</small>
                            </div>
                            <span class="badge" id="previewStatus">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Character counter untuk deskripsi
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');

        function updateCharCount() {
            const count = descriptionTextarea.value.length;
            charCount.textContent = count;

            if (count > 900) {
                charCount.style.color = '#dc3545';
            } else if (count > 700) {
                charCount.style.color = '#fd7e14';
            } else {
                charCount.style.color = '#6c757d';
            }
        }

        descriptionTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count

        // Live preview
        const nameInput = document.getElementById('name');
        const isActiveInput = document.getElementById('is_active');
        const previewCard = document.getElementById('previewCard');
        const previewName = document.getElementById('previewName');
        const previewDescription = document.getElementById('previewDescription');
        const previewStatus = document.getElementById('previewStatus');

        function updatePreview() {
            const name = nameInput.value.trim();
            const description = descriptionTextarea.value.trim();
            const isActive = isActiveInput.checked;

            if (name) {
                previewCard.style.display = 'block';
                previewName.textContent = name;
                previewDescription.textContent = description || 'Tidak ada deskripsi';
                previewStatus.textContent = isActive ? 'Aktif' : 'Nonaktif';
                previewStatus.className = `badge bg-${isActive ? 'success' : 'secondary'}`;
            } else {
                previewCard.style.display = 'none';
            }
        }

        nameInput.addEventListener('input', updatePreview);
        descriptionTextarea.addEventListener('input', updatePreview);
        isActiveInput.addEventListener('change', updatePreview);

        // Form validation dan submit handling
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            const name = nameInput.value.trim();
            const submitBtn = document.getElementById('submitBtn');

            // Validasi nama kategori
            if (name.length < 2) {
                e.preventDefault();
                alert('Nama kategori harus minimal 2 karakter');
                nameInput.focus();
                return false;
            }

            if (name.length > 255) {
                e.preventDefault();
                alert('Nama kategori maksimal 255 karakter');
                nameInput.focus();
                return false;
            }

            // Tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-spinner fa-spin"></i> Menyimpan...';

            // Log data yang akan dikirim untuk debugging
            console.log('Form data yang akan dikirim:', {
                name: name,
                description: descriptionTextarea.value,
                is_active: isActiveInput.checked
            });

            // Re-enable button setelah 10 detik sebagai fallback
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Simpan Kategori';
            }, 10000);
        });

        // Reset form handler
        document.getElementById('resetBtn').addEventListener('click', function() {
            setTimeout(function() {
                updateCharCount();
                updatePreview();
            }, 100);
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
