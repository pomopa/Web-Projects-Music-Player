<?= $this->extend('default_logged_in') ?>

<?= $this->section('title') ?>
<title>LSpoty - Profile</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-dark border border-success">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Edit Profile</h4>
                    </div>
                    <div class="card-body">
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

                        <?php if(session()->getFlashdata('errorImage')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('errorImage') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('/profile') ?>" method="POST" enctype="multipart/form-data" class="row g-3">
                            <?= csrf_field() ?>

                            <!-- Profile Picture -->
                            <div class="col-12 text-center mb-4">
                                <div class="mx-auto position-relative" style="width: 150px; height: 150px;">
                                    <?php if(!empty($user->profile_pic) && file_exists(WRITEPATH . 'uploads/' . $user->profile_pic)): ?>
                                        <img src="<?= base_url(route_to('profile_picture')) ?>" class="rounded-circle img-fluid border border-success" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-success" style="width: 150px; height: 150px;">
                                            <i class="fa fa-user fa-4x text-light"></i>
                                        </div>
                                    <?php endif; ?>

                                    <label for="profilePicture" class="position-absolute bottom-0 end-0 btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;">
                                        <i class="fa fa-camera"></i>
                                    </label>
                                    <input type="file" id="profilePicture" name="profilePicture" class="d-none" accept=".jpeg,.jpg,.png,.gif,image/jpeg,image/png,image/gif">
                                </div>
                                <small class="text-light mt-2 d-block">Click the camera icon to change your profile picture</small>
                                <small class="text-light mt-2 d-block">The allowed image types are jpg, jpeg, png or gif</small>
                            </div>

                            <!-- Username -->
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-focused">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control text-white" value="<?= $user->username ?>" required>
                                </div>

                                <?php if (!empty(\Config\Services::validation()->showError('username'))): ?>
                                    <h6 class="error-message">
                                        <?= \Config\Services::validation()->showError('username') ?>
                                    </h6>
                                <?php endif; ?>
                            </div>

                            <!-- Age -->
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-focused">
                                    <label class="form-label" for="age">Age</label>
                                    <input type="number" id="age" name="age" class="form-control text-white" value="<?= $user->age ?? '' ?>" min="1" max="120">
                                </div>

                                <?php if (!empty(\Config\Services::validation()->showError('age'))): ?>
                                    <h6 class="error-message">
                                        <?= \Config\Services::validation()->showError('age') ?>
                                    </h6>
                                <?php endif; ?>
                            </div>

                            <!-- Email (Read-only) -->
                            <div class="col-12">
                                <div class="input-group input-group-outline my-3 is-focused">
                                    <label class="form-label" for="email">Email (Cannot be changed)</label>
                                    <input type="email" id="email" class="form-control text-white" value="<?= $user->email ?>" readonly >
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-focused">
                                    <label class="form-label" for="password">New Password (leave blank to keep current)</label>
                                    <input type="password" id="password" name="password" class="form-control text-white">
                                </div>

                                <?php if (!empty(\Config\Services::validation()->showError('password'))): ?>
                                    <h6 class="error-message">
                                        <?= \Config\Services::validation()->showError('password') ?>
                                    </h6>
                                <?php endif; ?>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-focused">
                                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control text-white">
                                </div>

                                <?php if (!empty(\Config\Services::validation()->showError('confirm_password'))): ?>
                                    <h6 class="error-message">
                                        <?= \Config\Services::validation()->showError('confirm_password') ?>
                                    </h6>
                                <?php endif; ?>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" name="action" value="save" class="btn btn-success">Save Changes</button>
                                <button type="submit" name="action" value="delete" class="btn btn-danger">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?= $this->endSection() ?>


<?= $this->section('javascript') ?>
    <script src="<?= site_url('/assets/js/profile.js') ?>"></script>
    <script src="<?= site_url('/assets/js/commons.js') ?>"></script>
<?= $this->endSection() ?>