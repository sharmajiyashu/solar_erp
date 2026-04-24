@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-4 px-0 px-md-3">
    <!-- Clean Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 text-center text-md-start">
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small fw-bold text-uppercase" style="letter-spacing: 1px;">
                    <li class="breadcrumb-item text-primary"><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Subscriptions</li>
                </ol>
            </nav>
            <h2 class="fw-black text-dark mb-0">Solar Plans & Services</h2>
            <p class="text-muted mb-0 small fw-medium">Manage your active energy plans and explore new maintenance packages.</p>
        </div>
    </div>

    <!-- Active Subscriptions -->
    @if($subscriptions->count() > 0)
    <div class="row mb-5">
        <div class="col-12 mb-3 d-flex justify-content-between align-items-center px-4">
            <h6 class="fw-black mb-0 text-muted text-uppercase small" style="letter-spacing: 1px;">My Active Plans</h6>
            <span class="badge bg-light-success text-success border border-success border-opacity-10 rounded-pill px-3 py-2 fw-bold small">
                {{ $subscriptions->count() }} Active
            </span>
        </div>
        @foreach($subscriptions as $sub)
        <div class="col-lg-6 mb-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary">
                                <i class="fas fa-solar-panel fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-black mb-0 text-dark">{{ $sub->package->name }}</h5>
                                <small class="text-muted fw-bold small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">ID: #{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }} • {{ str_replace('_', ' ', $sub->package->duration_type) }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <h4 class="fw-black text-primary mb-0">₹{{ number_format($sub->amount, 0) }}</h4>
                            <span class="badge bg-light-success text-success border border-success border-opacity-10 rounded-pill px-2 py-1 mt-1 small" style="font-size: 0.6rem;">PAID</span>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light border border-white">
                                <small class="text-muted d-block small fw-black text-uppercase mb-1" style="font-size: 0.55rem;">Start Date</small>
                                <span class="fw-bold text-dark">{{ $sub->start_date->format('d M, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light border border-white">
                                <small class="text-muted d-block small fw-black text-uppercase mb-1" style="font-size: 0.55rem;">Expiry Date</small>
                                <span class="fw-bold text-dark">{{ $sub->end_date->format('d M, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="visit-logs pt-3 border-top border-light">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 small text-muted text-uppercase" style="letter-spacing: 0.5px;">
                                <i class="fas fa-calendar-check text-primary me-2"></i> Visit Schedule
                            </h6>
                            <span class="text-dark fw-black small">{{ $sub->slots->where('status', 'completed')->count() }} / {{ $sub->slots->count() }} Completed</span>
                        </div>
                        
                        <div class="scroll-horizontal-compact d-flex gap-2 pb-2 overflow-auto custom-scrollbar">
                            @foreach($sub->slots as $slot)
                            <div class="visit-pill p-2 px-3 border rounded-pill text-center transition-all flex-shrink-0 {{ $slot->status == 'completed' ? 'bg-light-success border-success border-opacity-10' : 'bg-white border-light' }}" style="min-width: 95px;">
                                <span class="fw-bold small d-block {{ $slot->status == 'completed' ? 'text-success' : 'text-muted' }}" style="font-size: 0.7rem;">
                                    {{ $slot->service_date->format('M d, Y') }}
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

    <!-- New Plans -->
    <div class="row">
        <div class="col-12 mb-4 px-4">
            <h6 class="fw-black mb-0 text-muted text-uppercase small" style="letter-spacing: 1px;">Available Maintenance Plans</h6>
        </div>
        
        @foreach($packages as $package)
        @php
            $isSubscribed = $subscriptions->where('package_id', $package->id)->where('status', 'active')->count() > 0;
        @endphp
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow-primary overflow-hidden bg-white transition-all">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="badge bg-light-primary text-primary border border-primary border-opacity-10 p-2 px-3 rounded-pill fw-black small">
                            {{ strtoupper($package->package_type) }}
                        </div>
                        @if($isSubscribed)
                            <span class="badge bg-light-success text-success border border-success border-opacity-10 rounded-pill px-3 py-2 fw-bold small">Current Plan</span>
                        @endif
                    </div>
                    
                    <h4 class="fw-black mb-1 text-dark">{{ $package->name }}</h4>
                    <p class="text-muted small mb-4 fw-medium lh-sm opacity-75">{{ \Str::limit($package->description, 100) }}</p>
                    
                    <div class="plan-features mb-4 flex-grow-1 p-3 bg-light rounded-4">
                        <h6 class="small fw-black text-muted text-uppercase mb-3" style="font-size: 0.6rem; letter-spacing: 1px;">Features Included</h6>
                        @if($package->features)
                        @foreach($package->features as $feature)
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 18px; height: 18px;">
                                <i class="fas fa-check text-primary" style="font-size: 0.6rem;"></i>
                            </div>
                            <span class="small text-dark fw-bold" style="font-size: 0.75rem;">{{ $feature }}</span>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    
                    <div class="mt-auto pt-4 border-top border-light">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <small class="text-muted d-block small mb-1 fw-bold">Frequency: Every {{ str_replace('_days', ' days', $package->frequency) }}</small>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="fw-black text-primary mb-0">₹{{ number_format($package->price, 0) }}</h3>
                                    <small class="text-muted ms-1 fw-bold">/ total</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($isSubscribed)
                            <button class="btn btn-outline-success w-100 rounded-pill py-2 fw-black small" disabled>
                                <i class="fas fa-check-double me-2"></i> Plan Active
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

<!-- Purchase Confirmation Modal -->
<div class="modal fade" id="purchaseConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-white bg-opacity-20 p-2 rounded-3 me-3">
                        <i class="fas fa-calendar-alt fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-black mb-0">Confirm Subscription</h5>
                        <small class="text-white-50" id="confirm_package_name"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 bg-light rounded-4 mb-4">
                    <label class="form-label small fw-black text-muted text-uppercase" style="letter-spacing: 0.5px;">Preferred first visit date</label>
                    <input type="date" id="solarStartDate" class="form-control form-control-lg rounded-3 border-0 shadow-sm" 
                           value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}">
                    <div class="form-text mt-2 small text-primary d-flex align-items-start">
                        <i class="fas fa-info-circle mt-1 me-2"></i>
                        <span>Used when you subscribe; all visits in this plan will be scheduled starting from this date.</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center px-2">
                    <span class="text-muted fw-bold">Total Amount:</span>
                    <h3 class="fw-black text-primary mb-0" id="confirm_package_price"></h3>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm" id="confirmPurchaseBtn">
                    Proceed to Payment <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(document).ready(function() {
    let selectedPackageId = null;
    let selectedBtn = null;
    let originalBtnText = '';

    $('.purchase-pkg-btn').on('click', function() {
        selectedBtn = $(this);
        selectedPackageId = selectedBtn.data('id');
        originalBtnText = selectedBtn.html();
        
        $('#confirm_package_name').text(selectedBtn.data('name'));
        $('#confirm_package_price').text('₹' + parseFloat(selectedBtn.data('price')).toLocaleString('en-IN'));
        $('#purchaseConfirmModal').modal('show');
    });

    $('#confirmPurchaseBtn').on('click', function() {
        $('#purchaseConfirmModal').modal('hide');
        initiateSubscription(selectedPackageId, selectedBtn, originalBtnText);
    });

    function initiateSubscription(packageId, btn, originalText) {
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
                        "theme": { "color": "#27445D" },
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
    }

    function verifyPayment(razorResponse, packageId, btn, originalText) {
        $.ajax({
            url: "{{ route('user.subscription.verify') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                package_id: packageId,
                start_date: $('#solarStartDate').val(),
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
