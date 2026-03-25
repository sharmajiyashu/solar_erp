@extends('user.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Quick Stats -->
        <div class="col-md-4">
            <div class="card p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fas fa-solar-panel text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Services</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fas fa-check-circle text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Active Projects</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fas fa-envelope text-warning fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Support Tickets</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Activity</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-plus text-primary me-3"></i>
                                            <span>Account Created</span>
                                        </div>
                                    </td>
                                    <td>{{ Auth::user()->created_at->format('M d, Y') }}</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                @if(Auth::user()->email_verified_at)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-double text-success me-3"></i>
                                            <span>Email Verified</span>
                                        </div>
                                    </td>
                                    <td>{{ Auth::user()->email_verified_at->format('M d, Y') }}</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Widget -->
        <div class="col-lg-4">
            <div class="card text-center p-4">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                <div class="d-grid">
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-primary btn-sm rounded-pill">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
