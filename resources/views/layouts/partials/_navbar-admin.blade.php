<!-- Admin Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-shield-check"></i> {{ config('app.name') }} - Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#adminNavbarSupportedContent" aria-controls="adminNavbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                </li>

                {{-- PERUBAHAN DI SINI: Dropdown dihilangkan --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}"
                        href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i> Kelola Produk</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}"
                        href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i> Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/messages*') ? 'active' : '' }}"
                        href="{{ route('admin.messages.index') }}"><i class="bi bi-inbox"></i> Pesan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}"
                        href="{{ route('admin.reports.sales') }}"><i class="bi bi-graph-up"></i> Laporan</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-eye"></i> Lihat
                        Situs</a>
                </li>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
