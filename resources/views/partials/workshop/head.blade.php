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
  <meta property="og:image" content="{{ asset('front/images/favicon-32x32.png') }}" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{{ $meta['title'] }}" />
  <meta name="twitter:description" content="{{ $meta['description'] }}" />
  <meta name="twitter:site" content="{{ '@'.env('APP_NAME') }}" />
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
  @stack('css')
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11521462700"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'AW-11521462700');
  </script>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-EPQRGDEXT2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-EPQRGDEXT2');
  </script>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-TN4W7WVS');
  </script>
  <!-- End Google Tag Manager -->
  <!-- scripts schema markup start -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [{
          "@type": "Organization",
          "name": "Wisemudra",
          "url": "https://wisemudra.com",
          "logo": "https://wisemudra.com/front/images/logo/logo.png",
          "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer Service",
            "telephone": "+91-97242-06519",
            "email": "info@wisemudra.com"
          },
          "sameAs": [
            "https://www.facebook.com/wisemudraofficial/",
            "https://www.instagram.com/wisemudra/",
            "http://www.youtube.com/@Wisemudra",
            "https://twitter.com/Wisemudra",
            "https://in.pinterest.com/wisemudra/",
            "https://in.linkedin.com/company/wisemudra"
          ]
        },
        {
          "@type": "LocalBusiness",
          "name": "Wisemudra",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "3rd Floor, SY-104/2 SHOP-302, Henny Arcade, Dabholi Road",
            "addressLocality": "Surat",
            "addressRegion": "GJ",
            "postalCode": "395004",
            "addressCountry": "IN"
          },
          "telephone": "+91-97242-06519",
          "openingHours": "Mo-Sa 09:30-18:30",
          "image": "https://wisemudra.com/front/images/logo/logo.png",
          "url": "https://wisemudra.com",
          "priceRange": "$$$"
        },
        {
          "@type": "Service",
          "serviceType": "Personal Loan",
          "provider": {
            "@type": "Organization",
            "name": "Wisemudra"
          },
          "offers": {
            "@type": "Offer",
            "priceCurrency": "INR",
            "price": "1000",
            "availability": "https://schema.org/InStock",
            "priceValidUntil": "2026-12-31",
            "url": "https://wisemudra.com/loan-agent"
          }
        },
        {
          "@type": "Product",
          "name": "Personal Loan",
          "description": "A flexible personal loan with competitive interest rates.",
          "image": "https://wisemudra.com/front/images/logo/logo.png",
          "brand": {
            "@type": "Brand",
            "name": "Wisemudra"
          },
          "offers": {
            "@type": "Offer",
            "priceCurrency": "INR",
            "price": "499",
            "availability": "https://schema.org/InStock",
            "priceValidUntil": "2026-12-31",
            "url": "https://wisemudra.com/self-apply"
          },
          "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.5",
            "bestRating": "5",
            "worstRating": "0",
            "ratingCount": "79000"
          },
          "review": {
            "@type": "Review",
            "reviewBody": "Excellent service and competitive rates.",
            "datePublished": "2024-09-09",
            "author": {
              "@type": "Person",
              "name": "Mr. Vicky Jaiswal"
            },
            "publisher": {
              "@type": "Organization",
              "name": "Wisemudra"
            }
          }
        },
        {
          "@type": "FAQPage",
          "mainEntity": [{
              "@type": "Question",
              "name": "What is a personal loan?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "A personal loan is a type of loan where you borrow a set amount of money for personal use, such as home improvements, medical bills, or debt consolidation. You repay it over a fixed period with interest."
              }
            },
            {
              "@type": "Question",
              "name": "How can Wisemudra help me get a personal loan?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Wisemudra provides personalized financial consultation services. We assess your financial situation, compare various lenders, and find the best personal loan options with low-interest rates and flexible repayment terms."
              }
            },
            {
              "@type": "Question",
              "name": "What are the benefits of taking a personal loan through Wisemudra?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "By consulting Wisemudra, you get expert advice, access to multiple lenders, competitive interest rates, and a hassle-free loan application process. We help you find the most suitable personal loan that fits your needs."
              }
            },
            {
              "@type": "Question",
              "name": "What is the minimum credit score required for a personal loan?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "The minimum credit score requirement can vary by lender. At Wisemudra, we work with a range of lenders to find personal loans even if your credit score is low. We guide you on how to improve your credit score for better loan terms."
              }
            },
            {
              "@type": "Question",
              "name": "Can I apply for a personal loan with low income?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes, you can. Wisemudra partners with lenders who offer personal loans to individuals with varying income levels. We help you find a loan option that matches your income and repayment capability."
              }
            },
            {
              "@type": "Question",
              "name": "How long does it take to get a personal loan approved?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "The approval time for personal loans varies by lender. However, with Wisemudra's streamlined consultation process, we aim to get your loan approved as quickly as possible, often within a few days."
              }
            },
            {
              "@type": "Question",
              "name": "Are personal loan interest rates fixed or variable?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Personal loan interest rates can be either fixed or variable. At Wisemudra, we provide you with options from multiple lenders so you can choose between fixed or variable interest rates based on your financial preferences."
              }
            },
            {
              "@type": "Question",
              "name": "Can I pay off my personal loan early?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes, most personal loans allow early repayment. However, some lenders may charge a prepayment fee. Wisemudra will help you understand the terms and conditions of your loan, including early repayment options."
              }
            },
            {
              "@type": "Question",
              "name": "What documents are required for a personal loan?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Common documents include proof of identity, proof of income, bank statements, and credit score. Wisemudra will guide you through the documentation process and ensure that everything is in place for quick loan approval."
              }
            },
            {
              "@type": "Question",
              "name": "Why should I choose Wisemudra for personal loan consultation?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Wisemudra offers expert financial advice, compares multiple lenders, provides competitive interest rates, and simplifies the loan application process. Our goal is to help you secure the best personal loan with minimal stress."
              }
            }
          ]
        },
        {
          "@type": "BreadcrumbList",
          "itemListElement": [{
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "https://wisemudra.com"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Self Apply",
              "item": "https://wisemudra.com/self-apply"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "Hire an Agent",
              "item": "https://wisemudra.com/loan-agent"
            },
            {
              "@type": "ListItem",
              "position": 4,
              "name": "Great deal Offer",
              "item": "https://wisemudra.com/loan-agent/great-deal-offer"
            },
            {
              "@type": "ListItem",
              "position": 5,
              "name": "Prime Offer",
              "item": "https://wisemudra.com/self-apply/prime-offer"
            },
            {
              "@type": "ListItem",
              "position": 6,
              "name": "Silver Loan Offer",
              "item": "https://wisemudra.com/loan-agent/silver-offer"
            }
          ]
        }
      ]
    }
  </script>
  <script type="text/javascript">     (function(c,l,a,r,i,t,y){         c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};         t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;         y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);     })(window, document, "clarity", "script", "r1di8ee76n"); </script>
  <!-- scripts schema markup end -->
  
  <!-- Facebook Domain + Pixel Code -->
<meta name="facebook-domain-verification" content="{{ getWebinarFacebookDomainVerificationId() }}" /><script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '{{ getWebinarFacebookPixelKey() }}');
  fbq('track', 'PageView');
</script>
  @include('stacks.css.front.style')
</head>