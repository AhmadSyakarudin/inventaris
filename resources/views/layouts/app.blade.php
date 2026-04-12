<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f8;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2b3fa0, #1e2d7a);
            color: white;
            flex-shrink: 0;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 24px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
        }

        .sidebar-label {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 20px 24px 6px;
            color: rgba(255, 255, 255, 0.45);
        }

        .sidebar a:not(.dropdown-item) {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 24px;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar a:not(.dropdown-item):hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .active-link {
            background: #3b52c4;
            border-left: 3px solid white;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .hero-header {
            height: 210px;
            background:
                linear-gradient(135deg,
                    rgba(43, 63, 160, 0.85),
                    rgba(59, 82, 196, 0.85)),
                url("{{ asset('assets/img/background.jpeg') }}") center/cover no-repeat;

            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 20px 28px;

        }

        .hero-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hero-logo-img {
            width: 42px;
        }

        .hero-welcome {
            color: white;
            font-weight: 600;
        }

        .hero-right {
            display: flex;
            align-items: center;
            gap: 18px;
            color: white;
        }

        .info-bar {
            margin: 15px 20px 0;
            padding: 10px 16px;
            background: #e9ecef;
            border-radius: 10px;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
        }

        .page-content {
            margin: 20px;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar-brand,
            .sidebar-label,
            .sidebar a span:not(i) {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex" id="app-container">

        @auth
            <div class="sidebar" id="sidebar">
                <div class="sidebar-brand">MENU</div>

                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-table-columns"></i> <span>Dashboard</span>
                </a>

                <div class="sidebar-label">Items Data</div>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('categories.index') }}"
                        class="{{ request()->routeIs('categories.*') ? 'active-link' : '' }}">
                        <i class="fa-solid fa-bars"></i> <span>Categories</span>
                    </a>
                @endif

                <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-earth-americas"></i> <span>Items</span>
                </a>

                @if (auth()->user()->role === 'staff')
                    <a href="{{ route('lendings.index') }}"
                        class="{{ request()->routeIs('lendings.*') ? 'active-link' : '' }}">
                        <i class="fa-solid fa-rotate"></i> <span>Lending</span>
                    </a>
                @endif

                <div class="sidebar-label">Accounts</div>

                @if (auth()->user()->role === 'admin')
                    <div class="dropdown">
                        <a class="dropdown-toggle {{ request()->routeIs('users.*') ? 'active-link' : '' }}" href="#"
                            data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i> <span>Users</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('users.index', ['role' => 'admin']) }}">Admin</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('users.index', ['role' => 'staff']) }}">Staff</a>
                            </li>
                        </ul>
                    </div>
                @endif

                @if (auth()->user()->role === 'staff')
                    <div class="dropdown">
                        <a class="dropdown-toggle {{ request()->routeIs('users.edit.self') ? 'active-link' : '' }}"
                            href="#" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i> <span>User</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('users.edit.self') }}">Edit</a></li>
                        </ul>
                    </div>
                @endif
            </div>
        @endauth

        <div class="main">

            <div class="hero-header">
                <div class="hero-left">

                    <img src="{{ asset('assets/img/logo.png') }}" class="hero-logo-img">

                    <span class="hero-welcome">
                        @auth
                            Welcome Back, {{ auth()->user()->name }}
                        @else
                            Welcome
                        @endauth
                    </span>
                </div>

                @auth
                    <div class="hero-right">
                        <span>{{ now()->format('d F, Y') }}</span>

                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endauth
            </div>

            @auth
                <div class="info-bar">
                    <span>Check menu in sidebar</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            @endauth

            @if (session('success'))
                <div class="px-3 mt-3">
                    <div class="alert alert-success alert-dismissible fade show rounded-3">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="px-3 mt-2">
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="page-content">
                @yield('content')
            </div>

        </div>

    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }
    </script>

</body>

</html>
