// Añade este código dentro de tu event listener DOMContentLoaded existente
document.addEventListener('DOMContentLoaded', function() {
    // Código existente...

    // Inicializar el Swiper para las canciones recientes
    const recentTracksSwiper = new Swiper('.recentTracksSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        loop: true,
        loopFillGroupWithBlank: true,
        loopAdditionalSlides: 5,
        speed: 600,  // Velocidad de transición más lenta
        watchSlidesProgress: true,
        preventClicksPropagation: false,
        preventClicks: false,
        slideToClickedSlide: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                spaceBetween: 10
            },
            480: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 4,
                slidesPerGroup: 4,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 5,
                slidesPerGroup: 5,
                spaceBetween: 16
            },
            1200: {
                slidesPerView: 6,
                slidesPerGroup: 6,
                spaceBetween: 16
            }
        },
        on: {
            beforeInit: function() {
                // Asegurar que hay suficientes slides para el loop
                const swiper = this;
                if (swiper.slides && swiper.slides.length < 10) {
                    const slides = Array.from(swiper.el.querySelectorAll('.swiper-slide'));
                    if (slides.length > 0) {
                        slides.forEach(slide => {
                            swiper.wrapperEl.appendChild(slide.cloneNode(true));
                        });
                    }
                }
            }
        }
    });

    const topTracksSwiper = new Swiper('.topTracksSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        loop: true,
        loopFillGroupWithBlank: true,
        loopAdditionalSlides: 5,
        speed: 600,
        watchSlidesProgress: true,
        preventClicksPropagation: false,
        preventClicks: false,
        slideToClickedSlide: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                spaceBetween: 10
            },
            480: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 4,
                slidesPerGroup: 4,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 5,
                slidesPerGroup: 5,
                spaceBetween: 16
            },
            1200: {
                slidesPerView: 6,
                slidesPerGroup: 6,
                spaceBetween: 16
            }
        },
        on: {
            beforeInit: function() {
                const swiper = this;
                if (swiper.slides && swiper.slides.length < 10) {
                    // Si hay muy pocos slides, duplicamos el contenido antes de iniciar
                    const slides = Array.from(swiper.el.querySelectorAll('.swiper-slide'));
                    if (slides.length > 0) {
                        slides.forEach(slide => {
                            swiper.wrapperEl.appendChild(slide.cloneNode(true));
                        });
                    }
                }
            }
        }
    });

    const featuredArtistsSwiper = new Swiper('.featuredArtistsSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        loop: true,
        loopFillGroupWithBlank: true,
        loopAdditionalSlides: 5,
        speed: 600,
        watchSlidesProgress: true,
        preventClicksPropagation: false,
        preventClicks: false,
        slideToClickedSlide: true,
        navigation: {
            nextEl: '.featuredArtistsSwiper .swiper-button-next',
            prevEl: '.featuredArtistsSwiper .swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                spaceBetween: 10
            },
            480: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 4,
                slidesPerGroup: 4,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 5,
                slidesPerGroup: 5,
                spaceBetween: 16
            },
            1200: {
                slidesPerView: 6,
                slidesPerGroup: 6,
                spaceBetween: 16
            }
        },
        on: {
            beforeInit: function() {
                const swiper = this;
                if (swiper.slides && swiper.slides.length < 10) {
                    // Si hay muy pocos slides, duplicamos el contenido antes de iniciar
                    const slides = Array.from(swiper.el.querySelectorAll('.swiper-slide'));
                    if (slides.length > 0) {
                        slides.forEach(slide => {
                            swiper.wrapperEl.appendChild(slide.cloneNode(true));
                        });
                    }
                }
            }
        }
    });

    const featuredPlaylistSwiper = new Swiper('.featuredPlaylistSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        loop: true,
        loopFillGroupWithBlank: true,
        loopAdditionalSlides: 5,
        speed: 600,
        watchSlidesProgress: true,
        preventClicksPropagation: false,
        preventClicks: false,
        slideToClickedSlide: true,
        navigation: {
            nextEl: '.featuredArtistsSwiper .swiper-button-next',
            prevEl: '.featuredArtistsSwiper .swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                spaceBetween: 10
            },
            480: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 4,
                slidesPerGroup: 4,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 5,
                slidesPerGroup: 5,
                spaceBetween: 16
            },
            1200: {
                slidesPerView: 6,
                slidesPerGroup: 6,
                spaceBetween: 16
            }
        },
        on: {
            beforeInit: function() {
                const swiper = this;
                if (swiper.slides && swiper.slides.length < 10) {
                    // Si hay muy pocos slides, duplicamos el contenido antes de iniciar
                    const slides = Array.from(swiper.el.querySelectorAll('.swiper-slide'));
                    if (slides.length > 0) {
                        slides.forEach(slide => {
                            swiper.wrapperEl.appendChild(slide.cloneNode(true));
                        });
                    }
                }
            }
        }
    });
});