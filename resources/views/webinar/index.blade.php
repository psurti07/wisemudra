@extends('layouts.webinar')

@push('css')
@endpush

@section('content')
<section class="page-title-home pb-0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-12 m-auto text-center mt-0 mb-0">
                <div
                    class="heading-section d-flex text-center align-items-center justify-content-center flex-column mb-0">
                    <h2 class="fw-7"> Become <span class="tf-forth-color"> High-Paying <br>
                            PAN-India Fintech Agent</span>
                    </h2>
                    <p>Grow Revenue 5x with Industry-Leading Business Ecosystem
                    </p>
                </div>
                @if(!empty($eventdetail))
                <div class="icons-box p-4 justify-content-between w-75 p-3 p-md-3 mb-4 m-auto flex-wrap bg-main">
                    <div class="d-flex flex-lg-nowrap flex-md-wrap flex-wrap justify-content-md-center justify-content-start">
                        <div class="left">
                            <h6 class="text-center d-flex mb-3 me-3 align-items-center">
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-calendar-alt" width="20px"
                                    height="20px" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z">
                                    </path>
                                </svg>
                                <span class="ms-2">{{ $eventdetail->event_datetime->format('jS F Y') }}</span>
                            </h6>
                        </div>
                        <div class="right">
                            <h6 class="text-start d-flex mb-3 align-items-center">
                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-clock" width="20px"
                                    height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z">
                                    </path>
                                </svg>
                                <span class="ms-2">{{ $eventdetail->event_datetime->format('h:i A') }}</span>
                            </h6>
                        </div>
                    </div>
                    <p class="trusted-badge d-inline-flex align-items-center mb-0 px-3 py-2 bg-white border w-100 justify-content-center rounded-0">
                        3-Day Refund Guarantee</p>
                </div>
                <div class="bottom-btns mb-5 mb-md-0 mb-lg-0">
                    <a href="{{ route('webinar.register', customEncrypt($eventdetail->id)) }}" class="tf-btn m-auto text-uppercase px-4 py-2"> Reserve Seat
                        Now</a>
                </div>
                @endif
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12 m-auto text-center">
                <div class="getstared-image">
                    <img class="w-100" src="{{ asset('front/webinar/images/webinarpage/Suyash-photo.png') }}" alt="weight gain">
                </div>
            </div>
        </div>

    </div>
</section>

