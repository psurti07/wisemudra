@extends('layouts.workshop')
@push('css')
<link rel="stylesheet" href="{{ asset('front/css/radiocards.css') }}">
<style>
    .accordion-button {
        background-color: transparent !important;
    }

    .accordion-button:focus {
        box-shadow: none !important;
    }

    .txt-block h2 {
        margin-bottom: 0px !important;
    }

    .cbox-1.ico-15 span {
        top: 5px !important;
    }

    a#failed-btn {
        background: #dc3545;
        border: 1px solid #dc3545;
    }

    a#failed-btn:hover {
        background: #bb2d3b !important;
        color: #fff !important;
    }

    .bc-5-img.bc-5-tablet.img-block-hidden {
        margin-bottom: -60px !important;
    }

    .bc-5-img.bc-5-tablet.img-block-hidden .video-btn {
        top: calc(70% - 70px);
    }

    @media only screen and (max-width: 767px) {
        .bc-5-img.bc-5-tablet.img-block-hidden {
            margin-bottom: 50px !important;
        }
    }

    .card:hover .radio:checked {
        border-color: transparent !important;
    }

    .more-questions-txt {
        padding: 0px 20px !important;
    }

    .recommended-txt {
        background: linear-gradient(269deg, rgb(241, 98, 98) -10.35%, rgb(116, 61, 220) 106.34%) text !important;
        font-weight: 600 !important;
        color: transparent !important;
    }

    .owl-dots {
        display: none !important;
    }

    .workshop-section {
        min-height: 95vh;
    }
</style>
@endpush
@section('content')

