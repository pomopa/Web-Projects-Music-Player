<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
Landing Page
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
Landing Page
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="text-center p-4">
    <h1 class="fw-bold text-primary-emphasis">Welcome to LSpoty!</h1>
    </br>
    <p class="text-muted">
        Please consider <a href="<?= base_url(route_to('sign-in_view')) ?>" class="text-decoration-none text-primary fw-semibold">logging in</a>
        or <a href="<?= base_url(route_to('sign-up_view')) ?>" class="text-decoration-none text-primary fw-semibold">registering</a>
        to access LSpoty and enjoy music at its finest!
    </p>
    <p>
        With LSpoty you will be able to:
    </p>
    <ul style="list-style: none">
        <li>🎶 Search for music from your favorite artists 🎶</li>
        <li>🎧 Discover what everyone is listening to 🎧</li>
        <li>⏯️ Create your own playlists to enjoy on the go. ⏯️</li>
    </ul>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-center gap-4 mt-4">
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='<?= base_url(route_to('sign-in_view')) ?>'">Sign in</button>
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='<?= base_url(route_to('sign-up_view')) ?>'">Sign up</button>
</div>

<?= $this->endSection() ?>
