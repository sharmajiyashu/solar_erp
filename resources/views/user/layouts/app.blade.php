<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Dashboard | {{ config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link href="{{ url('public/favicon.ico') }}" rel="icon">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ url('public/frontend-assets/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        :root {
            --primary: #71bbb2;
            --secondary: #27445D;
            --accent: #FF7E20;
            --light: #f4f7f6;
            --dark: #1a2a3a;
        }

        body {
            background-color: var(--light);
            font-family: 'Open Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--secondary);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .nav-link {
            padding: 15px 25px;
            color: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--primary);
        }

        .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }

        .top-navbar {
            background: white;
            padding: 15px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #5da69d;
            border-color: #5da69d;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -280px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="text-white text-decoration-none">
                <img src="{{ url('public/logo.jpg') }}" alt="Logo" style="height: 40px; filter: brightness(0) invert(1);">
                <h5 class="mt-2 mb-0">User Panel</h5>
            </a>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ Request::routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('user.services') }}" class="nav-link {{ Request::routeIs('user.services') ? 'active' : '' }}">
                <i class="fas fa-solar-panel"></i> My Services
            </a>
            <a href="{{ route('user.profile') }}" class="nav-link {{ Request::routeIs('user.profile') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i> Profile Settings
            </a>
            <a href="{{ url('/') }}" class="nav-link">
                <i class="fas fa-home"></i> Back to Website
            </a>
            <hr class="mx-3 text-white-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link bg-transparent border-0 w-100 text-start">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="btn d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0 d-none d-md-block">Welcome back, {{ Auth::user()->name }}!</h5>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-sm-block">
                    <p class="mb-0 fw-bold">{{ Auth::user()->name }}</p>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <script>
        $(document).ready(function() {
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
