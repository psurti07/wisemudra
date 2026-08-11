// JavaScript Document

    /*$(document).ready(function(){
        $('.numeric-input').on('keydown', function(event) {
            if (!(event.key === 'Backspace' || event.key === 'Delete' || (event.key >= '0' && event.key <= '9'))) {
                event.preventDefault();
            }
        });
    });*/

	$(window).on('load', function() {

		"use strict";

		/*----------------------------------------------------*/
		/*	Preloader
		/*----------------------------------------------------*/

		var preloader = $('#loading'),
			loader = preloader.find('#loading-center');
			loader.fadeOut();
			preloader.delay(400).fadeOut('slow');

	});


	$(window).on('scroll', function() {

		"use strict";

		/*----------------------------------------------------*/
		/*	Navigtion Menu Scroll
		/*----------------------------------------------------*/

		var b = $(window).scrollTop();

		if( b > 80 ){
			$(".wsmainfull").addClass("scroll");
		} else {
			$(".wsmainfull").removeClass("scroll");
		}

	});


	$(document).ready(function() {

		"use strict";


		new WOW().init();


		/*----------------------------------------------------*/
		/*	Mobile Menu Toggle
		/*----------------------------------------------------*/

		if ( $(window).outerWidth() < 992 ) {
			$('.wsmenu-list li.nl-simple, .wsmegamenu li, .sub-menu li').on('click', function() {
				 $('body').removeClass("wsactive");
				 $('.sub-menu').slideUp('slow');
     			 $('.wsmegamenu').slideUp('slow');
     			 $('.wsmenu-click').removeClass("ws-activearrow");
        		 $('.wsmenu-click02 > i').removeClass("wsmenu-rotate");
			});
		}

		if ( $(window).outerWidth() < 992 ) {
			$('.wsanimated-arrow').on('click', function() {
				 $('.sub-menu').slideUp('slow');
     			 $('.wsmegamenu').slideUp('slow');
     			 $('.wsmenu-click').removeClass("ws-activearrow");
        		 $('.wsmenu-click02 > i').removeClass("wsmenu-rotate");
			});
		}


	    /*----------------------------------------------------*/
		/*	Accordion
		/*----------------------------------------------------*/

		$(".accordion > .accordion-item.is-active").children(".accordion-panel").slideDown();

		$(".accordion > .accordion-item").on('click', function() {
			$(this).siblings(".accordion-item").removeClass("is-active").children(".accordion-panel").slideUp();
			$(this).toggleClass("is-active").children(".accordion-panel").slideToggle("ease-out");
		});


		/*----------------------------------------------------*/
		/*	Tabs
		/*----------------------------------------------------*/

		$('ul.tabs-1 li').on('click', function(){
			var tab_id = $(this).attr('data-tab');

			$('ul.tabs-1 li').removeClass('current');
			$('.tab-content').removeClass('current');

			$(this).addClass('current');
			$("#"+tab_id).addClass('current');
		});


		/*----------------------------------------------------*/
		/*	Single Image Lightbox
		/*----------------------------------------------------*/

		$('.image-link').magnificPopup({
		  type: 'image'
		});


		/*----------------------------------------------------*/
		/*	Video Link #1 Lightbox
		/*----------------------------------------------------*/

		$('.video-popup1').magnificPopup({
		    type: 'iframe',
				iframe: {
					patterns: {
						youtube: {
							index: 'youtube.com/',
							id: function(url) {
                                // Extract YouTube video ID from the URL
                                var match = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
                                return match && match[1] ? match[1] : null;
                            },
							src: 'https://www.youtube.com/embed/zK0mQmQg30w?autoplay=1'
								}
							}
						}
		});


		/*----------------------------------------------------*/
		/*	Video Link #2 Lightbox
		/*----------------------------------------------------*/

		$('.video-popup2').magnificPopup({
		    type: 'iframe',
				iframe: {
					patterns: {
						youtube: {
							index: 'hire agent',
							src: 'https://www.youtube.com/embed/WD7bAJMFr5E?autoplay=1'
								}
							}
						}
		});


		/*----------------------------------------------------*/
		/*	Video Link #3 Lightbox
		/*----------------------------------------------------*/

		$('.video-popup3').magnificPopup({
		    type: 'iframe',
				iframe: {
					patterns: {
						youtube: {
							index: 'self apply',
							src: 'https://www.youtube.com/embed/HFA0zqwPBXg?autoplay=1'
								}
							}
						}
		});


		/*----------------------------------------------------*/
		/*	Statistic Counter
		/*----------------------------------------------------*/

		$('.count-element').each(function () {
			$(this).appear(function() {
				$(this).prop('Counter',0).animate({
					Counter: $(this).text()
				}, {
					duration: 4000,
					easing: 'swing',
					step: function (now) {
						$(this).text(Math.ceil(now));
					}
				});
			},{accX: 0, accY: 0});
		});


		/*----------------------------------------------------*/
		/*	Testimonials Rotator
		/*----------------------------------------------------*/

		var owl = $('.reviews-1-wrapper');
			owl.owlCarousel({
				items: 3,
				loop:true,
				autoplay:true,
				navBy: 1,
				autoplayTimeout: 4500,
				autoplayHoverPause: true,
				smartSpeed: 1500,
				responsive:{
					0:{
						items:1
					},
					767:{
						items:1
					},
					768:{
						items:2
					},
					991:{
						items:3
					},
					1000:{
						items:3
					}
				}
		});


		/*----------------------------------------------------*/
		/*	Brands Logo Rotator
		/*----------------------------------------------------*/

		var owl = $('.brands-carousel-5');
			owl.owlCarousel({
				items: 5,
				loop:true,
				autoplay:true,
				navBy: 1,
				nav:false,
				autoplayTimeout: 4000,
				autoplayHoverPause: false,
				smartSpeed: 2000,
				responsive:{
					0:{
						items:2
					},
					550:{
						items:3
					},
					767:{
						items:3
					},
					768:{
						items:6
					},
					991:{
						items:6
					},
					1000:{
						items:5
					}
				}
		});


		var owl = $('.brands-carousel-3');
			owl.owlCarousel({
				items: 4,
				loop:true,
				autoplay:true,
				navBy: 1,
				nav:false,
				autoplayTimeout: 4000,
				autoplayHoverPause: false,
				smartSpeed: 2000,
				margin:30,
				responsive:{
					0:{
						items:3
					},
					550:{
						items:3
					},
					767:{
						items:3
					},
					768:{
						items:4
					},
					991:{
						items:4
					},
					1000:{
						items:4
					}
				}
		});
		/*----------------------------------------------------*/
		/*	Brands Logo Rotator
		/*----------------------------------------------------*/

		var owl = $('.brands-carousel-6');
			owl.owlCarousel({
				items: 3,
				loop:true,
				autoplay:true,
				navBy: 1,
                dots: false,
				nav:false,
				autoplayTimeout: 4000,
				autoplayHoverPause: false,
				smartSpeed: 2000,
                margin: 20,
				responsive:{
					0:{
						items:2
					},
					550:{
						items:3
					},
					767:{
						items:4//3
					},
					768:{
						items:4//5
					},
					991:{
						items:6//5
					},
					1000:{
						items:6//5
					}
				}
		});


		/*----------------------------------------------------*/
		/*	Show Password
		/*----------------------------------------------------*/

	    var showPass = 0;
	    $('.btn-show-pass').on('click', function(){
	        if(showPass == 0) {
	            $(this).next('input').attr('type','text');
	            $(this).find('span.eye-pass').removeClass('flaticon-visibility');
	            $(this).find('span.eye-pass').addClass('flaticon-invisible');
	            showPass = 1;
	        }
	        else {
	            $(this).next('input').attr('type','password');
	            $(this).find('span.eye-pass').addClass('flaticon-visibility');
	            $(this).find('span.eye-pass').removeClass('flaticon-invisible');
	            showPass = 0;
	        }

	    });


		/*----------------------------------------------------*/
		/*	Newsletter Subscribe Form
		/*----------------------------------------------------*/

		$('.newsletter-form').ajaxChimp({
        language: 'cm',
        url: 'https://dsathemes.us3.list-manage.com/subscribe/post?u=af1a6c0b23340d7b339c085b4&id=344a494a6e'
            //http://xxx.xxx.list-manage.com/subscribe/post?u=xxx&id=xxx
		});

		$.ajaxChimp.translations.cm = {
			'submit': 'Submitting...',
			0: 'We have sent you a confirmation email',
			1: 'Please enter your email address',
			2: 'An email address must contain a single @',
			3: 'The domain portion of the email address is invalid (the portion after the @: )',
			4: 'The username portion of the email address is invalid (the portion before the @: )',
			5: 'This email address looks fake or invalid. Please enter a real email address'
		};


	});

