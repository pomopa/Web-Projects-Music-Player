<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - <?= esc($artist->name) ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= site_url('/assets/css/artist-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row mt-4">
        <div class="col-12">
            <button class="back-button" onclick="history.back()">
                <i class="fa fa-arrow-left"></i> <?= lang('App.back') ?>
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
                <span class="tag"><i class="fa fa-calendar me-1"></i> <?= lang('App.joined') ?> <?= esc(date('F Y', strtotime($artist->joindate))) ?></span>
            </div>

            <div class="artist-stats mt-3">
                <div class="stat-item">
                    <span class="stat-value"><?= number_format($artist->fullcount) ?></span>
                    <span class="stat-label"><?= lang('App.albums') ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?= number_format(count($artist->tracks)) ?></span>
                    <span class="stat-label"><?= lang('App.tracks') ?></span>
                </div>
            </div>

            <div class="actions-container mt-4">
                <a href="<?= esc(($artist->website ?? '#')) ?>" target="_blank" class="action-btn">
                    <i class="fa fa-external-link-alt me-1"></i> <?= lang('App.website') ?>
                </a>
                <button class="action-btn" id="shareButton" data-artist-id="<?= $artist->id ?>">
                    <i class="fa fa-share-alt me-1"></i> <?= lang('App.share') ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Albums Section -->
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold fs-4 text-white mb-3"><?= lang('App.albums') ?> (<?= $artist->fullcount ?>)</h3>
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
                    <?= lang('App.no_albums_yet') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Audio Player (Hidden) -->
    <audio id="audioPlayer" preload="metadata">
        <source src="" type="audio/mpeg">
        <?= lang('App.non_supporting_browser') ?>
    </audio>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
    <script src="<?= site_url('/assets/js/artist-page.js') ?>"></script>
<?= $this->endSection() ?>
