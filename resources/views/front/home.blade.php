@extends('layouts.front')
@push('css')
<link rel="stylesheet" href="{{ asset('front/calc/commoncalculator.css') }}">
<link rel="stylesheet" href="{{ asset('front/calc/emicalculator.css') }}">
<link rel="stylesheet" href="{{ asset('front/calc/calcstyle.css') }}">
<link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
@endpush
@push('style-css')

@endpush
@section('content')
<!-- main section starts -->
<section id="hero-7" class="hero-section bg--blue-500 pb-0">
    <div class="hero-overlay">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-6 col-lg-6 col-12">
                    <div class="hero-7-txt mb-0">
                        <span class="color--blue-500 text-uppercase mb-2 d-block">Financial Consultation,
                            Simplified</span>
                        <h1 class="w-700 color--white mb-3">Helping You Move <br> Closer to<span
                                class="color--blue-500"> Success</span></h1>
                        <p class=" color--white mb-4">Let experienced professionals help you reach your financial goals.
                        </p>
                        <div>
                            <a href="{{ route('self.apply.main') }}"
                                class="btn r-04 btn--theme hover--tra-white last-link rounded-pill">Self Apply <span class="fbox-ico ico-10"> <span class="flaticon-right-arrow  ico-20 ms-1"></span></span></a>
                            {{-- <a href="{{ route('loan.agent.main') }}" class="btn r-04 btn--theme hover--tra-white
                            last-link">Hire an Agent</a> --}}
                        </div>


                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-12">
                    <div class="home-img text-center">
                        <img src="{{ asset('front/images/hero-image.png') }}" alt="wisemudra" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- main section ends -->

<section class="py-80 ct-02 content-section division" id="company">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="txt-block left-column border-0">
                    <h2 class="s-28 mb-40">About <span class="color--blue-500">Us!</span></h2>
                    <p>Wisemudra is a trusted financial consultation platform that connects customers with
                        leading NBFC partners across India. Our goal is to simplify the borrowing process by offering
                        expert guidance, transparent solutions, and a seamless digital experience. Through our
                        technology-driven platform, users can explore loan options, receive personalized assistance, and
                        apply for loans quickly and conveniently, all in one place.</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="txt-block right-column position-relative">
                    <p class="s-16 color--grey mt-0 border-start border-primary border-4 mb-40"><span
                            class="ms-3 d-block">Helping You Choose the Right Financial Path</span></p>
                    <div class="bg--blue-100 p-4 rounded-4">
                        <p class="w-700 mt-0 d-block">Helping You Achieve Financial Freedom !</p>
                        <ul class="simple-list">
                            <li class="list-item">
                                <p>We believe every financial journey is unique, which is why we offer customized
                                    support
                                    and solutions designed around individual needs.</p>
                            </li>
                            <li class="list-item">
                                <p class="mb-0">We make the loan process easy to understand, so you can move forward
                                    with
                                    confidence.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- why Wisemudra section starts -->
<section id="features-6" class="py-80 features-section feature-main-wrap division bg--blue-500">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="section-title mb-50">
                    <h2 class="s-28 mb-2 text-white">Why <span class="color--blue-500">Wisemudra</span></h2>
                    <p class="mt-0 color--white">The Difference We Bring</p>
                </div>
            </div>
        </div>
        <div class="fbox-wrapper text-center">
            <div class="row gx-3 gy-2 row-cols-1 row-cols-md-1 row-cols-lg-1">
                <hr class="divider">
                <div class="col">
                    <div
                        class="fbox--hover fb-1 r-12 h-100 d-flex justify-content-between py-md-4 py-3 flex-md-nowrap flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="shape-ico color--theme">
                                <h3>01</h3>
                            </div>
                            <h4 class="s-18 w-700 color--white ms-md-5 ms-3 d-flex align-items-center">
                                <span class="icon-wrap r-12 me-3  overflow-auto"><i
                                        class="fas fa-handshake icon"></i></span>

                                Enriching
                                Collaboration
                            </h4>
                        </div>
                        <div class="fbox-txt text-start">
                            <p class="color--grey mt-0">Explore multiple financial services backed by our leading NBFC
                                network.</p>
                        </div>
                    </div>
                </div>

                <hr class="divider">
                <div class="col">
                    <div
                        class="fbox--hover fb-1 r-12 h-100 d-flex justify-content-between py-md-4 py-3 flex-md-nowrap flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="shape-ico color--theme">
                                <h3>02</h3>
                            </div>
                            <h4 class="s-18 w-700 color--white ms-md-5 ms-3 d-flex align-items-center"><span
                                    class="icon-wrap r-12 me-3 overflow-auto"><span
                                        class="flaticon-computer icon"></span></span>100%
                                Online Process</h4>
                        </div>
                        <div class="fbox-txt text-start">
                            <p class="color--grey">Enjoy a smooth and hassle-free loan experience without stepping out
                                of your home.</p>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="col">
                    <div
                        class="fbox--hover fb-1 r-12 h-100 d-flex justify-content-between py-md-4 py-3 flex-md-nowrap flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="shape-ico color--theme">
                                <h3>03</h3>
                            </div>
                            <h4 class="s-18 w-700 color--white ms-md-5 ms-3 d-flex align-items-center"> <span
                                    class="icon-wrap r-12 me-3 overflow-auto"><i
                                        class="fas fa-hand-point-up icon"></i></span>Self-Apply
                                Feature</h4>
                        </div>
                        <div class="fbox-txt text-start">
                            <p class="color--grey">Enjoy the freedom to make smarter financial decisions with expert
                                support.</p>
                        </div>
                    </div>
                    <hr class="divider">
                </div>
            </div>
        </div>
</section>


<!-- why Wisemudra section ends -->

<!-- Trust Badges Section starts -->
<div id="statistic-1" class=" bg--blue-300 ct-03 py-50 statistic-section division">
    <div class="container">
        <div class="statistic-5-wrapper">
            <div class="row row-cols-2 row-cols-md-4">
                <div class="col sec-1">
                    <div id="sb-5-1" class="text-center">
                        <div class="statistic-block color--white">
                            <div class="statistic-digit">
                                <h2 class="s-30 w-700 mb-5">
                                    <span class="count-element">4</span>.<span class="count-element">5</span>k
                                </h2>
                            </div>
                            <div class="statistic-txt">
                                <h5 class="s-16 w-500">Happy Customer</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col sec-2">
                    <div id="sb-5-3" class="text-center">
                        <div class="statistic-block color--white">
                            <div class="statistic-digit">
                                <h2 class="s-30 w-700 mb-5">
                                    <span class="count-element">1</span>.<span class="count-element">5</span>Cr+
                                </h2>
                            </div>
                            <div class="statistic-txt">
                                <h5 class="s-16 w-500">Disbursal</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col sec-3">
                    <div id="sb-5-2" class="text-center">
                        <div class="statistic-block color--white">
                            <div class="statistic-digit">
                                <h2 class="s-30 w-700 mb-5">
                                    <span class="count-element">8</span>+
                                </h2>
                            </div>
                            <div class="statistic-txt">
                                <h5 class="s-16 w-500">NBFC Partners</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col sec-4">
                    <div id="sb-5-4" class="text-center">
                        <div class="statistic-block color--white">
                            <div class="statistic-digit">
                                <h2 class="s-30 w-700 mb-5">
                                    <span class="count-element">100</span>%
                                </h2>
                            </div>
                            <div class="statistic-txt">
                                <h5 class="s-16 w-500">Digital Process</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Trust Badges Section ends -->

<!-- Products Intro section starts -->
<section id="products" class="py-80 features-section division bg--blue-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-2">Grow Your Finances <span class="color--blue-500">the Smart Way</span></h2>
                    <p class="mt-0 color--grey">Choose a plan that matches your requirements.</p>
                </div>
            </div>
        </div>
        <div class="fbox-wrapper text-center">
            <div class="row d-flex gx-4 gy-4 align-items-center justify-content-center m-auto r-24">
                <div class="col-md-10 col-lg-9 m-auto">
                    <div class="row card-main bg-white">
                        <div class="col-lg-5 p-0">
                            <div class="fbox--hover fb-2 border bg-white mb-0">
                                <div class="fbox-5-img mb-0">
                                    <img class="light-theme-img w-100" src="{{ asset('front/images/plan-visual.png') }}"
                                        alt="feature-image">
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-7 p-0">
                            <div
                                class="fbox-txt text-start bg-white d-flex flex-column h-100 align-items-start justify-content-center p-4">
                                <h3 class="s-22 w-700">Quick Self-Apply</h3>
                                <p class="mb-20 color--grey">Relax and let our experienced loan specialist manage the
                                    entire
                                    process for
                                    you, helping improve your chances of approval.</p>
                                <a href="{{ route('self.apply.main') }}"
                                    class="btn r-04  btn-sm btn--tra-black hover--theme rounded-pill">Apply
                                    Now</a>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- <div class="col-md-6">
                        <div class="fbox-5 fbox--hover fb-2 border r-16">
                            <div class="fbox-5-img mb-2">
                                <img class="img-fluid light-theme-img" src="{{ asset('front/images/Img-23.png') }}"
                alt="feature-image">
            </div>
            <div class="fbox-txt">
                <h3 class="s-22 w-700">Hire Loan Agent</h3>
                <p class="mb-20">Get expert digital loan guidance, quick login access, and personalized loan offers from
                    trusted NBFC partners.</p>
                <a href="{{ route('loan.agent.main') }}" class="btn r-04 btn--theme hover--tra-black">Apply Now</a>
            </div>
        </div>
    </div> --}}
    </div>
    </div>
    </div>
