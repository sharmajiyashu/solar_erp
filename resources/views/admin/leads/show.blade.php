@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">

            <div class="content-body">

                <div class="card">

                    <div class="card-header">
                        <ul class="nav nav-tabs" id="leadTabs">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#customerTab">
                                    Customer Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leadTab">
                                    Lead Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#visitTab">
                                    Visit Management
                                </button>
                            </li>

                        </ul>
                    </div>

                    <div class="card-body">

                        <div class="tab-content">

                            {{-- ================= CUSTOMER TAB ================= --}}
                            <div class="tab-pane fade show active" id="customerTab">
                                @include('admin.leads.partials.customer')
                            </div>

                            {{-- ================= LEAD TAB ================= --}}
                            <div class="tab-pane fade" id="leadTab">
                                @include('admin.leads.partials.lead')
                            </div>

                            {{-- ================= VISIT TAB ================= --}}
                            <div class="tab-pane fade" id="visitTab">
                                @include('admin.leads.partials.visits')
                            </div>

                        </div>

                    </div>

                </div>



            </div>
        </div>
    </div>
@endsection
