@extends('layouts.selfapply')
@push('css')
<link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('front/css/offer.css') }}" rel="stylesheet" type="text/css" />
<style>
    .invalid-feedback{
        display:block !important;
    }
</style>
@endpush
@section('content')
    <section id="hero-202" class="bg--fixed hero-section">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-6 col-lg-6 order-2 order-md-1 order-lg-1">
                    <div class="d-flex justify-content-center align-items-start">
                        <div class="img-block">
                            <img src="{{ asset('front/images/selfapply-nbfc-list.webp') }}" alt="selfapply nbfc list" class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 self-apply-form order-1 order-md-2 order-lg-2">
                    <div id="hero-8-form" class="r-06">
                        <h4 class="s-22 text-dark mb-1"> Get Loan up to <span class="color--green-500">&#8377;10 LAKHS</span> from Affiliate NBFCs!</h4>
                        <p class="s-14">Unlock Your Personalized Pre-Approved Loan Offers</p>

                        <form method="post" class="request-form save-form-1" action="{{ route('self.apply.get.offer1') }}">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <div class="form-check ps-0">
                                        <div class="row gy-2">
                                            <div class="col-md-6 col-lg-6 col-sm-6">
                                                <fieldset class="picker1">
                                                    <label class="card" for="plan-1">
                                                        <input type="radio" name="user_type" id="plan-1" value="1" class="radio" checked="">
                                                        <span class="plan-details">
                                                            <span class="plan-type color--purple-500">Salaried</span>
                                                        </span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-6">
                                                <fieldset class="picker1">
                                                    <label class="card" for="plan-2">
                                                        <input type="radio" name="user_type" id="plan-2" value="2" class="radio">
                                                        <span class="plan-details">
                                                            <span class="plan-type color--purple-500">Self Employed</span>
                                                        </span>
                                                    </label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name*" autocomplete="off"  value="{{ old('first_name') }}">
                                    </div>
                                    @component('components.ajax-error',['field'=>'first_name'])@endcomponent
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name*" autocomplete="off"  value="{{ old('last_name') }}">
                                    </div>
                                    @component('components.ajax-error',['field'=>'last_name'])@endcomponent
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+91</span>
                                        </div>
                                        <input type="text" name="mobile" id="mobile" class="numeric-input form-control name" placeholder="Mobile*" autocomplete="off"  maxlength="10" minlength="10" inputmode="numeric" value="{{ old('mobile') }}">
                                    </div>
                                    @component('components.ajax-error',['field'=>'mobile'])@endcomponent
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email*" autocomplete="off"  value="{{ old('email') }}">
                                    </div>
                                    @component('components.ajax-error',['field'=>'email'])@endcomponent
                                </div>
                                <input type="hidden" value="2" name="acc_type" id="acc_type">
                                <input type="hidden" value="1" name="user_type" id="user_type">

                                <input class="form-check-input" value="1" type="hidden" id="flexCheckDefault1" checked name="allow_sms"/>
                                <input class="form-check-input" value="1" type="hidden" id="flexCheckDefault" checked name="accept_tnc"/>
                                <div class="col-md-12 form-btn">
                                    <button type="submit" id="submit-btn" class="btn btn--theme hover--theme submit-btn">Apply Now</button>
                                </div>
                            </div>
                        </form>
                        <p class="p-sm mt-3 mb-0">By submitting the form & proceeding, you agree to the <a href="{{ route('front.terms.conditions') }}" target="_blank" class="text-dark text-decoration-none">Terms</a>  and <a href="{{ route('front.privacy.policy') }}" target="_blank" class="text-dark text-decoration-none"> Privacy Policy</a> of Wisemudra.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="integrations-2" class="py-80 integrations-section">
        <div class="container">
            <div class="r-12 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="section-title mb-50">
                            <h2 class="s-34 w-700">Going Strong With Stronger Recommendations!</h2>
                            <p class="s-16 color--grey">Our guidance will drive you towards the best NBFC personalized offers.</p>
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

    <hr class="divider" />

    <!-- Testimonials section start  -->
    <section id="reviews-1" class="py-80 features-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="section-title text-center mb-50">
                        <h2 class="s-34 w-700">Our Happy Customer</h2>
                        <p>We Give Many Reasons For Our Customers To Shower Praises On Us!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <!-- Testimonials carousel start  -->
                    @include('partials.front.testimonials')
                    <!-- Testimonials carousel end  -->
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonials section ends  -->

    <section class="py-2 shape--06 gr--smoke reviews-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="form-holder">
                        <div class="contact-form-notice">
                            <p class="s-14">
                                <strong>APR Calculation:</strong> Range of Loan tenure is up to 72 months with Annual Interest Rates ranging between 11% - 36% and the processing fee up to 2%. For Example: Taking in consideration a personal loan of Rs.1,00,000 availed at 11%* interest rate for a tenure of 6* years with 2%* processing fee, the APR will be 11.75%*. *T&C Apply. All these numbers are tentative/indicative, the final loan specifics may vary depending upon the customer profile and NBFCs' criteria, rules & regulations, and terms &amp; conditions.
                            </p>
                            
                            <p class="s-14">
                                <strong>Important Note:</strong> BE AWARE! We ask our customers to make payments ONLY on our website https://wisemudra.com and NOT through any other source, directly or indirectly. Thanks!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
<!-- write or link your script file and script tag here -->
<script>
    $(document).ready(function() {
        $('.save-form-1').submit(function (event) {
            var status = document.activeElement.innerHTML;
            event.preventDefault();
            if (status) {
                $('.ajax-error').html('');
                var data = new FormData(this);
                $.ajax({
                    url: $(this).attr("action"),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $('#submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Apply Now');
                        $('#submit-btn').attr('disabled', true);
                    },
                    success: function (result) {
                        $(this).attr("disabled", false);
                        if (result.type === 'SUCCESS') {
                            toastr.success(result.message);
                            setTimeout(function() {
                                window.location.href = result.url;
                            }, 5000);
                        } else {
                            toastr.error(result.message);
                            $('#submit-btn').html('Apply Now');
                            $('#submit-btn').attr('disabled', false);
                        }
                    },
                    error: function (error) {
                        $(this).attr("disabled", false);
                        let errors = error.responseJSON.errors, errorsHtml = '';
                        $.each(errors, function (key, value) {
                            errorsHtml = '<strong>' + value[0] + '</strong>';
                            $('.' + key).html(errorsHtml);
                        });
                        $('#submit-btn').html('Apply Now');
                        $('#submit-btn').attr('disabled', false);
                    }
                });
            }
        });

        $(".banks-carousel").owlCarousel({
            loop: true,
            autoplay: true,
            nav: false,
            dots: false,
            autoplayTimeout: 4500,
            autoplayHoverPause: true,
            smartSpeed: 1500,
            margin: 10,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });

        $(".testimonials-carousel").owlCarousel({
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
    })
</script>
@endpush
