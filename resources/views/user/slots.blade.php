@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-3">
    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-3">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-3">{{ $errors->first() }}</div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-black mb-1">My service slots</h3>
            <p class="text-muted small mb-0">Your visits: verification code for the technician, and after completion you can <strong>rate the technician</strong> (not an admin rating you).</p>
        </div>
        <a href="{{ route('user.tickets.index') }}" class="btn btn-outline-primary rounded-pill">My tickets</a>
    </div>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Technician</th>
                        <th>Your code</th>
                        <th>Rate technician</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slots as $slot)
                    <tr>
                        <td class="fw-bold">{{ $slot->service_date?->format('d M Y') }}</td>
                        <td>{{ $slot->subscription?->package?->name }}</td>
                        <td>
                            @if($slot->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($slot->status === 'assigned')
                                <span class="badge bg-primary">Assigned</span>
                            @elseif($slot->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-secondary">{{ $slot->status }}</span>
                            @endif
                        </td>
                        <td>{{ $slot->assignedAdmin?->name ?? '—' }}</td>
                        <td><code class="user-select-all">{{ $slot->verification_code }}</code></td>
                        <td>
                            @if($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED && $slot->assigned_to)
                                @if($slot->technicianReview)
                                    <span class="text-warning small">@for($i = 1; $i <= 5; $i++){{ $i <= $slot->technicianReview->rating ? '★' : '☆' }}@endfor</span>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $slot->id }}">Rate technician</button>
                                @endif
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $slot->id }}">Ticket</button>
                        </td>
                    </tr>
                    @if($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED && $slot->assigned_to && !$slot->technicianReview)
                    <div class="modal fade" id="reviewModal{{ $slot->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4">
                                <form method="post" action="{{ route('user.slots.technician-review', $slot) }}">
                                    @csrf
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-black">Rate {{ $slot->assignedAdmin?->name ?? 'technician' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small text-muted">This review is visible to administrators. It is tied to visit #{{ $slot->id }}.</p>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Rating</label>
                                            <select name="rating" class="form-select rounded-3" required>
                                                <option value="">Choose…</option>
                                                @for($r = 5; $r >= 1; $r--)
                                                    <option value="{{ $r }}">{{ $r }} — @for($i=0;$i<$r;$i++)★@endfor</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold">Comment (optional)</label>
                                            <textarea name="comment" class="form-control rounded-3" rows="3" maxlength="5000" placeholder="How was the visit?"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Submit review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="modal fade" id="ticketModal{{ $slot->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4">
                                <form method="post" action="{{ route('user.tickets.store') }}">
                                    @csrf
                                    <input type="hidden" name="service_slot_id" value="{{ $slot->id }}">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-black">New ticket</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Subject</label>
                                            <input type="text" name="subject" class="form-control rounded-3" required maxlength="255">
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold">Message</label>
                                            <textarea name="message" class="form-control rounded-3" rows="4" required maxlength="5000"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">{{ $slots->links() }}</div>
    </div>
</div>
@endsection
