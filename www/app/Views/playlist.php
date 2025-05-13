<?php

$playlistId = $playlist ? $playlist->id : 0;
$playlistName = $playlist ? $playlist->name : 'Playlist Not Found';
$playlistImage = '/api/placeholder/400/400';
$playlistCreator = $playlist ? $playlist->user_name : 'Unknown Creator';
$playlistCreatorId = $playlist ? $playlist->user_id : 0;
$creationDate = $playlist ? date('Y-m-d', strtotime($playlist->creationdate)) : 'Unknown';
$tracks = $playlist ? $playlist->tracks : [];

// Calculate total duration and track count
$totalDuration = 0;
if ($playlist && !empty($tracks)) {
    foreach ($tracks as $track) {
        $totalDuration += $track->duration;
    }
}
$formattedTotalDuration = gmdate("H:i:s", $totalDuration);
$tracksCount = count($tracks);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - <?= esc($playlistName) ?></title>

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
    <!-- Album Detail CSS (reused for playlist details) -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/album-detail.css') ?>">

    <!-- Additional CSS for playlist page -->
    <style>
        /* Custom styles for playlist page */
        .playlist-header {
            margin-bottom: 2rem;
        }

        .playlist-cover {
            width: 220px;
            height: 220px;
            border-radius: 4px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            object-fit: cover;
        }

        .playlist-stats {
            display: flex;
            gap: 15px;
            color: var(--lspoty-text-secondary);
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .playlist-stats span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .track-item {
            display: grid;
            grid-template-columns: 40px 40px 1fr 80px 50px;
            padding: 8px 12px;
            border-radius: 4px;
            align-items: center;
            transition: background-color 0.2s ease;
        }

        .track-item:hover {
            background-color: var(--lspoty-hover-bg);
        }

        .track-play-btn {
            display: none;
            background: none;
            border: none;
            color: var(--lspoty-text);
            cursor: pointer;
        }

        .track-play-btn:hover {
            color: var(--lspoty-primary);
        }

        .track-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-title a:hover {
            color: var(--lspoty-primary) !important;
        }

        .track-duration {
            text-align: right;
            color: var(--lspoty-text-secondary);
        }

        .track-actions {
            text-align: right;
        }

        .track-actions button {
            background: none;
            border: none;
            color: var(--lspoty-text-secondary);
            cursor: pointer;
        }

        .track-actions button:hover {
            color: var(--lspoty-primary);
        }

        .saved-badge {
            background-color: var(--lspoty-primary);
            color: #000;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-left: 10px;
        }

        /* Creator card */
        .creator-card {
            background-color: var(--lspoty-card-bg);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .track-item {
                grid-template-columns: 30px 30px 1fr 60px 40px;
                padding: 8px 5px;
            }

            .playlist-cover {
                width: 180px;
                height: 180px;
            }
        }
    </style>
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

    <div class="row playlist-header">
        <div class="col-lg-3 col-md-4 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
            <img src="<?= esc($playlistImage) ?>" alt="Playlist cover" class="playlist-cover">
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 playlist-details">
            <div class="d-flex align-items-center">
                <h1 class="text-white display-5 fw-bold mb-1"><?= esc($playlistName) ?></h1>

            </div>

            <a href="/user/<?= $playlistCreatorId ?>" class="text-secondary fs-5 mb-3 d-block text-decoration-none hover-text-success">
                Created by <?= esc($playlistCreator) ?>
            </a>

            <div class="playlist-stats">
                <span><i class="fa fa-calendar"></i> Created: <?= esc($creationDate) ?></span>
                <span><i class="fa fa-music"></i> <?= $tracksCount ?> tracks</span>
                <span><i class="fa fa-clock"></i> <?= $formattedTotalDuration ?></span>
            </div>

            <div class="playlist-actions">
                <button class="action-btn primary" id="playPlaylistButton">
                    <i class="fa fa-play me-1"></i> Play Playlist
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
                <?php if (!empty($tracks)): ?>
                    <?php $trackNumber = 1; ?>
                    <?php foreach ($tracks as $track): ?>
                        <?php
                        $trackDuration = gmdate("i:s", $track->duration);
                        $trackId = $track->id;
                        $artistName = $track->artist_name;
                        $artistId = $track->artist_id;
                        ?>
                        <div class="track-item">
                            <div class="track-number"><?= $trackNumber ?></div>
                            <button class="track-play-btn" data-track-id="<?= $trackId ?>" data-track-url="<?= $track->audio ?>">
                                <i class="fa fa-play"></i>
                            </button>
                            <div class="track-title">
                                <a href="/track/<?= $trackId ?>" class="text-decoration-none text-white">
                                    <?= esc($track->name) ?>
                                </a>
                                <span class="text-secondary d-block small">
                                    <a href="/artist/<?= $artistId ?>" class="text-decoration-none text-secondary hover-text-success">
                                        <?= esc($artistName) ?>
                                    </a>
                                </span>
                            </div>
                            <div class="track-duration"><?= $trackDuration ?></div>
                            <div class="track-actions">
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" id="trackDropdown<?= $trackId ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="trackDropdown<?= $trackId ?>">
                                        <li><a class="dropdown-item" href="/track/<?= $trackId ?>"><i class="fa fa-info-circle me-2"></i>Track details</a></li>
                                        <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-plus me-2"></i>Add to playlist</a></li>
                                        <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-share-alt me-2"></i>Share track</a></li>
                                        <?php if ($isOwner): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="/playlist/<?= $playlistId ?>/remove-track" method="POST">
                                                    <input type="hidden" name="track_id" value="<?= $trackId ?>">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa fa-trash me-2"></i>Remove from playlist
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
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
                <h3 class="fw-bold fs-4 text-white mb-3">Playlist Creator</h3>
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= site_url('/api/placeholder/60/60') ?>"
                         alt="Creator avatar" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h4 class="text-white mb-0 fs-5"><?= esc($playlistCreator) ?></h4>
                    </div>
                </div>
                <a href="/user/<?= $playlistCreatorId ?>" class="btn btn-outline-success rounded-pill w-100">View Profile</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <h3 class="fw-bold fs-4 text-white mb-3">You Might Also Like</h3>
                <div class="row g-3">
                    <?php if(!empty($playlist->similarPlaylists)): ?>
                        <?php foreach($playlist->similarPlaylists as $pl): ?>
                            <div class="col-lg-3 col-md-4 col-6">
                                <a href="/playlist/<?= $pl->id ?>" class="text-decoration-none">
                                    <div class="card card-plain transition-transform hover-elevation">
                                        <img src="<?= !empty($pl->image) ? esc($pl->image) : '/api/placeholder/150/150' ?>"
                                             class="card-img-top rounded" alt="<?= esc($pl->name) ?> cover">
                                        <div class="card-body p-2">
                                            <h5 class="card-title fs-6 mb-0"><?= esc($pl->name) ?></h5>
                                            <p class="card-text small">By <?= esc($pl->creator_name) ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-muted">No similar playlists found.</p>
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

<!-- Core JS Files -->
<script src="<?= site_url('/assets/js/core/popper.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>

<!-- Playlist Player JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle play playlist button
        const playPlaylistButton = document.getElementById('playPlaylistButton');
        if (playPlaylistButton) {
            playPlaylistButton.addEventListener('click', function() {
                // Get first track play button and trigger click
                const firstTrackPlayBtn = document.querySelector('.track-play-btn');
                if (firstTrackPlayBtn) {
                    firstTrackPlayBtn.click();
                }
            });
        }

        // Handle individual track play buttons
        const trackPlayButtons = document.querySelectorAll('.track-play-btn');
        trackPlayButtons.forEach(button => {
            button.addEventListener('click', function() {
                const trackId = this.getAttribute('data-track-id');
                const trackUrl = this.getAttribute('data-track-url');

                // Navigate to track page or play audio
                if (trackUrl) {
                    console.log(`Playing track ID: ${trackId}, URL: ${trackUrl}`);
                    window.location.href = `/track/${trackId}`;
                }
            });
        });

        // Implement hover effect to show play button and hide number
        const trackItems = document.querySelectorAll('.track-item');
        trackItems.forEach(item => {
            const number = item.querySelector('.track-number');
            const playBtn = item.querySelector('.track-play-btn');

            item.addEventListener('mouseenter', function() {
                if (number) number.style.display = 'none';
                if (playBtn) playBtn.style.display = 'block';
            });

            item.addEventListener('mouseleave', function() {
                if (number) number.style.display = 'block';
                if (playBtn) playBtn.style.display = 'none';
            });
        });

        // Add to queue functionality
        const addToQueueButton = document.getElementById('addToQueueButton');
        if (addToQueueButton) {
            addToQueueButton.addEventListener('click', function() {
                // Implementation would depend on your backend API
                console.log('Adding playlist to queue');
                alert('Playlist added to your queue!');
            });
        }

        // Share button functionality
        const shareButton = document.getElementById('shareButton');
        if (shareButton) {
            shareButton.addEventListener('click', function() {
                const currentUrl = window.location.href;
                navigator.clipboard.writeText(currentUrl).then(() => {
                    alert('Playlist link copied to clipboard!');
                });
            });
        }
    });
</script>
</body>
</html>