@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
                    </ol>
                </nav>
                <h1 class="text-gradient"><i class="bi bi-cart3"></i> Keranjang Belanja</h1>
            </div>
        </div>

        <div id="cart-container">
            @if ($cartItems->isNotEmpty())
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Item dalam Keranjang (<span
                                        id="cart-item-count">{{ $count }}</span> produk)</h5>
                            </div>
                            <div class="card-body p-0">
                                @foreach ($cartItems as $item)
                                    <div class="cart-item border-bottom p-3" data-item-id="{{ $item->id }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-6 d-flex align-items-center">
                                                <img src="{{ asset($item->product->image ?? 'images/placeholder.png') }}"
                                                    class="img-fluid rounded me-3" alt="{{ $item->product->name }}"
                                                    style="height: 80px; width: 80px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('products.show', $item->product->id) }}"
                                                            class="text-decoration-none text-dark">{{ $item->product->name }}</a>
                                                    </h6>
                                                    <small class="text-muted">Rp
                                                        {{ number_format($item->product->price, 0, ',', '.') }}</small>
                                                    @if ($item->size)
                                                        <br><small class="text-info"><i class="bi bi-rulers"></i> Ukuran:
                                                            {{ strtoupper($item->size) }}</small>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                    class="update-quantity-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm">
                                                        <input type="number"
                                                            class="form-control text-center quantity-input" name="quantity"
                                                            value="{{ $item->quantity }}" min="1"
                                                            max="{{ $item->product->stock }}">
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-md-3 d-flex justify-content-end align-items-center">
                                                <div class="fw-bold me-3 item-subtotal">
                                                    {{-- PERBAIKAN: Hitung manual, bukan dari $item->total --}}
                                                    Rp
                                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                                </div>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                    class="remove-item-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0"
                                                        title="Hapus item">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Lanjut Belanja
                                </a>
                                <form action="{{ route('cart.clear') }}" method="POST" class="clear-cart-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash2"></i> Kosongkan Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Ringkasan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal (<span id="cart-summary-count">{{ $count }}</span> item)</span>
                                    {{-- PERBAIKAN: $total menjadi $subtotal --}}
                                    <span id="cart-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkos Kirim</span>
                                    <span class="text-muted">Dihitung di checkout</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <strong class="fs-5">Total</strong>
                                    {{-- PERBAIKAN: $total menjadi $subtotal --}}
                                    <strong id="cart-total" class="text-primary fs-5">Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</strong>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-credit-card"></i> Lanjut ke Checkout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @include('cart.partials._empty')
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let debounceTimer;

            // Fungsi utama untuk mengirim request AJAX ke controller
            async function handleCartAction(form, method) {
                try {
                    const response = await fetch(form.action, {
                        method: 'POST', // Form method spoofing handles PUT/DELETE
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan.');
                    }

                    if (data.success) {
                        updateCartUI(data.cart);
                    }
                } catch (error) {
                    console.error('Cart Action Error:', error);
                    alert(error.message);
                }
            }

            // Fungsi untuk mengupdate semua bagian UI keranjang
            function updateCartUI(cartData) {
                if (cartData.count === 0) {
                    // Jika keranjang kosong, muat ulang halaman untuk menampilkan pesan "keranjang kosong"
                    window.location.reload();
                    return;
                }

                // Update ringkasan pesanan
                document.getElementById('cart-item-count').textContent = cartData.count;
                document.getElementById('cart-summary-count').textContent = cartData.count;
                const formattedSubtotal = 'Rp ' + cartData.subtotal.toLocaleString('id-ID');
                document.getElementById('cart-subtotal').textContent = formattedSubtotal;
                document.getElementById('cart-total').textContent = formattedSubtotal;

                // Update subtotal per item dan quantity input
                cartData.cartItems.forEach(item => {
                    const cartItemEl = document.querySelector(`.cart-item[data-item-id="${item.id}"]`);
                    if (cartItemEl) {
                        // Update subtotal
                        const itemSubtotalEl = cartItemEl.querySelector('.item-subtotal');
                        if (itemSubtotalEl) {
                            itemSubtotalEl.textContent = 'Rp ' + (item.product.price * item.quantity)
                                .toLocaleString('id-ID');
                        }

                        // Update quantity input
                        const quantityInput = cartItemEl.querySelector('.quantity-input');
                        if (quantityInput && quantityInput.value != item.quantity) {
                            quantityInput.value = item.quantity;
                        }

                        // Update max attribute untuk quantity input
                        if (quantityInput && item.product.stock) {
                            quantityInput.setAttribute('max', item.product.stock);
                        }
                    }
                });

                // Update counter di navbar
                const navbarCartCount = document.getElementById('cart-count');
                if (navbarCartCount) {
                    navbarCartCount.textContent = cartData.count;
                    navbarCartCount.style.display = cartData.count > 0 ? 'block' : 'none';
                }
            }

            // --- Event Listeners ---
            document.getElementById('cart-container').addEventListener('click', function(e) {
                // Tombol +/- kuantitas
                if (e.target.closest('.quantity-btn')) {
                    const btn = e.target.closest('.quantity-btn');
                    const form = btn.closest('form');
                    const input = form.querySelector('.quantity-input');
                    let quantity = parseInt(input.value);
                    const maxStock = parseInt(input.getAttribute('max')) || 999;

                    if (btn.dataset.action === 'increase' && quantity < maxStock) {
                        input.value = quantity + 1;
                    } else if (btn.dataset.action === 'decrease' && quantity > 1) {
                        input.value = quantity - 1;
                    }
                    // Trigger change event untuk update via AJAX
                    input.dispatchEvent(new Event('change'));
                }
            });

            document.getElementById('cart-container').addEventListener('change', function(e) {
                // Input kuantitas
                if (e.target.classList.contains('quantity-input')) {
                    const input = e.target;
                    let quantity = parseInt(input.value);
                    const maxStock = parseInt(input.getAttribute('max')) || 999;
                    const minQuantity = parseInt(input.getAttribute('min')) || 1;

                    // Validasi input
                    if (isNaN(quantity) || quantity < minQuantity) {
                        input.value = minQuantity;
                        quantity = minQuantity;
                    } else if (quantity > maxStock) {
                        input.value = maxStock;
                        quantity = maxStock;
                        alert(`Stok maksimal untuk produk ini adalah ${maxStock}`);
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const form = input.closest('form');
                        handleCartAction(form, 'PUT');
                    }, 500); // Debounce 500ms
                }
            });

            document.getElementById('cart-container').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;

                // Form Hapus Item
                if (form.classList.contains('remove-item-form')) {
                    if (confirm('Yakin ingin menghapus item ini?')) {
                        // Hapus elemen dari DOM secara optimis
                        form.closest('.cart-item').remove();
                        handleCartAction(form, 'DELETE');
                    }
                }

                // Form Kosongkan Keranjang
                if (form.classList.contains('clear-cart-form')) {
                    if (confirm('Yakin ingin mengosongkan keranjang?')) {
                        handleCartAction(form, 'DELETE');
                    }
                }
            });
        });
    </script>
@endpush