document.addEventListener("DOMContentLoaded", function () {
    var otpInputs = document.querySelectorAll(".otp-input");
    function setupOtpInputListeners(inputs) {
        inputs.forEach(function (input, index) {
            input.addEventListener("paste", function (ev) {
                var clip = ev.clipboardData.getData('text').trim();
                if (!/^\d{4}$/.test(clip)) {
                    ev.preventDefault();
                    return;
                }

                var characters = clip.split("");
                inputs.forEach(function (otpInput, i) {
                    otpInput.value = characters[i] || "";
                });

                enableNextBox(inputs[0], 0);
                inputs[3].removeAttribute("disabled");
                inputs[3].focus();
                updateOTPValue(inputs);
            });

            input.addEventListener("input", function () {
                var currentIndex = Array.from(inputs).indexOf(this);
                var inputValue = this.value.trim();

                if (!/^\d$/.test(inputValue)) {
                    this.value = "";
                    return;
                }

                if (inputValue && currentIndex < 3) {
                    inputs[currentIndex + 1].removeAttribute("disabled");
                    inputs[currentIndex + 1].focus();
                }

                if (currentIndex === 2 && inputValue) {
                    inputs[3].removeAttribute("disabled");
                    inputs[3].focus();
                }

                updateOTPValue(inputs);
            });

            input.addEventListener("keydown", function (ev) {
                var currentIndex = Array.from(inputs).indexOf(this);

                if (!this.value && ev.key === "Backspace" && currentIndex > 0) {
                    inputs[currentIndex - 1].focus();
                }
            });
        });
    }

    function enableNextBox(input, currentIndex) {
        var inputValue = input.value;

        if (inputValue === "") {
            return;
        }

        var nextIndex = currentIndex + 1;
        var nextBox = otpInputs[nextIndex] || emailOtpInputs[nextIndex];

        if (nextBox) {
            nextBox.removeAttribute("disabled");
        }
    }

    function updateOTPValue(inputs) {
        var otpValue = "";

        inputs.forEach(function (input) {
            otpValue += input.value;
        });

        if (inputs === otpInputs) {
            document.getElementById("verificationCode").value = otpValue;
        } else if (inputs === emailOtpInputs) {
            document.getElementById("emailverificationCode").value = otpValue;
        }
    }

    setupOtpInputListeners(otpInputs);
    otpInputs[0].focus();
    otpInputs[3].addEventListener("input", function () {
        updateOTPValue(otpInputs);
    });
});

