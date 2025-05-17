<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - <?= esc($playlist['name']) ?></title>
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
            <span><i class="fa fa-music"></i> <?= count($playlist['tracks']) ?> tracks</span>
            <span><i class="fa fa-clock"></i> <?= $formattedTotalDuration ?></span>
        </div>

        <div class="playlist-actions">
            <button class="action-btn primary" id="playPlaylistButton">
                <i class="fa fa-play me-1"></i> Play Playlist
            </button>
            <button class="action-btn" id="wantToUpdateButton">
                <i class="fa fa-pencil-alt me-1"></i> Edit Playlist
            </button>
            <button class="action-btn alert-danger" id="deleteButton" onclick="deletePlaylist(<?= $playlist['id']?>, '<?= esc(addslashes($playlist['name'])) ?>')">
                <i class="fa fa-trash me-1"></i> Delete Playlist
            </button>
        </div>
        <div id="wantToUpdate" class="hidden">
            <label for="update_name" class="form-label text-light">New Playlist Name (Leave blank to not modify)</label>
            <input type="text" id="update_name" class="form-control border border-light ps-2 mb-3" placeholder="New name...">

            <?php /*<div class="input-group input-group-outline my-3" id="fileInputGroup">
                <label for="update_name" class="form-label text-light">New Cover (Leave blank to not modify)</label>

                <!-- input ocult -->
                <input type="file" name="picture" id="picture" class="d-none" accept=".jpeg,.jpg,.png,.gif,image/jpeg,image/png,image/gif" onchange="handleImagePreview(this)">

                <!-- label clicable -->
                <label for="picture" class="form-control " style="cursor: pointer;">
                    <span id="fileLabel">&nbsp;</span>
                </label>
            </div>

            <!-- Previsualització de la imatge seleccionada -->
            <div id="previewContainer" class="mb-3" style="display: none;">
                <img id="previewImage" src="#" alt="Preview" class="d-block mx-auto" style="max-width: 200px; border-radius: 8px;" />
            </div> */?>

            <button class="action-btn primary" onclick="updatePlaylist(<?= $playlist['id']?>)">Update Playlist</button>

        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h2 class="fw-bold fs-4 text-white mb-3">Tracks</h2>
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
                                    <li><a class="dropdown-item" href="/track/<?= $trackId ?>"><i class="fa fa-info-circle me-2"></i>Track details</a></li>
                                    <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-plus me-2"></i>Add to playlist</a></li>
                                    <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-share-alt me-2"></i>Share track</a></li>
                                    <li><a class="dropdown-item" href="#" data-track-id="<?= $trackId ?>"><i class="fa fa-times me-2"></i>Remove from playlist</a></li>
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
                <div>
                    <h4 class="text-white mb-0 fs-5"><?= esc($playlistCreator) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="bg-gray-800 rounded p-4 mb-4">
            <h3 class="fw-bold fs-4 text-white mb-3">More Playlists by <?= esc($playlistCreator) ?></h3>
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
                        <p class="text-muted">No other playlists created by this user.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="../../../assets/js/signup.js"></script>
<script src="<?= site_url('/assets/js/playlist-player.js') ?>"></script>
<script>
    const deletePlaylistBaseURL = "<?= base_url(route_to('my-playlist_delete', 0)) ?>".replace('/0', '');
    const updatePlaylistBaseURL = "<?= base_url(route_to('my-playlist_put', 0)) ?>".replace('/0', '');


    function deletePlaylist(playListID, playListName) {
        if (!confirm('Are you sure you want to delete playlist ' + playListName + '?')) {
            return;
        }

        fetch(deletePlaylistBaseURL + '/' + playListID, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message ?? 'Unexpected response');
                if (data.status === 'success') {
                    window.location.href = "<?= base_url(route_to('my-playlist_view')) ?>";
                }
            })
            .catch(error => {
                console.error('Error deleting playlist:', error);
                alert('An error occurred while deleting the playlist.');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('wantToUpdateButton').addEventListener('click', function () {

            const el = document.getElementById('wantToUpdate');
            if (el) {
                el.classList.toggle('hidden');
            }
        });
    });

    function updatePlaylistName(playListID) {
        const nameInput = document.getElementById('update_name');
        const newName = nameInput.value.trim();

        if (!newName) {
            return;
        }

        fetch(updatePlaylistBaseURL + '/' + playListID, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ name: newName })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error(error);
                alert("Error al editar el nom.");
            });
    }

    /*
    function updatePlaylistPicture(playListId) {
        const pictureInput = document.getElementById('picture');
        if (!pictureInput.files.length) {
            return;
        }

        const formData = new FormData();
        formData.append('picture', pictureInput.files[0]);

        console.log(pictureInput.files[0]);

        fetch(updatePlaylistBaseURL + '/' + playListId, {
            method: 'PUT',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error updating image.");
            });
    }
    */

    function updatePlaylist(playListID) {
        updatePlaylistName(playListID);
        //updatePlaylistPicture(playListID);
    }

    </script>
<?= $this->endSection() ?>