<div class="main-content">
    <!-- section-why -->
    <section class="section-why bg-main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-center justify-content-center flex-column">
                        <h2 class="fw-7"> Digital Systems That <span class="tf-forth-color"> Boost
                                Revenue</span>
                        </h2>
                        <p>verything you need to run a professional online loan advisory
                            business — without complexity.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-md-center">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="image mb-4 text-center">
                        <img class="w-75 m-auto" src="{{ asset('front/webinar/images/webinarpage/Suyesh-photo-side.png') }}"
                            alt="content-strategy">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-4">
                        <div class="icons-box review-item bg-white h-100">
                            <div class="image mb-4">
                                <img src="{{ asset('front/webinar/icons/content-strategy.svg') }}" alt="content-strategy"
                                    width="50">
                            </div>
                            <div class="content">
                                <h4><a class="fw-5" href="#">Smart Digital Strategy</a></h4>
                                <p>A clear, proven roadmap to shift from traditional loan sourcing to a fully
                                    digital model.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="icons-box review-item bg-white h-100">
                            <div class="image mb-4">
                                <img src="{{ asset('front/webinar/icons/technical-support.svg') }}" alt="technical-support"
                                    width="50">
                            </div>
                            <div class="content">
                                <h4><a class="fw-5" href="#">Expert Guidance</a></h4>
                                <p>Work with experienced fintech mentors who help you adopt the right tools and
                                    workflows.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="icons-box review-item bg-white h-100">
                            <div class="image mb-4">
                                <img src="{{ asset('front/webinar/icons/implementation.svg') }}" alt="implementation" width="50">
                            </div>
                            <div class="content">
                                <h4><a class="fw-5" href="#">Fast Setup &amp; Execution</a></h4>
                                <p>Website, CRM, automation, follow-up systems, and funnels — ready in days, not
                                    months.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="icons-box review-item bg-white h-100">
                            <div class="image mb-4">
                                <img src="{{ asset('front/webinar/icons/goal.svg') }}" alt="goal" width="50">
                            </div>
                            <div class="content">
                                <h4>Scale With Confidence</h4>
                                <p>Continuous optimization + lifetime support to help you grow month after
                                    month.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="bmi-report">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-center justify-content-center flex-column">
                        <h2 class="fw-7"> What Will Transform in <span class="tf-forth-color"> Your
                                Business</span>
                        </h2>
                        <p>Digital transformation isn’t optional anymore — customers prefer
                            fast, online loan processing.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-12 m-auto">
                    <div class="text-center report-image">
                        <img class="m-auto" src="{{ asset('front/webinar/images/webinarpage/graph_business_revamp.svg') }}"
                            alt="icon" width="620px">
                    </div>
                </div>
                @if(!empty($eventdetail))
                <div class="col-lg-12">
                    <div class="bottom-btns mb-md-0 mb-lg-0 mt-5 d-flex">
                        <a href="{{ route('webinar.register', customEncrypt($eventdetail->id)) }}" class="tf-btn m-auto text-uppercase text-center">
                            <span> Limited Spots Available! Reserve Yours Today!<br><b>@Just <del
                                        class="opacity-100">{{ $eventdetail->event_main_price}}/-</del>
                                    @if($eventdetail->event_offer_price == 0)
                                    <span>FREE</span>
                                    @else
                                    <span class="">{{ $eventdetail->event_offer_price }}/-</span>
                                    @endif</b></span></a>
                    </div>
                </div>
                @else
                <div class="col-lg-12">
                    <div class="bottom-btns mb-md-0 mb-lg-0 mt-5 d-flex">
                        <a href="#" class="tf-btn m-auto text-uppercase text-center">
                            NO Event
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    <img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

    <!-- counter-section -->
    <section id="counter-section" class="section-become-instructor">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-xl-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-centerjustify-content-center flex-column">
                        <h2 class="fw-7">Meet Your <span class="tf-forth-color"> Fintech Mentor</span></h2>
                        <div class="sub fs-15">Experience excellence through our strategic framework, expert
                            team, and fully digital ecosystem.</div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="text-center mb-3">
                        <img class="w-75 m-auto" src="{{ asset('front/webinar/images/webinarpage/Suyesh-photo-side.png') }}"
                            alt="content-strategy">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="content-wrap">
                        <div class="mb-5">
                            <h5 class="mb-2">Mr. Suyesh Pandey</h5>
                            <p>India’s Leading Fintech Advisory Coach</p>
                        </div>
                        <div class="counter style-2">
                            <div class="number-counter bg-main-green p-lg-4 p-md-4 p-4 rounded-4 text-white">
                                <h3>
                                    <div class="counter-content  text-white">
                                        <span class="number text-white" data-speed="2500" data-to="5"
                                            data-inviewport="yes">5</span>+
                                    </div>
                                </h3>
                                <p>Years in fintech training</p>
                            </div>
                            <div class="number-counter bg-main-green p-lg-4 p-md-4 p-4 rounded-4 text-white">
                                <h3>
                                    <div class="counter-content  text-white">
                                        <span class="number text-white" data-speed="2500" data-to="5"
                                            data-inviewport="yes">5</span>k+
                                    </div>
                                </h3>
                                <p>People trained across India</p>
                            </div>
                            <div class="number-counter bg-main-green p-lg-4 p-md-4 p-4 rounded-4 text-white">
                                <h3>
                                    <div class="counter-content  text-white">
                                        <span class="number text-white" data-speed="2500" data-to="120"
                                            data-inviewport="yes">120</span>+
                                    </div>
                                </h3>
                                <p>Masterclasses delivered</p>
                            </div>
                            <div class="number-counter bg-main-green p-lg-4 p-md-4 p-4 rounded-4 text-white">
                                <h3>
                                    <div class="counter-content  text-white">
                                        <span class="number text-white" data-speed="2500" data-to="45"
                                            data-inviewport="yes">45</span>+
                                    </div>
                                </h3>
                                <p>Fintech projects mentored
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <img src="{{ asset('front/webinar/images/sectionBorderL.png') }}" alt="line" class="w-100 h-auto">


    <!-- timeline -->
    <section class="section-timeline">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-center justify-content-center flex-column">
                        <h2 class="fw-7"> Growth Session <span class="tf-forth-color"> Breakdown </span>
                        </h2>
                        <p>Discover what you’ll unlock in this power-packed webinar
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="timeline">
                    <ul>
                        <li>
                            <div class="content">
                                <h5>Foundations of Online Loan Advisory</h5>
                                <p class="mb-0">Understanding the digital model, tools, earning
                                    potential &amp; industry insights.</p>
                            </div>
                            <div class="time py-1">
                                <p class="text-white mb-0">0 mins - 30 mins</p>
                            </div>
                        </li>
                        <li>
                            <div class="content">
                                <h5 class="mb-0">Growth Systems &amp; Lead Generation</h5>
                                <p class=" mb-0">Learn how to attract clients online, get daily
                                    inquiries &amp; convert consistently.</p>
                            </div>
                            <div class="time py-1">
                                <p class="text-white mb-0">30 mins - 1 hrs</p>
                            </div>
                        </li>
                        <li>
                            <div class="content">
                                <h5 class="mb-0">Digital Tools &amp; Business Setup</h5>
                                <p class=" mb-0">Live walkthrough of website, CRM, automation &amp;
                                    loan workflow systems.</p>
                            </div>
                            <div class="time py-1">
                                <p class="text-white mb-0">1 hrs - 1:30 hrs</p>
                            </div>
                        </li>
                        <li>
                            <div class="content">
                                <h5 class="mb-0">Scaling Beyond Your City</h5>
                                <p class=" mb-0">Strategies to expand PAN-India, increase commissions
                                    &amp; automate operations.</p>
                            </div>
                            <div class="time py-1">
                                <p class="text-white mb-0">1:30 hrs - 2 hrs</p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </section>
    <img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

    <!-- bmi-report -->
    <section id="bmi-report">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-center justify-content-center flex-column">
                        <h2 class="fw-7"> Who Will <span class="tf-forth-color"> Benefit the Most? </span>
                        </h2>
                        <p>If you want growth, independence, and digital opportunity, this is for you.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-12 m-auto">
                    <div class="text-center report-image">
                        <img class="m-auto" src="{{ asset('front/webinar/images/webinarpage/graph_ideal_for.svg') }}" alt="icon"
                            width="620px">
                    </div>
                </div>
                @if(!empty($eventdetail))
                <div class="col-lg-12">
                    <div class="bottom-btns mb-md-0 mb-lg-0 mt-5 d-flex">
                        <a href="{{ route('webinar.register', customEncrypt($eventdetail->id)) }}" class="tf-btn m-auto text-uppercase text-center">
                            <span> Limited Spots Available! Reserve Yours Today!<br><b>@Just <del
                                        class="opacity-100">{{ $eventdetail->event_main_price}}/-</del>
                                    @if($eventdetail->event_offer_price == 0)
                                    <span>FREE</span>
                                    @else
                                    <span class="">{{ $eventdetail->event_offer_price }}/-</span>
                                    @endif</b></span></a>
                    </div>
                </div>
                @else
                <div class="col-lg-12">
                    <div class="bottom-btns mb-md-0 mb-lg-0 mt-5 d-flex">
                        <a href="#" class="tf-btn m-auto text-uppercase text-center">
                            NO Event
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    <img src="{{ asset('front/webinar/images/sectionBorderL.png') }}" alt="line" class="w-100 h-auto">


    <!-- flat-about -->
    <section class="flat-about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-xl-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-center justify-content-center flex-column">
                        <h2 class="fw-7">Real Moments.<span class="tf-forth-color"> Real
                                Impacts.</span>
                        </h2>
                        <div class="sub fs-15">Take a look at the workplace where dreams meet reality.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="getstared-image-01 mt-lg-0 mt-4">
                        <img class="rounded-4 object-cover" src="{{ asset('front/webinar/images/webinarpage/Webinar-5.jpg') }}"
                            alt="weight gain" height="240px">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="getstared-image-01 mt-lg-0 mt-4"> <img class="rounded-4 object-cover"
                            src="{{ asset('front/webinar/images/webinarpage/Webinar-1.jpg') }}" alt="icon"></div>

                </div>
                <div class="col-lg-3">
                    <div class="getstared-image-01 mt-lg-0 mt-4"> <img class="rounded-4 object-cover"
                            src="{{ asset('front/webinar/images/webinarpage/Webinar-4.jpg') }}" alt="icon"></div>
                </div>
                <div class="col-lg-3">
                    <div class="getstared-image-01 mt-lg-0 mt-4"> <img class="rounded-4 object-cover"
                            src="{{ asset('front/webinar/images/webinarpage/Webinar-1.jpg') }}" alt="icon"></div>
                </div>
                <div class="col-lg-6">
                    <div class="getstared-image-01 mt-4"> <img class="rounded-4 object-cover"
                            src="{{ asset('front/webinar/images/webinarpage/Webinar-3.jpg') }}" alt="icon"></div>
                </div>
                <div class="col-lg-6">
                    <div class="getstared-image-01 mt-4"> <img class="rounded-4 object-cover"
                            src="{{ asset('front/webinar/images/webinarpage/Webinar-2.jpg') }}" alt="icon"></div>
                </div>
            </div>
        </div>
    </section>
    <img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

    <!-- section-saying -->
    <section class="section-saying">
        <div class="container">
            <div class="row justify-center">
                <div class="col-lg-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-centerjustify-content-center flex-column">
                        <h2 class="fw-7">Hear From <span class="tf-forth-color">Our Partners
                            </span></h2>
                        <div class="sub fs-15">Here's what our partners say about us.
                        </div>
                    </div>
                    <div class="swiper-container slider-courses-7">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonials-item-style-2 p-4 icons-box style-5">
                                    <div class="testimonials-item-header d-flex align-items-start mb-3 gap-3">
                                        <div class="image-wrap">
                                            <img src="{{ asset('front/webinar/images/customers/customer-1.png') }}" alt=""
                                                width="45" height="45">
                                        </div>
                                        <div class="content-wrap">
                                            <h6 class="text-black">Vijay Makwana </h6>
                                            <span>Fintech Client</span>
                                        </div>
                                    </div>
                                    <p> “Indiakarobar helped us set up our digital loan advisory system from
                                        scratch. Their tech and marketing support streamlined our entire
                                        workflow.”</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonials-item-style-2 p-4 icons-box style-5">
                                    <div class="testimonials-item-header d-flex align-items-start mb-3 gap-3">
                                        <div class="image-wrap">
                                            <img src="{{ asset('front/webinar/images/customers/customer-2.png') }}" alt=""
                                                width="45" height="45">
                                        </div>
                                        <div class="content-wrap">
                                            <h6 class="text-black">CA Haresh Dhaduk</h6>
                                            <span>Business Owner</span>
                                        </div>
                                    </div>
                                    <p> “The digital strategy and automation they implemented transformed how we
                                        manage clients. Their team is always available to support.”</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonials-item-style-2 p-4 icons-box style-5">
                                    <div class="testimonials-item-header d-flex align-items-start mb-3 gap-3">
                                        <div class="image-wrap">
                                            <img src="{{ asset('front/webinar/images/customers/customer-3.png') }}" alt=""
                                                width="45" height="45">
                                        </div>
                                        <div class="content-wrap">
                                            <h6 class="text-black">Naresh Solanki</h6>
                                            <span>Service Professional</span>
                                        </div>
                                    </div>
                                    <p> “The website, CRM setup, and marketing plan built by Indiakarobar helped
                                        us scale our operations faster than expected.”</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonials-item-style-2 p-4 icons-box style-5">
                                    <div class="testimonials-item-header d-flex align-items-start mb-3 gap-3">
                                        <div class="image-wrap">
                                            <img src="{{ asset('front/webinar/images/customers/customer-4.png') }}" alt=""
                                                width="45" height="45">
                                        </div>
                                        <div class="content-wrap">
                                            <h6 class="text-black">Mahesh Makwana</h6>
                                            <span>Business Owner</span>
                                        </div>
                                    </div>
                                    <p> “Their WhatsApp automation and AI assistant boosted our conversions
                                        instantly. Great team to work with!!”</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonials-item-style-2 p-4 icons-box style-5">
                                    <div class="testimonials-item-header d-flex align-items-start mb-3 gap-3">
                                        <div class="image-wrap">
                                            <img src="{{ asset('front/webinar/images/customers/customer-5.png') }}" alt=""
                                                width="45" height="45">
                                        </div>
                                        <div class="content-wrap">
                                            <h6 class="text-black">Dilip Gorasava</h6>
                                            <span>Fintech Professional</span>
                                        </div>
                                    </div>
                                    <p> “I went from local loan agent to serving clients across India.
                                        Indiakarobar’s digital system works.”</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonials-item-style-2 p-4 icons-box style-5">
                                    <div class="testimonials-item-header d-flex align-items-start mb-3 gap-3">
                                        <div class="image-wrap">
                                            <img src="{{ asset('front/webinar/images/customers/customer-6.png') }}" alt=""
                                                width="45" height="45">
                                        </div>
                                        <div class="content-wrap">
                                            <h6 class="text-black">Suresh Makwana </h6>
                                            <span>Fintech Professional</span>
                                        </div>
                                    </div>
                                    <p> “My wellness business now runs online with automated bookings and
                                        follow-ups. Huge time saver.”</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <img src="{{ asset('front/webinar/images/sectionBorderL.png') }}" alt="line" class="w-100 h-auto">

    <!-- section-mobile-app -->
    <section class="section-mobile-app">
        <div class="container">
            <div class="bg-main ps-0 rounded-5">
                <div class="row justify-content-center align-items-center p-4">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="content-left text-center px-lg-0">
                            <div class="box-sub-tag  mb-3  m-auto">
                                <div class="sub-tag-title px-3 py-1">
                                    <p>No Questions Asked!</p>
                                </div>
                            </div>
                            <div class="heading-section">
                                <h2
                                    class="fw-7 d-flex text-center align-items-center justify-content-center flex-md-wrap flex-wrap">
                                    100% Money Back <span class="tf-forth-color"> Guarantee
                                    </span></h2>
                                <h6>If you feel you didn’t learn anything new, claim a full refund
                                    within 3 days. No questions asked.</h6>
                            </div>


                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="image text-center">
                            <img class="w-100" src="{{ asset('front/webinar/images/webinarpage/money-back-guarantee.png') }}"
                                alt="call to action image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

    <!-- section-faq-page -->
    <section class="section-faq-page">
        <div class="container">
            <div class="row items-center justify-content-center">
                <div class="col-lg-12 col-md-12 col-12">
                    <div
                        class="heading-section d-flex text-center align-items-center justify-content-center flex-column">
                        <h2 class="fw-7">Frequently <span class="tf-forth-color">Asked Questions</span></h2>
                        <div class="sub fs-15">Everything you need to know before getting started with us.</div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="accordion-default accordion custom-bg-accordion bg-transparent border px-4 py-4 rounded-4"
                        id="accordionExample">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button bg-transparent collapsed shadow-none px-0"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                    aria-expanded="false" aria-controls="collapseOne">
                                    <span class="rectangle-314"></span>
                                    Do I need prior experience in finance or loans?
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-content">
                                    <p class="fs-15">
                                        No. The webinar is designed for beginners as well as existing
                                        agents.
                                        Everything is explained step-by-step.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <span class="accordion-button bg-transparent collapsed px-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                                    aria-controls="collapseTwo">
                                    <span class="rectangle-314"></span>
                                    What tools do I need to start an online loan business?
                                </span>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-content">
                                    <p>
                                        You’ll learn about the exact tools: website, CRM, automation,
                                        funnels,
                                        WhatsApp systems, and digital loan workflows.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <span class="accordion-button bg-transparent collapsed px-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                    aria-expanded="true" aria-controls="collapseThree">
                                    <span class="rectangle-314"></span>
                                    Will I get clients after the webinar?
                                </span>
                            </h3>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-content">
                                    <p>
                                        The webinar teaches you how to generate daily leads online using
                                        proven
                                        systems. Implementation is required for results.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <span class="accordion-button bg-transparent collapsed px-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                    aria-expanded="true" aria-controls="collapseFour">
                                    <span class="rectangle-314"></span>
                                    Will you teach how loan commissions work?
                                </span>
                            </h3>
                            <div id="collapseFour" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-content">
                                    <p>
                                        Yes. We explain commission structures, earning examples,
                                        bank/NBFC
                                        payouts, and how to maximize your income.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <span class="accordion-button bg-transparent collapsed px-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                    aria-expanded="true" aria-controls="collapseFive">
                                    <span class="rectangle-314"></span>
                                    Is this webinar live or recorded?
                                </span>
                            </h3>
                            <div id="collapseFive" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-content">
                                    <p>
                                        It is a live, interactive session with Q&A.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <span class="accordion-button  bg-transparent collapsed px-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true"
                                    aria-controls="collapseSix">
                                    <span class="rectangle-314"></span>
                                    What happens after the webinar?
                                </span>
                            </h3>
                            <div id="collapseSix" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-content">
                                    <p>You’ll get the opportunity to join Indiakarobar’s Online Fintech
                                        Agent
                                        Program, where we set up your complete digital system and help
                                        you start
                                        earning.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<footer class="footer-bottom footer-sticky py-2">
    <div class="container">
        <div class=" p-0">
            @if(!empty($eventdetail))
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 col-md-6 col-5 m-auto">
                    <div class="section-title text-lg-start">
                        <span class="content-price text-white mb-0 fw-notmal">7-Day Special Offer</span>
                        <h5 class="text-white mb-0">
                            <span class="content-price">Only @</span>
                            <del class="text-danger fw-bold opacity-100">{{ $eventdetail->event_main_price}}/-</del>
                            @if($eventdetail->event_offer_price == 0)
                            <span>FREE</span>
                            @else
                            <span class="text-price text-warning">{{ $eventdetail->event_offer_price }}/-</span>
                            @endif
                            <span class="content-text"> Limited Seats</span>
                            <span class="blinking content-text" style="color: #00a168;">Available </span>
                        </h5>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-7 m-auto">
                    <div class="btns text-lg-end text-center">
                        <a href="{{ route('webinar.register', customEncrypt($eventdetail->id)) }}" class="tf-btn ms-auto me-0 text-uppercase fw-bold desktop-btn px-4 py-2">
                            <span> Reserve Seat Now </span>
                        </a>
                        <a href="{{ route('webinar.register', customEncrypt($eventdetail->id)) }}" class="tf-btn fw-bold mobile-btn text-uppercase px-2 py-2">
                            <span> Reserve Now </span>
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-12">
                <div class="bottom-btns my-2 d-flex">
                    <a href="#" class="tf-btn m-auto text-uppercase text-center">
                        NO Event
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</footer>
@endsection
@push('scripts')

@endpush