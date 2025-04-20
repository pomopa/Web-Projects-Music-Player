<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - Your Music Companion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #1DB954;
            --dark-bg: #121212;
            --card-bg: #282828;
            --text-color: #FFFFFF;
            --text-secondary: #B3B3B3;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-color);
            font-family: 'Montserrat', sans-serif;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .search-container {
            background-color: var(--card-bg);
            border-radius: 30px;
            padding: 5px 20px;
            display: flex;
            align-items: center;
        }

        .search-container input {
            background: transparent;
            border: none;
            color: var(--text-color);
            outline: none;
            width: 100%;
            padding: 10px;
        }

        .search-container input::placeholder {
            color: var(--text-secondary);
        }

        .search-container .btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
        }

        .search-container .btn:hover {
            color: var(--primary-color);
        }

        .nav-btn {
            background-color: transparent;
            color: var(--text-color);
            border: none;
            margin-left: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .nav-btn:hover {
            background-color: var(--card-bg);
            color: var(--primary-color);
        }

        .nav-btn.profile-btn {
            width: auto;
            border-radius: 30px;
            padding: 5px 15px;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 30px 0 20px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s;
            height: 100%;
        }

        .card:hover {
            background-color: #3E3E3E;
            transform: translateY(-5px);
        }

        .card img {
            border-radius: 5px;
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        .card-title {
            font-weight: 600;
            font-size: 1rem;
            margin-top: 10px;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-text {
            color: var(--text-secondary);
            font-size: 0.8rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-filter-btn {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--text-secondary);
            border-radius: 20px;
            padding: 5px 15px;
            margin-right: 10px;
            font-size: 0.9rem;
        }

        .search-filter-btn.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: black;
        }

        .search-filter-btn:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: black;
        }

        .search-results {
            display: none;
        }

        #searchResults.active {
            display: block;
        }

        .result-item {
            background-color: var(--card-bg);
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .result-item:hover {
            background-color: #3E3E3E;
        }

        .result-img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            object-fit: cover;
            margin-right: 15px;
        }

        .result-info {
            flex-grow: 1;
        }

        .result-title {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .result-subtitle {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .result-action {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/home">LSpoty</a>

        <div class="d-flex align-items-center ms-auto">
            <a href="/my-playlists" class="nav-btn">
                <i class="bi bi-music-note-list"></i>
            </a>
            <a href="/profile" class="nav-btn profile-btn">
                <i class="bi bi-person-circle me-2"></i>
                <span class="d-none d-md-inline">Profile</span>
            </a>
            <form action="/sign-out" method="POST" class="d-inline">
                <button type="submit" class="nav-btn">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container">
    <!-- Search Bar -->
    <div class="row justify-content-center my-4">
        <div class="col-lg-8">
            <form id="searchForm" class="search-container">
                <input type="text" id="searchInput" name="query" placeholder="Search for tracks, albums, artists or playlists..." class="form-control">
                <button type="submit" class="btn">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="mt-3 text-center search-filters">
                <button type="button" class="search-filter-btn active" data-filter="tracks">Tracks</button>
                <button type="button" class="search-filter-btn" data-filter="albums">Albums</button>
                <button type="button" class="search-filter-btn" data-filter="artists">Artists</button>
                <button type="button" class="search-filter-btn" data-filter="playlists">Playlists</button>
            </div>
        </div>
    </div>

    <!-- Search Results Section (hidden by default) -->
    <div id="searchResults" class="search-results mb-5">
        <h2 class="category-title">Search Results</h2>
        <div class="row" id="resultsContainer">
            <!-- Sample search results - This would be populated dynamically -->
            <div class="col-12">
                <div class="result-item">
                    <img src="/api/placeholder/60/60" class="result-img" alt="Track cover">
                    <div class="result-info">
                        <div class="result-title">Bohemian Rhapsody</div>
                        <div class="result-subtitle">Queen · A Night at the Opera</div>
                    </div>
                    <div class="result-action">
                        <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="Add to playlist">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="result-item">
                    <img src="/api/placeholder/60/60" class="result-img" alt="Track cover">
                    <div class="result-info">
                        <div class="result-title">Yesterday</div>
                        <div class="result-subtitle">The Beatles · Help!</div>
                    </div>
                    <div class="result-action">
                        <button class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" title="Add to playlist">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Home Content -->
    <div id="homeContent">
        <!-- Recently Played -->
        <h2 class="category-title">Recently Played</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Album cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Album Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Album cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Album cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Song Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Album cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Album Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Album cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Tracks -->
        <h2 class="category-title">Top Tracks</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Track cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Track Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Track cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Track Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Track cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Track Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Track cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Track Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Track cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Track Title</h5>
                        <p class="card-text">Artist Name</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Artists -->
        <h2 class="category-title">Featured Artists</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Artist photo" class="rounded-circle">
                    <div class="card-body p-0 pt-2 text-center">
                        <h5 class="card-title">Artist Name</h5>
                        <p class="card-text">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Artist photo" class="rounded-circle">
                    <div class="card-body p-0 pt-2 text-center">
                        <h5 class="card-title">Artist Name</h5>
                        <p class="card-text">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Artist photo" class="rounded-circle">
                    <div class="card-body p-0 pt-2 text-center">
                        <h5 class="card-title">Artist Name</h5>
                        <p class="card-text">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Artist photo" class="rounded-circle">
                    <div class="card-body p-0 pt-2 text-center">
                        <h5 class="card-title">Artist Name</h5>
                        <p class="card-text">Artist</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Artist photo" class="rounded-circle">
                    <div class="card-body p-0 pt-2 text-center">
                        <h5 class="card-title">Artist Name</h5>
                        <p class="card-text">Artist</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Playlists -->
        <h2 class="category-title">Recommended Playlists</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 mb-5">
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Playlist cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Playlist cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Playlist cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Playlist cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="/api/placeholder/400/400" alt="Playlist cover">
                    <div class="card-body p-0 pt-2">
                        <h5 class="card-title">Playlist Name</h5>
                        <p class="card-text">By Creator</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-center text-light py-4">
    <div class="container">
        <p class="mb-0">© 2025 LSpoty - All rights reserved</p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle search form submission
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const homeContent = document.getElementById('homeContent');
        const filterButtons = document.querySelectorAll('.search-filter-btn');

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Only show search results if there's text in the search box
            if (searchInput.value.trim() !== '') {
                searchResults.classList.add('active');
                homeContent.style.display = 'none';

                // In a real app, you would fetch data from the API here
                // For now, we just show our hardcoded results
                console.log('Searching for:', searchInput.value);
            } else {
                searchResults.classList.remove('active');
                homeContent.style.display = 'block';
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
                if (searchResults.classList.contains('active')) {
                    console.log('Re-searching with filter:', this.dataset.filter);
                }
            });
        });

        // Enable tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
</body>
</html>