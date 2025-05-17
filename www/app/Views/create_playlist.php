<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - Create Playlist</title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link id="pagestyle" href="<?= site_url('/assets/css/material-dashboard.css?v=3.1.0') ?>" rel="stylesheet" />
<link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
<link rel="stylesheet" href="<?= site_url('/assets/css/playlist-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row mt-4">
    <div class="col-12">
        <button class="back-button" onclick="history.back()">
            <i class="fa fa-arrow-left"></i> Back
        </button>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card bg-dark text-white p-4 shadow">
            <h2 class="mb-4 fw-bold text-white">Create a New Playlist</h2>

            <form action="<?= base_url(route_to('my-playlist_create')) ?>" method="post" enctype="multipart/form-data">
                <?php if (session()->getFlashdata('errorImage')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('errorImage') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <p><?= esc($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="name" class="form-label text-light">Playlist Name</label>
                    <input type="text" name="name" id="name" class="form-control bg-secondary text-white border-0" maxlength="255" required>
                </div>

                <div class="mb-3">
                    <label for="picture" class="form-label text-light">Cover Image (optional)</label>
                    <input type="file" name="picture" id="picture" class="form-control bg-secondary text-white border-0">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Create Playlist
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-8 offset-md-2">
        <div class="card bg-dark text-white p-4 shadow">
            <h2 class="mb-4 fw-bold text-white">Manage Playlist ID 5</h2>

            <div class="d-grid gap-2 mb-3">
                <button class="btn btn-danger" onclick="deletePlaylist()">
                    <i class="fa fa-trash me-1"></i> Delete Playlist
                </button>
                <button class="btn btn-success" onclick="putTrackToPlaylist()">
                    <i class="fa fa-plus me-1"></i> Add Track 114069
                </button>
                <button class="btn btn-warning text-dark" onclick="deleteTrackFromPlaylist()">
                    <i class="fa fa-minus me-1"></i> Remove Track 114069
                </button>
            </div>

            <div class="mb-3">
                <label for="update_name" class="form-label text-light">New Playlist Name</label>
                <input type="text" id="update_name" class="form-control bg-secondary text-white border-0" placeholder="Enter new name...">
            </div>
            <button class="btn btn-info text-white" onclick="updatePlaylist()">
                <i class="fa fa-pencil-alt me-1"></i> Update Playlist
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function deletePlaylist() {
        if (!confirm('Are you sure you want to delete playlist ID 5?')) return;

        fetch("<?= base_url(route_to('my-playlist_delete', 4)) ?>", {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message ?? 'Unexpected response');
                if (data.status === 'success') location.reload();
            })
            .catch(error => {
                console.error('Error deleting playlist:', error);
                alert('An error occurred while deleting the playlist.');
            });
    }

    function putTrackToPlaylist() {
        fetch("<?= base_url(route_to('my-playlist_put_song', 4, "114069")) ?>", {
            method: 'PUT',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
            .then(response => response.json())
            .then(data => alert(data.message ?? 'Unexpected response'))
            .catch(error => {
                console.error('Error adding track:', error);
                alert('An error occurred while adding the track.');
            });
    }

    function deleteTrackFromPlaylist() {
        fetch("<?= base_url(route_to('my-playlist_delete_song', 4, '114069')) ?>", {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
        })
            .then(response => response.json())
            .then(data => alert(data.message ?? 'Unexpected response'))
            .catch(error => {
                console.error('Error deleting track:', error);
                alert('An error occurred while deleting the track.');
            });
    }

    function updatePlaylist() {
        const name = document.getElementById('update_name').value;

        fetch("<?= base_url(route_to('my-playlist_put', 4)) ?>", {
            method: 'PUT',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
            .then(response => response.json())
            .then(data => alert(data.message ?? 'Unexpected response'))
            .catch(error => {
                console.error('Error updating playlist:', error);
                alert('An error occurred while updating the playlist.');
            });
    }
</script>
<?= $this->endSection() ?>
    