<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
<?= lang('App.landing') ?>
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
<?= lang('App.landing') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="text-center p-4">
    <h1 class="fw-bold text-primary-emphasis"><?= lang('App.welcome') ?></h1>
    </br>
    <p class="text-muted">
        <?= lang('App.consider') ?><a href="<?= base_url(route_to('sign-in_view')) ?>" class="text-decoration-none text-primary fw-semibold"><?= lang('App.logging_in') ?></a>
        <?= lang('App.or') ?><a href="<?= base_url(route_to('sign-up_view')) ?>" class="text-decoration-none text-primary fw-semibold"><?= lang('App.registering') ?></a>
        <?= lang('App.to_access') ?>
    </p>
    <p>
        <?= lang('App.possibilities') ?>
    </p>
    <ul style="list-style: none">
        <li>🎶 <?= lang('App.feature1') ?> 🎶</li>
        <li>🎧 <?= lang('App.feature2') ?> 🎧</li>
        <li>⏯️ <?= lang('App.feature3') ?> ⏯️</li>
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
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='<?= base_url(route_to('sign-in_view')) ?>'"><?= lang('App.sign_in') ?></button>
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='<?= base_url(route_to('sign-up_view')) ?>'"><?= lang('App.sign_up') ?></button>
</div>

<?= $this->endSection() ?>
