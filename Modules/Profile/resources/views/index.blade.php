@extends('layouts.customer')
@section('title','My Profile')
@push('style-css')
@endpush

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl " id="kt_content_container">
            <div class="row g-3 g-xl-10 mb-xl-10 pt-10">
                <div class="col-md-6 col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-header flex-nowrap pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900">Registration On - {{ date('d-m-Y',strtotime($profile->rec_date)) }}</span>
                            </h3>
                        </div>
                        <div class="card-body p-7">
                            <form action="{{ route('customer.profile.update.profile') }}" id="profileForm" class="contact-form" novalidate="novalidate" method="post" accept-charset="utf-8">
                                <div class="row g-3">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_name" name="first_name" type="text" class="form-control name mb-0" placeholder="" value="{{ $profile->first_name }}" disabled>
                                            <label for="form_name">First Name *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'first_name'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_name" name="last_name" type="text" class="form-control name mb-0" placeholder="" value="{{ $profile->last_name }}" disabled>
                                            <label for="form_name">Last Name *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'last_name'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_pancard" type="pancard" name="pancard" class="mb-0 form-control pancard" placeholder="" value="{{ $profile->pancard }}" readonly disabled>
                                            <label for="form_pancard">PANCard *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'pancard'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_email" type="email" name="email" class="mb-0 form-control email" placeholder="" value="{{ $profile->email }}" disabled>
                                            <label for="form_email">Email *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'email'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_mobile" type="tel" name="mobile" class="mb-0 form-control mobile" placeholder="" value="{{ $profile->mobile }}" readonly disabled>
                                            <label for="form_mobile">Mobile *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'mobile'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_dob" type="date" name="dob" class="mb-0 form-control dob" placeholder="" value="{{ $profile->dob }}" disabled>
                                            <label for="form_dob">DOB *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'dob'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_pincode pincode" type="text" name="pincode" class="mb-0 form-control pincode numeric-input" placeholder="" value="{{ $profile->pincode }}" maxlength="6" minlength="6" disabled>
                                            <label for="form_pincode">PinCode *</label>
                                        </div>
                                        <span class="pincode-msg text-danger"></span>
                                        @component('components.ajax-error',['field'=>'pincode'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_city city" type="text" name="city" class="mb-0 form-control city" placeholder="" value="{{ $profile->city ?? old('city') }}" readonly disabled>
                                            <label for="form_city">City *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'city'])@endcomponent
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-floating">
                                            <input id="form_state state" type="text" name="state" class="mb-0 form-control state" placeholder="" value="{{ $profile->state ?? old('state') }}" readonly disabled>
                                            <label for="form_state">State *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'state'])@endcomponent
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                    <div class="row g-5">
                    @if($profile->acc_type == 2)
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="card">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-dark">Agent Details</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-7 px-0">
                                    <div class="tab-content mb-2 px-9">
                                        <div class="tab-pane fade show active" id="kt_timeline_widget_3_tab_content_1">
                                            <div class="d-flex align-items-center mb-3">
                                                <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-success"></span>
                                                <div class="flex-grow-1 me-5">
                                                    <div class="text-gray-700 fw-semibold fs-6">
                                                        Agent Name. : {{ isset($agent->fullname) ? $agent->fullname : 'Wisemudra Support' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-primary"></span>
                                                <div class="flex-grow-1 me-5">
                                                    <div class="text-gray-700 fw-semibold fs-6">
                                                        Agent Mobile. : {{ isset($agent->mobile) ? '+91 '.substr($agent->mobile, 0, 5) . ' ' . substr($agent->mobile, 5) : '+91-94292-14352' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-warning"></span>
                                                <div class="flex-grow-1 me-5">
                                                    <div class="text-gray-700 fw-semibold fs-6">
                                                        10:00 AM to 05:00 PM (Monday to Saturday)
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-danger"></span>
                                                <div class="flex-grow-1 me-5">
                                                    <div class="text-gray-700 fw-semibold fs-6">
                                                        Language : English, Hindi, Gujarati
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-info"></span>
                                                <div class="flex-grow-1 me-5">
                                                    <div class="text-gray-700 fw-semibold fs-6">
                                                        {{ config('constant.COMPANY_ADDRESS') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed my-3"></div>
                                            <div class="text-gray-400 fw-semibold fs-7">
                                                Our customer service experts are here for you. Lines are open Monday to Saturday from 9 am – 6 pm
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="card">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-dark">Company Details</span>
                                    </h3>
                                </div>
                                
                                <div class="card-body p-7">
                                    <form action="{{ route('customer.profile.create.company') }}" id="companyForm" class="company-form" novalidate="novalidate" method="post" accept-charset="utf-8">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="company_name" placeholder="Company Name" name="company_name" value="{{ $profile->company_name ?? '' }}"/>
                                                    <label for="company_name">Company Name *</label>
                                                </div>
                                                @component('components.ajax-error',['field'=>'company_name'])@endcomponent
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="company_gst" placeholder="Company GST" name="company_gst" value="{{ $profile->company_gst ?? '' }}"/>
                                                    <label for="company_gst">Company GST *</label>
                                                </div>
                                                @component('components.ajax-error',['field'=>'company_gst'])@endcomponent
                                            </div>
                                            <div class="col-md-12 text-start">
                                                <button class="btn btn-success btn-sm hover-elevate-up" type="submit" id="company-submit-btn">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="card">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-dark">Change Password</span>
                                    </h3>
                                </div>
                                
                                <div class="card-body p-7">
                                    <form action="{{ route('customer.profile.update.password') }}" id="changePasswordForm" class="contact-form" novalidate="novalidate" method="post" accept-charset="utf-8">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="password" placeholder="******" name="password"/>
                                                    <label for="password">New Password *</label>
                                                </div>
                                                @component('components.ajax-error',['field'=>'password'])@endcomponent
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="password_confirmation" placeholder="******" name="password_confirmation"/>
                                                    <label for="password_confirmation">Confirm Password *</label>
                                                </div>
                                                @component('components.ajax-error',['field'=>'password_confirmation'])@endcomponent
                                            </div>
                                            <div class="col-md-12 text-start">
                                                <button class="btn btn-success btn-sm hover-elevate-up" type="submit" id="support-submit-btn">Change Password</button>
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
    </div>
@endsection

@push('script-tag')
    <script>
            $('.pincode').on('input', function() {
                var pincode = $(this).val();

                // Only make request if pincode is of 6 digits
                if (pincode.length === 6) {
                    $.ajax({
                        url: `{{ route('customer.profile.postal.details') }}`,  // Route to the Laravel controller
                        type: 'POST',
                        data: { pincode: pincode },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Pass CSRF token
                        },
                        beforeSend: function(xhr) {
                            $(".pincode-msg").text('we are fetching cities and state..'); // Example: Show a loading indicator
                        },
                        success: function(response) {
                            $('#loader').hide();  // Hide loader
                            if (response.status === 'success') {
                                $(".pincode-msg").text('');
                                // Populate District and State fields
                                $('.city').val(response.district);
                                $('.state').val(response.state);
                            } else {
                                alert(response.message);
                                $(".pincode-msg").text('');
                                $('.district').val('');
                                $('.state').val('');
                            }
                        },
                        error: function() {
                            $(".pincode-msg").text('');
                            alert('An error occurred while fetching the details.');
                        }
                    });
                } else {
                    // Clear the fields if pincode length is not 6 digits
                    $('.city').val('');
                    $('.state').val('');
                }
            });
        $(document).ready(function(){
            $('.numeric-input').on('keydown', function(event) {
                if (!(event.key === 'Backspace' || event.key === 'Delete' || (event.key >= '0' && event.key <= '9'))) {
                    event.preventDefault();
                }
            });
            

            $("#profileForm").submit(function(){
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
                            $('#submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Update Profile');
                            $('#submit-btn').attr('disabled', true);
                        },
                        success: function (result) {
                            $(this).attr("disabled", false);
                            if (result.type === 'SUCCESS') {
                                toastr.success(`Profile updated successfully.Wait redirecting in 2 seconds`);
                                setTimeout(function(){
                                    window.location.reload();
                                },2000);
                            } else {
                                toastr.error(result.message);
                                $('#submit-btn').html('Update Profile');
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
                            $('#submit-btn').html('Update Profile');
                            $('#submit-btn').attr('disabled', false);
                        }
                    });
                }
            });
            
            $('#changePasswordForm').submit(function(){
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
                        beforeSend: function () {
                            $('#support-submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Update Password');
                            $('#support-submit-btn').attr('disabled', true);
                        },
                        success: function (result) {
                            $(this).attr("disabled", false);
                            if (result.type === 'SUCCESS') {
                                toastr.success(`${result.message}`);
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.error(result.message);
                                $('#support-submit-btn').html('Update Password');
                                $('#support-submit-btn').attr('disabled', false);
                            }
                        },
                        error: function (error) {
                            $(this).attr("disabled", false);
                            let errors = error.responseJSON.errors, errorsHtml = '';
                            $.each(errors, function (key, value) {
                                errorsHtml = '<strong>' + value[0] + '</strong>';
                                $('.' + key).html(errorsHtml);
                            });
                            $('#support-submit-btn').html('Update Password');
                            $('#support-submit-btn').attr('disabled', false);
                        }
                    });
                }
            });
            
            $('#companyForm').submit(function(){
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
                        beforeSend: function () {
                            $('#company-submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Submit');
                            $('#company-submit-btn').attr('disabled', true);
                        },
                        success: function (result) {
                            $(this).attr("disabled", false);
                            if (result.type === 'SUCCESS') {
                                toastr.success(`${result.message}`);
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.error(result.message);
                                $('#company-submit-btn').html('Submit');
                                $('#company-submit-btn').attr('disabled', false);
                            }
                        },
                        error: function (error) {
                            $(this).attr("disabled", false);
                            let errors = error.responseJSON.errors, errorsHtml = '';
                            $.each(errors, function (key, value) {
                                errorsHtml = '<strong>' + value[0] + '</strong>';
                                $('.' + key).html(errorsHtml);
                            });
                            $('#company-submit-btn').html('Submit');
                            $('#company-submit-btn').attr('disabled', false);
                        }
                    });
                }
            });
            
        });
    </script>
@endpush
