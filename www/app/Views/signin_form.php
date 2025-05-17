<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
<?= lang('App.sign_in') ?>
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
<?= lang('App.sign_in') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $session = session();
    if ($session->getFlashdata('error_message')): ?>
    <h6 class="missatgeError">
        <?= $session->getFlashdata('error_message'); ?>
    </h6>
<?php endif; ?>

<form action="<?= base_url(route_to('sign-in_logic')) ?>" method="post" accept-charset="utf-8" role="form" class="text-start">
    <!-- CSRF protection -->
    <?= csrf_field() ?>

    <div class="input-group input-group-outline my-3">
        <label for="email" class="form-label"><?= lang('App.email') ?></label>
        <input id="email" type="text" name="email" required class="form-control"
            <?= request()->getPost('email') !== null ? 'value="' . esc(request()->getPost('email')) . '"' : '' ?>>
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('email'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('email') ?>
        </h6>
    <?php endif; ?>

    <div class="input-group input-group-outline my-3">
        <label for="password" class="form-label"><?= lang('App.password') ?></label>
        <input id="password" type="password" name="password" required class="form-control">
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('password'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('password') ?>
        </h6>
    <?php endif; ?>

    <?php if (session('errors.password')): ?>
        <h6 class="missatgeError">
            <?= session('errors.password') ?>
        </h6>
    <?php endif; ?>


    <div class="text-center">
        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2"><?= lang('App.sign_in') ?></button>
    </div>
    <p class="mt-4 text-sm text-center">
        <?= lang('App.still_not_account') ?>
        <a href="<?= base_url(route_to('sign-up_view')) ?>" class="text-primary text-gradient font-weight-bold"><?= lang('App.sign_up') ?></a>
    </p>
</form>
<?= $this->endSection() ?>
