@extends('layouts.selfapply')
@push('css')
    <link rel="stylesheet" href="{{ asset('front/css/radiocards.css') }}">
    <style>
        .accordion-button{ background-color: transparent!important; }
        .accordion-button:focus { box-shadow:none!important; }
        .txt-block h2 { margin-bottom:0px!important; }
        .cbox-1.ico-15 span { top:5px!important; }
        a#failed-btn {
            background: #dc3545;
            border: 1px solid #dc3545;
        }
        a#failed-btn:hover{
            background: #bb2d3b !important;
            color: #fff !important;
        }
        .bc-5-img.bc-5-tablet.img-block-hidden{
            margin-bottom: -60px!important;
        }
        .bc-5-img.bc-5-tablet.img-block-hidden .video-btn{
            top: calc(70% - 70px);
        }
        @media only screen and (max-width: 767px) {
            .bc-5-img.bc-5-tablet.img-block-hidden {
                margin-bottom: 50px!important;
            }
        }
        .card:hover .radio:checked{
            border-color:transparent!important;
        }
    </style>
@endpush

@section('content')
    <section id="contacts" class="gr--white personal-details-form pb-100 inner-page-hero contacts-section division">
        <div class="container">
            <div class="row justify-content-center mb-35">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <div class="txt-block left-column">
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">1</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Loan Details</p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">2</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Personal Details</p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">3</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Unlock Offers</p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step border-right">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">4</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Purchase Plan</p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico border-dark-subtle">5</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Personalized Offers</p>
                                    </div>
                                </div>
                                <hr/>
                                <div class="accordion accordion-flush mb-30" id="accordionFlushExample">
                                    <div class="accordion-item bg-transparent">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                User Details
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Fullname</div>
                                                    <div class="col-lg-6 s-14 accordion-row-coll">{{ Cookie::get('fullname') }}</div>
                                                    <hr class="custm-HR"/>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Mobile</div>
                                                    <div class="col-lg-6 s-14 accordion-row-coll">{{ Cookie::get('user_mobile') }}</div>
                                                    <hr class="custm-HR"/>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Loan Amount</div>
                                                    <div class="col-lg-6 s-14 accordion-row-coll">&#8377;{{ formatePriceIndia(Cookie::get('loan_amount')) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-8 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="fw-bolder s-16">Purchase Your Preferred Plan</h5>
                                    <p class="mb-30 color--grey">Access Your Expert Consultation & Personalized Offers!</p>

                                    <form method="post" class="buyNowForm" action="{{ route('self.apply.checkout') }}">
                                        @csrf
                                        <input type="hidden" class="form-control" name="order_amount" id="order_amount" value="">
                                        <div class="row">
                                            <label class="card border-0">
                                                <input name="plan" value="1" class="radio" type="radio" checked data-plan="Self-Apply">
                                                <span class="plan-details">
                                                    <h5 class="fw-bolder s-16 plan-type mb-10">Self-Apply Plan</h5>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Unlock Your Personalized Pre-Approved Loan Offers</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Get Instant Self-Apply Loan Login Link(s)</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Access Your Personalized Portal</p>
                                                        </div>
                                                    </div>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="row mt-3">
                                            <label class="card border-0">
                                                <input name="plan" class="radio" value="2" type="radio" data-plan="Hire-Agent">
                                                <span class="plan-details">
                                                    <h5 class="fw-bolder s-16 plan-type mb-10">Hire Loan Agent Plan</h5>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Get Dedicated Loan Agent for Expert Consultation</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Unlock Your Personalized Pre-Approved Loan Offers</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Expert On-Call Consultation</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Access Your Personalized Portal</p>
                                                        </div>
                                                    </div>
                                                    <!--<div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Expert On-Call Consultation</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Get Instant Self-Apply Loan Login Link(s)</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Access Your Personalized Portal</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> 100% Digital Loan Process</p>
                                                        </div>
                                                    </div>
                                                    <div class="cbox-1 ico-15 ml-10">
                                                        <div class="ico-wrap color--theme">
                                                            <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                        </div>
                                                        <div class="cbox-1-txt">
                                                            <p class="s-14"> Plan Validity: {{ str_ireplace('+','',env('HIREAGENT_PLAN_VALIDITY')) }}</p>
                                                        </div>
                                                    </div>
                                                    <hr class="divider"/>
                                                    <span class="plan-cost s-22 pb-0">&#8377;{{ $hireAgent->inOffer ? $hireAgent->offeramount : $hireAgent->amount }}<span class="color--grey s-14">+ 18% GST additional</span></span>-->
                                                </span>
                                            </label>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-sm btn--theme hover--theme" id="submit-btn"></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
            // Set initial value based on the checked radio button
            updateOrderAmount();

            // Listen for the change event on the radio buttons with the class .plan-card
            $('input[name="plan"]').change(function() {
                updateOrderAmount();
            });

            // Function to update the order amount based on the selected radio button
            function updateOrderAmount() {
                // Get the value of the selected radio button
                var selectedPlan = $('input[name="plan"]:checked').val();

                // Determine the base price of the selected plan
                var baseAmount = 0;
                if (selectedPlan == "1") {
                    baseAmount = {{ $selfApply->inOffer ? $selfApply->offeramount : $selfApply->amount }}; // Set price for Super Saver
                } else if (selectedPlan == "2") {
                    baseAmount = {{ $hireAgent->inOffer ? $hireAgent->offeramount : $hireAgent->amount }}; // Set price for Standard
                }

                // Calculate the total amount including 18% GST
                var gst = 0.18;
                var totalAmount = baseAmount + (baseAmount * gst);

                // Use Math.floor to round down the total amount
                var finalAmount = totalAmount;
                $('#submit-btn').text('Purchase Plan: ₹'+Math.floor(baseAmount));
                // Update the hidden input field with the final amount
                $('#order_amount').val(finalAmount);
            }
        });

    </script>
@endpush
