@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d); border-radius: 15px; overflow: hidden;">
                <div class="card-body p-5 text-white position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="display-6 fw-bold mb-2">Solar Maintenance & Services</h2>
                            <p class="lead mb-0 opacity-75">Professional upkeep and smart subscription plans for maximum efficiency.</p>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <i class="fas fa-solar-panel fa-6x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions Section -->
    @if($subscriptions->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h4 class="fw-bold mb-4 d-flex align-items-center">
                <i class="fas fa-check-circle text-success me-2"></i> My Active Subscriptions
            </h4>
        </div>
        @foreach($subscriptions as $sub)
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $sub->package->name }}</h5>
                            <span class="badge bg-success small">Subscribed</span>
                        </div>
                        <div class="text-end">
                            <h4 class="fw-bold text-primary mb-0">₹{{ number_format($sub->amount, 2) }}</h4>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $sub->package->duration_type) }}</small>
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block small">Start Date</small>
                                <span class="fw-bold small">{{ $sub->start_date->format('d M, Y') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block small">Expires On</small>
                                <span class="fw-bold small">{{ $sub->end_date->format('d M, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 small d-flex align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-2"></i> Service Intervals
                        <span class="ms-auto badge bg-light text-primary border">{{ $sub->slots->count() }} Total Visits</span>
                    </h6>
                    
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($sub->slots as $slot)
                        <div class="p-2 border rounded-3 text-center transition-all bg-white" style="min-width: 80px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                            <small class="text-uppercase text-muted d-block" style="font-size: 0.6rem; font-weight: 800;">Visit {{ $loop->iteration }}</small>
                            <span class="fw-bolder small {{ $slot->status == 'completed' ? 'text-success' : 'text-dark' }}" style="font-size: 0.75rem;">
                                {{ $slot->service_date->format('d M') }}
                            </span>
                            @if($slot->status == 'completed')
                                <i class="fas fa-check-circle text-success ms-1 small"></i>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Available Packages Section -->
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold mb-4 d-flex align-items-center">
                <i class="fas fa-shopping-cart text-primary me-2"></i> New Subscription Plans
            </h4>
        </div>
        
        @foreach($packages as $package)
        @php
            $isSubscribed = $subscriptions->where('package_id', $package->id)->where('status', 'active')->count() > 0;
        @endphp
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-up overflow-hidden" style="border-radius: 12px; transition: all 0.3s ease;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                            <i class="fas fa-box-open fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $package->name }}</h5>
                            <small class="text-uppercase text-primary fw-bold" style="font-size: 0.65rem;">{{ str_replace('_', ' ', $package->package_type) }}</small>
                        </div>
                    </div>
                    
                    <p class="text-muted small mb-4 opacity-75">{{ \Str::limit($package->description, 100) }}</p>
                    
                    <div class="mb-4">
                        @if($package->features)
                        @foreach($package->features as $feature)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2 small"></i>
                            <span class="small text-muted">{{ $feature }}</span>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    
                    <div class="mt-auto pt-4 border-top">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <small class="text-muted d-block small">Service every {{ str_replace('_days', ' days', $package->frequency) }}</small>
                                <span class="h4 fw-bold mb-0">₹{{ number_format($package->price, 2) }}</span>
                                <small class="text-muted">/ {{ str_replace('_', ' ', $package->duration_type) }}</small>
                            </div>
                        </div>
                        
                        @if($isSubscribed)
                            <button class="btn btn-success w-100 rounded-pill disabled" disabled>
                                <i class="fas fa-check me-2"></i> Already Subscribed
                            </button>
                        @else
                            <button class="btn btn-primary w-100 rounded-pill purchase-pkg-btn" 
                                    data-id="{{ $package->id }}"
                                    data-name="{{ $package->name }}"
                                    data-price="{{ $package->price }}">
                                <i class="fas fa-bolt me-2"></i> Subscribe Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@push('scripts')
<script>
$(document).ready(function() {
    $('.purchase-pkg-btn').on('click', function() {
        let btn = $(this);
        let packageId = btn.data('id');
        let originalText = btn.html();
        
        btn.attr('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Initiating...');
        
        $.ajax({
            url: "{{ route('user.subscription.initiate') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                package_id: packageId
            },
            success: function(response) {
                if (response.status) {
                    var options = {
                        "key": response.key,
                        "amount": response.amount * 100,
                        "currency": "INR",
                        "name": "Solar ERP",
                        "description": "Subscription for " + response.package_name,
                        "order_id": response.order_id,
                        "handler": function (res){
                            verifyPayment(res, packageId, btn, originalText);
                        },
                        "prefill": {
                            "name": "{{ Auth::user()->name }}",
                            "email": "{{ Auth::user()->email }}",
                            "contact": "{{ Auth::user()->mobile }}"
                        },
                        "theme": {
                            "color": "#1a2a6c"
                        },
                        "modal": {
                            "ondismiss": function() {
                                btn.attr('disabled', false).html(originalText);
                            }
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else {
                    alert(response.message);
                    btn.attr('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
                btn.attr('disabled', false).html(originalText);
            }
        });
    });

    function verifyPayment(razorResponse, packageId, btn, originalText) {
        $.ajax({
            url: "{{ route('user.subscription.verify') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                package_id: packageId,
                razorpay_payment_id: razorResponse.razorpay_payment_id,
                razorpay_order_id: razorResponse.razorpay_order_id,
                razorpay_signature: razorResponse.razorpay_signature
            },
            success: function(response) {
                if (response.status) {
                    Toastify({
                        text: response.message,
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    }).showToast();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert(response.message);
                    btn.attr('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert('Verification failed. Contact support if amount was debited.');
                btn.attr('disabled', false).html(originalText);
            }
        });
    }
});
</script>
@endpush


<style>
.hover-up:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important;
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
.badge {
    font-weight: 600;
    padding: 0.5em 0.8em;
}
</style>
@endsection