</section>
<!-- Products Intro section ends -->

<!-- Quick and swift steps section starts -->

<section class="timeline-section py-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9 m-auto">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-2">How it <span class="color--blue-500">works!</span></h2>
                    <p class="mt-0 color--grey">Apply Now In 6 Easy Steps</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-lg-9 m-auto">
                <div class="timeline position-relative">
                    <div class="timeline-item">
                        <div class="timeline-content bg--blue-100 p-4 r-18">
                            Start by entering your mobile number and bank-registered name.
                        </div>
                        <div class="timeline-number">01</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content bg--blue-100 p-4 r-18">
                            Fill in the required information to let our automated system assess
                            your eligibility and show your pre-approved loan offer(s). This is
                            not the final offer.
                        </div>
                        <div class="timeline-number">02</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content bg--blue-100 p-4 r-18">
                            Subscribe to unlock access to your displayed pre-approved loan offer(s).
                        </div>
                        <div class="timeline-number">03</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content bg--blue-100 p-4 r-18">
                            Within 24-48 hours, our login team will reach out for verification
                            and guide you through the document submission process.
                        </div>
                        <div class="timeline-number">04</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content bg--blue-100 p-4 r-18">
                            Your documents and profile will be verified by the NBFC in accordance
                            with its terms and conditions.
                        </div>
                        <div class="timeline-number">05</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content bg--blue-100 p-4 r-18">
                            The NBFC will determine loan sanction, approval, and disbursement
                            in accordance with its rules and regulations.
                        </div>
                        <div class="timeline-number">06</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Quick and swift steps section end -->

