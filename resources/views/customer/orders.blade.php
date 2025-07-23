@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
        </ol>
    </nav>

    <h1 class="mb-4 text-gradient"><i class="bi bi-receipt"></i> Pesanan Saya</h1>

    @if($orders->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h3 class="mt-3 text-muted">Belum Ada Pesanan</h3>
                <p class="text-muted">Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-bag-plus"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status Pesanan</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <strong class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Diproses</span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-primary">Dikirim</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

