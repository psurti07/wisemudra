@extends('layouts.workshop')
@push('css')
<style>
    .personal-details-form{
min-height:95vh;
}
</style>

@endpush
@section('content')

<section id="contacts" class="gr--perl personal-details-form pb-100 inner-page-hero contacts-section division">
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-8 col-md-10 col-sm-12 offset-md-1">
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="txt-block left-column">
                                <div class="cbox-12 process-step  border-right">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">1</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Fill Basic Details</p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico border-dark-subtle">2</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Confirm Your Registration </p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico border-dark-subtle">3</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Get Webinar Access </p>
                                    </div>
                                </div>

                                <hr />
                                <div class="accordion accordion-flush mb-30" id="accordionFlushExample">
                                    <div class="accordion-item bg-transparent">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                User Details
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Name : </div>
                                                    <div class="col-lg-6 s-14">{{ session('firstname') }} {{ session('lastname') }}</div>
                                                    <hr class="custm-HR" />
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Mobile :</div>
                                                    <div class="col-lg-6 s-14">{{ session('mobile_no') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-8 col-12">
                            <div class="card">
                                <form action="{{ route('workshop.storeStep3') }}" id="personalDetailForm" class="contact-form signup-form" novalidate="novalidate" method="post" accept-charset="utf-8">
                                    <div class="card-body">
                                        <h5 class="fw-bolder s-16">Personal Details</h5>
                                        <p class="mb-30 color--grey">Fill in the details below to complete your registration.</p>

                                        <div class="row">
                                            <div class="col-lg-10 col-md-10 col-12">
                                                <div class="form-group mb-3">
                                                    <small class="d-block text-start color-000 mb-0 fsz-14">Email Id <span
                                                            class="text-danger">*</span></small>
                                                    <input type="email" name="email" class="form-control mb-0 fsz-14 radius-2"
                                                        placeholder="john@doe.com">
                                                    @component('components.ajax-error', ['field' => 'email'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-12">
                                                <div class="form-group mb-3">
                                                    <small class="d-block text-start color-000 mb-0fsz-14">Pincode <span
                                                            class="text-danger">*</span></small>
                                                    <input type="tel" id="pincode" name="pincode" class="form-control mb-0 name numeric-input fsz-14 radius-2" placeholder="123456" value="{{ old('pincode') }}" maxlength="6" minlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="postal-code">
                                                    @component('components.ajax-error', ['field' => 'pincode'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div id="loader" style="display:none;">
                                                Loading...
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-12">
                                                <div class="form-group mb-3">
                                                    <small class="d-block text-start color-000 mb-0fsz-14">City <span
                                                            class="text-danger">*</span></small>
                                                    <input id="city" name="city" type="text"
                                                        class="form-control mb-0 fsz-14 radius-2" placeholder="Mumbai"
                                                        value="{{ old('city') }}">
                                                    @component('components.ajax-error', ['field' => 'city'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-12">
                                                <div class="form-group mb-3">
                                                    <small class="d-block text-start color-000 mb-0fsz-14">State <span
                                                            class="text-danger">*</span></small>
                                                    <select id="state" name="state" class="form-select mb-0 fsz-14 radius-2">
                                                        <option value="">Select State</option>
                                                        {!! getStateOption(old('state')) !!}
                                                    </select>
                                                    @component('components.ajax-error', ['field' => 'state'])
                                                    @endcomponent
                                                </div>
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
    $(document).ready(function() {
        $('.signup-form').submit(function(event) {
            event.preventDefault();
            let form = this;
            let $btn = $('#signupbtn');
            $('.ajax-error').html('');
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
                    $('#registerLoader').removeClass('d-none');
                },
                success: function(result) {
                    $btn.attr('disabled', false);
                    $('#registerLoader').addClass('d-none');
                    if (result.type === 'SUCCESS') {
                        window.location.href = result.redirect;
                    } else {
                        alert(result.message);
                    }
                },
                error: function(error) {
                    $btn.attr('disabled', false);
                    $('#registerLoader').addClass('d-none');
                    let errors = error.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key).html('<strong>' + value[0] + '</strong>');
                    });
                }
            });
        });
    });
    $(document).ready(function() {
        $("#pincode, #goal").on("input", function() {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    });
    $('#pincode').on('input', function() {
        var pincode = $(this).val();
        // Only make request if pincode is of 6 digits
        if (pincode.length === 6) {
            $('#loader').show(); // Show loader
            $.ajax({
                url: `{{ route('webinar.postal.details') }}`, // Route to the Laravel controller
                type: 'POST',
                data: {
                    pincode: pincode
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loader').hide(); // Hide loader
                    if (response.status === 'success') {
                        // Populate District and State fields
                        $('#city').val(response.district);
                        $('#state').val(response.state);
                        $('.pincode').text('');
                    } else {
                        //alert(response.message);
                        $('#district').val('');
                        $('#state').val('');
                        $('.pincode').text('Enter valid pincode.');
                    }
                },
                error: function() {
                    $('#loader').hide(); // Hide loader on error
                    $('.pincode').text('Enter valid pincode.');
                }
            });
        } else {
            // Clear the fields if pincode length is not 6 digits
            $('#city').val('');
            $('#state').val('');
        }
    });
</script>

@endpush