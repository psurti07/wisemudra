<!DOCTYPE html>
<html lang="en">
@include('partials.front.head')
<body>

<!-- PAGE CONTENT -->
<div id="page" class="page font--poppins">
    <!-- HEADER -->
    @include('partials.front.header')
    @yield('content')
    <!-- Footer -->
    @include('partials.front.footer')
</div>
<script src="{{ asset('front/js/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/menu.js') }}"></script>
<script src="{{ asset('front/js/jquery.easing.js') }}"></script>
<script src="{{ asset('front/js/jquery.appear.js') }}"></script>
<script src="{{ asset('front/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('front/js/lunar.js') }}"></script>
<script src="{{ asset('front/js/wow.js') }}"></script>
<script src="{{ asset('front/js/custom.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
@stack('script-src')
@include('stacks.js.front.script')
</body>
</html>
