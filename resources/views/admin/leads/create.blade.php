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



                                            <div class="col-md-3 mb-2">
                                                <label>Visit Date</label>
                                                <input type="date" name="visit_date" class="form-control">
                                                <span class="text-danger validation-class"
                                                    id="visit_date-submit_errors"></span>
                                            </div>

                                            <div class="col-md-3 mb-1">
                                                <label>Visit Assign To</label>
                                                <select name="assigned_to" class="form-control">
                                                    <option value="">Select User</option>
                                                    @foreach ($visitUsers as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ old('assigned_to', $lead->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <span class="text-danger validation-class"
                                                    id="assigned_to-submit_errors"></span>
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
                        window.location.href = res;
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
