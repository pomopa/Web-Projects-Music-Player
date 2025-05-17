<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
    <title>LSpoty - <?= esc($album->name) ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= site_url('/assets/css/album-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row mt-4">
        <div class="col-12">
            <button class="back-button" onclick="history.back()">
                <i class="fa fa-arrow-left"></i> <?= lang('App.back') ?>
            </button>
        </div>
    </div>

    <div class="row album-header">
        <div class="col-lg-3 col-md-4 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
            <img src="<?= esc($album->image) ?>" alt="Album cover" class="album-cover">
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 album-details">
            <h1 class="text-white display-5 fw-bold mb-1"><?= esc($album->name) ?></h1>
            <a href="/artist/<?= $album->artist_id ?>" class="text-secondary fs-4 mb-3 d-block text-decoration-none hover-text-success">
                <?= esc($album->artist_name) ?>
            </a>

            <div class="album-stats">
                <span><i class="fa fa-calendar"></i> <?= esc(date('Y-m-d', strtotime($album->releasedate))) ?></span>
                <span><i class="fa fa-music"></i> <?= count($album->tracks) ?> <?= lang('App.tracks') ?></span>
                <span><i class="fa fa-clock"></i> <?= gmdate("H:i:s", $album->totalDuration) ?></span>
            </div>

            <div class="album-actions">
                <button class="action-btn primary" id="playAlbumButton">
                    <i class="fa fa-play me-1"></i> <?= lang('App.play_album') ?>
                </button>
                <button class="action-btn" id="shareButton">
                    <i class="fa fa-share-alt me-1"></i> <?= lang('App.share') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2 class="fw-bold fs-4 text-white mb-3"><?= lang('App.tracks') ?></h2>
            <div class="track-list">
                <?php if (!empty($album->tracks)): ?>
                    <?php $trackNumber = 1; ?>
                    <?php foreach ($album->tracks as $track): ?>
                        <?php
                        $trackDuration = gmdate("i:s", $track->duration);
                        $trackId = $track->id;
                        ?>
                        <div class="track-item">
                            <div class="track-number"><?= $trackNumber ?></div>
                            <button class="track-play-btn" data-track-id="<?= $trackId ?>" data-track-url="<?= $track->playerUrl ?>">
                                <i class="fa fa-play"></i>
                            </button>
                            <div class="track-title">
                                <a href="/track/<?= $trackId ?>" class="text-decoration-none text-white">
                                    <?= esc($track->name) ?>
                                </a>
                                <!-- Progress will be added here dynamically -->
                            </div>
                            <div class="track-duration"><?= $trackDuration ?></div>
                            <div class="track-actions">
                                <div class="dropdown position-relative">
                                    <button class="dropdown-toggle" type="button" id="trackDropdown<?= $trackId ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="trackDropdown<?= $trackId ?>">
                                        <?php if (!empty($album->playlists)): ?>
                                            <?php foreach ($album->playlists as $playlist): ?>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="addToPlaylist(<?= $playlist['id'] ?>, <?= $trackId ?>)">
                                                        <i class="fa fa-plus me-2"></i><?= esc($playlist['name']) ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php else: ?>
                                            <li>
                                                <span class="dropdown-item text-muted"><?= lang('App.no_playlists_available') ?></span>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php endif; ?>

                                        <li>
                                            <a class="dropdown-item" href="/track/<?= $trackId ?>">
                                                <i class="fa fa-info-circle me-2"></i><?= lang('App.track_information') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>">
                                                <i class="fa fa-share-alt me-2"></i><?= lang('App.share_track') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php $trackNumber++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-secondary">
                        <i class="fa fa-info-circle mb-2 fs-3"></i>
                        <p><?= lang('App.no_tracks_for_album') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-md-4">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <h3 class="fw-bold fs-4 text-white mb-3">Artist</h3>
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= esc($album->artist_image) ?>"
                         alt="Artist image" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h4 class="text-white mb-0 fs-5"><?= esc($album->artist_name) ?></h4>
                    </div>
                </div>
                <a href="/artist/<?= $album->artist_id ?>" class="btn btn-outline-success rounded-pill w-100"><?= lang('App.visit_artist') ?></a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <h3 class="fw-bold fs-4 text-white mb-3"><?= lang('App.more_albums_by') ?><?= esc($album->artist_name) ?></h3>
                <div class="row g-3">
                    <?php if(!empty($album->similarAlbums)): ?>
                        <?php foreach($album->similarAlbums as $alb): ?>
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="card card-plain transition-transform hover-elevation" data-album-id="<?= $alb->id ?>">
                                    <img src="<?= esc($alb->image)?>"
                                         class="card-img-top rounded" alt="<?= esc($alb->name) ?> cover">
                                    <div class="card-body p-2">
                                        <h5 class="card-title fs-6 mb-0"><?= esc($alb->name) ?></h5>
                                        <p class="card-text small"><?= esc($alb->releasedate) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-muted"><?= lang('App.no_albums_for_artist') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
            menu.style.display = 'none';
        });
    }

    function addToPlaylist(playlistId, trackId) {
        closeAllDropdowns()
        fetch(`/my-playlists/${playlistId}/track/${trackId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({})
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message ?? "<?= lang('App.unexpected_response') ?>");
            })
            .catch(error => {
                console.error("<?= lang('App.error_adding_track') ?>", error);
                alert("<?= lang('App.error_adding_track') ?>");
            });
    }

    const LANG = {
        link: "<?= lang('App.track_link') ?>"
    };
</script>
<script src="<?= site_url('/assets/js/album-player.js') ?>"></script>
<?= $this->endSection() ?>