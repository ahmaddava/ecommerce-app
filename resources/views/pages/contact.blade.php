@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
    <!-- Page Header -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kontak</li>
                        </ol>
                    </nav>
                    <h1 class="text-gradient">Hubungi Kami</h1>
                    <p class="text-muted">Kami siap membantu Anda dengan pertanyaan atau masukan apapun</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-geo-alt fs-3"></i>
                            </div>
                            <h5>Alamat Kantor</h5>
                            <p class="text-muted mb-0">
                                Jl. Sudirman No. 123<br>
                                Jakarta Pusat 10220<br>
                                Indonesia
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-telephone fs-3"></i>
                            </div>
                            <h5>Telepon</h5>
                            <p class="text-muted mb-0">
                                <strong>Customer Service:</strong><br>
                                +62 851-7310-2302<br>
                                <strong>WhatsApp:</strong><br>
                                +62 812 3456 7890
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-envelope fs-3"></i>
                            </div>
                            <h5>Email</h5>
                            <p class="text-muted mb-0">
                                <strong>General:</strong><br>
                                info@ecommerce.com<br>
                                <strong>Support:</strong><br>
                                support@ecommerce.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">
                                <i class="bi bi-envelope"></i> Kirim Pesan
                            </h4>
                            <p class="text-muted mb-0">Isi form di bawah ini dan kami akan segera merespon pesan Anda</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('pages.contact.submit') }}" method="POST" id="contact-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="subject" class="form-label">Subjek <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('subject') is-invalid @enderror" id="subject"
                                            name="subject" required>
                                            <option value="">Pilih Subjek</option>
                                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>
                                                Pertanyaan Umum</option>
                                            <option value="order" {{ old('subject') == 'order' ? 'selected' : '' }}>Masalah
                                                Pesanan</option>
                                            <option value="product" {{ old('subject') == 'product' ? 'selected' : '' }}>
                                                Informasi Produk</option>
                                            <option value="payment" {{ old('subject') == 'payment' ? 'selected' : '' }}>
                                                Masalah Pembayaran</option>
                                            <option value="shipping" {{ old('subject') == 'shipping' ? 'selected' : '' }}>
                                                Pengiriman</option>
                                            <option value="complaint"
                                                {{ old('subject') == 'complaint' ? 'selected' : '' }}>Keluhan</option>
                                            <option value="suggestion"
                                                {{ old('subject') == 'suggestion' ? 'selected' : '' }}>Saran</option>
                                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>
                                                Lainnya</option>
                                        </select>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"
                                        placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="privacy" name="privacy"
                                            required>
                                        <label class="form-check-label" for="privacy">
                                            Saya setuju dengan <a href="#" class="text-primary">kebijakan
                                                privasi</a> dan
                                            <a href="#" class="text-primary">syarat & ketentuan</a> <span
                                                class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send"></i> Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Business Hours & Additional Info -->
                <div class="col-lg-4">
                    <!-- Business Hours -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock"></i> Jam Operasional
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Senin - Jumat</span>
                                <span class="fw-bold">09:00 - 17:00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sabtu</span>
                                <span class="fw-bold">09:00 - 15:00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Minggu</span>
                                <span class="text-muted">Tutup</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Customer Service</span>
                                <span class="fw-bold text-success">24/7</span>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-question-circle"></i> FAQ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item border-0">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq1">
                                            Bagaimana cara melakukan pemesanan?
                                        </button>
                                    </h6>
                                    <div id="faq1" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body small">
                                            Pilih produk yang diinginkan, tambahkan ke keranjang, lalu lanjutkan ke checkout
                                            untuk menyelesaikan pembayaran.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2">
                                            Berapa lama waktu pengiriman?
                                        </button>
                                    </h6>
                                    <div id="faq2" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body small">
                                            Waktu pengiriman bervariasi 1-7 hari kerja tergantung lokasi dan jenis
                                            pengiriman yang dipilih.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h6 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq3">
                                            Apakah bisa retur produk?
                                        </button>
                                    </h6>
                                    <div id="faq3" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body small">
                                            Ya, kami menerima retur dalam 7 hari setelah produk diterima dengan syarat dan
                                            ketentuan yang berlaku.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-share"></i> Ikuti Kami
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="#" class="btn btn-outline-dark btn-sm">
                                    <i class="bi bi-youtube"></i>
                                </a>
                            </div>
                            <p class="small text-muted mt-3 mb-0">
                                Dapatkan update terbaru tentang produk dan promo menarik dari kami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">
                                <i class="bi bi-map"></i> Lokasi Kami
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <!-- Placeholder for Google Maps -->
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                <div class="text-center">
                                    <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Google Maps akan ditampilkan di sini</p>
                                    <small class="text-muted">Jl. Sudirman No. 123, Jakarta Pusat 10220</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const formData = new FormData(form);

            // Hapus alert lama jika ada
            const oldAlert = form.querySelector('.alert');
            if (oldAlert) {
                oldAlert.remove();
            }

            // Tampilkan loading state
            submitBtn.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...`;
            submitBtn.disabled = true;

            fetch("{{ route('pages.contact.submit') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    let alertClass = data.success ? 'alert-success' : 'alert-danger';
                    let alertMessage = data.success ? data.message :
                        'Terjadi kesalahan. Silakan periksa kembali isian Anda.';

                    if (!data.success && data.errors) {
                        // Tampilkan error validasi
                        let errorList = '<ul>';
                        for (const key in data.errors) {
                            errorList += `<li>${data.errors[key][0]}</li>`;
                        }
                        errorList += '</ul>';
                        alertMessage = errorList;
                    }

                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
                    alertDiv.innerHTML = `
                ${alertMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

                    form.insertBefore(alertDiv, form.firstChild);

                    if (data.success) {
                        form.reset(); // Reset form jika berhasil
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                Terjadi kesalahan teknis. Silakan coba lagi nanti.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                    form.insertBefore(alertDiv, form.firstChild);
                })
                .finally(() => {
                    // Kembalikan tombol ke state semula
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });
    </script>
@endpush
