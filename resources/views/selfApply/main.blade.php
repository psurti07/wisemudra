@extends('layouts.selfapply')
@push('css')
    {{-- write or link your css file and styles tag here --}}
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .resend-otp-div a.disabled {
            pointer-events: none;
            cursor: not-allowed;
        }
        .bank-crousel{
            display:block!important;
        }
        .radio:checked{ background: #02a6fc; border-color: #02a6fc; }
        .owl-carousel .owl-item img{
            width: 100%!important;
        }
        .testimonials-carousel .owl-item img{
            width: 100%!important;
        }

        @media screen and (max-width: 767px){
            .hero-section { padding-top:10px!important; }
        }
        .input-group-text{ color:#666;border:none;background-color: #f5f6f8;line-height: 1.3;border-top-left-radius: 5px;border-bottom-left-radius: 5px;border-top-right-radius: 0px;border-bottom-right-radius: 0px; }
        .request-form .form-control:focus{background-color:#f5f6f8;border-color:#f5f6f8;border:none;}
        @media screen and (max-width:991px){
            .input-group-text{ padding:1.06rem 1.06rem; }
        }
        @media screen and (min-width:992px) and (max-width:1199px){
            .input-group-text{ padding:1rem 1rem;margin-top:1px; }
        }
    </style>
@endpush
@section('content')
    <section id="hero-201" class="bg--white-100 bg--fixed hero-section">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-6 col-lg-6">
                    <div class="mt-30 mb-20">
                        <h1 class="s-50 mb-20">Get Instant <span class="color--green-500">Cash</span></h1>
                        <h3 class="s-24 mb-20">Up to <span class="color--green-500">₹10 Lakhs</span> in 24 Hours</h3>
                        <p class="s-18">India's fastest digital loan platform with instant pre-approval.</p>
                    </div>

                    <div class="d-none d-md-block d-lg-block">
                        <div class="row gx-2 gy-2 row-cols-1 row-cols-md-3 row-cols-lg-3 mb-10">
                            <div class="col">
                                <div class="fbox-7 fbox--hover bg--white-100 fb-1 r-12 p-3">
                                    <div class="fbox-ico ico-25 mb-2">
                                        <div class="shape-ico color--theme">
                                            <span class="flaticon-shield"></span>
                                        </div>
                                    </div>
                                    <div class="fbox-txt">
                                        <h4 class="s-16 w-700">Secure & Safe</h4>
                                        <p class="mb-0">256-bit encryption</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="fbox-7 fbox--hover bg--white-100 fb-1 r-12 p-3">
                                    <div class="fbox-ico ico-25 mb-2">
                                        <div class="shape-ico color--theme">
                                            <span class="flaticon-coin"></span>
                                        </div>
                                    </div>
                                    <div class="fbox-txt">
                                        <h4 class="s-16 w-700">Instant Approval</h4>
                                        <p class="mb-0">Within 5 minutes</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="fbox-7 fbox--hover bg--white-100 fb-1 r-12 p-3">
                                    <div class="fbox-ico ico-25 mb-2">
                                        <div class="shape-ico color--theme">
                                            <span class="flaticon-bar-chart"></span>
                                        </div>
                                    </div>
                                    <div class="fbox-txt">
                                        <h4 class="s-16 w-700">Low Interest Rate</h4>
                                        <p class="mb-0">Starting at 10.5%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <p class="btn-txt mb-3 ico-15"><span class="flaticon-check color--theme"></span> No Collateral Required</p>
                                <p class="btn-txt mb-3 ico-15"><span class="flaticon-check color--theme"></span> Flexible Repayment Options</p>
                            </div>

                            <div class="col-lg-6 col-md-6 col-12">
                                <p class="btn-txt mb-3 ico-15"><span class="flaticon-check color--theme"></span> Pre-Approved Offers Available</p>
                                <p class="btn-txt mb-3 ico-15"><span class="flaticon-check color--theme"></span> Minimal Documentation</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 self-apply-form">
                    <div id="hero-8-form" class="border border-primary r-06">
                        <h4 class="s-20 w-700 mb-2">Apply in <span class="color--green-500">5 Minutes</span></h4>
                        <p class="s-16">Get instant pre-approved offers.</p>
                        <form method="post" action="{{ route('self.apply.send.otp') }}" class="request-form save-form-1 needs-validation" novalidate>
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <div class="form-check ps-0">
                                        <div class="row gx-2">
                                            <div class="col-md-6 col-lg-6 col-sm-6 mb-2">
                                                <fieldset class="picker1">
                                                    <label class="card" for="personalloan">
                                                        <input type="radio" name="loan_type" id="personalloan" value="1" class="radio" checked>
                                                        <span class="plan-details">
                                                            <span class="plan-type color--green-500">Personal Loan</span>
											            </span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-6 mb-2">
                                                <fieldset class="picker1">
                                                    <label class="card" for="businessloan">
                                                        <input type="radio" name="loan_type" id="businessloan" value="2" class="radio">
                                                        <span class="plan-details">
                                                            <span class="plan-type color--green-500">Business Loan</span>
											            </span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group form-floating">
                                        <input type="hidden" name="acc_type" value="1" id="acc_type">

                                        <div class="mb-2">
                                            <select name="user_type" id="user_type" class="form-control form-select">
                                                <!-- Personal Options -->
                                                <option value="1" data-loan="personal">Salaried</option>
                                                <option value="2" data-loan="personal">Self Employed</option>

                                                <!-- Business Options -->
                                                <option value="3" data-loan="business" style="display:none;">Small Business</option>
                                                <option value="4" data-loan="business" style="display:none;">Audited Report</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+91</span>
                                        </div>
                                        <input type="text" name="mobile" id="mobile" class="numeric-input form-control name" placeholder="Enter Your Mobile*" autocomplete="off" required maxlength="10" minlength="10" inputmode="numeric">
                                    </div>
                                    @component('components.ajax-error',['field'=>'mobile'])@endcomponent
                                </div>
                                <div class="col-md-12 form-btn">
                                    <button type="submit" id="checkmodal" class="btn btn--theme hover--theme submit">Apply Now</button>
                                </div>
                                <div class="col-md-12"><span class="text-danger" id="usererrormsg"></span></div>
                            </div>
                           
                            <p class="mb-0 s-14 text-start text-dark">By submitting, you agree to Wisemudra's <a href="{{ route('front.terms.conditions') }}"  target="_blank"class="text-dark text-decoration-none">Terms of Use</a> and <a href="{{ route('front.privacy.policy') }}" class="text-dark text-decoration-none" target="_blank">Privacy Policy</a>, and consent to receive promotional and informational messages via email, call, or SMS.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Affiliate NBFCs section starts -->
    <section id="integrations-2" class="pt-0 pb-40 integrations-section">
        <div class="container">
            <div class="r-12 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="section-title mb-50">
                            <h2 class="s-28">Trusted by the <span class="color--green-500">Best in the Industry</span></h2>
                            <p class="s-16 color--grey">Partnering with the best NBFCs to provide the best for our customers.</p>
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
    <!-- Affiliate NBFCs section ends -->

    <!-- why Wisemudra section starts -->
    <section id="features-6" class="pt-60 pb-20 features-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9">
                    <div class="section-title mb-50">
                        <h2 class="s-28">Why <span class="color--green-500">Wisemudra</span></h2>
                        <p class="s-16 color--grey">Here's what sets us apart.</p>
                    </div>
                </div>
            </div>
            <div class="fbox-wrapper text-center">
                <div class="row gx-3 gy-2 row-cols-1 row-cols-md-2 row-cols-lg-4">
                    <div class="col">
                        <div class="fbox-8 fbox--hover fb-1 r-12">
                            <div class="fbox-ico ico-50">
                                <div class="shape-ico color--theme">
                                    <span class="flaticon-layers-1"></span>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h4 class="s-18 w-700">Enriching Collaboration</h4>
                                <p>Access a wide range of financial services empowered by our industry-leading NBFC partners.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8 fbox--hover fb-2 r-12">
                            <div class="fbox-ico ico-50">
                                <div class="shape-ico color--theme">
                                    <span class="flaticon-computer-1"></span>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h4 class="s-18 w-700">100% Online Process</h4>
                                <p>Experience the convenience and power of digital provisions from the comfort of your own home.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8 fbox--hover fb-2 r-12">
                            <div class="fbox-ico ico-50">
                                <div class="shape-ico color--theme">
                                    <span class="flaticon-click-1"></span>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h4 class="s-18 w-700">Self-Apply Feature</h4>
                                <p>Take charge of your own finances while reaping the benefits of impeccable provisions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8 fbox--hover fb-3 r-12">
                            <div class="fbox-ico ico-50">
                                <div class="shape-ico color--theme">
                                    <span class="flaticon-tech-support"></span>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h4 class="s-18 w-700">Hire Loan Agent</h4>
                                <p>Benefit from our experts' insights and strategies to increase your chances of loan approval.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- why Wisemudra section ends -->

    <!-- Testimonioals section starts -->
    <section id="reviews-1" class="py-80 shape--06 reviews-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="section-title mb-50">
                        <h2 class="s-28">What Our <span class="color--green-500">Customer Says</span></h2>
                        <p class="s-16 color--grey">Hear directly from our customers about their experiences with us.</p>
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
   
    <section class="py-20 shape--06 gr--smoke">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-holder">
                        <div class="contact-form-notice">
                            <p class="s-14">
                                <strong>Disclosure:</strong> Loan Tenure ranges from minimum 6 months to maximum of 72 months with Annual Interest Rates ranging between 11% - 35%. Processing fee up to 2%. For Example: Taking in consideration a personal loan of Rs.1,00,000 availed at 11%* interest rate for a tenure of 6* years with 2%* processing fee, the APR will be 11.75%*. Wisemudra is not a direct lender itself. Instead we provide consultation services to help users connect with right NBFCs for loan approval. *T&C Apply. All these numbers are tentative/indicative, the final loan specifics may vary depending upon the customer profile and NBFCs’ criteria, rules & regulations, and terms & conditions.
                            </p>
                            <p class="s-14">
                                <strong>Disclaimer:</strong> Wisemudra is a loan service aggregator and is authorised to provide services on behalf of its Lending Partners.The amount paid is only for the service charge. We are not lenders and do not guarantee any loan approval.
                            </p>
                            <p class="s-14">
                                <strong>Important Note:</strong> BE AWARE! We ask our customers to make payments ONLY on our website https://wisemudra.com and NOT through any other source, directly or indirectly. Thanks!
                            </p>
                            <p class="s-14">
                                <strong>Registered Office Address:</strong> {{ env('COMPANY_ADDRESS') }} <br/> Mobile: {{ env('COMPANY_MOBILE') }} | Email: {{ env('INFO_EMAIL') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- otp modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-30 border-radius-10">
                <form action="{{ route('self.apply.verify.otp') }}" method="post" class="request-form save-form-2 needs-validation" novalidate>
                    <div class="modal-body">
                        <div class="row">
                            <p class="s-24">OTP Verification</p>
                            <p class="s-16">Enter the 4 digit OTP received on your mobile <br/>
                                <span class="text-success w-600">+91 <span class="text-success w-600" id="mobileNumber"></span>
                                    <a href="javascript:;" class="edit-phoneNumber" title="Edit Phone number">
                                        <svg width="40px" height="18px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#949494">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.43200000000000005"/>
                                            <g id="SVGRepo_iconCarrier"> <path d="M15.4998 5.50067L18.3282 8.3291M13 21H21M3 21.0004L3.04745 20.6683C3.21536 19.4929 3.29932 18.9052 3.49029 18.3565C3.65975 17.8697 3.89124 17.4067 4.17906 16.979C4.50341 16.497 4.92319 16.0772 5.76274 15.2377L17.4107 3.58969C18.1918 2.80865 19.4581 2.80864 20.2392 3.58969C21.0202 4.37074 21.0202 5.63707 20.2392 6.41812L8.37744 18.2798C7.61579 19.0415 7.23497 19.4223 6.8012 19.7252C6.41618 19.994 6.00093 20.2167 5.56398 20.3887C5.07171 20.5824 4.54375 20.6889 3.48793 20.902L3 21.0004Z" stroke="#949494" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g>
                                        </svg>
                                    </a>
                                </span>
                            </p>
                            <div class="otp-form text-center">
                                <div class="otp-container">
                                    <input type="text" class="otp-input" pattern="\d" maxlength="1" inputmode="numeric">
                                    <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled inputmode="numeric">
                                    <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled inputmode="numeric">
                                    <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled inputmode="numeric">
                                    {{--<input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>
                                    <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>--}}
                                </div>
                            </div>
                            <span class="mt-2 s-12 text-success" id="msg">
                            <input type="hidden" id="verificationCode" name="otp" readonly>
                            <input type="hidden" id="acc_type" name="acc_type" readonly value="1">
                            <input class="form-check-input" value="1" type="hidden" id="flexCheckDefault1" checked name="allow_sms"/>
                            <input class="form-check-input" value="1" type="hidden" id="flexCheckDefault" checked name="accept_tnc"/>
                            <span class="text-danger f-w-400" id="invalidOtp" style="font-size:14px"></span>
                            @component('components.ajax-error',['field'=>'otp'])@endcomponent
                        </div>
                        <div class="row color--black resend-otp-div">
                            <p class="s-12">Didn’t receive the code? <a href="javascript:;" id="resendOtp" class="text-success">Resend OTP</a>&nbsp;<span id="timer" class="text-success">(00:15)</span></p>
                        </div>
                    </div>
                    <button type="submit" id="otpBtn" class="btn btn--theme hover--theme submit mt-3">Verify OTP</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const personalLoan = document.getElementById("personalloan");
        const businessLoan = document.getElementById("businessloan");

        const userTypeSelect = document.getElementById("user_type");
        const options = userTypeSelect.querySelectorAll("option");

        function toggleUserTypeOptions() {
            let type = "personal";
            if (businessLoan && businessLoan.checked) type = "business";

            // Show/Hide options based on loan type
            options.forEach(option => {
            if (option.dataset.loan === type) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
            });

            // If current selected option is hidden, select first visible one
            const selectedOption = userTypeSelect.options[userTypeSelect.selectedIndex];
            if (selectedOption.style.display === "none") {
            const firstVisible = [...options].find(opt => opt.style.display !== "none");
            if (firstVisible) userTypeSelect.value = firstVisible.value;
            }
        }

        // Run on load
        toggleUserTypeOptions();

        // Run on change
            if (personalLoan) personalLoan.addEventListener("change", toggleUserTypeOptions);
            if (businessLoan) businessLoan.addEventListener("change", toggleUserTypeOptions);
        });
    </script>

    <script>
        const sendOtpUrl = @json(route('self.apply.send.otp'));
    </script>
    <!-- write or link your script file and script tag here -->
    <script src="{{ asset('front/js/selfApply.js') }}"></script>
    <script>
        window.onscroll = function () {
          const btn = document.getElementById("goTopBtn");
          btn.style.display = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? "block" : "none";
        };
        
        // Scroll to top smoothly
        function goToTop() {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
@endpush
