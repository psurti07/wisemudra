@extends('layouts.workshop')
@push('css')
<style>
    .otp-validation-form {
        height: 95vh;
    }
</style>

@endpush
@section('content')

<section id="" class="bg--scroll division bg--green-100">
    <div class="container">
        <div class="row gx-0 align-items-center justify-content-center otp-validation-form">
            <div class="col-md-5 m-auto">
                <div class="register-page-wrapper p-4 rounded-3 border bg-white">
                    <h2 class="s-22 w-700">OTP Verification</h2>
                    <p class="fsz-12">Please enter the OTP sent to your registered mobile number.</p>
                    <form action="{{ route('webinar.verifyOtpStep') }}" method="POST" class="signup-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group pt-3">
                                    <div class="d-flex justify-content-center gap-2 mb-3">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <input type="tel" name="otp[]" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="form-control text-center otp-input p-0" style="width:45px; height:45px; font-size:20px;">
                                            @endfor
                                    </div>
                                    <span class="text-center">
                                        @component('components.ajax-error', ['field' => 'otp[]']) @endcomponent
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" id="otpbtn" class="btn btn--theme hover--theme submit btn-login">
                                    <span class="spinner-border spinner-border-sm me-2 d-none color-yellow2" role="status" aria-hidden="true" id="otpLoader"></span>
                                    Verify & Proceed
                                </button>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <p class="mb-0">
                                    Didn't receive the OTP?
                                    <button type="button" class="btn btn-link p-0 text-decoration-underline color-primary" id="resendOtpBtn" onclick="resendOtp()">
                                        Resend OTP
                                    </button>
                                    <span id="otpTimer" class="text-muted ms-1" style="font-size: 12px;"></span>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // OTP input auto-move
        $('.otp-input').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1) {
                $(this).next('.otp-input').focus();
            }
        });
        $('.otp-input').on('keydown', function(e) {
            if (e.key === "Backspace" && this.value === '') {
                $(this).prev('.otp-input').focus();
            }
        });
        // OTP form submit
        $('.signup-form').submit(function(event) {
            event.preventDefault();
            let form = this;
            let $btn = $('#otpbtn');
            let data = new FormData(form);
            $.ajax({
                url: $(form).attr("action"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $btn.attr('disabled', true);
                    $('#otpLoader').removeClass('d-none');
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);
                },
                error: function(error) {
                    $btn.attr('disabled', false);
                    $('#otpLoader').addClass('d-none');
                    // Clear previous ajax errors
                    $('.ajax-error').html('');
                    let errors = error.responseJSON.errors;
                    if (errors) {
                        let otpErrors = [];
                        $.each(errors, function(key, value) {
                            // If the key is otp.*, collect the error
                            if (key.startsWith("otp.")) {
                                otpErrors.push(value[0]);
                            } else {
                                const escapedKey = key.replace(/\[/g, '\\[').replace(/\]/g, '\\]');
                                $('.ajax-error.' + escapedKey).html('<strong>' + value[0] + '</strong>');
                            }
                        });
                        // Show first OTP error under otp[]
                        if (otpErrors.length) {
                            $('.ajax-error.otp\\[\\]').html('<strong>' + otpErrors[0] + '</strong>');
                        }
                    } else if (error.responseJSON && error.responseJSON.message) {
                        toastr.error(error.responseJSON.message);
                    }
                    setTimeout(() => {
                        $('.otp-input').val('');
                        $('.otp-input').first().focus();
                    }, 2000);
                }
            });
        });
    });
    // Resend OTP logic
    let otpCountdownInterval;

    function resendOtp() {
        $.ajax({
            url: '{{ route("webinar.resendOtp") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#resendOtpBtn').attr('disabled', true).text('Sending...');
                $('#otpTimer').text('');
                clearInterval(otpCountdownInterval); // clear any previous countdown
            },
            success: function(response) {
                toastr.success(response.message);
                $('#resendOtpBtn').text('Resend OTP');
                let countdown = 30;
                $('#otpTimer').text(`(${countdown}s)`);
                otpCountdownInterval = setInterval(() => {
                    countdown--;
                    $('#otpTimer').text(`(${countdown}s)`);
                    if (countdown <= 0) {
                        clearInterval(otpCountdownInterval);
                        $('#otpTimer').text('');
                        $('#resendOtpBtn').attr('disabled', false);
                    }
                }, 1000);
                $('.otp-input').val('');
                $('.otp-input').first().focus();
            },
            error: function(xhr) {
                let msg = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
                $('#resendOtpBtn').text('Resend OTP').attr('disabled', false);
                $('#otpTimer').text('');
                clearInterval(otpCountdownInterval);
            }
        });
    }
</script>
@endpush