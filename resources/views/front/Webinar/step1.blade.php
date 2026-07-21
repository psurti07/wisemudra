@extends('layouts.workshop')
@push('css')
<style>
    .bg-section-full {
        background-image: url('/front/images/selfapply-bg.webp');
        width: 100%;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }
</style>
@endpush
@section('content')

<section id="" class="py-120 bg--fixed bg--green-100 hero-section">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-6 col-lg-6 nbfc-selfapply-list">
                <div class="info px-3">
                    <p class="s-16 mt-2"><mark>Growth-Oriented Webinar</mark></p>
                    <h2 class="fsz-35 mb-30 fw-normal">Start Your Fintech Business & <span class="fw-bold color-yellow2">Earn 5x Revenue</span></h2>
                    <ul class="text-start mb-30">
                        <li class="mb-10 d-flex align-items-start"> <i class="fas fa-check-circle me-2 mt-1"></i> Serve PAN-India Without Fieldwork</li>
                        <li class="mb-10 d-flex align-items-start"> <i class="fas fa-check-circle me-2 mt-1"></i> Get High-Intent Loan Applicants</li>
                        <li class="mb-10 d-flex align-items-start"> <i class="fas fa-check-circle me-2 mt-1"></i> Build Scalable Digital Loan Business</li>
                    </ul>
                    <div class="row justify-content-center pb-30">
                        <div class="col-lg-12 col-12 d-flex align-items-center">
                            <img src="{{ asset('front/images/fintechpage/icons/thunder.png') }}" alt="icon" class="me-3" height="60">
                            <div class="info">
                                <p class="mb-0 color-yellow2"> Webinar Built for </p>
                                <p class="mb-2"> India’s Next-Gen Fintech Leaders </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-lg-5 offset-md-0 offset-lg-1 self-apply-form">
                <div id="hero-8-form" class="r-06">
                    <h4 class="s-14"><span class="color--purple-500 s-28">Let’s Begin!</span><br />Fill in the details below to get started.</h4>

                    <form method="post" action="{{ route('webinar.storeStep1') }}" class="request-form signup-form needs-validation " novalidate>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="">First Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="firstname" id="firstname" class=" form-control name" placeholder="Enter Your FirstName ">
                                </div>
                                @component('components.ajax-error',['field'=>'firstname'])@endcomponent
                            </div>
                            <div class="col-md-12">
                                <label for="">Last Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="lastname" id="lastname" class="form-control name" placeholder="Enter Your Lastname">
                                </div>
                                @component('components.ajax-error',['field'=>'lastname'])@endcomponent
                            </div>
                            <div class="col-md-12">
                                <label for="">Mobile No. <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="mobile_no" id="mobile" class="numeric-input form-control name" placeholder="Enter Your Mobile" autocomplete="off" required maxlength="10" minlength="10" id="mobileno" inputmode="numeric">
                                </div>
                                @component('components.ajax-error',['field'=>'mobile_no'])@endcomponent
                            </div>
                            <div class="col-md-12 form-btn">
                                <button type="submit" id="signupbtn" class="btn btn--theme hover--theme submit">
                                    <span class="spinner-border spinner-border-sm me-2 d-none color-yellow2" role="status" aria-hidden="true" id="signupLoader"></span>
                                    Let's Start!
                                </button>
                            </div>
                            <p class="mb-0 s-14 text-start text-dark">
                                <input type="checkbox" checked="checked" id="termsCheck" name="accept_tnc" />
                                &nbsp;<small>By submitting this form, you accept our <a href="{{ route('front.terms.conditions') }}" target="_blank" class="text-dark text-decoration-none">Terms of Service</a> and <a href="{{ route('front.privacy.policy') }}" target="_blank" class="text-dark text-decoration-none">Privacy Policy</a> and receive communication from {{ env('APP_NAME') }} via SMS, E-Mail and WhatsApp.</small>
                            </p>
                            @component('components.ajax-error', ['field' => 'terms']) @endcomponent
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features-13" class="py-80 shape--06 shape--gr-whitesmoke features-section division">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-5">
                <div class="img-block d-flex justify-content-center align-items-center">
                    <img class="img-fluid" src="{{ asset('front/images/Who-Can-Apply.webp') }}" alt="content-image">
                </div>
            </div>
            <div class="col-md-7">
                <div class="txt-block right-column">
                    <h2 class="mb-20"> Why Start Your Digital Loan Advisory Business? </h2>
                    <p class="color-666 mb-20">India’s lending ecosystem is expanding rapidly — and customers now expect fast, transparent, and tech-enabled financial services.</p>
                    <p class="mb-2"><strong>Key Industry Insights (INR):</strong></p>
                    <ul class="simple-list mb-20">
                        <li class="list-item">₹3.93 lakh crore — Current value of India’s Fintech market (2025) </li>
                        <li class="list-item">₹8.48 lakh crore by 2030 — Expected fintech market size </li>
                        <li class="list-item">12–13% growth — Projected overall bank credit (FY 2026) </li>
                        <li class="list-item">13–14% growth — Retail loan segment including personal loans </li>
                    </ul>
                    <p class="mb-2"><strong>What this means for you:</strong></p>
                    <p>Loan professionals who go digital today get more customers, close deals faster, and stand out from offline competitors.</p>
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

