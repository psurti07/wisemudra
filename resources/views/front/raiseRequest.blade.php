@extends('layouts.front')
@push('css')
<link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('style-css')
<style>
    .contact-form .form-select {
        margin-bottom: 0px !important;
        color: #2f3337 !important;
    }
    .btn-check:checked+.btn{
        color:#fff!important;
    }
</style>
@endpush
@section('content')
<section class="page-hero-section">
    <div class="page-hero-section-overlay bg--green-100 bg--scroll">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-12 text-center">
                    <div class="txt-block left-column border-0">
                        <span class="section-id"></span>
                        <h2 class="w-700">Raise a <span class="color--green-500">Request</span></h2>
                        <p class="p-md w-400">
                            Have a query? Raise a request with Wisemudra and get quick assistance for all your loan needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features-4" class="py-80 features-section division">
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div id="contacts" class="contacts-section division">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('front.request.raised.post') }}" class="contact-form career-form" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <input type="hidden" name="ticketno" value="{{date('mdh').random_code_num(4)}}">
                                    <!-- <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="usertype">I am a,</label><br />
                                            <div class="btn-group d-flex flex-wrap gap-2" role="group" aria-label="Basic radio toggle button group">
                                                
                                                <input type="radio" class="btn-check" name="usertype" value="usertype" id="inlineRadio1" autocomplete="off" checked>
                                                <label class="btn btn-sm btn--yellow-200" for="inlineRadio1">Self Apply</label>
                                    
                                                <input type="radio" class="btn-check" name="usertype" value="3" id="inlineRadio3" autocomplete="off">
                                                <label class="btn btn-sm btn--yellow-200" for="inlineRadio3">Hire Agent</label>
                                    
                                                <input type="radio" class="btn-check" name="usertype" value="2" id="inlineRadio2" autocomplete="off">
                                                <label class="btn btn-sm btn--yellow-200" for="inlineRadio2">Guest User</label>
                                            </div>
                                            @component('components.ajax-error',['field'=>'usertype'])@endcomponent
                                        </div>
                                    </div> -->

                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <select id="usertype" name="usertype" class="form-select s-15">
                                                <option value="" selected>I am *</option>
                                                <option value="1">Self Apply Customer</option>
                                                <option value="3">Hire Agent Customer</option>
                                                <option value="2">Guest User</option>
                                            </select>
                                        </div>
                                        @component('components.ajax-error',['field'=>'usertype'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <input id="firstname" type="text" name="firstname" class="form-control s-15" placeholder="" />
                                            <label for="firstname">First Name *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'firstname'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <input id="lastname" type="text" name="lastname" class="form-control s-15" placeholder="" />
                                            <label for="lastname">Last Name *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'lastname'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <input id="mobile" type="text" name="mobile" class="form-control s-15" placeholder="" minlength="10" maxlength="10" inputmode="numeric">
                                            <label for="Mobile">Mobile *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'mobile'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <input id="email" type="email" name="email" class="form-control s-15" placeholder="" />
                                            <label for="email">Email *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'email'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <select id="issuetype" name="issuetype" class="form-select s-15">
                                                <option value="" selected>Query Related To *</option>
                                                <option value="Service Problem">Service Problem</option>
                                                <option value="Payment Issue">Payment Issue</option>
                                                <option value="Technical Problem">Technical Problem</option>
                                                <option value="Eligibility or Pre-approval Query">Eligibility or Pre-approval Query</option>
                                                <option value="GST Return Query">GST Return Query</option>
                                                <option value="Double Payment">Double Payment</option>
                                                <option value="Refund Request">Refund Request</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        @component('components.ajax-error',['field'=>'issuetype'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-floating s-15">
                                            <textarea id="message" name="message" style="height:100px" class="form-control s-15" placeholder=""></textarea>
                                            <label for="message">Request Message *</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'message'])@endcomponent
                                    </div>
                                    <div class="col-lg-12 form-btn text-right">
                                        <button type="submit" class="apply-btn s-15 btn-sm btn btn--theme hover--theme submit">Submit Request</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="faqs-section">
                    <div class="faqs-3-questions">
                        <h4 class="mb-20">Common <span class="color--green-500">FAQs</span></h4>
                        <div class="accordion-wrapper">
                            {!! raiseRequestFaqs() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
@push('script-src')
@endpush
@push('scripts')
<script>
    $(document).ready(function() {
        $('.contact-form').submit(function(event) {
            let status = document.activeElement.innerHTML;
            event.preventDefault();
            if (status) {
                $('.ajax-error').html('');
                let data = new FormData(this);
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
                        $('.apply-btn').html('<span class="spinner-border spinner-border-sm"></span> Submit Request ');
                        $('.apply-btn').attr('disabled', true);
                    },
                    success: function(result) {
                        $(this).attr("disabled", false);
                        if (result.type === 'SUCCESS') {
                            toastr.success(result.message);
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        } else {
                            toastr.error(result.message);
                            $('.apply-btn').html('Submit Request');
                            $('.apply-btn').attr('disabled', false);
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
                        $('.apply-btn').html('Submit Request');
                        $('.apply-btn').attr('disabled', false);
                    }
                });
            }
        });
    });
</script>
@endpush


