<!-- Quote Start -->
<div class="container-fluid bg-light overflow-hidden px-lg-0" style="margin: 6rem 0;">
    <div class="container quote px-lg-0">
        <div class="row g-0 mx-lg-0">
            <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                <div class="position-relative h-100">
                    <img class="position-absolute img-fluid w-100 h-100" src="{{ url('public/frontend-assets/img/quote.jpg') }}" style="object-fit: cover;" alt="">
                </div>
            </div>
            <div class="col-lg-6 quote-text py-5 wow fadeIn" data-wow-delay="0.5s">
                <div class="p-lg-5 pe-lg-0">
                    <h6 class="text-primary">Free Quote</h6>
                    <h1 class="mb-4">Get A Free Quote</h1>
                    <p class="mb-4 pb-2">Ready to switch to clean energy? Request a free, customized solar quote today. Our experts will analyze your energy needs and provide the most efficient solution for your home or business.</p>
                    <form id="quoteForm">
                        @csrf
                        <input type="hidden" name="type" value="quotation">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="text" name="name" class="form-control border-0" placeholder="Your Name" style="height: 55px;">
                                <span class="text-danger small error-text" id="error-name"></span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="email" name="email" class="form-control border-0" placeholder="Your Email" style="height: 55px;">
                                <span class="text-danger small error-text" id="error-email"></span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="text" name="mobile" class="form-control border-0" placeholder="Your Mobile" style="height: 55px;">
                                <span class="text-danger small error-text" id="error-mobile"></span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="text" name="subject" class="form-control border-0" placeholder="Subject" style="height: 55px;">
                                <span class="text-danger small error-text" id="error-subject"></span>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control border-0" placeholder="Special Note"></textarea>
                                <span class="text-danger small error-text" id="error-message"></span>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary rounded-pill py-3 px-5" type="submit" id="quoteSubmitBtn">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div id="quoteMessage" class="mt-3"></div>

                    <script>
                        document.getElementById('quoteForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            
                            const form = this;
                            const submitBtn = document.getElementById('quoteSubmitBtn');
                            const messageDiv = document.getElementById('quoteMessage');
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
        </div>
    </div>
</div>
<!-- Quote End -->
