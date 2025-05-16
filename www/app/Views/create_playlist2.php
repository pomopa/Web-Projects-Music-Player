<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Playlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function deletePlaylist() {
            if (!confirm('Are you sure you want to delete playlist ID 5?')) {
                return;
            }

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
                    if (data.status === 'success') {
                        location.reload();
                    }
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
                .then(data => {
                    alert(data.message ?? 'Unexpected response');
                })
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
                .then(data => {
                    alert(data.message ?? 'Resposta inesperada');
                })
                .catch(error => {
                    console.error('Error deleting track:', error);
                    alert('S\'ha produït un error esborrant la pista.');
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
                body: JSON.stringify({
                    name: name
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message ?? 'Resposta inesperada');
                })
                .catch(error => {
                    console.error('Error updating playlist:', error);
                    alert('S\'ha produït un error actualitzant la playlist.');
                });
        }
    </script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Create a New Playlist</h2>

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
            <label for="name" class="form-label">Playlist Name</label>
            <input type="text" name="name" id="name" class="form-control" maxlength="255" required>
        </div>

        <div class="mb-3">
            <label for="picture" class="form-label">Cover Image (optional)</label>
            <input type="file" name="picture" id="picture" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Create Playlist</button>
    </form>

    <hr class="my-5">

    <div class="container mt-5">
        <h2 class="mb-4">Delete Playlist ID 5</h2>
        <button class="btn btn-danger" onclick="deletePlaylist()">Delete Playlist</button>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Add Track 114069 to Playlist ID 5</h2>
        <button class="btn btn-success" onclick="putTrackToPlaylist()">Add Track</button>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Delete Track 114069 from Playlist ID 5</h2>
        <button class="btn btn-warning" onclick="deleteTrackFromPlaylist()">Remove Track</button>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Update Playlist ID 5</h2>
        <div class="mb-3">
            <label for="update_name" class="form-label">New Playlist Name</label>
            <input type="text" id="update_name" class="form-control" placeholder="New name...">
        </div>
        <button class="btn btn-info" onclick="updatePlaylist()">Update Playlist</button>
    </div>
    <br><br>
</div>

</body>
</html>