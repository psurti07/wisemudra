<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large" />
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
  <!-- BOOTSTRAP CSS -->
  <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet" />
  <!-- FONT ICONS -->
  <link href="{{ asset('front/css/flaticon.css') }}" rel="stylesheet" />
  <!-- PLUGINS STYLESHEET -->
  <link href="{{ asset('front/css/menu.css') }}" rel="stylesheet" />
  <link id="effect" href="{{ asset('front/css/dropdown-effects/fade-down.css') }}" media="all" rel="stylesheet" />
  <link href="{{ asset('front/css/owl.carousel.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('front/css/owl.theme.default.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('front/css/lunar.css') }}" rel="stylesheet" />
  <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet" />
  <link href="{{ asset('front/css/magnific-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('front/css/crocus-theme.css') }}" rel="stylesheet" />
  <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet" />
  <link href="{{ asset('front/css/scrollbar.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  @stack('css')
  
  <!-- Facebook Domain + Pixel Code -->
<meta name="facebook-domain-verification" content="{{ getFacebookDomainVerificationId() }}" /><script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '{{ getFacebookPixelKey() }}');
  fbq('track', 'PageView');
</script>
  @include('stacks.css.front.style')
</head>