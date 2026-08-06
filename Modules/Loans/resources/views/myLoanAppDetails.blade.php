@extends('layouts.customer')
@section('title','Loan Profile & Requirement Specifics ')

@push('style-css')
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl " id="kt_content_container">
        <div class="row g-3 g-xl-10 mb-xl-10">
            <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title card-title text-gray-800">
                            <span class="card-label fw-bold text-dark">Profile &amp; Requirements</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Reg. Date</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ displayDate($appDetails->rec_date) }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Application Number</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->application_number }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Loan type</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->loan_type==1?'Personal Loan':'Business Loan'}}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Req. Loan Amount</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ formatePriceIndia($appDetails->loan_amount) }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <!-- <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Tenure</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->loantenure }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>-->
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Purpose</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->loan_purpose }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <!-- <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">CIBIL Score </div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->cibilscore }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div> -->
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Income </div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->monthly_income }}</span>
                                    </div>
                                </div>
                                <!-- <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Current EMI </div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->currentemi }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Emi Bounce </div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $appDetails->emibounce==0?'NO':'YES' }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div> -->
                            </div>
                        </div>
                    </div>
                </div>
                @if(accType() == 2)
                <div class="card mt-5">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title card-title text-gray-800">
                            <span class="card-label fw-bold text-dark">Agent Details</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Agent Name</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6">{{ $agentDetails->fullname ?? 'Wisemudra Support' }}</span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Agent Mobile</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6">+91&nbsp;{{ $agentDetails->mobile ?? 'config("constant.COMPANY_MOBILE")' }}</span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Agent Email Id</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6">{{ $agentDetails->emailid ?? 'support@wisemudra.com' }}</span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Timming</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6">Monday - Saturday - (10:00AM - 05:00PM)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @if(accType() == 2)
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <div class="row g-5">
                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class="card h-md-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title card-title text-gray-800">
                                    <span class="card-label fw-bold text-dark">Service Timeline</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                    @foreach($remarks as $remark)
                                    <div class="m-0">
                                        <div class="d-flex align-items-center collapsible py-3 toggle  mb-0" data-bs-toggle="collapse" data-bs-target="#kt_job_4_{{$loop->iteration}}">
                                            <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                                <i class="ki-duotone ki-minus-square {{ $loop->index==0 ? 'toggle-on' : 'toggle-off' }} text-primary fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                <i class="ki-duotone ki-plus-square {{ $loop->index==0 ? 'toggle-off' : 'toggle-on' }} fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </div>
                                            <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">
                                                {{ $remark->title }}
                                            </h4>
                                        </div>
                                        <div id="kt_job_4_{{$loop->iteration}}" class="collapse {{ $loop->index == 0 ? 'show' : '' }} fs-6 ms-1">
                                            <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                                {!! $remark->remarks !!}
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed"></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                <div class="notice d-flex bg-light-info rounded border-info border border-dashed  p-6">
                    <i class="ki-duotone ki-message-text fs-2tx text-info me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>        <!--end::Icon-->
                        <div class="d-flex flex-stack flex-grow-1 ">
                        <div class=" fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Raise a Request</h4>
                            <div class="fs-6 text-gray-700 ">If you have any concern, please <a class="text-primary fw-bold" href="{{ route('customer.support') }}">Raise a Request</a>.</div>
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

</script>
@endpush
