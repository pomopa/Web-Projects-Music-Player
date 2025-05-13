document.addEventListener('DOMContentLoaded', function() {
    // Handle search form submission
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const homeContent = document.getElementById('homeContent');
    const filterButtons = document.querySelectorAll('[data-filter]');
    const categorySections = document.querySelectorAll('.category-section');

    // Track current active filter
    let currentFilter = 'tracks';

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
                                        <img src="${item.album_image || '/api/placeholder/60/60'}" class="rounded me-3" alt="Track cover" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">${item.artist_name} · ${item.album_name || 'Album'}</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="Add to playlist">
                                                <i class="fa fa-plus"></i>
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
                                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="View album">
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
                                        <img src="${item.image || '/api/placeholder/60/60'}" class="rounded-circle me-3" alt="Artist photo" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">Artist</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="View artist">
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
                                        <img src="/api/placeholder/60/60" class="rounded me-3" alt="Playlist cover" style="width: 60px; height: 60px;">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0 text-white">${item.name}</h6>
                                            <p class="card-text text-secondary mb-0 small">Playlist</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="View playlist">
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

                // Initialize tooltips for the new elements
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

            } else {
                // No results found
                resultsContainer.innerHTML = `
                    <div class="text-center text-white py-4">
                        <p>No ${category} found matching "${query}"</p>
                    </div>
                `;
            }

        } catch (error) {
            console.error('Error fetching search results:', error);
            resultsContainer.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>Something went wrong. Please try again later.</p>
                </div>
            `;
        }
    }

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
        }
    });

    // Global object to store all Swiper instances
    window.swiperInstances = {};

    // Function to update and reinitialize Swiper when changing categories
    const updateSwiper = (swiperEl) => {
        const swiperContainer = swiperEl;
        const swiperId = swiperContainer.classList[1]; // Get the class name that identifies this swiper

        // If this swiper instance already exists, update it
        if (window.swiperInstances[swiperId]) {
            window.swiperInstances[swiperId].update();
        }
    };

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

            // Find all Swiper containers in this section and update them
            const swiperContainers = sectionToShow.querySelectorAll('.swiper');
            swiperContainers.forEach(container => {
                // Use setTimeout to ensure the DOM has had time to render
                setTimeout(() => {
                    updateSwiper(container);
                }, 50);
            });
        } else {
            console.error(`Section with ID ${filter}Section not found`);
        }
    };

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

    // Initialize tooltips
    try {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    } catch (e) {
        console.error('Error initializing tooltips:', e);
    }

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

    // By default, show the tracks section (since 'tracks' button is active by default)
    showCategorySection('tracks');
});