<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="{{ $meta['robots'] }}" />
  <link rel="canonical" href="{{ url()->current() }}">
  <meta name="description" content="{{ $meta['description'] }}">
  <meta name="keywords" content="{{ $meta['keywords'] }}">
  <meta name="author" content="{{ config('constant.APP_NAME') }}">

  <meta property="og:title" content="{{ $meta['title'] }}" />
  <meta property="og:description" content="{{ $meta['description'] }}" />
  <meta property="og:image" content="{{ asset('front/images/favicon-32x32.png') }}" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:site_name" content="{{ config('constant.APP_NAME') }}" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{{ $meta['title'] }}" />
  <meta name="twitter:description" content="{{ $meta['description'] }}" />
  <meta name="twitter:site" content="{{ '@'.config('constant.APP_NAME') }}" />

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">

  <!-- SITE TITLE -->
  <title>{{ $meta['title'] }}</title>

  <!-- FAVICON AND TOUCH ICONS -->
  <link rel="shortcut icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
  <link rel="icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('front/images/logo/apple-touch-icon-152x152.png') }}" />
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('front/images/logo/apple-touch-icon-120x120.png') }}" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('front/images/logo/apple-touch-icon-76x76.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('front/images/logo/apple-touch-icon-60x60.png') }}" />
  <link rel="icon" href="{{ asset('front/images/logo/main-favicon-180x180.png') }}" type="image/x-icon" />
  
  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
  @include('stacks.css.selfapply.style')
  <style>
    .register-page-form .form-control {
      margin-bottom: 0px !important;
    }
  </style>
</head>

<body>
  <!-- PAGE CONTENT -->
   <div id="page" class="page font--jakarta">
			<div id="login" class="bg--fixed login-1 login-section division">
				<div class="container">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-6 col-lg-6 col-12">	
							<div class="register-page-form">
								<!-- TITLE -->
								<div class="col-md-12">
									<div class="register-form-title">
                    <img src="{{ asset('front/images/logo/logo.png') }}" width="200" alt="{{ config('constant.APP_NAME') }}">
										<h4 class="mt-20 w-700">Log in to Portal</h4>
									</div>
								</div>

								<!-- LOGIN FORM -->
								 <form name="signinform" class="row sign-in-form auth-form g-3" action="{{ route('customer.authenticate') }}" method="post">
                    <!-- Form Input -->
                    <div class="col-md-12">
                      <p class="p-sm input-header">Mobile Number</p>
                      <input class="form-control numeric-input" type="text" name="mobile" placeholder="Mobile Number" id="mobile" maxlength="10" minlength="10" autocomplete="off" inputmode="numeric">
                      @component('components.ajax-error',['field'=>'mobile'])@endcomponent
                    </div>

                    <!-- Form Input -->
                    <div class="col-md-12">
                      <p class="p-sm input-header">Password</p>
                      <div class="wrap-input">
                        <span class="btn-show-pass ico-20"><span class="flaticon-visibility eye-pass"></span></span>
                        <input class="form-control password" type="password" name="password" id="password" placeholder="* * * * * * * * *">
                        @component('components.ajax-error',['field'=>'password'])@endcomponent
                      </div>
                    </div>

                    <!-- Reset Password Link -->
                    <div class="col-md-12">
                      <div class="reset-password-link text-end">
                        <p class="p-sm"><a href="{{ route('customer.forget.password') }}" class="color--theme">Forgot your password?</a></p>
                      </div>
                    </div>
                    <!-- Form Submit Button -->
                    <div class="col-md-12">
                      <button type="submit" class="btn btn--theme hover--theme submit btn-login mt-0 rounded-pill">Log In</button>
                    </div>
                    <!-- Sign Up Link -->
                    <div class="col-md-12">
                      <p class="create-account text-center mt-0">
                        Don't have an account? <a href="{{ route('self.apply.main') }}" class="color--theme">Sign up</a>
                      </p>
                    </div>
								</form>	<!-- END LOGIN FORM -->
							</div>	
						</div>	

            <!-- <div class="col-md-6 col-lg-6 col-12">
              <div class="d-flex justify-content-center align-items-start">
                  <div class="img-block">
                      <img src="{{ asset('front/images/Login-page-1.png') }}" alt="login now" class="img-fluid">
                  </div>
              </div>
            </div> -->
			 		</div>	  <!-- End row -->	
			 	</div> <!-- End container -->		
			</div>	<!-- END LOGIN PAGE -->
	 </div>

  <script src="{{ asset('front/js/jquery-3.7.0.min.js') }}"></script>
  <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('front/js/menu.js') }}"></script>
  <script src="{{ asset('front/js/jquery.easing.js') }}"></script>
  <script src="{{ asset('front/js/jquery.appear.js') }}"></script>
  <script src="{{ asset('front/js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('front/js/jquery.ajaxchimp.min.js') }}"></script>
  <script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('front/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('front/js/lunar.js') }}"></script>
  <script src="{{ asset('front/js/wow.js') }}"></script>
  <script src="{{ asset('front/js/custom.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
  <script>
    $('.auth-form').submit(function(event) {
      var status = document.activeElement.innerHTML;
      event.preventDefault();
      if (status) {
        $('.ajax-error').html('');
        var data = new FormData(this);
        $.ajax({
          url: $(this).attr("action"),
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          data: data,
          processData: false,
          contentType: false,
          beforeSend: function() {
            $('.btn-login').html('<span class="spinner-border spinner-border-sm"></span> Log In ');
            $('.btn-login').attr('disabled', true);
          },
          success: function(result) {
            $(this).attr("disabled", false);
            if (result.type === 'SUCCESS') {
              window.location.href = result.redirect
            } else {
              toastr.error(result.message)
              $("#password").val('');
              $('.btn-login').html('Log In');
              $('.btn-login').attr('disabled', false);
            }
          },
          error: function(error) {
            $(this).attr("disabled", false);
            let errors = error.responseJSON.errors,
              errorsHtml = '';
            $.each(errors, function(key, value) {
              errorsHtml = '<strong>' + value[0] + '</strong>';
              $('.' + key).html(errorsHtml);
            });
            $('.btn-login').html('Log In');
            $('.btn-login').attr('disabled', false);
          }
        });
      }
    });
  </script>
</body>
</html>