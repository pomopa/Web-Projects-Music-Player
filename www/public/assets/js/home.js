document.addEventListener('DOMContentLoaded', function() {
    window.swiperInstances = {};

    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const homeContent = document.getElementById('homeContent');

    const filterButtons = document.querySelectorAll('[data-filter]');
    const categorySections = document.querySelectorAll('.category-section');

    let currentFilter = 'tracks';


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
            preventClicks: true,
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
                    const swiperId = this.el.classList[1];
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

    const refreshSwipers = () => {
        const visibleSection = document.querySelector('.category-section:not(.d-none)');
        if (!visibleSection) return;

        const swipers = visibleSection.querySelectorAll('.swiper');

        swipers.forEach(swiperEl => {
            const swiperId = swiperEl.classList[1];
            if (window.swiperInstances[swiperId]) {
                window.swiperInstances[swiperId].update();
            }
        });
    };


    async function performSearch(query, category) {
        try {
            resultsContainer.innerHTML = `<div class="text-center text-white"><p>${LANG.searching}</p></div>`;
            const response = await fetch(`/home/${category}/${query}`);

            if (!response.ok) {
                throw new Error('Error with the network.');
            }

            let data = await response.json();
            console.log(data);
            resultsContainer.innerHTML = '';

            data = data['results'];
            if (data && data.length > 0) {
                data.forEach(item => {
                    let html = '';

                    switch(category) {
                        case 'tracks':
                            html = `
                                <div class="card card-plain bg-gray-800 mb-2">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <img src="${item.album_image}" class="rounded me-3" alt="Track cover" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">${item.artist_name} · ${item.album_name || 'Album'}</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light view-btn" data-url="/track/${item.id}" data-bs-toggle="tooltip" title="View track">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            break;

                        case 'albums':
                            html = `
                                <div class="card card-plain bg-gray-800 mb-2">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <img src="${item.image || '/api/placeholder/60/60'}" class="rounded me-3" alt="Album cover" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">${item.artist_name}</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light view-btn" data-url="/album/${item.id}" data-bs-toggle="tooltip" title="View album">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            break;

                        case 'artists':
                            html = `
                                <div class="card card-plain bg-gray-800 mb-2">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <img src="${item.image || 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg'}" class="rounded-circle me-3" alt="Artist photo" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">Artist</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light view-btn" data-url="/artist/${item.id}" data-bs-toggle="tooltip" title="View artist">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            break;

                        case 'playlists':
                            html = `
                                <div class="card card-plain bg-gray-800 mb-2">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <img src="https://img.freepik.com/premium-psd/music-icon-user-interface-element-3d-render-illustration_516938-1693.jpg" class="rounded me-3" alt="Playlist cover" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">Playlist</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light view-btn" data-url="/playlist/${item.id}" data-bs-toggle="tooltip" title="View playlist">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            break;
                    }

                    resultsContainer.innerHTML += html;
                });

                initializeTooltips();

            } else {
                resultsContainer.innerHTML = `
                    <div class="text-center text-white py-4">
                        <p>${LANG.no_category1} ${category} ${LANG.no_category2} "${query}"</p>
                    </div>
                `;
            }

        } catch (error) {
            console.error('Error search results:', error);
            resultsContainer.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>${LANG.something_wrong}</div>
            `;
        }
        document.querySelectorAll('.view-btn').forEach(button => {
            button.style.cursor = 'pointer';
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    }


    const showCategorySection = (filter) => {
        categorySections.forEach(section => {
            section.classList.add('d-none');
        });

        currentFilter = filter;

        const sectionToShow = document.getElementById(`${filter}Section`);
        if (sectionToShow) {
            sectionToShow.classList.remove('d-none');

            setTimeout(refreshSwipers, 10);
        } else {
            console.error(`Section with ID ${filter} Section not found`);
        }
    };


    const initializeTooltips = () => {
        try {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        } catch (e) {
            console.error('Error initializing tooltips:', e);
        }
    };


    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const query = searchInput.value.trim();

        if (query !== '') {
            searchResults.classList.remove('d-none');
            homeContent.classList.add('d-none');

            const activeFilterBtn = document.querySelector('[data-filter].active');
            currentFilter = activeFilterBtn ? activeFilterBtn.dataset.filter : 'tracks';

            performSearch(query, currentFilter);
        } else {
            searchResults.classList.add('d-none');
            homeContent.classList.remove('d-none');
            showCategorySection(currentFilter);
        }
    });

    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            filterButtons.forEach(btn => btn.classList.remove('active'));

            this.classList.add('active');

            const selectedFilter = this.dataset.filter;
            currentFilter = selectedFilter;

            if (!searchResults.classList.contains('d-none') && searchInput.value.trim() !== '') {
                performSearch(searchInput.value.trim(), selectedFilter);
            } else {
                showCategorySection(selectedFilter);
            }

            return false;
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('clear-search')) {
            searchInput.value = '';
            searchResults.classList.add('d-none');
            homeContent.classList.remove('d-none');
            resultsContainer.innerHTML = '';
            showCategorySection(currentFilter);
        }
    });

    window.addEventListener('resize', refreshSwipers);

    document.querySelectorAll('.swiper .card[data-url]').forEach(card => {
        card.style.cursor = 'pointer'; // Cursor de mano
        card.addEventListener('click', () => {
            const url = card.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });

    initializeSwipers();

    initializeTooltips();

    showCategorySection('tracks');
});