<section id="features-7" class="py-80 features-section division">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-5">How Your Fintech <span class="color--green-500">Journey Works</span></h2>
                    <p class="s-16 color--grey">A simple three-step path to becoming a digital loan professional.</p>
                </div>
            </div>
        </div>

        <div class="fbox-wrapper text-center">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 p-4 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/analysis.svg')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Fintech Business Assessment</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 p-4 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/workshop.svg')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Enrol in Programs & Workshops</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 p-4 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/virtual.svg')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Go Digital & Upscale Revenue</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

<section id="features-6" class="py-80 features-section division">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-5"> How Your Fintech <span class="color--green-500">Journey Works</span></h2>
                    <p class="s-16 color--grey mt-0">A simple three-step path to becoming a digital loan professional.</p>
                </div>
            </div>
        </div>
        <div class="fbox-wrapper text-center">
            <div class="row gx-3 gy-2 row-cols-1 row-cols-md-2 row-cols-lg-3">
                <div class="col d-flex mb-3">
                    <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/icons/icon5.webp')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-18 w-700">Professional Website for Loan Services</h4>
                            <p>Showcase all your loan offerings with credibility, branding, and proper structure — built to convert leads.</p>
                        </div>
                    </div>
                </div>
                <div class="col d-flex mb-3">
                    <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-2 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/icons/icon2.webp')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-18 w-700">CRM Setup for Lead Tracking</h4>
                            <p>Track every customer, automate follow-ups, and never lose a lead again.</p>
                        </div>
                    </div>
                </div>
                <div class="col d-flex mb-3">
                    <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-2 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/icons/icon4.webp')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-18 w-700">Lead Generation Funnel</h4>
                            <p>A funnel designed specifically for loan services — optimized for local + nationwide reach.</p>
                        </div>
                    </div>
                </div>
                <div class="col d-flex mb-3">
                    <div class="bg--white-400 border-radius-10 p-4 mb-20 fbox--hover fb-3 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/icons/icon1.webp')}}"
                                    alt="feature-image" height="80">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-18 w-700">Digital Branding Assets</h4>
                            <p>Professional creatives, profiles, and digital presence designed to make you look authoritative.</p>
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
                            <h4 class="s-18 w-700">Growth Strategy & Training</h4>
                            <p>Workshops and guidance on Customer acquisition, Digital presence scaling, Handling leads, and positioning yourself as a trusted financial advisor.</p>
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
                            <h4 class="s-18 w-700">Customer Care</h4>
                            <p>Online Query Module, Toll Free Number for Partners, Quick TAT for Faster Resolution.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<img src="{{ asset('front/webinar/images/sectionBorderL.png') }}" alt="line" class="w-100 h-auto">

<section id="features-7" class="py-80 features-section division">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-5">How Your Fintech <span class="color--green-500">Journey Works</span></h2>
                    <p class="s-16 color--grey">A simple three-step path to becoming a digital loan professional.</p>
                </div>
            </div>
        </div>

        <div class="fbox-wrapper text-center">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/anniversary.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">100% Done-For-You Digital Setup</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/crm.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">CRM + automation + website + funnel</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/growth-1.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Expert growth consultations</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/rating.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Dedicated support team</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/sync.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Regular updates & optimizations</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/light-bulb.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Zero technical knowledge required</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/connection.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">End-to-end ecosystem for your loan business</h4>
                        </div>
                    </div>
                </div>

                <div class="col d-flex mb-3">
                    <div class="border-radius-10 px-4 py-2 mb-20 fbox--hover fb-1 r-12 border h-100 w-100 d-flex flex-column">
                        <div class="fbox-ico">
                            <div class="shape-ico color--theme">
                                <img class="" src="{{asset('front/images/fintechpage/click.svg')}}"
                                    alt="feature-image" height="60">
                            </div>
                        </div>
                        <div class="fbox-txt">
                            <h4 class="s-16 w-400">Customer Engagement Tools</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<img src="{{ asset('front/webinar/images/sectionBorderR.png') }}" alt="line" class="w-100 h-auto">

