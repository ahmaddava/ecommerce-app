{{-- resources/views/cart/partials/_empty.blade.php --}}

<div class="row">
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-cart-x text-muted" style="font-size: 6rem;"></i>
            <h3 class="text-muted mt-4">Keranjang Belanja Anda Kosong</h3>
            <p class="text-muted">Sepertinya Anda belum menambahkan produk apa pun. Mari cari sesuatu yang menarik!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-shop"></i> Mulai Belanja
            </a>
        </div>
    </div>
</div>
