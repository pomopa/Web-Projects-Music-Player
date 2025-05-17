<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
<?= lang('App.success') ?>
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
<?= lang('App.success') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<br>
<label class="form-check-label mb-0 ms-3"><?= lang('App.signup_success') ?></label>
<br>
<p class="mt-4 text-sm text-center">
    <?= lang('App.auto_redirect') ?>
    <a href="<?= base_url(route_to('landing_view')) ?>" class="text-primary text-gradient font-weight-bold"><?= lang('App.link') ?></a>
</p>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    setTimeout(function() {
        window.location.href = "../..";
    }, 4000);
</script>
<?= $this->endSection() ?>