<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome CDN (required) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Sidebar styling */
        #sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            border-right: 1px solid #ddd;
            padding-top: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        #sidebar .nav-link {
            margin: 0.25rem 0;
        }

        /* Perbesar teks DiMarket */
        #sidebar .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Konten utama di sebelah kanan sidebar */
        #main-content {
            margin-left: 220px;
            padding: 1.5rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Footer fixed di bawah sidebar */
        #sidebar-footer {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #666;
            border-top: 1px solid #ddd;
            text-align: center;
        }

        /* User dropdown di sidebar */
        #userDropdown {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav id="sidebar">
            <div>
                <div class="px-3 mb-3">
                    <a href="{{ url('/') }}" class="navbar-brand">{{ config('app.name', 'Laravel') }}</a>
                </div>
                <!-- Contoh di dalam <nav id="sidebar"> -->
                <a href="{{ route('home') }}" wire:navigate
                    class="btn mt-1 py-2 px-3 w-100 text-start border-0 {{ request()->routeIs('home') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <li class="fas fa-home"></li>
                    Beranda
                </a>

                <a href="{{ route('user') }}" wire:navigate
                    class="btn mt-1 py-2 px-3 w-100 text-start border-0 {{ request()->routeIs('user') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <li class="fas fa-user"></li>
                    Pengguna
                </a>

                <a href="{{ route('produk') }}" wire:navigate
                    class="btn mt-1 py-2 px-3 w-100 text-start border-0 {{ request()->routeIs('produk') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <li class="fas fa-shopping-cart"></li>
                    Produk
                </a>

                <a href="{{ route('transaksi') }}" wire:navigate
                    class="btn mt-1 py-2 px-3 w-100 text-start border-0 {{ request()->routeIs('transaksi') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <li class="fas fa-money-bill-wave"></li>
                    Transaksi
                </a>

                <a href="{{ route('laporan') }}" wire:navigate
                    class="btn mt-1 py-2 px-3 w-100 text-start border-0 {{ request()->routeIs('laporan') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <li class="fas fa-book"></li>
                    Laporan
                </a>
            </div>
            <div>
                @guest
                    <div class="px-3">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-1 mb-2">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mt-1">Register</a>
                        @endif
                    </div>
                @else
                    <div class="dropdown px-3 mb-3">
                        <a id="userDropdown" class="btn btn-outline-primary w-100 dropdown-toggle mt-1" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endguest

                <div id="sidebar-footer">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                </div>
            </div>
        </nav>

        <main id="main-content">
            {{ $slot }}
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
