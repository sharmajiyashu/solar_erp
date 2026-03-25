@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Slim Premium Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card p-4 rounded-4 border-0 shadow-lg position-relative overflow-hidden" 
                 style="background: linear-gradient(135deg, #1e293b 0%, #27445D 100%); min-height: 120px; display: flex; align-items: center;">
                <div class="position-relative z-index-2 w-100">
                    <div class="row align-items-center">
                        <div class="col-md-9 text-center text-md-start">
                            <h3 class="fw-black text-white mb-1">Account Management</h3>
                            <p class="text-white-50 mb-0 fw-medium small">Update your profile details and manage your security settings.</p>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0 d-none d-md-block">
                             <div class="icon-circle p-3 rounded-circle d-inline-flex border border-white border-opacity-25 shadow-sm">
                                <i class="fas fa-user-shield text-white fs-4"></i>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="shape-1"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Container: Profile Details Form -->
        <div class="col-lg-7">
            <form id="profileUpdateForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card border border-light rounded-4 shadow-sm h-100">
                    <div class="card-header py-3 px-4 border-0 d-flex align-items-center">
                        <h6 class="fw-black mb-0 text-dark small text-uppercase" style="letter-spacing: 1px;">Personal Information</h6>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <!-- Profile Image Section -->
                        <div class="d-flex align-items-center mb-4 p-3 rounded-4 border border-light shadow-hover transition-all">
                            <div class="position-relative">
                                <img id="imagePreview" 
                                     src="{{ $user->profile_image ? url('public/uploads/profile/'.$user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=71bbb2&color=fff&size=128' }}" 
                                     class="rounded-circle border border-light shadow-sm" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <label for="profile_image" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                       style="width: 28px; height: 28px; cursor: pointer; border: 2px solid white;">
                                    <i class="fas fa-camera" style="font-size: 0.75rem;"></i>
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                            </div>
                            <div class="ms-4">
                                <h6 class="fw-black mb-1 text-dark small">Profile Picture</h6>
                                <p class="text-muted small mb-0">PNG, JPG or GIF. Max 2MB.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" value="{{ $user->mobile }}" required>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">Address</label>
                                <textarea name="address" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" rows="2">{{ $user->address }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">City</label>
                                <input type="text" name="city" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" value="{{ $user->city }}">
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">State</label>
                                <input type="text" name="state" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" value="{{ $user->state }}">
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">Pincode</label>
                                <input type="text" name="pincode" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" value="{{ $user->pincode }}">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-black shadow-sm" id="saveProfileBtn">
                                Update Details <i class="fas fa-save ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Container: Security/Password Form -->
        <div class="col-lg-5">
            <form id="passwordUpdateForm" action="{{ route('user.profile.password.update') }}" method="POST">
                @csrf
                <div class="card border border-light rounded-4 shadow-sm h-100">
                    <div class="card-header py-3 px-4 border-0 d-flex align-items-center">
                        <h6 class="fw-black mb-0 text-dark small text-uppercase" style="letter-spacing: 1px;">Account Security</h6>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <!-- Email (Readonly) -->
                        <div class="mb-4 p-3 rounded-4 border border-light">
                            <div class="d-flex align-items-center">
                                <div class="icon-box-sm border border-light p-2 rounded-3 me-3 text-muted"> <i class="fas fa-envelope"></i> </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small fw-black text-uppercase mb-0" style="font-size: 0.55rem;">Registered Email</label>
                                    <div class="fw-bold small text-dark">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light opacity-50">
                        <h6 class="fw-black mb-3 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Update Password</h6>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">Current Password</label>
                                <div class="position-relative">
                                    <input type="password" name="current_password" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" placeholder="Enter current password" required>
                                    <i class="fas fa-key position-absolute end-0 top-50 translate-middle-y me-3 text-muted small"></i>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">New Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" placeholder="Minimum 8 characters" required>
                                    <i class="fas fa-lock position-absolute end-0 top-50 translate-middle-y me-3 text-muted small"></i>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small fw-black text-uppercase mb-2" style="font-size: 0.6rem;">Confirm New Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" class="form-control rounded-3 border-light py-2 px-3 fw-bold small" placeholder="Repeat new password" required>
                                    <i class="fas fa-shield-halved position-absolute end-0 top-50 translate-middle-y me-3 text-muted small"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-black shadow-sm" id="savePasswordBtn">
                                Change Password <i class="fas fa-shield-alt ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Image Preview
    $('#profile_image').change(function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) { $('#imagePreview').attr('src', event.target.result); }
            reader.readAsDataURL(file);
        }
    });

    // Profile Details Submit
    $('#profileUpdateForm').on('submit', function(e) {
        e.preventDefault();
        const submitBtn = $('#saveProfileBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Updating...');
        
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalText);
                if (response.status) {
                    Toastify({ text: response.message, backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)" }).showToast();
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                const response = xhr.responseJSON;
                let msg = response && response.errors ? Object.values(response.errors)[0][0] : (response.message || "Error updating profile");
                Toastify({ text: msg, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)" }).showToast();
            }
        });
    });

    // Password Update Submit
    $('#passwordUpdateForm').on('submit', function(e) {
        e.preventDefault();
        const submitBtn = $('#savePasswordBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Updating...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalText);
                if (response.status) {
                    Toastify({ text: response.message, backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)" }).showToast();
                    $('#passwordUpdateForm')[0].reset();
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                const response = xhr.responseJSON;
                let msg = response && response.errors ? Object.values(response.errors)[0][0] : (response.message || "Error updating password");
                Toastify({ text: msg, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)" }).showToast();
            }
        });
    });
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
    
    .btn-outline-primary { color: var(--primary); border-color: var(--primary); transition: all 0.3s ease; }
    .btn-outline-primary:hover { background-color: var(--primary); color: white; transform: translateY(-2px); }

    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(113, 187, 178, 0.1); }
    .shadow-hover:hover { box-shadow: 0 5px 15px -3px rgba(0,0,0,0.05) !important; border-color: var(--primary) !important; }
    .transition-all { transition: all 0.3s ease; }
    
    .shape-1 { position: absolute; width: 300px; height: 300px; background: rgba(113, 187, 178, 0.1); border-radius: 50%; top: -100px; right: -50px; filter: blur(40px); }
</style>
@endsection
