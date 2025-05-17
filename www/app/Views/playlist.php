<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - <?= esc($playlist->name) ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= site_url('/assets/css/material-dashboard.css?v=3.1.0') ?>" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
    <!-- Playlist Detail CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/playlist-details.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mt-4">
    <div class="col-12">
        <button class="back-button" onclick="history.back()">
            <i class="fa fa-arrow-left"></i> Back
        </button>
    </div>
</div>

<div class="row playlist-header">
    <div class="col-lg-3 col-md-4 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
        <img src="<?= esc($playlist->image) ?>" alt="Playlist cover" class="playlist-cover">
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12 playlist-details">
        <h1 class="text-white display-5 fw-bold mb-1"><?= esc($playlist->name) ?></h1>
        <h2 class="text-secondary fs-4 mb-3 d-block text-decoration-none hover-text-success">
            <?= esc($playlist->owner->dispname) ?>
        </h2>

        <div class="playlist-stats">
                <span>
                <i class="fa fa-calendar"></i>
                <?php if (!empty($playlist->creationdate) && $playlist->creationdate !== '0000-00-00'): ?>
                    <?= esc(date('Y-m-d', strtotime($playlist->creationdate))) ?>
                <?php else: ?>
                    <?= esc($playlist->creationdate) ?>
                <?php endif; ?>
            </span>
            <span><i class="fa fa-music"></i> <?= count($playlist->tracks) ?> tracks</span>
            <span><i class="fa fa-clock"></i> <?= gmdate("H:i:s", $playlist->totalDuration) ?></span>
        </div>

        <div class="playlist-actions">
            <button class="action-btn primary" id="playPlaylistButton">
                <i class="fa fa-play me-1"></i> Play Playlist
            </button>
            <button class="action-btn" id="addPlaylistButton">
                <i class="fa fa-plus me-1"></i> Add to My Playlists
            </button>
            <button class="action-btn" id="shareButton">
                <i class="fa fa-share-alt me-1"></i> Share
            </button>
        </div>
    </div>
</div>

    <div class="row mt-4">
        <div class="col-12">
            <h2 class="fw-bold fs-4 text-white mb-3">Tracks</h2>
            <div class="track-list">
                <?php if (!empty($playlist->tracks)): ?>
                    <?php $trackNumber = 1; ?>
                    <?php foreach ($playlist->tracks as $track): ?>
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
                                    <button class="dropdown-toggle" type="button" id="trackDropdown<?= $track->id ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="trackDropdown<?= $track->id ?>">

                                        <?php if (!empty($playlist->playlists)): ?>
                                            <?php foreach ($playlist->playlists as $userPlaylist): ?>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="addToPlaylist(<?= $userPlaylist['id'] ?>, <?= $track->id ?>)">
                                                        <i class="fa fa-plus me-2"></i><?= esc($userPlaylist['name']) ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php else: ?>
                                            <li>
                                                <span class="dropdown-item text-muted">No playlists available</span>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php endif; ?>

                                        <li>
                                            <a class="dropdown-item" href="/track/<?= $track->id ?>">
                                                <i class="fa fa-info-circle me-2"></i>Track details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-track-id="<?= $track->id ?>">
                                                <i class="fa fa-share-alt me-2"></i>Share track
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
                        <p>No tracks available in this playlist.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<div class="row mt-4 mb-5">
    <div class="col-md-4">
        <div class="bg-gray-800 rounded p-4 mb-4">
            <h3 class="fw-bold fs-4 text-white mb-3">Created by</h3>
            <div class="d-flex align-items-center mb-3">
                <img src="<?= esc($playlist->owner->image) ?>"
                     alt="User image" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <h4 class="text-white mb-0 fs-5"><?= esc($playlist->owner->dispname) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="bg-gray-800 rounded p-4 mb-4">
            <h3 class="fw-bold fs-4 text-white mb-3">More Playlists by <?= esc($playlist->owner->dispname) ?></h3>
            <div class="row g-3">
                <?php if(!empty($playlist->similarPlaylists)): ?>
                    <?php foreach($playlist->similarPlaylists as $ply): ?>
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="card card-plain transition-transform hover-elevation" data-playlist-id="<?= $ply->id ?>">
                                <img src="<?= esc($ply->image)?>"
                                     class="card-img-top rounded" alt="<?= esc($ply->name) ?> cover">
                                <div class="card-body p-2">
                                    <h5 class="card-title fs-6 mb-0"><?= esc($ply->name) ?></h5>
                                    <p class="card-text small"><?= esc($ply->creationdate) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-muted">No other playlists created by this user.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    function addToPlaylist(playlistId, trackId) {
        fetch(`/my-playlists/${playlistId}/track/${trackId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({})
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error adding track to playlist');
                }
                return response.json();
            })
            .then(data => {
                alert('Track added to playlist!');
            })
            .catch(error => {
                console.error(error);
                alert('Failed to add track to playlist.');
            });
    }
</script>
<?= $this->endSection() ?>