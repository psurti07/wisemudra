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
        <div class="row gx-0 align-items-center justify-content-center otp-validation-form">
            <div class="col-md-6 m-auto">
                <div class="register-page-wrapper text-center p-4 rounded-3 border bg-white">
                    <img src="{{ asset('front/images/fintechpage/cancel.png') }}" alt="Payment Success Icon" height="80" class="mb-30">
                    <h3 class="w-700 text-danger">Payment Unsuccessful!</h3>
                    <p class="fsz-12 w-500">Complete the payment now to confirm your spot 👇</p>
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="{{ route('webinar.step1') }}" class="btn btn--theme hover--theme submit btn-login">
                                <span class="button_text_container text-white">Try Payment Again</span>
                            </a>
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