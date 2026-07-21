<!DOCTYPE html>
<html lang="en" >
@include('partials.customer.head')
<body  id="kt_body"  class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-disabled" >
<script>
    var defaultThemeMode = "light";
    var themeMode;
    if ( document.documentElement ) {
        if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if ( localStorage.getItem("data-bs-theme") !== null ) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }

        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }

        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
</script>
<div class="d-flex flex-column flex-root">
    <div class="page d-flex flex-row flex-column-fluid">
        <div
            id="kt_aside"
            class="aside aside-extended "
            data-kt-drawer="true"
            data-kt-drawer-name="aside"
            data-kt-drawer-activate="{default: true, lg: false}"
            data-kt-drawer-overlay="true"
            data-kt-drawer-width="auto"
            data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_aside_toggle">
            <div class="aside-secondary d-flex flex-column align-items-lg-center flex-row-auto">
                <div class="aside-logo d-none d-lg-flex flex-column align-items-center flex-column-auto py-10" id="kt_aside_logo">
                    <a href="{{ route('customer.dashboard') }}">
                        <img alt="Logo" src="{{ asset('front/images/logo/apple-touch-icon-50x50.png') }}" class="h-50px"/>
                    </a>
                </div>
                @include('partials.customer.navigation')
                @include('partials.customer.footer')
            </div>
        </div>
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            <div
                id="kt_header"
                class="header "
                data-kt-sticky="true"
                data-kt-sticky-name="header"
                data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                <div class="container-xxl d-flex align-items-center justify-content-between border-bottom" id="kt_header_container">
                    @if(isRouteActive('customer.myloan.offers.preapproved') == 'active')
                        <div
                            class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0"
                            data-kt-swapper="true"
                            data-kt-swapper-mode="prepend"
                            data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">

                            <h1 class="text-dark my-0">Hey! <strong>{{ Auth::guard('members')->user()->first_name.' '.Auth::guard('members')->user()->last_name }}</strong></h1>
                            <ul class="breadcrumb breadcrumb-line text-muted fw-semibold fs-base my-1">
                                <li class="breadcrumb-item text-muted">
                                    we are fetched best deals for you.
                                </li>
                            </ul>
                        </div>
                    @else
                        <div
                            class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0"
                            data-kt-swapper="true"
                            data-kt-swapper-mode="prepend"
                            data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">

                            <h1 class="text-dark my-0">@yield('title')</h1>
                            <p class="text-muted fs-base my-1">@yield('breadcrumb')</p>
                        </div>
                        @if(request()->segment(2) == 'loan-details' )
                        <div class="page-title d-flex flex-column align-items-end justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0">
                            <a href="{{ route('customer.download.report') }}" class="btn btn-sm btn-primary me-2" id="kt_user_follow_button">
                                <i class="ki-duotone ki-file fs-3"><span class="path1"></span><span class="path2"></span></i>
                                <span class="indicator-label">Download Report</span>
                            </a>
                        </div>
                        @endif
                    @endif
                    <div class="d-flex d-lg-none align-items-center ms-n2 me-2">
                        <div class="btn btn-icon btn-active-icon-primary" id="kt_aside_toggle">
                            <i class="ki-duotone ki-abstract-14 fs-2x"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <a href="javascript:;" class="d-flex align-items-center">
                            <img alt="Logo" src="{{ asset('front/images/logo/apple-touch-icon-50x50.png') }}" class="h-40px"/>
                        </a>
                    </div>
                </div>
            </div>
            @yield('content')

            <div class="footer py-4 d-flex flex-lg-column bg-primary" id="kt_footer">
                <div class=" container-xxl">
                    <div class="text-dark text-center">
                        <span class="fw-semibold">{{ date('Y') }} &copy;</span> <a href="{{ route('front.home') }}" target="_blank" class="text-dark text-hover-success fw-semibold fs-6">{{ env('COMPANY_NAME') }}</a>
                        <span class="fw-semibold">All Rights Reserved.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i cass="ki-duotone ki-arrow-up"><span class="path1"></span><span class="path2"></span></i>
    </div>
</div>
@include('stacks.js.customer.scripts')
</body>
</html>
