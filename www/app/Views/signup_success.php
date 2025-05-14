<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
Sign-up
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
Sign-up
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<br>
<label class="form-check-label mb-0 ms-3">Account created successfully! You will be redirected in any moment.</label>
<br>
<p class="mt-4 text-sm text-center">
    If you are not automatically redirected you can do it manually by clicking on the
    <a href="<?= base_url(route_to('landing_view')) ?>" class="text-primary text-gradient font-weight-bold">link</a>
</p>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    setTimeout(function() {
        window.location.href = "../..";
    }, 4000);
</script>
<?= $this->endSection() ?>