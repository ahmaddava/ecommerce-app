@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h1 class="text-success mb-3">Pesanan Berhasil!</h1>
                        <p class="lead mb-4">Terima kasih atas pesanan Anda. Pesanan Anda telah berhasil dibuat dan sedang
                            diproses.</p>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Detail Pesanan</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
                                        <p><strong>Total Pembayaran:</strong> Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> <span
                                                class="badge bg-warning">{{ ucfirst($order->status) }}</span></p>
                                        <p><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($order->payment_reference)
                            <div class="alert alert-info">
                                <h6><i class="bi bi-credit-card"></i> Lanjutkan Pembayaran</h6>
                                <p>Klik tombol di bawah untuk melanjutkan pembayaran melalui Midtrans:</p>
                                <button id="pay-button" class="btn btn-success btn-lg">
                                    <i class="bi bi-credit-card"></i> Bayar Sekarang
                                </button>
                            </div>
                        @else
                            @if ($order->payment_method == 'qris')
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-qr-code"></i> Pembayaran QRIS</h6>
                                    <p>Pilih provider QRIS untuk melakukan pembayaran:</p>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-3">
                                        <form action="{{ route('qris.generate', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="acquirer" value="gopay">
                                            <button type="submit" class="btn btn-success me-md-2">
                                                <i class="bi bi-qr-code"></i> Bayar dengan GoPay QRIS
                                            </button>
                                        </form>
                                        <form action="{{ route('qris.generate', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="acquirer" value="airpay_shopee">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-qr-code"></i> Bayar dengan ShopeePay QRIS
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif ($order->payment_method == 'bni')
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> Instruksi Pembayaran BNI</h6>
                                    <p>Silakan transfer ke rekening BNI: <strong>1234567890</strong> a.n. Unique Collection
                                    </p>
                                    <p>Jangan lupa sertakan nomor pesanan <strong>{{ $order->order_number }}</strong>
                                        sebagai berita transfer.</p>
                                </div>
                            @elseif($order->payment_method == 'bca')
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> Instruksi Pembayaran BCA</h6>
                                    <p>Silakan transfer ke rekening BCA: <strong>0987654321</strong> a.n. Unique Collection
                                    </p>
                                    <p>Jangan lupa sertakan nomor pesanan <strong>{{ $order->order_number }}</strong>
                                        sebagai berita transfer.</p>
                                </div>
                            @endif
                        @endif

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary me-md-2">
                                <i class="bi bi-speedometer2"></i> Lihat Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($order->payment_reference)
        <!-- Midtrans Snap JS -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function() {
                // SnapToken acquired from previous step
                snap.pay('{{ $order->payment_reference }}', {
                    // Optional
                    onSuccess: function(result) {
                        /* You may add your own js here, this is just example */
                        alert("Pembayaran berhasil!");
                        console.log(result);
                        window.location.href = "{{ route('customer.dashboard') }}";
                    },
                    // Optional
                    onPending: function(result) {
                        /* You may add your own js here, this is just example */
                        alert("Menunggu pembayaran!");
                        console.log(result);
                    },
                    // Optional
                    onError: function(result) {
                        /* You may add your own js here, this is just example */
                        alert("Pembayaran gagal!");
                        console.log(result);
                    }
                });
            };
        </script>
    @endif
@endsection