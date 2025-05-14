<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - <?= esc($artist->name) ?></title>

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
    <!-- Artist Detail CSS -->
    <link rel="stylesheet" href="<?= site_url('/assets/css/artist-detail.css') ?>">
    <link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
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

    <!-- Artist Header Section -->
    <div class="row artist-header">
        <div class="col-md-3 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
            <img src="<?= esc($artist->image) ?>" alt="<?= esc($artist->image) ?>" class="artist-image">
        </div>
        <div class="col-md-9 col-sm-12 artist-details">
            <div class="d-flex align-items-center">
                <h1 class="text-white display-4 fw-bold mb-0"><?= esc($artist->name) ?></h1>
                <?php if (isset($artist->verified) && $artist->verified): ?>
                    <span class="verified-badge ms-2"><i class="fa fa-check-circle" title="Verified Artist"></i></span>
                <?php endif; ?>
            </div>

            <div class="artist-metadata mt-2">
                <span class="tag"><i class="fa fa-calendar me-1"></i> Joined <?= esc(date('F Y', strtotime($artist->joindate))) ?></span>
            </div>

            <div class="artist-stats mt-3">
                <div class="stat-item">
                    <span class="stat-value"><?= number_format($artist->fullcount) ?></span>
                    <span class="stat-label">Albums</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?= number_format(count($artist->tracks)) ?></span>
                    <span class="stat-label">Tracks</span>
                </div>
            </div>

            <div class="actions-container mt-4">
                <a href="<?= esc(($artist->website ?? '#')) ?>" target="_blank" class="action-btn">
                    <i class="fa fa-external-link-alt me-1"></i> Website
                </a>
                <button class="action-btn" id="shareButton" data-artist-id="<?= $artist->id ?>">
                    <i class="fa fa-share-alt me-1"></i> Share
                </button>
            </div>
        </div>
    </div>

    <!-- Albums Section -->
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold fs-4 text-white mb-3">Albums (<?= $artist->fullcount ?>)</h3>
            <?php if (isset($artist->albums) && count($artist->albums) > 0): ?>
                <div class="row g-3">
                    <?php foreach($artist->albums as $album): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card card-plain transition-transform hover-elevation h-100"
                                 onclick="window.location.href='/album/<?= $album->id ?>'"
                                 data-album-id="<?= $album->id ?>">
                                <div class="position-relative">
                                    <img src="<?= esc($album->image ?? '/api/placeholder/300/300') ?>" class="card-img-top" alt="<?= esc($album->name) ?>">
                                </div>
                                <div class="card-body p-3">
                                    <h5 class="card-title text-truncate mb-1"><?= esc($album->name) ?></h5>
                                    <p class="card-text mb-0 text-truncate"><?= date('Y', strtotime($album->releasedate)) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert bg-gray-800 text-white">
                    No albums available for this artist yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Audio Player (Hidden) -->
<audio id="audioPlayer" preload="metadata">
    <source src="" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

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
<!-- Artist Page JS -->
<script src="<?= site_url('/assets/js/artist-page.js') ?>"></script>

</body>
</html>