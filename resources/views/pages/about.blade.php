@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content fade-in">
                        <h1 class="hero-title">Tentang {{ config('app.name') }}</h1>
                        <p class="hero-subtitle">Toko online terpercaya yang menghadirkan produk berkualitas dengan pelayanan
                            terbaik untuk kepuasan pelanggan.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center slide-in-right">
                        <i class="bi bi-people" style="font-size: 15rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="text-center mb-5">
                        <h2 class="text-gradient">Cerita Kami</h2>
                        <p class="text-muted">Perjalanan membangun toko online terpercaya</p>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <p class="lead">
                                {{ config('app.name') }} didirikan dengan visi untuk menjadi platform e-commerce terdepan
                                yang menghadirkan pengalaman berbelanja online yang mudah, aman, dan menyenangkan bagi
                                seluruh masyarakat Indonesia.
                            </p>

                            <p>
                                Sejak awal berdiri, kami berkomitmen untuk menyediakan produk-produk berkualitas tinggi
                                dengan harga yang kompetitif. Tim kami yang berpengalaman bekerja keras untuk memastikan
                                setiap produk yang kami jual telah melalui proses seleksi yang ketat dan quality control
                                yang baik.
                            </p>

                            <p>
                                Kepuasan pelanggan adalah prioritas utama kami. Oleh karena itu, kami terus berinovasi dalam
                                memberikan pelayanan terbaik, mulai dari kemudahan berbelanja, proses pembayaran yang aman,
                                hingga pengiriman yang cepat dan terpercaya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="text-gradient">Visi & Misi</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-eye fs-2"></i>
                            </div>
                            <h4 class="text-primary">Visi</h4>
                            <p class="text-muted">
                                Menjadi platform e-commerce terdepan di Indonesia yang menghadirkan pengalaman berbelanja
                                online terbaik dengan produk berkualitas dan pelayanan yang memuaskan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-target fs-2"></i>
                            </div>
                            <h4 class="text-secondary">Misi</h4>
                            <p class="text-muted">
                                Menyediakan produk berkualitas dengan harga terjangkau, memberikan pelayanan pelanggan yang
                                excellent, dan membangun kepercayaan melalui transparansi dan integritas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="text-gradient">Nilai-Nilai Kami</h2>
                    <p class="text-muted">Prinsip yang menjadi fondasi dalam setiap langkah kami</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <h5>Kepercayaan</h5>
                        <p class="text-muted small">Membangun kepercayaan melalui transparansi dan konsistensi dalam setiap
                            transaksi</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="bi bi-star fs-3"></i>
                        </div>
                        <h5>Kualitas</h5>
                        <p class="text-muted small">Mengutamakan kualitas produk dan layanan untuk kepuasan pelanggan</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="bi bi-lightning fs-3"></i>
                        </div>
                        <h5>Inovasi</h5>
                        <p class="text-muted small">Terus berinovasi untuk memberikan pengalaman berbelanja yang lebih baik
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="bi bi-heart fs-3"></i>
                        </div>
                        <h5>Pelayanan</h5>
                        <p class="text-muted small">Memberikan pelayanan terbaik dengan sepenuh hati kepada setiap pelanggan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    {{-- <section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-gradient">Tim Kami</h2>
                <p class="text-muted">Orang-orang hebat di balik {{ config('app.name') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-2"></i>
                        </div>
                        <h5>John Doe</h5>
                        <p class="text-muted small">CEO & Founder</p>
                        <p class="small">Memimpin visi dan strategi perusahaan dengan pengalaman 10+ tahun di industri e-commerce.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-2"></i>
                        </div>
                        <h5>Jane Smith</h5>
                        <p class="text-muted small">CTO</p>
                        <p class="small">Bertanggung jawab atas pengembangan teknologi dan inovasi platform.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-2"></i>
                        </div>
                        <h5>Mike Johnson</h5>
                        <p class="text-muted small">Head of Operations</p>
                        <p class="small">Mengawasi operasional harian dan memastikan kelancaran proses bisnis.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-2"></i>
                        </div>
                        <h5>Sarah Wilson</h5>
                        <p class="text-muted small">Head of Customer Service</p>
                        <p class="small">Memastikan kepuasan pelanggan melalui layanan customer service yang excellent.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

    <!-- Statistics -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <h2 class="text-primary display-4 fw-bold">10K+</h2>
                            <p class="text-muted">Pelanggan Puas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <h2 class="text-secondary display-4 fw-bold">5K+</h2>
                            <p class="text-muted">Produk Tersedia</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <h2 class="text-success display-4 fw-bold">50+</h2>
                            <p class="text-muted">Kota Terjangkau</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <h2 class="text-warning display-4 fw-bold">24/7</h2>
                            <p class="text-muted">Customer Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3>Siap Bergabung dengan Kami?</h3>
                    <p class="mb-0">Mulai pengalaman berbelanja online yang menyenangkan bersama {{ config('app.name') }}
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-grid"></i> Mulai Belanja
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
