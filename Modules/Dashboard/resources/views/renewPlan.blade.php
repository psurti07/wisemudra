@extends('layouts.customer')
@section('title','Renew Plan')
@push('style-css')
<style>
    .invalid-feedback>strong {
        font-weight: 500 !important;
        font-size: 14px !important;
    }
</style>
@endpush

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl " id="kt_content_container">
        <form method="post" action="{{ route('customer.renew.plan.store') }}" class="renewel-form">
            <div class="row g-3 g-xl-10 mb-xl-10">
                <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6">
                    <div class="card mb-5">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title card-title text-gray-800">
                                <span class="card-label fw-bold text-dark">Previous Loan Application Details</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Date</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ displayDate($loanApplicationDetails->rec_date) }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Loan type</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ $loanApplicationDetails->loan_type==1?'Personal Loan':'Business Loan'}}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Amount (&#8377;)</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ formatePriceIndia($loanApplicationDetails->loan_amount) }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Purpose</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ $loanApplicationDetails->loan_purpose }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Income (&#8377;)</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ $loanApplicationDetails->monthly_income }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Current EMI (&#8377;)</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ $loanApplicationDetails->currentemi }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title card-title text-gray-800">
                                <span class="card-label fw-bold text-dark">Previous Subscription Details
                                    @if (Carbon\Carbon::parse($membershipDetails->expiry_date)->lt(Carbon\Carbon::today()))
                                    <span class="badge badge-light-danger fs-base">Expired</span>
                                    @endif
                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Plan Name</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ Auth::user()->acc_type == 2 ? 'Hire Loan Agent Plan' : 'SelfApply Plan' }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Registration Date</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ date('d M, Y', strtotime($membershipDetails->registration_date)) }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Expiry Date</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ date('d M, Y', strtotime($membershipDetails->expiry_date)) }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Card Number</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ $membershipDetails->card_number }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-3"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Amount (&#8377;)</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">{{ formatePriceIndia($membershipDetails->amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6">
                    @php
                    $isExpired = Carbon\Carbon::parse($membershipDetails->expiry_date)->lt(Carbon\Carbon::today());
                    @endphp
                    @if($isExpired)
                    <div class="card mb-5">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title card-title text-gray-800">
                                <span class="card-label fw-bold text-dark">New Loan Application</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-5">
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-solid" style="margin-right:30px">
                                        <input class="form-check-input me-3" name="user_type" type="radio" value="1" id="kt_modal_update_role_option_0" {{ $loanApplicationDetails->user_type == 1 ? 'checked=checked' : '' }}>
                                        <label class="form-check-label" for="kt_modal_update_role_option_0">
                                            <div class="fw-bold text-gray-800">Salaried</div>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input me-3" name="user_type" type="radio" value="2" id="kt_modal_update_role_option_1" {{ $loanApplicationDetails->user_type == 2 ? 'checked=checked' : '' }}>
                                        <label class="form-check-label" for="kt_modal_update_role_option_1">
                                            <div class="fw-bold text-gray-800">Self Employed</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-7">
                                <div class="mb-5 fv-row fv-plugins-icon-container col-md-12 col-lg-6 col-xl-6">
                                    <label for="loan_amount">Loan Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="loan_amount" class="form-control mb-2" value="{{ $loanApplicationDetails->loan_amount }}" id="loan_amount" maxlength="7" minlength="5">
                                    @component('components.ajax-error',['field'=>'loan_amount'])@endcomponent
                                </div>
                                <div class="mb-5 fv-row fv-plugins-icon-container col-md-12 col-lg-6 col-xl-6">
                                    <label for="monthly_income">Monthly Income<span class="text-danger">*</span></label>
                                    <input type="text" name="monthly_income" class="form-control mb-2" value="" id="monthly_income" maxlength="7" minlength="5">
                                    @component('components.ajax-error',['field'=>'monthly_income'])@endcomponent
                                </div>
                                <div class="mb-5 fv-row fv-plugins-icon-container col-md-12 col-lg-6 col-xl-6">
                                    <label for="currentemi">Current EMI<span class="text-danger">*</span></label>
                                    <input type="text" name="currentemi" class="form-control mb-2" value="0" id="currentemi">
                                    @component('components.ajax-error',['field'=>'currentemi'])@endcomponent
                                </div>
                                <div class="mb-5 fv-row fv-plugins-icon-container col-md-12 col-lg-6 col-xl-6">
                                    <label for="loan_purpose">Loan Purpose<span class="text-danger">*</span></label>
                                    <select class="form-select" name="loan_purpose" id="loan_purpose">
                                        <option value="">Choose Loan Purpose</option>
                                        <option value="Personal Use" {{ $loanApplicationDetails->loan_purpose=='Personal Use' ? 'selected' : '' }}>Personal Use</option>
                                        <option value="Property Renovation" {{ $loanApplicationDetails->loan_purpose=='Property Renovation' ? 'selected' : '' }}>Property Renovation</option>
                                        <option value="Marriage Purpose" {{ $loanApplicationDetails->loan_purpose=='Marriage Purpose' ? 'selected' : '' }}>Marriage Purpose</option>
                                        <option value="Education Purpose" {{ $loanApplicationDetails->loan_purpose=='Education Purpose' ? 'selected' : '' }}>Education Purpose</option>
                                        <option value="Business Purpose" {{ $loanApplicationDetails->loan_purpose=='Business Purpose' ? 'selected' : '' }}>Business Purpose</option>
                                        <option value="Medical Emergency" {{ $loanApplicationDetails->loan_purpose=='Medical Emergency' ? 'selected' : '' }}>Medical Emergency</option>
                                    </select>
                                    @component('components.ajax-error',['field'=>'loan_purpose'])@endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-flush pt-3 mb-5 mb-lg-10" data-kt-subscriptions-form="pricing">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fw-bold">Plan Details</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="kt_create_new_payment_method">
                                <div class="py-1">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible toggle active" data-bs-toggle="collapse" data-bs-target="#kt_create_new_payment_method_1" aria-expanded="true">
                                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                                <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                <i class="ki-duotone ki-plus-square toggle-off fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </div>
                                            <div class="me-3">
                                                <div class="badge badge-light-primary mb-1">Most Recommended</div>
                                                <div class="d-flex align-items-center fw-bold">Hire Loan Agent</div>
                                                <div class="text-muted">Validity 1 Months</div>
                                            </div>
                                        </div>
                                        <div class="d-flex my-3 ms-9">
                                            <span class="text-primary fw-semibold fs-1">&#8377;{{ formatePriceIndia($planTwo->inOffer ? $planTwo->offeramount : $planTwo->amount) }}&nbsp;&nbsp;&nbsp;</span>
                                            <label class="form-check form-check-custom form-check-solid me-5">
                                                <input class="form-check-input" type="radio" name="payment_method" checked="checked" value="2">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="kt_create_new_payment_method_1" class="fs-6 ps-10 collapse show" style="">
                                        <div class="me-5">
                                            <table class="table table-flush fw-semibold gy-5">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Get Dedicated Loan Agent for Expert Consultation
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Unlock Your Personalized Pre-Approved Loan Offers
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Expert On-Call Consultation
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Access Your Personalized Portal
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                                <div class="py-1">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible toggle collapsed" data-bs-toggle="collapse" data-bs-target="#kt_create_new_payment_method_2" aria-expanded="false">
                                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                                <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                <i class="ki-duotone ki-plus-square toggle-off fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </div>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center fw-bold">Self Apply</div>
                                                <div class="text-muted">Validity 1 Month</div>
                                            </div>
                                        </div>
                                        <div class="d-flex my-3 ms-9">
                                            <span class="text-primary fw-semibold fs-1">&#8377;{{ formatePriceIndia($planOne->inOffer ? $planOne->offeramount : $planOne->amount) }}&nbsp;&nbsp;&nbsp;</span>
                                            <label class="form-check form-check-custom form-check-solid me-5">
                                                <input class="form-check-input" type="radio" name="payment_method" value="1">
                                            </label>
                                        </div>
                                    </div>
                                    <div id="kt_create_new_payment_method_2" class="fs-6 ps-10 collapse" style="">
                                        <div class="flex-equal me-5">
                                            <table class="table table-flush fw-semibold gy-1">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Unlock Your Personalized Pre-Approved Loan Offers
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Get Instant Self-Apply Loan Login Link(s)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-800 min-w-400px">
                                                            <i class="ki-duotone ki-check fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                            Access Your Personalized Portal
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg-end">
                        <button type="submit" class="btn btn-primary subscribeBtn">Subscribe Now</button>
                    </div>
                    @else
                    <div class="card border" data-bs-theme="light">
                        <div class="card-body">
                            <div class="align-items-center">
                                <div class="pt-4" style="color:#02a6fc !important">
                                    <span class="fs-3 fw-bold me-2 d-block lh-1 pb-2">Plan Currently Active</span>
                                </div>
                                <span class="fw-semibold text-dark fs-6 mb-5 d-block opacity-75">
                                    Your current subscription is active and valid until
                                    <strong>{{ date('d M, Y', strtotime($membershipDetails->expiry_date)) }}</strong>.
                                    You can continue enjoying all plan benefits without any interruption.
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<div id="result-container"></div>
@endsection

@push('script-tag')
<script>
    $(document).ready(function() {
        $('.renewel-form').submit(function(event) {
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
                    success: function(result) {
                        $(this).attr("disabled", false);
                        if (result.type === 'SUCCESS') {
                            toastr.success(result.message);
                            $('#result-container').html(result.html);
                            setTimeout(function() {
                                document.frm1.submit();
                            }, 500);
                        } else {
                            toastr.error(result.message);
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
                    }
                });
            }
        });
        $('#currentemi, #monthly_income, #loan_amount').on("input", function() {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    });
</script>
@endpush