@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="col-12">
                <h2 class="content-header-title">Complete visit</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.solar.my_services') }}">My services</a></li>
                    <li class="breadcrumb-item active">Verify</li>
                </ol>
            </div>
        </div>
        <div class="content-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted small mb-3">The <strong>customer (user)</strong> reads the 6-character code from their portal; you enter it here to complete this <strong>ServiceSlot</strong> #{{ $slot->id }} — {{ $slot->service_date?->format('M d, Y') }}. After completion they can rate you as the technician.</p>
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <form method="post" action="{{ route('admin.solar.slots.complete') }}">
                                @csrf
                                <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                                <div class="mb-2">
                                    <label class="form-label">Verification code</label>
                                    <input type="text" name="verification_code" class="form-control text-uppercase" maxlength="6" required autocomplete="off">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Mark completed</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
