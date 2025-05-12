<?php
// Get album data from API
$album = $albumData ?? null;
$artistId = $album ? $album->artist_id : 0;
$albumName = $album ? $album->name : 'Album Not Found';
$artistName = $album ? $album->artist_name : 'Unknown Artist';
$albumImage = $album ? $album->image : '/api/placeholder/400/400';
$artistImage = $album ? ($album->artist_image ?? '/api/placeholder/80/80') : '/api/placeholder/80/80';
$releaseDate = $album ? date('Y-m-d', strtotime($album->releasedate)) : 'Unknown';
$tracks = $album ? $album->tracks : [];

// Calculate total duration
$totalDuration = 0;
if ($album && !empty($tracks)) {
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
    <title>LSpoty - <?= esc($albumName) ?></title>

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

    <style>
        /* Additional Album Detail Specific Styles */
        .album-cover {
            width: 280px;
            height: 280px;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            object-fit: cover;
        }

        .track-list {
            background-color: var(--lspoty-card-bg);
            border-radius: 8px;
            overflow: hidden;
        }

        .track-item {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
        }

        .track-item:hover {
            background-color: var(--lspoty-hover-bg);
        }

        .track-number {
            width: 30px;
            text-align: center;
            color: var(--lspoty-text-secondary);
            font-size: 0.9rem;
        }

        .track-play-btn {
            color: var(--lspoty-text);
            background: none;
            border: none;
            font-size: 0.9rem;
            opacity: 0;
            transition: opacity 0.2s ease;
            width: 30px;
            text-align: center;
            padding: 0;
        }

        .track-item:hover .track-number {
            display: none;
        }

        .track-item:hover .track-play-btn {
            opacity: 1;
            display: block;
        }

        .track-title {
            flex-grow: 1;
            padding: 0 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--lspoty-text);
        }

        .track-duration {
            color: var(--lspoty-text-secondary);
            font-size: 0.85rem;
            width: 50px;
            text-align: right;
        }

        .track-actions {
            width: 40px;
            text-align: center;
        }

        .track-actions .dropdown-toggle {
            background: none;
            border: none;
            color: var(--lspoty-text-secondary);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .track-item:hover .track-actions .dropdown-toggle {
            opacity: 1;
        }

        .album-stats {
            color: var(--lspoty-text-secondary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 8px;
        }

        .album-stats i {
            margin-right: 5px;
        }

        .album-actions {
            display: flex;
            gap: 10px;
            margin: 20px 0;
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

    <div class="row album-header">
        <div class="col-lg-3 col-md-4 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
            <img src="<?= esc($albumImage) ?>" alt="Album cover" class="album-cover">
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 album-details">
            <h1 class="text-white display-5 fw-bold mb-1"><?= esc($albumName) ?></h1>
            <a href="/artist/<?= $artistId ?>" class="text-secondary fs-4 mb-3 d-block text-decoration-none hover-text-success">
                <?= esc($artistName) ?>
            </a>

            <div class="album-stats">
                <span><i class="fa fa-calendar"></i> <?= esc($releaseDate) ?></span>
                <span><i class="fa fa-music"></i> <?= $tracksCount ?> tracks</span>
                <span><i class="fa fa-clock"></i> <?= $formattedTotalDuration ?></span>
            </div>

            <div class="album-actions">
                <button class="action-btn primary" id="playAlbumButton">
                    <i class="fa fa-play me-1"></i> Play Album
                </button>
                <button class="action-btn" id="addToQueueButton">
                    <i class="fa fa-list me-1"></i> Add to Queue
                </button>
                <div class="dropdown">
                    <button class="action-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-plus me-1"></i> Add to playlist
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item playlist-add" href="#" data-playlist-id="1">My Favorites</a></li>
                        <li><a class="dropdown-item playlist-add" href="#" data-playlist-id="2">Workout Mix</a></li>
                        <li><a class="dropdown-item playlist-add" href="#" data-playlist-id="3">Chill Vibes</a></li>
                        <li><div class="dropdown-divider"></div></li>
                        <li><a class="dropdown-item" href="/create-playlist"><i class="fa fa-plus-circle me-2"></i>Create new playlist</a></li>
                    </ul>
                </div>
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
                    <img src="<?= esc($artistImage) ?>"
                         alt="Artist image" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h4 class="text-white mb-0 fs-5"><?= esc($artistName) ?></h4>
                    </div>
                </div>
                <a href="/artist/<?= $artistId ?>" class="btn btn-outline-success rounded-pill w-100">Visit Artist</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-gray-800 rounded p-4 mb-4">
                <h3 class="fw-bold fs-4 text-white mb-3">More Albums by <?= esc($artistName) ?></h3>
                <div class="row g-3">
                    <!-- This would be populated by other albums from the same artist -->
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="card card-plain transition-transform hover-elevation">
                            <img src="/api/placeholder/150/150" class="card-img-top rounded" alt="Album cover">
                            <div class="card-body p-2">
                                <h5 class="card-title fs-6 mb-0">Other Album</h5>
                                <p class="card-text small">2024</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="card card-plain transition-transform hover-elevation">
                            <img src="/api/placeholder/150/150" class="card-img-top rounded" alt="Album cover">
                            <div class="card-body p-2">
                                <h5 class="card-title fs-6 mb-0">Another Album</h5>
                                <p class="card-text small">2023</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="card card-plain transition-transform hover-elevation">
                            <img src="/api/placeholder/150/150" class="card-img-top rounded" alt="Album cover">
                            <div class="card-body p-2">
                                <h5 class="card-title fs-6 mb-0">First Album</h5>
                                <p class="card-text small">2022</p>
                            </div>
                        </div>
                    </div>
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

<!-- Album Player JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle play album button
        const playAlbumButton = document.getElementById('playAlbumButton');
        if (playAlbumButton) {
            playAlbumButton.addEventListener('click', function() {
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
                    // You could implement audio player functionality here
                    console.log(`Playing track ID: ${trackId}, URL: ${trackUrl}`);
                    // For now, let's navigate to the track page
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

        // Handle add to playlist functionality
        const playlistItems = document.querySelectorAll('.playlist-add');
        playlistItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const playlistId = this.getAttribute('data-playlist-id');
                // Implementation would depend on your backend API
                console.log(`Adding album to playlist ID: ${playlistId}`);

                // Show confirmation
                alert(`Album added to playlist: ${this.textContent}`);
            });
        });

        // Share button functionality
        const shareButton = document.getElementById('shareButton');
        if (shareButton) {
            shareButton.addEventListener('click', function() {
                // Implementation would depend on your sharing options
                // For now, let's just simulate copying a link
                const currentUrl = window.location.href;
                navigator.clipboard.writeText(currentUrl).then(() => {
                    alert('Album link copied to clipboard!');
                });
            });
        }
    });
</script>
</body>
</html>
