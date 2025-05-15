document.addEventListener('DOMContentLoaded', function() {
    window.swiperInstances = {};

    // Search elements
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const homeContent = document.getElementById('homeContent');

    // Navigation elements
    const filterButtons = document.querySelectorAll('[data-filter]');
    const categorySections = document.querySelectorAll('.category-section');

    // Track current active filter
    let currentFilter = 'tracks';


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
                    // Ensure there are enough slides for the loop
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
                window.swiperInstances[swiperId].update();
            }
        });
    };


    // Function to fetch search results
    async function performSearch(query, category) {
        try {
            // Show loading state
            resultsContainer.innerHTML = '<div class="text-center text-white"><p>Searching...</p></div>';

            // Fetch results from server
            const response = await fetch(`/home/${category}/${query}`);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            let data = await response.json();
            console.log(data);
            // Clear results container
            resultsContainer.innerHTML = '';

            data = data['results'];
            if (data && data.length > 0) {
                // Display results based on category
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
                // No results found
                resultsContainer.innerHTML = `
                    <div class="text-center text-white py-4">
                        <p>No ${category} found matching "${query}"</p>
                    </div>
                `;
            }

        } catch (error) {
            console.error('Error search results:', error);
            resultsContainer.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>Something went wrong. Please try again later.</p>
                </div>
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


    // Function to show the appropriate category section
    const showCategorySection = (filter) => {
        // Hide all category sections first
        categorySections.forEach(section => {
            section.classList.add('d-none');
        });

        // Update current filter
        currentFilter = filter;

        // Show the selected section
        const sectionToShow = document.getElementById(`${filter}Section`);
        if (sectionToShow) {
            sectionToShow.classList.remove('d-none');

            setTimeout(refreshSwipers, 10);
        } else {
            console.error(`Section with ID ${filter} Section not found`);
        }
    };


    // Function to initialize tooltips
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


    // Handle search form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const query = searchInput.value.trim();

        if (query !== '') {
            // Show search results section and hide home content
            searchResults.classList.remove('d-none');
            homeContent.classList.add('d-none');

            // Get currently active filter
            const activeFilterBtn = document.querySelector('[data-filter].active');
            currentFilter = activeFilterBtn ? activeFilterBtn.dataset.filter : 'tracks';

            // Perform search with current filter
            performSearch(query, currentFilter);
        } else {
            // If search box is empty, show home content
            searchResults.classList.add('d-none');
            homeContent.classList.remove('d-none');
            showCategorySection(currentFilter);
        }
    });

    // Handle filter buttons click
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Stop event propagation
            e.preventDefault();
            e.stopPropagation();

            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            const selectedFilter = this.dataset.filter;
            currentFilter = selectedFilter;

            // If there's a search query active, re-run the search with the new filter
            if (!searchResults.classList.contains('d-none') && searchInput.value.trim() !== '') {
                performSearch(searchInput.value.trim(), selectedFilter);
            } else {
                // Show the relevant content section based on filter
                showCategorySection(selectedFilter);
            }

            return false;
        });
    });

    // Clear search button functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('clear-search')) {
            searchInput.value = '';
            searchResults.classList.add('d-none');
            homeContent.classList.remove('d-none');
            resultsContainer.innerHTML = '';
            showCategorySection(currentFilter);
        }
    });

    // Handle window resize to refresh swipers
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

    // Initialize all swipers
    initializeSwipers();

    // Initialize tooltips
    initializeTooltips();

    // By default, show the tracks section (since 'tracks' button is active by default)
    showCategorySection('tracks');
});