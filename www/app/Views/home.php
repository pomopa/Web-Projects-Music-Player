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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
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
            <form action="/sign-out" method="GET" class="d-inline" style="margin: 0 !important;">
                <button type="submit" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white" style="margin: 0 !important;">
                    <i class="fa fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container">
    <div class="row justify-content-center mt-4 mb-3">
        <div class="col-lg-8">
            <form id="searchForm" class="input-group bg-gray-800 rounded-pill">
                <input type="text" id="searchInput" name="query" placeholder="Search for tracks, albums, artists or playlists..." class="form-control border-0 bg-transparent text-white">
                <button type="submit" class="btn btn-link text-secondary" style="margin: 0 !important;">
                    <i class="fa fa-search"></i>
                </button>
                <button type="button" class="btn btn-link text-secondary clear-search" style="margin: 0 !important;">
                    <i class="fa fa-times clear-search"></i>
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8" id="resultsContainer">
                <!-- Search results will be populated here -->
            </div>
        </div>
    </div>

    <!-- Main Home Content -->
    <div id="homeContent">
        <!-- Tracks Section -->
        <div id="tracksSection" class="category-section">
            <!-- Recently Uploaded Tracks -->
            <h2 class="fw-bold fs-4 my-3 text-white text-center">Recently Uploaded</h2>
            <div class="swiper recentTracksSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($recentTracks)): ?>
                        <?php foreach ($recentTracks as $track): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/track/<?= esc($track['id']) ?>">
                                    <div class="card-body p-3">
                                        <img src="<?= esc($track['album_image']) ?>" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($track['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small"><?= esc($track['artist_name']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No recent tracks found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <!-- Top Tracks -->
            <h2 class="fw-bold fs-4 my-3 text-white text-center">Top Tracks</h2>
            <div class="swiper topTracksSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($topTracks)): ?>
                        <?php foreach ($topTracks as $track): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/track/<?= esc($track['id']) ?>">
                                    <div class="card-body p-3">
                                        <img src="<?= esc($track['album_image']) ?>" alt="Track cover" class="img-fluid rounded mb-2 w-100">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($track['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small"><?= esc($track['artist_name']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No top tracks found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>
        </div>

        <!-- Albums Section -->
        <div id="albumsSection" class="category-section d-none">
            <h2 class="fw-bold fs-4 my-3 text-white text-center">Popular Albums</h2>
            <div class="swiper albumsSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($topAlbums)): ?>
                        <?php foreach ($topAlbums as $album): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/album/<?= esc($album['id']) ?>">
                                    <div class="card-body p-3">
                                        <img src="<?= esc($album['image']) ?>" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($album['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small"><?= esc($album['artist_name']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No popular albums found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <h2 class="fw-bold fs-4 my-3 text-white text-center">New Releases</h2>
            <div class="swiper newReleasesSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($newAlbums)): ?>
                        <?php foreach ($newAlbums as $album): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/album/<?= esc($album['id']) ?>">
                                    <div class="card-body p-3">
                                        <img src="<?= esc($album['image']) ?>" alt="Album cover" class="img-fluid rounded mb-2 w-100">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($album['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small"><?= esc($album['artist_name']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No new albums found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>
        </div>

        <!-- Artists Section -->
        <div id="artistsSection" class="category-section d-none">
            <h2 class="fw-bold fs-4 my-3 text-white text-center">Featured Artists</h2>
            <div class="swiper featuredArtistsSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($topArtists)): ?>
                        <?php foreach ($topArtists as $artist): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/artist/<?= esc($artist['id']) ?>">
                                    <div class="card-body p-3 text-center">
                                        <img src="<?= esc(!empty($artist['image']) ? $artist['image'] : 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg') ?>"
                                             alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($artist['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small">Artist</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No featured artists found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <h2 class="fw-bold fs-4 my-3 text-white text-center">New Artists</h2>
            <div class="swiper trendingArtistsSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($newArtists)): ?>
                        <?php foreach ($newArtists as $artist_n): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/artist/<?= esc($artist_n['id']) ?>">
                                    <div class="card-body p-3 text-center">
                                        <img src="<?= esc(!empty($artist_n['image']) ? $artist_n['image'] : 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg') ?>"
                                             alt="Artist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($artist_n['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small">Artist</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No new artists found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>
        </div>

        <!-- Playlists Section -->
        <div id="playlistsSection" class="category-section d-none">
            <h2 class="fw-bold fs-4 my-3 text-white text-center">New Playlists</h2>
            <div class="swiper featuredPlaylistSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($newPlaylists)): ?>
                        <?php foreach ($newPlaylists as $playlist): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/playlist/<?= esc($playlist['id']) ?>">
                                    <div class="card-body p-3 text-center">
                                        <img src="https://img.freepik.com/premium-psd/music-icon-user-interface-element-3d-render-illustration_516938-1693.jpg"
                                             alt="Playlist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($playlist['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small">Playlist</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No featured playlists found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <h2 class="fw-bold fs-4 my-3 text-white text-center">Oldest Playlists</h2>
            <div class="swiper popularPlaylistsSwiper">
                <div class="swiper-wrapper">
                    <?php if (!empty($oldPlaylists)): ?>
                        <?php foreach ($oldPlaylists as $playlist): ?>
                            <div class="swiper-slide">
                                <div class="card card-plain bg-gray-800 transition-transform hover-elevation h-100" data-url="/playlist/<?= esc($playlist['id']) ?>">
                                    <div class="card-body p-3 text-center">
                                        <img src="https://img.freepik.com/premium-psd/music-icon-user-interface-element-3d-render-illustration_516938-1693.jpg"
                                             alt="Playlist photo" class="img-fluid rounded-circle mb-2 w-75 mx-auto">
                                        <h6 class="card-title mb-1 text-truncate text-white"><?= esc($playlist['name']) ?></h6>
                                        <p class="card-text text-secondary text-truncate small">Playlist</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p>No old playlists found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-black text-center text-light py-3 mt-auto">
    <div class="container">
        <p class="mb-0">© 2025 LSpoty - All rights reserved</p>
    </div>
</footer>

<!-- Core JS Files -->
<script src="<?= site_url('/assets/js/core/popper.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/home.js') ?>"></script>
<script src="<?= site_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</body>
</html>