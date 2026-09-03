<div class="aside-nav d-flex flex-column align-items-center flex-column-fluid w-100 pt-5 pt-lg-0" id="kt_aside_nav">
    <div class="hover-scroll-overlay-y mb-10 scroll-ms"
        data-kt-scroll="true"
        data-kt-scroll-activate="{default: false, lg: true}"
        data-kt-scroll-height="auto"
        data-kt-scroll-wrappers="#kt_aside_nav"
        data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
        data-kt-scroll-offset="0px">
        @php
        $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
        @endphp
        <ul class="nav flex-column">
            <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Dashboard">
                <a class="nav-link btn btn-custom btn-icon {{ isRouteActive('customer.dashboard') }}" href="{{ route('customer.dashboard') }}">
                    <i class="ki-duotone ki-monitor-mobile fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </a>
            </li>
            <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Pre-Approved Offers">
                <a class="nav-link btn btn-custom btn-icon {{ isRouteActive('customer.pre.approved.loans') }}" href="{{ route('customer.pre.approved.loans') }}">
                    <i class="ki-duotone ki-badge fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                </a>
            </li>
            @if(accType() == 2)
            <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Documents">
                <a class="nav-link btn btn-custom btn-icon {{ isRouteActive('customer.document.kyc.document') }}" href="{{ route('customer.document.kyc.document') }}">
                    <i class="ki-duotone ki-document fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                </a>
            </li>
            @endif
            <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Application Info">
                <a class="nav-link btn btn-custom btn-icon {{ $routeName }} {{ (($routeName == 'customer.loan.history') ? 'active' : ($routeName == 'customer.loan.details' ? 'active' : '') )}}" href="{{ route('customer.loan.history') }}">
                    <i class="ki-duotone ki-chart-simple fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                </a>
            </li>
            <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="My Subscription">
                <a class="nav-link btn btn-custom btn-icon {{ $routeName }} {{ (($routeName == 'customer.subscription') ? 'active' : '' )}}" href="{{ route('customer.subscription') }}">
                    <i class="ki-duotone ki-shield-tick fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                </a>
            </li>
            <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Support">
                <a class="nav-link btn btn-custom btn-icon {{ isRouteActive('customer.support') }}" href="{{ route('customer.support') }}">
                    <i class="ki-duotone ki-support-24 fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </a>
            </li>
        </ul>
    </div>
</div>