class Slider {
    constructor (rangeElement, valueElement, options) {
        this.rangeElement = rangeElement
        this.valueElement = valueElement
        this.options = options

        // Attach a listener to "change" event
        this.rangeElement.addEventListener('input', this.updateSlider.bind(this))
    }

    // Initialize the slider
    init() {
        this.rangeElement.setAttribute('min', options.min)
        this.rangeElement.setAttribute('max', options.max)
        this.rangeElement.value = options.cur

        this.updateSlider()
    }

    // Format the money
    asMoney(value) {
        $("#loanAmount").val(value);
        return '&#8377;' + parseFloat(value)
            .toLocaleString('en-IN', { maximumFractionDigits: 2 })
    }

    generateBackground(rangeElement) {
        if (this.rangeElement.value === this.options.min) {
            return
        }

        let percentage =  (this.rangeElement.value - this.options.min) / (this.options.max - this.options.min) * 100
        return 'background: linear-gradient(to right, #02a6fc, #04a7ff ' + percentage + '%, #b5e5ff ' + percentage + '%, #ecf8ff 100%)'
    }

    updateSlider (newValue) {
        this.valueElement.innerHTML = this.asMoney(this.rangeElement.value)
        this.rangeElement.style = this.generateBackground(this.rangeElement.value)
    }
}

let rangeElement = document.querySelector('.range [type="range"]')
let valueElement = document.querySelector('.range .range__value span')

let options = {
    min: 10000,
    max: 5000000,
    cur: 500000
}

if (rangeElement) {
    let slider = new Slider(rangeElement, valueElement, options)

    slider.init()
}

/* accept only numbers */
$('.numeric-input').on('keydown', function(event) {
	if (!(event.key === 'Backspace' || event.key === 'Delete' || (event.key >= '0' && event.key <= '9'))) {
		event.preventDefault();
	}
});
