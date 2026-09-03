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
                    <img src="{{ asset('front/images/fintechpage/check.png') }}" alt="Payment Success Icon" height="100" class="mb-30">
                    <h2 class=" w-700 color-primary">Congratulations!</h2>
                    <p class="fsz-12 w-700">Your seat is confirmed! 🎉</p>
                    <p>All important updates, reminders and the webinar access link will be shared exclusively in our WhatsApp Community.</p>
                    <div class="row">
                        <div class="col-12 text-center">

                            <a href="https://chat.whatsapp.com/EkAV5N9L2Y9CxJfQorLNV1" class="btn btn--theme hover--theme submit btn-login">
                                <span class="button_text_container text-white">Join Community</span>
                            </a>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <p class="mb-0">
                                For any queries, please <a href=""><span class="color-primary w-700">raise a request .</span></a>
                            </p>
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