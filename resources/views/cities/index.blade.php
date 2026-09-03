@extends('layouts.front')
@push('css')
    <link rel="stylesheet" href="{{ asset('front/calc/commoncalculator.css') }}">
    <link rel="stylesheet" href="{{ asset('front/calc/emicalculator.css') }}">
    <link rel="stylesheet" href="{{ asset('front/calc/calcstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/extraPages.css') }}">
@endpush
@push('style-css')
    <style>
        .fbox-11{
            border:1px solid #ccc;
            border-radius: 10px;
            padding: 1.5rem 2rem;
        }
        .page-hero-section-overlay{
            padding-bottom: 170px!important;   
        }
        .bg--24{
            background: url({{ asset('front/city/'.$city.'.jpg') }});
            background-position: right bottom, left bottom;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
@endpush
@section('content')
    <section class="page-hero-section">
        <div class="page-hero-section-overlay bg--24">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-12 col-lg-12 text-center col-sm-12 mt-30 order-first form-details-section">
                        <div class="txt-block left-column color--white">
                            <h1 class="s-34 w-700 line mb-20">Personal Loan in {{ ucwords($city) }} – Quick & Easy Loans to Meet Your Needs
                            </h1>
                            <a href="{{ route('loan.agent.main') }}" class="btn r-04 btn--green-500 hover--tra-white last-link">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features sections starts -->
    <section id="features-11" class="py-80 features-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="section-title mb-40">
                        <h2 class="s-34 w-700">Why Choose Wisemudra for a Personal Loan in {{ ucwords($city) }}?</h2>
                        <p class="s-16">We partner with NBFCs and lenders open to offering loans with easy terms and quick processing in {{ ucwords($city) }}.</p>
                    </div>
                </div>
            </div>
            <div class="fbox-wrapper">
                <div class="row row-cols-1 row-cols-md-2 rows-2">
                    <div class="col">
                        <div class="fbox-11 fb-1 mb-20 height14">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme">
                                        <span class="flaticon-time"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Trusted Partners with Top Banks & NBFCs</h6>
                                <p>We work with leading lenders to help you get the best personal loan in {{ ucwords($city) }}. Whether you need money for a medical emergency, marriage, travel, or home renovation – we offer loan options that suit your needs and lifestyle.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-11 fb-1 mb-20 height14">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme">
                                        <span class="flaticon-paper-sizes"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Fast Approval with Easy Process</h6>
                                <p>No more long waits! Our partner lenders offer quick loan approvals, often within 24-48 hours. The process is online, smooth, and fast so you can get your loan without any hassle.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-11 fb-1 mb-20 height14">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme">
                                        <span class="flaticon-workflow-1"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Minimum Documents Required</h6>
                                <p>You don’t need to run behind paperwork. Just share your basic KYC documents, income proof, and bank details – and you’re good to go. The process is simple and stress-free.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-11 fb-1 mb-20 height14">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme">
                                        <span class="flaticon-shield"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Flexible Loan Amounts & EMI Options</h6>
                                <p>Whether you need ₹50,000 or ₹25 lakhs, we have options for all. Choose your loan amount and EMI based on your salary, repayment ability, and comfort.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-11 fb-1 mb-20 height14">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme">
                                        <span class="flaticon-time-1"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">100% Transparent – No Hidden Charges</h6>
                                <p>We ensure full clarity. From interest rates to processing fees – everything is shared upfront. So, you know exactly what you’re signing up for. No surprises later!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-11 fb-1 mb-20 height14">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme">
                                        <span class="flaticon-calendar"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Loans for All – Salaried & Self-Employed</h6>
                                <p>Whether you're a private employee, government worker, or running your own business – you can easily get a personal loan in {{ ucwords($city) }} through Wisemudra.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features sections ends -->

    <!-- fees and charges table section start -->
    <section id="features-6" class="py-80 shape--06 shape--gr-whitesmoke features-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="section-title mb-40">
                        <h2 class="s-34 w-700">Is There Any Fee for Getting a Loan in {{ ucwords($city) }}?</h2>
                        <p class="s-16">Yes, lenders may charge a small processing fee, usually 1% to 3% of the loan amount. But don’t worry – we share all the charges before you apply. No hidden costs at all!</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="30%">Fee Type</th>
                                <th width="70%">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Rate Of Interest</td>
                                <td>Starting from 16% p.a. on a reduced balance basis</td>
                            </tr>
                            <tr>
                                <td>Processing Fee</td>
                                <td>Starting from 2% of the loan amount plus GST</td>
                            </tr>
                            <tr>
                                <td>Tenure</td>
                                <td>3 to 36 months</td>
                            </tr>
                            <tr>
                                <td>APR</td>
                                <td>Starting from 18%</td>
                            </tr>
                            <tr>
                                <td>Bounce Charge</td>
                                <td>₹500 plus GST</td>
                            </tr>
                            <tr>
                                <td>Late Payment Charges</td>
                                <td>₹500 plus GST or 3% of the total loan amount, whichever is higher as per the overdue amount</td>
                            </tr>
                            <tr>
                                <td>Stamp Duty</td>
                                <td>0.1% of the loan amount</td>
                            </tr>
                            <tr>
                                <td>Mandate Rejection Charges</td>
                                <td>₹250 plus GST</td>
                            </tr>
                            <tr>
                                <td>Pre-Clouser Charges</td>
                                <td>Nil</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- fees and charges table section end -->

    <!-- Loan criteria start -->
    @include('partials.front.loan-criteria')
    <!-- Loan criteria end -->

    <!-- Fees & Interest Rates start -->
    @include('partials.front.amount-section')
    <!-- Fees & Interest Rates end -->

    <!-- Purpose and uses section starts -->
    <section id="integrations-1" class="py-60 integrations-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="section-title mb-50">
                        <h2 class="s-34 w-700">Choose a Personal Loan That Fits You
                        </h2>
                        <p class="s-16 color--grey">Not sure which one is best? No problem! We help you compare and choose the right personal loan plan in {{ ucwords($city) }} based on your budget and needs. Apply online and pay back in easy EMIs.
                        </p>
                    </div>
                </div>
            </div>
            <div class="integrations-1-wrapper">
                <div class="row">
                    <div class="col-md-12 order-last">
                        <div class="owl-carousel owl-theme industry-carousel">
                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-rocket-launch"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Travel</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-search-engine"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Education</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-24-7"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Emergency</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-shopping-cart"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Shopping</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-ring"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Wedding</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-money-1"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Low Salary</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-heart"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Maternity</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-gift-box"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Gifting</h4>
                                </div>
                            </div>

                            <div class="fbox-7 fb-1 r-12 mx-2 bg--white-100">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico color--theme d-flex justify-content-center">
                                        <span class="flaticon-home"></span>
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z" transform="translate(100 100)"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="fbox-txt text-center">
                                    <h4 class="s-15 w-600">Home Renovation</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Purpose and uses section ends -->

    <!-- Eligibility CTA section starts -->
    @include('partials.front.eligibility-cta')
    <!-- Eligibility CTA section ends -->

    <!-- FAQs section start  -->
    <section id="faqs-3" class="py-60 faqs-section">
        <div class="container">
            <div class="faqs-3-questions">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-xl-12">
                        <div class="inner-page-title mb-50">
                            <h2 class="s-34 w-700">Who can apply for a personal loan in {{ ucwords($city) }}?</h2>
                            <p class="s-16">Any salaried or self-employed person aged between 21-60 years with a stable income and a good credit history can apply.
                            </p>
                        </div>
                        <div class="accordion-wrapper" id="faq-container">
                            <ul class="accordion">
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">How much loan can I get in {{ ucwords($city) }}?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>Depending on your profile, you can get between ₹50,000 to ₹25 lakhs.</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">How long does it take to get the loan?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>Once you submit all documents, it usually takes 24 to 48 hours for loan disbursal.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">Do I need to visit any office in {{ ucwords($city) }}?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>No. You can apply for a personal loan completely online through Wisemudra. No office visit required.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">What documents are required for a personal loan?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>You’ll need PAN card, Aadhaar, salary slip or ITR, bank statement, and one photo.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">Can I get a loan with low CIBIL score in {{ ucwords($city) }}?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>Some NBFCs do provide loans even with lower scores, but the interest rate might be higher.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">Can I pre-close my loan early?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>Yes. Most lenders allow loan pre-closure after 6–12 months. Check for any foreclosure charges.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">What’s the interest rate for a personal loan in {{ ucwords($city) }}?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>Rates generally range between 10.5% to 24% depending on your profile and the lender.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">Is there a processing fee?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>Yes, usually 1% to 3% of the loan amount.</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="s-18 w-500">How do I repay the loan?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>You can repay in monthly EMIs directly from your bank account.</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>

                        </div>
                        <div class="text-center">
                            <button id="load-more" class="btn btn-sm btn--tra-grey hover--purple-500 last-link">View More</button>
                            <button id="view-less" style="display:none" class="btn btn-sm btn--tra-grey hover--purple-500 last-link">View Less</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQs section end  -->

    <!-- Hire agent section start  -->
    @include('partials.front.cta-hire-agent')
    <!-- Hire agent section end  -->

@endsection
@push('script-src')
    <script type="text/javascript" src="{{ asset('front/calc/calccore.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/calc/mouse.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/calc/slider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/calc/commoncalculator.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front/calc/emicalculator.js') }}"></script>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqs = document.querySelectorAll('#faq-container li');
            const loadMoreButton = document.getElementById('load-more');
            const viewLessButton = document.getElementById('view-less');
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

        $(".industry-carousel").owlCarousel({
            items: 2, // Number of items
            loop: true,
            autoplay: true,
            navBy: 1,
            dots: false,
            autoplayTimeout: 4500,
            autoplayHoverPause: true,
            smartSpeed: 1500,
            responsive: {
                0: {
                    items: 1
                },
                767: {
                    items: 1
                },
                768: {
                    items: 2
                },
                991: {
                    items: 3
                },
                1000: {
                    items: 3
                }
            }
        });
    </script>
@endpush
