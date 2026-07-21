@extends('layouts.front')
@push('css')
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('style-css')
@endpush
@section('content')
    <section class="page-hero-section">
        <div class="page-hero-section-overlay bg--green-100 bg--scroll">
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-12 text-center">
                        <div class="txt-block left-column">
                            <span class="section-id"></span>
                            <h2 class="w-700">Career <span class="color--green-500">with us</span></h2>
                            <p class="p-md w-400">Growth. Fun. Excitement. Challenges – all in one place.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="join-our-team" class="pt-100 pb-60 careers-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9">
                    <div class="section-title mb-50">
                        <h2 class="">Current Job <span class="color--green-500">Vacancies</span></h2>
                        <p class="color--grey">See where you fit in.</p>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2">
                @foreach($data as $career)
                    <div class="col">
                        <div class="role-box hover--green-400 r-10">
                            <a href="{{ url('apply-career/'.$career['slug']) }}">
                                <span>Position : </span>
                                <h5 class="w-700">{{ $career['title'] }}</h5>
                                <p>Code : {{ $career['slug'] }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <hr class="divider">

    <!-- benefits and perks -->
    <section id="features-11" class="py-80 features-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9">
                    <div class="section-title mb-70">
                        <h2 class="s-28">Plenty of <span class="color--green-500">Perks!</span></h2>
                        <p class="s-16 color--grey">Become a part of a workforce that’s creating milestones. Every day!</p>
                    </div>
                </div>
            </div>
            <div class="fbox-wrapper">
                <div class="row row-cols-1 row-cols-md-2 rows-2 gx-2 gy-2">
                    <div class="col">
                        <div class="fbox-8">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico">
                                        <span class="flaticon-profits color--theme"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Competitive Salary</h6>
                                <p>We know your worth and we’ll make sure you’re paid what you deserve!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico">
                                        <span class="flaticon-workflow-2 color--theme"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Growth-Oriented Culture</h6>
                                <p>We’ll bring out the best in you so that your growth is nurtured impeccably.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico">
                                        <span class="flaticon-prioritize color--theme"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Building Leaders</h6>
                                <p>We love identifying the leader in you! And provide you with some awesome leadership opportunities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico">
                                        <span class="flaticon-money color--theme"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Appreciation &amp; Rewards</h6>
                                <p>Your persistence and hard work will always be acknowledged and rewarded!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico">
                                        <span class="flaticon-fast-food color--theme"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Positive Workplace</h6>
                                <p>We strongly believe in a non-discriminatory office environment, free of office politics.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fbox-8">
                            <div class="fbox-ico-wrap">
                                <div class="fbox-ico ico-50">
                                    <div class="shape-ico">
                                        <span class="flaticon-diamond color--theme"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fbox-txt">
                                <h6 class="s-18 w-700">Every Voice Matters</h6>
                                <p>We value every voice. No hierarchy barriers; all ideas and feedback are always welcome.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  
@endsection
@push('script-src')
@endpush
@push('scripts')
@endpush
