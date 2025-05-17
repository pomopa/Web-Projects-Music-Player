<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - <?= esc($album->name) ?></title>

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
    <!-- Album Detail CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/album-detail.css') ?>">
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
    <div class="row mt-4">
        <div class="col-12">
            <button class="back-button" onclick="history.back()">
                <i class="fa fa-arrow-left"></i> Back
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
                <span><i class="fa fa-music"></i> <?= count($album->tracks) ?> tracks</span>
                <span><i class="fa fa-clock"></i> <?= gmdate("H:i:s", $album->totalDuration) ?></span>
            </div>

            <div class="album-actions">
                <button class="action-btn primary" id="playAlbumButton">
                    <i class="fa fa-play me-1"></i> Play Album
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
                                                <span class="dropdown-item text-muted">No playlists available</span>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php endif; ?>

                                        <li>
                                            <a class="dropdown-item" href="/track/<?= $trackId ?>">
                                                <i class="fa fa-info-circle me-2"></i>Track details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>">
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
                        <p>No tracks available for this album.</p>
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
                <a href="/artist/<?= $album->artist_id ?>" class="btn btn-outline-success rounded-pill w-100">Visit Artist</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <h3 class="fw-bold fs-4 text-white mb-3">More Albums by <?= esc($album->artist_name) ?></h3>
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
                            <p class="text-muted">No other albums found for this artist.</p>
                        </div>
                    <?php endif; ?>
                </div>
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
                alert(data.message ?? 'Unexpected response');
            })
            .catch(error => {
                console.error('Error adding track:', error);
                alert('An error occurred while adding the track.');
            });
    }
</script>

<!-- Core JS Files -->
<script src="<?= site_url('/assets/js/core/popper.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/album-player.js') ?>"></script>
</body>
</html>