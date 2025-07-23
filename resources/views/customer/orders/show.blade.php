@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-gradient mb-0"><i class="bi bi-receipt-cutoff"></i> Detail Pesanan</h1>
        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                        <span>Total Pembayaran</span>
                        <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nomor Pesanan:</strong><br>#{{ $order->order_number }}</p>
                    <p><strong>Tanggal:</strong><br>{{ $order->created_at->format('d F Y, H:i') }}</p>
                    <p><strong>Status Pembayaran:</strong><br><span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                    <p><strong>Status Pesanan:</strong><br><span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                    <hr>
                    <h6><strong>Alamat Pengiriman:</strong></h6>
                    <p>{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection