const swiper = new Swiper(".banner" ,{
    lopp: true,
    slidesPerView: 1,
    spaceBetween: 0,
    speed: 600, 
    autoplay: {
        delay: 5000,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});


const swiper2 = new Swiper(".category", {
    slidesPerView: 6,
    spaceBetween: 20,
    allowTouchMove: true,
    scrollbar: {
        el: '.swiper-scrollbar',
    },
    mousewheel: {
        forceToAxis: true,
        sensitivity: 1,
        releaseOnEdges: true,
    },
    on: {
        touchMove: function () {
            document.querySelector(".swiper-scrollbar").style.opacity = "1";
        },
        slideChange: function () {
            document.querySelector(".swiper-scrollbar").style.opacity = "1";
        },
        transitionEnd: function () {
            document.querySelector(".swiper-scrollbar").style.opacity = "0.4";
        }
    },
    freeMode: true,
    freeModeMomentum: true,  
    freeModeMomentumRatio: 0.5,  
    freeModeMomentumBounce: false, 
});
