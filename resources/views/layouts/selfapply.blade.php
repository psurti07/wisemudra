<!DOCTYPE html>
<html lang="en">
@include('partials.selfapply.head')

<body>
    <!-- PAGE CONTENT -->
    <div id="page" class="page font--poppins">
        <!-- HEADER -->
        @include('partials.selfapply.header')
        <!-- END HEADER -->
        @yield('content')
        @include('partials.selfapply.footer')
    </div>
    @include('stacks.js.selfapply.scripts')
</body>

</html>