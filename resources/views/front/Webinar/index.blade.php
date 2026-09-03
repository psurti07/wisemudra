<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large" />
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="{{ config('constant.APP_NAME') }}">
    <meta property="og:title" content="{{ $meta['title'] }}" />
    <meta property="og:description" content="{{ $meta['description'] }}" />
    <meta property="og:image" content="{{ asset('front/images/favicon-32x32.png') }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('constant.APP_NAME') }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $meta['title'] }}" />
    <meta name="twitter:description" content="{{ $meta['description'] }}" />
    <meta name="twitter:site" content="{{ '@'.config('constant.APP_NAME') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
    <!-- SITE TITLE -->
    <title>{{ $meta['title'] }}</title>
    <!-- FAVICON AND TOUCH ICONS -->
    <link rel="shortcut icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
    <link rel="icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('front/images/logo/apple-touch-icon-152x152.png') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('front/images/logo/apple-touch-icon-120x120.png') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('front/images/logo/apple-touch-icon-76x76.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('front/images/logo/apple-touch-icon-60x60.png') }}" />
    <link rel="icon" href="{{ asset('front/images/logo/main-favicon-180x180.png') }}" type="image/x-icon" />
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <!-- BOOTSTRAP CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- FONT ICONS -->
    <link href="{{ asset('front/css/flaticon.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLESHEET -->
    <link href="{{ asset('front/css/menu.css') }}" rel="stylesheet" />
    <link id="effect" href="{{ asset('front/css/dropdown-effects/fade-down.css') }}" media="all" rel="stylesheet" />
    <link href="{{ asset('front/css/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/owl.theme.default.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/lunar.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/crocus-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/scrollbar.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css" />
    @stack('css')
    @stack('style-css')

</head>

