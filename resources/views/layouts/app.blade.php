<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DSL Barcode Management System')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.25);
            --text-primary: rgba(255, 255, 255, 0.92);
            --text-muted: rgba(255, 255, 255, 0.65);
            --accent-green: rgba(76, 175, 80, 0.85);
            --accent-blue: rgba(33, 150, 243, 0.85);
            --body-gradient: linear-gradient(135deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
            --navbar-gradient: linear-gradient(90deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
            --footer-gradient: linear-gradient(90deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
        }

        [data-theme="light"] {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(0, 0, 0, 0.08);
            --text-primary: rgba(20, 24, 35, 0.95);
            --text-muted: rgba(60, 70, 90, 0.7);
            --accent-green: rgba(46, 125, 50, 0.9);
            --accent-blue: rgba(25, 118, 210, 0.9);
            --body-gradient: linear-gradient(135deg, #f5f7fa 0%, #e1e7f0 100%);
            --navbar-gradient: linear-gradient(90deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
            --footer-gradient: linear-gradient(90deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--body-gradient);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .navbar {
            background: var(--navbar-gradient);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 10;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff !important;
        }

        .nav-link {
            color: #e0e0e0 !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.35);
        }

        .container-main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px 40px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            color: var(--text-primary);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(12px);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(31, 38, 135, 0.45);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .theme-toggle {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .badge.text-bg-primary {
            background: linear-gradient(135deg, #ff6b35, #f7931e) !important;
            color: #fff;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.75), rgba(56, 142, 60, 0.85));
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            color: #fff;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.95), rgba(56, 142, 60, 0.95));
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(76, 175, 80, 0.25);
        }

        .btn-secondary {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.75), rgba(25, 103, 210, 0.85));
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.95), rgba(25, 103, 210, 0.95));
            box-shadow: 0 10px 22px rgba(33, 150, 243, 0.25);
        }

        .btn-outline-primary {
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-outline-primary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-outline-secondary {
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        [data-theme="light"] .btn-primary,
        [data-theme="light"] .btn-secondary {
            border: none;
            color: #fff;
        }

        [data-theme="light"] .btn-outline-primary,
        [data-theme="light"] .btn-outline-secondary {
            color: var(--text-primary);
            border-color: rgba(0, 0, 0, 0.2);
            background: rgba(0, 0, 0, 0.04);
        }

        [data-theme="light"] .btn-outline-primary:hover,
        [data-theme="light"] .btn-outline-secondary:hover {
            color: var(--text-primary);
            background: rgba(0, 0, 0, 0.08);
        }

        .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        /* Light theme form controls */
        [data-theme="light"] .form-control,
        [data-theme="light"] .form-select {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.15);
            color: var(--text-primary);
        }

        [data-theme="light"] .form-control::placeholder {
            color: rgba(0, 0, 0, 0.35);
        }

        [data-theme="light"] .form-control:focus,
        [data-theme="light"] .form-select:focus {
            border-color: rgba(0, 0, 0, 0.25);
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.98);
            color: var(--text-primary);
        }

        .table {
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .table td,
        .table th {
            color: var(--text-primary);
        }

        .table > :not(caption) > * > * {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .table thead th {
            background: rgba(0, 0, 0, 0.25);
            color: #fff;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background: rgba(255, 255, 255, 0.08);
        }

        .table-hover > tbody > tr:hover > * {
            background: rgba(255, 255, 255, 0.12);
        }

        .table-light {
            background: rgba(0, 0, 0, 0.2) !important;
            color: #fff;
        }

        /* Light theme table styling */
        [data-theme="light"] .table thead th {
            background: rgb(230, 235, 240);
            color: var(--text-primary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .table > :not(caption) > * > * {
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        [data-theme="light"] .table-striped > tbody > tr:nth-of-type(odd) > * {
            background: rgba(0, 0, 0, 0.02);
        }

        [data-theme="light"] .table-hover > tbody > tr:hover > * {
            background: rgba(0, 0, 0, 0.05);
        }

        .alert {
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .page-link {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .page-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .page-item.active .page-link {
            background: var(--accent-green);
            border-color: var(--accent-green);
            color: #fff;
        }

        .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.4);
        }

        /* Light theme alert and pagination */
        [data-theme="light"] .alert {
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            background: rgba(0, 0, 0, 0.03);
            color: var(--text-primary);
        }

        [data-theme="light"] .page-link {
            background: rgb(240, 244, 248);
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: var(--text-primary);
        }

        [data-theme="light"] .page-link:hover {
            background: rgb(230, 237, 244);
            color: var(--text-primary);
        }

        [data-theme="light"] .page-item.active .page-link {
            background: var(--accent-green);
            border-color: var(--accent-green);
            color: #fff;
        }

        [data-theme="light"] .page-item.disabled .page-link {
            background: rgba(0, 0, 0, 0.02);
            color: rgba(0, 0, 0, 0.28);
        }

        .footer {
            background: var(--footer-gradient);
            color: #e0e0e0;
            padding: 20px;
            text-align: center;
            margin-top: 60px;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
        }

        .invalid-feedback {
            display: block;
            color: #ffb4a2;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .dropdown-menu {
            background: rgba(26, 31, 58, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        @media (max-width: 768px) {
            .container-main {
                margin: 24px auto;
                padding: 0 14px 30px;
            }

            .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>

    @yield('extra-css')
</head>
<body data-theme="dark">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-barcode"></i> DSL Barcode System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center me-2">
                        <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    @if (Auth::user()->role !== 'operations_officer')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('grn.index') }}">
                            <i class="fas fa-list"></i> GRN
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('item.index') }}">
                            <i class="fas fa-box"></i> Items
                        </a>
                    </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            @if (Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        <i class="fas fa-users"></i> User Management
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 DSL Barcode Management System. All rights reserved.</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('extra-js')
    <script>
        (function() {
            var body = document.body;
            var toggle = document.getElementById('themeToggle');
            if (!toggle) {
                return;
            }

            // Function to get system theme preference
            var getSystemTheme = function() {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                    return 'light';
                }
                return 'dark'; // fallback
            };

            // Get saved theme or use system preference
            var savedTheme = localStorage.getItem('dsl-theme');
            var currentTheme;
            
            if (savedTheme === 'light' || savedTheme === 'dark') {
                currentTheme = savedTheme;
            } else {
                // No saved preference, use system theme
                currentTheme = getSystemTheme();
            }
            
            body.setAttribute('data-theme', currentTheme);

            var updateIcon = function(theme) {
                var icon = theme === 'light' ? 'fa-sun' : 'fa-moon';
                toggle.innerHTML = '<i class="fas ' + icon + '"></i>';
            };

            updateIcon(currentTheme);

            toggle.addEventListener('click', function() {
                var nextTheme = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                body.setAttribute('data-theme', nextTheme);
                localStorage.setItem('dsl-theme', nextTheme);
                updateIcon(nextTheme);
            });

            // Listen for system theme changes (if user hasn't manually set preference)
            if (window.matchMedia) {
                var darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                darkModeQuery.addEventListener('change', function(e) {
                    // Only auto-update if user hasn't manually set a preference
                    if (!localStorage.getItem('dsl-theme')) {
                        var newTheme = e.matches ? 'dark' : 'light';
                        body.setAttribute('data-theme', newTheme);
                        updateIcon(newTheme);
                    }
                });
            }
        })();
    </script>
</body>
</html>
