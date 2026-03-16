@extends('layouts.frontend')

@section('content')
    <!-- Carousel Start -->
    <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ url('public/frontend-assets/img/carousel-1.jpg') }}'>">
                <img class="img-fluid" src="{{ url('public/frontend-assets/img/carousel-1.jpg') }}" alt="">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-2 text-white animated slideInDown">Arkshakti Trusted Solar Installation Services</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">Affordable And Reliable Solar Energy Solutions For All.</p>
                                <!-- <a href="" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft">Read More</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ url('public/frontend-assets/img/carousel-2.jpg') }}'>">
                <img class="img-fluid" src="{{ url('public/frontend-assets/img/carousel-2.jpg') }}" alt="">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-2 text-white animated slideInDown">Arkshakti Trusted Solar Installation Services</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">Affordable And Reliable Solar Energy Solutions For All.</p>
                                <!-- <a href="" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft">Read More</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ url('public/frontend-assets/img/carousel-3.jpg') }}'>">
                <img class="img-fluid" src="{{ url('public/frontend-assets/img/carousel-3.jpg') }}" alt="">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-2 text-white animated slideInDown">Arkshakti Trusted Solar Installation Services</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">Affordable And Reliable Solar Energy Solutions For All.</p>
                                <!-- <a href="" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft">Read More</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


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
    <div id="about" class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
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
                        <h1 class="mb-4">Arkansas Trusted Solar & Renewable Energy Solutions</h1>
                        <p>Arkshakti Power Solutions Pvt. Ltd. is a dynamic and emerging solar energy solutions provider based in Jaipur, Rajasthan, India. Established with a vision to accelerate the adoption of clean and renewable energy, the company specializes in solar power systems, solar panel installations, and customized energy solutions for residential, commercial, and industrial clients.</p>
                        <p>Arkshakti Power Solutions focuses on delivering high-quality solar products, efficient installations, and outstanding after-sales support, aiming to help customers reduce electricity costs while contributing to a greener and more sustainable future. With a commitment to innovation and customer satisfaction, the company is steadily growing its presence in the solar energy market.</p>
                        
                        <div class="mt-4 p-3 bg-white rounded shadow-sm border-start border-primary border-4">
                            <h6 class="text-primary mb-2">Registration & Approvals</h6>
                            <p class="mb-1 small"><strong>APPROVED BY:</strong> नवीन एवं नवीकरणीय ऊर्जा मंत्रालय (MINISTRY OF NEW AND RENEWABLE ENERGY)</p>
                            <p class="mb-1 small"><strong>CIN:</strong> U43222RJ2025PTC103654</p>
                            <p class="mb-0 small"><strong>Registered Office:</strong> G-07 Pushp Enclave Pratap Nagar Main Tonk Road, Jaipur, Rajasthan, 302033</p>
                        </div>

                        <a href="{{ route('about') }}" class="btn btn-primary rounded-pill py-3 px-5 mt-3">Explore More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Service Start -->
    <div id="service" class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Our Services</h6>
                <h1 class="mb-4">We Are Pioneers In The World Of Renewable Energy</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded overflow-hidden">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/carousel-1.jpg') }}" alt="">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-solar-panel fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Residential Solar</h4>
                            <p>Customized rooftop solar solutions for homes to reduce electricity bills and increase energy independence.</p>
                            <a class="small fw-medium" href="">Read More<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded overflow-hidden">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/carousel-2.jpg') }}" alt="">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-industry fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Commercial Solar</h4>
                            <p>High-efficiency solar systems for commercial buildings, factories, and schools to optimize operational costs.</p>
                            <a class="small fw-medium" href="">Read More<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded overflow-hidden">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/carousel-3.jpg') }}" alt="">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-tools fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Maintenance & O&M</h4>
                            <p>Comprehensive operation and maintenance services ensure your solar plant performs at peak efficiency year-round.</p>
                            <a class="small fw-medium" href="">Read More<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    @include('frontend.partials.why-choose-us')


    <!-- Projects Start -->
    <div id="project" class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Our Projects</h6>
                <h1 class="mb-4">Visit Our Latest Solar And Renewable Energy Projects</h1>
            </div>
            <div class="row g-4 portfolio-container wow fadeInUp" data-wow-delay="0.5s">
                <div class="col-lg-4 col-md-6 portfolio-item first">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/gallery-1.jpg') }}" alt="">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="{{ url('public/frontend-assets/img/gallery-1.jpg') }}" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href=""><i class="fa fa-link"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 portfolio-item second">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/gallery-2.jpg') }}" alt="">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="{{ url('public/frontend-assets/img/gallery-2.jpg') }}" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href=""><i class="fa fa-link"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 portfolio-item third">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/gallery-3.jpg') }}" alt="">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="{{ url('public/frontend-assets/img/gallery-3.jpg') }}" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href=""><i class="fa fa-link"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Projects End -->


    @include('frontend.partials.quote')


    <!-- Contact Start -->
    <div id="contact" class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Contact Us</h6>
                <h1 class="mb-4">If You Have Any Query, Please Feel Free Contact Us</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light text-center p-4">
                        <i class="fa fa-phone-alt fa-2x text-primary mb-3"></i>
                        <h5>Phone Number</h5>
                        <p>{{ config('app.website_mobile') }}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="bg-light text-center p-4">
                        <i class="fa fa-envelope-open fa-2x text-primary mb-3"></i>
                        <h5>Email Address</h5>
                        <p>arkshaktipowersolutions@gmail.com</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="bg-light text-center p-4">
                        <i class="fa fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h5>Office Address</h5>
                        <p>Pushp Enclave Pratap nagar Main Tonk Road Jaipur, Jaipur, RJ 302033</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection
