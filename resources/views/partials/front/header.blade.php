<header id="header" class="tra-menu navbar-dark white-scroll">
    <div class="header-wrapper">
        <!-- MOBILE HEADER -->
        <div class="wsmobileheader clearfix">
            <span class="smllogo">
                <img src="{{ asset('front/images/logo/logo.png') }}" alt="mobile-logo" />
            </span>
            <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        </div>
        <!-- NAVIGATION MENU -->
        <div class="wsmainfull menu clearfix">
            <div class="wsmainwp clearfix">
                <!-- HEADER BLACK LOGO -->
                <div class="desktoplogo">
                    <a href="{{ route('front.home') }}" class="logo-black">
                        <img src="{{ asset('front/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" />
                    </a>
                </div>
                <!-- HEADER WHITE LOGO -->
                <div class="desktoplogo">
                    <a href="{{ route('front.home') }}" class="logo-white">
                        <img src="{{ asset('front/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" />
                    </a>
                </div>
                <!-- MAIN MENU -->
                <nav class="wsmenu clearfix">
                    <ul class="wsmenu-list nav-theme">
                        <li class="nl-simple" aria-haspopup="true">
                            <a href="{{ route('front.home') }}" class="h-link">Home</a>
                        </li>
                        <li class="nl-simple" aria-haspopup="true">
                            <a href="{{ route('front.home') }}#company" class="h-link">Company</a>
                        </li>
                        <li class="nl-simple" aria-haspopup="true">
                            <a href="{{ route('front.home') }}#products" class="h-link">Products</a>
                        </li>
                        <li class="nl-simple" aria-haspopup="true">
                            <a href="{{ route('customer.login') }}" class="h-link">Login</a>
                        </li>
                        @if(false)
                        <li aria-haspopup="true"><a href="javascript:;" class="h-link">Apply Now <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                <li aria-haspopup="true"><a href="{{ route('self.apply.main') }}">Self Apply</a></li>
                                <li aria-haspopup="true"><a href="{{ route('loan.agent.main') }}">Hire an Agent</a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="nl-simple" aria-haspopup="true">
                            <a href="{{ route('self.apply.main') }}" class="btn r-04 btn--green-400 hover--tra-black last-link">Self Apply</a>
                        </li>
                        <li class="nl-simple" aria-haspopup="true">
                            <a href="{{ route('loan.agent.main') }}" class="btn r-04 btn--green-400 hover--tra-black last-link">Hire an Agent</a>
                        </li>
                    </ul>
                </nav>
                <!-- END MAIN MENU -->
            </div>
        </div>
        <!-- END NAVIGATION MENU -->
    </div>
    <!-- End header-wrapper -->
</header>
