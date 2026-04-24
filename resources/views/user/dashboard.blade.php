@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-4 px-0 px-md-3">
    <!-- Clean Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 text-center text-md-start">
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small fw-bold text-uppercase" style="letter-spacing: 1px;">
                    <li class="breadcrumb-item text-primary"><a href="#" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Overview</li>
                </ol>
            </nav>
            <h2 class="fw-black text-dark mb-0">Hello, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-muted mb-0 small fw-medium">Welcome back! Here's what's happening with your solar energy today.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0 d-none d-md-flex justify-content-end gap-2">
            <a href="{{ route('user.services') }}" class="btn btn-white btn-sm px-4 py-2 fw-bold">My Plans</a>
            <a href="{{ route('user.profile') }}" class="btn btn-primary btn-sm px-4 py-2 fw-bold text-white">Profile Settings</a>
        </div>
    </div>

    <!-- Simple Blue Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 border-start border-4 border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="icon-box p-2 rounded-3 bg-light-primary text-primary">
                            <i class="fa-solid fa-bolt-lightning fs-5"></i>
                        </div>
                        <span class="badge badge-primary small fw-bold" style="font-size: 0.6rem;">PLANS</span>
                    </div>
                    <div class="ps-1">
                        <h3 class="fw-black mb-0 text-dark" style="font-size: 1.5rem;">{{ $subscriptions->count() }}</h3>
                        <p class="text-muted small fw-bold text-uppercase mb-0 mt-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Active Plans</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 border-start border-4 border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="icon-box p-2 rounded-3 bg-light-primary text-primary">
                            <i class="fa-solid fa-handshake-angle fs-5"></i>
                        </div>
                        <span class="badge badge-primary small fw-bold" style="font-size: 0.6rem;">VISITS</span>
                    </div>
                    <div class="ps-1">
                        <h3 class="fw-black mb-0 text-dark" style="font-size: 1.5rem;">{{ $totalSlotsCount }}</h3>
                        <p class="text-muted small fw-bold text-uppercase mb-0 mt-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Visits</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 border-start border-4 border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="icon-box p-2 rounded-3 bg-light-primary text-primary">
                            <i class="fa-solid fa-spinner fs-5 fa-spin-pulse" style="--fa-animation-duration: 3s;"></i>
                        </div>
                        <span class="badge badge-primary small fw-bold" style="font-size: 0.6rem;">WAITS</span>
                    </div>
                    <div class="ps-1">
                        <h3 class="fw-black mb-0 text-dark" style="font-size: 1.5rem;">{{ $totalSlotsCount - $completedSlotsCount }}</h3>
                        <p class="text-muted small fw-bold text-uppercase mb-0 mt-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Pending</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 border-start border-4 border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="icon-box p-2 rounded-3 bg-light-primary text-primary">
                            <i class="fa-solid fa-award fs-5"></i>
                        </div>
                        <span class="badge badge-primary small fw-bold" style="font-size: 0.6rem;">DONE</span>
                    </div>
                    <div class="ps-1">
                        <h3 class="fw-black mb-0 text-dark" style="font-size: 1.5rem;">{{ $completedSlotsCount }}</h3>
                        <p class="text-muted small fw-bold text-uppercase mb-0 mt-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress & Timeline Sections - CLEANED BGs -->
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border border-light rounded-4 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark">Analytics & History</h5>
                    <div class="d-flex align-items-center rounded-pill px-3 py-1 border">
                        <span class="text-primary fw-black small me-2">{{ $totalSlotsCount > 0 ? round(($completedSlotsCount / $totalSlotsCount) * 100) : 0 }}% Completion</span>
                    </div>
                </div>

                <div class="progress rounded-pill shadow-inner mb-4" style="height: 10px; background: #f1f5f9;">
                    @php $percentage = $totalSlotsCount > 0 ? ($completedSlotsCount / $totalSlotsCount) * 100 : 0; @endphp
                    <div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-primary" style="width: {{ $percentage }}%"></div>
                </div>

                <div class="row text-center mb-4 g-2">
                    <div class="col-4 border-end">
                        <p class="text-muted small mb-0 fw-medium">Available</p>
                        <span class="fw-black text-dark fs-5">{{ $totalSlotsCount }}</span>
                    </div>
                    <div class="col-4 border-end">
                        <p class="text-muted small mb-0 fw-medium">Finished</p>
                        <span class="fw-black text-success fs-5">{{ $completedSlotsCount }}</span>
                    </div>
                    <div class="col-4">
                        <p class="text-muted small mb-0 fw-medium">Remaining</p>
                        <span class="fw-black text-warning fs-5">{{ $totalSlotsCount - $completedSlotsCount }}</span>
                    </div>
                </div>

                <div class="timeline-section ps-1 mt-4">
                    <h6 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 1px;">Upcoming Scheduled Visits</h6>
                    @forelse($upcomingSlots as $index => $slot)
                    <div class="timeline-compact d-flex mb-3 align-items-center p-2 rounded-3 border-light border hover-shadow-light transition-all shadow-hover">
                        <div class="text-primary fw-black rounded-3 px-3 py-1 me-3 text-center border" style="min-width: 60px;">
                            <span class="d-block" style="font-size: 1.1rem;">{{ $slot->service_date->format('d') }}</span>
                            <span class="d-block text-uppercase" style="font-size: 0.6rem;">{{ $slot->service_date->format('M') }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-dark small">{{ $slot->subscription->package->name }}</h6>
                            <small class="text-muted">Regular Maintenance Checkup</small>
                        </div>
                        <div class="text-end">
                            <span class="badge rounded-pill {{ $index == 0 ? 'bg-primary shadow-sm' : 'text-muted border' }} px-3 py-2" style="font-size: 0.7rem;">
                                {{ now()->startOfDay()->diffInDays($slot->service_date->startOfDay()) }} Days Left
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 rounded-4 border-dashed border">
                        <p class="text-muted small mb-0">No upcoming visits found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border border-light rounded-4 shadow-sm mb-4 overflow-hidden">
                <div class="card-header py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Active Subscriptions</h6>
                    <a href="{{ route('user.services') }}" class="btn btn-light btn-sm rounded-pill fw-bold text-primary px-3 border" style="font-size: 0.7rem;">Explore</a>
                </div>
                <div class="card-body p-4 pt-0">
                    @forelse($subscriptions as $sub)
                    <div class="compact-plan p-3 rounded-4 mb-3 border position-relative overflow-hidden shadow-hover" 
                         style="border-left: 5px solid var(--primary) !important;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-black mb-0 text-dark small">{{ strtoupper($sub->package->name) }}</h6>
                            <span class="text-primary fw-black fs-6">₹{{ number_format($sub->amount, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <div>
                                <p class="text-muted small mb-0">Valid Until</p>
                                <span class="fw-bold text-dark small">{{ $sub->end_date->format('d M, Y') }}</span>
                            </div>
                            <span class="badge border border-success border-opacity-25 text-success rounded-pill px-3 fw-bold small">Active</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 rounded-4 border">
                        <p class="text-muted small mb-0">No active plans found.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Profile Summary Card -->
            <div class="card border border-light rounded-4 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="user-avatar-premium me-3" style="width: 50px; height: 50px; position: relative;">
                        <div class="w-100 h-100 rounded-circle d-flex align-items-center justify-content-center text-white fs-5 fw-bold shadow-sm" 
                             style="background: linear-gradient(135deg, var(--primary) 0%, #27445D 100%);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="status-indicator bg-success position-absolute bottom-0 end-0 border border-white border-2 rounded-circle" style="width: 12px; height: 12px;"></div>
                    </div>
                    <div class="text-start flex-grow-1">
                        <h6 class="fw-black mb-0 text-dark">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary btn-sm p-2 rounded-3 border-light shadow-sm"><i class="fa-solid fa-user-gear"></i></a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger-soft btn-sm p-2 rounded-3 shadow-sm"><i class="fa-solid fa-power-off"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; color: #334155; font-family: 'Plus Jakarta Sans', sans-serif; }
    .fw-black { font-weight: 900 !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .shadow-lg { box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.1) !important; }
    
    .btn-primary { background-color: #71bbb2; border: none; transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px -3px rgba(113, 187, 178, 0.3); }
    
    .btn-glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); }
    .btn-danger-soft { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; }
    .btn-danger-soft:hover { background: #ef4444; color: white; }

    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 25px -5px rgba(0,0,0,0.1) !important; }
    .card-glow { position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); opacity: 0; transition: opacity 0.3s; pointer-events: none; }
    .stat-card:hover .card-glow { opacity: 1; }
    
    .shape-1 { position: absolute; width: 300px; height: 300px; background: rgba(113, 187, 178, 0.1); border-radius: 50%; top: -100px; right: -50px; filter: blur(40px); }
    
    .shadow-hover:hover { box-shadow: 0 5px 15px -3px rgba(0,0,0,0.05) !important; border-color: var(--primary) !important; }
    .transition-all { transition: all 0.3s ease; }
    
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06) !important; }
</style>
@endsection
