<?= $this->extend('default') ?>

<?= $this->section('container') ?>
<div class="col-lg-5 col-md-8 col-12 mx-auto mt-5">
    <div class="card z-index-0 fadeIn3 fadeInBottom">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0"><?= $this->renderSection('sectionName') ?></h4>

            </div>
        </div>
        <div class="card-body">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>