<!-- eligibility calculator starts -->
<section id="features-21" class="py-80 features-section division bg--blue-500">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-2 color--white">Know Your <span class="color--blue-500">EMI in Seconds</span>
                    </h2>
                    <p class="mt-0 color--grey">Simplify Your Financial Planning </p>
                </div>
            </div>
        </div>
        <div class=" p-30 bg--white-100 shadow border-grey-1 r-20">

            <div class="row">
                <div class="col-md-7 order-first order-md-2">
                    <div id="emicalculatorinnerformwrapper">
                        <form id="emicalculatorform" class="comment-form">
                            <div class="form-horizontal" id="emicalculatorinnerform">
                                <div class="row">
                                    <!-- Loan Amount slider section starts -->
                                    <div class="col-md-12">
                                        <div class="row form-group lamount flex-display align-items-center">
                                            <label class="col-6 control-label s-18 w-500" for="loanamount">Loan
                                                amount</label>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text color--purple-500">₹</span>
                                                        </div>
                                                        <input class="form-control custm-box w-400" id="loanamount"
                                                            name="loanamount" value="10,00,000" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="loanamountslider"></div>
                                        <div id="loanamountsteps" class="steps">
                                            <span class="tick" style="left: 0%;">| <br>
                                                <span class="marker">50K</span>
                                            </span>
                                            <span class="tick d-none d-sm-block" style="left: 12.5%;">| <br>
                                                <span class="marker">10L</span>
                                            </span>
                                            <span class=tick style="left: 25%;">| <br>
                                                <span class=marker>20L</span>
                                            </span>
                                            <span class="tick d-none d-sm-block" style="left: 37.5%;">| <br>
                                                <span class="marker">30L</span>
                                            </span>
                                            <span class="tick" style="left: 50%;">| <br>
                                                <span class="marker">40L</span>
                                            </span>
                                            <span class="tick d-none d-sm-block" style="left: 62.5%;">| <br>
                                                <span class="marker">50L</span>
                                            </span>
                                            <span class="tick" style="left: 75%;">| <br>
                                                <span class="marker">60L</span>
                                            </span>
                                            <span class="tick d-none d-sm-block" style="left: 87.5%;">| <br>
                                                <span class="marker">70L</span>
                                            </span>
                                            <span class="tick" style="left: 100%;">| <br>
                                                <span class="marker">80L</span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Loan Amount slider section ends -->
                                    <!-- Interest Rate slider section starts -->
                                    <div class="col-md-12 mt-100">
                                        <div class="row form-group lint flex-display align-items-center">
                                            <label class="col-6 s-18 w-500 control-label" for="loaninterest">Interest
                                                rate</label>
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <input class="form-control custm-box w-400" id="loaninterest"
                                                        name="loaninterest" value="10.5" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="loaninterestslider"></div>
                                        <div id="loanintereststeps" class="steps">
                                            <span class="tick" style="left: 0%;">| <br>
                                                <span class="marker">5</span>
                                            </span>
                                            <span class="tick" style="left: 16.67%;">| <br>
                                                <span class="marker">7.5</span>
                                            </span>
                                            <span class="tick" style="left: 33.34%;">| <br>
                                                <span class="marker">10</span>
                                            </span>
                                            <span class="tick" style="left: 50%;">| <br>
                                                <span class="marker">12.5</span>
                                            </span>
                                            <span class="tick" style="left: 66.67%;">| <br>
                                                <span class="marker">15</span>
                                            </span>
                                            <span class="tick" style="left: 83.34%;">| <br>
                                                <span class="marker">17.5</span>
                                            </span>
                                            <span class="tick" style="left: 100%;">| <br>
                                                <span class="marker">20</span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Interest Rate slider section ends -->
                                    <!-- Loan Tenure slider section starts -->
                                    <div class="col-md-12 mt-100">
                                        <div class="row form-group lterm flex-display align-items-center">
                                            <label class="col-6 s-18 w-500 control-label" for="loanterm">Select EMI
                                                option</label>
                                            <div class="col-6">
                                                <div class="loantermwrapper">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend d-none">
                                                            <label class="s-14 input-group-text">
                                                                <input type="radio" class="mr-5" name="loantenure"
                                                                    id="loanyears" value="loanyears" tabindex="4"
                                                                    autocomplete="off"><span class="s-14">Yr</span>
                                                            </label>
                                                        </div>
                                                        <input class="form-control custm-box-2 w-400" id="loanterm"
                                                            name="loanterm" value="20" type="text">
                                                        <div class="input-group-prepend">
                                                            <label class="s-14 input-group-text months-input">
                                                                <input type="radio" class="mr-5 d-none"
                                                                    name="loantenure" id="loanmonths" value="loanmonths"
                                                                    tabindex="5" autocomplete="off" checked="checked">
                                                                <span class="s-14">Months</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="loantermslider"></div>
                                        <div id="loantermsteps" class="steps">
                                            <span class="tick" style="left: 0%;">| <br>
                                                <span class="marker">0</span>
                                            </span>
                                            <span class="tick" style="left: 16.67%;">| <br>
                                                <span class="marker">5</span>
                                            </span>
                                            <span class="tick" style="left: 33.33%;">| <br>
                                                <span class="marker">10</span>
                                            </span>
                                            <span class="tick" style="left: 50%;">| <br>
                                                <span class="marker">15</span>
                                            </span>
                                            <span class="tick" style="left: 66.67%;">| <br>
                                                <span class="marker">20</span>
                                            </span>
                                            <span class="tick" style="left: 83.33%;">| <br>
                                                <span class="marker">25</span>
                                            </span>
                                            <span class="tick" style="left: 100%;">| <br>
                                                <span class="marker">30</span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Loan Tenure slider section ends -->
                                </div>
                            </div>
                            <input id="loanproduct" name="loanproduct" value type="hidden">
                            <input id="loanstartdate" name="loanstartdate" value type="hidden">
                            <input id="loanyearformat" name="loanyearformat" value type="hidden">
                            <input id="loandata" name="loandata" value type="hidden">
                            <input id="calcversion" name="calcversion" value=4.0 type="hidden">
                        </form>
                        <div class="row gutter-left gutter-right d-none">
                            <div id="emipaymentsummary" class="col-sm-5 col-md-6 no-gutter-left no-gutter-right">
                                <div id="emiamount">
                                    <h4>Loan EMI</h4>
                                    <p>₹ <span>24,959</span>
                                    </p>
                                </div>
                                <div id="emitotalinterest">
                                    <h4>Total Interest Payable</h4>
                                    <p>₹ <span>34,90,279</span>
                                    </p>
                                </div>
                                <div id="emitotalamount" class="column-last">
                                    <h4>Total Payment <br>(Principal + Interest) </h4>
                                    <p>₹ <span>59,90,279</span>
                                    </p>
                                </div>
                            </div>
                            <div id="emipiechart"
                                class="d-none no-gutter-left no-gutter-right col-sm-7 col-md-6 highcharts-container">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- display none graph and list of emi's start --}}
                <div id="emipaymentdetails" class="d-none">
                    <form class="gutter-left gutter-right form-horizontal">
                        <div class="row form-group" id="emipaymentscheduleheader">
                            <label class="col-md-4 col-lg-5 control-label" for="startmonthyear">Schedule showing EMI
                                payments starting from</label>
                            <div class="col-md-4 col-lg-3">
                                <div class="input-group">
                                    <input class="form-control" id="startmonthyear" name="startmonthyear" value
                                        type="text">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3 form-group lyearformat">
                                <select class="form-control" tabindex="15" name="yearformat" id="yearformat">
                                    <option value="calendaryear" selected="selected">Calendar Year wise</option>
                                    <option value="financialyear">Financial Year wise</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div id="emibarchart" class="hidden-ts highcharts-container"></div>
                    <div id="emipaymenttable"></div>
                </div>
                {{-- display none graph and list of emi's end --}}
                <div class="col-md-5 order-last order-md-2 emi-details">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="p-4 text-center border-bottom">
                                <h6 class="card-title mb-3">Your monthly instalment:</h6>
                                <h2 class="mb-0 text-center s-40 color--purple-500" id="emiamount">₹<span>888</span>
                                </h2>
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted s-15">Total interest</span>
                                    <span id="emitotalinterest">₹<span>656</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted s-15">Principal amount</span>
                                    <span id="principalamount">₹<span>10,000</span></span>
                                </div>
                                <hr style="border:1px dashed grey">
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="s-16">Total amount</span>
                                    <span id="emitotalamount">₹<span>10,000</span></span>
                                </div>
                                <a href="{{ route('self.apply.main') }}"
                                    class="btn btn--green-400 hover--tra-black w-100">Apply for loan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- eligibility calculator ends -->
{{--<hr class="divider">--}}

<!-- Our Partners section start  -->
<section id="integrations-2" class="py-80 integrations-section">
    <div class="container">
        <div class="r-12 text-center">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-10">
                    <div class="section-title mb-50">
                        <h2 class="s-28 mb-2">Trusted<span class="color--blue-500"> NBFC Partners</span></h2>
                        <p class="mt-0 color--grey">Partnering with leading NBFCs to bring you reliable loan options.
                        </p>
                    </div>
                </div>
            </div>
            @php
            $lists = nbfcsList();
            @endphp

            <div class="bank-crousel">
                <div class="row">
                    <div class="col text-center">
                        <div class="owl-carousel brands-carousel-6 emi-carousel">
                            {!! $lists['carousel'] !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Our Partners section end  -->

<!-- Testimonioals section starts -->
<section id="reviews-1" class="py-80 reviews-section bg--blue-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12">
                <div class="section-title mb-40">
                    <h2 class="s-28 mb-2">Hear from <span class="color--blue-500">Our Customers</span></h2>
                    <p class="mt-0 color--grey">Discover what our customers have to say about their experience with us.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Testimonials carousel start  -->
                @include('partials.front.testimonials')
                <!-- Testimonials carousel end  -->
            </div>
        </div>
    </div>
</section>
<!-- Testimonioals section ends -->

<!-- Contact Start -->
<section id="contact" class="py-80 bg--blue-500">
    <div class="container">
        <div class="row align-items-start justify-content-center">
            <div class="col-lg-6 md-mb-50 mb-20">
                <div class="sec-title2 mb-40">
                    <h2 class="s-28 mb-2 color--white">Get in <span class="color--blue-500">Touch</span></h2>
                    <p class="description color--grey">Fill in your basic information, and our experts will contact you
                        as soon as
                        possible.</p>
                </div>
                <div class="row gy-3 gx-3">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="address-item">
                            <div class="address-text">
                                <h6 class="color--blue-500"> Customer Support </h6>
                                <p class="address-txt"><a
                                        href="tel:{{ str_ireplace(" ","",config('constant.COMPANY_MOBILE')) }}"
                                        class="text-white">{{ config('constant.COMPANY_MOBILE') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="address-item">
                            <div class="address-text">
                                <h6 class="color--blue-500"> Mail Us </h6>
                                <p class="address-txt"><a
                                        href="mailto:{{ str_ireplace(" ","",config('constant.COMPANY_SUPPORT_MAIL')) }}"
                                        class="text-white">{{ config('constant.COMPANY_SUPPORT_MAIL') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="address-item">
                            <div class="address-text">
                                <h6 class="color--blue-500"> Address </h6>
                                <p class="address-txt text-white">{{ config('constant.COMPANY_ADDRESS') }}</p>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="address-item">
                            <div class="address-text">
                                <h6 class="color--blue-500"> Working Hours </h6>
                                <p class="address-txt text-white">
                                    Monday to Saturday: 10:00 AM - 5:00 PM<br>
                                    Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 py-md-0 py-4">
                <div class="h-100">
                    <div class="bg-white r-24 shadow border-primary h-100 p-4">
                        <div class="card-body">
                            <p class="w-400 mb-20">
                                Fill out the form below and you'll hear from us soon.
                            </p>
                            <form method="post" action="{{ route('front.contact.us.store') }}"
                                class="contact-form career-form career-form-1" enctype="multipart/form-data">
                                <div class="row gx-2 gy-2">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group form-floating">
                                            <label for="firstname" class="position-static p-0 w-500 text-uppercase">Full
                                                Name *</label>
                                            <input id="form_name" name="fullname" type="text"
                                                class="form-control name mb-0 py-0" placeholder="">
                                        </div>
                                        @component('components.ajax-error',['field'=>'fullname'])@endcomponent
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group form-floating">
                                            <label for="form_mobile"
                                                class="position-static p-0 w-500 text-uppercase">Mobile *</label>
                                            <input id="form_mobile" type="text" name="mobile"
                                                class="numeric-input mb-0 form-control mobile py-0" placeholder=""
                                                minlength="10" maxlength="10" inputmode="numeric">

                                        </div>
                                        @component('components.ajax-error',['field'=>'mobile'])@endcomponent
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group form-floating">
                                            <label for="form_email"
                                                class="position-static p-0 w-500 text-uppercase">Email *</label>
                                            <input id="form_email" type="email" name="email"
                                                class="mb-0 form-control email py-0" placeholder="">

                                        </div>
                                        @component('components.ajax-error',['field'=>'email'])@endcomponent
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group form-floating">
                                            <label for="form_subject"
                                                class="position-static p-0 w-500 text-uppercase">Subject *</label>
                                            <input id="form_subject" type="text" name="subject"
                                                class="mb-0 form-control subject py-0" placeholder="">

                                        </div>
                                        @component('components.ajax-error',['field'=>'subject'])@endcomponent
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group form-floating">
                                            <label for="form_message"
                                                class="position-static p-0 w-500 text-uppercase">Message *</label>
                                            <textarea id="form_message" name="desc" class="mb-0 form-control message pt-3"
                                                placeholder="" style="height: 150px"></textarea>

                                        </div>
                                        @component('components.ajax-error',['field'=>'desc'])@endcomponent
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit"
                                            class="s-14 btn btn--green-400 hover--tra-black submit rounded-pill w-100"
                                            id="submit-btn">Submit Request <span class="fbox-ico ico-10"> <span class="flaticon-right-arrow  ico-20 ms-1"></span></span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact End -->

{{-- Wlecome message modal show here --}}
@if($msg->status == 1)
<div class="modal fade myModal" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row p-3">
                    <p>{!! $msg->content ?? 'N/A' !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('script-src')
<script type="text/javascript" src="{{ asset('front/calc/calccore.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/calc/mouse.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/calc/slider.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/calc/commoncalculator.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/calc/emicalculator.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('front/js/home.js') }}" type="text/javascript"></script>
@if($msg->status == 1)
<script>
$(document).ready(function() {
    setTimeout(function() {
        $(".myModal:not(.auto-off)").modal("show");
    }, 3600);
})
</script>
@endif

<script>
const routes = {
    'selfapply': "{{ route('self.apply.send.otp') }}",
};

document.addEventListener('DOMContentLoaded', () => {
    const faqs = document.querySelectorAll('#faq-container li');
    const loadMoreButton = document.getElementById('load-more-faq');
    const viewLessButton = document.getElementById('view-less-faq');
    let visibleCount = 5; // Number of FAQs initially shown
    const batchSize = 5; // Number of FAQs to show on each click

    // Initial setup: Show the first 7 FAQs
    faqs.forEach((faq, index) => {
        if (index >= visibleCount) {
            faq.style.display = 'none';
        }
    });

    // Event listener for Load More button
    loadMoreButton.addEventListener('click', () => {
        const hiddenFaqs = Array.from(faqs).filter(faq => faq.style.display === 'none');
        for (let i = 0; i < batchSize && i < hiddenFaqs.length; i++) {
            hiddenFaqs[i].style.display = 'list-item';
        }

        // Show the "View Less" button once more items are displayed
        if (hiddenFaqs.length > 0) {
            viewLessButton.style.display = 'inline-block';
        }

        // Hide the "Load More" button if no more FAQs to show
        if (hiddenFaqs.length <= batchSize) {
            loadMoreButton.style.display = 'none';
        }
    });

    // Event listener for View Less button
    viewLessButton.addEventListener('click', () => {
        faqs.forEach((faq, index) => {
            if (index >= visibleCount) {
                faq.style.display = 'none';
            }
        });

        // Reset button visibility
        loadMoreButton.style.display = 'inline-block';
        viewLessButton.style.display = 'none';
    });
});

$(document).ready(function() {
    $(".contact-form").submit(function(e) {
        let status = document.activeElement.innerHTML;
        e.preventDefault();
        if (status) {
            $('.ajax-error').html('');
            let data = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#submit-btn").html(
                        '<span class="spinner-border spinner-border-sm"></span> Submit Request '
                    )
                    $("#submit-btn").attr('disabled', true);
                },
                success: function(result) {
                    $(this).attr("disabled", false);
                    if (result.type === 'SUCCESS') {
                        toastr.success(result.message);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        toastr.error(result.message);
                        $('#submit-btn').html('Submit Request');
                        $('#submit-btn').attr('disabled', false);
                    }
                },
                error: function(error) {
                    $(this).attr("disabled", false);
                    let errors = error.responseJSON.errors,
                        errorsHtml = '';
                    $.each(errors, function(key, value) {
                        errorsHtml = '<strong>' + value[0] + '</strong>';
                        $('.' + key).html(errorsHtml);
                    });
                    $('#submit-btn').html('Submit Request');
                    $('#submit-btn').attr('disabled', false);
                }
            });
        }
    });
});
</script>
@endpush