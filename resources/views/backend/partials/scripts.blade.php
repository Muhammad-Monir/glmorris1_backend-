<script src="{{ asset('backend/assets/js/datatables/jquery-3.7.0.js') }}"></script>
<script src='{{ asset('backend/assets/libs/choices.js/public/assets/scripts/choices.min.js') }}'></script>
<script src="{{ asset('backend/assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('backend/assets/libs/lucide/umd/lucide.js') }}"></script>
<script src="{{ asset('backend/assets/js/tailwick.bundle.js') }}"></script>
<!--apexchart js-->
<script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!--dashboard ecommerce init js-->
<script src="{{ asset('backend/assets/js/pages/dashboards-ecommerce.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('backend/assets/js/app.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Toastr JS -->
<script>
    $(document).ready(function() {
        toastr.options = {
            'closeButton': true,
            'debug': true,
            'newestOnTop': true,
            'progressBar': false,
            'positionClass': 'toast-top-center',
            'preventDuplicates': true,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '5000', 
            'extendedTimeOut': '1000', 
            'showEasing': 'linear',
            'hideEasing': 'linear',
            'showMethod': 'slideDown',
            'hideMethod': 'slideUp',
            'hover' : false,
        };

        @if (Session::has('t-success'))
            toastr.success("{{ session('t-success') }}");
        @endif

        @if (Session::has('t-error'))
            toastr.error("{{ session('t-error') }}");
        @endif

        @if (Session::has('t-info'))
            toastr.info("{{ session('t-info') }}");
        @endif

        @if (Session::has('t-warning'))
            toastr.warning("{{ session('t-warning') }}");
        @endif
    });
</script>

@stack('scripts')