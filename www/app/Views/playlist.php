<?= $this->extend('default_logged_in') ?>



<?= $this->section('title') ?>
    <title>LSpoty - <?= esc($playlistName) ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/playlist.css') ?>">
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
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
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

<?= $this->endSection() ?>