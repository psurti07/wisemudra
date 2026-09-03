<!DOCTYPE html>
<html lang="en">
@include('partials.webinar.head')

<body class="counter-scroll">
    <div id="wrapper">
        @include('partials.webinar.header')

        @yield('content')

    </div>
    <script type="text/javascript" src="{{ asset('front/webinar/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/webinar/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/webinar/js/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/webinar/js/countto.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/webinar/js/swiper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/webinar/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
    @stack('scripts')
</body>

</html>