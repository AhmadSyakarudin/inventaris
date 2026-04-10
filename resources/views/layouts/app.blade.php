<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f2f8;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #2b3fa0;
            color: white;
            flex-shrink: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
            transition: width 0.3s ease;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 0;
        }

        .sidebar-brand {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 24px 24px 12px 24px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            white-space: nowrap;
        }

        .sidebar-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
            padding: 20px 24px 6px 24px;
            white-space: nowrap;
        }

        .sidebar a:not(.dropdown-item) {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 24px;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }

        .sidebar a:not(.dropdown-item) i {
            width: 20px;
            font-size: 1rem;
            text-align: center;
        }

        .sidebar a:not(.dropdown-item):hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: rgba(255, 255, 255, 0.4);
        }

        .active-link {
            background: #3b52c4 !important;
            border-left: 3px solid white !important;
            font-weight: 600;
        }

        .sidebar .dropdown-menu {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            border-radius: 0;
            padding: 4px 0;
            margin: 0;
            width: 100%;
        }

        .sidebar .dropdown-item {
            color: rgba(255, 255, 255, 0.85) !important;
            padding: 8px 24px 8px 56px !important;
            font-size: 0.85rem;
            font-weight: 500;
            background: transparent;
            white-space: nowrap;
        }

        .sidebar .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .hero-header {
            height: 210px;
            background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)),
                url("{{ asset('assets/img/background.jpeg') }}") center / cover no-repeat;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 20px 28px;
        }

        .hero-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hero-hamburger {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .hero-logo-img {
            width: 60px;
            height: auto;
        }

        .hero-welcome {
            font-weight: 600;
            font-size: 1rem;
            color: white;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .hero-date {
            color: white;
            font-weight: 500;
            font-size: 0.85rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .user-dropdown .dropdown-toggle {
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0;
        }

        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .user-chevron {
            font-size: 0.7rem;
        }

        .user-dropdown .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 8px 0;
            margin-top: 8px;
        }

        .user-dropdown .dropdown-item {
            font-size: 0.85rem;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1f2937;
        }

        .user-dropdown .dropdown-item i {
            width: 20px;
            color: #2b3fa0;
        }

        .info-bar {
            background: white;
            padding: 12px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9eef3;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .info-bar .hint {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .info-bar .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
        }

        .page-content {
            padding: 28px;
            flex: 1;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex">

        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">Menu</div>

            {{-- <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                <i class="fa-solid fa-table-columns"></i>
                <span>Dashboard</span>
            </a> --}}

            <div class="sidebar-label">Items Data</div>

            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-bars"></i>
                <span>Categories</span>
            </a>

            {{-- <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-earth-americas"></i>
                <span>Items</span>
            </a> --}}

            {{-- <a href="{{ route('lendins.index') }}" class="{{ request()->routeIs('lendings.*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-rotate"></i>
                <span>Lending</span>
            </a> --}}

            <div class="sidebar-label">Accounts</div>

            {{-- <div class="dropdown">
                <a class="dropdown-toggle {{ request()->routeIs('users.*') ? 'active-link' : '' }}" href="#"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                    <span>Users</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('#') }}">Admin</a></li>
                    <li><a class="dropdown-item" href="{{ route('#') }}">Operator</a></li>
                </ul>
            </div> --}}
        </div>

        <div class="main">

            <div class="hero-header">
                <div class="hero-left">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="hero-logo-img">
                    {{-- <span class="hero-welcome">Welcome Back, {{ auth()->user()->name }}</span> --}}
                </div>

                <div class="hero-right">
                    <span class="hero-date">{{ now()->format('d F, Y') }}</span>

                    <div class="dropdown user-dropdown">
                        <button class="dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            {{-- <span>{{ auth()->user()->name }}</span> --}}
                            <i class="fa-solid fa-chevron-down user-chevron"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                {{-- <form method="POST" action="{{ route('#') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </button>
                                </form> --}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div> 

            <div class="info-bar">
                <span class="hint">Check menu in sidebar</span>
                {{-- <span class="user-name">{{ auth()->user()->name }}</span> --}}
            </div>

            <div class="page-content">
                @yield('content')
            </div> 

        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script> --}}
</body>

</html>
