<!-- JQuery -->
<script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>
<!-- Node Waves JS-->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>
<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>
<!-- Color Picker JS -->
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>
<!-- Custom-Switcher JS -->
<script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script>
<!-- Notification JS -->
<script src="{{ asset('assets/libs/awesome-notifications/index.var.js') }}"></script>

<!-- Notification Custom JS -->
<script>
    $(function(){
        `use strict`

        var options = {
            'position' : 'top-right'
        }
        var notifier = new AWN(options);

        @if(Session::has('success'))
            notifier.success("{{ session('success') }}")
        @endif

        @if(Session::has('error'))
            notifier.alert("{{ session('error') }}")
        @endif

        @if(Session::has('info'))
            notifier.info("{{ session('info') }}")
        @endif

        @if(Session::has('warning'))
            notifier.warning("{{ session('warning') }}")
        @endif

        $(document).on('keyup', '#barcode_search', function () {
            let value = $(this).val();
            if (value.length > 3) {
                $.ajax({
                    url: '{{ route('barcode.search') }}',
                    method: 'POST',
                    data: {
                        barcode: value
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response != '') {
                            let searchCard = $('.search-card');
                            searchCard.removeClass('d-none');
                            $('.search-card .card-body').html(response);
                        } else {
                            $('.search-card').removeClass('d-none');
                            let html = `<p class="text-center align-middle">No product found</p>`;
                            $('.search-card .card-body').html(html);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                })
            } else {
                $('.search-card').removeClass('d-none');
                let html = `<p class="text-center align-middle">Please write atleast 3 character</p>`;
                $('.search-card .card-body').html(html);
            }
        });
    })
</script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/floating.js') }}"></script>
@stack('script')
