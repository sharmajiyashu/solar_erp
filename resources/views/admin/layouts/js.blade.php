<!-- BEGIN: Vendor JS-->
<script src="{{ url('public/dashboard-assets/app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ url('public/dashboard-assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ url('public/dashboard-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<!-- END: Page Vendor JS-->



<!-- BEGIN: Theme JS-->
<script src="{{ url('public/dashboard-assets/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ url('public/dashboard-assets/app-assets/js/core/app.js') }}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{ url('public/dashboard-assets/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>

<script src="{{ url('public/dashboard-assets/app-assets/js/scripts/components/components-popovers.js') }}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }

        $('#loader').fadeOut(); // Hide the loader with a fade effect
    })
</script>

@if (session('success'))
    <script>
        Toastify({
            text: `{{ session('success') }}`,
            className: "success",
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            }
        }).showToast();
    </script>
@endif

@if (session('error'))
    <script>
        Toastify({
            text: `{{ session('error') }}`,
            className: "error",
            style: {
                background: "linear-gradient(to right, #b73b3c, #b73b3c)",
            }
        }).showToast();
    </script>
@endif



<script src="{{ url('public/dashboard-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ url('public/dashboard-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>

<script>
    let video = document.getElementById('camera');
    let canvas = document.getElementById('canvas');
    let preview = document.getElementById('preview');
    let photoInput = document.getElementById('photo');

    let captureBtn = document.getElementById('captureBtn');
    let retakeBtn = document.getElementById('retakeBtn');
    let submitBtn = document.getElementById('submitBtn');

    let stream;

    $('#punchInModal').on('shown.bs.modal', async function() {

        try {

            stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });

            video.srcObject = stream;

            await video.play();

        } catch (error) {

            alert("Camera access denied");

        }

    });

    function capturePhoto() {

        if (!video.videoWidth) {
            alert("Camera not ready");
            return;
        }

        const context = canvas.getContext('2d');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/png');

        photoInput.value = imageData;

        preview.src = imageData;

        // show preview
        preview.style.display = 'block';
        video.style.display = 'none';

        // buttons toggle
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
        submitBtn.style.display = 'inline-block';

        stopCamera();

    }

    function retakePhoto() {

        preview.style.display = 'none';
        video.style.display = 'block';

        captureBtn.style.display = 'inline-block';
        retakeBtn.style.display = 'none';
        submitBtn.style.display = 'none';

        startCamera();

    }

    async function startCamera() {

        stream = await navigator.mediaDevices.getUserMedia({
            video: true
        });

        video.srcObject = stream;

    }

    function stopCamera() {

        if (stream) {

            stream.getTracks().forEach(track => track.stop());

        }

    }

    $('#punchInModal').on('hidden.bs.modal', function() {

        stopCamera();

    });
</script>
