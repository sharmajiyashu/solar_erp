@extends('admin.layouts.app')

@section('content')
    @push('css_links')
        <style>
            .error {
                color: #a93c3d !important;
                font-weight: 500;
            }
        </style>
    @endpush

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Enquiry</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.enquiries.index') }}">Enquiries</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        {{ !empty($enquiry->id) ? 'Edit' : 'Create' }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    @if (!empty($enquiry->id))
                                        <form class="form" action="{{ route('admin.enquiries.update', $enquiry->id) }}"
                                            method="POST" id="submitFrom">
                                            @method('PUT')
                                        @else
                                            <form class="form" action="{{ route('admin.enquiries.store') }}"
                                                method="POST" id="submitFrom">
                                    @endif

                                    @csrf

                                    <div class="row">

                                        @if (isset($enquiry) && $enquiry->exists)
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label">Enquiry No</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $enquiry->enquiry_no }}" readonly>
                                            </div>
                                        @endif

                                        <!-- Customer Name -->
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Customer Name</label>
                                            <input type="text" name="customer_name" class="form-control"
                                                placeholder="Enter customer full name"
                                                value="{{ old('customer_name', $enquiry->customer_name ?? '') }}">
                                            <span class="text-danger validation-class" id="customer_name-submit_errors">

                                            </span>
                                        </div>

                                        <!-- Mobile -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">Mobile</label>
                                            <input type="text" name="mobile" class="form-control"
                                                placeholder="Enter mobile number"
                                                value="{{ old('mobile', $enquiry->mobile ?? '') }}">
                                            <span class="text-danger validation-class" id="mobile-submit_errors">
                                            </span>
                                        </div>

                                        <!-- Alternate Mobile -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">Alternate Mobile</label>
                                            <input type="text" name="alternate_mobile" class="form-control"
                                                placeholder="Enter alternate mobile"
                                                value="{{ old('alternate_mobile', $enquiry->alternate_mobile ?? '') }}">
                                            <span class="text-danger validation-class" id="alternate_mobile-submit_errors">
                                            </span>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Enter email address"
                                                value="{{ old('email', $enquiry->email ?? '') }}">
                                            <span class="text-danger validation-class" id="email-submit_errors">
                                            </span>
                                        </div>


                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Project Size in KW</label>

                                            <input type="number" step="0.01" name="project_size" class="form-control"
                                                placeholder="Enter project size in KW"
                                                value="{{ old('project_size', $enquiry->project_size ?? '') }}">

                                            <span class="text-danger validation-class" id="project_size-submit_errors">
                                                @error('project_size')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>

                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Price Quote</label>

                                            <input type="number" step="0.01" name="price_quote" class="form-control"
                                                placeholder="Enter price quote"
                                                value="{{ old('price_quote', $enquiry->price_quote ?? '') }}">

                                            <span class="text-danger validation-class" id="price_quote-submit_errors">
                                                @error('price_quote')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>



                                        <!-- Source -->
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Source</label>

                                            @php
                                                $sources = [
                                                    'FIELD SALES EMP',
                                                    'REFERANCE',
                                                    'TELECALLING',
                                                    'CONNECTORS',
                                                    'DIGITAL MARKETING',
                                                    'CHARTED ACCOUNTS',
                                                ];
                                            @endphp

                                            <select name="source" class="form-control">

                                                <option value="">Select Source</option>

                                                @foreach ($sources as $source)
                                                    <option value="{{ $source }}"
                                                        {{ old('source', $enquiry->source ?? '') == $source ? 'selected' : '' }}>
                                                        {{ $source }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            <span class="text-danger validation-class" id="source-submit_errors"></span>
                                        </div>

                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Solar Type</label>

                                            @php
                                                $solorTypes = ['RESIDENTIAL', 'C&I'];
                                            @endphp

                                            <select name="solar_type" class="form-control">

                                                <option value="">Select Type</option>

                                                @foreach ($solorTypes as $type)
                                                    <option value="{{ $type }}"
                                                        {{ old('solar_type', $enquiry->solar_type ?? '') == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            <span class="text-danger validation-class" id="solar_type-submit_errors"></span>
                                        </div>

                                        <!-- Address -->
                                        <div class="col-12 mb-1">
                                            <label class="form-label">Address</label>
                                            <textarea name="address" class="form-control" placeholder="Enter full address">{{ old('address', $enquiry->address ?? '') }}</textarea>
                                            <span class="text-danger validation-class" id="address-submit_errors">

                                            </span>
                                        </div>

                                        <!-- City -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control"
                                                placeholder="Enter city" value="{{ old('city', $enquiry->city ?? '') }}">
                                            <span class="text-danger validation-class" id="city-submit_errors">
                                            </span>
                                        </div>

                                        <!-- State -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control"
                                                placeholder="Enter state" value="{{ old('state', $enquiry->state ?? '') }}">
                                            <span class="text-danger validation-class" id="state-submit_errors">
                                            </span>
                                        </div>

                                        <!-- Pincode -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">Pincode</label>
                                            <input type="text" name="pincode" class="form-control"
                                                placeholder="Enter pincode" value="{{ old('pincode', $enquiry->pincode ?? '') }}">
                                            <span class="text-danger validation-class" id="pincode-submit_errors">
                                            </span>
                                        </div>


                                        <!-- Status -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="next_followup"
                                                    {{ old('status', $enquiry->status ?? '') == 'next_followup' ? 'selected' : '' }}>
                                                    Call Back Date</option>
                                                <option value="converted_to_lead"
                                                    {{ old('status', $enquiry->status ?? '') == 'converted_to_lead' ? 'selected' : '' }}>
                                                    Converted To Lead</option>

                                            </select>
                                            <span class="text-danger validation-class" id="status-submit_errors">

                                            </span>
                                        </div>

                                        <!-- Next Followup Date -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label">Next Followup Date</label>
                                            <input type="date" name="next_followup_date" class="form-control"
                                                value="{{ old('next_followup_date', isset($enquiry->next_followup_date) ? \Carbon\Carbon::parse($enquiry->next_followup_date)->format('Y-m-d') : '') }}">
                                            <span class="text-danger validation-class"
                                                id="next_followup_date-submit_errors">

                                            </span>
                                        </div>

                                        <!-- Remarks -->
                                        <div class="col-12 mb-1">
                                            <label class="form-label">Remarks</label>
                                            <textarea name="remarks" class="form-control" placeholder="Enter remarks or discussion notes">{{ old('remarks', $enquiry->remarks ?? '') }}</textarea>
                                            <span class="text-danger validation-class" id="remarks-submit_errors">

                                            </span>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1">Submit</button>
                                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                        </div>

                                    </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#submitFrom').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var $form = $('#submitFrom');
                var url = $form.attr('action');
                var formData = new FormData($form[0]);
                $('.validation-class').html('');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#form-loader').show();
                        $('.spinner-loader').css('display', 'block');
                    },
                    success: function(res) {
                        location.reload();

                        $('#form-loader').hide();
                    },
                    error: function(res) {
                        if (res.status == 400 || res.status == 422) {
                            if (res.responseJSON && res.responseJSON.errors) {
                                var error = res.responseJSON.errors
                                $.each(error, function(key, value) {
                                    $("#" + key + "-submit_errors").text(value[0]);
                                });
                            }
                        }
                        $('#form-loader').hide();
                    }
                });
            });
        });
    </script>
@endsection
