<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - <?= esc($playlist['name']) ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Playlist Detail CSS -->
<link rel="stylesheet" href="<?= site_url('/assets/css/playlist-details.css') ?>">
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
    <?php
        $coverPath = WRITEPATH . 'uploads/' . $playlist['user_id'] . '/playlists/' . $playlist['cover'];
        $hasCover = !empty($playlist['cover']) && file_exists($coverPath);
        ?>

    <?php if ($hasCover): ?>
        <img src="<?= base_url(route_to('my-playlist_picture', $playlist['id'])) ?>"
             alt="Playlist Cover"
             class="rounded img-fluid border border-success"
             style="width: 160px; height: 160px; padding: 0; object-fit: cover; ">
    <?php else: ?>
        <div class="rounded bg-secondary d-flex align-items-center justify-content-center border border-success"
             style="width: 160px; height: 160px; padding: 0;">
            <i class="fa fa-music fa-4x text-light2"></i>
        </div>
    <?php endif; ?>
    <div class="col-lg-9 col-md-8 col-sm-12 playlist-details">
        <h1 class="text-white display-5 fw-bold mb-1"><?= esc($playlist['name']) ?></h1>
        <h2 class="text-secondary fs-4 mb-3 d-block text-decoration-none hover-text-success">
            <?= esc($playlistCreator) ?>
        </h2>

        <div class="playlist-stats">
            <span>
                <i class="fa fa-calendar"></i>
                <?php if (!empty($playlist['created_at']) && $playlist['created_at'] !== '0000-00-00'): ?>
                    <?= esc(date('Y-m-d', strtotime($playlist['created_at']))) ?>
                <?php else: ?>
                    <?= esc($playlist['created_at']) ?>
                <?php endif; ?>
            </span>
            <span><i class="fa fa-music"></i> <?= count($playlist['tracks']) ?> <?= lang('App.tracks') ?></span>
            <span><i class="fa fa-clock"></i> <?= $formattedTotalDuration ?></span>
        </div>

        <div class="playlist-actions">
            <button class="action-btn primary" id="playPlaylistButton">
                <i class="fa fa-play me-1"></i> <?= lang('App.play_playlist') ?>
            </button>
            <button class="action-btn" id="renamePlaylistButton">
                <i class="fa fa-pencil-alt me-1"></i> <?= lang('App.rename_playlist') ?>
            </button>
            <button class="action-btn alert-danger" id="deleteButton">
                <i class="fa fa-trash me-1"></i> <?= lang('App.delete_playlist') ?>
            </button>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h2 class="fw-bold fs-4 text-white mb-3"><?= lang('App.tracks') ?></h2>
        <div class="track-list">
            <?php if (!empty($playlist['tracks'])): ?>
                <?php $trackNumber = 1; ?>
                <?php foreach ($playlist['tracks'] as $track): ?>
                    <?php
                    $trackDuration = gmdate("i:s", $track['duration']);
                    $trackId = $track['id'];
                    ?>
                    <div class="track-item">
                        <div class="track-number"><?= $trackNumber ?></div>
                        <button class="track-play-btn" data-track-id="<?= $trackId ?>" data-track-url="<?= $track['player_url'] ?>">
                            <i class="fa fa-play"></i>
                        </button>
                        <div class="track-title">
                            <a href="/track/<?= $trackId ?>" class="text-decoration-none text-white">
                                <?= esc($track['name']) ?>
                            </a>
                        </div>
                        <div class="track-duration"><?= $trackDuration ?></div>
                        <div class="track-actions">
                            <div class="dropdown position-relative">
                                <button class="dropdown-toggle" type="button" id="trackDropdown<?= $trackId ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="trackDropdown<?= $trackId ?>">
                                    <li><a class="dropdown-item" href="/track/<?= $trackId ?>"><i class="fa fa-info-circle me-2"></i><?= lang('App.track_information') ?></a></li>
                                    <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-plus me-2"></i><?= lang('App.add_to_playlist') ?></a></li>
                                    <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-share-alt me-2"></i><?= lang('App.share_track') ?></a></li>
                                    <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-times me-2"></i><?= lang('App.remove_from_playlist') ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php $trackNumber++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-4 text-center text-secondary">
                    <i class="fa fa-info-circle mb-2 fs-3"></i>
                    <p><?= lang('App.no_tracks_for_playlist') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row mt-4 mb-5">
    <div class="col-md-4">
        <div class="bg-gray-800 rounded p-4 mb-4">
            <h3 class="fw-bold fs-4 text-white mb-3"><?= lang('App.created_by') ?></h3>
            <div class="d-flex align-items-center mb-3">
                <div>
                    <h4 class="text-white mb-0 fs-5"><?= esc($playlistCreator) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="bg-gray-800 rounded p-4 mb-4">
            <h3 class="fw-bold fs-4 text-white mb-3"><?= lang('App.more_playlists_by') ?> <?= esc($playlistCreator) ?></h3>
            <div class="row g-3">
                <?php if (!empty($playlist['similarPlaylists'])): ?>
                    <?php foreach($playlist['similarPlaylists'] as $ply): ?>
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="card card-plain transition-transform hover-elevation" data-playlist-id="<?= $ply['id'] ?>">
                                <img src="<?= esc($ply['image']) ?>"
                                     class="card-img-top rounded" alt="<?= esc($ply['name']) ?> cover">
                                <div class="card-body p-2">
                                    <h5 class="card-title fs-6 mb-0"><?= esc($ply['name']) ?></h5>
                                    <p class="card-text small"><?= esc($ply['creationdate']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-muted"><?= lang('App.no_other_playlists') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
