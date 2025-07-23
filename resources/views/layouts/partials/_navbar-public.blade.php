<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand text-gradient" href="{{ route('home') }}">
            <i class="bi bi-shop"></i> {{ config('app.name', 'E-Commerce') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}"><i class="bi bi-grid"></i> Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pages.about') }}"><i class="bi bi-info-circle"></i> Tentang
                        Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pages.contact') }}"><i class="bi bi-envelope"></i> Kontak</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i>
                                {{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i>
                                {{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    {{-- Cart Icon (for customers only) --}}
                    @if (Auth::user()->role == 'customer')
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span class="cart-badge" id="cart-count">0</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            {{-- Menu untuk Customer --}}
                            @if (Auth::user()->role == 'customer')
                                <a class="dropdown-item" href="{{-- route('customer.dashboard') --}}"><i class="bi bi-speedometer2"></i>
                                    Dashboard</a>
                                <a class="dropdown-item" href="{{ route('customer.orders.index') }}"><i
                                        class="bi bi-receipt"></i> Pesanan Saya</a>
                                <a class="dropdown-item" href="{{ route('customer.profile.index') }}"><i
                                        class="bi bi-person-fill"></i> Profil Saya</a>
                            @endif

                            {{-- Menu untuk Admin --}}
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i>
                                    Admin Panel</a>
                            @endif

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
