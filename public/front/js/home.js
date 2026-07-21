$(document).ready(function() {
    var screenWidth = $(window).width();
    var showItems = 0;  // Number of items to display initially
    if (screenWidth >= 992) { // Large screens
        showItems = 6;
    } else if (screenWidth >= 768 && screenWidth < 992) { // Medium screens (tablet)
        showItems = 5;
    } else if (screenWidth >= 576 && screenWidth < 768) { // Medium screens (tablet)
        showItems = 4;
    } else { // Small screens (mobile)
        showItems = 2;
    }
    var totalItems = $('.company').length;  // Total number of items

    // Show the first 'showItems' companies initially
    $('.company').slice(0, showItems).addClass('visible');

    // On clicking "View More"
    $('#view-more').click(function() {
        $('.company:hidden').slice(0, totalItems).slideDown(400).addClass('visible');
        $('#view-more').addClass('d-none');
        $('#view-less').removeClass('d-none');
    });

    // On clicking "View Less"
    $('#view-less').click(function() {
        $('.company').slice(showItems).removeClass('visible').slideUp(400);
        $('#view-more').removeClass('d-none');
        $('#view-less').addClass('d-none');
    });
});

$(".testimonials-carousel").owlCarousel({
    items: 3, // Number of items
    loop: true,
    autoplay: true,
    navBy: 1,
    dots:false,
    autoplayTimeout: 4500,
    autoplayHoverPause: true,
    smartSpeed: 1500,
    responsive: {
        0: {
            items: 1 // Show 1 item on small screens
        },
        600: {
            items: 2 // Show 2 items on medium screens
        },
        1000: {
            items: 3 // Show 3 items on large screens
        }
    }
});
