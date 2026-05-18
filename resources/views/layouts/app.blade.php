
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- Bootstrap 5.3 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Tailwind --}}
    <script>
    tailwind.config = {
        safelist: ['bg-[#005f77]', 'hover:bg-[#014f66]']
    }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom Styles --}}
    <style>
        body {
            padding-top: 72px;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #fdfbfb, #ebedee);
        }

        .btn-icon-mini {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
        }
        .btn-icon-mini:hover {
            color: #343a40;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            height: 72px;
        }

        .navbar-brand {
            font-weight: 700;

            font-size: 1.5rem; /* atau 24px */
            color: #005f77 !important;
        }

        .navbar-nav .nav-link {
            color: #005f77 !important;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 999px; /* biar super bulat */
            transition: all 0.25s ease-in-out;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus,
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background-color: #005f77 !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* biar ada depth dikit */
            transform: scale(1.05); /* zoom dikit */
        }

        .navbar-nav {
            flex-wrap: wrap;
        }

        .navbar-nav .nav-link.dropdown-toggle:hover {
            color: #ffffff !important;
            background-color: rgba(0, 95, 119, 0.1);
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .navbar-nav .nav-link {
            color: #005f77 !important;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 999px;
            transition: all 0.25s ease-in-out;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus,
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background-color: #005f77 !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        .navbar-nav {
            flex-wrap: wrap;
        }

        .navbar-nav .nav-link.dropdown-toggle:hover {
            color: #ffffff !important;
            background-color: rgba(0, 95, 119, 0.1);
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        main.container {
            max-width: 1280px;
        }

        .content-card {
            background-color: transparent;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
        }
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        .dropdown-toggle::after {
            margin-left: 8px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top navbar-expand-lg px-4">
        <div class="container-fluid">

            {{-- Brand --}}
            <a class="navbar-brand" href="{{ Auth::check() && Auth::user()->role === 'admin' ? route('admin.dashboard') : route('orangtua.dashboard') }}">
                <img src="{{ asset('images/logo2.png') }}" alt="Stunting Logo" style="height:55px;">
            </a>

            {{-- NAVBAR MENU --}}
            @auth
            @php
                $role = Auth::user()->role;
            @endphp

            <div class="d-flex w-100 justify-content-between align-items-center">
                {{-- Menu Tengah --}}
                <ul class="navbar-nav d-flex flex-row flex-wrap gap-3 align-items-center mx-auto mb-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.dashboard') : route('orangtua.dashboard') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.detections.index') : route('orangtua.detections.create') }}">Deteksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.nutrition.index') : route('orangtua.nutritionUs.index') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        @if($role === 'orangtua')
                            <a class="nav-link" href="{{ route('bmi') }}">BMI</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.artikel.index') : route('orangtua.artikel.index') }}">Artikel</a>
                    </li>
                    {{-- Imunisasi feature removed --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.tahapan_perkembangan.index') : route('orangtua.tahapan_perkembangan.index') }}">Perkembangan</a>
                    </li>
                </ul>

                {{-- Dropdown Kanan --}}
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, {{ Auth::user()->nama_anak ?? 'Pengguna' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="container mt-4">
        <div class="content-card">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>