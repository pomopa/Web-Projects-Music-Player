<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - Your Music Companion</title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="<?= site_url('/assets/css/nucleo-icons.css') ?>" rel="stylesheet" />
    <link href="<?= site_url('/assets/css/nucleo-svg.css') ?>" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= site_url('/assets/css/material-dashboard.css?v=3.1.0') ?>" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
</head>
<body class="bg-dark">
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black position-sticky top-0" style="z-index: 1000;">
    <div class="container">
        <a class="navbar-brand text-success fw-bold fs-4" style="margin: 0px !important;" href="/home">LSpoty</a>

        <div class="d-flex align-items-center ms-auto gap-2">
            <a href="/my-playlists" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white me-2" style="margin: 0 !important;">
                <i class="fa fa-music"></i>
            </a>
            <a href="/profile" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white me-2" style="margin: 0 5px 0 5px !important;">
                <i class="fa fa-user-circle"></i>
            </a>
            <form action="/sign-out" method="POST" class="d-inline" style="margin: 0 !important;">
                <button type="submit" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white" style="margin: 0 !important;">
                    <i class="fa fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container">
    <!-- Search Bar -->
    <div class="row justify-content-center mt-4 mb-3">
        <div class="col-lg-8">
            <form id="searchForm" class="input-group bg-gray-800 rounded-pill">
                <input type="text" id="searchInput" name="query" placeholder="Search for tracks, albums, artists or playlists..." class="form-control border-0 bg-transparent text-white">
                <button type="submit" class="btn btn-link text-secondary" style="margin: 0 !important;">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <div class="mt-3 text-center">
                <div class="btn-group" role="group" aria-label="Search filters">
                    <button type="button" class="btn btn-outline-success active rounded-pill px-3 mx-1" style="margin: 0 5px 0 0 !important;" data-filter="tracks">Tracks</button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-3 mx-1" style="margin: 0 5px 0 0 !important;" data-filter="albums">Albums</button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-3 mx-1" style="margin: 0 5px 0 0 !important;" data-filter="artists">Artists</button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-3 mx-1" style="margin: 0 0 0 0 !important;" data-filter="playlists">Playlists</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Section (hidden by default) -->
    <div id="searchResults" class="mb-4 d-none">
        <h2 class="fw-bold fs-4 my-3 text-white text-center">Search Results</h2>
        <div class="row justify-content-center" id="resultsContainer">
            <div class="col-12 col-md-10 col-lg-8">
                <!-- Sample search results -->
                <div class="card card-plain bg-gray-800 mb-2">
                    <div class="card-body p-3 d-flex align-items-center">
                        <img src="/api/placeholder/60/60" class="rounded me-3" alt="Track cover">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0 text-white">Bohemian Rhapsody</h6>
                            <p class="card-text text-secondary mb-0 small">Queen · A Night at the Opera</p>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="Add to playlist">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card card-plain bg-gray-800 mb-2">
                    <div class="card-body p-3 d-flex align-items-center">
                        <img src="/api/placeholder/60/60" class="rounded me-3" alt="Track cover">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0 text-white">Yesterday</h6>
                            <p class="card-text text-secondary mb-0 small">The Beatles · Help!</p>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="Add to playlist">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Home Content -->
    <div id="homeContent">
        <!-- Recently Played -->
        <h2 class="fw-bold fs-4 my-3 text-white text-center">Recently Played</h2>
        <div class="row justify-content-center g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Album Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Song Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Album Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Tracks -->
        <h2 class="fw-bold fs-4 my-3 text-white text-center">Top Tracks</h2>
        <div class="row justify-content-center g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Track cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Track Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Track cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Track Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Track cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Track Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Track cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Track Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Track cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Track Title</h6>
                        <p class="card-text text-secondary text-truncate small">Artist Name</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Artists -->
        <h2 class="fw-bold fs-4 my-3 text-white text-center">Featured Artists</h2>
        <div class="row justify-content-center g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3 text-center">
                        <img src="/api/placeholder/400/400" alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                        <h6 class="card-title mb-1 text-truncate text-white">Artist Name</h6>
                        <p class="card-text text-secondary text-truncate small">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3 text-center">
                        <img src="/api/placeholder/400/400" alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                        <h6 class="card-title mb-1 text-truncate text-white">Artist Name</h6>
                        <p class="card-text text-secondary text-truncate small">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3 text-center">
                        <img src="/api/placeholder/400/400" alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                        <h6 class="card-title mb-1 text-truncate text-white">Artist Name</h6>
                        <p class="card-text text-secondary text-truncate small">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3 text-center">
                        <img src="/api/placeholder/400/400" alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                        <h6 class="card-title mb-1 text-truncate text-white">Artist Name</h6>
                        <p class="card-text text-secondary text-truncate small">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3 text-center">
                        <img src="/api/placeholder/400/400" alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                        <h6 class="card-title mb-1 text-truncate text-white">Artist Name</h6>
                        <p class="card-text text-secondary text-truncate small">Artist</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Playlists -->
        <h2 class="fw-bold fs-4 my-3 text-white text-center">Recommended Playlists</h2>
        <div class="row justify-content-center g-3 mb-4">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Playlist cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Playlist cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Playlist cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Playlist cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-plain bg-gray-800 h-100 transition-transform hover-elevation">
                    <div class="card-body p-3">
                        <img src="/api/placeholder/400/400" alt="Playlist cover" class="img-fluid rounded mb-2 w-100">
                        <h6 class="card-title mb-1 text-truncate text-white">Playlist Name</h6>
                        <p class="card-text text-secondary text-truncate small">By Creator</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-black text-center text-light py-3 mt-3">
    <div class="container">
        <p class="mb-0">© 2025 LSpoty - All rights reserved</p>
    </div>
</footer>

<!-- Core JS Files -->
<script src="<?= site_url('/assets/js/core/popper.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>

<!-- Custom Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle search form submission
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const homeContent = document.getElementById('homeContent');
        const filterButtons = document.querySelectorAll('[data-filter]');

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Only show search results if there's text in the search box
            if (searchInput.value.trim() !== '') {
                searchResults.classList.remove('d-none');
                homeContent.classList.add('d-none');

                // In a real app, you would fetch data from the API here
                console.log('Searching for:', searchInput.value);
            } else {
                searchResults.classList.add('d-none');
                homeContent.classList.remove('d-none');
            }
        });

        // Handle filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                // In a real app, you would fetch filtered data here
                console.log('Filter selected:', this.dataset.filter);

                // If there's a search query active, re-run the search with the new filter
                if (!searchResults.classList.contains('d-none')) {
                    console.log('Re-searching with filter:', this.dataset.filter);
                }
            });
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
</body>
</html>