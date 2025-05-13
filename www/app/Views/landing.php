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
        Please consider <a href="/sign-in" class="text-decoration-none text-primary fw-semibold">logging in</a>
        or <a href="/sign-up" class="text-decoration-none text-primary fw-semibold">registering</a>
        to access LSpoty and enjoy #TODO posar les features.
    </p>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-center gap-4 mt-4">
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='/sign-in'">Sign in</button>
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='/sign-up'">Sign up</button>
</div>

<?= $this->endSection() ?>
