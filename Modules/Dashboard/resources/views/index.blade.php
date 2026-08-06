@extends('layouts.customer')
@section('title','Welcome to Wisemudra! ')
@push('style-css')
@endpush

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl " id="kt_content_container">
            <div class="row g-3 g-xl-10 mb-10">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="row g-5 mb-xl-0">
                        @if ($showMessage)
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                            <i class="ki-duotone ki-message-text fs-2tx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-stack flex-grow-1 ">
                                <div class=" fw-semibold">
                                    <h4 class="text-gray-900 fw-bold">Service Update</h4>
                                    <div class="fs-6 text-gray-700 ">Dear Customer, a new update on your service has been added. <a class="fw-bold" href="{{ route('customer.loan.details',['loanAppId'=>encrypt($appId)]) }}">Check Here</a>.</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(session('success'))
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="alert alert-dismissible bg-light-success border border-success d-flex flex-column flex-sm-row p-5">
                                    <i class="ki-duotone ki-information-5 fs-2hx text-success me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <div class="d-flex flex-column pe-0 pe-sm-10">
                                        <h5 class="mb-3 fs-8">Payment Success</h5>
                                        <span class="fs-7 text-success">
                                        {{ session('success') }}
                                        </span>
                                    </div>
                                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                        <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if($message!=NULL)
                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                            <div class="alert alert-dismissible bg-light-danger border border-danger d-flex flex-column flex-sm-row p-5">
                                <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-3 fs-8">Renewal Reminder / Notice</h5>
                                    <span class="fs-7 text-danger">
                                        {{ $message }}
                                        <a href="{{ route('customer.renew.plan') }}" class="me-2" id="kt_user_follow_button">
                                            <span class="indicator-label"><u>Renew Plan</u></span>
                                        </a>
                                    </span>
                                    @if($profile->acc_type == 1)
                                        <span class="fs-7 mt-2 text-danger">Want to Hire Loan Agent for expert consultation?<br/>
                                        <a href="{{ route('customer.renew.plan') }}" class="btn btn-sm btn-danger mt-2" id="kt_user_follow_button">
                                            <span class="indicator-label">Hire Agent Now</span>
                                        </a></span>
                                    @endif
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        </div>
                        @endif
                        @if($accmsg!=NULL || $accmsg!='')
                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                            <div class="alert alert-dismissible bg-light-warning border border-warning d-flex flex-column flex-sm-row p-5">
                                <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-3 fs-8">Important Update</h5>
                                    <span class="fs-7 text-dark">{{ $accmsg }}</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-duotone ki-cross fs-1 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="g-3 mb-xl-10">
                        <div class="card mb-xl-6">
                            <div class="card-body pt-9 pb-2">
                                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                                    <div class="me-7 mb-4">
                                        <div class="symbol symbol-80px symbol-lg-80px symbol-fixed position-relative">
                                            <img src="{{ asset('customer/assets/media/avatars/300-1.png') }}" alt="image" />
                                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center mb-2">
                                                    <a href="javascript:;" class="text-gray-900 text-hover-success fs-2 fw-bold me-1">{{ $profile->first_name.' '.$profile->last_name }}</a>
                                                    <a href="javascript:;"><i class="ki-duotone ki-verify fs-1 text-success"><span class="path1"></span><span class="path2"></span></i></a>
                                                </div>
                                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-2 pe-2">
                                                    <p class="d-flex align-items-center text-gray-700 text-hover-success me-5 mb-2">
                                                        <i class="ki-duotone ki-phone fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                        {{ $profile->mobile }}
                                                    </p>
                                                    <p class="d-flex align-items-center text-gray-700 text-hover-success me-5 mb-2">
                                                        <i class="ki-duotone ki-geolocation fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                        {{ $profile->city }}
                                                    </p>
                                                    <p class="d-flex align-items-center text-gray-700 text-hover-success mb-2">
                                                        <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                        {{ $profile->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            @if($profile->acc_type == 1)
                                            <a href="javascript:;" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan"  data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan" >
                                                <i class="ki-duotone ki-star fs-3"><span class="path1"></span><span class="path2"></span></i><span class="indicator-label"></span>Upgrade to Pro</span>
                                            </a>
                                            @endif
                                            
                                            <a href="{{ route('customer.profile') }}" class="btn btn-sm btn-light-info me-2" id="kt_user_follow_button">
                                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i><span class="indicator-label">Edit Profile</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @if(false)
                                <div class="separator separator-dashed my-6"></div>
                                <div class="card mb-xl-12">
                                    <div class="card-header p-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-success">Download Report!</span>
                                            <span class="text-muted mt-1 fw-semibold fs-7">View your service timeline to stay updated, always!</span>
                                        </h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <a href="{{ route('customer.download.report') }}" class="btn btn-sm btn-primary me-2" id="kt_user_follow_button">
                                            <i class="ki-duotone ki-file fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            <span class="indicator-label">Download Report</span>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="row g-5 mb-xl-10">
                        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6 col-xxl-6">
                            @if($profile->acc_type == 2)
                                <div class="card" data-bs-theme="light" style="background: linear-gradient(112.14deg, #3ca46c 0%, #4fcd89 100%)">
                                    <div class="card-body">
                                        <div class="row align-items-center h-100">
                                            <div class="col-12">
                                                <div class="text-white mb-6 pt-6">
                                                    <span class="fs-4 fw-semibold me-2 d-block lh-1 pb-2 opacity-75">Your Loan Consultant Details</span>
                                                </div>
                                                <div class="card mb-xl-6">
                                                    <div class="card-body pt-9 pb-2">
                                                        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                                                            <div class="me-7 mb-4">
                                                                <div class="symbol symbol-80px symbol-lg-80px symbol-fixed position-relative">
                                                                    <img src="{{ asset('customer/assets/media/avatars/300-1.png') }}" alt="image" />
                                                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                                                    <div class="d-flex flex-column">
                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <a href="javascript:;" class="text-gray-900 text-hover-success fs-2 fw-bold me-1">{{ isset($agent->fullname) ? $agent->fullname : 'Wisemudra Support' }}</a>
                                                                            <a href="javascript:;"><i class="ki-duotone ki-verify fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i></a>
                                                                        </div>
                                                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                                                            <a href="javascript:;" class="d-flex align-items-center text-gray-700 text-hover-success me-5 mb-2">
                                                                                <i class="ki-duotone ki-phone fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                                {{ isset($agent->mobile) ? '+91 '.substr($agent->mobile, 0, 5) . ' ' . substr($agent->mobile, 5) : 'config("constant.COMPANY_MOBILE")' }}
                                                                            </a>
                                                                            <a href="javascript:;" class="d-flex align-items-center text-gray-700 text-hover-success me-5 mb-2">
                                                                                <i class="ki-duotone ki-geolocation fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                                                Monday to Saturday - (10:00AM - 05:00PM)
                                                                            </a>
                                                                            <a href="javascript:;" class="d-flex align-items-center text-gray-700 text-hover-success mb-2">
                                                                                <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                                                {{ $agent->emailid ?? 'support@Wisemudra.com' }}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card" data-bs-theme="light" style="background: linear-gradient(112.14deg, #3ca46c 0%, #4fcd89 100%)">
                                    <div class="card-body">
                                        <div class="row align-items-center h-100">
                                            <div class="col-12">
                                                <img src="{{ asset('front/images/support.png') }}" width="90" height="90" alt="support">
                                                <div class="text-white mb-2 pt-6">
                                                    <span class="fs-4 fw-semibold me-2 d-block lh-1 pb-2 opacity-75"><u>Want Expert Consultation?</u></span>
                                                    <span class="fs-2qx fw-bold">Process Your Pre-Approved Offers Instantly with Our Experts.</span>
                                                </div>
                                                <div class="d-flex flex-column flex-sm-row mt-4 d-grid gap-2">
                                                    <a href="{{ route('customer.renew.plan') }}" class="btn btn-dark flex-shrink-0 me-lg-2">Hire Agent Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="card border mt-10" data-bs-theme="light">
                                <div class="card-body">
                                    <div class="align-items-center">
                                        <div class="pt-4" style="color:#02a6fc !important">
                                            <span class="fs-3 fw-bold me-2 d-block lh-1 pb-2">Download Report!</span>
                                        </div>
                                        <span class="fw-semibold text-dark fs-6 mb-5 d-block opacity-75">
                                            View your service timeline to stay updated, always!
                                        </span>
                                        <div class="d-flex flex-column flex-sm-row d-grid gap-2">
                                            <a href="{{ route('customer.download.report') }}" class="btn btn-success flex-shrink-0 me-lg-2"><i class="ki-duotone ki-file fs-2"><span class="path1"></span><span class="path2"></span></i>Download Report</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6 col-xxl-6">
                            <div class="card border" data-bs-theme="light">
                                <div class="card-header align-items-center">
                                    <h3 class="mb-0">Loan Eligibility Criteria</h3>
                                </div>
                                <div class="card-body">
                                    <div class="align-items-center">
                                        <p><strong>Salaried Employees :</strong></p>
                                        <p>Apply if you are a salaried employee who meets the following eligibility criteria:</p>
                                        <ul>
                                            <li>Minimum Age: 21 Years </li>
                                            <li>Minimum Salary – Rs.15,000/month (Should be reflected in the bank statement) </li>
                                            <li>Minimum Job Duration: 1 Year </li>
                                        </ul>
                                        <hr/>

                                        <p><strong>Self-Employed Individuals :</strong></p>
                                        <p>Apply if you run your own business and meet the following eligibility criteria: </p>
                                        <ul>
                                            <li>Minimum Age: 21 Years </li>
                                            <li>Minimum 1 year in the business</li>
                                            <li>Income Tax Return of at least 1 year</li>
                                        </ul>
                                        <hr/>

                                        <p><strong>Small-scale Business :</strong></p>
                                        <p>Loan Eligibility Requirements – For Small-Scale Businesses: </p>
                                        <ul>
                                            <li>Minimum Age: 21 Years </li>
                                            <li>Minimum 1 Year IT Return</li>
                                            <li>Minimum 1 Year Business Stability</li>
                                        </ul>
                                        <hr/>

                                        <p><strong>Audited Business :</strong></p>
                                        <p>Loan eligibility requirements – For Audited Report Businesses: </p>
                                        <ul>
                                            <li>Minimum Age: 21 Years </li>
                                            <li>1 Crore Plus Yearly Turnover</li>
                                            <li>Minimum 2 Year Audited Report</li>
                                        </ul>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <h6 class="text-danger"><marquee scrollamount="8" behavior="scroll">BE AWARE! We ask our customers to make payments ONLY on our website https://wisemudra.com and NOT through any other source, directly or indirectly. Thanks!</marquee></h6>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-tag')
    <script>
        function copyText() {
            var copyText = document.getElementById("target1");
            copyText.select();
            document.execCommand("copy");
            toastr.success('Referral link copied successfully!');
        }
    </script>
@endpush
