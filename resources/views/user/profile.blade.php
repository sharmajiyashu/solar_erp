@extends('user.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2 text-primary"></i>Profile Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form id="profileUpdateForm" action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email Address</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                                <small class="text-muted">Email cannot be changed.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ $user->dob }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ $user->address }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">City</label>
                                <input type="text" name="city" class="form-control" value="{{ $user->city }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">State</label>
                                <input type="text" name="state" class="form-control" value="{{ $user->state }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Pincode</label>
                                <input type="text" name="pincode" class="form-control" value="{{ $user->pincode }}">
                            </div>
                            
                            <hr class="my-4">
                            <h6 class="mb-3 text-primary">Change Password <small class="text-muted">(Leave blank to keep current)</small></h6>
                            
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                    Save Changes <i class="fas fa-save ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#profileUpdateForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Saving...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalText);
                if (response.status) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    }).showToast();
                    
                    if (form.find('[name="password"]').val()) {
                        form.find('[name="password"], [name="password_confirmation"]').val('');
                    }
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                const response = xhr.responseJSON;
                Toastify({
                    text: response.message || "Something went wrong!",
                    duration: 3000,
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }).showToast();
            }
        });
    });
});
</script>
@endpush
