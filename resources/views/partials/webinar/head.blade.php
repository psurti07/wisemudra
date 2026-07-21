<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large" />
  <link rel="canonical" href="{{ url()->current() }}">
  <meta name="description" content="{{ $meta['description'] }}">
  <meta name="keywords" content="{{ $meta['keywords'] }}">
  <meta name="author" content="{{ env('APP_NAME') }}">
  <meta property="og:title" content="{{ $meta['title'] }}" />
  <meta property="og:description" content="{{ $meta['description'] }}" />
  <meta property="og:image" content="{{ asset('front/webinar/images/webinarpage/Suyash-photo.png') }}" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{{ $meta['title'] }}" />
  <meta name="twitter:description" content="{{ $meta['description'] }}" />
  <meta name="twitter:site" content="{{ '@'.env('APP_NAME') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $meta['title'] }}</title>
  <link rel="stylesheet" href="{{ asset('front/webinar/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('front/webinar/css/bootstrap.min.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('front/webinar/css/swiper-bundle.min.css') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('front/webinar/images/logo/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('front/webinar/images/logo/apple-touch-icon-16x16.png') }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('front/webinar/images/logo/favicon.ico') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css" />
  
  <meta name="facebook-domain-verification" content="{{ getWebinarFacebookDomainVerificationId() }}" />
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ getWebinarFacebookPixelKey() }}');
    fbq('track', 'PageView');
  </script>

</head>