<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Dashboard | {{ config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ url('public/favicon.ico') }}" rel="icon">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ url('public/frontend-assets/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0052CC; /* Strong Professional Blue */
            --secondary: #111827; /* Near Black */
            --accent: #F0F5FF; /* Very Light Blue */
            --light: #F9FAFB; /* Light Background */
            --white: #FFFFFF;
            --dark: #111827;
            --sidebar-width: 260px;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
        }

        body {
            background-color: var(--light);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            color: var(--text-main);
            letter-spacing: -0.01em;
        }

        .fw-black { font-weight: 900 !important; }

        /* Sidebar Styling - Simple & Clean */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--white);
            color: var(--text-main);
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1050;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-header {
            padding: 40px 24px;
            text-align: left;
        }

        .sidebar-menu {
            padding: 0 16px;
        }

        .nav-link {
            padding: 12px 16px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            border-radius: 10px;
            margin-bottom: 4px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
        }

        .nav-link:hover {
            background: var(--accent);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(93, 95, 239, 0.2);
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .btn-primary { 
            background-color: var(--primary); 
            border: none; 
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.2s ease;
            color: white;
        }
        .btn-primary:hover { 
            background-color: #4338CA;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(93, 95, 239, 0.2);
            color: white;
        }
        
        .btn-white { background: var(--white); color: var(--primary); border: 1px solid var(--border-color); }
        .btn-white:hover { background: var(--accent); color: #4338CA; transform: translateY(-1px); }

        .bg-light-primary { background-color: #EBF5FF; color: #0052CC; }
        .bg-light-secondary { background-color: #F3F4F6; color: #111827; }
        .bg-blue-soft { background-color: #F0F7FF; color: #0052CC; }
        
        /* Unified Status Badge - Only Blue/Gray */
        .badge { font-weight: 700; border-radius: 6px; padding: 6px 12px; border: 1px solid transparent; }
        .badge-primary { background: #EBF5FF; color: #0052CC; border-color: rgba(0, 82, 204, 0.1); }
        .badge-secondary { background: #F3F4F6; color: #4B5563; border-color: rgba(0, 0, 0, 0.05); }

        .card { 
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .card:hover { 
            border-color: #D1D5DB;
        }

        .rounded-4 { border-radius: 0.75rem !important; }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Mobile Toggle Button - Sleek Left Tab */
        #mobileToggle {
            display: none;
            position: fixed;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 35px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: 0 12px 12px 0;
            border: none;
            box-shadow: 5px 0 15px rgba(113, 187, 178, 0.3);
            z-index: 2005;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }

        #mobileToggle:hover {
            width: 45px;
            background: var(--secondary);
        }

        #mobileToggle i {
            font-size: 1.1rem;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #mobileToggle.active {
            left: var(--sidebar-width);
            border-radius: 12px 0 0 12px;
            background: var(--secondary);
        }

        #mobileToggle.active i {
            transform: rotate(180deg);
        }

        /* Backdrop */
        #sidebarBackdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            z-index: 1040;
            transition: opacity 0.3s ease;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active {
                left: 0;
                box-shadow: 20px 0 50px rgba(0,0,0,0.1);
            }
            .main-content {
                margin-left: 0;
                padding: 25px;
            }
            #mobileToggle {
                display: flex;
            }
            #sidebarBackdrop.show {
                display: block;
            }
        }

        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.1); }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button id="mobileToggle">
        <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Backdrop for Mobile -->
    <div id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="text-decoration-none">
                <img src="{{ url('public/logo.jpg') }}" alt="Logo" style="height: 55px; border-radius: 12px; transition: transform 0.3s ease;">
                <h5 class="mt-3 mb-0 fw-black text-dark" style="letter-spacing: -0.5px; font-size: 1.1rem;">Arkshakti <span class="text-primary">Power</span></h5>
                <p class="small text-muted fw-bold mb-0" style="font-size: 0.65rem; opacity: 0.7;">SOLAR SOLUTIONS</p>
            </a>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ Request::routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('user.services') }}" class="nav-link {{ Request::routeIs('user.services') ? 'active' : '' }}">
                <i class="fas fa-solar-panel"></i> Subscriptions
            </a>
            <a href="{{ route('user.slots') }}" class="nav-link {{ Request::routeIs('user.slots') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> My slots
            </a>
            <a href="{{ route('user.tickets.index') }}" class="nav-link {{ Request::routeIs('user.tickets.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> Tickets
            </a>
            <a href="{{ route('user.profile') }}" class="nav-link {{ Request::routeIs('user.profile') ? 'active' : '' }}">
                <i class="fas fa-user-gear"></i> Settings
            </a>
            <a href="{{ url('/') }}" class="nav-link">
                <i class="fas fa-globe"></i> Website
            </a>
            
            <div class="mt-auto pt-5 px-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link bg-transparent border-0 w-100 text-start text-danger hover-light opacity-75">
                        <i class="fas fa-power-off"></i> Secure Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <script>
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const toggleBtn = $('#mobileToggle');
            const backdrop = $('#sidebarBackdrop');

            function toggleSidebar() {
                sidebar.toggleClass('active');
                toggleBtn.toggleClass('active');
                backdrop.toggleClass('show');
            }

            toggleBtn.on('click', toggleSidebar);
            backdrop.on('click', toggleSidebar);
        });
    </script>
    @include('components.firebase-user-fcm')
    @stack('scripts')
</body>
</html>