<section id="contacts" class="bg--green-100 pt-140 contacts-section workshop-section division">
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <div class="txt-block left-column p-3 bg-white rounded-2">
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">1</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Fill Basic Details</p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step border-right">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico text-white bg--green-500">2</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Confirm Your Registration </p>
                                    </div>
                                </div>
                                <div class="cbox-12 process-step">
                                    <div class="ico-wrap">
                                        <div class="cbox-12-ico border-dark-subtle">3</div>
                                    </div>
                                    <div class="cbox-12-txt">
                                        <p class="s-11">Get Webinar Access </p>
                                    </div>
                                </div>

                                <hr />
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item bg-transparent">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button" id="userDetailsBtn" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                User Details
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Name : </div>
                                                    <div class="col-lg-6 s-14">{{ session('firstname') }} {{ session('lastname') }}</div>
                                                    <hr class="custm-HR" />
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 s-13 accordion-row-coll">Mobile :</div>
                                                    <div class="col-lg-6 s-14">{{ session('mobile_no') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-12 mb-lg-0 mb-3">
                                    <div class="card">
                                        <div class="sub-blog-card bg-white mb-20 shadow-none rounded-top-2">
                                            <div class="img img-cover">
                                                @php
                                                // Get webinar or use default values
                                                $displayWebinar = $webinar ?? null;
                                                $isDefault = false;
                                                if (!$displayWebinar) {
                                                $isDefault = true;
                                                // Get last date of current month
                                                $lastDateOfMonth = \Carbon\Carbon::now()->endOfMonth();
                                                }
                                                @endphp
                                                @if($displayWebinar && $displayWebinar->event_image)
                                                <div class="article-thumb image-wrap">
                                                    <img class="web-image w-100 shadow-none rounded-top-2" src="{{ env('PATH_ADMIN_PANEL_IMAGE') . $displayWebinar->event_image }}" alt="images">
                                                </div>
                                                @else
                                                <img class="web-image w-100 shadow-none rounded-top-2" src="{{ asset('upload/webinar/1750402770.jpg') }}" alt="{{ $isDefault ? 'Upcoming Webinar' : ($displayWebinar->name ?? 'Webinar') }}" >
                                                @endif
                                            </div>
                                            <div class="info pt-2 ps-3">
                                                <h6 class="mb-20 fsz-22 text-dark">
                                                    @if($displayWebinar)
                                                    <h6>{{ $displayWebinar->event_title }}</h6>
                                                    @else
                                                    Become Online Fintech Agent & Earn 5x Revenue
                                                    @endif
                                                </h6>
                                            </div>
                                            <div class="ps-3">
                                                @if($displayWebinar)
                                                <p class="mb-10 color-666">
                                                    <li class="mb-2 d-flex align-items-center details-list text-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                            fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Mentor: {{ $displayWebinar->mentor_name }}
                                                    </li>
                                                </p>
                                                <p class="mb-10 color-666">
                                                    <li class="mb-2 d-flex align-items-center details-list text-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                            fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Date: {{ \Carbon\Carbon::parse($displayWebinar->event_datetime)->format('d F, Y') }}
                                                    </li>
                                                </p>
                                                <p class="mb-10 color-666">
                                                    <li class="mb-2 d-flex align-items-center details-list text-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                            fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Time: {{ $displayWebinar->event_datetime->format('h:i A') }}
                                                    </li>
                                                </p>
                                                <p class="mb-10 color-666">
                                                    <li class="mb-2 d-flex align-items-center details-list text-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                            fill="#0c6653" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Language: {{ $displayWebinar->language }}
                                                    </li>
                                                </p>
                                                @endif
                                            </div>
                                            <div class="ps-3 pt-0">
                                                <h3 class="text-content mt-1 text-dark">
                                                    <del class="fw-normal me-1 fs-5">
                                                        &#8377;{{ $displayWebinar->event_main_price }}
                                                    </del>
                                                    @if($displayWebinar->event_offer_price == 0)
                                                    <span>FREE</span>
                                                    @else
                                                    <span class="color--green-500">&#8377;{{ $displayWebinar->event_offer_price }}</span>
                                                    @endif
                                                </h3>
                                            </div>
                                            <div class="ps-3 pt-0">
                                                <div class="mb-10">
                                                    <span class="text-danger fsz-14">
                                                        <i class="far fa-clock"></i> Registration closing soon!
                                                    </span>
                                                </div>
                                            </div>

                                            @php
                                            if (!isset($webinar)) {
                                            $webinar = \App\Models\WebinarEvent::where('isActive', 1)
                                            ->where('isDelete', 0)
                                            ->orderBy('event_datetime', 'desc')
                                            ->first();
                                            }
                                            @endphp
                                            <div class="ps-3">
                                                <div class="text-start">
                                                    <button type="button" id="registerbtn" data-slug="{{ $displayWebinar?->id }}" class="s-12 btn btn--theme hover--theme submit">BOOK SLOT NOW</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-lg-6 col-12">
                                    <div class="card">
                                        <div class="row pt-4 px-3">
                                            <label class="">
                                                <input name="plan" value="1" class="radio" type="radio" checked data-plan="Self-Apply">
                                                <div class="plan-details">
                                                    <div class="more-questions-txt r-100 mb-10" style="background-color:#fbe9f6;width:190px">
                                                        <span class="s-12 recommended-txt">ONLY FEW SLOTS LEFT</span>
                                                    </div>
                                                    @php
                                                    // Price selection
                                                    $price = ($displayWebinar->event_offer_price == 0) ? 0 : ($displayWebinar->event_offer_price > 0 ? $displayWebinar->event_offer_price : ($displayWebinar->event_main_price ?? 299));

                                                    // GST (18%)
                                                    $gst = round($price * 0.18, 2);

                                                    // Total payable amount
                                                    $total = round($price + $gst, 2);
                                                    @endphp
                                                    <div class="p-3 bg-white">
                                                        <h4 class="mb-4">Order Summary</h4>

                                                        <div class="d-flex align-items-center mb-4">
                                                            <span>Price</span>
                                                            <div class="flex-grow-1 border-bottom mx-5"></div>
                                                            <span class="fw-medium">₹{{ number_format($price, 2) }}</span>
                                                        </div>

                                                        <div class="d-flex align-items-center mb-4">
                                                            <span>GST</span>
                                                            <div class="flex-grow-1 border-bottom mx-5"></div>
                                                            <span class="fw-medium">₹{{ number_format($gst, 2) }}</span>
                                                        </div>

                                                        <hr>

                                                        <div class="d-flex align-items-center">
                                                            <span class="fw-semibold">To Pay</span>
                                                            <div class="flex-grow-1 border-bottom mx-5"></div>
                                                            <span class="fw-bold">₹{{ number_format($total, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                            <div class="p-3">
                                                @if($displayWebinar && $displayWebinar->event_desc_1)
                                                {!! $displayWebinar->event_desc_1 !!}
                                                @else
                                                <p>Join our exclusive webinar hosted by <strong>Arvind Sir</strong>, a renowned expert in the field. This session will cover essential strategies and insights to help you grow your business.</p>
                                                <p><strong>Topics Covered:</strong></p>
                                                <ul class="mb-3">
                                                    <li>Business Growth Strategies</li>
                                                    <li>Market Analysis Techniques</li>
                                                    <li>Digital Marketing Essentials</li>
                                                    <li>Networking and Partnerships</li>
                                                </ul>
                                                <p>Don't miss this opportunity to learn from the best in the industry. Limited seats available!</p>
                                                @endif
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
</section>
<div id="result-container"></div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#registerbtn').on('click', function() {
            let id = $(this).data('slug');
            $('#registerLoader').removeClass('d-none');
            $('#registerbtn').attr('disabled', true);
            $.ajax({
                url: "{{ route('webinar.pay') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(result) {
                    if (result.type === 'SUCCESS') {
                        console.log($('#frm1').length);
                        toastr.success(result.message);
                        $('#result-container').html(result.html);
                        setTimeout(function() {
                            document.frm1.submit();
                        }, 500);
                        /*window.location.href = res.redirect;*/
                    } else if (result.type === 'FREE') {
                        toastr.success(result.message);
                        window.location.href = result.redirect_url;
                    } else if (result.type === 'ALREADY_REGISTERED') {
                        toastr.success(result.message);

                        setTimeout(function() {
                            window.location.href = result.redirect_url;
                        }, 2500);
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                },
                complete: function() {
                    $('#registerLoader').addClass('d-none');
                    $('#registerbtn').attr('disabled', false);
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {

        var accordionElement = document.getElementById('flush-collapseOne');
        var accordionButton = document.getElementById('userDetailsBtn');

        var accordion = new bootstrap.Collapse(accordionElement, {
            toggle: false
        });

        function updateAccordion() {
            if (window.innerWidth >= 768) {
                // Desktop -> Open
                accordion.show();
                accordionButton.classList.remove('collapsed');
                accordionButton.setAttribute('aria-expanded', 'true');
            } else {
                // Mobile -> Close
                accordion.hide();
                accordionButton.classList.add('collapsed');
                accordionButton.setAttribute('aria-expanded', 'false');
            }
        }

        updateAccordion();

        window.addEventListener('resize', updateAccordion);
    });
</script>

@endpush