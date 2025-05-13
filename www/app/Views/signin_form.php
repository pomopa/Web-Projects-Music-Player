<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
Sign-in
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
Sign in
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $session = session();
    if ($session->getFlashdata('error_message')): ?>
    <h6 class="missatgeError">
        <?= $session->getFlashdata('error_message'); ?>
    </h6>
<?php endif; ?>

<form action="<?= base_url('sign-in') ?>" method="post" accept-charset="utf-8" role="form" class="text-start">
    <!-- CSRF protection -->
    <?= csrf_field() ?>

    <div class="input-group input-group-outline my-3">
        <label class="form-label">Email</label>
        <input type="text" name="email" required class="form-control"
            <?= request()->getPost('email') !== null ? 'value="' . esc(request()->getPost('email')) . '"' : '' ?>>
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('email'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('email') ?>
        </h6>
    <?php endif; ?>

    <div class="input-group input-group-outline my-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" required class="form-control">
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
        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign in</button>
    </div>
    <p class="mt-4 text-sm text-center">
        You still don't have an account?
        <a href="<?= base_url('sign-up') ?>" class="text-primary text-gradient font-weight-bold">Sign up</a>
    </p>
</form>
<?= $this->endSection() ?>
