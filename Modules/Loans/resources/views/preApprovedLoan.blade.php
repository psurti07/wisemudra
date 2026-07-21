@extends('layouts.customer')
@section('title','Your Personalized Pre-Approved Loan Offers')

@push('style-css')
<style>
/* Container for locked cards */
.offer-card.locked {
  position: relative;
  user-select: none;
  overflow: hidden;
}

/* Blur the content */
.offer-card.locked .card-content {
  filter: blur(6px);
  pointer-events: none; /* Disable interaction */
}

/* Overlay for blur and lock icon */
.offer-card.locked .overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(2px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  pointer-events: none;
}

/* Lock icon styling */
.locked .lock-icon {
  width: 50px;
  height: 50px;
  opacity: 0.9;
}

/* Optional: prevent selecting text globally */
body {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;

    $isExpired = Carbon::parse($membership->expiry_date)->isPast();
    
@endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl " id="kt_content_container">
            <div class="row g-8 mt-3 mb-10">
                @if($message!=NULL)
                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
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
                                    @if(Auth::user()->acc_type == 1)
                                        <span class="fs-7 mt-2 text-danger">Want to Hire Loan Agent for expert consultation?<br/>
                                        <a href="javascript:;" class="btn btn-sm btn-danger mt-2" id="kt_user_follow_button">
                                            <span class="indicator-label">Hire Agent Now</span>
                                        </a></span>
                                    @endif
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach($offers as $offer)
                <div class="col-md-4 col-lg-4 col-xxl-4 mb-10">
                    @if($isExpired)
                    <div class="offer-card locked">
                    @endif
                        <div class="card {{ $isExpired ? 'card-content' : '' }} h-md-100">
                            <div class="card-header d-flex justify-content-center align-items-center border-0" style="{{ $loop->index != 0 ? 'background: rgba(0, 0, 0, 0) linear-gradient(120deg, #f94, #D9455A) 0 0 no-repeat;' : 'background: rgba(0, 0, 0, 0) linear-gradient(120deg, #62ffc6, #59b006) 0 0 no-repeat;' }}min-height:20px!important">
                                <h3 class="card-title">
                                    <span class="card-label fw-semibold fs-base text-light">{{ $loop->index == 0 ? 'Highly Recommended' : 'Pre-Approved Offers' }}</span>
                                </h3>
                            </div>
                            <div class="card-body pt-7 px-0">
                                <div class="px-5">
                                    <img src="{{ 'https://manage.wisemudra.com/upload/banks/'.$offer->bank_image }}" alt="{{ $offer->bank_name }}" width="150">
                                </div>
                                <div class="separator separator-dashed my-6"></div>
                                <div class="px-5">
                                    <h4 class="fw-bold text-gray-900 mb-4 me-1" style="line-height: 2.2rem;">{{ $offer->title }}</h4>
                                    
                                    <div class="fw-semibold pe-2" style="font-size:14px!important">
                                        @if($offer->option1!=NULL)
                                        <p class="text-dark me-5 mb-2">
                                            <i class="ki-duotone ki-check-circle me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            {{ $offer->option1 }}
                                        </p>
                                        @endif
                                        @if($offer->option2!=NULL)
                                        <p class="text-dark me-5 mb-2">
                                            <i class="ki-duotone ki-check-circle me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            {{ $offer->option2 }}
                                        </p>
                                        @endif
                                        @if($offer->option3!=NULL)
                                            <p class="text-dark me-5 mb-2">
                                                <i class="ki-duotone ki-check-circle me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                {{ $offer->option3 }}
                                            </p>
                                        @endif
                                        @if($offer->option4!=NULL)
                                            <p class="text-dark me-5 mb-2">
                                                <i class="ki-duotone ki-check-circle me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                {{ $offer->option4 }}
                                            </p>
                                        @endif
                                        @if($offer->option5!=NULL)
                                            <p class="text-dark me-5 mb-2">
                                                <i class="ki-duotone ki-check-circle me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                {{ $offer->option5 }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-5">
                                        <a href="javascript:;" data-id="{{ $offer->apply_id }}" data-url="{{ $offer->applyurl }}" class="btn btn-sm btn-primary align-self-center apply-now-btn">Apply Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($isExpired)
                        <div class="overlay">
                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 10.0546V8C5.25 4.27208 8.27208 1.25 12 1.25C15.7279 1.25 18.75 4.27208 18.75 8V10.0546C19.8648 10.1379 20.5907 10.348 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.40931 10.348 4.13525 10.1379 5.25 10.0546ZM6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C16.867 10 16.4515 10 16 10H8C7.54849 10 7.13301 10 6.75 10.0036V8ZM8 17C8.55228 17 9 16.5523 9 16C9 15.4477 8.55228 15 8 15C7.44772 15 7 15.4477 7 16C7 16.5523 7.44772 17 8 17ZM12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17ZM17 16C17 16.5523 16.5523 17 16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16Z" fill="#1C274C"></path>
                            </svg>
                        </div>
                    </div>    
                    @endif
                </div>
                @endforeach
            </div>
            
            <div class="row g-8 mb-5">
                <div class="col-12">
                    <p><strong>Ready with the following documents: <br/>Salaried - </strong>PAN Card, Aadhaar Card, Salary Slip, Bank Statements; <strong>Self Employeed - </strong>PAN Card, Aadhaar Card, IT Return, Bank Statements</p>
                    <p><strong>Disclaimer:</strong> Pre-Approved Offers are solely tentative and are shown on the basis of the information shared by the user – the final loan approval, sanction, and disbursement depend on the user profile and the NBFCs’ rules and regulations.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- modal to redirect starts -->
    <div class="modal fade" id="kt_modal_view_users" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content bg-dark">
                <!--begin::Modal body-->
                <div class="modal-body mx-5 mx-xl-18 pt-10 pb-10">
                    <div class="text-center mb-10">
                        <h1 class="text-white mb-3 mt-15 fs-1">Your pre-approved loan offers are almost ready!</h1>
                        <div class="text-muted fw-semibold fs-5">
                            We’re processing your personalized loan details.<br/>
                            Redirecting in <span id="countdown">3</span> seconds...
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
        $(".apply-now-btn").on('click', function(){
            var status = document.activeElement.innerHTML;
            event.preventDefault();

            if (status) {
                let applyId = $(this).data('id');
                let applyUrl = $(this).data('url');
                
                let countdownElement = document.getElementById('countdown');
                let countdown = 3;
                
                let data = new FormData();
                data.append('apply_id', applyId);
                data.append('apply_url', applyUrl);
                
                //let win = window.open('', '_blank');
                
                $.ajax({
                    url: `{{ route('customer.offers.apply.now') }}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $('#kt_modal_view_users').modal('show');
                        $('#kt_modal_view_users').modal({backdrop: 'static', keyboard: false});
                    },
                    success: function (result) {
                        $(this).attr("disabled", false);
                        
                        if (result.type === 'SUCCESS') {
                            const interval = setInterval(function() {
                                countdownElement.textContent = countdown;
                                countdown--;
                        
                                if (countdown < 0) {
                                    clearInterval(interval);
                                    window.open(applyUrl, '_blank');
                                    $('#kt_modal_view_users').modal('hide');
                                }
                            }, 1000);
                            //win.location.href = applyUrl;
                        } else {
                            toastr.error(result.message);
                            $('#kt_modal_view_users').modal('hide');
                        }
                    },
                    error: function (error) {
                        $(this).attr("disabled", false);
                        let errors = error.responseJSON.errors, errorsHtml = '';
                        $.each(errors, function (key, value) {
                            errorsHtml = '<strong>' + value[0] + '</strong>';
                            $('.' + key).html(errorsHtml);
                        });
                        $('#kt_modal_view_users').modal('hide');
                    }
                });
            }
        })
    })
</script>
@endpush
