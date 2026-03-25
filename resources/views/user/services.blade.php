@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Slim Premium Header - MINIMALIST -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card p-4 rounded-4 border-0 shadow-lg position-relative overflow-hidden" 
                 style="background: linear-gradient(135deg, #1e293b 0%, #27445D 100%); min-height: 120px; display: flex; align-items: center;">
                <div class="position-relative z-index-2 w-100">
                    <div class="row align-items-center">
                        <div class="col-md-9 text-center text-md-start">
                            <h3 class="fw-black text-white mb-1">Solar Subscriptions & Services</h3>
                            <p class="text-white-50 mb-0 fw-medium small">Manage your energy plans and scheduled maintenance visits in one place.</p>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0 d-none d-md-block">
                             <div class="icon-circle p-3 rounded-circle d-inline-flex border border-white border-opacity-25 shadow-sm">
                                <i class="fas fa-solar-panel text-white fs-4"></i>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="shape-1"></div>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions - CLEANED BGs -->
    @if($subscriptions->count() > 0)
    <div class="row mb-4">
        <div class="col-12 mb-3 d-flex justify-content-between align-items-center px-4">
            <h6 class="fw-bold mb-0 text-muted text-uppercase small" style="letter-spacing: 1px;">My Active Plans</h6>
            <span class="badge border border-success border-opacity-25 text-success rounded-pill px-3 py-1">{{ $subscriptions->count() }} Active</span>
        </div>
        @foreach($subscriptions as $sub)
        <div class="col-lg-6 mb-4">
            <div class="card border border-light rounded-4 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box border border-primary border-opacity-10 p-3 rounded-4 me-3 text-primary">
                                <i class="fas fa-bolt-lightning fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-black mb-0 text-dark">{{ $sub->package->name }}</h5>
                                <small class="text-muted fw-bold small text-uppercase" style="font-size: 0.6rem;">ID: #{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }} • {{ str_replace('_', ' ', $sub->package->duration_type) }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <h4 class="fw-black text-primary mb-0">₹{{ number_format($sub->amount, 0) }}</h4>
                            <span class="badge border border-success border-opacity-25 text-success rounded-pill px-2 py-1 mt-1 small" style="font-size: 0.6rem;">PAID</span>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-2 px-3 rounded-4 border border-light">
                                <small class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.55rem;">Start Date</small>
                                <span class="fw-bold text-dark small">{{ $sub->start_date->format('d M, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 px-3 rounded-4 border border-light">
                                <small class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.55rem;">Expiry Date</small>
                                <span class="fw-bold text-dark small">{{ $sub->end_date->format('d M, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="visit-logs pt-3 border-top border-light">
                        <h6 class="fw-bold mb-3 small d-flex align-items-center text-muted">
                            <i class="fas fa-calendar-check text-primary me-2"></i> Visit Schedule
                            <span class="ms-auto text-dark fw-black small">{{ $sub->slots->where('status', 'completed')->count() }} / {{ $sub->slots->count() }} Done</span>
                        </h6>
                        
                        <div class="scroll-horizontal-compact d-flex gap-2 pb-2 overflow-auto">
                            @foreach($sub->slots as $slot)
                            <div class="visit-pill p-2 px-3 border rounded-pill text-center transition-all flex-shrink-0 {{ $slot->status == 'completed' ? 'border-success border-opacity-25' : 'border-light' }}" style="min-width: 90px;">
                                <span class="fw-bold small d-block {{ $slot->status == 'completed' ? 'text-success' : 'text-muted' }}" style="font-size: 0.7rem;">
                                    {{ $slot->service_date->format('M d') }}
                                    @if($slot->status == 'completed') <i class="fas fa-check-circle ms-1"></i> @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- New Plans - CLEANED BGs -->
    <div class="row">
        <div class="col-12 mb-3 px-4">
            <h6 class="fw-bold mb-0 text-muted text-uppercase small" style="letter-spacing: 1px;">Available Plans</h6>
        </div>
        
        @foreach($packages as $package)
        @php
            $isSubscribed = $subscriptions->where('package_id', $package->id)->where('status', 'active')->count() > 0;
        @endphp
        <div class="col-md-4 mb-4">
            <div class="card h-100 border border-light rounded-4 shadow-sm hover-shadow-primary overflow-hidden bg-white" style="border-radius: 1.5rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="icon-box-sm border border-primary border-opacity-25 p-2 px-3 rounded-pill text-primary fw-black small">
                            {{ strtoupper($package->package_type) }}
                        </div>
                        @if($isSubscribed)
                            <span class="badge border border-success border-opacity-25 text-success rounded-pill px-3 py-1 fw-bold small">Current</span>
                        @endif
                    </div>
                    
                    <h4 class="fw-black mb-1 text-dark">{{ $package->name }}</h4>
                    <p class="text-muted small mb-4 fw-medium lh-sm" style="opacity: 0.8 !important;">{{ \Str::limit($package->description, 70) }}</p>
                    
                    <div class="plan-features mb-4 flex-grow-1">
                        @if($package->features)
                        @foreach($package->features as $feature)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-circle-check text-primary me-2" style="font-size: 0.75rem;"></i>
                            <span class="small text-dark fw-medium" style="font-size: 0.8rem;">{{ $feature }}</span>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    
                    <div class="mt-auto pt-4 border-top border-light">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <small class="text-muted d-block small mb-1">Service every {{ str_replace('_days', ' days', $package->frequency) }}</small>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="fw-black text-dark mb-0">₹{{ number_format($package->price, 0) }}</h3>
                                    <small class="text-muted ms-1 fw-bold">/ plan</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($isSubscribed)
                            <button class="btn btn-outline-success w-100 rounded-pill py-2 fw-black small" disabled>
                                <i class="fas fa-check-double me-2"></i> Active
                            </button>
                        @else
                            <button class="btn btn-primary w-100 rounded-pill py-2 fw-black shadow-sm purchase-pkg-btn" 
                                    data-id="{{ $package->id }}"
                                    data-name="{{ $package->name }}"
                                    data-price="{{ $package->price }}">
                                <i class="fas fa-bolt-lightning me-2"></i> Subscribe Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(document).ready(function() {
    $('.purchase-pkg-btn').on('click', function() {
        let btn = $(this);
        let packageId = btn.data('id');
        let originalText = btn.html();
        
        btn.attr('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
        
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
                        "theme": { "color": "#71bbb2" },
                        "modal": {
                            "ondismiss": function() {
                                btn.attr('disabled', false).html(originalText);
                            }
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else {
                    Toastify({ text: response.message, backgroundColor: "#ef4444" }).showToast();
                    btn.attr('disabled', false).html(originalText);
                }
            },
            error: function() {
                Toastify({ text: "Network error. Please try again.", backgroundColor: "#ef4444" }).showToast();
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
                    Toastify({ text: response.message, backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)" }).showToast();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Toastify({ text: response.message, backgroundColor: "#ef4444" }).showToast();
                    btn.attr('disabled', false).html(originalText);
                }
            },
            error: function() {
                Toastify({ text: "Verification failed. Contact support.", backgroundColor: "#ef4444" }).showToast();
                btn.attr('disabled', false).html(originalText);
            }
        });
    }
});
</script>
@endpush

<style>
    body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
    .fw-black { font-weight: 900 !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .shadow-lg { box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.1) !important; }
    
    .btn-primary { background-color: var(--primary); border: none; transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px -3px rgba(113, 187, 178, 0.4); }
    
    .btn-glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); }

    .hover-shadow-primary:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 20px 25px -5px rgba(113, 187, 178, 0.1) !important; 
        border: 1px solid var(--primary) !important;
    }
    
    .shape-1 { position: absolute; width: 300px; height: 300px; background: rgba(113, 187, 178, 0.1); border-radius: 50%; top: -100px; right: -50px; filter: blur(40px); }
    
    .scroll-horizontal-compact::-webkit-scrollbar { height: 4px; }
    .scroll-horizontal-compact::-webkit-scrollbar-track { background: transparent; }
    .scroll-horizontal-compact::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
