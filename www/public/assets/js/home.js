// Añade este código dentro de tu event listener DOMContentLoaded existente
document.addEventListener('DOMContentLoaded', function() {
    // Código existente...

    // Inicializar el Swiper para las canciones recientes
    const recentTracksSwiper = new Swiper('.recentTracksSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            // Cuando la ventana es >= 320px
            320: {
                slidesPerView: 2,
                spaceBetween: 10
            },
            // Cuando la ventana es >= 480px
            480: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            // Cuando la ventana es >= 768px
            768: {
                slidesPerView: 4,
                spaceBetween: 15
            },
            // Cuando la ventana es >= 992px
            992: {
                slidesPerView: 5,
                spaceBetween: 16
            },
            // Cuando la ventana es >= 1200px
            1200: {
                slidesPerView: 6,
                spaceBetween: 16
            }
        }
    });

    const topTracksSwiper = new Swiper('.topTracksSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        navigation: {
            nextEl: '.topTracksSwiper .swiper-button-next',
            prevEl: '.topTracksSwiper .swiper-button-prev',
        },
        breakpoints: {
            // Cuando la ventana es >= 320px
            320: {
                slidesPerView: 2,
                spaceBetween: 10
            },
            // Cuando la ventana es >= 480px
            480: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            // Cuando la ventana es >= 768px
            768: {
                slidesPerView: 4,
                spaceBetween: 15
            },
            // Cuando la ventana es >= 992px
            992: {
                slidesPerView: 5,
                spaceBetween: 16
            },
            // Cuando la ventana es >= 1200px
            1200: {
                slidesPerView: 6,
                spaceBetween: 16
            }
        }
    });
});