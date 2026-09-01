@extends('layouts.front')
@push('css')
@endpush
@push('style-css')
@endpush
@section('content')
<section class="page-hero-section">
    <div class="page-hero-section-overlay bg--blue-100 bg--scroll">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-12 text-center">
                    <div class="txt-block left-column border-0">
                        <span class="section-id"></span>
                        <h2 class="s-28">{{ $mainTitle }}</h2>
                        <!-- <p class="p-md w-400">
                            Have a query? Raise a request with Arrow Capital and get quick assistance for all your loan needs.
                        </p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="privacy-page" class="gr--white py-80 division">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="border p-4 r-12">
                    {!! $description !!}
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