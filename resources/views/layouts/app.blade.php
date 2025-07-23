<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>


    <meta charset="utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">


    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Toko Online Terpercaya')</title>




    <link rel="dns-prefetch" href="//fonts.bunny.net">


    <link href="https://fonts.bunny.net/css?family=Nunito:300,400,600,700" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">



    @vite(['resources/sass/app.scss', 'resources/js/app.js'])




    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">



    @stack('styles')

</head>



<body>

    <div id="app">



        {{-- Logika untuk menampilkan Navbar berdasarkan Role dan URL --}}

        @auth

            {{-- Jika user adalah ADMIN dan sedang berada di URL admin, tampilkan navbar admin --}}

            @if (Auth::user()->role == 'admin' && Request::is('admin/*'))
                @include('layouts.partials._navbar-admin')
            @else
                {{-- Jika tidak, tampilkan navbar publik (untuk customer yang login atau admin di halaman publik) --}}

                @include('layouts.partials._navbar-public')
            @endif
        @else
            {{-- Jika user adalah GUEST (belum login), tampilkan navbar publik --}}

            @include('layouts.partials._navbar-public')

        @endauth



        <main class="py-4">

            {{-- Logika untuk menampilkan Quick Actions hanya di halaman admin --}}

            @auth

                @if (Auth::user()->role == 'admin' && Request::is('admin/*') && !Request::is('admin/dashboard'))
                    <div class="container">

                        @include('admin.partials._quick-actions')

                    </div>
                @endif

            @endauth



            {{-- Menampilkan Flash Messages --}}

            @include('layouts.partials._flash-messages')



            @yield('content')

        </main>



        {{-- Footer (opsional, bisa dipisah juga) --}}

        @include('layouts.partials._footer')

    </div>



    @stack('scripts')

    <script>
        // Fungsi untuk mengupdate angka di ikon keranjang
        function updateCartCount() {
            const cartCountElement = document.getElementById('cart-count');
            if (!cartCountElement) return;

            @auth
            // Hanya jalankan jika user adalah customer
            @if (Auth::user() && Auth::user()->role == 'customer')
                fetch('{{ route('cart.count') }}')
                    .then(response => {
                        if (!response.ok) {
                            // Jika terjadi error seperti 404 atau 500, jangan tampilkan angka
                            cartCountElement.style.display = 'none';
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        cartCountElement.textContent = data.count;
                        // Tampilkan badge jika ada item, sembunyikan jika 0
                        cartCountElement.style.display = data.count > 0 ? 'flex' : 'none';
                    })
                    .catch(error => {
                        console.error('Error fetching cart count:', error);
                    });
            @endif
        @endauth
        }

        // Panggil fungsi ini saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // Buat listener agar bisa di-trigger dari tempat lain (misal setelah add to cart)
        window.addEventListener('cartUpdated', function() {
            updateCartCount();
        });
    </script>
</body>



</html>
