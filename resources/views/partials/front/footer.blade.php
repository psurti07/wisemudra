<footer id="footer-3" class="pt-80 footer">
    <div class="container">
        <!-- FOOTER CONTENT -->
        <div class="row">
            <!-- FOOTER LOGO -->
            <div class="col-xl-4 mb-sm-20 mb-md-20">
                <div class="footer-info">
                    <img class="footer-logo" src="{{ asset('front/images/logo/logo-w.png') }}" alt="{{ config('constant.APP_NAME') }}" />
                    <div class="fs-6 mt-3 mb-5 text-light">
                        <p>Wisemudra is India's thriving financial consultation and service provider that streamlines the loan process through its NBFC partners, giving you the option to apply on your own using the self-apply feature or hire a loan agent to make better borrowing decisions.</p>
                    </div>
                    <ul class="footer-socials mt-4 ico-25 text-center clearfix">
                        <li>
                            <a href="{{config('constant.SM_FACEBOOK')}}" target="_blank"><span class="flaticon-facebook text-light"></span></a>
                        </li>
                        <li>
                            <a href="{{config('constant.SM_INSTAGRAM')}}" target="_blank"><span class="flaticon-instagram text-light"></span></a>
                        </li>
                        <li>
                            <a href="{{config('constant.SM_YOUTUBE')}}" target="_blank"><span class="flaticon-youtube text-light"></span></a>
                        </li>
                        <li>
                            <a href="{{config('constant.SM_TWITTER')}}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" class="flaticon-twitter" x="0px" y="0px" viewBox="0 0 50 50" style="fill:#ffffff;height:1.39rem;top:-5px; position: relative;">
                                    <path d="M 5.9199219 6 L 20.582031 27.375 L 6.2304688 44 L 9.4101562 44 L 21.986328 29.421875 L 31.986328 44 L 44 44 L 28.681641 21.669922 L 42.199219 6 L 39.029297 6 L 27.275391 19.617188 L 17.933594 6 L 5.9199219 6 z M 9.7167969 8 L 16.880859 8 L 40.203125 42 L 33.039062 42 L 9.7167969 8 z"></path>
                                </svg></a>
                        </li>
                        <li>
                            <a href="{{config('constant.SM_PINTEREST')}}" target="_blank"><span class="flaticon-pinterest-logo text-light"></span></a>
                        </li>
                        <li>
                            <a href="{{config('constant.SM_LINKEDIN')}}" target="_blank"><span class="flaticon-linkedin-logo text-light"></span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- FOOTER LINKS -->
            <div class="col-sm-6 col-md-4 col-xl-2">
                <div class="footer-links fl-1">
                    <!-- Title -->
                    <h6 class="s-17 w-700 text-light">Company</h6>
                    <!-- Links -->
                    <ul class="foo-links clearfix">
                        <li>
                            <p><a href="{{ route('front.home') }}#company">Company</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.home') }}#products">Products</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.career') }}">Careers</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.faqs') }}">FAQs</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.home') }}#contact">Contact Us</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('webinar.index') }}" >Webinar</a></p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="footer-links fl-3">
                    <!-- Title -->
                    <h6 class="s-17 w-700 text-light">Legal</h6>
                    <!-- Links -->
                    <ul class="foo-links clearfix">
                        <li>
                            <p><a href="{{ route('front.raise.request') }}">Raise Request</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.privacy.policy') }}">Privacy Policy</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.disclaimer') }}">Disclaimer</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.refund.policy') }}">Cancellation &amp; Refund Policy</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('front.terms.conditions') }}">Terms &amp; Conditions</a></p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="footer-links fl-4">
                    <!-- Title -->
                    <h6 class="s-17 w-700 text-light">Connect With Us</h6>
                    <!-- Mail Link -->
                    <h6 class="s-14 w-700 kbz-h6 text-light" style="margin-bottom:5px!important;">Email Us</h6>
                    <p class="footer-mail-link ico-25">
                        <a href="mailto:{{ config('constant.INFO_EMAIL') }}" class="text-light">{{ config('constant.INFO_EMAIL') }}</a>
                    </p>
                    <h6 class="s-14 w-700 mt-15 kbz-h6 text-light" style="margin-bottom:5px!important;">Call Us</h6>
                    <p class="footer-mail-link ico-25">
                        <a href="tel:{{str_ireplace(' ','',config('constant.COMPANY_MOBILE'))}}" class="text-light">{{ config('constant.COMPANY_MOBILE') }}</a>
                    </p>
                    <h6 class="s-14 w-700 mt-15 kbz-h6 text-light" style="margin-bottom:5px!important;">Address</h6>
                    <p class="footer-mail-link ico-25">
                        <a href="javascript:;" class="text-light">{{ config('constant.COMPANY_ADDRESS') }}</a>
                    </p>
                </div>
            </div>
            <!-- END FOOTER LINKS -->
        </div>
        <!-- END FOOTER CONTENT -->
        <hr class="text-light"/>
        <!-- BOTTOM FOOTER -->
        <div class="bottom-footer text-center">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="footer-copyright">
                        <p><span class="text-light">{{ date('Y') }} &copy; {{ config('constant.COMPANY_NAME') }} All Rights Reserved.</span></p>
                    </div>
                </div>
                <!-- FOOTER SECONDARY LINK -->
            </div>
            <!-- End row -->
        </div>
        <!-- END BOTTOM FOOTER -->
    </div>
    <!-- End container -->
</footer>
