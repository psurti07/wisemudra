<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Call Booked</title>
    <link rel="shortcut icon" href="{{ asset('front/images/logo/favicon.ico') }}" title="Favicon" sizes="16x16" />
    <link rel="icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('front/images/logo/apple-touch-icon-152x152.png') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('front/images/logo/apple-touch-icon-120x120.png') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('front/images/logo/apple-touch-icon-76x76.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('front/images/logo/apple-touch-icon-60x60.png') }}" />
    <link rel="icon" href="{{ asset('front/images/logo/main-favicon-180x180.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.1/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        .main {

            max-width: 420px;
            margin: auto;
            background: #fff;
            text-align: center;
        }

        body {

            font-family: "DM Sans", sans-serif;
        }

        .container {
            width: 100%;
            padding: 15px;
        }

        .top {
            margin-top: 35px;
        }

        .top h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .top p {
            font-size: 15px;
            line-height: 24px;
            color: #212529;
            margin-bottom: 15px;
        }

        .fa {
            margin-right: 5px;
        }


        .schedule {
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .schedule span {
            font-size: 16px;
        }

        .reschedule {
            padding: 10px 30px;
            border: 1px solid #aaa;
            background: white;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Slider */
        .swiper-container {
            width: 100%;
            margin: 40px 0 0 0;
            padding: 45px 0;
        }

        .swiper-slide {
            opacity: 0.4;
            overflow: hidden;
            transition: .7s;
        }

        .swiper-slide img {
            width: 100%;
        }

        .swiper-slide-active {
            opacity: 1;
            z-index: 1;
            transform: scale(1.5);
        }

        /* CTA */
        .cta p {
            margin: 20px 0;
            font-size: 14px;
        }

        .cta h3 {
            color: #0a8b4b;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .cta h3.cta-text {
            color: #212529;
            font-weight: 600;
            margin-bottom: 15px;
        }

        button.btn-submit {
            min-width: 275px;
            padding: 13px 16px;
            margin-top: 20px;
            background: #144835;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-transform: uppercase;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        button.btn-submit:hover {
            background-color: #336e59;
        }


        .swiper-pagination-clickable .swiper-pagination-bullet {
            margin-top: 75px;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #ccc;
            opacity: 1;
            border-radius: 10px;
            transition: all 0.3s;
            margin-right: 8px;
        }

        .swiper-pagination-bullet-active {
            width: 40px;
            background: #144835;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="container">

            <!-- Top Section -->
            <div class="top">
                <h1>Call Booked!</h1>
                <p>Your hair coach will call you on <br>
                    your registered number.</p>

                <div class="schedule">
                    <span><i class="fa fa-calendar-alt"></i> 21st April</span>
                    <span><i class="fa fa-clock"></i> 11:54 AM - 12:24 PM</span>
                </div>

                <a href="{{ $cancelURL }}"><button class="reschedule">Reschedule</button></a>
            </div>

            <!-- Slider Section -->
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="{{ asset('front/webinar/images/webinarpage/slider-img-1.png') }}" alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('front/webinar/images/webinarpage/slider-img-2.png') }}" alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('front/webinar/images/webinarpage/slider-img-3.png') }}" alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('front/webinar/images/webinarpage/slider-img-1.png') }}" alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('front/webinar/images/webinarpage/slider-img-2.png') }}" alt=""></div>
                    <div class="swiper-slide"><img src="{{ asset('front/webinar/images/webinarpage/slider-img-3.png') }}" alt=""></div>
                </div>
                <div class="slider__controls">
                    <div class="slider__pagination"></div>

                </div>
            </div>
            <!-- Bottom CTA -->
            <div class="cta">
                <h3>Trusted by 2L+ Men</h3>
                <h3 class="cta-text">for visible results – Your Turn Now!</h3>
            </div>

        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.1/swiper-bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            centeredSlides: true,
            loop: true,
            speed: 500,
            dots: true,
            slidesPerView: 2.5,
            spaceBetween: 60,
            autoplay: {
                delay: 3000,
            },
            pagination: {
                el: '.slider__pagination',
                clickable: true,
            },


            breakpoints: {

                375: {
                    slidesPerView: 2.5,

                },
                640: {
                    slidesPerView: 2.75,

                },
                768: {
                    slidesPerView: 2.75,

                },
                1080: {
                    slidesPerView: 3.25,

                },
                1280: {
                    slidesPerView: 2.25,

                },
            },
        });
    </script>
</body>

</html>