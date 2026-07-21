@extends('layouts.customer')
@section('title','Active Support')
@push('style-css')
@endpush

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl " id="kt_content_container">
            <div class="row g-3 g-xl-10 mb-xl-10 pt-10">
                <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6">
                    <div class="card h-md-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Raise A Request</span>
                            </h3>
                        </div>
                        <div class="card-body p-7">
                            <form action="{{ route('customer.support.post') }}" id="supportform" class="contact-form" novalidate="novalidate" method="post" accept-charset="utf-8">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <select class="form-select" name="issuetype" id="issuetype" aria-label="Floating label select example">
                                                <option value="" selected>Select Issue</option>
                                                <option value="Service Problem">Service Problem</option>
                                                <option value="Payment Issue">Payment Issue</option>
                                                <option value="Technical Problem">Technical Problem</option>
                                                <option value="Eligibility or Pre-Approval Query">Eligibility or Pre-Approval Query</option>
                                                <option value="GST Return Query">GST Return Query</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <label for="issuetype">Query Related To*</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'issuetype'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="message" style="height: 100px" name="message"></textarea>
                                            <label for="message">Request Message*</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'message'])@endcomponent
                                    </div>
                                    <div class="col-md-12 text-end">
                                        <button class="btn btn-success btn-sm hover-elevate-up" type="submit" id="support-submit-btn">Submit request</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6">
                    <div class="card h-md-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">{{ env('COMPANY_NAME') }}</span>
                            </h3>
                        </div>
                        <div class="card-body pt-7 px-0">
                            <div class="tab-content mb-2 px-9">
                                <div class="tab-pane fade show active" id="kt_timeline_widget_3_tab_content_1">
                                    <div class="d-flex align-items-center mb-3">
                                        <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-gray"></span>
                                        <div class="flex-grow-1 me-5">
                                            <div class="text-gray-700 fw-semibold fs-6">
                                                {{ env('COMPANY_MOBILE') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-gray"></span>
                                        <div class="flex-grow-1 me-5">
                                            <div class="text-gray-700 fw-semibold fs-6">
                                                {{ env('INFO_EMAIL') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-20px mh-100 me-4 bg-gray"></span>
                                        <div class="flex-grow-1 me-5">
                                            <div class="text-gray-700 fw-semibold fs-6">
                                                10 AM to 5 PM ( Monday to Saturday )
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="text-gray-700 fw-semibold">
                                        Our customer service experts are here for you. Lines are open Monday to Saturday from 10 am – 5 pm
                                    </div>
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
        $(document).ready(function(){
            $('#supportform').submit(function(){
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
                            $('#support-submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Submit Request');
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
                                $('#support-submit-btn').html('Submit Request');
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
                            $('#support-submit-btn').html('Submit Request');
                            $('#support-submit-btn').attr('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
