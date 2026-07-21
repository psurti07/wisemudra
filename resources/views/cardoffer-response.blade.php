@extends('layouts.selfapply')
@push('css')
    {{-- write or link your css file and styles tag here --}}
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .bank-crousel{
            display:block!important;
        }
        .owl-carousel .owl-item img{
            width: 80%!important;
        }
        .testimonials-carousel .owl-item img{
            width: 100%!important;
        }
    </style>
@endpush
@section('content')
    <section id="contacts" class="gr--white personal-details-form pb-100 inner-page-hero contacts-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="row mb-35">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-12">
                                <div class="card bg--06">
                                    <div class="card-body">
                                        <div class="row">
                                            <h5 class="fw-bolder s-16 text-{{$response ? 'success' : 'danger'}}"><span class="flaticon-idea"></span>&nbsp;&nbsp;Payment {{$response ? 'Successful' : 'Failed'}}</h5>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12 text-start">
                                                <span class="s-14 color--grey text-{{$response ? 'success' : 'danger'}}">{{ $response ? 'Great! You’ve taken your smart step towards expert loan consultation.' : 'We regret to inform you that your payment for Subscription Plan was not successful.' }}</span>
                                            </div>
                                            <div class="col-md-12 text-start mt-2">
                                                <span class="s-14 color--grey text-{{$response ? 'success' : 'danger'}}">{{ $response ? 'Kindly check your E-mail & WhatsApp for further process.' : 'We request you to try another payment method.' }}</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">

                                            <span id="timer" class="s-14 color--grey text-{{$response ? 'success' : 'danger'}} text-center"></span>
                                            <hr class="divider my-3"/>
                                            <span class="text-center s-12">If you've any queries/ issues, kindly raise a request here: <a href="{{ route('front.raise.request') }}" class="text-success fw-bold">Click Here</a></span>
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
    <!-- Affiliate NBFCs section starts -->
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
    <!-- Affiliate NBFCs section ends -->
  
@endsection
@push('scripts')
    <!-- write or link your script file and script tag here -->
    <script>
        $(document).ready(function(){
            $(".banks-carousel").owlCarousel({
                items: 2, // Number of items
                loop: true,
                autoplay: true,
                navBy: 1,
                dots:false,
                autoplayTimeout: 4500,
                autoplayHoverPause: true,
                smartSpeed: 1500,
                responsive: {
                    0:{
                        items:1
                    },
                    767:{
                        items:1
                    },
                    768:{
                        items:2
                    },
                    991:{
                        items:3
                    },
                    1000:{
                        items:3
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
        });
    </script>
@endpush
