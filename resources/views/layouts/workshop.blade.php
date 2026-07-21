<!DOCTYPE html>
<html lang="en">
@include('partials.workshop.head')
<body class="pb-0">
<!-- PAGE CONTENT -->
<div id="page" class="page font--poppins">
    <!-- HEADER -->
    @include('partials.workshop.header')
    <!-- END HEADER -->
    @yield('content')
    @include('partials.workshop.footer')
</div>
@include('stacks.js.selfapply.scripts')
</body>
</html>
