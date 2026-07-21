@extends('layouts.selfapply')
@push('css')
    <link rel="stylesheet" href="{{ asset('front/css/radiocards.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
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
        .card:hover .radio:checked{
            border-color:transparent!important;
        }
    </style>
@endpush

@section('content')
    <section id="contacts" class="bg--white-100 personal-details-form pb-100 inner-page-hero contacts-section division">
        <div class="container">
            <div class="row justify-content-center mb-35">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-12">
                            <div class="txt-block left-column gr--white border border-radius-10 p-2">
                                <div class="accordion accordion-flush mb-10" id="accordionFlushExample">
                                    <div class="accordion-item bg-transparent">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                User Details
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body p-2">
                                                <p class="s-12 text-grey mb-0">Fullname :</p>
                                                <p class="s-14 text-black mt-0">{{ Cookie::get('fullname') }}</p>

                                                <p class="s-12 text-grey mb-0">Mobile :</p>
                                                <p class="s-14 text-black mt-0">{{ Cookie::get('user_mobile') }}</p>

                                                <p class="s-12 text-grey mb-0">Loan Amount :</p>
                                                <p class="s-14 text-black mt-0 mb-0">&#8377;{{ formatePriceIndia(Cookie::get('loan_amount')) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="divider" />

                                <div class="p-2">
                                    <p class="s-12 mt-10 mb-10">Application Process </p>

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
                                    <div class="cbox-12 process-step">
                                        <div class="ico-wrap">
                                            <div class="cbox-12-ico border-dark-subtle">5</div>
                                        </div>
                                        <div class="cbox-12-txt">
                                            <p class="s-11">Personalized Offers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-9 col-lg-9 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="fw-bolder s-16">Premium Subscription Offer</h5>
                                    <p class="mb-30 color--grey">Your pre-approved loan is waiting. Purchase a subscription to proceed. <span class="text-danger">- Offer Valid till 12 am only!</span></p>

                                    <form method="post" class="buyNowForm" action="{{ route('loan.agent.checkout') }}">
                                        @csrf
                                        <input type="hidden" class="form-control" name="order_amount" id="order_amount" value="">
                                        <div class="row gx-3 gy-3">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <label class="card border-0">
                                                    <input name="plan" value="2" class="radio d-none" type="radio" checked data-plan="Loan-Agent">
                                                    <div class="plan-details">
                                                        <h5 class="fw-bolder s-16 plan-type mb-10">Hire Loan Agent Plan</h5>
                                                       
                                                        <div class="corner-ribbon" data-offer="{{ calPercentage($hireAgent->amount, $hireAgent->offeramount) }} OFF"></div>

                                                        <div class="price my-2">
                                                            <!-- Monthly Price -->	
                                                            <div class="price2" >
                                                                <sup class="color--black">₹</sup>	
                                                                <sup class="coins color--red-300"><strike>{{intval($hireAgent->amount)}}</strike></sup>							
                                                                <span class="color--black">{{intval($hireAgent->offeramount)}}</span>
                                                            </div>
                                                        </div>

                                                        <div class="order-summary">
                                                            <div class="order-row order-header">
                                                                <span>Items</span>
                                                                <span>Price</span>
                                                            </div>

                                                            <div class="order-row">
                                                                <span>Price</span>
                                                                <span>{{formatePriceIndia($hireAgent->amount)}}</span>
                                                            </div>

                                                            <div class="order-row order-discount">
                                                                <span>Discount</span>
                                                                <span>- {{formatePriceIndia($hireAgent->amount - $hireAgent->offeramount)}}</span>
                                                            </div>

                                                            <div class="order-row">
                                                                <span>Offer Amount</span>
                                                                <span>{{formatePriceIndia($hireAgent->offeramount)}}</span>
                                                            </div>

                                                            <div class="order-row">
                                                                <span>GST</span>
                                                                <span>+ {{formatePriceIndia($hireAgent->offeramount * 0.18)}}</span>
                                                            </div>

                                                            <div class="order-divider"></div>

                                                            <div class="order-row order-total">
                                                                <span>Total</span>
                                                                <span>₹ {{formatePriceIndia($hireAgent->offeramount + ($hireAgent->offeramount * 0.18))}}</span>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-sm btn--theme hover--theme" id="submit-btn"></button>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="fw-bold mt-0">Subscription Benefits: : </p>

                                                        <div class="cbox-1 ico-15 ml-10">
                                                            <div class="ico-wrap color--grey">
                                                                <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                            </div>
                                                            <div class="cbox-1-txt">
                                                                <p class="s-14 mt-0"> Loan Process in Multiple NBFCs</p>
                                                            </div>
                                                        </div>
                                                        <div class="cbox-1 ico-15 ml-10">
                                                            <div class="ico-wrap color--grey">
                                                                <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                            </div>
                                                            <div class="cbox-1-txt">
                                                                <p class="s-14 mt-0"> 100% Online Financial Consultation</p>
                                                            </div>
                                                        </div>
                                                        <div class="cbox-1 ico-15 ml-10">
                                                            <div class="ico-wrap color--grey">
                                                                <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                            </div>
                                                            <div class="cbox-1-txt">
                                                                <p class="s-14 mt-0"> Access Personalized Tracking Portal</p>
                                                            </div>
                                                        </div>
                                                        <div class="cbox-1 ico-15 ml-10">
                                                            <div class="ico-wrap color--grey">
                                                                <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                            </div>
                                                            <div class="cbox-1-txt">
                                                                <p class="s-14 mt-0"> Dedicated Loan Expert Assigned</p>
                                                            </div>
                                                        </div>
                                                        <div class="cbox-1 ico-15 ml-10">
                                                            <div class="ico-wrap color--grey">
                                                                <div class="cbox-1-ico"><span class="flaticon-check"></span></div>
                                                            </div>
                                                            <div class="cbox-1-txt">
                                                                <p class="s-14 mt-0"> Loan Processing Time: 48 Hours</p>
                                                            </div>
                                                        </div>

                                                        <div class="pt-3">
                                                            <div id="rb-1-2" class="rbox-1">
                                                                <!-- Brand Logo -->
                                                                <div class="rbox-1-img">
                                                                    <img class="img-fluid" src="{{ asset('front/images/google.webp') }}" alt="feature-image">
                                                                </div>

                                                                <!-- Rating Stars -->
                                                                <div class="star-rating ico-10 bg--white-100 r-100 clearfix">
                                                                    <span class="flaticon-star"></span>
                                                                    <span class="flaticon-star"></span>
                                                                    <span class="flaticon-star"></span>
                                                                    <span class="flaticon-star"></span>
                                                                    <span class="flaticon-star mr-5"></span>	
                                                                    &nbsp; 4.95/5
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        var owl = $('.buyNow-carousel');
		owl.owlCarousel({
			items: 5,
			loop:true,
			autoplay:true,
			navBy: 1,
			nav:false,
			autoplayTimeout: 4000,
			autoplayHoverPause: false,
			smartSpeed: 2000,
			responsive:{
				0:{
					items:4
				},
				550:{
					items:4
				},
				767:{
					items:5
				},
				768:{
					items:5
				},
				991:{
					items:5
				},				
				1000:{
					items:5
				}
			}
	    });
    </script>
@endpush
