@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Pelanggan</li>
            </ol>
        </nav>

        <h1 class="mb-4 text-gradient"><i class="bi bi-speedometer2"></i> Dashboard Pelanggan</h1>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="{{ asset('images/user-placeholder.png') }}" alt="User Avatar" class="rounded-circle mb-3"
                            width="100">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="card-text text-muted">{{ $user->email }}</p>
                        <a href="{{ route('customer.profile.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-circle"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Pesanan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @if ($orders->isEmpty())
                            <p class="text-center text-muted">Anda belum memiliki pesanan.</p>
                            <div class="d-grid">
                                <a href="{{ route('products.index') }}" class="btn btn-primary">
                                    <i class="bi bi-bag-plus"></i> Mulai Belanja
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nomor Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                <td><span
                                                        class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'info') }}">{{ ucfirst($order->status) }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $order->id) }}"
                                                        class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                    Lihat Semua Pesanan <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-orange text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Akun</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Telepon:</strong> {{ $user->phone_number ?? '-' }}</p>
                        <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
                        <p><strong>Kota:</strong> {{ $user->city ?? '-' }}</p>
                        <p><strong>Kode Pos:</strong> {{ $user->postal_code ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-question-circle"></i> Bantuan & Dukungan</h5>
                    </div>
                    <div class="card-body">
                        <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim
                            dukungan kami.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-envelope"></i> Email: info@ecommerce.com</li>
                            <li><i class="bi bi-telephone"></i> Telepon: +62 851-7310-2302</li>
                        </ul>
                        <a href="{{ route('pages.contact') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-chat-dots"></i> Unique Collection
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
