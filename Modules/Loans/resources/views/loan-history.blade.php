@extends('layouts.customer')
@section('title','Application History')
@push('style-css')
@endpush

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl " id="kt_content_container">
            <div class="row g-3 pt-10">
                @foreach($loanHistory as $row)
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-body">
                                <div class=" mb-3">
                                    <!--end::Pic-->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center mb-2">
                                                    <a href="javascript:;" class="text-gray-900 fs-2 fw-bold me-1">{{ $row->loan_type == 1 ? 'Personal Loan' : 'Business Loan' }}</a>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="text-end">
                                                    <a href="{{ route('customer.loan.details',['loanAppId'=>encrypt($row->id)]) }}" class="btn btn-sm btn-warning me-2">
                                                        <i class="ki-duotone ki-information-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                       
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fw-semibold fs-6 text-gray-500">Loan Amount</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold" data-kt-countup-value="{{ formatePriceIndia($row->loan_amount) }}" data-kt-countup-prefix="&#8377; " data-kt-countup="true">&#8377;&nbsp;{{ formatePriceIndia($row->loan_amount) }}</div>
                                            </div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fw-semibold fs-6 text-gray-500">Application Number</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold" data-kt-countup-value="{{ $row->application_number }}">
                                                    {{ $row->application_number }}</div>
                                            </div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <div class="fw-semibold fs-6 text-gray-500">Reg. Date</div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bold" data-kt-countup-value="{{ displayDate($row->rec_date) }}">
                                                {{ displayDate($row->rec_date) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('script-tag')
@endpush
