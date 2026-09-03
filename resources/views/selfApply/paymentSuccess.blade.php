@extends('layouts.selfapply')
@push('css')
<link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')

<section class="bg--blue-400 bg--fixed pb-80 personal-details-form d-flex align-items-center success-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-7 col-12 m-auto">
                <div class="bg--blue-500 shadow r-24">
                    <div class="card-body p-4">
                        <div class="text-center mb-20">
                            <div class="mb-20">
                                <i class="far fa-check-circle display-1 color--green-300"></i>
                            </div>
                            <h3 class="fw-bolder color--white mb-0">Congratulations!,</h3>
                            <h3 class="fw-bolder color--green-300 mb-15">Payment Successful!</h3>
                            <p class="mb-0 color--grey">Your payment has been successfully processed.</p>
                            <p class="mb-0 color--grey mt-1">You can now access your pre-approved offers.</p>
                        </div>
                        <div class="text-center my-4">
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="border-0 rounded-4 px-3 py-2 bg--blue-200 h-100">
                                        <p class="fw-bold mb-0 color--white">Customer Portal</p>
                                        <p class="mt-0 color--grey">Your service is active. Log in to the portal.</p>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="border-0 rounded-4 px-3 py-2 bg--blue-200 h-100">
                                        <p class="fw-bold mb-0 color--white">Invoice</p>
                                        <p class="mt-0 color--grey">Invoice is available for download in portal.</p>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="border-0 rounded-4 px-3 py-2 bg--blue-200 h-100">
                                        <p class="fw-bold mb-0 color--white">Consultant</p>
                                        <p class="mt-0 color--grey">Our team will contact you within 24 hrs.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('customer.authenticate2') }}"
                                class="btn btn-sm btn--theme hover--theme r-100">Access Pre-Approved
                                Offers!</a>

                            <div class="mt-3"> <a href="{{ route('front.raise.request') }}" class="color--white">Start
                                    a new application <span class="fbox-ico ico-10"> <span
                                            class="flaticon-right-arrow  ico-20 ms-1"></span></span></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection