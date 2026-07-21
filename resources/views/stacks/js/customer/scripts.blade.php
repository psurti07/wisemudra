<script src="{{ asset('customer/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('customer/assets/js/scripts.bundle.js') }}"></script>

<script src="{{ asset('customer/assets/js/widgets.bundle.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<script>
    $('.numeric-input').on('keydown', function(event) {
        if (!(event.key === 'Backspace' || event.key === 'Delete' || (event.key >= '0' && event.key <= '9'))) {
            event.preventDefault();
        }
    });
</script>
@stack('script-tag')
