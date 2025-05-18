<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
    <title>LSpoty - <?= lang('App.companion') ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row justify-content-center mb-3">
        <div class="col-lg-8">
            <form id="searchForm" class="input-group bg-gray-800 rounded-pill">
                <input type="text" id="searchInput" name="query" placeholder="<?= lang('App.search') ?>" class="form-control border-0 bg-transparent text-white">
                <button type="submit" class="btn btn-link text-secondary" style="margin: 0 !important;">
                    <i class="fa fa-search"></i>
                </button>
                <button type="button" class="btn btn-link text-secondary clear-search" style="margin: 0 !important;">
                    <i class="fa fa-times clear-search"></i>
                </button>
            </form>

            <div class="mt-3 text-center">
                <div class="btn-group" role="group" aria-label="Search filters">
                    <button type="button" class="btn btn-outline-success active rounded-pill px-3 mx-1" style="margin: 0 5px 0 0 !important;" data-filter="tracks"><?= lang('App.tracks') ?></button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-3 mx-1" style="margin: 0 5px 0 0 !important;" data-filter="albums"><?= lang('App.albums') ?></button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-3 mx-1" style="margin: 0 5px 0 0 !important;" data-filter="artists"><?= lang('App.artists') ?></button>
                    <button type="button" class="btn btn-outline-success rounded-pill px-3 mx-1" style="margin: 0 0 0 0 !important;" data-filter="playlists"><?= lang('App.playlists') ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Section (hidden by default) -->
    <div id="searchResults" class="mb-4 d-none">
        <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.search_results') ?></h2>
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
            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.recently_uploaded') ?></h2>
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
                                <p><?= lang('App.no_recent_tracks') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <!-- Top Tracks -->
            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.top_tracks') ?></h2>
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
                                <p><?= lang('App.no_top_tracks') ?></p>
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
            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.popular_albums') ?></h2>
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
                                <p><?= lang('App.no_popular_albums') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.new_releases') ?></h2>
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
            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.featured_artists') ?></h2>
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
                                        <p class="card-text text-secondary text-truncate small"><?= lang('App.artist') ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p><?= lang('App.no_featured_artists') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.new_artists') ?></h2>
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
                                        <p class="card-text text-secondary text-truncate small"><?= lang('App.artist') ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p><?= lang('App.no_new_artists') ?></p>
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
            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.new_playlists') ?></h2>
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
                                        <p class="card-text text-secondary text-truncate small"><?= lang('App.playlist') ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p><?= lang('App.no_featured_playlists') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>

            <h2 class="fw-bold fs-4 my-3 text-white text-center"><?= lang('App.oldest_playlists') ?></h2>
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
                                        <p class="card-text text-secondary text-truncate small"><?= lang('App.playlist') ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <div class="text-white text-center w-100">
                                <p><?= lang('App.no_old_playlists') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-button-next text-success"></div>
                <div class="swiper-button-prev text-success"></div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
    <script>
        const LANG = {
            searching: "<?= lang('App.searching') ?>",
            something_wrong: "<?= lang('App.something_went_wrong') ?>",
            no_category1: "<?= lang('App.no_category1') ?>",
            no_category2: "<?= lang('App.no_category2') ?>"
        };
    </script>
    <script src="<?= site_url('/assets/js/home.js') ?>"></script>
<?= $this->endSection() ?>