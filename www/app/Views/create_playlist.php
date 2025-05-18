<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - <?= lang('App.create_playlist') ?></title>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= site_url('/assets/propis/styles.css') ?>">
<link id="pagestyle" href="<?= site_url('/assets/css/material-dashboard.css?v=3.1.0') ?>" rel="stylesheet" />
<!-- Custom CSS -->
<link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
<!-- Playlist Detail CSS -->
<link rel="stylesheet" href="<?= site_url('/assets/css/playlist-details.css') ?>">
<link rel="stylesheet" href="<?= site_url('/assets/css/playlist-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mt-4">
    <div class="col-12">
        <button class="back-button" onclick="history.back()">
            <i class="fa fa-arrow-left"></i> <?= lang('App.back') ?>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card bg-dark border border-success">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><?= lang('App.create_playlist') ?></h4>
            </div>
            <div class="card-body">
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

                <form action="<?= base_url(route_to('my-playlist_create')) ?>" method="post" enctype="multipart/form-data" class="row g-3">
                    <?= csrf_field() ?>

                    <!-- Cover Image -->
                    <div class="col-12 text-center mb-4">
                        <div class="mx-auto position-relative" style="width: 150px; height: 150px;">

                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-success" style="width: 150px; height: 150px;">
                                <i class="fa fa-music fa-4x text-light2"></i>
                            </div>


                            <label for="picture" class="position-absolute bottom-0 end-0 btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;">
                                <i class="fa fa-camera"></i>
                            </label>
                            <input type="file" id="picture" name="picture" class="d-none" accept=".jpeg,.jpg,.png,.gif,image/jpeg,image/png,image/gif">
                        </div>
                        <small class="text-light mt-2 d-block"><?= lang('App.click_camera_cover') ?></small>
                        <small class="text-light mt-2 d-block"><?= lang('App.allowed_types') ?></small>
                    </div>

                    <!-- Playlist Name -->
                    <div class="col-12">
                        <div class="input-group input-group-outline my-3 is-focused">
                            <label class="form-label text-white" for="name">Playlist Name</label>
                            <input type="text" name="name" id="name" class="form-control text-white" maxlength="255" required>
                        </div>
                        <?php if (!empty(\Config\Services::validation()->showError('name'))): ?>
                            <h6 class="error-message">
                                <?= \Config\Services::validation()->showError('name') ?>
                            </h6>
                        <?php endif; ?>
                    </div>

                    <!-- Submit -->
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus me-1"></i> <?= lang('App.create_playlist') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
    <script src="<?= site_url('/assets/js/profile.js') ?>"></script>
<?= $this->endSection() ?>
