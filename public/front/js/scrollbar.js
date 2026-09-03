// public/js/scroll-bar.js
document.addEventListener('DOMContentLoaded', function() {
    const scrollBar = document.getElementById('scroll-bar');
    /*const scrollHeight = document.documentElement.scrollHeight;
    const innerHeight = window.innerHeight;
    console.log("inner Height - "+innerHeight);
    console.log("scroll Height - "+scrollHeight);*/
     function checkScroll() {
         const scrollPercentage = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
         /*console.log(Math.floor(scrollPercentage)+'%');
         console.log(window.innerHeight);
         console.log(window.scrollY);*/
         if (scrollPercentage > 20) {
             scrollBar.classList.add('visible');
         } else {
             scrollBar.classList.remove('visible');
         }
     }

    // Check scroll on page load
     checkScroll();

    // Check scroll on scroll event
    window.addEventListener('scroll', checkScroll);/*function () {*/
        /*const scrollHeight = document.documentElement.scrollHeight;
        console.log()
        const innerHeight = window.innerHeight;

        // Calculate only if scrollHeight is greater than innerHeight
        if (scrollHeight > innerHeight) {
            const scrollPercentage = (window.scrollY / (scrollHeight - innerHeight)) * 100;
            console.log(Math.floor(scrollPercentage) + '%');
        } else {
            console.log('0%'); // No scrollable content, so scroll percentage is 0
        }
    });*/

    // Check scroll on resize (for orientation changes)
     window.addEventListener('resize', checkScroll);

});

/*window.addEventListener('scroll', function() {
    var scrollPercentage = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
    var scrollingBar = document.getElementById('scrollingBar');

    if (scrollPercentage > 20) {
        scrollingBar.style.display = 'block';
        scrollingBar.style.opacity = '1';
    } else {
        scrollingBar.style.opacity = '0';
        setTimeout(function() {
            if (scrollPercentage <= 20) {
                scrollingBar.style.display = 'none';
            }
        }, 300); // Match this to the transition time in CSS
    }
});*/
