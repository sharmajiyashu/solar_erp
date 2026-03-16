@extends('layouts.frontend')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Features</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Features</li>
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


    @include('frontend.partials.why-choose-us')
@endsection
