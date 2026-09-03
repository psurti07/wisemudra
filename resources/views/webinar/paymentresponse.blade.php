@extends('layouts.webinar')

@push('css')
@endpush

@section('content')
<section class="fullscreen">
  <div class="container px-3">
		<div class="row d-flex align-items-center justify-content-center">
			<!-- START : SUCCESS -->
            @if($responsedata == "true")
				<div class="col-lg-7 col-md-7 col-sm-7"> 
					<div class="card border-rounded border-success">
						<div class="card-body">
							<div class="m-t-30 m-b-30 text-center">
								<h1 class="icon pulse infinite text-success m-0" data-animate="pulse infinite"><i class="fa fa-check-circle"></i></h1>
								<h2 class="text-success">Thank You for Registering for the Webinar!</h2>
								<p class="small">Your registration and payment have been successfully completed. We look forward to seeing you!</p>
								<hr/>
								
								<a href="https://kbzp.in/KRDTBZ/srrju" class="btn btn-dark btn-sm m-t-10 text-uppercase">JOIN COMMUNITY</a>
								<a href="{{ route('webinar.index') }}" class="btn btn-dark btn-sm m-t-10 text-uppercase">BACK TO HOME</a>
								<br><br>
								<p>For any queries, please <span class="text-success">raise a request.</span></p>
							</div>
						</div>
					</div>
				</div>
			@endif
			<!-- END : SUCCESS -->

			<!-- START : FAIL -->
            @if($responsedata == "false")
				<div class="col-lg-7 col-md-7 col-sm-7">
					<div class="card border-rounded border-danger">
						<div class="card-body"> 
							<div class="m-t-30 m-b-30 text-center">
								<h1 class="icon pulse infinite text-danger m-0" data-animate="pulse infinite"><i class="fa fa-times-circle"></i></h1>
								<h2 class="text-danger">Payment Failed!</h2>
								<p>Unfortunately, your payment could not be completed.</p>
								<br>
								<h5>What can you do now?</h5>
								<br>
								<p>Don’t worry. You can retry the payment or contact our support team.</p>
								<hr>
								<a href="{{ route('webinar.index') }}" class="btn btn-dark btn-sm m-t-10 m-b-50 text-uppercase">BACK TO PROCESS</a>
								<br><br>
								<p>Need help? Contact: <span class="text-success"></span></p>
							</div>
						</div>
					</div>
				</div>
			@endif
			<!-- END : FAIL -->
		</div>
	</div>
</section>
@endsection
@push('scripts')

@endpush