@extends('layouts.frontend')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">About Us</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Feature Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle me-3">
                            <i class="fa fa-users text-white"></i>
                        </div>
                        <h1 class="mb-0" data-toggle="counter-up">3453</h1>
                    </div>
                    <h5 class="mb-3">Family with solar panels</h5>
                    <span>Helping thousands of families transition to renewable energy.</span>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle me-3">
                            <i class="fa fa-stamp text-white"></i>
                        </div>
                        <h1 class="mb-0" style="font-size: 1.5rem;">MNRE</h1>
                    </div>
                    <h5 class="mb-3">APPROVED BY</h5>
                    <span>नवीन एवं नवीकरणीय ऊर्जा मंत्रालय (MINISTRY OF NEW AND RENEWABLE ENERGY)</span>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle me-3">
                            <i class="fa fa-id-card text-white"></i>
                        </div>
                        <h1 class="mb-0" style="font-size: 1.2rem;">CIN</h1>
                    </div>
                    <h5 class="mb-3">U43222RJ2025PTC103654</h5>
                    <span>Corporate Identification Number</span>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle me-3">
                            <i class="fa fa-map-marker-alt text-white"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Jaipur, Rajasthan</h5>
                    <span>G-07 Pushp Enclave Pratap Nagar Main Tonk Road, Jaipur, 302033</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature End -->
    

    <!-- About Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ url('public/frontend-assets/img/about.jpg') }}" style="object-fit: cover;" alt="">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0">
                        <h6 class="text-primary">About Us</h6>
                        <h1 class="mb-4">Arkshakti Trusted Solar & Renewable Energy Solutions</h1>
                        <p>Arkshakti Power Solutions Pvt. Ltd. is a dynamic and emerging solar energy solutions provider based in Jaipur, Rajasthan, India. Established with a vision to accelerate the adoption of clean and renewable energy, the company specializes in solar power systems, solar panel installations, and customized energy solutions for residential, commercial, and industrial clients.</p>
                        <p>Arkshakti Power Solutions focuses on delivering high-quality solar products, efficient installations, and outstanding after-sales support, aiming to help customers reduce electricity costs while contributing to a greener and more sustainable future. With a commitment to innovation and customer satisfaction, the company is steadily growing its presence in the solar energy market.</p>
                        
                        <div class="mt-4 p-3 bg-white rounded shadow-sm border-start border-primary border-4">
                            <h6 class="text-primary mb-2">Registration & Approvals</h6>
                            <p class="mb-1 small"><strong>APPROVED BY:</strong> नवीन एवं नवीकरणीय ऊर्जा मंत्रालय (MINISTRY OF NEW AND RENEWABLE ENERGY)</p>
                            <p class="mb-1 small"><strong>CIN:</strong> U43222RJ2025PTC103654</p>
                            <p class="mb-0 small"><strong>Registered Office:</strong> G-07 Pushp Enclave Pratap Nagar Main Tonk Road, Jaipur, Rajasthan, 302033</p>
                        </div>

                        <!-- <a href="" class="btn btn-primary rounded-pill py-3 px-5 mt-3">Explore More</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    @include('frontend.partials.team')
@endsection
