{{-- resources/views/partials/_product-card.blade.php --}}
{{-- Variabel $product akan dikirim dari halaman yang memanggil @include ini --}}

<div class="card product-card h-100 border-0 shadow-sm">
    <div class="position-relative">
        <a href="{{ route('products.show', $product->id) }}">
            @if ($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}"
                    style="height: 200px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                    style="height: 200px;">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
            @endif
        </a>

        @if ($product->stock > 0 && $product->stock <= 10)
            <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                Stok Terbatas
            </span>
        @elseif($product->stock == 0)
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                Stok Habis
            </span>
        @endif
    </div>

    <div class="card-body d-flex flex-column">
        <h6 class="card-title">
            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark stretched-link">
                {{ Str::limit($product->name, 45) }}
            </a>
        </h6>
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <span class="product-price fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
        </div>
    </div>
</div>
