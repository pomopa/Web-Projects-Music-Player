<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
<?= lang('App.sign_up') ?>
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
<?= lang('App.sign_up') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form action="<?= base_url(route_to('sign-up_submit')) ?>" method="post" accept-charset="utf-8" role="form" class="text-start" enctype="multipart/form-data">
    <!-- CSRF protection -->
    <?= csrf_field() ?>

    <div class="input-group input-group-outline my-3">
        <label for="username" class="form-label"><?= lang('App.username') ?></label>
        <input id="username" type="text" name="username" class="form-control"
            <?= request()->getPost('username') !== null ? 'value="' . esc(request()->getPost('username')) . '"' : '' ?>>
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('username'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('username') ?>
        </h6>
    <?php endif; ?>

    <div class="input-group input-group-outline my-3" id="fileInputGroup">
        <label id="fileNameLabel" class="form-label no-shadow-label" style="color: #737373;"><?= lang('App.profile_pic') ?></label>

        <!-- input ocult -->
        <input type="file" name="picture" id="picture" class="d-none" accept=".jpeg,.jpg,.png,.gif,image/jpeg,image/png,image/gif" onchange="handleImagePreview(this)">

        <!-- label clicable -->
        <label for="picture" class="form-control " style="cursor: pointer;">
            <span id="fileLabel">&nbsp;</span>
        </label>
    </div>

    <!-- Previsualització de la imatge seleccionada -->
    <div id="previewContainer" class="mb-3" style="display: none;">
        <img id="previewImage" src="#" alt="Preview" class="d-block mx-auto" style="max-width: 200px; border-radius: 8px;" />
    </div>

    <?php if (session()->getFlashdata('errorImage')): ?>
        <div class="missatgeError"><?= esc(session()->getFlashdata('errorImage')) ?></div>
    <?php endif ?>

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

    <div class="input-group input-group-outline my-3">
        <label for="repeat_password" class="form-label"><?= lang('App.repeat_password') ?></label>
        <input id="repeat_password" type="password" name="repeat_password" required class="form-control">
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('repeat_password'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('repeat_password') ?>
        </h6>
    <?php endif; ?>

    <div class="text-center">
        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2"><?= lang('App.sign_up') ?></button>
    </div>
    <p class="mt-4 text-sm text-center">
        <?= lang('App.already_account') ?>
        <a href="<?= base_url(route_to('sign-in_view')) ?>" class="text-primary text-gradient font-weight-bold"><?= lang('App.sign_in') ?></a>
    </p>
</form>

<script src="../../../assets/js/signup.js"></script>

<?= $this->endSection() ?>
