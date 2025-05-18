<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - <?= esc($track->name) ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
<link rel="stylesheet" href="<?= site_url('/assets/css/track-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row mt-4">
        <div class="col-12">
            <button class="back-button" onclick="history.back()">
                <i class="fa fa-arrow-left"></i> <?= lang('App.back') ?>
            </button>
        </div>
    </div>

    <div class="row track-header">
        <div class="col-md-3 col-sm-12 d-flex justify-content-center justify-content-md-start mb-4 mb-md-0">
            <img src="<?= esc($track->cover) ?>" alt="Track cover" class="track-cover">
        </div>
        <div class="col-md-9 col-sm-12 track-details">
            <h1 class="text-white display-5 fw-bold mb-1"><?= esc($track->name) ?></h1>
            <a class="text-secondary fs-4 mb-3" href="/artist/<?=$track->artistId?>"><?= esc($track->artistName) ?></a>

            <div class="mb-3">
                <span class="tag"><i class="fa fa-calendar me-1"></i> <?= esc(date('Y-m-d', strtotime($track->releasedate))) ?></span>
            </div>

            <div class="actions-container">
                <button class="action-btn primary" id="playButton" data-track-url="<?= esc($track->playerUrl) ?>">
                    <i class="fa fa-play me-1"></i> <?= lang('App.play') ?>
                <div class="dropdown">
                    <button class="action-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-plus me-1"></i> <?= lang('App.add_to_playlist') ?>
                    </button>
                    <button class="action-btn" id="shareButton" data-track-id="<?=$track->id?>">
                        <i class="fa fa-share-alt me-1"></i> <?= lang('App.share') ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-track-id="<?=$track->id?>">
                        <?php if (!empty($track->playlists)): ?>
                            <?php foreach ($track->playlists as $playlist): ?>
                                <li>
                                    <a class="dropdown-item playlist-add" href="#" data-playlist-id="<?= esc($playlist['id']) ?>">
                                        <?= esc($playlist['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="dropdown-item text-muted">No playlists found</span></li>
                        <?php endif; ?>
                        <li><div class="dropdown-divider"></div></li>
                        <li><a class="dropdown-item" href="/create-playlist"><i class="fa fa-plus-circle me-2"></i><?= lang('App.create_new_playlist') ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="player-container">
            <div class="player-progress d-flex align-items-center">
                <span class="time-display current-time">0:00</span>
                <div class="progress-container">
                    <div class="progress-bar" style="width: 0%"></div>
                </div>
                <span class="time-display duration"><?= esc(gmdate("i:s", $track->duration)) ?></span>
            </div>
            <div class="player-controls">
                <button class="control-btn"><i class="fa fa-step-backward"></i></button>
                <button class="control-btn main" id="playPauseBtn"><i class="fa fa-play"></i></button>
                <button class="control-btn"><i class="fa fa-step-forward"></i></button>
            </div>
            <audio id="audioPlayer" preload="metadata">
                <source src="<?= esc($track->playerUrl) ?>" type="audio/mpeg">
                <?= lang('App.non_supporting_browser') ?>
            </audio>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="bg-gray-800 rounded p-4 mb-4">
                    <div class="">
                        <h4 class="fw-bold text-white mb-2"><?= lang('App.track_information') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td class="text-secondary"><?= lang('App.album') ?></td>
                                    <td class="text-white">
                                        <a href="/album/<?= esc($track->albumId) ?>" class="text-success text-decoration-none">
                                            <?= esc($track->albumName) ?>
                                        </a>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-secondary"><?= lang('App.duration') ?></td>
                                    <td class="text-white"><?= esc(gmdate("i:s", $track->duration)) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary"><?= lang('App.license') ?></td>
                                    <td class="text-white"><?= esc($track->license_ccurl) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-gray-800 rounded p-4 mb-4">
                    <h3 class="fw-bold fs-4 text-white mb-3"><?= lang('App.artist') ?></h3>
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= esc($track->artist_image) ?>"
                             alt="Artist image" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h4 class="text-white mb-0 fs-5"><?= esc($track->artistName) ?></h4>
                        </div>
                    </div>
                    <?php $artistUrl = site_url('/artist/' . $track->artistId); ?>
                    <button class="btn btn-outline-success rounded-pill w-100"
                            onclick="window.location.href='<?= $artistUrl ?>'">
                        <?= lang('App.visit_artist') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>


<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
    <script>
        const LANG = {
            error_loading: "<?= lang('App.error_loading_audio') ?>",
            link: "<?= lang('App.track_link') ?>",
            track_added: "<?= lang('App.track_added') ?>",
            failed_to_add_track: "<?= lang('App.failed_to_add_track') ?>"
        };
    </script>
    <script src="<?= site_url('/assets/js/track-player.js') ?>"></script>
<?= $this->endSection() ?>