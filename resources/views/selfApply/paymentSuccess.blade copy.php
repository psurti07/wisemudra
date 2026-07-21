@extends('layouts.selfapply')
@push('css')
    <style>
        .accordion-button{ background-color: transparent!important; }
        .accordion-button:focus { box-shadow:none!important; }
        .txt-block h2 { margin-bottom:0px!important; }
        span[class^="flaticon-right-arrow"]:before{ font-size:10px!important; }
        #counts{ font-size:20px;color:green;font-weight:bold; }
    </style>
@endpush

@section('content')
    <section id="contacts" class="gr--white personal-details-form pb-100 inner-page-hero contacts-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="row mb-35">
                    <div class="col-lg-8 col-md-10 col-sm-12 offset-md-1">
                        <div class="row">
                            <div class="col-md-4 col-lg-4">
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
                                    <div class="cbox-12 process-step">
                                        <div class="ico-wrap">
                                            <div class="cbox-12-ico text-white bg--green-500">4</div>
                                        </div>
                                        <div class="cbox-12-txt">
                                            <p class="s-11">Purchase Plan</p>
                                        </div>
                                    </div>
                                    <div class="cbox-12 process-step border-right">
                                        <div class="ico-wrap">
                                            <div class="cbox-12-ico text-white bg--green-500">5</div>
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
                                                        <div class="col-lg-6 s-14 accordion-row-coll">{{ Cookie::get('fullname') ?? '#' }}</div>
                                                        <hr class="custm-HR"/>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 s-13 accordion-row-coll">Mobile</div>
                                                        <div class="col-lg-6 s-14 accordion-row-coll">{{ Cookie::get('user_mobile') ?? '#' }}</div>
                                                        <hr class="custm-HR"/>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 s-13 accordion-row-coll">Loan Amount</div>
                                                        <div class="col-lg-6 s-14 accordion-row-coll">&#8377;{{ formatePriceIndia(Cookie::get('loan_amount')) ?? '#' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-8 col-12">
                                <div class="card bg--06">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex text-start">
                                                    <img src="{{ asset('front/paymentsuccess.gif') }}" width="80">
                                                    <h2 class="s-24 mt-2 d-flex align-items-center">Great! You`ve Done Well</h2>
                                                </div>
                                                <p id="timer" class="s-14 color--grey"></p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-start mb-2">
                                                <h5 class="fw-bolder s-14 text-success">Payment Successful!</h5>
                                                <p class="s-14 color--grey text-success">Great! You’ve taken your smart step towards expert loan consultation.</p>
                                                <p id="timer" class="s-14 color--grey text-success text-center"></p>
                                                <p class="s-14 color--grey text-success">Kindly check your E-mail & WhatsApp for your invoice and personalised portal credentials.</p>
                                                @if($orderData)
                                                <a href="{{ route('customer.authenticate2') }}" class="btn btn-xs btn--green-500 hover--green-500">Access Pre-Approved Offers!</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <h5 class="text-center fw-bolder s-16">Order Details</h5>
                                            <hr class="divider my-2"/>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="s-13 color--grey">Fullname :</span>
                                                    <p class="s-14">{{ Cookie::get('fullname') ?? '#' }}</p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <span class="s-13 color--grey">Mobile :</span>
                                                    <p class="s-14">{{ Cookie::get('user_mobile') ?? '#' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="s-13 color--grey">Order Id :</span>
                                                    <p class="s-14">{{ $orderData->orderid ?? '#' }}</p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <span class="s-13 color--grey">Payment Amount :</span>
                                                    <p class="s-14">&#8377; {{ $orderData->orderamount ?? '#' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="s-13 color--grey">Transaction Id :</span>
                                                    <p class="s-14">{{ $orderData->transactionid ?? '#' }}</p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <span class="s-13 color--grey">Transaction Date/Time :</span>
                                                    <p class="s-14">{{ date('d m,Y h:m:i A',strtotime($orderData->rec_date ?? '')) }}</p>
                                                </div>
                                            </div>
                                            <hr class="divider my-3"/>
                                            <span class="text-center s-12">If you've any queries/ issues, kindly raise a request here: <a href="{{ route('front.raise.request') }}" class="text-success fw-bold">Click Here &nbsp;&nbsp;<span class="flaticon-right-arrow"></span></a></span>
                                        </div>
                                    </div>
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
        // var count = 30; // Timer
        // var redirect = `{{ route('customer.authenticate2') }}`; // Target URL
        // $(document).ready(function(){
        //     countDown();
        // })
        // function countDown() {
        //     var timer = document.getElementById("timer"); // Timer ID
        //     if (count > 0) {
        //         count--;
        //         timer.innerHTML = `We're Generating Your Personalised Loan Offers! Please wait for <span id='counts'> ${count} </span> seconds!.`; // Timer Message
        //         setTimeout("countDown()", 1000);
        //     } else {
        //         window.location.href = redirect;
        //     }
        // }
    </script>
@endpush
