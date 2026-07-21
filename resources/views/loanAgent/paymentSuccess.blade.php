@extends('layouts.selfapply')
@push('css')
@endpush

@section('content')
    <section id="contacts" class="bg--white-100 personal-details-form pb-100 inner-page-hero contacts-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="row mb-35">
                    <div class="col-lg-qw col-md-qw col-sm-12">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-7 col-lg-7 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center mb-20">
                                            <h4 class="fw-bolder text-success mb-15">Payment Successful!</h4>
                                            <p>Your payment has been successfully processed.</p>
                                            <p>You can now access your pre-approved offers.</p>
                                        </div>

                                        <hr class="divider my-3"/>

                                        <div class="text-center mb-20">
                                            <div class="row gy-2 gx-2">
                                                <div class="col-lg-4 col-md-4 col-12">
                                                <div class="border rounded-3 p-2 bg--green-200">
                                                    <p class="s-14 fw-bold mb-2">Customer Portal</p>
                                                    <p class="s-14">Your service is active. Log in to the portal.</p>
                                                </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-12">
                                                <div class="border rounded-3 p-2 bg--green-200">
                                                    <p class="s-14 fw-bold mb-2">Invoice</p>
                                                    <p class="s-14">Invoice is available for download in portal.</p>
                                                </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-12">
                                                <div class="border rounded-3 p-2 bg--green-200">
                                                    <p class="s-14 fw-bold mb-2">Consultant</p>
                                                    <p class="s-14">Our team will contact you within 24 hrs.</p>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="divider my-3"/>

                                        <div class="text-center">
                                            <a href="{{ route('customer.authenticate2') }}" class="btn btn-xs r-04 btn--theme hover--tra-black">Access Pre-Approved Offers!</a>
                                        
                                            <p class="text-center s-12 mt-20">If you've any queries/ issues, kindly raise a request here: <a href="{{ route('front.raise.request') }}" class="text-success">Click Here</a></p>
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