<body>
    <div id="page" class="page font--poppins">
        <!-- HEADER -->
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
                                <img src="{{ asset('front/images/logo/logo.png') }}" alt="{{ config('constant.APP_NAME') }}" />
                            </a>
                        </div>
                        <!-- HEADER WHITE LOGO -->
                        <div class="desktoplogo">
                            <a href="{{ route('front.home') }}" class="logo-white">
                                <img src="{{ asset('front/images/logo/logo.png') }}" alt="{{ config('constant.APP_NAME') }}" />
                            </a>
                        </div>
                        <!-- END MAIN MENU -->
                    </div>
                </div>
                <!-- END NAVIGATION MENU -->
            </div>
            <!-- End header-wrapper -->
        </header>

        <section class="page-hero-section">
            <div class="page-hero-section-overlay bg--green-100 bg--scroll">
                <div class="container">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-md-10 col-lg-10 col-sm-12 form-details-section">
                            <div class="col-md-12 py-20">
                                <div class="txt-block text-center">
                                    <h1 class="s-34 w-700 color--black mb-20">Step Into the Future of Fintech Innovation</h1>
                                    <p class="mb-20 color--black">Digitally transform your loan or financial business with advanced technology and expert support.</p>
                                    <a href="{{ route('webinar.user.registration') }}" class="btn r-04 btn--theme hover--tra-black last-link">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features-2" class="py-80 features-section division">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-9">
                        <div class="section-title mb-40">
                            <h2 class="s-28 mb-5">How Digital Tech Is <span class="color--green-500">Reshaping Industry!</span></h2>
                            <p class="s-16 color--grey mt-0">As India’s Fintech sector accelerates, digital-ready businesses lead the growth wave.</p>
                        </div>
                    </div>
                </div>

                <div class="fbox-wrapper text-center">
                    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-2">
                        <div class="col">
                            <div class="fbox-11 fbox--hover fb-1 r-12 h-100 w-100 bg-white py-3 fbox-8 fbox--hover d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('front/images/fintechpage/icons/dollar.webp') }}" alt="icon" class="th-50 " height="60">
                                </div>
                                <div class="fbox-txt text-start ms-4">
                                    <h6 class=""><strong>₹3.93 lakh crore</strong></h6>
                                    <p class="mt-0">Current valuation of India’s Fintech market (2025)</p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="fbox-11 fbox--hover fb-1 r-12 h-100 w-100 bg-white py-3 fbox-8 fbox--hover d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('front/images/fintechpage/icons/pie-chart.webp') }}" alt="icon" class="th-50 " height="60">
                                </div>
                                <div class="fbox-txt text-start ms-4">
                                    <h6 class=""><strong>₹8.48 lakh crore by 2030</strong></h6>
                                    <p class="mt-0">Projected market size driven by a 16.65% CAGR</p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="fbox-11 fbox--hover fb-1 r-12 h-100 w-100 bg-white py-3 fbox-8 fbox--hover d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('front/images/fintechpage/icons/applications.webp') }}" alt="icon" class="th-50 " height="60">
                                </div>
                                <div class="fbox-txt text-start ms-4">
                                    <h6 class=""><strong>12–13% expected rise in overall bank credit</strong></h6>
                                    <p class="mt-0">Indicative of increasing financial activity (FY 2026)</p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="fbox-11 fbox--hover fb-1 r-12 h-100 w-100 bg-white py-3 fbox-8 fbox--hover d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('front/images/fintechpage/icons/loan.webp') }}" alt="icon" class="th-50 " height="60">
                                </div>
                                <div class="fbox-txt text-start ms-4">
                                    <h6 class=""><strong>13–14% growth in retail credit</strong></h6>
                                    <p class="mt-0">Including high-demand segments such as personal loans (FY 2026)</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <div id="statistic-1" class="bg--green-400 ct-03 py-50 statistic-section division">
            <div class="container">
                <div class="statistic-5-wrapper">
                    <div class="row row-cols-2 row-cols-md-3 justify-content-center align-items-center">
                        <div class="col border-right-light-1 sec-2">
                            <div id="sb-5-3" class="">
                                <div class="statistic-block">
                                    <div class="statistic-digit">
                                        <h2 class="s-30 w-700 text-white">
                                            <span class="count-element">45 </span>+
                                        </h2>
                                    </div>
                                    <div class="statistic-txt">
                                        <h5 class="s-16 w-600 text-white">Fintech Projects Delivered</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col border-right-light-1 sec-2">
                            <div id="sb-5-3" class="">
                                <div class="statistic-block">
                                    <div class="statistic-digit">
                                        <h2 class="s-30 w-700 text-white">
                                            <span class="count-element">10</span>+
                                        </h2>
                                    </div>
                                    <div class="statistic-txt">
                                        <h5 class="s-16 w-600 text-white">Fintech & Tech Experts</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col border-right-light-1 border-0 sec-2">
                            <div id="sb-5-3" class="">
                                <div class="statistic-block">
                                    <div class="statistic-digit">
                                        <h2 class="s-30 w-700 text-white">
                                            <span class="count-element">100</span>+
                                        </h2>
                                    </div>
                                    <div class="statistic-txt">
                                        <h5 class="s-16 w-600 text-white">WDigital Systems & Automation</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <section id="features-6" class="py-80 features-section division">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-9">
                        <div class="section-title mb-40">
                            <h2 class="s-28 mb-5">A Digital Foundation <span class="color--green-500">Built for Scale</span></h2>
                            <p class="s-16 color--grey mt-0">We enable your loan or finance business to run fully digitally and efficiently.</p>
                        </div>
                    </div>
                </div>
                <div class="fbox-wrapper text-center">
                    <div class="row gx-3 gy-2 row-cols-1 row-cols-md-2 row-cols-lg-3">
                        <div class="col d-flex mb-3">
                            <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                                <div class="fbox-ico">
                                    <div class="shape-ico color--theme">
                                        <img class="" src="{{asset('front/images/fintechpage/icons/icon2.webp')}}"
                                            alt="feature-image" height="80">
                                    </div>
                                </div>
                                <div class="fbox-txt">
                                    <h4 class="s-18 w-700">Complete Digital Setup</h4>
                                    <p>Get a professionally designed website, intelligent lead funnels, CRM setup, customer workflows, and automation — all customized for your Fintech needs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex mb-3">
                            <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-2 r-12 border h-100 w-100 d-flex flex-column">
                                <div class="fbox-ico">
                                    <div class="shape-ico color--theme">
                                        <img class="" src="{{asset('front/images/fintechpage/icons/icon1.webp')}}"
                                            alt="feature-image" height="80">
                                    </div>
                                </div>
                                <div class="fbox-txt">
                                    <h4 class="s-18 w-700">Growth-Focused Strategy & Consultation</h4>
                                    <p>Our experts help you build a strong digital presence, improve conversions, and position your financial services for long-term growth.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex mb-3">
                            <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-2 r-12 border h-100 w-100 d-flex flex-column">
                                <div class="fbox-ico">
                                    <div class="shape-ico color--theme">
                                        <img class="" src="{{asset('front/images/fintechpage/icons/icon3.webp')}}"
                                            alt="feature-image" height="80">
                                    </div>
                                </div>
                                <div class="fbox-txt">
                                    <h4 class="s-18 w-700">Marketing Support for Customer Acquisition</h4>
                                    <p>From targeted campaigns to lead nurturing systems, we help you attract, engage, and convert high-intent customers.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex mb-3">
                            <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-3 r-12 border h-100 w-100 d-flex flex-column">
                                <div class="fbox-ico">
                                    <div class="shape-ico color--theme">
                                        <img class="" src="{{asset('front/images/fintechpage/icons/icon4.webp')}}"
                                            alt="feature-image" height="80">
                                    </div>
                                </div>
                                <div class="fbox-txt">
                                    <h4 class="s-18 w-700">Technology & IT Support</h4>
                                    <p>Your business runs on a reliable digital infrastructure set up and maintained by experts.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex mb-3">
                            <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-2 r-12 border h-100 w-100 d-flex flex-column">
                                <div class="fbox-ico">
                                    <div class="shape-ico color--theme">
                                        <img class="" src="{{asset('front/images/fintechpage/icons/icon5.webp')}}"
                                            alt="feature-image" height="80">
                                    </div>
                                </div>
                                <div class="fbox-txt">
                                    <h4 class="s-18 w-700">Transparent & Ethical Operations</h4>
                                    <p>Clear processes, secure data handling, and compliant frameworks ensure a trustworthy experience for your customers.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex mb-3">
                            <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-3 r-12 border h-100 w-100 d-flex flex-column">
                                <div class="fbox-ico">
                                    <div class="shape-ico color--theme">
                                        <img class="" src="{{asset('front/images/fintechpage/icons/icon6.webp')}}"
                                            alt="feature-image" height="80">
                                    </div>
                                </div>
                                <div class="fbox-txt">
                                    <h4 class="s-18 w-700">Operational Workflow Automation</h4>
                                    <p>Automate repetitive tasks, accelerate approvals, and deliver a faster, smoother loan processing experience.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

        <section id="features-5" class="py-80 pb-50 features-section division">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-10">
                        <div class="section-title mb-40">
                            <h2 class="s-28 mb-5">What Support Do <span class="color--green-500">You Get?</span></h2>
                            <p class="s-16 color--grey">We don’t just guide you — we build your digital ecosystem WITH you.</p>
                        </div>
                    </div>
                </div>
                <div class="fbox-wrapper">
                    <div class="row d-flex">
                        <div class="col-md-6">
                            <div class="fbox-5 p-4 fb-1 bg--white-400 r-16">
                                <div class="fbox-txt mb-4">
                                    <h3 class="s-22 w-700">What We Offer</h3>
                                    <p class="s-16">Smart systems built for you</p>
                                </div>
                                <ul class="simple-list">
                                    <li class="list-item">
                                        <p>End-to-end digital business setup</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Website + CRM + automation workflows</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Admin dashboard access</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Complete marketing setup</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Technical & IT support</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Training on tools & systems</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Dedicated growth manager</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Regular updates & optimization</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fbox-5 p-4 fb-1 bg--white-400 r-16">
                                <div class="fbox-txt mb-4">
                                    <h3 class="s-22 w-700">Why This Matters For You</h3>
                                    <p class="s-16">Build a future-ready business</p>
                                </div>
                                <ul class="simple-list">
                                    <li class="list-item">
                                        <p>You focus on serving customers — we handle the digital heavy lifting</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Complete control over your leads, data, and processes</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Expert-driven growth systems</p>
                                    </li>
                                    <li class="list-item">
                                        <p>No infrastructure needed — everything is digital</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Real-time support whenever required</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Faster scaling with a strong digital foundation</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Reduced manual work through automation</p>
                                    </li>
                                    <li class="list-item">
                                        <p>Better customer experience with faster processes</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <img src="{{ asset('front/webinar/images/sectionBorderL.png') }}" alt="line" class="w-100 h-auto">

        <section class="tc-about-style25 py-lg-5 py-md-5 py-5">
            <div class="container">
                <div class="section-title section-title-style24 text-center">
                    <h2>How It Works — <span> Your Fintech Journey </span></h2>
                    <p class="mt-3">A simple, guided process designed for fast implementation and powerful results.</p>
                </div>
                <div class="row align-items-center mt-30">
                    <div class="col-lg-7 col-md-7 col-12">
                        <div class="step-info pe-lg-5">
                            <div class="step-card p-3 mb-3 border border-radius-10 border-grey-1 ">
                                <h5 class="mb-2">1. Assess Your Fintech Readiness</h5>
                                <p class="mb-0 color-666">We start with a focused audit of your existing operations, products, compliance posture, tech stack, and customer journeys.</p>
                            </div>
                            <div class="step-card p-3 mb-3 border border-radius-10 border-grey-1 ">
                                <h5 class="mb-2">2. Enrol in Programs & webinars</h5>
                                <p class="mb-0 color-666">Join our Fintech-focused programs and hands-on sessions to understand your digital ecosystem and learn how to use it efficiently.</p>
                            </div>
                            <div class="step-card p-3 mb-3 border border-radius-10 border-grey-1 ">
                                <h5 class="mb-2">3. Go Digital & Upscale Revenue</h5>
                                <p class="mb-0 color-666">Launch your fully digital Fintech business — with CRM, automation, website, lead systems, and marketing funnels that help you grow continuously.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-12 text-center">
                        <div class="img d-inline-block">
                            <img src="{{ asset('front/images/Who-Can-Apply.webp') }}" alt="fintech sector" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

        <section id="reviews-1" class="py-80  features-section division">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="section-title mb-50">
                            <h2 class="s-34 w-700">Our Happy Customer</h2>
                            <p>We Give Many Reasons For Our Customers To Shower Praises On Us!</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="owl-carousel owl-theme testimonials-carousel">
                            <div class="review-1 bg--white-100 block-shadow r-08">
                                <div class="review-txt pt-30">
                                    <div class="author-data clearfix">
                                        <div class="review-avatar">
                                            <img src="{{ asset('front/images/logo/apple-touch-icon.png') }}" alt="review-avatar" width="auto">
                                        </div>
                                        <div class="review-author">
                                            <h4 class="s-16 w-600">Kanishka Tiwari</h4>
                                            <div class="star-rating ico-15">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star-half-empty mr-5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="p-sm"><i>"I was so surprised with the service quickness! Self apply is really the best feature here"</i></p>
                                </div>
                            </div>
                            <div class="review-1 bg--white-100 block-shadow r-08">
                                <div class="review-txt pt-30">
                                    <div class="author-data clearfix">
                                        <div class="review-avatar">
                                            <img src="{{ asset('front/images/logo/apple-touch-icon.png') }}" alt="review-avatar" width="auto">
                                        </div>
                                        <div class="review-author">
                                            <h4 class="s-16 w-600">Kaushik Shah</h4>
                                            <div class="star-rating ico-15">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star-half-empty mr-5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="p-sm"><i>“Impressed with the loan consultation service! They truly give the best solutions...”</i></p>
                                </div>
                            </div>
                            <div class="review-1 bg--white-100 block-shadow r-08">
                                <div class="review-txt pt-30">
                                    <div class="author-data clearfix">
                                        <div class="review-avatar">
                                            <img src="{{ asset('front/images/logo/apple-touch-icon.png') }}" alt="review-avatar" width="auto">
                                        </div>
                                        <div class="review-author">
                                            <h4 class="s-16 w-600">Shrijita Deb</h4>
                                            <div class="star-rating ico-15">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star-half-empty mr-5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="p-sm"><i>"Just extraordinary…it’s so good to receive the loan applying link within few minutes only"</i></p>
                                </div>
                            </div>
                            <div class="review-1 bg--white-100 block-shadow r-08">
                                <div class="review-txt pt-30">
                                    <div class="author-data clearfix">
                                        <div class="review-avatar">
                                            <img src="{{ asset('front/images/logo/apple-touch-icon.png') }}" alt="review-avatar" width="auto">
                                        </div>
                                        <div class="review-author">
                                            <h4 class="s-16 w-600">Mukesh Sharma</h4>
                                            <div class="star-rating ico-15">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star-half-empty mr-5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="p-sm"><i>"Well done Wisemudra team, your guidance really makes a big difference. Also services are superb"</i></p>
                                </div>
                            </div>
                            <div class="review-1 bg--white-100 block-shadow r-08">
                                <div class="review-txt pt-30">
                                    <div class="author-data clearfix">
                                        <div class="review-avatar">
                                            <img src="{{ asset('front/images/logo/apple-touch-icon.png') }}" alt="review-avatar" width="auto">
                                        </div>
                                        <div class="review-author">
                                            <h4 class="s-16 w-600">Naina Kumari</h4>
                                            <div class="star-rating ico-15">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star-half-empty mr-5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="p-sm"><i>“Quick. Professional. Humble – that’s how I define this team. Thanks guys, you’re the best”</i></p>
                                </div>
                            </div>
                            <div class="review-1 bg--white-100 block-shadow r-08">
                                <div class="review-txt pt-30">
                                    <div class="author-data clearfix">
                                        <div class="review-avatar">
                                            <img src="{{ asset('front/images/logo/apple-touch-icon.png') }}" alt="review-avatar" width="auto">
                                        </div>
                                        <div class="review-author">
                                            <h4 class="s-16 w-600">Shirish Shah</h4>
                                            <div class="star-rating ico-15">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star-half-empty mr-5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="p-sm"><i>“It just went beyond my expectations. It’s so easy to get effective loan consultation with Wisemudra”</i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <img src="{{ asset('front/webinar/images/sectionBorderL.png') }}" alt="line" class="w-100 h-auto">

        <section id="faqs-3" class="py-80 faqs-section">
            <div class="container">
                <div class="faqs-3-questions">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-xl-12">
                            <div class="section-title mb-50">
                                <h2 class="s-28 mb-5">Frequently <span class="color--green-500">Asked Questions!</span></h2>
                                <p>Everything you need to know before getting started with us.</p>
                            </div>
                            <div class="accordion-wrapper" id="faq-container">
                                <ul class="accordion">
                                    <li class="accordion-item mb-10">
                                        <div class="accordion-thumb">
                                            <h6 class="s-16 w-500">I’m new to digital systems — can you help me set everything up?</h6>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-panel-item">
                                                <div class="faqs-2-answer">
                                                    <p>Yes. We build everything for you — website, CRM, automation, and digital marketing setup.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item mb-10">
                                        <div class="accordion-thumb">
                                            <h6 class="s-16 w-500">What sectors do you serve?</h6>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-panel-item">
                                                <div class="faqs-2-answer">
                                                    <p>We specialize in Fintech, Wellness, and CRM solutions — along with general business digitalisation.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item mb-10">
                                        <div class="accordion-thumb">
                                            <h6 class="s-16 w-500">Do I need technical knowledge?</h6>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-panel-item">
                                                <div class="faqs-2-answer">
                                                    <p> Not at all. Our team handles all tech, setup, and tools.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item mb-10">
                                        <div class="accordion-thumb">
                                            <h6 class="s-16 w-500">What if I get stuck?</h6>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-panel-item">
                                                <div class="faqs-2-answer">
                                                    <p>You get ongoing support, updates, and guidance as long as you’re with us.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item mb-10">
                                        <div class="accordion-thumb">
                                            <h6 class="s-16 w-500">Will I get a website and CRM?</h6>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-panel-item">
                                                <div class="faqs-2-answer">
                                                    <p>Yes — both can be included as part of your digital setup.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item mb-10">
                                        <div class="accordion-thumb">
                                            <h6 class="s-16 w-500">Can you work with my existing brand?</h6>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-panel-item">
                                                <div class="faqs-2-answer">
                                                    <p>Absolutely — we strengthen and digitise your current brand.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
    <script src="{{ asset('front/js/scrollbar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
    @stack('script-src')
    @include('stacks.js.front.script')
</body>

</html>