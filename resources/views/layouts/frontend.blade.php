<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Solar Arkshakti Solutions - Renewable Energy</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Solar Arkshakti Solutions, Solar Arkshi Solution, Solar Panels Jaipur, Renewable Energy Rajasthan, Solar Installation Services Jaipur, Arkshakti Power Solutions" name="keywords">
    <meta content="Solar Arkshakti Solutions is a trusted provider of reliable and affordable solar energy solutions in Jaipur, Rajasthan. Specializing in residential, commercial, and industrial solar installations." name="description">

    <!-- Favicon -->
    <link href="{{ url('public/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ url('public/frontend-assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/frontend-assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/frontend-assets/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ url('public/frontend-assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ url('public/frontend-assets/css/style.css') }}" rel="stylesheet">
    
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
<style>
    body {
        overflow-x: hidden !important;
    }
</style>
@stack('styles')
</head>

<body style="overflow-x: hidden;">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>Pushp Enclave Pratap nagar Main Tonk Road Jaipur, Jaipur, RJ 302033</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>Open today 09:00 am – 05:00 pm</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>{{ config('app.website_mobile') }}</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center mx-n2">
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary" href="https://www.facebook.com/share/18DjXkpmPo/"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary" href="https://youtube.com/@arkshaktipowersolutions?si=W9OyeONmBy7B7AGT"><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('app.website_mobile')) }}" target="_blank"><i class="fab fa-whatsapp text-success"></i></a>
                    <a class="btn btn-square btn-link rounded-0" href="https://www.instagram.com/arkshaktipower?igsh=MXR6cmYzaW1qODllMA=="><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
        <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center border-end px-4 px-lg-5">
            <img src="{{ url('public/logo.jpg') }}" alt="Logo" style="height: 45px; margin-right: 10px;">
            <!-- <h5 class="m-0 text-primary fw-bold">{{ config('app.name', 'Solartec') }}</h5> -->
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="{{ route('home') }}" class="nav-item nav-link {{ Request::routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="nav-item nav-link {{ Request::routeIs('about') ? 'active' : '' }}">About</a>
                <a href="{{ route('service') }}" class="nav-item nav-link {{ Request::routeIs('service') ? 'active' : '' }}">Service</a>
                <a href="{{ route('project') }}" class="nav-item nav-link {{ Request::routeIs('project') ? 'active' : '' }}">Project</a>
                <!-- <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="{{ route('feature') }}" class="dropdown-item">Feature</a>
                        <a href="{{ route('quote') }}" class="dropdown-item">Free Quote</a>
                        <a href="{{ route('team') }}" class="dropdown-item">Our Team</a>
                        <a href="{{ route('testimonial') }}" class="dropdown-item">Testimonial</a>
                    </div>
                </div> -->
                <a href="{{ route('contact') }}" class="nav-item nav-link {{ Request::routeIs('contact') ? 'active' : '' }}">Contact</a>
                
                @guest
                    <a href="{{ route('login') }}" class="nav-item nav-link {{ Request::routeIs('login') ? 'active' : '' }}">Login</a>
                    <a href="{{ route('register') }}" class="nav-item nav-link {{ Request::routeIs('register') ? 'active' : '' }}">Register</a>
                @else
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light m-0 border-0 shadow-sm">
                            <a href="{{ route('user.dashboard') }}" class="dropdown-item px-4 py-2">
                                <i class="fa fa-th-large me-2 text-primary"></i> Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item px-4 py-2">
                                    <i class="fa fa-sign-out-alt me-2 text-primary"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
            <!-- <a href="{{ route('quote') }}" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">Get A Quote<i class="fa fa-arrow-right ms-3"></i></a> -->
        </div>
    </nav>
    <!-- Navbar End -->

    @yield('content')

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-body footer pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">Address</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Pushp Enclave Pratap nagar Main Tonk Road Jaipur, Jaipur, RJ 302033</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ config('app.website_mobile') }}</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>arkshaktipowersolutions@gmail.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light btn-social" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('app.website_mobile')) }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <a class="btn btn-square btn-outline-light btn-social" href="https://www.facebook.com/share/18DjXkpmPo/"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light btn-social" href="https://youtube.com/@arkshaktipowersolutions?si=W9OyeONmBy7B7AGT"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square btn-outline-light btn-social" href="https://www.instagram.com/arkshaktipower?igsh=MXR6cmYzaW1qODllMA=="><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <a class="btn btn-link" href="{{ route('about') }}">About Us</a>
                    <a class="btn btn-link" href="{{ route('contact') }}">Contact Us</a>
                    <a class="btn btn-link" href="{{ route('service') }}">Our Services</a>
                    <a class="btn btn-link" href="">Terms & Condition</a>
                    <a class="btn btn-link" href="">Support</a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">Project Gallery</h5>
                    <div class="row g-2">
                        <div class="col-4">
                            <img class="img-fluid rounded" src="{{ url('public/frontend-assets/img/gallery-1.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded" src="{{ url('public/frontend-assets/img/gallery-2.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded" src="{{ url('public/frontend-assets/img/gallery-3.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded" src="{{ url('public/frontend-assets/img/gallery-4.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded" src="{{ url('public/frontend-assets/img/gallery-5.jpg') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded" src="{{ url('public/frontend-assets/img/gallery-3.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="#">{{ config('app.name') }}</a>, All Right Reserved.
                    </div>
                    <!-- <div class="col-md-6 text-center text-md-end">
                        Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                        <br>Distributed By: <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('public/frontend-assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ url('public/frontend-assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ url('public/frontend-assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ url('public/frontend-assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ url('public/frontend-assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ url('public/frontend-assets/lib/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ url('public/frontend-assets/lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ url('public/frontend-assets/js/main.js') }}"></script>
    
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
    
    @stack('scripts')
</body>

</html>
