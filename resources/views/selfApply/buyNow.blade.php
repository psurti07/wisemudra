@extends('layouts.selfapply')
@push('css')
<link rel="stylesheet" href="{{ asset('front/css/radiocards.css') }}">
<link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
<style>
.accordion-button {
    background-color: transparent !important;
}

.accordion-button:focus {
    box-shadow: none !important;
}

.txt-block h2 {
    margin-bottom: 0px !important;
}

.cbox-1.ico-15 span {
    top: 5px !important;
}

a#failed-btn {
    background: #dc3545;
    border: 1px solid #dc3545;
}

a#failed-btn:hover {
    background: #bb2d3b !important;
    color: #fff !important;
}

.card:hover .radio:checked {
    border-color: transparent !important;
}
</style>
@endpush

@section('content')
<section id="contacts"
    class="bg--blue-400 personal-details-form pb-60 inner-page-hero contacts-section division min-vh-100 ">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4 col-12 order-md-1 order-2">
                <div class="txt-block left-column r-24 p-4 bg--blue-500">
                    <div class="accordion accordion-flush mb-10" id="accordionFlushExample">
                        <div class="accordion-item bg-transparent">
                            <h2 class="accordion-header mb-0" id="flush-headingOne">
                                <button class="accordion-button color--grey text-uppercase" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true"
                                    aria-controls="flush-collapseOne">
                                    User Details
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body bg--blue-200 r-18 p-0 mt-2">
                                    <div class="d-flex justify-content-between px-3 py-2 details-main">
                                        <p class="s-12 color--grey mb-0">Fullname :</p>
                                        <p class="s-14 color--white mt-0">{{ Cookie::get('fullname') }}</p>
                                    </div>
                                    <div class="d-flex justify-content-between px-3 py-2 details-main">
                                        <p class="s-12 color--grey mb-0">Mobile :</p>
                                        <p class="s-14 color--white mt-0">{{ Cookie::get('user_mobile') }}</p>
                                    </div>
                                    <div class="d-flex justify-content-between px-3 py-2">
                                        <p class="s-12 color--grey mb-0">Loan Amount :</p>
                                        <p class="s-14 color--white mt-0">
                                            &#8377;{{ formatePriceIndia(Cookie::get('loan_amount')) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 pb-0">
                        <p class="s-12 mt-10 mb-10 color--grey text-uppercase">Application Process </p>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico text-white bg--green-300 border border-green">
                                    <span class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-check ms-0 color--white lh-1"></span></span>
                                </div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--white mb-0">Loan Details</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico text-white bg--green-300 border border-green">
                                    <span class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-check ms-0 color--white lh-1"></span></span>
                                </div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--white mb-0">Personal Details</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico text-white bg--green-300 border border-green">
                                    <span class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-check ms-0 color--white lh-1"></span></span>
                                </div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--white mb-0">Unlock Offers</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico border-success"><span class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-right-arrow ms-0 color--green-300 lh-1"></span></span>
                                </div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--grey mb-0">Purchase Plan</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico border-dark-subtle bg--blue-200"><span
                                        class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-right-arrow ms-0 color--white lh-1"></span></span>
                                </div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--grey mb-0">Personalized Offers</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="img-block mt-20">
                    <img src="{{ asset('front/images/details-img.png') }}" alt="login now" class="p-0 w-100">
                </div>
            </div>
            <div class="col-md-7 col-lg-8 col-12 order-md-2 order-1 mb-md-0 mb-0">
                <div class="txt-block left-column r-24 p-4 bg--blue-500">
                    <div class="card-body">
                        <h4 class="fw-bolder mb-10 color--white">Premium Subscription Offer</h4>
                        <p class="mb-20 color--grey mt-0">Your pre-approved loan is waiting. Purchase a subscription to
                            proceed. <span class="text-danger">- Offer Valid till 12 am only!</span></p>
                        <form method="post" class="buyNowForm" action="{{ route('self.apply.checkout') }}">
                            @csrf
                            <input type="hidden" class="form-control" name="order_amount" id="order_amount" value="">
                            <div class="row gx-3 gy-3">
                                <div class="col-lg-6 col-md-12 col-12">
                                    <label class="r-24 overflow-hidden  w-100 subscription-card">
                                        <input name="plan" value="1" class="radio d-none" type="radio" checked
                                            data-plan="Self-Apply">
                                        <div class="plan-details p-0 r-24 overflow-auto">
                                            <!-- <div class="corner-ribbon"
                                                data-offer="{{ calPercentage($selfApply->amount, $selfApply->offeramount) }} OFF">
                                            </div> -->
                                            <p
                                                class="mb-0 text-center fs-12 fw-bold btn--yellow-500 px-2 py-0 mt-0 text-white">
                                                77%
                                                OFF
                                            </p>
                                            <div class="p-4 bg--blue-200">
                                                <h5 class="fw-bolder s-16 mb-10 color--white">Self-Apply Plan</h5>
                                                <div class="price my-2">
                                                    <!-- Monthly Price -->
                                                    <div class="price2">
                                                        <sup class="color--red-300">₹</sup>
                                                        <sup
                                                            class="coins color--red-300"><strike>{{intval($selfApply->amount)}}</strike></sup>
                                                        <span
                                                            class="color--white">{{intval($selfApply->offeramount)}}</span>
                                                    </div>
                                                </div>
                                                <div class="order-summary pt-0 pb-0">
                                                    <div class="order-row order-header">
                                                        <span class="color--grey">Items</span>
                                                        <span class="color--grey">Price</span>
                                                    </div>

                                                    <div class="order-row">
                                                        <span class="color--grey">Price</span>
                                                        <span
                                                            class="color--grey">{{formatePriceIndia($selfApply->amount)}}</span>
                                                    </div>

                                                    <div class="order-row order-discount">
                                                        <span class="color--grey">Discount</span>
                                                        <span class="color--grey">-
                                                            {{formatePriceIndia($selfApply->amount - $selfApply->offeramount)}}</span>
                                                    </div>

                                                    <div class="order-row">
                                                        <span class="color--grey">Offer Amount</span>
                                                        <span
                                                            class="color--grey">{{formatePriceIndia($selfApply->offeramount)}}</span>
                                                    </div>

                                                    <div class="order-row pb-3">
                                                        <span class="color--grey">GST</span>
                                                        <span class="color--grey">+
                                                            {{formatePriceIndia($selfApply->offeramount * 0.18)}}</span>
                                                    </div>

                                                    <div class="details-main"></div>

                                                    <div class="order-row order-total pt-3">
                                                        <h5 class="color--white">Total</h5>
                                                        <h5 class="color--white">₹
                                                            {{formatePriceIndia($selfApply->offeramount + ($selfApply->offeramount * 0.18))}}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-sm btn--theme hover--theme w-100 rounded-pill mt-0"
                                                    id="submit-btn">Buy now <span class="fbox-ico ico-10"> <span
                                                            class="flaticon-right-arrow  ico-20 ms-1"></span></span></button>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 col-12">
                                    <div class="txt-block left-column r-24 p-4 bg--blue-200 subscription-card">
                                        <div class="card-body">
                                            <h6 class="mb-3 color--white">Subscription Benefits</h6>
                                            <div class="cbox-1 ico-9 ml-0">
                                                <div class="ico-wrap ms-0">
                                                    <div class="cbox-1-ico bg--green-300 rounded-pill">
                                                        <span class="flaticon-check end-0 text-white"></span>
                                                    </div>
                                                </div>
                                                <div class="cbox-1-txt">
                                                    <p class="s-14 mt-0 color--grey ms-2"> Loan Process in Multiple
                                                        NBFCs</p>
                                                </div>
                                            </div>
                                            <div class="cbox-1 ico-9 ml-0">
                                                <div class="ico-wrap ms-0">
                                                    <div class="cbox-1-ico bg--green-300 rounded-pill">
                                                        <span class="flaticon-check end-0 text-white"></span>
                                                    </div>
                                                </div>
                                                <div class="cbox-1-txt">
                                                    <p class="s-14 mt-0 color--grey ms-2"> 100% Online Financial
                                                        Consultation</p>
                                                </div>
                                            </div>
                                            <div class="cbox-1 ico-9 ml-0">
                                                <div class="ico-wrap ms-0">
                                                    <div class="cbox-1-ico bg--green-300 rounded-pill">
                                                        <span class="flaticon-check end-0 text-white"></span>
                                                    </div>
                                                </div>
                                                <div class="cbox-1-txt">
                                                    <p class="s-14 mt-0 color--grey ms-2"> Access Personalized Tracking
                                                        Portal
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="cbox-1 ico-9 ml-0">
                                                <div class="ico-wrap ms-0">
                                                    <div class="cbox-1-ico bg--green-300 rounded-pill">
                                                        <span class="flaticon-check end-0 text-white"></span>
                                                    </div>
                                                </div>
                                                <div class="cbox-1-txt">
                                                    <p class="s-14 mt-0 color--grey ms-2"> Dedicated Loan Expert
                                                        Assigned</p>
                                                </div>
                                            </div>
                                            <div class="cbox-1 ico-9 ml-0">
                                                <div class="ico-wrap ms-0">
                                                    <div class="cbox-1-ico bg--green-300 rounded-pill">
                                                        <span class="flaticon-check end-0 text-white"></span>
                                                    </div>
                                                </div>
                                                <div class="cbox-1-txt">
                                                    <p class="s-14 mt-0 color--grey ms-2"> Loan Processing Time: 48
                                                        Hours</p>
                                                </div>
                                            </div>
                                            <div class="pt-3">
                                                <div id="rb-1-2" class="rbox-1">
                                                    <div class="rbox-1-img mb-0">
                                                        <img class="w-100 p-0"
                                                            src="{{ asset('front/images/google.webp') }}"
                                                            alt="feature-image">
                                                    </div>
                                                    <div class="star-rating ico-10 clearfix color--grey">
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
            baseAmount = {
                {
                    $selfApply - > inOffer ? $selfApply - > offeramount : $selfApply - > amount
                }
            }; // Set price for Super Saver
        } else if (selectedPlan == "2") {
            baseAmount = {
                {
                    $hireAgent - > inOffer ? $hireAgent - > offeramount : $hireAgent - > amount
                }
            }; // Set price for Standard
        }

        // Calculate the total amount including 18% GST
        var gst = 0.18;
        var totalAmount = baseAmount + (baseAmount * gst);

        // Use Math.floor to round down the total amount
        var finalAmount = totalAmount;
        $('#submit-btn').text('Buy Now');
        // Update the hidden input field with the final amount
        $('#order_amount').val(finalAmount);
    }

    var owl = $('.buyNow-carousel');
    owl.owlCarousel({
        items: 5,
        loop: true,
        autoplay: false,
        //navBy: 1,
        nav: false,
        autoplayTimeout: 4000,
        autoplayHoverPause: false,
        smartSpeed: 2000,
        responsive: {
            0: {
                items: 4
            },
            550: {
                items: 4
            },
            767: {
                items: 5
            },
            768: {
                items: 5
            },
            991: {
                items: 5
            },
            1000: {
                items: 5
            }
        }
    });
});
</script>
@endpush