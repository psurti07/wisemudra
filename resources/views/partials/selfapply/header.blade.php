<header id="header" class="tra-menu navbar-dark white-scroll">
    <div class="header-wrapper">
        <!-- MOBILE HEADER -->
        <div class="wsmobileheader clearfix">
            <span class="smllogo">
                <img src="{{ asset('front/images/logo/logo.png') }}" alt="mobile-logo" />
            </span>
            {{--<a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>--}}
        </div>
        <!-- NAVIGATION MENU -->
        <div class="wsmainfull menu clearfix">
            <div class="wsmainwp clearfix">
                <!-- HEADER BLACK LOGO -->
                <div class="desktoplogo">
                    <a href="javascript:;" class="logo-black">
                        <img src="{{ asset('front/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" />
                    </a>
                </div>
                <!-- HEADER WHITE LOGO -->
                <div class="desktoplogo">
                    <a href="javascript:;" class="logo-white">
                        <img src="{{ asset('front/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" />
                    </a>
                </div>
                <!-- MAIN MENU -->
                <nav class="wsmenu clearfix">
                    <ul class="wsmenu-list nav-theme">

                    </ul>
                </nav>
                <!-- END MAIN MENU -->
            </div>
        </div>
        <!-- END NAVIGATION MENU -->
    </div>
    <!-- End header-wrapper -->
</header>
