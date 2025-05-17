<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
    <title>LSpoty - <?= lang('App.my_playlists') ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <!-- Playlist Detail CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/playlist-details.css') ?>">
    <link rel="stylesheet" href="<?= site_url('/assets/css/playlist-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row mt-4">
        <div class="col-12">
            <button class="back-button" onclick="history.back()">
                <i class="fa fa-arrow-left"></i> <?= lang('App.back') ?>
            </button>
        </div>
    </div>

    <div class="row playlist-header">
        <?php if(!empty($user['profile_pic']) && file_exists(WRITEPATH . 'uploads/' . $user['id'] . '/profile/' . $user['profile_pic'])): ?>
            <img src="<?= base_url(route_to('profile_picture')) ?>" class="rounded-circle img-fluid border border-success" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
        <?php else: ?>
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-success" style="width: 150px; height: 150px;">
                <i class="fa fa-user fa-4x text-light"></i>
            </div>
        <?php endif; ?>
        <div class="col-lg-9 col-md-8 col-sm-12 playlist-details">
            <h1 class="text-white display-5 fw-bold mb-1"><?= lang('App.my_playlists') ?></h1>
            <h2 class="text-secondary fs-4 mb-3 d-block text-decoration-none hover-text-success">
                <?= esc($username) ?>
            </h2>

            <div class="playlist-stats">
            </span>
                <span><i class="fa fa-music"></i> <?= count($myPlaylists) ?> <?= lang('App.playlists') ?></span>
                <span><i class="fa fa-clock"></i> <?= gmdate("H:i:s", $totalDuration) ?></span>
            </div>

            <div class="playlist-actions">
                <button class="action-btn primary" id="createPlaylistButton" onclick="window.location.href='<?= base_url(route_to('my-playlist_create')) ?>'">
                    <i class="fa fa-plus me-1"></i> <?= lang('App.create_playlist') ?>
                </button>
                <button class="action-btn" id="shareButton">
                    <i class="fa fa-share-alt me-1"></i> <?= lang('App.share') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2 class="fw-bold fs-4 text-white mb-3"><?= lang('App.my_playlists') ?></h2>
            <div class="track-list">
                <?php if (!empty($myPlaylists)): ?>
                    <?php $playlistNumber = 1; ?>
                    <?php foreach ($myPlaylists as $playlist): ?>
                        <?php
                        $trackDuration = gmdate("i:s", $playlist['duration']);
                        $playlistId = $playlist['id'];
                        if(!empty($playlist['tracks'])) {
                            $trackId = $playlist['tracks'][0]['id'];
                            $player_url = $playlist['tracks'][0]['player_url'];
                        } else {
                            $trackId = null;
                            $player_url = null;
                        }
                        $coverPath = WRITEPATH . 'uploads/' . $playlist['user_id'] . '/playlists/' . $playlist['cover'];
                        $hasCover = !empty($playlist['cover']) && file_exists($coverPath);
                        ?>
                        <div class="playlist-item">
                            <div class="track-number"><?= $playlistNumber ?></div>
                            <div class="track-image">
                                <?php if ($hasCover): ?>
                                    <img src="<?= base_url(route_to('my-playlist_picture', $playlist['id'])) ?>"
                                         alt="Playlist Cover"
                                         class="rounded img-fluid border border-success"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded bg-secondary d-flex align-items-center justify-content-center border border-success"
                                         style="width: 40px; height: 40px;">
                                        <i class="fa fa-music fa-1x text-light2"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="track-play-btn" onclick="window.location.href='<?= base_url(route_to('my-playlist_exact_view', $playlistId)) ?>'">
                                <i class="fa fa-play"></i>
                            </button>
                            <div class="track-title">
                                <a href="/my-playlists/<?= $playlistId ?>" class="text-decoration-none text-white">
                                    <?= esc($playlist['name']) ?>
                                </a>
                            </div>
                            <div class="track-duration"><?= $trackDuration ?></div>
                            <div class="number-tracks"><?= count($playlist['tracks']) ?? 0 ?> <?= lang('App.tracks') ?></div>

                        </div>
                        <?php $playlistNumber++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-secondary">
                        <i class="fa fa-info-circle mb-2 fs-3"></i>
                        <p><?= lang('App.no_playlists_yet') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>