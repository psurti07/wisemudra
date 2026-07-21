 
 
if ($(".slider-courses-7").length > 0) {
    var swiper = new Swiper(".slider-courses-7", {
        spaceBetween: 28.75,
        observer: true,
        observeParents: true,
        breakpoints: {
            0: {
                slidesPerView: 1.2,
                spaceBetween: 15,
            },
            700: {
                slidesPerView: 2,
            },
            1000: {
                slidesPerView: 3,
            },
            1440: {
                slidesPerView: 3,
            },
        },
        pagination: {
            el: ".pagination-courses1",
            clickable: true,
        },
        navigation: {
            clickable: true,
            nextEl: ".courses2-next",
            prevEl: ".courses2-prev",
        },
    });
}
 