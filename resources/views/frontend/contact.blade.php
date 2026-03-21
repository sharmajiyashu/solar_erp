@extends('layouts.frontend')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Contact</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Contact Start -->
    <div class="container-fluid bg-light overflow-hidden px-lg-0" style="margin: 3rem 0;">
        <div class="container contact px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 contact-text py-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 ps-lg-0">
                        <h6 class="text-primary">Contact Us</h6>
                        <h1 class="mb-4">Feel Free To Contact Us</h1>
                        <p class="mb-4">Have questions about solar energy? Our expert team is ready to help. Fill out the form below or contact us via phone or email for a personalized consultation.</p>
                        <form id="contactForm" novalidate>
                            @csrf
                            <input type="hidden" name="type" value="contact">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name">
                                        <label for="name">Your Name</label>
                                        <span class="text-danger small error-text" id="error-name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Your Email">
                                        <label for="email">Your Email</label>
                                        <span class="text-danger small error-text" id="error-email"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject">
                                        <label for="subject">Subject</label>
                                        <span class="text-danger small error-text" id="error-subject"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="message" class="form-control" placeholder="Leave a message here" id="message" style="height: 100px"></textarea>
                                        <label for="message">Message</label>
                                        <span class="text-danger small error-text" id="error-message"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary rounded-pill py-3 px-5" type="submit" id="contactSubmitBtn">Send Message</button>
                                </div>
                            </div>
                        </form>
                        <div id="contactMessage" class="mt-3"></div>

                        <script>
                            document.getElementById('contactForm').addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                const form = this;
                                const submitBtn = document.getElementById('contactSubmitBtn');
                                const messageDiv = document.getElementById('contactMessage');
                                const originalBtnText = submitBtn.innerHTML;
                                
                                // Clear previous errors
                                document.querySelectorAll('.error-text').forEach(el => el.innerHTML = '');
                                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                                
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
                                messageDiv.innerHTML = '';
                                
                                const formData = new FormData(form);
                                
                                fetch("{{ route('website.enquiry.store') }}", {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                                        form.reset();
                                    } else {
                                        if (data.errors) {
                                            Object.keys(data.errors).forEach(key => {
                                                const errorSpan = document.getElementById('error-' + key);
                                                const input = form.querySelector(`[name="${key}"]`);
                                                if (errorSpan) {
                                                    errorSpan.innerHTML = data.errors[key][0];
                                                }
                                                if (input) {
                                                    input.classList.add('is-invalid');
                                                }
                                            });
                                        } else {
                                            messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                                        }
                                    }
                                })
                                .catch(error => {
                                    messageDiv.innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again later.</div>`;
                                })
                                .finally(() => {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalBtnText;
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <iframe class="position-absolute w-100 h-100" style="object-fit: cover;"
                        src="{{ config('app.website_map_url') }}"
                        frameborder="0" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection
