@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="text-gradient">
                    <i class="bi bi-tags"></i> Kelola Kategori
                </h1>
                <p class="text-muted">Manajemen kategori produk</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
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

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.categories.index') }}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="search" class="form-label">Cari Kategori</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        value="{{ request('search') }}" placeholder="Nama kategori...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
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

        <!-- Categories Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list"></i> Daftar Kategori
                            </h5>
                            <span class="badge bg-primary">{{ $categories->total() }} kategori</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($categories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah Produk</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                                        <small class="text-muted">ID: {{ $category->id }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        {{ $category->description ? Str::limit($category->description, 50) : 'Tidak ada deskripsi' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $category->products_count ?? $category->products()->count() }}
                                                        produk
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $category->created_at->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.categories.show', $category->id) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                            class="btn btn-outline-warning btn-sm" title="Edit Kategori">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.categories.toggle-status', $category->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                class="btn btn-outline-{{ $category->is_active ? 'secondary' : 'success' }} btn-sm"
                                                                title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                                onclick="return confirm('Yakin ingin {{ $category->is_active ? 'menonaktifkan' : 'mengaktifkan' }} kategori ini?')">
                                                                <i
                                                                    class="bi bi-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                                                            </button>
                                                        </form>
                                                        @if ($category->products()->count() == 0)
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                title="Hapus Kategori"
                                                                onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-outline-danger btn-sm"
                                                                title="Tidak dapat dihapus (masih ada {{ $category->products()->count() }} produk)"
                                                                disabled>
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if ($categories->hasPages())
                                <div class="card-footer bg-white border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }}
                                            dari {{ $categories->total() }} kategori
                                        </div>
                                        <div class="pagination-wrapper">
                                            {{ $categories->appends(request()->query())->links('custom.pagination') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-tags text-muted" style="font-size: 4rem;"></i>
                                <h4 class="text-muted mt-3">Belum Ada Kategori</h4>
                                <p class="text-muted">Mulai tambahkan kategori pertama Anda</p>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus kategori <strong id="categoryName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle"></i>
                        <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pagination-wrapper .pagination {
            margin-bottom: 0;
        }

        .pagination-wrapper .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: #6c757d;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            opacity: 0.5;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
        }

        .btn-group .btn:not(:last-child) {
            margin-right: 0.25rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto submit form when status changes
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Confirm delete function
        function confirmDelete(categoryId, categoryName) {
            document.getElementById('categoryName').textContent = categoryName;
            document.getElementById('deleteForm').action = `/admin/categories/${categoryId}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Add loading state to buttons
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-spinner fa-spin"></i> Memproses...';

                    // Re-enable after 3 seconds as fallback
                    setTimeout(function() {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 3000);
                }
            });
        });
    </script>
@endpush
