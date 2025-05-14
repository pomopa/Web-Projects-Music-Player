<?php
$trackName = $track ? $track->name : 'Track Not Found';
$artistName = $track ? $track->artist_name : 'Unknown Artist';
$albumName = $track ? $track->album_name : 'Unknown Album';
$albumImage = $track ? $track->album_image : '/api/placeholder/400/400';
$artistImage = $track ? ($track->artist_image ?? 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg') : 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg';
$releaseDate = $track ? date('Y-m-d', strtotime($track->releasedate)) : 'Unknown';
$duration = $track ? gmdate("i:s", $track->duration) : '0:00';
$audio = $track ? $track->audio : '';
$genre = $track ? ($track->musicinfo->tags->genres[0] ?? 'Unknown') : 'Unknown';
$license = $track ? $track->license_ccurl : 'Unknown';

// ID para funcionalidad JavaScript
$trackId = $track ? $track->id : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - <?= esc($trackName) ?></title>

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
    <!-- Track Detail CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/track-detail.css') ?>">
</head>
<body class="bg-dark">
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black position-sticky top-0" style="z-index: 1000;">
    <div class="container">
        <a class="navbar-brand text-success fw-bold fs-4" style="margin: 0px !important;" href="<?= base_url(route_to('home_view')) ?>">LSpoty</a>

        <div class="d-flex align-items-center ms-auto gap-2">
            <a href="<?= base_url(route_to('playlist_view')) ?>" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white me-2" style="margin: 0 !important;">
                <i class="fa fa-music"></i>
            </a>
            <a href="<?= base_url(route_to('profile_view')) ?>" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white me-2" style="margin: 0 5px 0 5px !important;">
                <i class="fa fa-user-circle"></i>
            </a>
            <form action="<?= base_url(route_to('sign-out_submit')) ?>" method="POST" class="d-inline" style="margin: 0 !important;">
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

    <div class="row track-header">
        <div class="col-md-3 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
            <img src="<?= esc($albumImage) ?>" alt="Track cover" class="track-cover">
        </div>
        <div class="col-md-9 col-sm-12 track-details">
            <h1 class="text-white display-5 fw-bold mb-1"><?= esc($trackName) ?></h1>
            <h2 class="text-secondary fs-4 mb-3"><?= esc($artistName) ?></h2>

            <div class="mb-3">
                <span class="tag"><i class="fa fa-calendar me-1"></i> <?= esc($releaseDate) ?></span>
            </div>

            <div class="actions-container">
                <button class="action-btn primary" id="playButton" data-track-url="<?= esc($audio) ?>">
                    <i class="fa fa-play me-1"></i> Play
                </button>
                <div class="dropdown">
                    <button class="action-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-plus me-1"></i> Add to playlist
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-track-id="<?= $trackId ?>">
                        <li><a class="dropdown-item playlist-add" href="#" data-playlist-id="1">My Favorites</a></li>
                        <li><a class="dropdown-item playlist-add" href="#" data-playlist-id="2">Workout Mix</a></li>
                        <li><a class="dropdown-item playlist-add" href="#" data-playlist-id="3">Chill Vibes</a></li>
                        <li><div class="dropdown-divider"></div></li>
                        <li><a class="dropdown-item" href="/create-playlist"><i class="fa fa-plus-circle me-2"></i>Create new playlist</a></li>
                    </ul>
                </div>
                <button class="action-btn" id="shareButton" data-track-id="<?= $trackId ?>">
                    <i class="fa fa-share-alt me-1"></i> Share
                </button>
            </div>
        </div>
    </div>

    <div class="player-container">
        <div class="player-progress d-flex align-items-center">
            <span class="time-display current-time">0:00</span>
            <div class="progress-container">
                <div class="progress-bar" style="width: 0%"></div>
            </div>
            <span class="time-display duration"><?= esc($duration) ?></span>
        </div>
        <div class="player-controls">
            <button class="control-btn"><i class="fa fa-step-backward"></i></button>
            <button class="control-btn main" id="playPauseBtn"><i class="fa fa-play"></i></button>
            <button class="control-btn"><i class="fa fa-step-forward"></i></button>
        </div>
        <audio id="audioPlayer" preload="metadata">
            <source src="<?= esc($audio) ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <div class="">
                    <h4 class="fw-bold text-white mb-2">Track Information</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="text-secondary">Album</td>
                                <td class="text-white"><?= esc($albumName) ?></td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Duration</td>
                                <td class="text-white"><?= esc($duration) ?></td>
                            </tr>
                            <tr>
                                <td class="text-secondary">License</td>
                                <td class="text-white"><?= esc($license) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <h3 class="fw-bold fs-4 text-white mb-3">Artist</h3>
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= esc($artistImage) ?>"
                         alt="Artist image" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h4 class="text-white mb-0 fs-5"><?= esc($artistName) ?></h4>
                    </div>
                </div>
                <button class="btn btn-outline-success rounded-pill w-100">Visit Artist</button>
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
<script src="<?= site_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
<!-- Track Player JS -->
<script src="<?= site_url('/assets/js/track-player.js') ?>"></script>

</body>
</html>