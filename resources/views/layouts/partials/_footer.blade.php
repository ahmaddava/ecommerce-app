<footer class="bg-light pt-5 pb-4">
    <div class="container text-md-start">
        <div class="row">

            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold text-gradient mb-4">
                    <i class="bi bi-shop"></i> {{ config('app.name', 'E-Commerce') }}
                </h5>
                <p class="text-muted">
                    Toko online terpercaya dengan berbagai pilihan produk berkualitas dan pelayanan terbaik untuk
                    kepuasan pelanggan.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-muted"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-youtube fs-4"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-4">Navigasi</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a>
                    </li>
                    <li class="mb-2"><a href="{{ route('products.index') }}"
                            class="text-muted text-decoration-none">Produk</a></li>
                    <li class="mb-2"><a href="{{-- route('pages.about') --}}" class="text-muted text-decoration-none">Tentang
                            Kami</a></li>
                    <li class="mb-2"><a href="{{-- route('pages.contact') --}}"
                            class="text-muted text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-4">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Cara Berbelanja</a>
                    </li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Kebijakan Privasi</a>
                    </li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Syarat & Ketentuan</a>
                    </li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-4">Kontak Kami</h5>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i> Jl. Raya Gatot Soebroto, Kabupaten
                        Tangerang, Banten 15520</li>
                    <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i> support@unique.com</li>
                    <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i> +62 851-7310-2302</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center text-muted py-3" style="background-color: rgba(0, 0, 0, 0.05);">
        &copy; {{ date('Y') }} {{ config('app.name', 'E-Commerce') }}. All Rights Reserved.
    </div>
</footer>
