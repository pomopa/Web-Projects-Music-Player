<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
Sign-up
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
Sign-up
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form action="<?= base_url('sign-up') ?>" method="post" accept-charset="utf-8" role="form" class="text-start">
    <!-- CSRF protection -->
    <?= csrf_field() ?>

    <div class="input-group input-group-outline my-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="<?= set_value('username') ?>">
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('username'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('username') ?>
        </h6>
    <?php endif; ?>

    <div class="input-group input-group-outline my-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" name="profilePicture" class="form-control" value="<?= set_value('profilePicture') ?>">
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('profilePicture'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('profilePicture') ?>
        </h6>
    <?php endif; ?>

    <div class="input-group input-group-outline my-3">
        <label class="form-label">Email</label>
        <input type="text" name="email" required class="form-control" value="<?= set_value('email') ?>">
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

    <div class="input-group input-group-outline my-3">
        <label class="form-label">Repeat password</label>
        <input type="password" name="repeat_password" required class="form-control">
    </div>

    <?php if (!empty(\Config\Services::validation()->showError('repeat_password'))): ?>
        <h6 class="missatgeError">
            <?= \Config\Services::validation()->showError('repeat_password') ?>
        </h6>
    <?php endif; ?>

    <div class="text-center">
        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
    </div>
    <p class="mt-4 text-sm text-center">
        Already have an account?
        <a href="<?= base_url('sign-in') ?>" class="text-primary text-gradient font-weight-bold">Sign in</a>
    </p>
</form>
<?= $this->endSection() ?>
