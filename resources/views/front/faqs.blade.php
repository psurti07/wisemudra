@extends('layouts.front')
@push('css')
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('style-css')
@endpush
@section('content')
    <section class="page-hero-section">
        <div class="page-hero-section-overlay bg--green-100 bg--scroll">
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-12 text-center">
                        <div class="txt-block left-column">
                            <span class="section-id"></span>
                            <h2 class="w-700">Frequently Asked <span class="color--green-500">Questions</span></h2>
                            <p class="p-md w-400">
                                It's our fundamental trait to be transparent with our customers!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faqs-3" class="py-80 faqs-section">
        <div class="container">
            <div class="faqs-3-questions">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-xl-12">
                        <div class="accordion-wrapper">
                            <ul class="accordion">
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What is Loan Self-Apply?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    With this feature, you will get the login link through which you can easily start your loan process in our partnered NBFC.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What is Expert Login?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>If you need expert assistance for the loan application process in our partnered NBFCs, our team of experienced professionals will conduct on the process seamlessly.</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What are NBFC Partners?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    NBFCs stands for Non-Banking Financial Companies providing financial and lending services. Wisemudra is a direct selling agent with multiple NBFCs.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">How to start your loan process?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    Click on Apply Now and start your loan process with utmost ease! Make the most of our Loan Self-Apply/Expert Apply features.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">How can I reach out to the Wisemudra team?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    You can simply write to us at <a href="mailto:info@wisemudra.com" class="text-decoration-none">info@wisemudra.com</a> – our cheered up team is always eager to help you out.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What is a Personal Loan?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    A personal loan is an unsecured loan, for which you need not pledge collateral to receive funds. A personal loan can be a very handy option while dealing a financial urgency.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item mb-10">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">Personal Loan be used for what purposes?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    Personal Loans can be leveraged for any personal monetary pursuits, like:
                                                </p>
                                                <ul class="ml-30">
                                                    <li> - Wedding Expenses</li>
                                                    <li> - Home Renovation Expenses</li>
                                                    <li> - Higher Education Expenses</li>
                                                    <li> - High-Interest Debt Consolidation</li>
                                                    <li> - Tour &amp; Travel Expenses</li>
                                                    <li> - Shopping Expenses</li>
                                                    <li> - Medical emergency, etc.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What is the eligibility for a Personal Loan?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item">
                                            <div class="faqs-2-answer">
                                                <ul class="ml-30" style="list-style-type: inside">
                                                    <li>Age: 21 to 60 years</li>
                                                    <li>At least 1-year job stability.</li>
                                                    <li>Min. Salary: Rs.15,000/- monthly – received in the bank account</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">How to apply for a Personal Loan instantly?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item mb-35">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    Simply visit <a href="{{ route('self.apply.main') }}" class="text-decoration-none">Apply Now</a> and start your personal loan process in just a few clicks!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What’s the ideal CIBIL score/credit score required for a Personal Loan?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item mb-35">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    650 or more stands as a good credit score!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="accordion-item">
                                    <div class="accordion-thumb">
                                        <h6 class="w-600">What is Wisemudra’s Loan Self-Apply facility?</h6>
                                    </div>
                                    <div class="accordion-panel">
                                        <div class="accordion-panel-item mb-35">
                                            <div class="faqs-2-answer">
                                                <p>
                                                    With this feature, you will get the login link through which you can easily start your loan process in our partnered NBFC.
                                                </p>
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
    <hr class="divider">
@endsection
@push('script-src')
@endpush
@push('scripts')
@endpush
