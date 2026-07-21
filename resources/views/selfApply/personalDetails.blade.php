@extends('layouts.selfapply')
@push('css')
    <style>
        .accordion-button{ background-color: transparent!important; }
        .accordion-button:focus { box-shadow:none!important; }
        .txt-block h2 { margin-bottom:0px!important; }
    </style>
@endpush

@section('content')
    <section id="contacts" class="bg--white-100 personal-details-form pb-100 inner-page-hero contacts-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="txt-block left-column gr--white border border-radius-10 p-2">
                                    <div class="accordion accordion-flush mb-10" id="accordionFlushExample">
                                        <div class="accordion-item bg-transparent">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                    User Details
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body p-2">
                                                    <p class="s-12 text-grey mb-0">Mobile :</p>
                                                    <p class="s-14 text-black mt-0">{{ Cookie::get('user_mobile') }}</p>

                                                    <p class="s-12 text-grey mb-0">Loan Amount :</p>
                                                    <p class="s-14 text-black mt-0 mb-0">&#8377;{{ formatePriceIndia(Cookie::get('loan_amount')) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="divider" />

                                    <div class="p-2">
                                        <p class="s-12 mt-10 mb-10">Application Process </p>

                                        <div class="cbox-12 process-step">
                                            <div class="ico-wrap">
                                                <div class="cbox-12-ico text-white bg--green-500">1</div>
                                            </div>
                                            <div class="cbox-12-txt">
                                                <p class="s-11">Loan Details</p>
                                            </div>
                                        </div>
                                        <div class="cbox-12 process-step">
                                            <div class="ico-wrap">
                                                <div class="cbox-12-ico text-white bg--green-500">2</div>
                                            </div>
                                            <div class="cbox-12-txt">
                                                <p class="s-11">Personal Details</p>
                                            </div>
                                        </div>
                                        <div class="cbox-12 process-step">
                                            <div class="ico-wrap">
                                                <div class="cbox-12-ico border-dark-subtle">3</div>
                                            </div>
                                            <div class="cbox-12-txt">
                                                <p class="s-11">Unlock Offers</p>
                                            </div>
                                        </div>
                                        <div class="cbox-12 process-step">
                                            <div class="ico-wrap">
                                                <div class="cbox-12-ico border-dark-subtle">4</div>
                                            </div>
                                            <div class="cbox-12-txt">
                                                <p class="s-11">Purchase Plan</p>
                                            </div>
                                        </div>
                                        <div class="cbox-12 process-step">
                                            <div class="ico-wrap">
                                                <div class="cbox-12-ico border-dark-subtle">5</div>
                                            </div>
                                            <div class="cbox-12-txt">
                                                <p class="s-11">Personalized Offers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9 col-lg-9 col-12">
                                <div class="card">
                                    <form action="{{ route('self.apply.personal.details.store') }}" id="personalDetailForm" class="contact-form save-form-4" novalidate="novalidate" method="post" accept-charset="utf-8">
                                        <div class="card-body">
                                            <h5 class="fw-bolder s-16">Enter Your Personal Details</h5>
                                            <p class="mb-30 color--grey">For Our Experts To Analyze Your Loan Requirements.</p>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 mb-4">
                                                    <div class="form-group form-floating">
                                                        <input id="firstname" name="firstname" type="text" class="form-control name mb-0" placeholder="" value="{{ old('firstname') }}">
                                                        <label for="firstname">First Name *</label>
                                                    </div>
                                                    @component('components.ajax-error',['field'=>'firstname'])@endcomponent
                                                </div>
                                                <div class="col-md-6 col-sm-12 mb-4">
                                                    <div class="form-group form-floating">
                                                        <input id="lastname" name="lastname" type="text" class="form-control name mb-0" placeholder="" value="{{ old('lastname') }}">
                                                        <label for="lastname">Last Name *</label>
                                                    </div>
                                                    @component('components.ajax-error',['field'=>'lastname'])@endcomponent
                                                </div>
                                                <div class="col-md-6 col-sm-12 mb-4">
                                                    <div class="form-group form-floating">
                                                        <input id="email" name="email" type="email" class="form-control name mb-0" placeholder="" value="{{ old('email') }}">
                                                        <label for="email">Email *</label>
                                                    </div>
                                                    @component('components.ajax-error',['field'=>'email'])@endcomponent
                                                </div>
                                                <div class="col-md-6 col-sm-12 mb-4">
                                                    <div class="form-group form-floating">
                                                        <input id="pincode" name="pincode" type="text" class="form-control name numeric-input mb-0" placeholder="" value="{{ old('pincode') }}" maxlength="6" minlength="6" inputmode="numeric">
                                                        <label for="pincode">Pincode *</label>
                                                    </div>
                                                    @component('components.ajax-error',['field'=>'pincode'])@endcomponent
                                                </div>
                                                <div id="loader" style="display:none;">
                                                    Loading...
                                                </div>
                                                <div class="col-md-6 col-sm-12 mb-4">
                                                    <div class="form-group form-floating">
                                                        <input id="city" name="city"  type="text" class="form-control mb-0" placeholder="" value="{{ old('city') }}">
                                                        <label for="city">City *</label>
                                                    </div>
                                                    @component('components.ajax-error',['field'=>'city'])@endcomponent
                                                </div>
                                                <div class="col-md-6 col-sm-12 mb-4">
                                                    <div class="form-group form-floating">
                                                        <!--<input id="state" name="state"  type="text" class="form-control mb-0" placeholder="" value="{{ old('state') }}">-->
                                                        <select id="state" name="state" class="form-control mb-0" style="font-size:16px!important;">
                                                            <option value="">Select State</option>
                                                            {!! getStateOption(old('state')) !!}
                                                        </select>
                                                        <label for="state">State *</label>
                                                    </div>
                                                    @component('components.ajax-error',['field'=>'state'])@endcomponent
                                                </div>
                                                <div class="text-start">
                                                    <button type="submit" class="s-14 btn btn--theme hover--theme submit" id="submit-btn">Continue</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
    <script>
        $(document).ready(function(){
            $('#pancard').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });
            $('.save-form-4').submit(function (event) {
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
                            $('#submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Continue');
                            $('#submit-btn').attr('disabled', true);
                        },
                        success: function (result) {
                            $(this).attr("disabled", false);
                            if (result.type === 'SUCCESS') {
                                window.location.href = `{{ route('self.apply.get.offers') }}`;
                            } else {
                                toastr.error(result.message);
                                $('#submit-btnsubmit-btn').html('Continue');
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
                    $('#loader').show();  // Show loader
                    $.ajax({
                        url: `{{ route('self.apply.postal.details') }}`,  // Route to the Laravel controller
                        type: 'POST',
                        data: { pincode: pincode },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Pass CSRF token
                        },
                        success: function(response) {
                            $('#loader').hide();  // Hide loader
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
                            $('#loader').hide();  // Hide loader on error
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
