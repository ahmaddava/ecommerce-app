@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Kelola Pesanan</a></li>
            <li class="breadcrumb-item active">Detail: #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-gradient mb-0">
            <i class="bi bi-receipt"></i> Detail Pesanan
        </h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pesanan #{{ $order->order_number }}</h5>
                    <span class="badge bg-primary rounded-pill">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <h6 class="card-title">Item yang Dipesan</h6>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Produk</th>
                                <th scope="col" class="text-center">Kuantitas</th>
                                <th scope="col" class="text-end">Harga</th>
                                <th scope="col" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end border-top"><strong>Subtotal</strong></td>
                                <td class="text-end border-top">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Ongkos Kirim</strong></td>
                                <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5"><strong>Total</strong></td>
                                <td class="text-end fw-bold fs-5"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Informasi Pelanggan
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p class="mb-3"><strong>Telepon:</strong> {{ $order->customer_phone }}</p>

                    <hr>

                    <h6 class="mt-3"><strong><i class="bi bi-truck"></i> Alamat Pengiriman</strong></h6>
                    <p class="mb-0">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection