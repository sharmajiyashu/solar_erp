$(document).ready(function () {
    // Password Visibility Toggle
    $(document).on('click', '.toggle-password', function () {
        const target = $($(this).attr('data-target'));
        const icon = $(this).find('i');

        if (target.attr('type') === 'password') {
            target.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            target.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('.auth-form').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        // Reset errors
        form.find('.invalid-feedback').remove();
        form.find('.is-invalid').removeClass('is-invalid');

        // Loading state
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Loading...');

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    }).showToast();

                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1000);
                    }
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                const response = xhr.responseJSON;
                if (xhr.status === 422 && response.errors) {
                    // Specific validation errors
                    $.each(response.errors, function (field, messages) {
                        const input = form.find(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    });

                    Toastify({
                        text: "Please correct the errors below.",
                        duration: 3000,
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    }).showToast();
                } else {
                    // General error
                    Toastify({
                        text: response.message || "An unexpected error occurred. Please try again.",
                        duration: 3000,
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    }).showToast();
                }
            }
        });
    });
});
