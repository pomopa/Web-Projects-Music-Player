document.addEventListener('DOMContentLoaded', function() {
    // Store all Swiper instances in a global object for easier reference
    window.swiperInstances = {};

    // Configuration factory function to create consistent Swiper configs
    const createSwiperConfig = (navigationSelectors) => {
        return {
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
                nextEl: navigationSelectors.nextEl,
                prevEl: navigationSelectors.prevEl,
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
                },
                init: function() {
                    // Store this Swiper instance for later reference
                    const swiperId = this.el.classList[1]; // Get the class name that identifies this swiper
                    window.swiperInstances[swiperId] = this;
                }
            }
        };
    };

    // Initialize all swipers
    const initializeSwipers = () => {
        // Initialize Swiper for Tracks section
        window.swiperInstances.recentTracksSwiper = new Swiper('.recentTracksSwiper', createSwiperConfig({
            nextEl: '.recentTracksSwiper .swiper-button-next',
            prevEl: '.recentTracksSwiper .swiper-button-prev',
        }));

        window.swiperInstances.topTracksSwiper = new Swiper('.topTracksSwiper', createSwiperConfig({
            nextEl: '.topTracksSwiper .swiper-button-next',
            prevEl: '.topTracksSwiper .swiper-button-prev',
        }));

        // Initialize Swiper for Albums section
        window.swiperInstances.albumsSwiper = new Swiper('.albumsSwiper', createSwiperConfig({
            nextEl: '.albumsSwiper .swiper-button-next',
            prevEl: '.albumsSwiper .swiper-button-prev',
        }));

        window.swiperInstances.newReleasesSwiper = new Swiper('.newReleasesSwiper', createSwiperConfig({
            nextEl: '.newReleasesSwiper .swiper-button-next',
            prevEl: '.newReleasesSwiper .swiper-button-prev',
        }));

        // Initialize Swiper for Artists section
        window.swiperInstances.featuredArtistsSwiper = new Swiper('.featuredArtistsSwiper', createSwiperConfig({
            nextEl: '.featuredArtistsSwiper .swiper-button-next',
            prevEl: '.featuredArtistsSwiper .swiper-button-prev',
        }));

        window.swiperInstances.trendingArtistsSwiper = new Swiper('.trendingArtistsSwiper', createSwiperConfig({
            nextEl: '.trendingArtistsSwiper .swiper-button-next',
            prevEl: '.trendingArtistsSwiper .swiper-button-prev',
        }));

        // Initialize Swiper for Playlists section
        window.swiperInstances.featuredPlaylistSwiper = new Swiper('.featuredPlaylistSwiper', createSwiperConfig({
            nextEl: '.featuredPlaylistSwiper .swiper-button-next',
            prevEl: '.featuredPlaylistSwiper .swiper-button-prev',
        }));

        window.swiperInstances.popularPlaylistsSwiper = new Swiper('.popularPlaylistsSwiper', createSwiperConfig({
            nextEl: '.popularPlaylistsSwiper .swiper-button-next',
            prevEl: '.popularPlaylistsSwiper .swiper-button-prev',
        }));
    };

    // Function to refresh swipers when tab becomes visible
    const refreshSwipers = () => {
        // Get the currently visible tab
        const visibleSection = document.querySelector('.category-section:not(.d-none)');
        if (!visibleSection) return;

        // Find all swipers in the visible section
        const swipers = visibleSection.querySelectorAll('.swiper');

        // Update each swiper
        swipers.forEach(swiperEl => {
            const swiperId = swiperEl.classList[1];
            if (window.swiperInstances[swiperId]) {
                setTimeout(() => {
                    window.swiperInstances[swiperId].update();
                }, 50);
            }
        });
    };

    // Add event listeners to filter buttons to refresh swipers when tabs change
    const filterButtons = document.querySelectorAll('[data-filter]');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Use setTimeout to ensure DOM updates before refreshing swipers
            setTimeout(refreshSwipers, 100);
        });
    });

    // Initialize all swipers
    initializeSwipers();

    // Handle window resize to refresh swipers
    window.addEventListener('resize', refreshSwipers);
});