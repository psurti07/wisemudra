@extends('layouts.front')
@push('css')
@endpush
@push('style-css')
@endpush
@section('content')
    <section id="privacy-page" class="gr--white pt-150 pb-100 division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="inner-page-title mb-20">
                    <h2 class="s-28">{{ $mainTitle }}</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! $description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="divider"/>
@endsection
@push('script-src')
@endpush
@push('scripts')
@endpush
