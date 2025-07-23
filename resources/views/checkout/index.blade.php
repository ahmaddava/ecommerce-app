@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>

        <h1 class="mb-4 text-gradient"><i class="bi bi-credit-card"></i> Checkout</h1>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Lengkap <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                    required>{{ old('address', Auth::user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city', Auth::user()->city) }}"
                                        required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="postal_code" class="form-label">Kode Pos <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                        id="postal_code" name="postal_code"
                                        value="{{ old('postal_code', Auth::user()->postal_code) }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Nomor Telepon <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                    id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-wallet2"></i> Metode Pembayaran</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paymentBNI"
                                            value="bni" {{ old('payment_method') == 'bni' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="paymentBNI">
                                            <img src="{{ asset('images/bni.png') }}" alt="BNI" height="25"
                                                class="me-2"> Bank Transfer BNI
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="paymentQRIS" value="qris"
                                            {{ old('payment_method') == 'qris' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="paymentQRIS">
                                            <img src="{{ asset('images/qris.png') }}" alt="QRIS" height="25"
                                                class="me-2"> QRIS
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paymentBCA"
                                            value="bca" {{ old('payment_method') == 'bca' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="paymentBCA">
                                            <img src="{{ asset('images/bca.png') }}" alt="BCA" height="25"
                                                class="me-2"> Bank Transfer BCA
                                        </label>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100"><i
                                    class="bi bi-check-circle"></i> Proses Pesanan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-orange text-white">
                        <h5 class="mb-0"><i class="bi bi-cart"></i> Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="my-0">{{ $item->product->name }}</h6>
                                        <small class="text-muted">Jumlah: {{ $item->quantity }}</small>
                                    </div>
                                    <span class="text-muted">Rp
                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Subtotal
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Biaya Pengiriman
                                <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center fw-bold text-gradient-dark">
                                Total Pembayaran
                                <h4>Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
