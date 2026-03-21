@extends('layouts.frontend')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Services</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Services</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Service Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Our Services</h6>
                <h1 class="mb-4">We Are Pioneers In The World Of Renewable Energy</h1>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded overflow-hidden shadow-sm bg-white">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/img-600x400-1.jpg') }}" alt="">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-solar-panel fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Residential Solar</h4>
                            <p>Customized rooftop solar solutions for homes to reduce electricity bills and increase energy independence.</p>
                            <a class="small fw-medium" href="">Read More<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded overflow-hidden shadow-sm bg-white">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/img-600x400-2.jpg') }}" alt="">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-industry fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Commercial Solar</h4>
                            <p>High-efficiency solar systems for commercial buildings, factories, and schools to optimize operational costs.</p>
                            <a class="small fw-medium" href="">Read More<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded overflow-hidden shadow-sm bg-white">
                        <img class="img-fluid" src="{{ url('public/frontend-assets/img/img-600x400-3.jpg') }}" alt="">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-tools fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Maintenance & O&M</h4>
                            <p>Comprehensive operation and maintenance services ensure your solar plant performs at peak efficiency.</p>
                            <a class="small fw-medium" href="">Read More<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->

    @if($packages->count() > 0)
    <style>
        .service-package-section {
            background: #fcfcfc;
        }
        .service-package-row {
            transition: all 0.3s ease;
            border-radius: 15px !important;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: stretch;
        }
        .service-package-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
            border-color: var(--primary);
        }
        .package-badge-side {
            background: var(--primary);
            color: #000;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 1px;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            min-width: 40px;
            opacity: 0.8;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
        }
        .feature-item-inline {
            background: #fbfbfb;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            border-left: 3px solid #28a745;
            display: flex;
            align-items: center;
        }
        .price-section-inline {
            background: #fdfdfd;
            border-left: 1px solid #eee;
            min-width: 240px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        @media (max-width: 991px) {
            .service-package-row {
                flex-direction: column;
            }
            .price-section-inline {
                border-left: none;
                border-top: 1px solid #eee;
                width: 100%;
                padding: 1.5rem;
            }
            .package-badge-side {
                writing-mode: horizontal-tb;
                transform: none;
                width: 100%;
                padding: 8px;
            }
        }
        .btn-enquire-simple {
            background: var(--primary);
            border: none;
            color: #000;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .btn-enquire-simple:hover {
            filter: brightness(0.9);
            transform: translateY(-1px);
            color: #000;
        }
    </style>
    <!-- Service Packages Start -->
    <div class="container-xxl py-5 service-package-section">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary text-uppercase fw-bold">Maintenance Plans</h6>
                <h1 class="mb-4">Standardized Solar Care</h1>
                <p class="small text-muted">Premium maintenance packages designed to keep your solar investment shining bright and performing at its max.</p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach($packages as $package)
                <div class="col-12 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index + 1) }}s">
                    <div class="service-package-row shadow-sm">
                        <div class="package-badge-side d-none d-lg-flex">
                            SERVICE PLAN
                        </div>
                        <div class="p-4 flex-grow-1 d-flex flex-column justify-content-center">
                            <div class="mb-2">
                                <span class="badge bg-light text-primary border rounded-pill px-3 mb-1" style="font-size: 0.7rem;">Solar Maintenance</span>
                                <h4 class="fw-bold text-dark mb-1">{{ $package->name }}</h4>
                            </div>
                            <p class="text-muted mb-4 small" style="max-width: 600px;">{{ $package->description }}</p>
                            
                            @if($package->features)
                            <div class="feature-grid">
                                @foreach($package->features as $feature)
                                <div class="feature-item-inline">
                                    <i class="fa fa-check text-success me-2" style="font-size: 0.7rem;"></i>
                                    <span class="text-secondary">{{ $feature }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="price-section-inline shadow-xs">
                            <span class="text-muted x-small text-uppercase fw-bold mb-1">Starting At</span>
                            <h3 class="text-primary fw-bold mb-3">₹{{ number_format($package->price, 0) }}</h3>
                            <button class="btn btn-primary btn-enquire-simple rounded-pill px-4 py-2 w-100 enquire-package" 
                                    data-name="{{ $package->name }}" 
                                    data-price="{{ $package->price }}">
                                <i class="fa fa-paper-plane me-2"></i> Enquire Now
                            </button>
                            <small class="text-muted mt-2 x-small text-center opacity-75">* Professional maintenance</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Service Packages End -->
    @endif


    @include('frontend.partials.why-choose-us')


    @include('frontend.partials.testimonial')

    <!-- Package Enquiry Modal -->
    <div class="modal fade" id="packageEnquiryModal" tabindex="-1" aria-labelledby="packageEnquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-primary text-white border-0 py-3 px-4" style="background: var(--primary) !important; border-radius: 0;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-solar-panel text-primary" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="packageEnquiryModalLabel">Ready to Go Solar?</h6>
                            <small class="text-dark opacity-75 x-small">Fill the details below to get started</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="packageEnquiryForm" novalidate>
                    @csrf
                    <input type="hidden" name="type" value="package_enquiry">
                    <input type="hidden" name="subject" id="modal_package_name">
                    <input type="hidden" name="price" id="modal_package_price">
                    
                    <div class="modal-body p-4 p-lg-5">
                        <div class="selected-package-badge mb-4 p-3 rounded-3 text-center" style="background: rgba(255, 193, 7, 0.05); border: 1px solid rgba(255, 193, 7, 0.1);">
                            <span class="text-muted x-small text-uppercase fw-bold d-block mb-1">Selected Plan</span>
                            <h5 id="display_package_name" class="fw-bold text-primary mb-1"></h5>
                            <p id="display_package_price" class="fw-bold mb-0 text-dark opacity-75 small"></p>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control border-0 bg-light rounded-3" id="enquiry_name" placeholder="John Doe" style="padding-top: 1.625rem; padding-bottom: 0.625rem; font-size: 0.9rem;">
                            <label for="enquiry_name" class="text-muted small"><i class="fa fa-user me-2 text-primary opacity-50"></i>Full Name</label>
                            <span class="text-danger small error-text" id="error-name"></span>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="mobile" class="form-control border-0 bg-light rounded-3" id="enquiry_mobile" placeholder="+91 98765 43210" style="padding-top: 1.625rem; padding-bottom: 0.625rem; font-size: 0.9rem;">
                            <label for="enquiry_mobile" class="text-muted small"><i class="fa fa-phone me-2 text-primary opacity-50"></i>Mobile Number</label>
                            <span class="text-danger small error-text" id="error-mobile"></span>
                        </div>

                        <div class="form-floating">
                            <textarea name="message" class="form-control border-0 bg-light rounded-3" id="enquiry_message" placeholder="Message" style="height: 100px; padding-top: 1.625rem; font-size: 0.9rem;"></textarea>
                            <label for="enquiry_message" class="text-muted small"><i class="fa fa-comment me-2 text-primary opacity-50"></i>Special Requirements?</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold btn-enquire-simple shadow-sm" id="enquirySubmitBtn">
                            Submit Enquiry
                        </button>
                    </div>
                </form>
                <div id="enquiryResultMessage" class="px-4 pb-4"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Open Modal and Populate Data
            $(document).on('click', '.enquire-package', function() {
                const name = $(this).data('name');
                const price = $(this).data('price');
                
                $('#modal_package_name').val(name);
                $('#modal_package_price').val(price);
                $('#display_package_name').text(name);
                $('#display_package_price').text('₹' + parseFloat(price).toLocaleString('en-IN'));
                $('#enquiryResultMessage').empty();
                $('#packageEnquiryForm')[0].reset();
                $('.error-text').text('');
                $('.is-invalid').removeClass('is-invalid');
                
                $('#packageEnquiryModal').modal('show');
            });

            // Handle Form Submission
            $('#packageEnquiryForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const submitBtn = $('#enquirySubmitBtn');
                const messageDiv = $('#enquiryResultMessage');
                const originalBtnText = submitBtn.html();
                
                // Clear errors
                $('.error-text').text('');
                $('.is-invalid').removeClass('is-invalid');
                
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');
                messageDiv.empty();
                
                $.ajax({
                    url: "{{ route('website.enquiry.store') }}",
                    method: "POST",
                    data: $(form).serialize(),
                    success: function(response) {
                        messageDiv.html(`<div class="alert alert-success border-0 shadow-sm mb-0">
                            <i class="fa fa-check-circle me-2"></i>${response.message}
                        </div>`);
                        form.reset();
                        setTimeout(() => {
                            $('#packageEnquiryModal').modal('hide');
                        }, 2500);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#error-' + key).text(value[0]);
                                $('[name="' + key + '"]').addClass('is-invalid');
                            });
                        } else {
                            messageDiv.html(`<div class="alert alert-danger border-0 shadow-sm mb-0">
                                <i class="fa fa-exclamation-triangle me-2"></i>Something went wrong. Please try again.
                            </div>`);
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
