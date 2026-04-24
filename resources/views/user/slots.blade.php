@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-4 px-0 px-md-3">
    <!-- Clean Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 text-center text-md-start">
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small fw-bold text-uppercase" style="letter-spacing: 1px;">
                    <li class="breadcrumb-item text-primary"><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service Visits</li>
                </ol>
            </nav>
            <h2 class="fw-black text-dark mb-0">Maintenance Schedule</h2>
            <p class="text-muted mb-0 small fw-medium">View and manage your scheduled solar panel maintenance visits.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 px-4 py-3 d-flex align-items-center">
            <i class="fas fa-check-circle me-3 fs-4"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 px-4 py-3 d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-black text-dark mb-0">Maintenance Schedule</h6>
            <span class="badge bg-light text-primary rounded-pill px-3 py-2 fw-bold small border">
                Total Visits: {{ $slots->total() }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase small fw-black text-muted" style="letter-spacing: 1px;">Visit Date</th>
                        <th class="text-uppercase small fw-black text-muted" style="letter-spacing: 1px;">Service Plan</th>
                        <th class="text-uppercase small fw-black text-muted" style="letter-spacing: 1px;">Status</th>
                        <th class="text-uppercase small fw-black text-muted" style="letter-spacing: 1px;">Technician</th>
                        <th class="text-uppercase small fw-black text-muted text-center" style="letter-spacing: 1px;">Security Code</th>
                        <th class="text-uppercase small fw-black text-muted" style="letter-spacing: 1px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slots as $slot)
                    <tr class="transition-all hover-light">
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $slot->service_date?->format('d M, Y') }}</div>
                            <small class="text-muted small">#{{ $slot->id }}</small>
                        </td>
                        <td>
                            <span class="fw-medium text-dark">{{ $slot->subscription?->package?->name ?? 'Custom Plan' }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'badge-secondary',
                                    'assigned' => 'badge-primary bg-blue-soft',
                                    'completed' => 'badge-primary'
                                ][$slot->status] ?? 'badge-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }} border border-opacity-25 rounded-pill px-3 py-2 fw-bold small text-capitalize">
                                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> {{ $slot->status }}
                            </span>
                        </td>
                        <td>
                            @if($slot->assignedAdmin)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user-gear small"></i>
                                    </div>
                                    <span class="fw-bold text-dark small">{{ $slot->assignedAdmin->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small italic">Not Assigned</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center bg-white border border-primary border-opacity-25 p-2 px-3 rounded-3 shadow-sm hover-shadow-primary transition-all">
                                <div class="me-2 pe-2 border-end text-primary">
                                    <i class="fas fa-shield-alt small"></i>
                                </div>
                                <code class="user-select-all fw-black text-dark fs-6 mb-0" style="letter-spacing: 3px; font-family: 'Monaco', 'Consolas', monospace; color: #1e293b !important;">{{ $slot->verification_code }}</code>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED && $slot->assigned_to && !$slot->technicianReview)
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" 
                                            data-bs-toggle="modal" data-bs-target="#reviewModal{{ $slot->id }}">
                                        <i class="fas fa-star me-1"></i> Rate
                                    </button>
                                @elseif($slot->technicianReview)
                                    <div class="text-primary d-flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $slot->technicianReview->rating ? '' : 'opacity-25' }}" style="font-size: 0.7rem;"></i>
                                        @endfor
                                    </div>
                                @endif
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" 
                                        data-bs-toggle="modal" data-bs-target="#ticketModal{{ $slot->id }}">
                                    <i class="fas fa-headset me-1"></i> Ticket
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Review Modal -->
                    @if($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED && $slot->assigned_to && !$slot->technicianReview)
                    <div class="modal fade" id="reviewModal{{ $slot->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <form method="post" action="{{ route('user.slots.technician-review', $slot) }}">
                                    @csrf
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="modal-title fw-black">Rate Your Technician</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                                            <div class="avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                                <i class="fas fa-user-wrench"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $slot->assignedAdmin?->name ?? 'Technician' }}</h6>
                                                <small class="text-muted">Visit #{{ $slot->id }} • {{ $slot->service_date?->format('d M, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-black text-muted text-uppercase">Performance Rating</label>
                                            <div class="rating-input d-flex gap-2 justify-content-between mb-2">
                                                @for($r = 1; $r <= 5; $r++)
                                                <div class="flex-fill">
                                                    <input type="radio" name="rating" id="r{{ $slot->id }}_{{ $r }}" value="{{ $r }}" class="btn-check" required>
                                                    <label class="btn btn-outline-primary w-100 rounded-3 py-2 fw-black" for="r{{ $slot->id }}_{{ $r }}">
                                                        {{ $r }} <i class="fas fa-star ms-1 small"></i>
                                                    </label>
                                                </div>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-black text-muted text-uppercase">Your Feedback</label>
                                            <textarea name="comment" class="form-control rounded-4 border-light bg-light" rows="3" maxlength="5000" placeholder="Tell us about the service experience..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-black shadow-sm">Submit Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Ticket Modal -->
                    <div class="modal fade" id="ticketModal{{ $slot->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <form method="post" action="{{ route('user.tickets.store') }}">
                                    @csrf
                                    <input type="hidden" name="service_slot_id" value="{{ $slot->id }}">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="modal-title fw-black text-primary">Need Assistance?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="text-muted small mb-4">Create a support ticket regarding visit #{{ $slot->id }}. Our team will respond shortly.</p>
                                        <div class="mb-3">
                                            <label class="form-label small fw-black text-muted text-uppercase">Subject</label>
                                            <input type="text" name="subject" class="form-control rounded-3 border-light bg-light" required maxlength="255" placeholder="Short description of the issue">
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-black text-muted text-uppercase">Message Detail</label>
                                            <textarea name="message" class="form-control rounded-4 border-light bg-light" rows="4" required maxlength="5000" placeholder="Please describe your concern in detail..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-secondary w-100 rounded-pill py-2 fw-black shadow-sm">
                                            Create Ticket <i class="fas fa-paper-plane ms-2"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Premium Pagination -->
        @if($slots->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4 d-flex justify-content-center custom-pagination">
            {{ $slots->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .bg-light-warning { background-color: #fff8eb; }
    .bg-light-primary { background-color: #e8f4f3; }
    .bg-light-success { background-color: #e6f7ef; }
    
    .custom-table th { padding: 1.2rem 0.75rem; border-bottom: none; }
    .custom-table td { padding: 1.2rem 0.75rem; border-bottom: 1px solid #f1f5f9; }
    .custom-table tr:last-child td { border-bottom: none; }
    
    .hover-light:hover { background-color: #f8fafc; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-shadow-primary:hover { 
        box-shadow: 0 10px 20px -5px rgba(113, 187, 178, 0.2) !important; 
        transform: translateY(-1px);
        border-color: var(--primary) !important;
    }
    
    /* Pagination Styling */
    .custom-pagination .pagination {
        gap: 8px;
        margin-bottom: 0;
    }
    .custom-pagination .page-item .page-link {
        border-radius: 12px !important;
        border: 1px solid #f1f5f9;
        color: #64748b;
        font-weight: 700;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 5px 15px -3px rgba(113, 187, 178, 0.4);
    }
    .custom-pagination .page-item.disabled .page-link {
        background-color: #f8fafc;
        border-color: #f1f5f9;
    }
    .custom-pagination .page-item .page-link:hover:not(.active) {
        background-color: #f1f5f9;
        color: var(--primary);
        border-color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .custom-table thead { display: none; }
        .custom-table tr { 
            display: block; 
            margin-bottom: 1rem; 
            padding: 1rem; 
            border: 1px solid #f1f5f9; 
            border-radius: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .custom-table td { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border: none; 
            padding: 0.5rem 0;
            text-align: right;
        }
        .custom-table td::before {
            content: attr(data-label);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.65rem;
            color: #64748b;
            text-align: left;
        }
    }
</style>
@endpush
@endsection
