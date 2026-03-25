@extends('layouts.frontend')

@section('content')
@push('styles')
<style>
    .auth-page-wrapper {
        min-height: 80vh;
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url("{{ url('public/frontend-assets/img/auth-bg.png') }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(15px) !important;
        -webkit-backdrop-filter: blur(15px) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 25px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37) !important;
    }
    .glass-card .card-header {
        background: rgba(255, 126, 32, 0.2) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    .glass-card .form-control {
        color: #fff !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.3) !important;
    }
    .glass-card .form-control:focus {
        border-bottom-color: var(--primary) !important;
    }
    .glass-card label, .glass-card .text-muted, .glass-card .form-check-label {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    .glass-card .btn-primary {
        background: linear-gradient(135deg, var(--primary), #ffa142) !important;
        border: none !important;
    }
</style>
@endpush

@section('content')
<div class="auth-page-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card glass-card shadow-lg wow zoomIn" data-wow-delay="0.1s">
                    <div class="card-header py-4 text-center">
                        <h3 class="text-white mb-0 fw-bold">Forgot Password</h3>
                        <p class="text-white-50 small mb-0">Recover your access</p>
                    </div>
                    <div class="card-body p-4 p-sm-5 text-center">
                        <p class="text-white-50 small mb-4">Enter your email and we'll send a code to reset your password.</p>
                        <form action="{{ route('password.otp') }}" method="POST" class="auth-form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control border-bottom" id="email" placeholder="Email Address" required style="background: transparent;">
                                        <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3 fw-bold shadow-sm" type="submit" style="border-radius: 12px;">
                                        SEND OTP <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <a href="{{ route('login') }}" class="small fw-bold text-white" style="text-decoration: underline;"><i class="fas fa-arrow-left me-2"></i>Back to login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('public/frontend-assets/js/auth_ajax.js') }}"></script>
@endpush
