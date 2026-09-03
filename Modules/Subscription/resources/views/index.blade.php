@extends('layouts.customer')
@section('title','Subscription Details')
@push('style-css')
@endpush

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl pt-10" id="kt_content_container">
            <div class="row g-3 g-xl-10 mb-xl-10">
                
                @foreach($lists as $list)
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-white">
                        <!--begin::Labels-->
                        <div class="mb-6">   
                            {!! (Carbon\Carbon::parse($list->expiry_date)->lt(Carbon\Carbon::today())) ? '<span class="badge badge-light-danger fs-base me-2">Expired</span>' : '<span class="badge badge-light-success fs-base me-2">Active</span>' !!}
                        </div>                
                        <!--end::Labels-->
                        
                        <!--begin::Title-->
                        <h6 class="mb-6 fw-bolder text-gray-600 text-hover-primary">SUBSCRIPTION DETAILS #{{ $loop->iteration }}</h6>
                        <!--end::Title-->   
                    
                        <!--begin::Item-->
                        <div class="mb-4">       
                            <div class="fw-semibold text-gray-600 fs-7">Registration Date:</div> 
                            <div class="fw-bold text-gray-800 fs-6">{{ date('d M, Y', strtotime($list->registration_date)) }}</div>          
                        </div>                
                        <!--end::Item-->   
                        
                        <!--begin::Item-->
                        <div class="mb-4">       
                            <div class="fw-semibold text-gray-600 fs-7">Expiry Date:</div> 
                            <div class="fw-bold text-gray-800 fs-6">{{ date('d M, Y', strtotime($list->expiry_date)) }}</div>          
                        </div>                
                        <!--end::Item--> 
                        
                        <!--begin::Item-->
                        <div class="mb-4">       
                            <div class="fw-semibold text-gray-600 fs-7">Card Number:</div> 
                            <div class="fw-bold text-gray-800 fs-6">{{ $list->card_number }}</div>          
                        </div>                
                        <!--end::Item--> 
                        
                        <!--begin::Item-->
                        <div class="mb-4">       
                            <div class="fw-semibold text-gray-600 fs-7">Amount(&#8377;):</div> 
                            <div class="fw-bold text-gray-800 fs-6">{{ formatePriceIndia($list->amount) }}</div>          
                        </div>                
                        <!--end::Item--> 
                        
                        <!--begin::Item-->
                        <div class="mb-0">       
                            <div class="fw-semibold text-gray-600 fs-7">Payment ID:</div> 
                            <div class="fw-bold text-gray-800 fs-6">{{ $list->paymentid }}</div>          
                        </div>                
                        <!--end::Item--> 
                        
                        <!--begin::Labels-->
                         <div class="mt-10"> 
                            <a href="{{ route('customer.invoice') }}" target="_blank" class="btn btn-sm btn-primary">Download Invoice</a>
                        </div>                 
                        <!--end::Labels-->   
                    </div>  
                </div>
                @endforeach
                
            </div>
        </div>
    </div>
@endsection

@push('script-tag')

@endpush
