@extends('layouts.webinar')

@push('css')
@endpush

@section('content')
<section class="register-section pt-0 pb-0 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mt-lg-0 mt-md-4 mt-5">
                <div class="content-left-from text-start">
                    <h4 class="mb-3 text-black fw-bold">{{ $eventdetails->event_title }}
                        <h3 class="text-content mt-1 text-dark">
                            <del class="fw-normal me-1 fs-6">
                                ₹{{ $eventdetails->event_main_price }}
                            </del>
                            @if($eventdetails->event_offer_price == 0)
                            <span>FREE</span>
                            @else
                            <span>{{ $eventdetails->event_offer_price }}</span>
                            @endif
                        </h3>
                        <div class="article-thumb image-wrap mt-3 mb-4">
                            <img class="web-image w-100" src="{{ env('PATH_ADMIN_PANEL_IMAGE') . $eventdetails->event_image }}" alt="images">
                        </div>
                        <div class="teachers-content mb-3">
                            <div class="d-none d-md-block d-lg-block mb-3">
                                <p class="mb-1"><strong>🚀 Program Highlights</strong></p>
                                {!! $eventdetails->event_desc_1 !!}
                            </div>
                            <h5 class="text-black">Webinar Details</h5>
                            <ul class="mt-3 ps-0 mb-4">
                                <li class="mb-2 d-flex align-items-center details-list text-black">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                        fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                    Mentor: {{ $eventdetails->mentor_name }}
                                </li>
                                <li class="mb-2 d-flex align-items-center details-list text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                        fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                    Language: {{ $eventdetails->language }}
                                </li>
                                <li class="mb-2 d-flex align-items-center details-list text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                        fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                    Date: {{ $eventdetails->event_datetime->format('jS F Y') }}
                                </li>
                                <li class="mb-2 d-flex align-items-center details-list text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                        fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                    Time: {{ $eventdetails->event_datetime->format('h:i A') }}
                                </li>
                            </ul>
                        </div>
                        <p class="title-bottom d-none d-md-block d-lg-block mb-0 text-black"><small>
                                You agree to share information entered
                                on this page with IndiaKarobar (owner of this page) and Razorpay, adhering to
                                applicable
                                laws.</small></p>
                        <ul class="d-flex d-none d-md-block d-lg-block"
                            style="padding-left:0px;list-style-type:none;margin-bottom:15px; line-height: 2;">
                            <li class="me-2">
                                <a href="{{ route('front.privacy.policy') }}" class="text-decoration-underline text-black">Privacy</a> &nbsp; |
                                &nbsp;
                                <a href="{{ route('front.terms.conditions') }}" class="text-decoration-underline text-black">Terms</a>
                            </li>
                            <li class="me-2 text-black">Wisemudra 2026</li>
                        </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 column-right">
                <div class="content-right h-100">
                    <h5 class="fw-bold">Billing Information</h5>
                    <div class="register">
                        <p class="mt-0">Complete your purchase by providing your payment details.</p>
                    </div>
                    <form id="webinarForm" method="POST" class="webinar-form">
                        @csrf
                        <input type="hidden" name="eid" value="{{ $eventdetails->id }}">
                        <input type="hidden" name="offer_price" value="{{ $eventdetails->event_offer_price }}">
                        <div class="checkout-billing p-lg-0 p-md-2 mb-3">
                            <div class="cols pt-3">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input id="firstname" type="text" name="first_name" class="form-control w-100 py-2" placeholder="First Name" />
                                        </div>
                                    </div>
                                </div>
                                @component('components.ajax-error',['field'=>'first_name'])@endcomponent
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input id="lastname" type="text" name="last_name" class="form-control w-100 py-2" placeholder="Last Name" />
                                        </div>
                                    </div>
                                </div>
                                @component('components.ajax-error',['field'=>'last_name'])@endcomponent
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input id="mobile_no" type="text" name="mobile_no" class="form-control w-100 py-2" minlength="10" maxlength="10" placeholder="Mobile No" />
                                        </div>
                                    </div>
                                </div>
                                @component('components.ajax-error',['field'=>'mobile_no'])@endcomponent
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input id="email" type="email" name="email" class="form-control w-100 py-2" placeholder="Email Id" />
                                        </div>
                                    </div>
                                </div>
                                @component('components.ajax-error',['field'=>'email'])@endcomponent
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input id="pincode" type="text" name="pincode" class="form-control w-100 py-2" placeholder="Pincode" maxlength="6" minlength="6" inputmode="numeric" />
                                        </div>
                                    </div>
                                </div>
                                <div id="loader" style="display:none;">
                                    Loading...
                                </div>
                                @component('components.ajax-error',['field'=>'pincode'])@endcomponent
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input id="city" type="text" name="city" class="form-control w-100 py-2" placeholder="City" />
                                        </div>
                                    </div>
                                </div>
                                @component('components.ajax-error',['field'=>'city'])@endcomponent
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <select id="state" name="state" class="form-select w-100 py-2">
                                                <option value="">State</option>
                                                {!! getStateOption(old('state')) !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @component('components.ajax-error',['field'=>'state'])@endcomponent
                            </div>
                        </div>
                        <div class="shop-checkout">
                            <div class="container p-0">
                                <div class="row">
                                    <div class="cols">
                                        <div class="sidebar-shop-checkout">
                                            <div class="sidebar-checkout-item your-order">
                                                <h2 class="title fw-bold"></h2>
                                                <ul class="product-list ps-0">
                                                    <li
                                                        class="product-item pb-0 d-flex justify-content-between">
                                                        <p class="fw-normal mb-0">SubTotal</p>
                                                        @if($eventdetails->event_offer_price > 0)
                                                        <p class="fw-normal mb-0" id="subtotal-amount">
                                                            {{ formatePriceIndia($eventdetails->event_offer_price) }}
                                                        </p>
                                                        @else
                                                        <p class="fw-normal mb-0" id="subtotal-amount">
                                                            FREE
                                                        </p>
                                                        @endif
                                                    </li>

                                                    @if($eventdetails->event_offer_price > 0)
                                                    @php
                                                    $event_price = $eventdetails->event_offer_price;
                                                    @endphp
                                                    <li class="product-item pb-0 d-flex justify-content-between cgst-row">
                                                        <p class="fw-normal">GST (18%)</p>
                                                        <p class="fw-normal" id="cgst-amount">
                                                            {{ formatePriceIndia($event_price * 0.18) }}
                                                        </p>
                                                    </li>
                                                    @endif
                                                </ul>

                                                @php
                                                $event_price = $eventdetails->event_offer_price ?? 0;
                                                $gst = $event_price * 0.18;
                                                $grandtotal = $event_price + $gst;
                                                @endphp

                                                <ul class="checkout-total-bill ps-0 mb-0">
                                                    <li class="total d-flex justify-content-between">
                                                        <p class="fw-normal mb-0"><strong>Total :</strong></p>
                                                        @if($event_price > 0)
                                                        <p class="fw-normal mb-0">
                                                            <strong id="total-amount">{{ formatePriceIndia($grandtotal) }}</strong>
                                                        </p>
                                                        @else
                                                        <p class="fw-normal mb-0"><strong id="total-amount">₹0.00</strong></p>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="btns mt-4 mb-2">
                                                <button type="submit"
                                                    class="butn py-2 m-0 lh-5 text-center w-100 rounded-3 bg-dark text-white">
                                                    Proceed to Pay
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')

<script>
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
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Pass CSRF token
                },
                success: function(response) {
                    $('#loader').hide(); // Hide loader
                    if (response.status === 'success') {
                        // Populate District and State fields
                        $('#city').val(response.district);
                        $('#state').val(response.state);
                    } else {
                        alert(response.message);
                        $('#district').val('');
                        $('#state').val('');
                    }
                },
                error: function() {
                    $('#loader').hide(); // Hide loader on error
                    alert('An error occurred while fetching the details.');
                }
            });
        } else {
            // Clear the fields if pincode length is not 6 digits
            $('#city').val('');
            $('#state').val('');
        }
    });

    $('#webinarForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $('.text-danger').html(''); // clear old errors

        $.ajax({
            url: "{{ route('webinar.checkout') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    window.location.href = response.redirect;
                } else if (response.status === 'exists') {
                    toastr.error(response.message);  
                }
            },
            error: function(xhr) {

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value) {

                        // Show error message
                        $('#' + key + '_error').html(value[0]);

                        // Highlight input field
                        $('[name="' + key + '"]').addClass('is-invalid');
                    });

                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });
</script>
@endpush