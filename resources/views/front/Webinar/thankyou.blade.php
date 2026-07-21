@extends('layouts.workshop')
@push('css')
<style>
    .otp-validation-form {
        height: 95vh;
    }
</style>

@endpush
@section('content')

<section id="" class="bg--scroll division gr--perl">
    <div class="container">
        <div class="content">
            <div class="row align-items-center justify-content-center vh-75">
                <div class="col-lg-10 col-md-10 col-12">
                    <div class="section-card mb-3 text-center">
                        <img src="{{ asset('front/img/logo/mailbox.png') }}" alt="Thank You Icon" class="mb-30">
                        <h3 class="mb-20">Thank You!</h3>
                        <p>We're glad to receive your contact request! Our Company Executive will reach out to you within 48 working hours. Please check your registered email id for further informations.</p>
                        <div class="col-lg-12">
                            <div class="button_su radius-2 border mt-10">
                                <span class="su_button_circle bg-darkBlue1 desplode-circle"></span>
                                <a href="{{ route('fintech.payment.success') }}" class="butn py-2 button_su_inner m-0 radius-2 bg-000 custom-button-color">
                                    <span class="button_text_container text-white">Payment Success</span>
                                </a>
                            </div>
                            <div class="button_su radius-2 border mt-10">
                                <span class="su_button_circle bg-darkBlue1 desplode-circle"></span>
                                <a href="{{ route('fintech.payment.failed') }}" class="butn py-2 button_su_inner m-0 radius-2 bg-000 custom-button-color">
                                    <span class="button_text_container text-white">Payment Failed</span>
                                </a>
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

@endpush