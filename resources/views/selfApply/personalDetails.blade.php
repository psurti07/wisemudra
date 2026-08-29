@extends('layouts.selfapply')
@push('css')
<link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
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
</style>
@endpush

@section('content')
<section id="contacts" class="bg--blue-400 personal-details-form pb-60 inner-page-hero contacts-section division min-vh-100 ">
    <div class="container">
        <div class="row justify-content-center mb-md-0 mb-35">
            <div class="col-md-5 col-lg-4 col-12 order-md-1 order-2 mt-md-0 mt-20">
                <div class="txt-block left-column r-24 p-4 bg--blue-500">
                    <div class="accordion accordion-flush mb-10" id="accordionFlushExample">
                        <div class="accordion-item bg-transparent">
                            <h2 class="accordion-header" id="flush-headingOne">
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
                                        <p class="s-12 color--grey mb-0">Mobile :</p>
                                        <p class="s-14 color--white mt-0">{{ Cookie::get('user_mobile') }}
                                        </p>

                                    </div>
                                    <div class="d-flex justify-content-between px-3 py-2">
                                        <p class="s-12 color--grey mb-0">Loan Amount :</p>
                                        <p class="s-14 color--white mt-0 mb-0">
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
                                <div class="cbox-12-ico border-success"><span class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-right-arrow ms-0 color--green-300 lh-1"></span></span></div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--white mb-0">Personal Details</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico border-dark-subtle bg--blue-200"><span
                                        class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-right-arrow ms-0 color--white lh-1"></span></span></div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--grey mb-0">Unlock Offers</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico border-dark-subtle bg--blue-200"><span
                                        class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-right-arrow ms-0 color--white lh-1"></span></span></div>
                            </div>
                            <div class="cbox-12-txt mb-0">
                                <p class="s-11 color--grey mb-0">Purchase Plan</p>
                            </div>
                        </div>
                        <div class="cbox-12 process-step">
                            <div class="ico-wrap">
                                <div class="cbox-12-ico border-dark-subtle bg--blue-200"><span
                                        class="fbox-ico ico-9 lh-1"> <span
                                            class="flaticon-right-arrow ms-0 color--white lh-1"></span></span></div>
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

            <div class="col-md-7 col-lg-8 col-12 mt-md-0 mt-20 order-md-2 order-1">
                <div class="txt-block left-column r-24 p-4 bg--blue-500">
                    <form action="{{ route('self.apply.personal.details.store') }}" id="personalDetailForm"
                        class="contact-form form-details save-form-4" novalidate="novalidate" method="post" accept-charset="utf-8">
                        <div class="card-body">
                            <div
                                class="fbox-7 fb-1 r-18 border-0  d-flex align-items-center justify-content-between p-0 mb-0">

                                <div class="fbox-ico d-flex align-items-start justify-content-start mb-3">
                                    <div>
                                        <div
                                            class="fbox-image d-flex align-items-center justify-content-center r-16 ico-20 details-icon">
                                            <span class="flaticon-briefcase text-white lh-1"></span>
                                        </div>
                                    </div>
                                    <div class="fbox-txt ms-3">
                                        <h4 class="color--white  mb-10 w-700 d-block">Personal Details
                                        </h4>
                                        <p class="mt-1 color--grey">For Our Experts To Analyze Your Loan Requirements.
                                        </p>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <label for="firstname"
                                        class="position-static p-0 text-uppercase s-14 mb-1 fw-normal color--grey">First
                                        Name *</label>
                                    <div class="form-group form-floating">

                                        <input id="firstname" name="firstname" type="text"
                                            class="form-control name mb-0 py-0 bg--blue-400" placeholder=""
                                            value="{{ old('firstname') }}">

                                    </div>
                                    @component('components.ajax-error',['field'=>'firstname'])@endcomponent
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <label for="lastname"
                                        class="position-static p-0 text-uppercase s-14 mb-1 fw-normal color--grey">Last
                                        Name *</label>
                                    <div class="form-group form-floating">
                                        <input id="lastname" name="lastname" type="text"
                                            class="form-control name mb-0 py-0 bg--blue-400" placeholder=""
                                            value="{{ old('lastname') }}">
                                    </div>
                                    @component('components.ajax-error',['field'=>'lastname'])@endcomponent
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <label for="email"
                                        class="position-static p-0 text-uppercase s-14 mb-1 fw-normal color--grey">Email
                                        *</label>
                                    <div class="form-group form-floating">
                                        <input id="email" name="email" type="email"
                                            class="form-control name mb-0 py-0 bg--blue-400" placeholder=""
                                            value="{{ old('email') }}">

                                    </div>
                                    @component('components.ajax-error',['field'=>'email'])@endcomponent
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <label for="pincode"
                                        class="position-static p-0 text-uppercase s-14 mb-1 fw-normal color--grey">Pincode
                                        *</label>
                                    <div class="form-group form-floating">
                                        <input id="pincode" name="pincode" type="text"
                                            class="form-control name numeric-input mb-0 py-0 bg--blue-400"
                                            placeholder="" value="{{ old('pincode') }}" maxlength="6" minlength="6"
                                            inputmode="numeric">

                                    </div>
                                    @component('components.ajax-error',['field'=>'pincode'])@endcomponent
                                </div>
                                <div id="loader" style="display:none;">
                                    Loading...
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <label for="city"
                                        class="position-static p-0 text-uppercase s-14 mb-1 fw-normal color--grey">City
                                        *</label>
                                    <div class="form-group form-floating">
                                        <input id="city" name="city" type="text"
                                            class="form-control mb-0 py-0 bg--blue-400" placeholder=""
                                            value="{{ old('city') }}">

                                    </div>
                                    @component('components.ajax-error',['field'=>'city'])@endcomponent
                                </div>
                                <div class="col-md-6 col-sm-12 mb-2">
                                    <label for="state"
                                        class="position-static p-0 text-uppercase s-14 mb-1 fw-normal color--grey">State
                                        *</label>
                                    <div class="form-group form-floating">
                                        <!--<input id="state" name="state"  type="text" class="form-control mb-0" placeholder="" value="{{ old('state') }}">-->
                                        <select id="state" name="state" class="form-control mb-0 py-0 bg--blue-400"
                                            style="font-size:16px!important;">
                                            <option value="">Select State</option>
                                            {!! getStateOption(old('state')) !!}
                                        </select>

                                    </div>
                                    @component('components.ajax-error',['field'=>'state'])@endcomponent
                                </div>
                                <div class="text-start">
                                    <button type="submit"
                                        class="s-14 btn btn--theme hover--theme submit w-100 rounded-pill"
                                        id="submit-btn">Continue <span class="fbox-ico ico-10"> <span
                                                class="flaticon-right-arrow  ico-20 ms-1"></span></span></button>
                                </div>
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
    $('#pancard').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
    $('.save-form-4').submit(function(event) {
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
                beforeSend: function() {
                    $('#submit-btn').html(
                        '<span class="spinner-border spinner-border-sm"></span> Continue'
                    );
                    $('#submit-btn').attr('disabled', true);
                },
                success: function(result) {
                    $(this).attr("disabled", false);
                    if (result.type === 'SUCCESS') {
                        window.location.href = `{{ route('self.apply.get.offers') }}`;
                    } else {
                        toastr.error(result.message);
                        $('#submit-btnsubmit-btn').html('Continue');
                        $('#submit-btn').attr('disabled', false);
                    }
                },
                error: function(error) {
                    $(this).attr("disabled", false);
                    let errors = error.responseJSON.errors,
                        errorsHtml = '';
                    $.each(errors, function(key, value) {
                        errorsHtml = '<strong>' + value[0] + '</strong>';
                        $('.' + key).html(errorsHtml);
                    });
                    $('#submit-btn').html('Continue');
                    $('#submit-btn').attr('disabled', false);
                }
            });
        }
    });
    /* get postal data like city and state */
    $('#pincode').on('input', function() {
        var pincode = $(this).val();

        // Only make request if pincode is of 6 digits
        if (pincode.length === 6) {
            $('#loader').show(); // Show loader
            $.ajax({
                url: `{{ route('self.apply.postal.details') }}`, // Route to the Laravel controller
                type: 'POST',
                data: {
                    pincode: pincode
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Pass CSRF token
                },
                success: function(response) {
                    $('#loader').hide(); // Hide loader
                    if (response.status === 'success') {
                        // Populate District and State fields
                        $('#city').val(response.district);
                        $('#state').val(response.state);
                    } else {
                        alert(response.message);
                        $('#district').val('');
                        $('#state').val('');
                    }
                },
                error: function() {
                    $('#loader').hide(); // Hide loader on error
                    alert('An error occurred while fetching the details.');
                }
            });
        } else {
            // Clear the fields if pincode length is not 6 digits
            $('#city').val('');
            $('#state').val('');
        }
    });
})
</script>
@endpush