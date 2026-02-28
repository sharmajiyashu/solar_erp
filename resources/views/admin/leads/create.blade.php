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

    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">

            <div class="content-header row">
                <div class="col-12">
                    <h2>Create Lead</h2>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form class="form"
                                        action="{{ !empty($lead->id) ? route('admin.leads.update', $lead->id) : route('admin.leads.store') }}"
                                        method="POST" id="submitForm">

                                        @csrf

                                        @if (!empty($lead->id))
                                            @method('PUT')
                                        @endif

                                        <div class="row">

                                            <!-- ================= CUSTOMER DETAILS ================= -->

                                            <div class="col-12">
                                                <h4 class="mb-2 text-primary">Customer Details</h4>
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label>Customer Name</label>
                                                <input type="text" name="customer_name" class="form-control"
                                                    placeholder="Enter customer name"
                                                    value="{{ old('customer_name', $lead->customer->name ?? '') }}">

                                                <span class="text-danger validation-class"
                                                    id="customer_name-submit_errors"></span>
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label>Customer Phone</label>
                                                <input type="text" name="customer_phone" class="form-control"
                                                    placeholder="Enter mobile number"
                                                    value="{{ old('customer_phone', $lead->customer->mobile ?? '') }}">

                                                <span class="text-danger validation-class"
                                                    id="customer_phone-submit_errors"></span>
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label>Customer Email</label>
                                                <input type="email" name="customer_email" class="form-control"
                                                    placeholder="Enter email"
                                                    value="{{ old('customer_email', $lead->customer->email ?? '') }}">
                                            </div>

                                            <div class="col-12 mb-1">
                                                <label>Customer Address</label>
                                                <textarea name="customer_address" class="form-control" placeholder="Enter full address">{{ old('customer_address', $lead->customer->address ?? '') }}</textarea>
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" placeholder="City"
                                                    value="{{ old('city', $lead->customer->city ?? '') }}">
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label>State</label>
                                                <input type="text" name="state" class="form-control"
                                                    placeholder="State"
                                                    value="{{ old('state', $lead->customer->state ?? '') }}">
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label>Pincode</label>
                                                <input type="text" name="pincode" class="form-control"
                                                    placeholder="Pincode"
                                                    value="{{ old('pincode', $lead->customer->pincode ?? '') }}">
                                            </div>

                                            <hr>

                                            <!-- ================= LEAD DETAILS ================= -->

                                            <div class="col-12">
                                                <h4 class="mb-2 text-primary">Lead Details</h4>
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label>Lead Status</label>
                                                <select name="status" class="form-control">

                                                    @php
                                                        $statuses = [
                                                            'pending',
                                                            'in_progress',
                                                            'completed',
                                                            'cancelled',
                                                        ];
                                                    @endphp

                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status }}"
                                                            {{ old('status', $lead->status ?? 'pending') == $status ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                <span class="text-danger validation-class" id="status-submit_errors"></span>
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label>Assign To</label>
                                                <select name="assigned_to" class="form-control">

                                                    <option value="">Select User</option>

                                                    @foreach (\App\Models\User::all() as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ old('assigned_to', $lead->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <div class="col-12 mb-1">
                                                <label>Remarks</label>
                                                <textarea name="remarks" class="form-control" placeholder="Enter remarks">{{ old('remarks', $lead->remarks ?? '') }}</textarea>

                                                <span class="text-danger validation-class"
                                                    id="remarks-submit_errors"></span>
                                            </div>

                                            <div class="col-12 mt-2">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ !empty($lead->id) ? 'Update Lead' : 'Create Lead' }}
                                                </button>
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

            $('#submitForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var formData = new FormData(this);

                $('.validation-class').html('');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // window.location.href = "{{ route('admin.leads.pending') }}";
                    },
                    error: function(res) {
                        if (res.status === 422) {
                            $.each(res.responseJSON.errors, function(key, value) {
                                $("#" + key + "-submit_errors").text(value[0]);
                            });
                        }
                    }
                });

            });

        });
    </script>
@endsection