<section id="faqs-3" class="py-80 faqs-section">
    <div class="container">
        <div class="faqs-3-questions">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-12">
                    <div class="section-title mb-50">
                        <h2 class="s-28 mb-5">Frequently <span class="color--green-500">Asked Questions!</span></h2>
                        <p>Hit The Key Highlights!</p>
                    </div>
                    <div class="accordion-wrapper" id="faq-container">
                        <ul class="accordion">
                            <li class="accordion-item mb-10">
                                <div class="accordion-thumb">
                                    <h6 class="s-16 w-500">Do I need to be a registered DSA to join?</h6>
                                </div>
                                <div class="accordion-panel">
                                    <div class="accordion-panel-item">
                                        <div class="faqs-2-answer">
                                            <p>No. This is for all loan professionals — new or experienced.</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion-item mb-10">
                                <div class="accordion-thumb">
                                    <h6 class="s-16 w-500">Do you provide customers?</h6>
                                </div>
                                <div class="accordion-panel">
                                    <div class="accordion-panel-item">
                                        <div class="faqs-2-answer">
                                            <p>No — we build your digital system so you can attract and convert your own customers.</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion-item mb-10">
                                <div class="accordion-thumb">
                                    <h6 class="s-16 w-500">Will I get a ready website?</h6>
                                </div>
                                <div class="accordion-panel">
                                    <div class="accordion-panel-item">
                                        <div class="faqs-2-answer">
                                            <p>Lead tracking, customer notes, status updates, automation, and analytics.</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion-item mb-10">
                                <div class="accordion-thumb">
                                    <h6 class="s-16 w-500">Can this work for national clients?</h6>
                                </div>
                                <div class="accordion-panel">
                                    <div class="accordion-panel-item">
                                        <div class="faqs-2-answer">
                                            <p>Yes — your digital setup allows you to operate pan-India.</p>
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

<section class="bg--green-400  mt-50 py-50 ct-03 content-section division">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-10 col-lg-10">
                <div class="txt-block color--white right-column">
                    <h2 class="s-30 w-700">Ready to Become Fintech Professional?</h2>
                    <span class="section-id">Let our experts build your complete digital fintech ecosystem.</span>
                </div>
            </div>
            <div class="col-md-2 col-lg-2">
                <div class="d-flex align-item-center text-center justify-content-center">
                    <a href="{{ route('webinar.step1') }}" class="btn r-04 btn--white hover--tra-black last-link">Let's Start</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="form-holder">
                    <div class="contact-form-notice">
                        <p class="s-14">
                            <strong>Disclaimer:</strong> This is a business opportunity program. Earnings are not guaranteed and may vary based on individual effort, market conditions, product approvals, and business performance. Indiakarobar provides training, systems, and support; however, success depends on your execution and external factors.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.signup-form').submit(function(event) {
            event.preventDefault();
            let form = this;
            let $btn = $('#signupbtn');
            $('.ajax-error').html('');

            // ✅ Client-side validation for all fields
            let hasError = false;

            if (!$('[name="firstname"]').val().trim()) {
                $('.firstname').html('<strong>The first name field is required.</strong>');
                hasError = true;
            }
            if (!$('[name="lastname"]').val().trim()) {
                $('.lastname').html('<strong>The last name field is required.</strong>');
                hasError = true;
            }
            if (!$('[name="mobile_no"]').val().trim()) {
                $('.mobile_no').html('<strong>The mobile number field is required.</strong>');
                hasError = true;
            }
            if (!$('#termsCheck').is(':checked')) {
                $('.terms').html('<strong>Please accept our Terms & Privacy Policy.</strong>');
                hasError = true;
            }

            if (hasError) return false;

            // proceed with AJAX only if all valid
            let data = new FormData(form);
            $.ajax({
                url: $(form).attr("action"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $btn.attr('disabled', true);
                    $('#signupLoader').removeClass('d-none');
                },
                success: function(response) {
                    if (response.type === 'ALREADY_REGISTERED') {
                        toastr.error(response.message);
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 2500);
                    } else {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 2500);
                    }
                },
                error: function(error) {
                    $btn.attr('disabled', false);
                    $('#signupLoader').addClass('d-none');
                    let errors = error.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key).html('<strong>' + value[0] + '</strong>');
                    });
                }
            });
        });

        $("#mobileno").on("input", function() {
            this.value = this.value.replace(/[^0-9]/g, "");
        });

        $('#termsCheck').on('change', function() {
            if ($(this).is(':checked')) {
                $('.terms').html('');
            }
        });
    });
</script>
@endpush