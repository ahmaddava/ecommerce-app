@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="text-gradient">
                    <i class="bi bi-receipt"></i> Kelola Pesanan
                </h1>
                <p class="text-muted">Manajemen pesanan dan status pembayaran</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel"></i> Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf"></i> PDF</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.orders.index') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="search" class="form-label">Cari Pesanan</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        value="{{ request('search') }}" placeholder="Nomor pesanan...">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Status Pesanan</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="processing"
                                            {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>
                                            Shipped</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="payment_status" class="form-label">Status Pembayaran</label>
                                    <select class="form-select" id="payment_status" name="payment_status">
                                        <option value="">Semua Status</option>
                                        <option value="pending"
                                            {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                            Paid</option>
                                        <option value="failed"
                                            {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded"
                                            {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
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

        <!-- Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list"></i> Daftar Pesanan
                            </h5>
                            <span class="badge bg-primary">{{ $orders->total() }} pesanan</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nomor Pesanan</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status Pesanan</th>
                                            <th>Status Pembayaran</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">#{{ $order->order_number }}</h6>
                                                        <small class="text-muted">ID: {{ $order->id }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $order->user->name }}</h6>
                                                        <small class="text-muted">{{ $order->user->email }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong>Rp
                                                        {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->status == 'pending'
                                                            ? 'warning'
                                                            : ($order->status == 'processing'
                                                                ? 'info'
                                                                : ($order->status == 'shipped'
                                                                    ? 'primary'
                                                                    : ($order->status == 'delivered'
                                                                        ? 'success'
                                                                        : 'danger'))) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->payment_status == 'pending'
                                                            ? 'warning'
                                                            : ($order->payment_status == 'paid'
                                                                ? 'success'
                                                                : ($order->payment_status == 'failed'
                                                                    ? 'danger'
                                                                    : 'secondary')) }}">
                                                        {{ ucfirst($order->payment_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <div class="btn-group" role="group">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                                data-bs-toggle="dropdown" title="Update Status">
                                                                <i class="bi bi-gear"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <h6 class="dropdown-header">Status Pesanan</h6>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updateOrderStatus({{ $order->id }}, 'pending')">Pending</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updateOrderStatus({{ $order->id }}, 'processing')">Processing</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updateOrderStatus({{ $order->id }}, 'shipped')">Shipped</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updateOrderStatus({{ $order->id }}, 'delivered')">Delivered</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">Cancelled</a>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <h6 class="dropdown-header">Status Pembayaran</h6>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updatePaymentStatus({{ $order->id }}, 'pending')">Pending</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updatePaymentStatus({{ $order->id }}, 'paid')">Paid</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updatePaymentStatus({{ $order->id }}, 'failed')">Failed</a>
                                                                </li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="updatePaymentStatus({{ $order->id }}, 'refunded')">Refunded</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if ($orders->hasPages())
                                <div class="card-footer bg-white border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }}
                                            dari {{ $orders->total() }} pesanan
                                        </div>
                                        <div class="pagination-wrapper">
                                            {{ $orders->appends(request()->query())->links('custom.pagination') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                                <h4 class="text-muted mt-3">Belum Ada Pesanan</h4>
                                <p class="text-muted">Pesanan akan muncul di sini setelah customer melakukan pembelian</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden forms for status updates -->
    <form id="updateOrderStatusForm" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>

    <form id="updatePaymentStatusForm" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>
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

        .pagination-wrapper .page-link svg {
            width: 16px;
            height: 16px;
        }

        /* Custom styles for better table appearance */
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
        // Auto submit form when filters change
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('payment_status').addEventListener('change', function() {
            this.form.submit();
        });

        // Update order status
        function updateOrderStatus(orderId, status) {
            if (confirm(`Yakin ingin mengubah status pesanan menjadi "${status}"?`)) {
                const form = document.getElementById('updateOrderStatusForm');
                form.action = `/admin/orders/${orderId}/status`;

                // Clear previous inputs
                const existingInputs = form.querySelectorAll('input[name="status"]');
                existingInputs.forEach(input => input.remove());

                // Add status input
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                form.appendChild(statusInput);

                form.submit();
            }
        }

        // Update payment status
        function updatePaymentStatus(orderId, status) {
            if (confirm(`Yakin ingin mengubah status pembayaran menjadi "${status}"?`)) {
                const form = document.getElementById('updatePaymentStatusForm');
                form.action = `/admin/orders/${orderId}/payment-status`;

                // Clear previous inputs
                const existingInputs = form.querySelectorAll('input[name="payment_status"]');
                existingInputs.forEach(input => input.remove());

                // Add status input
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'payment_status';
                statusInput.value = status;
                form.appendChild(statusInput);

                form.submit();
            }
        }
    </script>
@endpush
