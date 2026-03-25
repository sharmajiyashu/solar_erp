@extends('user.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4 text-center bg-white">
                <div class="mb-3">
                    <i class="fas fa-solar-panel fa-4x text-primary opacity-25"></i>
                </div>
                <h3>Our Solar Services</h3>
                <p class="text-muted">Explore the range of sustainable energy solutions we offer.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4">
                <i class="fas fa-home text-primary fa-2x mb-3"></i>
                <h5>Residential Solar</h5>
                <p class="text-muted small">Custom designed solar power systems for your home to reduce electricity bills.</p>
                <div class="mt-auto">
                    <button class="btn btn-sm btn-outline-primary rounded-pill w-100">Enquire Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4">
                <i class="fas fa-building text-primary fa-2x mb-3"></i>
                <h5>Commercial Solar</h5>
                <p class="text-muted small">Boost your business efficiency with industrial-scale solar installations.</p>
                <div class="mt-auto">
                    <button class="btn btn-sm btn-outline-primary rounded-pill w-100">Enquire Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4">
                <i class="fas fa-tools text-primary fa-2x mb-3"></i>
                <h5>Maintenance & Repair</h5>
                <p class="text-muted small">Professional upkeep and cleaning services for maximum solar panel performance.</p>
                <div class="mt-auto">
                    <button class="btn btn-sm btn-outline-primary rounded-pill w-100">Enquire Now</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
